<?php 
/**
 * 这个页面用于加载别的服务器上的SLK
 */
$instance_list=array(
	"Gateway"=>"http://proxy.slk.leqee.com",
	"ERP"=>"https://ecadmin.leqee.com/admin/filelock/SinriLogKeeper/",
	"ERP Brand"=>"https://erpbrand.leqee.com/logs/SinriLogKeeper/",
	"OMS Manager"=>"http://omsmanager.slk.leqee.com",
	"OMS Server"=>"http://omsserver.slk.leqee.com" ,
	"Romeo"=>"http://romeo.slk.leqee.com" ,
	"ERP Sync"=>"http://erpsync.slk.leqee.com",
	"Common Report"=>"http://commonreport.slk.leqee.com",
	"Finance Report"=>"http://financereport.slk.leqee.com", 
	"OC"=>"http://oc.slk.leqee.com", 
	"BI"=>"http://bi.slk.leqee.com/",
	"WMS Client"=>"http://wmsclient.slk.leqee.com" ,
	"WMS"=>"http://wms.slk.leqee.com",
	"WMS Wai-I"=>"http://wmswai1.slk.leqee.com" ,
	"WMS Wai-II"=>"http://wmswai2.slk.leqee.com",
	"Web CRM"=>"http://webcrm.slk.leqee.com" ,
	"Test for Fundament"=>"http://test.slk.leqee.com",
	"Test for OMS"=>"http://testoms.slk.leqee.com",
	"Test for OMS Manager"=>"http://testomsmanager.slk.leqee.com",
	"Test for Web CRM"=>"http://testwebcrm.slk.leqee.com" ,
	"Test for WMS (I)"=>"http://testwms1.slk.leqee.com" ,
	"Test for WMS (II)"=>"http://testwms2.slk.leqee.com",
);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Gateway - SinriLogKeeper</title>
	<style type="text/css">
		div {
			margin: 2px auto 0 auto;
			padding: 2px;
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
				echo "<li>SinriLogKeeper Instance on <a href='{$InstanceURL}' target='_blank'>{$InstanceName}</a></li>";
			}
			?>
		</ul>
	</div>
	<div id="div_footer">
		Copyright 2017 Sinri Edogawa, under GPLv2
	</div>
</body>
</html>