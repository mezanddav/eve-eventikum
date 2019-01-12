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

get_header('tmp');

?>
<div class="ctn max">
	<div class="evenp__banner sh<?php if(wp_is_mobile()){ echo ' mobile'; } ?>">
		<div class="evenp__banner-presenter<?php if(wp_is_mobile()){ echo ' mobile'; } ?>"><?php eve_get_profile( 'presenter', get_the_ID() ); ?></div>
		<div class="evenp__banner-presenter-bg"></div>
		<?php if( wp_is_mobile() ): ?>
		<div class="evenp__banner-psn mobile"><img class="evenp__banner-img loadlzly" src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'two-one-events-thumb' ); ?>" data-src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'two-one-events-full' ); ?>"></div>
		<?php else: ?>
		<div class="evenp__banner-psn desktop"><img class="evenp__banner-img loadlzly" src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'tree-one-events-thumb' ); ?>" data-src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'tree-one-events-full' ); ?>"></div>
		<?php endif; ?>
	</div>
	<div class="content-area">
		<div class="content-main with-sidebar">
			<main class="evenp__main">
				<?php while ( have_posts() ): the_post(); ?>
				<div class="evenp__tags_breadcrumb">
					<?php if( get_field('eventikum_kategoria') ){
						?><div class="evenp__tag"><?php echo get_field( 'eventikum_kategoria', get_the_ID() ); ?></div><?php
					} ?>
					<?php the_breadcrumb(); ?>
				</div>
				<div class="evenp__title"><?php the_title(); ?></div>
				<div class="evenp__subtitle"><?php echo get_field( 'eventikum_esemeny_eloadas_cime', get_the_ID() ); ?></div>
				<div class="evenp__organizer">
					<?php eve_get_profile( 'organizer', get_the_ID() ); ?>
				</div>
				<div class="evenp__content"><?php the_content(); ?></div>
				<div class="evenp__gallery"><?php eve_get_gallery( get_the_ID() ); ?></div>
				<div class="evenp__share"></div>
				<?php endwhile; ?>
			</main>
		</div>
		<div class="content-aside"><?php get_sidebar('events'); ?></div>
	</div>
</div>
<div class="evenp__gmap"></div>
<?php

get_footer('tmp');
