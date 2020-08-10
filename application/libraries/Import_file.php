<?php
	/*
	 *导入导出文件公共
	 * */
	class Import_file{
		protected $CI;
		public function __construct(){
	        // Assign the CodeIgniter super-object
	        $this->CI =& get_instance();
	        $this->CI->load->helper(['file']);
			$this->CI->load->library(['phpexcel','upload_file']);
	    }
		
		/**
		 上传后获取数据并添加到数据库
		 $upload_path：文件保存位置
		 $validation=>function 自定义数据验证规则
		 $tableInfo：['title'=>["A"=>'姓名',"B"=>'年龄1'...],'field'=>['name','age'...]]
		*/
		
		public function get_import_data($upload_path,$tableInfo,$validation){
			$success=[];
			$json_data=$this->CI->upload_file->sheetUpload($upload_path);
			if($json_data['code']===200&&!empty($json_data['data']['path'])){
				//上传文件完成获取地址
				$path=$json_data['data']['path'];
				$success=$this->import_read_excel_data($path,$tableInfo,$validation);
			}else{
				if($json_data['code']===200){
					$success['code']=200;
					$success['msg']=$json_data['msg'];
				}else{
					$success['code']=40001;
					$success['msg']='导入文件发生错误！';
				}
				
			}
			return $success;
		}
		/**
		$path:文件地址
		$tableInfo//表头字段信息
		$validation:自定义字段验证函数
		*/
		public function import_read_excel_data($path,$tableInfo,$validation){
			
			$error_data=[];//验证失败失败数据
			$data_arr=[]; //验证成功数据
			$success=[];
			
			$title=$tableInfo['title'];//表头
			$field=$tableInfo['field'];//数据对应数据库字段名称
			$path="./".$path;		   //文件地址	
			$paths=FCPATH.$path;
			
			//读取表格
			$excel=$this->CI->phpexcel->excel_reader($path)->load($path);
			// 读取第一個工作表
			$currentSheet=$excel->getSheet(0);
			//获取总列数  
			$allColumn=$currentSheet->getHighestColumn();  
			//获取总行数  
			$allRow=$currentSheet->getHighestRow();  
			
			if($allRow>1){
				//循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始  
				$count=$allRow-1; //数据的实际总条数(包含标题)
				for($currentRow=1;$currentRow<=$allRow;$currentRow++){
					//循环每一列$currentColumn=A从A列开始
					$data=[]; //存储每一行的数据
					$key=0;
					$isTitle=true;
					//echo $currentRow;
					
					for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
						//得到每一列数据
						$address=$currentColumn.$currentRow;
						if($currentRow<=1){
							$value=$currentSheet->getCell($address)->getValue();
							$keys=str_replace(' ','',$value);
							if(array_key_exists($currentColumn,$title)){
								//是否和设置相同
								if($keys!==$title[$currentColumn]){
									//echo '错误=>'.$currentColumn."=>".$keys."****".$title[$currentColumn];
									$isTitle=false;
								}
							}else{
								$isTitle=false;	
							}
						}else{
							$value=$currentSheet->getCell($address)->getValue();
							if(!empty($field[$key])){
								//$value=" ".$value;
								$data[$field[$key]]=$value;
							}
						}
						$key++;
					}
					//验证表头是否正确
					if($isTitle){
						if($currentRow!=1){
							$state=$this->CI->$validation($data,'verify');
							if(empty($state['error'])){
								array_push($data_arr,$state['data']);//验证成功数据
							}else{
								array_push($error_data,$state);//验证失败失败数据
							}
						}
					}else{
						$success['code']=40003;
						$success['msg']='表头和模版表头不一致，检查后重新上传!';
						$success['title']=$title;
						unlink($paths);
						return $success;
					}
				}
				$success['code']=40000;
				$error_count=count($error_data);//统计验证失败总条数
				$correct_count=count($data_arr);//统计验证成功总条数
				$success['count']=$count;//文件导入数据的总条数
				$success['error_count']=$error_count;//验证失败总条数
				$success['correct_count']=$correct_count; //验证成功总条数
				$success['error_data']=$error_data; //验证失败数据
				$success['import_count']=0; //导入成功条数
				if($correct_count===$count){
					//全部数据正确在写入
					$state=$this->CI->$validation($data_arr,'write');	
					$success['import_count']=$state;
					$success['msg']='导入成功!';
				}else{
					$success['code']=40002;
					$success['title']=$title;
					$success['field']=$field;
					$success['msg']='数据导入失败，请检查数据是否真确!';
				}
				unlink($paths);
				return $success;
			}else{
				$success['code']=40001;
				$success['msg']='不能导入空数据表格!';
				unlink($paths);
				return $success;
			}
		}
		
		//导出文件
		/**
		 $data:筛选后的数据=>[]
		 $filename:导出时的文件名称=>会员列表.xls
		 $table_title：字段对应表头名称=>["nickname"=>"昵称"]
		 */
		public function export_data($data,$filename,$table_title){
			$str = '<!DOCTYPE html><html><head><meta charset="utf-8">'; 
			$str.='<style>tr th{background:#2F4056;color:#fff;}tr{height:36px;text-align:center;}</style>';
			$str.="</head><body><table border=1>"; 
			//标题
			$str .="<tr>";
			foreach($table_title as $key=>$item){
				$str.="<th>".$item."</th>";
			}
			$str.="</tr>";
			foreach ($data as $key=>$item){
				$str.="<tr>";
				foreach($table_title as $k=>$i){
					if($item[$k]){
						$str.="<td style='vnd.ms-excel.numberformat:@'>".$item[$k]."</td>";
					}else{
						$str.="<td style='vnd.ms-excel.numberformat:@'>--</td>";
					}
				}
				$str.="</tr>";
			}
			$str .= "</table></body></html>"; 
			// echo $str;	
			// exit;		
			header( "Content-Type: application/vnd.ms-excel; name='excel'"); 
			header( "Content-type: application/octet-stream" ); 
			header( "Content-Disposition: attachment; filename=".$filename ); 
			header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" ); 
			header( "Pragma: no-cache" ); 
			header( "Expires: 0" ); 
			exit($str);
		}
		
		
	}
?>