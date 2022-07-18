function myFunction() {
    let x = document.getElementById("myLinks");
	let burger = document.getElementById("burger");
    if (x.style.display === "block") {
		burger.classList.remove("fa-close");
		burger.classList.add("fa-bars");
        x.style.display = "none";
    } else {
		burger.classList.remove("fa-bars");
		burger.classList.add("fa-close");
        x.style.display = "block";
    }
}