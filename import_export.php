<script>
	var upload_success = function(){
		$("#loading").hide(500, function(){
			$("#upload_form").show(500);
			$("#success").show(500);
		})
	}

	var upload_fail = function(){
		$("#loading").hide(500, function(){
			$("#upload_form").show(500);
			$("#fail").show(500);
		})
	}

	$(document).ready(function(){
		$('#upload_form').submit(function(){
			$("#loading").show(500);
			$("#success").hide(500);
			$("#fail").hide(500);
			$("#upload_form").hide(500);
			return true;
		});
		
		$('#download_button').click(function(){
			$.post("php/lessonAPI.php", {}, 
				function(data){
					location.href = "data/" + data;
				});
		});
	});
</script>
<h2>匯入檔案</h2>
<form action='import.php' method='post' enctype="multipart/form-data" target="upload_target" id="upload_form" style="margin-top: 50px;">
	<input type="file" name="csv" value="" />
	<input type="submit" name="submit" value="儲存" />
</form>
<iframe id='upload_target' name='upload_target' src='#' style='width:0;height:0;border:0px solid #fff;'></iframe>
<div id="loading" style="display: none"><img src='images/loader.gif' alt='Loading...' /></div>
<div id="success" style="display: none; color: green; font-weight: bold;">匯入成功！</div>
<div id="fail" style="display: none; color: red; font-weight: bold;">上傳失敗！請檢查檔案格式或是資料庫狀況。</div>

<h2>匯出全部課程</h2>
<div class="button download_button" id="download_button">課程資料下載</div>