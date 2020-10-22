<?php
include_once '../inc/config_inc.php';
include_once '../inc/mysql_inc.php';
include_once '../inc/too_inc.php';
$link = connect();

if(isset($_POST['submit'])){
	include_once 'inc/check_login_inc.php';
	$_POST=escape($link,$_POST);
	$query="select * from bbs_manage where name='{$_POST['name']}' and pw=md5('{$_POST['pw']}')";
	$result=execute($link,$query);
	if(mysqli_num_rows($result)==1){
		$data=mysqli_fetch_assoc($result);
		$_SESSION['manage']['id']=$data['id'];
		$_SESSION['manage']['name']=$data['name'];
		$_SESSION['manage']['pw']=$data['pw'];
		$_SESSION['manage']['level']=$data['level'];
		skip('登录成功','ok','index.php');
	}else{
		skip('登录失败','error','login.php');
	}
}
?>

<!doctype html>
<html>
<script>
	//图片预加载
	var imgSrc = [];
	for (var i = 1; i <= 72; i++) {
		imgSrc.push('style/background/' + i + '.png');
	}
	console.log(imgSrc);

	var loaded = 0;
	var toload = imgSrc.length;

	for (var i = 0; i < imgSrc.length; i++) {
		var img = new Image();
		img.onload = function() {
			loaded++;
			var percent = parseInt(loaded / toload * 100);

		}
		img.src = imgSrc[i];
	}
</script>

<head>
	<meta charset="utf-8">
	<title>登录</title>
	<style>
		#login {
			width: 306px;
			height: 500px;
			margin: auto;
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			text-align: center;
		}

		.loginFrom {
			width: 300px;
			height: 50px;
			margin-top: 20px;
			text-indent: 20px;
			font-size: 1rem;
			font-weight: 400;
			line-height: 1.5;
			color: #6e707e;
			background-color: #fff;
			background-clip: padding-box;
			border: 1px solid #d1d3e2;
			border-radius: 50px;
			-webkit-transition: border-color .15s ease-in-out, -webkit-box-shadow .15s ease-in-out;
			transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out, -webkit-box-shadow .15s ease-in-out;
			outline: none
		}

		#vcodeIpt {
			width: 140px;
			float: left;
		}

		#vcodeImg {
			width: 140px;
			float: right;
		}

		#login-btn {
			background: #4e73df;
			color: #fff;
			text-indent: 0px;
			border: none;
		}

		#textBox {
			text-align: center;
			vertical-align: middel;
		}

		#logo {
			width: 70px;
			display: inline-block;
			margin: 0 auto;
		}

		#bg {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			transition: background-image 3s;
			background-size: cover;
			position: absolute;
			overflow: hidden;
		}
	</style>
</head>

<body>
	<div id="bg"></div>
	<script>
		//图片效果
		function randomNum(minNum, maxNum) {
			switch (arguments.length) {
				case 1:
					return parseInt(Math.random() * minNum + 1, 10);
					break;
				case 2:
					return parseInt(Math.random() * (maxNum - minNum + 1) + minNum, 10);
					break;
				default:
					return 0;
					break;
			}
		}
		document.getElementById('bg').style.backgroundImage = 'url(style/background/' + randomNum(1, 72) + '.png)';
		setInterval(function() {
			document.getElementById('bg').style.backgroundImage = 'url(' + imgSrc[randomNum(0, 71)] + ')';
		}, 5000);
	</script>
	<div id="login">
		<img src="style/logo.png" id="logo">
		<div id="textBox">
			<form method="post">
				<input type="text" class="loginFrom" name="name" placeholder="请输入账号">
				<br>
				<input type="password" class="loginFrom" name="pw" placeholder="请输入密码">
				<input id="vcodeIpt" class="loginFrom" name="vcode" type="text" placeholder="请输入验证码" />
				<img id="vcodeImg" class="loginFrom" id="vcode" class="vcode" src="<?php echo SUB_URL ?>inc/show_code.php" title="点击刷新"/>
				<script>
					document.getElementById('vcodeImg').onclick = function() {
						document.getElementById('vcodeImg').src = "<?php echo SUB_URL ?>inc/show_code.php" + "?" + "a=" + Math.ceil(Math.random() * 1000);
					}
				</script>
				<br>
				<input class="loginFrom" type="submit" id="login-btn" name=submit value="登录">
			</form>
		</div>
	</div>
</body>

</html>