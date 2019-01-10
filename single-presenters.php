<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package eve
 */

get_header();
?>
<img class="loadlzly" style="width: 500px; height: 500px;" src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'one-one-image-thumb', array( 'class' => 'valami' ) ); ?>" data-src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'one-one-image-full', array( 'class' => 'valami' ) ); ?>">
<?php
get_footer();
