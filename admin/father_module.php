<?php
include_once '../inc/config_inc.php';
include_once '../inc/mysql_inc.php';
include_once '../inc/too_inc.php';
$link = connect();
$title = '父板块列表页';
$css = ["style/public.css"];
if(isset($_POST['submit'])){
	foreach($_POST['sort'] as $key=>$val){
		if(!is_numeric($key) || !is_numeric($val)){
			skip('排序参数错误','error','father_module.php');
		}
		$query[] = "update bbs_father_module set sort={$val} where id={$key}";
	}
	if(execute_multi($link,$query,$error)){
		skip('排序修改成功','ok','father_module.php');
	}else{
		skip('排序修改失败','error','father_module.php');
	}
}
?>
<?php include 'inc/header_inc.php' ?>
<div id="main">
	<div class="title">父板块列表</div>
	<form method="post">
		<table class="list">
			<tr>
				<th>排序</th>
				<th>版块名称</th>
				<th>操作</th>
			</tr>
			<?php
			$query = "select * from bbs_father_module";
			$result = execute($link, $query);
			while ($data = mysqli_fetch_assoc($result)) {
				$url = urlencode("father_module_delete.php?id={$data['id']}"); //编码，传值($_GET已经被解码)
				$return_url = urlencode($_SERVER['REQUEST_URI']);

				$query_son = "select * from bbs_son_module where father_module_id={$data['id']}";
				$result_son = execute($link, $query_son);
				if (($result_son->num_rows) > 0) {
					$message = "该父板块下面存在 {$result_son->num_rows} 个子板块，会被一起删除，你真的要删除 {$data['module_name']} 吗？";
				} else {
					$message = "你真的要删除 {$data['module_name']} 吗？";
				}
				$delete_url = "confirm.php?url={$url}&return_url={$return_url}&message={$message}";
				$html = <<<A
		<tr>
			<td><input class="sort" type="text" name="sort[{$data['id']}]" value="{$data['sort']}" /></td>
			<td>{$data['module_name']}[id:{$data['id']}]</td>
			<td><a href="#">[访问]</a>&nbsp;&nbsp;<a href="father_module_update.php?id={$data['id']}">[编辑]</a>&nbsp;&nbsp;<a href="$delete_url">[删除]</a></td>
		</tr>
A;
				echo $html;
			}
			?>
		</table>
		<input style="margin-top: 20px; cursor:pointer;" class="btn" type="submit" name="submit" value="排序" />
	</form>
</div>
<?php include 'inc/footer_inc.php' ?>