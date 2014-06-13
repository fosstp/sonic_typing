<!DOCTYPE html>
<?php
    session_start();
    if( !isset($_SESSION['identity']) || ($_SESSION['identity']!='admin' && !isset($_SESSION['id'])) ){
        header("Location: logout.php");
    }
    include('./php/website_metadata_getter.php');
?>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="css/main.css"/>
    <link rel="stylesheet" type="text/css" href="css/rmlist.css"/>
    <link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.css" type="text/css">
    <meta charset="utf-8" />
    <title>英速小子</title>
    <script type="text/javascript" src="./js/jquery-latest.min.js"></script>
    <script type="text/javascript" src="./js/jquery-ui.js"></script>
    <script type="text/javascript" src="./js/jQuery.getUrlVars.js"></script>
    <script type="text/javascript" src="./js/audio.js"></script>
    <script type="text/javascript" src="./js/jQuery.md5.js"></script>
    <?php
        if( $_SESSION['identity'] == "teacher" || $_SESSION["identity"] == "admin") {
            if( isset($_GET['lid']) ){
                echo "<script> var \$lid = " .  $_GET['lid'] . ";</script>";
            }else{
                echo "<script> var \$lid = 'undefined' </script>";
            }
            if( isset($_GET['p']) ){
                switch($_GET['p']){
                    case "lesson":
                    case "class":
                    case "admin_teacher":
                        echo "<script type='text/javascript' src='js/generalControl.js'></script>";
                        break;
                }
            }
        }
    ?>
</head>
<body>
    <div id="mainPage" class="page">
        <header>
            <?php
                $website_data = website_metadata_get('./php/');
                echo "<h1>".$website_data["websitename"]."</h1>";
            ?>
            <?php
            if( isset($_GET['p']) ){
                switch($_GET['p']){
                    case "lesson":
                        echo "<div class='page_title'>課程管理</div>";
                        break;
                    case "typing";
                        echo "<div class='page_title'>單字互動</div>";
                        break;
                    case "record";
                        echo "<div class='page_title'>互動記錄</div>";
                        break;
                    case "class":
                        echo "<div class='page_title'>班級管理</div>";
                        break;
                    case "imex":
                        echo "<div class='page_title'>匯入檔案</div>";
                        break;
                    case "admin_teacher":
                        echo "<div class='page_title'>教師管理</div>";
                        break;
                    case "website_config":
                        echo "<div class='page_title'>網站設定</div>";
                        break;
                    default:
                        echo "<div class='page_title'>大廳主頁</div>";
                        break;
                }
            }else{
                echo "<div class='page_title'>大廳主頁</div>";
            }
            ?>
            <hr>
        </header>
        <nav>
            <?php
                if( $_SESSION['identity'] == "teacher" ){
                    echo "<div id='identity'>$_SESSION[realname]　教師</div>";
                }else if( $_SESSION['identity'] == "student" ){
                    echo "<div id='identity'>$_SESSION[realname]　同學</div>";
                }else if( $_SESSION['identity'] == "admin" ){
                    echo "<div id='identity'>$website_data[realname]　站長</div>";
                }
            ?>
            <ul>
                <?php
                if( $_SESSION['identity'] == "admin" ){
                    ?>
                    <li><a href="./lobby.php">大廳主頁</a></li>
                    <li><a href="?p=website_config">網站設定</a></li>
                    <li><a href="?p=admin_teacher">教師管理</a></li>
                    <li><a href="logout.php">登出</a></li>
                    <?php
                }else if( $_SESSION['identity'] == "student" ){
                    ?>
                    <li><a href="./lobby.php">大廳主頁</a></li>
                    <li><a href="?p=typing">單字互動</a></li>
                    <li><a href="?p=record">互動記錄</a></li>
                    <li><a href="logout.php">登出</a></li>
                    <?php
                }else if( $_SESSION['identity'] == "teacher" ){
                    ?>
                    <li><a href="./lobby.php">大廳主頁</a></li>
                    <li><a href="?p=typing">單字互動</a></li>
                    <li><a href="?p=record">互動記錄</a></li>
                    <li><a href='?p=lesson'>課程管理</a></li>
                    <li><a href='?p=class'>班級管理</a></li>
                    <li><a href='?p=imex'>匯入檔案</a></li>
                    <li><a href="logout.php">登出</a></li>
                    <?php
                }
                ?>
            </ul>
        </nav>
        <article>
            <section>
            <?php
                if( isset($_GET['p']) ){
                    switch($_GET['p']){
                        case "lesson":
                            include "lesson.php";
                            break;
                        case "typing":
                            include "typing.php";
                            break;
                        case "class":
                            include "class.php";
                            break;
                        case "record":
                            include "record_page.php";
                            break;
                        case "imex":
                            include "import_export.php";
                            break;
                        case "admin_teacher":
                            include "admin_teacher.php";
                            break;
                        case "website_config":
                            include "website_config.php";
                            break;
                    }
                }else{
                    //大廳主頁內容
                }
            ?>
            </section>
        </article>
        <footer>--- Powered by RM Workshop ---</footer>
    </div>
</body>
</html>
