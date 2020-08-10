<?php

	//全局执行函数
	class Globals extends MY_Controller{
				
		public function __construct(){
			parent::__construct();
		}
		
		//发送消息($cname：发送消息的名称，$content：发送内容)
		public function comet_push($cname, $content){
			$cname = urlencode($cname);
			$content = urlencode($content);
			$url = "http://127.0.0.1:8000/push?cname=".$cname."&content=".$content;
			$ch = curl_init($url) ;
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1) ;
			$result = curl_exec($ch) ;
			curl_close($ch) ;
			return $result;
		}
		
		//服务器操作了未到岗后台自动弹窗工人未到岗提示
		public function icometMsg(){
			$icomet_msg=$this->db->get_where('icomet_msg',['msgnum >'=>0])->row_array();
			if(!empty($icomet_msg)){
			   $result=$this->comet_push('jianzhu',"1");
			}
			var_dump($icomet_msg);
		}
		//系统自动确认订单未确认订单
		public function timeConfirmOrder(){
			$siteinfo=$this->db->select('timeLimit')->get_where('siteinfo')->row_array();
			$max_time = time();
			$order=$this->db->select('o.id,o.datetime_nwwc,o.status')
			->from('order as o')
			->where('o.status','3')
			->get()
			->result_array();
			foreach($order as $key=>$item){
				$min_time = strtotime($item['datetime_nwwc']); 
				$diff= $max_time - $min_time;
				if(round($diff/3600)>=$siteinfo['timeLimit']){
				  $this->db->where('id',$item['id'])->update('order',['status'=>'1']);
				}
			}
		}		
		//系统自动取消24点没有接单的订单	
		public function timeCancelOrder(){
			$order=$this->db->select('o.id,o.order_pay_id,op.pay_order,o.service_price,op.free_state,u.openid,op.price as op_price')
			->from('order as o')
			->join('order_pay as op','op.id=o.order_pay_id','left')
			->join('users as u','u.id=op.user_id','left')
			->where('o.status','4')
			->get()
			->result_array();
			
			$arr=[];
			foreach($order as $key=>$item){
				//user_id
				if(!empty($item['pay_order'])){
					$state=$this->priceRefund($item['pay_order'],$item['op_price'],$item['service_price']);	
					if($state){
						//退款成功加入退款数组中
						array_push($arr,['id'=>$item['id'],'status'=>'2','datetime_px'=>unix_to_human(time(), TRUE, 'eu'),'cancel_type'=>'3']);
					}
				}else{
					array_push($arr,['id'=>$item['id'],'status'=>'2','datetime_px'=>unix_to_human(time(), TRUE, 'eu'),'cancel_type'=>'3']);
				}
			}
			if(!empty($arr)){
				$this->db->update_batch('order',$arr,'id');
			}
		}
		//退款操作
		public function priceRefund($pay_order,$total_fee,$refund_fee){
			$transaction_id=$pay_order;
			$refund=$this->refundPrice($transaction_id,$total_fee*100,$refund_fee*100);
			if($refund['return_code']=='SUCCESS'&&$refund['result_code']=='SUCCESS'){
				return true;
			}
		}
	   
	}			
