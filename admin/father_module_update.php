<?php
include_once '../inc/config_inc.php';
include_once '../inc/mysql_inc.php';
include_once '../inc/too_inc.php';
$title = '父板块修改页';
$css = ["style/public.css"];
$link = connect();
if(!isset($_GET['id']) or !is_numeric($_GET['id'])){
    skip("id参数错误，请重试","error","father_module.php");
}
$query = "select * from bbs_father_module where id={$_GET['id']}";
$result = execute($link,$query);
if(!mysqli_num_rows($result)){
    skip("此父板块不存在，请重试","error","father_module.php");
}
if(isset($_POST['submit'])){
    //验证
    $check_flag='update';
    include './inc/check_father_module_inc.php';
    $query="update bbs_father_module set module_name='{$_POST['module_name']}',sort={$_POST['sort']} where id = {$_GET['id']}";
    execute($link,$query);
    if(mysqli_affected_rows(($link))==1){
        skip("修改成功","ok","father_module.php");
    }
    else{
        skip('修改失败，请重试','error','father_module.php');
    }
}
$data=mysqli_fetch_assoc($result);
?>
<?php include './inc/header_inc.php' ?>
<div id="main">
    <div class="title">修改父板块 - <?php echo $data['module_name'] ?></div>
    <form method="POST">
        <table class="au">
            <tr>
                <td>版块名称</td>
                <td><input name="module_name" value="<?php echo $data['module_name'] ?>" type="text" /></td>
                <td>
                    板块名称不能为空
                </td>
            </tr>
            <tr>
                <td>排序</td>
                <td><input name="sort" value="<?php echo $data['sort'] ?>" type="text" /></td>
                <td>
                    填写一个数字即可
                </td>
            </tr>
        </table>
        <input style="margin-top: 20px; cursor:pointer;" class="btn" type="submit" name="submit" value="修改" />
    </form>
</div>
<?php include 'inc/footer_inc.php' ?>