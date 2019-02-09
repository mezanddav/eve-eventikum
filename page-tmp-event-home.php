<?php

/* Template Name: Event homepage */

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
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

		<?php 
		
		$args = array(
			'numberposts' => 5,
			'offset' => 0,
			'category' => 0,
			'orderby' => 'post_date',
			'order' => 'DESC',
			'include' => '',
			'exclude' => '',
			'meta_key' => '',
			'meta_value' =>'',
			'post_type' => 'events',
			'post_status' => 'publish',
			'suppress_filters' => true
		);
		$recent_posts = new WP_Query( $args );
		
		if ( $recent_posts->have_posts() ) : ?>
	
			<header class="page-header">
				<?php
				printf( '<h1 class="page-title">%s</h1>', 'Eseményeink' );
				the_breadcrumb();
				?>
			</header>

			<?php

			$article_loop = 1;
			echo '<div class="article__row row-of-3">';

			while ( $recent_posts->have_posts() ) : $recent_posts->the_post();
				get_template_part( 'template-parts/content', 'events-loop' );

				if( ($article_loop % 3) === 0 ){echo '</div><div class="article__row row-of-3">';}
				$article_loop++;

			endwhile;

			echo '</div>';

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'events-none' );

		endif;

		wp_reset_query();

		?>

		</main>
	</div>
</div>
<?php

get_footer();
