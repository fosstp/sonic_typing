<head>
    <meta charset="utf-8" />
</head>

<?php

function removeBOM($str = '')
{
    if (substr($str, 0,3) == pack("CCC",0xef,0xbb,0xbf)) {
        $str = substr($str, 3);
    }
    return $str;
}

function __fgetcsv(&$handle, $length = null, $d = ",", $e = '"')
{
    $d = preg_quote($d);
    $e = preg_quote($e);
    $_line = "";
    $eof=false;
    while ($eof != true) {
        $temp = (empty ($length) ? fgets($handle) : fgets($handle, $length));

        $res = mb_detect_encoding($temp, array('UTF-8','big5'));
        switch($res){
            case 'UTF-8':
                $_line .= removeBOM($temp);
                break;
            case 'big5':
                $_line .= iconv("big5","UTF-8",$temp);
                break;
        }

        $itemcnt = preg_match_all('/' . $e . '/', $_line, $dummy);
        if ($itemcnt % 2 == 0)
            $eof = true;
    }
   $_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));

    $_csv_pattern = '/(' . $e . '[^' . $e . ']*(?:' . $e . $e . '[^' . $e . ']*)*' . $e . '|[^' . $d . ']*)' . $d . '/';
    preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
    $_csv_data = $_csv_matches[1];

    for ($_csv_i = 0; $_csv_i < count($_csv_data); $_csv_i++) {
        $_csv_data[$_csv_i] = preg_replace("/^" . $e . "(.*)" . $e . "$/s", "$1", $_csv_data[$_csv_i]);
        $_csv_data[$_csv_i] = str_replace($e . $e, $e, $_csv_data[$_csv_i]);
    }
    return empty ($_line) ? false : $_csv_data;
}


session_start();
if( !isset($_SESSION['identity']) || !isset($_SESSION['id']) ) header("Location: index.html");
if( $_SESSION['identity'] != "teacher" ) header("Location: index.html");

include 'php/dbLink.php';
$mysqli = STmysqli();
$csv = array();

// check there are no errors
if($_FILES['csv']['error'] == 0){
    $name = $_FILES['csv']['name'];
    $ext = strtolower(end(explode('.', $_FILES['csv']['name'])));
    $type = $_FILES['csv']['type'];
    $tmpName = $_FILES['csv']['tmp_name'];

    // check the file is a csv
    if(true || $ext === 'csv'){
        if(($handle = fopen($tmpName, 'r')) !== FALSE) {
            // necessary if a large csv file
            set_time_limit(0);

            $lesson_id = 0;
            $class_id = 0;
            $fail = false;

            $mysqli->query("START TRANSACTION;");
            while(($data = __fgetcsv($handle)) !== FALSE) {
                if( $data[0][0] != "#" && $data[0][0] != ""){
                    if( $data[0] == "lesson" ){
                        $data[1] = preg_replace("/ /","&nbsp;", $data[1]);
                        $mysqli->query("INSERT INTO lesson(name, teacher_id) VALUES('$data[1]', $_SESSION[id]);");
                        $lesson_id = $mysqli->insert_id;
                    } else if($data[0] == "word"){
                        if($lesson_id == 0){
                            $fail = true;
                            break;
                        }
                        $data[1] = preg_replace("/ /","&nbsp;", $data[1]);
                        $data[2] = preg_replace("/ /","&nbsp;", $data[2]);
                        $mysqli->query("INSERT INTO word(lesson_id, content, meaning) VALUES($lesson_id, '$data[1]', '$data[2]');");
                    } else if($data[0] == "class"){
                        $data[1] = preg_replace("/ /","&nbsp;", $data[1]);
                        $mysqli->query("INSERT INTO class(class_name, teacher_id) VALUES('$data[1]', $_SESSION[id]);");
                        $class_id = $mysqli->insert_id;
                    } else if($data[0] == "student"){
                        if($class_id == 0){
                            $fail = true;
                            break;
                        }
                        $data[1] = preg_replace("/ /","&nbsp;", $data[1]);
                        $data[4] = preg_replace("/ /","&nbsp;", $data[4]);
                        $mysqli->query("INSERT INTO student(username, password, seat_num, realname, class_id) VALUES('$data[1]','".md5($data[2])."', '$data[3]', '$data[4]', $class_id);");
                    } else if($data[0] == "lessonREF"){
                        $lesson_id = $data[1];
                        $result = $mysqli->query("SELECT * FROM lesson WHERE lesson_id = $lesson_id AND teacher_id = $_SESSION[id]");
                        if($result->num_rows <= 0){
                            $fail = true;
                            break;
                        }
                    } else if($data[0] == "classREF"){
                        $class_id = $data[1];
                        $result = $mysqli->query("SELECT * FROM class WHERE class_id = $class_id AND teacher_id = $_SESSION[id]");
                        if($result->num_rows <= 0){
                            $fail = true;
                            break;
                        }
                    } else{
                        $fail = true;
                        break;
                    }
                }

            }
            fclose($handle);
            echo $fail;
            if( $fail ){
                $mysqli->query("ROLLBACK;");
                ?>
                    <script language="javascript" type="text/javascript">window.top.window.upload_fail();</script>
                <?php
            } else{
                $mysqli->query("COMMIT;");
            ?>
                <script language="javascript" type="text/javascript">window.top.window.upload_success();</script>
            <?php
            }
        } else{
            ?>
                <script language="javascript" type="text/javascript">window.top.window.upload_fail();</script>
            <?php
        }

    } else{
        ?>
            <script language="javascript" type="text/javascript">window.top.window.upload_fail();</script>
        <?php
    }
}

?>
