<?php
	class WxDmApi extends MY_Controller{
      
      	private $appid="wx1e50f10351052738";
        private $secret="a09ace5592caaf9b18626a296ede6d92";
      
		public function __construct(){
			parent::__construct();
			//$this->load->model("operation_model");
			$this->load->helper(['url',"common",'file']);
		    $this->load->library(["session"]);
		}
      	//提交手机号验证短信
		public function codeVerification(){
			$user=$this->userVerification();
			//print_r($user);
			//获取code，手机号
			$tel_code=$this->session->userdata('code');
			$tel=$this->session->userdata('tel');
			if(!empty($tel_code)){
				if($tel===$_POST['tel']){
					if($tel_code===$_POST['code']){
						$state=$this->db->where('id',$user['id'])->update('users',['tel'=>$tel]);
						if($state){
							respond([],"绑定成功！",200);
						}else{
							respond([],"绑定失败！",40001);
						}
					}else{
						respond([],"验证码错误！",40001);
					}
				}else{
					respond([],"输入手机号和发送验证码手机不一致！",40001);
				}	
			}else{
				respond([],"请先发送验证码！",40001);
			}
		}
		
		//发送短信
		public function code(){
			// $accessKeyId=$this->config->item('accessKeyId');
			// $accessKeySecret=$this->config->item('accessKeySecret');
			// $sign=$this->config->item('sign');
			
			$code=200;
			$msg="";
			//手机号
			$tel=$_POST['tel'];
			if(!empty($_POST['tel'])){
			$user=$this->db->get_where('users',['tel'=>$tel])->row_array();
			if(empty($user)){
				$modify=randString();
				$this->session->set_userdata('code', $modify);
				$this->session->set_userdata('tel', $tel);
				$this->session->mark_as_temp('code', 300);
				$this->session->mark_as_temp('tel', 300);
				$param=array("modify"=>$modify);
				$response=$this->smsNotify($tel,"SMS_76400145",$param);
				
				if($response->Code=="OK"){
					$msg='短信发送成功';
				}else{
					if($response->Code="isv.BUSINESS_LIMIT_CONTROL"){
						$msg='发送过于频繁,稍后重试';
						$code=40001;
					}else{
						$code=40001;
						$msg='短信发送失败';
					}
				}
			}else{
				$code=40001;
				$msg="手机已经使用过！";
			}
			}else{
				$code=40001;
				$msg="手机号不能为空！";
			}
			respond([],$msg,$code);
		}
		
		
		
		
      	/**
         * 检验数据的真实性，并且获取解密后的明文.
         * @param $encryptedData string 加密的用户数据
         * @param $iv string 与用户数据一同返回的初始向量
         * @param $data string 解密后的原文
         *
         * @return int 成功0，失败返回对应的错误码
         */
        public function decryptData( $encryptedData, $iv, $sessionkey)
        {
          
          $aesKey=base64_decode($sessionkey);
          $aesIV=base64_decode($iv);
          $aesCipher=base64_decode($encryptedData);
       	  $result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
          $dataObj=json_decode($result);
          if($dataObj  == NULL){
          	return false;
          }
          
          if($dataObj->watermark->appid != $this->appid){
          	return false;
          }
          
          return $dataObj->purePhoneNumber;
          
        }
		//手机号解密
      	public function getPhoneNumber(){
            $user=$this->userVerification();
			$code=200;
			$msg="请求成功";
			$success=[];
          	//小程序信息
			$appid=$this->appid; //小程序 appId
			$secret=$this->secret; //小程序 appSecret

          	  $vstate=Verification($_POST,[
                "encryptedData"=>"参数不能为空！",
                "iv"=>"参数不能为空！"
              ]);
          
              if(!$vstate){
				 $session_key=$this->session->userdata('sessionKey');
                 if(!empty($session_key)){
					$tel=$this->decryptData($_POST['encryptedData'],$_POST['iv'],$session_key);
					//$_POST['uid']
                    if($tel){
                      //更新手机号
                      $state=$this->db->where('token',$user['token'])->update('users',['tel'=>$tel]);
					  //绑定下级关系userchild
					  //别人分享进来会存在uid，如果没有不处理绑定关系
					  if(!empty($_POST['uid'])){
						  //查询我是否已经是别人的下线
						  $userchild=$this->db->get_where('userchild',['child_user_id'=>$user['id']])->row_array();
						  if(empty($userchild)){
							  //如果不是别人的下线则绑定关系
							  $childdata=[
								  'user_id'=>$_POST['uid'],
								  'child_user_id'=>$user['id'],
								  'jointime'=>unix_to_human(time(), TRUE, 'eu')
							  ];
							 $this->db->insert('userchild',$childdata);	 
						  }
					  }
					 
                      $msg=$state ? "手机号绑定成功!" : "手机号绑定失败!";
                      $code=$state ? 200 : 40001;
                    }else{
                    	$code=40001;
              			$msg="手机号解密失败";
                    }
                 }else{
                 	$code=40001;
              		$msg="session丢失";
                 }
              }else{
               $code=40001;
               $msg=$vstate;
              }
			
			respond($success,$msg,$code);
        }
		//登录验证
		public function login(){
			$success=[];
			$code=200;
			$msg="授权登录成功";
			//小程序信息
			$appid=$this->appid; //小程序 appId
			$secret=$this->secret; //小程序 appSecret
		  
			if(!empty($_POST['code'])){
				$js_code=$_POST['code']; //登录时获取的 code
				$grant_type="authorization_code"; //授权类型，此处只需填写 authorization_code
				$url="https://api.weixin.qq.com/sns/jscode2session?appid=$appid&secret=$secret&js_code=$js_code&grant_type=$grant_type";
				
				$res=file_get_contents($url);
				 //json数据转换为数组格式
				$res=json_decode($res,true);
			    // print_r($res);
				if(!empty($res['openid'])){
                    $this->session->set_userdata('sessionKey', $res['session_key']);
					//获取用户信息
					$user=$this->db->get_where('users',['openid'=>$res['openid']])->row_array();
					if(!empty($user)){
						//$msg="用户已经存在！";
						//$code=40002;
                        $success['token']=$user['token'];
                        if(!empty($user['tel'])){
							 $success['isTel']='ok';
                        }else{
                         	 $success['isTel']='no';
                        } 
					}else{
                    	  $vstate=Verification($_POST,[
                            "headimg"=>"头像不能为空！",
                            "nickname"=>"昵称不能为空！"
                          ]);

                          if(!$vstate){
                             $token=md5($res['openid'].$appid.$secret);
							 $state=$this->db->insert('users',[
                             	'headimg'=>$_POST['headimg'],
                                'nickname'=>$_POST['nickname'],
                                'openid'=>$res['openid'],
                                'datetime'=>unix_to_human(time(), TRUE, 'eu'),
                                'token'=>$token
                             ]);
                            
							 $msg=$state ? "授权登录成功!" : "用户信息失败!";
                             $code=$state ? 200 : 40001;
                             $success['isTel']='no';
                             $success['token']=$token;	
                          }else{
                             $code=40001;
							 $msg=$vstate;
                          }
                     
						//$success=['openid'=>$res['openid']];
					}
				}else{
					$code=40001;
					$msg="授权发生错误";
				}
			}else{
				$code=40001;
				$msg="code不能为空";
			}
			respond($success,$msg,$code);
		}
		//检验用户获取用户信息
		public function getUserInfo(){
			$user=$this->userVerification();
			if(!empty($user['tel'])){
				respond(['isTel'=>'ok','revocation'=>$user['revocation'],'cwstate'=>$user['cwstate'],'state'=>$user['state'],'audit'=>$user['audit']],"信息获取成功",200);
			}
		}
        //验证用户是否存在
        public function userVerification(){
          //验证用户是否存在
          if(!empty($_SERVER['HTTP_TOKEN'])){
			
			
			$user=$this->db->select('u.*,tw.name as type_p_text,tws.name as type_s_text')
			->from("users as u")
			->join('type_work tw',"tw.id=u.type_p",'left') //工种
			->join('type_work tws',"tws.id=u.type_s",'left') //工种
			 ->where(['u.token'=>$_SERVER['HTTP_TOKEN']])->get()->row_array();  
			  
			  
          //	$user=$this->db->get_where('users',['token'=>$_SERVER['HTTP_TOKEN']])->row_array();
			//print_r($user);
            if(empty($user)){
            //   $json_string = json_encode(['code'=>40001,"msg"=>"用户不存在"]);
         	  // echo $json_string;
         	  // exit;	
			  respond([],"用户不存在",40001);
            }else{
              return $user;
            } 
          }else{
			  respond([],"登录已过期！",40001);
          }
        }  
		//获取网站信息
		public function getSiteInfo(){
			$code=200;
			$msg="请求成功";
			$success=[];
			$siteinfo=$this->db->select('shareName,shareImgurl')->from('siteinfo')->where('id',1)->get()->row_array();
			$user=[];
			if(!empty($_SERVER['HTTP_TOKEN'])){
				$user=$this->db->select('u.*')->from("users as u") ->where(['u.token'=>$_SERVER['HTTP_TOKEN']])->get()->row_array();  
			}
			if(!empty($user)){
				$siteinfo['user_id']=$user['id'];
			}
		
			$success=$siteinfo;
			respond($success,"成功获取数据！",200);
		}
		//上传图片
		public function upload(){
            $this->userVerification();
			$code=200; 
            $success=[];
			$msg="验证成功";//验证成功
			$create=$_POST['path']."/".date("Y-m-d")."/";
			$upload_path="./upload/".$create;
			$file_path="upload/".$create;
			create_folders($upload_path);
			
			if($_FILES){
				$file_key=key($_FILES);
				$config['upload_path'] =$upload_path;// './upload/excel/';//文件临时存储位置
				$config['allowed_types'] = 'jpg|gif|png|jpeg|pjpeg|bmp|x-png';//允许文件格式
				$config['file_name']=time();//自行生成文件名
				$config['overwrite'] = TRUE;//允许文件格式
				$config['max_size'] = 0;
				
				$this->load->library('upload',$config);
				//判断图片是否保存成功
				$img_url="";
				if(!$this->upload->do_upload($file_key)){
				    $errs="<p>The file you are attempting to upload is larger than the permitted size.</p>";
					$msg=$this->upload->display_errors();
					if($errs==$msg){
				      $msg="上传头像不能超过5mb";
				    }
				   $code=40001;
				}else{
					$data = $this->upload->data();
					$img_url=$upload_path.$data['orig_name'];
					$msg="图片上传成功";
				}
				$url="https://".$_SERVER['HTTP_HOST'].base_url().$img_url;
                $success['path']=$img_url;
              	$success['url']=$url;
				$json_data=array("code"=>$code,"data"=>$success,"msg"=>$msg);
				$json_string = json_encode($json_data);
				echo $json_string;
				exit;
			}
		}
       //获取我的二维码
        public function getQCode(){
            $user=$this->userVerification();
			$code=200; 
            $success=[];
			$msg="验证成功";//验证成功
             
        	$access_token=$this->getAccessToken();
          	if(!empty($access_token)){
              	//$url='https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token='.$access_token; //小程序必须上线
              	$url="https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=".$access_token; //二维码
              	$url="https://api.weixin.qq.com/wxa/getwxacode?access_token=".$access_token; //小程序码
            	$options=[
                    //'scene'=>'uid='.$user['id'],
                    'path'=>'pages/index/index?uid='.$user['id'],
                    'width'=>324
                    //'is_hyaline'=>true
                ]; 
              $postdata=urldecode(json_encode($options));
              $res=$this->postXml($url,$postdata); //返回数据
              if(is_null(json_decode($res))){
              	  $upload_path="./upload/userQCode/".date("Y-m-d").'/';
			  	  create_folders($upload_path);
                  $file=$upload_path.uniqid().'.jpg';
                  if (file_put_contents($file,$res)) {
					  $siteinfo=$this->db->select('imgPoster')->from('siteinfo')->where('id',1)->get()->row_array();
					  bg_code($siteinfo['imgPoster'],$$upload_path);
                    //echo "xier";
                  }else{
                    echo "2";
                  } 
              }else{
              	$code=40001; 
				$msg="微信图片生成错误";
              }
            
            }else{
            	$code=40001; 
				$msg="access_token获取失败";
            }
          
            $json_data=array("code"=>$code,"data"=>$success,"msg"=>$msg);
            $json_string = json_encode($json_data);
            echo $json_string;
            exit;
        }
		//获取access_token
		public function getAccessToken(){
			$token=$this->getWriteToken();
            if(!empty($token['access_token'])){
              return $token['access_token'];
            }
		}
		//获取token=>7200秒
		public function getWriteToken(){
			//小程序信息
			$appid=$this->appid; //小程序 appId
			$secret=$this->secret; //小程序 appSecret
			
			$token_url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
			//获取文件内容
			$file_path='./access_token.txt';	
			$token=read_file($file_path); 	
			
			if(!empty($token)){
				$arr_token=json_decode($token,true);
                if(!empty($arr_token)){
                  if($arr_token['expires_in']<time()){
                      //重新获取
                      $token=file_get_contents($token_url);
                      $token=json_decode($token,true);
                      $token['expires_in']=$token['expires_in']+time();
                  }else{
                      $token=$arr_token;
                  }	
                }else{
                 	$token=file_get_contents($token_url);
                  	$token=json_decode($token,true);
                 	$token['expires_in']=$token['expires_in']+time();
                }
			}else{
				 $token=file_get_contents($token_url);
                 $token=json_decode($token,true);
                 $token['expires_in']=$token['expires_in']+time();
			}
          	$this->logs($file_path,json_encode($token));
            return $token;
		}
		//写入日志记录$logsFile：文件名称，$string:内容
		public function logs($logsFile,$string){
		    //写入到文件
		    if(!write_file($logsFile, $string)){
		        $state=false;
		    }else{
		        $state=true;
		    }
		}
      
      
		//发送xml数据
		public function postXml($url,$postfields){
			$ch = curl_init();
			$params[CURLOPT_URL] = $url;    //请求url地址
			$params[CURLOPT_HEADER] = false; //是否返回响应头信息
			$params[CURLOPT_RETURNTRANSFER] = true; //是否将结果返回
			$params[CURLOPT_FOLLOWLOCATION] = true; //是否重定向
			$params[CURLOPT_POST] = true;
			$params[CURLOPT_POSTFIELDS] = $postfields;
			$params[CURLOPT_SSL_VERIFYPEER] = false;
			$params[CURLOPT_SSL_VERIFYHOST] = false;
			curl_setopt_array($ch, $params); //传入curl参数
			$content = curl_exec($ch); //执行
			curl_close($ch); //关闭连接
			return $content;
		}
	}
?>
