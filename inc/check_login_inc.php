<?php
if(empty($_POST['name'])){
    skip('用户名不得为空','error','login.php');
}
if(mb_strlen($_POST['name'])>233){
    skip('用户名长度不得超过233个字符','error','login.php');
}
if(mb_strlen($_POST['pw'])<6){
    skip('密码不得少于6位','error','login.php');
}
if(strtolower($_POST['vcode'])!=strtolower($_SESSION['vcode'])){
    skip('验证码输入错误','error','login.php');
}
if(empty($_POST['name'] || is_numeric($_POST['name']) || $_POST['time']>2592000)){
    $_POST['time']=2592000;
}
?>