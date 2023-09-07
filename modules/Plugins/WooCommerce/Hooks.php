<?php

namespace F4\WPI\Plugins\WooCommerce;

use F4\WPI\Core\Helpers as Core;
use F4\WPI\Core\Options\Helpers as Options;

/**
 * Plugins/WooCommerce hooks
 *
 * Hooks for the Plugins/WooCommerce module
 *
 * @since 1.3.0
 * @package F4\WPI\Plugins\WooCommerce
 */
class Hooks {
	/**
	 * Initialize the hooks
	 *
	 * @since 1.3.0
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
	 * @since 1.3.0
	 * @access public
	 * @static
	 */
	public static function loaded() {
		add_action('before_woocommerce_init', __NAMESPACE__ . '\\Hooks::declare_woocommerce_compatibilities');

		add_filter('F4/WPI/register_options_tabs', __NAMESPACE__ . '\\Hooks::register_options_tabs', 50);
		add_filter('F4/WPI/register_options_sections', __NAMESPACE__ . '\\Hooks::register_options_sections');
		add_filter('F4/WPI/register_options_settings', __NAMESPACE__ . '\\Hooks::register_options_settings');
		add_filter('F4/WPI/register_options_fields', __NAMESPACE__ . '\\Hooks::register_options_fields');

		add_action('F4/WPI/after_update', __NAMESPACE__ . '\\Hooks::after_update');
	}

	/**
	 * Sets the module default constants
	 *
	 * @since 1.3.0
	 * @access public
	 * @static
	 */
	public static function set_default_constants() {

	}

	/**
	 * Register admin options tab
	 *
	 * @since 1.3.0
	 * @access public
	 * @static
	 */
	public static function register_options_tabs($tabs) {
		$tabs['woocommerce'] = [
			'label' => __('WooCommerce', 'f4-improvements')
		];

		return $tabs;
	}

	/**
	 * Register admin options sections
	 *
	 * @since 1.3.0
	 * @access public
	 * @static
	 */
	public static function register_options_sections($sections) {
		$sections['wc-frontend'] = [
			'tab' => 'woocommerce',
			'title' => __('Front end', 'f4-improvements')
		];

		$sections['wc-backend'] = [
			'tab' => 'woocommerce',
			'title' => __('Back end', 'f4-improvements')
		];

		$sections['wc-system'] = [
			'tab' => 'woocommerce',
			'title' => __('System', 'f4-improvements')
		];

		return $sections;
	}

