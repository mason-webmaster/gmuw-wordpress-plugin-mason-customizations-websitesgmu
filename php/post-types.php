<?php

/**
 * Summary: php file which implements the custom post types
 */


/**
 * Register custom post types
 */
add_action('init', function(){

    // Register website custom post type.

    // Define labels for website post type
    $labels = array(
        'name'                  => 'Websites',
        'singular_name'         => 'Website',
        'menu_name'             => 'Websites',
        'name_admin_bar'        => 'Website',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New Website',
        'new_item'              => 'New Website',
        'edit_item'             => 'Edit Website',
        'view_item'             => 'View Website',
        'all_items'             => 'All Websites',
        'search_items'          => 'Search Websites',
        'parent_item_colon'     => 'Parent Website:',
        'not_found'             => 'No Websites found.',
        'not_found_in_trash'    => 'No Websites found in Trash.',
        'featured_image'        => 'Website Image',
        'set_featured_image'    => 'Set website image',
        'remove_featured_image' => 'Remove website image',
        'use_featured_image'    => 'Use as website image',
        'archives'              => 'Websites archives',
        'insert_into_item'      => 'Insert into website',
        'uploaded_to_this_item' => 'Uploaded to this website',
        'filter_items_list'     => 'Filter website list',
        'items_list_navigation' => 'Website list navigation',
        'items_list'            => 'Website list',
    );

    // Set up arguments for the register_post_type function
    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'show_ui'           => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'website'),
        'capability_type'   => 'post',
        'has_archive'       => true,
        'hierarchical'      => false,
        'menu_position'     => 20,
        'menu_icon'         => 'dashicons-admin-site',
        'show_in_rest'      => true,
        'supports'          => array('title', 'editor', 'thumbnail'),
    );

    // Register website custom post type
    register_post_type('website', $args);


    // Register ga_account custom post type.

    // Define labels for ga_account post type
    $labels = array(
        'name'                  => 'GA Account(s)',
        'singular_name'         => 'GA Account',
        'menu_name'             => 'GA Accounts',
        'name_admin_bar'        => 'GA Account',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New GA Account',
        'new_item'              => 'New GA Account',
        'edit_item'             => 'Edit GA Account',
        'view_item'             => 'View GA Account',
        'all_items'             => 'All GA Accounts',
        'search_items'          => 'Search GA accounts',
        'parent_item_colon'     => 'Parent GA account:',
        'not_found'             => 'No GA accounts found.',
        'not_found_in_trash'    => 'No GA accounts found in Trash.',
        'featured_image'        => 'GA Account Image',
        'set_featured_image'    => 'Set GA account image',
        'remove_featured_image' => 'Remove GA account image',
        'use_featured_image'    => 'Use as GA account image',
        'archives'              => 'GA Accounts archives',
        'insert_into_item'      => 'Insert into GA account',
        'uploaded_to_this_item' => 'Uploaded to this GA account',
        'filter_items_list'     => 'Filter GA account list',
        'items_list_navigation' => 'GA account list navigation',
        'items_list'            => 'GA account list',
    );

    // Set up arguments for the register_post_type function
    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'show_ui'           => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'ga_account'),
        'capability_type'   => 'post',
        'has_archive'       => true,
        'hierarchical'      => false,
        'menu_position'     => 20,
        'menu_icon'         => 'dashicons-category',
        'show_in_rest'      => true,
        'supports'          => array('title', 'editor', 'thumbnail'),
    );

    // Register ga_account custom post type
    register_post_type('ga_account', $args);


    // Register ga_property custom post type.

    // Define labels for ga_property post type
    $labels = array(
        'name'                  => 'GA Property',
        'singular_name'         => 'GA Property',
        'menu_name'             => 'GA Properties',
        'name_admin_bar'        => 'GA Property',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New GA Property',
        'new_item'              => 'New GA Property',
        'edit_item'             => 'Edit GA Property',
        'view_item'             => 'View GA Property',
        'all_items'             => 'All GA Properties',
        'search_items'          => 'Search GA properties',
        'parent_item_colon'     => 'Parent GA property:',
        'not_found'             => 'No GA properties found.',
        'not_found_in_trash'    => 'No GA properties found in Trash.',
        'featured_image'        => 'GA Property Image',
        'set_featured_image'    => 'Set GA property image',
        'remove_featured_image' => 'Remove GA property image',
        'use_featured_image'    => 'Use as GA property image',
        'archives'              => 'GA Properties archives',
        'insert_into_item'      => 'Insert into GA property',
        'uploaded_to_this_item' => 'Uploaded to this GA property',
        'filter_items_list'     => 'Filter GA property list',
        'items_list_navigation' => 'GA property list navigation',
        'items_list'            => 'GA property list',
    );

    // Set up arguments for the register_post_type function
    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'show_ui'           => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'ga_property'),
        'capability_type'   => 'post',
        'has_archive'       => true,
        'hierarchical'      => false,
        'menu_position'     => 20,
        'menu_icon'         => 'dashicons-analytics',
        'show_in_rest'      => true,
        'supports'          => array('title', 'editor', 'thumbnail'),
    );

    // Register ga_property custom post type
    register_post_type('ga_property', $args);


    // Register gtm_account custom post type.

    // Define labels for gtm_account post type
    $labels = array(
        'name'                  => 'GTM Account(s)',
        'singular_name'         => 'GTM Account',
        'menu_name'             => 'GTM Accounts',
        'name_admin_bar'        => 'GTM Account',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New GTM Account',
        'new_item'              => 'New GTM Account',
        'edit_item'             => 'Edit GTM Account',
        'view_item'             => 'View GTM Account',
        'all_items'             => 'All GTM Accounts',
        'search_items'          => 'Search GTM accounts',
        'parent_item_colon'     => 'Parent GTM account:',
        'not_found'             => 'No GTM accounts found.',
        'not_found_in_trash'    => 'No GTM accounts found in Trash.',
        'featured_image'        => 'GTM Account Image',
        'set_featured_image'    => 'Set GTM account image',
        'remove_featured_image' => 'Remove GTM account image',
        'use_featured_image'    => 'Use as GTM account image',
        'archives'              => 'GTM Accounts archives',
        'insert_into_item'      => 'Insert into GTM account',
        'uploaded_to_this_item' => 'Uploaded to this GTM account',
        'filter_items_list'     => 'Filter GTM account list',
        'items_list_navigation' => 'GTM account list navigation',
        'items_list'            => 'GTM account list',
    );

    // Set up arguments for the register_post_type function
    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'show_ui'           => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'gtm_account'),
        'capability_type'   => 'post',
        'has_archive'       => true,
        'hierarchical'      => false,
        'menu_position'     => 20,
        'menu_icon'         => 'dashicons-category',
        'show_in_rest'      => true,
        'supports'          => array('title', 'editor', 'thumbnail'),
    );

    // Register gtm_account custom post type
    register_post_type('gtm_account', $args);


    // Register gtm_container custom post type.

    // Define labels for gtm_container post type
    $labels = array(
        'name'                  => 'GTM Container',
        'singular_name'         => 'GTM Container',
        'menu_name'             => 'GTM Containers',
        'name_admin_bar'        => 'GTM Container',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New GTM Container',
        'new_item'              => 'New GTM Container',
        'edit_item'             => 'Edit GTM Container',
        'view_item'             => 'View GTM Container',
        'all_items'             => 'All GTM Containers',
        'search_items'          => 'Search GTM containers',
        'parent_item_colon'     => 'Parent GTM container:',
        'not_found'             => 'No GTM containers found.',
        'not_found_in_trash'    => 'No GTM containers found in Trash.',
        'featured_image'        => 'GTM Container Image',
        'set_featured_image'    => 'Set GTM container image',
        'remove_featured_image' => 'Remove GTM container image',
        'use_featured_image'    => 'Use as GTM container image',
        'archives'              => 'GTM Containers archives',
        'insert_into_item'      => 'Insert into GTM container',
        'uploaded_to_this_item' => 'Uploaded to this GTM container',
        'filter_items_list'     => 'Filter GTM container list',
        'items_list_navigation' => 'GTM container list navigation',
        'items_list'            => 'GTM container list',
    );

    // Set up arguments for the register_post_type function
    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'show_ui'           => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'gtm_container'),
        'capability_type'   => 'post',
        'has_archive'       => true,
        'hierarchical'      => false,
        'menu_position'     => 20,
        'menu_icon'         => 'dashicons-index-card',
        'show_in_rest'      => true,
        'supports'          => array('title', 'editor', 'thumbnail'),
    );

    // Register gtm_container custom post type
    register_post_type('gtm_container', $args);

});


