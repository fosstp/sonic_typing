var html_button_name_modify = "<div class = 'button inputModify'>修改</div>";
var html_loading = "<div class='load_img'></div>";

$(document).ready(
	function () {
	    $("#lessonAdd").click(function () {
			//todo:移除目前尚無課程
			
	        $.post("./lessonControl.php",
                { "control": "insert" }, function (data) {
                    data = eval("(" + data + ")");
                    $("#lessonHeader").after("<tr style='display:none;' class='newadd'><td>" + data['lesson_id'] +"</td>"
						+"<td><div class='edit_button lesson_edit_button'></div></td>"
						+"<td><div class='delete_button lesson_delete_button'></div></td>"
						+"<td style='text-align: left'><input type='text' value='"+data['name']+"' class='inputTitle'/>"+html_button_name_modify
						+"</tr>");
                    $('#empty_lesson_prompt').hide(500);
                    $('.newadd').show(500);
                    var lessonCount = parseInt($('#lesson_count').html());
                    ++lessonCount;
                    $('#lesson_count').html(lessonCount);
                });
            return false;
	    });
        //按下課程名稱，轉變為編輯名稱模式
	    $(document).on('click', '.titleChange', function () {
	        var title = $(this).html();
	        $(this).html("<input type='text' value='" + title + "' class='inputTitle'/>"+html_button_name_modify);
	        $(this).removeClass('titleChange');
	    });

	    $(document).on('keypress', '.inputTitle', function (key) {
	        var code = (key.keyCode ? key.keyCode : key.which);
	        if (code == 13) {
	            var $outerThis = $(this);
	            var $writePlace = $(this).parent();
	            var $title = $(this).parent().children('.inputTitle').val();
				$title = $title==="" ? "未命名課程" : $title;
	            var $id = $(this).parent().parent().children("td:nth-child(1)").html();
				$outerThis.parent().html(html_loading);
	            $.post("./lessonControl.php", {
	                control: "modify",
	                title: $title,
	                id: $id 
	            }, function (data) {
	                $writePlace.html(data);
	            });
	            $writePlace.addClass('titleChange');
	        }
	    });

	    $(document).on('click', '.inputModify', function () {
	        var $outerThis = $(this);
	        var $writePlace = $(this).parent();
	        var $title = $(this).parent().children('.inputTitle').val();
	        var $id = $(this).parent().parent().children("td:nth-child(1)").html();
	        $outerThis.parent().html(html_loading);
	        $.post("./lessonControl.php", {
	            control: "modify",
	            title: $title, 
	            id: $id
	        }, function(data){
	            $writePlace.html(data);
	        });
	        $writePlace.addClass('titleChange');
	    });
	 

	    $(document).on('click', '.lesson_delete_button', function () {
			//todo: 加入目前尚無課程
	        var delcheck = confirm("您確定要刪除本課程嗎？");
	        if (delcheck) {
	            var $item = $(this).parent().parent();
	            $(this).parent().html("<img src='images/ajax-loader.gif' alt='Loading...'/>");
	            $.post("./lessonControl.php", {
	                control: "delete",
	                id: $item.children("td:nth-child(1)").html()
	            }, function (data) {
	                if (data == "OK") {
	                	var lessonCount = parseInt($('#lesson_count').html());
	                	--lessonCount;
	                    $item.hide("slow", function(){
	                    	if( lessonCount <= 0 ){
                    			$('#empty_lesson_prompt').show(500);
	                    	}
	                    });
	                    $item.removeClass('newadd');
	                    $('#lesson_count').html(lessonCount);
	                    
	                }
	                else {
	                    $item.html(data);
	                }
	            });
	        }
	    });
		
		//[按下課程編輯]
		$(document).on('click', '.lesson_edit_button', function () {
			var lid = $(this).parent().parent().children("td:nth-child(1)").html();
			//console.log( lid );
			document.location.href = "./lobby.php?p=lesson&lid="+lid;
		});
		
		//[課程單字編輯頁]
		//新增單字鈕
		$("#wordAdd").click(function () {
			//todo:移除目前尚無單字
	        $.post("./php/wordControl.php",
                { "control": "insert",
					"lid" : $.getUrlVar('lid'),
				},function (data) {
					console.log(data);
					data = eval("(" + data + ")");
					$("#word_editer_header").after("<tr style='display:none;' class='newadd'>"
						+"<td>"+data['word_id']+"</td>"
						+"<td><div class='delete_button word_delete_button'></div></td>"
						+"<td class='word_content_change'>"+data['content']+"</td>"
						+"<td class='word_meaning_change'>"+data['meaning']+"</td>"
						+"<td>"+data['sound']+"<div class='upload_button word_sound_upload_button'></div></td>"
						+"</tr>");
					var word_count = parseInt($('#word_count').html());
					++word_count;
					$('#word_count').html(word_count);
                    $('.newadd').show(500);
                    $('#empty_word_prompt').hide();
                });
            return false;
	    });
		
		//刪除單字鈕
		$(document).on('click', '.word_delete_button', function () {
			//todo: 加入目前尚無單字
			var delcheck = confirm("您確定要刪除本單字嗎？");
			if (delcheck) {
				var $item = $(this).parent().parent();
				$(this).parent().html(html_loading);
				$.post("./php/wordControl.php", {
					control: "delete",
					wid: $item.children("td:nth-child(1)").html()
				}, function (data) {
					if (data == "OK") {
						var wordCount = parseInt($('#word_count').html());
						--wordCount;
						$('#word_count').html(wordCount);
						$item.removeClass('newadd');
						$item.hide("slow", function(){
							if(wordCount <= 0 ){
								$('#empty_word_prompt').show(500);
							}
						});
					}else {
						$item.html(data);
					}
				});
			}
		});
		
		//按下單字英文處，轉變為編輯英文模式
		$(document).on('click', '.word_content_change', function () {
			var content = $(this).html();
			$(this).html("<input type='text' value='" + content + "' class='word_content_input'/>");
			$(this).removeClass('word_content_change');
		});
		//編輯英文模式按下enter，回傳資料庫
		$(document).on('keypress', '.word_content_input', function (key) {
			var code = (key.keyCode ? key.keyCode : key.which);
			if (code == 13) {
				var $outerThis = $(this);
				var $writePlace = $(this).parent();
				var $content = $(this).parent().children('.word_content_input').val();
				$content = $content==="" ? "none" : $content;
				$content = $content.replace(/ /g,'&nbsp'); //空白換成&nbsp
				var $wid = $(this).parent().parent().children("td:nth-child(1)").html();
				$outerThis.parent().html(html_loading);
				$.post("./php/wordControl.php", {
					control: "modifyContent",
					content: $content,
					wid: $wid
				}, function (data) {
					$writePlace.html(data);
				});
				$writePlace.addClass('word_content_change');
			}
		});
		
		//按下單字中文處，轉變為編輯中文模式
		$(document).on('click', '.word_meaning_change', function () {
			var meaning = $(this).html();
			$(this).html("<input type='text' value='" + meaning + "' class='word_meaning_input'/>");
			$(this).removeClass('word_meaning_change');
		});
		//編輯中文模式按下enter，回傳資料庫
		$(document).on('keypress', '.word_meaning_input', function (key) {
			var code = (key.keyCode ? key.keyCode : key.which);
			if (code == 13) {
				var $outerThis = $(this);
				var $writePlace = $(this).parent();
				var $meaning = $(this).parent().children('.word_meaning_input').val();
				$meaning = $meaning==="" ? "無" : $meaning;
				var $wid = $(this).parent().parent().children("td:nth-child(1)").html();
				$outerThis.parent().html(html_loading);
				$.post("./php/wordControl.php", {
					control: "modifyMeaning",
					meaning: $meaning,
					wid: $wid
				}, function (data) {
					$writePlace.html(data);
				});
				$writePlace.addClass('word_meaning_change');
			}
		});
	}
);