<?php

namespace RuvuvElementorExpand\Modules\MediaSlider;

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
		return 'media-slider';
	}

	public function bg_image_slider_controls(Controls_Stack $element) {
		$element->start_controls_section(
			'eg_bg_slider_tab',
			[
				'label' => $this->brand . __( 'Background Media Slider', 'ruvuv-extension' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$element->add_control(
			'eg_bg_slider_on',
			[
				'label'        => esc_html__( 'Enable', 'ruvuv-extension' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'separator'    => 'before',
				'frontend_available' => true,
				'render_type' => 'template',
			]
		);

		$element->add_control(
			'eg_bg_slider_elements',
			[
				'label'   => __( 'Slider Items', 'ruvuv-extension' ),
				'type'    => Controls_Manager::REPEATER,
				'condition'    => [
					'eg_bg_slider_on' => 'yes',
				],
				'render_type' => 'template',
				'fields' => [
					[
						'name'	=> 'eg_bg_slider_media_select',
						'label'	=> __('Select Media Resource', 'ruvuv-extension'),
						'type'	=> Controls_Manager::SELECT,
						'options' => [
							'media' => __( 'Select From Media', 'ruvuv-extension' ),
							'link' => __( 'Use Link', 'ruvuv-extension' ),
						],
						'default'	=> 'media',
						'frontend_available' => true,
					],
					[
						'name'	=> 'eg_bg_slider_elements_media',
						'label'	=> __('Select Media', 'ruvuv-extension'),
						'type'	=> Controls_Manager::MEDIA,
						'label_block' => true,
						'condition'    => [
							'eg_bg_slider_media_select' => 'media',
						],
					],
					[
						'name'	=> 'eg_bg_slider_elements_url',
						'label'	=> __('Media URL', 'ruvuv-extension'),
						'type'	=> Controls_Manager::TEXT,
						'show_external' => true,
						'label_block' => true,
						'condition'    => [
							'eg_bg_slider_media_select' => 'link',
						],
					],
					[
						'name'	=> 'eg_bg_slider_elements_alt',
						'label'	=> __('Alternative Text', 'ruvuv-extension'),
						'type'	=> Controls_Manager::TEXT,
						'show_external' => true,
					]
				]
			]
		);
		
		$element->add_control(
			'eg_bg_slider_duration',
			array(
				'label'        => esc_html__( 'Duration', 'ruvuv-extension' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 10000,
						'step' => 200,
					)
				),
				'default' => array(
					'unit' => 'px',
					'size' => 5000,
				),
				'frontend_available' => true,
				'render_type' => 'template',
				'condition'    => [
					'eg_bg_slider_on' => 'yes',
				],
			)
		);

		$element->add_control(
			'eg_bg_slider_transition',
			[
				'label'	=> __('Transition Type', 'ruvuv-extension'),
				'type'	=> Controls_Manager::SELECT,
				'options' => [
					'fade' => __( 'Fade', 'ruvuv-extension' ),
					'fade_in_out' => __( 'Fade In Out', 'ruvuv-extension' ),
					'push_left' => __( 'Push Left', 'ruvuv-extension' ),
					'push_up' => __( 'Push Up', 'ruvuv-extension' ),
					'push_down' => __( 'Push Down', 'ruvuv-extension' ),
					'cover_left' => __( 'Cover Left', 'ruvuv-extension' ),
					'cover_right' => __( 'Cover Right', 'ruvuv-extension' ),
					'cover_up' => __( 'Cover Up', 'ruvuv-extension' ),
					'cover_down' => __( 'Cover Down', 'ruvuv-extension' ),
				],
				'default'	=> 'fade',
				'frontend_available' => true,
				'render_type' => 'template',
				'condition'    => [
					'eg_bg_slider_on' => 'yes',
				],
			]
		);

		$element->add_control(
			'eg_bg_slider_transitionDuration',
			[
				'label'	=> __('Transition Duration', 'ruvuv-extension'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5000,
						'step' => 100,
					]
				],
				'frontend_available' => true,
				'render_type' => 'template',
				'default' => [
					'unit' => 'px',
					'size' => 400,
				],
				'condition'    => [
					'eg_bg_slider_on' => 'yes',
				],
			]
		);

		$element->end_controls_section();
	}

	public function eg_bg_slider_before_render($section) {
		$settings = $section->get_settings();
		if( $settings['eg_bg_slider_on'] == 'yes' ) {
			$slider_elements = $settings['eg_bg_slider_elements'];
			if (empty($slider_elements)) return;

			$section->add_render_attribute( '_wrapper', 'class', 'eg-bg-slider' );

			$section->add_render_attribute( '_wrapper', 'data-slider', json_encode($slider_elements) );
		}
	}

	private function add_actions() {
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'bg_image_slider_controls' ] );
        add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'bg_image_slider_controls' ] );
		add_action( 'elementor/frontend/section/before_render', [ $this, 'eg_bg_slider_before_render' ], 10, 1 );
        add_action( 'elementor/frontend/column/before_render', [ $this, 'eg_bg_slider_before_render' ], 10, 1 );
		
		add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
	}

	public function enqueue_scripts() {
		wp_enqueue_script(
            'eg-jquery.backstretch',
            RUVUV_EXPAND_ASSETS_URL . 'lib/backstretch/jquery.backstretch.min.js',
            [
                'jquery'
            ], null, false
        );
	}

}