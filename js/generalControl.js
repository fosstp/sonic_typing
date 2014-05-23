var html_loading = "<div class='load_img'></div>";
var uploading = false;

var autoSoundFilepathEncode = function(word){
	var decoded = word.replace(/&nbsp;/g, " ");
	decoded = $("<div/>").html(decoded).text();
	
	return "./audio/"+ encodeURIComponent(decoded) +".mp3";
};

var stopUpload = function(result){
	if(result == 'upload_error'){

	}
	else{
		$('.update').html("<div class='sound_button' data-sound='"+ result +"'></div><div class='upload_button' data-file='" + result + "'></div>");
		$('.update').removeClass('update');
		$('.dialog-upload').dialog("close");
	}
};

var ContentMaker = function($list, data, afterwho, classname, valchange){
	var dataEnterable = ($list.attr("data-enterable") != undefined)? parseInt($list.attr("data-enterable")): 'undefined';
	var dataDeletable = ($list.attr("data-deletable") != undefined)? parseInt($list.attr("data-deletable")): 'undefined';

	if(valchange == undefined) valchange = false;

	var dataEditList = [];
	var dataUploadList = [];
	var dataSoundList = [];
	var dataPasswordList = [];
	var dataCheckList = [];
	$list.find("table tr:first-child th, table tr:first-child td").each(function(){
		if($(this).attr("data-editable") == "true"){
			dataEditList.push("edit");
		}
		else{
			dataEditList.push("");
		}

		if($(this).attr("data-uploadable") == "true"){
			dataUploadList.push(true);
		}
		else{
			dataUploadList.push(false);
		}

		if($(this).attr("data-soundable") == "true"){
			dataSoundList.push(true);
		}
		else{
			dataSoundList.push(false);
		}

		if($(this).attr("data-password") == "true"){
			dataPasswordList.push("password");
		}
		else{
			dataPasswordList.push("");
		}
		
		if($(this).attr("data-checkable") == "true"){
			dataCheckList.push("check");
		}
		else{
			dataCheckList.push("");
		}
	});


	for(var row in data){
		var dataEnterPrint = (dataEnterable == 'undefined');
		var dataDeletePrint = (dataDeletable == 'undefined');
		var order = 0;

		content = data[row];
		var line = (classname == undefined)? "<tr>" : "<tr class='" + classname + "'>";
		for(var i = 0 ; i < content.length ; i += 2 ){
			//order : 表格上第幾格
			//cotent[i] : SQL屬性名
			//cotent[i+1] : SQL屬性值
			
			while( (!dataEnterPrint && dataEnterable != NaN && order == dataEnterable)
				   || ( !dataDeletePrint && dataDeletable != NaN && order == dataDeletable)){
				if(order == dataEnterable){
					line += "<td><div class='edit_button'></div></td>";
					dataEnterPrint = true;
				}
				else{
					line += "<td><div class='delete_button'></div></td>";
					dataDeletePrint = true;
				}
				++order;
			}
			if( valchange ){
				line += "<td class='" +dataPasswordList[order] +' '+dataCheckList[order] +"' data-name='" + content[i] + "'>";
			}
			else{
				line += "<td class='" +dataEditList[order] +" "+dataPasswordList[order] +' '+dataCheckList[order] +"' data-name='" + content[i] + "'>";
			}
			
			if(dataPasswordList[order] == "password"){
				if( valchange ){
					line += "<input type='password' value='' class='inputing'/>";
				}
				else{
					line += "********";
				}
			}
			else if(!dataSoundList[order] || (dataSoundList[order] && content[i+1] == '電腦發音')){
				if( valchange && dataEditList[order] ){
					line += "<input type='text' value='" + content[i+1] + "' class='inputing'/>"	
				}
				else{
					if( !dataCheckList[order] )
						line += content[i+1];
				}
			}

			if(dataUploadList[order]){
				line += "<div class='upload_button' data-file='" + content[i+1] + "'></div>";
			}
			if(dataSoundList[order]){
				line += "<div class='sound_button'";
				if( content[i+1] == '電腦發音'){
					line += " data-sound='"+ autoSoundFilepathEncode(content[i-3]) +"'";
				}else{
					line += " data-sound='"+ content[i+1] +"'";
				}
				line += "></div>";
			}
			
			if( dataCheckList[order] ){
				if( content[i+1] == 1 ){
					line += "<div class='checked_button'></div>";
				}else if( content[i+1] == 0 ){
					line += "<div class='unchecked_button'></div>";
				}
			}
			
			line += "</td>";
			++order;
		}

		if(!dataEnterPrint){
			line += "<td><div class='edit_button'></div></td>";
			dataEnterPrint = true;
		}

		if(!dataDeletePrint){
			line += "<td><div class='delete_button'></div></td>";
			dataDeletePrint = true;
		}


		line += "</tr>";
		$list.find(afterwho).after(line);
	}
}

