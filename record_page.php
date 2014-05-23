<link rel="stylesheet" type="text/css" href="css/record.css"/>
<script type="text/javascript" language="javascript" src="./js/record.js"></script>
<!-- dataTablesIncludes -->
<style type="text/css" title="currentStyle">
	@import "DataTables-1.9.4/media/css/demo_page.css";
	@import "DataTables-1.9.4/media/css/jquery.dataTables.css";
</style>
<script type="text/javascript" language="javascript" src="DataTables-1.9.4/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="DataTables-1.9.4/media/js/jquery.dataTables.js"></script>

<?php
include "./php/dbLink.php";
$mysqli = STmysqli();

/*
function TeacherRecordView(){
	global $mysqli;
	
	//序號, 練習時間, 單元名稱, 班級, 學生, 版本, 花費時間
	$my_query = "
		SELECT record_id, record_created_time, name, class_name, seat_num, student.realname, mode, record_cost_time
		FROM( 
			record NATURAL JOIN lesson NATURAL JOIN student NATURAL JOIN class
			JOIN teacher ON (class.teacher_id=teacher.teacher_id)
		) WHERE teacher.teacher_id=$_SESSION[id]
		ORDER BY `record`.`record_created_time` DESC;
	";
	$record_result = $mysqli->query( $my_query );

	if($record_result==NULL || $record_result->num_rows==0 ){
		echo "<div class='error_msg'>尚無任何紀錄</div>";
	}else{
		//echo "<div style='text-align:right; margin-right:50px;'>單字數:<span id='word_count'>$word_result->num_rows</span></div>";
		?>
		<table style='margin-top:10px;'>
		<tr>
		<th style='width:50px'>編號</th>
		<th style='width:120px'>練習日期</th>
		<th style='width:250px'>單元名稱</th>
		<th style='width:80px'>班級</th>
		<th style='width:60px'>座號</th>
		<th style='width:80px'>學生</th>
		<th style='width:80px'>版本</th>
		<th style='width:120px'>花費時間</th>
		</tr>
		<?php
		while( $row = $record_result->fetch_assoc() ){
			echo "<tr>
			<td>$row[record_id]</td>
			<td>$row[record_created_time]</td>
			<td>$row[name]</td>
			<td>$row[class_name]</td>
			<td>$row[seat_num]</td>
			<td>$row[realname]</td>
			";
			if( $row['mode'] == 'recite' ){
				echo "<td>背書版</td>";
			}else if( $row['mode'] == 'hint' ){
				echo "<td>提示版</td>";
			}else if( $row['mode'] == 'test' ){
				echo "<td>測驗版</td>";
			}else{
				echo "<td>黑板</td>";
			}
			
			echo "
			<td>$row[record_cost_time]秒</td>
			</tr>";
		}
		echo "
</table>
";
	}
}
*/
function TeacherViewShowAll(){
	global $mysqli;
	?>
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			dataTableStart();
		} );
	</script>
	<?php
	//序號, 練習時間, 單元名稱, 班級, 學生, 版本, 花費時間
	$my_query = "
		SELECT record_id, record_created_time, name, class_name, seat_num, student.realname, mode, record_cost_time
		FROM( 
			record NATURAL JOIN lesson NATURAL JOIN student NATURAL JOIN class
			JOIN teacher ON (class.teacher_id=teacher.teacher_id)
		) WHERE teacher.teacher_id=$_SESSION[id]
		ORDER BY `record`.`record_created_time` DESC;
	";
	$record_result = $mysqli->query( $my_query );

	if($record_result==NULL || $record_result->num_rows==0 ){
		echo "<div class='error_msg'>尚無任何紀錄</div>";
	}else{
		?>
		<div style='height: 10px;'></div>
		<table style='margin-top:10px;' id='record_table' class="display">
			<thead>
				<tr>
				<th style='width:60px'>編號</th>
				<th style='width:120px'>練習日期</th>
				<th style='width:250px'>單元名稱</th>
				<th style='width:80px'>班級</th>
				<th style='width:60px'>座號</th>
				<th style='width:80px'>學生</th>
				<th style='width:80px'>版本</th>
				<th style='width:110px'>秒數</th>
				</tr>
			</thead>
			<tbody>
		<?php
		while( $row = $record_result->fetch_assoc() ){
			echo "<tr>
			<td>$row[record_id]</td>
			<td>$row[record_created_time]</td>
			<td>$row[name]</td>
			<td>$row[class_name]</td>
			<td>$row[seat_num]</td>
			<td>$row[realname]</td>
			";
			if( $row['mode'] == 'recite' ){
				echo "<td>背書版</td>";
			}else if( $row['mode'] == 'hint' ){
				echo "<td>提示版</td>";
			}else if( $row['mode'] == 'test' ){
				echo "<td>測驗版</td>";
			}else{
				echo "<td>黑板</td>";
			}
			
			echo "
			<td>$row[record_cost_time]秒</td>
			</tr>";
		}
		?>
			</tbody>
			<tfoot>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</tfoot>
		</table>
		<div style='height: 50px;'></div>
		<?php
	}
}

