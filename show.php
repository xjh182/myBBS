<?php
include 'inc/config_inc.php';
include_once 'inc/mysql_inc.php';
include_once 'inc/too_inc.php';

$link = connect();
$member = isLogin($link);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    skip('帖子参数不合法', 'error', 'index.php');
}

//文章信息查询
$query = <<< A
select
    bbs_content.id,
    bbs_content.module_id,
    bbs_content.title,
    bbs_content.md_file,
    bbs_content.time,
    bbs_content.member_id,
    bbs_content.times,
    bbs_member.name,
    bbs_member.photo
from
    bbs_content,
    bbs_member
where
    bbs_content.id={$_GET['id']} and
    bbs_content.member_id=bbs_member.id
A;

$result_content = execute($link, $query);
if (mysqli_num_rows($result_content) == 0) {
    skip('帖子不存在', 'error', 'index.php');
}
$data_content = mysqli_fetch_assoc($result_content);
//标题禁止嵌入html，
$data_content['title'] = htmlspecialchars($data_content['title']);

//阅读量
$data_content['times'] = $data_content['times'] + 1;
$query = "update bbs_content set times=times+1 where id={$_GET['id']}";
execute($link, $query);
//子板块信息查询
$query = "select * from bbs_son_module where id={$data_content['module_id']}";
$result_son = execute($link, $query);
$data_son = mysqli_fetch_assoc($result_son);

//父板块信息查询
$query = "select * from bbs_father_module where id={$data_son['father_module_id']}";
$result_father = execute($link, $query);
$data_father = mysqli_fetch_assoc($result_father);

$title = $data_content['title'];
$css = ['style/public.css', 'style/show.css'];

include_once 'inc/header_inc.php';
include_once 'inc/page_inc.php';
?>
<div id="position" class="auto">
    <a href="index.php">首页</a> &gt; <a href="list_father.php?id=<?php echo $data_father['id'] ?>"><?php echo $data_father['module_name'] ?></a> &gt; <a href="list_son.php?id=<?php echo $data_son['id'] ?>"><?php echo $data_son['module_name'] ?></a> &gt; <?php echo $data_content['title'] ?>
