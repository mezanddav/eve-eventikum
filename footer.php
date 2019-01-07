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
	<div class="ctn max">
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'eve' ) ); ?>">
				<?php
				/* translators: %s: CMS name, i.e. WordPress. */
				printf( esc_html__( 'Proudly powered by %s', 'eve' ), 'WordPress' );
				?>
			</a>
			<span class="sep"> | </span>
				<?php
				/* translators: 1: Theme name, 2: Theme author. */
				printf( esc_html__( 'Theme: %1$s by %2$s.', 'eve' ), 'eve', '<a href="https://www.progresseve.uk/">Progresseve Ltd.</a>' );
				?>
		</div><!-- .site-info -->
	</div>
</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>