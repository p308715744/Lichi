<?php
include 'Fun_Include.php';
//include 'e:/wwwroot/bbs.we54.com/gp_getcode.php';


FLEA::loadClass('Controller_BoBase');
class Controller_MainACT extends Controller_BoBase
{
	
	
    function Controller_MainACT() 
	{
			FI_get_session_by_cookie();
    }

	
	function actionTaa()
	{
		$_SESSION['Zend_Auth']['storage'] = "";
		$_SESSION['Zend_Auth'] = "";
		$_SESSION = "";
	}
	function actionTbb()
	{
		dump($_SESSION);
	}
	/*
	*/
	function actionHelpDoc()
	{
			if($_GET['forwhat'] == "gy")
			{
						include(APP_DIR . '/LichiViews/about_us.html');
			}
			if($_GET['forwhat'] == "td")
			{
						include(APP_DIR . '/LichiViews/about_us.html');
			}
			if($_GET['forwhat'] == "bz")
			{
						include(APP_DIR . '/LichiViews/about_us.html');
			}
			if($_GET['forwhat'] == "whats")
			{
						include(APP_DIR . '/LichiViews/about_us.html');
			}
			
	}
	/*注册
	*/
	function actionRegister()
	{
			$temp = FI_TestComUser($_SESSION["Zend_Auth"]['storage']->username,0,0,0);
			if(!$temp){
				FI_alert("您没有登录，请登录后提交申请！","http://passport.we54.com/Index/login?forward=".urlencode("http://lichi.we54.com/?Controller=MainACT&action=Register"));
			}
			$tempuser = FI_TestSysUser($_SESSION["Zend_Auth"]['storage']->username,0,-1,0);
			/*
			*/
			if($this->_isPost())
			{
					//获取文件大小
					$size1 = $_FILES['img1']['size'];
					$size2 = $_FILES['img2']['size'];
					$size3 = $_FILES['img3']['size'];
					$size4 = $_FILES['img4']['size'];
					if($size1>1048576||$size2>1048576||$size3>1048576||$size4>1048576){
						FI_alert("单个上传图片不能超过1MB！","?Controller=MainACT&action=Register");
					}
					if($_POST['truename']==''||$_POST['bdate']==''||$_POST['mobile']==''){
						FI_alert("请确保资料填写完整","?Controller=MainACT&action=Register");
					}	
					$arr=array(
								  'username' => $temp['username'],	
								  'uid' => $temp['uid'],	
								  'nickname' => $temp['nickname'],
								  'truename' => Fi_ConvertChars($_POST['truename']),
								  'e_msg' => Fi_ConvertChars($_POST['instrper']),
								  'birthdate' => $_POST['bdate'],
								  'height' => (int)$_POST['height'],
								  'weight' => (int)$_POST['weight'],
								  'mobile' => (int)$_POST['mobile'],
								  'qq' => (int)$_POST['qq']	,
								  'msg_b' => Fi_ConvertChars($_POST['msg_b']),
								  'e_msg' => Fi_ConvertChars($_POST['e_msg']),
								  'active_num' => rand(1,10),
								  'dateline' => time()	  
					);
					/*
					*/
					if($tempuser){
						if($tempuser['pass'] != 1)
							$arr['user_id'] = $tempuser['user_id'];
						else
							FI_alert("您已经是荔枝会员，请勿重复申请","/");
					}
					$tableLichiUser = FLEA::getSingleton('Table_LichiUser');
					foreach($_FILES as $upload_name => $val)
					{
							$file_name = $_FILES[$upload_name]['name'];
							if($file_name != "")
							{
									$pathname = 'lichiimgs/'."regimgs_".strtotime(date('Y-m-d H:i:s')).rand(1000,9999);
									$urllink = "?Controller=SecondACT&action=Archives&id=".(int)$_POST['user_id']."&edit=1";
									$uploadfile = FI_ImgUpLoad($upload_name,$pathname,500,600,$urllink,0);
									if($tempuser[$upload_name])
										unlink($tempuser[$upload_name]);
									$arr[$upload_name] = $uploadfile ;
							}
					}
					$suc = $tableLichiUser->save_pr($arr);
					if($suc){
						FI_alert('您的个人信息，已成功记录到荔枝先生资料库中,如符合标准,我们会与您联系.欢迎你继续关注荔枝先生,关注新青年！','/');				
					}else{
						FI_alert('提交信息发生错误，请联系管理员！','/');
					}
			}
			else{
					include(APP_DIR . '/LichiViews/Register.html');
					if($tempuser){
						if($tempuser['pass'] == 1)
								FI_alert("您已经是荔枝会员，请勿重复申请","/");
						else
								echo   '<script>alert("您已经申请过，再申请将会更新您的提交信息");</script>';
					}
			}		
		
	}
	
	
	/*会员
	*/
	function actionLichier()
	{
				$table = FLEA::getSingleton('Table_LichiUser');
				if($_POST['serchkey'])
				{
					
						$tableshowips = FLEA::getSingleton('Table_LichiShowIps');
						$suc = $tableshowips->forSearch();
						if(!$suc)
								FI_alert("请勿频繁搜索！","/");
						$key = $_POST['serchkey'];
						$pagesize	=	25;
						$conditions	=	"pass = 1 and truename like '%".$key."%'";
						$sortby		=	"dateline DESC";
						FLEA::loadClass('Lib_NewPager');
						$page	=	new Lib_NewPager( $table, $pagesize, $conditions , $sortby );
						$Browset	=	$page->rowset;
						include(APP_DIR."/LichiViews/Lichier_hy.html");
				}
				if($_GET['more'] == 'sx')
				{
						$pagesize	=	25;
						$conditions	=	"pass = 1 and status = 1";
						$sortby		=	"dateline DESC";
						FLEA::loadClass('Lib_NewPager');
						$page	=	new Lib_NewPager( $table, $pagesize, $conditions , $sortby );
						$Arowset	=	$page->rowset;
						include(APP_DIR."/LichiViews/Lichier_sx.html");
				}
				if($_GET['more'] == 'hy')
				{
						$pagesize	=	25;						
						$conditions	=	"pass = 1";
						$sortby		=	"dateline DESC";
						FLEA::loadClass('Lib_NewPager');
						$page	=	new Lib_NewPager( $table, $pagesize, $conditions , $sortby );
						$Browset	=	$page->rowset;
						include(APP_DIR."/LichiViews/Lichier_hy.html");
				}
				if(!$_GET['more'])
				{
						$pagesize	=	10;
						$conditions	=	"pass = 1 and status = 1";
						$sortby		=	"dateline DESC";
						FLEA::loadClass('Lib_NewPager');
						$page	=	new Lib_NewPager( $table, $pagesize, $conditions , $sortby );
						$Arowset	=	$page->rowset;
						/*
						*/
						$pagesize	=	10;
						$conditions	=	"pass = 1";
						$sortby		=	"dateline DESC";
						FLEA::loadClass('Lib_NewPager');
						$page	=	new Lib_NewPager( $table, $pagesize, $conditions , $sortby );
						$Browset	=	$page->rowset;
						include(APP_DIR."/LichiViews/Lichier.html");
				}
	}
	
	
	/*展示
	*/
	function actionShows()
	{
				$tableModaShow = FLEA::getSingleton('Table_LichiShows');		

				if($_GET['more'] == "sws")
				{
						$pagesize	=	30;
						$conditions	=	"available = 1 and public = 1 and main = 1"; 
						$sortby		=	"dateline DESC";
						FLEA::loadClass('Lib_NewPager');
						$page	=	new Lib_NewPager( $tableModaShow, $pagesize, $conditions , $sortby );
						$Arowset	=	$page->rowset;
						include(APP_DIR . '/LichiViews/Shows_sws.html');	
				}
				if($_GET['more'] == "zs")
				{
						$pagesize	=	30;
						$conditions	=	"available = 1 and public = 1 and main < 1";
						$sortby		=	"dateline DESC";
						FLEA::loadClass('Lib_NewPager');
						$page	=	new Lib_NewPager( $tableModaShow, $pagesize, $conditions , $sortby );
						$Browset	=	$page->rowset;
						include(APP_DIR . '/LichiViews/Shows_zs.html');	
				}
				if(!$_GET['more'])
				{
						$pagesize	=	12;
						$conditions	=	"available = 1 and public = 1 and main = 1"; 
						$sortby		=	"dateline DESC";
						FLEA::loadClass('Lib_NewPager');
						$page	=	new Lib_NewPager( $tableModaShow, $pagesize, $conditions , $sortby );
						$Arowset	=	$page->rowset;
						/*
						*/
						$pagesize	=	24;
						$conditions	=	"available = 1 and public = 1 and main < 1";
						$sortby		=	"dateline DESC";
						FLEA::loadClass('Lib_NewPager');
						$page	=	new Lib_NewPager( $tableModaShow, $pagesize, $conditions , $sortby );
						$Browset	=	$page->rowset;
				
						include(APP_DIR . '/LichiViews/Shows.html');	
				}
	}
	/*
	*/
	function actionLichiNewsList()
	{
				$tableModaEvent = FLEA::getSingleton('Table_LichiEvent');
				$modaEvent  = $tableModaEvent->findAll("","dateline desc");
				include(APP_DIR."/LichiViews/LichiNewsList.html");
	}
	/*
	*/
	function actionNewsDetail()
	{
				$event_id = (int)$_GET['event_id'];
				$tableModaEvent = FLEA::getSingleton('Table_LichiEvent');
				$modaEvent  = $tableModaEvent->find("event_id =".$event_id."");
				if(!$event_id)
					FI_alert("活动页不存在","/");
				include(APP_DIR."/LichiViews/NewsDetail.html");
	}
	/*
	*/
//	function actionNewsDiscuss()
//	{
//				$event_id = (int)$_GET['event_id'];
//				$tableModaEvent = FLEA::getSingleton('Table_LichiEvent');
//				$modaEvent  = $tableModaEvent->find("event_id = ".$event_id."");
//				/*
//				*/
//				$tableModaShowDiscuss = FLEA::getSingleton('Table_LichiShowDiscuss');
//				$ShowDiscuss = $tableModaShowDiscuss->findAll("status = 1 and show_id=".$event_id." order by dateline desc ");
//				/*
//				*/
//				$ShowDiscuss = Fi_CdbmembersSet($ShowDiscuss);
//				
//				include(APP_DIR . '/LichiViews/ShowEventDiscuss.html');		
//	}
	/*
	*/
//	function actionEditNewsDiscuss()
//	{
//				$discuss_id = (int)$_GET["discuss_id"];
//				FI_DiscussAdimnRoot($discuss_id,1);
//				/*
//				*/
//				$tableModaShowDiscuss = FLEA::getSingleton('Table_LichiShowDiscuss');
//				$disdat = $tableModaShowDiscuss->getShowDiscussByID($discuss_id);
//				
//				include(APP_DIR . '/showView/ShowEventDiscuss.html');		
//	}
	/*
	*/
	function actionPostNewsDiscuss()
	{
				
				FI_TestComUser($_SESSION["Zend_Auth"]['storage']->username,"","",1);
				/*
				*/
				$show_id = (int)$_POST['show_id'];
				if((int)$_POST['show_id'] == "")
					FI_alert("缺少参数","/");
				$Content = $_POST['content'];
				if($Content == "")
						FI_alert("回复不能为空","?Controller=MainACT&action=NewsDiscuss&event_id=".$show_id);
				$tableModaShowDiscuss = FLEA::getSingleton('Table_LichiShowDiscuss');
				if($_POST['discuss_id']!='')
						$discussData['call_id'] = (int)$_POST['discuss_id'];
				$discussData['status'] = 1;
				$discussData['show_id'] = $show_id;
				$discussData['uname'] = $_SESSION["Zend_Auth"]['storage']->username;
				$discussData['nickname'] = $_SESSION["Zend_Auth"]['storage']->nickname;
				$discussData['content'] = $Content;
				$discussData['dateline'] = time();
				$id = $tableModaShowDiscuss->savetar($discussData);
	
				FI_changePage("?Controller=MainACT&action=NewsDiscuss&event_id=".$show_id);						
	}
	/*
	*/
	function actionListMV()
	{
				$table = FLEA::getSingleton('Table_LichiNews');
				//$result = $table->findAll("","dateline desc limit 15");
				
				
				$pagesize	=	15;
			  	$conditions	=	"";
				$sortby		=	"dateline DESC";
				FLEA::loadClass('Lib_NewPager');
				$page	=	new Lib_NewPager( $table, $pagesize, $conditions , $sortby );
				$rowset	=	$page->rowset;	
				
				//include(APP_DIR."/View/ftlb.html");	
				include(APP_DIR."/LichiViews/ListMV.html");	
	} 
	/*
	*/
	 function actionMVDetail()
	 {
				$table = FLEA::getSingleton('Table_LichiNews');
				$news = $table->find("news_id = '".(int)$_GET['news_id']."'");
				//include(APP_DIR."/View/mdft222.html");
				$newslist = $table->findAll("","dateline DEsc limit 8");
				
				include(APP_DIR."/LichiViews/MVDetail.html");
	 }
	/*
	*/		
	function actionPersonal()
	{
				$tmpid = (int)$_GET['id'];
				if($tmpid == "")
				{
						$temp = FI_TestSysUser($_SESSION["Zend_Auth"]['storage']->username,0,1,1);
						FI_changePage("?Controller=MainACT&action=Personal&id=".$temp['user_id']."");
				}
				$visitUser_id = $tmpid;
				$result = FI_TestSysUser("",$tmpid,1,1);
				Fi_incresActiveNum();
				/*
				*/
				$tableLichiUser = FLEA::getSingleton('Table_LichiUser');
				$tableLichiUser->incrfield("user_id='".$result['user_id']."'","liulanliang","5");
				/*
				*/		
				$Table_LichiShows = FLEA::getSingleton('Table_LichiShows');
				$mainshows = $Table_LichiShows->getMainShowById($result['username']);
				$showData = $Table_LichiShows->findAll("username='".$result['username']."' and available = 1 and public = 1 and main = 0 ","sortvlue desc,dateline DESC limit 5");
				/*
				*/
				$tableModaShowDiscuss = FLEA::getSingleton('Table_LichiShowDiscuss');
				$ShowDiscuss = $tableModaShowDiscuss->getToPersonPage($tmpid);
				$ShowDiscuss = Fi_CdbmembersSet($ShowDiscuss);
				$ShowDiscuss = Fi_ConvertSPchar($ShowDiscuss,"content");
				/*
				*/
				$table3 = FLEA::getSingleton('Table_LichiNews');
				$news = $table3->find("user_id = ".$tmpid);
				/*
				*/
				$tableModaDoor = FLEA::getSingleton('Table_LichiDoor');	
				$doorDat = $tableModaDoor->getatest($tmpid);
				include(APP_DIR."/LichiViews/personal.html");
	}
	
