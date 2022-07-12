//Description:
//Append light styling during the daytime, nighttime/dark-mode CSS classes by default (sue me).
//Local Storage setting to check for manual dark/light mode along with automatic overrides.
function toggleDarkMode() {
	if( //custom localStorage setting
		localStorage.getItem("dark_mode")==="true" 
		//Automatic mode
		||((new Date).getHours() < 6 || (new Date).getHours() > 18 && localStorage.getItem("dark_mode") === null)
	) 
	{
		localStorage.setItem("dark_mode","false");
		location.reload();
	}
	else {
		localStorage.setItem("dark_mode","true");
		location.reload();
	}
}
function resetDarkMode() {
	window.localStorage.removeItem('dark_mode');
	location.reload();
}
if (localStorage.getItem("dark_mode")==="false"
	//Automatic setting.
	||((new Date()).getHours() >= 6 && (new Date()).getHours() <= 18 && localStorage.getItem("dark_mode") === null)) {
    window.addEventListener("load", function() {
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
        }
    });
}