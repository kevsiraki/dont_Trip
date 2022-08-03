$(document).on('click', '#verify', function(e) {
	e.preventDefault();
    let tfa = $('#tfa').val();
    let csrf = $('#csrf').val();
    $.ajax({
        url: '../backend/two_factor_auth_backend',
        type: 'post',
		dataType: "html",
		timeout: 5000,
        data: {
            tfa: tfa,
            csrf: csrf
        },
        success: function(response) {
			let button = document.getElementById("verify");
			let error = document.getElementById("invalid-login");
			if (response == 1) {
				error.style.display = "none";
                window.location.href = "dt";
            }
			else {
				$('#invalid-login').html(response + '<span style = "float:right;margin-right:-12px;margin-top:-12px;"> &#215; </span>');
				error.style.display = "block";
				$('#invalid-login').on('click', function(e) {
					error.style.display = "none";
				});
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

$(function() {
    $(document).keydown(function(e) {
        switch (e.which) {
            case 13: 
                $("#verify").trigger("click");
                break;
        }
    });
});