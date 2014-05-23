var bool_check_password = false;

var Delay = function(){
  var timer = 0;
  this.execute = function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
};

$(document).ready(function(){
	$('#config_form').attr("autocomplete", "off");
	var passwordCheckDelay = (new Delay()).execute;
	//[check password]
	$('#config_form [name=password2]').keyup(function(){
		passwordCheckDelay(
			function(){
				$('#check_pw_status').removeClass('checked');
				$('#check_pw_status').removeClass('warning');
				$('#check_pw_status').addClass('loading');
				
				$('#register_msg').removeClass('error_msg');
				$('#register_msg').removeClass('success_msg');
				if( $('#config_form [name=password1]').val() === $('#config_form [name=password2]').val() ){
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
	//[submit]
	$('#config_form').submit(function(){
		console.log("orz");
		
		
		$('#config_msg').removeClass('error_msg');
		$('#config_msg').removeClass('success_msg');
		
		$.post(
			"./php/website_config_submit.php",
			{
				adminname : $('#config_form [name=adminname]').val(),
				password : $('#config_form [name=password1]').val()=='' ? '':$.md5($('#config_form [name=password1]').val()),
				realname : $('#config_form [name=realname]').val(),
				websitename: $('#config_form [name=websitename]').val()
			},
			function(rev){
				if( rev=="OK" ){
					$('#config_msg').addClass('success_msg');
					$('#config_msg').html('修改成功');
					setTimeout(
						function(){window.location.reload();},
						1000
					);
				}else if( rev=="NO" ){
					$('#config_msg').addClass('error_msg');
					$('#config_msg').html('檔案錯誤');
				}
			}
		);
		
		return false;
	});
});