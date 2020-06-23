<?php

namespace F4\WPI\Backend;

use F4\WPI\Core\Helpers as Core;
use F4\WPI\Core\Options\Helpers as Options;

/**
 * Backend hooks
 *
 * Hooks for the Backend module
 *
 * @since 1.0.0
 * @package F4\WPI\Backend
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

		add_filter('F4/WPI/register_options_tabs', __NAMESPACE__ . '\\Hooks::register_options_tabs', 15);
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
		// User
		add_filter('insert_user_meta', __NAMESPACE__ . '\\Hooks::set_default_user_infos', 10, 3);

		// Editor
		add_action('current_screen', __NAMESPACE__ . '\\Hooks::disable_editor_features');
		add_filter('use_block_editor_for_post', __NAMESPACE__ . '\\Hooks::disable_gutenberg_post_types', 99, 2);

		if(Options::get('keep_tax_checklist_hierarchy')) {
			add_filter('wp_terms_checklist_args', __NAMESPACE__ . '\\Hooks::keep_tax_checklist_hierarchy', 99);
			add_action('admin_footer', __NAMESPACE__ . '\\Hooks::add_checklist_hierarchy_script');
		}

		// Dashboard
		add_action('wp_dashboard_setup', __NAMESPACE__ . '\\Hooks::remove_dashboard_widgets');
	}

	/**
	 * Register admin options tabs
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function register_options_tabs($tabs) {
		$tabs['backend'] = [
			'label' => __('Back end', 'f4-improvements')
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
		$sections['backend-appearance'] = [
			'tab' => 'backend',
			'title' => __('Appearance', 'f4-improvements')
		];

		$sections['backend-content'] = [
			'tab' => 'backend',
			'title' => __('Content', 'f4-improvements')
		];

		$sections['backend-dashboard'] = [
			'tab' => 'backend',
			'title' => __('Dashboard', 'f4-improvements')
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
		$settings['default_user_color_scheme'] = [
			'default' => 'default'
		];

		$settings['disable_editor'] = [
			'default' => [],
			'type' => 'array'
		];

		$settings['disable_editor_media'] = [
			'default' => [],
			'type' => 'array'
		];

		$settings['disable_gutenberg'] = [
			'default' => [],
			'type' => 'array'
		];

		$settings['keep_tax_checklist_hierarchy'] = [
			'default' => 'default'
		];

		$settings['remove_dashboard_welcome'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		$settings['remove_dashboard_quickpress'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		$settings['remove_dashboard_primary'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		$settings['remove_dashboard_right_now'] = [
			'default' => '0',
			'type' => 'boolean'
		];

		$settings['remove_dashboard_activity'] = [
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
		// Get post types
		$post_types = [];
		$post_types_objects = get_post_types(array('show_ui' => true), 'objects');

		foreach($post_types_objects as $post_type_name => $post_type) {
			if(post_type_supports( $post_type_name, 'editor') && !in_array($post_type_name, ['wp_block'])) {
				$post_types[$post_type_name] = $post_type->label;
			}
		}

		// Register users fields
		$fields['default_user_color_scheme'] = [
			'tab' => 'backend',
			'section' => 'backend-appearance',
			'type' => 'select',
			'label' => __('Default Color Scheme', 'f4-improvements'),
			'options' => [
				'default' => __('Default', 'f4-improvements'),
				'light' => __('Light', 'f4-improvements'),
				'blue' =>  __('Blue', 'f4-improvements'),
				'coffee' => __('Coffee', 'f4-improvements'),
				'ectoplasm' => __('Ectoplasm', 'f4-improvements'),
				'midnight' => __('Midnight', 'f4-improvements'),
				'ocean' => __('Ocean', 'f4-improvements'),
				'sunrise' => __('Sunrise', 'f4-improvements')
			]
		];

		// Register content fields
		$fields['disable_editor'] = [
			'tab' => 'backend',
			'section' => 'backend-content',
			'type' => 'checkboxes',
			'label' => __('Disable WYSIWYG', 'f4-improvements'),
			'options' => $post_types
		];

		$fields['disable_editor_media'] = [
			'tab' => 'backend',
			'section' => 'backend-content',
			'type' => 'checkboxes',
			'label' => __('Disable WYSIWYG Media', 'f4-improvements'),
			'options' => $post_types
		];

		$fields['disable_gutenberg'] = [
			'tab' => 'backend',
			'section' => 'backend-content',
			'type' => 'checkboxes',
			'label' => __('Disable Gutenberg', 'f4-improvements'),
			'options' => $post_types
		];

		$fields['keep_tax_checklist_hierarchy'] = [
			'tab' => 'backend',
			'section' => 'backend-content',
			'type' => 'checkbox',
			'label' => __('Keep Taxonomy Checklist Hierarchy', 'f4-improvements')
		];

		// Register dashboard fields
		$fields['remove_dashboard_welcome'] = [
			'tab' => 'backend',
			'section' => 'backend-dashboard',
			'type' => 'checkbox',
			'label' => __('Remove "Welcome to WP!"', 'f4-improvements')
		];

		$fields['remove_dashboard_quickpress'] = [
			'tab' => 'backend',
			'section' => 'backend-dashboard',
			'type' => 'checkbox',
			'label' => __('Remove "Quick Draft"', 'f4-improvements')
		];

		$fields['remove_dashboard_primary'] = [
			'tab' => 'backend',
			'section' => 'backend-dashboard',
			'type' => 'checkbox',
			'label' => __('Remove "News and Events"', 'f4-improvements')
		];

		$fields['remove_dashboard_right_now'] = [
			'tab' => 'backend',
			'section' => 'backend-dashboard',
			'type' => 'checkbox',
			'label' => __('Remove "At a Glance"', 'f4-improvements')
		];

		$fields['remove_dashboard_activity'] = [
			'tab' => 'backend',
			'section' => 'backend-dashboard',
			'type' => 'checkbox',
			'label' => __('Remove "Activity"', 'f4-improvements')
		];

		return $fields;
	}

	/**
	 * Disable editor features
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function disable_editor_features($current_screen) {
		if($current_screen->base === 'post') {
			// Editor
			if(in_array($current_screen->post_type, (array)Options::get('disable_editor'))) {
				remove_post_type_support(get_post_type($current_screen->post_type), 'editor');
			}

			// Media button
			if(in_array($current_screen->post_type, (array)Options::get('disable_editor_media'))) {
				remove_action('media_buttons', 'media_buttons');
			}
		}
	}

	/**
	 * Disable gutenberg for post types
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function disable_gutenberg_post_types($use, $post) {
		if($use) {
			$use = !in_array($post->post_type, (array)Options::get('disable_gutenberg'));
		}

		return $use;
	}

	/**
	 * Keep the hierarchy for taxonomy checklists
	 *
	 * @since 1.1.0
	 * @access public
	 * @static
	 */
	public static function keep_tax_checklist_hierarchy($args) {
		$args['checked_ontop'] = false;
		return $args;
	}

	/**
	 * Add script to jump to selected term in taxonomy checklist
	 *
	 * @since 1.1.0
	 * @access public
	 * @static
	 */
	public static function add_checklist_hierarchy_script() {
		echo '
			<script>
				jQuery(function() {
					jQuery("[id$=\'-all\'] > ul.categorychecklist").each(function() {
						var $list = jQuery(this);
						var $checked = $list.find(":checkbox:checked").first();

						if($checked.length) {
							var firstElementTop = $list.find(":checkbox").position().top;
							var firstCheckedTop = $checked.position().top;
							$list.closest(".tabs-panel").scrollTop(firstCheckedTop - firstElementTop + 5);
						}
					});
				});
			</script>
		';
	}

	/**
	 * Set default user infos
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function set_default_user_infos($meta, $user, $update) {
		if($update) {
			return $meta;
		}

		if(Options::get('default_user_color_scheme') !== 'default') {
			$meta['admin_color'] = Options::get('default_user_color_scheme');
		}

		return $meta;
	}

	/**
	 * Remove dashboard widgets
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function remove_dashboard_widgets() {
		global $wp_meta_boxes;

		if(Options::get('remove_dashboard_welcome')) {
			remove_action('welcome_panel', 'wp_welcome_panel');
		}

		if(Options::get('remove_dashboard_quickpress')) {
			unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
		}

		if(Options::get('remove_dashboard_primary')) {
			unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
		}

		if(Options::get('remove_dashboard_right_now')) {
			unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
		}

		if(Options::get('remove_dashboard_activity')) {
			unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
		}
	}
}

?>
