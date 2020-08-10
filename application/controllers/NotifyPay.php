<?php
    class NotifyPay extends MY_Controller{
		public function __construct(){
		    parent::__construct();
		}
		
		//服务费用支付回调地址
		public function servepay(){
			$xmldata=$this->input->raw_input_stream;
			$this->logs_text("支付回调到了".$xmldata);//记录支付后的数据
			//支付数据转换为数组
			$notifyArr=XmlToArr($xmldata);
			if($notifyArr['return_code']=='SUCCESS' && $notifyArr['result_code']=='SUCCESS'){
				$order=$this->order_reset($notifyArr);
				$this->logs_text("支付成功！".$xmldata);	
				$returnParams=array(
					'return_code'=>'SUCCESS',
					'return_msg'=>'OK'
				);	
				echo ArrToXml($returnParams);
			}else{
				//业务处理不正确
				$this->logs_text("支付不成功！".$xmldata);	
			}
		}
		//操作支付订单
		public function order_reset($notifyArr){
			//$out_trade_no=$notifyArr["out_trade_no"];
			
			$out_trade_no="J608102530172739";
			
			exit;
			$order_pay=$this->db->select('o.*,tw.name as type_p_text,tws.name as type_s_text,u.openid')
			->from("order_pay as o")
			->join('users as u','u.id=o.user_id','left')
			->join('type_work tw',"tw.id=o.type_p",'left') //工种
			->join('type_work tws',"tws.id=o.type_s",'left') //工种
			 ->where(['o.order_number'=>$out_trade_no])->get()->row_array();  
			$data=[
				"pay_order"=>$notifyArr["transaction_id"],
				"datetime_zf"=>unix_to_human(time(), TRUE, 'eu'),
				"pay_price"=>$notifyArr["total_fee"],
				"pay_state"=>"1"
			];
			print_r($order_pay);
			$state=$this->db->where('order_number',$out_trade_no)->update("order_pay",$data);
			if($state){
				$this->copyorder($order_pay);
				$this->messageWork($order_pay);
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
				$this->db->insert_batch('order',$arr);
				//$msg=$state ? '订单确认成功' : '订单确认失败';
				//respond([],$msg,200);
			}else{
				$data['number']=date("Ymd").substr(md5(microtime(true).order_number()), 0, 6);
				$this->db->insert('order', $data);
				//$msg=$state ? '订单确认成功' : '订单确认失败';
				//respond([],$msg,200);
			}
			  
		}
		
		
	}