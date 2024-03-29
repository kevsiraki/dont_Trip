$(document).on('click', '#delete-account', function (e) {
    e.preventDefault();
    let password = $('#password').val();
    let csrf = $('#csrf').val();
    $.ajax({
        url: '../backend/delete_confirmation_backend',
        type: 'DELETE',
        dataType: 'json',
		contentType: "application/json",
        timeout: 5000,
        data: JSON.stringify({
            password: password,
            csrf: csrf
        }),
        success: function (result) {
            let button = document.getElementById("delete-account");
            let error = document.getElementById("invalid-delete");
            if (result.message == 1) {
                error.style.display = "none";
                button.classList.remove("btn-secondary");
                button.classList.add("btn-success");
                window.location.href = "../backend/logout";
            } else {
                if (containsAnyLetter(result.message)) {
                    $('#invalid-delete').html(result.message + '<span style = "float:right;margin-right:-12px;margin-top:-12px;"> &#215; </span>');
                    error.style.display = "block";
                    $('#invalid-delete').on('click', function (e) {
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
            $("#delete-account").trigger("click");
            break;
        }
    });
});