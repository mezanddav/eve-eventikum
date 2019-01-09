<?php

function eve_events_cpt() {

  // Tax Events
  $taxonomy_labels = array(
    'name'                       => _x( 'Kategóriák', 'taxonomy general name', 'eve' ),
    'singular_name'              => _x( 'Kategória', 'taxonomy singular name', 'eve' ),
    'search_items'               => __( 'Keresés a kategóriák között', 'eve' ),
    'popular_items'              => __( 'Gyakori kategóriák', 'eve' ),
    'all_items'                  => __( 'Összes kategória', 'eve' ),
    'parent_item'                => null,
    'parent_item_colon'          => null,
    'edit_item'                  => __( 'Kategória szerkesztése', 'eve' ),
    'update_item'                => __( 'Kategória frissítése', 'eve' ),
    'add_new_item'               => __( 'Új kategória hozzáadása', 'eve' ),
    'new_item_name'              => __( 'New Type Name', 'eve' ),
    'separate_items_with_commas' => __( 'Separate types with commas', 'eve' ),
    'add_or_remove_items'        => __( 'Add or remove types', 'eve' ),
    'choose_from_most_used'      => __( 'Choose from the most used types', 'eve' ),
    'not_found'                  => __( 'No types found.', 'eve' ),
    'menu_name'                  => __( 'Kategóriák', 'eve' ),
  );

  $taxonomy_args = array(
    'hierarchical'          => false,
    'labels'                => $taxonomy_labels,
    'show_ui'               => true,
    'show_admin_column'     => true,
    'update_count_callback' => '_update_post_term_count',
    'show_in_rest'       => true,
    'query_var'             => true,
    'rewrite'               => array( 'slug' => 'kategoria' ),
  );

  register_taxonomy( 'category', 'events', $taxonomy_args );


  // CPT Events
  $labels = array(
    'name'                  => _x( 'Események', 'Post type general name', 'eve' ),
    'singular_name'         => _x( 'Esemény', 'Post type singular name', 'eve' ),
    'menu_name'             => _x( 'Események', 'Admin Menu text', 'eve' ),
    'name_admin_bar'        => _x( 'Esemény', 'Add New on Toolbar', 'eve' ),
    'add_new'               => __( 'Hozzáadás', 'eve' ),
    'add_new_item'          => __( 'Esemény hozzáadása', 'eve' ),
    'new_item'              => __( 'Új esemény', 'eve' ),
    'edit_item'             => __( 'Esemény szerkesztése', 'eve' ),
    'view_item'             => __( 'Esemény megtekintése', 'eve' ),
    'all_items'             => __( 'Összes esemény', 'eve' ),
    'search_items'          => __( 'Keresés a események között', 'eve' ),
    'parent_item_colon'     => __( 'Parent események:', 'eve' ),
    'not_found'             => __( 'No események found.', 'eve' ),
    'not_found_in_trash'    => __( 'No események found in Trash.', 'eve' ),
    'featured_image'        => _x( 'Esemény fő képe (1:1,3:1)', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'eve' ),
    'set_featured_image'    => _x( 'Esemény kép beállítása', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'eve' ),
    'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'eve' ),
    'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'eve' ),
    'archives'              => _x( 'Esemény Események', 'The post type archive label used in nav menus. Default “Post Események. Added in 4.4', 'eve' ),
    'insert_into_item'      => _x( 'Insert into judet', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'eve' ),
    'uploaded_to_this_item' => _x( 'Uploaded to this judet', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'eve' ),
    'filter_items_list'     => _x( 'Filter Események list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'eve' ),
    'items_list_navigation' => _x( 'Események list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'eve' ),
    'items_list'            => _x( 'Események list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'eve' ),
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'esemenyek' ),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => true,
    'taxonomies'  			 => array( 'category' ),
    'menu_position'      => null,
    'show_in_rest'       => true,
    'menu_icon'          => 'dashicons-tickets-alt',
    'supports'           => array( 'title', 'editor', 'thumbnail' ),
  );

  register_post_type( 'events', $args );

}
add_action( 'init', 'eve_events_cpt', 0 );