<?php
include 'Fun_Include.php';

/* 后台管理员，以及主持人Control层代码*/
FLEA::loadClass('Controller_BoBase');
class Controller_ExtjsAdmin extends Controller_BoBase
{
	
    function Controller_ExtjsAdmin() 
	{
				parent::Controller_BoBase();
				/*口令错误
				*/
				if($_SESSION["modaAdmin"]->modaAdmin != 1)
				{
						include('./extjsAdmin/login.html');	
						exit;
				}
				/*管理员
				*/
				if($_SESSION["Zend_Auth"]['storage']->adminid!=1)
						if($_SESSION["Zend_Auth"]['storage']->username!='moda' && $_SESSION["Zend_Auth"]['storage']->username!='aaa')
								FI_alert("权限不够，您可能没有登录！","http://passport.we54.com/Index/login?forward=".urlencode("http://lichi.we54.com/?Controller=ExtjsAdmin"));
    }
	
	/*
	*/	
	function actionDeleteShow()
	{
				$pd['show_id'] = $_POST['show_id'];
				$tableModaShows = FLEA::getSingleton('Table_LichiShows');
				$suc = $tableModaShows->deleteByShowId($pd['show_id']);	
				/*
				*/
				$tableModaUser = FLEA::getSingleton('Table_LichiUser');
				$result = $tableModaUser->find("user_id='".$_POST['user_id']."'");
				$result['showcount'] = $result['showcount'] -1 ;
				$suc = $tableModaUser->update($result);
				
				if($suc)
						echo "{success:true,message:\"成功\"}"; 
				else
						echo "{success:false,message:\"修改失败\"}"; 
	}
	
	
	/*
	*/
	function actionIndexPageSet()
	{
				$tableLichiDoor = FLEA::getSingleton('Table_LichiDoor');
				$datar = $tableLichiDoor->IndexTest();
				$urllink = "?Controller=ExtjsAdmin";
				foreach($_POST as $key=>$val)
				{
						if($_POST[$key])
							$datar[$key] = $_POST[$key];
				}
				$datar['user_id'] = "-1";
				$datar = FI_ComImgUpLoad('Table_LichiDoor',$datar);
				$suc = $tableLichiDoor->save_per($datar);
				if($suc)
					echo "{success:true,message:\"成功\"}";
				else
					echo "{success:false,message:\"失败\"}";
	}
	/*
	*/	
	function actionLichiSWSManange()
	{		
				/*翻页
				*/
				$limit=$_POST['limit'];
				$start=$_POST['start'];
				if($start == "")
					$start =0;
				if($limit == "")
					$limit =10;
				FLEA::loadClass('Services_Db');
				$db=new Services_Db("192.168.1.9","user1","123","bbsup");
				$sqltocount = 'select * from lichi_shows WHERE available = 1 and main = 1';
				$sqltoquery = 'select * from lichi_shows WHERE available = 1 and main = 1 order by show_id DESC limit '.$start.','.$limit;
				echo $db->toExtJsonByGp($sqltocount,$sqltoquery);
	}
	/*
	*/	
	function actionLichiShowDom()
	{
				$pd['show_id'] = $_POST['show_id'];
				$tableModaShows = FLEA::getSingleton('Table_LichiShows');
				if($_POST['setback'])
					$pd['main'] = 0;
				else
					$pd['main'] = 1;
				$res = $tableModaShows->getShowByShowIdNoLimit($pd['show_id']);	
				$suc = $tableModaShows->updatetar($pd);
				/*
				*/
				$tableModaUser = FLEA::getSingleton('Table_LichiUser');
				$dat['user_id'] = $res['user_id'];
				if($pd['main'])
						$dat['status'] = 1;
				else
						$dat['status'] = 0;
				$suc = $tableModaUser->update($dat);
				if($suc)
						echo "{success:true,message:\"成功\"}"; 
				else
						echo "{success:false,message:\"修改失败\"}"; 
	}


