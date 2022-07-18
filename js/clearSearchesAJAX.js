document.getElementById("clear-searches").addEventListener("click", function(){ 
	$.ajax({
		url: '../backend/settings_backend',
		type: 'post',
		timeout: 5000,
		data: {
			delete_searches: "true"
		},
		success: function(response) {
			$('#clear_response').html("<br>Search History Cleared.")
		}
	});
	document.getElementById("clear-searches").blur();
	setTimeout(function(){
		document.getElementById("clear_response").innerHTML = '';
	}, 2000);
});