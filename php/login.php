<?php
include('./dbLink.php');
$mysqli = STmysqli();

if( $_POST['identity'] == "teacher" ){
    //防止sql injection
    $username = $mysqli->real_escape_string($_POST['username']);
    $password = $mysqli->real_escape_string($_POST['password']);

    $result = $mysqli->query("SELECT teacher_id, realname, activate FROM teacher WHERE username='$username' AND password='$password'");
    $row = $result->fetch_assoc();
    if( $row==NULL ){
        echo "帳號不存在 或 密碼錯誤";
    }else if( $row['activate'] == 0 ){
        echo "帳號尚未啟用，請等待審核";
    }else{
        echo "登入成功，即將啟動...";

        session_start();
        $_SESSION['id'] = $row['teacher_id'];
        $_SESSION['identity'] = $_POST['identity'];
        $_SESSION['realname'] = $row['realname'];
    }
}else if( $_POST['identity'] == "student" ){
    //防止sql injection
    $teacher_reference = $mysqli->real_escape_string($_POST['teacher_reference']);
    $student_username = $mysqli->real_escape_string($_POST['student_username']);
    $password = $mysqli->real_escape_string($_POST['password']);

    $my_query = "
        SELECT student.student_id, student.realname
        FROM(
            student NATURAL JOIN class
            JOIN teacher ON (class.teacher_id=teacher.teacher_id)
        )
        WHERE(
            student.username='$student_username'
            AND student.password='$password'
            AND teacher.username='$teacher_reference'
        )
    ";
    $result = $mysqli->query($my_query);

    $row = $result->fetch_assoc();
    if( $row==NULL ){
        echo "帳號不存在 或 密碼錯誤";
    }else{
        echo "登入成功，即將啟動...";

        session_start();
        $_SESSION['id'] = $row['student_id'];
        $_SESSION['identity'] = $_POST['identity'];
        $_SESSION['realname'] = $row['realname'];
    }
}
