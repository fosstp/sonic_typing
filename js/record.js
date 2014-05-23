var unselectAndFadeoutAll = function (){
	$("#selectShowAll").removeClass('selected');
	$("#selectLessonClassList").removeClass('selected');
	$("#selectStudentList").removeClass('selected');
	$("#selectOneStudent").removeClass('selected');
	
	$('#PageShowAll').css('display', 'none');
	$('#PageLessonClassList').css('display', 'none');
	$('#PageStudentList').css('display', 'none');
	$('#PageOneStudent').css('display', 'none');
	
};

(function($) {
	$(document).ready(function(){
		$(".datepicker").datepicker();
	});
})(jQuery);

var dataTableStart = function(){
	$('#record_table').dataTable( {
		"sPaginationType": "full_numbers",
		"aaSorting": [[ 1, "desc" ]],
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"oLanguage": {
			"sLengthMenu": "每頁顯示 _MENU_ 筆記錄",
			"sZeroRecords": "查無記錄",
			"sInfo": "第 _START_ 至 _END_ 筆記錄，共 _TOTAL_ 筆記錄",
			"sInfoEmpty": "第 0 至 0 筆記錄，共 0 筆記錄",
			"sInfoFiltered": "(從 _MAX_ 筆中搜尋)",
			"sSearch": "搜尋關鍵字:",
			"oPaginate": {
				"sFirst": "首頁",
				"sPrevious": "上一頁",
				"sNext": "下一頁",
				"sLast": "末頁"
			},
		},
	} );
};

var TeacherViewSelectStudent = function(start_t, end_t, lesson_id, class_id, data){
	data = eval(data);
	content = "";
	
	content += "<div style='text-align:left; margin-left:10pt;'>"
	if( lesson_id != '' ){
		content += "<div><strong>"+$('#opt_lesson_'+lesson_id).html()+"</strong></div>";
	}
	if( class_id != '' ){
		content += "<div><strong>"+$('#opt_class_'+class_id).html()+"</strong></div>";
	}
	if( start_t!='' || end_t!='' ){
		content += "<div><strong>日期 "+start_t+"～"+end_t+"</strong></div>";
	}
	content += "</div>"
	
	content += "<table class='link'><tr>";
	content += "<th style='display:none;'>編號</th>";
	content += "<th>班名</th>";
	content += "<th>座號</th>";
	content += "<th>姓名</th>";
	content += "<th>背書版次數</th>";
	content += "<th>提示版次數</th>";
	content += "<th>測驗版次數</th>";
	content += "<th>總計</th>";
	content += "</tr>";
	if( data.length==0 ){
		content += "<tr><td colspan='7'>尚未建立任何學生</td></tr>";
	}else{
		for( var i in data ){
			var row = data[i]
			//console.log(row);
			content += "<tr>";
			for( var j in row ){
				var cell_content = row[j];
				//console.log(j);
				//console.log(cell_content);
				if( j==0 ){
					content += "<td style='display:none;'>" + cell_content + "</td>";
				}else{
					content += "<td>" + cell_content + "</td>";
				}
			}
			content += "</tr>";
		}
	}
	content += "</table>";
	$('#PageStudentList').html(content);
	$( "#selectStudentList" ).trigger( "click" );
}

