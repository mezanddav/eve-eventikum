<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package eve
 */

get_header();
?>
<div class="ctn max">
	<div class="content-area">
		<main class="site-main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				printf( '<h1 class="page-title">%s</h1>', 'Események' );
				the_archive_description( '<div class="archive-description">', '</div>' );
				the_breadcrumb();
				?>
			</header><!-- .page-header -->

			<?php

			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', 'events' );

			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div>
<?php
get_footer();
