<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_LichiAttachments extends FLEA_Db_TableDataGateway
{

    var $tableName = 'lichi_attachments';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'attach_id';
	
	//删除展示
	
	function deleteByAttachId($key){
		$this->deleteImgByAttachId($key);
		return $this->removeByPkv($key);
	}
	//删除所有展示
	function deleteAllByShowId($show_id){
		$this->deleteImgAllByShowId($show_id);
		return $this->removeByConditions("show_id=".$show_id."");
	}
	//插入
	function save_per($data)
	{

//			$upload_name = $data['uploadfilename'];
//			$img = $this->do_upload($upload_name);
//				if($img)
//				{
//					$data['img_url'] = $img;
//				}
			return $this->create($data);
	}
	//插入
	function savetar($data)
	{

			$upload_name = $data['uploadfilename'];
			$img = $this->do_upload($upload_name);
				if($img)
				{
					$data['img_url'] = $img;
				}
			$this->create($data);
	}
	//更新
	function upload_par($data){

//			$upload_name = $data['uploadfilename'];
//			$img = $this->do_upload($upload_name);
//			if($img)
//			{
//				$this->deleteImgByAttachId($data['attach_id']);
//				$data['img_url'] = $img;
//			}
//			$reData = $this->find("show_id=".$data['show_id']." and attach_id=".$data['attach_id']."");
			//$data['created_on'] = $reData['created_on'];
			
			return $this->updateByConditions("show_id=".$data['show_id']." and attach_id=".$data['attach_id']."",$data);

	}
	//更新
	function uploadtar($data){

			$upload_name = $data['uploadfilename'];
			$img = $this->do_upload($upload_name);
			if($img)
			{
				$this->deleteImgByAttachId($data['attach_id']);
				$data['img_url'] = $img;
			}
			$reData = $this->find("show_id=".$data['show_id']." and attach_id=".$data['attach_id']."");
			$data['created_on'] = $reData['created_on'];
			
			return $this->updateByConditions("show_id=".$data['show_id']." and attach_id=".$data['attach_id']."",$data);

	}
	//查询
	function getAttachAllByShowId($show_id){

		return $this->findAll("show_id=".$show_id."","dateline ASC");
	}
	
	function getCountByShowId($show_id){

		return $this->findCount("show_id=".$show_id."");
	}
	function getAttachByAttachId($key){

		return $this->find("attach_id=".$key."");
	}
	//上传图片
	function do_upload($upload_name)
	{
	
		$file_name =  $_FILES[$upload_name]['name'];
		
		$file_postfix = substr($file_name,strrpos($file_name,"."));
		$newname	=	"/showAttachUpload/showAttach".time().$file_postfix;
	
		$size=$_FILES[$upload_name]['size'];

		$limittyppe	=	array('.jpg','.JPG','.gif','.png','.PNG','.BMP','.bmp','.GIF');
			
		if(!in_array($file_postfix,$limittyppe) || $size>2048000){
			return false ;
		}else{
				 $temp_name = $_FILES[$upload_name]['tmp_name'];
				 
				 $result = move_uploaded_file($temp_name,".".$newname);
				 if($result)
				 {
					return $newname;
				 }else 
				 {
					return false;
				 }
		 }
		 
	}
	//删除图片
	function deleteImgByAttachId($key)
	 {
	 	$one = $this->find("attach_id=".$key."");
		if($one['img_url'])
			unlink($one['img_url']);

	}
	function deleteImgAllByShowId($show_id)
	 {
	 	$all = $this->findAll("show_id=".$show_id."");
		foreach($all as $one){
			unlink(".".$one['img_url']);

		}
	}	


	function _alertLocal($word=""){
		
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\" /><title>$word</title><script type=\"text/javascript\" >alert('$word');</script>
</head><body></body></html>";
	}
	
	
/////////////////新后台	
	function getPicTypeCountByShowId($show_id){

		return $this->findCount("show_id=".$show_id." and atc_type = 2");
	}


}
