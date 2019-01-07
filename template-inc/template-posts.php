<?php
/**
 * Functions which add new post types
 *
 * @package eve
 */



function deltafitness_custom_taxonomies() {

	// Taxonomies for members
	$labels = array(
		'name'              => _x( 'Specialities', 'taxonomy general name', 'eve' ),
		'singular_name'     => _x( 'Speciality', 'taxonomy singular name', 'eve' ),
		'search_items'      => __( 'Search Specialities', 'eve' ),
		'all_items'         => __( 'All Specialities', 'eve' ),
		'parent_item'       => __( 'Parent Speciality', 'eve' ),
		'parent_item_colon' => __( 'Parent Speciality:', 'eve' ),
		'edit_item'         => __( 'Edit Speciality', 'eve' ),
		'update_item'       => __( 'Update Speciality', 'eve' ),
		'add_new_item'      => __( 'Add New Speciality', 'eve' ),
		'new_item_name'     => __( 'New Speciality Name', 'eve' ),
		'menu_name'         => __( 'Specialities', 'eve' ),
	);
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		// 'update_count_callback' => '_update_post_term_count',
		'rewrite'           => array( 'slug' => 'speciality' ),
	);
	register_taxonomy( 'speciality', array( 'members' ), $args );


	// Taxonomies for transformations
	$labels = array(
		'name'                       => _x( 'Types', 'taxonomy general name', 'eve' ),
		'singular_name'              => _x( 'Type', 'taxonomy singular name', 'eve' ),
		'search_items'               => __( 'Search Types', 'eve' ),
		'popular_items'              => __( 'Popular Types', 'eve' ),
		'all_items'                  => __( 'All Types', 'eve' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Type', 'eve' ),
		'update_item'                => __( 'Update Type', 'eve' ),
		'add_new_item'               => __( 'Add New Type', 'eve' ),
		'new_item_name'              => __( 'New Type Name', 'eve' ),
		'separate_items_with_commas' => __( 'Separate types with commas', 'eve' ),
		'add_or_remove_items'        => __( 'Add or remove types', 'eve' ),
		'choose_from_most_used'      => __( 'Choose from the most used types', 'eve' ),
		'not_found'                  => __( 'No types found.', 'eve' ),
		'menu_name'                  => __( 'Types', 'eve' ),
	);

	$args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'type' ),
	);
	register_taxonomy( 'type', 'transformations', $args );

}
// add_action( 'init', 'deltafitness_custom_taxonomies', 0 );



function register_my_custom_submenu_page()
{
	add_submenu_page( 'edit.php?post_type=members', 'Settings', 'Settings', 'manage_options', 'members-settings', 'my_custom_submenu_page_callback' );
	add_submenu_page( 'edit.php?post_type=transformations', 'Settings', 'Settings', 'manage_options', 'transformations-settings', 'my_custom_submenu_page_callback' );
}
// add_action('admin_menu', 'register_my_custom_submenu_page');



function my_custom_submenu_page_callback()
{
	echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
		echo '<h2>Settings</h2>';
	echo '</div>';
}