<?php
    if(empty($_POST['name'])){
        skip("管理员名称不得为空", "error", "manage_add.php");
    }
    if(mb_strlen($_POST['name'],'utf-8')>233){
        skip("管理员名称不能超过233个字符", "error", "manage_add.php");
    }
    if(mb_strlen($_POST['pw'],'utf-8')<6){
        skip("密码不得少于6位", "error", "manage_add.php");
    }

    if($_POST['level']=='1'){
        $_POST['level']=1;
    }else if($_POST['level']=='0'){
        $_POST['level']=0;
    }else{
        $_POST['level']=1;
    }

    $_POST = escape($link,$_POST);
    if ($check_flag=='add'){
        $query = "select * from bbs_manage where name='{$_POST['name']}'";
    }
    $result = execute($link, $query);
    if(mysqli_num_rows($result)){
        skip("板块已经存在", "error", "manage_add.php");
    }
?>
