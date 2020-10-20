<?php
include 'inc/config_inc.php';
include_once 'inc/mysql_inc.php';
include_once 'inc/too_inc.php';
$title = "注册页面";
$css =['style/public.css','style/register.css'];
$link=connect();
$member=isLogin($link);
if($member){
	skip('您已经登录，请不要重复登录','error','index.php');
}
if(isset($_POST['submit'])){
	include 'inc/check_register_inc.php';
	$query="insert into bbs_member(name,pw,register_time) values('{$_POST['name']}',md5('{$_POST['pw']}'),now())";
	execute($link,$query);
	if(mysqli_affected_rows($link)==1){
		setcookie('member[name]',$POST['name']);
		setcookie('member[pw',md5($_POST['pw']));
        skip('恭喜你，注册成功','ok','register.php');
    }else{
        skip('对不起，注册失败，请重试','error','register.php');
    }
}
include_once 'inc/header_inc.php';
?>
	<div id="register" class="auto">
		<h2>欢迎注册成为 私房库会员</h2>
		<form method="post">
			<label>用户名：<input type="text" name="name" /><span>*用户名不得为空，并且长度不得超过233个字符</span></label>
			<label>密码：<input type="password" name="pw" /><span>*密码不得少于6位</span></label>
			<label>确认密码：<input type="password" name="confirm_pw" /><span>*请与上面一致</span></label>
			<label>验证码：<input name="vcode" type="text"  /><span>*请输入下方验证码</span></label>
			<img id="vcode" class="vcode" src="inc/show_code.php">
			<script>
			document.getElementById('vcode').onclick=function(){
				document.getElementById('vcode').src="inc/show_code.php"+"?"+"a="+Math.ceil(Math.random()*1000);
			}
			</script>
			<div style="clear:both;"></div>
			<input class="btn" type="submit" value="确定注册" name="submit"/>
		</form>
	</div>
<?php
include 'inc/footer_inc.php'
?>