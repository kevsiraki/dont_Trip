$(document).on('click', '#delete-account', function(e) {
    e.preventDefault();
    let password = $('#password').val();
    let csrf = $('#csrf').val();
    $.ajax({
        url: '../backend/delete_confirmation_backend',
        type: 'post',
		dataType: "html",
        timeout: 5000,
        data: {
            password: password,
            csrf: csrf
        },
        success: function(response) {
            let button = document.getElementById("delete-account");
            let error = document.getElementById("invalid-delete");
            if (response == 1) {
                error.style.display = "none";
                button.classList.remove("btn-secondary");
                button.classList.add("btn-success");
                window.location.href = "../backend/logout";
            } else {
                if (containsAnyLetter(response)) {
                    $('#invalid-delete').html(response + '<span style = "float:right;margin-right:-12px;margin-top:-12px;"> &#215; </span>');
                    error.style.display = "block";
                    $('#invalid-delete').on('click', function(e) {
                        error.style.display = "none";
                    });
                } else {
                    error.style.display = "none";
                }
                button.classList.remove("btn-secondary");
                button.classList.add("btn-danger");
                setTimeout(function() {
                    button.classList.remove("btn-danger");
                    button.classList.add("btn-secondary");
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
                $("#delete-account").trigger("click");
                break;
        }
    });
});