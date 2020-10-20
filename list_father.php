<?php
include 'inc/config_inc.php';
include_once 'inc/mysql_inc.php';
include_once 'inc/too_inc.php';
$title = "父板块列表页";
$css = ['style/public.css', 'style/list.css'];

$link = connect();
$member = isLogin($link);

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    skip('父板块id不合法','error','index.php');
}
//父板块信息查询
$query="select * from bbs_father_module where id={$_GET['id']}";
$result_father = execute($link,$query);
if(mysqli_num_rows($result_father)==0){
    skip('父板块不存在','error','index.php');
}
$data_father =mysqli_fetch_assoc($result_father);

//子版块信息查询
$query="select * from bbs_son_module where father_module_id={$_GET['id']}";
$result_son=execute($link,$query);
$id_son='';
$son_list='';
while($data_son=mysqli_fetch_assoc($result_son)){
    $id_son .=$data_son['id'].',';
    $son_list .= "<a href='list_son.php?id={$data_son['id']}' >{$data_son['module_name']}</a> ";
}
$id_son = trim($id_son,',');
//计算发帖数量
if($id_son==''){
    $id_son='0';
}
$query = "select count(*) from bbs_content where module_id in({$id_son})";
$count_all = num($link, $query);
$query = "select count(*) from bbs_content where module_id in({$id_son}) and time>CURDATE()";
$count_today = num($link, $query);


include_once 'inc/header_inc.php';
include_once 'inc/page_inc.php';
//分页
$page=page($count_all,10,5);
?>
<div id="position" class="auto">
    <a href="index.php">首页</a> &gt; <a href="list_father.php?id=<?php echo $data_father['id'] ?>" target="_blank"><?php echo $data_father['module_name'] ?></a>
</div>
<div id="main" class="auto">
    <div id="left">
        <div class="box_wrap">
            <h3><?php echo $data_father['module_name'] ?></h3>
            <div class="num">
                今日：<span><?php echo $count_today ?></span>&nbsp;&nbsp;&nbsp;
                总帖：<span><?php echo $count_all ?></span>
                <div class="moderator"> 子版块： <?php echo $son_list ?> </div>
            </div>
            <div class="pages_wrap">
                <a class="btn publish" href="publish.php?father_module_id=<?php echo $data_father['id'] ?>"></a>
                <div class="pages">
                <?php
                echo $page['html'];
            ?>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
        <div style="clear:both;"></div>
        <ul class="postsList">
            <?php
            $query = <<< A
            select
                bbs_content.md_file,
                bbs_content.id,
                bbs_son_module.id sid,
                bbs_son_module.module_name,
                bbs_content.time,
                bbs_member.name,
                bbs_member.id uid,
                bbs_member.photo,
                bbs_content.times
            from
                bbs_content,
                bbs_member,
                bbs_son_module
            where
                bbs_content.module_id in ({$id_son}) and
                bbs_content.member_id=bbs_member.id and
                bbs_content.module_id=bbs_son_module.id
                order by bbs_content.time desc
            {$page['limit']}
            A;
            $result_content = execute($link,$query);
            while($data_content=mysqli_fetch_assoc($result_content)){
                $data_content['md_file']=htmlspecialchars($data_content['md_file']);
                $query="select count(*) from bbs_reply where content_id={$data_content['id']} ";
                $replies = num($link,$query);
                $query = "select * from bbs_reply where content_id={$data_content['id']} order by id desc limit 1";
                $result_last_reply=execute($link,$query);
                $data_last_reply = mysqli_fetch_assoc($result_last_reply);
            ?>
            <li>
                <div class="smallPic">
                    <a href="user.php?id=<?php echo $data_content['uid'] ?>">
                        <img width="45" height="45" src= <?php if($data_content['photo']!=''){echo $data_content['photo'];}else{echo "style/photo.jpg";}?>>
                    </a>
                </div>
                <div class="subject">
                    <div class="titleWrap"><a href="list_son.php?id=<?php echo $data_content['sid'] ?>">[<?php echo $data_content['module_name'] ?>]</a>&nbsp;&nbsp;<h2><a href="show.php?id=<?php echo $data_content['id'] ?>" target="_blank"><?php echo $data_content['md_file'] ?></a></h2>
                    </div>
                    <p>
                        楼主：<?php echo $data_content['name'] ?>&nbsp;<?php echo $data_content['time'] ?>&nbsp;&nbsp;&nbsp;&nbsp;最后回复：<?php if ($data_last_reply['time']){echo $data_last_reply['time'];} else{echo '暂无';} ?>
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
            }
            ?>
        </ul>
        <div class="pages_wrap">
            <a class="btn publish"href="list_father.php?id=<?php echo $data_father['id'] ?>" target="_blank"></a>
            <div class="pages">
            <?php
                echo $page['html'];
            ?>
            </div>
            <div style="clear:both;"></div>
        </div>
    </div>
    <div id="right">
        <div class="classList">
            <div class="title">版块列表</div>
            <?php
            $query = "select * from bbs_father_module"; //查询父板块
            $result_father = execute($link,$query);
            while($data_father=mysqli_fetch_assoc($result_father)){
            ?>
            <ul class="listWrap">
                <li>
                    <h2><a href="list_father.php?id=<?php echo $data_father['id'] ?>"><?php echo $data_father['module_name'] ?></a></h2>
                    <ul>
                        <?php
                        $query = "select * from bbs_son_module where father_module_id={$data_father['id']}"; //查询子版块
                        $result_son = execute($link,$query);
                        while($data_son=mysqli_fetch_assoc($result_son)){
                        ?>
                        <li>
                            <h3><a href="list_son.php?id=<?php echo $data_son['id'] ?>"><?php echo $data_son['module_name'] ?></a></h3>
                        </li>
                        <?php
                        }
                        ?>
                    </ul>
                </li>
            </ul>
            <?php
            }
            ?>
        </div>
    </div>
    <div style="clear:both;"></div>
</div>
<?php
include_once 'inc/footer_inc.php';
?>