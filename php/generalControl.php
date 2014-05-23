<?php
	session_start();
	if( !(isset($_SESSION['identity']) && ($_SESSION['identity']=="teacher" || $_SESSION['identity']=="admin")) ) 
		header("Location: error.html");
	include 'dbLink.php';
	include 'SQLquery.php';
	$mysqli = STmysqli();
	$dataname = $_POST['dataname'];

	if($_POST['control'] == "insert"){
        $mysqli->query(QueryMaking($dataname, $_POST['control'], null, $_POST['id']));
		$id = $mysqli->insert_id;
		//[exception]
		if( $dataname=='student' ){
			$mysqli->query( "UPDATE student SET username='student$id' WHERE student_id=$id" );
		}
		//[data getback]
        $result = $mysqli->query(QueryMaking($dataname, "newInsert", $mysqli, $id));
        $row = $result->fetch_assoc();
        $data = array();
        foreach ($row as $key => $value) {
            array_push($data, $key);
            array_push($data, $value);
        }
        echo json_encode($data);
    }
    else if($_POST['control'] == "modify"){
		//[exception]
		if($dataname=='student' && $_POST['colname']=='username'){
			$result = $mysqli->query("SELECT student_id FROM student NATURAL JOIN class WHERE teacher_id=$_SESSION[id] AND username='$_POST[content]' AND student_id!=$_POST[id];");
			if( $result->num_rows > 0 ){
				$_POST['content'] = "帳號不可重複$_POST[id]";
			}
		}
		
		//[modify]
        $mysqli->query(QueryMaking($dataname, $_POST['control'], null, $_POST['id'], $_POST['colname'], $_POST['content'] ));
		//[data getback]
		echo $_POST['content'];
    }
    else if($_POST['control'] == "delete"){
        if( $mysqli->query(QueryMaking($dataname, $_POST['control'], null, $_POST['id'])) ){
            echo "OK";
        }
        else{
            echo $mysqli->error;
        }
    }
    else if($_POST['control'] == "view"){
    	$result = $mysqli->query(QueryMaking($dataname, $_POST['control'], null, $_POST['id']));
    	$data = array();
    	while( $row = $result->fetch_assoc() ){
            $rowArray = array();
            foreach ($row as $key => $value) {
                array_push($rowArray, $key);
                array_push($rowArray, $value);
            }
            array_push($data, $rowArray);
    	} 
    	echo json_encode($data);
    }

	
?>