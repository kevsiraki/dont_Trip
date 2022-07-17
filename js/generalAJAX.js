/* password and confirm password checking functions */
//check the password, respond with CSS class injection
function getPassword() {
	$(".invalid-feedback").html("");
    let text = document.getElementById('password').value;
    let length = document.getElementById('length');
    let lowercase = document.getElementById('lowercase');
    let uppercase = document.getElementById('uppercase');
    let number = document.getElementById('number');
    checkIfEightChar(text) ? length?.classList.add('pw-stength-success') : length?.classList.remove('pw-stength-success');
    checkIfOneLowercase(text) ? lowercase?.classList.add('pw-stength-success') : lowercase?.classList.remove('pw-stength-success');
    checkIfOneUppercase(text) ? uppercase?.classList.add('pw-stength-success') : uppercase?.classList.remove('pw-stength-success');
    checkIfOneDigit(text) ? number?.classList.add('pw-stength-success') : number?.classList.remove('pw-stength-success');
}
//check confirmed password
function getConfirmPassword() {
	$(".invalid-feedback").html("");
    let p = document.getElementById('password').value;
    let cp = document.getElementById('confirm-password').value;
    let special = document.getElementById('matching');
    checkIfMatching(p, cp) ? matching?.classList.add('pw-stength-success') : matching?.classList.remove('pw-stength-success');
}
//check length
function checkIfEightChar(text) {
    return text.length >= 8;
}
//check if the password contains a lowercase letter
function checkIfOneLowercase(text) {
    return /[a-z]/.test(text);
}
//check if the password contains an uppercase letter
function checkIfOneUppercase(text) {
    return /[A-Z]/.test(text);
}
//check if the password contains a number
function checkIfOneDigit(text) {
    return /[0-9]/.test(text);
}

function checkIfMatching(p, cp) {
    return (p == cp && p.length > 0 && cp.length > 0);
}

/* toggle strength meters and showing password text */
//show password/confirm password text
function showF() {
    let x = document.getElementById("password");
    let y = document.getElementById("confirm-password");
    if (x.type === "password") {
        x.type = "text";
        y.type = "text";
    } else {
        x.type = "password";
        y.type = "password";
    }
}
//show the password meter
function showMeter() {
    document.getElementById('password-strength').style.display = 'block';
}
//show the confirm password meter
function showConfirmMeter() {
    document.getElementById('confirm-password-strength').style.display = 'block';
}
//hide the password meter
function hideMeter() {
    document.getElementById('password-strength').style.display = 'none';
}
//hide the confirm password meter
function hideCPMeter() {
    document.getElementById('confirm-password-strength').style.display = 'none';
}
/* username, email, and email reset ajax requests to check against database as well. */
$(document).ready(function() {
    $("#username").keyup(function() {
		$(".invalid-feedback").html("");
        let username = $(this).val().trim();
        if (username != '') {
            $.ajax({
                url: '../backend/ajax_requests',
                type: 'post',
                data: {
                    username: username
                },
                success: function(response) {
                    $('#uname_response').html(response);
                }
            });
        } else {
            $("#uname_response").html("");
        }
    });
});

$(document).ready(function() {
    $("#email").keyup(function() {
		$(".invalid-feedback").html("");
        let email = $(this).val().trim();
        if (email != '') {
            $.ajax({
                url: '../backend/ajax_requests',
                type: 'post',
                data: {
                    email: email
                },
                success: function(response) {
                    $('#ename_response').html(response);
                }
            });
        } else {
            $("#ename_response").html("");
        }
    });
});

$(document).ready(function() {
    $("#email-reset").keyup(function() {
		$(".invalid-feedback").html("");
        let email_reset = $(this).val().trim();
        if (email_reset != '') {
            $.ajax({
                url: '../backend/ajax_requests',
                type: 'post',
                data: {
                    email_reset: email_reset
                },
                success: function(response) {
                    $('#ename_response').html(response);
                }
            });
        } else {
            $("#ename_response").html("");
        }
    });
});