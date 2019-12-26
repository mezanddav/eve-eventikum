<?php

function eve_partners_cpt() {

  $type_labels = array(
    'name'                       => _x( 'Típusok', 'taxonomy general name', 'eve' ),
    'singular_name'              => _x( 'Típus', 'taxonomy singular name', 'eve' ),
    'search_items'               => __( 'Keresés a típusok között', 'eve' ),
    'popular_items'              => __( 'Gyakran használt típusok', 'eve' ),
    'all_items'                  => __( 'Összes típus', 'eve' ),
    'parent_item'                => null,
    'parent_item_colon'          => null,
    'edit_item'                  => __( 'Típus szerkesztése', 'eve' ),
    'update_item'                => __( 'Típus frissítése', 'eve' ),
    'add_new_item'               => __( 'Új típus hozzáadása', 'eve' ),
    'new_item_name'              => __( 'New Type Name', 'eve' ),
    'separate_items_with_commas' => __( 'Separate types with commas', 'eve' ),
    'add_or_remove_items'        => __( 'Add or remove types', 'eve' ),
    'choose_from_most_used'      => __( 'Choose from the most used types', 'eve' ),
    'not_found'                  => __( 'No types found.', 'eve' ),
    'menu_name'                  => __( 'Típusok', 'eve' ),
  );
  $type_args = array(
    'hierarchical'          => false,
    'labels'                => $type_labels,
    'show_ui'               => true,
    'show_admin_column'     => true,
    'update_count_callback' => '_update_post_term_count',
    'show_in_rest'          => true,
    'query_var'             => true,
    'rewrite'               => array( 'slug' => 'tipus' ),
  );
  register_taxonomy( 'partner_type', 'partners', $type_args );

  // CPT Presenters
  $labels = array(
      'name'                  => _x( 'Partnerek', 'Post type general name', 'eve' ),
      'singular_name'         => _x( 'Partner', 'Post type singular name', 'eve' ),
      'menu_name'             => _x( 'Partnerek', 'Admin Menu text', 'eve' ),
      'name_admin_bar'        => _x( 'Partner', 'Add New on Toolbar', 'eve' ),
      'add_new'               => __( 'Hozzáadás', 'eve' ),
      'add_new_item'          => __( 'Partner hozzáadása', 'eve' ),
      'new_item'              => __( 'Új partner', 'eve' ),
      'edit_item'             => __( 'Partner szerkesztése', 'eve' ),
      'view_item'             => __( 'Partner megtekintése', 'eve' ),
      'all_items'             => __( 'Összes partner', 'eve' ),
      'search_items'          => __( 'Keresés a partnerek között', 'eve' ),
      'parent_item_colon'     => __( 'Parent partner:', 'eve' ),
      'not_found'             => __( 'No partner found.', 'eve' ),
      'not_found_in_trash'    => __( 'No partner found in Trash.', 'eve' ),
      'featured_image'        => _x( 'Partner képe', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'eve' ),
      'set_featured_image'    => _x( 'Partner kép beállítása', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'eve' ),
      'remove_featured_image' => _x( 'Partner kép eltávolítása', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'eve' ),
      'use_featured_image'    => _x( 'Használd mint patner kép', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'eve' ),
      'archives'              => _x( 'Partnerek', 'The post type archive label used in nav menus. Default “Post partnerek. Added in 4.4', 'eve' ),
      'insert_into_item'      => _x( 'Insert into partner', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'eve' ),
      'uploaded_to_this_item' => _x( 'Uploaded to this partner', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'eve' ),
      'filter_items_list'     => _x( 'Filter partner list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'eve' ),
      'items_list_navigation' => _x( 'Partner list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'eve' ),
      'items_list'            => _x( 'Partner list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'eve' ),
  );

  $args = array(
      'labels'             => $labels,
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'query_var'          => true,
      'rewrite'            => array( 'slug' => 'partner' ),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'menu_position'      => null,
      'show_in_rest'       => true,
      'menu_icon'          => 'dashicons-businessman',
      'supports'           => array( 'title', 'editor', 'thumbnail' ),
  );

  register_post_type( 'partners', $args );

}
add_action( 'init', 'eve_partners_cpt', 0 );