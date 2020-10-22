<?php
    if(empty($_POST['module_name'])){
        skip("板块名称不得为空", "error", "father_module_add.php");
    }
    if(!is_numeric($_POST['sort'])){
        skip("排序只能是数字", "error", "father_module_add.php");
    }
    if(mb_strlen($_POST['module_name'],'utf-8')>233){
        skip("板块名称不能超过233个字符", "error", "father_module_add.php");
    }
    $_POST = escape($link,$_POST);
    if ($check_flag=='add'){
        $query = "select * from bbs_father_module where module_name='{$_POST['module_name']}'";
    }else if ($check_flag=='update'){
        $query = "select * from bbs_father_module where module_name='{$_POST['module_name']}' and id!={$_GET['id']}";
    }else{
        skip('$check_flag参数错误','error','father_module_add.php');
    }
    $result = execute($link, $query);
    if(mysqli_num_rows($result)){
        skip("板块已经存在", "error", "father_module_add.php");
    }
?>
