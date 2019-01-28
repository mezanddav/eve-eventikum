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
		<div class="brand">
			<a class="brand-uri fr" href="<?php echo get_site_url(); ?>"><img class="brand-img" src="<?php echo get_template_directory_uri(); ?>/img/eventikum-logo.png" alt="<?php echo get_bloginfo('name'); ?>"></a>
		</div>
		<div class="site-header__action">
			<nav id="site-navigation" class="site-header__nav main-navigation">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><span></span><span></span><span></span></button>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'header-menu',
					'menu_id'        => 'header-menu',
					'container'      => false
				) );
				?>
			</nav>
			<div class="site-header__socials">
				<div class="site-header__social"><a href="https://www.facebook.com/eventikum.ro/" target="_blank"><svg class="i i-facebook" width="14" height="14" title="Delta fitness"><use xlink="http://www.w3.org/1999/xlink" xlink:href="#i-facebook" href="#i-facebook"></use></svg></a></div>
				<div class="site-header__social"><a href="https://www.instagram.com/eventikum.ro/" target="_blank"><svg class="i i-instagram" width="14" height="14" title="Delta fitness"><use xlink="http://www.w3.org/1999/xlink" xlink:href="#i-instagram" href="#i-instagram"></use></svg></a></div>
				<div class="site-header__social"><a href="https://m.me/eventikum.ro" target="_blank"><svg class="i i-messenger" width="14" height="14" title="Delta fitness"><use xlink="http://www.w3.org/1999/xlink" xlink:href="#i-messenger" href="#i-messenger"></use></svg></a></div>
			</div>
		</div>
	</div>
</header>
<div class="site-content">
