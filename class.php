<?php
	include 'php/dbLink.php';
	if( $_SESSION['identity'] == "teacher" ){
		if( isset($_GET['lid']) ){
			$mysqli = STmysqli();
			$result = $mysqli->query( "SELECT class_name FROM class WHERE teacher_id=$_SESSION[id] AND class_id=$_GET[lid]" );
			$row = $result->fetch_assoc();
			echo "<div class='lesson_name'><span>$row[class_name]</span></div>";
?>	
	<div style="float:left;">
		<div class="tab_button" id="studentEditChange">編輯學生</div>
		<div class="tab_button" id="lessonOpenChange">開放課程</div>
	</div>
	<div id="studentEdit" style="clear:both;">
		<div class="RMList" 
			 data-name="student" 
			 data-title="學生"
			 data-deletable="1">
			<table>
				<tr>
					<th style="width: 50px">編號</th>
					<th style="width: 50px">刪除</th>
					<th style="width: 200px" data-editable="true" data-unique="true">學生帳號</th>
					<th style="width: 100px" data-editable="true" data-password="true">學生密碼</th>
					<th style="width: 60px" data-editable="true">座號</th>
					<th style="width: 100px" data-editable="true">姓名</th>
				</tr>
				<tr class='empty_prompt'>
					<td colspan='6'>尚未建立任何學生</td>
				</tr>
			</table>
		</div>
	</div>
	<div id="lessonOpen" style="clear:both;">
		<div class="button save_class_button">儲存資料</div>
		<table>
			<tr>
				<th style="width: 50px">開放</th>
				<th style="width: 50px">課程編號</th>
				<th style="width: 500px">課程名稱</th>
			</tr>
			<?php
				$mysqli = STmysqli();
				$result = $mysqli->query("SELECT lesson_id, name FROM lesson WHERE teacher_id = '$_SESSION[id]' ORDER BY lesson_id DESC");
				if($result->num_rows <= 0){
					echo "<tr><td colspan='3'>尚未建立任何課程</td></tr>";
				}
				else{
					while( $row = $result->fetch_assoc() ){
						$allow_result = $mysqli->query("SELECT lesson_id FROM lesson_class_allow WHERE class_id=$_GET[lid] AND lesson_id=$row[lesson_id]");
						echo "<tr><td class='checkit'>";
						if( $allow_result->num_rows > 0 ){
							echo "<img src='images/1376380886_notification_done.png' alt='Yes' style='width: 30px'/>";
						}
						else{
							echo "<img src='images/1376380913_notification_error.png' alt='No' style='width: 30px'/>";
						}
						echo "</td>";
						echo "<td>" . $row["lesson_id"] . "</td>";
						echo "<td>" . $row["name"] . "</td>";
						echo "</tr>";
					}
				}
			?>
		</table>
		<div class="button save_class_button">儲存資料</div>
	</div>
<?php
		}
		else{
?>
	<div class="RMList" 
		 data-name="class" 
		 data-title="班級"
		 data-enterable="1" 
		 data-deletable="2">
		<table>
			<tr>
				<th style="width: 50px">班號</th>
				<th style="width: 50px">編輯</th>
				<th style="width: 50px">刪除</th>
				<th style="width: 530px" data-editable="true">班級名稱</th>
			</tr>
			<tr class='empty_prompt'>
				<td colspan='4'>尚未建立任何班級</td>
			</tr>
		</table>
	</div>
<?php
	}
}
?>