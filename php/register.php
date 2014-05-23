<?php
	include('./dbLink.php');
	$mysqli = STmysqli();
	
	if( $_POST['action'] == "checkUsername" ){
		$username = $mysqli->real_escape_string($_POST['username']);
		$result = $mysqli->query("SELECT username FROM teacher WHERE username='$username'");
		$row = $result->fetch_assoc();
		
		if( $username == '' ){
			echo "EMPTY";
		}else if( $row == NULL ){
			echo "OK";
		}else{
			echo "REPEAT";
		}
	}else if($_POST['action'] == "register"){
		$username = $mysqli->real_escape_string($_POST['username']);
		$password = $mysqli->real_escape_string($_POST['password']);
		$realname = $mysqli->real_escape_string($_POST['realname']);
		$school = $mysqli->real_escape_string($_POST['school']);
		$email = $mysqli->real_escape_string($_POST['email']);
		
		$result = $mysqli->query("INSERT INTO `teacher` (`username`, `password`, `email`, `school`, `realname`) VALUES ('$username', '$password', '$email', '$school', '$realname');");
		
		if( $result == 0 ){
			echo "NO";
		}else{
			echo "OK";
		}
	}
?>