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
				<?php 
				
				$urgency = get_field( 'eventikum_sense_of_urgnecy', get_the_ID() );

				if( $urgency ){
					if( $urgency != 'off' ){
						if( $urgency == 'urgency' ):
							?><div class="soful soful--type-urgency">Fogytán a jegyek!</div><?php
						elseif( $urgency == 'available' ):
							?><div class="soful soful--type-available">Jegyek elérhetőek!</div><?php
						elseif( $urgency == 'sold_out' ):
							?><div class="soful soful--type-soldout">Teltház!</div><?php
						endif;
					}
				}
				
				?>
				<div class="article__img-content">
					<div class="article__img-presenter"><?php eve_get_profile( 'presenter', get_the_ID() ); ?></div>
					<h2 class="article__img-title"><?php echo get_field( 'eventikum_esemeny_eloadas_cime', get_the_ID() ); ?></h2>
				</div>
				<img class="evenp__banner-img loadlzly" src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'two-one-events-thumb' ); ?>" data-src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'two-one-events-full' ); ?>">
			</a>
			<div class="article__img-cta sh">
				<div class="row">
					<div class="col-sm-6"><?php $location_city = get_field( 'eventikum_varos', get_the_ID() );
					if( $location_city ){
						printf( '<div class="article__meta article__location"><svg class="i i-pin" width="16" height="16" title="%s"><use xlink="http://www.w3.org/1999/xlink" xlink:href="#i-pin" href="#i-pin"></use></svg>%s</div>', $location_city, $location_city ); } ?></div>
					<div class="col-sm-6">
						<a class="article__meta article__ctn" href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>"><?php if( ! $soon ): _e('BŐVEBBEN', 'eve'); else: _e('HAMAROSAN', 'eve'); endif; ?></a>
					</div>
				</div>
				
			</div>
		<?php else: ?>
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
		<?php endif; ?>
	</article>
</div>