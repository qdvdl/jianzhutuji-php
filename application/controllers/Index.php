<?php
    class Index extends MY_Controller{
        
        public function __construct(){
            parent::__construct();
			$this->load->library(['import_file','session']);
			$this->load->helper(['url','common','download']);
        }
		
		public function index(){
			echo "网站正在建设总！！";
		}
		
		//下载导入模版文件
		public function downTempClient(){
			$file_path="./downloadFile/client.xls";
			// echo $file_path;
			force_download($file_path, NULL);
		}
		//下载订单导入模版
		public function downTempOrder(){
			$file_path="./downloadFile/order.xls";
			// echo $file_path;
			force_download($file_path, NULL);
		}
		//准备客户数据导出
		public function exportClient(){
			$this->verification_dow();
			
			$like=[];
			//条件
			$where=[];
			//昵称
			if(!empty($_GET['name'])){
				$like['name']=$_GET['name'];
			}
			//查询数据
			//名称
			if(!empty($_GET['client_name'])){
				$like['c.client_name']=$_GET['client_name'];
			}
			//电话
			if(!empty($_GET['client_tel'])){
				$like['c.client_tel']=$_GET['client_tel'];
			}
			//成交时间
			if(!empty($_GET['deal_time'])){
				$tarr=str_date_time($_GET['deal_time']);
				$where['c.deal_time >=']=$tarr['s'];
				$where['c.deal_time <=']=$tarr['e'];
			}
			
			//开始查询
			$data_list=$this->db->select('c.*,v.version as v_version,b.name as b_name,a.name as a_name')
			->from("client as c")
			->like($like)
			->join('vehicle as v',"v.id=c.vehicle_id",'left') //绑定车辆
			->join('brand as b',"v.brand_id=b.id",'left') //绑定车辆品牌
			->join('admin as a',"a.id=c.admin_id",'left') //管理员
			->where($where)->order_by('c.submit_time DESC')->get()->result_array();
			
					
			foreach($data_list as $key=>$item){
				$data_list[$key]['state_text']=$item['deal_state']==='2' ? '否' : '是';
			}
			
			$table_title=[
				'client_name'=>'客户姓名',
				'client_tel'=>'客户电话',
				'state_text'=>'成交状态',
				'b_name'=>'客户品牌',
				'v_version'=>'车辆型号',
				'deal_time'=>'成交时间',
				'deal_price'=>'成交价格',
				'a_name'=>'管理员',
				'business'=>'业务员'
			];
		
			$file_name="客户列表.xls";
			$this->import_file->export_data($data_list,$file_name,$table_title);
			
		}
		//准备订单数据
		public function exportOrder(){
			$this->verification_dow();
			
			$like=[];//模糊搜索内容
			$where=[];//搜索条件
			
			//名称
			if(!empty($_GET['client_name'])){
				$like['c.client_name']=$_GET['client_name'];
			}
			//电话
			if(!empty($_GET['client_tel'])){
				$like['c.client_tel']=$_GET['client_tel'];
			}
			
			
			//成交时间
			if(!empty($_GET['deal_time'])){
				$tarr=str_date_time($_GET['deal_time']);
				$where['c.deal_time >=']=$tarr['s'];
				$where['c.deal_time <=']=$tarr['e'];
			}
			//提交时间
			if(!empty($_GET['submit_time'])){
				$tarr=str_date_time($_GET['submit_time']);
				$where['c.submit_time >=']=$tarr['s'];
				$where['c.submit_time <=']=$tarr['e'];
			}
			//成交状态
			if(!empty($_GET['deal_state'])){
				$where['c.deal_state']=$_GET['deal_state'];
			}
			//品牌
			if(!empty($_GET['brand_id'])){
				$where['v.brand_id']=$_GET['brand_id'];
			}
			//车型
			if(!empty($_GET['vehicle'])){
				$where['v.id']=$_GET['vehicle'];
			}
			
			//开始查询
			$data_list=$this->db->select('c.*,v.version as v_version,b.name as b_name,a.name as a_name,v.price')
			->from("order as c")
			->like($like)
			->join('vehicle as v',"v.id=c.vehicle_id",'left') //绑定车辆
			->join('brand as b',"v.brand_id=b.id",'left') //绑定车辆品牌
			->join('admin as a',"a.id=c.admin_id",'left') //管理员
			->where($where)->order_by('c.submit_time DESC')->get()->result_array();
			//计算总页数
			//品牌名称
			foreach($data_list as $key=>$item){
				if($item['deal_state']=="1"){
					$data_list[$key]['state_text']="已通过";
				}else if($item['deal_state']=="2"){
					$data_list[$key]['state_text']="还款中";
				}else if($item['deal_state']=="3"){
					$data_list[$key]['state_text']="已完成";
				}
				
				// $data_list[$key]['price']=number_format($item['price'],2);
				// $data_list[$key]['deal_price']=number_format($item['deal_price'],2);
				// $data_list[$key]['payment']=number_format($item['payment'],2);
				
			}
			
			$table_title=[
				'client_name'=>'客户姓名',
				'client_tel'=>'客户电话',
				'state_text'=>'订单状态',
				'b_name'=>'车辆品牌',
				'v_version'=>'车辆型号',
				'price'=>'厂家指导价（元）',
				'deal_price'=>'成交价格（元）',
				'deal_time'=>'成交时间',
				'tions'=>'成交比例%',
				'payment'=>'首付金额（元）'
				
			];
				
			$file_name="订单列表.xls";
			$this->import_file->export_data($data_list,$file_name,$table_title);
			
		}
		
		
		
		//导出导出数据验证
		public function verification_dow(){
			if(!empty($_GET['dowfiled'])){
				$v=$this->session->userdata('dname');
				$this->session->unset_userdata('dname');
				if(empty($v)){
					echo "请不要非法访问";
					exit;
				}
			}else{
				echo "请不要非法访问";
				exit;
			}
		}
		
		
		
		
		
	}	