(function(auto){
	
	$(".auto-field-value").change(function(){
		$(this).attr("data-changed", "true");
	});
	
	$("select.auto-group").change(function(){
		var field = $(this).data("field");
		var queryfield = "t_" + field;
		
		if(this.value){
			AUTO_QUERY[queryfield] = this.value;
		}else{
			delete AUTO_QUERY[queryfield];
		}
		
		auto.reload(AUTO_QUERY);
		
		return false;
	});
	
	$("#select-all").click(function(){
		if($(this).is(":checked")){
			$(".select-row").prop("checked", true);
		}else{
			$(".select-row").prop("checked", false);
		}
	});
	
	$("#auto-btn-add").click(function(){
		
		$("#new-row").show();
		$("#data-rows").hide();
		
		return false;
	});
	
	$("#new-row-add").click(function(){
		
		var obj = {}
		$("#new-row .auto-field-value").each(function(idx, ele){
			var $this = $(ele);
			var field = $this.data("field");
			
			if(field){
				if($this.hasClass("auto-richtext")){
					obj[field] = editors[field+"_0"].html();
				}else{
					obj[field] = this.value;
				}
			}
		});
		
		$("select.auto-group").each(function(idx, ele){
			var $this = $(ele);
			var field = $this.data("field");
			
			if(field){
				obj[field] = this.value;
			}
		});
		
		$.ajax({
			url: "/auto/create",
			dataType: "json",
			type: "post",
			data: {
				config_id: AUTO_QUERY.id,
				data: JSON.stringify(obj)
			},
			success: function(json){
				if(json.ret == 0){
					window.location.reload();
				}else{
					alert(json.msg);
				}
			},
			error: function(){
				
			}
		});
		
		return false;
	});
	
	$("#new-row-cancel").click(function(){
		$("#new-row").hide();
		$("#data-rows").show();
		$('#new-row form')[0].reset();
		
		return false;
	});
	
	/*
	 * 1: {field: sdfsdf}
	 */
	var getRowUpdateData = function(row_id){
		$doms = $("*[data-row-id="+row_id+"][data-changed=true]");
		
		if($doms.length > 0){
			
			var obj = {};
			
			$doms.each(function(idx, ele){
				var field = $(this).data("field");
				//多选框
				if($(ele).attr("type") === "checkbox"){
					if(!obj[field]){
						obj[field] = [];
					}
					
					if($(ele).is(":checked")){
						obj[field].push(ele.value);
					}
				}else{
					obj[field] = ele.value;
				}
			});
			
			return obj;
		}
		
		return false;
	}
	
	$("#auto-btn-update").click(function(){
		
		var rows = [];
		
		$(".select-row:checked").each(function(idx, ele){
			var fvals = getRowUpdateData(ele.value);
			if(fvals){
				rows.push({
					row_id: ele.value,
					row_data: fvals
				});
			}
		});
		
		if(rows.length > 0){
			$.ajax({
				url: "/auto/update",
				dataType: "json",
				type: "post",
				data: {
					config_id: AUTO_QUERY.id,
					data: JSON.stringify(rows)
				},
				success: function(json){
					if(json.ret == 0){
						window.location.reload();
					}else{
						alert(json.msg);
					}
				},
				error: function(){
					
				}
			});
		}else{
			layer.msg('无修改');
		}
		
		return false;
	});
	
	$("#auto-btn-search").click(function(){
		layer.msg('不给搜索');
		return false;
	});
	
	$("#auto-btn-delete").click(function(){
		
		var rows = [];
		
		$(".select-row:checked").each(function(idx, ele){
			rows.push(ele.value);
		});
		
		if(rows.length > 0){
			$.ajax({
				url: "/auto/delete",
				dataType: "json",
				type: "post",
				data: {
					config_id: AUTO_QUERY.id,
					data: JSON.stringify(rows)
				},
				success: function(json){
					if(json.ret == 0){
						window.location.reload();
					}else{
						alert(json.msg);
					}
				},
				error: function(){
					
				}
			});
		}
		
		return false;
	});
	
	var editors = {};
	if($(".auto-richtext").length > 0){
		KindEditor.ready(function(K) {
			$(".auto-richtext").each(function(idx, ele){
				var field = $(ele).data("field");
				field += "_"+($(ele).data("row-id") ? $(ele).data("row-id") : "0");
				editors[field] = K.create(ele, {
					resizeType : 1,
					allowPreviewEmoticons : false,
					allowImageUpload : false,
					items : [
						'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
						'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
						'insertunorderedlist', '|', 'emoticons', 'image', 'link']
				});
			});
		});
	}
	
	if($(".auto-date").length > 0 || $(".auto-datetime").length > 0){
		$(".auto-date").datetimepicker({
			minView: 2,
			todayBtn: false,
			todayHighlight: true,
			autoclose: true,
			language: "zh-CN",
			format: "yyyy-mm-dd"
		});
		
		$(".auto-datetime").datetimepicker({
			minView: 0,
			todayBtn: false,
			todayHighlight: true,
			autoclose: true,
			language: "zh-CN",
			format: "yyyy-mm-dd hh:ii:ss"
		});
	}
		
	if($(".auto-timestamp").length > 0){
		$(".auto-timestamp").datepicker({
			minView: 0,
			todayBtn: false,
			todayHighlight: true,
			autoclose: true,
			language: "zh-CN",
			format: {
				toDisplay: function(date, format, language){
					var dt = new Date(date);
					return Math.round(dt.getTime() / 1000);
				},
				toValue: function(date, format, language){
					if(date){
						var dt = new Date(date * 1000);
						return dt;
					}
				}
			}
		});
	}
	
	if($(".auto-json").length > 0){
		$(".auto-json").each(function(idx, ele){
			if(ele.value){
				console.log(ele.value);
				var json = ele.value.replace(/</g, '&lt;');
				json = json.replace(/>/g, '&gt;');
				var result = parse(json);
				console.log(result);
				
				$(ele).next("div").html(result.html);
			}
			
			ele.onchange = function(){
				var json = this.value.replace(/</g, '&lt;');
				json = json.replace(/>/g, '&gt;');
				var result = parse(json);
				console.log(result);
				
				$(ele).next("div").html(result.html);
			};
		});
	}
	
	//文件上传
	auto.uploaded = function(json){
		if(json.ret == 0){
			if(json.data.req.field && json.data.req.row_id && json.data.url){
				
				if(json.data.req.act_id == 1){
					if(json.data.is_image == 1){
						$("img[data-field="+json.data.req.field+"][data-row-id="+json.data.req.row_id+"]").attr("src", json.data.url);
						$("input.auto-field-value[data-field="+json.data.req.field+"][data-row-id="+json.data.req.row_id+"]").val(json.data.url).attr("data-changed", "true");
					}
				}else if(json.data.req.act_id == 2){
					$("textarea.auto-field-value[data-field="+json.data.req.field+"][data-row-id="+json.data.req.row_id+"]").val(json.data.type+":"+json.data.path).attr("data-changed", "true");
				}
				
			}
		}else{
			layer.msg(json.msg);
		}
	}
})(auto);