	function actionPersonalPageImgs()
	{
			if($_GET['user_id'])
				$user_id = $_GET['user_id'];
			if($_POST['user_id'])
				$user_id = $_POST['user_id'];
			if($_POST)
			{
					$tableLichiDoor = FLEA::getSingleton('Table_LichiDoor');
					$datar = $tableLichiDoor->getatest($user_id);
					if(!$datar)
						$datar['user_id'] = $user_id;
					foreach($_POST as $key=>$val)
					{
							if($_POST[$key])
								$datar[$key] = $_POST[$key];
					}
					$urllink = "?Controller=ExtjsAdmin";
					$datar = FI_ComImgUpLoad('Table_LichiDoor',$datar);
					$suc = $tableLichiDoor->save_per($datar);
					echo   '<script>alert("成功");window.self.close();</script>';
			}
			$result = FI_TestSysUser("",$user_id,1,1);
			include('./extjsAdmin/htmlviews/perimgchange.html');	
	}	
	
	
	function actionIndex()
	{
		include('./extjsAdmin/index.html');
	}	
	/*
	*/
	function actionAdminLogin()
	{
		$_SESSION["Zend_Auth"]['storage']->modaAdmin = 1;
		echo "true";
	}
	/*操作界面
	*/
	function actionMainView(){
		
		include('./extjsAdmin/index.html');
			
		//echo "成功";
	}	

	/*添加美达榜
	*/
//	function actionAddModaRank(){
//	
//		$arr = array(
//			'rank_title' => $_POST["rank_title"],
//			'overtime'	=>  $_POST["overtime"],
//			'content'	=>  $_POST["content"]
//		);	
//	
//	
//	
//		if($_POST["rank_id"] != "")
//			$arr['rank_id'] = $_POST["rank_id"];
//
//
//		$table = FLEA::getSingleton('Table_ModaRanks');
//		$has = $table->getRankByTitle($arr['rank_title']);
//		
//		if($has)
//		{
//			if($_POST["rank_id"] == "")
//			{
//				
//					echo "{success:false,message:\"标题名已经存在，请换一个再试\"}";
//					exit;
//				}
//		}
//		
//		$rrr = $table->save($arr);	
//		
//		if($rrr)
//		{
//				echo "{success:true,message:\"成功\"}";
//				
//			}
//		else
//			echo "{success:false,message:\"添加失败\"}";
//		
//		exit;
//
//
//	
//	}



	function actionimglist()
	{


		$user_id = (int)$_GET['user_id'];
		
		$this->connect_to19_db();
		FLEA::loadClass('Services_json');
		


		$sql = "select img1,img2,img3,img4 from lichi_users where user_id = ".$user_id;
		
		if($result = mysql_query($sql))
		{
			$json = new Services_JSON();
			$items = array();
			$row = mysql_fetch_assoc($result);
			$success = '{"images":[{"name":"'.$row[img1].'","url":"'.$row[img1].'"},{"name":"'.$row[img2].'","url":"'.$row[img2].'"},{"name":"'.$row[img3].'","url":"'.$row[img3].'"},{"name":"'.$row[img4].'","url":"'.$row[img4].'"}]}';
			
		}
		echo $success;
		




	}


