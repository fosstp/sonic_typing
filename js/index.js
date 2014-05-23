var login_begin = function(){
	$('#login_msg').removeClass('success_msg');
	$('#login_msg').removeClass('error_msg');
	$('#login_msg').html("登入中...");
}

var login_handler = function(rev){
	$('#login_msg').html(rev);
	if( rev=='登入成功，即將啟動...' ){
	    $('#login_msg').addClass('success_msg');
	    document.location.href = "./lobby.php";
	}else{
		$('#login_msg').addClass('error_msg');
	}
}

$(document).ready(
	function(){
		//[判斷是否老師帳號已被輸入]
		if( window.location.hash === '' ){
			//teacher page
			$('#teacher_login_tab_name').addClass('selected');
			$('#teacher_login_tab_content').addClass('selected');
		}else{
			//student page
			$('#student_login_tab_name').addClass('selected');
			$('#student_login_tab_content').addClass('selected');
			$('#student_form [name=teacher_reference]').val( window.location.hash.substring(1) );
		}
		
		//[tab select]
		$('.tab_name').click(
			function(element){
				$('#login_msg').html('');
				
				$('#teacher_login_tab_name').removeClass('selected');
				$('#student_login_tab_name').removeClass('selected');
				$('#teacher_login_tab_content').removeClass('selected');
				$('#student_login_tab_content').removeClass('selected');
				
				$(this).addClass('selected');
				if( element.target.id === 'teacher_login_tab_name' ){
					$('#teacher_login_tab_content').addClass('selected');
				}else if( element.target.id === 'student_login_tab_name' ){
					$('#student_login_tab_content').addClass('selected');
				}
			}
		);
		
		//[login]
		$('#teacher_form').submit(
			function(){
				//console.log(this);
				//console.log($('#teacher_form [name=password]').val());
				login_begin();
				
				$.post(
					"./php/login.php",
					{
						identity : "teacher",
						username : $('#teacher_form [name=username]').val(),
						password : $.md5($('#teacher_form [name=password]').val()),
					},
					login_handler
				);
				return false; //阻止form的action
			}
		);
		
		$('#student_form').submit(
			function(){
				login_begin();
				
				$.post(
					"./php/login.php",
					{
						identity : "student",
						teacher_reference : $('#student_form [name=teacher_reference]').val(),
						student_username : $('#student_form [name=student_username]').val(),
						password : $.md5($('#student_form [name=password]').val()),
					},
					login_handler
				);
				return false;
			}
		);
		$('#student_form [name=teacher_reference]').keyup(function(){
			//按上一頁不會重複讀
			location.replace('#'+$('#student_form [name=teacher_reference]').val());
		});
	}
);
