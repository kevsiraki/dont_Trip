$(document).on('click', '#sign-up', function(e) {
	e.preventDefault();
    let email = $('#email').val();
    let username = $('#username').val();
    let password = $('#password').val();
    let confirm_password = $('#confirm-password').val();
	let button = document.getElementById("sign-up");
	let error = document.getElementById("invalid-signup");
	if (email !== "" &&username !== "" && password !== "" && confirm_password!=="")
    {
    $.ajax({
        url: '../backend/register_backend',
        type: 'post',
		timeout: 5000,
        data: {
            email: email,
            username: username,
            password: password,
            confirm_password: confirm_password
        },
        success: function(response) {
			
            if (response == 1) {
				error.style.display = "none";
                window.location.href = "../login";
            }
			else {
				error.classList.remove("alert-warning");
                    error.classList.add("alert-danger");
				if(containsAnyLetter(response)) {
					$('#invalid-signup').html(response);
					error.style.display = "block";
					$('#invalid-signup').on('click', function(e) {
						error.style.display = "none";
					});
				}
				else {
					error.style.display = "none";
				}
				button.classList.remove("btn-success");
				button.classList.add("btn-danger");
				setTimeout(function(){
					button.classList.remove("btn-danger");
					button.classList.add("btn-success");
				}, 2000);
			}
        }
    });
}
else {
	$('#invalid-signup').html("Please fill in all fields.");
        error.style.display = "block";
        error.classList.remove("alert-danger");
        error.classList.add("alert-warning");
        $('#invalid-signup').on('click', function(e)
        {
            error.style.display = "none";
        });
        button.classList.remove("btn-success");
        button.classList.add("btn-warning");
        setTimeout(function()
        {
            button.classList.remove("btn-warning");
            button.classList.add("btn-success");
        }, 2000);
}
});

function containsAnyLetter(str) {
  return /[a-zA-Z]/.test(str);
}

$(function() {
    $(document).keydown(function(e) {
        switch (e.which) {
            case 13: 
                $("#sign-up").trigger("click");
                break;
        }
    });
});