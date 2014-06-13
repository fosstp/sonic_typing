<?php
function typingInclude()
{
    include "./php/dbLink.php";
    include "./php/onlineSoundDownloader.php";
    ?>
    <script type='text/javascript' src='./js/jquery-latest.min.js'></script>
    <script type='text/javascript' src='./js/jquery-ui.js'></script>
    <script type='text/javascript' src='./js/audio.js'></script>
    <script type='text/javascript' src='./js/typing.js'></script>
    <link rel='stylesheet' type='text/css' href='./css/jquery-ui-1.10.3.custom.css'/>
    <link rel='stylesheet' type='text/css' href='./css/typing.css'/>
    <?php
}

function get_words()
{
    $mysqli = STmysqli();
    if( $_SESSION['identity']=='teacher' ){
        return $mysqli->query( "SELECT word_id, content, meaning, sound FROM (word NATURAL JOIN lesson) WHERE (lesson_id=$_GET[lid] AND teacher_id=$_SESSION[id]) ORDER BY word_id ASC" );
    }else if( $_SESSION['identity']=='student' ){
        return $mysqli->query( "SELECT word_id, content, meaning, sound FROM (word NATURAL JOIN lesson NATURAL JOIN lesson_class_allow NATURAL JOIN student) WHERE (lesson_id=$_GET[lid] AND student_id=$_SESSION[id]) ORDER BY word_id ASC" );
    }
}

//[課程列表]
function PageTypingLessonList()
{
    include "./php/dbLink.php";
    $mysqli = STmysqli();

    echo "
<table style='margin-top:10px;'>
<tr id='lessonHeader'>
    <th style='width: 50px'>課號</th>
";
    if( $_SESSION['identity']=='teacher' ){
        echo "<th style='width: 50px; padding: 5px 1px 5px 1px;'>編輯</th>";
    }else if( $_SESSION['identity']=='student' ){
        echo "<th style='width: 50px; padding: 5px 1px 5px 1px;'>瀏覽</th>";
    }
    echo "
    <th style='width: 80px; padding: 5px 1px 5px 1px;'>背書版</th>
    <th style='width: 80px; padding: 5px 1px 5px 1px;'>提示版</th>
    <th style='width: 80px; padding: 5px 1px 5px 1px;'>測驗版</th>
    <th style='width: 500px'>單元名稱</th>
</tr>
";
    if( $_SESSION['identity']=='teacher' ){
        $result = $mysqli->query( "SELECT lesson_id, name FROM lesson WHERE teacher_id=$_SESSION[id] ORDER BY lesson_id DESC" );
    }else if( $_SESSION['identity']=='student' ){
        $result = $mysqli->query( "SELECT lesson_id, name FROM (student NATURAL JOIN lesson_class_allow NATURAL JOIN lesson) WHERE student_id=$_SESSION[id] ORDER BY lesson_id DESC" );
    }

    if( $result==NULL || $result->num_rows == 0 ){
        echo "<tr><td colspan='6'>目前尚未開設課程</td></tr>";
    }else{
        while( $row = $result->fetch_assoc() ){
            echo "<tr>";
            echo "<td>$row[lesson_id]</td>";
            if( $_SESSION['identity']=='teacher' ){
                echo "<td><a href='./lobby.php?p=lesson&lid=$row[lesson_id]'><div class='edit_button lesson_edit_button'></div></a></td>";
            }else if( $_SESSION['identity']=='student' ){
                echo "<td><a href='./lobby.php?p=typing&lid=$row[lesson_id]&mode=view'><div class='browse_button'></div></a></td>";
            }
            echo "<td><a href='?p=typing&lid=$row[lesson_id]&mode=recite'><span class='play_button'></span></a></td>";
            echo "<td><a href='?p=typing&lid=$row[lesson_id]&mode=hint'><div class='play_button'></div></a></td>";
            echo "<td><a href='?p=typing&lid=$row[lesson_id]&mode=test'><div class='play_button'></div></a></td>";
            echo "<td style='text-align: left'>" . $row['name'] ."</td>";
            echo "</tr>";
        }
    }
    echo "
</table>
";
}

