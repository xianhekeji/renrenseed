<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$data = $_POST ["data"];
$result = html_entity_decode($data);
echo $result;
