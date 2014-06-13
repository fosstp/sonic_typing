<?php
    session_start();
    if( !isset($_SESSION['identity']) || !isset($_SESSION['id']) ) header("Location: index.html");
    if( $_SESSION['identity'] != "teacher" ) header("Location: index.html");

    include 'dbLink.php';
    include 'SQLquery.php';
    $mysqli = STmysqli();

    if($_POST['control'] == "Add"){
        $result = $mysqli->query("INSERT INTO lesson_class_allow(lesson_id, class_id) VALUES ($_POST[lesson_id], $_POST[class_id])");
        if( $result == NULL ){
            echo $mysqli->error;
        } else{
            echo "OK";
        }
    } else if($_POST['control'] == "Delete"){
        $result = $mysqli->query("DELETE FROM lesson_class_allow WHERE lesson_id = $_POST[lesson_id] AND class_id = $_POST[class_id]");
        if( $result == NULL ){
            echo $mysqli->error;
        } else{
            echo "OK";
        }
    }
