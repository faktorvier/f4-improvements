<?php

namespace F4\WPI\Frontend;

use F4\WPI\Core\Helpers as Core;
use F4\WPI\Core\Options\Helpers as Options;

/**
 * Frontend hooks
 *
 * Hooks for the Frontend module
 *
 * @since 1.0.0
 * @package F4\WPI\Frontend
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
		add_action('F4/WPI/set_constants', __NAMESPACE__ . '\\Hooks::set_default_constants', 99);
		add_action('F4/WPI/loaded', __NAMESPACE__ . '\\Hooks::loaded');

		add_filter('F4/WPI/register_options_tabs', __NAMESPACE__ . '\\Hooks::register_options_tabs', 10);
		add_filter('F4/WPI/register_options_sections', __NAMESPACE__ . '\\Hooks::register_options_sections');
		add_filter('F4/WPI/register_options_settings', __NAMESPACE__ . '\\Hooks::register_options_settings');
		add_filter('F4/WPI/register_options_fields', __NAMESPACE__ . '\\Hooks::register_options_fields');
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
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function loaded() {
		if(Options::get('remove_rsd_link')) {
			remove_action('wp_head', 'rsd_link');
		}

		if(Options::get('remove_rest_output')) {
			remove_action('wp_head', 'rest_output_link_wp_head', 10);
		}

		if(Options::get('remove_oemebed_discovery')) {
			remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
		}

		if(Options::get('remove_wlwmanifest_link')) {
			remove_action('wp_head', 'wlwmanifest_link');
		}

		if(Options::get('remove_shortlinks')) {
			remove_action('wp_head', 'wp_shortlink_wp_head');
		}

		if(Options::get('remove_adjacent_links')) {
			remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
		}

		if(Options::get('remove_feed_links')) {
			remove_action('wp_head', 'feed_links', 2);
			remove_action('wp_head', 'feed_links_extra', 3);
		}

		if(Options::get('remove_wp_generator')) {
			remove_action('wp_head', 'wp_generator');
		}

		if(Options::get('remove_remove_emojis')) {
			remove_action('wp_head', 'print_emoji_detection_script', 7);
			remove_action('wp_print_styles', 'print_emoji_styles');
		}

		if(Options::get('remove_oemebed_assets')) {
			add_action('wp_footer', __NAMESPACE__ . '\\Hooks::remove_oembed_assets');
		}

		if(Options::get('remove_gutenberg_assets')) {
			remove_action('wp_enqueue_scripts', 'wp_common_block_scripts_and_styles');
		}

		if(Options::get('hide_author_page')) {
			add_action('template_redirect', __NAMESPACE__ . '\\Hooks::hide_author_page');
		}

		if(Options::get('disable_admin_toolbar')) {
			add_filter('show_admin_bar', '__return_false', 99);
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
		$tabs['frontend'] = [
			'label' => __('Front end', 'f4-improvements')
		];

		return $tabs;
	}

	/**
	 * Register admin options sections
	 *
	 * @since 1.1.0
	 * @access public
	 * @static
	 */
	public static function register_options_sections($sections) {
		$sections['frontend-meta'] = [
			'tab' => 'frontend',
			'title' => __('Meta Tags', 'f4-improvements')
		];

		$sections['frontend-scripts-styles'] = [
			'tab' => 'frontend',
			'title' => __('Scripts and Stylesheets', 'f4-improvements')
		];

		$sections['frontend-pages'] = [
			'tab' => 'frontend',
			'title' => __('Pages', 'f4-improvements')
		];

		return $sections;
	}

	/**
	 * Register admin options
	 *
	 * @since 1.1.0
	 * @access public
	 * @static
	 */
	public static function register_options_settings($settings) {
		$settings['remove_rsd_link'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		$settings['remove_rest_output'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		$settings['remove_oemebed_discovery'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		$settings['remove_wlwmanifest_link'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		$settings['remove_shortlinks'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		$settings['remove_adjacent_links'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		$settings['remove_feed_links'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		$settings['remove_wp_generator'] = [
			'default' => '1',
			'type' => 'boolean'
		];

		$settings['remove_remove_emojis'] = [
			'default' => '1',
			'type' => 'boolean'
		];

		$settings['remove_oemebed_assets'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		$settings['remove_gutenberg_assets'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		$settings['hide_author_page'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		$settings['disable_admin_toolbar'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		return $settings;
	}

	/**
	 * Register admin options fields
	 *
	 * @since 1.1.0
	 * @access public
	 * @static
	 */
	public static function register_options_fields($fields) {
		// Register meta fields
		$fields['remove_rsd_link'] = [
			'tab' => 'frontend',
			'section' => 'frontend-meta',
			'type' => 'checkbox',
			'label' => __('Remove RSD Link', 'f4-improvements')
		];

		$fields['remove_rest_output'] = [
			'tab' => 'frontend',
			'section' => 'frontend-meta',
			'type' => 'checkbox',
			'label' => __('Remove REST Output', 'f4-improvements')
		];

		$fields['remove_oemebed_discovery'] = [
			'tab' => 'frontend',
			'section' => 'frontend-meta',
			'type' => 'checkbox',
			'label' => __('Remove oEmbed Discovery', 'f4-improvements')
		];

		$fields['remove_wlwmanifest_link'] = [
			'tab' => 'frontend',
			'section' => 'frontend-meta',
			'type' => 'checkbox',
			'label' => __('Remove wlwmanifest Link', 'f4-improvements')
		];

		$fields['remove_shortlinks'] = [
			'tab' => 'frontend',
			'section' => 'frontend-meta',
			'type' => 'checkbox',
			'label' => __('Remove Shortlinks', 'f4-improvements')
		];

		$fields['remove_adjacent_links'] = [
			'tab' => 'frontend',
			'section' => 'frontend-meta',
			'type' => 'checkbox',
			'label' => __('Remove Adjacent Links', 'f4-improvements')
		];

		$fields['remove_feed_links'] = [
			'tab' => 'frontend',
			'section' => 'frontend-meta',
			'type' => 'checkbox',
			'label' => __('Remove Feed Links', 'f4-improvements')
		];

		$fields['remove_wp_generator'] = [
			'tab' => 'frontend',
			'section' => 'frontend-meta',
			'type' => 'checkbox',
			'label' => __('Remove Generator Tag', 'f4-improvements')
		];

		// Register scripts and styles fields
		$fields['remove_remove_emojis'] = [
			'tab' => 'frontend',
			'section' => 'frontend-scripts-styles',
			'type' => 'checkbox',
			'label' => __('Remove Emojis', 'f4-improvements')
		];

		$fields['remove_oemebed_assets'] = [
			'tab' => 'frontend',
			'section' => 'frontend-scripts-styles',
			'type' => 'checkbox',
			'label' => __('Remove oEmbed Assets', 'f4-improvements')
		];

		$fields['remove_gutenberg_assets'] = [
			'tab' => 'frontend',
			'section' => 'frontend-scripts-styles',
			'type' => 'checkbox',
			'label' => __('Remove Gutenberg Assets', 'f4-improvements')
		];

		// Register pages fields
		$fields['hide_author_page'] = [
			'tab' => 'frontend',
			'section' => 'frontend-pages',
			'type' => 'checkbox',
			'label' => __('Hide Author Pages', 'f4-improvements')
		];

		$fields['disable_admin_toolbar'] = [
			'tab' => 'frontend',
			'section' => 'frontend-pages',
			'type' => 'checkbox',
			'label' => __('Hide Admin Toolbar', 'f4-improvements')
		];

		return $fields;
	}

	/**
	 * Removes the wp embed scripts
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function remove_oembed_assets() {
		wp_dequeue_script('wp-embed');
	}

	/**
	 * Hide author page
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function hide_author_page() {
		if(is_author()) {
			wp_redirect(home_url()); exit;
		}
	}
}

?>
