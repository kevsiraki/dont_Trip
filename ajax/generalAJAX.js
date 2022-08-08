/* password and confirm password checking functions (client side checks before server-side checks) */
//check the password, respond with CSS class injection
function getPassword() {
    $(".invalid-feedback").html("");
    let text = document.getElementById('password').value;
    let length = document.getElementById('length');
    let lowercase = document.getElementById('lowercase');
    let uppercase = document.getElementById('uppercase');
    let number = document.getElementById('number');
    checkIfEightChar(text) ? length ?.classList.add('pw-stength-success') : length ?.classList.remove('pw-stength-success');
    checkIfOneLowercase(text) ? lowercase ?.classList.add('pw-stength-success') : lowercase ?.classList.remove('pw-stength-success');
    checkIfOneUppercase(text) ? uppercase ?.classList.add('pw-stength-success') : uppercase ?.classList.remove('pw-stength-success');
    checkIfOneDigit(text) ? number ?.classList.add('pw-stength-success') : number ?.classList.remove('pw-stength-success');
}
//check confirmed password
function getConfirmPassword() {
    $(".invalid-feedback").html("");
    let p = document.getElementById('password').value;
    let cp = document.getElementById('confirm-password').value;
    let special = document.getElementById('matching');
    checkIfMatching(p, cp) ? matching ?.classList.add('pw-stength-success') : matching ?.classList.remove('pw-stength-success');
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

/*toggle strength meters*/

//show the password meter
function showMeter() {
    document.getElementById('password-strength').style.display = 'block';
}
//show the confirm password meter
function showConfirmMeter() {
    document.getElementById('confirm-password-strength').style.display = 'block';
}
//show both meters
function showBoth() {
    showMeter();
    showConfirmMeter();
}
//check both passwords and show both meters
function getBoth() {
    showMeter();
    showConfirmMeter();
    getPassword();
    getConfirmPassword();
}
//hide the password meter
function hideMeter() {
    document.getElementById('password-strength').style.display = 'none';
}
//hide the confirm password meter
function hideCPMeter() {
    document.getElementById('confirm-password-strength').style.display = 'none';
}
//throttle requests
function throttle(func, wait) {
    var timeout;
    return function () {
        var context = this,
            args = arguments;
        if (!timeout) {
            timeout = setTimeout(function () {
                timeout = null;
                func.apply(context, args);
            }, wait);
        }
    }
}

/* 
-username, email, and email reset API requests to check against database as well. 
-throttles how many requests can be sent at a time (tested in Insomnia)...
-(Throttled on server side as well)...
*/

$(document).ready(function () {
    $("#username").on("input", throttle(function () {
        $(".invalid-feedback").html("");
        let username = $(this).val().trim();
        if (username != '') {
            let verify = function () {
                $.ajax({
                    url: '../backend/ajax_requests',
                    type: 'post',
                    dataType: "JSON",
                    timeout: 5000,
                    data: {
                        username: username
                    },
                    success: function (response) {
                        if (response.check) {
                            $('#uname_response').html('<small><span style="color: #ff8c00;">'+response.check+'</span></small>');
                            setTimeout(function () {
                                verify();
								if(response.success) {
									$('#uname_response').html('<small><span style="color: green;">'+response.success+'</span></small>');
								}
								else if(response.error) {
									$('#uname_response').html('<small><span style="color: red;">'+response.error+'</span></small>');
								}
                            }, 2000);
                        } else {
							if(response.success) {
								$('#uname_response').html('<small><span style="color: green;">'+response.success+'</span></small>');
							}
							else if(response.error) {
								$('#uname_response').html('<small><span style="color: red;">'+response.error+'</span></small>');
							}
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        var text = 'Ajax Request Error: ' + 'XMLHTTPRequestObject status: (' + xhr.status + ', ' + xhr.statusText + '), ' +
                            'text status: (' + textStatus + '), error thrown: (' + errorThrown + ')';
                        console.log('The AJAX request failed with the error: ' + text);
                        console.log(xhr.responseText);
                        console.log(xhr.getAllResponseHeaders());
                    }
                });
            }
            verify();
        } else {
            $("#uname_response").html("");
        }
    }, 1000));
});

$(document).ready(function () {
    $("#email").on("input", throttle(function () {
        $(".invalid-feedback").html("");
        let email = $(this).val().trim();
        if (email != '') {
            let verify = function () {
                $.ajax({
                    url: '../backend/ajax_requests',
                    type: 'post',
                    dataType: "JSON",
                    timeout: 5000,
                    data: {
                        email: email
                    },
                    success: function (response) {
                        if (response.check) {
                            $('#ename_response').html('<small><span style="color: #ff8c00;">'+response.check+'</span></small>');
                            setTimeout(function () {
                                verify();
								if(response.success) {
									$('#ename_response').html('<small><span style="color: green;">'+response.success+'</span></small>');
								}
								else if(response.error) {
									$('#ename_response').html('<small><span style="color: red;">'+response.error+'</span></small>');
								}
                            }, 2000);
                        } else {
							if(response.success) {
								$('#ename_response').html('<small><span style="color: green;">'+response.success+'</span></small>');
							}
							else if(response.error) {
								$('#ename_response').html('<small><span style="color: red;">'+response.error+'</span></small>');
							}
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        var text = 'Ajax Request Error: ' + 'XMLHTTPRequestObject status: (' + xhr.status + ', ' + xhr.statusText + '), ' +
                            'text status: (' + textStatus + '), error thrown: (' + errorThrown + ')';
                        console.log('The AJAX request failed with the error: ' + text);
                        console.log(xhr.responseText);
                        console.log(xhr.getAllResponseHeaders());
                    }
                });
            }
            verify();
        } else {
            $("#ename_response").html("");
        }
    }, 1000));
});

$(document).ready(function () {
    $("#email-reset").on("input", throttle(function () {
        $(".invalid-feedback").html("");
        let email_reset = $(this).val().trim();
        if (email_reset != '') {
            let verify = function () {
                $.ajax({
                    url: '../backend/ajax_requests',
                    type: 'post',
                    dataType: "JSON",
                    timeout: 5000,
                    data: {
                        email_reset: email_reset
                    },
                    success: function (response) {
                        if (response.check) {
                            $('#ename_response').html('<small><span style="color: #ff8c00;">'+response.check+'</span></small>');
                            setTimeout(function () {
                                verify();
								if(response.success) {
									$('#ename_response').html('<small><span style="color: green;">'+response.success+'</span></small>');
								}
								else if(response.error) {
									$('#ename_response').html('<small><span style="color: red;">'+response.error+'</span></small>');
								}
                            }, 2000);
                        } else {
							if(response.success) {
								$('#ename_response').html('<small><span style="color: green;">'+response.success+'</span></small>');
							}
							else if(response.error) {
								$('#ename_response').html('<small><span style="color: red;">'+response.error+'</span></small>');
							}
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        var text = 'Ajax Request Error: ' + 'XMLHTTPRequestObject status: (' + xhr.status + ', ' + xhr.statusText + '), ' +
                            'text status: (' + textStatus + '), error thrown: (' + errorThrown + ')';
                        console.log('The AJAX request failed with the error: ' + text);
                        console.log(xhr.responseText);
                        console.log(xhr.getAllResponseHeaders());
                    }
                });
            }
            verify();
        } else {
            $("#ename_response").html("");
        }
    }, 1000));
});