var channel_num = 10;

var sound = new Array();
for(i = 0;i < channel_num;i++)
	sound[i] = new Audio();

function playSound(url)
{
	i = 0;
	while(i < channel_num  &&  (!sound[i].paused && !sound[i].ended)) i++;
	if(i == channel_num) i = 0;
	//console.log("Sound file will play:"+url);
	url = encodeURI(url);
	sound[i].src = url;
	sound[i].load();
	sound[i].play();
}