<?php
namespace RuvuvElementorExpand;

use Elementor\Core\Responsive\Files\Frontend as FrontendFile;
use Elementor\Core\Responsive\Responsive;
use Elementor\Utils;

/**
 * Main Ruvuv_Extension Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Ruvuv_Extension {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Ruvuv_Extension The single instance of the class.
	 */
	private static $_instance = null;

	private $classes_aliases = [
		'RuvuvElementorExpand\Modules\PanelPostsControl\Module' => 'RuvuvElementorExpand\Modules\QueryControl\Module',
		'RuvuvElementorExpand\Modules\PanelPostsControl\Controls\Group_Control_Posts' => 'RuvuvElementorExpand\Modules\QueryControl\Controls\Group_Control_Posts',
		'RuvuvElementorExpand\Modules\PanelPostsControl\Controls\Query' => 'RuvuvElementorExpand\Modules\QueryControl\Controls\Query',
	];

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return Ruvuv_Extension An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {

		spl_autoload_register( [ $this, 'autoload' ] );

		$this->includes();

		$this->setup_hooks();
	}

	/**
	 * @return \Elementor\Plugin
	 */

	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}

	private function includes() {
		require RUVUV_EXPAND_INCLUDE_DIR . 'modules-manager.php';
	}

	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$has_class_alias = isset( $this->classes_aliases[ $class ] );

		// Backward Compatibility: Save old class name for set an alias after the new class is loaded
		if ( $has_class_alias ) {
			$class_alias_name = $this->classes_aliases[ $class ];
			$class_to_load = $class_alias_name;
		} else {
			$class_to_load = $class;
		}

		if ( ! class_exists( $class_to_load ) ) {
			$filename = strtolower(
				preg_replace(
					[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$class_to_load
				)
			);
			$filename = RUVUV_EXPAND_PATH . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include( $filename );
			}
		}

		if ( $has_class_alias ) {
			class_alias( $class_alias_name, $class );
		}
	}

	private function setup_hooks() {
		add_action( 'elementor/init', [ $this, 'elementor_init' ] );

		add_action( 'elementor/frontend/before_register_scripts', [ $this, 'register_frontend_scripts' ] );

		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );
		//add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );

		add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_styles' ] );

		do_action( 'elementor/frontend/before_register_styles', [ $this, 'external_register_styles' ] );
	}

	public function enqueue_styles() {
		wp_enqueue_style( 'font-awesome-animation', RUVUV_EXPAND_ASSETS_URL . 'lib/font-awesome-animation.min.css', '', RUVUV_EXPAND_VERSION );

		wp_enqueue_style(
			'ruvuv-extension-frontend',
			RUVUV_EXPAND_ASSETS_URL . 'css/frontend.css',
			'',
			RUVUV_EXPAND_VERSION
		);
	}

	public function external_register_styles() {

		wp_register_style( 'ruvuv-extension-tippy', RUVUV_EXPAND_ASSETS_URL . 'lib/tippy/tippy.min.css', [], RUVUV_EXPAND_VERSION );

		wp_register_style( 'font-awesome-animation', RUVUV_EXPAND_ASSETS_URL . 'lib/font-awesome-animation.min.css', [], RUVUV_EXPAND_VERSION );
	}

	public function enqueue_frontend_scripts() {

		wp_enqueue_script(
			'ruvuv-extension-frontend',
			RUVUV_EXPAND_ASSETS_URL . 'js/frontend.js',
			[
				'jquery',
				'eg-sticky'
			],
			RUVUV_EXPAND_VERSION,
			true
		);

		$locale_settings = [
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'ruvuv-extension-frontend' ),
			'lang' => get_locale(),
		];

		$locale_settings = apply_filters( 'ruvuv_expand/frontend/localize_settings', $locale_settings );

		wp_localize_script(
			'ruvuv-extension-frontend',
			'RuvuvElementorExpandFrontendConfig',
			$locale_settings
		);
	}

	public function enqueue_editor_scripts() {

		wp_enqueue_script(
			'ruvuv-extension-editor',
			RUVUV_EXPAND_ASSETS_URL . 'js/editor.js',
			[
				'jquery'
			],
			RUVUV_EXPAND_VERSION,
			true
		);

		$locale_settings = [
			'i18n' => [],
			'isActive' => true,
			'lang' => get_locale(),
		];

		$locale_settings = apply_filters( 'ruvuv_expand/editor/localize_settings', $locale_settings );

		wp_localize_script(
			'ruvuv-extension-editor',
			'RuvuvElementorExpandConfig',
			$locale_settings
		);
	}

	public function register_frontend_scripts() {
		wp_register_script(
			'eg-sticky',
			RUVUV_EXPAND_ASSETS_URL . 'lib/sticky/jquery.sticky.js',
			[
				'jquery',
			],
			RUVUV_EXPAND_VERSION,
			true
		);

        wp_enqueue_script(
            'eg-ResizeSensor',
            RUVUV_EXPAND_ASSETS_URL . 'lib/ResizeSensor.min.js',
            [
                'jquery'
            ], null, false
        );
	}

	public function enqueue_editor_styles() {

		wp_enqueue_style(
			'ruvuv-extension-editor',
			RUVUV_EXPAND_ASSETS_URL . 'css/editor.css',
			'',
			RUVUV_EXPAND_VERSION
		);
	}

	public function elementor_init() {
		$this->modules_manager = new Manager();

		do_action( 'ruvuv_expand/init' );
	}
}

Ruvuv_Extension::instance();