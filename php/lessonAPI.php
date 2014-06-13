<?php

    session_start();
    if( !isset($_SESSION['identity']) || !isset($_SESSION['id']) ) header("Location: index.php");
    if( $_SESSION['identity'] != "teacher" ) header("Location: index.php");

    include 'dbLink.php';
    include 'SQLquery.php';
    $fp = fopen("../data/$_SESSION[id].csv", "w");
    $mysqli = STmysqli();
    if( isset($_POST["lesson_id"]) ){
        $result = $mysqli->query("SELECT * FROM lesson WHERE teacher_id = $_SESSION[id] AND lesson_id = $_POST[lesson_id]");
    } else{
        $result = $mysqli->query("SELECT * FROM lesson WHERE teacher_id = $_SESSION[id]");
    }
        // workaround for encoding issue
    fwrite($fp, "\xEF\xBB\xBF");
    while( $row = $result->fetch_assoc() ){
        fwrite($fp, "\"lesson\",\"$row[name]\"\n");
        $result_word = $mysqli->query("SELECT * FROM word WHERE lesson_id = $row[lesson_id]");
        while( $row_word = $result_word->fetch_assoc() ){
            fwrite($fp, "\"word\",\"$row_word[content]\",\"$row_word[meaning]\"\n");
        }
    }
    fclose($fp);

    echo "$_SESSION[id].csv";
