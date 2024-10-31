<?php

namespace RuvuvElementorExpand\Modules\Sticky;

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
		return 'Sticky';
	}

	public function sticky_register_controls( Controls_Stack $element ) {
		$element->start_controls_section(
			'eg_sticky_tab',
			[
				'label' => $this->brand . __( 'Sticky', 'ruvuv-extension' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'eg_sticky',
			[
				'label' => __( 'Sticky', 'ruvuv-extension' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'ruvuv-extension' ),
					'top' => __( 'Top', 'ruvuv-extension' ),
					'bottom' => __( 'Bottom', 'ruvuv-extension' ),
				],
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'eg_sticky_on',
			[
				'label' => __( 'Sticky On', 'ruvuv-extension' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => 'true',
				'default' => [ 'desktop', 'tablet', 'mobile' ],
				'options' => [
					'desktop' => __( 'Desktop', 'ruvuv-extension' ),
					'tablet' => __( 'Tablet', 'ruvuv-extension' ),
					'mobile' => __( 'Mobile', 'ruvuv-extension' ),
				],
				'condition' => [
					'eg_sticky!' => '',
				],
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'eg_sticky_offset',
			[
				'label' => __( 'Offset', 'ruvuv-extension' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 500,
				'required' => true,
				'condition' => [
					'eg_sticky!' => '',
				],
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'eg_sticky_effects_offset',
			[
				'label' => __( 'Effects Offset', 'ruvuv-extension' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 100,
				'required' => true,
				'condition' => [
					'eg_sticky!' => '',
				],
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$element->add_responsive_control(
			'eg_sticky_zindex',
			[
				'label' => __( 'Z-Index', 'ruvuv-extension' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 999,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-sticky--active' => 'z-index: {{SIZE}};',
				],
				'condition' => [
					'eg_sticky!' => '',
				],
				'render_type' => 'template',
			]
		);

		if ( $element instanceof Widget_Base ) {
			$element->add_control(
				'eg_sticky_parent',
				[
					'label' => __( 'Stay In Column', 'ruvuv-extension' ),
					'type' => Controls_Manager::SWITCHER,
					'condition' => [
						'eg_sticky!' => '',
					],
					'render_type' => 'template',
					'frontend_available' => true,
				]
			);
		}

		$element->end_controls_section();
	}

	public function sticky_column_register_controls( Controls_Stack $element ) {
		$element->start_controls_section(
			'eg_column_sticky_tab',
			[
				'label' => $this->brand . __( 'Sticky', 'ruvuv-extension' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'eg_column_sticky',
			[
				'label' => __( 'Sticky', 'ruvuv-extension' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'render_type'	=> 'template'
			]
		);

		$element->add_control(
			'eg_column_sticky_on',
			array(
				'label'    => __( 'Sticky On', 'ruvuv-extension' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => 'true',
				'default' => array(
					'desktop',
					'tablet',
				),
				'options' => array(
					'desktop' => __( 'Desktop', 'ruvuv-extension' ),
					'tablet'  => __( 'Tablet', 'ruvuv-extension' ),
					'mobile'  => __( 'Mobile', 'ruvuv-extension' ),
				),
				'condition' => array(
					'eg_column_sticky!' => '',
				),
				'render_type'        => 'template',
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'eg_column_sticky_top_offset',
			[
				'label' => __( 'Top Offset', 'ruvuv-extension' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 500,
				'required' => true,
				'condition' => [
					'eg_column_sticky!' => '',
				],
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'eg_column_sticky_bottom_offset',
			[
				'label' => __( 'Bottom Offset', 'ruvuv-extension' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 500,
				'required' => true,
				'condition' => [
					'eg_column_sticky!' => '',
				],
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$element->end_controls_section();
	}

	public function sticky_column_before_render($section) {    		
		$settings = $section->get_settings();
		if( $section->get_settings( 'eg_column_sticky' ) == 'yes' ) {

			$section->add_render_attribute( '_wrapper', 'class', 'ruvuv-sticky-column' );
		}
	}

	private function add_actions() {
		add_action( 'elementor/element/section_advanced/after_section_end', [ $this, 'sticky_register_controls' ] );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'sticky_register_controls' ] );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'sticky_register_controls' ] );

		//sticky column
		add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'sticky_column_register_controls' ] );
		add_action( 'elementor/frontend/column/before_render', [ $this, 'sticky_column_before_render' ], 10, 1 );

		add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
	}

	public function enqueue_scripts() {
		wp_enqueue_script(
            'eg-sticky-sidebar',
            RUVUV_EXPAND_ASSETS_URL . 'lib/sticky-sidebar/sticky-sidebar.min.js',
            [
                'jquery'
            ], null, false
        );
	}

}