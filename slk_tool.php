<?php
function getRequest($name,$default=null){
	if(isset($_REQUEST[$name])){
		return $_REQUEST[$name];
	}else{
		return $default;
	}
}
function responseInJson($code,$data){
	echo json_encode(array('code'=>$code,'data'=>$data));
	exit();
}
