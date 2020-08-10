<?php
	class WxApiAll extends MY_Controller{
      
      	// private $appid="wx1e50f10351052738";
       //  private $secret="a09ace5592caaf9b18626a296ede6d92";
		
		public function __construct(){
			parent::__construct();
			//$this->load->model("operation_model");
			$this->load->helper(['url',"common"]);
		    $this->load->library(["session"]);
		}
	
		//首页数据
		public function getHome(){
			$transaction_id="4200000536202006086167208806";
			$refund=$this->refundPrice($transaction_id,0.11*100,0.11*100);
			print_r($refund);
			if($refund['return_code']=='SUCCESS'&&$refund['result_code']=='SUCCESS'){
			 echo "退款成功";
			}
		}
		//用户认证
		public function UserAuth(){
			$user=$this->userVerification();
			
				if($_POST["cwstate"]=='1'){
					if(birthday($_POST['birthday'])<18||birthday($_POST['birthday'])>60){
						respond([],'工人年龄不可低于18或者高于60岁',40001);
					}
				}
				
				$users=$this->db->get_where('users',['cardid'=>$_POST["cardid"],'id !='=>$user['id']])->row_array();
				if(!empty($users)){
					respond([],'身份证号已经使用过！',40001);
				}
				
				if($user["cwstate"]=='0'){
					//print_r($user);
					$data=[
						"cwstate"=>$_POST["cwstate"],
						"name"=>$_POST["name"],
						"cardid"=>$_POST["cardid"],
						"birthday"=>$_POST["birthday"],
						"card_img_z"=>$_POST["card_img_z"],
						"card_img_f"=>$_POST["card_img_f"],
						"province"=>$_POST["province"],
						"city"=>$_POST["city"],
						"audit"=>"3",
						"revocation"=>"2",
						"area"=>$_POST["area"],
					];
					
					
					
					// 工人
					if($_POST["cwstate"]=='1'&&!empty($_POST["type_p"])){
						$data['type_p']=$_POST["type_p"];
					}
					if($_POST["cwstate"]=='1'&&!empty($_POST["type_s"])){
						$data['type_s']=$_POST["type_s"];
					}
					
					if(!empty($_POST["uid"])){
						//认证后绑定用户关系
						$this->binding($user['id'],$_POST["uid"]);
						$uid_user=$this->db->get_where('users',['id'=>$_POST["uid"]])->row_array();
						//$data['sharenum']=$user['sharenum']+1;
					}
					$state=$this->db->where('id',$user['id'])->update('users',$data);
					if($state){
						$state=$this->db->where('id',$uid_user['id'])->update('users',["sharenum"=>$uid_user['sharenum']+1]);
					}
					respond($state,"提交成功，等待后台审核！",200);
				}else{
					respond([],"您已经认证了其他身份！",40001);
				}
				
			
		}
		//绑定用户关系
		public function binding($user,$uid){
			if(!empty($_POST['uid'])){
			  //查询我是否已经是别人的下线
			  
			  $userchild=$this->db->get_where('userchild',['user_id'=>$uid,'child_user_id'=>$user])->row_array();
			  if(empty($userchild)){
				  $childdata=[
					  'user_id'=>$uid,
					  'child_user_id'=>$user,
					  'jointime'=>unix_to_human(time(), TRUE, 'eu')
				  ];
				 $this->db->insert('userchild',$childdata);	 
			  }
			}
		}
		//获取工种
		public function getTypeWork(){
			$user=$this->userVerification();
			$typeWork=$this->db->select('id,name,parent_id')->order_by('sort DESC')->get_where('type_work')->result_array();
			$typeWorks=get_attr($typeWork,0);
			$arr=[];
			foreach($typeWorks as $key=>$item){
				if(!empty($item['children'])){
					array_push($arr,$item);
				}
			}
			$success=[
				'user'=>$user,
				'typeWork'=>$arr
			];
			respond($success,'加载成功！',200);
		}
		//发布订单
		public function releaseOrder(){
			
			//201 该用户不是包工头
			//202 包工头身份正在审核中
			//203 包工头身份审核未通过
			//204 帐号被冻结
			
			$user=$this->userVerification();
			if($user['cwstate']==='2'){
				if($user['audit']==='3'){
					respond([],'包工头身份正在审核中！',202);	
				}else if($user['audit']==='2'){
					respond([],'包工头身份审核未通过！',203);	
				}else{
					//冻结
					if($user['state']=='1'){
						//发布正常
						$siteinfo=$this->getSiteInfo();
						$free='3';//发布价格优惠计算方式1免费，2，半价，3正常发布
						//是否开启分享发布
						if($siteinfo['mCOff']=='1'){
							//开启
							//查询是否发布过免费订单
							$order_pay=$this->db->get_where('order_pay',['user_id'=>$user['id'],'free_state'=>'1'])->result_array();
							//如果分享次数大于
							$order_pay_mf=count($order_pay);//已经免费发布过的订单数
							$mCfirstCS=$siteinfo['mCfirstCS'];//前多少次免费值
							$sharenum=$user['sharenum']; //总分享次数
							// 免费下单计算
							$cz=$mCfirstCS-$order_pay_mf;	
							$freenum=$sharenum-$order_pay_mf;
								
							if($cz>0){
								if($cz<=$freenum){
									$free='1';
								}else{
									if($freenum>0){
										$free='1';
									}
								}
							}
							//判断是半价
							if($free=='3'){
								if($user['sharenum']>=$siteinfo['mCfirstCSmin']&&$user['sharenum']<=$siteinfo['mCfirstCSmax']){
									$free='2';
								}
							}
						}
						$this->saveOrder($free,$_POST,$siteinfo['servicePrice'],$user['id']);
						//正常发布
						//print_r($siteinfo);
					}else{
						respond([],'帐号被冻结！',204);	
					}
				}
			}else{
				//是否已经	
				respond([],'该用户不是包工头！',201);	
			}
		} 
		//发布提交数据
		public function saveOrder($free,$datapost,$servicePrice,$user_id){
			//$free=发布价格优惠计算方式1免费，2，半价，3正常发布
			$data=[
				"order_number"=>order_number(),
				"name"=>$datapost['name'],
				"type_p"=>$datapost['type_p'],
				"type_s"=>$datapost['type_s'],
				"num"=>$datapost['num'],
				"workhours"=>$datapost['workhours'],
				"province"=>$datapost['province'],
				"city"=>$datapost['city'],
				"area"=>$datapost['area'],
				"address"=>$datapost['address'],
				"des"=>$datapost['des'],
				"lat"=>$datapost['lat'],
				"lng"=>$datapost['lng'],
				"free_state"=>"2",
				"price"=>"",
				"user_id"=>$user_id,
				"pay_state"=>"",
				"price_c"=>"",
				"unit"=>"",
				"datetime_fb"=>unix_to_human(time(), TRUE, 'eu')
			];
			//编辑地区价格筛选地区价格
			$price_arr=$this->province_city_price($data);
			$price=$price_arr['price'];
			$hours=$price_arr['hours'];
			
			//echo $hours;
			
			if(!empty($price)&&$price!='0.00'){
				$data['price_c']=$price;
				if(!empty($hours)){
					$data['unit']='/'.$hours.'小时';
				}
				
				$numprice=$price*$datapost['num'];//根据数量计算价格
				$orderServerPrice =$numprice*($servicePrice/100);//计算服务费用价格,需要支付的费用       
				//echo $orderServerPrice;需要支付的
				if($free==='1'){
					//免费发布	
					$data['free_state']="1";
					$data['price']="0.00";
					$data['pay_state']="3";
				}
				if($free==='2'){
					//半价	
					$data['price']=$orderServerPrice/2;
					$data['pay_state']="3";
				}
				if($free==='3'){
					//全价格	
					$data['price']=$orderServerPrice;
					$data['pay_state']="3";
				}
				//插入
				// print_r($data);
				// exit;
				$state=$this->db->insert("order_pay",$data);
				$id=$this->db->insert_id();
				respond(['id'=>$id],'订单发布成功！',200);	
			}else{
				respond([],'当前区域未设置价格，不能发布订单！',40001);	
			}
		}
		//获取订单数据
		public function getOrderInfo(){
			$user=$this->userVerification();
			$order_pay=$this->db->select('o.*,tw.name as type_p_text,tws.name as type_s_text')
			->from("order_pay as o")
			->join('type_work tw',"tw.id=o.type_p",'left') //工种
			->join('type_work tws',"tws.id=o.type_s",'left') //工种
			 ->where(['o.id'=>$_GET['id']])->get()->row_array();  
			//$order_pay=$this->db->get_where('order_pay',['id'=>$_GET['id']])->row_array();
			respond($order_pay,'获取成功！',200);
		}
		//确认订单操作
		public function okorder(){
			$user=$this->userVerification();
			$order_pay=$this->db->select('o.*,tw.name as type_p_text,tws.name as type_s_text,u.openid')
			->from("order_pay as o")
			->join('users as u','u.id=o.user_id','left')
			->join('type_work tw',"tw.id=o.type_p",'left') //工种
			->join('type_work tws',"tws.id=o.type_s",'left') //工种
			 ->where(['o.id'=>$_POST['id']])->get()->row_array();  
			
			if($order_pay['free_state']==='1'){
				//免费订单，直接通过
				$this->copyorder($order_pay);
				//匹配工人，发送短信
				$this->messageWork($$order_pay);
			}else{
				if($order_pay['pay_state']!=='3'){
					//前端发起支付回调
					$this->copyorder($order_pay);
					//匹配工人，发送短信
					$this->messageWork($order_pay);
				}else{
					$order=[
						"openid"=>$order_pay['openid'],
						"body"=>"建筑突击小程序支付服务费用！",
						"order_id"=>$order_pay['order_number'],
						"total_fee"=>$order_pay['price']*100,
						"attach"=>$order_pay['id']
					];
					$jspai=$this->wechatPayServer($order);
					if($jspai){
						respond(['jspai'=>$jspai],'支付支付费参数！',20001);
					}else{
						respond([],'支付参数错误！',40001);
					}
					
				}
			}
		}
		//订单发布成功匹配接单工人短信提醒|
		public function messageWork($order){
			$lat=$order['lat'];
			$lng=$order['lng'];
			//查出匹配用户的订单
			$where=[
				'o.order_id ='=>'',
				'o.workhours'=>$order['workhours'],
				'o.type_s_text'=>$order['type_s_text']
			];
			$sql = "round(6378.138*2*asin(sqrt(pow(sin( ({$lat}*pi()/180-o.lat*pi()/180)/2),2)+cos({$lat}*pi()/180)*cos(o.lat*pi()/180)* pow(sin( ({$lng}*pi()/180-o.lng*pi()/180)/2),2))))";
			$match_jiedan=$this->db->select('o.*,'.$sql.' as km,u.tel as u_tel')
			->from('match_jiedan as o')
			->join('users as u','u.id=o.user_id','left')
			->where($where)
			->having(['km <='=>15])
			->get()
			->result_array();
			//发送短信
			foreach($match_jiedan as $key=>$item){
				$this->toSmsNotify($item['u_tel'],'1');
			}
		}
		
		//复制多个订单
		public function copyorder($o){
			//获取网站信息
			//$siteinfo=$this->getSiteInfo();
			//$service_price=$o['price_c']*($siteinfo['servicePrice']/100);
			//echo $service_price."######".(0.00/12);
			$data=[
				'order_pay_id'=>$o['id'],
				'name'=>$o['name'],
				'number'=>"",
				'contractor_id'=>$o['user_id'],
				'province'=>$o['province'],
				'city'=>$o['city'],
				'area'=>$o['area'],
				'address'=>$o['address'],
				'service_price'=>$o['price']/$o['num'],
				'status'=>'4',
				'datetime_xd'=>unix_to_human(time(), TRUE, 'eu'),
				'o_type_p_text'=>$o['type_p_text'],
				'o_type_s_text'=>$o['type_s_text'],
				'unit'=>$o['unit'],
				'lat'=>$o['lat'],
				'lng'=>$o['lng'],
				'workhours'=>$o['workhours'],
				'price'=>$o['price_c'],
				'num'=>1,
				'des'=>$o['des']
			];
			if($o['num']>1){
				$arr=[];
				for($i=0;$i<$o['num'];$i++){
					$number=date("Ymd").substr(md5(microtime(true).order_number()), 0, 6);
					$data['number']=$number;
					array_push($arr,$data);
				}
				$state=$this->db->insert_batch('order',$arr);
				$msg=$state ? '订单确认成功' : '订单确认失败';
				respond([],$msg,200);
			}else{
				$data['number']=date("Ymd").substr(md5(microtime(true).order_number()), 0, 6);
				$state=$this->db->insert('order', $data);
				$msg=$state ? '订单确认成功' : '订单确认失败';
				respond([],$msg,200);
			}
			  
		}
		//获取支付后的所有订单
		public function getOrderPayInfo(){
			$user=$this->userVerification();
			$order=$this->db->select('o.number,op.price')
			->from("order as o")
			->join('order_pay as op','o.order_pay_id=op.id','left')
			->where(['o.order_pay_id'=>$_GET['id']])->get()->result_array();  
			
			respond($order,'数据获取成功！',200);
			//print_r($order);
		}
		//获取所有工种并且获取工种价格
		public function getTypeWorkT(){
			//获取工种单价
			$data=$_GET;
			$typeWork=$this->db->select('id,name,parent_id,price,areaPrice')->order_by('sort DESC')->get_where('type_work')->result_array();
			foreach($typeWork as $key=>$item){
				if(!empty($item['areaPrice'])){
					$price=$this->province_city_price([
						'type_s'=>$item['id'],
						'province'=>$data['province'],
						'city'=>$data['city']
					]);
					if(!empty($price['price'])){
						$typeWork[$key]['price']=$price['price'];
					}
					if(!empty($price['hours'])){
						$typeWork[$key]['hours']='/'.$price['hours'].'小时';
					}
				}
			}
			respond([
				'typework'=>get_attr($typeWork,0)
			],'数据获取成功！',200);
		}
		//获取工种省市区价格
		public function province_city_price($datapost){
			$type_work=$this->db->get_where('type_work',['id'=>$datapost['type_s']])->row_array();
			
			$price=$type_work['price'];
			$hours="";
			if(empty($type_work['areaPrice'])){
				return $price;
				exit ;
			}
			$areaPrice=json_decode($type_work['areaPrice'],true);
			$province_price=0; //省价格
			$city_price=0;     //市区价格
			//小时
			$province_h="";
			$city_h="";
			foreach($areaPrice as $key=>$item){
				
				if($datapost['province']==citytext($item['text'])){
					$province_price=$item['price'];
					$province_h=$item['hours'];
					foreach($item['children'] as $keys=>$items){
						if($datapost['city']==$items['text']){
							$city_price=$items['price'];
							$city_h=$items['hours'];
						}
					}
				}
			}
			if(!empty($city_price)){
				$price=$city_price;
				$hours=$city_h;
			}else{
				if(!empty($province_price)){
					$price=$province_price;
					$hours=$province_h;
				}
			}
		//	print_r(['price'=>$price,'province_price'=>$province_price,'city_price'=>$city_price]);
			return ['price'=>$price,'hours'=>$hours];
		}
		//是否为空
	    public function getPriceNull($areaPrice){
			$areaPrice=json_decode($areaPrice,true);
			$prce_off=false;
			foreach($areaPrice as $key=>$item){
				if(!empty($item['price'])){
					return true;
				}else{
					foreach($item['children'] as $keys=>$items){
						if(!empty($items['price'])){
							return true;
						}
					}
				}
			}
			return $prce_off;
		}
		//获取设置了价格的工种
		public function getTypeWorks(){
			$user=$this->userVerification();
			
			//获取工种单价
			$typeWork=$this->db->select('id,name,parent_id,price,areaPrice')->order_by('sort DESC')->get_where('type_work')->result_array();
			$arr=[];
			foreach($typeWork as $key=>$item){
				//print_r($item);
				if(!empty($item['price'])&&$item['price']!='0.00'){
					array_push($arr,$item);
				}else{
					if($item['parent_id']=='0'){
						array_push($arr,$item);
					}else{
						if(!empty($item['areaPrice'])){
							if($this->getPriceNull($item['areaPrice'])){
								array_push($arr,$item);
							}
						}
					}
				}
			}
			$shoarr=get_attr($arr,0);
			$arrtype=[];
			foreach($shoarr as $k=>$i){
				if(!empty($i['children'])){
					array_push($arrtype,$i);
				}
			}
			//print_r($arrtype);
			$success=[
				'typeWork'=>$arrtype
			];
			
			if(!empty($user['cwstate'])&&$user['cwstate']=='2'){
				
				if($user['audit']==='1'){
					//用户身份冻结状态
					if($user['state']=='1'){
						respond($success,'加载成功！',200);
					}else{
						respond($success,'冻结！',204);
					}
				}else if($user['audit']==='2'){
					respond($success,'包工身份审核拒绝！',203);
				}else if($user['audit']==='3'){
					respond($success,'包工身份审核中！',202);
				}
			}else{
				respond($success,'您还不是包工身份！',201);
			}
			
		}
		//获取工人工种价格
	    public function getUserType(){
			$user=$this->userVerification();
			
			//201 该用户不是工人
			//202 包工头身份正在审核中
			//203 包工头身份审核未通过
			//204 帐号被冻结
			$success=[
				'type_s'=>$user['type_s'],
				'province'=>$user['province'],
				'city'=>$user['city']
			];
			
			if(!empty($user['province'])&&!empty($user['type_s'])){
				$type_work=$this->province_city_price($success);
				//var_dump($type_work);
				$price=$type_work['price'];
				$hours=$type_work['hours'];
				if(!empty($price)&&$price!='0.00'){
					$success['price_c']=$price;
					if(!empty($hours)){
						$success['unit']='/'.$hours.'小时';
					}
				}
				$success['type_p_text']=$user['type_p_text'];
				$success['type_s_text']=$user['type_s_text'];
			}
			
			if(!empty($user['cwstate'])&&$user['cwstate']=='1'){
				
				if($user['audit']==='1'){
					if($user['state']=='1'){
						$siteinfo=$this->getSiteInfo();
						$success['mWOff']=$siteinfo['mWOff']; //是否开启了分享
						$success['mWfXCS']=$siteinfo['mWfXCS'];  //分享次数限制值
						$success['mWDJCS']=$siteinfo['mWDJCS']; //未到岗
						$success['sharenum']=$user['sharenum']; //用户分享中次数
						$success['noArrive']=$user['noArrive']; //用户未到岗次数
						respond($success,'获取成功！',200);
						//print_r($type_work);
					}else{
						respond($success,'冻结！',204);
					}
				}else if($user['audit']==='2'){
					respond($success,'用户审核拒绝！',203);
				}else if($user['audit']==='3'){
					respond($success,'用户审核中！',202);
				}
			}else{
				respond($success,'用户不是工人！',201);
			}
			
		}
		//匹配接单
		public function jiedan(){
			$user=$this->userVerification();
			$siteinfo=$this->getSiteInfo();
			
			if($user['cancel_ym']==date('Ym')){
				if($user['cancel_num']>=$siteinfo['cancelTimes']){
					respond([],'本月取消订单数已经超过'.$siteinfo['cancelTimes'].'次，不能在接单！',40001);
				}
			}
		
			$jiedan=$this->db->get_where('match_jiedan',['user_id'=>$user['id']])->row_array();
			if(!empty($jiedan)){
				if(!empty($jiedan['order_id'])){
					respond([],'您已经有一个服务中的订单！',40001);
				}else{
					$this->getJiedanOrder($_POST,$user);
				}
			}else{
				$this->getJiedanOrder($_POST,$user);
			}
		}
		//查询匹配订单
		public function getJiedanOrder($data,$user){
			//匹配工种
			$lat=$data['lat'];
			$lng=$data['lng'];
			//print_r($user);
			$sql = "round(6378.138*2*asin(sqrt(pow(sin( ({$lat}*pi()/180-o.lat*pi()/180)/2),2)+cos({$lat}*pi()/180)*cos(o.lat*pi()/180)* pow(sin( ({$lng}*pi()/180-o.lng*pi()/180)/2),2))))";
			//$item
			$order=$this->db->select('o.*,'.$sql.'as km')
			->from('order as o')
			->having(['km <='=>15])
			->where(['o.status'=>'4','o.o_type_p_text ='=>$user['type_p_text'],'o.o_type_s_text ='=>$user['type_s_text'],'workhours ='=>$data['workhours']])
			->get()
			->row_array();
			//匹配成功
			$jiedan=$this->db->get_where('match_jiedan',['user_id'=>$user['id']])->row_array();
			$jiedan_data=[
				'lat'=>$lat,
				'lng'=>$lng,
				'user_id'=>$user['id'],
				'workhours'=>$data['workhours'],
				'type_p_text'=>$user['type_p_text'],
				'type_s_text'=>$user['type_s_text'],
				'order_id'=>''
			];
			$id='';
			$code=200;
			if(empty($order)){
				//匹配没有成功下一次等待匹配
				$code=202;
			}
			if(!empty($jiedan)){
				//添加接单规则，下一次准备提醒
				$state=$this->db->where('user_id',$user['id'])->update("match_jiedan",$jiedan_data);
				$id=$jiedan['id'];
			}else{
				//添加接单规则，下一次准备提醒
				$state=$this->db->insert("match_jiedan",$jiedan_data);
				$id=$this->db->insert_id();
			}
			respond(['id'=>$id],'匹配成功！',$code);
		}
		//获取匹配订单
		public function getMyOrder(){
			$user=$this->db->get_where('match_jiedan',['id'=>$_GET['id']])->row_array();
			//匹配工种
			$lat=$user['lat'];
			$lng=$user['lng'];
			
			$page=$_GET['page'];//当前页码
			$limit=$_GET['limit'];//一页显示数量
			
			$sql = "round(6378.138*2*asin(sqrt(pow(sin( ({$lat}*pi()/180-o.lat*pi()/180)/2),2)+cos({$lat}*pi()/180)*cos(o.lat*pi()/180)* pow(sin( ({$lng}*pi()/180-o.lng*pi()/180)/2),2))))";
			//DISTINCT(order_pay_id),
			$order=$this->db->select('o.order_pay_id,o.id,o.name,o.unit,o.price,o.datetime_xd,o.province,o.city,o.area,o.address,o.des,u.headimg,u.name as u_name,u.tel as u_tel,'.$sql.'as km')
			->from('order as o')
			->join('users as u','u.id=o.contractor_id','left')
			->having(['km <='=>15])
			->where(['o.status'=>'4','o.o_type_p_text ='=>$user['type_p_text'],'o.o_type_s_text ='=>$user['type_s_text'],'workhours ='=>$user['workhours']])
			->limit($limit, ($page-1)*$limit)
			->get()
			->result_array();
			//print_r($order);
			foreach($order as $key=>$item){
				
				$xing = substr($item['u_tel'],3,4);  //获取手机号中间四位
				$utel=str_replace($xing,'****',$item['u_tel']);  //用****进行替换
				$order[$key]['u_tel']=$utel;
				$order[$key]['datetime_xd']=date_format_text($item['datetime_xd'],'Y.m.d H:i');
				
			}
			
			respond($order,'加载成功！',200);
		}
		//获取订单详情
		public function orderDetails(){
			$order=$this->db->select('o.*,u.headimg,u.name as u_name,u.tel as u_tel')
			->from('order as o')
			->join('users as u','u.id=o.contractor_id','left')
			->where(['o.id'=>$_GET['id']])
			->get()
			->row_array();
			
			$order['datetime_xd']=date_format_text($order['datetime_xd'],'Y.m.d H:i');
			//获取包工的评价
			$evaluation=$this->db->select('e.*,u.headimg,u.name')
			->from('evaluation as e')
			->join('users as u','u.id=e.user_id','left')
			->where(['e.obj_user_id'=>$order['contractor_id'],'e.type'=>'1','e.show_type'=>'1']);
			$db = clone($this->db);
			$count = $this->db->count_all_results();
			$this->db = $db;		
			$evaluation=$this->db->order_by('e.datetime DESC')->limit(2)->get()->result_array();	
			
			foreach($evaluation as $key=>$item){
				$evaluation[$key]['datetime']=date_format_text($item['datetime'],'Y.m.d H:i');
			}
			
			$order['evaluation']=$evaluation;
			$order['evaluation_num']=$count;
			respond($order,'加载成功！',200);
		}
		//工人接单确认接单
		public function okjiedan(){
			$user=$this->userVerification();
			$siteinfo=$this->getSiteInfo();
			
			if($user['cancel_ym']==date('Ym')){
				if($user['cancel_num']>=$siteinfo['cancelTimes']){
					respond([],'本月取消订单数已经超过'.$siteinfo['cancelTimes'].'次，不能在接单！',40001);
				}
			}
			
			
			$order=$this->db->select('o.*,u.tel as u_tel')
			->from('order as o')
			->join('users as u','u.id=o.contractor_id','left')
			->where(['o.id'=>$_POST['id']])
			->get()
			->row_array();
			
			$jiedan=$this->db->get_where('match_jiedan',['user_id'=>$user['id']])->row_array();
			
			if(!empty($jiedan['order_id'])){
				respond([],'您有未完成订单，不能接单！！',202);
			}else{
				if($order['status']==='4'){
					$data=[
						"status"=>"5", //已经接单
						"work_id"=>$user['id'],
						"datetime_jd"=>unix_to_human(time(), TRUE, 'eu')
					];
					$state=$this->db->where('id',$_POST['id'])->update("order",$data);
					$state=$this->db->where('user_id',$user['id'])->update("match_jiedan",['order_id'=>$_POST['id']]);
					$msg=$state ? '接单成功':'接单失败';
					if($state){
						$this->toSmsNotify($order['u_tel'],'2');
					}
					respond(['number'=>$order['number'],'u_tel'=>$order['u_tel']],$msg,200);
				}else{
					respond([],'已经被其他人接单，请重新选择！',202);
				}
			}
			
		}
		//获取网站信息
		public function getSiteInfo(){
			//siteinfo
			$siteinfo=$this->db->get_where('siteinfo',['id'=>1])->row_array();
			return $siteinfo;
		}
		//获取个人信息
		public function getUserInfo(){
			$user=$this->userVerification();
			$siteinfo=$this->getSiteInfo();
			$cwstate="0";
			if($user['cwstate']!='0'){
				if($user['audit']=='1'){
					$cwstate=$user['cwstate'];
				}
			}
			$xing = substr($user['tel'],3,4);  //获取手机号中间四位
			$tel=str_replace($xing,'****',$user['tel']);  //用****进行替换
			
			
			$typeWork=$this->db->select('id,name,parent_id')->order_by('sort DESC')->get_where('type_work')->result_array();
			$typeWorks=get_attr($typeWork,0);
			$arr=[];
			foreach($typeWorks as $key=>$item){
				if(!empty($item['children'])){
					array_push($arr,$item);
				}
			}
			respond([
				'headimg'=>$user['headimg'],
				'tel'=>$tel,
				'nickname'=>$user['nickname'],
				'cwstate'=>$cwstate,
				'phoneTel'=>$siteinfo['phoneTel'],
				'name'=>$user['name'],
				'cardid'=>$user['cardid'],
				'birthday'=>$user['birthday'],
				'type_p'=>$user['type_p'],
				'type_s'=>$user['type_s'],
				'card_img_z'=>$user['card_img_z'],
				'card_img_f'=>$user['card_img_f'],
				'type_p_text'=>$user['type_p_text'],
				'type_s_text'=>$user['type_s_text'],
				'type_work'=>$arr
			],'数据获取成功！',200);
		}
		public function savUserinfo(){
			$user=$this->userVerification();
			$u=$_POST;
			$data=[
				'headimg'=>$u['headimg'],
				'nickname'=>$u['nickname'],
				'name'=>$u['name'],
				'cardid'=>$u['cardid'],
				'birthday'=>$u['birthday'],
				'card_img_z'=>$u['card_img_z'],
				'card_img_f'=>$u['card_img_f'],
				'type_p'=>$u['type_p'],
				'type_s'=>$u['type_s']
			];
			
			if($user['cwstate']=="0"){
				$state=$this->db->where('id',$user['id'])->update("users",$data);
				respond([],'保存成功！',200);
			}else{
				$data=[
					'headimg'=>$u['headimg'],
					'nickname'=>$u['nickname'],
				];
				$state=$this->db->where('id',$user['id'])->update("users",$data);
				respond([],'保存成功！！',200);
			}
		}
		//获取我的评价
		public function getEvaluationList(){
			$user=$this->userVerification();
			if(!empty($_GET['cid'])){
				$where=['e.obj_user_id'=>$_GET['cid'],'show_type'=>'1','e.type'=>'1'];
			}else{
				$where=['e.obj_user_id'=>$user['id']];
				if($user['cwstate']=='1'){
					$where['e.type']='2';
				}
				if($user['cwstate']=='2'){
					$where['e.type']='1';
				}
			}
			
			$page=$_GET['page'];//当前页码
			$limit=$_GET['limit'];//一页显示数量
			
			//DISTINCT(order_pay_id),
			$query=$this->db->select('e.*,u.nickname,u.name as u_name,u.headimg')
			->from('evaluation as e')
			->join('users as u','u.id=e.user_id','left')
			->where($where);
			
			$db = clone($this->db);
			$count = $this->db->count_all_results();
			$this->db = $db;	
			$evaluation=$this->db->limit($limit, ($page-1)*$limit)
			->get()
			->result_array();	
			foreach($evaluation as $key=>$item){
				$evaluation[$key]['datetime']=date_format_text($item['datetime'],'Y.m.d H:i:m');
			}
			respond(['evaluation'=>$evaluation,'count'=>$count],'获取成功！！',200);
		}
		//我的接单
		public function getMyjidan(){
			
			$user=$this->userVerification();
			$page=$_GET['page'];//当前页码
			$limit=$_GET['limit'];//一页显示数量
			$status=$_GET['state'];	
			$or_where=[];
			if($status=='7'){
				$or_where['status']='3';
			}
			//DISTINCT(order_pay_id),
			$order=$this->db->select('o.order_pay_id,o.id,o.name,o.unit,o.price,o.datetime_xd,o.province,o.city,o.area,o.address,o.des,u.headimg,u.name as u_name,u.tel as u_tel')
			->from('order as o')
			->join('users as u','u.id=o.contractor_id','left')
			->where(['o.status'=>$status,'work_id'=>$user['id']])
			->or_where($or_where)
			->limit($limit, ($page-1)*$limit)
			->get()
			->result_array();
			foreach($order as $key=>$item){
				
				$xing = substr($item['u_tel'],3,4);  //获取手机号中间四位
				$u_tel=str_replace($xing,'****',$item['u_tel']);  //用****进行替换
				$order[$key]['u_tel']=$u_tel;
				$order[$key]['datetime_xd']=date_format_text($item['datetime_xd'],'Y.m.d H:i');
			}
			respond($order,'加载成功！',200);
		}
		//我的发布
		public function getMyfabu(){
			$user=$this->userVerification();
			$page=$_GET['page'];//当前页码
			$limit=$_GET['limit'];//一页显示数量
			$status=$_GET['state'];	
			$or_where=[];
			if($status=='5'){
				$or_where['status']='4';
			}
			//DISTINCT(order_pay_id),
			$order=$this->db->select('o.status,o.service_price,o.order_pay_id,o.id,o.name,o.unit,o.price,o.datetime_xd,o.province,o.city,o.area,o.address,o.des,u.headimg,u.name as u_name,u.tel as u_tel')
			->from('order as o')
			->join('users as u','u.id=o.contractor_id','left')
			->where(['o.status'=>$status,'o.contractor_id'=>$user['id']])
			->or_where($or_where)
			->limit($limit, ($page-1)*$limit)
			->get()
			->result_array();
			foreach($order as $key=>$item){
				$order[$key]['datetime_xd']=date_format_text($item['datetime_xd'],'Y.m.d H:i');
			}
			respond($order,'加载成功！',200);
		}
		//我的发布详情
		public function getMyfabuDes(){
			$user=$this->userVerification();
			$order=$this->db->select('o.*,u.headimg,u.name as u_name,u.tel as u_tel,cu.name as cu_name,cu.tel as cu_tel,cu.headimg as cu_headimg')
			->from('order as o')
			->join('users as u','u.id=o.work_id','left')
			->join('users as cu','cu.id=o.contractor_id','left')
			->where(['o.id'=>$_GET['id']])
			->get()
			->row_array();
			
			$xing = substr($order['cu_tel'],3,4);  //获取手机号中间四位
			$cu_tel=str_replace($xing,'****',$order['cu_tel']);  //用****进行替换
			$order['cu_tel']=$cu_tel;
			
			//查询我评价的
			$evaluation=$this->db->select('e.*')
			->from('evaluation as e')
			->where(['e.order_id'=>$_GET['id'],'e.user_id'=>$user['id'],'e.type'=>'2'])
			->get()
			->row_array();
			
			if(!empty($evaluation)){
				$order['text']=$evaluation['text'];
				$order['star']=$evaluation['star'];
				$order['datetime_pj']=date_format_text($evaluation['datetime'],'Y.m.d H:i:m');
				$order['e_state']="1";
			}
			if(!empty($order['datetime_xd'])){$order['datetime_xd']=date_format_text($order['datetime_xd'],'Y.m.d H:i:m');}
			if(!empty($order['datetime_px'])){$order['datetime_px']=date_format_text($order['datetime_px'],'Y.m.d H:i:m');}
			if(!empty($order['datetime_wc'])){$order['datetime_wc']=date_format_text($order['datetime_wc'],'Y.m.d H:i:m');}
			// $order['datetime_px']=date_format_text($order['datetime_px'],'Y.m.d H:i:m');
			// $order['datetime_wc']=date_format_text($order['datetime_wc'],'Y.m.d H:i:m');
			respond($order,'加载成功！',200);
		}
		
		//我的接单详情
		public function getMyjiedanDes(){
			$user=$this->userVerification();
			$siteinfo=$this->getSiteInfo();
			$order=$this->db->select('o.*,u.headimg,u.name as u_name,u.tel as u_tel,u.nickname as u_nickname')
			->from('order as o')
			->join('users as u','u.id=o.contractor_id','left')
			// ->join('evaluation as e','e.order_id=o.id','left')
			->where(['o.id'=>$_GET['id']])
			->get()
			->row_array();
			
			$xing = substr($order['u_tel'],3,4);  //获取手机号中间四位
			$u_tel=str_replace($xing,'****',$order['u_tel']);  //用****进行替换
			$order['u_tel']=$u_tel;
			//获取评价
			$evaluation=$this->db->select('e.*')
			->from('evaluation as e')
			->where(['e.order_id'=>$_GET['id'],'e.user_id'=>$user['id'],'e.type'=>'1'])
			->get()
			->row_array();
			//获取投诉
			$complaints=$this->db->select('c.*')
			->from('complaints as c')
			->where(['c.order_id'=>$_GET['id'],'c.user_id'=>$user['id']])
			->get()
			->row_array();
			
			if(!empty($complaints)){
				$order['text']=$complaints['text'];
				$order['f_text']=$complaints['f_text'];
				if(!empty($complaints['img'])){
					$order['complaints_img']=explode(',',$complaints['img']);
				}else{
					$order['complaints_img']=$complaints['img'];
				}
				
				$order['datetime_ts']=date_format_text($complaints['datetime'],'Y.m.d H:i:m');
				$order['datetime_fk']=date_format_text($complaints['datetime_f'],'Y.m.d H:i:m');
				
				$order['complaints_state']=$complaints['state'];
				$order['ts_state']="1";
			}
			
			if(!empty($evaluation)){
				$order['text']=$evaluation['text'];
				$order['star']=$evaluation['star'];
				$order['datetime_pj']=date_format_text($evaluation['datetime'],'Y.m.d H:i:m');
				$order['e_state']="1";
			}
			
			//print_r($order);
			
			$order['datetime_xd']=date_format_text($order['datetime_xd'],'Y.m.d H:i:m');
			$order['datetime_jd']=date_format_text($order['datetime_jd'],'Y.m.d H:i:m');
			$order['datetime_wc']=date_format_text($order['datetime_wc'],'Y.m.d H:i:m');
			$order['cancelTimes']=$siteinfo['cancelTimes'];
			respond($order,'加载成功！',200);
		}
		//退款操作
		public function priceRefund($pay_order,$total_fee,$refund_fee){
			$transaction_id=$pay_order;
			$refund=$this->refundPrice($transaction_id,$total_fee*100,$refund_fee*100);
			if($refund['return_code']=='SUCCESS'&&$refund['result_code']=='SUCCESS'){
				return true;
			}
		}
		//订单状态改变
		public function setOrder(){
			
			$user=$this->userVerification();
			$priceRefundOff=false;
		    $order=$this->db->select('o.*,op.pay_order,op.free_state,op.price as op_price,u.cancel_num as o_u_cancel_num,u.noArrive_ym as u_o_noArrive_ym,u.noArrive,uc.tel as uc_tel')
		    ->from('order as o')
			->join('order_pay as op','op.id=o.order_pay_id','left')
		    ->join('users as u','u.id=o.work_id','left')
			->join('users as uc','uc.id=o.contractor_id','left')
		    ->where(['o.id'=>$_POST['id']])
		    ->get()
		    ->row_array();
			
			
			$siteinfo=$this->getSiteInfo();
			$data=[];
			$status="";
			if($order['status']==='5'){
				$data['status']='6';
				$status='6';
			}
			// 取消订单操作
			if(!empty($_POST['type'])&&$_POST['type']=='cancel'){
				//取消订单订单时根据身份做不同操作
				//1工人取消后重置取消订单状态，可以再次接单
				if($_POST['cancel_type']=='1'){
					$max_time = time();
					$min_time = strtotime($order['datetime_jd']); 
					$diff= $max_time - $min_time;
					//YM
					$cancel_num=0;
					if($user['cancel_ym']==date('Ym')){
						if($user['cancel_num']>=$siteinfo['cancelTimes']){
							respond([],'本月取消订单数已经超过'.$siteinfo['cancelTimes'].'次，不能取消订单！',202);
						}else{
							$cancel_num=$user['cancel_num']+1;
						}
					}else{
						$cancel_num=1;
					}
					//可以取消订单
					if(round($diff/3600)<$siteinfo['cancelTime']){
						// $data['cancel_type']=$_POST['cancel_type'];
						$data['status']='4';
						$data['work_id']="";
						$data['datetime_jd']=NULL;
						$data['datetime_px']=NULL;
						$status='4';
						$state=$this->db->where('user_id',$user['id'])->update("match_jiedan",['order_id'=>'']);	
						if($state){
							$this->toSmsNotify($order['uc_tel'],'3');
							$this->db->where('id',$user['id'])->update("users",['cancel_num'=>$cancel_num,'cancel_ym'=>date('Ym')]);	
						}
					}else{
						respond(['status'=>$status],'接单已经超过'.$siteinfo['cancelTime'].'小时，不能取消操作！',202);
					}
				}
				//2.包工取消的不能再次接单
				if($_POST['cancel_type']=='2'){
					if($order['status']!='2'){
						$priceRefundOff=true;
					}
					
					//退出费用等操作（此处对接退款操作）
					//priceRefund
					$status='2';
					$data['status']="2";
					$data['cancel_type']="2";
					$data['datetime_px']=unix_to_human(time(), TRUE, 'eu');
				}
				
			}
			//服务中
			if(!empty($_POST['type'])&&$_POST['type']=='confirm'){
				$data['status']='7';
				$status='7';
			}
			//未到岗
			if(!empty($_POST['type'])&&$_POST['type']=='confirmWdg'){
				//u_o_cancel_ym,u.noArrive
					$noArrive_num=0;
					$mWDJCS=$siteinfo['mWDJCS'];//用户限制未到岗次数冻结用户
					$noArrive=$order['noArrive'];//未到岗次数
					$ym=$order['u_o_noArrive_ym'];
					$work_id=$order['work_id'];
				
					if($ym==date('Ym')){
						if($noArrive>=$mWDJCS){
							//冻结帐号
							$this->db->where('id',$work_id)->update("users",['state'=>'0']);	
						}else{
							$noArrive_num=$noArrive+1;
						}
					}else{
						$this->db->where('id',$work_id)->update("users",['noArrive'=>1,'noArrive_ym'=>date('Ym')]);	
					}
				
				$data['state_wdg']='2';
				$this->db->where('id','1')->update("icomet_msg",['msgnum'=>1]);	
				//$data['state_wdg']='2';
				$status='2';
			}
			//确认服务完成
			if(!empty($_POST['type'])&&$_POST['type']=='confirmServer'){
				$status='3';
				$data['status']='3';
				$data['datetime_nwwc']=unix_to_human(time(), TRUE, 'eu');
				if($order['status']==='2'){
					respond(['status'=>$status],'未到岗订单已经被后台确认取消！',40001);
				}
				//echo ""
				//exit;
			}
			//确认支付劳务费用
			if(!empty($_POST['type'])&&$_POST['type']=='pay'){
				$data['status']='1';
				$data['datetime_wc']=unix_to_human(time(), TRUE, 'eu');
				$status='1';
				if($order['status']==='1'){
					respond(['status'=>$status],'订单已确认！',200);
				}
				$this->db->where('user_id',$user['id'])->update("match_jiedan",['order_id'=>'']);	
			}
			
			if(!empty($data)){
				$state=$this->db->where('id',$_POST['id'])->update("order",$data);
				if($state){
					$success=['status'=>$status];
					if($data['status']=='2'){
						$success['datetime_px']=$data['datetime_px'];
					}
					$msg='成功';
					if($priceRefundOff){
						$state=$this->priceRefund($order['pay_order'],$order['op_price'],$order['service_price']);
					    if(!$state){
							$msg='退款失败，请联系平台！';
						}
					}
					respond($success,$msg,200);
				}else{
					respond([],'失败！',202);
				}
			}
			//print_r($data);
		}
		//提交评价
		public function savEvaluate(){
			$user=$this->userVerification();
			
			$order=$this->db->select('o.*')
			->from('order as o')
			// ->join('users as u','u.id=o.contractor_id','left')
			->where(['o.id'=>$_POST['id']])
			->get()
			->row_array();
		  
			$data=[
				'user_id'=>$user['id'],
				'obj_user_id'=>$order['contractor_id'],
				'text'=>$_POST['text'],
				'star'=>$_POST['star'],
				'datetime'=>unix_to_human(time(), TRUE, 'eu'),
				'order'=>$order['number'],
				'order_id'=>$order['id'],
				'type'=>'1'
			];
			$state=$this->db->insert("evaluation",$data);
			respond([],'评价成功！',200);
			//print_r($data);
			//evaluation
		}
	
		
		//发布者提交评价
		public function savEvaluates(){
			$user=$this->userVerification();
			
			$order=$this->db->select('o.*')
			->from('order as o')
			// ->join('users as u','u.id=o.contractor_id','left')
			->where(['o.id'=>$_POST['id']])
			->get()
			->row_array();
		  
			$data=[
				'user_id'=>$user['id'],
				'obj_user_id'=>$order['work_id'],
				'text'=>$_POST['text'],
				'star'=>$_POST['star'],
				'datetime'=>unix_to_human(time(), TRUE, 'eu'),
				'order'=>$order['number'],
				'order_id'=>$order['id'],
				'type'=>'2' //2代表包工评价工人的
			];
			$state=$this->db->insert("evaluation",$data);
			respond([],'评价成功！',200);
			//print_r($data);
			//evaluation
		}
		//提交投诉
		public function savComplaint(){
			$user=$this->userVerification();
			
			$order=$this->db->select('o.*')
			->from('order as o')
			->where(['o.id'=>$_POST['id']])
			->get()
			->row_array();
		  
			$data=[
				'user_id'=>$user['id'],
				'obj_user_id'=>$order['contractor_id'],
				'text'=>$_POST['text'],
				'img'=>$_POST['img'],
				'datetime'=>unix_to_human(time(), TRUE, 'eu'),
				'order_id'=>$order['id']
			];
			$state=$this->db->insert("complaints",$data);
			respond([],'提交成功！',200);
			//print_r($data);
			//evaluation
		}
		//意见反馈
		public function savFeedback(){
			$user=$this->userVerification();
			
			$data=[
				'user_id'=>$user['id'],
				'text'=>$_POST['text'],
				'img'=>$_POST['img'],
				'datetime'=>unix_to_human(time(), TRUE, 'eu')
			];
			$state=$this->db->insert("feedback",$data);
			respond([],'反馈成功，请耐心等待平台处理！',200);
		}
		//获取评价内容
		public function getEvaluate(){
			$user=$this->userVerification();
			$evaluation=$this->db->select('e.*')
			->from('order as o')
			->join('evaluation as e','e.order_id=o.id','left')
			->where(['o.id'=>$_POST['id']])
			->get()
			->row_array();
			respond($evaluation,'获取成功！',200);
		}
		//获取协议
		public function getXieyi(){
			$data=$this->getSiteInfo();
			$content="";
			if($_GET['type']=='renzhen'){
				$content=$data['hometext'];
			}		
			if($_GET['type']=='jiedan'){
				$content=$data['gmxztext'];
			}
			if($_GET['type']=='fabu'){
				$content=$data['gywmtext'];
			}
			respond(['content'=>$content],'获取成功！',200);
		}
        //验证用户是否存在
        public function userVerification(){
          //验证用户是否存在
		  $this->isRole();
          if(!empty($_SERVER['HTTP_TOKEN'])){
          	//$user=$this->db->get_where('users',['token'=>$_SERVER['HTTP_TOKEN']])->row_array();
			
			$user=$this->db->select('u.*,tw.name as type_p_text,tws.name as type_s_text')
			->from("users as u")
			->join('type_work tw',"tw.id=u.type_p",'left') //工种
			->join('type_work tws',"tws.id=u.type_s",'left') //工种
			 ->where(['token'=>$_SERVER['HTTP_TOKEN']])->get()->row_array();  
			
            if(empty($user)){
              $json_string = json_encode(['code'=>40001,"msg"=>"用户不存在"]);
         	  echo $json_string;
         	  exit;	
            }else{
              return $user;
            } 
          }else{
             $json_string = json_encode(['code'=>40001,"msg"=>"登录已过期！"]);
         	 echo $json_string;
         	 exit;	
          }
        } 
        //权限验证
		public function isRole(){
			if(!empty($_SERVER['HTTP_AUTH'])){
			  if($_SERVER['HTTP_AUTH']!='jianzhu'  ){
				  $json_string = json_encode(['code'=>40001,"msg"=>"接口暂无权限"]);
				  echo $json_string;
				  exit;	
			  }
			}else{
			  $json_string = json_encode(['code'=>40001,"msg"=>"接口暂无权限"]);
			  echo $json_string;
			  exit;	
			}
		}
		
	}
?>