function TeacherViewLessonClassList(){
	global $mysqli;
	$my_query = "
		SELECT class_id, class_name
		FROM class
		WHERE teacher_id=$_SESSION[id]
		ORDER BY `class_id` DESC;
	";
	$lesson_query = "
		SELECT lesson_id, name 
		FROM `lesson`
		WHERE teacher_id=$_SESSION[id]
		ORDER BY `lesson_id` DESC;
	";
	$record_result = $mysqli->query( $my_query );
	$lesson_result = $mysqli->query( $lesson_query );
	if($record_result==NULL || $record_result->num_rows==0 || $lesson_result==NULL || $lesson_result->num_rows==0 ){
		echo "<div class='error_msg'>尚無建立班級或課程</div>";
	}else{
	?>
		<div style='height: 10px;'></div>
		<div style='margin: 0px 100px 5px 0px;' class="button" id="submitClassSearch">記錄查詢</div>
		<div class="msg" id="msgClassSearch"></div>
		<div style='text-align:left;'>
			<div style='margin-left: 12px;'><strong>日期限定: </strong><input type="text" class="datepicker" id='start_t'> 至 <input type="text" class="datepicker" id='end_t'> </div>
			<table cellspacing='10' style='padding:0px; margin:0px;'>
				<tr>
				<td style="width: 250px;">
					<div><strong>限定課程: </strong></div>
					<select id='select_lesson' size="20" style='margin-left: 50px; width:200px;'>
					<?php
						while( $row = $lesson_result->fetch_assoc() ){
							echo "<option value='$row[lesson_id]' id='opt_lesson_$row[lesson_id]'>課號$row[lesson_id] : $row[name]</option>";
						}
						
					?>
				</td>
				<td style="width: 200px;">
					<div><strong>限定班級: </strong></div>
					<select id='select_class' size="20" style='margin-left: 50px; width:150px;'>
					<?php
						while( $row = $record_result->fetch_assoc() ){
							echo "<option value='$row[class_id]' id='opt_class_$row[class_id]'>班號$row[class_id] : $row[class_name]</option>";
						}
					?>
					</select>
				</td>
				<tr>
			</table>
		</div>
	<?php
	}
}

function TeacherViewStudentList(){
	?>
	<div class='error_msg'>請先使用 選擇課程/班級</div>
	<?php
}

function TeacherViewOneStudent(){
	?>
	<div class='error_msg'>請先使用 選擇學生</div>
	<?php
}

function StudentRecordView(){
	global $mysqli;
	
	?>
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			dataTableStart();
		} );
	</script>
	<?php
	//序號, 練習時間, 單元名稱, 版本, 花費時間
	$record_result = $mysqli->query( "SELECT record_id, record_created_time, name, mode, record_cost_time FROM (record NATURAL JOIN lesson) WHERE student_id=$_SESSION[id] ORDER BY `record`.`record_created_time` DESC;" );

	if($record_result==NULL || $record_result->num_rows==0 ){
		echo "<div class='error_msg'>尚無任何紀錄</div>";
	}else{
		//echo "<div style='text-align:right; margin-right:50px;'>單字數:<span id='word_count'>$word_result->num_rows</span></div>";
		?>
		<div style='height: 10px;'></div>
		<table style='margin-top:10px;' id='record_table'>
			<thead>
				<tr>
				<th style='width:50px'>編號</th>
				<th style='width:200px'>練習時間</th>
				<th style='width:200px'>單元名稱</th>
				<th style='width:80px'>版本</th>
				<th style='width:120px'>花費時間</th>
				</tr>
			</thead>
		<?php
		while( $row = $record_result->fetch_assoc() ){
			echo "<tr>
			<td>$row[record_id]</td>
			<td>$row[record_created_time]</td>
			<td>$row[name]</td>
			";
			if( $row['mode'] == 'recite' ){
				echo "<td>背書版</td>";
			}else if( $row['mode'] == 'hint' ){
				echo "<td>提示版</td>";
			}else if( $row['mode'] == 'test' ){
				echo "<td>測驗版</td>";
			}else{
				echo "<td>黑板</td>";
			}
			
			echo "
			<td>$row[record_cost_time]秒</td>
			</tr>";
		}
?>
		<tfoot style='margin-down: 10px;'>
			<tr>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
		</tfoot>
		</table>
		<div style='height: 50px;'></div>
<?php
	}
}




if( $_SESSION['identity'] == "teacher" ){
?>
	<div style='text-align: left;'>
		<!--<div class="tab_button" id="selectShowAll">全部顯示</div>-->
		<div class="tab_button" id="selectLessonClassList">選擇課程/班級</div>
		<div class="tab_button" id="selectStudentList">選擇學生</div>
		<div class="tab_button" id="selectOneStudent">個人記錄</div>
	</div>
	<div id='PageShowAll'><?php /*TeacherViewShowAll();*/ ?></div>
	<div id='PageLessonClassList'><?php TeacherViewLessonClassList(); ?></div>
	<div id='PageStudentList'><?php TeacherViewStudentList() ?></div>
	<div id='PageOneStudent'><?php TeacherViewOneStudent() ?></div>
<?php
}else if( $_SESSION['identity'] == "student" ){
	StudentRecordView();
}
?>
