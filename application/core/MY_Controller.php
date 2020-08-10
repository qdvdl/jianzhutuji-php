<?php
	class MY_Controller extends CI_Controller
	{
	   public function __construct(){
			parent::__construct();
			$this->load->database();
			$this->load->library(['encryption','sms','wechat_pay_base']);
			$this->load->helper(['url','date','file','common']);
		}
		//统一支付（支付服务费接口）
		public function wechatPayServer($order){
			//获取统一下单id
			$jsapiParams=[];
			$order['notify_url']="https://jianzhu.sctshd.com/NotifyPay/servepay/";
			$order['trade_type']="JSAPI";
			$pay=$this->wechat_pay_base->getPrepayId($order);
			//print_r($pay);
			
			if($pay['return_code']=='SUCCESS'&&$pay['result_code']==='SUCCESS'){
				$jsapiParams=$this->wechat_pay_base->getJsParams($pay['prepay_id']);
			}
		
			return $jsapiParams;
		}
		//记录支付后的数据（调试）
		public function logs_text($text){
			$this->wechat_pay_base->logs('./log/payloge.txt',$text);
		}
		//退款操作
		public function refundPrice($transaction_id,$total_fee,$refund_fee){
			//构建退款参数
			$sslcert_path="./cert/apiclient_cert.pem";
			$sslkeytype_path="./cert/apiclient_key.pem";
			$refundPrams=$this->wechat_pay_base->refund_params($transaction_id,order_number(),$total_fee,$refund_fee);
			$refund_data=$this->wechat_pay_base->order_refund($refundPrams,$sslcert_path,$sslkeytype_path);
			return $refund_data;
		}
		
		//短信通知参数配置
		public function smsNotify($tel,$smsCode,$param=[]){
			//短信配置参数
			$accessKeyId=$this->config->item('accessKeyId');
			$accessKeySecret=$this->config->item('accessKeySecret');
			$sign=$this->config->item('sign');
			//$response=$this->sms->sendSms($accessKeyId,$accessKeySecret,$sign,$tel,$smsCode,$param);	
			$response=$this->sms->sendSms($accessKeyId,$accessKeySecret,$sign,$tel,'SMS_76400145',['code'=>'1213']);	
			return $response;
		}
		//发送短信规定模版发送短信
		//$type=1(工人订单匹配成功)，2(包工头订单接走)，3订单被工人取消，4工人认证审核通过，5工人认证审核拒绝，6包工头认证审核通过，7包工头认证审核拒绝，
		public function toSmsNotify($tel,$type){
			if($type=='1'){
				//尊敬的用户，我们已为您匹配到用工订单，请进入小程序及时接单~
				$response=$this->smsNotify($tel,"SMS_192571413",[]);
			}else if($type=='2'){
				//尊敬的用户，您的用工订单已被工人接单，请保持手机畅通，稍后工人会和您联系！
				$response=$this->smsNotify($tel,"SMS_192576502",[]);
			}else if($type=='3'){
				//尊敬的用户，您的用工订单已被工人取消，系统将会重新给您匹配工人！
				$response=$this->smsNotify($tel,"SMS_192576503",[]);
			}else if($type=='4'){
				//尊敬的用户，您的工人认证已经审核通过，可以开启您的接单之旅了！
				$response=$this->smsNotify($tel,"SMS_192576506",[]);
			}else if($type=='5'){
				//尊敬的用户，您的工人认证审核被拒绝，请检查您的认证资料重新提交认证，有疑问请联系平台！
				$response=$this->smsNotify($tel,"SMS_192576508",[]);
			}else if($type=='6'){
				//尊敬的用户，您的包工头认证已经审核通过，可以开始发单线上招工了！
				$response=$this->smsNotify($tel,"SMS_192576510",[]);
			}else if($type=='7'){
				//尊敬的用户，您的包工头认证审核被拒绝，请检查您的认证资料重新提交认证，有疑问请联系平台！
				$response=$this->smsNotify($tel,"SMS_192541431",[]);
			}
		}
	
		//接口去权限验证
		public function apiVerification(){
			
			if($_SERVER['REQUEST_METHOD']==='OPTIONS'){
				$json_string = json_encode(["code"=>200,"msg"=>"请求成功！"],true);
				echo $json_string;
				exit;
			}
	
			//获取hider信息
			$arr=$this->getBasicAuthorized();
			if(!empty($arr)&&!empty($arr['username'])&&!empty($arr['userpass'])){
				$arr['token']=$this->token(); //A81A29049FC905AB81F9B12848E9C60A
				if($this->chekSign($arr)){
					$cName=$this->uri->segment(2);
					if(!empty($cName)){
						$this->authVerification($cName);
					}else{
						$json_string = json_encode(["code"=>40004,"msg"=>"接口验证权限验证失败！"],true);
						echo $json_string;
						exit;
					}
				}else{
					$json_string = json_encode(["code"=>40004,"msg"=>"接口验证权限验证失败不能访问！"],true);
					echo $json_string;
					exit;
				}
			}else{
				if(!empty($_POST['iseditimg'])&&$_POST['iseditimg']!='text'){
					$json_string = json_encode(["code"=>40004,"msg"=>"暂无调用接口权限！"],true);
					echo $json_string;
					exit;
				}
			}
			
		}
		//获取管理权限是否开启
		public function authVerification($cName){
			$nocName=['login','setIinfo','upload_file','authAdd']; //不需要开启验证的配置
			
			if(!in_array($cName,$nocName)&&$_SERVER['REQUEST_METHOD']==='POST'){
				if(!empty($_POST['userSID'])){
					$auth_id=$this->db->select('a.user_name,r.auth_id')
					->from("admin as a")
					->join('role r',"r.id=a.role_id",'left')
					->where(["a.id"=>$_POST['userSID']])->get()->row_array();
					$admin_auth=[];
					if(!empty($auth_id['auth_id'])){
						$admin_auth=explode(",",$auth_id['auth_id']);
					}
					$auth=$this->db->where_in('id', $admin_auth)->order_by('order', 'ASC')->get("auth")->result_array();
					$state=false;
					foreach($auth as $key=>$item){
						if($cName===$item['name']){
							$state=true;
						}
					}
					if(!$state){
						$json_string = json_encode(["code"=>40001,"msg"=>"当前权限不足，请联系管理员！"],true);
						echo $json_string;
						exit;
					}
				}else{
					$json_string = json_encode(["code"=>40004,"msg"=>"登录失败，请重新登录！"],true);
					echo $json_string;
					exit;
				}
			}
		}
		
		//生成服务器端token
		public function token(){
			$username=$this->config->item('username');
			$userpass=$this->config->item('userpass');
			$serverurl=$this->config->item('serverurl');
			$arr=[
				'username'=>$username,
				'userpass'=>$userpass,
				'serverurl'=>$serverurl
			];
			return $this->getSign($arr);
		}
		
		/*
		 *验证签名
		 * */
		public function chekSign($arr){
			//得到我们生成好的签名
			$sign=$this->getSign($arr);
			//检验数组中的签名是否正确
			if($sign==$arr['token']){
				return true;
			}else{
				return false;
			}
		}
		/*
		 *生成签名:
		 * */
		public function getSign($arr){
			//去除数组的空值(微信：如果参数的值为空不参与签名)
			$arr=array_filter($arr);
			//防止数组中有带参数的签名字段导致签名错误，必须去除（微信规则）
			if(isset($arr['token'])){
				//去除数组中的sign
				unset($arr['token']);
			}
			//字典排序(微信：参数名ASCII码从小到大排序)
			ksort($arr);
			//格式化url字符串并且连接商户key
			//注意：http_build_query()
			$str=$this->arrUrlDecode($arr);
			//生成sign并转成大写
			$sign=strtoupper(md5($str));
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
		//获取验证参数		
		public function getBasicAuthorized(){
			$UserName = '';
			$PassWord = '';
			$HttpReferer='';
			
			//Apache服务器
			if (isset($_SERVER['PHP_AUTH_USER'])) {
				//print_r($_SERVER);
				$UserName = $_SERVER['PHP_AUTH_USER'];
				$PassWord = $_SERVER['PHP_AUTH_PW'];
				$HttpReferer=$_SERVER['HTTP_HOST'];
				
			}elseif(isset($_SERVER['HTTP_AUTHORIZATION'])){
			//其他服务器如 Nginx  Authorization
				if (strpos(strtolower($_SERVER['HTTP_AUTHORIZATION']), 'basic') === 0) {
					$Authorization = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
					$UserName = isset($Authorization[0])?$Authorization[0]:'';
					$PassWord = isset($Authorization[1])?$Authorization[1]:0;
				}
			}
			return ['username'=>$UserName,'userpass'=>$PassWord,'serverurl'=>$HttpReferer];
		}
		//记录操作日志
		public function addLog($type="",$state=""){
			$admin=$this->db->get_where('admin',['id'=>$_POST['userSID']])->row_array();
			$data=[
				"name"=>$admin['name'],
				"type"=>$type,
				"time"=>unix_to_human(time(), TRUE, 'eu'),
				"details"=>"管理员【".$admin['name']."】操作".$type.",".$state
			];
			$state=$this->db->insert("log",$data);
			return $state;
		}
		//获取管理员信息
		public function getAdmin(){
			$admin=[];
			if($_SERVER['REQUEST_METHOD']==='POST'){
				$admin=$this->db->get_where('admin',['id'=>$_POST['userSID']])->row_array();
			}
			if($_SERVER['REQUEST_METHOD']==='GET'){
				$admin=$this->db->get_where('admin',['id'=>$_GET['userSID']])->row_array();
			}
			return $admin;
		}
	}
	
?>