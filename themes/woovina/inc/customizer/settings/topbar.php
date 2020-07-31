<?php
/**
 * Top Bar Customizer Options
 *
 * @package WooVina WordPress theme
 */

if(! defined('ABSPATH')) {
	exit;
}

if(! class_exists('WooVina_Top_Bar_Customizer')) :

	class WooVina_Top_Bar_Customizer {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {

			add_action('customize_register', 	array($this, 'customizer_options'));
			add_filter('woovina_head_css', 		array($this, 'head_css'));

		}

		/**
		 * Customizer options
		 *
		 * @since 1.0.0
		 */
		public function customizer_options($wp_customize) {

			/**
			 * Panel
			 */
			$panel = 'woovina_topbar_panel';
			$wp_customize->add_panel($panel , array(
				'title' 			=> esc_html__('Top Bar', 'woovina'),
				'priority' 			=> 210,
			));

			/**
			 * Section
			 */
			$wp_customize->add_section('woovina_topbar_general' , array(
				'title' 			=> esc_html__('General', 'woovina'),
				'priority' 			=> 10,
				'panel' 			=> $panel,
			));

			/**
			 * Top Bar
			 */
			$wp_customize->add_setting('woovina_top_bar', array(
				'default'           	=> true,
				'sanitize_callback' 	=> 'woovina_sanitize_checkbox',
			));

			$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'woovina_top_bar', array(
				'label'	   				=> esc_html__('Enable Top Bar', 'woovina'),
				'type' 					=> 'checkbox',
				'section'  				=> 'woovina_topbar_general',
				'settings' 				=> 'woovina_top_bar',
				'priority' 				=> 10,
			)));

			/**
			 * Top Bar Full Width
			 */
			$wp_customize->add_setting('woovina_top_bar_full_width', array(
				'transport' 			=> 'postMessage',
				'default'           	=> false,
				'sanitize_callback' 	=> 'woovina_sanitize_checkbox',
			));

			$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'woovina_top_bar_full_width', array(
				'label'	   				=> esc_html__('Top Bar Full Width', 'woovina'),
				'type' 					=> 'checkbox',
				'section'  				=> 'woovina_topbar_general',
				'settings' 				=> 'woovina_top_bar_full_width',
				'priority' 				=> 10,
				'active_callback' 		=> 'woovina_cac_has_topbar',
			)));

			/**
			 * Top Bar Visibility
			 */
			$wp_customize->add_setting('woovina_top_bar_visibility', array(
				'transport' 			=> 'postMessage',
				'default'           	=> 'all-devices',
				'sanitize_callback' 	=> 'woovina_sanitize_select',
			));

			$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'woovina_top_bar_visibility', array(
				'label'	   				=> esc_html__('Visibility', 'woovina'),
				'type' 					=> 'select',
				'section'  				=> 'woovina_topbar_general',
				'settings' 				=> 'woovina_top_bar_visibility',
				'priority' 				=> 10,
				'active_callback' 		=> 'woovina_cac_has_topbar',
				'choices' 				=> array(
					'all-devices' 			=> esc_html__('Show On All Devices', 'woovina'),
					'hide-tablet' 			=> esc_html__('Hide On Tablet', 'woovina'),
					'hide-mobile' 			=> esc_html__('Hide On Mobile', 'woovina'),
					'hide-tablet-mobile' 	=> esc_html__('Hide On Tablet & Mobile', 'woovina'),
				),
			)));

			/**
			 * Top Bar Style
			 */
			$wp_customize->add_setting('woovina_top_bar_style', array(
				'default'           	=> 'one',
				'sanitize_callback' 	=> 'woovina_sanitize_select',
			));

			$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'woovina_top_bar_style', array(
				'label'	   				=> esc_html__('Style', 'woovina'),
				'type' 					=> 'select',
				'section'  				=> 'woovina_topbar_general',
				'settings' 				=> 'woovina_top_bar_style',
				'priority' 				=> 10,
				'active_callback' 		=> 'woovina_cac_has_topbar',
				'choices' 				=> array(
					'one' 		=> esc_html__('Left Content & Right Social', 'woovina'),
					'two' 		=> esc_html__('Left Social & Right Content', 'woovina'),
					'three' 	=> esc_html__('Centered Content & Social', 'woovina'),
				),
			)));

			/**
			 * Top Bar Padding
			 */
			$wp_customize->add_setting('woovina_top_bar_top_padding', array(
				'transport' 			=> 'postMessage',
				'default'           	=> '8',
				'sanitize_callback' 	=> 'woovina_sanitize_number',
			));
			$wp_customize->add_setting('woovina_top_bar_right_padding', array(
				'transport' 			=> 'postMessage',
				'default'           	=> '0',
				'sanitize_callback' 	=> 'woovina_sanitize_number',
			));
			$wp_customize->add_setting('woovina_top_bar_bottom_padding', array(
				'transport' 			=> 'postMessage',
				'default'           	=> '8',
				'sanitize_callback' 	=> 'woovina_sanitize_number',
			));
			$wp_customize->add_setting('woovina_top_bar_left_padding', array(
				'transport' 			=> 'postMessage',
				'default'           	=> '0',
				'sanitize_callback' 	=> 'woovina_sanitize_number',
			));

			$wp_customize->add_setting('woovina_top_bar_tablet_top_padding', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'woovina_sanitize_number_blank',
			));
			$wp_customize->add_setting('woovina_top_bar_tablet_right_padding', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'woovina_sanitize_number_blank',
			));
			$wp_customize->add_setting('woovina_top_bar_tablet_bottom_padding', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'woovina_sanitize_number_blank',
			));
			$wp_customize->add_setting('woovina_top_bar_tablet_left_padding', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'woovina_sanitize_number_blank',
			));

			$wp_customize->add_setting('woovina_top_bar_mobile_top_padding', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'woovina_sanitize_number_blank',
			));
			$wp_customize->add_setting('woovina_top_bar_mobile_right_padding', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'woovina_sanitize_number_blank',
			));
			$wp_customize->add_setting('woovina_top_bar_mobile_bottom_padding', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'woovina_sanitize_number_blank',
			));
			$wp_customize->add_setting('woovina_top_bar_mobile_left_padding', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'woovina_sanitize_number_blank',
			));

			$wp_customize->add_control(new WooVina_Customizer_Dimensions_Control($wp_customize, 'woovina_top_bar_padding', array(
				'label'	   				=> esc_html__('Padding (px)', 'woovina'),
				'section'  				=> 'woovina_topbar_general',
				'settings' => array(
		            'desktop_top' 		=> 'woovina_top_bar_top_padding',
		            'desktop_right' 	=> 'woovina_top_bar_right_padding',
		            'desktop_bottom' 	=> 'woovina_top_bar_bottom_padding',
		            'desktop_left' 		=> 'woovina_top_bar_left_padding',
		            'tablet_top' 		=> 'woovina_top_bar_tablet_top_padding',
		            'tablet_right' 		=> 'woovina_top_bar_tablet_right_padding',
		            'tablet_bottom' 	=> 'woovina_top_bar_tablet_bottom_padding',
		            'tablet_left' 		=> 'woovina_top_bar_tablet_left_padding',
		            'mobile_top' 		=> 'woovina_top_bar_mobile_top_padding',
		            'mobile_right' 		=> 'woovina_top_bar_mobile_right_padding',
		            'mobile_bottom' 	=> 'woovina_top_bar_mobile_bottom_padding',
		            'mobile_left' 		=> 'woovina_top_bar_mobile_left_padding',
			   ),
				'priority' 				=> 10,
				'active_callback' 		=> 'woovina_cac_has_topbar',
			    'input_attrs' 			=> array(
			        'min'   => 0,
			        'max'   => 100,
			        'step'  => 1,
			   ),
			)));

			/**
			 * Top Bar Background Color
			 */
			$wp_customize->add_setting('woovina_top_bar_bg', array(
				'transport' 			=> 'postMessage',
				'default'           	=> '#ffffff',
				'sanitize_callback' 	=> 'woovina_sanitize_color',
			));

			$wp_customize->add_control(new WooVina_Customizer_Color_Control($wp_customize, 'woovina_top_bar_bg', array(
				'label'	   				=> esc_html__('Background Color', 'woovina'),
				'section'  				=> 'woovina_topbar_general',
				'settings' 				=> 'woovina_top_bar_bg',
				'priority' 				=> 10,
				'active_callback' 		=> 'woovina_cac_has_topbar',
			)));

			/**
			 * Top Bar Border Color
			 */
			$wp_customize->add_setting('woovina_top_bar_border_color', array(
				'transport' 			=> 'postMessage',
				'default'           	=> '#f1f1f1',
				'sanitize_callback' 	=> 'woovina_sanitize_color',
			));

			$wp_customize->add_control(new WooVina_Customizer_Color_Control($wp_customize, 'woovina_top_bar_border_color', array(
				'label'	   				=> esc_html__('Border Color', 'woovina'),
				'section'  				=> 'woovina_topbar_general',
				'settings' 				=> 'woovina_top_bar_border_color',
				'priority' 				=> 10,
				'active_callback' 		=> 'woovina_cac_has_topbar',
			)));

			/**
			 * Top Bar Text Color
			 */
			$wp_customize->add_setting('woovina_top_bar_text_color', array(
				'transport' 			=> 'postMessage',
				'default'           	=> '#929292',
				'sanitize_callback' 	=> 'woovina_sanitize_color',
			));

			$wp_customize->add_control(new WooVina_Customizer_Color_Control($wp_customize, 'woovina_top_bar_text_color', array(
				'label'	   				=> esc_html__('Text Color', 'woovina'),
				'section'  				=> 'woovina_topbar_general',
				'settings' 				=> 'woovina_top_bar_text_color',
				'priority' 				=> 10,
				'active_callback' 		=> 'woovina_cac_has_topbar',
			)));

			/**
			 * Top Bar Link Color
			 */
			$wp_customize->add_setting('woovina_top_bar_link_color', array(
				'transport' 			=> 'postMessage',
				'default'           	=> '#555555',
				'sanitize_callback' 	=> 'woovina_sanitize_color',
			));

			$wp_customize->add_control(new WooVina_Customizer_Color_Control($wp_customize, 'woovina_top_bar_link_color', array(
				'label'	   				=> esc_html__('Link Color', 'woovina'),
				'section'  				=> 'woovina_topbar_general',
				'settings' 				=> 'woovina_top_bar_link_color',
				'priority' 				=> 10,
				'active_callback' 		=> 'woovina_cac_has_topbar',
			)));

			/**
			 * Top Bar Link Color Hover
			 */
			$wp_customize->add_setting('woovina_top_bar_link_color_hover', array(
				'transport' 			=> 'postMessage',
				'default'           	=> '#f68e13',
				'sanitize_callback' 	=> 'woovina_sanitize_color',
			));

			$wp_customize->add_control(new WooVina_Customizer_Color_Control($wp_customize, 'woovina_top_bar_link_color_hover', array(
				'label'	   				=> esc_html__('Link Color: Hover', 'woovina'),
				'section'  				=> 'woovina_topbar_general',
				'settings' 				=> 'woovina_top_bar_link_color_hover',
				'priority' 				=> 10,
				'active_callback' 		=> 'woovina_cac_has_topbar',
			)));

			/**
			 * Section
			 */
			$wp_customize->add_section('woovina_topbar_content' , array(
				'title' 			=> esc_html__('Content', 'woovina'),
				'priority' 			=> 10,
				'panel' 			=> $panel,
			));

			/**
			 * Top Bar Template
			 */
			$wp_customize->add_setting('woovina_top_bar_template', array(
				'default'           	=> '0',
				'sanitize_callback' 	=> 'woovina_sanitize_select',
			));

			$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'woovina_top_bar_template', array(
				'label'	   				=> esc_html__('Select Template', 'woovina'),
				'description'	   		=> esc_html__('Choose a template created in Theme Panel > My Library to replace the content.', 'woovina'),
				'type' 					=> 'select',
				'section'  				=> 'woovina_topbar_content',
				'settings' 				=> 'woovina_top_bar_template',
				'priority' 				=> 10,
				'active_callback' 		=> 'woovina_cac_has_topbar',
				'choices' 				=> woovina_customizer_helpers('library'),
			)));

			/**
			 * Top Bar Content
			 */
			$wp_customize->add_setting('woovina_top_bar_content', array(
				'transport'           	=> 'postMessage',
				'default'           	=> esc_html__('Place your content here', 'woovina'),
				'sanitize_callback' 	=> 'wp_kses_post',
			));

			$wp_customize->add_control(new WooVina_Customizer_Textarea_Control($wp_customize, 'woovina_top_bar_content', array(
				'label'	   				=> esc_html__('Content', 'woovina'),
				'description'	   		=> sprintf(esc_html__('Shortcodes allowed, %1$ssee the list%2$s.', 'woovina'), '<a href="https://woovina.com/docs/getting-started/woovina-shortcodes?ref=dashboard" target="_blank">', '</a>'),
				'section'  				=> 'woovina_topbar_content',
				'settings' 				=> 'woovina_top_bar_content',
				'priority' 				=> 10,
				'active_callback' 		=> 'woovina_cac_has_topbar',
			)));

			/**
			 * Section
			 */
			$wp_customize->add_section('woovina_topbar_social' , array(
				'title' 			=> esc_html__('Social', 'woovina'),
				'priority' 			=> 10,
				'panel' 			=> $panel,
			));

			/**
			 * Top Bar Social
			 */
			$wp_customize->add_setting('woovina_top_bar_social', array(
				'default'           	=> true,
				'sanitize_callback' 	=> 'woovina_sanitize_checkbox',
			));

			$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'woovina_top_bar_social', array(
				'label'	   				=> esc_html__('Enable Social', 'woovina'),
				'type' 					=> 'checkbox',
				'section'  				=> 'woovina_topbar_social',
				'settings' 				=> 'woovina_top_bar_social',
				'priority' 				=> 10,
				'active_callback' 		=> 'woovina_cac_has_topbar',
			)));

			/**
			 * Top Bar Social Alternative
			 */
			$wp_customize->add_setting('woovina_top_bar_social_alt_template', array(
				'default'           	=> '0',
				'sanitize_callback' 	=> 'woovina_sanitize_select',
			));

			$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'woovina_top_bar_social_alt_template', array(
				'label'	   				=> esc_html__('Social Alternative', 'woovina'),
				'description'	   		=> esc_html__('Choose a template created in Theme Panel > My Library.', 'woovina'),
				'type' 					=> 'select',
				'section'  				=> 'woovina_topbar_social',
				'settings' 				=> 'woovina_top_bar_social_alt_template',
				'priority' 				=> 10,
				'active_callback' 		=> 'woovina_cac_has_topbar_social',
				'choices' 				=> woovina_customizer_helpers('library'),
			)));

			/**
			 * Top Bar Social Link Target
			 */
			$wp_customize->add_setting('woovina_top_bar_social_target', array(
				'transport'           	=> 'postMessage',
				'default'           	=> 'blank',
				'sanitize_callback' 	=> 'woovina_sanitize_select',
			));

			$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'woovina_top_bar_social_target', array(
				'label'	   				=> esc_html__('Social Link Target', 'woovina'),
				'type' 					=> 'select',
				'section'  				=> 'woovina_topbar_social',
				'settings' 				=> 'woovina_top_bar_social_target',
				'priority' 				=> 10,
				'active_callback' 		=> 'woovina_cac_has_topbar_social',
				'choices' 				=> array(
					'blank' => esc_html__('New Window', 'woovina'),
					'self' 	=> esc_html__('Same Window', 'woovina'),
				),
			)));

			/**
			 * Top Bar Social Font Size
			 */
			$wp_customize->add_setting('woovina_top_bar_social_font_size', array(
				'transport' 			=> 'postMessage',
				'default'           	=> '14',
				'sanitize_callback' 	=> 'woovina_sanitize_number',
			));

			$wp_customize->add_setting('woovina_top_bar_social_tablet_font_size', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'woovina_sanitize_number_blank',
			));

			$wp_customize->add_setting('woovina_top_bar_social_mobile_font_size', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'woovina_sanitize_number_blank',
			));

			$wp_customize->add_control(new WooVina_Customizer_Slider_Control($wp_customize, 'woovina_top_bar_social_font_size', array(
				'label' 			=> esc_html__('Font Size (px)', 'woovina'),
				'section'  			=> 'woovina_topbar_social',
				'settings' => array(
		            'desktop' 	=> 'woovina_top_bar_social_font_size',
		            'tablet' 	=> 'woovina_top_bar_social_tablet_font_size',
		            'mobile' 	=> 'woovina_top_bar_social_mobile_font_size',
			   ),
				'priority' 				=> 10,
				'active_callback' 		=> 'woovina_cac_has_topbar_social',
			    'input_attrs' 			=> array(
			        'min'   => 0,
			        'max'   => 100,
			        'step'  => 1,
			   ),
			)));

			/**
			 * Top Bar Social Padding
			 */
			$wp_customize->add_setting('woovina_top_bar_social_right_padding', array(
				'transport' 			=> 'postMessage',
				'default'           	=> '6',
				'sanitize_callback' 	=> 'woovina_sanitize_number',
			));
			$wp_customize->add_setting('woovina_top_bar_social_left_padding', array(
				'transport' 			=> 'postMessage',
				'default'           	=> '6',
				'sanitize_callback' 	=> 'woovina_sanitize_number',
			));

			$wp_customize->add_setting('woovina_top_bar_social_tablet_right_padding', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'woovina_sanitize_number_blank',
			));
			$wp_customize->add_setting('woovina_top_bar_social_tablet_left_padding', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'woovina_sanitize_number_blank',
			));

			$wp_customize->add_setting('woovina_top_bar_social_mobile_right_padding', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'woovina_sanitize_number_blank',
			));
			$wp_customize->add_setting('woovina_top_bar_social_mobile_left_padding', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'woovina_sanitize_number_blank',
			));

			$wp_customize->add_control(new WooVina_Customizer_Dimensions_Control($wp_customize, 'woovina_top_bar_social_padding', array(
				'label'	   				=> esc_html__('Padding (px)', 'woovina'),
				'section'  				=> 'woovina_topbar_social',
				'settings' => array(
		            'desktop_right' 	=> 'woovina_top_bar_social_right_padding',
		            'desktop_left' 		=> 'woovina_top_bar_social_left_padding',
		            'tablet_right' 		=> 'woovina_top_bar_social_tablet_right_padding',
		            'tablet_left' 		=> 'woovina_top_bar_social_tablet_left_padding',
		            'mobile_right' 		=> 'woovina_top_bar_social_mobile_right_padding',
		            'mobile_left' 		=> 'woovina_top_bar_social_mobile_left_padding',
			   ),
				'priority' 				=> 10,
				'active_callback' 		=> 'woovina_cac_has_topbar_social',
			    'input_attrs' 			=> array(
			        'min'   => 0,
			        'max'   => 60,
			        'step'  => 1,
			   ),
			)));

			/**
			 * Top Bar Social Link Color
			 */
			$wp_customize->add_setting('woovina_top_bar_social_links_color', array(
				'transport' 			=> 'postMessage',
				'default'           	=> '#bbbbbb',
				'sanitize_callback' 	=> 'woovina_sanitize_color',
			));

			$wp_customize->add_control(new WooVina_Customizer_Color_Control($wp_customize, 'woovina_top_bar_social_links_color', array(
				'label'	   				=> esc_html__('Social Links Color', 'woovina'),
				'section'  				=> 'woovina_topbar_social',
				'settings' 				=> 'woovina_top_bar_social_links_color',
				'priority' 				=> 10,
				'active_callback' 		=> 'woovina_cac_has_topbar_social',
			)));

			/**
			 * Top Bar Social Link Color Hover
			 */
			$wp_customize->add_setting('woovina_top_bar_social_hover_links_color', array(
				'transport' 			=> 'postMessage',
				'sanitize_callback' 	=> 'woovina_sanitize_color',
			));

			$wp_customize->add_control(new WooVina_Customizer_Color_Control($wp_customize, 'woovina_top_bar_social_hover_links_color', array(
				'label'	   				=> esc_html__('Social Links Color: Hover', 'woovina'),
				'section'  				=> 'woovina_topbar_social',
				'settings' 				=> 'woovina_top_bar_social_hover_links_color',
				'priority' 				=> 10,
				'active_callback' 		=> 'woovina_cac_has_topbar_social',
			)));

			/**
			 * Top Bar Social Settings
			 */
			$social_options = woovina_social_options();
			foreach ($social_options as $key => $val) {
				if('skype' == $key) {
					$sanitize = 'wp_filter_nohtml_kses';
				} else if('email' == $key) {
					$sanitize = 'sanitize_email';
				} else {
					$sanitize = 'esc_url_raw';
				}

				$wp_customize->add_setting('woovina_top_bar_social_profiles[' . $key .']', array(
					'sanitize_callback' 	=> $sanitize,
				));

				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'woovina_top_bar_social_profiles[' . $key .']', array(
					'label'	   				=> esc_html($val['label']),
					'type' 					=> 'text',
					'section'  				=> 'woovina_topbar_social',
					'settings' 				=> 'woovina_top_bar_social_profiles[' . $key .']',
					'priority' 				=> 10,
					'active_callback' 		=> 'woovina_cac_has_topbar_social',
				)));
			}

		}

		/**
		 * Get CSS
		 *
		 * @since 1.0.0
		 */
		public static function head_css($output) {
		
			// Global vars
			$top_padding 					= get_theme_mod('woovina_top_bar_top_padding', '8');
			$right_padding 					= get_theme_mod('woovina_top_bar_right_padding', '0');
			$bottom_padding 				= get_theme_mod('woovina_top_bar_bottom_padding', '8');
			$left_padding 					= get_theme_mod('woovina_top_bar_left_padding', '0');
			$tablet_top_padding 			= get_theme_mod('woovina_top_bar_tablet_top_padding');
			$tablet_right_padding 			= get_theme_mod('woovina_top_bar_tablet_right_padding');
			$tablet_bottom_padding 			= get_theme_mod('woovina_top_bar_tablet_bottom_padding');
			$tablet_left_padding 			= get_theme_mod('woovina_top_bar_tablet_left_padding');
			$mobile_top_padding 			= get_theme_mod('woovina_top_bar_mobile_top_padding');
			$mobile_right_padding 			= get_theme_mod('woovina_top_bar_mobile_right_padding');
			$mobile_bottom_padding 			= get_theme_mod('woovina_top_bar_mobile_bottom_padding');
			$mobile_left_padding 			= get_theme_mod('woovina_top_bar_mobile_left_padding');
			$background 					= get_theme_mod('woovina_top_bar_bg', '#ffffff');
			$border_color 					= get_theme_mod('woovina_top_bar_border_color', '#f1f1f1');
			$text_color 					= get_theme_mod('woovina_top_bar_text_color', '#929292');
			$link_color 					= get_theme_mod('woovina_top_bar_link_color', '#555555');
			$link_color_hover 				= get_theme_mod('woovina_top_bar_link_color_hover', '#f68e13');
			$social_font_size 				= get_theme_mod('woovina_top_bar_social_font_size');
			$social_tablet_font_size 		= get_theme_mod('woovina_top_bar_social_tablet_font_size');
			$social_mobile_font_size 		= get_theme_mod('woovina_top_bar_social_mobile_font_size');
			$social_right_padding 			= get_theme_mod('woovina_top_bar_social_right_padding');
			$social_left_padding 			= get_theme_mod('woovina_top_bar_social_left_padding');
			$social_tablet_right_padding 	= get_theme_mod('woovina_top_bar_social_tablet_right_padding');
			$social_tablet_left_padding 	= get_theme_mod('woovina_top_bar_social_tablet_left_padding');
			$social_mobile_right_padding 	= get_theme_mod('woovina_top_bar_social_mobile_right_padding');
			$social_mobile_left_padding 	= get_theme_mod('woovina_top_bar_social_mobile_left_padding');
			$social_links_color 			= get_theme_mod('woovina_top_bar_social_links_color', '#bbbbbb');
			$social_hover_links_color 		= get_theme_mod('woovina_top_bar_social_hover_links_color');

			// Define css var
			$css = '';

			// Top bar padding
			if(isset($top_padding) && '8' != $top_padding && '' != $top_padding
				|| isset($right_padding) && '0' != $right_padding && '' != $right_padding
				|| isset($bottom_padding) && '8' != $bottom_padding && '' != $bottom_padding
				|| isset($left_padding) && '0' != $left_padding && '' != $left_padding) {
				$css .= '#top-bar{padding:'. woovina_spacing_css($top_padding, $right_padding, $bottom_padding, $left_padding) .'}';
			}

			// Tablet top bar padding
			if(isset($tablet_top_padding) && '' != $tablet_top_padding
				|| isset($tablet_right_padding) && '' != $tablet_right_padding
				|| isset($tablet_bottom_padding) && '' != $tablet_bottom_padding
				|| isset($tablet_left_padding) && '' != $tablet_left_padding) {
				$css .= '@media (max-width: 768px){#top-bar{padding:'. woovina_spacing_css($tablet_top_padding, $tablet_right_padding, $tablet_bottom_padding, $tablet_left_padding) .'}}';
			}

			// Mobile top bar padding
			if(isset($mobile_top_padding) && '' != $mobile_top_padding
				|| isset($mobile_right_padding) && '' != $mobile_right_padding
				|| isset($mobile_bottom_padding) && '' != $mobile_bottom_padding
				|| isset($mobile_left_padding) && '' != $mobile_left_padding) {
				$css .= '@media (max-width: 480px){#top-bar{padding:'. woovina_spacing_css($mobile_top_padding, $mobile_right_padding, $mobile_bottom_padding, $mobile_left_padding) .'}}';
			}

			// Top bar background color
			if(! empty($background) && '#ffffff' != $background) {
				$css .= '#top-bar-wrap,.woovina-top-bar-sticky{background-color:'. $background .';}';
			}

			// Top bar border color
			if(! empty($border_color) && '#f1f1f1' != $border_color) {
				$css .= '#top-bar-wrap{border-color:'. $border_color .';}';
			}

			// Top bar text color
			if(! empty($text_color) && '#929292' != $text_color) {
				$css .= '#top-bar-wrap,#top-bar-content strong{color:'. $text_color .';}';
			}

			// Top bar link color
			if(! empty($link_color) && '#555555' != $link_color) {
				$css .= '#top-bar-content a,#top-bar-social-alt a{color:'. $link_color .';}';
			}

			// Top bar link color hover
			if(! empty($link_color_hover) && '#f68e13' != $link_color_hover) {
				$css .= '#top-bar-content a:hover,#top-bar-social-alt a:hover{color:'. $link_color_hover .';}';
			}

			// Add top bar social font size
			if(! empty($social_font_size) && '14' != $social_font_size) {
				$css .= '#top-bar-social li a{font-size:'. $social_font_size .'px;}';
			}

			// Add top bar social tablet font size
			if(! empty($social_tablet_font_size)) {
				$css .= '@media (max-width: 768px){#top-bar-social li a{font-size:'. $social_tablet_font_size .'px;}}';
			}

			// Add top bar social mobile font size
			if(! empty($social_mobile_font_size)) {
				$css .= '@media (max-width: 480px){#top-bar-social li a{font-size:'. $social_mobile_font_size .'px;}}';
			}

			// Top bar padding
			if(isset($social_right_padding) && '6' != $social_right_padding && '' != $social_right_padding
				|| isset($social_left_padding) && '6' != $social_left_padding && '' != $social_left_padding) {
				$css .= '#top-bar-social li a{padding:'. woovina_spacing_css('', $social_right_padding, '', $social_left_padding) .'}';
			}

			// Tablet top bar padding
			if(isset($social_tablet_right_padding) && '' != $social_tablet_right_padding
				|| isset($social_tablet_left_padding) && '' != $social_tablet_left_padding) {
				$css .= '@media (max-width: 768px){#top-bar-social li a{padding:'. woovina_spacing_css('', $social_tablet_right_padding, '', $social_tablet_left_padding) .'}}';
			}

			// Mobile top bar padding
			if(isset($social_mobile_right_padding) && '' != $social_mobile_right_padding
				|| isset($social_mobile_left_padding) && '' != $social_mobile_left_padding) {
				$css .= '@media (max-width: 480px){#top-bar-social li a{padding:'. woovina_spacing_css('', $social_mobile_right_padding, '', $social_mobile_left_padding) .'}}';
			}

			// Top bar social link color
			if(! empty($social_links_color) && '#bbbbbb' != $social_links_color) {
				$css .= '#top-bar-social li a{color:'. $social_links_color .';}';
			}

			// Top bar social link color hover
			if(! empty($social_hover_links_color)) {
				$css .= '#top-bar-social li a:hover{color:'. $social_hover_links_color .'!important;}';
			}
				
			// Return CSS
			if(! empty($css)) {
				$output .= '/* Top Bar CSS */'. $css;
			}

			// Return output css
			return $output;

		}

	}

endif;

return new WooVina_Top_Bar_Customizer();