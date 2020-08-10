<?php
	
	function testing($str=""){
   		$words=['习大大','习近平','辛灏年','陈胜','紧掏','紧淘','锦淘','锦掏','紧套','藏独','soufun','搜房','139116797372','学生静坐','操你','傻逼','人体炸弹','温家保','炸药','代考','温家堡','造反','共产党','温总','恽小华','黄疽','胡进套','温家饱','黄JU','HUANG菊','HUANGJU'];
		
		$banned=generateRegularExpression($words);
		$res_banned=check_words($banned,$str);
		
		echo count($res_banned);
		//return ['testing_num'=>$banned,'str'=>$res_banned];
	}
	
  /**
     * @describe 数组生成正则表达式
     * @param array $words
     * @return string
     */
    function generateRegularExpression($words)
    {
        $regular = implode('|', array_map('preg_quote', $words));
        return "/$regular/i";
    }
    /**
     * @describe 字符串 生成正则表达式
     * @param array $words
     * @return string
     */
    function generateRegularExpressionString($string){
          $str_arr[0]=$string;
          $str_new_arr=  array_map('preg_quote', $str_arr);
          return $str_new_arr[0];
    }
    /**
     * 检查敏感词
     * @param $banned
     * @param $string
     * @return bool|string
     */
    function check_words($banned,$string)
    {    $match_banned=array();
        //循环查出所有敏感词
 
        $new_banned=strtolower($banned);
		
        $i=0;
        do{
            $matches=null;
            if (!empty($new_banned) && preg_match($new_banned, $string, $matches)) {
                $isempyt=empty($matches[0]);
                if(!$isempyt){
                    $match_banned = array_merge($match_banned, $matches);
                    $matches_str=strtolower(generateRegularExpressionString($matches[0]));
                    $new_banned=str_replace("|".$matches_str."|","|",$new_banned);
                    $new_banned=str_replace("/".$matches_str."|","/",$new_banned);
                    $new_banned=str_replace("|".$matches_str."/","/",$new_banned);
                }
            }
            $i++;
            if($i>20){
                $isempyt=true;
                break;
            }
        }while(count($matches)>0 && !$isempyt);
 
        //查出敏感词
        if($match_banned){
            return $match_banned;
        }
        //没有查出敏感词
        return array();
    }
    
?>