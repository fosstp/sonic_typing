<?php
   // Edit upload location here
   $destination_path = "data/upload/";

   $result = "upload_error";

   $name = $_POST['dataname'] . "-" . $_POST['id'] . "-" . basename( $_FILES['myfile']['name']);
   $target_path = $destination_path . $name;

   if(move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path)) {
     $result = $target_path;
     unlink($_POST['oldfile']);
      include 'php/dbLink.php';
      include 'php/SQLquery.php';
      $mysqli = STmysqli();
     $mysqli->query(QueryMaking($_POST['dataname'], "modify", null, $_POST['id'], $_POST['colname'], $target_path));
   }

   sleep(1);
?>
<script language="javascript" type="text/javascript">window.top.window.stopUpload(<?php echo '"' . $result . '"'; ?>);</script>
