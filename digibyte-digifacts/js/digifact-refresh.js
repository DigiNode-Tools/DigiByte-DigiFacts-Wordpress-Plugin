jQuery(document).ready(function ($) {

    // Check if language was changed on page load
    var languageChanged = digibyte_digifacts_ajax_params.languageChanged;
    
    if (languageChanged) {
        localStorage.removeItem('digifacts');
        localStorage.removeItem('digifacts_timestamp');
        // You may also want to remove the transient flag as it's no longer needed
        $.post(digibyte_digifacts_ajax_params.ajaxurl, {
            action: 'clear_language_changed_flag',
            nonce: digibyte_digifacts_ajax_params.nonce
        });
    }

    function storeDigiFacts(data) {
        // Store in local storage with a timestamp
        localStorage.setItem('digifacts', JSON.stringify(data));
        localStorage.setItem('digifacts_timestamp', Date.now());
    }

    function getDigiFacts() {
        // Get the data from local storage
        let data = localStorage.getItem('digifacts');
        return data ? JSON.parse(data) : null;
    }

    function isCacheValid() {
        // Check if the cache is older than 60 minutes
        const timestamp = localStorage.getItem('digifacts_timestamp');
        return timestamp && Date.now() - timestamp < 3600000; // 60 minutes in milliseconds
    }

    function refreshDigiFact() {
        let facts = getDigiFacts();
        // console.log('Facts from local storage:', facts);

        if (!facts || !isCacheValid()) {
            // Fetch the facts from the server and store them if there's no cache or if it's invalid
            console.log('Fetching new facts from server...');
            $.ajax({
                url: digibyte_digifacts_ajax_params.ajaxurl,
                type: 'POST',
                data: {
                    action: 'digibyte_digifacts_ajax_refresh',
                    nonce: digibyte_digifacts_ajax_params.nonce
                },
                success: function (response) {
                    console.log('Received response:', response);
                    if (response.success) {
                        // Convert object of objects into an array
                        var factsArray = Object.keys(response.data).map(function (key) {
                            return response.data[key];
                        });
                
                        storeDigiFacts(factsArray); // Store the array of DigiFacts
                        displayRandomDigiFact(factsArray);
                    } else {
                        console.error('Error fetching DigiFacts: ' + response.data);
                    }
                },
                error: function (error) {
                    console.error('Error fetching DigiFacts: ' + error.responseText);
                },
            });
        } else {
            // If the cache is valid, use it to display a random DigiFact
            console.log('Using cached facts.');
            displayRandomDigiFact(facts);
        }
    }

    function displayRandomDigiFact(factsArray) {
        // Pick a random fact from the array and update the content
        const randomIndex = Math.floor(Math.random() * factsArray.length);
        const fact = factsArray[randomIndex];
        $('.digifact-title').text(fact.title);
        $('.digifact-content').html(fact.content);
    }    

    // Check if the facts are already valid in local storage before setting the interval
    if (!getDigiFacts() || !isCacheValid()) {
        // If not, fetch new facts immediately
        refreshDigiFact();
    }

    // Optionally, you might want to refresh the DigiFact from local storage more frequently
    setInterval(refreshDigiFact, 60000); // Refresh every 60 seconds
});
