<?php

namespace RuvuvElementorExpand\Modules\ColumnOrder;

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
		return 'column-order';
	}

	public function ruvuv_colum_order(Controls_Stack $element) {
		$element->add_responsive_control(
			'ruvuv_column_order',
			[
				'label' => $this->brand . __( 'Responsive Column Order', 'ruvuv-extension' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '-webkit-order: {{SIZE}}; order: {{SIZE}};',
				],
			]
		);
	}

	private function add_actions() {
		add_action( 'elementor/element/column/section_advanced/before_section_end', [ $this, 'ruvuv_colum_order' ] );
	}

}