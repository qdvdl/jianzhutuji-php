<?php
	/*
	 *微信核心类-重写
	 * */
	class WeChat_bases{
        protected $CI;
		public $config=[];
		public function __construct()
	    {
	        $this->CI =& get_instance();
            $this->CI->load->helper(array('url','file','common'));
            $this->config=$this->CI->config->item('wechat_config');
        }
		//获取配置信息是否正确
        public function weChatConfig(){
            var_dump($this->config);
         // echo $this->config['appid'];
          //echo $this->config['appSecret'];
		}
     	//获取token=>7200秒
		public function getToken(){
			$appid=$this->config['appid'];//公众号appid
			$appSecret=$this->config['appSecret'];//公众号秘钥	
			$token_url='https://api.weixin.qq.com/cgi-bin/token?';//url需要获取token 
			$token_url=$token_url.'grant_type=client_credential&appid='.$appid.'&secret='.$appSecret;
			//获取文件内容
			$file_path='./log/wechat_token.txt';	
			$token=read_file($file_path); 	
			if(!empty($token)){
				$arr_token=json_decode($token,true);
				if($arr_token['expires_in']<time()){
					//重新获取
					$token=file_get_contents($token_url);
					$token=$this->expires_in($file_path,$token);
				}else{
					$token=$arr_token;
				}	
			}else{
				$token=file_get_contents($token_url);
				$token=$this->expires_in($file_path,$token);
			}
            return $token;
		}
		//获取ticket
		public function getTicket(){
			//wechat_ticket.txt
			$access_token=$this->getToken();
			$access_token=$access_token['access_token'];
			$ticket_url='https://api.weixin.qq.com/cgi-bin/ticket/getticket?';
			$ticket_url=$ticket_url.'access_token='.$access_token.'&type=jsapi';
			//获取文件内容
			$file_path='./log/wechat_ticket.txt';	
			$ticket=read_file($file_path);
			if(!empty($ticket)){
				$arr_ticket=json_decode($ticket,true);
				if($arr_ticket['expires_in']<time()){
					//重新获取
					$ticket=file_get_contents($ticket_url);
					$ticket=$this->expires_in($file_path,$ticket);
				}else{
					$ticket=$arr_ticket;
				}	
			}else{
				$ticket=file_get_contents($ticket_url);
				$ticket=$this->expires_in($file_path,$ticket);
			}
            return $ticket;
		}
		//设置获取时间
		public function expires_in($file_path,$json){
			$arr=json_decode($json,true);
			if(!empty($arr['expires_in'])){
				$arr['expires_in']=time()+7000;
				$this->logs($file_path,json_encode($arr));
			}
			return $arr;
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
		/*
		 *http_build_query()会将中文进行编码,导致签名和微信不统一
		 *处理函数：arrUrlDecode();解码url呈现中文格式url
		 * */
		public function arrUrlDecode($arr){
			//返回解码后的
			return urldecode(http_build_query($arr)); 			
		}
		//通用jsapi config
		public function get_config(){
			$ticket=$this->getTicket();
			$uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			$params=array(
				'timestamp'=>time(),					//时间戳
				'noncestr'=>md5(time()),				//随机字符串
				'jsapi_ticket'=>$ticket['ticket'],
				'url'=>$uri
			);
			$params['signature']=$this->getJsSign($params);
			$params['appid']=$this->config['appid'];
			return $params;
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
	}
	
?>