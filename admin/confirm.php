<?php
include_once '../inc/config_inc.php';
if(!isset($_GET['message']) || !isset($_GET['url']) || !isset($_GET['return_url'])){//验证
    var_dump($_GET);
    exit();
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<title>确认页</title>
<meta name="keywords" content="后台界面" />
<meta name="description" content="后台界面" />
<link rel="stylesheet" type="text/css" href=<?php echo SUB_URL."style/remind.css" ?> />
</head>
<body>
<div class="notice"><span class="pic ask"></span><?php echo $_GET['message'] ?><br><a href="<?php echo $_GET['url'] ?>">确定</a><span> | </span><a href="<?php echo $_GET['return_url'] ?>">取消</a></div>
</body>
</html>