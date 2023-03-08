<?php

/**
 * Summary: php file which implements the custom taxonomies
 */


// Register taxonomies
add_action('init', function(){

	// Register taxonomies. Register additional taxonomies here as needed.

	// Register departments taxonomy
		register_taxonomy(
			'department',
			'website',
			array(
			'hierarchical' => true,
			'labels' => array(
				'name' => 'Department(s)',
				'singular_name' => 'Department',
				'search_items' =>  'Search Departments',
				'all_items' => 'All Departments',
				'parent_item' => 'Parent Department',
				'parent_item_colon' => 'Parent Department:',
				'edit_item' => 'Edit Department',
				'update_item' => 'Update Department',
				'add_new_item' => 'Add New Department',
				'new_item_name' => 'New Department',
				'menu_name' => 'Departments',
				),
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'department' ),
			'show_admin_column' => true,
			'show_in_rest' => true
			)
		);

	// Register web host taxonomy
		register_taxonomy(
			'web_host',
			'website',
			array(
			'hierarchical' => true,
			'labels' => array(
				'name' => 'Web Host(s)',
				'singular_name' => 'Web Host',
				'search_items' =>  'Search Web Hosts',
				'all_items' => 'All Web Hosts',
				'parent_item' => 'Parent Web Host',
				'parent_item_colon' => 'Parent Web Host:',
				'edit_item' => 'Edit Web Host',
				'update_item' => 'Update Web Host',
				'add_new_item' => 'Add New Web Host',
				'new_item_name' => 'New Web Host',
				'menu_name' => 'Web Hosts',
				),
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'web_host' ),
			'show_admin_column' => true,
			'show_in_rest' => true
			)
		);

	// Register cms taxonomy
		register_taxonomy(
			'cms',
			'website',
			array(
			'hierarchical' => false,
			'labels' => array(
				'name' => 'CMS(s)',
				'singular_name' => 'Content Management System',
				'search_items' =>  'Search Content Management Systems',
				'all_items' => 'All Content Management Systems',
				'parent_item' => 'Parent Content Management System',
				'parent_item_colon' => 'Parent Content Management System:',
				'edit_item' => 'Edit Content Management System',
				'update_item' => 'Update Content Management System',
				'add_new_item' => 'Add New Content Management System',
				'new_item_name' => 'New Content Management System',
				'menu_name' => 'Content Management Systems',
				),
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'web_host' ),
			'show_admin_column' => true,
			'show_in_rest' => true
			)
		);

});
