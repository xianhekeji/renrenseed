<?php

/**
 * @filename common.php  
 * @encoding UTF-8  
 * @author liguangming <JN XianHe>  
 * @datetime 2017-7-11 15:34:13
 *  @version 1.0 
 * @Description
 *  */
//定义文件路径

define('DT_ROOT', str_replace("\\", '/', dirname(__FILE__)));

require DT_ROOT . '/vendor/autoload.php';
require DT_ROOT . '/comm/xhComm.php';
require DT_ROOT . '/comm/Ip.class.php';
require_once DT_ROOT . '/vendor/autoload.php';
$CFG = array();
require DT_ROOT . '/config.php';
$config = array(
    'cache_dir' => DT_ROOT . '/compilation_cache',
    'template_dir' => DT_ROOT . '/templates'
);
$twig_data = array();
$twig_data['comm_url'] = $CFG['commurl'];
$twig_data['url'] = $CFG['url'];
$twig_data['DT_ROOT'] = DT_ROOT;

//$islogin = isset($_SESSION['userinfo']) ? true : false;
//$twig_data['islogin'] = $islogin;
//$twig_data['session'] = $_SESSION['userinfo'];
require DT_ROOT . '/comm/Twig.class.php';
$twigclass = TwigClass::class;
$twig = new $twigclass($config, $twig_data);
$twig->__construct($config, $twig_data);
define('DT_CHARSET', $CFG['charset']);
define('DT_PATH', $CFG ['url']);
//$ip_info = json_decode(getAddressFromIp(getIP()));
//$CFG['province'] = $ip_info->data->region;
require DT_ROOT . '/data/PDODB.php';
//数据连接初始化
//$db_class = PDODB;
$db = new PDODB($CFG['db_host'], $CFG['db_name'], $CFG['db_user'], $CFG['db_pass']);
$db->__construct($CFG['db_host'], $CFG['db_name'], $CFG['db_user'], $CFG['db_pass']);

function _post($str) {
    $val = !empty($_POST[$str]) ? $_POST[$str] : null;
    return $val;
}

function _get($str) {
    $val = !empty($_GET[$str]) ? $_GET[$str] : null;
    return $val;
}

?>