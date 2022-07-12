document.addEventListener('DOMContentLoaded', function () {
	var checkbox = document.querySelector('input[type="checkbox"]');
	checkbox.addEventListener('change', function () {
		var two_factor = checkbox.checked;
		$.ajax({
			url: '../backend/settings_backend.php',
			type: 'post',
			data: {
				two_factor: two_factor
			},
			success: function(response) {
				$('#two_factor_response').html(response)
				if(two_factor===false) {
					setTimeout(function(){
						document.getElementById("two_factor_response").innerHTML = '';
					}, 3000);
				}
			}
		});
		if (two_factor===false) {
			if(document.getElementById("to-hide")) {
				document.getElementById("to-hide").style.display="none";
			}
			
		}
	});
});

