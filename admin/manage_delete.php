<?php
include_once '../inc/config_inc.php';
include_once '../inc/mysql_inc.php';
include_once '../inc/too_inc.php';
$link = connect();

//验证登录
include_once 'inc/is_manage_login_inc.php';

//id校验 防注入
if (!isset($_GET['id']) or !is_numeric($_GET['id'])) {
    skip("id参数错误", "error", "father_module.php");
}

$query = "delete from bbs_manage where id={$_GET['id']}";
//echo $query;
execute($link, $query);
if (mysqli_affected_rows($link) == 1) {
    skip("删除成功", "ok", "manage.php");
} else {
    skip("删除失败，请重试", "error", "manage.php");
}