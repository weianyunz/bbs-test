<?php
include_once 'inc/dblink.inc.php';
include_once 'inc/tool.inc.php';
setcookie('bbs[name]','',time()-3600);
setcookie('bbs[pw]','',time()-3600);
skip('index.php','ok','退出成功！');
?>