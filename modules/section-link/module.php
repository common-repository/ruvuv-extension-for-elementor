<?php

namespace RuvuvElementorExpand\Modules\SectionLink;

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
		return 'section-link';
	}

	public function section_link(Controls_Stack $element) {
		$element_name = $element->get_name();

		if($element_name == 'column') {
			$element->add_control(
				'ruvuv_section_link',
				[
					'label' => $this->brand . __( 'Column Link', 'ruvuv-extension' ),
					'type' => \Elementor\Controls_Manager::URL,
					'description' => __('Note that it is not visible in edit/preview mode & can only be viewed on the frontend.', 'ruvuv-extension'),
					'label_block' => true,
                    'dynamic' => [
                        'active' => true,
                    ],
				]
			);
		} else if($element_name == 'section') {
			$element->add_control(
				'ruvuv_section_link',
				[
					'label' => $this->brand . __( 'Section Link', 'ruvuv-extension' ),
					'type' => \Elementor\Controls_Manager::URL,
					'description' => __('Note that it is not visible in edit/preview mode & can only be viewed on the frontend.', 'ruvuv-extension'),
					'label_block' => true,
                    'dynamic' => [
                        'active' => true,
                    ],
				]
			);
		}
	}

	public function section_link_before_render($element) {
		$settings = $element->get_settings();
		$link = $settings['ruvuv_section_link'];
		if( !empty($link['url']) ) {
			$element->add_render_attribute( '_wrapper', [
				'data-ruvuv-section-link' => esc_url($link['url']),
				'data-ruvuv-section-link-external' => $link['is_external'],
			] );
		}
	}

	private function add_actions() {
		add_action( 'elementor/element/section/section_layout/before_section_end', [ $this, 'section_link' ], 10, 2 );
		add_action( 'elementor/frontend/section/before_render', [ $this, 'section_link_before_render' ] );
		add_action( 'elementor/element/column/layout/before_section_end', [ $this, 'section_link' ] );
		add_action( 'elementor/frontend/column/before_render', [ $this, 'section_link_before_render' ] );
	}

}