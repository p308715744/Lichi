<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_LichiNews extends FLEA_Db_TableDataGateway
{

    var $tableName = 'lichi_news';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'news_id';
	
	
	public function save($data)
	{	
		return parent::save($data);			
	}
	
	function removeByPkv($id){
		$uploaddir	=	get_app_inf('uploadDir');
		$row	=	$this->find((int)$id);
		if($row['img_url']!=""){unlink($row['img_url']);}
		if($row['url']!=""){unlink($row['url']);}
		return parent::removeByPkv($id);	
	}

	function delByNewsId($id)
	{
			$row	=	$this->find("news_id = ".$id."");
			if(!$row)
				 return "none";
			if($row['index_img']!=""){unlink($row['index_img']);}
			if($row['img_url']!=""){unlink($row['img_url']);}
			if($row['url']!=""){unlink($row['url']);}
			return $this->removeByPkv($id);
	}	
	

}
