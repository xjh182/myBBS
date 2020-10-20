<?php
if(empty($_POST['module_id']) || !is_numeric($_POST['module_id'])){
    skip('所属id不合法','error','publish.php');
}
$query = "select * from bbs_son_module where id={$_POST['module_id']}";
$result = execute($link,$query);
if (mysqli_num_rows($result)!=1){
    skip('所属版块不存在','error','publish.php');
}
if (empty($_POST['title'])){
    skip('标题不得为空','error','publish.php');
}
if (empty($_POST['content'])){
    skip('内容不得为空','error','publish.php');
}
if (mb_strlen($_POST['title'])>233){
    skip('标题字数不得多于233个字符','error','publish.php');
}

?>