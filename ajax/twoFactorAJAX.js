document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById("two_factor_response")) {
        let checkbox = document.querySelector('input[type="checkbox"]');
        checkbox.addEventListener('change', function () {
            let two_factor = checkbox.checked;
            $.ajax({
                url: '../backend/settings_backend',
                type: 'post',
				dataType: 'JSON',
                timeout: 5000,
                data: {
                    two_factor: two_factor
                },
                success: function (response) {
					if(response.secret !== undefined)
					{
						$('#two_factor_response').html('2FA Secret: <b id="copyNew">'+response.secret+'</b>&nbsp;'+
							'<button class = "btn btn-outline-info btn-sm" onclick="copySecret(copyNew);">📋</button><br><br>'+
							'<p>1. Copy and paste the code above or scan the QR code below in your authenticator app of choice.</p>'+
							'<p>2. Keep this secret somewhere safe in case you lose access to your authenticator app.</p>'+
							'<p>3. The one-time code will refresh every 30 seconds and will be required for future logins/password resets</p>'+
							'<img class="center" src = "'+response.qr+'" alt = "QR Code" />');
					}
					else
					{
						$('#two_factor_response').html('');
					}
                },
                error: function (xhr, textStatus, errorThrown) {
                    var text = 'Ajax Request Error: ' + 'XMLHTTPRequestObject status: (' + xhr.status + ', ' + xhr.statusText + '), ' +
                        'text status: (' + textStatus + '), error thrown: (' + errorThrown + ')';
                    console.log('The AJAX request failed with the error: ' + text);
                    console.log(xhr.responseText);
                    console.log(xhr.getAllResponseHeaders());
                }
            });
            if (two_factor === false) {
                if (document.getElementById("to-hide")) {
                    document.getElementById("to-hide").style.display = "none";
                }
            }
        });
    }
});

function copySecret(id) {
	var copyText = id.innerText;
	console.log(copyText);
	var elem = document.createElement("textarea");
	document.body.appendChild(elem);
	elem.value = copyText;
	elem.select();
	elem.setSelectionRange(0, 99999); 
	navigator.clipboard.writeText(elem.value);
	alert("Copied Secret: " + copyText+"\nPaste into your authenticator app.");
	document.body.removeChild(elem);
}