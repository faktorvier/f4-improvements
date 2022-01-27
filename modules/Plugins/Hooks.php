<?php

namespace F4\WPI\Plugins;

/**
 * Plugins hooks
 *
 * Hooks for the Plugins module
 *
 * @since 1.2.0
 * @package F4\WPI\Plugins
 */
class Hooks {
	/**
	 * Initialize the plugins hooks
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 */
	public static function init() {
		add_action('F4/WPI/loaded', __NAMESPACE__ . '\\Hooks::loaded');

		// WP Rocket activation
		register_activation_hook(F4_WPI_MAIN_FILE, '\F4\WPI\Plugins\WPRocket\Hooks::activate_plugin');
		register_deactivation_hook(F4_WPI_MAIN_FILE, '\F4\WPI\Plugins\WPRocket\Hooks::deactivate_plugin');
	}

	/**
	 * Fires once the plugins are loaded
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 */
	public static function loaded() {
		// Check if plugins are installed
		define('F4_WPI_PLUGIN_ACTIVE_WPROCKET', defined('WP_ROCKET_VERSION'));
		define('F4_WPI_PLUGIN_ACTIVE_WOOCOMMERCE', function_exists('WC'));
		define('F4_WPI_PLUGIN_ACTIVE_YOASTSEO', defined('WPSEO_VERSION'));

		// Init plugins improvements
		if(F4_WPI_PLUGIN_ACTIVE_WPROCKET) {
			\F4\WPI\Plugins\WPRocket\Hooks::init();
		}

		if(F4_WPI_PLUGIN_ACTIVE_WOOCOMMERCE) {
			\F4\WPI\Plugins\WooCommerce\Hooks::init();
		}

		if(F4_WPI_PLUGIN_ACTIVE_YOASTSEO) {
			\F4\WPI\Plugins\YoastSEO\Hooks::init();
		}
	}
}
