<?php
if(empty($_POST['name'])){
    skip('register.php','error','用户名不得为空');
}
if(mb_strlen($_POST['name'])>32){//mb_strlen — 获取字符串的长度
    skip('register.php','error','用户名不能超过32个字符');
}
if(mb_strlen($_POST['pw'])<6){
    skip('register.php','error','密码不得小于六位');
}
if($_POST['pw']!=$_POST['confirm_pw']){
    skip('register.php','error','密码不一致');
}

//进行转义
$_POST=escape($link,$_POST);
$query="select * from member where name='{$_POST['name']}'";
$result=execute($link,$query);
if(mysqli_num_rows($result)){
    skip('register.php', 'error', '用户名已注册！');
}
?>