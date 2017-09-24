<?php

/**
 * @filename dbClass.php  
 * @encoding UTF-8  
 * @author liguangming <JN XianHe>  
 * @datetime 2017-7-18 11:23:40
 *  @version 1.0 
 * @Description
 *  */
$CFG = array();
require DT_ROOT . '/common.php';
$db_class = 'db_' . $CFG['database'];
$db = new $db_class($CFG['db_host'], $CFG['db_user'], $CFG['db_pass'], $CFG['db_name'], $CFG['pconnect'], $CFG['db_charset']);
$db->__construct($CFG['db_host'], $CFG['db_user'], $CFG['db_pass'], $CFG['db_name'], $CFG['pconnect'], $CFG['db_charset']);
