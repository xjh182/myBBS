<?php
include_once '../inc/config_inc.php';
include_once '../inc/mysql_inc.php';
include_once '../inc/too_inc.php';
$link = connect();

//验证登录
include_once 'inc/is_manage_login_inc.php';

var_dump($_SESSION)
?>