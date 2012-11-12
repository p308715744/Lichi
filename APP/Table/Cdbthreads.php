<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_Cdbthreads extends FLEA_Db_TableDataGateway
{

    var $tableName = 'cdb_threads';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'tid';

}
