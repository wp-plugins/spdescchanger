function spDescChanger(descriptions, duration){
	var newDuration = duration*1000;
	var description = descriptions[Math.floor(Math.random()*descriptions.length)]
	$('.description').hide();
	$('.description').html(description);
	$('.description').fadeIn(2000);
	window.setTimeout(function callFunc() { spDescChanger(descriptions, duration); }, newDuration);
} 