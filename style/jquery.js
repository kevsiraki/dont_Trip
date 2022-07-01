/* //scrapping this
var i = 0;
var dragging = false;
$('#dragbar').mousedown(function(e) {
    e.preventDefault();

    dragging = true;
    var main = $('#container');
    var dragbar = $("#dragbar");
    var ghostbar = $('<div>', {
        id: 'ghostbar',
        css: {
            height: dragbar.outerHeight(),
            width: dragbar.outerWidth(),
            top: main.offset().top,
            bottom: main.offset().bottom
        }
    }).appendTo('body');

    $(document).mousemove(function(e) {
        ghostbar.css("top", e.pageY + 2);
    });
});
$(document).mouseup(function(e) {
    if (dragging) {
        $('#map').css("height", e.pageY + 2);
        $('#container').css("top", e.pageY + 2);
        $('#ghostbar').remove();
        $(document).unbind('mousemove');
        dragging = false;
    }
});
*/
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