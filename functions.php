<?php
/**
 * eve functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package eve
 */

if ( ! function_exists( 'eve_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function eve_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on eve, use a find and replace
		 * to change 'eve' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'eve', get_template_directory() . '/languages' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );


		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );


		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'header-menu' => esc_html__( 'Header Menu', 'eve' ),
		) );


		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );


		## Image sizes

		# 1:1 / 10x10 Thumbnail size for: Events, Organizers, Presenters, Gallery
		add_image_size( 'one-one-image-thumb', 20, 20, true );
		add_image_size( 'one-one-gallery-thumb', 100, 100, true );
		
		# 1:1 / 500x500 Full size for: Events, Organizers, Presenters
		add_image_size( 'one-one-image-full', 500, 500, true );

		# 3:1 / 60x20 Thumbnail size for: Events
		add_image_size( 'tree-one-events-thumb', 60, 20, true );
		
		# 3:1 / 900x300 Full size for: Events
		add_image_size( 'tree-one-events-full', 900, 300, true );

		# 2:1 / 60x30 Thumbnail size for: Events
		add_image_size( 'two-one-events-thumb', 60, 30, true );

		# 2:1 / 900x450 Full size for mobile: Events
		add_image_size( 'two-one-events-full', 900, 450, true );
	}
endif;
add_action( 'after_setup_theme', 'eve_setup' );



require get_theme_file_path('template-inc/cpt-events.php');
require get_theme_file_path('template-inc/cpt-presenters.php');
require get_theme_file_path('template-inc/cpt-organizers.php');



/**
 * WP Init
 */
function eve_init()
{
  // Display the links to the extra feeds such as category feeds
  remove_action( 'wp_head', 'feed_links_extra', 3 );

  // Display the link to the Really Simple Discovery service endpoint, EditURI link
  remove_action( 'wp_head', 'rsd_link' );

  // Display the link to the Windows Live Writer manifest file.
  remove_action( 'wp_head', 'wlwmanifest_link' );

  // Display the XHTML generator that is generated on the wp_head hook, WP version
  remove_action( 'wp_head', 'wp_generator' );
  remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
  remove_action( 'wp_head', 'feed_links', 2 );

  // Turn off oEmbed auto discovery.
  // Don't filter oEmbed results.
  remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10);

  // Remove oEmbed discovery links.
  remove_action( 'wp_head', 'wp_oembed_add_discovery_links');

  // Remove oEmbed-specific JavaScript from the front-end and back-end.
  remove_action( 'wp_head', 'wp_oembed_add_host_js');

  //Remove emoji script
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );

  // Remove emoji style from head
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  // add_filter( 'tiny_mce_plugins', 'ayn_disable_emojis_tinymce' );
  add_filter( 'emoji_svg_url', '__return_false' );

  // Remove recent comments style
  add_filter( 'show_recent_comments_widget_style', function() { return false; });

  // Disable feed
  add_action( 'do_feed', 'eve_itsme_disable_feed', 1 );
  add_action( 'do_feed_rdf', 'eve_itsme_disable_feed', 1 );
  add_action( 'do_feed_rss', 'eve_itsme_disable_feed', 1 );
  add_action( 'do_feed_rss2', 'eve_itsme_disable_feed', 1 );
  add_action( 'do_feed_atom', 'eve_itsme_disable_feed', 1 );
  add_action( 'do_feed_rss2_comments', 'eve_itsme_disable_feed', 1 );
  add_action( 'do_feed_atom_comments', 'eve_itsme_disable_feed', 1 );
}
add_action( 'init', 'eve_init', 666 );



/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function eve_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar for pages', 'eve' ),
		'id'            => 'sidebar-page',
		'description'   => esc_html__( 'Add widgets here.', 'eve' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar for blog', 'eve' ),
		'id'            => 'sidebar-single',
		'description'   => esc_html__( 'Add widgets here.', 'eve' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Esemény sidebar', 'eve' ),
		'id'            => 'sidebar-events',
		'description'   => esc_html__( 'Add widgets here.', 'eve' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Esemény hamarosan sidebar', 'eve' ),
		'id'            => 'sidebar-soon',
		'description'   => esc_html__( 'Add widgets here.', 'eve' )
	) );
}
add_action( 'widgets_init', 'eve_widgets_init' );


/**
 * Enqueue scripts and styles.
 */
function eve_scripts()
{
	if( file_exists( get_template_directory() . '/inline.css' ) ){
		add_action( 'wp_head', 'progresseve_inline_style', 100 );
	}else{
		wp_enqueue_style( 'eve-style', get_stylesheet_uri(), array(), false );
	}
	wp_enqueue_script( 'jquery' );
	add_action( 'wp_footer', 'progresseve_scripts', 1 );
	wp_enqueue_style( 'progresseve-fonts', progresseve_fonts_url(), array(), null );
	// wp_dequeue_style( 'wp-block-library' );
	// wp_deregister_style( 'wp-block-library' );
	// add_action( 'wp_footer', 'progresseve_dynamic_vars', 0 );
	
	// Plugins
	// add_action( 'wp_footer', 'progresseve_cf7_api_settings', 0 );

	// wp_enqueue_script( 'eve-scripts', get_template_directory_uri() . '/js/eve.js', array(), '20151215', true );
	// if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
	// 	wp_enqueue_script( 'comment-reply' );
	// }

	if( is_singular('events') || is_front_page() ) {
		add_action( 'wp_footer', 'eve_photoswipe_gallery_dom', 20 );
	}
}
add_action( 'wp_enqueue_scripts', 'eve_scripts' );



/**
 * Progresseve improvements
 */
require get_template_directory() . '/template-inc/template-functions.php';

// Init and other
add_filter( 'body_class', 'eve_body_classes' ); // Adding body classes
add_filter( 'wp_resource_hints', 'progresseve_resource_hints', 0, 2 );
add_filter( 'xmlrpc_methods', 'ayn_remove_xmlrpc_pingback_ping' );
add_filter( 'wp_default_scripts', 'eve_remove_jquery_migrate' );
add_action( 'admin_menu', 'eve_admin_remove_menus' );
add_action( 'wp_before_admin_bar_render', 'eve_admin_bar_render' );
add_filter( 'widget_text', 'do_shortcode' );

// Blog and posts
// add_action( 'pre_get_posts', 'eve_ignore_sticky' ); // Ignore sticky posts or not?
// add_filter( 'excerpt_length', 'custom_excerpt_length', 999 ); // Excerpt length
// add_filter( 'excerpt_more', 'new_excerpt_more' ); // Read more button text
// add_action( 'template_redirect', 'eve_cpt_redirect_post' ); // Redirect post type
// add_action( 'pre_get_posts', 'eve_cpt_change_sort_order'); // Change post order

// Admin actions
add_action( 'admin_init', 'eve_general_section' );
if( get_option('eve_html_compression') == 1 && get_option('eve_environment_details') == 1 ){
	add_action( 'get_header', 'eve_html_compression_start' );
}
add_filter( 'acf/fields/google_map/api', 'eve_acf_google_map_api' );

// Template actions
add_action( 'wp_footer', 'eve_theme_icons', 10 );

// GTM Tracking Code
add_action( 'wp_head', 'progresseve_gtm_core', 5 );
add_action( 'eve_body_opening', 'progresseve_gtm_noscript', 10 );

/**
 * Progresseve template views
 */
require get_template_directory() . '/template-inc/template-views.php';

/**
 * Gallery metabox
 */
require get_template_directory() . '/template-inc/admin/gallery-metabox/gallery-metabox.php';