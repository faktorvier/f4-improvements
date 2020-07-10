<?php

namespace F4\WPI\System;

use F4\WPI\Core\Helpers as Core;
use F4\WPI\Core\Options\Helpers as Options;

/**
 * System hooks
 *
 * Hooks for the System module
 *
 * @since 1.0.0
 * @package F4\WPI\System
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
		add_action('F4/WPI/set_constants', __NAMESPACE__ . '\\Hooks::set_default_constants');
		add_action('F4/WPI/loaded', __NAMESPACE__ . '\\Hooks::loaded');
		add_action('F4/WPI/after_loaded', __NAMESPACE__ . '\\Hooks::after_loaded');
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
	 * Fires once the module is loaded
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 */
	public static function loaded() {
		add_filter('F4/WPI/register_options_tabs', __NAMESPACE__ . '\\Hooks::register_options_tabs', 25);
		add_filter('F4/WPI/register_options_sections', __NAMESPACE__ . '\\Hooks::register_options_sections');
		add_filter('F4/WPI/register_options_settings', __NAMESPACE__ . '\\Hooks::register_options_settings');
		add_filter('F4/WPI/register_options_fields', __NAMESPACE__ . '\\Hooks::register_options_fields');
	}

	/**
	 * Fires after the module is loaded
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 */
	public static function after_loaded() {
		// Updates
		if(Options::get('disable_core_update_email')) {
			add_filter('auto_core_update_send_email', '__return_false');
		}

		// Security
		if(Options::get('disable_xmlrpc')) {
			add_filter('xmlrpc_enabled', '__return_false');
		}

		if(Options::get('disable_theme_editor') && !defined('DISALLOW_FILE_EDIT')) {
			define('DISALLOW_FILE_EDIT', true);
		}
	}

	/**
	 * Register admin options tabs
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function register_options_tabs($tabs) {
		$tabs['system'] = [
			'label' => __('System', 'f4-improvements')
		];

		return $tabs;
	}

	/**
	 * Register admin options sections
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function register_options_sections($sections) {
		$sections['system-updates'] = [
			'tab' => 'system',
			'title' => __('Updates', 'f4-improvements')
		];

		$sections['system-security'] = [
			'tab' => 'system',
			'title' => __('Security', 'f4-improvements')
		];

		return $sections;
	}

	/**
	 * Register admin options
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function register_options_settings($settings) {
		$settings['disable_core_update_email'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		$settings['disable_xmlrpc'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		$settings['disable_theme_editor'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		return $settings;
	}

	/**
	 * Register admin options fields
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function register_options_fields($fields) {
		// Register updates fields
		$fields['disable_core_update_email'] = [
			'tab' => 'system',
			'section' => 'system-updates',
			'type' => 'checkbox',
			'label' => __('Disable Core Update Email', 'f4-improvements')
		];

		// Register security fields
		$fields['disable_xmlrpc'] = [
			'tab' => 'system',
			'section' => 'system-security',
			'type' => 'checkbox',
			'label' => __('Disable XML-RPC', 'f4-improvements')
		];

		$fields['disable_theme_editor'] = [
			'tab' => 'system',
			'section' => 'system-security',
			'type' => 'checkbox',
			'label' => __('Disable Theme Editor', 'f4-improvements')
		];

		return $fields;
	}
}

?>
