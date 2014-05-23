var bool_check_password = false;

var Delay = function(){
  var timer = 0;
  this.execute = function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
};

$(document).ready(function(){
	var passwordCheckDelay = (new Delay()).execute;
	
	//[readme]
	$('#readme_button').click(function(){
		$( "#readme_page" ).dialog({
			modal: true,
			width: 640,
			height: 480
		});
	});
	//[check password]
	$('#setup_form [name=password2]').keyup(function(){
		passwordCheckDelay(
			function(){
				$('#check_pw_status').removeClass('checked');
				$('#check_pw_status').removeClass('warning');
				$('#check_pw_status').addClass('loading');
				
				$('#setup_msg').removeClass('error_msg');
				$('#setup_msg').removeClass('success_msg');
				if( $('#setup_form [name=password1]').val() === $('#setup_form [name=password2]').val() ){
					$('#check_pw_status').removeClass('loading');
					$('#check_pw_status').addClass('checked');
					$('#setup_msg').html('');
					bool_check_password = true;
				}else{
					$('#check_pw_status').removeClass('loading');
					$('#check_pw_status').addClass('warning');
					$('#setup_msg').addClass('error_msg');
					$('#setup_msg').html('兩次密碼輸入不一致');
					bool_check_password = false;
				}
			}
			, 1000
		);
	});
	/*todo
	//[submit]
	$('#setup_form').submit(function(){
		$('#setup_msg').removeClass('error_msg');
		$('#setup_msg').removeClass('success_msg');
		
		if( $('#setup_form [name=username]').val() == ''
			|| $('#setup_form [name=password1]').val() == ''
			|| $('#setup_form [name=password2]').val() == ''
			|| $('#setup_form [name=realname]').val() == ''
			|| $('#setup_form [name=school]').val() == ''
			|| $('#setup_form [name=email]').val() == '' )
		{
			$('#setup_msg').addClass('error_msg');
			$('#setup_msg').html('請填完所有欄位');
		}else if( !(bool_check_username && bool_check_password && bool_check_email) ){
			$('#setup_msg').addClass('error_msg');
			$('#setup_msg').html('請確保沒有警告標誌');
		}else{
			$('#setup_msg').html('註冊中...');
			$.post(
				"./php/setup.php",
				{
					action : "setup",
					username : $('#setup_form [name=username]').val(),
					password : $.md5($('#setup_form [name=password1]').val()),
					realname: $('#setup_form [name=realname]').val(),
					school: $('#setup_form [name=school]').val(),
					email: $('#setup_form [name=email]').val(),
				},
				function(rev){
					if( rev=="OK" ){
						$('#setup_msg').addClass('success_msg');
						$('#setup_msg').html('註冊成功，請等待管理員審核');
						$( "#setup_success_page" ).dialog({
							modal: true,
							close: function(){document.location.href = "index.html";}
						});
					}else if( rev=="NO" ){
						$('#setup_msg').addClass('error_msg');
						$('#setup_msg').html('資料庫錯誤');
					}
				}
			);
		}
		return false;
	});
	*/
});