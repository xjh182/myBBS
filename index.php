<?php
include 'inc/config_inc.php';
include_once 'inc/mysql_inc.php';
include_once 'inc/too_inc.php';
$title = "首页";
$css = ['style/public.css', 'style/index.css'];

$link = connect();
$member = isLogin($link);

include_once 'inc/header_inc.php';
?>
<div id="hot" class="auto">
	<div class="title">热门动态</div>
	<ul class="newlist">
		<!-- 20条 -->
		<li><a href="#">[库队]</a> <a href="#">私房库实战项目录制中...</a></li>
	</ul>
	<div style="clear:both;"></div>
</div>
<?php
$query_father = "select * from bbs_father_module order by sort desc";
$result_father = execute($link, $query_father);
while ($data_father = mysqli_fetch_assoc($result_father)) {
?>
	<div class="box auto">
		<div class="title">
			<a href="list_father.php?id=<?php echo $data_father["id"] ?>">
				<?php echo $data_father["module_name"] ?>
			</a>
		</div>
		<div class="classList">
			<?php
			$query_son = "select * from bbs_son_module where father_module_id = {$data_father["id"]}";
			$result_son = execute($link, $query_son);
			if(mysqli_num_rows($result_son)){
				while ($data_son = mysqli_fetch_assoc($result_son)){
					$query_content_today = "select count(*) from bbs_content where module_id={$data_son['id']} and time > CURDATE()";
					$count_today =  num($link, $query_content_today);
					$query_content_all = "select count(*) from bbs_content where module_id={$data_son['id']}";
					$count_all =  num($link, $query_content_all);
					?>
					<div class="childBox new">
						<h2><a href="list_son.php?id=<?php echo $data_son['id'] ?>"><?php echo $data_son["module_name"] ?></a> <span>(今日<?php echo $count_today?>)</span></h2>
						帖子: <?php echo $count_all ?><br />
					</div>
				<?php
				}
			}else{
				echo '<div style="padding:10px 0;">暂无子版块...</div>';
			}
			?>
			<div style="clear:both;"></div>
		</div>
	</div>
<?php
}
?>
<div style="clear:both;"></div>
</div>
</div>
<?php
include_once 'inc/footer_inc.php';
?>