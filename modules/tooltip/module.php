<?php

namespace RuvuvElementorExpand\Modules\Tooltip;

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

	public function tooltip_register_controls( Controls_Stack $element ) {
		$element->start_controls_section(
			'eg_tooltip_tab',
			[
				'label' => $this->brand . __( 'Tooltip', 'ruvuv-extension' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'eg_tooltip',
			array(
				'label'        => esc_html__( 'Enable', 'ruvuv-extension' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'frontend_available' => true,
				'render_type'	=> 'template'
			)
		);

		$element->start_controls_tabs( 'eg_tooltip_tabs' );

		$element->start_controls_tab(
			'eg_tooltip_settings_tab',
			array(
				'label' => esc_html__( 'Settings', 'ruvuv-extension' ),
				'condition' => array(
					'eg_tooltip' => 'yes',
				),
			)
		);

		$element->add_control(
			'eg_tooltip_position',
			[
				'label' => __( 'Position', 'ruvuv-extension' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => __( 'Top', 'ruvuv-extension' ),
					'bottom' => __( 'Bottom', 'ruvuv-extension' ),
					'left' => __( 'Left', 'ruvuv-extension' ),
					'right' => __( 'Right', 'ruvuv-extension' ),
				],
				'default' => 'top',
				'render_type' => 'template',
				'condition' => array(
					'eg_tooltip' => 'yes',
				),
			]
		);

		$element->add_control(
			'eg_tooltip_description',
			array(
				'label' => esc_html__( 'Description', 'ruvuv-extension' ),
				'type'  => Controls_Manager::TEXTAREA,
				'render_type'  => 'template',
				'default'      => 'This is tooltip',
				'condition' => array(
					'eg_tooltip' => 'yes',
				),
			)
		);

		$element->add_control(
			'eg_tooltip_trigger',
			[
				'label' => __( 'Trigger', 'ruvuv-extension' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'mouseenter' => __( 'Mouse Enter', 'ruvuv-extension' ),
					'click' => __( 'Click', 'ruvuv-extension' )
				],
				'default'=> 'mouseenter',
				'condition' => [
					'eg_tooltip' => 'yes',
				],
				'render_type' => 'template',
			]
		);

		$element->add_control(
			'eg_tooltip_animation',
			array(
				'label'   => esc_html__( 'Animation', 'ruvuv-extension' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'shift-toward',
				'options' => array(
					'shift-away'   => esc_html__( 'Shift-Away', 'ruvuv-extension' ),
					'shift-toward' => esc_html__( 'Shift-Toward', 'ruvuv-extension' ),
					'fade'         => esc_html__( 'Fade', 'ruvuv-extension' ),
					'scale'        => esc_html__( 'Scale', 'ruvuv-extension' ),
					'perspective'  => esc_html__( 'Perspective', 'ruvuv-extension' ),
				),
				'render_type'  => 'template',
				'condition' => array(
					'eg_tooltip' => 'yes',
				),
			)
		);

		$element->add_control(
			'eg_tooltip_x_offset',
			array(
				'label'   => esc_html__( 'Offset', 'ruvuv-extension' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
				'min'     => -1000,
				'max'     => 1000,
				'step'    => 1,
				'condition' => array(
					'eg_tooltip' => 'yes',
				),
				'render_type'	=> 'template'
			)
		);

		$element->add_control(
			'eg_tooltip_y_offset',
			array(
				'label'   => esc_html__( 'Distance', 'ruvuv-extension' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
				'min'     => -1000,
				'max'     => 1000,
				'step'    => 1,
				'condition' => array(
					'eg_tooltip' => 'yes',
				),
				'render_type'	=> 'template'
			)
		);

		$element->add_control(
			'eg_tooltip_z_index',
			array(
				'label'   => esc_html__( 'Z-Index', 'ruvuv-extension' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 999,
				'min'     => 0,
				'max'     => 999,
				'step'    => 1,
				'condition' => array(
					'eg_tooltip' => 'yes',
				),
				'render_type'	=> 'template'
			)
		);

		$element->end_controls_tab();

		$element->start_controls_tab(
			'eg_tooltip_styles_tab',
			array(
				'label' => esc_html__( 'Style', 'ruvuv-extension' ),
				'condition' => array(
					'eg_tooltip' => 'yes',
				),
			)
		);

		$element->add_responsive_control(
			'eg_tooltip_width',
			array(
				'label'      => esc_html__( 'Width', 'ruvuv-extension' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 50,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .tippy-tooltip' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'eg_tooltip' => 'yes',
				),
				'render_type'  => 'template',
			)
		);

		$element->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'eg_tooltip_typography',
				'selector' => '{{WRAPPER}} .tippy-tooltip .tippy-content .ruvuv-widget__content',
				'condition' => array(
					'eg_tooltip' => 'yes',
				),
			)
		);

		$element->add_control(
			'eg_tooltip_color',
			array(
				'label'  => esc_html__( 'Text Color', 'ruvuv-extension' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tippy-tooltip' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'eg_tooltip' => 'yes',
				),
			)
		);

		$element->add_control(
			'eg_tooltip_text_align',
			array(
				'label'   => esc_html__( 'Text Alignment', 'ruvuv-extension' ),
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
				'condition' => array(
					'eg_tooltip' => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tippy-tooltip .tippy-content' => 'text-align: {{VALUE}};',
				),
			)
		);

		$element->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'eg_tooltip_background',
				'selector' => '{{WRAPPER}} .tippy-tooltip',
				'condition' => array(
					'eg_tooltip' => 'yes',
				),
			)
		);

		$element->add_control(
			'overlay_color',
			[
				'label' => __( 'Overlay Color', 'ruvuv-extension' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tippy-tooltip .tippy-content .tooltip-overlay' => 'background-color: {{VALUE}}; position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;',
				],
				'condition' => array(
					'eg_tooltip' => 'yes',
				),
			]
		);

		$element->add_control(
			'eg_tooltip_arrow_color',
			array(
				'label'  => esc_html__( 'Arrow Color', 'ruvuv-extension' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tippy-popper[x-placement^=left] .tippy-tooltip .tippy-arrow'=> 'border-left-color: {{VALUE}}',
					'{{WRAPPER}} .tippy-popper[x-placement^=right] .tippy-tooltip .tippy-arrow'=> 'border-right-color: {{VALUE}}',
					'{{WRAPPER}} .tippy-popper[x-placement^=top] .tippy-tooltip .tippy-arrow'=> 'border-top-color: {{VALUE}}',
					'{{WRAPPER}} .tippy-popper[x-placement^=bottom] .tippy-tooltip .tippy-arrow'=> 'border-bottom-color: {{VALUE}}',
				),
				'condition' => array(
					'eg_tooltip' => 'yes',
				),
			)
		);

		$element->add_responsive_control(
			'eg_tooltip_padding',
			array(
				'label'      => __( 'Padding', 'ruvuv-extension' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tippy-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'render_type'  => 'template',
				'condition' => array(
					'eg_tooltip' => 'yes',
				),
			)
		);

		$element->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'eg_tooltip_border',
				'label'       => esc_html__( 'Border', 'ruvuv-extension' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .tippy-tooltip',
				'condition' => array(
					'eg_tooltip' => 'yes',
				),
			)
		);

		$element->add_responsive_control(
			'eg_tooltip_border_radius',
			array(
				'label'      => __( 'Border Radius', 'ruvuv-extension' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tippy-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'eg_tooltip' => 'yes',
				),
			)
		);

		$element->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'eg_tooltip_box_shadow',
				'selector' => '{{WRAPPER}} .tippy-tooltip',
				'condition' => array(
					'eg_tooltip' => 'yes',
				),
			)
		);

		$element->end_controls_tab();

		$element->end_controls_tabs();

		$element->end_controls_section();
	}

	public function eg_tooltip_before_render($widget) {
		$settings = $widget->get_settings();
		$id = $widget->get_id();
		if (!empty($settings['eg_tooltip'])) {
			$widget_settings['tooltip'] = $settings['eg_tooltip'] == 'yes' ? 'yes' : '';
			$widget_settings['tooltipDescription'] = $settings['eg_tooltip_description'];
			$widget_settings['tooltipPlacement'] = $settings['eg_tooltip_position'];
			$widget_settings['xOffset'] = $settings['eg_tooltip_x_offset'];
			$widget_settings['yOffset'] = $settings['eg_tooltip_y_offset'];
			$widget_settings['tooltipAnimation'] = $settings['eg_tooltip_animation'];
			$widget_settings['zIndex'] = $settings['eg_tooltip_z_index'];
			$widget_settings['tooltip_trigger'] = $settings['eg_tooltip_trigger'];

			$widget->add_render_attribute( '_wrapper', 'class', 'ruvuv-tooltip-widget' );
			$widget->add_render_attribute( '_wrapper', 'data-tooltip-settings', json_encode($widget_settings));

			if(!empty($settings['eg_tooltip_description']))
			echo sprintf( '<div id="ruvuv-tooltip-content-%1$s" class="ruvuv-widget__content">%2$s<div class="tooltip-overlay"></div></div>', $id, $settings['eg_tooltip_description'] );
		}
	}

	private function add_actions() {
		add_action( 'elementor/element/section_advanced/after_section_end', [ $this, 'tooltip_register_controls' ] );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'tooltip_register_controls' ] );
		add_action( 'elementor/frontend/widget/before_render', [ $this, 'eg_tooltip_before_render' ], 10, 1 );
	
		add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
	}

	public function enqueue_scripts() {
		wp_enqueue_script(
            'eg-tippy',
            RUVUV_EXPAND_ASSETS_URL . 'lib/tippy/tippy.all.min.js',
            [
                'jquery'
            ], null, false
        );
	}

}