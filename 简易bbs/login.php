<?php
include_once 'inc/dblink.inc.php';
include_once 'inc/tool.inc.php';
if(is_login($link)){
	skip('index.php','error','你已经登录，请不要重复登陆！');
}
if(isset($_POST['submit'])){
	include 'inc/check_login.inc.php';
	if(mysqli_num_rows($result)==1){
		setcookie('bbs[name]',$_POST['name']);
		setcookie('bbs[pw]',sha1(md5($_POST['pw'])));
		skip('index.php','ok','登录成功！');
	}else{
		skip('login.php', 'error', '用户名或密码填写错误！');
	}
}
$template['title']='欢迎登录';
?>
<?php include 'inc/header.inc.php'?>
	<div id="register" class="auto">
		<h2>请登陆</h2>
		<form method="post">
			<label>用户名：<input type="text" name="name" /></label>
			<label>密码：<input type="password" name="pw" /></label>
			
			<div style="clear:both;"></div>
			<input class="btn1" type="submit" name="submit" value="登陆" />
		</form>
	</div>
<?php include 'inc/footer.inc.php'?>