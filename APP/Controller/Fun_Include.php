<?php
function FI_alert($word="",$url="",$target="self")
{
		if($_REQUEST["forward"]!=''){
			$url	=	$_REQUEST["forward"];
		}elseif($url==""){
			$url	=	$_SERVER['HTTP_REFERER'];
		}
		//FI_errorPage($url);
		
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><title>$word</title><script type=\"text/javascript\" >alert('$word');$target.location='$url';</script>
</head><body></body></html>";
		exit;
}
function FI_changePage($url="",$target="self")
{
		if($url==""){
			$url	=	$_SERVER['HTTP_REFERER'];
		}
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><script type=\"text/javascript\" >$target.location='$url';</script>
</head><body></body></html>";
		exit;
}

function FI_errorPage($url="",$errormsg="",$target="self")
{
		include(APP_DIR."/LichiViews/404.htm");
		exit;
}


/*��õ�ǰϵͳ�û�ID
*/
function Fi_GetCurUserId()
{
		$curUsername = $_SESSION["Zend_Auth"]['storage']->username;
		$tableModaUser = FLEA::getSingleton('Table_LichiUser');
		$userDat = $tableModaUser->getUserByUname($curUsername);
		return $userDat['user_id'];
}

/*ͨ��֤�û�
*/	
function FI_TestComUser($username,$uid,$status,$notice)
{
		if($username)
		{
				$table=FLEA::getSingleton('Table_PassportUser');
				$result=$table->find("username='".$username."'" );
		}
		elseif($uid)
		{
				$table=FLEA::getSingleton('Table_PassportUser');
				$result=$table->find("uid='".$uid."'" );
		}
		/*
		*/
		if($notice)
		{
				if(!$result)
					FI_alert("��û�е�¼�����¼���ύ���룡","http://passport.we54.com/Index/login?forward=".urlencode("http://lichi.we54.com/"));
				else
					return $result;
		}
		else
		{
				if(!$result)
					return false;
				else
					return $result;
		}
}

/*ϵͳ�û�
*/	
function FI_TestSysUser($username,$user_id,$status,$notice)
{
		if($username)
		{
				$table = FLEA::getSingleton('Table_LichiUser');
				if($status == -1)
					$result= $table->find("username='".$username."'");
				else
					$result= $table->find("username='".$username."'And pass = ".$status);
		}
		elseif($user_id)
		{
				$table = FLEA::getSingleton('Table_LichiUser');
				if($status == -1)
					$result= $table->find("user_id='".(int)$user_id."'");
				$result= $table->find("user_id='".(int)$user_id."'And pass = ".$status);
		}
		if($notice)
		{
				if(!$result)
					FI_alert('���û�������',"/");
				else
					return $result;
		}
		else
		{
				if(!$result)
					return false;
				else
					return $result;
		}
}
/*����
*/
//function FI_CheckUserRight($username = "",$userid = "",$notice)
//{
//		if($username)
//		{
//			$user = FI_TestSysUser($username,"",1,0);
//			if($user['username'] == $_SESSION["Zend_Auth"]['storage']->username)
//				return true;
//			else
//			if(FI_CheckAdminRoot())
//				return true;
//		}
//		if($userid)
//		{
//			$user = FI_TestSysUser("",$userid,1,0);
//			if($user['username'] == $_SESSION["Zend_Auth"]['storage']->username)
//				return true;
//			else
//			if(FI_CheckAdminRoot())
//				return true;
//		}
//		if($notice)
//				if(!$msg)
//					FI_alert('���޹���Ȩ��',"/");
//		else
//				if(!$msg)
//					return false;
//}


