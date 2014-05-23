//
Array.prototype.shuffle = function() {
 	var len = this.length;
	var i = len;
	 while (i--) {
	 	var p = parseInt(Math.random()*len);
		var t = this[i];
  	this[i] = this[p];
  	this[p] = t;
 	}
};

//Timer
var cost_time=0;
var timeout_pointer;
var timer_is_on=0;

function timedCount()
{
	cost_time=cost_time+1;
	timeout_pointer=setTimeout(function(){timedCount()},1000);
}

function doTimer()
{
	if (!timer_is_on)
	{
		timer_is_on=1;
		timedCount();
	}
}

function stopCount()
{
	clearTimeout(timeout_pointer);
	timer_is_on=0;
}

function modeInitial(){
	if( typing_mode==="hint" || typing_mode==="test" )
		word_order.shuffle();
}

var word_now=0;

var change_content = function(){
	$('#typed').html( "" );
	$('#meaning').html( "" );
	if( word_now >= word_order.length ){
		//[打完]
		$('#sound_play').hide();
		
		//$( "#typing_result" ).show( 'explode', {}, 500 );
		$( "#typing_result" ).toggle( "drop", 600 );
		$('#typing').hide();
		$('#typed').hide();
		$('#meaning').hide();
		stopCount();
		$('#timer').html("共計 "+cost_time+" 秒");
		//$('#timer').show( "highlight", {}, 1000 );
		$('#timer').show();
		word_now += 1;
		
		//[上傳資料庫]
		if( identity === "student" ){
			$('#upload_msg').removeClass('success_msg');
			$('#upload_msg').removeClass('error_msg');
			$('#upload_msg').html('成績記錄中...');
			
			$.post(
				"./php/record.php",
				{
					"action":"upload",
					"lesson_id":lesson_id,
					"mode":typing_mode,
					"cost_time":cost_time,
				},function(rev){
					//console.log(rev);
					if( rev=='記錄成功，按《BackSpace》返回前頁' ){
						$('#upload_msg').addClass('success_msg');
						$('#upload_msg').html(rev);
					}else{
						$('#upload_msg').addClass('error_msg');
						$('#upload_msg').html('記錄失敗，請洽管理員');
					}
				}
			);
		}
		
		return;
	}
	if( typing_mode==="hint" || typing_mode==="recite" ){
		playSound(sounds[word_order[word_now]]);
	}
	$('#typing').html( contents[word_order[word_now]] ) ;
	$('#meaning').html( meanings[word_order[word_now]] ) ;
	
	word_now += 1;
	
	underline_initial();
	$('#typed_underline').width( $('#typed').width() );
}

var underline_initial = function(){
	offset = $('#typed').offset();
	offset.top += 5;
	offset.left += $('#typed').innerWidth() + $('#typed').outerWidth();
	$('#typed_underline').offset(offset);
}


$(document).ready(
	function(){
		modeInitial();
		change_content();
		doTimer();
		$('#sound_play').click(function(){
			playSound(sounds[word_order[word_now-1]]);
		});
	}
);

window.onkeypress = function(evt)
{	
	evt = evt || window.event;
	var charCode = evt.which || evt.keyCode || e.charCode;
	keyin = String.fromCharCode(charCode);
	//console.log("keyin="+keyin);
	
	if( (keyin==$('#typing').text()[0]) || (keyin==' ' && $('#typing').text().charCodeAt(0)==160) ){
		//空白 = &nbsp
		//[打對]
		
		//alert(keyin);
		var alphabet = $('#typing').text()[0];
		$('#typed').html( $('#typed').text()+"<span class='typed_span'>"+alphabet+"</span>" ) ;
		$('#typing').html( $('#typing').text().substring(1) ) ;
		
		//extends underline
		$('#typed_underline').width( $('#typed').width() );
		if( $('#typing').text().length==0 ){
			//[a word typed over]
			if( typing_mode==="test" ){
				playSound(sounds[word_order[word_now-1]]);
			}
			setTimeout(function(){
					change_content();
					$('#progression_bar').addClass('highlight');
					$( "#progression_bar" ).animate({
						width: (word_now-1)/word_order.length*100+'%',
						}
						, 500
						, function() {
							// Animation complete.
							$('#progression_bar').removeClass('highlight');
						}
					);
				}
				,500
			);
		}
	}else{
		//[打錯]
		$( "#typing_area" ).css("border-color","red");
		$( "#typing_area" ).stop();
		$( "#typing_area" ).animate({
			'border-color':'black'
		  }, 500, function(){
			// Animation complete.
		});
	}
	
	if( keyin == ' ' ){
		return false;
	}
};


