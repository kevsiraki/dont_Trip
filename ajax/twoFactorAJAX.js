document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById("two_factor_response")) {
        let checkbox = document.querySelector('input[type="checkbox"]');
        checkbox.addEventListener('change', function() {
            let two_factor = checkbox.checked;
            $.ajax({
                url: '../backend/settings_backend',
                type: 'post',
				dataType: "html",
                timeout: 5000,
                data: {
                    two_factor: two_factor
                },
                success: function(response) {
                    $('#two_factor_response').html(response);
                    if (response.includes("Disabled.")) {
                        document.getElementById("two_factor_response").innerHTML = '';
                    }
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