/*����
*/
function FI_CheckCommonRight($username,$notice)
{
		if(FI_CheckAdminRoot())
			return true;

		if($username)
			if($username == $_SESSION["Zend_Auth"]['storage']->username)
				return true;
		if($notice)
				if(!$msg)
					FI_alert('���޹���Ȩ��',"/");
		else
				if(!$msg)
					return false;
}
/*����Ա�û�
*/
function FI_CheckAdminRoot()
{
	
	//return false;
	
	if($_SESSION["Zend_Auth"]['storage']->adminid!=1)
	{
				if($_SESSION["Zend_Auth"]['storage']->username == 'moda' )
					return true;
				if($_SESSION["Zend_Auth"]['storage']->username =='aaa' )
					return true;
				else
					return false;		
	}	
	else
		return true;		
}
/*��֤�Ƿ����չʾ
*/	
function FI_ShowExist($show_id,$notice)
{
			if(!$show_id)
					FI_alert('ȱ��չʾID',"/");
			$tableModaShows = FLEA::getSingleton('Table_LichiShows');
			$showDE = $tableModaShows->getShowByShowIdNoLimit($show_id);
			if($showDE)
					return $showDE;
			else
					$msg = false;
			if($notice)
					if(!$msg)
						FI_alert('���޹���Ȩ��',"/");
			else
					if(!$msg)
						return false;
}
/*��֤�Ƿ����չʾ�����Ƿ�չʾ����Ȩ�ޣ�����չʾ����
*/	
function FI_ShowAdimnRoot($show_id,$notice)
{
			$showDE = FI_ShowExist($show_id,$notice);
			if($showDE['username'] == $_SESSION["Zend_Auth"]['storage']->username)
			{
				if($showDE['main'] == 1)
				{
					if(FI_CheckAdminRoot())
						return $showDE;
					else
						$msg = false;
				}
				else		
					return $showDE;
			}
			else
			{
				if(FI_CheckAdminRoot())
					return $showDE;
				else
					$msg = false;
				
			}
			if($notice)
					if(!$msg)
						FI_alert('���޹���Ȩ��',"/");
			else
					if(!$msg)
						return false;
}
/*��֤�Ƿ����
*/	
function FI_DiscussExist($dis_id,$notice)
{
			if(!$dis_id)
					FI_alert('ȱ��չʾID',"/");
			$tableModaShows = FLEA::getSingleton('Table_LichiShowDiscuss');
			$showDE = $tableModaShows->getShowDiscussByID($dis_id);
			if($showDE)
					return $showDE;
			else
					$msg = false;
			if($notice)
					if(!$msg)
							FI_alert('���޹���Ȩ��',"/");
			else
					if(!$msg)
							return false;
}

/*��֤�Ƿ�������ۣ����Ƿ����Ȩ��
*/	
function FI_DiscussAdimnRoot($dis_id,$notice)
{
			$showDE = FI_DiscussExist($dis_id,$notice);
			if($showDE['uname'] == $_SESSION["Zend_Auth"]['storage']->username)
				return $showDE;
			else
				$msg = false;
			if($notice)
					if(!$msg)
						FI_alert('���޹���Ȩ��',"/");
			else
					if(!$msg)
						return false;
}
/*
*/	
function FI_ImgUpLoad($upload_name,$dir,$dst_w,$dst_h,$backurl,$notice,$done = "")//$done ǿ��ѹ��
{
		$limittyppe	=	array('.jpg','.gif','.png');
		$file_name = $_FILES[$upload_name]['name'];
		$file_postfix = strtolower(substr($file_name,strrpos($file_name,".")));
		$uploadfile = $dir.$file_postfix;
		/*
		*/
		if(!in_array($file_postfix,$limittyppe))
		{
				if($notice)
						FI_alert("���ύjpg,gif,png��ʽ��ͼƬ��",$backurl);
				else
						return false;
		}
		if($_FILES[$upload_name]['size']>2048000 || $_FILES[$upload_name]['size'] == 0)
		{
				if($notice)
						FI_alert("ͼƬ���ܳ���2M��",$backurl);
				else
						return false;
		}
		/*
		*/
		if (move_uploaded_file($_FILES[$upload_name]['tmp_name'], $uploadfile)) 
		{
				/*
				*/
				if($file_postfix == ".gif")
					$src_img = imagecreatefromgif($uploadfile);
				elseif($file_postfix == ".jpg")
					$src_img = imagecreatefromjpeg($uploadfile);
				elseif($file_postfix == ".png")
					$src_img = imagecreatefrompng($uploadfile);
				/*
				*/
				list($w, $h, $type, $attr)=getimagesize($uploadfile);//PHP����
				
				if($done)
				{
					$src_h = $w*$dst_h/$dst_w;
				}
				else
				{
						if($w < $dst_w)
						{
							$dst_w = $w;
							
							if($h < $dst_h)
							{
								$dst_h = $h;
								$src_h = $h;
							}
							else
								$src_h = $w*$dst_h/$dst_w;
						}
						else
						{
							$tem_h = $dst_w*$h/$w;//ԭͼ������
							
							if($tem_h < $dst_h)
							{
								$src_h = $h;
								$dst_h = $tem_h;
							}
							else
							{
								$src_h = $w*$dst_h/$dst_w;
							}
						}
				}
				$dst_img = imagecreatetruecolor($dst_w,$dst_h); 
				$suc = imagecopyresampled($dst_img,$src_img,0,0,0,0,$dst_w,$dst_h,$w,$src_h);
				if($suc)
				{
						if($file_postfix == ".gif")
							imagegif($dst_img,$uploadfile);
						elseif($file_postfix == ".jpg")
							imagejpeg($dst_img,$uploadfile,100);
						elseif($file_postfix == ".png")
							imagepng($dst_img,$uploadfile);
				}
				return $uploadfile;
		}
		if($notice)
				FI_alert('ʧ��',"/");
		else
				return false;
		
}

