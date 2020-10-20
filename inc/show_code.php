<?php
session_start();
include_once 'vcode_inc.php';
$_SESSION['vcode']=vcode(120,40,25,4,100,4);
?>