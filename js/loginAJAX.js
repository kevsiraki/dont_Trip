$(document).on('click', '#log-in', function(e)
{
    e.preventDefault();
    let username = $('#username').val();
    let password = $('#password').val();
    let button = document.getElementById("log-in");
    let error = document.getElementById("invalid-login");
    if (username !== "" && password !== "")
    {
        $.ajax(
        {
            url: 'backend/login_backend',
            type: 'post',
			timeout: 5000,
            data:
            {
                username: username,
                password: password
            },
            success: function(response)
            {
                if (response.includes("ten"))
                {
                    button.disabled = true;
                    $('#invalid-login').html(response);
                    error.style.display = "block";
                    $('#invalid-login').on('click', function(e)
                    {
                        error.style.display = "none";
                    });
                    setTimeout(function()
                    {
                        button.disabled = false;
                    }, 10000);

                }
				else if (response.includes("google"))
                {
					button.style.display = "none";
                    window.location.href = "https://donttrip.technologists.cloud/donttrip/client/hecker";
                }
                else if (response.includes("404"))
                {
                    button.style.display = "none";
					window.location.href = "https://donttrip.technologists.cloud/donttrip/client/locked";
                }
                else if (response == 1)
                {
                    error.style.display = "none";
                    window.location.href = "client/dt";
                }
                else if (response == 2)
                {
                    window.location.href = "client/two_factor_auth";
                }
                else
                {
                    error.classList.remove("alert-warning");
                    error.classList.add("alert-danger");
                    if (containsAnyLetter(response))
                    {
                        $('#invalid-login').html(response);
                        error.style.display = "block";
                        $('#invalid-login').on('click', function(e)
                        {
                            error.style.display = "none";
                        });
                    }
                    else
                    {
                        error.style.display = "none";
                    }
                    button.classList.remove("btn-success");
                    button.classList.add("btn-danger");
                    setTimeout(function()
                    {
                        button.classList.remove("btn-danger");
                        button.classList.add("btn-success");
                    }, 2000);
                }
            },
			error: function(xhr, textStatus, errorThrown) {
                        var text = 'Ajax Request Error: ' + 'XMLHTTPRequestObject status: ('+xhr.status + ', ' + xhr.statusText+'), ' +
                                                'text status: ('+textStatus+'), error thrown: ('+errorThrown+')';
                        console.log('The AJAX request failed with the error: ' + text);
                        console.log(xhr.responseText);
                        console.log(xhr.getAllResponseHeaders());
			}
        });
    }
    else
    {
        $('#invalid-login').html("Please fill in all fields.");
        error.style.display = "block";
        error.classList.remove("alert-danger");
        error.classList.add("alert-warning");
        $('#invalid-login').on('click', function(e)
        {
            error.style.display = "none";
        });
        button.classList.remove("btn-success");
        button.classList.add("btn-warning");
        setTimeout(function()
        {
            button.classList.remove("btn-warning");
            button.classList.add("btn-success");
        }, 2000);
    }
});

function containsAnyLetter(str)
{
    return /[a-zA-Z]/.test(str);
}

$(function() {
    $(document).keydown(function(e) {
        switch (e.which) {
            case 13: 
                $("#log-in").trigger("click");
                break;
        }
    });
});