/*
*/	
//function FI_UNRarImgUpLoad($upload_name,$dir,$backurl,$notice)
//{
//		$limittyppe	=	array('.jpg','.gif','.png');
//		$file_name = $_FILES[$upload_name]['name'];
//		$file_postfix = strtolower(substr($file_name,strrpos($file_name,".")));
//		$uploadfile = $dir.$file_postfix;
//		/*
//		*/
//		if(!in_array($file_postfix,$limittyppe))
//		{
//				if($notice)
//						FI_alert("���ύjpg,gif,png��ʽ��ͼƬ��",$backurl);
//				else
//						return false;
//		}
//		if($_FILES[$upload_name]['size']>2048000 || $_FILES[$upload_name]['size'] == 0)
//		{
//				if($notice)
//						FI_alert("ͼƬ���ܳ���2M��",$backurl);
//				else
//						return false;
//		}
//		/*
//		*/
//		if (move_uploaded_file($_FILES[$upload_name]['tmp_name'], $uploadfile)) 
//			return $uploadfile;
//			
//		if($notice)
//			FI_alert('ʧ��',"/");
//		else
//			return false;
//		
//}
/*
*/	
function FI_ComImgUpLoad($tablename,$data)
{
		$thetable = FLEA::getSingleton($tablename);
		$limittyppe	=	array('.jpg','.gif','.png','.bmp');
		$uploaddir ='lichiimgs/';	
		foreach($_FILES as $upload_name => $val)
		{
				$file_name = $_FILES[$upload_name]['name'];
				if($file_name != "")
				{
						$file_postfix = strtolower(substr($file_name,strrpos($file_name,".")));
						$newname	=	$uploaddir.$upload_name.rand(1000,9999).time().$file_postfix;
						//�ļ����ͼ��
						if(in_array($file_postfix,$limittyppe))
						{
							//������޸���Ϣ
							if($data[$thetable->primaryKey]!="" and $file_name != "")
							{
								$trow	=	$thetable->find($data[$thetable->primaryKey]);
								if($trow[$upload_name] !="")
									unlink($trow[$upload_name]);
							}
							$temp_name = $_FILES[$upload_name]['tmp_name']; 		 
							$result = move_uploaded_file($temp_name,$newname);	
							$data[$upload_name]	=	$newname;		
						}
				}
				
		}
		return $data;
}
/*
*/	
function FI_CutImgUpLoad($src,$targ_x,$targ_y,$targ_w,$targ_h,$backurl,$notice,$fd_w = "",$fd_h = "")
{
		  $jpeg_quality = 100;
		  $file_postfix = strtolower(substr($src,strrpos($src,".")));
		  if($file_postfix == ".gif")
			  $img_r = imagecreatefromgif($src);
		  elseif($file_postfix == ".jpg")
			  $img_r = imagecreatefromjpeg($src);
		  elseif($file_postfix == ".png")
			  $img_r = imagecreatefrompng($src);
		  else
			  FI_alert("���ύjpg,gif,png��ʽ��ͼƬ��",$backurl);
		  /*����
		  */
		  $dst_r = imagecreatetruecolor( $targ_w,$targ_h );
		  $suc = imagecopymerge ($dst_r,$img_r,0,0,$targ_x,$targ_y,$targ_w,$targ_h,$jpeg_quality);
		  
		  if($fd_w != "" && $fd_w != "" )
		  {
		  		$new_r = imagecreatetruecolor( $fd_w,$fd_h );
				$suc = imagecopyresampled($new_r,$dst_r,0,0,0,0,$fd_w,$fd_h,$targ_w,$targ_h);
				$dst_r = $new_r;
		  }
		  if($suc)
		  {
				  if($file_postfix == ".gif")
						  $suc = imagegif($dst_r,$src);
				  elseif($file_postfix == ".jpg")
						  $suc = imagejpeg($dst_r,$src,$jpeg_quality);
				  elseif($file_postfix == ".png")
						  $suc = imagepng($dst_r,$src);
				  return $src;
		  }
		if($notice)
				FI_alert('ʧ��',"/");
		else
				return false;
}
/*�ַ�����
*/
function Fi_SempleConvertChars($text)
{
		$text = str_ireplace("rgb","'rgb'",$text);
		$text = str_ireplace("script","'script'",$text);
	    $text = str_ireplace("#","'#'",$text);
	    $text = str_ireplace("@","'@'",$text);
	    $text = str_ireplace("$","'$'",$text);
	    $text = str_ireplace("%","'%'",$text);
	    $text = str_ireplace("^","'^'",$text);
	    $text = str_ireplace("*","'*'",$text);
	    $text = str_ireplace("|","'|'",$text);
		return $text;
			 
}
/*�ַ�����
*/
function Fi_ConvertChars($text)
{
		$text = str_ireplace("rgb","'rgb'",$text);
		$text = str_ireplace("script","'script'",$text);
	    $text = str_ireplace("class","'class'",$text);
	    $text = str_ireplace("style","'style'",$text);
	    $text = str_ireplace("span","h",$text);
	    $text = str_ireplace("\r\n"," ",$text);
	    $text = str_ireplace("&nbsp;"," ",$text);
		
	    $text = FI_comCov($text);
			 
		return $text;
}
/*�ַ�����
*/
function Fi_ConvertSPchar($arr,$name)
{
		$temp = 0;
		foreach($arr as $data)
		{
			 $arr[$temp][$name] = str_ireplace("rgb","'rgb'",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("script","'script'",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("\r\n"," ",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("&nbsp;"," ",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("class","'class'",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("style","'style'",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("span","p",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("strong","p",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("h1","h",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("h2","h",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("h3","h",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("h4","h",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("h5","h",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("h6","h",$arr[$temp][$name]);
//			 $arr[$temp][$name] = preg_replace("/<img [\d\w=\"\'_: \/\.]*>/"," ",$arr[$temp][$name]);
	    	 $arr[$temp][$name] = FI_comCov($arr[$temp][$name]);
			 $temp++;
		}
		return $arr;
}
/*�ַ�����
*/
function FI_comCov($text)
{
	    $text = str_ireplace("#","'#'",$text);
	    $text = str_ireplace("@","'@'",$text);
	    $text = str_ireplace("$","'$'",$text);
	    $text = str_ireplace("%","'%'",$text);
	    $text = str_ireplace("^","'^'",$text);
//	    $text = str_ireplace("&","'&'",$text);
	    $text = str_ireplace("*","'*'",$text);
	    $text = str_ireplace("|","'|'",$text);
		return $text;
}


/*�����ֶΣ���̳ͷ��
*/
function Fi_CdbmembersSet($arr,$cmn = "")
{
		  $tableCdbmembers = FLEA::getSingleton('Table_Cdbmembers');
		  $temp = 0;
		  foreach($arr as $data)
		  {
				  $oneData = $tableCdbmembers->_getUserByUsername($data['uname']);
				  $arr[$temp]['groupid'] = $oneData['groupid'];
				  $arr[$temp]['cdbmem_id'] = $oneData['uid'];
				  if($arr[$temp]['groupid']==4)
				  {
				  		$arr[$temp]['content'] = "���û���Ϣ�ѱ�����";
				  }
				  /*
				  */
				  if($data['head_img'] == "")		
				  {
						  $tableCdbmemberfields = FLEA::getSingleton('Table_Cdbmemberfields');
						  $cdbmfi = $tableCdbmemberfields->_getUserByUID($oneData['uid']);
						  if(strstr($cdbmfi['avatar'], "http"))
								  $arr[$temp]['head_img'] = $cdbmfi['avatar'];
						  else
						  {
								  if($cdbmfi['avatar'])
									  	$arr[$temp]['head_img'] = "http://bbs.we54.com/".$cdbmfi['avatar'];
								  else
									 	$arr[$temp]['head_img'] = "images/defhead.jpg";
						  }
				  }	
				  /*
				  */
				  if($cmn)
					 	$arr[$temp][$cmn] = str_ireplace("\r\n"," ",$data[$cmn]);
				  $temp += 1;
		  }
		  return $arr;
}

function Fi_cut_str($sourcestr,$cutlength) 
{ 
		   $returnstr=''; 
		   $i=0; 
		   $n=0; 
		   $str_length=strlen($sourcestr);//�ַ������ֽ��� 
		   while (($n<$cutlength) and ($i<=$str_length)) 
		   { 
			  $temp_str=substr($sourcestr,$i,1); 
			  $ascnum=Ord($temp_str);//�õ��ַ����е�$iλ�ַ���ascii�� 
			  if ($ascnum>=224)    //���ASCIIλ����224��
			  { 
				 $returnstr=$returnstr.substr($sourcestr,$i,3); //����UTF-8����淶����3���������ַ���Ϊ�����ַ�         
				 $i=$i+3;            //ʵ��Byte��Ϊ3
				 $n++;            //�ִ����ȼ�1
			  }
			  elseif ($ascnum>=192) //���ASCIIλ����192��
			  { 
				 $returnstr=$returnstr.substr($sourcestr,$i,2); //����UTF-8����淶����2���������ַ���Ϊ�����ַ� 
				 $i=$i+2;            //ʵ��Byte��Ϊ2
				 $n++;            //�ִ����ȼ�1
			  }
			  elseif ($ascnum>=65 && $ascnum<=90) //����Ǵ�д��ĸ��
			  { 
				 $returnstr=$returnstr.substr($sourcestr,$i,1); 
				 $i=$i+1;            //ʵ�ʵ�Byte���Լ�1��
				 $n++;            //�������������ۣ���д��ĸ�Ƴ�һ����λ�ַ�
			  }
			  else                //��������£�����Сд��ĸ�Ͱ�Ǳ����ţ�
			  { 
				 $returnstr=$returnstr.substr($sourcestr,$i,1); 
				 $i=$i+1;            //ʵ�ʵ�Byte����1��
				 $n=$n+0.5;        //Сд��ĸ�Ͱ�Ǳ���������λ�ַ���...
			  } 
		   } 
				 if ($str_length>$cutlength){
				  $returnstr = $returnstr . "..";//��������ʱ��β������ʡ�Ժ�
			  }
			return $returnstr; 
}

function Fi_incresActiveNum($username = "" ,$num = "1",$limit = "")
{
			$Table_LichiShowIps = FLEA::getSingleton('Table_LichiShowIps');
			if(!$username)
					$username = $_SESSION["Zend_Auth"]['storage']->username;
			$Table_LichiShowIps->incresActiveNum($username,$num,$limit);
}
function Fi_alertShortMsg()
{
			$Table_Cdbpms = FLEA::getSingleton('Table_Cdbpms');
			$suc = $Table_Cdbpms->find("folder = 'inbox' and msgtoid =".$_SESSION["Zend_Auth"]['storage']->uid);
			return $suc;
}
function Fi_SendShortMsg($msgtoid = "",$msgfrom = "",$msgfromid = "",$title = "",$msg = "")
{
			$dat['msgtoid'] = $msgtoid;
			$dat['msgfrom'] = $msgfrom;
			$dat['msgfromid'] = $msgfromid;
			$dat['folder'] = "inbox";
			$dat['new'] = 1;
			$dat['subject'] = $title;
			$dat['dateline'] = time();
			$dat['message'] = $msg;
			$dat['delstatus'] = 0;
			$Table_Cdbpms = FLEA::getSingleton('Table_Cdbpms');
			$suc = $Table_Cdbpms->save($dat);
			return $suc;
}

function Fi_upshowsdata($data)
{
			$tableModaShows = FLEA::getSingleton('Table_LichiShows');
			$suc = $tableModaShows->updateByConditions("username = '".$data['username']."'",$data);
}


function authcode($string, $operation, $key = '') {

	$auth_key = md5('71c978FGaY8GIIUy'.$_SERVER['HTTP_USER_AGENT']);  
	$key = md5($key ? $key : $auth_key);
	$key_length = strlen($key);

	$string = $operation == 'DECODE' ? base64_decode($string) : substr(md5($string.$key), 0, 8).$string;
	$string_length = strlen($string);

	$rndkey = $box = array();
	$result = '';

	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($key[$i % $key_length]);
		$box[$i] = $i;
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if(substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)) {
			return substr($result, 8);
		} else {
			return '';
		}
	} else {
		return str_replace('=', '', base64_encode($result));
	}

}


function daddslashes($string, $force = 0) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force);
			}
		} else {
			$string = addslashes($string);
		}
	}
	return $string;
}




function FI_get_session_by_cookie()
{
			$_DCOOKIE = $_COOKIE;
			list($discuz_pw, $discuz_secques, $discuz_uid) = empty($_DCOOKIE['cdb_auth']) ? array('', '', 0) : daddslashes(explode("\t", authcode($_DCOOKIE['cdb_auth'], 'DECODE')), 1);
			$cdb_uid = $discuz_uid;
			if($discuz_uid)
			{
					$Table_We54userview = FLEA::getSingleton('Table_We54userview');
					$one = $Table_We54userview->find("cdb_uid = '".$cdb_uid."'");
					if($one)
					{
							foreach($one as $key => $value)
							{
									$_SESSION['Zend_Auth']['storage']->$key = $value; 
							}
					}
			}
		
}














?>