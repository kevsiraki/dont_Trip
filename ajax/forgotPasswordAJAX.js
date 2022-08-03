$(document).on('click', '#submit-password', function(e) {
    e.preventDefault();
    let new_password = $('#password').val();
    let confirm_password = $('#confirm-password').val();
    let tfa = $('#tfa').val();
    let email = $('#hidden-email').val();
    let key = $('#hidden-key').val();
    let csrf = $('#csrf').val();
    $.ajax({
        url: '../backend/forgot-password_backend',
        type: 'post',
		dataType: "html",
        timeout: 5000,
        data: {
            new_password: new_password,
            confirm_password: confirm_password,
            tfa: tfa,
            email: email,
            key: key,
            csrf: csrf
        },
        success: function(response) {
            let button = document.getElementById("submit-password");
            let error = document.getElementById("invalid-reset");
            if (response == 1) {
                error.style.display = "none";
                window.location.href = "../login?message=Password reset successfully.";
            } else {
                if (containsAnyLetter(response)) {
                    error.style.display = "block";
                    $('#invalid-reset').html(response + '<span style = "float:right;margin-right:-12px;margin-top:-12px;"> &#215; </span>');
                    $('#invalid-reset').on('click', function(e) {
                        error.style.display = "none";
                    });
                } else {
                    error.style.display = "none";
                }
                button.classList.remove("btn-primary");
                button.classList.add("btn-danger");
                setTimeout(function() {
                    button.classList.remove("btn-danger");
                    button.classList.add("btn-primary");
                }, 2000);
            }
        }
    });
});

function containsAnyLetter(str) {
    return /[a-zA-Z]/.test(str);
}

$(function() {
    $(document).keydown(function(e) {
        switch (e.which) {
            case 13:
                $("#submit-password").trigger("click");
                break;
        }
    });
});