<?php
	/**
	* 后台所有功能接口
	* 后台提供数据接口开发 
	* @author webvdl 
	* @version 1.0 版本号
	*/ 
	
	header('Access-Control-Allow-Origin:http://localhost:8080');
	header("Access-Control-Allow-Credentials: true" );
	header('Access-Control-Allow-Headers:Origin,Authorization,Content-Type');
	header('Access-Control-Allow-Methods:POST,GET,OPTIONS');
	
    class ApiAll extends MY_Controller{
        public function __construct(){
            parent::__construct();
            $this->load->helper(['url','common']);
			$this->load->library(['import_file','session']);
			$this->apiVerification();
        }
		//请求清除未到岗消息记录
		public function setIcometMs(){
			$this->db->where('id','1')->update("icomet_msg",['msgnum'=>0]);	
			respond([],'',200);
		}
		//获取banner
		public function banner(){
			$code=200; //返回错误对象
			$msg="OK"; //返回
			$success=[];
			
			//开始查询
			$success=$this->db->select('b.*')
			->from("banner as b")->order_by('b.id DESC')->get()->result_array();
					
			$dataArr=["code"=>$code,"msg"=>$msg,"data"=>$success];
			$json_string = json_encode($dataArr,true);
			echo $json_string;
			exit;
		}
		//添加轮播
		public function addBanner(){
			$code=200; //返回错误对象
			$msg="OK"; //返回
			$success=[];
			
			$data=[
				"name"=>$_POST["name"],
				"bannerurl"=>$_POST["imgurl"],
				"content"=>$_POST["content"]
			];
			
			$state=$this->db->insert("banner",$data);
			$msg=$state ? "添加成功！" : "添加失败！";
			$code=$state ? 200: 40001;
			$this->addLog('添加banner',"《".$data['name']."》".$msg);
			
			$dataArr=["code"=>$code,"msg"=>$msg,"data"=>$success];
			$json_string = json_encode($dataArr,true);
			echo $json_string;
			exit;
		}
		//编辑轮播
		public function editBanner(){
			$code=200; //返回错误对象
			$msg="OK"; //返回
			$success=[];
			
			$data=[
				"name"=>$_POST["name"],
				"bannerurl"=>$_POST["imgurl"],
				"content"=>$_POST["content"]
			];
			
			$state=$this->db->where('id',$_POST['id'])->update('banner',$data);
	
			$msg=$state ? "编辑成功！" : "编辑失败！";
			$code=$state ? 200: 40001;
			$this->addLog('编辑banner',"《".$data['name']."》".$msg);
			
			$dataArr=["code"=>$code,"msg"=>$msg,"data"=>$success];
			$json_string = json_encode($dataArr,true);
			echo $json_string;
			exit;
		}
		//删除轮播	
		public function delBanner(){
			$code=200; //返回错误对象
			$msg="OK"; //返回
			$success=[];
			
			$state=$this->db->delete('banner',['id' => $_POST["id"]]);
				
			$msg=$state ? "删除成功！" : "删除失败！";
			$code=$state ? 200: 40001;
			$this->addLog('删除banner',"《".$_POST['name']."》".$msg);
			
			$dataArr=["code"=>$code,"msg"=>$msg,"data"=>$success];
			$json_string = json_encode($dataArr,true);
			echo $json_string;
			exit;
			
		}
		//编辑图文信息
		public function imgtext(){
			
			if($_SERVER['REQUEST_METHOD']==='GET'){
				//开始查询
				$success=$this->db->select('*')->from("siteinfo")->get()->row_array();	
				respond($success,'加载成功',200);
			}
			if($_SERVER['REQUEST_METHOD']==='POST'){
				$data=[
					'imgPoster'=>$_POST['imgPoster'], 
					// 'homeimg'=>$_POST['homeimg'],  
					'hometext'=>$_POST['hometext'],       
					'gywmtext'=>$_POST['gywmtext'],   
					'gmxztext'=>$_POST['gmxztext']
				];
				
				$state=$this->db->where('id',1)->update('siteinfo',$data);
				$msg=$state ? "设置成功！" : "设置失败！";
				$this->addLog('图文设置',$msg);
				respond($state,$msg,200);
			}
			
		}
		//基本信息设置
		public function setBasics(){
			//如果是post提交信息
			if($_SERVER['REQUEST_METHOD']==='POST'){
				$data=[
					'phoneTel'=>$_POST['phoneTel'], 
					'shareImgurl'=>$_POST['shareImgurl'], 
					'shareName'=>$_POST['shareName'],
					'servicePrice'=>$_POST['servicePrice'], //收取比例
					'timeLimit'=>$_POST['timeLimit'],    //确实时间
					'cancelTime'=>$_POST['cancelTime'],   //取消订单时间
					'cancelTimes'=>$_POST['cancelTimes']   //取消次数
				];
				$state=$this->db->where('id',1)->update('siteinfo',$data);
				$msg=$state ? "设置成功！" : "设置失败！";
				$this->addLog('基本信息设置',$msg);
				respond([],$msg,200);
			}
			
			if($_SERVER['REQUEST_METHOD']==='GET'){
				//开始查询
				$data=$this->db->select('*')->from("siteinfo")->get()->row_array();	
				
				respond([
					'phoneTel'=>$data['phoneTel'],
					'shareImgurl'=>$data['shareImgurl'],
					'shareName'=>$data['shareName'],
					'servicePrice'=>$data['servicePrice'], //收取比例
					'timeLimit'=>$data['timeLimit'],    //确实时间
					'cancelTime'=>$data['cancelTime'],   //取消订单时间
					'cancelTimes'=>$data['cancelTimes']   //取消次数
				],'加载成功',200);
			}
		}
		//营销设置
		public function marketing(){
			
			if($_SERVER['REQUEST_METHOD']==='POST'){
				$data=[
					'mCOff'=>$_POST['mCOff'],
					'mCfirstCS'=>$_POST['mCfirstCS'],
					'mCfirstCSmin'=>$_POST['mCfirstCSmin'],
					'mCfirstCSmax'=>$_POST['mCfirstCSmax'], 
					'mWOff'=>$_POST['mWOff'],   
					'mWfXCS'=>$_POST['mWfXCS'],  
					'mWDJCS'=>$_POST['mWDJCS']   
				];
				$state=$this->db->where('id',1)->update('siteinfo',$data);
				$msg=$state ? "设置成功！" : "设置失败！";
				$this->addLog('营销设置',$msg);
				respond([],$msg,200);
				
			}
			
			if($_SERVER['REQUEST_METHOD']==='GET'){
				//开始查询
				$data=$this->db->select('*')->from("siteinfo")->get()->row_array();	
				respond([
					'mCOff'=>$data['mCOff'],
					'mCfirstCS'=>$data['mCfirstCS'],
					'mCfirstCSmin'=>$data['mCfirstCSmin'],
					'mCfirstCSmax'=>$data['mCfirstCSmax'], 
					'mWOff'=>$data['mWOff'],   
					'mWfXCS'=>$data['mWfXCS'],  
					'mWDJCS'=>$data['mWDJCS']   
				],'加载成功',200);
			}
			
		}
	    // 获取列表用户数据
		public function userList(){
			$page=$_GET['page'];//当前页码
			$limit=$_GET['limit'];//一页显示数量
			$like=[];//模糊搜索内容
			$where=[];//搜索条件
			//昵称
			if(!empty($_GET['nickname'])){
				$like['u.nickname']=$_GET['nickname'];
			}
			//姓名
			if(!empty($_GET['name'])){
				$like['u.name']=$_GET['name'];
			}
			//电话
			if(!empty($_GET['tel'])){
				$like['u.tel']=$_GET['tel'];
			}
			//身份证
			if(!empty($_GET['cardid'])){
				$like['u.cardid']=$_GET['cardid'];
			}
			//状态
			if(!empty($_GET['cwstate'])||$_GET['cwstate']=='0'){
				$where['u.cwstate']=$_GET['cwstate'];
			}
			
			//注册时间
			if(!empty($_GET['datetime'])){
				$tarr=str_date_time($_GET['datetime']);
				$where['u.datetime >=']=$tarr['s'];
				$where['u.datetime <=']=$tarr['e'];
			}
			//开始查询
			$query=$this->db->select('u.audit,u.nickname,u.id,u.tel,u.datetime,u.headimg,u.name,u.age,u.cardid,u.cwstate,u.birthday,u.type_p,u.type_s,u.card_img_z,u.card_img_f')
			->from("users as u")
			->like($like)
			// ->join('role r',"r.id=a.role_id",'left') //联合查询
			 ->where($where);
			$db = clone($this->db);  
			$count = $this->db->count_all_results();
			$this->db = $db;
			$query=$this->db->limit($limit, ($page-1)*$limit)
			->order_by('u.datetime DESC')->get();
			$data_list=$query->result_array();
			//计算总页数
			$pageTool= $count%$limit ==0 ? $count/$limit : ceil($count/$limit); //计算总页数
			
			foreach($data_list as $key=>$item){
				
				$data_list[$key]['cstatet']=$item['cwstate']==='2'&&$item['audit']==='1' ? '是' : '否'; 
				$data_list[$key]['wstatet']=$item['cwstate']==='1'&&$item['audit']==='1' ? '是' : '否'; 
				
				$data_list[$key]['gztype']=$item['cwstate']==='1'||$item['cwstate']==='2' ? '1' : '0'; 
				// 计算年龄
				$data_list[$key]['age']=birthday($item['birthday']); 
				
			}
			$success=[
				"count"=>$count,  //总条数					
				"pageTool"=>$pageTool,//总页数					
				"data"=>$data_list //数据
			];
			
			respond($success,'加载成功！',200);
		}
		
		//设置为工人
		public function worker(){
			if($_SERVER['REQUEST_METHOD']==='POST'){
				$data=[
					"name"=>$_POST['name'],  //姓名
					"cardid"=>$_POST['cardid'], //身份证号
					"birthday"=>$_POST['birthday'], //出生年月日
					"type_p"=>$_POST['type_p'], //工种父级
					"type_s"=>$_POST['type_s'], //工种子级
					"card_img_z"=>$_POST['card_img_z'], //正面
					"card_img_f"=>$_POST['card_img_f'], //反面
					"audit"=>"1", //审核状态
					"cwstate"=>"1"
				];
				
				
				$cardid=$this->db->get_where('users',['cardid'=>$_POST['cardid'],'id !='=>$_POST['id']])->row_array();
				
				if(empty($cardid)){
					
					if(birthday($_POST['birthday'])<18||birthday($_POST['birthday'])>60){
						respond([],'工人年龄不可低于18或者高于60岁',40001);
					}else{
						$state=$this->db->where('id',$_POST['id'])->update('users',$data);
						$msg=$state ? "设置成功！" : "设置失败！";
						$this->addLog('用户设为工人',$msg);
						respond($state,$msg,200);
					}
				}else{
					respond([],"身份证号已经被使用过！",40001);
				}
			}
			
			if($_SERVER['REQUEST_METHOD']==='GET'){
				$data=$this->getTypeWork($_GET['parent_id']);
				respond($data,'加载成功！',200);
			}
		}
		//获取工种
		public function getTypeWork($parent_id){
			$typeWork=$this->db->select('id as value,name as text')->order_by('sort DESC')->get_where('type_work',['parent_id'=>$parent_id])->result_array();
			return $typeWork;
		}
		//撤销为工人
		public function rWorker(){
			if(!empty($_POST['isid'])){
				$arr=explode(',',$_POST['isid']);
				$data=[];
				foreach($arr as $key=>$item){
					array_push($data,['id'=>$item,'cwstate'=>'0','revocation'=>'1']);
				}
				$state=$this->db->update_batch('users', $data, 'id');
				$msg=$state ? "撤销成功！" : "撤销失败！";
				$this->addLog('撤销工人身份',$msg);
				respond($state,$msg,200);
			}
			
		}
		//设置为包工
		public function contractor(){
			$data=[
				"name"=>$_POST['name'],  //姓名
				"cardid"=>$_POST['cardid'], //身份证号
				"birthday"=>$_POST['birthday'], //出生年月日
				"card_img_z"=>$_POST['card_img_z'], //正面
				"card_img_f"=>$_POST['card_img_f'], //反面
				"audit"=>"1", //审核状态
				"cwstate"=>"2"
			];
			//print_r($data);
			$cardid=$this->db->get_where('users',['cardid'=>$_POST['cardid'],'id !='=>$_POST['id']])->row_array();
			if(empty($cardid)){
				$state=$this->db->where('id',$_POST['id'])->update('users',$data);
				$msg=$state ? "设置成功！" : "设置失败！";
				$this->addLog('用户设为包工',$msg);
				respond($state,$msg,200);
			}else{
				respond([],"身份证号已经被使用过！",40001);
			}
			
		}
		//撤销为包工
		public function rContractor(){
			if(!empty($_POST['isid'])){
				$arr=explode(',',$_POST['isid']);
				$data=[];
				foreach($arr as $key=>$item){
					array_push($data,['id'=>$item,'cwstate'=>'0','revocation'=>'1']);
				}
				$state=$this->db->update_batch('users', $data, 'id');
				$msg=$state ? "撤销成功！" : "撤销失败！";
				$this->addLog('撤销包工身份',$msg);
				respond($state,$msg,200);
			}
		}
		
		// 工种列表
		public function speciesList(){
			$typeWork=$this->db->order_by('sort DESC')->get_where('type_work')->result_array();
			respond(get_attr($typeWork,0),'加载成功！',200);
		}
		// 添加工种
		public function addSpecies(){
			$data=[
				"name"=>$_POST['name'],
				"price"=>$_POST['price'],
				"sort"=>$_POST['sort'],
				"parent_id"=>$_POST['parent_id'],
				"datetime"=>unix_to_human(time(), TRUE, 'eu')
			];
			$typeWork=$this->db->get_where('type_work',['name'=>$_POST['name'],'parent_id'=>$_POST['parent_id']])->row_array();
			if(empty($typeWork)){
				$state=$this->db->insert("type_work",$data);
				$msg=$state ? "添加成功！" : "添加失败！";
				$code=200;
			}else{
				$msg="同级目录工种名称不能重复！";
				$state=false;
				$code=40001;
			}
			$this->addLog('添加工种',$msg);
			respond($state,$msg,$code);
		}
		// 编辑工种
		public function editSpecies(){
			$data=[
				"name"=>$_POST['name'],
				"price"=>$_POST['price'],
				"sort"=>$_POST['sort']
			];
			
			$typeWork=$this->db->get_where('type_work',['name'=>$_POST['name'],'id !='=>$_POST['id'],'parent_id'=>$_POST['parent_id']])->row_array();
			if(empty($typeWork)){
				$state=$this->db->where('id',$_POST['id'])->update("type_work",$data);
				$msg=$state ? "修改成功！" : "修改失败！";
				$code=200;
			}else{
				$msg="同级目录工种名称不能重复！";
				$state=false;
				$code=40001;
			}
			$this->addLog('修改工种',$msg);
			respond($state,$msg,$code);
		}
		// 删除工种
		public function delSpecies(){
		
			$typeWork=$this->db->get_where('type_work',['parent_id'=>$_POST['id']])->row_array();
			if(empty($typeWork)){
				$typeWork=$this->db->get_where('users',['type_p'=>$_POST['id']])->row_array();
				$typeWorks=$this->db->get_where('users',['type_s'=>$_POST['id']])->row_array();
				if(empty($typeWork)&&empty($typeWorks)){
					$state=$this->db->delete('type_work',['id' => $_POST["id"]]);
					$msg=$state ? "删除成功！" : "删除失败！";
					$code=200;
				}else{
					$msg="该工种已绑定工人，不能直接删除！";
					$state=false;
					$code=40001;
				}
			}else{
				$msg="该工种包含了子类，不能删除直接删除，请先删除子类目录！";
				$state=false;
				$code=40001;
			}
			
			$this->addLog('删除工种',$msg);
			respond($state,$msg,$code);
		}
		// 设置工种地区价格
		public function setSpecies(){
			
			if($_SERVER['REQUEST_METHOD']==='GET'){
				
				$data=$this->db->get_where('type_work',['id'=>$_GET['id']])->row_array();
				if(!empty($data['areaPrice'])){
					$parseJson = json_decode($data['areaPrice'],true);
				}else{
					$parseJson=[];
				}
				respond($parseJson,'加载成功',200);
			}
			
			
			if($_SERVER['REQUEST_METHOD']==='POST'){
				$data=[
					'areaPrice'=>$_POST['listdata']
				];
				$state=$this->db->where('id',$_POST['id'])->update("type_work",$data);
				$msg=$state ? "设置成功！" : "设置失败！";
				$code=200;
				$this->addLog('工种地区价格',$msg);
				respond($state,$msg,$code);
				
			}
			
		}
		//用户详情数据userDetails
		public function userDetails(){
			$page=$_GET['page'];//当前页码
			$limit=$_GET['limit'];//一页显示数量
			$like=[];//模糊搜索内容
			$where=[];//搜索条件
			
			//开始查询
			$query=$this->db->select('u.nickname,u.headimg,u.datetime')
			->from("userchild as us")
			->like($like)
			->join('users as u',"u.id=us.child_user_id",'left') //工种
			 ->where(['us.user_id'=>$_GET['id']]);
			$db = clone($this->db);  
			$count = $this->db->count_all_results();
			$this->db = $db;
			$query=$this->db->limit($limit, ($page-1)*$limit)
			->order_by('u.datetime DESC')->get();
			$data_list=$query->result_array();
			//计算总页数
			$pageTool= $count%$limit ==0 ? $count/$limit : ceil($count/$limit); //计算总页数
		
			$success=[
				"count"=>$count,  //总条数					
				"pageTool"=>$pageTool,//总页数					
				"data"=>$data_list//数据
			];		
			respond($success,'',200);
		}
		
		
		//工人列表
		public function workersList(){
			$page=$_GET['page'];//当前页码
			$limit=$_GET['limit'];//一页显示数量
			$like=[];//模糊搜索内容
			$where=[];//搜索条件
			//昵称
			if(!empty($_GET['nickname'])){
				$like['u.nickname']=$_GET['nickname'];
			}
			//姓名
			if(!empty($_GET['name'])){
				$like['u.name']=$_GET['name'];
			}
			//电话
			if(!empty($_GET['tel'])){
				$like['u.tel']=$_GET['tel'];
			}
			//身份证
			if(!empty($_GET['cardid'])){
				$like['u.cardid']=$_GET['cardid'];
			}
			//注册时间
			if(!empty($_GET['datetime'])){
				$tarr=str_date_time($_GET['datetime']);
				$where['u.datetime >=']=$tarr['s'];
				$where['u.datetime <=']=$tarr['e'];
			}
			//生日
			if(!empty($_GET['birthday'])){
				$tarr=str_date_time($_GET['birthday']);
				$where['u.birthday >=']=$tarr['s'];
				$where['u.birthday <=']=$tarr['e'];
			}
			//市
			if(!empty($_GET['city'])){
				$like['u.city']=$_GET['city'];
			}
			//审核状态
			if(!empty($_GET['audit'])){
				$where['u.audit']=$_GET['audit'];
			}
			//冻结状态
			if(!empty($_GET['state'])||$_GET['state']=='0'){
				$where['u.state']=$_GET['state'];
			}
			//工种
			if(!empty($_GET['type_p'])){
				$where['u.type_p']=$_GET['type_p'];
			}
			if(!empty($_GET['type_s'])){
				$where['u.type_s']=$_GET['type_s'];
			}
			//状态
			$where['u.cwstate']="1";
			
			
			//开始查询
			$query=$this->db->select('u.nickname,tw.name as type_p_text,tws.name as type_s_text,u.id,u.state,u.audit,u.province,u.city,u.area,u.tel,u.datetime,u.headimg,u.name,u.age,u.cardid,u.cwstate,u.birthday,u.type_p,u.type_s,u.card_img_z,u.card_img_f')
			->from("users as u")
			->like($like)
			->join('type_work tw',"tw.id=u.type_p",'left') //工种
			->join('type_work tws',"tws.id=u.type_s",'left') //工种
			 ->where($where);
			$db = clone($this->db);  
			$count = $this->db->count_all_results();
			$this->db = $db;
			$query=$this->db->limit($limit, ($page-1)*$limit)
			->order_by('u.datetime DESC')->get();
			$data_list=$query->result_array();
			//计算总页数
			$pageTool= $count%$limit ==0 ? $count/$limit : ceil($count/$limit); //计算总页数
			
			foreach($data_list as $key=>$item){
				$data_list[$key]['cstatet']=$item['cwstate']==='2' ? '是' : '否'; 
				$data_list[$key]['wstatet']=$item['cwstate']==='1' ? '是' : '否'; 
				$data_list[$key]['gztype']=$item['cwstate']==='1'||$item['cwstate']==='2' ? '1' : '0'; 
				// 计算年龄
				$data_list[$key]['age']=birthday($item['birthday']); 
				//工种
				$data_list[$key]['typetext']=$item['type_p_text'].'-'.$item['type_s_text']; 
				//地区拼接
				$data_list[$key]['province_city']=$item['city']; 
				//审核状态1通过，2未通过，3待审核
				if($item['audit']=='1') $data_list[$key]['audittext']="已通过";
				if($item['audit']=='2') $data_list[$key]['audittext']="未通过";
				if($item['audit']=='3') $data_list[$key]['audittext']="待审核";
				//冻结状态
				$data_list[$key]['statetext']=$item['state']==='1' ? '正常' : '已冻结'; 
				//计算接单数量
				$data_list[$key]['num']='0';
				//计算接单数量
				$data_list[$key]['staynum']='0';
				
			}
			$typeWork=$this->db->select('id,name,parent_id')->order_by('sort DESC')->get_where('type_work')->result_array();
			//print_r($typeWork);
			$success=[
				"count"=>$count,  //总条数					
				"pageTool"=>$pageTool,//总页数					
				"data"=>$data_list, //数据
				"typeWork"=>[]  //工种
			];
			if(!empty($typeWork)){
				$success['typeWork']=get_attr($typeWork,0);
			}
			//获取工种
			respond($success,'加载成功！',200);
		}
		//工人审核
		public function auditWorkers(){
			$arr=explode(',',$_POST['isid']);
			$data=[];
			$off=true;
			foreach($arr as $key=>$item){
				$users=$this->db->select('tel,audit')->get_where('users',['id'=>$item])->row_array();
				if($users['audit']==='3'){
					if($_POST['audit']=='1'){
						$this->toSmsNotify($users['tel'],'4');
					}
					if($_POST['audit']=='2'){
						$this->toSmsNotify($users['tel'],'5');
					}
					array_push($data,['id'=>$item,'audit'=>$_POST['audit']]);
				}else{
					$off=false;
				}
			}
			
			if($off){
				$state=$this->db->update_batch('users', $data, 'id');
				$msg=$state ? "操作成功！" : "操作失败！";
				$this->addLog('审核工人',$msg);
				respond($state,$msg,200);
			}else{
				respond([],'有已经审核过的用户，不能重复审核',40001);
			}
		
			
		}
		//冻结解冻
		public function frozenThaw(){
			$arr=explode(',',$_POST['isid']);
			$data=[];
			
			if($_POST['state']==='1'){
				foreach($arr as $key=>$item){
					array_push($data,['id'=>$item,'state'=>'1']);
				}
			}
			
			if($_POST['state']==='0'){
				foreach($arr as $key=>$item){
					array_push($data,['id'=>$item,'state'=>'0']);
				}
			}
			$state=$this->db->update_batch('users', $data, 'id');
			$msg=$state ? "操作成功！" : "操作失败！";
			$this->addLog('工人冻结/解冻',$msg);
			respond($state,$msg,200);
		}
		//编辑
		public function editWorkers(){
			$data=[
				"name"=>$_POST['name'],  //姓名
				"cardid"=>$_POST['cardid'], //身份证号
				"birthday"=>$_POST['birthday'], //出生年月日
				"type_p"=>$_POST['type_p'], //工种父级
				"type_s"=>$_POST['type_s'], //工种子级
				"card_img_z"=>$_POST['card_img_z'], //正面
				"card_img_f"=>$_POST['card_img_f'], //反面
				"audit"=>"1" //审核状态
			];
			$cardid=$this->db->get_where('users',['cardid'=>$_POST['cardid'],'id !='=>$_POST['id']])->row_array();
			
			if(empty($cardid)){
				
				if(birthday($_POST['birthday'])<18||birthday($_POST['birthday'])>60){
					respond([],'工人年龄不可低于18或者高于60岁',40001);
				}else{
					$state=$this->db->where('id',$_POST['id'])->update('users',$data);
					$msg=$state ? "修改成功！" : "修改失败！";
					$this->addLog('修改工人',$msg);
					respond($state,$msg,200);
				}
			}else{
				respond([],"身份证号已经被使用过！",40001);
			}
			
		}
		
		
		//包工列表
		public function contractorList(){
			$page=$_GET['page'];//当前页码
			$limit=$_GET['limit'];//一页显示数量
			$like=[];//模糊搜索内容
			$where=[];//搜索条件
			//昵称
			if(!empty($_GET['nickname'])){
				$like['u.nickname']=$_GET['nickname'];
			}
			//姓名
			if(!empty($_GET['name'])){
				$like['u.name']=$_GET['name'];
			}
			//电话
			if(!empty($_GET['tel'])){
				$like['u.tel']=$_GET['tel'];
			}
			//身份证
			if(!empty($_GET['cardid'])){
				$like['u.cardid']=$_GET['cardid'];
			}
			//注册时间
			if(!empty($_GET['datetime'])){
				$tarr=str_date_time($_GET['datetime']);
				$where['u.datetime >=']=$tarr['s'];
				$where['u.datetime <=']=$tarr['e'];
			}
			//生日
			// if(!empty($_GET['birthday'])){
			// 	$tarr=str_date_time($_GET['birthday']);
			// 	$where['u.birthday >=']=$tarr['s'];
			// 	$where['u.birthday <=']=$tarr['e'];
			// }
			//市
			if(!empty($_GET['city'])){
				$where['u.city']=$_GET['city'];
			}
			//审核状态
			if(!empty($_GET['audit'])){
				$where['u.audit']=$_GET['audit'];
			}
			//冻结状态
			if(!empty($_GET['state'])||$_GET['state']=='0'){
				$where['u.state']=$_GET['state'];
			}
			//工种
			// if(!empty($_GET['type_p'])){
			// 	$like['u.type_p']=$_GET['type_p'];
			// }
			// if(!empty($_GET['type_s'])){
			// 	$like['u.type_s']=$_GET['type_s'];
			// }
			//状态
			$where['u.cwstate']="2";
			
			
			//开始查询
			$query=$this->db->select('u.nickname,tw.name as type_p_text,tws.name as type_s_text,u.id,u.state,u.audit,u.province,u.city,u.area,u.tel,u.datetime,u.headimg,u.name,u.age,u.cardid,u.cwstate,u.birthday,u.type_p,u.type_s,u.card_img_z,u.card_img_f')
			->from("users as u")
			->like($like)
			->join('type_work tw',"tw.id=u.type_p",'left') //工种
			->join('type_work tws',"tws.id=u.type_s",'left') //工种
			 ->where($where);
			$db = clone($this->db);  
			$count = $this->db->count_all_results();
			$this->db = $db;
			$query=$this->db->limit($limit, ($page-1)*$limit)
			->order_by('u.datetime DESC')->get();
			$data_list=$query->result_array();
			//计算总页数
			$pageTool= $count%$limit ==0 ? $count/$limit : ceil($count/$limit); //计算总页数
			
			foreach($data_list as $key=>$item){
				$data_list[$key]['cstatet']=$item['cwstate']==='2' ? '是' : '否'; 
				$data_list[$key]['wstatet']=$item['cwstate']==='1' ? '是' : '否'; 
				$data_list[$key]['gztype']=$item['cwstate']==='1'||$item['cwstate']==='2' ? '1' : '0'; 
				// 计算年龄
				$data_list[$key]['age']=birthday($item['birthday']); 
				//工种
				$data_list[$key]['typetext']=$item['type_p_text'].'-'.$item['type_s_text']; 
				//地区拼接
				$data_list[$key]['province_city']=$item['city']; 
				//审核状态1通过，2未通过，3待审核
				if($item['audit']=='1') $data_list[$key]['audittext']="已通过";
				if($item['audit']=='2') $data_list[$key]['audittext']="未通过";
				if($item['audit']=='3') $data_list[$key]['audittext']="待审核";
				//冻结状态
				$data_list[$key]['statetext']=$item['state']==='1' ? '正常' : '已冻结'; 
				//计算接单数量
				$data_list[$key]['num']='0';
				//计算接单数量
				$data_list[$key]['staynum']='0';
				
			}
			$typeWork=$this->db->select('id,name,parent_id')->order_by('sort DESC')->get_where('type_work')->result_array();
			//print_r($typeWork);
			$success=[
				"count"=>$count,  //总条数					
				"pageTool"=>$pageTool,//总页数					
				"data"=>$data_list, //数据
				"typeWork"=>[]  //工种
			];
			if(!empty($typeWork)){
				$success['typeWork']=get_attr($typeWork,0);
			}
			//获取工种
			respond($success,'加载成功！',200);
		}
		//审核
		public function auditContractor(){
			$arr=explode(',',$_POST['isid']);
			$data=[];
			$off=true;
			foreach($arr as $key=>$item){
				$users=$this->db->select('tel,audit')->get_where('users',['id'=>$item])->row_array();
				if($users['audit']=='3'){
					if($_POST['audit']=='1'){
						$this->toSmsNotify($users['tel'],'6');
					}
					if($_POST['audit']=='2'){
						$this->toSmsNotify($users['tel'],'7');
					}
					array_push($data,['id'=>$item,'audit'=>$_POST['audit']]);
				}else{
					$off=false;
				}
			}
			if($off){
				$state=$this->db->update_batch('users', $data, 'id');
				$msg=$state ? "操作成功！" : "操作失败！";
				$this->addLog('审核包工',$msg);
				respond($state,$msg,200);
			}else{
				respond([],'有已经审核过的用户不能重复审核！',40001);
			}
			
		}
		//冻结/解冻
		public function frozenThawC(){
			$arr=explode(',',$_POST['isid']);
			$data=[];
			
			if($_POST['state']==='1'){
				foreach($arr as $key=>$item){
					array_push($data,['id'=>$item,'state'=>'1']);
				}
			}
			
			if($_POST['state']==='0'){
				foreach($arr as $key=>$item){
					array_push($data,['id'=>$item,'state'=>'0']);
				}
			}
			$state=$this->db->update_batch('users', $data, 'id');
			$msg=$state ? "操作成功！" : "操作失败！";
			$this->addLog('包工冻结/解冻',$msg);
			respond($state,$msg,200);
		}
		//编辑
		public function editContractor(){
			$data=[
				"name"=>$_POST['name'],  //姓名
				"cardid"=>$_POST['cardid'], //身份证号
				"birthday"=>$_POST['birthday'], //出生年月日
				"card_img_z"=>$_POST['card_img_z'], //正面
				"card_img_f"=>$_POST['card_img_f'], //反面
				"audit"=>"1" //审核状态
			];
			//print_r($data);
			$cardid=$this->db->get_where('users',['cardid'=>$_POST['cardid'],'id !='=>$_POST['id']])->row_array();
			if(empty($cardid)){
				$state=$this->db->where('id',$_POST['id'])->update('users',$data);
				$msg=$state ? "修改成功！" : "修改失败！";
				$this->addLog('编辑包工',$msg);
				respond($state,$msg,200);
			}else{
				respond([],"身份证号已经被使用过！",40001);
			}
		}
		//详情
		//seeContractor
		
		// 订单列表
		public function orderList(){
			$page=$_GET['page'];//当前页码
			$limit=$_GET['limit'];//一页显示数量
			$like=[];//模糊搜索内容
			$where=[];//搜索条件
		
			
			//工人名称
			if(!empty($_GET['uw_name'])){
				$like['uw.name']=$_GET['uw_name'];
			}
			//工人电话
			if(!empty($_GET['uw_tel'])){
				$like['uw.tel']=$_GET['uw_tel'];
			}
			//包工名称
			if(!empty($_GET['uc_name'])){
				$like['uc.name']=$_GET['uc_name'];
			}
			//包工电话
			if(!empty($_GET['uc_tel'])){
				$like['uc.tel']=$_GET['uc_tel'];
			}
			
			//完成时间
			if(!empty($_GET['datetime_wc'])){
				$tarr=str_date_time($_GET['datetime_wc']);
				$where['o.datetime_wc >=']=$tarr['s'];
				$where['o.datetime_wc <=']=$tarr['e'];
			}
			//下单时间
			if(!empty($_GET['datetime_xd'])){
				$tarr=str_date_time($_GET['datetime_xd']);
				$where['o.datetime_xd >=']=$tarr['s'];
				$where['o.datetime_xd <=']=$tarr['e'];
			}
			//接单时间
			if(!empty($_GET['datetime_jd'])){
				$tarr=str_date_time($_GET['datetime_jd']);
				$where['o.datetime_jd >=']=$tarr['s'];
				$where['o.datetime_jd <=']=$tarr['e'];
			}
			//市
			// 地址
			if(!empty($_GET['city'])){
				$like['o.address']=$_GET['city'];
			}
			//订单状态
			$or_where=[];
			if(!empty($_GET['status'])){
				$status=$_GET['status'];
				if($status=='7-2'){
					$status="7";
					$where['o.state_wdg']='2';
				}
				
				if($status=='2-2'){
					$status="2";
					$where['o.cancel_type']='2';
				}
				if($status=='2-3'){
					$status="2";
					$where['o.cancel_type']='3';
				}
				$where['o.status']=$status;
			}
			//工种
			if(!empty($_GET['type_p'])){
				$where['uw.type_p']=$_GET['type_p'];
			}
			if(!empty($_GET['type_s'])){
				$where['uw.type_s']=$_GET['type_s'];
			}
			//orderList
			//开始查询
			$query=$this->db->select('o.*,utw.name as type_p_text,utws.name as type_s_text,uw.birthday,uw.name as uw_name,uw.tel as uw_tel,uc.name as uc_name,uc.tel as uc_tel')
			->from("order as o")
			->like($like)
			->join('users as uw',"uw.id=o.work_id",'left') //工人
			->join('users as uc',"uc.id=o.contractor_id",'left') //包工
			->join('type_work utw',"utw.id=uw.type_p",'left') //工人工种
			->join('type_work utws',"utws.id=uw.type_s",'left') //包工工种
			// ->join('type_work tw',"tw.id=o.type_p",'left') //工种
			// ->join('type_work tws',"tws.id=o.type_s",'left') //工种
			 ->where($where)->or_where($or_where);
			$db = clone($this->db);  
			$count = $this->db->count_all_results();
			$this->db = $db;
			$query=$this->db->limit($limit, ($page-1)*$limit)
			->order_by('o.datetime_xd DESC')->get();
			$data_list=$query->result_array();
			//计算总页数
			$pageTool= $count%$limit ==0 ? $count/$limit : ceil($count/$limit); //计算总页数
			
			foreach($data_list as $key=>$item){
				// 计算年龄
				$data_list[$key]['age']=birthday($item['birthday']); 
				// //地区拼接
				$data_list[$key]['province_city']=$item['province'].$item['city']; 
				// //审核状态
				$status=$this->stateText($item['status']);
				if($status=='已取消'){
					if($item['cancel_type']==='2'){
						$status="包工已取消";
					}
					if($item['cancel_type']==='3'){
						$status="平台已取消";
					}
				}
				if($status=='服务中'){
					if($item['state_wdg']==='2'){
						$status="服务中(未到岗)";
					}
				}
				$data_list[$key]['status_text']=$status;
			}
			$typeWork=$this->db->select('id,name,parent_id')->order_by('sort DESC')->get_where('type_work')->result_array();
			//print_r($typeWork);
			
			$where['o.status !=']="2";
			$content=$this->db->select('sum(service_price) as server_price_content')
			->from("order as o")
			->like($like)
			->join('users as uw',"uw.id=o.work_id",'left') //工人
			->join('users as uc',"uc.id=o.contractor_id",'left') //包工
			->join('type_work utw',"utw.id=uw.type_p",'left') //工人工种
			->join('type_work utws',"utws.id=uw.type_s",'left') //包工工种
			 ->where($where)->get()->row_array();
			 
			 
			$success=[
				"server_price_content"=>$content['server_price_content'],
				"count"=>$count,  //总条数					
				"pageTool"=>$pageTool,//总页数					
				"data"=>$data_list, //数据
				"typeWork"=>[]  //工种
			];
			if(!empty($typeWork)){
				$success['typeWork']=get_attr($typeWork,0);
			}
			//获取工种
			respond($success,'加载成功！',200);
		}
		//确认订单
		public function okOrder(){
			$arr=explode(',',$_POST['isid']);
			$data=[];
			foreach($arr as $key=>$item){
				$this->db->where('order_id',$item)->update("match_jiedan",['order_id'=>'']);
				array_push($data,['id'=>$item,'status'=>$_POST['status'],'datetime_wc'=>unix_to_human(time(), TRUE, 'eu')]);
			}
			$state=$this->db->update_batch('order', $data, 'id');
			$msg=$state ? "确认成功！" : "确认失败！";
			$this->addLog('确认订单',$msg);
			respond($state,$msg,200);
		}
		//取消订单
		public function cancelOrder(){
			$arr=explode(',',$_POST['isid']);
			$data=[];
			foreach($arr as $key=>$item){
				
				$order=$this->db->select('o.id,o.order_pay_id,op.pay_order,o.service_price,op.free_state,u.openid,op.price as op_price')
				->from('order as o')
				->join('order_pay as op','op.id=o.order_pay_id','left')
				->join('users as u','u.id=op.user_id','left')
				->where(['o.id'=>$item])
				->get()
				->row_array();
				
				$state=$this->priceRefund($order['pay_order'],$order['op_price'],$order['service_price']);	
				if($state){
					array_push($data,['id'=>$item,'status'=>$_POST['status'],'datetime_px'=>unix_to_human(time(), TRUE, 'eu'),'cancel_type'=>'3']);
				}
			}
			if(!empty($data)){
				$state=$this->db->update_batch('order', $data, 'id');
				$msg=$state ? "取消成功！" : "取消失败！";
				$this->addLog('取消订单',$msg);
				respond($state,$msg,200);
			}else{
				respond([],'请勿重复操作',40001);
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
		
		
		//删除订单
		public function delOrder(){
			$arr=explode(',',$_POST['isid']);
			
			
			$state=$this->db->where_in('id',$arr)->delete('order');;
			$msg=$state ? "删除成功！" : "删除失败！";
			$this->addLog('删除订单',$msg);
			respond($state,$msg,200);
		}
	
		//订单状态
		public function stateText($state){
			//订单状态，1已确认，2取消,3待确认订单
			$text='';
			if($state==='4'){
				$text='待接单';
			}else if($state==='5'){
				$text='待联系';
			}else if($state==='6'){
				$text='待开工';
			}else if($state==='7'){
				$text='服务中';
			}else if($state==='3'){
				$text='待确认';
			}else if($state==='1'){
				$text='已完成';
			}else if($state==='2'){
				$text='已取消';
			}
			return $text;
		}
		
		//评价列表
		public function commentList(){
			
			$page=$_GET['page'];//当前页码
			$limit=$_GET['limit'];//一页显示数量
			$like=[];//模糊搜索内容
			$where=[];//搜索条件
			
			//评价人名称
			if(!empty($_GET['user_name'])){
				$like['u.name']=$_GET['user_name'];
			}
			//评价人电话
			if(!empty($_GET['user_tel'])){
				$like['u.tel']=$_GET['user_tel'];
			}
			//评价对象名称
			if(!empty($_GET['obj_name'])){
				$like['ou.name']=$_GET['obj_name'];
			}
			//评价对象电话
			if(!empty($_GET['obj_tel'])){
				$like['ou.tel']=$_GET['obj_tel'];
			}
			//评价时间
			if(!empty($_GET['datetime'])){
				$tarr=str_date_time($_GET['datetime']);
				$where['e.datetime >=']=$tarr['s'];
				$where['e.datetime <=']=$tarr['e'];
			}
			//隐藏显示状态
			if(!empty($_GET['show_type'])){
				$where['e.show_type']=$_GET['show_type'];
			}
			//开始查询
			$query=$this->db->select('e.*,u.name as user_name,u.tel as user_tel,u.headimg,ou.name as obj_user_name,ou.tel as obj_user_tel')
			->from("evaluation as e")
			->like($like)
			->join('users as u',"u.id=e.user_id",'left') //评价人
			->join('users as ou',"ou.id=e.obj_user_id",'left') //评价对象
			 ->where($where);
			$db = clone($this->db);  
			$count = $this->db->count_all_results();
			$this->db = $db;
			$query=$this->db->limit($limit, ($page-1)*$limit)
			->order_by('e.datetime DESC')->get();
			$data_list=$query->result_array();
			//计算总页数
			$pageTool= $count%$limit ==0 ? $count/$limit : ceil($count/$limit); //计算总页数
			
			foreach($data_list as $key=>$item){
				$data_list[$key]['show_type_text']=$item['show_type']=='1' ? '显示' : '隐藏';
			}
			$typeWork=$this->db->select('id,name,parent_id')->order_by('sort DESC')->get_where('type_work')->result_array();
			//print_r($typeWork);
			$success=[
				"count"=>$count,  //总条数					
				"pageTool"=>$pageTool,//总页数					
				"data"=>$data_list, //数据
				"typeWork"=>[]  //工种
			];
			if(!empty($typeWork)){
				$success['typeWork']=get_attr($typeWork,0);
			}
			//获取工种
			respond($success,'加载成功！',200);
		}
		//显示评价
		public function showComment(){
			$arr=explode(',',$_POST['isid']);
			$data=[];
			
			foreach($arr as $key=>$item){
				array_push($data,['id'=>$item,'show_type'=>'1']);
			}
			$state=$this->db->update_batch('evaluation', $data, 'id');
			$msg=$state ? "设置成功！" : "设置失败！";
			$this->addLog('显示评价',$msg);
			respond($state,$msg,200);
		}   
		//隐藏评价
		public function hideComment(){
			$arr=explode(',',$_POST['isid']);
			$data=[];
			
			foreach($arr as $key=>$item){
				array_push($data,['id'=>$item,'show_type'=>'2']);
			}
			$state=$this->db->update_batch('evaluation', $data, 'id');
			$msg=$state ? "设置成功！" : "设置失败！";
			$this->addLog('隐藏评价',$msg);
			respond($state,$msg,200);
		}   //隐藏评价
		// seeComment:"apiAll/seeComment",   //评价详情
		
		//投诉列表
		public function complaintsList(){
			$page=$_GET['page'];//当前页码
			$limit=$_GET['limit'];//一页显示数量
			$like=[];//模糊搜索内容
			$where=[];//搜索条件
			
			//评价人名称
			if(!empty($_GET['user_name'])){
				$like['u.name']=$_GET['user_name'];
			}
			//评价人电话
			if(!empty($_GET['user_tel'])){
				$like['u.tel']=$_GET['user_tel'];
			}
			//评价对象名称
			if(!empty($_GET['obj_name'])){
				$like['ou.name']=$_GET['obj_name'];
			}
			//评价对象电话
			if(!empty($_GET['obj_tel'])){
				$like['ou.tel']=$_GET['obj_tel'];
			}
			//评价时间
			if(!empty($_GET['datetime'])){
				$tarr=str_date_time($_GET['datetime']);
				$where['c.datetime >=']=$tarr['s'];
				$where['c.datetime <=']=$tarr['e'];
			}
			
			//开始查询
			$query=$this->db->select('c.*,u.name as user_name,u.tel as user_tel,u.headimg,ou.name as obj_user_name,ou.tel as obj_user_tel')
			->from("complaints as c")
			->like($like)
			->join('users as u',"u.id=c.user_id",'left') //评价人
			->join('users as ou',"ou.id=c.obj_user_id",'left') //评价对象
			 ->where($where);
			$db = clone($this->db);  
			$count = $this->db->count_all_results();
			$this->db = $db;
			$query=$this->db->limit($limit, ($page-1)*$limit)
			->order_by('c.datetime DESC')->get();
			$data_list=$query->result_array();
			//计算总页数
			$pageTool= $count%$limit ==0 ? $count/$limit : ceil($count/$limit); //计算总页数
			
			foreach($data_list as $key=>$item){
				$data_list[$key]['img']=explode(',',$item['img']);
			}
			$typeWork=$this->db->select('id,name,parent_id')->order_by('sort DESC')->get_where('type_work')->result_array();
			//print_r($typeWork);
			$success=[
				"count"=>$count,  //总条数					
				"pageTool"=>$pageTool,//总页数					
				"data"=>$data_list, //数据
				"typeWork"=>[]  //工种
			];
			if(!empty($typeWork)){
				$success['typeWork']=get_attr($typeWork,0);
			}
			//获取工种
			respond($success,'加载成功！',200);
		}
		//反馈投诉内容
		public function fComplaints(){
			$data=[
				'datetime_f'=>unix_to_human(time(), TRUE, 'eu'),
				'f_text'=>$_POST['reply'],
				'state'=>'1'
			];
			$state=$this->db->where('id',$_POST['id'])->update('complaints',$data);
			$msg=$state ? "反馈成功！" : "反馈失败！";
			$this->addLog('投诉反馈',$msg);
			respond($state,$msg,200);
		}
		// seeComplaints
		
		//反馈意见
		public function feedbackList(){
			$page=$_GET['page'];//当前页码
			$limit=$_GET['limit'];//一页显示数量
			$like=[];//模糊搜索内容
			$where=[];//搜索条件
		
			
			//昵称
			if(!empty($_GET['nickname'])){
				$like['u.nickname']=$_GET['nickname'];
			}
			//姓名
			if(!empty($_GET['name'])){
				$like['u.name']=$_GET['name'];
			}
			//电话
			if(!empty($_GET['tel'])){
				$like['u.tel']=$_GET['tel'];
			}
			//身份
			if(!empty($_GET['cwstate'])){
				$where['u.cwstate']=$_GET['cwstate'];
			}
			//评价时间
			if(!empty($_GET['datetime'])){
				$tarr=str_date_time($_GET['datetime']);
				$where['c.datetime >=']=$tarr['s'];
				$where['c.datetime <=']=$tarr['e'];
			}
			
			//开始查询
			$query=$this->db->select('f.*,u.name as user_name,u.tel as user_tel,u.headimg,u.nickname,u.cwstate')
			->from("feedback as f")
			->like($like)
			->join('users as u',"u.id=f.user_id",'left') //评价人
			 ->where($where);
			$db = clone($this->db);  
			$count = $this->db->count_all_results();
			$this->db = $db;
			$query=$this->db->limit($limit, ($page-1)*$limit)
			->order_by('f.datetime DESC')->get();
			$data_list=$query->result_array();
			//计算总页数
			$pageTool= $count%$limit ==0 ? $count/$limit : ceil($count/$limit); //计算总页数
			
			foreach($data_list as $key=>$item){
				$data_list[$key]['img']=explode(',',$item['img']);
				$data_list[$key]['cwstate_text']=$item['cwstate']==='1' ? '工人':'包工';
			}
			$typeWork=$this->db->select('id,name,parent_id')->order_by('sort DESC')->get_where('type_work')->result_array();
			//print_r($typeWork);
			$success=[
				"count"=>$count,  //总条数					
				"pageTool"=>$pageTool,//总页数					
				"data"=>$data_list, //数据
				"typeWork"=>[]  //工种
			];
			if(!empty($typeWork)){
				$success['typeWork']=get_attr($typeWork,0);
			}
			//获取工种
			respond($success,'加载成功！',200);
		}
		
		//seeFeedback
		
    }
?>