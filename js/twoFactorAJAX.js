document.addEventListener('DOMContentLoaded', function () {
	let checkbox = document.querySelector('input[type="checkbox"]');
	checkbox.addEventListener('change', function () {
		let two_factor = checkbox.checked;
		$.ajax({
			url: '../backend/settings_backend',
			type: 'post',
			timeout: 5000,
			data: {
				two_factor: two_factor
			},
			success: function(response) {
				$('#two_factor_response').html(response);
				//console.log(response);
				if(response.includes("Secret:")) {
					$('#two_factor_response').html(response);
				}
				else if(response.includes("Disabled.")) {
					//setTimeout(function(){
						document.getElementById("two_factor_response").innerHTML = '';
					//}, 1000);
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