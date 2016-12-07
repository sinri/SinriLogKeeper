<?php
require_once('worker.php');
require_once('slk_tool.php');

$SLK_Worker=new SinriLogKeeperWorker();

$act=getRequest('act');
if($act==='load_files'){
	try {
		$groups=$SLK_Worker->getLogFileGroups();
		responseInJson('ok',$groups);	
	} catch (Exception $e) {
		responseInJson('fail',$e->getMessage());	
	}
}elseif($act==='search_file'){
	try {
		$username=getRequest('username');
		$password=getRequest('password');
		$auth=$SLK_Worker->checkUserAuth($username,$password);
		if(!$auth){
			throw new Exception("Log Locked.", 1);			
		}
		$filename=getRequest('filename','');
		$filter_method=getRequest('filter_method','text');
		$filter=getRequest('filter','');
		$line_begin=getRequest('line_begin',0);
		$line_end=getRequest('line_end',0);
		$around_lines=getRequest('around_lines',0);
	
		$is_readable=$SLK_Worker->checkIsReadableFile($filename);
		if(!$is_readable){
			throw new Exception("想搞注入攻击吗，干得好。", 1);
		}
		$list=$SLK_Worker->filterTargetFile($filename,$filter_method,$filter,$line_begin,$line_end,$around_lines,$command);
		responseInJson('ok',array('list'=>$list,'command'=>$command));
	} catch (Exception $e) {
		responseInJson('fail',$e->getMessage());
	}
}elseif($act==='download'){
	try {
		$username=getRequest('username');
		$password=getRequest('password');
		$auth=$SLK_Worker->checkUserAuth($username,$password);
		if(!$auth){
			throw new Exception("Log Locked.", 1);			
		}
		$filename=getRequest('filename','');
		responseFileDownload($filename);
	} catch (Exception $e) {
		responseInJson('fail',$e->getMessage());
	}
	
}