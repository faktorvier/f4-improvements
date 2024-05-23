<?php

namespace F4\WPI\Plugins\WPRocket;

use F4\WPI\Core\Helpers as Core;
use F4\WPI\Core\Options\Helpers as Options;

/**
 * Plugins/WPRocket hooks
 *
 * Hooks for the Plugins/WPRocket module
 *
 * @since 1.2.0
 * @package F4\WPI\Plugins\WPRocket
 */
class Hooks {
	/**
	 * Initialize the hooks
	 *
	 * @since 1.2.0
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
	 * @since 1.2.0
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
	 * @since 1.2.0
	 * @access public
	 * @static
	 */
	public static function set_default_constants() {

	}

	/**
	 * Register admin options tab
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 */
	public static function register_options_tabs($tabs) {
		$tabs['wprocket'] = [
			'label' => __('WP Rocket', 'f4-improvements')
		];

		return $tabs;
	}

	/**
	 * Register admin options sections
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 */
	public static function register_options_sections($sections) {
		$sections['wprocket-general'] = [
			'tab' => 'wprocket',
			'title' => __('General', 'f4-improvements')
		];

		if(F4_WPI_PLUGIN_ACTIVE_WOOCOMMERCE) {
			$sections['wprocket-woocommerce'] = [
				'tab' => 'wprocket',
				'title' => __('WooCommerce', 'f4-improvements')
			];
		}

		return $sections;
	}