	/**
	 * Register admin options
	 *
	 * @since 1.3.0
	 * @access public
	 * @static
	 */
	public static function register_options_settings($settings) {
		// Frontend
		$settings['wc_persistent_ship_to_different_address'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		$settings['wc_remove_adjacent_links'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		$settings['wc_hide_flat_rates_if_free_shipping'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		// Backend
		$settings['wc_hide_embed_loader'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		// System
		$settings['wc_set_reply_to_email'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		return $settings;
	}

	/**
	 * Register admin options fields
	 *
	 * @since 1.3.0
	 * @access public
	 * @static
	 */
	public static function register_options_fields($fields) {
		// Frontend
		$fields['wc_persistent_ship_to_different_address'] = [
			'tab' => 'woocommerce',
			'section' => 'wc-frontend',
			'type' => 'checkbox',
			'label' => __('Save ship_to_different_address', 'f4-improvements')
		];

		$fields['wc_remove_adjacent_links'] = [
			'tab' => 'woocommerce',
			'section' => 'wc-frontend',
			'type' => 'checkbox',
			'label' => __('Remove Adjacent Links', 'f4-improvements')
		];

		$fields['wc_hide_flat_rates_if_free_shipping'] = [
			'tab' => 'woocommerce',
			'section' => 'wc-frontend',
			'type' => 'checkbox',
			'label' => __('Hide flat rates if free shipping', 'f4-improvements')
		];

		// Backend
		$fields['wc_hide_embed_loader'] = [
			'tab' => 'woocommerce',
			'section' => 'wc-backend',
			'type' => 'checkbox',
			'label' => __('Hide jumping notices loader', 'f4-improvements')
		];

		// System
		$fields['wc_set_reply_to_email'] = [
			'tab' => 'woocommerce',
			'section' => 'wc-system',
			'type' => 'checkbox',
			'label' => __('Set reply to email', 'f4-improvements')
		];

		return $fields;
	}

	/**
	 * Fires after the module is loaded
	 *
	 * @since 1.3.0
	 * @access public
	 * @static
	 */
	public static function after_loaded() {
		// Frontend
		if(Options::get('wc_persistent_ship_to_different_address')) {
			add_action('woocommerce_after_checkout_validation', __NAMESPACE__ . '\\Hooks::save_ship_to_different_address_session');
			add_action('woocommerce_ship_to_different_address_checked', __NAMESPACE__ . '\\Hooks::get_ship_to_different_address_session');
		}

		if(Options::get('wc_remove_adjacent_links')) {
			add_filter('wpseo_next_rel_link', '__return_false', 10);
			add_filter('wpseo_prev_rel_link', '__return_false', 10);
		}

		if(Options::get('wc_hide_flat_rates_if_free_shipping')) {
			add_filter('woocommerce_package_rates', __NAMESPACE__ . '\\Hooks::hide_flat_rates_if_free_shipping', 10, 2);
		}

		// Backend
		if(Options::get('wc_hide_embed_loader')) {
			add_action('admin_head', __NAMESPACE__ . '\\Hooks::hide_embed_loader');
		}

		// System
		if(Options::get('wc_set_reply_to_email')) {
			add_filter('woocommerce_email_headers', __NAMESPACE__ . '\\Hooks::set_reply_to_email_address', 99, 4);
		}
	}

	/**
	 * Declare WooCommerce compatibilities.
	 *
	 * @since 1.8.0
	 * @access public
	 * @static
	 */
	public static function declare_woocommerce_compatibilities() {
		if(class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', F4_WPI_MAIN_FILE, true);
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('product_block_editor', F4_WPI_MAIN_FILE, true);
		}
	}

	/**
	 * Save ship_to_different_address in session
	 *
	 * @since 1.3.0
	 * @access public
	 * @static
	 */
	public static function save_ship_to_different_address_session($data) {
		$ship_to_different_address = $data['ship_to_different_address'] ?? false;
		WC()->session->set('ship_to_different_address', $ship_to_different_address);
		return $data;
	}

	/**
	 * Get ship_to_different_address from session
	 *
	 * @since 1.3.0
	 * @access public
	 * @static
	 */
	public static function get_ship_to_different_address_session($value) {
		$ship_to_different_address = WC()->session->get('ship_to_different_address', null);

		if(is_null($ship_to_different_address)) {
			return $value;
		}

		return (int)$ship_to_different_address;
	}

	/**
	 * Hide flat rates if free shipping is available
	 *
	 * @since 1.3.0
	 * @access public
	 * @static
	 */
	public static function hide_flat_rates_if_free_shipping($rates, $package) {
		$has_free = false;

		// Check if has free shipping
		foreach($rates as $rate_id => $rate) {
			if('free_shipping' === $rate->method_id) {
				$has_free = true;
				break;
			}
		}

		// Remove flat rates if free shipping
		if($has_free) {
			foreach($rates as $rate_id => $rate) {
				if('flat_rate' === $rate->method_id) {
					unset($rates[$rate_id]);
				}
			}
		}

		return $rates;
	}


	/**
	 * Hide jumping embed loader on woocommerce pages
	 *
	 * @since 1.4.0
	 * @access public
	 * @static
	 */
	public static function hide_embed_loader() {
		echo '<style>
			.woocommerce-layout__primary > .woocommerce-spinner {
				display: none !important;
			}

			.is-embed-loading + #wpbody .wrap {
				padding-top: 70px !important;
			}

			@media (max-width:782px) {
				.is-embed-loading + #wpbody .wrap {
					padding-top: 21px !important;
				}
			}
		</style>';
	}

	/**
	 * Set reply to email address
	 *
	 * @since 1.3.0
	 * @access public
	 * @static
	 */
	public static function set_reply_to_email_address($header, $email_id, $order, $email = null) {
		if(is_null($email) && !in_array($email_id, ['new_order', 'cancelled_order', 'failed_order'], true)) {
			return $header;
		}

		// Explode header string into assoc array
		$headers = [];

		// Split by \r\n
		$header_splits = preg_split('/\R/', $header);

		foreach ($header_splits as $key => $value) {
			// Split by : to get a key => value array
			$header_split = explode(':', $value);

			if(!empty($header_split[1])) {
				$headers[$header_split[0]] = trim($header_split[1]);
			}
		}

		// Set reply-to to sender
		$reply_to_name = $email->get_from_name();
		$reply_to_email = $email->get_from_address();
		$headers['Reply-to'] = $reply_to_name . ' <' . $reply_to_email . '>';

		// Implode header array into header string
		$header_splits = [];

		foreach ($headers as $key => $value) {
			$header_splits[] = $key . ': ' . $value;
		}

		$header = implode("\r\n", $header_splits) . "\r\n";

		return $header;
	}

	/**
	 * After update
	 *
	 * @since 1.3.0
	 * @access public
	 * @static
	 */
	public static function after_update() {

	}
}
