<?php
include_once '../inc/config_inc.php';
include_once '../inc/mysql_inc.php';
include_once '../inc/too_inc.php';
$link = connect();

//验证登录
include_once 'inc/is_manage_login_inc.php';

$title = '管理员';
$css = ["style/public.css"];
?>
<?php include 'inc/header_inc.php' ?>

<div id="main">
	<div class="title">管理员列表</div>
	<form method="post">
		<table class="list">
			<tr>
				<th>id</th>
                <th>管理员名称</th>
                <th>创建日期</th>
                <th>等级</th>
                <th>操作</th>
			</tr>
			<?php
			$query = "select * from bbs_manage";
			$result = execute($link, $query);
			while ($data = mysqli_fetch_assoc($result)) {
                if($data['level']==0){
                    $data['level']='超级管理员';
                }else{
                    $data['level']='普通管理员';
                }

                $url=urldecode("manage_delete.php?id={$data['id']}");
                $return_url=urldecode($_SERVER['REQUEST_URI']);
                $message="你真的要删除管理员 {$data['name']} 吗？";
                $delete_url="confirm.php?url={$url}&return_url={$return_url}&message={$message}";

				$html = <<<A
		<tr>
			<td>{$data['id']}</td>
            <td>{$data['name']}</td>
            <td>{$data['create_time']}</td>
            <td>{$data['level']}</td>
            <td><a href="{$delete_url}">[删除]</a></td>
		</tr>
A;
				echo $html;
			}
			?>
		</table>
	</form>
</div>

<?php include 'inc/footer_inc.php' ?>