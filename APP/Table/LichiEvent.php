<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_LichiEvent extends FLEA_Db_TableDataGateway
{

    var $tableName = 'lichi_event';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'event_id';
	
	public function save($data)
	{	
//		$limittyppe	=	array('.jpg','.gif','.png','.bmp');
//		$uploaddir ='lichiimgs/';	
//		
//		foreach($_FILES as $upload_name => $val)
//		{
//				$file_name = $_FILES[$upload_name]['name'];
//				if($file_name != "")
//				{
//						$file_postfix = strtolower(substr($file_name,strrpos($file_name,".")));
//						$newname	=	$uploaddir.$upload_name.rand(1000,9999).time().$file_postfix;
//						//文件类型检查
//						if(in_array($file_postfix,$limittyppe))
//						{
//							//如果是修改信息
//							if($data[$this->primaryKey]!="" and $file_name != "")
//							{
//								$trow	=	$this->find($data[$this->primaryKey]);
//								if($trow[$upload_name] !="")
//									unlink($trow[$upload_name]);
//							}
//							$temp_name = $_FILES[$upload_name]['tmp_name']; 		 
//							$result = move_uploaded_file($temp_name,$newname);	
//							$data[$upload_name]	=	$newname;		
//						}
//				}
//				
//		}
		return parent::save($data);			
	}
	
	function removeByPkv($id){
		$uploaddir	=	get_app_inf('uploadDir');
		$row	=	$this->find((int)$id);
		if($row['img_url']!=""){unlink($row['img_url']);}
		if($row['url']!=""){unlink($row['url']);}
		return parent::removeByPkv($id);	
	}

	function delByEventId($id)
	{
	
			$row	=	$this->find("event_id = ".$id."");
			if(!$row)
				 return "none";
				 
			if($row['home_img']!=""){unlink($row['home_img']);}
			if($row['index_img']!=""){unlink($row['index_img']);}
			if($row['header_img']!=""){unlink($row['header_img']);}
			return $this->removeByPkv($id);
		
	}	
	

}
