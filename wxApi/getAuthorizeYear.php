<?php

require '../common.php';
include '../wxAction.php';
$sql = "select DISTINCT(AuthorizeYear) AuthorizeYear from WXAuthorize where AuthorizeYear>2009 
union all select '2009' AuthorizeYear ORDER BY AuthorizeYear DESC";
$result = $db->query($sql);
$array = array();
foreach ($result as $rows) {
    if ($rows ['AuthorizeYear'] == '2009') {
        $rows ['AuthorizeYear'] = '2009前';
    }
    $array [] = $rows;
}
echo app_wx_iconv_result_no('getAuthorizeYear', true, 'success', 0, 0, 0, $array);
?>