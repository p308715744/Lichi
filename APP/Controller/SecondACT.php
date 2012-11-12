<?php
include 'Fun_Include.php';
FLEA::loadClass('Controller_BoBase');

class Controller_SecondACT extends Controller_BoBase
{
	
	function Controller_SecondACT()
	{
			FI_get_session_by_cookie();
	}
	
	
	function actionDeleteDiscuss()
	{
				$visitUser_id = (int)$_GET['user_id'];
				$show_id = (int)$_GET['show_id'];
				$discuss_id = (int)$_GET['discuss_id'];
				FI_CheckAdminRoot();
				/*
				*/
				$TableModaShowDiscuss = FLEA::getSingleton('Table_LichiShowDiscuss');
				$suc = $TableModaShowDiscuss->deleteByDiscussId($discuss_id);
		
				$tableModaShows = FLEA::getSingleton('Table_LichiShows');
				$showData = $tableModaShows->getShowByShowId($show_id);	
				$showData['discuss_count'] -= 1;
				$tableModaShows->updatetar($showData);
				
				FI_changePage("?Controller=SecondACT&action=ShowDiscuss&user_id=".$visitUser_id."&show_id=".$show_id);
	}
	
	/*
	*/
	function actionShowDelete()
	{
				$user_id = (int)$_GET['user_id'];
				$show_id = (int)$_GET['show_id'];
				$showd = FI_ShowAdimnRoot($show_id,1);
				/*
				*/
				$tableModaShow = FLEA::getSingleton('Table_LichiShows');
				$rest = $tableModaShow->find("show_id='".$show_id."'");
				$tableModaUser = FLEA::getSingleton('Table_LichiUser');
				$result = $tableModaUser->find("user_id='".$rest['user_id']."'");
				$result['user_id'] = $rest['user_id'];
				$result['showcount'] = $result['showcount'] -1 ;
				$tableModaUser->update($result);
				$tableModaShow->deleteByShowId($show_id);
				FI_changePage("?Controller=SecondACT&action=ShowPosts&id=".$user_id);
    }
	/*
	*/
	function actionShowPosts()
	{
				$tmpid = (int)$_GET['id'];
				if($tmpid == "")
				{
						$temp = FI_TestSysUser($_SESSION["Zend_Auth"]['storage']->username,0,1,1);
						FI_changePage("?Controller=SecondACT&action=ShowPosts&id=".$temp['user_id']);
				}
				$visitUser_id = $tmpid;
				$result = FI_TestSysUser("",$tmpid,1,1);
				/*
				*/		
				$tableModaShows = FLEA::getSingleton('Table_LichiShows');
				$showData = $tableModaShows->getShowByUserName($result['username']);
				$shows = $showData;
				$mainshows = $tableModaShows->getMainShowByReallId($tmpid);
				/*
				*/
				$pagesize	=	11;
			  	$conditions	=	"user_id = '".$tmpid."'and available = 1 and main = 0";
				$sortby		=	"dateline desc";
				FLEA::loadClass('Lib_NewPager');
				$page	=	new Lib_NewPager( $tableModaShows, $pagesize, $conditions , $sortby );
				$rowset	=	$page->rowset;	
				include(APP_DIR . '/LichiViews/ShowPosts.html');		
    }
	
