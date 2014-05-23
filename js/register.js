var bool_check_username = false;
var bool_check_password = false;
var bool_check_email = false;

var Delay = function(){
  var timer = 0;
  this.execute = function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
};

var isEmail = function (email){
  var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

$(document).ready(function(){
	var usernameCheckDelay = (new Delay()).execute;
	var passwordCheckDelay = (new Delay()).execute;
	var emailCheckDelay = (new Delay()).execute;
	
	//[readme]
	$('#readme_button').click(function(){
		$( "#readme_page" ).dialog({
			modal: true,
			width: 640,
			height: 480
		});
	});
	//[check username]
	$('#register_form [name=username]').keyup(function(){
		usernameCheckDelay(
			function(){
				$('#check_username_status').removeClass('checked');
				$('#check_username_status').removeClass('warning');
				$('#check_username_status').addClass('loading');
				
				$.post(
					"./php/register.php",
					{
						action : "checkUsername",
						username : $('#register_form [name=username]').val(),
					},
					function(rev){
						$('#check_username_status').removeClass('loading');
						$('#register_msg').removeClass('error_msg');
						$('#register_msg').removeClass('success_msg');
						if( rev=="OK" ){
							$('#check_username_status').addClass('checked');
							$('#register_msg').html('');
							bool_check_username = true;
						}else if( rev=="EMPTY" ){
							$('#check_username_status').addClass('warning');
							$('#register_msg').addClass('error_msg');
							$('#register_msg').html('使用者帳號不可為空白');
							bool_check_username = false;
						}else if( rev=="REPEAT" ){
							$('#check_username_status').addClass('warning');
							$('#register_msg').addClass('error_msg');
							$('#register_msg').html('使用者帳號重複');
							bool_check_username = false;
						}
					}
				);
			}
			, 1000
		);
	});
	//[check password]
	$('#register_form [name=password2]').keyup(function(){
		passwordCheckDelay(
			function(){
				$('#check_pw_status').removeClass('checked');
				$('#check_pw_status').removeClass('warning');
				$('#check_pw_status').addClass('loading');
				
				$('#register_msg').removeClass('error_msg');
				$('#register_msg').removeClass('success_msg');
				if( $('#register_form [name=password1]').val() === $('#register_form [name=password2]').val() ){
					$('#check_pw_status').removeClass('loading');
					$('#check_pw_status').addClass('checked');
					$('#register_msg').html('');
					bool_check_password = true;
				}else{
					$('#check_pw_status').removeClass('loading');
					$('#check_pw_status').addClass('warning');
					$('#register_msg').addClass('error_msg');
					$('#register_msg').html('兩次密碼輸入不一致');
					bool_check_password = false;
				}
			}
			, 1000
		);
	});
	//[check e-mail]
	$('#register_form [name=email]').keyup(function(){
		emailCheckDelay(
			function(){
				$('#check_email_status').removeClass('checked');
				$('#check_email_status').removeClass('warning');
				$('#check_email_status').addClass('loading');
				$('#register_msg').removeClass('error_msg');
				$('#register_msg').removeClass('success_msg');
				if( isEmail( $('#register_form [name=email]').val() ) ){
					$('#check_email_status').addClass('checked');
					$('#register_msg').html('');
					bool_check_email = true;
				}else{
					$('#check_email_status').addClass('warning');
					$('#register_msg').addClass('error_msg');
					$('#register_msg').html('E-MAIL格式錯誤');
					bool_check_email = false;
				}
			}
			, 1000
		);
	});
	//[submit]
	$('#register_form').submit(function(){
		$('#register_msg').removeClass('error_msg');
		$('#register_msg').removeClass('success_msg');
		
		if( $('#register_form [name=username]').val() == ''
			|| $('#register_form [name=password1]').val() == ''
			|| $('#register_form [name=password2]').val() == ''
			|| $('#register_form [name=realname]').val() == ''
			|| $('#register_form [name=school]').val() == ''
			|| $('#register_form [name=email]').val() == '' )
		{
			$('#register_msg').addClass('error_msg');
			$('#register_msg').html('請填完所有欄位');
		}else if( !(bool_check_username && bool_check_password && bool_check_email) ){
			$('#register_msg').addClass('error_msg');
			$('#register_msg').html('請確保沒有警告標誌');
		}else{
			$('#register_msg').html('註冊中...');
			$.post(
				"./php/register.php",
				{
					action : "register",
					username : $('#register_form [name=username]').val(),
					password : $.md5($('#register_form [name=password1]').val()),
					realname: $('#register_form [name=realname]').val(),
					school: $('#register_form [name=school]').val(),
					email: $('#register_form [name=email]').val(),
				},
				function(rev){
					if( rev=="OK" ){
						$('#register_msg').addClass('success_msg');
						$('#register_msg').html('註冊成功，請等待管理員審核');
						$( "#register_success_page" ).dialog({
							modal: true,
							close: function(){document.location.href = "index.html";}
						});
					}else if( rev=="NO" ){
						$('#register_msg').addClass('error_msg');
						$('#register_msg').html('資料庫錯誤');
					}
				}
			);
		}
		return false;
	});
});