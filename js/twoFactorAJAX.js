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
			}
		});
		if (two_factor===false) {
			document.getElementById("to-hide").style.display="none";
		}
	});
});

