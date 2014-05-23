<?php
session_start();

date_default_timezone_set("Asia/Taipei");

if( !(isset($_SESSION['identity']) && ($_SESSION['identity']=="teacher" || $_SESSION['identity']=="admin")) ) 
	header("Location: error.html");

if( !(isset($_POST['action'])) )
	header("Location: error.html");

include 'dbLink.php';
$mysqli = STmysqli();

if( $_POST['start_t'] == '' )
	$start_t = '"2000-01-01 00:00:00"';
else{
	$start_t = $_POST['start_t'];
	$dt = new DateTime($start_t);
	$start_t = '"'.$dt->format('Y-m-d 00:00:00').'"';
}
	
if( $_POST['end_t'] == '' )
	$end_t = '"3000-01-01 00:00:00"';
else{
	$end_t = $_POST['end_t'];
	$dt = new DateTime($end_t);
	$end_t = '"'.$dt->format('Y-m-d 23:59:59').'"';
}

if( $_POST['lesson_id'] == '' )
	$lesson_id = 'lesson_id';
else
	$lesson_id = $_POST['lesson_id'];

if( isset($_POST['class_id']) ){
	if( $_POST['class_id'] == '' )
		$class_id = 'class_id';
	else 
		$class_id = $_POST['class_id'];
}
if( isset($_POST['student_id']) )
	$student_id = $_POST['student_id'];

if( $_POST['action'] == 'classRecordQuery' ){
	$my_query = "
		SELECT
			T0.student_id,
			class_name,
			T0.seat_num,
			T0.realname,
			SUM( if(T1.`mode`='recite', 1, 0) ) AS recite_count,
			SUM( if(T1.`mode`='hint', 1, 0) ) AS hint_count,
			SUM( if(T1.`mode`='test', 1, 0) ) AS test_count,
			SUM( if(T1.`mode`='recite', 5, if(T1.`mode`='hint', 1, if(T1.`mode`='test', 1, 0))) ) AS total
		FROM ( 
			SELECT student_id, class_id, class_name, seat_num, realname
			FROM student NATURAL JOIN class 
			WHERE class_id = $class_id
		) AS T0 LEFT JOIN ( 
			SELECT student_id, `mode`
			FROM record
			WHERE 
				$start_t <= `record_created_time` 
				AND `record_created_time` <= $end_t
				AND lesson_id = $lesson_id
		) AS T1 ON T0.student_id=T1.student_id
		GROUP BY T0.student_id
		ORDER BY class_id, seat_num ASC
		;
	";
}else if($_POST['action'] == 'studentRecordQuery'){
	$my_query = "
		SELECT
			record_id, 
			record_created_time, 
			name, 
			mode, 
			record_cost_time
		FROM 
			record NATURAL JOIN lesson
		WHERE 
			student_id=$student_id
			AND $start_t <= `record_created_time` 
			AND `record_created_time` <= $end_t
			AND lesson_id = $lesson_id
		ORDER BY `record`.`record_created_time` DESC;
	";
}

$result = $mysqli->query($my_query);
$data = array();

while( $row = $result->fetch_assoc() ){
	$rowArray = array();
	foreach ($row as $key => $value) {
		//array_push($rowArray, $key);
		array_push($rowArray, $value);
	}
	array_push($data, $rowArray);
} 

//array_push( $data, $my_query );
echo json_encode($data);
?>