    /**
     */	 
    function actionIndex()
	{
				//$_SESSION["Zend_Auth"]['storage']->username = "qwe";
		
				/*
				*/
				$tableshowips = FLEA::getSingleton('Table_LichiShowIps');	
				$tableshowips->incresAll();
				/*
				*/
				$tableModaUser = FLEA::getSingleton('Table_LichiUser');			
				$newusrs = $tableModaUser->findAll("pass = 1 order by user_id desc limit 0,10");
				/*
				*/
				$tableModaShow = FLEA::getSingleton('Table_LichiShows');	
				$showDatass = $tableModaShow->findAll("available = 1 and public = 1 and main = 1","sortvlue desc , show_id DESC limit 8");
				$showds = $tableModaShow->findAll("available = 1 and public = 1 and main < 1 ","dateline DESC limit 0,8");
				/*
				*/
				$tableModaDoor = FLEA::getSingleton('Table_LichiDoor');	
				$doorDat = $tableModaDoor->IndexTest();
				/*
				*/
				$table3 = FLEA::getSingleton('Table_LichiNews');
				if($doorDat['link_2img'])
					$news = $table3->find("news_id = ".(int)$doorDat['link_2img']);
				else
					$news = $table3->find("","dateline desc");
				/*
				*/
				$Table_Cdbthreads = FLEA::getSingleton('Table_Cdbthreads');
				$bbs = $Table_Cdbthreads->findAll("fid = 951","dateline DESC limit 0,11");
				/*
				*/
				$tableModaEvent = FLEA::getSingleton('Table_LichiEvent');
				$modaEvent  = $tableModaEvent->find("","event_id desc");
				include(APP_DIR."/LichiViews/index.html");
    }
	
    function actiondumpdata()
	{
		
				$tableModaUser = FLEA::getSingleton('Table_LichiUser');
				$userinfo = $tableModaUser->findAll("");
				//$userinfo = $tableModaUser->getUserByUserId(2);
				
//				$tableModaUser = FLEA::getSingleton('Table_LichiShows');
//				//$userinfo = $tableModaUser->getShowByShowIdNoLimit(2);
//				$userinfo = $tableModaUser->getShows();
	
				$tableModaUser = FLEA::getSingleton('Table_Cdbpms');
				$userinfo = $tableModaUser->findAll("msgtoid = 16910");
	
				dump($userinfo);
	}
	
	

}
?>
