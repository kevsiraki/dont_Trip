$(document).on('click', '#recover-account', function (e) {
    e.preventDefault();
    let username = $('#username').val();
    let password = $('#password').val();
    let csrf = $('#csrf').val();
    $.ajax({
        url: '../backend/recovery_backend',
        type: 'post',
        dataType: 'json',
		contentType: "application/x-www-form-urlencoded; charset=UTF-8",
        timeout: 5000,
        data: JSON.stringify({
            username: username,
            password: password,
            csrf: csrf
        }),
        success: function (result) {
            let button = document.getElementById("recover-account");
            let error = document.getElementById("invalid-recovery");
            if (result.message == 1) {
                error.style.display = "none";
                button.classList.remove("btn-secondary");
                button.classList.add("btn-success");
                window.location.href = "../client/reset-password";
            } else {
                if (containsAnyLetter(result.message)) {
                    $('#invalid-recovery').html(result.message + '<span style = "float:right;margin-right:-12px;margin-top:-12px;"> &#215; </span>');
                    error.style.display = "block";
                    $('#invalid-recovery').on('click', function (e) {
                        error.style.display = "none";
                    });
                } else {
                    error.style.display = "none";
                }
                button.classList.remove("btn-secondary");
                button.classList.add("btn-danger");
                setTimeout(function () {
                    button.classList.remove("btn-danger");
                    button.classList.add("btn-secondary");
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
            $("#recover-account").trigger("click");
            break;
        }
    });
});