	function actionUserInfo()
	{
				$this->connect_to19_db();
				$tableModaShow = FLEA::getSingleton('Table_LichiShows');		
				$tableModaAttach = FLEA::getSingleton('Table_LichiAttachments');
				$tableModaShowDiscuss = FLEA::getSingleton('Table_LichiShowDiscuss');
				/*
				*/
				foreach($_POST as $key=>$val)
						if($_POST[$key])
								if($key != 'limit' && $key != 'start')
									$serch .= " and ".$key." like '%".iconv("UTF-8","gb2312",$_POST[$key])."%'";
				if($serch)
					  $count=@mysql_query("select count(user_id) as count from lichi_users where pass = 1 ".$serch);	
				else
					  $count=@mysql_query("select count(user_id) as count from lichi_users where pass = 1");	
				$count_nu=@mysql_fetch_array($count);
				$count_number=$count_nu['count'];
				$limit=$_POST['limit'];
				$start=$_POST['start'];
				if($start == "")
					$start =0;
				if($limit == "")
					$limit =10;
				if($start+$limit>$count_number)
					$limit=$limit-($start+$limit-$count_number);
				if($serch)
					  $req=@mysql_query("select * from lichi_users where pass = 1 ".$serch." order by dateline DESC limit $start,$limit");
				else
					  $req=@mysql_query("select * from lichi_users where pass = 1 order by dateline DESC limit $start,$limit");
				/*
				*/
				$strs = "{'totalCount':'$count_number','rows':[";
				$i=0;											   
				while($return=@mysql_fetch_array($req))
				{
					
							$user_id=$return['user_id'];
							$restime=date("Y-m-d",$return['dateline']);
							$username=$return['username'];
							$truename=$return['truename'];
							/*
							*/
							$pasuser = @mysql_query("select lastvisit from passport_user where username =  '".$username."'");
							$pasinfo=@mysql_fetch_array($pasuser);
							$lastvisit = date("Y-m-d",$pasinfo['lastvisit']);
							/*
							*/
							$reallpageview = 0;
							$att_count = 0;
							$dis_count = 0;
							$club_count = 0;
							$clubcall_count = 0;
							$shows = $tableModaShow->getViewsByUsername($username);
							foreach($shows as $srows)
							{
									//页面浏览量
									$reallpageview+=$srows['views'];
									/*图片类型附件量
									*/
									$attcount = $tableModaAttach->getPicTypeCountByShowId($srows['show_id']);
									$att_count += $attcount;
									/*图片评论量
									*/
									$discount = $tableModaShowDiscuss->getDiscussCountByShowId($srows['show_id']);
									$dis_count += $discount;
							}
							//展示数
							$showcount = $tableModaShow->getViewCountByUsername($username);
							/*前置展示封面
							*/
							$mainshow = $tableModaShow->getMainShowById($username);
							$showurl = $mainshow['show_img'];
							$show_id = $mainshow['show_id'];
							/*显示浏览量
							*/
							$pageviews=$return['liulanliang'];	
							$username=$return['username'];	
							$nickname=$return['nickname'];	
							$birthdate=$return['birthdate'];	
							$mobile=$return['mobile'];
							$qq=$return['qq'];
							$height=$return['height'];
							$weight=$return['weight'];
							$email=$return['email'];
							$head_img=$return['head_img'];
							$status=$return['status'];
							$active_num=$return['active_num'];
							$uid=$return['uid'];
							
							
							
							$strs.=	"{'user_id':'$user_id','truename':'$truename','restime':'$restime','lastvisit':'$lastvisit','reallpageview':'$reallpageview','pageviews':'$pageviews','showcount':'$showcount','att_count':'$att_count','dis_count':'$dis_count','showurl':'$showurl'";
							$strs.=",'username':'$username','nickname':'$nickname','birthdate':'$birthdate','mobile':'$mobile','qq':'$qq','height':'$height','weight':'$weight','show_id':'$show_id','email':'$email','active_num':'$active_num','head_img':'$head_img','status':'$status','uid':'$uid'}";
							$i++;
							if($i<$limit){$strs.= ",";}				
				}
				$strs.= "]}";
				echo $strs;
		
	}
		
	function actionNewUserList()
	{
			$search=$_POST['title'];
			$search=iconv("UTF-8","gb2312",$search);
			/*翻页
			*/
			$limit=$_POST['limit'];
			$start=$_POST['start'];
			if($start == "")
				$start =0;
			if($limit == "")
				$limit =10;
			FLEA::loadClass('Services_Db');
			$db=new Services_Db("192.168.1.9","user1","123","bbsup");
			if(!$search)
			{
					$sqltocount = 'select * from lichi_users WHERE pass = 0';
					$sqltoquery = 'select * from lichi_users WHERE pass = 0 order by dateline DESC limit '.$start.','.$limit;
				}
			else
			{
					$sqltocount = "select * from lichi_users WHERE username like '%$search%'";
					$sqltoquery = "select * from lichi_users WHERE username like '%$search%' order by dateline DESC limit ".$start.','.$limit;
					
				}
			echo $db->toExtJsonByGp($sqltocount,$sqltoquery);
		
	}		
		
