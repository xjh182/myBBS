<?php
include 'inc/config_inc.php';
include_once 'inc/mysql_inc.php';
include_once 'inc/too_inc.php';
$link = connect();
$member=isLogin($link);
if($member){
	skip('你已经登录，不要重复登录！','error','index.php');
}

if (isset($_POST['submit'])) {
	include 'inc/check_login_inc.php';
	$_POST=escape($link, $_POST);
	$query = "select * from bbs_member where name='{$_POST['name']}' and pw=md5('{$_POST['pw']}')";
	$result = execute($link, $query);
	if (mysqli_num_rows($result) == 1) {
		setcookie('member[name]',$_POST['name'],time()+$_POST['time']);
		setcookie('member[pw]',md5($_POST['pw']),time()+$_POST['time']);
        skip('登录成功，正在跳转主页','ok','index.php');
	} else {
		skip('用户名或密码输入错误', 'error', 'login.php');
	}
}
$title = "登录页面";
$css = ['style/public.css', 'style/register.css'];
include_once 'inc/header_inc.php';
?>

<div style="margin-top:55px;"></div>
<div id="register" class="auto">
	<h2>欢迎注册成为 私房库会员</h2>
	<form method="post">
		<label>用户名：<input type="text" name="name" /><span></span></label>
		<label>密码：<input type="password" name="pw" /><span></span></label>
		<label>验证码：<input name="vcode" type="text" /><span>*请输入下方验证码</span></label>
		<img id="vcode" class="vcode" src="inc/show_code.php" />
		<script>
			document.getElementById('vcode').onclick = function() {
				document.getElementById('vcode').src = "inc/show_code.php" + "?" + "a=" + Math.ceil(Math.random() * 1000);
			}
		</script>
		<label>自动登录：
			<select style="width:236px;height:25px;" name="time">
				<option value="3600">1小时内</option>
				<option value="86400">1天内</option>
				<option value="259200">3天内</option>
				<option value="2592000">30天内</option>
			</select>
			<span>*公共电脑上请勿长期自动登录</span>
		</label>
		<div style="clear:both;"></div>
		<input class="btn" type="submit" value="确定登录" name="submit" />
	</form>
</div>
<?php
include_once 'inc/footer_inc.php';
?>