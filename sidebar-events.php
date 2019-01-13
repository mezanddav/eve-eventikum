<?php
/**
 * The sidebar containing the events widgets
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package eve
 */

?>
<?php if ( is_active_sidebar( 'sidebar-events' ) ): ?>
	<div><?php dynamic_sidebar( 'sidebar-events' ); ?></div>
<?php endif; ?>