<?php
	header('Access-Control-Allow-Origin:http://localhost:8080');
	header("Access-Control-Allow-Credentials: true" );
	header('Access-Control-Allow-Headers:Origin,Authorization,X-Requested-With,Content-Type, Accept');
	header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE,PUT');

    //导入文件
	class Import extends MY_Controller{
		public function __construct(){
			parent::__construct();
			$this->load->library(['import_file','session']);
			$this->load->helper(['url','common','download']);
		}
		
		//自定义数据验证
		public function excel_field_validation($data,$type){
			//$type=verify;进行验证$data=[];
			//$type=write;直接写入$data=[[][]]二维数据
			if($type==='verify'){
				$data['datetime']=get_date_by_excel($data['datetime']);
				$errArr=['data'=>$data];//错误
				$tel_match='/^1[34578]{1}\d{9}$/';	//验证手机号是否合法
				$telOff=preg_match($tel_match,$data['tel']);
				$telOff=true;
				if($telOff){
					$test=$this->db->get_where('test',['tel'=>$data['tel']])->row_array();
					if(!empty($test)){
						$errArr['error']="用户已经存在！";
						return $errArr;
					}
				}else{
					$errArr['error']="手机号格式错误！";
					return $errArr;
				}
				return $errArr;
			}
			//直接写入批量写入减少数据库压力
			if($type==='write'){
				if(count($data)==1){
					$state=$this->db->insert('test', $data[0]);
				}else{
					$state=$this->db->insert_batch('test',$data);
				}
				return $state;
			}
		} 
		//导入数据
		public function import_user_data(){
			//40002数据验证没通过
			$upload_path="upload/".date("Y-m-d").'/';
			if(!empty($_POST['path'])){
				$upload_path="upload/".$_POST['path'].'/'.date("Y-m-d").'/';
			}
			create_folders('./'.$upload_path);
			
			//设置表头信息
			$tableInfo['title']=['A'=>'姓名','B'=>'年龄','C'=>'电话','D'=>'身份证号','E'=>'日期'];//表头
			$tableInfo['field']=['name','age','tel','number','datetime'];//数据库字段名称
			$json_data=$this->import_file->get_import_data($upload_path,$tableInfo,'excel_field_validation');
			
			$json_string = json_encode($json_data);
			echo $json_string;
			exit;
		}
		//下载导入模版文件
		public function downloadFile(){
			$file_path="./downloadFile/shop.xls";
			// echo $file_path;
			force_download($file_path, NULL);
		}
		
		public function test(){
			$code=200; //返回错误对象
			$msg="OK"; //返回
			$success=[];
			
			$page=$_GET['page'];//当前页码
			$limit=$_GET['limit'];//一页显示数量
			$like=[];//模糊搜索内容
			$where=[];//搜索条件
			
			if(!empty($_GET['name'])){
				$like['a.name']=$_GET['name'];
			}
			
				
			//开始查询
			$query=$this->db->select('a.*')
			->from("test as a")
			->like($like)
			// ->join('role r',"r.id=a.role_id",'left') //联合查询
			 ->where($where);
			$db = clone($this->db);  
			$count = $this->db->count_all_results();
			$this->db = $db;
			$query=$this->db->limit($limit, ($page-1)*$limit)
			->order_by('datetime DESC')->get();
			$data_list=$query->result_array();
			
			$pageTool= $count%$limit ==0 ? $count/$limit : ceil($count/$limit); //计算总页数
		
			
			$dataArr=[
				"count"=>$count,  //总条数					
				"pageTool"=>$pageTool,//总页数					
				"data"=>$data_list //数据
			];
			
			$success=$dataArr;
			$dataArr=array("code"=>$code,"msg"=>$msg,"data"=>$success);
			$json_string = json_encode($dataArr,true);
			echo $json_string;
			exit;
		}
		//导出数据
		public function export(){
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
			//提交时间
			if(!empty($_GET['submit_time'])){
				$tarr=str_date_time($_GET['submit_time']);
				$where['c.submit_time >=']=$tarr['s'];
				$where['c.submit_time <=']=$tarr['e'];
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
				'client_name'=>'客户名称',
				'client_tel'=>'客户电话',
				'b_name'=>'客户品牌',
				'v_version'=>'车辆型号',
				'deal_time'=>'成交时间',
				'deal_price'=>'成交价格',
				'a_name'=>'管理员',
				'business'=>'业务员'
			];
			print_r($data_list);
			//$file_name="客户列表.xls";
			//$this->import_file->export_data($data_list,$file_name,$table_title);
		}
		
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
		public function export_verification(){
			$code=200; //返回错误对象
			$msg="OK"; //返回
			$success=[];
			//print_r($_SERVER);
			if($_POST){
				$dname="import_export_".time();
				$success['name']=$dname;
				$this->session->set_userdata(['dname'=>$dname]);
				
				// $_SESSION['item'] = 'value';
				// $this->session->mark_as_flash('item');
			}else{
				$code=40001; 
				$msg="请求方式错误！"; 
			}
			
			$dataArr=array("code"=>$code,"msg"=>$msg,"data"=>$success);
			$json_string = json_encode($dataArr,true);
			echo $json_string;
			exit;
		} 
		
		
	}
?>