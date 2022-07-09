function getPassword() {
	$(".invalid-feedback").html("");
    let text = document.getElementById('password').value;
    let length = document.getElementById('length');
    let lowercase = document.getElementById('lowercase');
    let uppercase = document.getElementById('uppercase');
    let number = document.getElementById('number');
    let special = document.getElementById('special');
    checkIfEightChar(text) ? length?.classList.add('pw-stength-success') : length?.classList.remove('pw-stength-success');
    checkIfOneLowercase(text) ? lowercase?.classList.add('pw-stength-success') : lowercase?.classList.remove('pw-stength-success');
    checkIfOneUppercase(text) ? uppercase?.classList.add('pw-stength-success') : uppercase?.classList.remove('pw-stength-success');
    checkIfOneDigit(text) ? number?.classList.add('pw-stength-success') : number?.classList.remove('pw-stength-success');
    checkIfOneSpecialChar(text) ? special?.classList.add('pw-stength-success') : special?.classList.remove('pw-stength-success');
}

function getConfirmPassword() {
	$(".invalid-feedback").html("");
    let p = document.getElementById('password').value;
    let cp = document.getElementById('confirm-password').value;
    let special = document.getElementById('matching');
    checkIfMatching(p, cp) ? matching?.classList.add('pw-stength-success') : matching?.classList.remove('pw-stength-success');
}

function checkIfEightChar(text) {
    return text.length >= 8;
}

function checkIfOneLowercase(text) {
    return /[a-z]/.test(text);
}

function checkIfOneUppercase(text) {
    return /[A-Z]/.test(text);
}

function checkIfOneDigit(text) {
    return /[0-9]/.test(text);
}

function checkIfOneSpecialChar(text) {
    return /[~`!#$%\^&*+=\-\[\]\\';,/{}|\\":<>\?]/g.test(text);
}

function checkIfMatching(p, cp) {
    return (p == cp && p.length > 0 && cp.length > 0);
}

function togglePassword() {
    let passInput = document.getElementById('password');
    let togglePW = document.getElementById('togglePW');
    passInput.type === "password" ? passInput.type = "text" : passInput.type = "password";
    togglePW.textContent === "Show Password" ? togglePW.textContent = "Hide Password" : togglePW.textContent = "Show Password";
}

function showF() {
    var x = document.getElementById("password");
    var y = document.getElementById("confirm-password");
    if (x.type === "password") {
        x.type = "text";
        y.type = "text";
    } else {
        x.type = "password";
        y.type = "password";
    }
}

function showMeter() {
    document.getElementById('password-strength').style.display = 'block';
}

function showConfirmMeter() {
    document.getElementById('confirm-password-strength').style.display = 'block';
}

function hideMeter() {
    document.getElementById('password-strength').style.display = 'none';
}

function hideCPMeter() {
    document.getElementById('confirm-password-strength').style.display = 'none';
}

$(document).ready(function() {
    $("#username").keyup(function() {
		$(".invalid-feedback").html("");
        var username = $(this).val().trim();
        if (username != '') {
            $.ajax({
                url: '../backend/ajax_requests.php',
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
        var email = $(this).val().trim();
        if (email != '') {
            $.ajax({
                url: '../backend/ajax_requests.php',
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
        var email_reset = $(this).val().trim();
        if (email_reset != '') {
            $.ajax({
                url: '../backend/ajax_requests.php',
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