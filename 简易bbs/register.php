<?php
include_once 'inc/dblink.inc.php';
include_once 'inc/tool.inc.php';
$template['title']='欢迎注册';
if(is_login($link)){
	skip('index.php','error','你已经登录，请不要重复注册！');
}
if(isset($_POST['submit'])){
	include 'inc/check_register.inc.php';
	$query="insert into member(name,pw,register_time) values('{$_POST['name']}',md5('{$_POST['pw']}'),now())";
	execute($link,$query);
	if(mysqli_affected_rows($link)==1){//mysqli_affected_rows（）函数返回先前的SELECT，INSERT，UPDATE，REPLACE或DELETE查询中受影响的行数
		//设置cookie
		setcookie('bbs[name]',$_POST['name']);
		setcookie('bbs[pw]',sha1(md5($_POST['pw'])));
		skip('index.php','ok','注册成功！');
	}else{
		skip('register.php','error','注册失败,请重试！');
	}
}
?>
<?php include 'inc/header.inc.php'?>
	<div id="register" class="auto">
		<h2>欢迎注册会员</h2>
		<form  method="post">
			<label>用户名：<input type="text" name="name" /></label>
			<label>密码：<input type="password" name="pw"  /></label>
			<label>确认密码：<input type="password" name="confirm_pw"/></label>
			<div style="clear:both;"></div>
			<input class="btn1" name="submit" type="submit" value="确定注册" />
		</form>
	</div>
	<?php include 'inc/footer.inc.php'?>