	function actionGetListNopass()
	{
			/*翻页
			*/
			$limit=$_POST['limit'];
			$start=$_POST['start'];
			if($start == "")
				$start =0;
			if($limit == "")
				$limit =10;
			FLEA::loadClass('Services_Db');
			$db=new Services_Db("192.168.1.9","user1","123","bbsup");
			echo $db->toExtJson('lichi_users',$start,$limit,"pass = 2");
	}		
	
	
	function actionGetModaTopGirlList(){
		
		/*翻页
		*/
		$limit=$_POST['limit'];
		$start=$_POST['start'];
		if($start == "")
		{
			$start =0;
		}
		if($limit == "")
		{
			$limit =10;
		}
		FLEA::loadClass('Services_Db');
		$db=new Services_Db("192.168.1.9","user1","123","bbsup");
		//echo $db->toExtJson('moda_ranks',$start,$limit);

		$sqltocount = 'select * from moda_ranks';
		$sqltoquery = 'select * from moda_ranks order by rank_id DESC limit '.$start.','.$limit;
	
		echo $db->toExtJsonByGp($sqltocount,$sqltoquery);
			
					
	}
	
//	function actionRankShowManange()
//	{
//			$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
//			$tableModaUser = FLEA::getSingleton('Table_LichiUser');
//			$tableModaShow = FLEA::getSingleton('Table_ModaShows');		
//			
//			$rank_id = $_GET['rank_id'];	
//			$limit=$_POST['limit'];
//			$start=$_POST['start'];
//
//			if($start == "")
//			{
//				$start =0;
//			}
//			if($limit == "")
//			{
//				$limit =10;
//			}
//		
//			$rankshows = $tableModaRankShow->findAll(" rank_id = $rank_id ORDER by Id DESC limit $start,$limit");
//			
//			$count_number = $tableModaRankShow->findCount("rank_id = $rank_id ");
//			
//			$i = 0;
//			$strs = "{'totalCount':'$count_number','rows':[";
//														   
//			foreach($rankshows as $row)
//			{
//					
//					$perinfo = $tableModaUser->getUserByUserId($row['user_id']);
//					$truename = $perinfo['truename'];
//					$username = $perinfo['username'];
//					$show = $tableModaShow->getShowByShowIdNoLimit($row['show_id']);
//					$views = $show['views'];
//					
//					
//					$strs.=	"{'Id':'$row[Id]','rank_id':'$row[rank_id]','user_id':'$row[user_id]','show_id':'$row[show_id]','title':'$row[title]','head_img':'$row[head_img]','ticket':'$row[ticket]','mingci':'$row[mingci]','truename':'$truename','username':'$username','views':'$views'}";
//					
//					$i++;
//					if($i<$limit){$strs.= ",";}				
//				
//				}
//			$strs.= "]}";
//			
//			
//			echo $strs;
//					
//	}
		
	/*lichi_news
	*/	
	function actionModaTalkList()
	{
				$limit=$_POST['limit'];
				$start=$_POST['start'];
				if($start == "")
					$start =0;
				if($limit == "")
					$limit =10;
				FLEA::loadClass('Services_Db');
				$db=new Services_Db("192.168.1.9","user1","123","bbsup");
				//echo $db->toExtJson('moda_news',$start,$limit);
				$sqltocount = 'select * from lichi_news';
				//$sqltoquery = 'select user_id,dateline,news_id,news_st,news_title,url,img_url from lichi_news order by dateline DESC  limit '.$start.','.$limit;
				$sqltoquery = 'select * from lichi_news order by dateline DESC  limit '.$start.','.$limit;
				echo $db->toExtJsonByGp($sqltocount,$sqltoquery);
	}
		
	/*lichi_event
	*/	
	function actionModaEventList()
	{
				$limit=$_POST['limit'];
				$start=$_POST['start'];
				if($start == "")
					$start =0;
				if($limit == "")
					$limit =10;
				FLEA::loadClass('Services_Db');
				$db=new Services_Db("192.168.1.9","user1","123","bbsup");
				//echo $db->toExtJson('moda_news',$start,$limit);
				$sqltocount = 'select * from lichi_event';
				$sqltoquery = 'select event_id,home_img,index_img,header_img,title,dateline from lichi_event ORDER by event_id DESC limit '.$start.','.$limit;
				echo $db->toExtJsonByGp($sqltocount,$sqltoquery);
	}
	
	/*
	*/	
	function actionAddModaUserByAdmin()
	{
				$userD['username'] = iconv("UTF-8","gb2312",$_POST['username']);
				$userD['truename'] = iconv("UTF-8","gb2312",$_POST['truename']);
				/*	读取moda_user
				*/
				$tableModaUser = FLEA::getSingleton('Table_LichiUser');
				$user_id = $tableModaUser->AdminInsertUser($userD);
				if(!$user_id)
					echo "{success:false,message:\"这个用户不存在,或已经是会员\"}"; 
				else
					echo "{success:true,message:\"成功\"}"; 
	}
	/*
	*/	
	function actionModaerInfoEdit()
	{
				$userD['user_id'] = $_POST['user_id'];
				$userD['truename'] =iconv("UTF-8","gb2312",$_POST['truename']);
				$userD['liulanliang'] = $_POST['pageviews'];
				$userD['active_num'] = $_POST['active_num'];
				$userD['status'] = $_POST['status'];
				/*
				*/
				$tableModaUser = FLEA::getSingleton('Table_LichiUser');
				$userinfo = $tableModaUser->getUserByUserId($userD['user_id']);
				$sda['username'] = $userinfo['username'];
				$sda['truename'] = $userD['truename'];
				Fi_upshowsdata($sda);
				if(!$userinfo)
				{
					echo "{success:false,message:\"修改失败,错误阶段0\"}"; 
					exit;
				}
				$tablePassportUser = FLEA::getSingleton('Table_PassportUser');
				$userDat = $tablePassportUser->getUserByUsername($userinfo['username']);
				$userD['uid'] = $userDat['uid'];
				$suc = $tableModaUser->update($userD);
				if(!$suc)
				{
					echo "{success:false,message:\"修改失败,错误阶段1\"}";
					exit;
				}
				else
					echo "{success:true,message:\"成功\"}"; 
	}
	
