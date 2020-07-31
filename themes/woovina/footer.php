<?php
/**
 * The template for displaying the footer.
 *
 * @package WooVina WordPress theme
 */ ?>

        </main><!-- #main -->

        <?php do_action('woovina_after_main'); ?>

        <?php do_action('woovina_before_footer'); ?>

        <?php
        // Elementor `footer` location
        if(! function_exists('elementor_theme_do_location') || ! elementor_theme_do_location('footer')) { ?>

            <?php do_action('woovina_footer'); ?>
            
        <?php } ?>

        <?php do_action('woovina_after_footer'); ?>
                
    </div><!-- #wrap -->

    <?php do_action('woovina_after_wrap'); ?>

</div><!-- #outer-wrap -->

<?php do_action('woovina_after_outer_wrap'); ?>

<?php
// If is not sticky footer
if(! class_exists('WooVina_Sticky_Footer')) {
    get_template_part('partials/scroll-top');
} ?>

<?php
// Search overlay style
if('overlay' == woovina_menu_search_style()) {
    get_template_part('partials/header/search-overlay');
} ?>

<?php
// If sidebar mobile menu style
if('sidebar' == woovina_mobile_menu_style()) {
    
    // Mobile panel close button
    if(get_theme_mod('woovina_mobile_menu_close_btn', true)) {
        get_template_part('partials/mobile/mobile-sidr-close');
    } ?>

    <?php
    // Mobile Menu (if defined)
    get_template_part('partials/mobile/mobile-nav'); ?>

    <?php
    // Mobile search form
    if(get_theme_mod('woovina_mobile_menu_search', true)) {
        get_template_part('partials/mobile/mobile-search');
    }

} ?>

<?php
// If full screen mobile menu style
if('fullscreen' == woovina_mobile_menu_style()) {
    get_template_part('partials/mobile/mobile-fullscreen');
} ?>

<?php
    // Mobile Navbar (if defined)
    get_template_part('partials/mobile/mobile-navbar'); ?>
	
<?php 
	// WooVina Copyright Removal
	do_action('woovina_copyright_removal'); ?>

<?php wp_footer(); ?>
</body>
</html>