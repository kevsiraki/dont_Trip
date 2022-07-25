//Description:
//Append light styling during the daytime, nighttime/dark-mode CSS classes by default (sue me).
//Local Storage setting to check for manual dark/light mode along with automatic overrides.
function toggleDarkMode() {
    if (
        //custom localStorage setting
        localStorage.getItem("dark_mode") === "true"
        //Automatic mode
        ||
        (((new Date).getHours() < 6 || (new Date).getHours() > 18) && localStorage.getItem("dark_mode") === null)
    ) {
        localStorage.setItem("dark_mode", "false");
        location.reload();
    } else {
        localStorage.setItem("dark_mode", "true");
        location.reload();
    }
}

function resetDarkMode() {
    window.localStorage.removeItem('dark_mode');
    location.reload();
}
if (
    localStorage.getItem("dark_mode") === "false"
    //Automatic setting.
    ||
    ((new Date()).getHours() >= 6 && (new Date()).getHours() <= 18 && localStorage.getItem("dark_mode") === null)
) {
    window.addEventListener("DOMContentLoaded", function() {
		lightStyle();
    });
}

function lightStyle() {
    //DOM Elements
    if (document.body) {
        document.body.style.background = "#FFFFED";
    }
    if (document.body) {
        document.body.style.backgroundColor = "#FFFFED";
    }
    if (document.documentElement) {
        document.documentElement.style.background = "#FFFFED";
    }
    if (document.documentElement) {
        document.documentElement.style.backgroundColor = "#FFFFED";
    }
    if (document.getElementById('bg')) {
        document.getElementById('bg').style.backgroundColor = "#FFFFED";
    }
    //Text/Divs/Forms
    if (document.getElementById("info")) {
        document.getElementById("info").style.color = "#000000";
    }
    if (document.getElementsByClassName("form-control").length > 0&& !document.getElementById("log-in")) {
        $('link[href*="form_style.css"]').attr("disabled", "true");
        $('head').append('<link rel="stylesheet" href="../style/formLightInputs.css" type="text/css" />');
		$(document.documentElement).removeClass('hidden');
    }
    if (document.getElementById("log-in")) {
        $('link[href*="form_style.css"]').attr("disabled", "true");
        $('head').append('<link rel="stylesheet" href="style/formLightInputs.css" type="text/css" />');
    }
    if (document.getElementById("info-two")) {
        document.getElementById("info-two").style.color = "#000000";
    }
    if (document.getElementById("info-bar")) {
        document.getElementById("info-bar").style.color = "#000000";
    }
    if (document.getElementsByClassName('wrapper')[0]) {
        document.getElementsByClassName('wrapper')[0].style.backgroundImage = "linear-gradient(to right, rgba(255,255,255, 0.9) 0 100%), url(\"https://donttrip.technologists.cloud/donttrip/icons/form_bg.jpg\")";
    }
    if (document.getElementById('other')) {
        document.getElementById('other').style.color = "#000000";
    }
    //Password/Confirm Password strength/matching text
    if (document.getElementById("length")) {
        document.getElementById("length").style.color = "#000000";
    }
    if (document.getElementById("lowercase")) {
        document.getElementById("lowercase").style.color = "#000000";
    }
    if (document.getElementById("uppercase")) {
        document.getElementById("uppercase").style.color = "#000000";
    }
    if (document.getElementById("number")) {
        document.getElementById("number").style.color = "#000000";
    }
    if (document.getElementById("matching")) {
        document.getElementById("matching").style.color = "#000000";
    }
    //2 Factor AJAX requests/responses
    if (document.getElementById("usernav")) {
        document.getElementById("usernav").style.color = "#000000";
    }
    if (document.getElementById("two_factor_response")) {
        document.getElementById("two_factor_response").style.color = "#000000";
    }
    if (document.getElementById("two_factor_div")) {
        document.getElementById("two_factor_div").style.color = "#000000";
    }
    if (document.getElementById("clear_response")) {
        document.getElementById("clear_response").style.color = "#000000";
    }
    //Sason's place info page Divs/Text
    if (
        document.getElementById("panel1") &&
        document.getElementById("panel2") &&
        document.getElementById("panel3") &&
        document.getElementById("panel4")
    ) {
        document.getElementById("panel1").style.color = "#000000";
        document.getElementById("panel1").style.backgroundColor = "#f4f4f4";
        document.getElementById("panel2").style.color = "#000000";
        document.getElementById("panel2").style.backgroundColor = "#f4f4f4";
        document.getElementById("panel3").style.color = "#000000";
        document.getElementById("panel3").style.backgroundColor = "#f4f4f4";
        document.getElementById("panel4").style.color = "#000000";
        document.getElementById("panel4").style.backgroundColor = "#f4f4f4";
    }
    if (
        document.getElementById("panel1") &&
        document.getElementById("panel2") &&
        document.getElementById("panel3")
    ) {
        document.getElementById("panel1").style.color = "#000000";
        document.getElementById("panel1").style.backgroundColor = "#f4f4f4";
        document.getElementById("panel2").style.color = "#000000";
        document.getElementById("panel2").style.backgroundColor = "#f4f4f4";
        document.getElementById("panel3").style.color = "#000000";
        document.getElementById("panel3").style.backgroundColor = "#f4f4f4";
    }
    //E-mail verification card Div
    if (document.getElementById("card")) {
        document.getElementById("card").style.backgroundImage = "linear-gradient(to right, rgba(255,255,255, 0.9) 0 100%), url(\"https://donttrip.technologists.cloud/donttrip/icons/form_bg.jpg\")";
        document.getElementById("card").style.backgroundColor = "#f4f4f4";
        document.getElementById("card").style.border = "1px solid #FFFFED";
    }
    //ul/li/hx/sidebar Divs for certain pages.
    if (document.getElementsByName("darkable")[0]) {
        document.getElementsByName("darkable")[0].style.backgroundColor = "#FFFFED";
    }
    if (document.getElementsByName("keywords")[0]) {
        document.getElementsByName("keywords")[0].style.color = "#000000";
    }
    if (document.getElementsByName("rust")[0]) {
        document.getElementsByName("rust")[0].style.backgroundColor = "#FFFFED";
    }
    if (document.getElementById("russ")) {
        document.getElementById("russ").style.backgroundColor = "#FFFFED";
    }
    if (document.getElementById("sidebar")) {
        document.getElementById("sidebar").style.backgroundColor = "#FFFFED";
    }
    if (document.getElementById("darkable")) {
        document.getElementById("darkable").style.color = "#000000";
    }
    if (document.getElementById("underline")) {
        document.getElementById("underline").style.color = "#000000";
        $("li").css("background-color", "#c6cfea");
        $(".link").css("color", "	#202020");
        $("li:nth-of-type(even)").css("background-color", "#d3d3d3");
        $("button").css("background-color", "transparent");
        $("li").mouseenter(function() {
            $(this).css("box-shadow", "inset -0.4em 0 #8b9dc3")
        }).mouseleave(function() {
            $(this).css("box-shadow", "none");
        });
        $("li:nth-of-type(even)").mouseenter(function() {
            $(this).css("box-shadow", "inset -0.4em 0 #aaa")
        }).mouseleave(function() {
            $(this).css("box-shadow", "none");
        });
    }
    if (document.getElementById("dragbar")) {
        document.getElementById("dragbar").style.color = "#000000";
    }
	if (document.getElementById("bubble")) {
        const bubbles = document.querySelectorAll('.bubble');
        bubbles.forEach(bubble => {
			bubble.style.backgroundColor = '#F0F0F0';
            bubble.style.color = 'black';
        });
    }
    //header/footer/hamburger menu (navbar header)
    if (document.getElementById("header")) {
        document.getElementById("header").style.backgroundImage = "linear-gradient(to right, rgba(255,255,255, 0.9) 0 100%), url(\"https://donttrip.technologists.cloud/donttrip/icons/form_bg.jpg\")";
    }
    if (document.getElementById("footer")) {
        document.getElementById("footer").style.backgroundImage = "linear-gradient(to right, rgba(255,255,255, 0.9) 0 100%), url(\"https://donttrip.technologists.cloud/donttrip/icons/form_bg.jpg\")";

    }
    if (document.getElementById("footer-link")) {
        document.getElementById("footer-link").style.color = "black";
    }
    if (document.getElementById("topnav")) {
        document.getElementById("topnav").style.backgroundColor = "#E8E8E8"; // #d3d3d3(other good color)
        document.getElementById("burger").style.color = "black";
        const links = document.querySelectorAll('.navlink');
        links.forEach(link => {
            link.style.color = 'black';
            link.style.fontWeight = '500';
        });
        const activePages = document.querySelectorAll('.currentPage');
        activePages.forEach(link => {
            link.style.backgroundColor = '#c6cfea';
        });
    }
}