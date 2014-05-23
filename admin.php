<!DOCTYPE html>
<head>
	<title>英速小子</title>
	<meta charset="utf-8">
</head>
<body>
<?php
	include('./php/website_metadata_getter.php');
	$website_data = website_metadata_get('./php/');
	
	if( $_POST['username']==$website_data['adminname'] && md5($_POST['password'])==$website_data['password'] ){
		session_start();
		$_SESSION['realname'] = $website_data['realname'];
		$_SESSION['identity'] = 'admin';
		header('Location: lobby.php');
	}else{
		?>
		<script>
			alert("帳號 或 密碼錯誤");
			document.location.href = "./admin.html";
		</script>
		<?php
	}
?>
</body>