$(document).ready(function(){
	$("#studentEditChange").addClass('selected');
	$("#wordEditChange").addClass('selected');
	
	$("#studentEditChange").click(function(){
		$("#lessonOpenChange").removeClass('selected');
		$("#studentEditChange").addClass('selected');
		
		$("#studentEdit").stop();
		$("#wordEdit").stop();
		$("#lessonOpen").stop();
		/*
		$('#lessonOpen').slideUp(200, function(){
			$('#studentEdit').slideDown(200);
		});
		*/
		$('#lessonOpen').css('display','none');
		$('#studentEdit').fadeIn();
	});

	$("#wordEditChange").click(function(){
		$("#lessonOpenChange").removeClass('selected');
		$("#wordEditChange").addClass('selected');
		
		$("#studentEdit").stop();
		$("#wordEdit").stop();
		$("#lessonOpen").stop();
		/*
		$('#lessonOpen').slideUp(200, function(){
			$('#wordEdit').slideDown(200);
		});
		*/
		$('#lessonOpen').css('display','none');
		$('#wordEdit').fadeIn();
	});

	$("#lessonOpenChange").click(function(){
		$("#studentEditChange").removeClass('selected');
		$("#wordEditChange").removeClass('selected');
		$("#lessonOpenChange").addClass('selected');
		
		$("#studentEdit").stop();
		$("#wordEdit").stop();
		$("#lessonOpen").stop();
		
		if($('#wordEdit').length <= 0){
			/*
			$('#studentEdit').slideUp(200, function(){
				$('#lessonOpen').slideDown(200);
			});
			*/
			$('#studentEdit').css('display','none');
		}
		else{
			/*
			$('#wordEdit').slideUp(200, function(){
				$('#lessonOpen').slideDown(200);
			});*/
			$('#wordEdit').css('display','none');
		}
		$('#lessonOpen').fadeIn();
	});

	$(".checkit, .checkitOn").click(function(){
		if($(this).hasClass("checkit")){
			$(this).removeClass("checkit");
			$(this).addClass("checkitOn");
			$(this).parent().children("td").addClass("yellow");
		}
		else{
			$(this).removeClass("checkitOn");
			$(this).addClass("checkit");
			$(this).parent().children("td").removeClass("yellow");
		}

		if( $(this).children("img").attr('alt') == "Yes" ){
			$(this).children("img").attr('src', 'images/1376380913_notification_error.png');
			$(this).children("img").attr('alt', 'No');
		}
		else{
			$(this).children("img").attr('src', 'images/1376380886_notification_done.png');
			$(this).children("img").attr('alt', 'Yes');
		}
	});

	$(".save_lesson_button").click(function(){
		$(".save_lesson_button").hide(500);
		$(".save_lesson_button").after("<div class='temp_message'>資料儲存中...</div>");
		$(".checkitOn").each(function(index){
			if($(this).children("img").attr('alt') == "Yes"){
				$classid = $(this).parent().children("td:nth-child(2)").html();
				//console.log("lid~"+$lid + ",classid^_^" + $classid);
				$.post("php/lessonClassLink.php", {control: "Add", lesson_id: $lid, class_id: $classid},
					function(data){
						if(data == "OK"){
							if($(".checkitOn").length <= index+1){
								$(".checkitOn").addClass("checkit");
								$(".checkitOn").parent().children("td").removeClass("yellow");
								$(".checkitOn").removeClass("checkitOn");
								$(".save_lesson_button").show(500);
								$(".temp_message").remove();
							}
							
						}
						else{
							console.log("Error");
						}
					});
			}
			else{
				$classid = $(this).parent().children("td:nth-child(2)").html();
				$.post("php/lessonClassLink.php", {control: "Delete", lesson_id: $lid, class_id: $classid},
					function(data){
						if(data == "OK"){
							if($(".checkitOn").length <= index+1){
								$(".checkitOn").addClass("checkit");
								$(".checkitOn").parent().children("td").removeClass("yellow");
								$(".checkitOn").removeClass("checkitOn");
								$(".save_lesson_button").show(500);
								$(".temp_message").remove();
							}
						}
						else{
							console.log("Error");
						}
					});
			}

			
		});
	});

	$(".save_class_button").click(function(){
		$(".save_class_button").hide(500);
		$(".save_class_button").after("<div class='temp_message'>資料儲存中...</div>");
		$(".checkitOn").each(function(index){
			if($(this).children("img").attr('alt') == "Yes"){
				$lessonid = $(this).parent().children("td:nth-child(2)").html();
				$.post("php/lessonClassLink.php", {control: "Add", lesson_id: $lessonid, class_id: $lid},
					function(data){
						if(data == "OK"){
							if($(".checkitOn").length <= index+1){
								$(".checkitOn").addClass("checkit");
								$(".checkitOn").parent().children("td").removeClass("yellow");
								$(".checkitOn").removeClass("checkitOn");
								$(".save_class_button").show(500);
								$(".temp_message").remove();
							}
							
						}
						else{
							console.log("Error");
						}
					});
			}
			else{
				$lessonid = $(this).parent().children("td:nth-child(2)").html();
				$.post("php/lessonClassLink.php", {control: "Delete", lesson_id: $lessonid, class_id: $lid},
					function(data){
						if(data == "OK"){
							if($(".checkitOn").length <= index+1){
								$(".checkitOn").addClass("checkit");
								$(".checkitOn").parent().children("td").removeClass("yellow");
								$(".checkitOn").removeClass("checkitOn");
								$(".save_class_button").show(500);
								$(".temp_message").remove();
							}
						}
						else{
							console.log("Error");
						}
					});
			}

			
		});
	});

	/* general generate */
	$(".RMList").each(function(){
		var $list = $(this);
		var $dataname = $(this).attr("data-name");
		var $datatitle = $(this).attr("data-title");

		$list.after("<div class='RMConsole'><img src='images/loader.gif' alt='Loading...' /></div>");
		$.post("./php/generalControl.php", { control: "view", dataname: $dataname, id: $lid }, function(data){
			data = eval(data);
			$list.children("table").before("<div style='text-align:right; margin-right:30px;'>" + $datatitle 
				+"數:<span class='count'>" + data.length + "</span></div>");
			if( $dataname!='teacher' ){
				$list.children("table").before("<div style='text-align:right; margin-right:30px;'>" 
					+"<div class='button'><a href='#' class='addButton'>新增" + $datatitle + "</a></div></div><div class='button save_button'>全部儲存</div>");
			}
			$list.children("table").before("<div class='dialog-upload' title='上傳語音檔'>"
				+"<form action='upload.php' target='upload_target' method='post' enctype='multipart/form-data'>"
				+"<input type='file' name='myfile' /><fieldset style='display:none;'></fieldset></form>"
				+"<iframe id='upload_target' name='upload_target' src='#' style='width:0;height:0;border:0px solid #fff;'></iframe>"
				+"<img src='images/loader.gif' alt='Uploading...' style='display:none'/></div>")
			$list.children("table").after("<div class='button save_button' style='margin-bottom: 10px'>全部儲存</div>");
			ContentMaker($list, data, ".empty_prompt");

			if(data.length > 0) $list.find(".empty_prompt").hide();
			/*
			$('.RMConsole').hide(500, function(){
				$list.show(500);
			});
			*/
			$('.RMConsole').css('display','none');
			$list.fadeIn();
		});

	});

	$(document).on("click", ".RMList .addButton", function(){
		var $list = $(this).parents(".RMList");
		var $dataname = $list.attr("data-name");
		$.post("./php/generalControl.php",
              { dataname: $dataname, 
              	id: $lid,
            	control: "insert" }, function (data) {
                data = eval("[" + data + "]");
                ContentMaker($list, data, ".empty_prompt", "newadd", true);
                $list.find('.empty_prompt').hide(500);
                $list.find('.newadd').show(500);

                var $count = $list.find(".count");
                $count.html(parseInt($count.html())+1);
            });
        return false;
	});

	$(document).on("click", ".RMList .edit", function(){
		var content = $(this).html();
		if( $(this).hasClass("password")){
			$(this).html("<input type='password' value='' class='inputing'/>");
		}
		else{
			$(this).html("<input type='text' value='" + content + "' class='inputing' data-origin='"+content+"' />");
		}
        
        $(this).removeClass('edit');
        $(this).addClass('input');
	});
	
	//按下enter編輯的反應
	$(document).on('keypress', '.RMList .inputing', function (key) {
        var code = (key.keyCode ? key.keyCode : key.which);
        if (code == 13) {
        	var $list = $(this).parents(".RMList"); 
        	var $dataname = $list.attr("data-name");
            var $input = $(this);
            var $writePlace = $(this).parent();
            var $content = $(this).val();
			var $content_origin = $(this).attr("data-origin");
            var $colname = $writePlace.attr("data-name");

			if( $content === "" ){
				if( $writePlace.hasClass("password") ) $writePlace.html("********");
				else $writePlace.html($content_origin);
				$writePlace.removeClass('input');
				$writePlace.addClass('edit');
			}else{
				$content = $content.replace(/ /g,'&nbsp;'); //空白換成&nbsp;
				if($writePlace.hasClass("password")){ $content = $.md5($content); }
	            var $id = $(this).parent().parent().children("td:first-child").html();
				$writePlace.html(html_loading);
	            $.post("./php/generalControl.php", {
	                control: "modify",
	                dataname: $dataname,
	                colname: $colname,
	                content: $content,
	                id: $id 
	            }, function (data) {
	            	if( $writePlace.hasClass("password") ) $writePlace.html("********");
	                else $writePlace.html(data);
					
					//將發音置換成最新的檔案
					if( $dataname=='word' && $colname=='content' ){
						var tr = $writePlace.parent();
						
						//console.log( tr.children('td:nth-child(5)') );
						//console.log( tr.children('td:nth-child(5)').children('div:nth-child(2)') );
						//console.log( tr.children('td:nth-child(5)').children('div:nth-child(2)').attr('data-sound') );
						
						
						tr.children('td:nth-child(5)').children('div:nth-child(2)').attr('data-sound', autoSoundFilepathEncode($content) );
					}
	            });
				$writePlace.removeClass('input');
				$writePlace.addClass('edit');
			}
        }
    });

	
	$(document).on('click', '.RMList .delete_button', function () {
        var delcheck = confirm("您確定要刪除嗎？");
        if (delcheck) {
        	var $list = $(this).parents(".RMList");
        	var $dataname = $list.attr("data-name");
            var $item = $(this).parent().parent();
            $(this).parent().html("<img src='images/ajax-loader.gif' alt='Loading...'/>");
            $.post("./php/generalControl.php", {
                control: "delete",
                dataname: $dataname,
                id: $item.children("td:first-child").html()
            }, function (data) {
                if (data == "OK") {
                	var count = parseInt($list.find('.count').html());
                	--count;
                    $item.hide("slow", function(){
                    	if( count <= 0 ){
                			$list.find('.empty_prompt').show(500);
                    	}
                    });
                    $item.removeClass('newadd');
                    $list.find('.count').html(count);
                    
                }
                else {
                    $item.html(data);
                }
            });
        }
    });
	

	$(document).on('click', '.RMList .edit_button', function () {
		var lid = $(this).parent().parent().children("td:first-child").html();
		//console.log( lid );
		document.location.href = document.location.href + "&lid="+lid;
	});

	$(document).on('click', '.RMList .upload_button', function(){
		var $list = $(this).parents('.RMList');
		var $upload = $(this).parent();
		$('.dialog-upload fieldset').html("<input type='hidden' name='colname' value='" + $upload.attr('data-name') + "' />"
			+ "<input type='hidden' name='dataname' value='" + $list.attr('data-name') + "' />"
			+ "<input type='hidden' name='id' value='" + $upload.parent().children("td:first-child").html() + "' />"
			+ "<input type='hidden' name='oldfile' value='" + $(this).attr('data-file') + "' />");
		//注意：不得有多個表格具備上傳功能，因為jquery-ui的一個小限制 啾咪>_O '`ｧ,､ｧ(*´Д｀*)'`ｧ,､ｧ/
		$('.dialog-upload').dialog({
			height: 250,
			width: 500,
			modal: true,
			buttons: {
				"上傳": function(){
					if(!uploading){
						$upload.addClass('update');
						$('.ui-button').hide();
						$('.dialog-upload form').hide(300);
						$('.dialog-upload img').show(300);
						$('.dialog-upload form').submit();
						uplaoding = true;
					}
				}
			},
			close: function(){
				$('.dialog-upload form').show();
				$('.ui-button').show();
				$('.dialog-upload img').hide();
				$('.dialog-upload form input[type=file]').val("");
			}});
	});

	$(document).on('click', '.RMList .sound_button', function(){
		//console.log($(this).attr('data-sound'));
		playSound($(this).attr('data-sound'));
	})

	$(document).on('click', '.RMList .save_button', function(){
		$(this).parents('.RMList').find('.inputing').each(function(){
			var $list = $(this).parents(".RMList"); 
        	var $dataname = $list.attr("data-name");
            var $input = $(this);
            var $writePlace = $(this).parent();
            var $content = $(this).val();
			var $content_origin = $(this).attr("data-origin");
            var $colname = $writePlace.attr("data-name");

			if( $content === "" ){
				//[空白改回原來的值]
				if( $writePlace.hasClass("password") ) $writePlace.html("********");
				else $writePlace.html($content_origin);
				$writePlace.removeClass('input');
				$writePlace.addClass('edit');
			}else{
				$content = $content.replace(/ /g,'&nbsp;'); //空白換成&nbsp;
				if($writePlace.hasClass("password")){ $content = $.md5($content); }
	            var $id = $(this).parent().parent().children("td:first-child").html();
				$writePlace.html(html_loading);
	            $.post("./php/generalControl.php", {
	                control: "modify",
	                dataname: $dataname,
	                colname: $colname,
	                content: $content,
	                id: $id 
	            }, function (data) {
	            	if( $writePlace.hasClass("password") ) $writePlace.html("********");
	                else $writePlace.html(data);
					
					//console.log("全部儲存");
					//將發音置換成最新的檔案
					if( $dataname=='word' && $colname=='content' ){
						var tr = $writePlace.parent();
						
						//console.log( tr.children('td:nth-child(5)') );
						//console.log( tr.children('td:nth-child(5)').children('div:nth-child(2)') );
						//console.log( tr.children('td:nth-child(5)').children('div:nth-child(2)').attr('data-sound') );
						
						tr.children('td:nth-child(5)').children('div:nth-child(2)').attr('data-sound', autoSoundFilepathEncode($content) );
					}
	            });
	            $writePlace.removeClass('input');
	            $writePlace.addClass('edit');
       		}
		});
	});
	
	$(document).on('click', '.RMList .check', function(){
		var $list = $(this).parents(".RMList");
		var $dataname = $list.attr("data-name");
		var $writePlace = $(this);
		var $colname = $writePlace.attr("data-name");
		var $id = $(this).parent().children("td:first-child").html();
		
		var value_to_modify = $(this).children("div").hasClass('checked_button')?0:1 ;
		$writePlace.html(html_loading);
		$.post("./php/generalControl.php", {
			control: "modify",
			dataname: $dataname,
			colname: $colname,
			content: value_to_modify,
			id: $id 
		}, function (data) {
			if( value_to_modify == 0 ){
				$writePlace.html("<div class='unchecked_button'></div>");
			}else{
				$writePlace.html("<div class='checked_button'></div>");
			}
		});
	});
});