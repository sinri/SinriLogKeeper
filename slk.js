function loadFiles(){
	// $("#file_select").html("<option value='1'>I</option><option value='2'>II</option>")
	$.ajax({
		url:'index.php?act=load_files',
		dataType:'json'
	}).done(function(obj){
		if(obj.code==='ok'){
			console.log(obj.data);
			var html="";
			for(var group in obj.data){
				if ({}.hasOwnProperty.call(obj.data, group)) {
					html+="<optgroup label='"+group+"'>";
					for(var i=0;i<obj.data[group].length;i++){
						var file=obj.data[group][i];
						html+="<option value='"+file+"'>"+file+"</option>";
					}
					html+="</optgroup>";
				}
			}
			$("#file_select").html(html);
			$("#news_pane").html('Files loaded.')
		}else{
			alert('Request Error. '+obj.data);
		}
	}).fail(function(err){
		console.log(err);
		alert('Ajax Error. See console for more information.');
	}).always(function(){
		se_loading_instance.hide();
	})
}
function searchWithFilter(){
	var username=$("#username").val();
	var password=$("#password").val();
	var filename=$("#file_select").val();
	var filter_method=$("#filter_method_select").val();
	var filter_text=$("#filter_text").val();
	var line_begin=$("#line_begin").val();
	var line_end=$("#line_end").val();
	var around_lines=$("#around_lines").val();

	if(!around_lines){around_lines=0;}

	var params={
		username:username,
		password:password,
		filename:filename,
		filter_method:filter_method,
		filter:filter_text,
		line_begin:line_begin,
		line_end:line_end,
		around_lines:around_lines
	};

	searchWithFilterParmas(params,false);	
}

function searchOneLineAround(center_line,around){
	if(!around){
		around=parseInt($("#around_lines").val(),10);
	}
	var username=$("#username").val();
	var password=$("#password").val();
	var filename=$("#file_select").val();
	var filter_method=$("#filter_method_select").val();
	var filter_text="";//$("#filter_text").val();
	var line_begin=center_line*1-around*1; //$("#line_begin").val();
	var line_end=center_line*1+around*1; //$("#line_end").val();

	var params={
		username:username,
		password:password,
		filename:filename,
		filter_method:filter_method,
		filter:filter_text,
		line_begin:line_begin,
		line_end:line_end
	};

	searchWithFilterParmas(params,true);	
}

function searchWithFilterParmas(params,can_back){
	var time_start=new Date().getTime();
	se_loading_instance.show();
	$.ajax({
		url:'index.php?act=search_file',
		method:'POST',
		data:params,
		dataType:'json'
	}).done(function(obj){
		if(obj.code==='ok'){
			if(can_back){
				var old_display_html=$("#display_pane").html();
				var old_news_html=$("#news_pane").html();
			}
			// console.log(obj.data);
			var html='<table id="src_table">';
			html+="<thead><tr><th>Line</th><th>Content</th></tr></thead>";
			html+="<tbody>";
			var count=0;
			for(var line_no in obj.data.list){
				if ({}.hasOwnProperty.call(obj.data.list, line_no)) {
					count+=1;
					var c_c=(count%2===0?'c0':'c1');
					html+="<tr>";
					html+="<td class='cc'>";
					// html+="<pre><code>";
					if(!can_back){
						html+="<a href='javascript:void(0);' onclick='searchOneLineAround("+line_no+")'>";
					}
					html+=line_no;
					if(!can_back){
						html+="</a>";
					}
					// html+="</pre></code>";
					html+="</td>";
					html+="<td class='"+c_c+"'><pre><code>"+obj.data.list[line_no]+"</code></pre></td>";
					html+="</tr>";
				}
			}
			html+="</tbody>";
			html+='</table>';
			$("#display_pane").html(html);

			var time_end=new Date().getTime();

			var news_html='<span>';
			news_html+='Found '+count+' lines from '+params.filename+' with '+((time_end-time_start)/1000)+"s for ajax. ";
			news_html+="</span>";
			if(obj.data.command){
				news_html+="<span>Command: <code>"+obj.data.command+"</code></span>";
			}
			if(can_back){
				news_html+=" <button id='back_to_prev_table_btn'>BACK TO PREVIOUS RESULT</button>";
			}
			$("#news_pane").html(news_html);

			if(can_back){
				$("#back_to_prev_table_btn").on('click',function(){
					$("#display_pane").html(old_display_html);
					$("#news_pane").html(old_news_html);
				})
			}
		}else{
			alert('Request Error. '+obj.data);
		}
	}).fail(function(err){
		console.log(err);
		alert('Ajax Error. See console for more information.');
	}).always(function(){
		se_loading_instance.hide();
	})
}

function rangeTool_firstLines(n){
	$('#line_begin').val('');$('#line_end').val(n);
}
function rangeTool_lastLines(n){
	$('#line_begin').val(0-n);$('#line_end').val('');
}
function rangeTool_prevLines(n){
	$('#line_end').val($('#line_begin').val());$('#line_begin').val($('#line_begin').val()-n);
}
function rangeTool_nextLines(n){
	$('#line_begin').val($('#line_end').val());$('#line_end').val($('#line_end').val()*1+n);
}

// Exp.

function download_this_file(){
	var username=$("#username").val();
	var password=$("#password").val();
	var filename=$("#file_select").val();

	var url='index.php?act=download&username='+encodeURIComponent(username)+'&password='+encodeURIComponent(password)+'&filename='+encodeURIComponent(filename);
	// window.open(url);
	window.location.href=url;
	/*
	$.ajax({
		url:'index.php?act=download',
		method:'post',
		data:{
			username:username,
			password:password,
			filename:filename
		}
	}).done(function(obj){
		console.log('ok');
	}).fail(function(){
		console.log('fail');
	})
	*/
}
