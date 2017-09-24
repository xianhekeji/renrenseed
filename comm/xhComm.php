<?php

/**
 * @filename xhComm.php  
 * @encoding UTF-8  
 * @author liguangming <JN XianHe>  
 * @datetime 2017-7-18 9:01:14
 *  @version 1.0 
 * @Description
 *  */
function url_encode($str) {
    if (is_array($str)) {
        foreach ($str as $key => $value) {
            $str [urlencode($key)] = url_encode($value);
        }
    } else {
        $str = urlencode($str);
    }

    return $str;
}

?>