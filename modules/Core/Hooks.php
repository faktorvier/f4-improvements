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
		add_action('F4/WPI/set_constants', __NAMESPACE__ . '\\Hooks::set_default_constants', 1);
		add_action('plugins_loaded', __NAMESPACE__ . '\\Hooks::core_loaded');
		add_action('setup_theme', __NAMESPACE__ . '\\Hooks::core_after_loaded');
		add_action('init', __NAMESPACE__ . '\\Hooks::load_textdomain');

		register_activation_hook(F4_WPI_MAIN_FILE, __NAMESPACE__ . '\\Hooks::core_loaded');
	}

	/**
	 * Sets the plugin default constants
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function set_default_constants() {

	}

	/**
	 * Fires once the plugin is loaded
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function core_loaded() {
		do_action('F4/WPI/set_constants');
		do_action('F4/WPI/loaded');
	}

	/**
	 * Fires once after the plugin is loaded
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 */
	public static function core_after_loaded() {
		do_action('F4/WPI/after_loaded');
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
