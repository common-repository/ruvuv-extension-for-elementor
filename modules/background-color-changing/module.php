<?php

namespace RuvuvElementorExpand\Modules\BackgroundColorChanging;

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
		return 'three-d';
	}

	public function background_color_changing_register_controls( Controls_Stack $element ) {
		$element->start_controls_section(
			'background_color_changing_tab',
			[
				'label' => $this->brand . __( 'Multi Color Motion', 'ruvuv-extension' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$element->add_control(
			'background_color_changing',
			array(
				'label'        => esc_html__( 'Enable', 'ruvuv-extension' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'render_type'	=> 'template'
			)
		);

		$element->add_control(
			'background_color_changing_angle',
			array(
				'label' => __( 'Angle', 'ruvuv-extension' ),
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
					'size' => -45,
				],
				'frontend_available' => false,
				'condition'    => [
					'background_color_changing' => 'yes',
				],
				'render_type'	=> 'template'
			)
		);

		$element->add_control(
			'background_color_animation_time',
			array(
				'label' => __( 'Animation Time', 'ruvuv-extension' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'frontend_available' => false,
				'condition'    => [
					'background_color_changing' => 'yes',
				],
				'render_type'	=> 'template'
			)
		);

		$element->add_control(
			'changing_colors',
			[
				'label'   => __( 'Colors', 'ruvuv-extension' ),
				'type'    => Controls_Manager::REPEATER,
				'condition'    => [
					'background_color_changing' => 'yes',
				],
				'frontend_available' => false,
				'render_type'	=> 'template',
				'fields' => [
					[
						'name'	=> 'changing_color',
						'label'	=> __('Color', 'ruvuv-extension'),
						'type'	=> Controls_Manager::COLOR,
					],
				]
			]
		);

		$element->end_controls_section();
	}

	public function background_color_changing_before_render($section) {
		$settings = $section->get_settings();
		if( $settings['background_color_changing'] == 'yes' ) {

			$section->add_render_attribute( '_wrapper', 'class', 'ruvuv-gradient-move' );

			if (!empty($settings['changing_colors'])) {
				$changing_colors = '';
				$count = 1;
				foreach ($settings['changing_colors'] as $key => $value) {
					if ($count < count($settings['changing_colors'])) {
						$changing_colors .= $value['changing_color'].',';
					} else {
						$changing_colors .= $value['changing_color'];
					}
					
					$count++;
				}

				$cssString = 'linear-gradient('.$settings['background_color_changing_angle']['size'].'deg,'.$changing_colors.')';
				echo '<div class="ruvuv-gradient-move-color" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; background: '.$cssString.'; background-size: 400% 400% !important; -webkit-animation: Gradient '.$settings['background_color_animation_time']['size'].'s ease-in-out infinite; -moz-animation: Gradient '.$settings['background_color_animation_time']['size'].'s ease-in-out infinite; animation: Gradient '.$settings['background_color_animation_time']['size'].'s ease-in-out infinite;"></div>';
			}
		}
	}

	private function add_actions() {
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'background_color_changing_register_controls' ], 10, 2 );
		add_action( 'elementor/frontend/section/before_render', [ $this, 'background_color_changing_before_render' ] );
	}

}