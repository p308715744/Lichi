<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_LichiUser extends FLEA_Db_TableDataGateway
{
    var $tableName = 'lichi_users';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'user_id';
	function save_pr($data)
	{
		return $this->save($data);
	}
	public function savetar($data)
	{	
			$limittyppe	=	array('.jpg','.gif','.png','.bmp');
			$uploaddir ='regUpload/';	
			
			foreach($_FILES as $upload_name => $val)
			{
					$file_name = $_FILES[$upload_name]['name'];
					if($file_name != "")
					{
							$file_postfix = strtolower(substr($file_name,strrpos($file_name,".")));
							$newname	=	$uploaddir.$upload_name.rand(1000,9999).time().$file_postfix;
							//文件类型检查
							if(in_array($file_postfix,$limittyppe))
							{
									//如果是修改信息
									if($data[$this->primaryKey]!="" and $file_name != "")
									{
										$trow	=	$this->find($data[$this->primaryKey]);
										if($trow[$upload_name] !="")
											unlink($trow[$upload_name]);
									}
									$temp_name = $_FILES[$upload_name]['tmp_name']; 		 
									$result = move_uploaded_file($temp_name,$newname);	
									$data[$upload_name]	=	$newname;		
							}
					}
					
			}
			return parent::save($data);			
	}
	
	function removeByPkv($id){
		$uploaddir	=	get_app_inf('uploadDir');
		$row	=	$this->find((int)$id);
		if($row['img1']!=""){unlink($row['img1']);}
		if($row['img2']!=""){unlink($row['img2']);}
		if($row['img3']!=""){unlink($row['img3']);}
		if($row['img4']!=""){unlink($row['img4']);}
		return parent::removeByPkv($id);	
	}
	
	public function getAllUsers(){	
		return $this->findAll();
	}
	
	//查询
	public function getUserByUname($username)
	{
		return $this->find("username = '".$username."' and pass = 1");
	}
	
	//查询
	public function getUserByUnamePass($username)
	{
		return $this->find("username = '".$username."' and pass = 1");
	}
	
	//查询
	public function getUserByUserId($user_id)
	{
		return $this->find("user_id = ".$user_id." and pass = 1");
	}
	
	//查询
	public function getUserByUnameNoLimit($username)
	{
		return $this->find("username = '".$username."'");
	}
	
	//查询
	public function getUserByNickName($nickname)
	{
		return $this->findAll("nickname like '%".$nickname."%'");
	}
	
	
		//查询
	public function getUserIDByAddress($address)
	{
		return $this->find("address = '".$address."'");
	}
	
	
	//管理员操作插入用户
	public function AdminInsertUser($dataU)
	{
			$Data['username'] = $dataU['username'];
			$Data['truename'] = $dataU['truename'];
			$tablePassportUser = FLEA::getSingleton('Table_PassportUser');
			$userDat = $tablePassportUser->getUserByUsername($Data['username']);
			if(!$userDat)
			{
					echo "{success:false,message:\"this user is not exist in passportuser\"}"; 
					exit;
			}
			else
			{	
					$Data['nickname'] = $userDat['nickname'];
					$Data['uid'] = $userDat['uid'];
					$Data['pass'] = 0;
					$Data['dateline'] = time();
					$userDat = $this->getUserByUnameNoLimit($Data['username']);
					if($userDat)
					{
						echo "{success:false,message:\"this user is exist in lichiuser \"}"; 
						exit;
					}
					else
					{
						$user_id = $this->create($Data);
						return $user_id;
					}
			}
			
		
		
	}
	
	
	
	
	
	
	

}
