<?php

function eve_presenters_cpt() {

  // CPT Presenters
  $labels = array(
      'name'                  => _x( 'Előadók', 'Post type general name', 'eve' ),
      'singular_name'         => _x( 'Előadó', 'Post type singular name', 'eve' ),
      'menu_name'             => _x( 'Előadók', 'Admin Menu text', 'eve' ),
      'name_admin_bar'        => _x( 'Előadó', 'Add New on Toolbar', 'eve' ),
      'add_new'               => __( 'Hozzáadás', 'eve' ),
      'add_new_item'          => __( 'Előadó hozzáadása', 'eve' ),
      'new_item'              => __( 'Új előadó', 'eve' ),
      'edit_item'             => __( 'Előadó szerkesztése', 'eve' ),
      'view_item'             => __( 'Előadó megtekintése', 'eve' ),
      'all_items'             => __( 'Összes előadó', 'eve' ),
      'search_items'          => __( 'Keresés az előadók között', 'eve' ),
      'parent_item_colon'     => __( 'Parent Előadók:', 'eve' ),
      'not_found'             => __( 'No Előadók found.', 'eve' ),
      'not_found_in_trash'    => __( 'No Előadók found in Trash.', 'eve' ),
      'featured_image'        => _x( 'Előadó képe (1:1)', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'eve' ),
      'set_featured_image'    => _x( 'Előadó kép beállítása', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'eve' ),
      'remove_featured_image' => _x( 'Előadó kép eltávolítása', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'eve' ),
      'use_featured_image'    => _x( 'Használd mint előadó kép', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'eve' ),
      'archives'              => _x( 'Előadók', 'The post type archive label used in nav menus. Default “Post Előadók. Added in 4.4', 'eve' ),
      'insert_into_item'      => _x( 'Insert into judet', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'eve' ),
      'uploaded_to_this_item' => _x( 'Uploaded to this judet', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'eve' ),
      'filter_items_list'     => _x( 'Filter Előadók list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'eve' ),
      'items_list_navigation' => _x( 'Előadók list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'eve' ),
      'items_list'            => _x( 'Előadók list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'eve' ),
  );

  $args = array(
      'labels'             => $labels,
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'query_var'          => true,
      'rewrite'            => array( 'slug' => 'eloadok' ),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'menu_position'      => null,
      'show_in_rest'       => true,
      'menu_icon'          => 'dashicons-businessman',
      'supports'           => array( 'title', 'editor', 'thumbnail' ),
  );

  register_post_type( 'presenters', $args );

}
add_action( 'init', 'eve_presenters_cpt', 0 );