function loadFiles(){
	// $("#file_select").html("<option value='1'>I</option><option value='2'>II</option>")
	$.ajax({
        url: 'apiv2.php/load_files',
        //url:'api.php?act=load_files',
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
			arise_alert('Request Error. '+obj.data);
		}
	}).fail(function(err){
		console.log(err);
		arise_alert('Ajax Error. See console for more information.');
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
        // url:'api.php?act=search_file',
        url: 'apiv2.php/search_file',
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
			html+="<thead><tr><th>Line</th><th>Content</th></tr></thead><tbody>";
			var count=0;
			for(var line_no in obj.data.list){
				if ({}.hasOwnProperty.call(obj.data.list, line_no)) {
					count+=1;
					var c_c=(count%2===0?'c0':'c1');
					html+="<tr>"
						+"<td class='cc'>"
						+(can_back?line_no:"<a href='javascript:void(0);' onclick='searchOneLineAround("+line_no+")'>"+line_no+"</a>")
						+"</td>"
						+"<td class='"+c_c+"'><pre><code>"+obj.data.list[line_no]+"</code></pre></td>"
						+"</tr>";
				}
			}
			html+="</tbody></table>";
			$("#display_pane").html(html);

			var time_end=new Date().getTime();
			console.log('obj.data.command',obj.data.command);
			var news_html='<span>'
				+'Found '+count+' lines from '+params.filename+' with '+((time_end-time_start)/1000)+"s for ajax. "
				+"</span>"
				+(obj.data.command ? ("<br><span>Command: <code>"+obj.data.command+"</code></span>"):"");
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
			arise_alert('Request Error. '+obj.data);
		}
	}).fail(function(err){
		console.log(err);
		arise_alert('Ajax Error. See console for more information.');
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


function arise_alert(alert_text){
	$("#alert_pane").css("display","block");
	$("#alert_pane p").html(alert_text);
}
function close_alert(){
	$('#alert_pane').css('display','none');
}

// Exp.

function download_this_file(){
	var username=$("#username").val();
	var password=$("#password").val();
	var filename=$("#file_select").val();

    // var url='api.php?act=download&'
    var url = 'apiv2.php/download?'
        + 'username=' + encodeURIComponent(username) + '&password=' + encodeURIComponent(password) + '&filename=' + encodeURIComponent(filename);
	// window.open(url);
	window.location.href=url;
}

function initializePage() {
    $.ajax({
        //url:'api.php?act=initializePage',
        url: 'apiv2.php/initializePage',
        dataType: 'json'
    }).done(function (res) {
        console.log(res);
        if (res.code === 'ok') {
            if (!res.data.useUserAuth) {
                $("#user_auth_row").css('display', 'none');
            } else {
                $("#display_data_footer_use_auth").html(" | UserAuth Protecting ");
            }

            display_data = res.data.display_data;
            $("#display_data_header").html(display_data.header);
            if (display_data.special !== 'LEQEE') {
                $("#motto").css('display', 'block');
            } else {
                $("#ad_div").remove();
            }
            $("#maxResultLineCount").html(display_data.maxResultLineCount);

        }
    })
}
