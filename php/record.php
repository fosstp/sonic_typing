<?php
include('./dbLink.php');
$mysqli = STmysqli();
session_start();

if( $_SESSION['identity'] == "teacher" ){
	
}else if( $_SESSION['identity'] == "student" ){
	if( $_POST['action']=='upload' ){
		$result = $mysqli->query("INSERT INTO `record` (`lesson_id`, `student_id`, `mode`, `record_cost_time`) VALUES ('$_POST[lesson_id]', '$_SESSION[id]', '$_POST[mode]', '$_POST[cost_time]');");
		if (!$result){
			echo "$mysqli->error";
		}else{
			echo '記錄成功，按《BackSpace》返回前頁';
		}
	}
}
?>
