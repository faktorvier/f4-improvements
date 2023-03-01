<?php

namespace F4\WPI\Core\Options;

/**
 * Core\Options Helpers
 *
 * Helpers for the Core\Options module
 *
 * @since 1.0.0
 * @package F4\WPI\Core
 */
class Helpers {
	public static $settings = null;

	/**
	 * Check if current page is options page
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function is_option_page() {
		return isset(get_current_screen()->base) && get_current_screen()->base === 'settings_page_' . F4_WPI_OPTION_PAGE_NAME;
	}

	/**
	 * Register fields
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function register_fields() {
		$fields = apply_filters('F4/WPI/register_options_fields', []);

		foreach($fields as $field_name => $field_args) {
			add_settings_field(
				F4_WPI_OPTION_PREFIX . $field_name,
				$field_args['label'] ?? '',
				function($args) {
					include F4_WPI_PATH . 'modules/Core/Options/views/field-' . $args['field']['type'] . '.php';
				},
				F4_WPI_OPTION_PAGE_NAME . '-' . $field_args['tab'],
				$field_args['section'] ?? 'default',
				[
					'field' => $field_args,
					'option_name' => $field_name,
					'field_name' => F4_WPI_OPTION_PREFIX . $field_name,
					'label_for' => F4_WPI_OPTION_PREFIX . $field_name
				]
			);
		}
	}

	/**
	 * Get options settings
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function get_settings($name = null) {
		if(!self::$settings) {
			self::$settings = apply_filters('F4/WPI/register_options_settings', []);
		}

		$return = self::$settings;

		if($name) {
			$name = str_replace(F4_WPI_OPTION_PREFIX, '', $name);
			$return = self::$settings[$name] ?? null;
		}

		return $return;
	}

	/**
	 * Get options
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function get($name) {
		$settings = self::get_settings();
		$default = $settings[$name]['default'] ?? false;

		return get_option(F4_WPI_OPTION_PREFIX . $name, $default);
	}
}
