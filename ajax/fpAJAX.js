$(document).on('click', '#submit-email', function (e) {
    e.preventDefault();
    let emailReset = $('#email-reset').val();
    let csrf = $('#csrf').val();
    $.ajax({
        url: '../backend/fp_backend',
        type: 'post',
        dataType: 'json',
		contentType: "application/json",
        timeout: 5000,
        data: JSON.stringify({
            email: emailReset,
            csrf: csrf
        }),
        success: function (result) {
            let button = document.getElementById("submit-email");
            let error = document.getElementById("invalid-email");
            if (result.message == 1) {
                error.style.display = "none";
                button.classList.remove("btn-primary");
                button.classList.add("btn-success");
                window.location.href = "../login?message=Success! Check your e-mail to reset.";
            } else {
                if (containsAnyLetter(result.message)) {
                    $('#invalid-email').html(result.message + '<span style = "float:right;margin-right:-12px;margin-top:-12px;"> &#215; </span>');
                    error.style.display = "block";
                    $('#invalid-email').on('click', function (e) {
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
            $("#submit-email").trigger("click");
            break;
        }
    });
});