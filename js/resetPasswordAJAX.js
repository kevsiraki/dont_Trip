$(document).on('click', '#submit-password', function(e) {
	e.preventDefault();
    let new_password = $('#password').val();
	let confirm_password = $('#confirm-password').val();
    $.ajax({
        url: '../backend/reset-password_backend',
        type: 'post',
		timeout: 5000,
        data: {
            new_password: new_password,
			confirm_password: confirm_password
        },
        success: function(response) {
			let button = document.getElementById("submit-password");
			let error = document.getElementById("invalid-reset");
            if (response == 1) {
				error.style.display = "none";
                window.location.href = "../login";
            }
			else{
				if(containsAnyLetter(response)) {
					error.style.display = "block";
					$('#invalid-reset').html(response);
					$('#invalid-reset').on('click', function(e) {
						error.style.display = "none";
					});
				}
				else {
					error.style.display = "none";
				}
				button.classList.remove("btn-primary");
				button.classList.add("btn-danger");
				setTimeout(function(){
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