<?php

namespace F4\WPI\Core;

/**
 * Core Hooks
 *
 * Hooks for the Core module
 *
 * @since 1.0.0
 * @package F4\WPI\Core
 */
class Hooks {
	/**
	 * Initialize the hooks
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function init() {
		register_activation_hook(F4_WPI_MAIN_FILE, __NAMESPACE__ . '\\Hooks::activate_plugin');

		add_action('plugins_loaded', __NAMESPACE__ . '\\Hooks::core_loaded');
		add_action('F4/WPI/set_constants', __NAMESPACE__ . '\\Hooks::set_default_constants', 98);
	}

	/**
	 * Plugin activation
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function activate_plugin() {
		do_action('F4/WPI/set_constants');
	}

	/**
	 * Sets the module default constants
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function set_default_constants() {

	}

	/**
	 * Fires once the core module is loaded
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function core_loaded() {
		do_action('F4/WPI/set_constants');
		do_action('F4/WPI/loaded');

		add_action('init', __NAMESPACE__ . '\\Hooks::load_textdomain');
	}

	/**
	 * Load plugin textdomain
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function load_textdomain() {
		load_plugin_textdomain('f4-improvements', false, plugin_basename(F4_WPI_PATH . 'languages') . '/');
	}
}

?>