	/*
	*/
	function actionPostCreateShowFace()
	{
				$tmpid = (int)$_POST['user_id'];
				if((int)$_POST['user_id'] == "")
					FI_alert("缺少参数","?Controller=SecondACT&action=CreateShowFace");

				$visitUser_id = $tmpid;
				$result = FI_TestSysUser("",$tmpid,1,1);
				FI_CheckCommonRight($result['username'],1);
				/*
				*/
				$tableModaShows = FLEA::getSingleton('Table_LichiShows');
				if(strlen($_POST['title']) < 1 || strlen($_POST['title']) > 50 )
						FI_alert("标题不能少于2个字符,不能超过50个字符","?Controller=SecondACT&action=CreateShowFace");
				if($_POST['title'] == '')
						FI_alert("请输入标题！","?Controller=SecondACT&action=CreateShowFace");
				else
						$showsData['title'] = Fi_ConvertChars($_POST['title']);
				/*
				*/
				if((int)$_POST['show_id'] == "")
				{
						$dataShow = $tableModaShows->getTempShowByUserName($result['username']);
						$showsData['user_id']	= $result['user_id'];
						$showsData['nickname']	= $result['nickname'];
						$showsData['truename']	= $result['truename'];
						$showsData['username'] = $result['username'];
						$showsData['text']	=	Fi_SempleConvertChars($_POST['content']);
						if(strlen($showd['text']) > 1500 )
								FI_alert("介绍不能超过1500个字符","?Controller=SecondACT&action=CreateShowFace");
						if($dataShow)
								$tableModaShows->deleteByShowId($dataShow['show_id']);
						$show_id = $tableModaShows->save_pr($showsData);
				}
				else
				{
						FI_ShowAdimnRoot((int)$_POST['show_id'],1);
						/*
						*/
						$show_id = (int)$_POST['show_id'];
						$showd = FI_ShowAdimnRoot($show_id,1);
						$showd['title'] = Fi_ConvertChars($_POST['title']);
						$showd['text']	=	Fi_SempleConvertChars($_POST['content']);
						if(strlen($showd['text']) > 1500 )
								FI_alert("介绍不能超过1500个字符","?Controller=SecondACT&action=CreateShowFace");
						$tableModaShows->save_pr($showd);
				}
				Fi_incresActiveNum("",2,true);
				if($_POST['jump'])
					FI_changePage("?Controller=SecondACT&action=ShowPosts&id=".$visitUser_id);
				else
					FI_changePage("?Controller=SecondACT&action=PrePicUpload&user_id=".$visitUser_id."&show_id=".$show_id);
	}
	/*
	*/
	function actionCreateShowFace()
	{
				$tmpid = (int)$_GET['user_id'];
				if($tmpid == "")
				{
						$temp = FI_TestSysUser($_SESSION["Zend_Auth"]['storage']->username,0,1,1);
						FI_changePage("?Controller=SecondACT&action=CreateShowFace&user_id=".$temp['user_id']);
				}
				$visitUser_id = $tmpid;
				$result = FI_TestSysUser("",$tmpid,1,1);
				FI_CheckCommonRight($result['username'],1);
				/*
				*/
				include(APP_DIR . '/LichiViews/CreateShowFace.html');		
    }
	/*
	*/
	function actionPrePicUpload()
	{
				if($_GET['show_id'])
					$show_id = (int)$_GET['show_id'];
				if($_GET['user_id'])
					$user_id = (int)$_GET['user_id'];
					
				if($_POST['show_id'])
					$show_id = (int)$_POST['show_id'];
				if($_POST['user_id'])
					$user_id = (int)$_POST['user_id'];
				/*
				*/
				$visitUser_id = $user_id;
				$result = FI_TestSysUser("",$user_id,1,1);
				/*
				*/
				$showd = FI_ShowAdimnRoot($show_id,1);
				/*
				*/
				$show_img = $showd['show_img'];
				$temp_img = $showd['temp_img'];
				$dst_w = 800;
				$dst_h = 600;
				if($this->_isPost())
				{
							$urllink = "?Controller=SecondACT&action=PrePicUpload&user_id=".$user_id."&show_id=".$show_id;
							$upload_name = 'file_tmp';
							$pathname = 'showimgs/'."temp_img".strtotime(date('Y-m-d H:i:s')).rand(1000,9999);
							$uploadfile = FI_ImgUpLoad($upload_name,$pathname,$dst_w,$dst_h,$urllink,1,1);
							if($uploadfile)
							{
									if($showd['temp_img'])
										unlink($showd['temp_img']);
									$showd['temp_img']	=	$uploadfile;
									$temp_img = $uploadfile;
									$tableModaShows = FLEA::getSingleton('Table_LichiShows');
									$suc = $tableModaShows->save_pr($showd);
									FI_changePage($urllink);
							}
				  }
				include(APP_DIR."/LichiViews/PrePicUpload.html");
	}
	/*
	*/
	function actionPostFacePicUpload()
	{
				if((int)$_POST['show_id'] == "")
					FI_alert("缺少参数","/");
				if((int)$_POST['user_id'] == "")
					FI_alert("缺少参数","/");
		
				if($_POST['show_id'])
					$show_id = (int)$_POST['show_id'];
				if($_POST['user_id'])
					$user_id = (int)$_POST['user_id'];
				$visitUser_id = $user_id;
				$showd = FI_ShowAdimnRoot($show_id,1);
				/*
				*/
				$backurl = "?Controller=SecondACT&action=PrePicUpload&user_id=".$user_id."&show_id=".$show_id;
				$targ_x = $_POST['x']*$_POST['rw']/$_POST['pw'];
				$targ_y = $_POST['y']*$_POST['rh']/$_POST['ph'];
				$targ_w = $_POST['w']*$_POST['rw']/$_POST['pw'];
				$targ_h = $_POST['h']*$_POST['rh']/$_POST['ph'];
				$fd_w = 220;
				$fd_h = 160;
				/*
				*/
				if($showd['temp_img'] == "")
					FI_alert("请选择图片，剪切后上传！",$backurl);
				$src = $_POST['bigImage'];
				if(!$src)
					FI_alert("参数错误！",$backurl);
				$src = FI_CutImgUpLoad($src,$targ_x,$targ_y,$targ_w,$targ_h,$backurl,1,$fd_w,$fd_h);
				/*
				*/	
				$tableModaShows = FLEA::getSingleton('Table_LichiShows');
				if($showd['show_img'])
					unlink($showd['show_img']);
				$showd['show_img'] = $src;
				$showd['temp_img'] = "";
				$showd['available'] = 1;
				if(!$showd['dateline'])
				$showd['dateline'] = time();
				$showd['public'] = 1;
				$tableModaShows->save_pr($showd);
				Fi_incresActiveNum("",2,true);
				FI_changePage("?Controller=SecondACT&action=ShowsEidt&user_id=".$user_id."&show_id=".$show_id);
	
	}
	/*
	*/
	function actionShowDetail()
	{
				$visitUser_id = (int)$_GET['user_id'];	
				$show_id = (int)$_GET['show_id'];
				$shows = FI_ShowExist($show_id,1);
				$result = FI_TestSysUser("",$visitUser_id,1,1);
				/*
				*/	
				$tableModaShows = FLEA::getSingleton('Table_LichiShows');
				$tableModaAttach = FLEA::getSingleton('Table_LichiAttachments');
				if($_GET['set'] == 1)
				{
						$sd = $tableModaShows->find("user_id = ".$visitUser_id." and show_id > ".$show_id." and public = 1 and available = 1  order by dateline asc ");
						if(!$sd)
						{
								$sd = $tableModaShows->find("user_id = ".$visitUser_id." and public = 1 and available = 1  order by dateline asc ");
						}
						
				}
				elseif($_GET['set'] == 2)
				{
						$sd = $tableModaShows->find("user_id = ".$visitUser_id." and show_id < ".$show_id." and public = 1 and available = 1  order by dateline desc ");
						if(!$sd)
						{
								$sd = $tableModaShows->find("user_id = ".$visitUser_id." and public = 1 and available = 1  order by dateline desc ");
						}
				}
				if($sd)
				{
						FI_changePage("?Controller=SecondACT&action=ShowDetail&user_id=".$visitUser_id."&show_id=".$sd['show_id']);
				}
				else	
						$AttachmentsData = $tableModaAttach->getAttachAllByShowId($show_id);
				$shows['views']+=3;
				$tableModaShows->updatetar($shows);
				include(APP_DIR . '/LichiViews/ShowDetail.html');
	}
	/*
	*/
	function actionShowFaceEidt()
	{
				$visitUser_id = (int)$_GET['user_id'];
				$show_id = (int)$_GET['show_id'];
				$result = FI_TestSysUser("",$visitUser_id,1,1);
				$showData = FI_ShowAdimnRoot($show_id,1);
				include(APP_DIR . '/LichiViews/ShowFaceEidt.html');	
    }
	/*
	*/
	function actionShowsEidt()
	{
				$show_id = (int)$_GET['show_id'];
				$tmpid = (int)$_GET['user_id'];
				$visitUser_id = $tmpid;
				$result = FI_TestSysUser("",$tmpid,1,1);
				$showd = FI_ShowAdimnRoot($show_id,1);
				$shows = $showd;
				/*
				*/
				$tableModaAttach = FLEA::getSingleton('Table_LichiAttachments');
				$AttachmentsData = $tableModaAttach->getAttachAllByShowId($show_id);
				//include(APP_DIR . '/showView/ShowsEidt.html');	
				include(APP_DIR . '/LichiViews/ShowsEidt.html');	
				//include(APP_DIR . '/LichiViews/publish_cont.html');	
    }
	/*
	*/
	function actionPostShowsEidt()
	{
				if((int)$_POST['show_id'] == "")
					FI_alert("缺少参数","/");
				if((int)$_POST['user_id'] == "")
					FI_alert("缺少参数","/");
				$show_id = (int)$_POST['show_id'];
				$tmpid = (int)$_POST['user_id'];
				$visitUser_id = $tmpid;
				$showd = FI_ShowAdimnRoot($show_id,1);
				/*
				*/
				$urllink = "?Controller=SecondACT&action=showsEidt&user_id=".$visitUser_id."&show_id=".$show_id;
				$AttachData['show_id'] = $show_id;
				$AttachData['attach_id'] = (int)$_POST['attach_id'];
				$AttachData['atc_type'] = (int)$_POST['atc_type'];
				$atc_type = $AttachData['atc_type'];
				
				if(strlen($_POST['content']) > 1800 )
						FI_alert("文字不能超过1800个字符",$urllink);
				
				if($atc_type == 1)
				{
						if(strlen($_POST['content']) < 10 )
								FI_alert("文字不能少于10个字符",$urllink);
						$AttachData['content'] = Fi_ConvertChars($_POST['content']);
				}
				if($atc_type == 2)
				{
						$AttachData['content'] = Fi_ConvertChars($_POST['content']);
						$attach_id = (int)$_POST['attach_id'];
						$tableModaAttach = FLEA::getSingleton('Table_LichiAttachments');
						$attach = $tableModaAttach->getAttachByAttachId($attach_id);
						$upload_name = $_POST['file_name'];
						if($_FILES[$upload_name]['name'] != "")
						{
								if($attach['img_url'])
									unlink($attach['img_url']);
								$pathname = 'attachimgs/'.strtotime(date('Y-m-d H:i:s')).$_SESSION["Zend_Auth"]['storage']->username;
								$uploadfile = FI_ImgUpLoad($upload_name,$pathname,714,1100,$urllink,1);
								$AttachData['img_url'] = $uploadfile;
						}
				}
				if($atc_type == 3)
				{
						$AttachData['img_url'] = $_POST['img_url'];
						$AttachData['content'] = Fi_ConvertChars($_POST['content']);
				}
				$tableModaAttach = FLEA::getSingleton('Table_LichiAttachments');
				$id = $tableModaAttach->upload_par($AttachData);
				Fi_incresActiveNum("",2,true);
				FI_changePage($urllink);						
	}
	/*
	*/
	function actionIndexInsertAttachment()
	{
				$show_id = (int)$_GET['show_id'];
				$tmpid = (int)$_GET['user_id'];
				$visitUser_id = $tmpid;
				$result = FI_TestSysUser("",$tmpid,1,1);
				$showd = FI_ShowAdimnRoot($show_id,1);
				$shows = $showd;
				
				$arc_type = (int)$_GET['type'];
				if($arc_type == 1)
				include(APP_DIR . '/LichiViews/wordinsert.html');		
				if($arc_type == 2)
				include(APP_DIR . '/LichiViews/picinsert.html');		
				if($arc_type == 3)
				include(APP_DIR . '/LichiViews/videoinsert.html');		
	}
	/*
	*/
	function actionInsertAttachment()
	{
				$show_id = (int)$_POST['show_id'];
				$visitUser_id = (int)$_POST['user_id'];
				$showd = FI_ShowAdimnRoot($show_id,1);
				/*
				*/
				$urllink = "?Controller=SecondACT&action=showsEidt&user_id=".$visitUser_id."&show_id=".$show_id;
				$tableModaAttach = FLEA::getSingleton('Table_LichiAttachments');	
				$AttachData['atc_type'] = (int)$_POST['atc_type'];
				$atc_type = (int)$_POST['atc_type'];
				if(strlen($_POST['content']) > 1800 )
						FI_alert("文字不能超过1800个字符",$urllink);
				if($atc_type == 1)
				{
						$AttachData['atc_type'] = $atc_type;
						$AttachData['content'] = Fi_ConvertChars($_POST['content']);
						if(strlen($_POST['content']) < 10 )
								FI_alert("文字不能少于10个字符","?Controller=SecondACT&action=IndexInsertAttachment&user_id=".$visitUser_id."&show_id=".$show_id."&type=1");
				}
				
				if($atc_type == 2)
				{
						$AttachData['atc_type'] = $atc_type;
						$AttachData['content'] = Fi_ConvertChars($_POST['content']);
						$upload_name = 'upload';
						$pathname = 'attachimgs/'.strtotime(date('Y-m-d H:i:s')).$_SESSION["Zend_Auth"]['storage']->username;
						$uploadfile = FI_ImgUpLoad($upload_name,$pathname,714,1100,$urllink,1);
						if($uploadfile)
								$AttachData['img_url'] = $uploadfile;
				}
				if($atc_type == 3)
				{
						$AttachData['atc_type'] = $atc_type;
						$AttachData['img_url'] = Fi_ConvertChars($_POST['img_url']);
						$AttachData['content'] = Fi_ConvertChars($_POST['content']);
				}
				$AttachData['show_id'] = $show_id;
				$AttachData['dateline'] = time();
				$id = $tableModaAttach->save_per($AttachData);
				FI_changePage($urllink);						
	}
	/*
	*/
	function actionAttachDelete()
	{
				$show_id = (int)$_GET['show_id'];
				$visitUser_id = (int)$_GET['user_id'];		
				$attach_id = (int)$_GET['attach_id'];
				$showd = FI_ShowAdimnRoot($show_id,1);
				/*
				*/
				$tableModaAttach = FLEA::getSingleton('Table_LichiAttachments');
				$tableModaAttach->deleteByAttachId((int)$_GET['attach_id']);
				FI_changePage("?Controller=SecondACT&action=showsEidt&user_id=".$visitUser_id."&show_id=".$show_id);						
    }
	/*公开，暂时不用
	*/
	function actionManageShows()
	{
//				$show_id = (int)$_GET["show_id"];
//				$user_id = (int)$_GET["user_id"];
//				$showd = FI_ShowAdimnRoot($show_id,1);
//				$data['show_id'] = $show_id;
//				$data['public']	= (int)$_GET['do']%2;
//				$tableModaShows = FLEA::getSingleton('Table_LichiShows');
//				$showData = $tableModaShows->updatetar($data);
//				FI_changePage("?Controller=SecondACT&action=showsEidt&user_id=".$user_id."&show_id=".$show_id);						
    }
	/*
	*/
	function actionListShowDiscuss()
	{
				$visitUser_id = (int)$_GET['user_id'];
				$result = FI_TestSysUser("",$visitUser_id,1,1);
				/*
				*/
				$tableModaShowDiscuss = FLEA::getSingleton('Table_LichiShowDiscuss');
				$pagesize	=	10;
			  	$conditions	=	"user_id=".$visitUser_id;
				$sortby		=	"dateline DESC";
				FLEA::loadClass('Lib_NewPager');
				$page	=	new Lib_NewPager( $tableModaShowDiscuss, $pagesize, $conditions , $sortby );
				$ShowDiscuss	=	$page->rowset;
				/*
				*/								
				$ShowDiscuss = Fi_CdbmembersSet($ShowDiscuss);
				
				/*
				*/
				$tableModaShow = FLEA::getSingleton('Table_LichiShows');
				$add = $tableModaShow->find("user_id = ".$visitUser_id." order by dateline desc");
				$show_id = $add['show_id'];
				include(APP_DIR . '/LichiViews/ListShowDiscuss.html');		
    }	
	/*
	*/
	function actionShowDiscuss()
	{
		FI_alert("评论功能暂时关闭！");
		
				$visitUser_id = (int)$_GET['user_id'];
				$show_id = (int)$_GET['show_id'];
				$shows = FI_ShowExist($show_id,1);
				$result = FI_TestSysUser("",$visitUser_id,1,1);
				/*
				*/
				$tableModaShowDiscuss = FLEA::getSingleton('Table_LichiShowDiscuss');
				$ShowDiscuss = $tableModaShowDiscuss->getShowDiscussByShowId($show_id);
				/*
				*/								
				$ShowDiscuss = Fi_CdbmembersSet($ShowDiscuss);
				include(APP_DIR . '/LichiViews/ShowDiscuss.html');		
    }	
	/*
	*/
	function actionEditShowDiscuss()
	{
				$visitUser_id = (int)$_GET['user_id'];
				$show_id = (int)$_GET['show_id'];
				$shows = FI_ShowExist($show_id,1);
				$result = FI_TestSysUser("",$visitUser_id,1,1);
				$discuss_id = (int)$_GET['discuss_id'];
				/*
				*/
				$tableModaShowDiscuss = FLEA::getSingleton('Table_LichiShowDiscuss');
				$ShowDiscuss = $tableModaShowDiscuss->getShowDiscussByID($discuss_id);
		
				include(APP_DIR . '/LichiViews/EditShowDiscuss.html');		
	}
	/*
	*/
	function actionPostShowDiscuss()
	{
				$usr = FI_TestSysUser($_SESSION["Zend_Auth"]['storage']->username,"",1,0);
				if(!$usr)
					$usr = FI_TestComUser($_SESSION["Zend_Auth"]['storage']->username,"","",1);
				/*
				*/
				$show_id = (int)$_POST['show_id'];
				$user_id = (int)$_POST['user_id'];
				if((int)$_POST['show_id'] == "")
					FI_alert("缺少参数","/");
				if((int)$_POST['show_id'] == "")
					FI_alert("缺少参数","/");
				$Content = Fi_ConvertChars($_POST['content']);
				$reContent = Fi_ConvertChars($_POST['recontent']);
				if(strlen($Content) < 6 || strlen($Content) > 800 )
						FI_alert("回复少于6个字符,不能超过800个字符","?Controller=SecondACT&action=ShowDiscuss&user_id=".$user_id."&show_id=".$show_id);
				if($Content == "")
						FI_alert("回复不能为空","?Controller=SecondACT&action=ShowDiscuss&user_id=".$user_id."&show_id=".$show_id);
				$tableModaShowDiscuss = FLEA::getSingleton('Table_LichiShowDiscuss');
				if($_POST['discuss_id']!='')
				{
						$discussData['discuss_id'] = (int)$_POST['discuss_id'];
						$discussData['call_id'] = "";
						FI_DiscussAdimnRoot($discussData['discuss_id'],1);
				}
				else
				{
						$discussData['dateline'] = time();
				}
				$discussData['user_id'] = $user_id;
				$discussData['show_id'] = $show_id;
				$discussData['uname'] = $_SESSION["Zend_Auth"]['storage']->username;
				$discussData['nickname'] = $_SESSION["Zend_Auth"]['storage']->nickname;
				$discussData['head_img'] = $usr['head_img'];
				$discussData['content'] = $Content;
				$discussData['recontent'] = $reContent;
				$id = $tableModaShowDiscuss->savetar($discussData);
				FI_changePage("?Controller=SecondACT&action=ShowDiscuss&user_id=".$user_id."&show_id=".$show_id);						
	}
	/*高亮，暂时不用
	*/	
	function actionPostTitleLight()
	{
//				$visitUser_id = (int)$_POST["user_id"];//被访问者
//				$color = htmlspecialchars(Fi_ConvertChars($_POST['highL']));
//				$clubData['title'] = htmlspecialchars(Fi_ConvertChars($_POST['title']));
//				$clubData['title'] = $clubData['title'].'^?'.$color;
//				$clubData['club_id'] = (int)$_POST['club_id'];
//				$tableModaClub = FLEA::getSingleton('Table_lichiClub');
//				$clb = $tableModaClub->getDetailByClubId((int)$_POST['club_id']);
//				$tableModaClub->updatetar($clubData);
	}
	/*
	*/
	function actionArchives()
	{
				$user_id = $_GET['id'];
				if($user_id == "")
				{
						$temp = FI_TestSysUser($_SESSION["Zend_Auth"]['storage']->username,0,1,1);
						FI_changePage("?Controller=SecondACT&action=Archives&id=".$temp['user_id']);
				}
				$visitUser_id = $user_id;
				$result = FI_TestSysUser("",$user_id,1,1);		
				/*
				*/		
				if($_GET['edit'])
						include(APP_DIR."/LichiViews/about meEdit.html");
				else
						include(APP_DIR."/LichiViews/about me.html");
	}
	/*
	*/
	function actionPostArchives()
	{
				if((int)$_POST['user_id'] == "")
					FI_alert("缺少参数","?Controller=SecondACT&action=Archives&id=".(int)$_POST['user_id']."&edit=1");
				$result = FI_TestSysUser("",(int)$_POST['user_id'],1,1);
				FI_CheckCommonRight($result['username'],1);
				/*
				*/
				foreach($_POST as $key=>$val)
				{
						if($key == "birthdate")
							$row[$key]	=	$_POST[$key];
						else if($key == "height")
							$row[$key]	=	substr((int)$_POST[$key],0,3);
						else if($key == "weight")
							$row[$key]	=	substr((int)$_POST[$key],0,3);
						else if($key == "msg")
						{
							$row[$key]	=	Fi_ConvertChars($_POST[$key]);
							if(strlen($row[$key]) > 1900 )
									FI_alert("文字不能超过1900个字符","?Controller=SecondACT&action=Archives&id=".(int)$_POST['user_id']."&edit=1");
						}
						else	
							$row[$key]	=	Fi_ConvertChars($_POST[$key]);
				}
				$upload_name  = "img_1";
				if($_FILES[$upload_name]['name'] != "")
				{
						$pathname = 'lichiimgs/'."perheadimg".strtotime(date('Y-m-d H:i:s')).rand(1000,9999);
						$urllink = "?Controller=SecondACT&action=Archives&id=".(int)$_POST['user_id']."&edit=1";
						$uploadfile = FI_ImgUpLoad($upload_name,$pathname,651,383,$urllink,1);
						$tableLichiDoor = FLEA::getSingleton('Table_LichiDoor');
						$datar = $tableLichiDoor->getatest((int)$_POST['user_id']);
						if($datar)
						{
								if($datar[$upload_name])
										unlink($datar[$upload_name]);
						}
						else
								$datar['user_id'] = (int)$_POST['user_id'] ;
						$datar[$upload_name] = $uploadfile ;
						$suc = $tableLichiDoor->save_per($datar);
				}
				Fi_incresActiveNum("",2,true);
				$table = FLEA::getSingleton('Table_LichiUser');
				/*
				*/
				$sda['username'] = $result['username'];
				$sda['truename'] = $_POST['truename'];
				Fi_upshowsdata($sda);
				if($table->save_pr($row))
					FI_changePage("?Controller=SecondACT&action=Archives&id=".$row['user_id']."&edit=1");
				else
					FI_alert("失败","?Controller=SecondACT&action=Archives&id=".$user_id."&edit=1");
	}
	/*
	*/
	function actionHeadImgUpload()
	{
				if($_GET['user_id'])
					$user_id = (int)$_GET['user_id'];
				if($_POST['user_id'])
					$user_id = (int)$_POST['user_id'];
				$visitUser_id = $user_id;
				$result = FI_TestSysUser("",$user_id,1,1);
				FI_CheckCommonRight($result['username'],1);
				/*
				*/
				$head_img = $result['head_img'];
				$temp_img = $result['temp_headimg'];
				$dst_w = 400;
				$dst_h = 300;
				if($this->_isPost())
				{
						$urllink = "?Controller=SecondACT&action=HeadImgUpload&user_id=".$user_id;
						$upload_name = 'file_tmp';
						$pathname = 'lichiimgs/'."temp_headimg".strtotime(date('Y-m-d H:i:s')).rand(1000,9999);
						$uploadfile = FI_ImgUpLoad($upload_name,$pathname,$dst_w,$dst_h,$urllink,1,1);
						if($uploadfile)
						{
								if($result['temp_headimg'])
									unlink($result['temp_headimg']);
								$result['temp_headimg']	=	$uploadfile;
								$temp_img = $uploadfile;
								$tableModaShows = FLEA::getSingleton('Table_LichiUser');
								$suc = $tableModaShows->save_pr($result);
								FI_changePage($urllink);
						}
						
				}
				include(APP_DIR."/LichiViews/HeadImgUpload.html");
	}
	/*截取
	*/
	function actionPostHeadImgUpload()
	{
				if((int)$_POST['user_id'] == "")
					FI_alert("缺少参数","/");
				if($_POST['user_id'])
					$user_id = (int)$_POST['user_id'];
				$visitUser_id = $user_id;
				$result = FI_TestSysUser("",$user_id,1,1);
				/*
				*/
				$backurl = "?Controller=SecondACT&action=HeadImgUpload&user_id=".$user_id;
				$targ_x = $_POST['x']*$_POST['rw']/$_POST['pw'];
				$targ_y = $_POST['y']*$_POST['rh']/$_POST['ph'];
				$targ_w = $_POST['w']*$_POST['rw']/$_POST['pw'];
				$targ_h = $_POST['h']*$_POST['rh']/$_POST['ph'];
				$fd_w = 138;
				$fd_h = 138;
				/*
				*/
				if($result['temp_headimg'] == "")
					FI_alert("请选择图片，剪切后上传！",$backurl);
				$src = $_POST['bigImage'];
				if(!$src)
					FI_alert("参数错误！",$backurl);
				$src = FI_CutImgUpLoad($src,$targ_x,$targ_y,$targ_w,$targ_h,$backurl,1,$fd_w,$fd_h);
				/*
				*/	
				$tableModaShows = FLEA::getSingleton('Table_LichiUser');
				if($result['head_img'])
					unlink($result['head_img']);
				$result['head_img'] = $src;
				$result['temp_headimg'] = "";
				$tableModaShows->save_pr($result);
				Fi_incresActiveNum("",2,true);
				FI_changePage("?Controller=SecondACT&action=Archives&id=".$user_id."&edit=1");
	}
	/*
	*/
	function actionForShowTicket()
	{
				$show_id = $_POST['show_id'];
				$tableModaShows = FLEA::getSingleton('Table_LichiShows');
				$suc = $tableModaShows->incrfield("show_id='".$show_id."'","ticket","1");
				if(!$suc)
					echo "{success:false,message:\"失败\"}";
				else
					echo "谢谢您的支持"; 
	}
	/*
	*/
	function actionPageSendShortMsg()
	{
		
			$show_id = (int)$_GET['show_id'];
			$tmpid = (int)$_GET['user_id'];
			$visitUser_id = $tmpid;
			$result = FI_TestSysUser("",$tmpid,1,1);
			$showd = FI_ShowExist($show_id,1);
			$shows = $showd;
	
			include(APP_DIR . '/LichiViews/sendmsg.html');		

	}
	/*
	*/
	function actionPostSendShortMsg()
	{
				if((int)$_POST['show_id'] == "")
					FI_alert("缺少参数","/");
				if((int)$_POST['user_id'] == "")
					FI_alert("缺少参数","/");
				$show_id = $_POST['show_id'];
				$user_id = $_POST['user_id'];
				$userfrom = FI_TestComUser($_SESSION["Zend_Auth"]['storage']->username,"",1,0);
				if(!$userfrom)
				{
						 FI_alert("请登录后留言","?Controller=SecondACT&action=PageSendShortMsg&user_id=".$user_id."&show_id=".$show_id);
				}
				$userto = FI_TestSysUser("",(int)$_POST['user_id'],1,1);
				$dat['msgfrom'] = $userfrom['nickname'];
				$dat['msgfromid'] = $userfrom['uid'];
				$dat['msgtoid'] = $userto['uid'];
				$dat['folder'] = "inbox";
				$dat['new'] = 1;
				$dat['subject'] = $userfrom['nickname']."通过自荔枝频道给你留言了";
				$dat['dateline'] = time();
				$dat['message'] = Fi_ConvertChars($_POST['content']);
				if(strlen($dat['message']) > 500 )
						FI_alert("文字不能超过500个字符","?Controller=SecondACT&action=PageSendShortMsg&user_id=".$user_id."&show_id=".$show_id);
				$dat['delstatus'] = 0;
				$Table_Cdbpms = FLEA::getSingleton('Table_Cdbpms');
				$suc = $Table_Cdbpms->save($dat);
				if(!$suc)
						 FI_alert("失败","?Controller=SecondACT&action=PageSendShortMsg&user_id=".$user_id."&show_id=".$show_id);
				else
					FI_alert("成功","?Controller=MainACT&action=Personal&id=".$user_id);
	}
	
