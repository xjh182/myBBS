<?php
include 'inc/config_inc.php';
include_once 'inc/mysql_inc.php';
include_once 'inc/too_inc.php';

$link = connect();
$member = isLogin($link);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    skip('用户id不合法', 'error', 'index.php');
}

//用户信息查询
$query = "select * from bbs_member where id={$_GET['id']}";
$result_user = execute($link,$query);
if (mysqli_num_rows($result_user) == 0) {
    skip('用户不存在', 'error', 'index.php');
}
$data_user = mysqli_fetch_assoc($result_user);

//帖子总计
$query = "select count(*) from bbs_content where bbs_content.member_id={$_GET['id']}";
$posts=num($link,$query);

$title = "{$data_user['name']}的个人空间";
$css = ['style/public.css', 'style/list.css'];
include_once 'inc/header_inc.php';
include_once 'inc/page_inc.php';
$page= page($posts,10,5);
?>

<style type="text/css">
    #main #right .member_big {
        margin: 20px auto 0 auto;
        width: 180px;
    }

    #main #right .member_big dl dd {
        line-height: 150%;
    }

    #main #right .member_big dl dd a {
        color: #333;
    }

    #main #right .member_big dl dd.name {
        font-size: 22px;
        font-weight: 400;
        line-height: 140%;
        padding: 5px 0 10px 0px;
    }
</style>
<div id="position" class="auto">
    <a href="index.php">首页</a> &gt; <?php echo $data_user['name'] ?>
</div>
<div id="main" class="auto">
    <div id="left">
        <ul class="postsList">
            <?php
            $query = "select * from bbs_content where bbs_content.member_id={$_GET['id']} order by bbs_content.time desc {$page['limit']}";
            $result_content = execute($link,$query);
            while($data_content = mysqli_fetch_assoc($result_content)){
                $data_content['title']=htmlspecialchars($data_content['title']);
                $query="select count(*) from bbs_reply where content_id={$data_content['id']} ";
                $replies = num($link,$query);
                $query = "select * from bbs_reply where content_id={$data_content['id']} order by id desc limit 1";
                $result_last_reply=execute($link,$query);
                $data_last_reply = mysqli_fetch_assoc($result_last_reply);
                $data_content['title']=htmlspecialchars($data_content['title']);
            ?>
            <li>
                <div class="smallPic">
                    <a href="#">
                    <img width="45" height="45" src= <?php if($data_user['photo']!=''){echo $data_user['photo'];}else{echo "style/photo.jpg";}?>>
                    </a>
                </div>
                <div class="subject">
                    <div class="titleWrap">
                        <h2><a target="_blank" href="show.php?id=<?php echo $data_content['id']?>"><?php echo $data_content['title'] ?></a></h2>
                    </div>
                    <p>
                        <?php
                        if($member=$data_content['member_id']){
                            $url = urlencode("content_delete.php?id={$data_content['id']}"); //编码，传值($_GET已经被解码)
                            $return_url = urlencode($_SERVER['REQUEST_URI']);
                            $message = "你真的要删除 {$data_content['title']} 吗？";
                            $delete_url = "confirm.php?url={$url}&return_url={$return_url}&message={$message}";
                        ?>
                        <a target='_blank' href='content_update.php?id=<?php echo $data_content['id'] ?>'>编辑</a> | <a href='<?php echo $delete_url ?>'>删除</a>
                        <?php }?>
                        最后回复：<?php echo $data_last_reply['content'] ?>
                    </p>
                </div>
                <div class="count">
                    <p>
                        回复<br /><span><?php echo $replies ?></span>
                    </p>
                    <p>
                        浏览<br /><span><?php echo $data_content['times'] ?></span>
                    </p>
                </div>
                <div style="clear:both;"></div>
            </li>
            <?php
        }?>
        <div class="pages"><?php echo $page['html'] ?></div>
        </ul>
    </div>
    <div id="right">
        <div class="member_big">
            <dl>
                <dt>
                <img width="180" height="180" src= <?php if($data_user['photo']!=''){echo $data_user['photo'];}else{echo "style/photo.jpg";}?>>
                </dt>
                <dd class="name"><?php echo $data_user['name'] ?></dd>
                <dd>帖子总计：<?php echo $posts ?></dd>
                <dd>操作：<a target="_blank" href="user_photo_update.php">修改头像</a> | <a target="_blank" href="user_photo_update.php?id=<?php echo $member ?>">修改密码</a></dd>
            </dl>
            <div style="clear:both;"></div>
        </div>
    </div>
    <div style="clear:both;"></div>
</div>
<?php
include_once 'inc/footer_inc.php';
?>