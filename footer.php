<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package eve
 */

?>

</div>

<div class="site-partners partners">
<!-- <div class="ctn max"><h2 class="partners__title">Támogatók</h2></div> -->
<div class="ctn max"><?php 
  
  $pterms_args = array(
    'taxonomy' => 'partner_type',
    'hide_empty' => true
  ); 
  
  $pterms = get_terms( $pterms_args );

  foreach( $pterms as $pterm ): ?>

    <div class="partner__row">
      <div class="partner__title"><?php echo $pterm->name; ?></div>
      <div class="partner__list">
        <?php 
        
          $plargs = array(
            'post_type' => 'partners',
            'tax_query' => array(
              array(
                'taxonomy' => 'partner_type',
                'field' => 'term_id',
                'terms' => $pterm->term_id
              )
            )
          );

          $pl_query = new WP_Query( $plargs );

          if( $pl_query->have_posts() ){
            while( $pl_query->have_posts() ):
              $pl_query->the_post();

              echo '<div class="partner">';

              $partner_link = get_field( "partner_link" );
              if( $partner_link ){ 
                echo sprintf( '<a class="partner__uri" target="_blank" href="%s" rel="me nofollow noopener noreferrer">', $partner_link ); 
              }else{
                echo sprintf( '<a class="partner__uri">' ); 
              }

              $partner_logo_id = get_field( "partner_logo" );
              $partner_logo_size = 'even-partners';
              $partner_logo = wp_get_attachment_image_src( $partner_logo_id, $partner_logo_size, false );
              var_dump($partner_logo);
              echo sprintf( '<img class="partner__img" src="%s" alt="%s">', $partner_logo[0], get_the_title() );

              echo '</a>';
              echo '</div>';

            endwhile;
          }
          
          wp_reset_postdata();
        
        ?>
      </div>
    </div>

  <?php endforeach; ?>
</div></div>

<footer class="site-footer">
<?php //if( is_active_sidebar( 'sidebar-footer' ) && ! is_page_template('page-narrow.php') ): ?>
<?php if( false ): ?>
<div class="site-footer__top">
  <div class="ctn max">
    <div class="site-footer-widget">
      <div class="site-footer-widget-title-wrp">
        <div class="site-footer-widget-title-lab">Segíts nekünk!</div>
        <h4 class="site-footer-widget-title">Kinek az előadására ülnél be szívesen? Válaszolj és nyerj Zacher könyvet!</h4>
      </div>
      <!-- <ul class="site-footer-widget-list"> -->
        <?php //dynamic_sidebar( 'sidebar-footer' ); ?>
      <!-- </ul> -->
      <div style="text-align:center;">
        <a class="btn btn-yellow" href="https://www.eventikum.ro/segits-nekunk/">Döntsd el te a következő előadót</a>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
  <div class="site-footer__bottom">
    <img class="site-footer__logo" src="<?php echo get_template_directory_uri(); ?>/img/eventikum-white-logo.png" alt="<?php echo get_bloginfo('name'); ?>">
    <div class="site-footer__social">
      <div class="site-header__socials">
				<div class="site-header__social"><a href="https://www.facebook.com/eventikum.ro/" target="_blank"><svg class="i i-facebook" width="14" height="14" title="Delta fitness"><use xlink="http://www.w3.org/1999/xlink" xlink:href="#i-facebook" href="#i-facebook"></use></svg></a></div>
				<div class="site-header__social"><a href="https://www.instagram.com/eventikum.ro/" target="_blank"><svg class="i i-instagram" width="14" height="14" title="Delta fitness"><use xlink="http://www.w3.org/1999/xlink" xlink:href="#i-instagram" href="#i-instagram"></use></svg></a></div>
				<div class="site-header__social"><a href="https://m.me/eventikum.ro" target="_blank"><svg class="i i-messenger" width="14" height="14" title="Delta fitness"><use xlink="http://www.w3.org/1999/xlink" xlink:href="#i-messenger" href="#i-messenger"></use></svg></a></div>
			</div>
    </div>
    <div class="site-footer__hirlevel"><a class="btn btn-yellow" href="https://www.eventikum.ro/iratkozz-fel-hirlevelunkre/">Iratkozz fel hírlevelünkre!</a></div>
    <div class="site-footer__imppr"><a class="site-footer__imppr-uri" href="https://www.mezeidavid.com/" title="DESIGNED BY DAVID">DESIGNED BY DAVID</a></div>
  </div>
</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>