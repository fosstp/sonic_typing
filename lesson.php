<?php
	include 'php/dbLink.php';
	if( $_SESSION['identity'] == "teacher" ){
		if( isset($_GET['lid']) ){
			$mysqli = STmysqli();
			$result = $mysqli->query( "SELECT name FROM lesson WHERE teacher_id=$_SESSION[id] AND lesson_id=$_GET[lid]" );
			$row = $result->fetch_assoc();
			echo "<div class='lesson_name'><span>$row[name]</span></div>";
?>	
	<script>
		$(document).ready(function(){
			$("#lessonDownload").click(function(){
				$.post("php/lessonAPI.php", {lesson_id: $lid}, function(data){
					location.href = "data/" + data;
				});
			});
		});
	</script>
	<div style="float:left;">
		<div class="tab_button" id="wordEditChange">編輯單字</div>
		<div class="tab_button" id="lessonOpenChange">開放班級</div>
		<div class="tab_button" id="lessonDownload">下載課程</div>
	</div>
	<div id="wordEdit" style="clear: both;">
		<div class="RMList" 
			 data-name="word" 
			 data-title="單字"
			 data-deletable="1">
			<table>
				<tr>
					<th style="width: 50px">單字編號</th>
					<th style="width: 50px">刪除</th>
					<th style="width: 265px" data-editable="true">英文</th>
					<th style="width: 265px" data-editable="true">中文</th>
					<th style="width: 265px" data-uploadable="true" data-soundable="true">語音檔</th>
				</tr>
				<tr class='empty_prompt'>
					<td colspan='5'>尚未建立任何單字</td>
				</tr>
			</table>
		</div>
	</div>
	<div id="lessonOpen" style="clear: both;">
		<div class="button save_lesson_button">儲存資料</div>
		<table>
			<tr>
				<th style="width: 50px">開放</th>
				<th style="width: 50px">班級編號</th>
				<th style="width: 500px">班級名稱</th>
			</tr>
			<?php
				$mysqli = STmysqli();
				$result = $mysqli->query("SELECT class_id, class_name FROM class WHERE teacher_id = '$_SESSION[id]' ORDER BY class_id DESC");
				if($result->num_rows <= 0){
					echo "<tr><td colspan='3'>尚未建立任何班級</td></tr>";
				}
				else{
					while( $row = $result->fetch_assoc() ){
						$allow_result = $mysqli->query("SELECT class_id FROM lesson_class_allow WHERE lesson_id = '$_GET[lid]' AND class_id = '$row[class_id]'");
						echo "<tr><td class='checkit'>";
						if( $allow_result != NULL && $allow_result->num_rows > 0 ){
							echo "<img src='images/1376380886_notification_done.png' alt='Yes' style='width: 30px'/>";
						}
						else{
							echo "<img src='images/1376380913_notification_error.png' alt='No' style='width: 30px'/>";
						}
						echo "</td>";
						echo "<td>" . $row["class_id"] . "</td>";
						echo "<td>" . $row["class_name"] . "</td>";
						echo "</tr>";
					}
				}

			?>
		</table>
		<div class="button save_lesson_button">儲存資料</div>
	</div>

<?php
		}
		else{
?>
	<div class="RMList" 
		 data-name="lesson" 
		 data-title="單元"
		 data-enterable="1" 
		 data-deletable="2">
		<table>
			<tr>
				<th style="width: 50px">課號</th>
				<th style="width: 50px">編輯</th>
				<th style="width: 50px">刪除</th>
				<th style="width: 530px" data-editable="true">單元名稱及修改</th>
			</tr>
			<tr class='empty_prompt'>
				<td colspan='4'>尚未建立任何單元</td>
			</tr>
		</table>
	</div>
<?php
		}
	}
?>