$(document).ready(function () {
    // Delete One Destination
    $('.deleteDest').click(function () {
        let element = this;
        // Delete id
        let delete_id = $(this).data('id');
        $.ajax({
            url: '../backend/delete_search',
            type: 'DELETE',
            timeout: 5000,
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify( { "type": "destination", "id": delete_id } ),
			processData: false,
            success: function (result) {
                if (result.message == "Destination Deleted") {
                    // Remove row from HTML Table
                    $(element).closest('li').css('background', 'gray');
                    $(element).closest('li').fadeOut(800, function () {
                        $(this).remove();
                    });
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
    });
});

$(document).ready(function () {
    // Delete One Keyword
    $('.deleteKey').click(function () {
        let element = this;
        // Delete id
        let delete_id = $(this).data('id');
        $.ajax({
            url: '../backend/delete_search',
            type: 'DELETE',
            timeout: 5000,
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify( { "type": "keyword", "id": delete_id } ),
            success: function (result) {
                if (result.message === "Keyword Deleted") {
                    // Remove row from HTML Table
                    $(element).closest('li').css('background', 'gray');
                    $(element).closest('li').fadeOut(800, function () {
                        $(this).remove();
                    });
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
    });
});

if (document.getElementById("clear-searches")) {
    document.getElementById("clear-searches").addEventListener("click", function () {
        $.ajax({
            url: '../backend/delete_search',
            type: 'DELETE',
            timeout: 5000,
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify( { "type": "nuke", "id": "null" } ),
            success: function (result) {
                $('#clear_response').html("<br>" + result.message);
            },
            error: function (xhr, textStatus, errorThrown) {
                var text = 'Ajax Request Error: ' + 'XMLHTTPRequestObject status: (' + xhr.status + ', ' + xhr.statusText + '), ' +
                    'text status: (' + textStatus + '), error thrown: (' + errorThrown + ')';
                console.log('The AJAX request failed with the error: ' + text);
                console.log(xhr.responseText);
                console.log(xhr.getAllResponseHeaders());
            }
        });
        document.getElementById("clear-searches").blur();
        setTimeout(function () {
            document.getElementById("clear_response").innerHTML = '';
        }, 2000);
    });
}