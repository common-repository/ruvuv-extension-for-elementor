<?php
namespace RuvuvElementorExpand\Modules\CustomCss;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Core\Files\CSS\Post;
use Elementor\Element_Base;
use Elementor\Element_Column;
use Elementor\Element_Section;
use Elementor\Widget_Base;
use RuvuvElementorExpand\Base\Module_Base;
use RuvuvElementorExpand\Ruvuv_Extension;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public $brand = '<img src="'.RUVUV_EXPAND_ASSETS_URL.'image/logo.png'.'" style="max-height: 12px; margin-right: 10px; vertical-align: middle;">';

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	public function get_name() {
		return 'customcss';
	}

	/**
	 * @param $element    Controls_Stack
	 * @param $section_id string
	 */
	public function register_controls( Controls_Stack $element, $section_id ) {

		if ( $element instanceof Element_Section || $element instanceof Widget_Base ) {
			$required_section_id = '_section_responsive';
		} elseif ( $element instanceof Element_Column ) {
			$required_section_id = 'section_advanced';
		} else {
			$required_section_id = 'section_page_style';
		}

		if ( $required_section_id !== $section_id ) {
			return;
		}

		$element->start_controls_section(
			'ruvuv_expand_section_custom_css',
			[
				'label' => $this->brand . __( 'Custom CSS', 'ruvuv-extension' ),
				'tab' => 'section_page_style' === $section_id ? Controls_Manager::TAB_STYLE : Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'ruvuv_expand_custom_css_title',
			[
				'raw' => __( 'Add your own custom CSS here', 'ruvuv-extension' ),
				'type' => Controls_Manager::RAW_HTML,
			]
		);

		$element->add_control(
			'ruvuv_expand_custom_css',
			[
				'type' => Controls_Manager::CODE,
				'label' => __( 'Major Custom CSS', 'ruvuv-extension' ),
				'language' => 'css',
				'render_type' => 'ui',
				'show_label' => false,
				'separator' => 'none',
			]
		);

		$element->add_control(
			'ruvuv_expand_custom_css_description',
			[
				'raw' => __( 'Use "selector" to target wrapper element. Examples:<br>selector {color: red;} // For main element<br>selector .child-element {margin: 10px;} // For child element<br>.my-class {text-align: center;} // Or use any custom selector', 'ruvuv-extension' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);

		$element->end_controls_section();
	}

	/**
	 * @param $post_css Post
	 * @param $element  Element_Base
	 */
	public function add_post_css( $post_css, $element ) {
		$element_settings = $element->get_settings();

		if ( empty( $element_settings['ruvuv_expand_custom_css'] ) ) {
			return;
		}

		$css = trim( $element_settings['ruvuv_expand_custom_css'] );

		if ( empty( $css ) ) {
			return;
		}
		$css = str_replace( 'selector', $post_css->get_element_unique_selector( $element ), $css );

		// Add a css comment
		$css = sprintf( '/* Start custom CSS for %s, class: %s */', $element->get_name(), $element->get_unique_selector() ) . $css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css( $css );
	}

	/**
	 * @param $post_css Post
	 */
	public function add_page_settings_css( $post_css ) {
		$document = Ruvuv_Extension::elementor()->documents->get( $post_css->get_post_id() );
		$custom_css = $document->get_settings( 'ruvuv_expand_custom_css' );

		$custom_css = trim( $custom_css );

		if ( empty( $custom_css ) ) {
			return;
		}

		$custom_css = str_replace( 'selector', 'body.elementor-page-' . $post_css->get_post_id(), $custom_css );

		// Add a css comment
		$custom_css = '/* Start custom CSS for page-settings */' . $custom_css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css( $custom_css );
	}

	protected function add_actions() {
		add_action( 'elementor/element/after_section_end', [ $this, 'register_controls' ], 10, 2 );
		add_action( 'elementor/element/parse_css', [ $this, 'add_post_css' ], 10, 2 );
		add_action( 'elementor/post-css-file/parse', [ $this, 'add_page_settings_css' ] );
	}
}
