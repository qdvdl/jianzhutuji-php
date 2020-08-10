<?php
   // session_start();  
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
    $_SESSION['adminVcode']=$checkCode;  
    //输出  
    header("content-type: image/png");  
    imagepng($img);  
?>