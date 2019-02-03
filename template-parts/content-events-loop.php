<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package eve
 */

$soon = get_field( 'eventikum_soon', get_the_ID() );

?>
<div class="article__column">
	<article id="post-<?php the_ID(); ?>" <?php post_class('article__event'); ?>>
		<?php if( has_post_thumbnail( get_the_ID() ) ): ?>
		<a class="article__img sh" href="<?php echo get_permalink(); ?>" data-img-type="post-thumb">
			<img class="evenp__banner-img loadlzly" src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'two-one-events-thumb' ); ?>" data-src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'two-one-events-full' ); ?>">
		</a>
		<?php endif; ?>
		<a class="article__event-uri" href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title( '<h2 class="article__event-title">', '</h2>' ); ?></a>
		<div class="article__presenter"><?php eve_get_profile( 'presenter', get_the_ID() ); ?></div>
		<div class="article__details">
			<div class="article__details-left"></div>
			<div class="article__details-right"></div>
		</div>
		<div class="article__cta"><a class="btn btn-light small block" href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>">
		<?php if( ! $soon ): ?>
			<?php _e('BŐVEBBEN', 'eve'); ?>
		<?php else: ?>
			<?php _e('HAMAROSAN', 'eve'); ?>
		<?php endif; ?>
		</a></div>
	</article>
</div>