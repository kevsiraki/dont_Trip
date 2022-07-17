$(document).on('click', '#log-in', function(e) {
	e.preventDefault();
    let username = $('#username').val();
    let password = $('#password').val();
    $.ajax({
        url: 'backend/login_backend',
        type: 'post',
        data: {
            username: username,
            password: password
        },
        success: function(response) {
			let button = document.getElementById("log-in");
			let error = document.getElementById("invalid-login");
            if (response == 1) {
				error.style.display = "none";
                window.location.href = "client/dt";
            }
			else if(response==2||response==21) {
				window.location.href = "client/two_factor_auth";
			}
			else {
				if(containsAnyLetter(response)) {
					$('#invalid-login').html(response);
					error.style.display = "block";
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