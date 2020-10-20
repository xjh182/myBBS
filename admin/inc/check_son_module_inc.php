<?php
if(!is_numeric($_POST['father_module_id'])){
    skip('所属父板块不得为空','error','son_module_add.php');
}
$query="select *from bbs_father_module where id={$_POST['father_module_id']}";
$result=execute($link,$query);
if(mysqli_num_rows($result)==0){
    skip('所属的父板块不存在','error','son_module_add.php');
}
if(empty($_POST['module_name'])){
    skip('子板块名称不得为空','error','son_module_add.php');
}
if(mb_strlen($_POST['module_name'])>233){
    skip('子板块名称不得超过233个字符','error','son_module_add.php');
}

$_POST=escape($link,$_POST);
if ($check_flag=='add'){
    $query = "select * from bbs_son_module where module_name='{$_POST['module_name']}'";
}else if ($check_flag=='update'){
    $query = "select * from bbs_son_module where module_name='{$_POST['module_name']}' and id!={$_GET['id']}";
}else{
    skip('$check_flag参数错误','error','son_module_add.php');
}

$result = execute($link, $query);
if(mysqli_num_rows($result)){
    skip("这个子板块已经存在", "error", "son_module_add.php");
}
if(!is_numeric($_POST['sort'])){
    skip("排序只能是数字", "error", "son_module_add.php");
}
?>