// Customize custom post type admin pages

// Websites

// Add additional columns to the admin list
add_filter ('manage_website_posts_columns', 'gmuw_websitesgmu_add_columns_website');
function gmuw_websitesgmu_add_columns_website ($columns) {

    return array_merge ( $columns, array ( 
        //ACF fields
        'environment_name'   => 'Env. Name',
        'hosting_domain' => 'Hosting Domain',
        'production_domain'   => 'Prod Domain',
        'wordpress_theme'   => 'WP Theme',
        'website_status'   => 'Status',
        //Other fields
        'wordpress_data_feeds' => 'WP Data Feeds',
        'wordpress_theme_live' => 'Live WP Theme',
        'admin_login' => 'Admin Login',
        'web_host_admin' => 'Web Host Admin',
    ) );

}

// Generate field output for additional columns in the admin list
add_action ('manage_website_posts_custom_column', 'gmuw_websitesgmu_website_custom_column', 10, 2);
function gmuw_websitesgmu_website_custom_column ($column, $post_id) {

    switch ($column) {
        case 'environment_name':
            echo get_post_meta($post_id, 'environment_name', true);
            break;
        case 'hosting_domain':
            if (has_term('wpengine', 'web_host')) {
            echo '<a href="https://'.get_post_meta($post_id, 'environment_name', true).'.wpengine.com" target="_blank">' . get_post_meta($post_id, 'environment_name', true) . 'wpengine.com</a>';
            }
            if (has_term('materiell', 'web_host')) {
            echo '<a href="https://'.get_post_meta($post_id, 'environment_name', true).'.materiellcloud.com" target="_blank">' . get_post_meta($post_id, 'environment_name', true) . 'materiellcloud.com</a>';
            }
            if (has_term('acquia-cloud-site-factory', 'web_host')) {
            echo '<a href="https://'.get_post_meta($post_id, 'environment_name', true).'.sitemasonry.gmu.edu" target="_blank">' . get_post_meta($post_id, 'environment_name', true) . '.sitemasonry.gmu.edu</a>';
            }
            break;
        case 'production_domain':
            echo '<a href="https://'.get_post_meta($post_id, 'production_domain', true).'" target="_blank">' . get_post_meta ($post_id, 'production_domain', true) . '</a>';
            break;
        case 'wordpress_theme':
            if (has_term('wordpress', 'cms')) {
                echo get_post_meta($post_id, 'wordpress_theme', true);
            }
            break;
        case 'website_status':
            echo get_post_meta($post_id, 'website_status', true);
            break;
        case 'wordpress_data_feeds':
            if (get_post_meta($post_id, 'website_status', true)!='deleted') {
                if (has_term('wordpress', 'cms')) {
                    if (has_term('materiell','web_host')) {
                        $hosting_domain='https://'.get_post_meta($post_id, 'environment_name', true).'.materiellcloud.com';
                    }
                    if (has_term('wpengine','web_host')) {
                        $hosting_domain='https://'.get_post_meta($post_id, 'environment_name', true).'.wpengine.com';
                }
                echo '<a href="'.$hosting_domain.'/wp-json/gmuj-sci/theme-info">theme info</a><br /><a href="'.$hosting_domain.'/wp-json/gmuj-sci/most-recent-modifications">modifications</a><br /><a href="'.$hosting_domain.'/wp-json/gmuj-mmi/mason-site-info">site info</a>';
                }
            }
            break;
        case 'wordpress_theme_live':
            if (get_post_meta($post_id, 'website_status', true)!='deleted') {
                if (has_term('wordpress', 'cms')) {
                    if ($_GET['live_theme_check']=='1') {
                        echo gmuj_websitesgmu_get_live_website_theme($post_id);
                    } else {
                        echo '<a href="'.$_SERVER['REQUEST_URI'].'&live_theme_check=1">enable</a>';
                    }
                }
            }
            break;
        case 'admin_login':
            if (get_post_meta($post_id, 'website_status', true)!='deleted') {
                if (has_term('wpengine', 'web_host')) {
                    echo '<a href="https://'.get_post_meta($post_id, 'environment_name', true).'.wpengine.com/wp-admin/" target="_blank" title="WordPress login"><img style="width:25px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-wordpress.png'.'" /></a>';
                }
                if (has_term('materiell', 'web_host')) {
                    echo '<a href="https://'.get_post_meta($post_id, 'environment_name', true).'.materiellcloud.com/wp-admin/" target="_blank" title="WordPress login"><img style="width:25px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-wordpress.png'.'" /></a>';
                }
                if (has_term('acquia-cloud-site-factory', 'web_host')) {
                    echo '<a href="https://'.get_post_meta($post_id, 'environment_name', true).'.sitemasonry.gmu.edu/user/login/" target="_blank" title="Drupal login"><img style="width:25px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-drupal.png'.'" /></a>';
                }
            }
            break;
        case 'web_host_admin':
            if (get_post_meta($post_id, 'website_status', true)!='deleted') {
                if (has_term('wpengine', 'web_host')) {
                    //Store web host admin URL
                    $web_host_admin_url='https://my.wpengine.com/installs/'.get_post_meta($post_id, 'environment_name', true).'/';
                    // Output web host admin links
                    echo '<a href="'.$web_host_admin_url.'" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-wpengine.png'.'" /> overview</a><br />';
                    echo '<a href="'.$web_host_admin_url.'cache_dashboard" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-wpengine.png'.'" /> cache</a><br />';
                    echo '<a href="'.$web_host_admin_url.'advanced" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-wpengine.png'.'" /> advanced</a><br />';
                }
                if (has_term('acquia-cloud-site-factory', 'web_host')) {
                    //Store web host admin URL
                      $web_host_admin_url='https://www.georgemasonusf.acsitefactory.com/sites-by-group/list?field_domain_contains='.get_post_meta($post_id, 'environment_name', true).'';
                    // Output web host admin links
                    echo '<a href="'.$web_host_admin_url.'" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-acsf.png'.'" /></a><br />';
                }
            }
            break;
    }

}
