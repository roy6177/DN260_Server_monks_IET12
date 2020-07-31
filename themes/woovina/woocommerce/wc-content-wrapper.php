<?php
/**
 * After Container template.
 *
 * @package WooVina WordPress theme
 */

if(! defined('ABSPATH')) {
	exit; // Exit if accessed directly
} ?>

<?php do_action('woovina_before_content_wrap'); ?>

<div id="content-wrap" class="container clr">

	<?php do_action('woovina_before_primary'); ?>

	<div id="primary" class="content-area clr">
		<div class="yoast-crumb" style="margin:0px">
					<?php if ( function_exists('yoast_breadcrumb') && ! is_front_page() && !is_page( array( 4180,4290,4718,3714,8) ) ) : ?>
    				<?php yoast_breadcrumb( '<h6 style="color:red" id="breadcrumbs">','</h6>' ); ?>
					<?php endif; ?>
					</div>

		<?php do_action('woovina_before_content'); ?>

		<div id="content" class="clr site-content">

			<?php do_action('woovina_before_content_inner'); ?>

			<article class="entry-content entry clr">