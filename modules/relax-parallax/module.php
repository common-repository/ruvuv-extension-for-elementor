<?php

namespace RuvuvElementorExpand\Modules\RelaxParallax;

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
		return 'relax-parallax';
	}

	public function ruvuv_rellax_parallax(Controls_Stack $element) {
		$element->start_controls_section(
			'rellax_parallax_tab',
			[
				'label' => $this->brand . __( 'Rellax Parallax', 'ruvuv-extension' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$element->add_control(
			'rellax_parallax',
			[
				'label'        => esc_html__( 'Enable', 'ruvuv-extension' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'frontend_available' => true,
				'render_type' => 'template',
			]
		);

		$element->add_control(
			'rellax_parallax_speed',
			[
				'label' => __( 'Speed', 'ruvuv-extension' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -10,
						'max' => 10,
						'step' => 0.1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'frontend_available' => true,
				'render_type' => 'template',
				'condition' => [
					'rellax_parallax' => 'yes',
				],
			]
		);

		$element->add_control(
			'rellax_parallax_percentage',
			[
				'label' => __( 'Percentage', 'ruvuv-extension' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 0.5,
				],
				'frontend_available' => true,
				'render_type' => 'template',
				'condition' => [
					'rellax_parallax' => 'yes',
				],
			]
		);

		$element->add_control(
			'rellax_parallax_zindex',
			[
				'label' => __( 'Z-Index', 'ruvuv-extension' ),
				'type' => Controls_Manager::NUMBER,
				'min' => -10,
				'max' => 10000,
				'step' => 1,
				'default' => 0,
				'frontend_available' => true,
				'render_type' => 'template',
				'condition' => [
					'rellax_parallax' => 'yes',
				],
			]
		);

		$element->end_controls_section();
	}

	public function rellax_parallax_before_render($section) {
		$settings = $section->get_settings();
		if ($settings['rellax_parallax']) {
			$section->add_render_attribute( '_wrapper', 'class', 'ruvuv-rellax' );
		}
	}

	private function add_actions() {
		add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'ruvuv_rellax_parallax' ], 10, 2 );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'ruvuv_rellax_parallax' ], 10, 2 );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'ruvuv_rellax_parallax' ] );
		add_action( 'elementor/frontend/widget/before_render', [ $this, 'rellax_parallax_before_render' ] );
		add_action( 'elementor/frontend/column/before_render', [ $this, 'rellax_parallax_before_render' ] );
		add_action( 'elementor/frontend/section/before_render', [ $this, 'rellax_parallax_before_render' ] );
	
		add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
	}

	public function enqueue_scripts() {
		wp_enqueue_script(
			'eg-rellax',
			RUVUV_EXPAND_ASSETS_URL . 'lib/rellax/rellax.min.js',
			[
				'jquery'
			], null, false
		);
	}

}