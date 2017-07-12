var se_loading=function(target_div_id){
	var that=this;

	this.se_loading_code='<ul id="loading"><li class="color1"></li><li class="color1"></li><li class="color1"></li><li class="color1"></li><li class="color1"></li><li class="color2"></li><li class="color2"></li><li class="color2"></li><li class="color2"></li><li class="color2"></li></ul>';

	document.getElementById(target_div_id).innerHTML=(this.se_loading_code);

	this.show=function(){
		document.getElementById(target_div_id).style.display='block';
    };

	this.hide=function(){
		document.getElementById(target_div_id).style.display='none';
    };

	return this;
};