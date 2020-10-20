<?php
include_once '../inc/config_inc.php';
include_once '../inc/mysql_inc.php';
include_once '../inc/too_inc.php';
$title = '子版块列表';
$css = ["style/public.css"];
$link = connect();
if(isset($_POST['submit'])){
	foreach($_POST['sort'] as $key=>$val){
		if(!is_numeric($key) || !is_numeric($val)){
			skip('排序参数错误','error','son_module.php');
		}
		$query[] = "update bbs_son_module set sort={$val} where id={$key}";
	}
	if(execute_multi($link,$query,$error)){
		skip('排序修改成功','ok','son_module.php');
	}else{
		skip('排序修改失败','error','son_module.php');
	}
}
?>
<?php include 'inc/header_inc.php' ?>
<div id="main">
	<div class="title">功能说明</div>
	<form method="post">
		<table class="list">
			<tr>
				<th>排序</th>
				<th>版块名称</th>
				<th>所属的父板块</th>
				<th>版主</th>
				<th>操作</th>
			</tr>
			<?php
			$query = "select bsm.id,bsm.sort,bsm.module_name module_name,bfm.module_name father_module_name,bsm.member_id from bbs_son_module bsm,bbs_father_module bfm where bsm.father_module_id=bfm.id order by bfm.id";
			$result = execute($link, $query);
			while ($data = mysqli_fetch_assoc($result)) {
				$url = urlencode("son_module_delete.php?id={$data['id']}"); //编码，传值($_GET已经被解码)
				$return_url = urlencode($_SERVER['REQUEST_URI']);
				$message = "你真的要删除子板块 {$data['module_name']} 吗？";
				$delete_url = "confirm.php?url={$url}&return_url={$return_url}&message={$message}";
				$html = <<<A
		<tr>
			<td><input class="sort" type="text" name="sort[{$data['sort']}]" value="{$data['sort']}" /></td>
            <td>{$data['module_name']}[id:{$data['id']}]</td>
            <td>{$data['father_module_name']}</td>
            <td>{$data['member_id']}</td>
			<td><a href="#">[访问]</a>&nbsp;&nbsp;<a href="son_module_update.php?id={$data['id']}">[编辑]</a>&nbsp;&nbsp;<a href="$delete_url">[删除]</a></td>
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