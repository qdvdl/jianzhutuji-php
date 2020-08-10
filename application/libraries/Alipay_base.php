<?php
	/*
	 *支付宝支付类
	 * */
	class Alipay_base{
		protected $CI;
		public $config=[];
		public function __construct()
	    {
	        // Assign the CodeIgniter super-object
	        $this->CI =& get_instance();
            $this->CI->load->helper(array('url','file','common'));
            $this->config=$this->CI->config->item('alipay_config');
        }
        //获取配置信息是否正确
        public function alipayConfig(){
            var_dump($this->config);
          
        }
        //发送所需要的参数
        public function payParasm($resParams){
             //公共参数
            $params = [
                'app_id'    => $resParams['appid'],
                'method'    =>  'alipay.trade.wap.pay', //接口名称 应填写固定值alipay.trade.page.pay
                'format'    =>  'JSON', //目前仅支持JSON
                'return_url'    => $resParams["return_url"], //同步返回地址
                'charset'    =>  'UTF-8',
                'sign_type'    =>  'RSA2',//签名方式
                'sign'    =>  '', //签名
                'timestamp'    => date('Y-m-d H:i:s'), //发送时间 格式0000-00-00 00:00:00
                'version'    =>  '1.0', //固定为1.0
                'notify_url'    => $resParams["notify_url"], //异步通知地址
                'biz_content'    =>  '', //业务请求参数的集合
            ];
            //业务参数
            $api_params =[
                'out_trade_no'  => $resParams["out_trade_no"],//商户订单号
                'product_code'  => 'FAST_INSTANT_TRADE_PAY', //销售产品码 固定值
                'total_amount'  => $resParams["total_amount"], //总价 单位为元
                'subject'  => $resParams["subject"], //订单标题
            ];
            $params['biz_content'] = json_encode($api_params,JSON_UNESCAPED_UNICODE); //JSON_UNESCAPED_UNICODE:防止中文编码

            $params =  $this->setRsa2Sign($params);//获取签名
           // print_r($params);
            //发送支付
            $url = $this->config['payurl'].'?'. $this->getUrl($params);
            header("location:" . $url);
           
        }
        //获取请求参数获取参数
        public function getStr($arr,$type="RSA"){
            //筛选  
            if(isset($arr['sign'])){
                unset($arr['sign']);
            }
            if(isset($arr['sign_type']) && $type == 'RSA2'){
                unset($arr['sign_type']);
            }
            //排序  
            ksort($arr);
            //拼接
            return  $this->getUrl($arr,false);
        }
        //将数组转换为url格式的字符串
        public function getUrl($arr,$encode = true){
            if($encode){
                return http_build_query($arr);
            }else{
                return urldecode(http_build_query($arr));
            }
        }
         //获取含有签名的数组RSA
        public function setRsa2Sign($arr){
            $arr['sign'] = $this->getRsa2Sign($arr);
            return $arr;
        }
        //获取签名RSA2
        public function getRsa2Sign($arr){
            return $this->rsaSign($this->getStr($arr),$this->config['appPrivate']) ;
        }
        //支付验证
        public function notify($postData,$logPath){
            $state=true;
            
            //验证是否来自支付宝的请求
            if(!$this->isAlipay($postData)){
                $this->logs($logPath, '不是来之支付宝的通知!');
                return false;
            }else{
                $this->logs($logPath, '是来之支付宝的通知验证通过!');
            }
            // 4.验证交易状态
            if(!$this->checkOrderStatus($postData)){
                $this->logs($logPath, '交易未完成!');
                return false;
            }else{
                $this->logs($logPath, '交易成功!=>订单号:'.$postData['out_trade_no'].'=>订单金额:'.$postData['total_amount']);
            }
            return $state;
            //5. 验证订单号和金额
            //获取支付发送过来的订单号  在商户订单表中查询对应的金额 然后和支付宝发送过来的做对比
           // $this->logs('log.txt', '订单号:' . $postData['out_trade_no'] . '订单金额:' . $postData['total_amount']);
            //更改订单状态
           // echo 'success';
        }
        //验证是否来之支付宝的通知
        public function isAlipay($arr){
            $checkUrl = 'https://mapi.alipay.com/gateway.do?service=notify_verify&partner=' . $this->config['pid'] . '&notify_id=';
            $str = file_get_contents($checkUrl .$arr['notify_id']);
            if($str == 'true'){
                return true;
            }else{
                return false;
            }
        }
        // 4.验证交易状态
        public function checkOrderStatus($arr){
            if($arr['trade_status'] == 'TRADE_SUCCESS' || $arr['trade_status'] == 'TRADE_FINISHED'){
                return true;
            } else {
                return false;
            }
        }
        /**
         * RSA签名
         * @param $data 待签名数据
         * @param $private_key 私钥字符串
         * return 签名结果
         */
        function rsaSign($data, $private_key) {
            $search = [
                    "-----BEGIN RSA PRIVATE KEY-----",
                    "-----END RSA PRIVATE KEY-----",
                    "\n",
                    "\r",
                    "\r\n"
            ];
            $private_key=str_replace($search,"",$private_key);
            $private_key=$search[0] . PHP_EOL . wordwrap($private_key, 64, "\n", true) . PHP_EOL . $search[1];
            $res=openssl_get_privatekey($private_key);
            if($res)
            {
                openssl_sign($data, $sign,$res,OPENSSL_ALGO_SHA256);
                openssl_free_key($res);
            }else {
                exit("私钥格式有误");
            }
            $sign = base64_encode($sign);
            return $sign;
        }
         /**
         * RSA验签
         * @param $data 待签名数据
         * @param $public_key 公钥字符串
         * @param $sign 要校对的的签名结果
         * return 验证结果
         */
        function rsaCheck($data, $public_key, $sign)  {
            $search = [
                    "-----BEGIN PUBLIC KEY-----",
                    "-----END PUBLIC KEY-----",
                    "\n",
                    "\r",
                    "\r\n"
            ];
            $public_key=str_replace($search,"",$public_key);
            $public_key=$search[0] . PHP_EOL . wordwrap($public_key, 64, "\n", true) . PHP_EOL . $search[1];
            $res=openssl_get_publickey($public_key);
            if($res)
            {
                $result = (bool)openssl_verify($data, base64_decode($sign), $res,OPENSSL_ALGO_SHA256);
                openssl_free_key($res);
            }else{
                    exit("公钥格式有误!");
            }
            return $result;
        }
        //写入日志记录$logsFile：文件名称，$string:内容
        public function logs($logsFile,$string){
            //写入到文件
            if(!write_file($logsFile, $string))
            {
                $state=false;
            }else{
                $state=true;
            }
        }
      
       	//退款处理
        public function aliRefund($params){
            //公共参数 固定值
            $pub_params = [
               'app_id'    => $params["appid"],
               'method'    =>  'alipay.trade.refund', //接口名称 应填写固定值alipay.trade.refund
               'format'    =>  'JSON', //目前仅支持JSON
               'charset'    =>  'UTF-8',
               'sign_type'    =>  'RSA2',//签名方式
               'timestamp'    => date('Y-m-d H:i:s'), //发送时间 格式0000-00-00 00:00:00
               'version'    =>  '1.0', //固定为1.0
               'biz_content'    =>  '', //业务请求参数的集合
            ];
    		
            //业务参数
            $api_params = [
               'trade_no'  => $params['trade_no'],//商户订单号 和支付宝交易号trade_no 二选一
               'refund_amount'  => $params['refund_amount'], //退款金额
               'out_request_no'  => $params['out_request_no'], //退款唯一标识  部分退款时必传
            ];
            
            //公共参数中加入业务参数
           	$pub_params['biz_content'] = json_encode($api_params,JSON_UNESCAPED_UNICODE);
         	
           	$pub_data = $this->setRsa2Sign($pub_params);
         
            $json_data = $this->curlRequest($this->config['payurl'],$pub_data);
         	
            return json_decode($json_data,true);
        }
      	//通过curl发送
        public function curlRequest($url,$data = ''){
          $ch = curl_init();
          $params[CURLOPT_URL] = $url;    //请求url地址
          $params[CURLOPT_HEADER] = false; //是否返回响应头信息
          $params[CURLOPT_RETURNTRANSFER] = true; //是否将结果返回
          $params[CURLOPT_FOLLOWLOCATION] = true; //是否重定向
          $params[CURLOPT_TIMEOUT] = 30; //超时时间
          if(!empty($data)){
              $params[CURLOPT_POST] = true;
              $params[CURLOPT_POSTFIELDS] = $data;
          }
          $params[CURLOPT_SSL_VERIFYPEER] = false;//请求https时设置,还有其他解决方案
          $params[CURLOPT_SSL_VERIFYHOST] = false;//请求https时,其他方案查看其他博文
          curl_setopt_array($ch, $params); //传入curl参数
          $content = curl_exec($ch); //执行
          curl_close($ch); //关闭连接
          return $content;
      	}
      
	}
?>