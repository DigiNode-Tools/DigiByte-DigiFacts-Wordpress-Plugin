jQuery(document).ready(function ($) {
    function refreshDigiFact() {
        $.ajax({
            url: digibyte_digifacts_ajax_params.ajaxurl, // Corrected ajaxurl
            type: 'POST',
            data: {
                action: 'digibyte_digifacts_ajax_refresh', // Action hook for the PHP function
                nonce: digibyte_digifacts_ajax_params.nonce
            },
            success: function (response) {
                if (response.success) {
                    // Update the DigiFact content with the new data
                    $('.digifact-title').html(response.data.title);
                    $('.digifact-content').html(response.data.content);
                } else {
                    console.error('Error refreshing DigiFact: ' + response.data);
                }
            },
            error: function (error) {
                console.error('Error refreshing DigiFact: ' + error.responseText);
            },
        });
    }

    // Refresh DigiFact every 60 seconds (60000 milliseconds)
    setInterval(refreshDigiFact, 60000);
});
