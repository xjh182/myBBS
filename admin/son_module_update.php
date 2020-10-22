<?php
include_once '../inc/config_inc.php';
include_once '../inc/mysql_inc.php';
include_once '../inc/too_inc.php';
$title = '子板块修改页';
$css = ["style/public.css"];
$link = connect();

//验证登录
include_once 'inc/is_manage_login_inc.php';

if(!isset($_GET['id']) or !is_numeric($_GET['id'])){
    skip("id参数错误，请重试","error","son_module.php");
}
$query = "select * from bbs_son_module where id={$_GET['id']}";
$result = execute($link,$query);
if(!mysqli_num_rows($result)){
    skip("此子板块不存在，请重试","error","son_module.php");
}
if(isset($_POST['submit'])){
    //验证
    $check_flag='update';
    include './inc/check_son_module_inc.php';
    $query="update bbs_son_module set father_module_id='{$_POST['father_module_id']}',module_name='{$_POST['module_name']}',info='{$_POST['info']}',member_id='{$_POST['member_id']}',sort={$_POST['sort']} where id = {$_GET['id']}";
    execute($link,$query);
    if(mysqli_affected_rows(($link))==1){
        skip("修改成功","ok","son_module.php");
    }
    else{
        skip('修改失败，请重试','error','son_module.php');
    }
}
$data=mysqli_fetch_assoc($result);
?>
<?php include 'inc/header_inc.php' ?>
<div id="main">
    <div class="title">修改子板块 - <?php echo $data['module_name'] ?></div>
    <form method="POST">
        <table class="au">
            <tr>
                <td>所属父板块</td>
                <td>
                    <select name="father_module_id" id="">
                        <option value="0">=====请选择一个父板块=====</option>
                        <?php
                        $query="select * from bbs_father_module";
                        $result_father=execute($link,$query);
                        while($data_father=mysqli_fetch_assoc($result_father)){
                            if($data['father_module_id']==$data_father['id']){
                                echo "<option selected='selected' value='{$data_father['id']}'>{$data_father['module_name']}</option>";
                            }else{
                                echo "<option value='{$data_father['id']}'>{$data_father['module_name']}</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
                <td>
                    必须选择一个所属的父板块
                </td>
            </tr>
            <tr>
                <td>版块名称</td>
                <td><input name="module_name" value="<? echo $data['module_name'] ?>" type="text" /></td>
                <td>
                    板块名称不能为空
                </td>
            </tr>
            <tr>
                <td>版块简介</td>
                <td><textarea name="info"><?php echo $data['info'] ?></textarea></td>
                <td>
                    简介不得多于233个字符
                </td>
            </tr>
            <tr>
                <td>版主</td>
                <td>
                    <select name="member_id" id="">
                        <option value="0">=====请选择一个会员作为版主=====</option>
                    </select>
                </td>
                <td>
                    你可以在这里选择一个会员作为版主
                </td>
            </tr>
            <tr>
                <td>排序</td>
                <td><input name="sort" value="<? echo $data['sort'] ?>" type="text" /></td>
                <td>
                    填写一个数字即可
                </td>
            </tr>
        </table>
        <input style="margin-top: 20px; cursor:pointer;" class="btn" type="submit" name="submit" value="修改" />
    </form>
</div>
<?php include 'inc/footer_inc.php' ?>