<?php
/**
 * Blog entry quote format
 *
 * @package WooVina WordPress theme
 */

// Exit if accessed directly
if(! defined('ABSPATH')) {
	exit;
}

// Return if WooVina Extra is not active
if(! WOOVINA_EXTRA_ACTIVE) {
	return;
}

// Quote link
$link = get_post_meta(get_the_ID(), 'woovina_quote_format_link', true);

// Add post classes
$classes = woovina_post_entry_classes(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class($classes); ?>>
	<div class="post-quote-wrap">
		<?php if('post' == $link) { ?>
			<a href="<?php the_permalink(); ?>" class="thumbnail-link">
		<?php } ?>
				<div class="post-quote-content">
					<?php echo wp_kses_post(get_post_meta(get_the_ID(), 'woovina_quote_format', true)); ?>
					<span class="post-quote-icon icon-speech"></span>
				</div>
				<div class="post-quote-author"><?php the_title(); ?></div>
		<?php if('post' == $link) { ?>
			</a>
		<?php } ?>
	</div>
</article>