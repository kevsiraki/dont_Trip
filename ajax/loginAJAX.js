$(document).on('click', '#log-in', function (e) {
    if (document.getElementById("successful")) {
        document.getElementById("successful").style.display = "none";
    }
    e.preventDefault();
    let username = $('#username').val();
    let password = $('#password').val();
    let csrf = $('#csrf').val();
    let button = document.getElementById("log-in");
    let error = document.getElementById("invalid-login");
    $.ajax({
        url: 'backend/login_backend',
        type: 'post',
        dataType: 'json',
		contentType: 'application/json',
        timeout: 5000,
        data: JSON.stringify({
            username: username,
            password: password,
			csrf: csrf
        }),
        success: function (response) {
			let result = JSON.stringify(response.message).replace(/['"]+/g, '');
            if (result.includes("ten")) {
                button.disabled = true;
                $('#invalid-login').html(result + '<span style = "float:right;margin-right:-12px;margin-top:-12px;"> &#215; </span>');
                error.style.display = "block";
                $('#invalid-login').on('click', function (e) {
                    error.style.display = "none";
                });
                setTimeout(function () {
                    button.disabled = false;
                }, 10000);
            } else if (result.includes("hecker")) {
                button.style.display = "none";
                window.location.href = "https://www.donttrip.org/donttrip/client/hecker";
            } else if (result.includes("404")) {
                button.style.display = "none";
                window.location.href = "https://www.donttrip.org/donttrip/client/locked";
            } else if (result == 1) {
                error.style.display = "none";
                window.location.href = "client/dt";
            } else if (result == 2) {
                window.location.href = "client/two_factor_auth";
            } else {
                error.classList.remove("alert-warning");
                error.classList.add("alert-danger");
                if (containsAnyLetter(result)) {
                    $('#invalid-login').html(result + '<span style = "float:right;margin-right:-12px;margin-top:-12px;"> &#215; </span>');
                    error.style.display = "block";
                    $('#invalid-login').on('click', function (e) {
                        error.style.display = "none";
                    });
					if (result.includes("Refreshing...")) { 
						async function refresh() {
							await sleep(3000);
							window.location.reload();
						}
						refresh();
					}
                } else {
                    error.style.display = "none";
                }
                button.classList.remove("btn-success");
                button.classList.add("btn-danger");
                setTimeout(function () {
                    button.classList.remove("btn-danger");
                    button.classList.add("btn-success");
                }, 2000);
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
});

function containsAnyLetter(str) {
    return /[a-zA-Z]/.test(str);
}

$(function () {
    $(document).keydown(function (e) {
        switch (e.which) {
        case 13:
            $("#log-in").trigger("click");
            break;
        }
    });
});

$(document).on('click', '#successful', function (e) {
    document.getElementById("successful").style.display = "none";
});

function sleep(ms) { //outdated vanilla JS function
  return new Promise(resolve => setTimeout(resolve, ms));
}