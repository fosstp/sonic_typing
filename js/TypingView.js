$(document).ready(
	function(){
	}
);

$(document).on('click', '.sound_button',function (){
	playSound($(this).attr('data-sound'));
})