//[單一課程內容]
function PageTypingLessonView()
{
    if( $_SESSION['identity']=='student' ){
        typingInclude();
        echo "<script type='text/javascript' src='./js/TypingView.js'></script>";
        $mysqli = STmysqli();
        $lesson_result = $mysqli->query( "SELECT lesson_id, name FROM lesson WHERE lesson_id=$_GET[lid]" );
        $lesson = $lesson_result->fetch_assoc();
        $word_result = get_words();

        if($word_result==NULL || $lesson_result==NULL || $word_result->num_rows==0 || $lesson_result->num_rows==0){
            echo "<div class='error_msg'>瀏覽權限不符 或 此課程尚無內容</div>";
        }else{
            echo "
<div class='lesson_name'><span>$lesson[name]</span></div>
<div style='text-align:right; margin-right:50px;'>單字數:<span id='word_count'>$word_result->num_rows</span></div>

<table style='margin-top:10px;'>
<tr>
<th style='width:50px'>編號</th>
<th style='width:200px'>英文</th>
<th style='width:150px'>中文</th>
<th style='width:150px'>發音</th>
</tr>
";
            while( $row = $word_result->fetch_assoc() ){
                echo "<tr>
                <td>$row[word_id]</td>
                <td>$row[content]</td>
                <td>$row[meaning]</td>
                <td><div class='sound_button' data-sound='$row[sound]'></div></td>
                </tr>";
            }
            echo "
</table>
";
        }
    }else if( $_SESSION['identity']=='teacher' ){
        //PageTypingLessonList已經讓教師直接跑到編輯課程
        //教師進不來這個地方
    }
}



//[玩打字]
function PageTypingPlayer()
{
/*
共通:
    發音鈕
    記錄上傳資料庫

背單字版:
    五次
    照順序
    先發音

提示版:
    一次
    不照順序
    先發音

測驗版:
    一次
    不照順序
    不顯示還沒打的字
    打完才發音
*/
    typingInclude();

    $mysqli = STmysqli();
    $lesson_result = $mysqli->query( "SELECT lesson_id, name FROM lesson WHERE lesson_id=$_GET[lid]" );
    $lesson = $lesson_result->fetch_assoc();
    $word_result = get_words();

    if($word_result==NULL || $lesson_result==NULL || $word_result->num_rows==0 || $lesson_result->num_rows==0){
        echo "<div class='error_msg'>瀏覽權限不符 或 此課程尚無內容</div>";
    }else{
        echo "
    <script>
    //從資料庫讀入單字
    var identity = '$_SESSION[identity]';
    var typing_mode = '$_GET[mode]';
    var lesson_id = $_GET[lid];

    var word_order = [];
    var contents = [];
    var meanings = [];
    var sounds = [];
    ";
        $word_count = 0;
        while( $row = $word_result->fetch_assoc() ){
            if( $_GET['mode'] == 'recite' ){
                for( $i=0 ; $i<5 ; $i+=1 )
                    echo "word_order.push($word_count);";
            }else{
                echo "word_order.push($word_count);";
            }
            echo "contents.push('$row[content]');";
            echo "meanings.push('$row[meaning]');";
            if( $row['sound'] != '電腦發音' ){
                echo "sounds.push('$row[sound]');";
            }else{
                $filepath = onlineSoundDownload($row['content']);
                echo "sounds.push('$filepath');";
            }
            $word_count += 1;
        }
        echo "
    </script>
";



        echo "<div class='typing_page'>";
        echo "<div class='lesson_name'><span>$lesson[name]</span></div>";
        if( $_GET['mode'] == 'recite' ){
            echo "<div id='mode'>背書版</div>";
        }else if( $_GET['mode'] == 'hint' ){
            echo "<div id='mode'>提示版</div>";
        }else if( $_GET['mode'] == 'test' ){
            echo "<div id='mode'>測驗版</div>";
        }else{
            echo "<div id='mode'>黑板</div>";
        }
        echo "
        <div id='typing_area'>
            <div id='typing_result'>Finish</div>
";
        if( $_GET['mode'] == 'test' ){
            echo "<div class='line' id='typed'></div><div class='line invisible unselectable' id='typing'>error</div>";
        } else{
            echo "<div class='line' id='typed'></div><div class='line' id='typing'>error</div>";
        }
        echo "
            <div id='typed_underline'></div>
            <div id='meaning'></div>
            <div id='sound_play' class='sound_button'></div>
            <div id='timer'></div>
            <div id='progression_bar'></div>
        </div>
        <div class='msg' id='upload_msg'></div>
    </div>
    ";
    }
}

//[main]
if( !isset($_GET['lid']) ){
    PageTypingLessonList();
}else{
    if( $_GET['mode'] == 'view' ){
        PageTypingLessonView();
    }else{
        PageTypingPlayer();
    }
}
?>
