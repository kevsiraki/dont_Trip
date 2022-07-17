$(document).on('click', '#submit-email', function(e) {
	e.preventDefault();
    let emailReset = $('#email-reset').val();
    $.ajax({
        url: '../backend/fp_backend',
        type: 'post',
        data: {
            email: emailReset
        },
        success: function(response) {
			let button = document.getElementById("submit-email");
			let error = document.getElementById("invalid-email");
			if (response == 1) {
				error.style.display = "none";
                window.location.href = "../login";
				button.classList.remove("btn-primary");
				button.classList.add("btn-success");
            }
			else {
				if(containsAnyLetter(response)) {
					$('#invalid-email').html(response);
					error.style.display = "block";
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