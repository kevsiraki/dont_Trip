$(document).ready(function() {
    // Delete 
    $('.deleteDest').click(function() {
        let element = this;
        // Delete id
        let delete_id = $(this).data('id');
        $.ajax({
            url: '../backend/searches_backend',
            type: 'GET',
            data: {
                toDeleteDestination: delete_id
            },
            success: function(response) {
                if (response == 1) {
                    // Remove row from HTML Table
                    $(element).closest('li').css('background', 'gray');
                    $(element).closest('li').fadeOut(800, function() {
                        $(this).remove();
                    });
                }
            }
        });
    });
});

$(document).ready(function() {
    // Delete 
    $('.deleteKey').click(function() {
        let element = this;
        // Delete id
        let delete_id = $(this).data('id');
        $.ajax({
            url: '../backend/searches_backend',
            type: 'GET',
            data: {
                toDeleteKeyword: delete_id
            },
            success: function(response) {
                if (response == 2) {
                    // Remove row from HTML Table
                    $(element).closest('li').css('background', 'gray');
                    $(element).closest('li').fadeOut(800, function() {
                        $(this).remove();
                    });
                }
            }
        });
    });
});