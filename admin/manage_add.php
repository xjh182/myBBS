<?php
include_once '../inc/config_inc.php';
include_once '../inc/mysql_inc.php';
include_once '../inc/too_inc.php';
$link = connect();

//验证登录
include_once 'inc/is_manage_login_inc.php';

if(isset($_POST['submit'])){
    $check_flag='add';
    //验证用户填写的信息
    include './inc/check_manage_inc.php';
    $query="insert into bbs_manage(name,pw,create_time,level) values('{$_POST['name']}',md5('{$_POST['pw']}'),now(),{$_POST['level']})";
    execute($link,$query);
    if(mysqli_affected_rows($link)==1){
        skip('恭喜你，添加成功','ok','manage.php');
    }else{
        skip('添加失败，请重试','ok','manage.php');
    }
}

$title = '添加管理员';
$css = ["style/public.css"];
?>
<?php include 'inc/header_inc.php' ?>

<div id="main">
    <div class="title">添加管理员</div>
    <form method="POST">
        <table class="au">
            <tr>
                <td>管理员名称</td>
                <td><input name="name" type="text" /></td>
                <td>
                    管理员名称不能为空,不得超过233个字符
                </td>
            </tr>
            <tr>
                <td>密码</td>
                <td><input name="pw" type="text" /></td>
                <td>
                    密码不能为空,不得少于233个字符
                </td>
            </tr>
            <tr>
                <td>等级</td>
                <td><select name="level">
                    <option name='level' value="1">普通管理员</option>
                    <option name='level' value="0">超级管理员</option>
                </select></td>
                <td>
                    请选择一个等级，默认为普通管理员
                </td>
            </tr>
        </table>
        <input style="margin-top: 20px; cursor:pointer;" class="btn" type="submit" name="submit" value="添加" />
    </form>
</div>

<?php include 'inc/footer_inc.php' ?>