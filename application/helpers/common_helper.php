<?php
	//公共工具
	require './resource/phpTool/common.php';
	//require './resource/phpTool/testingWord.php';//字符串检测
	//添加私有工具

	/**
	* [敏感字过滤]
	* @param    $content     需要验证的字符串
	* @param    $verify      是否提示验证
	* @return    替换后的内容
	*/
	function lexVerify($content = '', $verify = false){
	    if(!$content) return false;
	    $word = require './resource/phpTool/word.php';               					// 引入敏感字词库
	    $lexicon = array_combine($word,array_fill(0,count($word),'*')); // 换字符
	    $str = strtr($content, $lexicon); // 匹配替换
	    if($verify) if($str != $content) return false;//有敏感词
	    return true;//没有敏感词
	}
	
	// api
	
	function respond($data=[],$msg='OK',$code=200){
		$success=[];
		$success['code']=$code;
		$success['msg']=$msg;
		$success['data']=$data;
		
		$json_string = json_encode($success,true);
		echo $json_string;
		exit;
	}
	
		
	// // 通用响应方式
	// respond($data, 200);
	// // 通用错误响应
	// fail($errors, 400);
	// // 项目创建响应
	// respondCreated($data);
	// // 项目成功删除
	// respondDeleted($data);
	// // 客户端未授权
	// failUnauthorized($description);
	// // 禁止动作
	// failForbidden($description);
	// // 找不到资源
	// failNotFound($description);
	// // Data 数据没有验证
	// failValidationError($description);
	// // 资源已存在
	// failResourceExists($description);
	// // 资源早已被删除
	// failResourceGone($description);
	// // 客户端请求数过多
	// failTooManyRequests($description);
	
		
	
	
	
?>