<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
if (isset($_SESSION['userinfo'])) {
    session_unset(); //free all session variable
    session_destroy(); //销毁一个会话中的全部数据
    header("location: https://www.renrenseed.com");
} else {
    header("location: https://www.renrenseed.com");
}
?>
