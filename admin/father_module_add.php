<?php
include_once '../inc/config_inc.php';
include_once '../inc/mysql_inc.php';
include_once '../inc/too_inc.php';
$title = '父板块添加页';
$css = ["style/public.css", "style/father_module_add.css"];

if(isset($_POST['submit'])){
    $link = connect();
    //验证用户填写的信息
    $check_flag='add';
    include './inc/check_father_module_inc.php';
    $query = "INSERT INTO `bbs_father_module` (`module_name`, `sort`) VALUES ('{$_POST['module_name']}', '{$_POST['sort']}');";
    $result = execute($link, $query);
    if(mysqli_affected_rows($link)==1){
        skip("添加成功", "ok", "father_module_add.php");
    }
    else {
        skip("添加失败，请重试", "error", "father_module_add.php");
    }
}
?>
<?php include 'inc/header_inc.php' ?>
<div id="main">
    <div class="title">添加父板块</div>
    <form method="POST">
        <table class="au">
            <tr>
                <td>版块名称</td>
                <td><input name="module_name" type="text" /></td>
                <td>
                    板块名称不能为空
                </td>
            </tr>
            <tr>
                <td>排序</td>
                <td><input name="sort" type="text" /></td>
                <td>
                    填写一个数字即可
                </td>
            </tr>
        </table>
        <input style="margin-top: 20px; cursor:pointer;" class="btn" type="submit" name="submit" value="添加" />
    </form>
</div>
<?php include 'inc/footer_inc.php' ?>