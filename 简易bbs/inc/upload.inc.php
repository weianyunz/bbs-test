<?php 
function upload($save_path,$custom_upload_max_filesize,$key,$type=array('jpg','jpeg','gif','png','txt')){
	$return_data=array();
	/*
    uniqid — 生成一个唯一ID
	mt_rand — 生成更好的随机数
	*/
	$new_filename=str_replace('.','',uniqid(mt_rand(100000,999999),true));
	if($arr_filename['extension']!=''){
		$new_filename.=".{$arr_filename['extension']}";
	}
	//rtrim — 删除字符串末端的空白字符（或者其他字符）
	$save_path=rtrim($save_path,'/').'/';
	if(!move_uploaded_file($_FILES[$key]['tmp_name'],$save_path.$new_filename)){
		$return_data['error']='临时文件移动失败，请检查权限!';
		$return_data['return']=false;
		return $return_data;
	}
	$return_data['save_path']=$save_path.$new_filename;
	$return_data['filename']=$new_filename;
	$return_data['return']=true;
	return $return_data;
}

header("Content-type:text/html;charset=utf-8");
if(isset($_POST['submit'])){
	$upload=upload('a/b/c','2M','myfile');
}
?>
