<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_LichiShowDiscuss extends FLEA_Db_TableDataGateway
{
    var $tableName = 'lichi_showdiscuss';
    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'discuss_id';
	
	//查询
	function getShowDiscussByShowId($show_id){

		return $this->findAll("status = 0 and show_id=".$show_id." order by dateline desc ");
	}
	//查询
	function getShowDiscussByUserid($user_id){

		return $this->findAll("status = 0 and user_id=".$user_id." order by dateline desc ");
	}
	//查询
	function getToPersonPage($user_id){

		return $this->findAll("status = 0 and user_id=".$user_id." order by dateline desc limit 0,11");
	}
	//查询
	function getToHomePage(){

		return $this->findAll("status = 0 order by dateline desc limit 0,12");
	}
	
	//查询
	function getShowDiscussByID($discuss_id){

		return $this->find("discuss_id=".$discuss_id."");
	}
	//插入
	function savetar($data){
			
		$tableModaShows = FLEA::getSingleton('Table_LichiShows');
		$showData = $tableModaShows->getShowByShowId($data['show_id']);	
		if($showData)
		{
			$showData['discuss_count'] += 1;
			$tableModaShows->updatetar($showData);	
		}
		return $this->save($data);
	}
	
	//更新
	function uploadtar($data){
		
		return $this->updateByConditions("discuss_id =".$data['discuss_id']."",$data);

	}
	
	//更新
	function uploadByUname($data){
		
		return $this->updateByConditions("uname = '".$data['uname']."' ",$data);

	}
	
	//删除
	function deleteByDiscussId($key){
	
		return $this->removeByPkv($key);
	}
	//删除showID
	function deleteAllByShowId($show_id){

		return $this->removeByConditions("status = 0 and show_id=".$show_id."");
	}
	
	function getDiscussCountByShowId($show_id){
		
		return $this->findCount("status = 0 and show_id=".$show_id."");
	}

	/*
	*/
	

}
