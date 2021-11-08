<?php 
/*
调用：$page=page(100,10,9);
返回值：array('limit','html')
 参数说明：
$count：总记录数
$page_size：每页显示的记录数
$num_btn：要展示的页码按钮数目
$page：分页的get参数
*/
function page($count,$page_size,$num_btn=10,$page='page'){
	if(!isset($_GET[$page]) || !is_numeric($_GET[$page]) || $_GET[$page]<1){
		$_GET[$page]=1;
	}
	if($count==0){//没有记录数直接返回空的字符串
		$data=array(
			'limit'=>'',
			'html'=>''
	);
	return $data;
	}
	//总页数:$page_num_all
	$page_num_all=ceil($count/$page_size);
	if($_GET[$page]>$page_num_all){
		$_GET[$page]=$page_num_all;
	}
	$start=($_GET[$page]-1)*$page_size;
	$limit="limit {$start},{$page_size}";

	$current_url=$_SERVER['REQUEST_URI'];//获取当前url地址
	$arr_current=parse_url($current_url);//将当前url拆分到数组里面
	$current_path=$arr_current['path'];//将文件路径部分保存起来
	$url='';
	if(isset($arr_current['query'])){
		parse_str($arr_current['query'],$arr_query);
		unset($arr_query[$page]);  //unset() 销毁指定的变量
		if(empty($arr_query)){
			$url="{$current_path}?{$page}=";
		}else{
			$other=http_build_query($arr_query); // http_build_query:使用给出的关联（或下标）数组生成一个经过 URL-encode 的请求字符串。 
			$url="{$current_path}?{$other}&{$page}=";
		}
	}else{
		$url="{$current_path}?{$page}=";
	}
	$html=array();
	if($num_btn>=$page_num_all){
		//把所有的页码按钮全部显示
		for($i=1;$i<=$page_num_all;$i++){//这边的$page_num_all是限制循环次数以控制显示按钮数目的变量,$i是记录页码号
			if($_GET[$page]==$i){
				$html[$i]="<span>{$i}</span>";
			}else{
				$html[$i]="<a href='{$url}{$i}'>{$i}</a>";
			}
		}
	}else{
		$num_left=floor(($num_btn-1)/2); //floor:返回不大于 value 的最接近的整数，舍去小数部分取整
		$start=$_GET[$page]-$num_left;
		$end=$start+($num_btn-1);
		if($start<1){
			$start=1;
		}
		if($end>$page_num_all){
			$start=$page_num_all-($num_btn-1);
		}
		for($i=0;$i<$num_btn;$i++){
			if($_GET[$page]==$start){
				$html[$start]="<span>{$start}</span>";
			}else{
				$html[$start]="<a href='{$url}{$start}'>{$start}</a>";
			}
			$start++;
		}
		//如果按钮数目大于等于3的时候做省略号效果
		if(count($html)>=3){
			reset($html); //reset — 将数组的内部指针指向第一个单元
			$key_first=key($html);//key — 从关联数组中取得键名
			end($html);  //end — 将数组的内部指针指向最后一个单元 
			$key_end=key($html);
			if($key_first!=1){
				array_shift($html); //array_shift — 将数组开头的单元移出数组 
				//array_unshift — 在数组开头插入一个或多个单元 
				array_unshift($html,"<a href='{$url}=1'>1...</a>");
			}
			if($key_end!=$page_num_all){
				array_pop($html); //array_pop — 弹出数组最后一个单元（出栈）
				//array_push — 将一个或多个单元压入数组的末尾（入栈） 
				array_push($html,"<a href='{$url}={$page_num_all}'>...{$page_num_all}</a>");
			}
		}
	}
    
    /*上一页、下一页*/
	if($_GET[$page]!=1){
		$prev=$_GET[$page]-1;
		array_unshift($html,"<a href='{$url}{$prev}'>« 上一页</a>");
	}
	if($_GET[$page]!=$page_num_all){
		$next=$_GET[$page]+1;
		array_push($html,"<a href='{$url}{$next}'>下一页 »</a>");
	}
	$html=implode(' ',$html); //implode — 将一个一维数组的值转化为字符串
	$data=array(
			'limit'=>$limit,
			'html'=>$html
	);
	return $data;
}
?>