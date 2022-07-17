$(document).on('click', '#delete-account', function(e) {
	e.preventDefault();
    let password = $('#password').val();
    $.ajax({
        url: '../backend/delete_confirmation_backend',
        type: 'post',
        data: {
            password: password
        },
        success: function(response) {
			let button = document.getElementById("delete-account");
			let error = document.getElementById("invalid-delete");
            if (response == 1) {
				error.style.display = "none";
				button.classList.remove("btn-secondary");
				button.classList.add("btn-success");
                window.location.href = "../backend/logout";
            }
			else {
				if(containsAnyLetter(response)) {
					$('#invalid-delete').html(response);
					error.style.display = "block";
					
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