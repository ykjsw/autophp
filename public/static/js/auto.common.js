(function(win){
	
	var auto = {}
	
	
	auto.reload = function(obj){
		var arr = [];
		for(var i in obj){
			arr.push(i + "=" + encodeURIComponent(obj[i]));
		}
		
		win.location.href = "/auto/?" + arr.join("&");
	}
	
	win.auto = auto;
	
})(window);
