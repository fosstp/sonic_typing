<?php
include "./dbLink.php";
function fail(){
	header("Location: index.html");
}

//todo 防止sql injection
session_start();
if( !isset($_SESSION['identity']) || !isset($_SESSION['id']) ) fail();
if( $_SESSION['identity'] != "teacher" ) fail();

$mysqli = STmysqli();

if($_POST['control'] == "insert"){
	
	$mysqli->query("INSERT INTO word (lesson_id) VALUES ($_POST[lid]);");
	$result = $mysqli->query("SELECT * FROM word WHERE word_id = $mysqli->insert_id;");
	$row = $result->fetch_assoc();
	echo json_encode($row);
	
}else if($_POST['control'] == "delete"){
	if( $mysqli->query("DELETE FROM word WHERE word_id=$_POST[wid];") ){
		echo "OK";
	}else{
		echo $mysqli->error;
	}
}else if($_POST['control'] == "modifyContent"){
	$mysqli->query("UPDATE `typing`.`word` SET `content`='$_POST[content]' WHERE `word_id`=$_POST[wid];");
	echo $_POST['content'];
}else if($_POST['control'] == "modifyMeaning"){
	$mysqli->query("UPDATE `typing`.`word` SET `meaning`='$_POST[meaning]' WHERE `word_id`=$_POST[wid];");
	echo $_POST['meaning'];
}
?>