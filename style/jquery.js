var set = 0;
document.getElementById("dragbar").addEventListener('click', function handleClick()  {
	if(set==0) {
		document.getElementById("map").style.height = "88%";
		set = 1;
	} else if(set==1) {
		document.getElementById("map").style.height = "50%";
		set = 0;
	}
});