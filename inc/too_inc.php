<?php
function skip($message,$pic,$url){
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<title>正在跳转中</title>
<meta http-equiv="refresh" content="3;URL=<?php echo $url ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo SUB_URL.'style/remind.css' ?>" />
</head>
<body>
<div class="notice"><span class="pic <?php echo $pic ?>"></span> <?php echo $message ?> <a href="<?php echo $url ?>">3秒后自动跳转</a></div>
</body>
</html>
<?php
exit();
}
?>

<?php
//验证前台用户是否登录
function isLogin($link){
    if(isset($_COOKIE['member']['name']) && isset($_COOKIE['member']['pw'])){
        $query="select * from bbs_member where name='{$_COOKIE['member']['name']}' and pw='{$_COOKIE['member']['pw']}'";
        $result=execute($link,$query);
        if(mysqli_num_rows($result)==1){
            $data = mysqli_fetch_assoc($result);
            return $data['id'];
        }else{
            return false;
        }
    }
}

//验证后台管理员是否登录
function is_manage_login($link){
    if(isset($_SESSION['manage']['name']) && isset($_SESSION['manage']['pw'])){
        $query="select * from bbs_manage where name='{$_SESSION['manage']['name']}' and pw='{$_SESSION['manage']['pw']}'";
        $result=execute($link,$query);
        if(mysqli_num_rows($result)==1){
            $data = mysqli_fetch_assoc($result);
            return $data['id'];
        }else{
            return false;
        }
    }
}
?>
