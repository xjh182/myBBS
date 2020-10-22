<?php
include 'inc/config_inc.php';
$_GET['message']=htmlspecialchars($_GET['message']);
if(!isset($_GET['message']) || !isset($_GET['url']) || !isset($_GET['return_url'])){
    var_dump($_SERVER['REQUEST_URI']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<title>确认页</title>
<meta name="keywords" content="确认页">
<meta name="description" content="确认页">
<link rel="stylesheet" type="text/css" href=<?php echo SUB_URL."style/remind.css" ?> />
</head>
<body>
<div class="notice"><span class="pic ask"></span> <?php echo $_GET['message'] ?> <a href="<?php echo $_GET['url'] ?>">确定</a><span> | </span><a href="<?php echo $_GET['return_url'] ?>">取消</a></div>
</body>
</html>