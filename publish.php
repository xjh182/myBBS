<?php
include 'inc/config_inc.php';
include_once 'inc/mysql_inc.php';
include_once 'inc/too_inc.php';
$title = "发帖页面";
$css = ['style/public.css', 'style/publish.css'];

$link = connect();
if (!$member = isLogin($link)) {
    skip('请登录后再发帖', 'error', 'login.php');
}

if (isset($_POST['submit'])) {
    include 'inc/check_publish_inc.php';
    $new_filename='content/'.str_replace('.','',uniqid($_POST['title'].'_',true)).'.md';
    $MDFile = fopen($new_filename, "w");
    $MDWite = fwrite($MDFile,$_POST['content']);
    fclose($MDFile);
    $_POST = escape($link, $_POST);
    $query = "insert into bbs_content (module_id,title,md_file,member_id,time) values ({$_POST["module_id"]},'{$_POST['title']}','{$new_filename}','{$member}',now())";
    execute($link, $query);
    if (mysqli_affected_rows($link) == 1) {
        skip('发布成功', 'ok', 'index.php');
    } else {
        skip('发布失败，请重试', 'error', 'publish.php');
    }
}

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
            // $query="select * from bbs_father_module";
            // $result_father=execute($link,$query);
            // while($data_father=mysqli_fetch_assoc($result_father)){
            //     $query2 = "select * from bbs_son_module where father_module_id={$data_father['id']}";
            //     $result_bbs=execute($link,$query2);
            //     while($data_module=mysqli_fetch_assoc($result_bbs)){
            //         echo "<option value='{$data_father["id"]}'>{$data_father["module_name"]}->{$data_module["module_name"]}</option>";
            //     }
            // }

            // $query = "select * from bbs_father_module order by sort desc";
            // $result_father = execute($link,$query);
            // while ($data_father=mysqli_fetch_assoc($result_father)){
            //     echo $data_father['module_name'];
            // }
            if (isset($_GET['father_module_id'])) {
                $query = "select * from bbs_father_module where id={$_GET['father_module_id']} order by sort desc";
            } else {
                $query = "select * from bbs_father_module order by sort desc";
            }
            $result_father = execute($link, $query);
            while ($data_father = mysqli_fetch_assoc($result_father)) {
                echo "<optgroup label='{$data_father["module_name"]}'>";
                $query = "select * from bbs_son_module where father_module_id={$data_father['id']} order by sort desc";
                $result_son = execute($link, $query);
                while ($data_son = mysqli_fetch_assoc($result_son)) {
                    if (isset($_GET['son_module_id']) && $_GET['son_module_id'] == $data_son['id']) {
                        echo "<option selected='selected' name='module_id' value='{$data_son["id"]}'>{$data_son['module_name']}</option>";
                    } else {
                        echo "<option name='module_id' value='{$data_son["id"]}'>{$data_son['module_name']}</option>";
                    }
                }
                echo "</optgroup>";
            }
            ?>
            <!-- <optgroup label="父板块1">
                <option value="子版块1"></option>
            </optgroup>
            <optgroup label="父板块2">
                <option value="子版块1"></option>
            </optgroup> -->
        </select>
        <input class="title" placeholder="请输入标题" name="title" type="text" />
        <br>
        <div id="vditor"></div>
        <textarea id="content" name="content" style="display: none;"></textarea>
        <input id="submit" class="publish" type="submit" name="submit" value="" />
        <div style="clear:both;"></div>
    </form>
    <script>
            window.onload = function() {
                var vditor = new Vditor('vditor', {
                    "height": 360,
                    "cache": {
                        "enable": false
                    },
                    "value": "",
                    "mode": "sv",
                    "preview": {
                        "mode": "both"
                    }
                });
                document.getElementById('vditor').append(vditor);
                var content=document.getElementById('content');
                setInterval(function(){
                    content.innerHTML=vditor.getValue();
                    },100);
            }
        </script>
</div>
<?php
include_once 'inc/footer_inc.php';
?>