	/*
	*/	
	function actionModaerPerInfoEdit()
	{
				$userD['user_id'] = $_POST['user_id'];
				$userD['truename'] = $_POST['truename'];
				$userD['mobile'] = $_POST['mobile'];
				$userD['qq'] = $_POST['qq'];
				$userD['email'] = $_POST['email'];
				$userD['height'] = $_POST['height'];
				$userD['weight'] = $_POST['weight'];
				$userD['truename'] = iconv("UTF-8","gb2312",$userD['truename']);
				$tableModaUser = FLEA::getSingleton('Table_LichiUser');
				$userinfo = $tableModaUser->getUserByUserId($userD['user_id']);
				if(!$userinfo)
				{
					echo "{success:false,message:\"修改失败,错误阶段0\"}"; 
					exit;
				}
				
				$suc = $tableModaUser->update($userD);
				if(!$suc)
				{
					echo "{success:false,message:\"修改失败,错误阶段1\"}";
					exit;
				}
				else
					echo "{success:true,message:\"成功\"}"; 
	}
	
	/*
	*/	
//	function actionAddTopGirlShow()
//	{
//		
//					$show_id = (int)$_POST['show_id'];
//					$rank_id = $_POST['rank_id'];
//					$title = $_POST['title'];
//					$title = iconv("UTF-8","gb2312",$title);
//				
//					//查询rank_id
//					$tableModaRanks = FLEA::getSingleton('Table_ModaRanks');
//					$rankDa = $tableModaRanks->getRankById($rank_id);
//					
//					//dump($rankDa);
//					if(!$rankDa)
//					{
//						//FI_alert("榜不存在");
//						echo "{success:false,message:\"榜不存在,错误阶段0\"}"; 
//						exit;
//					}
//					
//					/*读取Table_ModaShows
//					*/		
//					$tableModaShows = FLEA::getSingleton('Table_ModaShows');
//					$showData = $tableModaShows->getShowByShowId($show_id);
//					//dump($showData);
//					if(!$showData)
//					{
//						//FI_alert("展示不存在");
//						echo "{success:false,message:\"展示不存在,错误阶段1\"}"; 
//						exit;
//					}
//					
//					$RankGirlDat['head_img'] = $showData['show_img'];
//					$RankGirlDat['rank_id'] = $rankDa['rank_id'];
//					$RankGirlDat['rank_title'] = $rankDa['rank_title'];
//					$RankGirlDat['user_id'] = $showData['user_id'];
//					$RankGirlDat['show_id'] = $show_id;
//					$RankGirlDat['title'] = $title;
//					
//					//dump($RankGirlDat);
//					/*添加展示
//					*/
//					$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
//					$rr = $tableModaRankShow->savetar($RankGirlDat);
//					if($rr)
//					{
//							//FI_alert("添加成功","index.php?controller=Admin");
//							echo "{success:true,message:\"成功\"}"; 
//							exit;
//						}
//					else
//					{
//							//FI_alert("未知错误","index.php?controller=Admin");
//							echo "{success:false,message:\"失败\"}"; 
//							exit;
//					}	
//	}
	
	/*
	*/	
	function actionTopGirlShowEdit()
	{
					$pd['rank_id'] = $_POST['rank_id'];
					$pd['show_id']  = $_POST['show_id'];
					$pd['ticket']  = $_POST['ticket'];
					$sd['views']  = $_POST['views'];
					$sd['show_id']  = $_POST['show_id'];
					$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
					$suc = $tableModaRankShow->updatetar($pd);
					if($suc)
					{
//							echo "{success:true,message:\"成功\"}"; 
//							exit;
						}
					else
					{
							echo "{success:false,message:\"修改失败：阶段错误0\"}"; 
							exit;
					}
					$tableModaShows = FLEA::getSingleton('Table_ModaShows');
					$suc = $tableModaShows->updatetar($sd);
					if($suc)
							echo "{success:true,message:\"成功\"}"; 
					else
							echo "{success:false,message:\"修改失败：阶段错误1\"}"; 
		}
	