	/**
	 * Register admin options
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 */
	public static function register_options_settings($settings) {
		// General
		$settings['wprocket_show_toggle_cache_action'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		$settings['wprocket_enable_common_loggedin_cache'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		if(version_compare(\WP_ROCKET_VERSION, '3.16', '>=')) {
			$settings['wprocket_disable_separate_mobile_cache'] = [
				'default' => '0',
				'type' => 'boolean'
			];
		}

		$settings['wprocket_remove_footprint'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		$settings['wprocket_disable_htaccess_newline_removal'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		$settings['wprocket_ignore_query_strings'] = [
			'default' => ''
		];

		// WooCommerce
		if(F4_WPI_PLUGIN_ACTIVE_WOOCOMMERCE) {
			$settings['wprocket_wc_clean_post_cache_after_stock_change'] = [
				'default' => '0',
				'type' => 'boolean'
			];

			$settings['wprocket_wc_deactivate_cart_fragments_cache'] = [
				'default' => '0',
				'type' => 'boolean'
			];
		}

		// WooCommerce
		// https://github.com/wp-media/wp-rocket-helpers/tree/master/compatibility/wp-rocket-compat-wc-order-clean-cache
		// https://github.com/wp-media/wp-rocket-helpers/tree/master/compatibility/wp-rocket-compat-wc-cart-fragments

		// https://github.com/wp-media/wp-rocket-helpers/tree/master/_experimental/wp-rocket-compat-wc-optimize
		// https://github.com/wp-media/wp-rocket-helpers/tree/master/compatibility/wp-rocket-compat-wc-product-images
		// https://github.com/wp-media/wp-rocket-helpers/tree/master/compatibility/wp-rocket-compat-wc-recently-viewed-widget

		return $settings;
	}


	/**
	 * Register admin options fields
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 */
	public static function register_options_fields($fields) {
		// General
		$fields['wprocket_show_toggle_cache_action'] = [
			'tab' => 'wprocket',
			'section' => 'wprocket-general',
			'type' => 'checkbox',
			'label' => __('Show toggle cache action', 'f4-improvements')
		];

		$fields['wprocket_enable_common_loggedin_cache'] = [
			'tab' => 'wprocket',
			'section' => 'wprocket-general',
			'type' => 'checkbox',
			'label' => __('Common Cache for all Users', 'f4-improvements')
		];

		if(version_compare(\WP_ROCKET_VERSION, '3.16', '>=')) {
			$fields['wprocket_disable_separate_mobile_cache'] = [
				'tab' => 'wprocket',
				'section' => 'wprocket-general',
				'type' => 'checkbox',
				'label' => __('Disable mobile-specific cache', 'f4-improvements')
			];
		}

		$fields['wprocket_remove_footprint'] = [
			'tab' => 'wprocket',
			'section' => 'wprocket-general',
			'type' => 'checkbox',
			'label' => __('Remove Footprint Comment', 'f4-improvements')
		];

		$fields['wprocket_disable_htaccess_newline_removal'] = [
			'tab' => 'wprocket',
			'section' => 'wprocket-general',
			'type' => 'checkbox',
			'label' => __('Leave empty Lines in .htaccess', 'f4-improvements')
		];

		$fields['wprocket_ignore_query_strings'] = [
			'tab' => 'wprocket',
			'section' => 'wprocket-general',
			'type' => 'text',
			'placeholder' => 'comma separated',
			'label' => __('Ignore Query Strings', 'f4-improvements')
		];

		// WooCommerce
		if(F4_WPI_PLUGIN_ACTIVE_WOOCOMMERCE) {
			$fields['wprocket_wc_clean_post_cache_after_stock_change'] = [
				'tab' => 'wprocket',
				'section' => 'wprocket-woocommerce',
				'type' => 'checkbox',
				'label' => __('Clean post cache after stock change', 'f4-improvements')
			];

			$fields['wprocket_wc_deactivate_cart_fragments_cache'] = [
				'tab' => 'wprocket',
				'section' => 'wprocket-woocommerce',
				'type' => 'checkbox',
				'label' => __('Deactivate cart fragments cache', 'f4-improvements')
			];
		}

		return $fields;
	}

	/**
	 * Fires after the module is loaded
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 */
	public static function after_loaded() {
		// General
		if(Options::get('wprocket_show_toggle_cache_action')) {
			add_filter('wp_before_admin_bar_render', __NAMESPACE__ . '\\Hooks::change_admin_bar_menu');
			add_filter('admin_action_f4wpi_toggle_wprocket_cache', __NAMESPACE__ . '\\Hooks::toggle_cache_admin_action');
		}

		if(!get_option(F4_WPI_OPTION_PREFIX . 'wprocket_enable_cache', 1)) {
			add_filter('do_rocket_generate_caching_files', '__return_false', 99);
			add_filter('rocket_disable_htaccess', '__return_true', 99);
			add_filter('rocket_cache_wc_empty_cart', '__return_false', 99);
			add_filter('rocket_css_url', __NAMESPACE__ . '\\Hooks::disable_assets_minify', 10, 2);
			add_filter('rocket_js_url', __NAMESPACE__ . '\\Hooks::disable_assets_minify', 10, 2);
			add_filter('do_rocket_critical_css_generation', '__return_false');
			remove_action('admin_notices', 'rocket_warning_plugin_modification');
		}

		if(Options::get('wprocket_enable_common_loggedin_cache')) {
			add_filter('rocket_common_cache_logged_users', '__return_true');
		}

		if(Options::get('wprocket_disable_separate_mobile_cache') && version_compare(\WP_ROCKET_VERSION, '3.16', '>=')) {
			add_filter('rocket_above_the_fold_optimization', __NAMESPACE__ . '\\Hooks::disable_above_the_fold_optimization');
		}

		if(!defined('WP_ROCKET_WHITE_LABEL_FOOTPRINT') && Options::get('wprocket_remove_footprint')) {
			define('WP_ROCKET_WHITE_LABEL_FOOTPRINT', false);
		}

		if(Options::get('wprocket_disable_htaccess_newline_removal')) {
			add_filter('rocket_remove_empty_lines', '__return_false', 99);
		}

		add_filter('rocket_cache_ignored_parameters', __NAMESPACE__ . '\\Hooks::rocket_cache_ignored_parameters');

		// WooCommerce
		if(F4_WPI_PLUGIN_ACTIVE_WOOCOMMERCE) {
			if(Options::get('wprocket_wc_clean_post_cache_after_stock_change')) {
				add_action('woocommerce_reduce_order_stock', __NAMESPACE__ . '\\Hooks::wc_clean_post_cache_after_stock_change');
				add_action('woocommerce_restore_order_stock', __NAMESPACE__ . '\\Hooks::wc_clean_post_cache_after_stock_change');
			}

			if(Options::get('wprocket_wc_deactivate_cart_fragments_cache')) {
				add_filter('rocket_cache_wc_empty_cart', '__return_false');
			}
		}
	}

	/**
	 * Disables the css and js minify if option is set
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 */
	public static function disable_assets_minify($minify_url, $original_url) {
		return $original_url;
	}

	/**
	 * Disable above the fold optimizations if mobile-specific cache is diabled.
	 *
	 * @since 1.9.0
	 * @access public
	 * @static
	 */
	public static function disable_above_the_fold_optimization($enabled) {
		$options = get_option('wp_rocket_settings', []);
		return $enabled && isset($options['do_caching_mobile_files'], $options['cache_mobile']) && $options['do_caching_mobile_files'] == 1 && $options['cache_mobile'] == 1;
	}

	/**
	 * Add ignored query string params
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 */
	public static function rocket_cache_ignored_parameters($params) {
		$new_params_raw = Options::get('wprocket_ignore_query_strings');

		if(!empty($new_params_raw)) {
			$new_params = explode(',', $new_params_raw);

			foreach($new_params as $new_param) {
				$params[trim($new_param)] = 1;
			}
		}

		return $params;
	}

	/**
	 * Change the admin bar menu (add status bubble and menu item for page cache toggle)
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 */
	public static function change_admin_bar_menu() {
		global $wp_admin_bar;

		// Get wp rocket node
		$wprocket_node = $wp_admin_bar->get_node('wp-rocket');
		$all_nodes = $wp_admin_bar->get_nodes();

		// Abort if node not exists
		if(!$wprocket_node) {
			return;
		}

		$cache_enabled = get_option(F4_WPI_OPTION_PREFIX . 'wprocket_enable_cache', 1);

		// Build action url
		$href = admin_url('admin.php?action=f4wpi_toggle_wprocket_cache');
		$href = add_query_arg('toggle', $cache_enabled ? 'disable' : 'enable', $href);
		$href = add_query_arg('referer', urlencode($_SERVER['REQUEST_URI']), $href);

		// Add node to admin bar
		$wp_admin_bar->add_node([
			'id' => 'toggle-cache',
			'title' => ($cache_enabled ? __('Disable Cache', '4-improvements-for-wprocket') : __('Enable Cache', '4-improvements-for-wprocket')),
			'parent' => 'wp-rocket',
			'href' => $href
		]);

		foreach($all_nodes as $node_name => $node) {
			$wp_admin_bar->remove_node($node_name);
			$wp_admin_bar->add_node($node);
		}

		// Add status bubble to wp rocket menu
		$color = $cache_enabled ? '#8BC34A' : '#FF5722';
		$wp_admin_bar->remove_node('wp-rocket');
		$wprocket_node->title = $wprocket_node->title . '<span style="margin-left:5px;width:10px;height:10px;display:inline-block;border-radius:100%;background-color:' . $color . ';"></span>';
		$wp_admin_bar->add_menu($wprocket_node);
	}

	/**
	 * Toggles our option to enable or disable caching functions
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 */
	public static function toggle_cache_admin_action() {
		if(isset($_GET['toggle'])) {
			$enable = $_GET['toggle'] === 'enable';

			// Toggle caching
			update_option(F4_WPI_OPTION_PREFIX . 'wprocket_enable_cache', $enable ? 1 : 0);

			// Flush htaccess cache
			if(!$enable) {
				add_filter('rocket_disable_htaccess', '__return_true', 99);
				add_filter('do_rocket_generate_caching_files', '__return_false', 99);
				add_filter('rocket_cache_wc_empty_cart', '__return_false', 99);
				flush_rocket_htaccess(true);
			} else {
				add_filter('rocket_disable_htaccess', '__return_false', 99);
				add_filter('do_rocket_generate_caching_files', '__return_true', 99);
				flush_rocket_htaccess(false);
			}

			// Flush all caches
			Helpers::clear_cache();

			// Redirect back
			if(isset($_GET['referer'])) {
				wp_redirect($_GET['referer']);
				exit();
			}
		}
	}

	/**
	 * Clean post cache after order
	 *
	 * @since 1.3.0
	 * @access public
	 * @static
	 */
	public static function wc_clean_post_cache_after_stock_change($order) {
		$items = $order->get_items();

		foreach($items as $item) {
			$product_id = (int)$item['product_id'];
			$product_ids = [$item['product_id']];

			if(function_exists('PLL')) {
				$product_ids = PLL()->model->post->get_translations($product_id);
			} elseif(function_exists('wpml_get_content_translations')) {
				$product_ids = wpml_get_content_translations('post_product', $product_id);
			}

			foreach($product_ids as $translated_product_id) {
				rocket_clean_post((int)$translated_product_id);
			}

			// @todo: clean product overview archive pages
			// @todo: preload page
		}
	}

	/**
	 * Plugin activation
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 */
	public static function activate_plugin() {
		if(F4_WPI_PLUGIN_ACTIVE_WPROCKET) {
			if(Options::get('wprocket_enable_common_loggedin_cache')) {
				add_filter('rocket_common_cache_logged_users', '__return_true');
			} else {
				add_filter('rocket_common_cache_logged_users', '__return_false');
			}

			if(Options::get('wprocket_disable_separate_mobile_cache')) {
				Helpers::toggle_mobile_specific_cache(false);
			} else {
				Helpers::toggle_mobile_specific_cache(true);
			}

			if(Options::get('wprocket_wc_deactivate_cart_fragments_cache')) {
				add_filter('rocket_cache_wc_empty_cart', '__return_false');
			} else {
				add_filter('rocket_cache_wc_empty_cart', '__return_true');
			}

			Helpers::clear_cache();

		}
	}

	/**
	 * Plugin deactivation
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 */
	public static function deactivate_plugin() {
		if(F4_WPI_PLUGIN_ACTIVE_WPROCKET) {
			add_filter('rocket_common_cache_logged_users', '__return_false');
			add_filter('rocket_cache_wc_empty_cart', '__return_true');

			Helpers::toggle_mobile_specific_cache(true);
			Helpers::clear_cache();
		}
	}

	/**
	 * After update
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 */
	public static function after_update() {
		if(Options::get('wprocket_enable_common_loggedin_cache')) {
			add_filter('rocket_common_cache_logged_users', '__return_true');
		} else {
			add_filter('rocket_common_cache_logged_users', '__return_false');
		}

		if(Options::get('wprocket_disable_separate_mobile_cache')) {
			Helpers::toggle_mobile_specific_cache(false);
		} else {
			Helpers::toggle_mobile_specific_cache(true);
		}

		if(Options::get('wprocket_wc_deactivate_cart_fragments_cache')) {
			add_filter('rocket_cache_wc_empty_cart', '__return_false');
		} else {
			add_filter('rocket_cache_wc_empty_cart', '__return_true');
		}

		Helpers::clear_cache();
	}
}
