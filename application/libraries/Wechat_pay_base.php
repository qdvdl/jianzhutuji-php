<?php
	/*
	 *微信支付类
	 * */
	class Wechat_pay_base{
		protected $CI;
		public $config=[];
		public function __construct()
	    {
	        // Assign the CodeIgniter super-object
	        $this->CI =& get_instance();
            $this->CI->load->helper(array('file','common'));
            $this->config=$this->CI->config->item('wechat_config');
        }
        //获取配置信息是否正确
        public function weChatConfig(){
            var_dump($this->config);
		}

        //生成支付签名
        public function getSign($arr){
            //去除空值
            $arr = array_filter($arr);
            if(isset($arr['sign'])){
                unset($arr['sign']);
            }
            //按照键名字典排序
            ksort($arr);
            //生成url格式的字符串
            $str = $this->arrUrlDecode($arr) . '&key=' .$this->config['key'];
            return strtoupper(md5($str));
        }
        /**获取带签名的数组**/
        public function setSign($arr){
            $arr['sign']=$this->getSign($arr);
            return $arr;
        }
        /*
        *http_build_query()会将中文进行编码,导致签名和微信不统一
        *处理函数：arrUrlDecode();解码url呈现中文格式url
        * */
        public function arrUrlDecode($arr){
            //返回解码后的
            return urldecode(http_build_query($arr)); 			
        }
        //验证签名
        public function chekSign($arr){
            $sign = $this->getSign($arr);
            if($sign == $arr['sign']){
                return true;
            }else{
                return false;
            }
        }
        //调用统一下单api
        public function unifiedOrder($params){
            $uri='https://api.mch.weixin.qq.com/pay/unifiedorder';
            /**
             * 2.加入签名
             * 3.将数据转换为XML
             * 4.发送XML格式的数据到接口地址
             */
            $params = $this->setSign($params); //加入签名
            $xmldata =ArrToXml($params);
            $this->logs('./log/paylogs.txt', $xmldata); //记录支付前数据
            $resdata = $this->postXml($uri, $xmldata);
            $arr=XmlToArr($resdata);
            return $arr;
        }
        //获取prepayid
        public function getPrepayId($info){
            //1.构建原始数据
			$ip=GetIP();
			if($info['total_fee']<=0){
				$info['total_fee']=1;	
			}
			// $info=[
			// 	"openid"=>"",
			// 	"body"=>"",
			// 	"out_trade_no"=>"",
			// 	"total_fee"=>"",
			// 	"notify_url"=>"",
			// 	"trade_type"=>"JSAPI",
			// 	"attach"=>"",
			//	"order_id"=>""
			// ];
			
            $params = [
                'appid'=> $this->config['appid'],  //小程序id||或者是公众号id
                'mch_id'=> $this->config['mch_id'], //商户号
                'nonce_str'=>md5(time()),           //随机字符串
                'body'=>$info['body'],              //支付内容
                'out_trade_no'=>$info['order_id'],  //支付订单id
                'total_fee'=>$info['total_fee'],    //支付金额
                'spbill_create_ip'=>$ip,			 //当前支付ip
                'notify_url'=>$info['notify_url'],  //回调地址
                'trade_type'=>$info['trade_type'], //支付方式MWEB，JSAPI
                'product_id'=>$info['order_id'],
				'attach'=>$info['attach']
            ];
			
            if($info['trade_type']==="JSAPI"){
                $params['openid'] =$info['openid'];
            }
            //获取统一下单prepay_id
            $arr=$this->unifiedOrder($params);
            return  $arr;
        }
		
       //调用调用查询订单接口
        public function get_Order($order_id){
            $url= 'https://api.mch.weixin.qq.com/pay/orderquery';
            //构建数据
            $params = [
                'appid'=> $this->config['appid'],
                'mch_id'=> $this->config['mch_id'],
                'out_trade_no' => $order_id,
                'nonce_str'=>md5(time()),
                'sign_type' => 'MD5'
            ];
            $params = $this->setSign($params); 
            $xmldata = ArrToXml($params);
            $resdata = $this->postXml($url, $xmldata);
            $arr =XmlToArr($resdata);
            return $arr;
        }
        //通过JSAPI，获取支付数据，$prepay_id：统一下单app_id
		public function getJsParams($prepay_id){
			$params=array(
				'appId'=>$this->config['appid'],		//公众号Id
				'timeStamp'=>'"'.time().'"',			//时间戳(字符串形式，ios兼容问题)
				'nonceStr'=>md5(time()),				//随机字符串
				'package'=>'prepay_id='.$prepay_id,		//prepay_id=于支付id
				'signType'=>'MD5'						//签名方式
			//	'paySign'=>''							//签名
			);
			$params['paySign']=$this->getSign($params);
			return json_encode($params);
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
      	//获取post过来的数据
        public function getPost(){
            return file_get_contents('php://input');
        }
		//发送xml数据
		public function postXml($url,$postfields){
			$ch = curl_init();
			$params[CURLOPT_URL] = $url;    //请求url地址
			$params[CURLOPT_HEADER] = false; //是否返回响应头信息
			$params[CURLOPT_RETURNTRANSFER] = true; //是否将结果返回
			$params[CURLOPT_FOLLOWLOCATION] = true; //是否重定向
			$params[CURLOPT_POST] = true;
			$params[CURLOPT_POSTFIELDS] = $postfields;
			$params[CURLOPT_SSL_VERIFYPEER] = false;
			$params[CURLOPT_SSL_VERIFYHOST] = false;
			curl_setopt_array($ch, $params); //传入curl参数
			$content = curl_exec($ch); //执行
			curl_close($ch); //关闭连接
			return $content;
		}
		//携带证书提交
		public function postData($url,$postfields,$sslcert_path,$sslkeytype_path){
			$ch = curl_init();
			$params[CURLOPT_URL] = $url;    //请求url地址
			$params[CURLOPT_HEADER] = false; //是否返回响应头信息
			$params[CURLOPT_RETURNTRANSFER] = true; //是否将结果返回
			$params[CURLOPT_FOLLOWLOCATION] = true; //是否重定向
			$params[CURLOPT_POST] = true;
			$params[CURLOPT_POSTFIELDS] = $postfields;
			$params[CURLOPT_SSL_VERIFYPEER] = false;
			$params[CURLOPT_SSL_VERIFYHOST] = false;
			//以下是证书相关代码
			$params[CURLOPT_SSLCERTTYPE] = 'PEM';
			$params[CURLOPT_SSLCERT] = $sslcert_path;//证书路径
			$params[CURLOPT_SSLKEYTYPE] = 'PEM';
			$params[CURLOPT_SSLKEY] = $sslkeytype_path;//证书路径
		
			curl_setopt_array($ch, $params); //传入curl参数
			$content = curl_exec($ch); //执行

			curl_close($ch); //关闭连接
			return $content;
		}
		//退款操作
		//1.需要的数据参数 $transaction_id：微信统一单号，$out_refund_no：退单号唯一编号，操作随机生成即可，$total_fee：支付时订单金额，$refund_fee：退款金额
		public function refund_params($transaction_id,$out_refund_no,$total_fee,$refund_fee){
			$params = [
		        'appid'   =>  $this->config['appid'], //APPID||小程序id
		        'mch_id'  =>  $this->config['mch_id'], //商户号
		        'nonce_str'=> md5(time()), //随机串
		        'sign'  => 'md5',          //签名
		        'transaction_id'=> $transaction_id,//微信支付订单号 与商户订单号二选一
		        //'out_trade_no'=> '', //商户订单号 和微信支付订单号二选一
		        'out_refund_no' => $out_refund_no,	//退单号，唯一标识就行
		        'total_fee'     => $total_fee,    //订单金额
		        'refund_fee'    => $refund_fee    //退款金额
		    ];
			return $params;
		}
		
		//发送退款请求
		//$params：生成的退款参数，$sslcert_path：证书连接，$sslkeytype_path：证书地址
		//退款操作
		public function order_refund($params,$sslcert_path,$sslkeytype_path){
		    //生成签名
		    $signParams = $this->setSign($params);
		
		    //将数据转换为xml
		    $xmlData =ArrToXml($signParams);
		    //发送请求
		    $refund_url='https://api.mch.weixin.qq.com/secapi/pay/refund';
			
		    $refund_data=$this->postData($refund_url,$xmlData,$sslcert_path,$sslkeytype_path);
			
			$refund_data=XmlToArr($refund_data);
		 
		    return  $refund_data;
		}
		
		
	}
	
?>