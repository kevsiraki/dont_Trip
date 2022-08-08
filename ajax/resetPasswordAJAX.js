$(document).on('click', '#submit-password', function (e) {
    e.preventDefault();
    let new_password = $('#password').val();
    let confirm_password = $('#confirm-password').val();
    let csrf = $('#csrf').val();
    $.ajax({
        url: '../backend/reset-password_backend',
        type: 'post',
        timeout: 5000,
        dataType: 'json',
		contentType: "application/json",
        data: JSON.stringify({
            new_password: new_password,
            confirm_password: confirm_password,
            csrf: csrf
        }),
        success: function (result) {
            let button = document.getElementById("submit-password");
            let error = document.getElementById("invalid-reset");
            if (result.message == 1) {
                error.style.display = "none";
                window.location.href = "../login?message=Password reset successfully.";
            } else {
                if (containsAnyLetter(result.message)) {
                    error.style.display = "block";
                    $('#invalid-reset').html(result.message + '<span style = "float:right;margin-right:-12px;margin-top:-12px;">&#215;</span>');
                    $('#invalid-reset').on('click', function (e) {
                        error.style.display = "none";
                    });
                } else {
                    error.style.display = "none";
                }
                button.classList.remove("btn-primary");
                button.classList.add("btn-danger");
                setTimeout(function () {
                    button.classList.remove("btn-danger");
                    button.classList.add("btn-primary");
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
            $("#submit-password").trigger("click");
            break;
        }
    });
});