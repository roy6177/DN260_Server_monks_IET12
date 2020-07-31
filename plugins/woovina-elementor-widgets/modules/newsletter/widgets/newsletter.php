<?php
namespace wvnElementor\Modules\Newsletter\Widgets;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;

if(! defined('ABSPATH')) exit; // Exit if accessed directly

class Newsletter extends Widget_Base {

	public function get_name() {
		return 'wew-newsletter';
	}

	public function get_title() {
		return __('Newsletter Form', 'woovina-elementor-widgets');
	}

	public function get_icon() {
		// Upload "eicons.ttf" font via this site: http://bluejamesbond.github.io/CharacterMap/
		return 'wew-icon eicon-favorite';
	}

	public function get_categories() {
		return [ 'woovina-elements' ];
	}

	public function get_script_depends() {
		return [ 'wew-newsletter' ];
	}

	public function get_style_depends() {
		return [ 'wew-newsletter' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_form',
			[
				'label' 		=> __('Form', 'woovina-elementor-widgets'),
			]
		);

		// If no API KEy and List ID
        if(! get_option('wvn_mailchimp_api_key')
            || ! get_option('wvn_mailchimp_list_id')) {
            $this->add_control(
			'set_key',
				[
					'type' 		=> Controls_Manager::RAW_HTML,
					'raw'  		=> sprintf(
						__('You need to set your Api Key & List Id on the %1$ssettings page%2$s', 'woovina-elementor-widgets'),
						'<a href="' . add_query_arg(array('page' => 'woovina-panel&tab=integrations#mailchimp',), esc_url(admin_url('admin.php'))) . '" target="_blank">',
						'</a>'
					),
				]
			);
    	}

		$this->add_control(
			'placeholder_text',
			[
				'label' 		=> __('Placeholder Text', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __('Enter your email address', 'woovina-elementor-widgets'),
				'label_block' 	=> true,
				'dynamic' 		=> [ 'active' => true ],
			]
		);

		$this->add_control(
			'submit_text',
			[
				'label' 		=> __('Submit Button Text', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __('Go', 'woovina-elementor-widgets'),
				'label_block' 	=> true,
				'dynamic' 		=> [ 'active' => true ],
			]
		);

		$this->add_responsive_control(
			'input_width',
			[
				'label' 		=> __('Width', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::SLIDER,
				'size_units' 	=> [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
					'size' => 400,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wew-newsletter-form-wrap' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'input_height',
			[
				'label' 		=> __('Height', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::SLIDER,
				'size_units' 	=> [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 300,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wew-newsletter-form-wrap input[type="email"]' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'gdpr_label',
			[
				'label' 		=> __('GDPR Checkbox Label', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __('Accept GDPR Terms', 'woovina-elementor-widgets'),
				'label_block' 	=> true,
				'dynamic' 		=> [ 'active' => true ],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' 		=> __('Alignment', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::CHOOSE,
				'options' 		=> [
					'left'    	=> [
						'title' => __('Left', 'woovina-elementor-widgets'),
						'icon' 	=> 'fa fa-align-left',
					],
					'center' 	=> [
						'title' => __('Center', 'woovina-elementor-widgets'),
						'icon' 	=> 'fa fa-align-center',
					],
					'right' 	=> [
						'title' => __('Right', 'woovina-elementor-widgets'),
						'icon' 	=> 'fa fa-align-right',
					],
				],
				'prefix_class' 	=> 'elementor%s-align-',
				'default' 		=> '',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title',
			[
				'label' 		=> __('Input', 'woovina-elementor-widgets'),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('tabs_input_style');

		$this->start_controls_tab(
			'tab_input_normal',
			[
				'label' => __('Normal', 'woovina-elementor-widgets'),
			]
		);

		$this->add_control(
			'input_bg',
			[
				'label' 		=> __('Background', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .wew-newsletter-form-wrap input[type="email"]' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_color',
			[
				'label' 		=> __('Color', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .wew-newsletter-form-wrap input[type="email"]' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_input_hover',
			[
				'label' => __('Hover', 'woovina-elementor-widgets'),
			]
		);

		$this->add_control(
			'input_bg_hover',
			[
				'label' 		=> __('Background', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .wew-newsletter-form-wrap input[type="email"]:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_color_hover',
			[
				'label' 		=> __('Color', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .wew-newsletter-form-wrap input[type="email"]:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_border_color_hover',
			[
				'label' 		=> __('Border Color', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .wew-newsletter-form-wrap input[type="email"]:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_input_focus',
			[
				'label' => __('Focus', 'woovina-elementor-widgets'),
			]
		);

		$this->add_control(
			'input_bg_focus',
			[
				'label' 		=> __('Background', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .wew-newsletter-form-wrap input[type="email"]:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_color_focus',
			[
				'label' 		=> __('Color', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .wew-newsletter-form-wrap input[type="email"]:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_border_color_focus',
			[
				'label' 		=> __('Border Color', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .wew-newsletter-form-wrap input[type="email"]:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' 			=> 'input_border',
				'label' 		=> __('Border', 'woovina-elementor-widgets'),
				'placeholder' 	=> '1px',
				'default' 		=> '1px',
				'selector' 		=> '{{WRAPPER}} .wew-newsletter-form-wrap input[type="email"]',
			]
		);

		$this->add_control(
			'input_border_radius',
			[
				'label' 		=> __('Border Radius', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .wew-newsletter-form-wrap input[type="email"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'newsletter_input',
				'selector' 		=> '{{WRAPPER}} .wew-newsletter-form-wrap input[type="email"]',
				'scheme' 		=> Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_btn',
			[
				'label' 		=> __('Button', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::SECTION,
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'btn_border_radius',
			[
				'label' 		=> __('Border Radius', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .wew-newsletter-form-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'newsletter_btn',
				'selector' 		=> '{{WRAPPER}} .wew-newsletter-form-button',
				'scheme' 		=> Scheme_Typography::TYPOGRAPHY_2,
			]
		);

		$this->start_controls_tabs('tabs_button_style');

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __('Normal', 'woovina-elementor-widgets'),
			]
		);

		$this->add_control(
			'btn_bg',
			[
				'label' 		=> __('Background', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .wew-newsletter-form-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_color',
			[
				'label' 		=> __('Color', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .wew-newsletter-form-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __('Hover', 'woovina-elementor-widgets'),
			]
		);

		$this->add_control(
			'btn_hover_bg',
			[
				'label' 		=> __('Background', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .wew-newsletter-form-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_co_hoverlor',
			[
				'label' 		=> __('Color', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .wew-newsletter-form-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_checkbox',
			[
				'label' 		=> __('GDPR Checkbox', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::SECTION,
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'gdpr_label_typo',
				'selector' 		=> '{{WRAPPER}} .gdpr-wrap label',
				'scheme' 		=> Scheme_Typography::TYPOGRAPHY_2,
			]
		);

		$this->add_control(
			'gdpr_label_color',
			[
				'label' 		=> __('Label Color', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .gdpr-wrap label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'gdpr_checkbox_bg',
			[
				'label' 		=> __('Checkbox Background Color', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .gdpr-wrap input[type="checkbox"]' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'gdpr_checkbox_color',
			[
				'label' 		=> __('Checkbox Color', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .gdpr-wrap input[type="checkbox"]:checked:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'gdpr_checkbox_border_color',
			[
				'label' 		=> __('Checkbox Border Color', 'woovina-elementor-widgets'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .gdpr-wrap input[type="checkbox"]' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Admin ajax, put it in data so that it works in the edit mode of Elementor
		$ajax = admin_url('admin-ajax.php'); ?>

		<div class="wew-newsletter-form clr" data-ajaxurl="<?php echo esc_url($ajax); ?>">

			<div id="mc_embed_signup" class="wew-newsletter-form-wrap">

	            <form action="" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" novalidate>

                    <div class="email-wrap elem-wrap">
                        <input type="email" value="<?php echo $settings['placeholder_text']; ?>" onfocus="if(this.value == this.defaultValue)this.value = '';" onblur="if(this.value == '')this.value = this.defaultValue;" name="EMAIL" class="required email">

                        <?php if($settings['submit_text']) { ?>
                            <button type="submit" value="" name="subscribe" class="wew-newsletter-form-button button">
                                <?php echo $settings['submit_text']; ?>
                            </button>
                        <?php } ?>
                    </div>
                    <span class="email-err err-msg req" style="display:none;"><?php _e('Email is required.', 'woovina-elementor-widgets'); ?></span>
                    <span class="email-err err-msg not-valid" style="display:none;"><?php _e('Email not valid.', 'woovina-elementor-widgets'); ?></span>

                    <?php if($settings['gdpr_label']) { ?>
                        <div class="gdpr-wrap elem-wrap">
                            <label><input type="checkbox" name="GDPR" value="1" class="gdpr required"><?php echo $settings['gdpr_label']; ?></label>
                            <span class="gdpr-err err-msg" style="display:none;"><?php _e('This field is required', 'woovina-elementor-widgets'); ?></span>
                        </div>
                    <?php } ?>

                    <div class="success res-msg" style="display:none;"><?php _e('Thanks for your subscription.', 'woovina-elementor-widgets'); ?></div>
                    <div class="failed  res-msg" style="display:none;"><?php _e('Failed to subscribe, please contact admin.', 'woovina-elementor-widgets'); ?></div>
                </form>

	        </div><!--.wew-newsletter-form-wrap-->

	    </div><!-- .wew-newsletter-form -->

	<?php
	}

	protected function _content_template() { ?>
		<div class="wew-newsletter-form clr">

			<div id="mc_embed_signup" class="wew-newsletter-form-wrap">

				<form action="" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" novalidate>

                    <div class="email-wrap elem-wrap">
                        <input type="email" value="{{ settings.placeholder_text }}" name="EMAIL" class="required email">

                        <# if(settings.submit_text) { #>
                            <button type="submit" value="" name="subscribe" class="wew-newsletter-form-button button">
                                {{{ settings.submit_text }}}
                            </button>
                        <# } #>
                    </div>
                    <span class="email-err err-msg req" style="display:none;"><?php _e('Email is required.', 'woovina-elementor-widgets'); ?></span>
                    <span class="email-err err-msg not-valid" style="display:none;"><?php _e('Email not valid.', 'woovina-elementor-widgets'); ?></span>

                    <# if(settings.gdpr_label) { #>
                        <div class="gdpr-wrap elem-wrap">
                            <label><input type="checkbox" name="GDPR" value="1" class="gdpr required">{{{ settings.gdpr_label }}}</label>
                            <span class="gdpr-err err-msg" style="display:none;"><?php _e('This field is required', 'woovina-elementor-widgets'); ?></span>
                        </div>
                    <# } #>

                    <div class="success res-msg" style="display:none;"><?php _e('Thanks for your subscription.', 'woovina-elementor-widgets'); ?></div>
                    <div class="failed  res-msg" style="display:none;"><?php _e('Failed to subscribe, please contact admin.', 'woovina-elementor-widgets'); ?></div>
                </form>

	        </div><!--.wew-newsletter-form-wrap-->

	    </div><!-- .wew-newsletter-form -->
	<?php
	}

}