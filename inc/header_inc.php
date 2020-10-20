<!DOCTYPE html>
<html lang="zh-CN">

<head>
	<meta charset="utf-8" />
	<title><?php echo $title ?></title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<?php foreach ($css as $val) {
		echo "<link rel='stylesheet' type='text/css' href='{$val}' />";
	} ?>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vditor/dist/index.css" />
	<script src="https://cdn.jsdelivr.net/npm/vditor/dist/index.min.js" defer></script>

</head>

<body>
	<div class="header_wrap">
		<div id="header" class="auto">
			<div class="logo">sifangku</div>
			<div class="nav">
				<a class="hover" href="index.php">首页</a>
				<a href="publish.php">新帖</a>
				<a>话题</a>
			</div>
			<div class="search">
				<form>
					<input class="keyword" type="text" name="keyword" placeholder="搜索其实很简单" />
					<input class="submit" type="submit" name="submit" value="" />
				</form>
			</div>
			<div class="login">
				<?php
				if (isset($member) && $member) {
					echo "<a href='user.php?id={$member}'>{$_COOKIE['member']['name']}</a> <span style='color:#fff;'>|</span> <a href='logout.php'>退出</a>";
				} else {
					echo '<a href="login.php">登录</a>&nbsp';
					echo '<a href="register.php">注册</a>';
				}
				?>
			</div>
		</div>
	</div>
	<div style="margin-top:55px;"></div>