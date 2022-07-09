var d = new Date();
if (d.getHours() >= 6 && d.getHours() <= 18) 
{
    window.addEventListener("load", function() {
        document.getElementsByName("darkable")[0].style.backgroundColor = "#FFFFED";
    });
	window.addEventListener("load", function() {
        document.getElementsByName("keywords")[0].style.color = "#000000";
    });
    window.addEventListener("load", function() {
        document.getElementById("russ").style.backgroundColor = "#FFFFED";
    });
    window.addEventListener("load", function() {
        document.body.style.backgroundColor = "#FFFFED";
    });
    window.addEventListener("load", function() {
        document.getElementById("sidebar").style.backgroundColor = "#FFFFED";
    });
	window.addEventListener("load", function() {
        document.getElementById("darkable").style.color = "#000000";
    });
	window.addEventListener("load", function() {
        document.getElementById("underline").style.color = "#000000";
    });
    window.addEventListener("load", function() {
        document.getElementsByName("rust")[0].style.backgroundColor = "#FFFFED";
    });
}