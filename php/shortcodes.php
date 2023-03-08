<?php

/**
 * Summary: php file which implements the custom shortcodes
 */


// Add shortcodes on init
add_action('init', function(){

    // Add shortcodes. Add additional shortcodes here as needed.

    // Add example shortcode
    add_shortcode(
        'gmuw_websitesgmu_shortcode', //shortcode label (use as the shortcode on the site)
        'gmuw_websitesgmu_shortcode' //callback function
    );

});

// Define shortcode callback functions. Add additional shortcode functions here as needed.

// Define example shortcode
function gmuw_websitesgmu_shortcode(){

    // Determine return value
    $content='set what the shortcode will do/say...';

    // Return value
    return $content;

}
