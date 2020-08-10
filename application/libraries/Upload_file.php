<?php
	/*
	 *上传大文件分片上传2019/11/29
	 * */
	class Upload_file{
		protected $CI;
		public function __construct(){
	        // Assign the CodeIgniter super-object
	        $this->CI =& get_instance();
	        $this->CI->load->helper(['url','file']);
	    }
		
		
		public function upload_base64($upload_path,$base64){
			$err=[];
			$success=[];
			$base64_img = trim($base64);//移除空格
			if(!file_exists($upload_path)){
				mkdir($upload_path,0777);//给于权限
			}
			if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_img, $result)){
				$type = $result[2];
				if(in_array($type,array('pjpeg','jpeg','jpg','gif','bmp','png'))){
					$new_file = $upload_path.date('YmdHis_').'.'.$type;
					if(file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_img)))){
					    $img_path = str_replace('../../..', '', $new_file);
						$success['path']=$img_path;
						$success['msg']="图片上传成功!";
					}else{
						$err="图片上传失败!";
					}
				}else{
					$err="图片上传类型错误!";
				}
				
			}else{
				$err="文件错误,可能不是base64!";
			}
			
			if(!empty($success)){
				//成功返回
				return $success;
			}else{
				$success['err']=$err;
				return $success;
			}
			
		}
		
		//分片上传
		public function sheetUpload($upload_path){
			
			$code=200;
			$msg="ok";
			$success=[];
		
			if(!empty($_POST['isMesh'])){
				$hash = $_POST['hash'];//文件唯一
				$path=FCPATH .'upload_tmp/'.$hash;
				//保存二进制
				if($_POST['isMesh']==='binary'){
					$state=$this->saveBinary($path.$_POST['index']);
					if(!empty($state)){
						$code=$state['code'];
						$msg=$state['msg'];
					}else{
						$code=200;
						$msg="分片保存成功！";
					}
				}
				//合并文件内容生成新的文件
				if($_POST['isMesh']==='mesh'&&!empty($_POST['chunks'])&&!empty($_POST['name'])){
					$chunks=$_POST['chunks'];
					$name=$_POST['name'];
					
					$filetype=explode(".",$name);
					$filetype=end($filetype);
					//保存文件目录	
					
					$file_path=$upload_path.date('Ym_').md5(uniqid()).'.'.$filetype;
					$state=$this->meshBinary(FCPATH .$file_path,$path,$chunks,$name);
					if(!empty($state)){
						$code=$state['code'];
						$msg=$state['msg'];
					}else{
						$code=200;
						$msg="上传成功！";
						//全部完成后清除切片
						$this->clear_tmp($path,$chunks);
						$success['path']=$file_path;
					}
				}
			}
			return ["code"=>$code,"data"=>$success,"msg"=>$msg];
		}
		//保存二进制文件
		public function saveBinary($path){
			$errr=[];
			if($_FILES){
				$myfile=$_FILES['file'];
				$tmp=$myfile['tmp_name'];
				//其他字段
				if(!move_uploaded_file($tmp,$path)){
					$errr=[
						'msg'=>'分片保存发生错误',
						'code'=>40001
					];
				}
			}else{
				$errr=[
					'msg'=>'文件发生错误，请重新上传',
					'code'=>40001
				];
			}
			return $errr;
		}
		//合并二进制文件按照顺序合并，防止发生错误
		public function meshBinary($filepath,$path,$chunks,$name){
			$errr=[];
			for($i=0;$i<$chunks;$i++){
				if(!file_put_contents($filepath,file_get_contents($path.$i),FILE_APPEND)){
					$errr=[
						'msg'=>'文件保存失败'.$i,
						'code'=>40001
					];
				}
			}
			return $errr;
		}
		//清除已经上传的分片
		public function clear_tmp($path,$chunks){
			for($i=0;$i<$chunks;$i++){
				unlink($path.$i);
			}
		}
		
	}


	
?>	