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
				printf( '<h1 class="page-title">%s</h1>', 'Eseményeink' );
				the_breadcrumb();
				?>
			</header><!-- .page-header -->

			<?php

			$article_loop = 1;
			echo '<div class="article__row row-of-3">';

			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', 'events-loop' );

				if( ($article_loop % 3) === 0 ){echo '</div><div class="article__row row-of-3">';}
				$article_loop++;

			endwhile;

			echo '</div>';

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'events-none' );

		endif;
		?>

		</main>
	</div>
</div>
<?php
get_footer();
