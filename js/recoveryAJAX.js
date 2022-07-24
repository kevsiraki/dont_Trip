$(document).on('click', '#recover-account', function(e) {
	e.preventDefault();
	let username = $('#username').val();
    let password = $('#password').val();
    $.ajax({
        url: '../backend/recovery_backend',
        type: 'post',
		timeout: 5000,
        data: {
			username: username,
            password: password
        },
        success: function(response) {
			let button = document.getElementById("recover-account");
			let error = document.getElementById("invalid-recovery");
            if (response == 1) {
				error.style.display = "none";
				button.classList.remove("btn-secondary");
				button.classList.add("btn-success");
                window.location.href = "../client/reset-password";
            }
			else {
				if(containsAnyLetter(response)) {
					$('#invalid-recovery').html(response);
					error.style.display = "block";
					$('#invalid-recovery').on('click', function(e) {
						error.style.display = "none";
					});
				}
				else {
					error.style.display = "none";
				}
				button.classList.remove("btn-secondary");
				button.classList.add("btn-danger");
				setTimeout(function(){
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
                $("#recover-account").trigger("click");
                break;
        }
    });
});