<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require( "common.php");
//$twig->display('base.xhtml', array('name' => 'Bobby'));
echo $twig->render('base.xhtml', array('name' => 'Bobby'));
