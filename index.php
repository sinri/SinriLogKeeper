<?php
require_once('worker.php');
require_once('slk_tool.php');

$display_data=SinriLogKeeperWorker::displayData();

$act=getRequest('act');
if($act=='load_files'){
	try {
		$groups=SinriLogKeeperWorker::getLogFileGroups();
		responseInJson('ok',$groups);	
	} catch (Exception $e) {
		responseInJson('fail',$e->getMessage());	
	}
}elseif($act=='search_file'){
	$username=getRequest('username');
	$password=getRequest('password');
	$auth=SinriLogKeeperWorker::checkUserAuth($username,$password);
	if(!$auth){
		responseInJson('fail','Log Locked.');
	}
	$filename=getRequest('filename','');
	$filter_method=getRequest('filter_method','text');
	$filter=getRequest('filter','');
	$line_begin=getRequest('line_begin',0);
	$line_end=getRequest('line_end',0);
	$around_lines=getRequest('around_lines',0);
	try {
		$is_readable=SinriLogKeeperWorker::checkIsReadableFile($filename);
		if(!$is_readable){
			responseInJson('fail','想搞注入攻击吗，干得好。');
		}
		$list=SinriLogKeeperWorker::filterTargetFile($filename,$filter_method,$filter,$line_begin,$line_end,$around_lines,$command);
		responseInJson('ok',array('list'=>$list,'command'=>$command));
	} catch (Exception $e) {
		responseInJson('fail',$e->getMessage());
	}
}elseif($act=='download'){
	$username=getRequest('username');
	$password=getRequest('password');
	$auth=SinriLogKeeperWorker::checkUserAuth($username,$password);
	if(!$auth){
		responseInJson('fail','Log Locked.');
	}
	$filename=getRequest('filename','');
	responseFileDownload($filename);
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $display_data['title']; ?> - SinriLogKeeper</title>
	<script src="./jquery-2.2.4.min.js"></script>
	<script src="./se_loading/se_loading.js"></script>
	<link rel="stylesheet" type="text/css" href="./se_loading/se_loading.css">
	<script src="./slk.js"></script>
	<link rel="stylesheet" type="text/css" href="./slk.css">
	<script type="text/javascript">
		var se_loading_instance=null;
		var filter_method_mapping={
			pure_grep:{
				title:"Grep",
				readme:"Use Shell GREP command (-n)",
			},
			pure_grep_case_insensitive:{
				title:"Grep Case Insensitive",
				readme:"Use Shell GREP command, case insensitively (-n -i)",
			},
			egrep:{
				title:"EGrep",
				readme:"Use Shell GREP command with extended regular expression support (-n -E)",
			},
			text:{
				title:"Match Text Case Sensitive",
				readme:"Use PHP to filter file content",
			},
			text_case_insensitive:{
				title:"Match Text Case Insensitive",
				readme:"Use PHP to filter file content, case insensitively",
			},
			regex:{
				title:"PHP-Style Regular Expression",
				readme:"Use PHP to filter file content, using regular expression",
			}
		};
		$(document).ready(function(){
			se_loading_instance=se_loading('loading_div');
			loadFiles();
			<?php if(!SinriLogKeeperWorker::checkUseUserAuth()){ ?>
			$("#user_auth_row").css('display','none');
			<?php }?>
			writeFilterMethodSelect();
		})
		function writeFilterMethodSelect(){
			var code="";
			for(var key in filter_method_mapping){
				code+='<option value="'+key+'">'+filter_method_mapping[key]['title']+'</option>';
			}
			$("#filter_method_select").html(code);
			$("#filter_method_select").on('change',refreshFilterMethodReadme);
			refreshFilterMethodReadme();
		}
		function refreshFilterMethodReadme(){
			var key=$("#filter_method_select").val();
			console.log('refreshFilterMethodReadme for '+key)
			$("#filter_method_readme").html(filter_method_mapping[key]['readme']);
		}
	</script>
</head>
<body>
	<h1>
		SinriLogKeeper for <?php echo $display_data['header']; ?>
	</h1>
	<!--
	<blockquote>
		For God shall bring every work into judgment, 
		with every secret thing, 
		whether it be good, 
		or whether it be evil. 
		(Ecclesiastes 12:14 KJV)
	</blockquote>
	-->
	<div id="controller_pane">
		<div class="condition_row" id="user_auth_row">
			<div class='row_label'>Username</div> 
			<input type="text" id="username">
			<div class='row_label'>Password</div> 
			<input type="password" id="password">
		</div>
		<div class="condition_row">
			<div class='row_label'>File</div>
			<select id="file_select">
				<option value='null'>Not Loaded Yet</option>
			</select>
			&nbsp;
			<button class="extension" onclick='download_this_file()'>↓</button>
		</div>
		<div class="condition_row">
			<div class='row_label'>Line Range</div>
			<input type='text' id="line_begin">
			To 
			<input type='text' id="line_end">
			&nbsp;
			<button onclick="rangeTool_firstLines(1000)">Head</button>
			<button onclick="rangeTool_lastLines(1000)">Tail</button>
			<button onclick="rangeTool_prevLines(1000)">Previous</button>
			<button onclick="rangeTool_nextLines(1000)">Next</button>
			For 1000 Lines
		</div>
		<div class="condition_row">
			<div class='row_label'>Around Lines</div>
			<input type="text" id="around_lines" value="10">
			<!--
			Quick Ranger Setter
			<a href="javascript:void(0);" onclick="rangeTool_firstLines(1000)">First 1000 Lines</a>
			<a href="javascript:void(0);" onclick="rangeTool_lastLines(1000)">Last 1000 Lines</a>
			<a href="javascript:void(0);" onclick="rangeTool_prevLines(1000)">Previous 1000 Lines</a>
			<a href="javascript:void(0);" onclick="rangeTool_nextLines(1000)">Next 1000 Lines</a>
			-->
		</div>
		<div class="condition_row">
			<div class='row_label'>Filter Method</div>
			<select id="filter_method_select">
			</select>
			<span id="filter_method_readme"></span>
		</div>
		<div class="condition_row">
			<div class='row_label'>Filter </div>
			<input type="text" id="filter_text">
			&nbsp;
			<button class="primary" onclick="searchWithFilter()">SEARCH</button>
		</div>
		<div class="condition_row">
			With Limitation of Result Line Count of <?php echo SinriLogKeeperWorker::getMaxResultLineCount();?>.
		</div>
	</div>
	<div id="news_pane"></div>
	<div id="display_pane">
	</div>
	<div id="loading_div"></div>
	<div id="footer_div">
		<a href="http://github.everstray.com/SinriLogKeeper/">Version 1.3 For Leqee</a> 
		<?php if(!SinriLogKeeperWorker::checkUseUserAuth()){ 
			// echo ""
		}else{
			echo " | UserAuth Protecting ";
		}?>
		| 
		Copyright 2016 Sinri Edogawa 
		| 
		<a href="https://raw.githubusercontent.com/sinri/SinriLogKeeper/master/LICENSE">License GPLv2</a> 
	</div>
	<!-- slk_wms_product -->
	<!--
	<div style="text-align:center">
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<ins class="adsbygoogle"
			     style="display:inline-block;width:728px;height:90px"
			     data-ad-client="ca-pub-5323203756742073"
			     data-ad-slot="1082241140"></ins>
			<script>
			(adsbygoogle = window.adsbygoogle || []).push({});
		</script>
	</div>
	-->
</body>
</html>
