<?php

/**
 * Summary: php file which implements custom URL functionality
 */


//Custom link check feature
add_action('parse_request', 'gmuw_custom_url_handler_link_check');
function gmuw_custom_url_handler_link_check() {

  //define pattern
  $pattern = "/^\/gmuw-link-check/i";

  //does the server request uri match the pattern?
  if(preg_match($pattern, $_SERVER["REQUEST_URI"])==1) {

    //display custom content
    include( plugin_dir_path( __DIR__ ) . 'templates/link-check.php' );
    exit();

  }

}