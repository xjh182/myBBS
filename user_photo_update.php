<?php
include 'inc/config_inc.php';
include_once 'inc/mysql_inc.php';
include_once 'inc/too_inc.php';
include_once 'inc/upload_inc.php';

$link = connect();
$member = isLogin($link);

if (!$member = isLogin($link)) {
    skip('请登录后再设置头像', 'error', 'login.php');
}

//用户信息查询
$query = "select photo from bbs_member where id={$member}";
$result_user = execute($link,$query);
$data_user = mysqli_fetch_assoc($result_user);

if(isset($_POST['submit'])){
	$save_path='userImg'.date('/Y/m/d/');//服务器上文件系统的路径
	if(isset($_POST['submit'])){
		$upload=upload($_COOKIE['member']['name'],$save_path,'10M','photo');
		if($upload['return']){
			$_POST = escape($link, $_POST);
			$query="update bbs_member set photo='{$upload['save_path']}' where id={$member}";
			execute($link,$query);
			if(mysqli_affected_rows($link)==1){
				skip('头像设置成功','ok',"member.php?id={$member}");
			}else{
				skip('头像设置失败，请重试','error','member_photo_update.php');
			}
		}else{
			skip('member_photo_update.php','error',$upload['error']);
		}
	}
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<title></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<style type="text/css">
body {
	font-size:12px;
	font-family:微软雅黑;
}
h2 {
	padding:0 0 10px 0;
	border-bottom: 1px solid #e3e3e3;
	color:#444;
}
.submit {
	background-color: #3b7dc3;
	color:#fff;
	padding:5px 22px;
	border-radius:2px;
	border:0px;
	cursor:pointer;
	font-size:14px;
}
#main {
	width:80%;
	margin:0 auto;
}
</style>
</head>
<body>
	<div id="main">
		<h2>更改头像</h2>
		<div>
			<h3>原头像：</h3>
			<img width="180" height="180" src= <?php if($data_user['photo']!=''){echo $data_user['photo'];}else{echo "style/photo.jpg";}?> />
			<br />
			最佳图片尺寸：180*180
		</div>
		<div style="margin:15px 0 0 0;">
			<form method="post" enctype="multipart/form-data">
				<input style="cursor:pointer;" width="100" type="file" name="photo" /><br /><br />
				<input class="submit" type="submit" name="submit" value="保存" />
			</form>
		</div>
	</div>
</body>
</html>