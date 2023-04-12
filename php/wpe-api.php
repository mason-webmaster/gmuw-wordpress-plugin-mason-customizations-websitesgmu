<?php

/**
 * Summary: php file which implements WPE API functionality
 */

function gmuw_websitesgmu_wpe_api_request($user_name,$user_pass,$request_url) {

	// initialize variables
	$return_value='';

	// initalize cURL request
	$ch = curl_init();

	// set cURL options
	curl_setopt( $ch, CURLOPT_URL, $request_url );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );

	// set cURL headers
	$headers = array();
	$cred_string = $user_name . ":" . $user_pass;
	$headers[] = "Authorization: Basic " . base64_encode($cred_string);
	
	// set cURL options
	curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

	// get result
	$result = curl_exec( $ch );

	if ( curl_errno( $ch ) ) {
	    $return_value = 'Error:' . curl_error( $ch );
	} else {
		$return_value = $result;
	}

    // close cURL request
	curl_close( $ch );
		
	// return value
	return $return_value;

}
