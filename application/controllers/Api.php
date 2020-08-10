<?php
	
	header('Access-Control-Allow-Origin:http://localhost:8080');
	header("Access-Control-Allow-Credentials: true" );
	header('Access-Control-Allow-Headers:Origin,Authorization,Content-Type');
	header('Access-Control-Allow-Methods:POST,GET,OPTIONS');

    class Api extends MY_Controller{
        
        public function __construct(){
            parent::__construct();
            $this->load->library(['session']);
            $this->load->helper(['url','common']);
			$this->apiVerification();
        }
		
		//菜单列表
		public function authList(){
			$code=200;
			$msg="OK";
			$success=[];
			$success=$this->getAuthList($_GET['userSID']);
			$dataArr=array("code"=>$code,"msg"=>$msg,"data"=>$success);
			$json_string = json_encode($dataArr,true);
			echo $json_string;
			exit;
		}
		//获取当前菜单的list
		public function getAuthList($id){
			
			$auth_id=$this->db->select('a.user_name,r.auth_id')
			->from("admin as a")
			->join('role r',"r.id=a.role_id",'left')
			->where(["a.id"=>$id])->get()->row_array();
			$admin_auth=[];
			if(!empty($auth_id['auth_id'])){
				$admin_auth=explode(",",$auth_id['auth_id']);
			}
			
			//获取所有菜单
			$auths=$this->db->where('parent_id', 0)->order_by('order', 'DESC')->get("auth")->result_array();
			$auth=$this->db->where_in('id', $admin_auth)->order_by('order', 'DESC')->get("auth")->result_array();
			$arr=$auth;
			//print_r($auth);
			//合并
			$auth=get_attr(array_merge_recursive($auths,$auth),0);
			
			$auth_list=array();
			foreach($auth as $key=>$item){
				if(!empty($item['children'])){
					array_push($auth_list,$auth[$key]);
				}
			}
			//获取站点信息
			$siteinfo=$this->db->select('name,title,record,phoneTel')->get_where('siteinfo',['id'=>1])->row_array();
			
			return [
				'operation'=>$arr,
				'auth_list'=>$auth_list,
				'siteinfo'=>$siteinfo
			];
			
		}
        //栏目列表
		public function auth(){
			$code=200;
			$msg="OK";
			
			$auth=get_attr($this->db->from('auth')
			->where('show',2)
			->order_by('order', 'desc')
			->get()
			->result_array(),0);
			
			$dataArr=array("code"=>$code,"msg"=>$msg,"data"=>$auth);
			$json_string = json_encode($dataArr,true);
			echo $json_string;
			exit;
		}
		//配置权限列表开发人员开放
		public function authAdd(){
			$code=200;
			$msg="OK";
			if($_POST){
				
				if($_POST['type']!="del"){
					$data=array(
						"name"=>$_POST["name"],
						"title"=>$_POST["title"],
						"order"=>$_POST["order"],
						"icon"=>$_POST["icon"]
					);
					
					if($_POST['type']=="add"){
						if(!empty($_POST['id'])){
							$data['parent_id']=$_POST['id'];
						}
						$state=$this->db->insert("auth",$data);
						$msg=$state ? "添加成功！" : "添加失败！";
						$code=$state ? 200: 40001;
					}
					
					if($_POST['type']==="edit"){
						$state=$this->db->where('id',$_POST['id'])->update('auth',$data);
						$msg=$state ? "修改成功！" : "修改失败！";
						$code=$state ? 200: 40001;
					}
				}else{
					$state=$this->db->delete('auth', array('id' => $_POST["id"])); 
					$msg=$state ? "删除成功！" : "删除失败！";
					$code=$state ? 200: 40001;
				}
			}else{
				$code=40001;
				$msg="请求方式不是POST";
			}
		
			$dataArr=array("code"=>$code,"msg"=>$msg);
			$json_string = json_encode($dataArr,true);
			echo $json_string;
			exit;
		}
		//角色列表
		public function role(){
			$code=200;
			$msg="OK";
			
			$role=$this->db->from('role')
			->where(['id !='=>"1"])
			->order_by('id', 'desc')
			->get()
			->result_array();
			
			$auth=$this->db->from('auth')
			->where('show',2)
			->order_by('order', 'desc')
			->get()
			->result_array();
			
			$auth=get_attr($auth,0);
			
			$dataArr=array("code"=>$code,"msg"=>$msg,"data"=>['role'=>$role,'auth'=>$auth]);
			$json_string = json_encode($dataArr,true);
			echo $json_string;
			exit;
		}
		//添加角色
		public function addRole(){
			$code=200;
			$msg="OK";
			$success=[];
			if($_POST){
				$data=[
					"role_name"=>$_POST['role_name'],
					"remark"=>$_POST['remark'],
					"auth_id"=>$_POST['auth_id']
				];
				
				$data['time']=unix_to_human(time(), TRUE, 'eu');
				$role_name=$this->db->get_where('role',['role_name'=>$data['role_name']])->row_array();
				if(empty($role_name)){
					$state=$this->db->insert("role",$data);
					$msg=$state ? "添加成功！" : "添加失败！";
					$code=$state ? 200: 40001;
				}else{
					$code=40001;
					$msg="角色名称已经存在，不能重复添加！";
				}
				$this->addLog('添加角色',$msg);	
				
			}else{
				$code=40001;
				$msg="请求方式不是POST";
			}
		
			$json_string = json_encode(["code"=>$code,"msg"=>$msg,"data"=>$success],true);
			echo $json_string;
			exit;
		}
		//编辑角色
		public function editRole(){
			$code=200;
			$msg="OK";
			$success=[];
			if($_POST){
				$data=[
					"role_name"=>$_POST['role_name'],
					"remark"=>$_POST['remark'],
					"auth_id"=>$_POST['auth_id']
				];
				$role_name=$this->db->get_where('role',['role_name'=>$data['role_name'],'id !='=>$_POST['id']])->row_array();
				if(empty($role_name)){
					$state=$this->db->where('id',$_POST['id'])->update('role',$data);
					$msg=$state ? "添加成功！" : "添加失败！";
					$code=$state ? 200: 40001;
				}else{
					$code=40001;
					$msg="角色名称已经存在，不能重复添加！";
				}
				$this->addLog('编辑角色',$msg);
			}else{
				$code=40001;
				$msg="请求方式不是POST";
			}
			$json_string = json_encode(["code"=>$code,"msg"=>$msg,"data"=>$success],true);
			echo $json_string;
			exit;
		}
		//删除角色
		public function delRole(){
			$code=200;
			$msg="OK";
			$success=[];
			if($_POST){
				$admin=$this->db->get_where('admin',['role_id'=>$_POST['id']])->row_array();
				if(empty($admin)){
					$state=$this->db->delete('role', array('id' => $_POST["id"]));
					$msg=$state ? "删除成功！" : "删除失败！";
					$code=$state ? 200: 40001;
				}else{
					$msg="当前角色绑定了管理员，不能删除！";
					$code=40001;
				}
				$this->addLog('删除角色',$msg);
			}else{
				$code=40001;
				$msg="请求方式不是POST";
			}
			$json_string = json_encode(["code"=>$code,"msg"=>$msg,"data"=>$success],true);
			echo $json_string;
			exit;
		}
		//管理员
		public function admin(){
			$code=200;
			$msg="OK";
			//权限列表页面
			$admin=$this->db->select('a.*,r.role_name')->from('admin as a')
			 ->join('role as r',"r.id=a.role_id",'left')
		//	->where(['a.id !='=>1,'a.type'=>"admin"])
			->where(['a.type'=>"admin"])
			->order_by('a.time DESC')
			->get()->result_array();
			foreach($admin as $key=>$item){
				$admin[$key]['headimg_url']=strUrl($item['headimg']);
			}
			//角色列表
			$role=$this->db->select('id,role_name')->from('role')
			->where(['id !='=>"1"])
			->order_by('id', 'desc')
			->get()
			->result_array();
				
			$dataArr=array("code"=>$code,"msg"=>$msg,"data"=>['admin'=>$admin,'role'=>$role]);
			$json_string = json_encode($dataArr,true);
			echo $json_string;
			exit;
		}
		//管理员修改信息
		public function setIinfo(){
			$code=200;
			$msg="OK";
			if($_POST){
				$admin=$this->db->get_where('admin',['id'=>$_POST['userSID']])->row_array();
				
				$data=[];
				if(!empty($_POST["vtype"])){
					if($_POST["vtype"]==='set'){
						if(!empty($_POST["password"])){
							if($admin['password']===md5($_POST["password"])){
								$data['password']=MD5($_POST["checkPass"]);
								$state=$this->db->where('id',$_POST['userSID'])->update('admin',$data);
								$msg=$state ? "修改成功！" : "修改失败！";
								$code=$state ? 200: 40001;
							}else{
								$msg="旧密码错误！";
								$code=40001;
							}
						}else{
							$msg="参数不能为空！";
							$code=40001;
						}
					}
					if($_POST["vtype"]==='img'){
						if(!empty($_POST['headimg'])){
							$data['headimg']=$_POST['headimg'];
							$state=$this->db->where('id',$_POST['userSID'])->update('admin',$data);
							$msg=$state ? "修改成功！" : "修改失败！";
							$code=$state ? 200: 40001;
						}else{
							$msg="参数不能为空！";
							$code=40001;
						}
					}
					
				}else{
					$msg="参数不能为空！";
					$code=40001;
				}
				
			}
			$dataArr=array("code"=>$code,"msg"=>$msg);
			$json_string = json_encode($dataArr,true);
			echo $json_string;
			exit;
		}
		//添加管理员
		public function addAdmin(){
			$success=[];
			$code=200;
			$msg="OK";
			if($_POST){
				$data=[
					"name"=>$_POST['name'],
					"user_name"=>$_POST['user_name'],
					"role_id"=>$_POST['role_id'],
					"tel"=>$_POST['tel'],
					"headimg"=>$_POST['headimg']
				];
				if($_POST['password']){
					$data['password']=MD5($_POST["password"]);
				}else{
					$data['password']=MD5("123456");
				}
				$user_name=$this->db->get_where('admin',['user_name'=>$data['user_name']])->row_array();
				if(empty($user_name)){
					$tel=$this->db->get_where('admin',['tel'=>$data['tel']])->row_array();	
					if(empty($tel)){
						$data['time']=unix_to_human(time(), TRUE, 'eu');
						$state=$this->db->insert("admin",$data);
						$msg=$state ? "添加成功！" : "添加失败！";
						$code=$state ? 200: 40001;
					}else{
						$msg="手机号不能重复使用，请使用其他手机号！";
						$code=40001;
					}
				}else{
					$msg="账号已经存在，请使用其他账号！";
					$code=40001;
				}	
				$this->addLog('添加管理员',$msg);
				
			}else{
				$code=40001;
				$msg="请求方式不是POST";
			}
			$json_string = json_encode(["code"=>$code,"msg"=>$msg,'data'=>$success],true);
			echo $json_string;
			exit;
		}
		//编辑管理员
		public function editAdmin(){
			$code=200;
			$msg="OK";
			$success=[];
			if($_POST){
				
				$data=[
					"name"=>$_POST['name'],
					"user_name"=>$_POST['user_name'],
					"role_id"=>$_POST['role_id'],
					"tel"=>$_POST['tel'],
					"headimg"=>$_POST['headimg']
				];
				if(!empty($_POST['password'])){
					$data['password']=MD5($_POST["password"]);
				}	
				$user_name=$this->db->get_where('admin',['user_name'=>$data['user_name'],'id !='=>$_POST['id']])->row_array();
				if(empty($user_name)){
					$tel=$this->db->get_where('admin',['tel'=>$data['tel'],'id !='=>$_POST['id']])->row_array();
					if(empty($tel)){
						$state=$this->db->where('id',$_POST['id'])->update('admin',$data);
						$msg=$state ? "修改成功！" : "修改失败！";
						$code=$state ? 200: 40001;
					}else{
						$msg="手机号不能重复使用，请使用其他手机号！";
						$code=40001;
					}
				}else{
					$msg="账号已经存在，请使用其他账号！";
					$code=40001;
				}
				$this->addLog('修改管理员',$msg);
			}else{
				$code=40001;
				$msg="请求方式不是POST";
			}		
		
			$json_string = json_encode(["code"=>$code,"msg"=>$msg,'data'=>$success],true);
			echo $json_string;
			exit;
		}
		//删除管理员
		public function delAdmin(){
			$code=200;
			$msg="OK";
			$success=[];
			
			if($_POST){
				$state=$this->db->delete('admin', array('id' => $_POST["id"]));
				$msg=$state ? "删除成功！" : "删除失败！";
				$code=$state ? 200: 40001;
				$this->addLog('删除管理员',$msg);
			}else{
				$code=40001;
				$msg="请求方式不是POST";
			}	
			
			$json_string = json_encode(["code"=>$code,"msg"=>$msg,'data'=>$success],true);
			echo $json_string;
			exit;
		}
		//登录操作
		public function login(){
			$code=200;
			$msg="OK";
			$success=[];	
			if($_POST){
				//验证码 strtolower不区分大小写
				if(!empty($_POST['code'])&&strtolower($_POST['code'])===strtolower($this->session->vcode)){
                 //echo $_POST['user_name'];//administrator administrat
					$user_name=$this->db->get_where('admin',['user_name'=>$_POST['user_name'],'type'=>'admin'])->row_array();
                 	//print_r($user_name);
					if(empty($user_name)){
						$user_name=$this->db->get_where('admin',['tel'=>$_POST['user_name'],'type'=>'admin'])->row_array();
					}
					
					if(!empty($user_name)){
						if($user_name['password']===md5($_POST['pass'])){
							$user_name['headimgUrl']=strUrl($user_name['headimg']);
							// print_r($user_name);
							// exit;
							$success=$this->getAuthList($user_name['id']); //获取用户操作菜单
							$success['userinfo']=$user_name; //用户信息
							
							$msg="登录成功！";
						}else{
							$code=40001;
							$msg="密码错误！";
						}
						$_POST['userSID']=$user_name['id'];
						$this->addLog('管理员登录',$msg);
					}else{
						$code=40001;
						$msg="账号不存在！";
					}
				}else{
					$code=40001;
					$msg="验证码错误！";
				}
			}else{
				$code=40001;
				$msg="请求方式不是POST";
			}
			$dataArr=array("code"=>$code,"msg"=>$msg,"data"=>$success);
			$json_string = json_encode($dataArr,true);
			echo $json_string;
			exit;
		}
		//后台信息接口
		public function getLog(){
			$code=200;
			$msg="OK";
			$success=[];
			//权限列表页面
			$success['admin_sum']=$this->db->count_all('admin'); 	//管理员数量	
			//用户数量
			$success['sum_user']=$this->db->count_all('users'); 	//用户数量
			//成交客户数
			//$dealc_sum=$this->db->select('count(distinct client_tel) as sum')->from('client')->where('deal_state','1')->get()->row_array();
			//成交车辆数
			//$client=$this->db->count_all_results('client');
			
			// $success['dealc_sum']=$dealc_sum['sum'];//成交客户数
			// $success['dealv_sum']=$client; //车辆数
			
			
			// $success['article_sum']=$this->db->count_all('article');  //文章总数
			//最新日志记录
			$success['log']=$this->db->select('l.*')->from('log as l')->order_by('l.time DESC')->limit(10)->get()->result_array();	
			$dataArr=array("code"=>$code,"msg"=>$msg,"data"=>$success);
			$json_string = json_encode($dataArr,true);
			echo $json_string;
			exit;
		}
		//操作日志记录
		public function log(){
			$code=200; //返回错误对象
			$msg="OK"; //返回
			$success=[];
			$page=$_GET['page'];//当前页码
			$limit=$_GET['limit'];//一页显示数量
			$like=[];//模糊搜索内容
			$where=[];//搜索条件
			
			if(!empty($_GET['name'])){
				$like['l.name']=$_GET['name'];
			}
			
			if(!empty($_GET['details'])){
				$like['l.details']=$_GET['details'];
			}
			if(!empty($_GET['time'])){
				$tarr=str_date_time($_GET['time']);
				print_r($tarr);
				$where['l.time >=']=$tarr['s'];
				$where['l.time <=']=$tarr['e'];
			}
				
			//开始查询
			$query=$this->db->select('l.*')
			->from("log as l")
			->like($like)
			// ->join('role r',"r.id=a.role_id",'left') //联合查询
			 ->where($where);
			$db = clone($this->db);  
			$count = $this->db->count_all_results();
			$this->db = $db;
			$query=$this->db->limit($limit, ($page-1)*$limit)
			->order_by('l.time DESC')->get();
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
		
		//删除操作日志
		public function dellog(){
			$code=200; //返回错误对象
			$msg="OK"; //返回
			$success=[];
			if($_POST){
				$state=$this->db->delete('log',['id' => $_POST["id"]]);
				$msg=$state ? "删除成功！" : "删除失败！";
				$code=$state ? 200: 40001;
				// $this->addLog('删除操作日志',$msg);
			}else{
				$msg="请求方式错误！";
				$code=40001;
			}
			$dataArr=array("code"=>$code,"msg"=>$msg,"data"=>$success);
			$json_string = json_encode($dataArr,true);
			echo $json_string;
			exit;
		}
		//网站设置
		public function setAdmin(){
			$code=200; //返回错误对象
			$msg="OK"; //返回
			$success=[];
		
			if($_SERVER['REQUEST_METHOD']==='GET'){
				$siteinfo=$this->db->select('name,title,record,phoneTel')->get_where('siteinfo',['id'=>1])->row_array();
				$success=$siteinfo;
			}
			if($_SERVER['REQUEST_METHOD']==='POST'){
				$data=[
					'title'=>$_POST['title'], //网站title名称
					'name'=>$_POST['name'],   //网站名称
					'record'=>$_POST['record'],       //网站备案号
					'phoneTel'=>$_POST['phoneTel']   
				];
				$state=$this->db->where('id',1)->update('siteinfo',$data);
				$msg=$state ? "删除成功！" : "删除失败！";
				$this->addLog('修改网站信息',$msg);
				$success=$data;
			}
			$dataArr=array("code"=>$code,"msg"=>$msg,"data"=>$success);
			$json_string = json_encode($dataArr,true);
			echo $json_string;
			exit;
		}
		
    }
?>