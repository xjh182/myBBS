<?php
include_once '../inc/config_inc.php';
include_once '../inc/mysql_inc.php';
include_once '../inc/too_inc.php';

//id校验 防注入
if (!isset($_GET['id']) or !is_numeric($_GET['id'])) {
    skip("id参数错误", "error", "father_module.php");
}

$link = connect();

$query_son = "select * from bbs_son_module where father_module_id={$_GET['id']}";
$result_son = execute($link, $query_son);
if ($result_son->num_rows==0) {
    $query = "delete from bbs_father_module where id={$_GET['id']}";
    //echo $query;
    execute($link, $query);
    if (mysqli_affected_rows($link) == 1) {
        skip("删除成功", "ok", "father_module.php");
    } else {
        skip("删除失败，请重试", "error", "father_module.php");
    }
} else {

    $query = "delete from bbs_son_module where father_module_id={$_GET['id']}";
    execute($link, $query);

    $query = "delete from bbs_father_module where id={$_GET['id']}";
    //echo $query;
    execute($link, $query);
    if (mysqli_affected_rows($link) == 1) {
        skip("删除成功", "ok", "father_module.php");
    } else {
        skip("删除失败，请重试", "error", "father_module.php");
    }
}
