<?php

namespace F4\WPI\Plugins\WPRocket;

use F4\WPI\Core\Helpers as Core;

/**
 * Plugins/WPRocket Helpers
 *
 * Helpers for the Plugins/WPRocket module
 *
 * @since 1.2.0
 * @package F4\WPI\Plugins\WPRocket
 */
class Helpers {
	/**
	 * Clear all caches
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 */
	public static function clear_cache() {
		if(function_exists('rocket_generate_config_file')) {
			rocket_generate_config_file();
		}

		if(function_exists('rocket_clean_domain')) {
			rocket_clean_domain();
		}

		if(function_exists('rocket_clean_minify')) {
			rocket_clean_minify();
		}

		if(function_exists('rocket_clean_cache_busting')) {
			rocket_clean_cache_busting();
		}

		if(function_exists('flush_rocket_htaccess')) {
			//flush_rocket_htaccess();
		}

		$critical_css_path = rocket_get_constant('WP_ROCKET_CRITICAL_CSS_PATH') . get_current_blog_id() . '/';

		if(file_exists($critical_css_path)) {
			Core::rmdir_content($critical_css_path);
		}

		/*
		set_transient('rocket_clear_cache', 'all', HOUR_IN_SECONDS);

		$options = get_option(WP_ROCKET_SLUG);
		$options['minify_css_key'] = create_rocket_uniqid();
		$options['minify_js_key'] = create_rocket_uniqid();
		remove_all_filters('update_option_' . WP_ROCKET_SLUG);
		update_option(WP_ROCKET_SLUG, $options);

		rocket_dismiss_box('rocket_warning_plugin_modification');
		 */
	}
}
