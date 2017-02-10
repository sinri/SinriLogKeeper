<?php 
/**
 * 这个页面用于加载别的服务器上的SLK
 */
$instance_list=array(
	"Common Report"=>"commonreport.slk.leqee.com",
	"ERP Sync"=>"erpsync.slk.leqee.com",
	"Finance Report"=>"financereport.slk.leqee.com", 
	"Operate Center"=>"oc.slk.leqee.com", 
	"OMS Manager"=>"omsmanager.slk.leqee.com",
	"OMS Server"=>"omsserver.slk.leqee.com" ,
	"Romeo"=>"romeo.slk.leqee.com" ,
	"Gateway"=>"proxy.slk.leqee.com",
	"Test for Fundament"=>"test.slk.leqee.com",
	"Test for OMS"=>"testoms.slk.leqee.com",
	"Test for OMS Manager"=>"testomsmanager.slk.leqee.com",
	"Test for Web CRM"=>"testwebcrm.slk.leqee.com" ,
	"Test for WMS (I)"=>"testwms1.slk.leqee.com" ,
	"Test for WMS (II)"=>"testwms2.slk.leqee.com",
	"Web CRM"=>"webcrm.slk.leqee.com" ,
	"WMS Client"=>"wmsclient.slk.leqee.com" ,
	"WMS"=>"wms.slk.leqee.com",
	"WMS Wai-I"=>"wmswai1.slk.leqee.com" ,
	"WMS Wai-II"=>"wmswai2.slk.leqee.com",
);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Gateway - SinriLogKeeper</title>
	<style type="text/css">
		div {
			margin: 5px auto 0 auto;
			padding: 5px;
			width: 80%;
		}
		#div_header{
			border-bottom: 2px solid gray;
		}
		#div_project {

		}
		#div_list {

		}
		#div_footer{
			border-top: 2px solid gray;
			text-align: center;
			color: gray;
		}
	</style>
</head>
<body>
	<div id="div_header">
		<h1>Gateway of SinriLogKeeper</h1>
	</div>
	<div id="div_project">
		<h2>Project</h2>
		<p>
			Read introduction of <a href="http://github.everstray.com/SinriLogKeeper/">SinriLogKeeper</a>. 
			View source code on <a href="https://github.com/sinri/SinriLogKeeper">GitHub</a>.
		</p>
	</div>
	<div id="div_list">
		<h2>
			Instance List
		</h2>
		<ul>
			<?php 
			foreach ($instance_list as $InstanceName => $InstanceURL) {
				echo "<li><a href='http://{$InstanceURL}' target='_blank'>{$InstanceName}</a></li>";
			}
			?>
		</ul>
	</div>
	<div id="div_footer">
		Copyright 2017 Sinri Edogawa, under GPLv2
	</div>
</body>
</html>