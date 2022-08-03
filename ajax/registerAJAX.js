$(document).on('click', '#sign-up', function(e) {
	e.preventDefault();
    let email = $('#email').val();
    let username = $('#username').val();
    let password = $('#password').val();
    let confirm_password = $('#confirm-password').val();
    let csrf = $('#csrf').val();
	let button = document.getElementById("sign-up");
	let error = document.getElementById("invalid-signup");
    $.ajax({
        url: '../backend/register_backend',
        type: 'post',
		dataType: "html",
		timeout: 5000,
        data: {
            email: email,
            username: username,
            password: password,
            confirm_password: confirm_password,
            csrf: csrf
        },
        success: function(response) {
			
            if (response == 1) {
				error.style.display = "none";
                window.location.href = "../login?message=Success! Verify your e-mail.";
            }
			else {
				if(containsAnyLetter(response)) {
					$('#invalid-signup').html(response + '<span style = "float:right;margin-right:-12px;margin-top:-12px;"> &#215; </span>');
					error.style.display = "block";
					$('#invalid-signup').on('click', function(e) {
						error.style.display = "none";
					});
                    if(response.includes("Exist.")) {
						$('#ename_response').html("<small><span style='color: red;'>E-mail Address Does Not Exist.</span></small>");
					}
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