</div>
<div id="main" class="auto">
    <div class="wrap1">
        <div class="pages">
            <?php
            $query = "select count(*) from bbs_reply where content_id={$_GET['id']}";
            $count_reply = num($link, $query);
            $page_size = 10;
            $page = page($count_reply, $page_size);
            echo $page['html'];
            ?>
        </div>
        <a class="btn reply" href="reply.php?id=<?php echo $_GET['id']; ?>"></a>
        <div style="clear:both;"></div>
    </div>

    <!-- 帖子内容 -->
    <?php
    if (!isset($_GET['page']) || $_GET['page'] == 1) {
    ?>
        <div class="wrapContent">
            <div class="pubdate">
                <span class="date">发布于：<?php echo $data_content['time'] ?> </span>
                <span class="floor" style="color:red;font-size:14px;font-weight:bold;">楼主</span>
            </div>
            <div class="left">
                <div class="face">
                    <a target="_blank" href="">
                        <img width="120" height="120" src="<?php if ($data_content['photo']) {
                                                                echo $data_content['photo'];
                                                            } else {
                                                                echo 'style/photo.jpg';
                                                            } ?>" />
                    </a>
                </div>
                <div class="name">
                    <a href=""><?php echo $data_content['name'] ?></a>
                </div>
            </div>
            <div class="right">
                <div class="content" id="main_content">
                </div>
            </div>
            <div style="clear:both; "></div>
        </div>
    <?php
    }
    ?>

    <!-- 回复 -->
    <?php
    $query = <<< A
    select
        bbs_reply.id,
        bbs_reply.content_id,
        bbs_reply.quote_id,
        bbs_reply.content,
        bbs_reply.time,
        bbs_member.name,
        bbs_member.id uid,
        bbs_reply.member_id,
        bbs_member.photo
    from
        bbs_reply,
        bbs_member
    where
        bbs_reply.member_id=bbs_member.id and
        bbs_reply.content_id={$_GET['id']}
        {$page['limit']}
    A;
    $result_reply = execute($link, $query);
    $floor = ($_GET['page'] - 1) * $page_size + 1;
    while ($data_reply = mysqli_fetch_assoc($result_reply)) {
    ?>
        <div class="wrapContent">
            <div class="left">
                <div class="face">
                    <a target="_blank" href="user.php?id=<?php echo $data_reply['uid'] ?>">
                        <img width="120" height="120" src="<?php if ($data_reply['photo']) {
                                                                echo $data_reply['photo'];
                                                            } else {
                                                                echo 'style/photo.jpg';
                                                            } ?>" />
                    </a>
                </div>
                <div class="name">
                    <a href=""><?php echo $data_reply['name'] ?></a>
                </div>
            </div>
            <div class="right">

                <div class="pubdate">
                    <span class="date"><?php echo $data_reply['time'] ?></span>
                    <span class="floor"><?php echo $floor++ ?>楼&nbsp;|&nbsp;<a href="quote.php?id=<?php echo $data_content['id'] ?>&reply=<?php echo $data_reply['id'] ?>">引用</a></span>
                </div>
                <div class="content">
                    <?php if ($data_reply['quote_id'] != 0) {
                        $query = "select * from bbs_reply where id={$data_reply['quote_id']}";
                        $result_quote = execute($link, $query);
                        $data_quote = mysqli_fetch_assoc($result_quote);
                        $query = "select count(*) from bbs_reply where content_id={$_GET['id']} and id<={$data_reply['quote_id'] }";
                        $quoteFloor = num($link,$query);
                    ?>
                        <div class="quote">
                            <h2>引用 <?php echo $quoteFloor ?>楼 <?php echo $data_quote['name'] ?> 发表的: </h2>
                            <?php echo $data_quote['content'] ?>
                        </div>
                    <?php
                    }
                    ?>
                    <?php echo $data_reply['content'] ?>
                </div>
            </div>
            <div style="clear:both;"></div>
        </div>
    <?php
    }
    ?>

    <div class="wrap1">
        <div class="pages">
            <?php echo $page['html']; ?>
        </div>
        <a class="btn reply" href="reply.php?id=<?php echo $_GET['id']; ?>"></a>
        <div style="clear:both;"></div>
    </div>
</div>
<script>
    window.onload = function() {
        const initOutline = () => {
            const headingElements = []
            Array.from(document.getElementById('main_content').children).forEach((item) => {
                if (item.tagName.length === 2 && item.tagName !== 'HR' && item.tagName.indexOf('H') === 0) {
                    headingElements.push(item)
                }
            })

            let toc = []
            window.addEventListener('scroll', () => {
                const scrollTop = window.scrollY
                toc = []
                headingElements.forEach((item) => {
                    toc.push({
                        id: item.id,
                        offsetTop: item.offsetTop,
                    })
                })

                const currentElement = document.querySelector('.vditor-outline__item--current')
                for (let i = 0, iMax = toc.length; i < iMax; i++) {
                    if (scrollTop < toc[i].offsetTop - 30) {
                        if (currentElement) {
                            currentElement.classList.remove('vditor-outline__item--current')
                        }
                        let index = i > 0 ? i - 1 : 0
                        document.querySelector('div[data-id="' + toc[index].id + '"]').classList.add('vditor-outline__item--current')
                        break
                    }
                }
            })
        }
        fetch("<?php echo $data_content['md_file'] ?>").
        then(response => response.text()).
        then(markdown => {
            Vditor.preview(document.getElementById('main_content'),
                markdown, {
                    speech: {
                        enable: true,
                    },
                    anchor: 1,
                    after() {
                        if (window.innerWidth <= 768) {
                            return
                        }
                        const outlineElement = document.getElementById('outline')
                        Vditor.outlineRender(document.getElementById('main_content'), outlineElement)
                        if (outlineElement.innerText.trim() !== '') {
                            outlineElement.style.display = 'block'
                            initOutline()
                        }
                    },
                })
        })
    }
</script>
<?php
include_once 'inc/footer_inc.php';
?>