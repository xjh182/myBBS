<?php
include 'inc/config_inc.php';
include_once 'inc/mysql_inc.php';
include_once 'inc/too_inc.php';
$title = "帖子展示";
$css = ['style/public.css', 'style/publish.css'];

$link = connect();
$member = isLogin($link);

if(!$member=isLogin($link)){
    skip('请登录后再回复','error',"show.php?id={$_GET['id']}");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    skip('帖子参数不合法', 'error', "show.php?id={$_GET['id']}");
}

//文章信息查询
$query = <<< A
select
    bbs_content.id,
    bbs_content.module_id,
    bbs_content.title,
    bbs_content.member_id,
    bbs_member.name
from
    bbs_content,
    bbs_member
where
    bbs_content.id={$_GET['id']} and
    bbs_content.member_id=bbs_member.id
A;

$result_content = execute($link, $query);
if (mysqli_num_rows($result_content) == 0) {
    skip('帖子不存在', 'error', "show.php?id={$_GET['id']}");
}

$data_content = mysqli_fetch_assoc($result_content);

//子板块信息查询
$query = "select * from bbs_son_module where id={$data_content['module_id']}";
$result_son = execute($link, $query);
$data_son = mysqli_fetch_assoc($result_son);

//父板块信息查询
$query = "select * from bbs_father_module where id={$data_son['father_module_id']}";
$result_father = execute($link, $query);
$data_father = mysqli_fetch_assoc($result_father);

//回复
if(isset($_POST['submit'])){
    include 'inc/check_reply_inc.php';
    $_POST=escape($link,$_POST);
    $query = "insert into bbs_reply (content_id,content,time,member_id) values ({$_GET['id']},'{$_POST['content']}',now(),{$member})";
    execute($link,$query);
	if(mysqli_affected_rows($link)==1){
        skip('发布成功','ok',"show.php?id={$_GET['id']}");
    }else{
        skip('发布失败，请重试','error',"show.php?id={$_GET['id']}");
    }
}

include_once 'inc/header_inc.php';
?>
<div id="position" class="auto">
    <a href="index.php">首页</a> &gt; <a href="list_father.php?id=<?php echo $data_father['id'] ?>"><?php echo $data_father['module_name'] ?></a> &gt; <a href="list_son.php?id=<?php echo $data_son['id']?>"><?php echo $data_son['module_name'] ?></a> &gt; <?php echo $data_content['title']?>
</div>
<div id="publish">
    <div>回复：由 <?php echo $data_content['name']?> 发布的 <?php echo $data_content['title']?></div>
    <form method="post">
        <textarea name="content" class="content"></textarea>
        <input class="reply" type="submit" name="submit" value="" />
        <div style="clear:both;"></div>
    </form>
</div>
<?php
include_once 'inc/footer_inc.php';
?>