	/*
	*/
	function actionSendShortMsg()
	{
				$userfrom = FI_TestComUser($_SESSION["Zend_Auth"]['storage']->username,"",1,0);
				if(!$userfrom)
				{
						 echo "{success:false,message:\"请登录后留言\"}";
						 exit;
				}
				$chknumber=$_POST['chknumber'];
				if(intval($chknumber)!=intval($_COOKIE['code']))
						echo "{success:false,message:\"验证码错误\"}";
				else
				{
						$userto = FI_TestSysUser("",(int)$_POST['user_id'],1,0);
						if(!$userto)
						{
								echo "{success:false,message:\"用户不存在\"}";
								exit;
						}
						$description = iconv("utf-8","gbk",Fi_ConvertChars($_POST['description']));
						if(strlen($description) <3)
						{
								echo "{success:false,message:\"内容应4个字符以上\"}";
								exit;
						}
						$dat['msgfrom'] = $userfrom['nickname'];
						$dat['msgfromid'] = $userfrom['uid'];
						$dat['msgtoid'] = $userto['uid'];
						$dat['folder'] = "inbox";
						$dat['new'] = 1;
						$dat['subject'] = $user['nickname']."通过自荔枝频道给你留言了";
						$dat['dateline'] = time();
						$dat['message'] = $description;
						$dat['delstatus'] = 0;
						$Table_Cdbpms = FLEA::getSingleton('Table_Cdbpms');
						$suc = $Table_Cdbpms->save($dat);
						if(!$suc)
							echo "{success:false,message:\"失败\"}";
						else
							echo "{success:true,message:\"发送成功\"}"; 
				}
	}
	
    /**
     */	 
    function actionIndex()
	{
    }
	


}
?>
