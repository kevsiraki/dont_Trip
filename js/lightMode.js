const d = new Date();
if (localStorage.getItem("dark_mode") === "false" || (d.getHours() >= 6 && d.getHours() <= 18 && localStorage.getItem("dark_mode") === null)) {
    window.addEventListener("DOMContentLoaded", function () {
        lightStyle();
        if (localStorage.getItem("dark_mode") === null && document.getElementById("reset-dark")) {
            document.getElementById("reset-dark").innerText = "✓Auto";
        }
    });
}
window.addEventListener("DOMContentLoaded", function () {
    if (localStorage.getItem("dark_mode") === null && document.getElementById("reset-dark")) {
        document.getElementById("reset-dark").innerText = "✓Auto";
		document.getElementById("reset-dark").disabled = "true";
    }
});
function toggleDarkMode() {
    if (localStorage.getItem("dark_mode") === "true" || ((d.getHours() < 6 || d.getHours() > 18) && localStorage.getItem("dark_mode") === null)) {
        localStorage.setItem("dark_mode", "false");
        location.reload();
    } else {
        localStorage.setItem("dark_mode", "true");
		document.body.className = 'body fade-in'
		setTimeout(setFadeOut, 5000);
        location.reload();
    }
}
function setFadeOut(){
	document.body.className = 'body';
}
function resetDarkMode() {
    window.localStorage.removeItem('dark_mode');
    location.reload();
}
function redirectTo(s, event) {
    window.location.href = s;
}
function no(event) {
    event.stopPropagation();
}
function lightStyle() {
    const form_bg = "linear-gradient(to right, rgba(255,255,255, 0.9) 0 100%), url(\"https://donttrip.technologists.cloud/donttrip/icons/form_bg.jpg\")";
    if (document.getElementsByClassName("form-control").length > 0 && !document.getElementById("log-in")) {
        $('link[href*="form_style.css"]').attr("disabled", "true");
        $('head').append('<link rel="stylesheet" href="../style/formLightInputs.css" type="text/css" />');
    }
    if (document.getElementById("log-in")) {
        $('link[href*="form_style.css"]').attr("disabled", "true");
        $('head').append('<link rel="stylesheet" href="style/formLightInputs.css" type="text/css" />');
    }
    if (document.documentElement) {
        document.documentElement.style.background = "#FFFFED";
        document.documentElement.style.backgroundColor = "#FFFFED";
        document.body.style.background = "#FFFFED";
        document.body.style.backgroundColor = "#FFFFED";
    }
    if (document.getElementById('bg')) {
        document.getElementById('bg').style.backgroundColor = "#FFFFED";
    }
    if (document.getElementsByClassName("rust").length > 0) {
        const sidebars = document.querySelectorAll('.rust');
        sidebars.forEach(sidebar => {
            sidebar.style.backgroundColor = "#FFFFED";
        });
    }
    if (document.getElementById("dragbar")) {
        document.getElementById("dragbar").style.color = "#000000";
    }
    if (document.getElementById("footer-link")) {
        document.getElementById("footer-link").style.color = "#000000";
    }
    if (document.getElementsByClassName("darkable-text").length > 0) {
        const darkables = document.querySelectorAll('.darkable-text');
        darkables.forEach(darkable => {
            darkable.style.color = '#000000';
        });
    }
    if (document.getElementById('other')) {
        const others = document.querySelectorAll('.other');
        others.forEach(other => {
            other.style.color = '#000000';
        });
        document.getElementById('other').style.color = "#000000";
    }
    if (document.getElementById("password-strength")) {
        const meters = document.querySelectorAll('.pw-stength');
        meters.forEach(meter => {
            meter.style.color = '#000000';
        });
    }
    if (document.getElementsByClassName("panel").length > 0) {
        const panels = document.querySelectorAll('.panel');
        panels.forEach(panel => {
            panel.style.color = '#000000';
            panel.style.backgroundColor = "#f4f4f4";
        });
    }
    if (document.getElementById("card")) {
        document.getElementById("card").style.backgroundImage = form_bg;
        document.getElementById("card").style.backgroundColor = "#f4f4f4";
        document.getElementById("card").style.border = "1px solid #FFFFED";
    }
    if (document.getElementsByClassName('wrapper')[0]) {
        document.getElementsByClassName('wrapper')[0].style.backgroundImage = form_bg;
    }
    if (document.getElementById("header")) {
        document.getElementById("header").style.backgroundImage = form_bg;
    }
    if (document.getElementById("footer")) {
        document.getElementById("footer").style.backgroundImage = form_bg;
    }
    if (document.getElementById("underline")) {
        document.getElementById("underline").style.color = "#000000";
        $("li").css("background-color", "#c6cfea");
        $(".link").css("color", "	#202020");
        $("li:nth-of-type(even)").css("background-color", "#d3d3d3");
        $("button").css("background-color", "transparent");
        $("li").mouseenter(function () {
            $(this).css("box-shadow", "inset -0.4em 0 #8b9dc3")
        }).mouseleave(function () {
            $(this).css("box-shadow", "none");
        });
        $("li:nth-of-type(even)").mouseenter(function () {
            $(this).css("box-shadow", "inset -0.4em 0 #aaa")
        }).mouseleave(function () {
            $(this).css("box-shadow", "none");
        });
    }
    if (document.getElementById("bubble")) {
        const bubbles = document.querySelectorAll('.bubble');
        bubbles.forEach(bubble => {
            bubble.style.backgroundColor = '#F0F0F0';
            bubble.style.color = '#000000';
        });
    }
    if (document.getElementById("password")) {
        const eyes = document.querySelectorAll('.toggle-password');
        eyes.forEach(eye => {
            eye.style.color = '#000000';
        });
    }
    if (document.getElementById("topnav")) {
        document.getElementById("topnav").style.backgroundColor = "#E8E8E8";
        document.getElementById("burger").style.color = "#000000";
        const links = document.querySelectorAll('.navlink');
        links.forEach(link => {
            link.style.color = '#000000';
            link.style.fontWeight = '500';
        });
        const activePages = document.querySelectorAll('.currentPage');
        activePages.forEach(link => {
            link.style.backgroundColor = '#c6cfea';
        });
    }
}
$(function () {
    $(".form-group .field-placeholder").on("click", function () {
        $(this).closest(".form-group").find("input").focus();
    });
    $(".form-group input").on("change", function () {
        var value = $.trim($(this).val());
        if (value) {
            $(this).closest(".form-group").addClass("hasValue");
        } else {
            $(this).closest(".form-group").removeClass("hasValue");
        }
    });
    $(".form-group input").on("input", function () {
        var value = $.trim($(this).val());
        if (value) {
            $(this).closest(".form-group").addClass("hasValue");
        } else {
            $(this).closest(".form-group").removeClass("hasValue");
        }
    });
});