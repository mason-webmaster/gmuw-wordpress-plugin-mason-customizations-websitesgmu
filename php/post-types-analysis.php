<?php
/**
 * Summary: php file which contains functions to perform analysis on custom post types
 */


function gmuw_websitesgmu_get_custom_posts($post_type,$count_mode,$meta_key='',$meta_value='',$tax_key='',$tax_value=''){

  //Set basic arguments for the get posts function
  $args = array(
      'post_type'  => $post_type,
      'post_status' => 'publish',
      'nopaging' => true,
      'order' => 'ASC',
      'orderby' => 'name'
  );  

  // get meta query args based on mode
  switch ($count_mode) {
    case 'all':
      $args_meta = array();
      break;
    case 'deleted':
      $args_meta = array(
        'meta_query' => array(
          array(
            'key'   => 'deleted',
            'value' => '1',
            'compare' => '=',
          ),
        )
      );
      break;
    case 'not-deleted':

      // Do we have a meta_key to find?
			if (empty($meta_key)) {

				// We are just looking for all non-deleted records
	      $args_meta = array(
	        'meta_query' => array(
	          array(
	            'relation' => 'OR',
	            array(
	              'key'   => 'deleted',
	              'compare' => 'NOT EXISTS',
	            ),
	            array(
	              'key'   => 'deleted',
	              'value' => '1',
	              'compare' => '!=',
	            ),
	          )
	        )
	      );

			} else {

				// We are looking for a particular meta key

				// Are we looking for a particular value?

				if (empty($meta_value)) {

					//We are not looking for a particular meta value, just whether the key record exists and has a non-empty value

				  $args_meta = array(
				    'meta_query' => array(
				      array(
				        'relation' => 'AND',
				        array(
				          'relation' => 'OR',
				          array(
				            'key'   => 'deleted',
				            'compare' => 'NOT EXISTS',
				          ),
				          array(
				            'key'   => 'deleted',
				            'value' => '1',
				            'compare' => '!=',
				          ),
				        ),
				        array(
				          'relation' => 'AND',
				          array(
				            'key'   => $meta_key,
				            'compare' => 'EXISTS',
				          ),
				          array(
				            'key'   => $meta_key,
				            'value' => '',
				            'compare' => '!=',
				          ),
				        ),
				      )
				    )
				  );

				} else {

					//We are looking for a particular meta value

				  $args_meta = array(
				    'meta_query' => array(
				      array(
				        'relation' => 'AND',
				        array(
				          'relation' => 'OR',
				          array(
				            'key'   => 'deleted',
				            'compare' => 'NOT EXISTS',
				          ),
				          array(
				            'key'   => 'deleted',
				            'value' => '1',
				            'compare' => '!=',
				          ),
				        ),
				        array(
				          'key'   => $meta_key,
				          'value' => $meta_value,
				          'compare' => '=',
				        ),
				      )
				    )
				  );

				}

			}
			break;
  }

  // get tax query args based on parameter
  if (empty($tax_key)) {
		// We don't have a taxonomy query
      $args_tax = array();
  } else {
		// We do have a taxonomy query
		$args_tax = array(
			'tax_query' => array(
				array(
					'taxonomy' => $tax_key,
					'field'    => 'slug',
					'terms'    => array($tax_value)
				)
			)
		);
  }

  // merge arg and tax arrays
  $args_full = array_merge($args, $args_meta, $args_tax);

  // Get posts
  $posts = get_posts($args_full);

	// Return posts
	return $posts;

}

/**
 * Get totals summaries for a custom post type
 */
function gmuw_websitesgmu_get_cpt_totals($post_type) {

  // Initialize variables
  $return_value='';

  //Get numbers of deleted/non-deleted
  $count_non_deleted=count(gmuw_websitesgmu_get_custom_posts($post_type,'not-deleted'));
  $count_deleted=count(gmuw_websitesgmu_get_custom_posts($post_type,'deleted'));

  //Start building return value
  $return_value.='<p>';
  $return_value.='<strong>'.$count_non_deleted.' Records</strong><br />';

  //Do we have deleted?
  if ($count_deleted>1) {
    $return_value.='('.$count_deleted.' deleted; '.count(gmuw_websitesgmu_get_custom_posts($post_type,'all')).' total)';
  }

  //Finish building return value
  $return_value.='</p>';

  //Return value
  return $return_value;

}
