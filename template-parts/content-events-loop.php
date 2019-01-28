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
		<!-- <div class="article__img">kép</div> -->
		<a class="article__event-uri" href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title( '<h2 class="article__event-title">', '</h2>' ); ?></a>
		<div class="article__presenter"><?php eve_get_profile( 'presenter', get_the_ID() ); ?></div>
		<div class="article__details">
			<div class="article__details-left"></div>
			<div class="article__details-right"></div>
		</div>
		<div class="article__cta"><a class="btn btn-light small block" href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>"><?php _e('BŐVEBBEN', 'eve'); ?></a></div>
	</article>
</div>