var TeacherViewOneStudent = function(start_t, end_t, lesson_id, student_id, class_name, seat_num, student_name, data){
	data = eval(data);
	
	content = "";
	content += "<div style='height: 10px;'></div>";
	content += "<div style='text-align:left; margin-left:10pt;'>"
	content += "<div><strong>班級 "+class_name+" / 座號 "+seat_num+" / 姓名 "+student_name+"</strong></div>";
	if( lesson_id != '' ){
		content += "<div><strong>"+$('#opt_lesson_'+lesson_id).html()+"</strong></div>";
	}
	if( start_t!='' || end_t!='' ){
		content += "<div><strong>日期 "+start_t+"～"+end_t+"</strong></div>";
	}
	content += "</div>"
	
	content += "<table style='margin-top:10px;' id='record_table' class='display'>";
	content += "<thead><tr>";
	content += "<th style='width:50px; display:none;'>編號</th>";
	content += "<th style='width:200px'>練習時間</th>";
	content += "<th style='width:200px'>單元名稱</th>";
	content += "<th style='width:80px'>版本</th>";
	content += "<th style='width:120px'>花費時間</th>";
	content += "</tr></thead>";
	content += "<tbody>";
	if( data.length==0 ){
		content += "<tr><td colspan='7'>尚未建立任何學生</td></tr>";
	}else{
		for( var i in data ){
			var row = data[i]
			//console.log(row);
			content += "<tr>";
			for( var j in row ){
				var cell_content = row[j];
				//console.log(j);
				//console.log(cell_content);
				if( j==0 ){
					content += "<td style='display:none;'>" + cell_content + "</td>";
				}else if( j==3 ){
					if( cell_content == 'recite' ){
						content += "<td>背書版</td>";
					}else if( cell_content == 'hint' ){
						content += "<td>提示版</td>";
					}else if( cell_content == 'test' ){
						content += "<td>測驗版</td>";
					}else{
						content += "<td>黑板</td>";
					}
				}else if( j==4 ){
					content += "<td>" + cell_content + "秒</td>";
				}else{
					content += "<td>" + cell_content + "</td>";
				}
			}
			content += "</tr>";
		}
	}
	content += "</tbody>";
	content += "<tfoot><tr>";
	content += "<th style='display:none;'></th>";
	content += "<th></th>";
	content += "<th></th>";
	content += "<th></th>";
	content += "<th></th>";
	content += "</tr></tfoot>";
	content += "</table>";
	
	content += "<div style='height: 50px;'></div>";
	
	$('#PageOneStudent').html(content);
	dataTableStart();
	$( "#selectOneStudent" ).trigger( "click" );
	//console.log(content);
}

$(document).ready(function(){
	//Record 全頁
	//[初始化誰可見]
	unselectAndFadeoutAll();
	$("#selectLessonClassList").addClass('selected');
	$('#PageLessonClassList').css('display', 'block');
	
	$(".tab_button").click(function(){
		unselectAndFadeoutAll();
		
		id = $(this).attr('id');
		page = 'Page' + $(this).attr('id').substring(6);
		//console.log(id);
		//console.log(page);
		$('#'+id).addClass('selected');
		$('#'+page).fadeIn();
	});
	
	//選擇日期、課程、班級頁
	$('#submitClassSearch').click(function(){
		var start_t = $('#start_t').val();
		var end_t = $('#end_t').val();
		var lesson_id = $('#select_lesson').val()==null?'':$('#select_lesson').val();
		var class_id = $('#select_class').val()==null?'':$('#select_class').val();
		
		/*
		if( start_t=='' && end_t=='' && lesson_id=='' && class_id=='' ){
			alert('請至少限定 日期/課程/班級 其一，或 前往全部顯示');
			return;
		}
		*/
		
		/*
		console.log($('#select_class').val());
		console.log($('#select_lesson').val());
		console.log($('#start_t').val());
		console.log($('#end_t').val());
		*/

		
		$.post(
			"./php/recordQuery.php",
			{
				action: 'classRecordQuery',
				start_t : start_t,
				end_t : end_t,
				lesson_id : lesson_id,
				class_id : class_id,
			},
			function(rev){
				//console.log(rev);
				TeacherViewSelectStudent(start_t, end_t, lesson_id, class_id, rev);
			}
		);
	});
	
	$('select option').on('mousedown', function (e) {
		if( this.selected ){
			this.selected = !this.selected;
			e.preventDefault();
		}
	});
	

});

//選擇學生頁
$(document).on('click', 'table.link td', function(){
	var start_t = $('#start_t').val();
	var end_t = $('#end_t').val();
	var lesson_id = $('#select_lesson').val()==null?'':$('#select_lesson').val();
	var student_id = $(this).parent().children("td:first-child").html();
	var class_name = $(this).parent().children("td:nth-child(2)").html();
	var seat_num = $(this).parent().children("td:nth-child(3)").html();
	var student_name = $(this).parent().children("td:nth-child(4)").html();
	$.post(
		"./php/recordQuery.php",
		{
			action: 'studentRecordQuery',
			start_t : start_t,
			end_t : end_t,
			lesson_id : lesson_id,
			student_id : student_id,
		},
		function(rev){
			console.log(rev);
			TeacherViewOneStudent(start_t, end_t, lesson_id, student_id, class_name, seat_num, student_name, rev);
		}
	);
});

