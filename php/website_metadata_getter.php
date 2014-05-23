<?php
	function website_metadata_get($relative_path='./'){
		$filepath = $relative_path . './website_metadata';
		if( file_exists($filepath) ){
			$fp = fopen($filepath, 'r');
			$contents = fread($fp, filesize($filepath));
			fclose($fp);
			
			$website_data = json_decode($contents, true);
		}else{
			$website_data = array(
				"adminname" => 'sonic',
				"password" => md5('typing'),
				"realname" => '英速子',
				"websitename" => '英速小子 Sonic Typing',
			);
		}
		return $website_data;
	}
?>