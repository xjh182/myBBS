<?php
include 'inc/config_inc.php';
include_once 'inc/mysql_inc.php';
include_once 'inc/too_inc.php';

$link = connect();
$member = isLogin($link);

if(!$member=isLogin($link)){
    skip('请登录后再回复','error',"show.php?id={$_GET['id']}");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    skip('文章参数不合法', 'error', "show.php?id={$_GET['id']}");
}

if (!isset($_GET['reply']) || !is_numeric($_GET['reply'])) {
    skip('回复参数不合法', 'error', "show.php?id={$_GET['id']}");
}

//查询回复信息
$query= <<< A
    select
        bbs_reply.id,
        bbs_reply.content,
        bbs_reply.member_id,
        bbs_member.name,
        bbs_reply.content_id
    from
        bbs_reply,
        bbs_member
    where
        bbs_reply.member_id=bbs_member.id and
        bbs_reply.id={$_GET['reply']}
A;
$result_reply = execute($link, $query);
if (mysqli_num_rows($result_reply) == 0) {
    skip('回复不存在', 'error', "show.php?id={$_GET['id']}");
}
$data_reply = mysqli_fetch_assoc($result_reply);

//文章信息查询
$query = "select * from bbs_content where bbs_content.id={$_GET['id']}";
$result_content = execute($link, $query);
if (mysqli_num_rows($result_content) == 0) {
    skip('文章不存在', 'error', "index.php");
}
$data_content = mysqli_fetch_assoc($result_content);

if(isset($_POST['submit'])){
    include 'inc/check_reply_inc.php';
    $_POST=escape($link,$_POST);
    $query = "insert into bbs_reply (content_id,quote_id,content,time,member_id) values ({$data_content['id']},'{$data_reply['id']}','{$_POST['content']}',now(),{$member})";
    execute($link,$query);
	if(mysqli_affected_rows($link)==1){
        skip('发布成功','ok',"show.php?id={$_GET['id']}");
    }else{
        skip('发布失败，请重试','error',"show.php?id={$_GET['id']}");
    }
}

//楼层
$query = "select count(*) from bbs_reply where content_id={$_GET['id']} and id<={$_GET['reply']}";
$floor = num($link,$query);

//子板块信息查询
$query = "select * from bbs_son_module where id={$data_content['module_id']}";
$result_son = execute($link, $query);
$data_son = mysqli_fetch_assoc($result_son);

//父板块信息查询
$query = "select * from bbs_father_module where id={$data_son['father_module_id']}";
$result_father = execute($link, $query);
$data_father = mysqli_fetch_assoc($result_father);

$title = "回复";
$css = ['style/public.css', 'style/publish.css'];
include_once 'inc/header_inc.php';
?>

<div id="position" class="auto">
    <a href="index.php">首页</a> &gt; <a href="list_father.php?id=<?php echo $data_father['id']?>"><?php echo $data_father['module_name'] ?></a> &gt; <a href="list_son.php?id=<?php echo $data_son['id'] ?>"><?php echo $data_son['module_name'] ?></a> &gt; <?php echo $data_content['md_file'] ?>
</div>
<div id="publish">
    <div><?php echo $data_content['name'] ?>: <?php echo $data_content['md_file'] ?></div>
    <div class="quote">
        <p class="title">引用<?php echo $floor ?>楼 <?php echo $data_reply['name']?> 发表的: </p>
        <?php echo $data_reply['content'] ?>
    </div>
    <form method="post">
        <textarea name="content" class="content"></textarea>
        <input class="reply" type="submit" name="submit" value="" />
        <div style="clear:both;"></div>
    </form>
</div>

<?php
include_once 'inc/footer_inc.php';
?>