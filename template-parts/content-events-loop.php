<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package eve
 */

?>
<div class="article__column">
	<article id="post-<?php the_ID(); ?>" <?php post_class('article__event'); ?>>
		<?php 
		$acf_image = get_field( 'eventikum_esemeny_fo_kepe', get_the_ID() );
		if( $acf_image ): ?>
		<div class="article__img sh" data-img-type="acf-image">
			<img class="evenp__banner-img loadlzly" src="<?php echo wp_get_attachment_image_src( $acf_image, 'one-one-image-thumb' )[0]; ?>" data-src="<?php echo wp_get_attachment_image_src( $acf_image, 'one-one-image-full' )[0]; ?>">
		</div>
		<?php elseif( has_post_thumbnail( get_the_ID() ) ): ?>
		<div class="article__img sh" data-img-type="post-thumb">
			<img class="evenp__banner-img loadlzly" src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'one-one-image-thumb' ); ?>" data-src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'one-one-image-full' ); ?>">
		</div>
		<?php endif; ?>
		<a class="article__event-uri" href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title( '<h2 class="article__event-title">', '</h2>' ); ?></a>
		<div class="article__presenter"><?php eve_get_profile( 'presenter', get_the_ID() ); ?></div>
		<div class="article__details">
			<div class="article__details-left"></div>
			<div class="article__details-right"></div>
		</div>
		<div class="article__cta"><a class="btn btn-light small block" href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>"><?php _e('BŐVEBBEN', 'eve'); ?></a></div>
	</article>
</div>