<?php

namespace RuvuvElementorExpand\Modules\Schedule;

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
		return 'schedule';
	}

	public function schedule_register_controls(Controls_Stack $element) {
		$element->start_controls_section(
			'eg_schedule_tab',
			[
				'label' => $this->brand . __( 'Schedule', 'ruvuv-extension' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);
		
		$element->add_control(
			'eg_schedule',
			[
				'label'        => __( 'Enable', 'ruvuv-extension' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'description'  => __( 'Switch on to schedule the contents.', 'ruvuv-extension' ),
			]
		);
		
		$element->add_control(
			'eg_schedule_start_date',
			[
				'label' => __( 'Start Date', 'ruvuv-extension' ),
				'type' => Controls_Manager::DATE_TIME,
				'default' => '2018-02-01 00:00:00',
				'condition' => [
					'eg_schedule' => 'yes',
				],
				'description' => __( 'Set start date.', 'ruvuv-extension' ),
			]
		);
		
		$element->add_control(
			'eg_schedule_end_date',
			[
				'label' => __( 'End Date', 'ruvuv-extension' ),
				'type' => Controls_Manager::DATE_TIME,
				'default' => '2018-02-28 00:00:00',
				'condition' => [
					'eg_schedule' => 'yes',
				],
				'description' => __( 'Set end date.', 'ruvuv-extension' ),
			]
		);

		$element->end_controls_section();
	}

	public function schedule_before_render($section) {    		
		$settings = $section->get_settings();
		if( $section->get_settings( 'eg_schedule' ) == 'yes' ) {
			$star_date    = strtotime($settings['eg_schedule_start_date']);
			$end_date     = strtotime($settings['eg_schedule_end_date']);
			$current_date = strtotime(gmdate( 'Y-m-d H:i', ( time() + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ) ));

			if ( ($current_date >= $star_date) and ($current_date <= $end_date) ) {
				$section->add_render_attribute( '_wrapper', 'class', 'eg-scheduled' );
			} else {
				$section->add_render_attribute( '_wrapper', 'class', 'eg-hidden' );
			}
		}
	}

	public function eg_schedule_before_render($section) {    		
		$settings = $section->get_settings();
		if( $section->get_settings( 'eg_schedule' ) == 'yes' ) {
			$star_date    = strtotime($settings['eg_schedule_start_date']);
			$end_date     = strtotime($settings['eg_schedule_end_date']);
			$current_date = strtotime(gmdate( 'Y-m-d H:i', ( time() + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ) ));

			if ( ($current_date >= $star_date) and ($current_date <= $end_date) ) {
				$section->add_render_attribute( '_wrapper', 'class', 'eg-scheduled' );
			} else {
				$section->add_render_attribute( '_wrapper', 'class', 'eg-hidden' );
			}
		}
	}

	private function add_actions() {
		add_action( 'elementor/element/section_advanced/after_section_end', [ $this, 'schedule_register_controls' ] );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'schedule_register_controls' ] );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'schedule_register_controls' ] );
		add_action( 'elementor/frontend/before_render', [ $this, 'schedule_before_render' ], 10, 1 );
		add_action( 'elementor/frontend/section/before_render', [ $this, 'schedule_before_render' ], 10, 1 );
	}

}