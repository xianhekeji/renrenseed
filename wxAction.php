<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$time = date('Y-m-d H:i:s', time());

function app_wx_iconv_result_no($RequestSign, $result, $resultNote, $totalRecordNum, $pages, $pageNo, $detail) {
    $result = array(
        'RequestSign' => $RequestSign,
        'result' => $result,
        'resultNote' => $resultNote,
        'pages' => $pages,
        'pageNo' => $pageNo,
        'detail' => str_replace("\r", "\\n", str_replace("\r\n", '\\n', str_replace('\n', '\\n', $detail)))
    );

    return urldecode(json_encode(url_encode($result)));
}

function app_wx_iconv_result($RequestSign, $result, $resultNote, $totalRecordNum, $pages, $pageNo, $detail) {
    $result = array(
        'RequestSign' => $RequestSign,
        'result' => $result,
        'resultNote' => $resultNote,
        'pages' => $pages,
        'pageNo' => $pageNo,
        'detail' => $detail
    );

    return json_encode($result);
}

/**
 * 去除多余的0
 */
function del0($s) {
    $s = trim(strval($s));
    if (preg_match('#^-?\d+?\.0+$#', $s)) {
        return preg_replace('#^(-?\d+?)\.0+$#', '$1', $s);
    }
    if (preg_match('#^-?\d+?\.[0-9]+?0+$#', $s)) {
        return preg_replace('#^(-?\d+\.[0-9]+?)0+$#', '$1', $s);
    }
    return $s;
}
