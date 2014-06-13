<?php
    include "onlineSoundDownloader.php";

    public function QueryMaking($dataname, $control, $mysqli = null,  $id = null, $colname = null, $content = null)
    {
        switch($control){
            case "insert":
                switch ($dataname) {
                    case 'class':
                        return "INSERT INTO class (teacher_id) VALUES (". $_SESSION['id'] . ")";
                        break;
                    case "student":
                        return "INSERT INTO student(class_id) VALUES (". $id . ")";
                        break;
                    case "lesson":
                        return "INSERT INTO lesson (teacher_id) VALUES (". $_SESSION['id']. ")";
                        break;
                    case "word":
                        onlineSoundDownload('NewWord', '../');
                        return "INSERT INTO word (lesson_id) VALUES (". $id. ")";
                        break;
                    case "teacher":
                        //not allowed
                        break;
                }
                break;

            case "view":
                switch($dataname){
                    case "class":
                        return "SELECT class_id, class_name FROM class WHERE teacher_id = ". $_SESSION['id'] ." ORDER BY `class_id` ASC";
                        break;
                    case "student":
                        return "SELECT student_id, username, password, seat_num, realname FROM student WHERE class_id = " .$id ." ORDER BY `seat_num` DESC";
                        break;
                    case "lesson":
                        return "SELECT lesson_id, name FROM lesson WHERE teacher_id = ". $_SESSION['id'] ." ORDER BY `lesson_id` ASC";
                        break;
                    case "word":
                        return "SELECT word_id, content, meaning, sound FROM word WHERE lesson_id = ". $id ." ORDER BY `word_id` ASC";
                        break;
                    case "teacher":
                        return "SELECT teacher_id, username, password, email, school, realname, activate FROM teacher ORDER BY `teacher_id` ASC";
                        break;
                }
                break;

            case "newInsert":
                switch($dataname){
                    case "class":
                        return "SELECT class_id, class_name FROM class WHERE class_id = ". $id;
                        break;
                    case "student":
                        return "SELECT student_id, username, password, seat_num, realname FROM student WHERE student_id = ". $id;
                        break;
                    case "lesson":
                        return "SELECT lesson_id, name FROM lesson WHERE lesson_id = ". $id;
                        break;
                    case "word":
                        return "SELECT word_id, content, meaning, sound FROM word WHERE word_id = ". $id;
                        break;
                    case "teacher":
                        //not allowed
                        break;
                }
                break;

            case "modify":
                switch ($dataname) {
                    case 'class':
                        return "UPDATE class SET " . $colname . " = '". $content ."' WHERE class_id =" . $id;
                        break;
                    case 'student':
                        return "UPDATE student SET " . $colname . " = '". $content ."' WHERE student_id =" . $id;
                        break;
                    case "lesson":
                        return "UPDATE lesson SET " . $colname . " = '". $content ."' WHERE lesson_id =" . $id;
                        break;
                    case "word":
                        if( $colname=="content" ){
                            onlineSoundDownload($content, '../');
                        }
                        return "UPDATE word SET " . $colname . " = '". $content ."' WHERE word_id =" . $id;
                        break;
                    case "teacher":
                        return "UPDATE teacher SET " . $colname . " = '". $content ."' WHERE teacher_id =" . $id;
                        break;
                }
                break;

            case "delete":
                switch ($dataname) {
                    case 'class':
                        return "DELETE FROM class WHERE class_id = " . $id . ";";
                        break;
                    case 'student':
                        return "DELETE FROM student WHERE student_id = " . $id . ";";
                        break;
                    case "lesson":
                        return "DELETE FROM lesson WHERE lesson_id = " . $id . ";";
                        break;
                    case "word":
                        return "DELETE FROM word WHERE word_id = " . $id . ";";
                        break;
                    case "teacher":
                        return "DELETE FROM teacher WHERE teacher_id = " . $id . ";";
                        break;
                }

        }

    }
