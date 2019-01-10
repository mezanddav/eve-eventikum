<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package eve
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="dns-prefetch" href="//www.googletagmanager.com">
<link rel="dns-prefetch" href="//ajax.googleapis.com">
<?php wp_head(); ?>
<meta name="theme-color" content="#0D494E">
<link rel="icon" sizes="192x192" href="<?php echo get_template_directory_uri(); ?>/img/eve-highres.png">
</head>
<body <?php body_class(); ?>>
<?php do_action( 'eve_body_opening' ); ?>
<div class="site">
<header class="site-header">
	<div class="ctn max">
		<a class="brand-uri" href="<?php echo get_home_url(); ?>">
			<svg class="i i-logo" width="100" height="20.25" title="Eventikum"><use xlink="http://www.w3.org/1999/xlink" xlink:href="#i-logo" href="#i-logo"></use></svg>
		</a>
	</div>
</header>
<div class="site-content">
