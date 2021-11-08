<?php
date_default_timezone_set('Asia/Shanghai');//设置默认时区
session_start(); //session_start — 启动新会话或者重用现有会话
define('SA_PATH',dirname(dirname(__FILE__))); //dirname — 返回路径中的目录部分
define('SUB_URL',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('\\','/',SA_PATH)).'/'); //str_replace — 子字符串替换

$dbHost="localhost";
$dbUser="root";
$dbPass="123456";
$dbName="bbs2";

$link=@mysqli_connect($host, $user, $password, $database);
if(!$link=mysqli_connect($dbHost,$dbUser,$dbPass,$dbName)){
	exit(mysqli_connect_error());
}
mysqli_set_charset($link,"utf-8");


//执行一条SQL语句,返回结果
function execute($link,$query){
	$result=mysqli_query($link,$query);
	if(mysqli_errno($link)){
		exit(mysqli_error($link));
	}
	return $result;
}

//执行一条SQL语句，只返回布尔值
function execute_bool($link,$query){
	$bool=mysqli_real_query($link,$query);
	if(mysqli_errno($link)){
		exit(mysqli_error($link));
	}
	return $bool;
}

//获取记录数
function num($link,$sql_count){
	$result=execute($link,$sql_count);
	$count=mysqli_fetch_row($result);
	return $count[0];
}

//数据入库之前进行转义
function escape($link,$data){
	if(is_string($data)){
		return mysqli_real_escape_string($link,$data);
	}
	if(is_array($data)){
		foreach ($data as $key=>$val){
			$data[$key]=escape($link,$val);
		}
	}
	return $data;
}


//关闭与数据库的连接
function close($link){
	mysqli_close($link);
}
?>