<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_Cdbpms extends FLEA_Db_TableDataGateway
{

    var $tableName = 'cdb_pms';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'pmid';
	

//	function _getUserByUsername($username){
//		return $this->find("username='".$username."'");
//	}



}
