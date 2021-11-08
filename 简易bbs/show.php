<?php 
include_once 'inc/dblink.inc.php';
include_once 'inc/tool.inc.php';
include_once 'inc/page.inc.php';

$query="select * from info where id=1";
$result_info=execute($link, $query);
$data_info=mysqli_fetch_assoc($result_info);
$member_id=is_login($link);

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
	skip('index.php', 'error', '帖子id参数不合法!');
}
$query="select sc.id cid,sc.module_id,sc.title,sc.content,sc.time,sc.member_id,sc.times,sm.name,sm.photo from content as sc,member as sm where sc.id={$_GET['id']} and sc.member_id=sm.id";
$result_content=execute($link,$query);
if(mysqli_num_rows($result_content)!=1){
	skip('index.php', 'error', '本帖子不存在!');
}
//帖子浏览次数增加（浏览一次则+1）

$query="update content set times=times+1 where id={$_GET['id']}";
execute($link,$query);

$data_content=mysqli_fetch_assoc($result_content);

//防止用户输入html标签
/*
htmlspecialchars:传入字符串$html,将$html中包含<>等HTML中预留的特殊字符,转换成字符实体,返回转换后的字符串
nl2br()：字符串中的每个新行（\n）之前插入 HTML 换行符(<br>或<br/>)
*/
$data_content['times']=$data_content['times']+1;
$data_content['title']=htmlspecialchars($data_content['title']);
$data_content['content']=nl2br(htmlspecialchars($data_content['content']));

//获取顶部标题栏名称
$template['title']=$data_content['title'];

//连接子板块
$query="select * from son_module where id={$data_content['module_id']}";
$result_son=execute($link,$query);
$data_son=mysqli_fetch_assoc($result_son);

//连接父板块
$query="select * from father_module where id={$data_son['father_module_id']}";
$result_father=execute($link,$query);
$data_father=mysqli_fetch_assoc($result_father);

//连接会员中心
$query="select * from member where id={$data_content['member_id']}";
$result_member=execute($link,$query);
$data_member=mysqli_fetch_assoc($result_member);

//连接回复，获取回复次数
$query="select count(*) from reply where content_id={$_GET['id']}";
$count_reply=num($link,$query);
?>

<?php include_once 'inc/header.inc.php';?>

<!-- 头部 -->
<div id="position" class="auto">
	 <a href="index.php">首页</a> &gt; <a href="list_father.php?id=<?php echo $data_father['id']?>"><?php echo $data_father['module_name']?></a> &gt; <a href="list_son.php?id=<?php echo $data_son['id']?>"><?php echo $data_son['module_name']?></a> &gt; <?php echo $data_content['title']?>
</div>

<!-- 分页和回复 -->
<div id="main" class="auto">
	<div class="wrap1">
		<div class="pages">
		<?php
		//获取回复数量，回复每超过10个就新增一页
		$query="select count(*) from reply where content_id={$_GET['id']}";
		$count_reply=num($link,$query);
		$page_size=5;
		$page=page($count_reply,5);
		echo $page['html'];
		?>
		</div>
		<a class="btn reply" href="reply.php?id=<?php echo $_GET['id']?>" target="_blank"><span>回复</span></a>
		<div style="clear:both;"></div>
	</div>

	<?php
	if($_GET['page']==1){
	?>
	<!-- 楼主 -->
	<div class="wrapContent">
	<!-- 头像和昵称 -->
		<div class="left">
			<div class="face">
				<!-- 头像 -->
					<img width=110 height=110 src="<?php if($data_content['photo']!=''){echo $data_content['photo'];}else{echo 'style/photo.jpg';}?>" />
				
			</div>
			<div class="name">
				<a href="member.php?id=<?php echo $data_member['id']?>"><?php echo $data_member['name']?></a>
			</div>
		</div>
		<!-- 帖子主体 -->
		<div class="right">
			<div class="title">
				<h2>标题： <?php echo $data_content['title']?></h2>
				<span>阅读：<?php echo $data_content['times']?>&nbsp;|&nbsp;回复：<?php echo $count_reply?></span>
				<div style="clear:both;"></div>
			</div>
			<div class="pubdate">
				<span class="date">发布于：<?php echo $data_content['time']?> </span>
				<span class="floor" style="color:red;font-size:14px;font-weight:bold;">楼主</span>
			</div>
			<div class="content">
				 <?php echo $data_content['content']?>
			</div>
		</div>
		<div style="clear:both;"></div>
	</div>
	<?php }?>
	
	<?php
	$query="select sm.name,sr.quote_id,sr.member_id,sm.photo,sr.time,sr.id,sr.content from reply sr,member sm where sr.member_id=sm.id and sr.content_id={$_GET['id']} order by id asc {$page['limit']}";
	$result_reply=execute($link,$query);
	$i=($_GET['page']-1)*$page_size+1;

	while($data_reply=mysqli_fetch_assoc($result_reply)){
		$data_reply['content']=nl2br(htmlspecialchars($data_reply['content']));
		//连接会员中心
    $query="select * from member where id={$data_reply['member_id']}";
    $result_member=execute($link,$query);
    $data_member=mysqli_fetch_assoc($result_member);
	?>

	<div class="wrapContent">
		<!-- 评论者头像和昵称 -->
		<div class="left">
			<div class="face">
				<img width=110 height=110 src="<?php if($data_reply['photo']!=''){echo $data_reply['photo'];}else{echo 'style/photo.jpg';}?>" />
			</div>
			<div class="name">
				<a class="J_user_card_show mr5" data-uid="2374101" href="member.php?id=<?php echo $data_member['id']?>"><?php echo $data_reply['name']?></a>
			</div>
		</div>
		<!-- 评论主体 -->
		<div class="right">
			<div class="pubdate">
				<span class="date">回复时间：<?php echo $data_reply['time']?></span>
				<span class="floor"><?php echo $i++?>楼&nbsp;|&nbsp;<a href="quote.php?id=<?php echo $_GET['id']?>&reply_id=<?php echo $data_reply['id']?>">引用</a></span>
			</div>
			<div class="content">
			<?php
			if($data_reply['quote_id']){
			//引用楼层			
			$query="select count(*) from reply where content_id={$_GET['id']} and id<={$data_reply['quote_id']}";
			$floor=num($link,$query);
			//引用的评论者昵称和内容
			$query="select reply.content,member.name from reply,member where reply.id={$data_reply['quote_id']} and reply.content_id={$_GET['id']} and reply.member_id=member.id";
			$result_quote=execute($link,$query);
			$data_quote=mysqli_fetch_assoc($result_quote);
			?>
			 <div class="quote">
			 <h2>引用<?php echo $floor?>楼 <?php echo $data_quote['name']?>发表的 </h2>
			 <?php echo nl2br(htmlspecialchars($data_quote['content']))?>
			 </div>
			 <?php
				}	
			 ?>
			<?php
			echo $data_reply['content'];
			?>
				</div>
		</div>
		<div style="clear:both;"></div>
	</div>
	<?php 
	}
	?>
	<div class="wrap1">
		<div class="pages">
		<?php
		echo $page['html'];
		?>
		</div>
        <a class="btn reply" href="reply.php?id=<?php echo $_GET['id']?>" target="_blank"><span>回复</span></a>
		<div style="clear:both;"></div>
	</div>
</div>
<?php include 'inc/footer.inc.php'?>
?>