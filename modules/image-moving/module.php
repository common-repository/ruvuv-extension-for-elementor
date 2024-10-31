<?php

namespace RuvuvElementorExpand\Modules\ImageMoving;

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
		return 'bg-media-slider';
	}

	public function bg_moving_image_register_controls(Controls_Stack $element) {	
		$element->add_control(
			'bg_moving_image',
			[
				'label'        => $this->brand . esc_html__( 'Background Image Moving', 'ruvuv-extension' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'description'  => esc_html__( 'Enable this option to move background image.', 'ruvuv-extension' ),
				'separator'    => 'before',
				'condition'    => [
					'background_background' => ['classic'],
				],
				'render_type' => 'template'
			]
		);

		$element->add_control(
			'bg_moving_image_on',
			[
				'label' => __( 'Moving Image On', 'ruvuv-extension' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => 'true',
				'frontend_available' => true,
				'default' => [ 'desktop', 'tablet', 'mobile' ],
				'options' => [
					'desktop' => __( 'Desktop', 'ruvuv-extension' ),
					'tablet' => __( 'Tablet', 'ruvuv-extension' ),
					'mobile' => __( 'Mobile', 'ruvuv-extension' ),
				],
				'condition' => [
					'bg_moving_image' => 'yes',
				],
			]
		);

		$element->add_control(
			'bg_moving_image_direction',
			[
				'label' => __( 'Direction Type', 'ruvuv-extension' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h' => __( 'Horizontal', 'ruvuv-extension' ),
					'v' => __( 'Vertical', 'ruvuv-extension' ),
					'd' => __( 'Diagonal', 'ruvuv-extension' ),
				],
				'default'	=> 'h',
				'condition'    => [
					'bg_moving_image' => 'yes',
				],
				'render_type'	=> 'template'
			]
		);

		$element->add_control(
			'bg_moving_image_direction_type_h',
			[
				'label' => __( 'Moving Type', 'ruvuv-extension' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'+' => __( 'Left To Right', 'ruvuv-extension' ),
					'-' => __( 'Right To Left', 'ruvuv-extension' )
				],
				'default'	=> '+',
				'condition'    => [
					'bg_moving_image' => 'yes',
					'bg_moving_image_direction' => 'h',
				],
				'render_type'	=> 'template'
			]
		);

        $element->add_control(
            'bg_moving_image_horizontal_position',
            [
                'label' => __( 'Image Position', 'ruvuv-extension' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'top' => __( 'Top', 'ruvuv-extension' ),
                    'middle' => __( 'Middle', 'ruvuv-extension' ),
                    'bottom' => __( 'Bottom', 'ruvuv-extension' )
                ],
                'default'	=> 'middle',
                'condition'    => [
                    'bg_moving_image' => 'yes',
                    'bg_moving_image_direction' => 'h',
                ],
                'render_type'	=> 'template'
            ]
        );

		$element->add_control(
			'bg_moving_image_direction_type_v',
			[
				'label' => __( 'Moving Type', 'ruvuv-extension' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'+' => __( 'Top To Bottom', 'ruvuv-extension' ),
					'-' => __( 'Bottom To Top', 'ruvuv-extension' )
				],
				'default'	=> '+',
				'condition'    => [
					'bg_moving_image' => 'yes',
					'bg_moving_image_direction' => 'v',
				],
				'render_type'	=> 'template'
			]
		);

        $element->add_control(
            'bg_moving_image_vertical_position',
            [
                'label' => __( 'Image Position', 'ruvuv-extension' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'left' => __( 'Left', 'ruvuv-extension' ),
                    'center' => __( 'Center', 'ruvuv-extension' ),
                    'right' => __( 'Right', 'ruvuv-extension' )
                ],
                'default'	=> 'center',
                'condition'    => [
                    'bg_moving_image' => 'yes',
                    'bg_moving_image_direction' => 'v',
                ],
                'render_type'	=> 'template'
            ]
        );

		$element->add_control(
			'bg_moving_image_direction_type_d',
			[
				'label' => __( 'Moving Type', 'ruvuv-extension' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'+' => __( 'Left-Top To Right-Bottom', 'ruvuv-extension' ),
					'-' => __( 'Right-Bottom To Left-Top', 'ruvuv-extension' )
				],
				'default'	=> '+',
				'condition'    => [
					'bg_moving_image' => 'yes',
					'bg_moving_image_direction' => 'd',
				],
				'render_type'	=> 'template'
			]
		);

		$element->add_control(
			'bg_moving_image_diagonal_reverse',
			[
				'label'        => esc_html__( 'Reverse', 'ruvuv-extension' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'render_type' => 'template',
				'condition'    => [
					'bg_moving_image' => 'yes',
					'bg_moving_image_direction' => 'd',
				],
			]
		);

		$element->add_control(
			'bg_moving_image_value',
			[
				'label' => __( 'Moving Speed', 'ruvuv-extension' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 5,
				'render_type' => 'template',
				'condition'    => [
					'bg_moving_image' => 'yes'
				],
			]
		);
	}

	public function eg_bg_moving_image_before_render($section) {
		$settings = $section->get_settings();
		if( $settings['bg_moving_image'] == 'yes' ) {
			$moving_options['speed'] = empty($settings['bg_moving_image_value']) ? 5 : $settings['bg_moving_image_value'];
			$direction = empty($settings['bg_moving_image_direction']) ? 'h' : $settings['bg_moving_image_direction'];
			$moving_options['direction'] = $direction;
			$moving_options['direction_diagonal'] = empty($settings['bg_moving_image_diagonal_reverse']) ? 'no' : $settings['bg_moving_image_diagonal_reverse'];
			
			if ($direction == 'h') {
				$moving_options['direction_type'] = empty($settings['bg_moving_image_direction_type_h']) ? '+' : $settings['bg_moving_image_direction_type_h'];
                $moving_options['horizontal_position'] = empty($settings['bg_moving_image_horizontal_position']) ? 'middle' : $settings['bg_moving_image_horizontal_position'];
			} else if ($direction == 'v') {
				$moving_options['direction_type'] = empty($settings['bg_moving_image_direction_type_v']) ? '+' : $settings['bg_moving_image_direction_type_v'];
                $moving_options['vertical_position'] = empty($settings['bg_moving_image_vertical_position']) ? '+' : $settings['bg_moving_image_vertical_position'];
			} else if ($direction == 'd') {
				$moving_options['direction_type'] = empty($settings['bg_moving_image_direction_type_d']) ? '+' : $settings['bg_moving_image_direction_type_d'];
			}

			$section->add_render_attribute( '_wrapper', 'class', 'bg-moving-image' );
			$section->add_render_attribute( '_wrapper', 'data-moving', json_encode($moving_options) );
		}
	}

	private function add_actions() {
		add_action( 'elementor/element/section/section_background/before_section_end', [ $this, 'bg_moving_image_register_controls' ], 10, 1 );		
		add_action( 'elementor/frontend/section/before_render', [ $this, 'eg_bg_moving_image_before_render' ], 10, 1 );
		add_action( 'elementor/element/column/section_style/before_section_end', [ $this, 'bg_moving_image_register_controls' ], 10, 1 );		
		add_action( 'elementor/frontend/column/before_render', [ $this, 'eg_bg_moving_image_before_render' ], 10, 1 );
	
		add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
	}

	public function enqueue_scripts() {
		wp_enqueue_script(
            'eg-jquery.bgscroll',
            RUVUV_EXPAND_ASSETS_URL . 'lib/bg-moving/jquery.bgscroll.js',
            [
                'jquery'
            ], null, false
        );
	}

}