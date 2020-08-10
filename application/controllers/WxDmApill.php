<?php
	class WxDmApill extends MY_Controller{
      
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
          	$this->isRole();
			$code=200;
			$msg="请求成功";
			$success=[];
			
          		
			//获取banner
			$banner=$this->db->select('id,name,bannerurl')->get_where('banner')->result_array();
			
			$vehicle=$this->db->select('v.*,b.name as b_name')
			->from("vehicle as v")
			->join('brand as b',"v.brand_id=b.id",'left')
			->where('home','1')
			->or_group_start()
			->where('head', '1')
			->group_end()
			 ->get()->result_array();
			$vehicle_home=[];
			$vehicle_head=[];
			foreach($vehicle as $key=>$item){
				$item['fprice']=get_number($item['fprice']);
				$item['mprice']=get_number($item['mprice']);
				$item['fprices']=get_number($item['fprices']);
				$item['mprices']=get_number($item['mprices']);
				if($item['home']==='1'){
					array_push($vehicle_home,$item);
				}
				if($item['head']==='1'){
					array_push($vehicle_head,$item);
				}
			}
			
			//获取首付推荐
			// $vehicle_home=$this->db->select('v.*,b.name as b_name')
			// ->from("vehicle as v")
			// ->join('brand as b',"v.brand_id=b.id",'left')
			//  ->where('home','1')->get()->result_array();
			//推荐车型
			// $vehicle_head=$this->db->select('v.*,b.name as b_name')
			// ->from("vehicle as v")
			// ->join('brand as b',"v.brand_id=b.id",'left') 
			//  ->where('head','1')->get()->result_array();
			
          //成交信息
			$info=$this->db->select('infolist')->get_where('siteinfo',['id'=>1])->row_array();
          	$info=json_decode($info['infolist'],true);
          //	print_r($info);
          
            foreach($info as $key=>$item){
              //echo $item['tel'];
            	$xing = substr($item['tel'],3,4);  //获取手机号中间四位
              	$info[$key]['tel']=str_replace($xing,'****',$item['tel']);  //用****进行替换
            }

          	
          
          	$success['banner']=$banner;
			$success['vehicle_home']=$vehicle_home;
			$success['vehicle_head']=$vehicle_head;
          	//print_r($info);
			$success['info']=$info;
			
			$json_string = json_encode(['code'=>$code,"msg"=>$msg,"data"=>$success]);
			echo $json_string;
			exit;
		}
       //获取去选车页面数据
		public function getVehicle(){
            $this->userVerification();
			$code=200;
			$msg="请求成功";
			$success=[];
			
			$page=$_GET['page'];//当前页码
			$limit=$_GET['limit'];//一页显示数量
			
			$like=[];//模糊搜索内容
			$where=[];//搜索条件
          
          //排序
			$orderby=$_GET['orderby'];
			if($orderby=='fprice_ASC'){
				$orderby='v.fprice ASC';
			}else if($orderby=='fprice_DESC'){
				$orderby='v.fprice DESC';
			}else if($orderby=='mprice_ASC'){
				$orderby='v.mprice ASC';
			}else if($orderby=='mprice_DESC'){
				$orderby='v.mprice DESC';
			}
			//型号
			if(!empty($_GET['name'])){
				$like['v.version']=$_GET['name'];
			}
			//分类
			if(!empty($_GET['brand'])){
				$where['v.brand_id']=$_GET['brand'];
			}
			
			//开始查询
			$query=$this->db->select('v.*,b.name as b_name')
			->from("vehicle as v")
			->like($like)
			->join('brand as b',"v.brand_id=b.id",'left')
			 ->where($where);
			$db = clone($this->db);  
			$count = $this->db->count_all_results();
			$this->db = $db;
			$query=$this->db->limit($limit, ($page-1)*$limit)
			->order_by($orderby)->get();
			$data_list=$query->result_array();
          //转换成万
         // print_r($data_list);
			foreach($data_list as $key=>$item){
				$data_list[$key]['fprice']=get_number($item['fprice']);
				$data_list[$key]['mprice']=get_number($item['mprice']);
				$data_list[$key]['fprices']=get_number($item['fprices']);
				$data_list[$key]['mprices']=get_number($item['mprices']);
			}
			//计算总页数
			$pageTool= $count%$limit ==0 ? $count/$limit : ceil($count/$limit); //计算总页数
			//获取品牌
			$brand=$this->db->select('name,imgurl,id')->get_where('brand')->result_array();
			
			//print_r($brand);
			$success=[
              	"brand"=>$brand,  //品牌
				"count"=>$count,  //总条数					
				"pageTool"=>$pageTool,//总页数					
				"data"=>$data_list //数据
			];
			
			$json_string = json_encode(['code'=>$code,"msg"=>$msg,"data"=>$success]);
			echo $json_string;
			exit;
		}
      //获取车辆详情
		public function getVehicleDeta(){
			$user=$this->userVerification();
			$code=200;
			$msg="请求成功";
			$success=[];
				
          	$where=[];
			
			if(!empty($_GET['id'])){
				$where['v.id']=$_GET['id'];
			}
			
			$vehicle=$this->db->select('v.*,b.name as b_name')
			->from("vehicle as v")
			->join('brand as b',"v.brand_id=b.id",'left')
			 ->where($where)->get()->row_array();
			$vehicle['banner']=explode(",",$vehicle['banner']);  
		  
			$vehicle['price']=get_number($vehicle['price']);
			//获取购买须知
			$siteinfo=$this->db->select('gmxztext,phoneTel')->from('siteinfo')->where('id',1)->get()->row_array();
			$vehicle['siteinfo']=$siteinfo;
			//查询评价
			$evaluation=$this->db->select('e.*,u.nickname,u.headimg')->from('evaluation as e')
			->join('users as u','u.token=e.user_id')
			->where('e.vehicle_id',$vehicle['id']);
			$db = clone($this->db);
			$count = $this->db->count_all_results();
			$this->db = $db;
			$evaluation=$this->db->order_by('e.datetime DESC')->get()->row_array();	
          	if(!empty($evaluation)){
              	$evaluation['img']=explode(",",$evaluation['img']);
				$evaluation['datetime']=date_format(date_create($evaluation['datetime']),"Y.m.d H:i");
            }
		
          	//用户信息
            $vehicle['user']=$user;
			$vehicle['evaluation']=$evaluation;
			$vehicle['evaluation_count']=$count;
          	//附带数据
			$success=$vehicle;
			
			
			$json_string = json_encode(['code'=>$code,"msg"=>$msg,"data"=>$success]);
			echo $json_string;
			exit;
		}
      //提交车辆信息
		public function setClient(){
			$user=$this->userVerification();
			$code=200;
			$msg="请求成功";
			$success=[];
			
			$data=[
				"user_id"=>$user['id'],
				"vehicle_id"=>$_POST['vehicle_id'],
				"client_tel"=>$_POST['client_tel'],
				"client_name"=>$_POST['client_name'],
				"submit_time"=>unix_to_human(time(), TRUE, 'eu'),
				"budget"=>$_POST['budget']	
			];
			
          	//print_r($data);
			$state=$this->db->insert("client",$data);
			$msg=$state ? "提交成功！" : "提交失败！";
			$code=$state ? 200: 40001;
			
			$json_string = json_encode(['code'=>$code,"msg"=>$msg,"data"=>$success]);
			echo $json_string;
			exit;
		}
      	//获取评论
		public function getCommentary(){
			$user=$this->userVerification();
			$code=200;
			$msg="请求成功";
			$success=[];
			
			$page=$_GET['page'];//当前页码
			$limit=$_GET['limit'];//一页显示数量
			
			$like=[];//模糊搜索内容
			$where=[];//搜索条件
			
			
			//查询评价
			$query=$this->db->select('e.*,u.nickname,u.headimg')->from('evaluation as e')
			->join('users as u','u.token=e.user_id')
			->where('e.vehicle_id',$_GET['id']);
			$db = clone($this->db);
			$count = $this->db->count_all_results();
			$this->db = $db;
			$query=$this->db->limit($limit, ($page-1)*$limit)
			->order_by('datetime DESC')->get();
			$data_list=$query->result_array();
			
			//循环数据格式化
			foreach($data_list as $key=>$item){
				$data_list[$key]['img']=explode(",",$item['img']);
				$data_list[$key]['datetime']=date_format(date_create($item['datetime']),"Y.m.d H:i");
			}
			//计算总页数
			$pageTool= $count%$limit ==0 ? $count/$limit : ceil($count/$limit); //计算总页数
			//print_r($brand);
			$success=[
				"count"=>$count,  //总条数					
				"pageTool"=>$pageTool,//总页数					
				"data"=>$data_list //数据
			];
			
			$json_string = json_encode(['code'=>$code,"msg"=>$msg,"data"=>$success]);
			echo $json_string;
			exit;
		}
		//用户信息
		public function getUserInfo(){
			$user=$this->userVerification();
			$code=200;
			$msg="请求成功";
			$success=[];
			
			//平台客服电话
			$siteinfo=$this->db->select('phoneTel')->from('siteinfo')->where('id',1)->get()->row_array();
			
			
			$xing = substr($user['tel'],3,4);  //获取手机号中间四位
			$tel=str_replace($xing,'****',$user['tel']);  //用****进行替换
			
			$success=[
				'tel'=>$tel,
				'headimg'=>$user['headimg'],
				'nickname'=>$user['nickname'],
				'phoneTel'=>$siteinfo['phoneTel']
			];
          
			$json_string = json_encode(['code'=>$code,"msg"=>$msg,"data"=>$success]);
			echo $json_string;
			exit;
		}
      //设置用户信息
		public function setUserInfo(){
			$user=$this->userVerification();
			$code=200;
			$msg="请求成功";
			$success=[];
			
			$data=[];
			
			if(!empty($_POST['nickname'])){
				$data['nickname']=$_POST['nickname'];
			}
			if(!empty($_POST['headimg'])){
				$data['headimg']=$_POST['headimg'];
			}
			
            //print_r($data);
			$state=$this->db->where('id',$user['id'])->update("users",$data);
			$msg=$state ? "修改成功！" : "修改失败！";
			$code=$state ? 200: 40001;
			
			$json_string = json_encode(['code'=>$code,"msg"=>$msg,"data"=>$success]);
			echo $json_string;
			exit;
		}
		//获取我的订单
		public function getOrder(){
			$user=$this->userVerification();
			$code=200;
			$msg="请求成功";
			$success=[];
			
          	$order=$this->db->select('o.*,v.version,b.name as b_name,e.order_id,e.text as e_text,e.reply as e_reply,e.star,e.img as e_img')
			->from('order as o')
			->join('vehicle as v','o.vehicle_id=v.id','left') //查询关联车辆
			->join('brand as b','b.id=v.brand_id','left')     //查询车辆关联品牌
			->join('evaluation as e','e.order_id=o.id','left') //获取评价内容
			->where('o.client_tel',$user['tel'])
			->order_by('submit_time DESC')
			->get()->result_array();	
          	//循环计算期数
         	foreach($order as $key=>$item){
				//首付金额=成交金额/首付比例
				$firstMoney=$item['deal_price']/$item['tions'];
				$periods=($item['deal_price']-$firstMoney)/$item['payment']; 
				$order[$key]['firstMoney']=sprintf("%.2f",$firstMoney);
				//期数=成交金额-首付金额/月供金额
				$order[$key]['periods']=ceil($periods);
              	$order[$key]['deal_time']=date_format(date_create($item['deal_time']),"Y.m.d");
				$order[$key]['estate']=!empty($item['order_id']) ? '1' : '2';
				
				if(!empty($item['e_img'])){
					$order[$key]['e_img']=explode(',',$item['e_img']);
				}
				
				
			}
			$success['order']=$order;
			
			$json_string = json_encode(['code'=>$code,"msg"=>$msg,"data"=>$success]);
			echo $json_string;
			exit;
		}
      	//用户评价信息
		public function myEvaluation(){
			$user=$this->userVerification();
			$code=200;
			$msg="请求成功";
			$success=[];
			
			$data=[
				'user_id'=>	$user['id'],
				'vehicle_id'=>$_POST['vehicle_id'],
				'star'=>$_POST['star'],
				'datetime'=>unix_to_human(time(), TRUE, 'eu'),
				'order_id'=>$_POST['order_id']
			];
			//验证是否有图片
			if(!empty($_POST['text'])){
				$data['text']=$_POST['text'];
			}
			//是否有图片
			if(!empty($_POST['img'])){
				$data['img']=$_POST['img'];
			}
			//提交到数据库
			$state=$this->db->insert("evaluation",$data);
			$msg=$state ? "评价成功！" : "评价失败！";
			$code=$state ? 200: 40001;
			
			$json_string = json_encode(['code'=>$code,"msg"=>$msg,"data"=>$success]);
			echo $json_string;
			exit;
		}
		//获取奖励
		public function getUsersReward(){
			$user=$this->userVerification();
			$code=200;
			$msg="请求成功";
			$success=[];
			//用户信息
			$success['nickname']=$user['nickname'];
			$success['headimg']=$user['headimg'];
			$reward=$this->db->select('ur.id,r.name,r.price,r.imgurl')
			->from('user_reward as ur')
			->join('reward as r','r.id=ur.reward_id','left')
			->where('ur.user_id',$user['id'])->get()->result_array();
			//奖励
			$success['reward']=$reward;
			
			$json_string = json_encode(['code'=>$code,"msg"=>$msg,"data"=>$success]);
			echo $json_string;
			exit;
		}
     	 //获取我的订单
		public function getUserOrder(){
			$user=$this->userVerification();
			$code=200;
			$msg="请求成功";
			$success=[];
			
			$order=$this->db->select('o.*,v.version,b.name as b_name')
			->from('order as o')
			->join('vehicle as v','o.vehicle_id=v.id','left') //查询关联车辆
			->join('brand as b','b.id=v.brand_id','left')     //查询车辆关联品牌
			->where('o.client_tel',$user['tel'])
			->order_by('submit_time DESC')
			->get()->result_array();	
			//循环计算期数
          	$contmoney=0;
			foreach($order as $key=>$item){
				//首付金额=成交金额/首付比例
				$firstMoney=$item['deal_price']/$item['tions'];
             	$contmoney+=$item['deal_price']-$firstMoney;
				$periods=($item['deal_price']-$firstMoney)/$item['payment']; 
				$order[$key]['firstMoney']=sprintf("%.2f",$firstMoney);
				//期数=成交金额-首付金额/月供金额
				$order[$key]['periods']=ceil($periods);
			  	$order[$key]['deal_time']=date_format(date_create($item['deal_time']),"Y.m.d");
				$order[$key]['estate']=!empty($item['order_id']) ? '1' : '2';
			}
          	$success['contmoney']=sprintf("%.2f",$contmoney);
			$success['order']=$order;
			$json_string = json_encode(['code'=>$code,"msg"=>$msg,"data"=>$success]);
			echo $json_string;
			exit;
		}
      //获取平台信息
		public function getSiteInfo(){
			$user=$this->userVerification();
			$code=200;
			$msg="请求成功";
			$success=[];
		
			$siteinfo=$this->db->select('shareName,shareImgurl,imgPoster,gywmtext')->from('siteinfo')->where('id',1)->get()->row_array();
			$success['siteinfo']=$siteinfo;
			$json_string = json_encode(['code'=>$code,"msg"=>$msg,"data"=>$success]);
			echo $json_string;
			exit;
		}
      //轮播图信息
		public function getBnnerInfo(){
			$user=$this->userVerification();
			$code=200;
			$msg="请求成功";
			$success=[];
			//获取banner
			$banner=$this->db->select('id,name,bannerurl,content')->get_where('banner',['id'=>$_GET['id']])->row_array();
			$success=$banner;
			
			$json_string = json_encode(['code'=>$code,"msg"=>$msg,"data"=>$success]);
			echo $json_string;
			exit;
		}
        //验证用户是否存在
        public function userVerification(){
          //验证用户是否存在
		  $this->isRole();
          if(!empty($_SERVER['HTTP_TOKEN'])){
          	$user=$this->db->get_where('users',['token'=>$_SERVER['HTTP_TOKEN']])->row_array();
            if(empty($user)){
              $json_string = json_encode(['code'=>40001,"msg"=>"用户不存在"]);
         	  echo $json_string;
         	  exit;	
            }else{
              return $user;
            } 
          }else{
             $json_string = json_encode(['code'=>40001,"msg"=>"TOKEN无效"]);
         	 echo $json_string;
         	 exit;	
          }
        } 
        //权限验证
		public function isRole(){
			if(!empty($_SERVER['HTTP_AUTH'])){
			  if($_SERVER['HTTP_AUTH']!='dingma'  ){
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
