<?php
    session_start();
    if( !isset($_SESSION['identity']) || !isset($_SESSION['id']) ) header("Location: index.html");
    if( $_SESSION['identity'] != "teacher" ) header("Location: index.html");
    
    if($_POST['control'] == "insert"){
        $mysqli = new mysqli("localhost:3306", "root", "moe", "typing");
        $mysqli->query("INSERT INTO lesson (teacher_id) VALUES (". $_SESSION['id']. ");");
        $result = $mysqli->query("SELECT * FROM lesson WHERE lesson_id = ". $mysqli->insert_id .";");
        $row = $result->fetch_assoc();
        echo json_encode($row);
    }
    else if($_POST['control'] == "modify"){
        $mysqli = new mysqli("localhost:3306", "root", "moe", "typing");
        $mysqli->query("UPDATE `typing`.`lesson` SET `name` = '". $_POST['title'] ."' WHERE `lesson`.`lesson_id` =" . $_POST['id'] . ";");
        echo $_POST['title'];
    }
    else if($_POST['control'] == "delete"){
        $mysqli = new mysqli("localhost:3306", "root", "moe", "typing");
        if( $mysqli->query("DELETE FROM lesson WHERE lesson_id = " . $_POST['id'] . ";") ){
            echo "OK";
        }
        else{
            echo $mysqli->error;
        }
    }
?>