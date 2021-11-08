<?php
include_once 'inc/dblink.inc.php';
include_once 'inc/tool.inc.php';
include_once 'inc/page.inc.php';
$template['title']='用户中心';

$member_id=is_login($link);
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
	skip('index.php', 'error', '用户id参数不合法!');
}

$query="select * from member where id={$_GET['id']}";
$result_member=execute($link,$query);
if(mysqli_num_rows($result_member)!=1){
    skip('index.php', 'error', '不存在该用户');
}
$data_member=mysqli_fetch_assoc($result_member);

//获取会员所发帖子数量
$query="select count(*) from content where member_id={$_GET['id']}";
$count_all=num($link,$query);

?>

<?php include 'inc/header.inc.php'?>

	<!-- 头部 -->
	<div id="position" class="auto">
		<a href="index.php">首页</a> &gt; <?php echo $data_member['name']?>
	</div>

	<div id="main" class="auto">
		<div id="left">
			<!-- 左侧帖子列表 -->
			<ul class="postsList">
            <?php
            $page=page($count_all,10);
			$query="select
			content.title,content.id,content.time,content.member_id,content.times,member.name,member.photo,member.sign
			from content,member where
			content.member_id={$_GET['id']} and
			content.member_id=member.id order by id desc {$page['limit']}";
			$result_content=execute($link, $query);
			while($data_content=mysqli_fetch_assoc($result_content)){
				$data_content['title']=htmlspecialchars($data_content['title']);
				//获取帖子最后回复时间
				$query="select time from reply where content_id={$data_content['id']} order by id desc limit 1";
				$result_last_reply=execute($link, $query);
				if(mysqli_num_rows($result_last_reply)==0){
					$last_time='暂无';
				}else{
					$data_last_reply=mysqli_fetch_assoc($result_last_reply);
					$last_time=$data_last_reply['time'];
				}
				//获取帖子回复数
				$query="select count(*) from reply where content_id={$data_content['id']}";
				$count_reply=num($link,$query);
			?>
				<li>
					<!-- 发帖人头像 -->
					<div class="smallPic">
							<img width="45" height="45" src="<?php if($data_member['photo']!=''){echo $data_member['photo'];}else{echo 'style/photo.jpg';}?>" />
					</div>
					<!-- 帖子列表 -->
					<div class="subject">
                    <div class="titleWrap"><h2><a target="_blank" href="show.php?id=<?php echo $data_content['id']?>"><?php echo $data_content['title']?></a></h2></div>
					<p>
                    <?php
                    if($member_id==$data_content['member_id']){//urlencode — 编码 URL 字符串
                        $return_url=urlencode($_SERVER['REQUEST_URI']);
                        $url=urlencode("content_delete.php?id={$data_content['id']}");
                        $message="你真的要删除帖子 {$data_content['title']} 吗？";
                        $delete_url="confirm.php?url={$url}&return_url={$return_url}&message={$message}";
                        echo "<a href='content_update.php?id={$data_content['id']}'>编辑  <a href='{$delete_url}'>删除</a>";
                    }
                    ?>
						发帖日期：&nbsp;<?php echo $data_content['time']?>&nbsp;&nbsp;&nbsp;&nbsp;最后回复：<?php echo $last_time?>
					</p>
					</div>
					<!-- 帖子回复总数和浏览总数 -->
					<div class="count">
					<p>
						回复<br /><span><?php echo $count_reply?></span>
					</p>
					<p>
						浏览<br /><span><?php echo $data_content['times']?></span>
					</p>
					</div>
					<div style="clear:both;"></div>
				</li>
                <?php
                }
                ?>
			</ul>
			
				<div class="pages_wrap">
					<div class="pages">
					<?php 
					echo $page['html'];
					?>
					</div>
					<div style="clear:both;"></div>
				</div>
		</div>

		<!-- 右侧个人信息 -->
		<div id="right">
			<!-- 大头像 -->
			<div class="member_big">
				<dl>
					<dt>	
					<a target="_blank" href="member_photo_update.php?id=<?php echo $data_member['id']?>">	
					
						<img width="180" height="180" src="<?php if($data_member['photo']!=''){echo $data_member['photo'];}else{echo 'style/photo.jpg';}?>" />
					</a>
					</dt>
					<dd class="name"><?php echo $data_member['name']?></dd>
					<dd>帖子总计:<?php echo $count_all?></dd>
					<?php
					if($member_id==$data_member['id']){
					?>
					
					<dd><a  href="sign.php" >个性签名： </a><br/><?php echo $data_member['sign']?></dd>
					<?php }?>
				</dl>
				<div style="clear:both;"></div>
			</div>
		</div>
		<div style="clear:both;"></div>
	</div>
</body>
</html>
<?php include 'inc/footer.inc.php'?>