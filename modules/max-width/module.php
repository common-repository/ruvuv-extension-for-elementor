<?php

namespace RuvuvElementorExpand\Modules\MaxWidth;

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
		return 'max-width';
	}

	public function ruvuv_max_width(Controls_Stack $element) {
		$element->start_controls_section(
			'ruvuv_max_width_section',
			[
				'label' => $this->brand . __( 'Widget Max Width', 'ruvuv-extension' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$element->add_control(
			'ruvuv_max_width_enable',
			[
				'label' => __( 'Max Width', 'ruvuv-extension' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => 'Yes',
				'label_off' => 'No',
				'return_value' => 'yes',
			]
		);

		$element->add_responsive_control(
			'ruvuv_max_width',
			[
				'label' =>'Max Width',
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 2000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'max-width: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'ruvuv_max_width_enable' => 'yes',
				],
			]
		);

		$element->add_responsive_control(
			'ruvuv_max_width_center_align',
			[
				'label' => __( 'Center Align Element', 'ruvuv-extension' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'ruvuv-extension' ),
                        'icon'  => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'ruvuv-extension' ),
                        'icon'  => 'fa fa-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'ruvuv-extension' ),
                        'icon'  => 'fa fa-align-right',
                    ),
                ),
                'selectors_dictionary' => [
                    'left' => 'margin-right: auto !important;',
                    'center' => 'margin-left: auto !important; margin-right: auto !important;',
                    'right' => 'margin-left: auto !important;',
                ],
				'selectors' => [
					'{{WRAPPER}}' => 'display: block !important; {{VALUE}}',
				],
				'condition' => [
					'ruvuv_max_width_enable' => 'yes',
				],
			]
		);

		$element->end_controls_section();
	}

	private function add_actions() {
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'ruvuv_max_width' ], 10, 2 );
	}

}