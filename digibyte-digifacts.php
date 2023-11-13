<?php
/*
Plugin Name: DigiByte DigiFacts
Description: Display random DigiFacts about DigiByte in multiple languages.
Version: 1.0
Author: Olly Stedall (DigiByte.Help)
*/

add_action('admin_menu', 'digibyte_digifacts_admin_menu');

function digibyte_digifacts_admin_menu() {
    add_options_page('DigiByte DigiFacts Settings', 'DigiFacts', 'manage_options', 'digibyte-digifacts', 'digibyte_digifacts_settings_page');
}

function digibyte_digifacts_settings_page() {
    // Automatically reload languages whenever the settings page is loaded
    digibyte_digifacts_reload_languages();

    // Show any settings errors that were added
    settings_errors('digibyte_digifacts_languages');

    ?>
    <div class="wrap">
        <h2>DigiByte DigiFacts Settings</h2>
        <form action="options.php" method="post">
            <?php
            settings_fields('digibyte_digifacts_options');
            do_settings_sections('digibyte-digifacts');
            submit_button();
            ?>
        </form>
        <div>
            <h3>Shortcode Usage</h3>
            <p>To display DigiFacts on your site, use the shortcode <code>[digifacts]</code> in your posts or pages.</p>
            <h3>Styling the DigiFacts</h3>
            <p>You can style the DigiFacts title and content with CSS. Add custom styles to your theme's stylesheet or in the Customizer under Additional CSS.</p>
            <pre>
.digibyte-digifact .digifact-title {
    font-size: 24px;
    color: #333;
}
.digibyte-digifact .digifact-content {
    font-size: 16px;
    color: #666;
}
            </pre>
            <p>Replace the font-size and color values with your own preferences.</p>
        </div>
    </div>
    <?php
}


add_action('updated_option', 'digibyte_digifacts_updated_option', 10, 3);
function digibyte_digifacts_updated_option($option_name, $old_value, $value) {
    if ('digibyte_digifacts_language' === $option_name) {
        // Fetch and cache new facts when the language option is updated.
        digibyte_digifacts_fetch_facts($value);
    }
}

function digibyte_digifacts_settings_section_cb() {
    echo '<p>Set your preferences for the DigiByte DigiFacts plugin here.</p>';
}

function digibyte_digifacts_display_field_cb() {
    $display = get_option('digibyte_digifacts_display', 'box'); // Default to 'box' if not set

    ?>
    <input type="radio" id="display_box" name="digibyte_digifacts_display" value="box" <?php checked($display, 'box'); ?> />Box<br />
    <input type="radio" id="display_text" name="digibyte_digifacts_display" value="text" <?php checked($display, 'text'); ?> />Text Only
    <?php
}

add_action('admin_init', 'digibyte_digifacts_settings_init');

function digibyte_digifacts_settings_init() {
    // Register settings for your options page
    register_setting('digibyte_digifacts_options', 'digibyte_digifacts_language', 'digibyte_digifacts_sanitize_language');
    register_setting('digibyte_digifacts_options', 'digibyte_digifacts_display');
    
    // Add a settings section on your options page
    add_settings_section(
        'digibyte_digifacts_settings_section',
        'DigiFacts Display Settings',
        'digibyte_digifacts_settings_section_cb',
        'digibyte-digifacts'
    );

    // Add a settings field for language selection
    add_settings_field(
        'digibyte_digifacts_language_field',
        'Language',
        'digibyte_digifacts_language_field_cb',
        'digibyte-digifacts',
        'digibyte_digifacts_settings_section'
    );

    // Add a settings field for display selection
    add_settings_field(
        'digibyte_digifacts_display_field',
        'Display',
        'digibyte_digifacts_display_field_cb',
        'digibyte-digifacts',
        'digibyte_digifacts_settings_section'
    );

    // Check if the "Reload Languages" button was clicked and the nonce field is valid
    if (isset($_POST['reload_languages_nonce'], $_POST['reload_languages']) &&
        wp_verify_nonce($_POST['reload_languages_nonce'], 'reload_languages_action')) {
        // Call the function to re-fetch the languages
        digibyte_digifacts_reload_languages();
    }
}

