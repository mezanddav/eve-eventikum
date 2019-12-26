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
    <div class="site-footer__imppr"><a class="site-footer__imppr-uri" href="https://www.mezeidavid.com/" title="DESIGNED BY DAVID">DESIGNED BY DAVID</a></div>
  </div>
</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>