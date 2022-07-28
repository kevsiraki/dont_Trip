$(document).on('click', '#submit-email', function(e) {
    e.preventDefault();
    let emailReset = $('#email-reset').val();
    $.ajax({
        url: '../backend/fp_backend',
        type: 'post',
        timeout: 5000,
        data: {
            email: emailReset
        },
        success: function(response) {
            let button = document.getElementById("submit-email");
            let error = document.getElementById("invalid-email");
            if (response == 1) {
                error.style.display = "none";
                button.classList.remove("btn-primary");
                button.classList.add("btn-success");
                window.location.href = "../login?message=Success! Check your e-mail to reset.";
            } else {
                if (containsAnyLetter(response)) {
                    $('#invalid-email').html(response + '<span style = "float:right;margin-right:-12px;margin-top:-12px;"> &#215; </span>');
                    error.style.display = "block";
                    $('#invalid-email').on('click', function(e) {
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
                $("#submit-email").trigger("click");
                break;
        }
    });
});