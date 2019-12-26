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