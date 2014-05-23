<?php
include 'php/dbLink.php';
if( $_SESSION['identity'] != "admin" ){
	header('Location: logout.php');
}
?>
<div class="RMList" 
	 data-name="teacher" 
	 data-title="教師"
	 data-deletable="1">
	<table>
		<tr>
			<th style="width: 50px">編號</th>
			<th style="width: 30px">刪除</th>
			<th style="width: 120px">教師帳號</th>
			<th style="width: 60px" data-editable="true" data-password="true">密碼</th>
			<th style="width: 150px" data-editable="true">E-MAIL</th>
			<th style="width: 120px" data-editable="true">學校</th>
			<th style="width: 100px" data-editable="true">姓名</th>
			<th style="width: 30px" data-checkable='true'>啟用</th>
		</tr>
		<tr class='empty_prompt'>
			<td colspan='8'>尚未有任何教師申請帳號</td>
		</tr>
	</table>
</div>