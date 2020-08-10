<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	//页面登录检验类
	class Know_img{
		protected $CI;
		public function __construct(){
			$this->CI =& get_instance();
			$this->CI->load->helper('file');
		}
		
		//识别图片文件内容
		public function konw_file($file_name){
			
			require_once 'img_api/AipImageClassify.php';
			
			$APP_ID = '10466504';
			$API_KEY = 'evMOxxzFg0du0uzfmQVGURPo';
			$SECRET_KEY = 'K88ItWqEOGxIQkHPTgOyp4EOe7bep2w7';
		
			$client = new AipImageClassify($APP_ID, $API_KEY, $SECRET_KEY);
			//图片资源
			$img_url=FCPATH."upload/yu/".$file_name;
			//读取图片内容
			$image = file_get_contents($img_url);
			//动物
			$json=$client->animalDetect($image);
			//图片
			//$json=$client->plantDetect($image);
			return $json;
		}
		
	}
?>
