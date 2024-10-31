<?php

namespace RuvuvElementorExpand\Modules\Heading;

use Elementor\Core\Responsive\Responsive;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Core\Files\CSS\Post;
use Elementor\Element_Base;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Scheme_Typography;
use Elementor\Element_Column;
use Elementor\Element_Section;
use Elementor\Widget_Base;
use RuvuvElementorExpand\Base\Module_Base;
use RuvuvElementorExpand\Ruvuv_Extension;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public $brand = '<img src="'.RUVUV_EXPAND_ASSETS_URL.'image/logo.png'.'" style="max-height: 12px; margin-right: 10px; vertical-align: middle;">';

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	public function get_name() {
		return 'heading';
	}

	public function ruvuv_heading(Controls_Stack $element) {
		$element->start_controls_section(
			'ruvuv_heading_tab',
			[
				'label' => $this->brand . __( 'Heading Expand', 'ruvuv-extension' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$element->add_control(
			'ruvuv_heading',
			[
				'label'        => esc_html__( 'Enable', 'ruvuv-extension' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'frontend_available' => true,
				'render_type' => 'template',
			]
		);

		$element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'heading_background',
				'label' => __( 'Background', 'ruvuv-extension' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}}.elementor-widget-heading .elementor-heading-title',
				'condition' => [
					'ruvuv_heading' => 'yes',
				],
			]
		);

		$element->add_control(
			'ruvuv_heading_color',
			[
				'label' => __( 'Title Color', 'ruvuv-extension' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-widget-heading .elementor-heading-title' => '-webkit-background-clip: text; -webkit-text-fill-color: {{VALUE}};',
				],
				'selectors_dictionary' => [
					'' => 'transparent',
				],
				'condition' => [
					'ruvuv_heading' => 'yes',
				],
			]
		);

		$element->add_responsive_control(
			'ruvuv_heading_rotate',
			[
				'label' => __( 'Rotate', 'ruvuv-extension' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'condition' => [
					'ruvuv_heading' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-widget-heading .elementor-heading-title' => 'transform:rotate({{SIZE}}deg);',
				],
			]
		);

		$element->end_controls_section();
	}

	private function add_actions() {
		add_action( 'elementor/element/heading/section_title_style/after_section_end', [ $this, 'ruvuv_heading' ] );
	}

}