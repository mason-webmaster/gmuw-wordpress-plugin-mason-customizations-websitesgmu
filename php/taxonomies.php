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
				'name' => 'Departments',
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
			)
		);

});