function digibyte_digifacts_sanitize_language($language_code) {
    // Ensure the language_code is in the list of languages
    $languages = get_option('digibyte_digifacts_languages', array());

    if (array_key_exists($language_code, $languages)) {
        return $language_code;
    } else {
        // Return the default 'en' if the provided code isn't valid
        return 'en';
    }
}


function digibyte_digifacts_reload_languages() {
    $response = wp_remote_get('https://digifacts.digibyte.help/?get_langs');
    
    if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
        $language_codes = json_decode(wp_remote_retrieve_body($response), true);
        if (is_array($language_codes)) {
            // Convert numeric array to associative array
            $languages_assoc = array_combine($language_codes, $language_codes);
            // Save the associative array in the database option
            update_option('digibyte_digifacts_languages', $languages_assoc);
        } else {
            // Handle the case where the languages array is not valid
            add_settings_error(
                'digibyte_digifacts_languages',
                'digibyte_digifacts_invalid_array',
                'Invalid languages array received from the API',
                'error'
            );
        }
    } else {
        // Handle the case where the response is a WP error or the response code is not 200
        $error_message = is_wp_error($response) ? $response->get_error_message() : 'Unexpected response code: ' . wp_remote_retrieve_response_code($response);
        add_settings_error(
            'digibyte_digifacts_languages',
            'digibyte_digifacts_fetch_error',
            'Unable to fetch languages from the API: ' . $error_message,
            'error'
        );
    }
    
}

function digibyte_digifacts_language_field_cb() {
    // Fetch the languages every time the settings page is loaded
    digibyte_digifacts_reload_languages();
    
    // Try to get the stored languages from the options table.
    $languages = get_option('digibyte_digifacts_languages', array());

    // Display the language selection dropdown
    $current_language = get_option('digibyte_digifacts_language', 'en');
    echo '<select id="digibyte_digifacts_language" name="digibyte_digifacts_language">';
    
    foreach ($languages as $code => $name) {
        printf(
            '<option value="%s" %s>%s</option>',
            esc_attr($code),
            selected($current_language, $code, false),
            esc_html($name)
        );
    }
    echo '</select>';
}

// Fetch and cache remote DigiFacts
function digibyte_digifacts_fetch_facts($language) {
    $transient_key = 'digibyte_digifacts_' . $language;
    $facts = get_transient($transient_key);

    if (false === $facts) {
        $url = "https://digifacts.digibyte.help/?format=html&lang=" . $language;
        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            error_log('Unable to retrieve DigiFacts at this time.');
            return false;
        }

        if (wp_remote_retrieve_response_code($response) !== 200) {
            error_log('Unexpected response code received from the API.');
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        json_decode($body);
        if (json_last_error() === JSON_ERROR_NONE) {
            $facts = json_decode($body, true);
            set_transient($transient_key, $facts, 5 * MINUTE_IN_SECONDS);
        } else {
            error_log('Error decoding DigiFacts: ' . json_last_error_msg());
            return false;
        }
    }

    return $facts;
}

// Shortcode to insert random DigiFact
function digibyte_digifacts_display_shortcode($atts) {
    $language = get_option('digibyte_digifacts_language', 'en');
    if (!$language || $language == '0') {
        error_log('Invalid language code retrieved from settings: ' . $language);
        return 'Error: Invalid language code. Please check the DigiByte DigiFacts settings.';
    }

    $facts = digibyte_digifacts_fetch_facts($language);
    if (!$facts) {
        return 'No DigiFacts available at the moment.';
    }

    $random_key = array_rand($facts);
    $fact = $facts[$random_key];

    ob_start();
    ?>
    <div class="digibyte-digifact">
        <h4 class="digifact-title"><?php echo esc_html($fact['title']); ?></h4>
        <div class="digifact-content"><?php echo wp_kses_post($fact['content']); ?></div>
    </div>
    <?php
    $content = ob_get_clean();
    return $content;
}
add_shortcode('digifacts', 'digibyte_digifacts_display_shortcode');

