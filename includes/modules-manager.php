<?php
namespace RuvuvElementorExpand;

use RuvuvElementorExpand\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

final class Manager {
	/**
	 * @var Module_Base[]
	 */
	private $modules = [];

	public function __construct() {
		$modules = [
			'customcss',
			'background-color-changing',
			'sticky',
			'tooltip',
			'schedule',
			'particle',
			'image-moving',
			'media-slider',
			'relax-parallax',
			'column-order',
			'heading',
			'section-link',
			'max-width',
		];

		foreach ( $modules as $module_name ) {
			$class_name = str_replace( '-', ' ', $module_name );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$class_name = __NAMESPACE__ . '\\Modules\\' . $class_name . '\Module';

			/** @var Module_Base $class_name */
			if ( $class_name::is_active() && get_option( 'ruvuv-' . $module_name, 'on' ) == 'on' ) {
				$this->modules[ $module_name ] = $class_name::instance();
			}
		}
	}

	/**
	 * @param string $module_name
	 *
	 * @return Module_Base|Module_Base[]
	 */
	public function get_modules( $module_name ) {
		if ( $module_name ) {
			if ( isset( $this->modules[ $module_name ] ) ) {
				return $this->modules[ $module_name ];
			}

			return null;
		}

		return $this->modules;
	}
}
