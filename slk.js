function loadFiles(){
	// $("#file_select").html("<option value='1'>I</option><option value='2'>II</option>")
	$.ajax({
		url:'index.php?act=load_files',
		dataType:'json'
	}).done(function(obj){
		if(obj.code=='ok'){
			console.log(obj.data);
			var html="";
			for(var group in obj.data){
				html+="<optgroup label='"+group+"'>";
				for(var i=0;i<obj.data[group].length;i++){
					var file=obj.data[group][i];
					html+="<option value='"+file+"'>"+file+"</option>";
				}
				html+="</optgroup>";
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
	// console.log(file+" ~ "+filter_text);
	se_loading_instance.show();

	var time_start=new Date().getTime();

	$.ajax({
		url:'index.php?act=search_file',
		method:'POST',
		data: {
			username:username,
			password:password,
			filename:filename,
			filter_method:filter_method,
			filter:filter_text,
			line_begin:line_begin,
			line_end:line_end
		},
		dataType:'json'
	}).done(function(obj){
		if(obj.code=='ok'){
			// console.log(obj.data);
			var html='<table id="src_table">';
			html+="<thead><tr><th>Line</th><th>Content</th></tr></thead>";
			html+="<tbody>";
			var count=0;
			for(var line_no in obj.data){
				count+=1;
				var c_c=(count%2==0?'c0':'c1');
				html+="<tr>";
				html+="<td class='cc'><pre><code>"+line_no+"</pre></code></td>";
				html+="<td class='"+c_c+"'><pre><code>"+obj.data[line_no]+"</code></pre></td>";
				html+="</tr>";
			}
			html+="</tbody>";
			html+='</table>';
			$("#display_pane").html(html);

			var time_end=new Date().getTime();

			$("#news_pane").html('Found '+count+' lines from '+filename+' with '+((time_end-time_start)/1000)+"s for ajax");
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
