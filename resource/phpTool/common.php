<?php
    function telStr(){
		// $xing = substr($user['tel'],3,4);  //获取手机号中间四位
		// $tel=str_replace($xing,'****',$user['tel']);  //用****进行替换
	}

	//格式化
	function citytext($text){
		if($text=='北京'||$text=='上海'||$text=='天津'||$text=='重庆'){
			$text.='市';
		}
		return $text;
	}

//echo birthday('1986-07-22'); 
	function birthday($birthday){ 
	 $age = strtotime($birthday); 
	 if($age === false){ 
	  return false; 
	 } 
	 list($y1,$m1,$d1) = explode("-",date("Y-m-d",$age)); 
	 $now = strtotime("now"); 
	 list($y2,$m2,$d2) = explode("-",date("Y-m-d",$now)); 
	 $age = $y2 - $y1; 
	 if((int)($m2.$d2) < (int)($m1.$d1)) 
	  $age -= 1; 
	 return $age; 
	} 


//金额转换
	function get_number($sum){
		if($sum>=10000){
			return round($sum/10000,2) .'万';
		}else{
        	return $sum;
        }
	}
//验证字段
	function Verification($value,$VerifArr){
		$state=false;
		foreach($VerifArr as $key=>$item){
			if(empty($value[$key])){
				return $item;
			}
		}
		return $state;
	}
	//拼接字url
	function strUrl($str){
		$url="http://".$_SERVER['HTTP_HOST'];
		$str_url="";
		if($str!=""){
			$str_url=$url.base_url().$str;
		}
		return $str_url;
	}
	
	//递归：重新组合数组 		
	function get_attr($a,$pid){
		$tree = array();  //每次都声明一个新数组用来放子元素  
		foreach($a as $v){  
			if($v['parent_id'] == $pid){  //匹配子记录  
				$v['children'] =get_attr($a,$v['id']); //递归获取子记录  
				$tree[] = $v;  //将记录存入新数组  
			}  
		}  
		return $tree; //返回新数组  
	}

	//生成目录文件:$dir//生成文件目录
	function create_folders($dir){
	    return is_dir($dir) or (create_folders(dirname($dir)) and mkdir($dir, 0777));
	}
    //数组转xml
   	function ArrToXml($arr)
    {
            if(!is_array($arr) || count($arr) == 0) return '';

            $xml = "<xml>";
            foreach ($arr as $key=>$val)
            {
                    if (is_numeric($val)){
                            $xml.="<".$key.">".$val."</".$key.">";
                    }else{
                            $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
                    }
            }
            $xml.="</xml>";
            return $xml; 
    }
    function XmlToArr($xml)
    {	
        if($xml == '') return '';
        libxml_disable_entity_loader(true);
        $arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
        return $arr;
    }
	//生成验证码
	function randString($len = 6)
	{
	    $chars = str_repeat('0123456789', 3);
	    // 位数过长重复字符串一定次数
	    $chars = str_repeat($chars, $len);
	    $chars = str_shuffle($chars);
	    $str = substr($chars, 0, $len);
	    return $str;
	}
	//生成图片验证码
	function vcode($path){
		// session_start();  
		$path=$path;
	   	$checkCode=""; 
	    //创建图片，并把随机数画上去  
	    $img=imagecreatetruecolor(100, 40);  
	    //你可以指定背景颜色  
	    $bgcolor=imagecolorallocate($img, 255, 255, 255);  
	    imagefill($img,0,0,$bgcolor);  
	    //创建新的颜色  
	   	$top=32;
		$fontSize=25;
		$x=12;
	    //画出噪点，自己画  
	    for($i=1;$i<5;$i++) {
	    	$color = imagecolorallocate($img, rand(0,255), rand(0,255), rand(0,255));  
	    	$code=dechex(rand(1,15));  
	        $checkCode.=$code; 
			imagettftext($img,$fontSize,rand(-20,20),$x,$top,$color,"./font/TRAJANPRO-REGULAR.OTF",$code);
			$x=$x+20;
	    }  
	    //讲随机验证码保存到session中  
	    $_SESSION['vcode']=$checkCode;
	    //输出  
	    header("content-type: image/png");  
	    imagepng($img,$path);  
		ImageDestroy($img);  //销毁图像
	}
	//匹配数组中相同的值
	function deep_in_array($value, $array) {   
	    foreach($array as $item) {   
	        if(!is_array($item)) {   
	            if ($item == $value) {  
	                return true;  
	            } else {  
	                continue;   
	            }  
	        }   
	            
	        if(in_array($value, $item)) {  
	            return true;      
	        } else if(deep_in_array($value, $item)) {  
	            return true;      
	        }  
	    }   
	    return false;   
	}
	//计算天
	function day($this_time,$unix){
		$timeDay=$this_time-$unix;
		$Day=ceil($timeDay/60/60/24); //计算天;
		return $Day;
	}
	
	//生成带背景的二维码
	//$bg:背景图片,$code_path:二维码图片
	function bg_code($bg,$code_path,$vertica=0){
		//第二：创建一个画布，以后的操作都将基于此画布区域     
		//$bg="./bg.png"; //背景图片
		$bg_info=getimagesize($bg); 
     // print_r($bg_info);
     // exit;
	 	//print_r($srcImageInfo); 
		$w=$bg_info[0];
		$h=$bg_info[1];
		$im = imagecreatetruecolor($w, $h);
		//$bg = imagecreatefrompng($bg);
      	switch($bg_info['mime']){ 
			case "image/png": 
				$bg=imagecreatefrompng($bg); 
				break; 
			case "image/jpeg":
				$bg=imagecreatefromjpeg($bg); 
				break; 
			case "image/jpg": 
				$bg=imagecreatefromjpeg($bg); 
				break; 
		   case "image/gif": 
				$bg=imagecreatefromgif($bg); 
				break; 
		}
      
		//创建带背景的画布
		imagecopy($im,$bg,0,0,0,0,$w,$h);
		
		//插入二维码
		$r_info=getimagesize($code_path); 
		$rw=$r_info[0];
		$rh=$r_info[1];
      	
      	switch($r_info['mime']){ 
			case "image/png": 
				$qrim=imagecreatefrompng($code_path); 
				break; 
			case "image/jpeg":
				$qrim=imagecreatefromjpeg($code_path); 
				break; 
			case "image/jpg": 
				$qrim=imagecreatefromjpeg($code_path); 
				break; 
		   case "image/gif": 
				$qrim=imagecreatefromgif($code_path); 
				break; 
		}
      	
      
		//$qrim=imagecreatefrompng($code_path);
      
      
		imagecopy($im,$qrim,($w/2)-($rw/2),$vertical,0,0,$rw,$rh);
		//第五：输出创建的画布
		imagepng($im,$code_path, 1);
		//第六：销毁画布
		imagedestroy($im);
	}
	//截取两个字符串之间的字符（$kw1：字符串，$mark1：开始字符，$mark2：结束字符）
	function getNeedBetween($kw1,$mark1,$mark2){
		$kw=$kw1;
		$kw='123'.$kw.'123';
		$st =stripos($kw,$mark1);
		$ed =stripos($kw,$mark2);
		if(($st==false||$ed==false)||$st>=$ed)
		return 0;
		$kw=substr($kw,($st+1),($ed-$st-1));
		return $kw;
	}
	//截取文字显示省略号	
	function subtext($text, $length,$replace="..."){
	    if(mb_strlen($text, 'utf8') > $length) {
	        return mb_substr($text, 0, $length, 'utf8').$replace;
	    } else {
	        return $text;
	    }
	}
	//生成订单号
	function order_number(){
		$yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
		$orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
		return $orderSn;
	}
	//图片处理
	//修改图片大小
	function resize_img($url,$path='./',$w,$h,$cover=false){
		$file = $url;
		$ext= pathinfo($url);
      	$extension=$ext['extension'];
		$imginfo=getimagesize($url);
		$mime=explode("/", $imginfo['mime']);
		$ext['extension']=$mime[1];
		
		if(!$cover){
			$imgname = $path.uniqid().'.'.$extension;
		}else{
			$imgname = $path.$ext['filename'].'.'.$extension;
		}
		
		switch($ext['extension']){ 
			case "png": 
				$sourceImage=imagecreatefrompng($url); 
				break; 
			case "jpeg":
				$sourceImage=imagecreatefromjpeg($url); 
				break; 
			case "jpg": 
				$sourceImage=imagecreatefromjpeg($url); 
				break; 
		   case "gif": 
				$sourceImage=imagecreatefromgif($url); 
				break; 
		}
		/*
		resource imagecreatefromgif( string filename )
		resource imagecreatefromjpeg( string filename )
		resource imagecreatefrompng( string filename )
		resource imagecreatefromwbmp( string filename )
		resource imagecreatefromstring( string image )
		*/
		//缩放尺寸
		$newWidth = $w;  // 生成新图片的宽度
		$newHeight = $h; // 生成新图片的高度
		list($width, $height) = getimagesize($file); //获取原图尺寸
		$canvas = imagecreatetruecolor($newWidth,$newHeight);
		imagecopyresampled($canvas,$sourceImage,0,0,0,0,$newWidth,$newHeight,$width,$height);
		
		if($ext['extension']=="png"){
			imagepng($canvas,$imgname);
		}
		if($ext['extension']=="jpeg"){
			imagejpeg($canvas,$imgname);
		}
		if($ext['extension']=="jpg"){
			imagejpeg($canvas,$imgname);
         
		}
		if($ext['extension']=="gif"){
			imagegif($canvas,$imgname);
		}
//		imagejpeg($canvas,$imgname,100);
      	ImageDestroy($canvas);
		//return $imgname;
	}
	//将图片裁剪为圆形
	function img_circle($url,$path='./',$w,$h,$cover=false){  
       // $w = 800;  $h=800; // original size  
        $original_path= $url;  
		$ext= pathinfo($url);
		if(!$cover){
			$dest_path = $path.uniqid().'.'.$ext['extension'];
		}else{
			$dest_path = $path.$ext['filename'].'.'.$ext['extension'];
		}
//      $dest_path = $path.uniqid().'.png';  
//		$dest_path=$path.'.'.$ext['extension'];
		
        $src = imagecreatefromstring(file_get_contents($original_path));  
        $newpic = imagecreatetruecolor($w,$h);  
        imagealphablending($newpic,false);  
        $transparent = imagecolorallocatealpha($newpic, 0, 0, 0, 127);  
        $r=$w/2;  
        for($x=0;$x<$w;$x++)  
            for($y=0;$y<$h;$y++){  
                $c = imagecolorat($src,$x,$y);  
                $_x = $x - $w/2;  
                $_y = $y - $h/2;  
                if((($_x*$_x) + ($_y*$_y)) < ($r*$r)){  
                    imagesetpixel($newpic,$x,$y,$c);  
                }else{  
                    imagesetpixel($newpic,$x,$y,$transparent);  
                }  
            }
        imagesavealpha($newpic, true);  
        imagepng($newpic, $dest_path);  
        imagedestroy($newpic);  
        imagedestroy($src);  
       // unlink($url);  
        return $dest_path;  
    }
	//计算图片大小
	function getsize($size, $format = 'kb') {
	    $p = 0;
	    if ($format == 'kb') {
	        $p = 1;
	    } elseif ($format == 'mb') {
	        $p = 2;
	    } elseif ($format == 'gb') {
	        $p = 3;
	    }
	    $size /= pow(1024, $p);
	    return $size;
	}
	//图片压缩
	function img_compress($path){
		/*获取文件后缀*/
		$ename=getimagesize($path);
		$size = filesize($path);
		$size=round(getsize($size,'kb')/4096,2);
		if($size>1){
			$size=round((1/$size),2)*100;
			$ename=explode('/',$ename['mime']); 
			$ext=$ename[1]; 
			
			/*判断文件类型载入图像*/
			switch($ext){ 
				case "png": 
					$im=imagecreatefrompng($path); 
					break; 
				case "jpeg":
					$im=imagecreatefromjpeg($path); 
					break; 
				case "jpg": 
					$im=imagecreatefromjpeg($path); 
					break; 
			   case "gif": 
					$im=imagecreatefromgif($path); 
					break; 
			}
			imageinterlace($im, 1); //打开隔行扫描  0关闭
          
          		if($ext=="png"){
					imagepng($im,$path, $size);
				}
				if($ext=="jpeg"){
					imagejpeg($im,$path, $size);
				}
				if($ext=="jpg"){
					imagejpeg($im,$path, $size);
				}
				if($ext=="gif"){
					imagegif($im,$path, $size);
				}
          
			//Imagejpeg($im,$path, $size); //调整图片质量，新建图片，覆盖地址
			ImageDestroy($im);		//销毁图像
          
		}
	}
	//带处理生成二维码带logo
	function logo_code($code_path,$logo){
		if ($logo !== FALSE) {   
		    $QR = imagecreatefromstring(file_get_contents($code_path));   
		    $logo = imagecreatefromstring(file_get_contents($logo));   
		    $QR_width = imagesx($QR);//二维码图片宽度   
		    $QR_height = imagesy($QR);//二维码图片高度   
		    $logo_width = imagesx($logo);//logo图片宽度   
		    $logo_height = imagesy($logo);//logo图片高度   
		    $logo_qr_width = $QR_width / 5;   
		    $scale = $logo_width/$logo_qr_width;   
		    $logo_qr_height = $logo_height/$scale;   
		    $from_width = ($QR_width - $logo_qr_width) / 2;   
		    //重新组合图片并调整大小   
		    imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,    
		    $logo_qr_height, $logo_width, $logo_height);   
		}   
		//输出图片   
		imagepng($QR, $code_path); 
		return $code_path;
	}
	//图片水印添加
	 function watermark($path,$reset=FALSE){
		//$path="./upload/wetermark/1534914941.jpg";
		$water="./shuiyin_03.png";
		//第二：创建一个画布，以后的操作都将基于此画布区域     
		//$bg="./bg.png"; //背景图片
		$ext  = pathinfo($path);
		$bg_info=getimagesize($path); 
		
		if($reset){
			$new_path=$ext['dirname'].'/'.$ext['filename']."_mark.".$ext['extension'];	
		}else{
			$new_path=$path;
		}
		$w=$bg_info[0];
		$h=$bg_info[1];
		
		$mime=explode("/", $bg_info['mime']);
		$ext['extension']=$mime[1];
		
		$im = imagecreatetruecolor($w, $h);
		switch($ext['extension']){ 
			case "png": 
				$sourceImage=imagecreatefrompng($path); 
				break; 
			case "jpeg":
				$sourceImage=imagecreatefromjpeg($path); 
				break; 
			case "jpg": 
				$sourceImage=imagecreatefromjpeg($path); 
				break; 
		   case "gif": 
				$sourceImage=imagecreatefromgif($path); 
				break; 
		}
		
		//创建带背景的画布
		imagecopy($im,$sourceImage,0,0,0,0,$w,$h);
//			//插入二维码
		$r_info=getimagesize($water); 
		$rw=$r_info[0];
		$rh=$r_info[1];
		$wa=imagecreatefrompng($water);
		imagecopy($im,$wa,($w/2)-($rw/2),($h/2)-($rh/2),0,0,$rw,$rh);
//			//第五：输出创建的画布
		imagepng($im,$new_path,1);
		//第六：销毁画布
		imagedestroy($im);
		return $new_path;
	}	
	//保存微信头像
	function web_img($url,$path='./upload/head/'){
		$header = array('User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:45.0) Gecko/20100101 Firefox/45.0','Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',      
		'Accept-Encoding: gzip, deflate',);  
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_ENCODING, 'gzip');  
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		$data = curl_exec($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);  
		if ($code == 200) {
			//把URL格式的图片转成base64_encode格式的！      
		 	$imgBase64Code = "data:image/jpeg;base64," . base64_encode($data);
			$img_content=$imgBase64Code;//图片内容 
			if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $img_content,$result)){
		        $type = $result[2]; 
				switch($type){ 
					case "png": 
						$type='png';
						break; 
					case "jpeg":
						$type='jpg';
						break; 
					case "jpg": 
						$type='jpg';
						break; 
				   case "gif": 
						$type='gif';
						break; 
				}
				$path=$path.uniqid().".".$type;
				$img_content=base64_decode(str_replace($result[1], '', $img_content));
				if(!write_file($path, $img_content))
				{
				   return $path;
				}else
				{
				   return $path;
				}
		    }  
		}else{
			return false;
		}  
	}
	//图片转换为base64
	function base64EncodeImage ($image_file) {
	    $base64_image = '';
	    $image_info = getimagesize($image_file);
	    $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
	    $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
	    return $base64_image;
	}
	//图片旋转处理
	function img_rotate($path){
		$ext  = pathinfo($path);	
		$imginfo=getimagesize($path);
		$mime=explode("/", $imginfo['mime']);
		$ext['extension']=$mime[1];
		
		if(!empty($imginfo['channels'])){
			//必须安装exif 扩展库
			if(function_exists('exif_read_data')){
				$exif = exif_read_data($path);
		      //  $image = imagecreatefromjpeg($path);
				switch($mime[1]){ 
			        case "png": 
			            $image=imagecreatefrompng($path); 
			            break; 
			        case "jpeg":
			            $image=imagecreatefromjpeg($path); 
			            break; 
			        case "jpg": 
			            $image=imagecreatefromjpeg($path); 
			            break; 
			        case "gif": 
			            $image=imagecreatefromgif($path); 
		            break; 
	       		 }
				
				if(!empty($exif['Orientation'])) {
				 switch($exif['Orientation']) {
					 case 8:
					  	$image = imagerotate($image,90,0);
					 	 break;
					 case 3:
					 	 $image = imagerotate($image,180,0);
					  	break;
					 case 6:
					  	$image = imagerotate($image,-90,0);
					  	break;
					 }
				}
			}else{
				echo "扩展没有开启";
			}
			imageinterlace($image, 1);
			if($mime[1]=="png"){
				imagepng($image,$path);
			}else if($mime[1]=="jpeg"){
				imagejpeg($image,$path);
			}else if($mime[1]=="jpg"){
				imagejpeg($image,$path);
			}else if($mime[1]=="gif"){
				imagegif($image,$path);
			}
			//imagejpeg($image,$path);
			ImageDestroy($image);
		}
	}
	//事件格式
	//时间展示格式说明：该条留言信息发布时间距当前不超过1小时，则显示“xx分钟前”；该条留言发布时间距当前超过1小时，但未超过24小时，则显示“xx小时前”；该条留言发布时间距当前大于24小时，则显示“xx月xx日”的格式；若大于24小时，但与当前时间非同一年，则显示xxxx年xx月xx日的格式。对于月数和天数，若＜10，则显示x月和x日
	function time_set($date){
		$this_time=time();  //当前时间	毫秒数
		$unix = human_to_unix($date);//日期时间毫秒数
		$time=$this_time-$unix;
		if ($time >= 31104000) { // N年前
//      	$num = (int)($time / 31104000);
//	        return $num.'年前';
			$num=date("Y年m月d日", strtotime($date));
	        return $num;
	    }
	    if ($time >= 2592000) { // N月前
//	        $num = (int)($time / 2592000);
//	        return $num.'月前';
			$num=date("m月d日", strtotime($date));
	        return $num;
	    }
	    if ($time >= 86400) { // N天前
//	        $num = (int)($time / 86400);
			$num=date("m月d日", strtotime($date));
	        return $num;
	    }
	    if ($time >= 3600) { // N小时前
	        $num = (int)($time / 3600);
	        return $num.'小时前';
	    }
	    if ($time > 60) { // N分钟前
	        $num = (int)($time / 60);
	        return $num.'分钟前';
	    }
	    return '1分钟前';
	}
	//将图片风格数组，没图片返回图片为展位图
	function img_arr($str){
		if(!empty($str)){
			$img=explode(",", $str);	
		}else{
			$src=base_url("resource/img/no_img.png");
			$img=[$src];	
		}
  		return $img;
	}
	//无数据替换	
	function str_null_replace($str=""){
		$s="--";
		if($str){
			$s=	$str;
		}
		return $s;
	}
	//无图片时
	function img_null($src=""){
		$s=base_url('resource/img/u873.png');
		if($src){
			$s=$src;
		}
		return 	$s;	
	}
	//风格搜索日期字符串分割
	function str_date_time($datetime){
		if(is_array($datetime)){
			$s_date=trim($datetime[0]);
			$e_date=trim($datetime[1]);
		
		}else{
			$date=explode(",",$datetime);
			if(count($date)===2){
				$datetime=$date;
			}else{
				$datetime=explode("~",$datetime);
			}
			//print_r($datetime);
			$s_date=trim($datetime[0]);
			$e_date=trim($datetime[1]);
		}
		if($s_date==$e_date){
			$dateArr2=explode(" ",$e_date);
			$e_date=$dateArr2[0]." 23:59:59";
		}
		return ['s'=>$s_date,'e'=>$e_date];
	}
	
	function date_format_text($datetime,$strtype){
		if(!empty($datetime)){
			return date_format(date_create($datetime),$strtype);
		}else{
			return $datetime;
		}
	}
	
	//分割字符串
	function strArr($string){
		$arr=[];
		if($string){
			$arr=explode(",", $string);	
		}
		return $arr;
	}
	define('PI',3.1415926535898);
	define('EARTH_RADIUS',6378.137);
	
	//计算范围，可以做搜索用户
	function GetRange($lat,$lon,$raidus){
	    //计算纬度
	    $degree = (24901 * 1609) / 360.0;
	    $dpmLat = 1 / $degree; 
	    $radiusLat = $dpmLat * $raidus;
	    $minLat = $lat - $radiusLat; //得到最小纬度
	    $maxLat = $lat + $radiusLat; //得到最大纬度     
	    //计算经度
	    $mpdLng = $degree * cos($lat * (PI / 180));
	    $dpmLng = 1 / $mpdLng;
	    $radiusLng = $dpmLng * $raidus;
	    $minLng = $lon - $radiusLng;  //得到最小经度
	    $maxLng = $lon + $radiusLng;  //得到最大经度
	    //范围
	    $range = array(
	        'minLat' => $minLat,
	        'maxLat' => $maxLat,
	        'minLon' => $minLng,
	        'maxLon' => $maxLng
	    );
	    return $range;
	}
	//获取2点之间的距离
	function GetDistance($lat1, $lng1, $lat2, $lng2){ 
	    $radLat1 = $lat1 * (PI / 180);
	    $radLat2 = $lat2 * (PI / 180);
	   
	    $a = $radLat1 - $radLat2; 
	    $b = ($lng1 * (PI / 180)) - ($lng2 * (PI / 180)); 
	   
	    $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1)*cos($radLat2)*pow(sin($b/2),2))); 
	    $s = $s * EARTH_RADIUS; 
	    $s = round($s * 10000) / 10000; 
	    return $s; 
	}
	//汉字装换为首字母
	function getfirstchar($s0){   
	    $fchar = ord($s0{0});
	    if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});
	    $s1 = iconv("UTF-8","GB2312", $s0);
	    $s2 = iconv("GB2312","UTF-8", $s1);
		//
	    if($s2 == $s0){$s = $s1;}else{$s = $s0;}
	    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
	    if($asc >= -20319 and $asc <= -20284) return "A";
	    if($asc >= -20283 and $asc <= -19776) return "B";
	    if($asc >= -19775 and $asc <= -19219) return "C";
	    if($asc >= -19218 and $asc <= -18711) return "D";
	    if($asc >= -18710 and $asc <= -18527) return "E";
	    if($asc >= -18526 and $asc <= -18240) return "F";
	    if($asc >= -18239 and $asc <= -17923) return "G";
	    if($asc >= -17922 and $asc <= -17418) return "H";
	    if($asc >= -17922 and $asc <= -17418) return "I";
	    if($asc >= -17417 and $asc <= -16475) return "J";
	    if($asc >= -16474 and $asc <= -16213) return "K";
	    if($asc >= -16212 and $asc <= -15641) return "L";
	    if($asc >= -15640 and $asc <= -15166) return "M";
	    if($asc >= -15165 and $asc <= -14923) return "N";
	    if($asc >= -14922 and $asc <= -14915) return "O";
	    if($asc >= -14914 and $asc <= -14631) return "P";
	    if($asc >= -14630 and $asc <= -14150) return "Q";
	    if($asc >= -14149 and $asc <= -14091) return "R";
	    if($asc >= -14090 and $asc <= -13319) return "S";
	    if($asc >= -13318 and $asc <= -12839) return "T";
	    if($asc >= -12838 and $asc <= -12557) return "W";
	    if($asc >= -12556 and $asc <= -11848) return "X";
	    if($asc >= -11847 and $asc <= -11056) return "Y";
	    if($asc >= -11055 and $asc <= -10247) return "Z";
	    return NULL;
	}
	
	//数组对象中是否包含当前值
	function arr_contain($arr,$name,$value){
		$newArr=[];
		foreach($arr as $key=>$item){
			if($item[$name]===$value){
				$newArr=$item;
			}
		}
		return $newArr;
	}
	//获取正确的ip
	function GetIP(){
		if (getenv("HTTP_CLIENT_IP") 
			&& strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
		   $ip = getenv("HTTP_CLIENT_IP");
		else if (getenv("HTTP_X_FORWARDED_FOR") 
			&& strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
		   $ip = getenv("HTTP_X_FORWARDED_FOR");
		else if (getenv("REMOTE_ADDR") 
			&& strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
		   $ip = getenv("REMOTE_ADDR");
		else if (isset($_SERVER['REMOTE_ADDR']) 
			&& $_SERVER['REMOTE_ADDR'] 
			&& strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
		   $ip = $_SERVER['REMOTE_ADDR'];
		else
		   $ip = "unknown";
		return($ip);
	}
	
	//导入字段格式化
	function get_date_by_excel($data,$type="Y-m-d H:i:s"){
		$unix_time= \PHPExcel_Shared_Date::ExcelToPHP($data);
		
		
		return ($unix_time < 0) ? date($type, $unix_time) : date($type, strtotime(gmdate($type, $unix_time)));
	}
	
  

?>