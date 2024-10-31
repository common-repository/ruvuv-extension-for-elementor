<?php
/**
 * Plugin Name: Ruvuv Extension For Elementor
 * Description: Extended Visual Functionality Add-on for Elementor Page Builder.
 * Plugin URI:  https://elementorextension.ruvuv.com/
 * Version:     1.0.0
 * Author:      Ruvuv
 * Author URI:  https://ruvuv.com/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: ruvuv-extension
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'RUVUV_EXPAND_VERSION', '1.0.0' );
define( 'RUVUV_MINIMUM_PHP_VERSION', '7.0' );
define( 'RUVUV_EXPAND_BASE', plugin_basename( __FILE__ ) );
define( 'RUVUV_EXPAND_PATH', plugin_dir_path( __FILE__ ) );
define( 'RUVUV_EXPAND_URL', plugins_url( '/', __FILE__ ) );
define( 'RUVUV_EXPAND_INCLUDE_DIR', RUVUV_EXPAND_PATH . 'includes/' );
define( 'RUVUV_EXPAND_ASSETS_PATH', RUVUV_EXPAND_PATH . 'assets/' );
define( 'RUVUV_EXPAND_ASSETS_URL', RUVUV_EXPAND_URL . 'assets/' );

if(! function_exists('ruvuv_expand_load_plugin')) {
    add_action( 'plugins_loaded', 'ruvuv_expand_load_plugin' );
	function ruvuv_expand_load_plugin(){
		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', 'ruvuv_expand_admin_notice_missing_main_plugin' );
			return;
		}

		// Check for required Elementor version
		$elementor_version_required = '2.1.0';
		if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
			add_action( 'admin_notices', 'ruvuv_expand_admin_notice_minimum_elementor_version' );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, RUVUV_MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', 'ruvuv_expand_admin_notice_minimum_php_version' );
			return;
		}

        require_once RUVUV_EXPAND_PATH . 'includes/admin-settings.php';

		require_once RUVUV_EXPAND_PATH . 'includes/plugin.php';
	}
}

/**
 * Admin notice
 *
 * Warning when the site doesn't have Elementor installed or activated.
 *
 * @since 1.0.0
 *
 * @access public
 */
if(! function_exists('ruvuv_expand_admin_notice_missing_main_plugin')) {
	function ruvuv_expand_admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$screen = get_current_screen();
		if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
			return;
		}

		$plugin = 'elementor/elementor.php';

		if ( ruvuv_expand_is_elementor_installed() ) {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

			$message = '<p>' . __( 'Ruvuv Elementor Expand not working because you need to activate the Elementor plugin.', 'ruvuv-extension' ) . '</p>';
			$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Elementor Now', 'ruvuv-extension' ) ) . '</p>';
		} else {
			if ( ! current_user_can( 'install_plugins' ) ) {
				return;
			}

			$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

			$message = '<p>' . __( 'Ruvuv Elementor Expand not working because you need to install the Elementor plugin', 'ruvuv-extension' ) . '</p>';
			$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Elementor Now', 'ruvuv-extension' ) ) . '</p>';
		}

		echo '<div class="error"><p>' . $message . '</p></div>';
	}
}

/**
 * Admin notice
 *
 * Warning when the site doesn't have a minimum required Elementor version.
 *
 * @since 1.0.0
 *
 * @access public
 */
if(! function_exists('ruvuv_expand_admin_notice_minimum_elementor_version')) {
	function ruvuv_expand_admin_notice_minimum_elementor_version() {

		if ( ! current_user_can( 'update_plugins' ) ) {
			return;
		}

		$file_path = 'elementor/elementor.php';

		$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
		$message = '<p>' . __( 'Ruvuv Elementor Expand not working because you are using an old version of Elementor.', 'ruvuv-extension' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'ruvuv-extension' ) ) . '</p>';

		echo '<div class="error">' . $message . '</div>';
	}
}

/**
 * Admin notice
 *
 * Warning when the site doesn't have a minimum required PHP version.
 *
 * @since 1.0.0
 *
 * @access public
 */
if(! function_exists('ruvuv_expand_admin_notice_minimum_php_version')) {
	function ruvuv_expand_admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ruvuv-extension' ),
			'<strong>' . esc_html__( 'Dial Elementor Extension', 'ruvuv-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'ruvuv-extension' ) . '</strong>', RUVUV_MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}
}

if(! function_exists('ruvuv_expand_is_elementor_installed')) {
	function ruvuv_expand_is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}

add_filter( 'plugin_row_meta', 'ruvuv_expand_plugin_meta', 10, 2 );
function ruvuv_expand_plugin_meta($plugin_meta, $plugin_file) {
	if($plugin_file == RUVUV_EXPAND_BASE) {
		$row_meta = [
			'docs' => '<a href="https://elementorextension.ruvuv.com/tutorials/" aria-label="' . esc_attr( __( 'View Ruvuv Elementor Expand Documentation', 'ruvuv-extension' ) ) . '" target="_blank">' . __( 'Documentation', 'ruvuv-extension' ) . '</a>',
		];

		$plugin_meta = array_merge( $plugin_meta, $row_meta );
	}

	return $plugin_meta;
}

function ruvuv_expand_option( $option, $default = '' ) {

    $options = get_option( $option );

    if ( !is_null($options) ) {
        return $options;
    }

    return $default;
}