	/*
	*/	
	function actionModaerShowManange()
	{		
				$user_id = $_GET['user_id'];
				if($user_id == "")
				{
							echo "{success:false,message:\"错误\"}"; 
							exit;
				}
				/*翻页
				*/
				$limit=$_POST['limit'];
				$start=$_POST['start'];
				if($start == "")
					$start =0;
				if($limit == "")
					$limit =10;
				FLEA::loadClass('Services_Db');
				$db=new Services_Db("192.168.1.9","user1","123","bbsup");
				$sqltocount = 'select * from lichi_shows WHERE available = 1 and user_id = '.$user_id;
				$sqltoquery = 'select * from lichi_shows WHERE available = 1 and user_id = '.$user_id.' order by show_id DESC limit '.$start.','.$limit;
				echo $db->toExtJsonByGp($sqltocount,$sqltoquery);
	}
	
	
	/*
	*/	
	function actionModaerShowEdit()
	{
//				$user_id = $_POST['user_id'];
				$pd['show_id'] = $_POST['show_id'];
				if(!$pd['show_id'])
				{
					echo "{success:false,message:\"修改失败：阶段错误1\"}"; 
					exit;
				}
				$pd['title']  = $_POST['title'];
				$pd['views']  = $_POST['views'];
				$pd['public']  = $_POST['public'];
				$pd['main']  = $_POST['main'];
				$pd['sortvlue']  = $_POST['sortvlue'];
				$pd['title'] = iconv("UTF-8","gb2312",$pd['title']);
//				$pd['text'] = iconv("UTF-8","gb2312",$pd['text']);
				$tableModaShows = FLEA::getSingleton('Table_LichiShows');
				if($_POST['public'] == "")
						$pd['public'] = 0;
				if($_POST['main'] == "")
						$pd['main'] = 0;
				else
				{
						//$suc = $tableModaShows->CansleMainShow($user_id);	
				}
				$suc = $tableModaShows->updatetar($pd);
				if($suc)
						echo "{success:true,message:\"成功\"}"; 
				else
						echo "{success:false,message:\"修改失败：阶段错误1\"}"; 
	}


	/*审核通过
	*/
	function actionAgree()
	{
				$user_id = (int)$_POST['user_id'];		
				$ck = FLEA::getSingleton('Table_LichiUser');
				$result = $ck->find($user_id);
				$result['pass']=1;
				$suc = $ck->update($result);
				if(!$suc)
				{
						echo "错误：阶段错误0";
						exit;
				}
				/*处理展示
				*/
				$data['user_id'] = $result['user_id'];
				$data['available'] = 1;
				$tableModaShows = FLEA::getSingleton('Table_LichiShows');
				$suc = $tableModaShows->failByUserId($data);
				if($suc)
				{
						$msgfrom = "荔枝管理员";
						$msgfromid = 34;
						$title = "您已通过荔枝认证";
						$msg = $result['nickname']."您好，您已经通过荔枝认证，请登录lichi.we54.com完善您的资料，祝愉快！";		
						$usr = FI_TestComUser($result['username'],"","",0);
						Fi_SendShortMsg($usr['uid'],$msgfrom,$msgfromid,$title,$msg);
						echo "成功";
				}
				else
						echo "错误：阶段错误1";
	}

	/*待审核操作
	*/
	function actionNoAgree()
	{
				$ck = FLEA::getSingleton('Table_LichiUser');
				$user_id = (int)$_POST['user_id'];		
				$result = $ck->find($user_id);
				$result['pass'] = 2;
				$suc = $ck->update($result);
				if(!$suc)
				{
						echo "错误：阶段错误0";
						exit;
				}
				/*处理展示
				*/
				$data['user_id'] = $result['user_id'];
				$data['available'] = 0;
				$tableModaShows = FLEA::getSingleton('Table_LichiShows');
				$suc = $tableModaShows->failByUserId($data);
				if(!$suc)
						echo "错误：阶段错误1";
				else
				{
						$msgfrom = "荔枝管理员";
						$msgfromid = 34;
						$title = "荔枝频道消息";
						$msg = $result['nickname']."您好，因为某些原因，您已经无法正常使用荔枝频道部分功能，请您与管理员联系！";		
						$usr = FI_TestComUser($result['username'],"","",0);
						Fi_SendShortMsg($usr['uid'],$msgfrom,$msgfromid,$title,$msg);
						echo "成功";
				}
	}
	
