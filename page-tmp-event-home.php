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
	<?php
	if( has_post_thumbnail( get_the_ID() ) ): ?>
	<div class="evenp__banner sh<?php if(wp_is_mobile()){ echo ' mobile'; } ?>">
		<div class="evenp__banner-presenter<?php if(wp_is_mobile()){ echo ' mobile'; } ?>"><?php eve_get_profile( 'presenter', get_the_ID() ); ?></div>
		<div class="evenp__banner-presenter-bg"></div>
		<?php if( wp_is_mobile() ): ?>
		<div class="evenp__banner-psn mobile"><img class="evenp__banner-img loadlzly" src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'two-one-events-thumb' ); ?>" data-src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'two-one-events-full' ); ?>"></div>
		<?php else: ?>
		<div class="evenp__banner-psn desktop"><img class="evenp__banner-img loadlzly" src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'tree-one-events-thumb' ); ?>" data-src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'tree-one-events-full' ); ?>"></div>
		<?php endif; ?>
	</div>
	<?php endif; ?>
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
		<div class="content-aside">
		<aside class="evenp__sidebar">
			<div class="evenp__sidebar-inner">
				<div class="evenp__sidebar-until-the-event"><?php eve_get_event_date_diff( get_the_ID() );?></div>
				<div class="evenp__sidebar-details">
					<div class="evenp__sidebar-detail">
						<div class="evenp__sidebar-detail-title"><?php _e( 'IDŐPONT', 'eventikum' ); ?></div>
						<?php

						$date = get_field( 'eventikum_datum', get_the_ID() );
						if( $date ){
							$date = ucfirst(date_i18n( get_option( 'date_format' ), strtotime( $date ) ));
							printf( '<div class="evenp__sidebar-detail-meta">%s</div>', $date ); } 
						
						$time = get_field( 'eventikum_idopont', get_the_ID() );
						if( $time ){
							printf( '<div class="evenp__sidebar-detail-meta">%s</div>', $time ); }
						
						?>
					</div>
					<div class="evenp__sidebar-detail">
						<div class="evenp__sidebar-detail-title mb-top"><?php _e( 'HELYSZÍN', 'eventikum' ); ?></div>
						<?php 
						
						$location_city = get_field( 'eventikum_varos', get_the_ID() );
						if( $location_city ){
							printf( '<div class="evenp__sidebar-detail-meta">%s</div>', $location_city ); } 

						$location_note = get_field( 'eventikum_helyszin', get_the_ID() );
						if( $location_note ){
							printf( '<div class="evenp__sidebar-detail-note">%s</div>', $location_note ); } 

						?>
						<div class="evenp__sidebar-detail-action"><?php eve_get_google_map_link( get_the_ID() ); ?></div>
					</div>
				</div>
				<div class="evenp__sidebar-detail-sep"></div>
				<div class="evenp__sidebar-details">
					<div class="evenp__sidebar-detail-title"><?php _e( 'JEGY ÁRA', 'eventikum' ); ?></div>
					<?php 
					
					$price = get_field( 'eventikum_jegy_ara', get_the_ID() );
					$price_type = get_field_object( 'eventikum_penznem', get_the_ID() );
					
					if( $price && $price_type ){
						$selected_price_type = $price_type['value'];
						$price_type = $price_type['choices'][ $selected_price_type ];

						printf( '<div class="evenp__sidebar-detail-meta">%s %s</div>', $price, $price_type ); } 
					
					?>
				</div>
			</div>
			<div class="evenp__sidebar-tickets"><?php eve_get_event_tickets( get_the_ID() ); ?></div>
			<?php get_sidebar('events'); ?>
		</aside>
		</div>
	</div>
</div>
<div class="evenp__gmap"><?php eve_get_events_map( get_the_ID() ); ?></div>
<?php

get_footer();
