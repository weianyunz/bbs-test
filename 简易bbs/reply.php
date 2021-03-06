<?php
include_once 'inc/dblink.inc.php';
include_once 'inc/tool.inc.php';
$template['title']='回复帖子';

$member_id=is_login($link);
if(!$member_id=is_login($link)){
	skip('login.php', 'error', '请登录之后再回复!');
}
if(!isset($_GET['id'])||!is_numeric($_GET['id'])){//is_numeric:检测字符串是否只由数字组成，如果字符串中只包括数字，就返回Ture，否则返回False。
    skip('index.php', 'error', '不存在该帖子');
}

$query="select sc.id,sc.title,sm.name from content as sc,member as sm where sc.id={$_GET['id']} and sc.member_id=sm.id";
$result_content=execute($link,$query);
$data_content=mysqli_fetch_assoc($result_content);
$data_content['title']=htmlspecialchars($data_content['title']);

if(mysqli_num_rows($result_content)!=1){
    skip('index.php', 'error', '您要回复的帖子不存在!');
}

if(isset($_POST['submit'])){
    $_POST=escape($link,$_POST);
    $query="insert into reply(content_id,content,time,member_id) values({$_GET['id']},'{$_POST['content']}',now(),{$member_id})";
    execute($link,$query);
    if(mysqli_affected_rows($link)==1){
        skip("show.php?id={$_GET['id']}",'ok', '回复成功');
    }else{
        skip($_SERVER['REQUEST_URI'], 'error', '回复失败,请重试!');
    }
}


?>
<?php include 'inc/header.inc.php'?>
<div id="position" class="auto">
		 <a href="index.php">首页</a> &gt;回复帖子
	</div>
	<div id="publish">
		<div>回复：由<?php echo $data_content['name'] ?> 发布的：<?php echo $data_content['title'] ?></div>
		<form method="post">
			<textarea name="content" class="content"></textarea>
			<input class="reply" type="submit" name="submit" value="回复" />
			<div style="clear:both;"></div>
		</form>
	</div>

<?php include 'inc/footer.inc.php'?>