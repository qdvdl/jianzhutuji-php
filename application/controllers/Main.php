<?php

	header('Access-Control-Allow-Origin:http://localhost:8080');
	header("Access-Control-Allow-Credentials: true" );
	header('Access-Control-Allow-Headers:Origin,Authorization,Content-Type');
	header('Access-Control-Allow-Methods:POST,GET,OPTIONS');
	
    class Main extends MY_Controller{
            
            public function __construct(){
                parent::__construct();
                $this->load->library(['session','upload_file']);
                $this->load->helper(['url','common']);
				$this->apiVerification();
            }
			//上传base64图片
    		public function upload_base64($upload_path){
				$code=200;
				$msg="OK";
				$success=[];
				$upload_path="./".$upload_path;
				
				if(!empty($_POST['base64'])){
					$state=$this->upload_file->upload_base64($upload_path,$_POST['base64']);
					if(empty($state['err'])){
						$success['path']=$state['path'];
						$msg=$state['msg'];
					}else{
						$msg=$state['err'];
						$code=40001;
					}
				}else{
					$msg="base64参数不能为空!";
					$code=40001;
				}
				return ["code"=>$code,"msg"=>$msg,"data"=>$success];
    		}
    		//ci自带上传
    		public function upload_img($upload_path){
    			$code=200;
    			$msg="OK";
    			$success=[];
    			$upload_path="./".$upload_path;
    			
    			if($_FILES){
    				$file_key=key($_FILES);
    				$config['upload_path'] =$upload_path;// './upload/excel/';//文件临时存储位置
    				$config['allowed_types'] = 'jpg|gif|png|jpeg|pjpeg|bmp|x-png';//允许文件格式
    				$config['file_name']=uniqid().time();//自行生成文件名
    				$config['overwrite'] = TRUE;//允许文件格式
    				$config['max_size'] = 0;
    				
    				$this->load->library('upload',$config);
    				//判断图片是否保存成功
    				$img_url="";
    				if(!$this->upload->do_upload($file_key)){
    					$msg=$this->upload->display_errors();
    					$code=40001;
    				}else{
    					$data = $this->upload->data();
    					$img_url=$upload_path.$data['orig_name'];
    				  //	img_compress($img_url);//压缩
    				// 	img_rotate($img_url);  							//手机上传旋转图片
    				//	resize_img($img_url,$upload_path,50,50,true);	//裁剪图片
    				//    watermark($img_url);							//水印图片
    					$msg="图片上传成功";
    				}
    				$url="http://".$_SERVER['HTTP_HOST'].base_url().$img_url;
    				$success['path']=$img_url;
    				
					return ["code"=>$code,"data"=>$success,"msg"=>$msg];
    				
    				
    			}
    		}		
    		//上传文件
    		public function upload_file(){
				if($_SERVER['REQUEST_METHOD']!='OPTIONS'){
					if($_POST){
						$upload_path="upload/".date("Y-m-d")."/";
						if(!empty($_POST['path'])){
							$upload_path="upload/".$_POST['path']."/".date("Y-m-d")."/";
							create_folders("./".$upload_path);
						}
						//exit;
						if(!empty($_POST['isFileType'])&&$_POST['isFileType']==='base64'){
							$json_data=$this->upload_base64($upload_path);
						}else if(!empty($_POST['isFileType'])&&$_POST['isFileType']==='sheet'){
							$json_data=$this->sheetUpload($upload_path);
						}else{
							$json_data=$this->upload_img($upload_path);
						}
					
						$json_string = json_encode($json_data);
						echo $json_string;
						exit;
					}else{
						$json_data=array("code"=>40001,"data"=>[],"msg"=>"请求方式错误！");
						$json_string = json_encode($json_data);
						echo $json_string;
						exit;
					}
    			}else{
					$json_data=array("code"=>200,"data"=>[],"msg"=>"验证通过！");
					$json_string = json_encode($json_data);
					echo $json_string;
					exit;
				}
    		}
    		//分片上传
    		public function sheetUpload($upload_path){
				$json_data=$this->upload_file->sheetUpload($upload_path);
				return $json_data;
    		}
			//验证码
			public function getCode(){
				$path="./upload/code/code.png";
				vcode($path);
				echo json_encode(array("code"=>200,"codeimg"=>base_url().$path),true);
				exit;
			}
    	}	