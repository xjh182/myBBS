<?php
include 'inc/config_inc.php';
include_once 'inc/mysql_inc.php';
include_once 'inc/too_inc.php';

$link = connect();
if (!$member = isLogin($link)) {
    skip('你没有登录', 'error', 'login.php');
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    skip('帖子id不合法', 'error', 'index.php');
}

//查询帖子信息，校验
$query = "select * from bbs_content where id={$_GET['id']}";
$result_content = execute($link, $query);
if (mysqli_num_rows($result_content) == 0) {
    skip('帖子不存在', 'error', 'index.php');
}
$data_content = mysqli_fetch_assoc($result_content);
if($data_content['member_id']!=$member){
    skip('你没有权限', 'error', 'index.php');
}

//查询板块信息
$query = "select * from bbs_son_module where id={$data_content['module_id']}";
$result_module = execute($link, $query);
$data_module = mysqli_fetch_assoc($result_module);


if (isset($_POST['submit'])) {
    include 'inc/check_publish_inc.php';
    unlink("{$data_content['md_file']}");
    $new_filename='content/'.str_replace('.','',uniqid($_POST['title'].'_',true)).'.md';
    $MDFile = fopen($new_filename, "w");
    $MDWite = fwrite($MDFile, $_POST['content']);
    fclose($MDFile);
    $_POST = escape($link, $_POST);
    $query = "update
                bbs_content
            set
                title='{$_POST['title']}',
                module_id='{$_POST["module_id"]}',
                md_file='{$new_filename}'
            where
                id={$_GET['id']}
            ";
    execute($link, $query);
    if (mysqli_affected_rows($link) == 1) {
        skip('修改成功', 'ok', 'index.php');
    } else {
        skip('修改失败，请重试', 'error', "{$_SERVER['REQUEST_URI']}");
    }
}

$title = "修改页面";
$css = ['style/public.css', 'style/publish.css'];

include_once 'inc/header_inc.php';
?>

<div id="position" class="auto">
    <a href="index.php">首页</a> &gt; <a>发帖</a>
</div>
<div id="publish">
    <form method="post">
    <select name="module_id">
            <option>请选择一个版块</option>
            <?php
            $query = "select * from bbs_father_module order by sort desc";
            $result_father = execute($link, $query);
            while ($data_father = mysqli_fetch_assoc($result_father)) {
                echo "<optgroup label='{$data_father["module_name"]}'>";
                $query = "select * from bbs_son_module where father_module_id={$data_father['id']} order by sort desc";
                $result_son = execute($link, $query);
                while ($data_son = mysqli_fetch_assoc($result_son)) {
                    if ($data_module['id'] == $data_son['id']) {
                        echo "<option selected='selected' name='module_id' value='{$data_son["id"]}'>{$data_son['module_name']}</option>";
                    } else {
                        echo "<option name='module_id' value='{$data_son["id"]}'>{$data_son['module_name']}</option>";
                    }
                }
                echo "</optgroup>";
            }
            ?>
        </select>
        <input class="title" placeholder="请输入标题" name="title" type="text" value="<?php echo $data_content['title'] ?>" />
        <br>
        <div id="vditor"></div>
        <textarea id="content" name="content" style="display: none;"></textarea>
        <input id="submit" class="publish" type="submit" name="submit" value="" />
        <div style="clear:both;"></div>
    </form>
    <script>
            window.onload = function() {
                var vditor;
                fetch("<?php echo $data_content['md_file'] ?>").
                then(response => response.text()).
                then(markdown => {
                    vditor = new Vditor('vditor', {
                        "height": 360,
                        "cache": {
                            "enable": false
                        },
                        "value": markdown,
                        "mode": "sv",
                        "preview": {
                            "mode": "both"
                        }
                    });
                });
                document.getElementById('vditor').append(vditor);
                var content = document.getElementById('content');
                setInterval(function() {
                    content.innerHTML = vditor.getValue();
                }, 100);
            }
        </script>
</div>
<?php
include_once 'inc/footer_inc.php';
?>