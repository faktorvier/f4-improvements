<?php

namespace F4\WPI\Core\Options;

use F4\WPI\Core\Helpers as Core;
use F4\WPI\Core\Options\Helpers as Helpers;

/**
 * Core\Options hooks
 *
 * Hooks for the Core\Options module
 *
 * @since 1.0.0
 * @package F4\WPI\Core\Options
 */
class Hooks {
	protected static $page_registered = false;

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
	}

	/**
	 * Sets the module default constants
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function set_default_constants() {
		if(!defined('F4_WPI_OPTION_PAGE_NAME')) {
			define('F4_WPI_OPTION_PAGE_NAME', 'f4-improvements');
		}

		if(!defined('F4_WPI_OPTION_GROUP_NAME')) {
			define('F4_WPI_OPTION_GROUP_NAME', 'f4wpi_options');
		}

		if(!defined('F4_WPI_OPTION_PREFIX')) {
			define('F4_WPI_OPTION_PREFIX', 'f4wpi_');
		}
	}

	/**
	 * Fires once the module is loaded
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function loaded() {
		add_action('admin_menu', __NAMESPACE__ . '\\Hooks::check_registered_options_page', 99);
		add_action('admin_menu', __NAMESPACE__ . '\\Hooks::register_options_page', 99);
		add_action('admin_init', __NAMESPACE__ . '\\Hooks::register_options');
		add_action('admin_head', __NAMESPACE__ . '\\Hooks::add_admin_styles');
		add_filter('plugin_action_links_' . F4_WPI_BASENAME, __NAMESPACE__ . '\\Hooks::add_settings_link_to_plugin_list');
		add_action('wp_redirect',  __NAMESPACE__ . '\\Hooks::after_update', 50, 2);
	}

	/**
	 * Check if options page is already registered
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function check_registered_options_page() {
		global $_registered_pages;

		if(isset($_registered_pages['settings_page_' . F4_WPI_OPTION_PAGE_NAME])) {
			self::$page_registered = true;
		}
	}

	/**
	 * Register options page
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function register_options_page() {
		if(self::$page_registered) {
			return;
		}

		$submenu_label = '
		<span class="' . F4_WPI_OPTION_PAGE_NAME . '-submenu-item">
			<svg class="f4-icon" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"width="75px" height="100px" viewBox="0 0 75 100">
				<path d="M74.295,21.04c0,1.38-1.122,2.504-2.502,2.504H54.259c-1.384,0-2.504-1.124-2.504-2.504V3.504
					c0-1.379,1.12-2.504,2.504-2.504h17.534c1.38,0,2.502,1.125,2.502,2.504V21.04z"/>
				<path d="M74.295,46.562c0,1.384-1.122,2.506-2.502,2.506H54.259c-1.384,0-2.504-1.122-2.504-2.506V29.029
					c0-1.382,1.12-2.504,2.504-2.504h17.534c1.38,0,2.502,1.122,2.502,2.504V46.562z"/>
				<path d="M74.295,72.086c0,1.384-1.122,2.506-2.502,2.506H54.259c-1.384,0-2.504-1.122-2.504-2.506V54.557
					c0-1.387,1.12-2.506,2.504-2.506h17.534c1.38,0,2.502,1.119,2.502,2.506V72.086z"/>
				<path d="M48.769,46.562c0,1.384-1.12,2.506-2.502,2.506H28.733c-1.384,0-2.504-1.122-2.504-2.506V29.029
					c0-1.382,1.12-2.504,2.504-2.504h17.533c1.382,0,2.502,1.122,2.502,2.504V46.562z"/>
				<path d="M48.769,72.086c0,1.384-1.12,2.506-2.502,2.506H28.733c-1.384,0-2.504-1.122-2.504-2.506V54.557
					c0-1.387,1.12-2.506,2.504-2.506h17.533c1.382,0,2.502,1.119,2.502,2.506V72.086z"/>
				<path d="M23.247,72.086c0,1.384-1.124,2.506-2.503,2.506H3.21c-1.384,0-2.505-1.122-2.505-2.506V54.557
					c0-1.387,1.122-2.506,2.505-2.506h17.533c1.379,0,2.503,1.119,2.503,2.506V72.086z"/>
				<path d="M53.833,98.412c-1.086,1.085-2.078,0.581-2.078-0.799V80.077c0-1.38,1.12-2.501,2.504-2.501h17.534
					c1.38,0,1.768,1.106,0.798,2.075L53.833,98.412z"/>
				<path d="M46.691,2.708c1.084-1.087,2.077-0.583,2.077,0.796v17.534c0,1.382-1.12,2.506-2.502,2.506H28.733
					c-1.384,0-1.771-1.107-0.799-2.08L46.691,2.708z"/>
				<path d="M21.167,28.229c1.086-1.085,2.08-0.582,2.08,0.8v17.532c0,1.384-1.124,2.506-2.503,2.506H3.21
					c-1.384,0-1.773-1.107-0.801-2.078L21.167,28.229z"/>
			</svg>

			' .  __('Improvements', 'f4-improvements') . '
		</span>
		';

		add_options_page(
			__('Improvements for WordPress', 'f4-improvements'),
			$submenu_label,
			'manage_options',
			F4_WPI_OPTION_PAGE_NAME,
			function() {
				include F4_WPI_PATH . 'modules/Core/Options/views/admin-options.php';
			}
		);
	}

	/**
	 * Register settings for options page
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function register_options() {
		// Section
		$sections = apply_filters('F4/WPI/register_options_sections', []);

		foreach($sections as $section_name => $section_args) {
			$callback = null;

			if(isset($section_args['description'])) {
				$callback = function() use($section_args) {
					echo $section_args['description'] ?? '';
				};
			}

			add_settings_section(
				$section_name,
				$section_args['title'] ?? null,
				$callback,
				F4_WPI_OPTION_PAGE_NAME . '-' . $section_args['tab']
			);
		}

		// Settings
		$settings = Helpers::get_settings();

		foreach($settings as $option_name => $option_args) {
			$option_args['type'] = $option_args['type'] ?? 'string';
			$option_name_full = F4_WPI_OPTION_PREFIX . $option_name;

			// Sanitize value
			switch($option_args['type']) {
				case 'boolean':
					$option_args['sanitize_callback'] = function($value) {
						return (int)$value;
					};

					break;

				case 'array':
					$option_args['sanitize_callback'] = function($value) {
						return is_array($value) ? $value : [];
					};

					break;
			}

			// Register setting
			register_setting(
				F4_WPI_OPTION_GROUP_NAME,
				$option_name_full,
				[
					'default' => $option_args['default'] ?? [],
					'sanitize_callback' => $option_args['sanitize_callback'] ?? null
				]
			);
		}
	}

	/**
	 * Add admin styles
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function add_admin_styles() {
		if(self::$page_registered) {
			return;
		}

		echo '<style>';
			echo '
				.' . F4_WPI_OPTION_PAGE_NAME . '-submenu-item {
				display: flex;
				align-items: center;
				white-space: nowrap;
			}

			.' . F4_WPI_OPTION_PAGE_NAME . '-submenu-item .f4-icon {
				flex: 0 0 auto;
				width: 1em;
				height: 1em;
				margin-right: 0.4em;
				fill: currentColor;
			}
			';

			if(Helpers::is_option_page()) {
				echo '
				.form-table tr:not(:last-child) th,
				.form-table tr:not(:last-child) td {
					padding-bottom: 0;
				}

				.f4-options-sidebar {
					display: none;
				}

				.f4-options-sidebar-link {
					display: block;
					font-size: 0;
					line-height: 0;
				}

				@media screen and (min-width: 851px) {
					.wrap {
						display: flex;
					}

					.f4-options-form {
						flex: 1 1 100%;
						padding-right: 20px;
					}

					.f4-options-sidebar {
						display: block;
						padding-top: 12px;
						flex: 0 0 300px;
					}
				}
				';
			}
		echo '</style>';
	}

	/**
	 * Add settings link to plugin list
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function add_settings_link_to_plugin_list($links) {
		array_push(
			$links,
			'<a href="' . admin_url('options-general.php?page=' . F4_WPI_OPTION_PAGE_NAME) . '&tab=frontend">' . __('Settings', 'f4-improvements') . '</a>'
		);

		return $links;
	}

	/**
	 * After the options has been updated
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function after_update($location, $status) {
		if(strpos($location, 'page=' . F4_WPI_OPTION_PAGE_NAME) !== false) {
			do_action('F4/WPI/after_update');
		}

		return $location;
	}
}

?>
