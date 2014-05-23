<?php
   // Edit upload location here
   $destination_path = "./voice/";

   $result = "upload_error";
   
   $target_path = $destination_path . $_POST['dataname'] . "-" . $_POST['id'] . basename($_FILES["myfile"]["name"]);

   echo basename($_FILES["myfile"]["name"]);

   if(@move_uploaded_file($_FILES["myfile"]["tmp_name"], $target_path)) {
      $result = $target_path;

	  include 'dbLink.php';
	  include 'SQLquery.php';
	  $mysqli = STmysqli();
	  $mysqli->query(QueryMaking($_POST['dataname'], "modify", null, $_POST['id'], $_POST['colname'], $target_path));
   }
   
   

   sleep(1);
?>

<script language="javascript" type="text/javascript">window.top.window.stopUpload(<?php echo "\"" . $result . "\""; ?>);</script>   
