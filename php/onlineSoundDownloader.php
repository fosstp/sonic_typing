<?php
	/*
	//回傳下載好的file路徑
	function onlineSoundDownload($word, $relative_path='./'){
		//[google撈沒上傳發音的單字]
		// Convert Words (text) to Speech (MP3)
		// ------------------------------------
		// Google Translate API cannot handle strings > 100 characters
		$word = substr($word, 0, 100);
		// Replace the non-alphanumeric characters 
		// The spaces in the sentence are replaced with the Plus symbol
		$word = urlencode($word);
		// Save the MP3 file in this folder with the .mp3 extension 
		$filepath = $relative_path . "audio/" . $word . ".mp3";
		// If the MP3 file exists, do not create a new request
		if (!file_exists($filepath)) {
			$mp3 = file_get_contents("http://translate.google.com/translate_tts?ie=UTF-8&q=$word&tl=en-us");
			file_put_contents($filepath, $mp3);
		}
		
		return $filepath;
	}
	*/
	//回傳下載好的file路徑
	function onlineSoundDownload($word, $relative_path='./'){
		//API http://www.voicerss.org/api/documentation.aspx
		$word = substr($word, 0, 100);
		$word = str_replace("&nbsp;"," ",$word);
		//file_put_contents($relative_path."audio/"."debug0.txt", "$word");
		$word = html_entity_decode($word);
		// Replace the non-alphanumeric characters 
		// The spaces in the sentence are replaced with the Plus symbol
		//file_put_contents($relative_path."audio/"."debug.txt", "$word");
		//$word = urlencode($word);
		$word = rawurlencode($word);
		//file_put_contents($relative_path."audio/"."debug2.txt", "$word");
		//file_put_contents($relative_path."audio/"."debug2.txt", rawurlencode('New Word'));
		
		// Save the MP3 file in this folder with the .mp3 extension 
		$filepath = $relative_path . "audio/" . $word . ".mp3";
		// If the MP3 file exists, do not create a new request
		if (!file_exists($filepath)) {
			$key = "CHANGEMYAPIKEY";
			$hl = "en-us";
			$c = "MP3";
			$f = "12khz_16bit_stereo";
			$r = "-2";
			$file_content = file_get_contents("http://api.voicerss.org/?key=$key&hl=$hl&c=$c&f=$f&r=$r&src=$word");
			file_put_contents($filepath, $file_content);
		}
		return $filepath;
	}
?>
