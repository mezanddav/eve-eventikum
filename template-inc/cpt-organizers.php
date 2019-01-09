<?php

function eve_organizers_cpt() {

  // CPT Organizers
  $labels = array(
      'name'                  => _x( 'Szervezők', 'Post type general name', 'eve' ),
      'singular_name'         => _x( 'Szervező', 'Post type singular name', 'eve' ),
      'menu_name'             => _x( 'Szervezők', 'Admin Menu text', 'eve' ),
      'name_admin_bar'        => _x( 'Szervező', 'Add New on Toolbar', 'eve' ),
      'add_new'               => __( 'Hozzáadás', 'eve' ),
      'add_new_item'          => __( 'Szervező hozzáadása', 'eve' ),
      'new_item'              => __( 'Új szervező', 'eve' ),
      'edit_item'             => __( 'Szervező szerkesztése', 'eve' ),
      'view_item'             => __( 'Szervező megtekintése', 'eve' ),
      'all_items'             => __( 'Összes szervező', 'eve' ),
      'search_items'          => __( 'Keresés a szervezők között', 'eve' ),
      'parent_item_colon'     => __( 'Parent szervezők:', 'eve' ),
      'not_found'             => __( 'No szervezők found.', 'eve' ),
      'not_found_in_trash'    => __( 'No szervezők found in Trash.', 'eve' ),
      'featured_image'        => _x( 'Szervező képe (1:1)', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'eve' ),
      'set_featured_image'    => _x( 'Szervező kép beállítása', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'eve' ),
      'remove_featured_image' => _x( 'Szervező kép eltávolítása', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'eve' ),
      'use_featured_image'    => _x( 'Használd mint szervező kép', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'eve' ),
      'archives'              => _x( 'Szervezők', 'The post type archive label used in nav menus. Default “Post Szervezők. Added in 4.4', 'eve' ),
      'insert_into_item'      => _x( 'Insert into judet', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'eve' ),
      'uploaded_to_this_item' => _x( 'Uploaded to this judet', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'eve' ),
      'filter_items_list'     => _x( 'Filter szervezők list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'eve' ),
      'items_list_navigation' => _x( 'Szervezők list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'eve' ),
      'items_list'            => _x( 'Szervezők list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'eve' ),
  );

  $args = array(
      'labels'             => $labels,
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'query_var'          => true,
      'rewrite'            => array( 'slug' => 'szervezok' ),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'menu_position'      => null,
      'show_in_rest'       => true,
      'menu_icon'          => 'dashicons-groups',
      'supports'           => array( 'title', 'editor', 'thumbnail' ),
  );

  register_post_type( 'organizers', $args );

}
add_action( 'init', 'eve_organizers_cpt', 0 );