	/*投票排序
	*/	
//	function actionSortByTicket(){
//			
//			
//		    $rank_id = (int)$_POST['rank_id'];
//			
//			$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
//			$res = $tableModaRankShow->sortByTicket($rank_id);
//			
//			if(!$res)
//			{
//					//echo "{success:true,message:\"成功\"}";
//					echo "错误：阶段错误0";
//					exit;
//				}
//				elseif($res == "none")
//				{
//					echo "成功";
//					exit;
//					}
//			$count = 1;
//			foreach ($res as $r){
//			
//						$r['mingci']=$count;
//						$count += 1;
//						$tableModaRankShow->updatetar($r);
//						if(!$res)
//						{
//								//echo "{success:true,message:\"成功\"}";
//								echo "错误：阶段错误".$r['show_id']."排序失败";
//								exit;
//							}
//						
//						
//			}
//			echo "成功";
//			exit;
//			//FI_alert("完成排序","index.php?controller=Admin&action=AllRank");
//			//$this->_changePage("index.php?controller=Admin&action=AllRank");
//		}	
	
	  /* 取消排序
	  */
//	  function actionNOTicketSort(){
//		  $rank_id = (int)$_POST['rank_id'];
//		  $tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
//		  $ranSDat = $tableModaRankShow->NOsort($rank_id);
//		  
//		  if(!$ranSDat)
//		  {
//				  echo "错误：阶段错误0";
//				  exit;
//			  }
//			  elseif($res == "none")
//			  {
//				  echo "成功";
//				  exit;
//				  }
//				  else
//				  {
//					  echo "成功";
//					  exit;
//					  }
//		  
//		  //dump($ranSDat);
//		  
//		  //$this->_changePage("index.php?controller=Admin&action=AllRank");
//		  
//	  }		
	/* 删除榜
	*/
//	function actionRankDel(){
//		$rank_id = (int)$_POST['rank_id'];
//		//$rank_id = (int)$_GET['rank_id'];
//		
//		$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
//		$suc = $tableModaRankShow->delRankShowByRankId($rank_id);
//		if(!$suc)
//		{
//				echo "错误：阶段错误0";
//				exit;
//			}
//				
//		$tableModaRanks = FLEA::getSingleton('Table_ModaRanks');
//		$suc = $tableModaRanks->delByRankId($rank_id);
//		
//		
//		
//		if($suc)
//		{
//				//echo "{success:true,message:\"成功\"}";
//				echo "成功";
//				exit;
//			}
//		else
//		{
//				//echo "{success:false,message:\"错误：阶段错误2\"}";
//				echo "错误：阶段错误1";
//				exit;
//		}
//	
//		//$this->_changePage("index.php?controller=Admin&action=AllRank");
//	}	
	/* 榜内展示删除
	*/
//	function actionRankShowDel(){
//		$rank_id = (int)$_POST['rank_id'];
//		$show_id = (int)$_POST['show_id'];
//		
//		$data['rank_id'] = $rank_id;
//		$data['show_id'] = $show_id;
//		
//		//dump($data);
//		
//		$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
//		$suc = $tableModaRankShow->delRankShow($data);
//		if($suc)
//		{
//				//echo "{success:true,message:\"成功\"}";
//				echo "成功";
//				exit;
//			}
//		else
//		{
//				//echo "{success:false,message:\"错误：阶段错误2\"}";
//				echo "错误：阶段错误1";
//				exit;
//		}
//		//$this->_changePage("index.php?controller=Admin&action=RankShowManange&rank_id=".$ranSDat['rank_id']."");
//	}
	
	/* 删除访谈
	*/
//	function actionTalkDel(){
//		$news_id = (int)$_POST['news_id'];
//		
//		$tableModaNews = FLEA::getSingleton('Table_ModaNews');
//		$suc = $tableModaNews->delByNewsId($news_id);
//		if($suc)
//		{
//				echo "成功";
//				exit;
//			}
//		else
//		{
//				echo "错误：阶段错误1";
//				exit;
//		}
//		
//	}
	/* 
	*/
	function actionLichiNewsDel()
	{
			$news_id = (int)$_POST['news_id'];
			$tableModaNews = FLEA::getSingleton('Table_LichiNews');
			$suc = $tableModaNews->delByNewsId($news_id);
			if($suc)
					echo "成功";
			else
					echo "错误：阶段错误1";
	}
	
	/*
	*/
	function actionEventDel()
	{
			$event_id = (int)$_POST['event_id'];
			$tableModaEvent = FLEA::getSingleton('Table_LichiEvent');
			$suc = $tableModaEvent->delByEventId($event_id);
			if($suc)
					echo "成功";
			else
					echo "错误：阶段错误1";
	}
	
