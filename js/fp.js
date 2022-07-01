const d = new Date();
console.log(d);
if (d.getHours() >= 6 && d.getHours() <= 18) {
    window.addEventListener("load", function() {
        document.body.style.backgroundColor = "#FFFFED";
    });
    window.addEventListener("load", function() {
        document.documentElement.style.backgroundColor = "#FFFFED";
    });
}