<?php

namespace F4\WPI\Plugins\YoastSEO;

use F4\WPI\Core\Helpers as Core;
use F4\WPI\Core\Options\Helpers as Options;

/**
 * Plugins/YoastSEO hooks
 *
 * Hooks for the Plugins/YoastSEO module
 *
 * @since 1.6.0
 * @package F4\WPI\Plugins\YoastSEO
 */
class Hooks {
	/**
	 * Initialize the hooks
	 *
	 * @since 1.6.0
	 * @access public
	 * @static
	 */
	public static function init() {
		self::loaded();
		add_action('F4/WPI/set_constants', __NAMESPACE__ . '\\Hooks::set_default_constants');
		add_action('F4/WPI/after_loaded', __NAMESPACE__ . '\\Hooks::after_loaded');
	}

	/**
	 * Fires once the module is loaded
	 *
	 * @since 1.6.0
	 * @access public
	 * @static
	 */
	public static function loaded() {
		add_filter('F4/WPI/register_options_tabs', __NAMESPACE__ . '\\Hooks::register_options_tabs', 50);
		add_filter('F4/WPI/register_options_sections', __NAMESPACE__ . '\\Hooks::register_options_sections');
		add_filter('F4/WPI/register_options_settings', __NAMESPACE__ . '\\Hooks::register_options_settings');
		add_filter('F4/WPI/register_options_fields', __NAMESPACE__ . '\\Hooks::register_options_fields');

		add_action('F4/WPI/after_update', __NAMESPACE__ . '\\Hooks::after_update');
	}

	/**
	 * Sets the module default constants
	 *
	 * @since 1.6.0
	 * @access public
	 * @static
	 */
	public static function set_default_constants() {

	}

	/**
	 * Register admin options tab
	 *
	 * @since 1.6.0
	 * @access public
	 * @static
	 */
	public static function register_options_tabs($tabs) {
		$tabs['yoastseo'] = [
			'label' => __('Yoast SEO', 'f4-improvements')
		];

		return $tabs;
	}

	/**
	 * Register admin options sections
	 *
	 * @since 1.6.0
	 * @access public
	 * @static
	 */
	public static function register_options_sections($sections) {
		$sections['yoastseo-social'] = [
			'tab' => 'yoastseo',
			'title' => __('Social', 'f4-improvements')
		];

		return $sections;
	}

	/**
	 * Register admin options
	 *
	 * @since 1.6.0
	 * @access public
	 * @static
	 */
	public static function register_options_settings($settings) {
		$settings['yoastseo_og_image_size'] = [
			'default' => '',
			'type' => 'string'
		];

		return $settings;
	}

	/**
	 * Register admin options fields
	 *
	 * @since 1.6.0
	 * @access public
	 * @static
	 */
	public static function register_options_fields($fields) {
		$image_sizes_options = [
			'' => __('Default', 'f4-improvements')
		];

		foreach(get_intermediate_image_sizes() as $image_size) {
			$image_sizes_options[$image_size] = $image_size;
		}

		$fields['yoastseo_og_image_size'] = [
			'tab' => 'yoastseo',
			'section' => 'yoastseo-social',
			'type' => 'select',
			'label' => __('Image size for Open Graph', 'f4-improvements'),
			'options' => $image_sizes_options
		];

		return $fields;
	}

	/**
	 * Fires after the module is loaded
	 *
	 * @since 1.6.0
	 * @access public
	 * @static
	 */
	public static function after_loaded() {
		add_filter('wpseo_opengraph_image_size', __NAMESPACE__ . '\\Hooks::set_opengraph_image_size');
	}

	/**
	 * Save ship_to_different_address in session
	 *
	 * @since 1.6.0
	 * @access public
	 * @static
	 */
	public static function set_opengraph_image_size($image_size) {
		$new_image_size = Options::get('yoastseo_og_image_size');

		if(!empty($new_image_size)) {
			$image_size = $new_image_size;
		}

		return $image_size;
	}
}

?>
