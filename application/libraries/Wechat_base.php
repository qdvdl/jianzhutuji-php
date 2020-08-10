<?php
	/*
	 *微信核心类
	 * */
	class WeChat_base{
		protected $CI;
		public $config=[];
		public function __construct()
	    {
	        // Assign the CodeIgniter super-object
	        $this->CI =& get_instance();
	        $this->CI->load->helper(array('url','file','common'));
			$this->CI->load->library('session');
			$xml='./application/admin/config/web_config.xml';
			//读取xml内容
			$string =read_file($xml);
			$this->config=XmlToArr($string);

	    }
		public function weChatConfig(){
			
			print_r($this->config);
		}
		public function wechat_redb(){
			
			$parem=[
				"nonce_str"=>md5(time()),//随机字符串
			//	"sign"=>'',              //签名
				"mch_billno"=>time(),		//商户订单号
				"mch_id"=>$this->config['number'],			//商户号
				"wxappid"=>$this->config['appid'],			//公众号APPid
				"send_name"=>'天舜网络',       //红包发送者名称
				"re_openid"=>'',       //用户openid
				"total_amount"=>100,    //付款金额单位分
				"total_num"=>1,       //发放红包人数
				"wishing"=>'红包祝福语',         //红包祝福语
				"client_ip"=>'101.201.232.76',	   //当前ip
				"act_name"=>'开发测试',        //活动名称
				'remark'=>'测试成功'           //描述备注
			];
			return $parem;
		}
		/*
		 *生成签名:支付签名
		 * */
		public function getSign($arr){
			$KEY=$this->config['key'];
			//去除数组的空值(微信：如果参数的值为空不参与签名)
			$arr=array_filter($arr);
			//防止数组中有带参数的签名字段导致签名错误，必须去除（微信规则）
			if(isset($arr['sign'])){
				//去除数组中的sign
				unset($arr['sign']);
			}
			//字典排序(微信：参数名ASCII码从小到大排序)
			ksort($arr);
			//格式化url字符串并且连接商户key
			//注意：http_build_query()
			$str=$this->arrUrlDecode($arr)."&key=".$KEY;
			//生成sign并转成大写
			$sign=strtoupper(MD5($str));
			//返回签名
			return $sign;	
		}
		//获取带签名的数组
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
		/*
		 *验证签名
		 * */
		public function chekSign($arr){
			//得到我们生成好的签名
			$sign=$this->getSign($arr);
			//检验数组中的签名是否正确
			if($sign==$arr['sign']){
				return true;
			}else{
				return false;
			}
		}
		/*
		 *获取用户的openid
		 *用户授权页面
		 * */
		public function getOpenId(){
			$get_code_url='https://open.weixin.qq.com/connect/oauth2/authorize?';//获取code公共链接
			$get_token_url='https://api.weixin.qq.com/sns/oauth2/access_token?';//获取token公共链接
			$appid=$this->config['appid'];//公众号appid
			$appSecret=$this->config['appSecret'];//公众号秘钥
			//$redirect_uri=site_url('/main/WeChat');//返回回调页面
         	$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			//判断是否已经获取到sebase_urlssion
			if(isset($_SESSION['user_openid'])){
				//有session返回出去openid
				return $_SESSION['user_openid'];
			}else{
				//没有我们需要获取openid,获取用户信息需要先获取code
				//判断用户访问微信链接是否带有code
				if(!isset($_GET['code'])){
					//获取code
					$url_code=$get_code_url.'appid='.$appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
					//跳转到微信
					redirect($url_code);
				}else{
					//返回code,使用code
					$url_token=$get_token_url.'appid='.$appid.'&secret='.$appSecret.'&code='.$_GET['code'].'&grant_type=authorization_code';
					//file_get_contents执行请求获取token信息
					$data=file_get_contents($url_token);
					//json数据转换为数组格式
					$token_arr=json_decode($data,true);
					//$_SESSION['user_openid']=$token_arr['openid'];
					//设置openid存入session
					$this->CI->session->set_userdata('user_openid',$token_arr['openid']);
					//echo $token_arr['openid'];
					return $token_arr['openid'];
				}
			}
		}
      	//获取微信用户信息
		public function wechat_user_info($openid){
			$access_token=$this->get_access_token();
			$user_info_url='https://api.weixin.qq.com/cgi-bin/user/info?';
			$user_info_url=$user_info_url.'access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
			$user_info_data=file_get_contents($user_info_url);
			//json数据转换为数组格式
			$user_info_data=json_decode($user_info_data,true);
			return $user_info_data;
		}
		//获取ticket
		public function get_ticket(){
			$access_token=$this->get_access_token();
			$ticket_url='https://api.weixin.qq.com/cgi-bin/ticket/getticket?';
			$ticket_url=$ticket_url.'access_token='.$access_token.'&type=jsapi';
			//企业号接口
       		$ticket=file_get_contents($ticket_url);
            return $ticket;
		}
      	//获取token
     	 public function getToken(){
			$appid=$this->config['appid'];//公众号appid
			$appSecret=$this->config['appSecret'];//公众号秘钥	
			$token_url='https://api.weixin.qq.com/cgi-bin/token?';
			$token_url=$token_url.'grant_type=client_credential&appid='.$appid.'&secret='.$appSecret;
			//企业号接口
//			$token_url='https://qyapi.weixin.qq.com/cgi-bin/gettoken?';//企业
//			$token_url=$token_url.'corpid='.$appid.'&corpsecret='.$appSecret;
       		$token=file_get_contents($token_url);
            return $token;
		}
		//access_token写入缓存文件
		public function get_access_token(){
			$file_path='./access_token.txt';	
			$string=read_file($file_path);
			$data_access=json_decode($string,true);
			$access_token="";
			if($data_access['access_token']){
				if($data_access['expires_in']<time()){
					$data_access=$this->getToken();
					$data_access=json_decode($data_access,true);
					if($data_access['access_token']){
						$data_access['expires_in']=time()+7000;
						$this->logs($file_path,json_encode($data_access));
						$access_token=$data_access['access_token'];
					}
				}
			}else{
				$data_access=$this->getToken();
				$data_access=json_decode($data_access,true);
				if($data_access['access_token']){
					$data_access['expires_in']=time()+7000;
					$this->logs($file_path,json_encode($data_access));
					$access_token=$data_access['access_token'];
				}
			}
            //json数据转换为数组格式
           	return $access_token;
		}
		//获取jsapi_ticket写入缓存文件
		public function get_jsapi_ticket(){
			$file_path='./jsapi_ticket.txt';	
			$data_ticket=read_file($file_path);
			if($data_ticket){
				$data_ticket=json_decode($data_ticket,true);
				if($data_ticket['expires_in']<time()){
					$data_ticket=$this->get_ticket();
					$data_ticket=json_decode($data_ticket,true);
					$data_ticket['expires_in']=time()+7000;
					$this->logs($file_path,json_encode($data_ticket));
				}
			}else{
				//如果没有读到文件内容处理
				$data_ticket=$this->get_ticket();
				$data_ticket=json_decode($data_ticket,true);
				$data_ticket['expires_in']=time()+7000;
				$this->logs($file_path,json_encode($data_ticket));
			}
            //json数据转换为数组格式
           	return $data_ticket['ticket'];
		}
		
		//调用统一下单api
		public function unifiedOrder($order_number,$order_id,$money,$body,$notify_url,$attach){
			/*
			 * 1.构建原始数据
			 * 2.加入签名
			 * 3.将数据转换为xml格式
			 * 4.发送xml格式的数据到接口地址
			 * */
			 //必须数据
			$params=array(
			 	'appid'=>$this->config['appid'],//公众号id
			 	'mch_id'=>$this->config['number'],//商户号
			 	'nonce_str'=>md5(time()),//随机字符串
			 	'body'=>$body,//商品描述
			 	'out_trade_no'=>$order_number,//内部订单号
			 	'total_fee'=>$money,//总价
			 	'spbill_create_ip'=>$_SERVER['REMOTE_ADDR'],//客户端IP
			 	'notify_url'=>$notify_url,//支付通知回调地址
			 	'trade_type'=>'JSAPI',//支付类型
             	'attach'=>$attach,
			 	'product_id'=>$order_id,//产品ID/订单号
			 	'openid'=>$this->getOpenId()
			);
			$unUrl='https://api.mch.weixin.qq.com/pay/unifiedorder';
			//加入签名
			//echo "<pre>";
			$params=$this->setSign($params);
			$xmldata=ArrToXml($params);
			$logs='./logs.txt';
			$this->logs($logs,$xmldata);
			$resdata=$this->postXml($unUrl,$xmldata);
			$arr=XmlToArr($resdata);
			return $arr;
		}
		//获取prepayid
		public function getPrepayId($order_number,$order_id,$money,$body,$notify_url,$attach){
			$arr=$this->unifiedOrder($order_number,$order_id,$money,$body,$notify_url,$attach);
			return $arr['prepay_id'];
         //return 1;
		}
		//获取微信所需要的数据
		public function getJsParams($prepay_id){
			$params=array(
				'appId'=>$this->config['appid'],		//公众号Id
				'timeStamp'=>'"'.time().'"',					//时间戳
				'nonceStr'=>md5(time()),				//随机字符串
				'package'=>'prepay_id='.$prepay_id,		//prepay_id=于支付id
				'signType'=>'MD5'						//签名方式
			//	'paySign'=>''							//签名
			);
			$params['paySign']=$this->getSign($params);
			return json_encode($params);
		}
		//jsapi签名
		public function getJsSign($arr){
			//去除数组的空值(微信：如果参数的值为空不参与签名)
			$arr=array_filter($arr);
			//防止数组中有带参数的签名字段导致签名错误，必须去除（微信规则）
			if(isset($arr['sign'])){
				//去除数组中的sign
				unset($arr['sign']);
			}
			//字典排序(微信：参数名ASCII码从小到大排序)
			ksort($arr);
			//格式化url字符串并且连接商户key
			//注意：http_build_query()
			$str=$this->arrUrlDecode($arr);
			//生成sign并转成大写
			$sign=sha1($str);
			//返回签名
			return $sign;	
		}
		
		//通用jsapi config
		public function get_config(){
			$uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			$params=array(
				'timestamp'=>time(),					//时间戳
				'noncestr'=>md5(time()),				//随机字符串
				'jsapi_ticket'=>$this->get_jsapi_ticket(),
				'url'=>$uri
			);
			$params['signature']=$this->getJsSign($params);
			$params['appId']=$this->config['appid'];
			return $params;
		}
		
		//将xml写入到日志纪录文件
		public function logs($logsFile,$string){
			//写入到文件
			if(!write_file($logsFile, $string))
			{
			   $state=false;
			}else{
			   $state=true;
			}
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
		//退款处理
		//1.需要的数据参数 $transaction_id：微信统一单号，$out_refund_no：退单号唯一编号，操作随机生成即可，$total_fee：支付时订单金额，$refund_fee：退款金额
		public function refund_params($transaction_id,$out_refund_no,$total_fee,$refund_fee){
			$params = [
	            'appid'   =>  $this->config['appid'], //APPID
	            'mch_id'  =>  $this->config['number'], //商户号
	            'nonce_str'=> md5(time()), //随机串
	            'sign'  => 'md5',          //签名方式
	            'transaction_id'=> $transaction_id,//微信支付订单号 与商户订单号二选一
	            //'out_trade_no'=> '', //商户订单号 和微信支付订单号二选一
	            'out_refund_no' => $out_refund_no,	//退单号，唯一标识就行
	            'total_fee'     => $total_fee,    //订单金额
	            'refund_fee'    => $refund_fee    //退款金额
	        ];
			return $params;
		}
		//发送退款请求
		//$params：生成的退款参数
		
		//https://api.mch.weixin.qq.com/pay/orderquery
		
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
		//不通过微信关注获取用户信息
		public function get_user(){
			$get_code_url='https://open.weixin.qq.com/connect/oauth2/authorize?';//获取code公共链接
			$get_token_url='https://api.weixin.qq.com/sns/oauth2/access_token?';//获取token公共链接
         	 $get_user="https://api.weixin.qq.com/sns/userinfo?access_token=";
			$appid=$this->config['appid'];//公众号appid
			$appSecret=$this->config['appSecret'];//公众号秘钥
			//$redirect_uri=site_url('/main/WeChat');//返回回调页面
         	$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			//没有我们需要获取openid,获取用户信息需要先获取code
			if(!isset($_GET['code'])){
				//获取code
				$url_code=$get_code_url.'appid='.$appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
				//跳转到微信
				redirect($url_code);
			}else{
				//返回code,使用code
				$url_token=$get_token_url.'appid='.$appid.'&secret='.$appSecret.'&code='.$_GET['code'].'&grant_type=authorization_code';
				//file_get_contents执行请求获取token信息
				$data=file_get_contents($url_token);
				//json数据转换为数组格式
				$token_arr=json_decode($data,true);
				$get_user_url=$get_user.$token_arr['access_token']."&openid=".$token_arr['openid']."&lang=zh_CN";
				$wc_user=file_get_contents($get_user_url);
				$user_info=json_decode($wc_user,true);
				return $user_info;
			}
		}
		//发送模版消息
		//生成模版数据=》$openid:用户微信openid，$template_id:模版id,$url:跳转到那个连接,$data：自定义参数
		//$data=['first'=>['value'=>'帮扶申请审核处理通知！",'color'=>'#173177'],"keyword1"=>['value'=>"xxx","remark"=>['value'=>"xxx"]]];
		public function wx_temp($openid,$template_id,$url,$data){
			//生成模版数据
			$msgDatas=array(
	          'touser'=>$openid,  
	          'template_id'=>$template_id,  
	          'topcolor'=>"#FF0000",
	          'url'=>$url,
	          'data'=>$data
			); 
			
//			'data'=>array(  
//	              'first'=>array('value'=>"帮扶申请审核处理通知！",'color'=>'#173177'),  
//	              'keyword1'=>array('value'=>$u["name"]),
//				  'keyword2'=>array('value'=>$title),
//				  'keyword3'=>array('value'=>$dataTime),
//	              'remark'=>array('value'=>$remarks) 
//	          )  
			//发送消息
			$res_url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->get_access_token();
			$res=$this->postXml($res_url,urldecode(json_encode($msgDatas)));
			return json_decode($res,true);
		}
	}
	
?>