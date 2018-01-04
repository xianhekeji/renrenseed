<?php

/**
 * @filename dbClass.php  
 * @encoding UTF-8  
 * @author liguangming <JN XianHe>  
 * @datetime 2017-7-18 11:23:40
 *  @version 1.0 
 * @Description
 *  */
$db_config = array();
$db_config['database'] = 'mysql';
$db_config['pconnect'] = '0';
$db_config['db_host'] = '101.200.62.29';
$db_config['db_name'] = 'db_renrenseed';
$db_config['db_user'] = 'root';
$db_config['db_pass'] = 'devLGM123';
$db_config['db_charset'] = 'utf8_decode';
$db_config['db_expires'] = '0';
require_once 'PDODB.php';
//数据连接初始化
//$db_class = PDODB;
$db = new PDODB($db_config['db_host'], $db_config['db_name'], $db_config['db_user'], $db_config['db_pass']);
$db->__construct($db_config['db_host'], $db_config['db_name'], $db_config['db_user'], $db_config['db_pass']);
