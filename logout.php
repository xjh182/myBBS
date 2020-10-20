<?php
include 'inc/config_inc.php';
include_once 'inc/mysql_inc.php';
include_once 'inc/too_inc.php';
$link = connect();
$member=isLogin($link);
if(!$member){
	skip('您没有登录，不需要退出','error','index.php');
}

setcookie('member[name]','',time()-3600);
setcookie('member[pw]','',time()-3600);
skip('退出成功','ok','index.php');
?>