	/*
	*/
	function actionNewLichiMV()
	{	
				if($this->_isPost())
				{	
						$table = FLEA::getSingleton('Table_LichiNews');
						$arr=array(
									'news_title'   =>$_POST['news_title'],
									'user_id'   =>$_POST['user_id'],
									'news_content' =>$_POST['news_content'],
									'news_st' =>$_POST['news_st'],
									'video_link' =>$_POST['video_link'],
									'dateline' =>time(),
									'content' =>$_POST['content']
									);
						$arr = FI_ComImgUpLoad('Table_LichiNews',$arr);
						$suc = $table->save($arr);	
						if($suc)
								echo   '<script>alert("添加成功");window.self.close();</script>';
						else
								FI_alert("添加失败");
						exit;
				}
				else
						include('./extjsAdmin/htmlviews/modanews.html');
	}
	/*
	*/
	function actionNewLichiMVEdit()
	{	
				if($this->_isPost())
				{	
						$table = FLEA::getSingleton('Table_LichiNews');
						foreach($_POST as $key=>$val)
							$arr[$key] = $_POST[$key];
						$arr = FI_ComImgUpLoad('Table_LichiNews',$arr);
						$suc = $table->save($arr);	
						if($suc)
								echo   '<script>alert("修改成功");window.self.close();</script>';
						else
								FI_alert("修改失败");
				}
				else
				{
						$table = FLEA::getSingleton('Table_LichiNews');
						$news = $table->find("news_id='".(int)$_GET['news_id']."'");
						include('./extjsAdmin/htmlviews/editnews.html');
				}
	}
	/*
	*/
	function actionNewLichiEvent()
	{
			if($this->_isPost())
			{	
					$tableModaEvent = FLEA::getSingleton('Table_LichiEvent');
					$arr['content'] = $_POST['content'];
					$arr['title'] = $_POST['title'];
					$arr['dateline'] = time();
					$arr = FI_ComImgUpLoad('Table_LichiEvent',$arr);
					$suc = $tableModaEvent->save($arr);	
					if($suc)
							echo   '<script>alert("添加成功");window.self.close();</script>';
					else
							FI_alert("添加失败");
			}
			else
					include('./extjsAdmin/htmlviews/eventadd.html');	
	}
	/*
	*/
	function actionEventEditView()
	{
			$tableModaEvent = FLEA::getSingleton('Table_LichiEvent');
			$news = $tableModaEvent->find("event_id='".(int)$_GET['event_id']."'");
			include('./extjsAdmin/htmlviews/eventEdit.html');	
	}
	/*
	*/
	function actionPostEventEdit()
	{
			$tableModaEvent = FLEA::getSingleton('Table_LichiEvent');
			foreach($_POST as $key=>$val)
				$arr[$key] = $_POST[$key];
			$arr = FI_ComImgUpLoad('Table_LichiEvent',$arr);
			$suc = $tableModaEvent->save($arr);	
			if($suc)
					echo   '<script>alert("修改成功");window.self.close();</script>';
			else
					FI_alert("修改失败");
	}
	/*
	*/
	function actionSendMsg()
	{
				$uid=$_POST['uid'];
				if(!$uid)
				{
							echo "{success:false,message:\"参数错误\"}";
							exit;
				}
				$chknumber=$_POST['chknumber'];
				if(intval($chknumber)!=intval($_COOKIE['code']))
						echo "{success:false,message:\"验证码错误\"}";
				else
				{
						$msgfrom = "荔枝管理员";
						$msgfromid = 34;
						$title = "来自荔枝频道管理员的消息";
						$msg = iconv("utf-8","gbk",Fi_ConvertChars($_POST['description']));
						$suc = Fi_SendShortMsg($uid,$msgfrom,$msgfromid,$title,$msg);
						if(!$suc)
							echo "{success:false,message:\"失败\"}";
						else
							echo "{success:true,message:\"发送成功\"}"; 
				}
	}




	
	
	/*链接数据库函数
	*/
	function connect_to19_db(){
		mysql_connect ("192.168.1.9", "user1", "123") or die ('I cannot connect to mysql because: ' . mysql_error());
		mysql_query ("set names utf8");
		mysql_select_db ("bbsup") or die ('I cannot select the database because: '.mysql_error());
	}
	/*动作跳转
	*/
	function _changePage($url="",$target="self"){
		if($url==""){
			$url	=	$_SERVER['HTTP_REFERER'];
		}
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><script type=\"text/javascript\" >$target.location='$url';</script>
</head><body></body></html>";
	}
	
	
	
	
}
?>