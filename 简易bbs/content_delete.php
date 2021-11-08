<?php
include_once 'inc/dblink.inc.php';
include_once 'inc/tool.inc.php';
$template['title']='首页';

$member_id=is_login($link);
if(!$member_id){
	skip('login.php', 'error', '未登陆!');
}
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
	skip('index.php', 'error', ' 帖子id参数不合法!');
}

$query="select member_id from content where id={$_GET['id']}";
$result_content=execute($link,$query);
if(mysqli_num_rows($result_content)==1){
    $data_content=mysqli_fetch_assoc($result_content);
    if($member_id=is_login($link)){
        $query="delete from content where id={$_GET['id']}";
        execute($link,$query);
        if(isset($_GET['return_url'])){
			$return_url=$_GET['return_url'];
		}else{
			$return_url="member.php?id={$member_id}";
		}
		if(mysqli_affected_rows($link)==1){
			skip($return_url, 'ok', '恭喜你，删除成功!');
		}else{
			skip($return_url, 'error', '对不起删除失败!');
		}
    }else{
        skip('index.php', 'error', '该帖子不存在!');
    }
}else{
    skip('index.php', 'error', '该帖子不存在!');
}
?>