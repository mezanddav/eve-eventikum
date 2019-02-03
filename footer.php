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
  <div class="site-footer__top">
    <div class="ctn max"></div>
  </div>
  <div class="site-footer__bottom">
    <img class="site-footer__logo" src="<?php echo get_template_directory_uri(); ?>/img/eventikum-white-logo.png" alt="<?php echo get_bloginfo('name'); ?>">
    <div class="site-footer__imppr"><a class="site-footer__imppr-uri" href="https://www.mezeidavid.com/" title="DESIGNED BY DAVID">DESIGNED BY DAVID</a></div>
  </div>
</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>