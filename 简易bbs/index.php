<?php
include_once 'inc/dblink.inc.php';
include_once 'inc/tool.inc.php';
$template['title']='首页';
$member_id=is_login($link);
?>
<?php include 'inc/header.inc.php'?>
    <div id="hot" class="auto">
        <div class="title">引言</div>
        <ul class="newlist">
            <li>东临碣石，以观沧海。水何澹澹，山岛竦峙。树木丛生，百草丰茂。秋风萧瑟，<br/>&nbsp;&nbsp;&nbsp;&nbsp;洪波涌起。日月之行，若出其中；星汉灿烂，若出其里。幸甚至哉，歌以咏志。</li>
            
			
		</ul>
		<div style="clear:both;"></div>
	</div>
	<?php
	$query="select * from father_module order by sort desc";
	$result_father=execute($link,$query);
	while($data_father=mysqli_fetch_assoc($result_father)){
	?>
	<div class="box auto">
	<div class="title">
		<a href="list_father.php?id=<?php echo $data_father['id']?>" style="color:#105cb6;"><?php echo $data_father['module_name']?></a>
	</div>
	<div class="classList">
	<?php
	$query="select * from son_module where father_module_id={$data_father['id']}";
	$result_son=execute($link,$query);
	if(mysqli_num_rows($result_son)){
		while($data_son=mysqli_fetch_assoc($result_son)){
			$query="select count(*) from content where module_id={$data_son['id']} and time > CURDATE()";
				$count_today=num($link,$query);
				$query="select count(*) from content where module_id={$data_son['id']}";
				$count_all=num($link,$query);
			$html=<<<A
			<div class="childBox new">
				<h2><a href="list_son.php?id={$data_son['id']}">{$data_son['module_name']}</a> <span>(今日{$count_today})</span></h2>
				帖子：{$count_all}<br />
			</div>
A;
		echo $html;
		}
	}else{
		echo '<div style="padding:10px 0;">暂无子版块...</div>';
	}
	?>
	<div style="clear:both;"></div>
	</div>
</div>
<?php }?>
    <?php include 'inc/footer.inc.php'?>