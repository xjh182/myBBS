<?php
include 'inc/config_inc.php';
include_once 'inc/mysql_inc.php';
include_once 'inc/too_inc.php';

$link = connect();
if (!$member = isLogin($link)) {
    skip('请先登录', 'error', 'login.php');
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    skip('帖子id不合法', 'error', 'index.php');
}

//查询帖子信息
$query = "select * from bbs_content where id={$_GET['id']}";
$result_content=execute($link,$query);
if(mysqli_num_rows($result_content)==1){
	$data_content=mysqli_fetch_assoc($result_content);
	if($member==$data_content['member_id']){
		$query="delete from bbs_content where id={$_GET['id']}";
		execute($link, $query);
		if(mysqli_affected_rows($link)==1){
            unlink($data_content["md_file"]);
            skip('恭喜你，删除成功!', 'ok',"user.php?id={$member}");
		}else{
			skip( '对不起删除失败!', 'error',"user.php?id={$member}");
		}
	}else{
		skip( '这个帖子不属于你，你没有权限!', 'error','index.php');
	}
}else{
	skip('帖子不存在!', 'error', 'index.php');
}

?>