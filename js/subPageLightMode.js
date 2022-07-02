var d = new Date();
if (d.getHours() >= 6 && d.getHours() <= 18) 
{
    window.addEventListener("load", function() {
        document.getElementsByName("darkable")[0].style.backgroundColor = "#FFFFED";
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
        document.getElementById("darkable").style.backgroundColor = "#FFFFED";
    });
    window.addEventListener("load", function() {
        document.getElementsByName("rust")[0].style.backgroundColor = "#FFFFED";
    });
}