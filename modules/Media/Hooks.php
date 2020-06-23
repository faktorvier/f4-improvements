<?php

namespace F4\WPI\Media;

use F4\WPI\Core\Helpers as Core;
use F4\WPI\Core\Options\Helpers as Options;

/**
 * Media hooks
 *
 * Hooks for the Media module
 *
 * @since 1.0.0
 * @package F4\WPI\Media
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

		add_filter('F4/WPI/register_options_tabs', __NAMESPACE__ . '\\Hooks::register_options_tabs', 20);
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
		if(!empty(Options::get('jpeg_quality'))) {
			add_filter('jpeg_quality', __NAMESPACE__ . '\\Hooks::set_jpeg_quality');
		}

		if(Options::get('enable_svg_support')) {
			add_filter('upload_mimes', __NAMESPACE__ . '\\Hooks::allow_svg_upload');
			add_filter('wp_get_attachment_image_attributes', __NAMESPACE__ . '\\Hooks::fix_svg_media_list', 10, 3);
			add_action('wp_enqueue_media', __NAMESPACE__ . '\\Hooks::fix_svg_media_grid', 1);
		}

		if(Options::get('allow_image_upscaling')) {
			add_filter('image_resize_dimensions', __NAMESPACE__ . '\\Hooks::enable_image_upscaling', 10, 6);
		}

		if(Options::get('normalize_upload_filename')) {
			add_filter('sanitize_file_name', __NAMESPACE__ . '\\Hooks::clean_upload_filename');
		}

		if(Options::get('add_alt_attribute_to_attachment')) {
			add_action('add_attachment', __NAMESPACE__ . '\\Hooks::add_alt_attribute_to_attachment');
		}

		// if(Options::get('normalize_upload_title')) {
		// 	add_action('add_attachment', __NAMESPACE__ . '\\Hooks::clean_upload_attachment_title');
		// }

		if(!empty(Options::get('remove_image_sizes'))) {
			add_action('init', __NAMESPACE__ . '\\Hooks::remove_image_sizes');
			add_filter('intermediate_image_sizes', __NAMESPACE__ . '\\Hooks::remove_image_size_names');
			add_filter('intermediate_image_sizes_advanced', __NAMESPACE__ . '\\Hooks::remove_image_size_names');
			add_filter('image_size_names_choose', __NAMESPACE__ . '\\Hooks::remove_image_size_names');
		}
	}

	/**
	 * Register admin options tab
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function register_options_tabs($tabs) {
		$tabs['media'] = [
			'label' => __('Media', 'f4-improvements')
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
		$sections['media-library'] = [
			'tab' => 'media',
			'title' => __('Media Library', 'f4-improvements')
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
		$settings['jpeg_quality'] = [
			'default' => ''
		];

		$settings['enable_svg_support'] = [
			'default' => '1',
			'type' => 'boolean'
		];

		$settings['allow_image_upscaling'] = [
			'default' => '1',
			'type' => 'boolean'
		];

		$settings['normalize_upload_filename'] = [
			'default' => '1',
			'type' => 'boolean'
		];

		$settings['add_alt_attribute_to_attachment'] = [
			'default' => '1',
			'type' => 'boolean'
		];

		// $settings['normalize_upload_title'] = [
		// 	'default' => '1',
		// 	'type' => 'boolean'
		// ];

		$settings['remove_image_sizes'] = [
			'default' => [],
			'type' => 'array'
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
		// Register library fields
		$fields['jpeg_quality'] = [
			'tab' => 'media',
			'section' => 'media-library',
			'type' => 'text',
			'label' => __('Change JPEG Quality', 'f4-improvements'),
			'placeholder' => '90'
		];

		$fields['enable_svg_support'] = [
			'tab' => 'media',
			'section' => 'media-library',
			'type' => 'checkbox',
			'label' => __('Enable SVG Support', 'f4-improvements')
		];

		$fields['allow_image_upscaling'] = [
			'tab' => 'media',
			'section' => 'media-library',
			'type' => 'checkbox',
			'label' => __('Allow image upscaling', 'f4-improvements')
		];

		$fields['normalize_upload_filename'] = [
			'tab' => 'media',
			'section' => 'media-library',
			'type' => 'checkbox',
			'label' => __('Normalize Upload Filename', 'f4-improvements')
		];

		$fields['add_alt_attribute_to_attachment'] = [
			'tab' => 'media',
			'section' => 'media-library',
			'type' => 'checkbox',
			'label' => __('Use title as alt attribute on upload', 'f4-improvements')
		];

		// $fields['normalize_upload_title'] = [
		// 	'tab' => 'media',
		// 	'section' => 'media-library',
		// 	'type' => 'checkbox',
		// 	'label' => __('Normalize Upload Title', 'f4-improvements')
		// ];

		$fields['remove_image_sizes'] = [
			'tab' => 'media',
			'section' => 'media-library',
			'type' => 'checkboxes',
			'label' => __('Remove default image sizes', 'f4-improvements'),
			'options' => [
				'medium' => 'Medium',
				'medium_large' => 'Medium Large',
				'large' => 'Large',
				'1536x1536' => '1536x1536',
				'2048x2048' => '2048x2048'
			]
		];

		return $fields;
	}

	/**
	 * Change the jpeg quality
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @param integer quality The original image quality
	 * @return integer The new image quality
	 */
	public static function set_jpeg_quality($quality) {
		return (int)Options::get('jpeg_quality');
	}

	/**
	 * Allow svg upload
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @param array $mimes An array with all the allowed mime types
	 * @return array The modified array with all the allowed mime types
	 */
	public static function allow_svg_upload($mimes) {
		$mimes['svg'] = 'image/svg+xml';

		return $mimes;
	}

	/**
	 * Fix svg preview in the media list
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @param array $attr All the html attributes for the list entry
	 * @param object $attachment The attachment object
	 * @param array $size Size array
	 * @return array The modified html attributes for the list entry
	 */
	public static function fix_svg_media_list($attr, $attachment, $size) {
		if($attachment->post_mime_type == 'image/svg+xml') {
			$attr['style'] = 'width:100%;';
		}

		return $attr;
	}

	/**
	 * Fix svg preview in the media grid
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function fix_svg_media_grid() {
		if(has_action('admin_footer', 'wp_print_media_templates')) {
			remove_action('admin_footer', 'wp_print_media_templates');
			add_action('admin_footer', __NAMESPACE__ . '\\Hooks::fix_wp_print_media_templates');
		}

		if(has_action('wp_footer', 'wp_print_media_templates')) {
			remove_action('wp_footer', 'wp_print_media_templates');
			add_action('wp_footer', __NAMESPACE__ . '\\Hooks::fix_wp_print_media_templates');
		}

		if(has_action('customize_controls_print_footer_scripts', 'wp_print_media_templates')) {
			remove_action('customize_controls_print_footer_scripts', 'wp_print_media_templates');
			add_action('customize_controls_print_footer_scripts', __NAMESPACE__ . '\\Hooks::fix_wp_print_media_templates');
		}
	}

	/**
	 * Fix print media templates
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function fix_wp_print_media_templates() {
		ob_start();
		wp_print_media_templates();
		$content = ob_get_clean();

		$content = str_replace(
			'<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',
			'<# } else if ( \'svg+xml\' === data.subtype ) { #>
				<img class="details-image" src="{{ data.url }}" draggable="false" />
			<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',
			$content
		);

		$content = str_replace(
			'<# } else if ( \'image\' === data.type && data.sizes ) { #>',
			'<# } else if ( \'svg+xml\' === data.subtype ) { #>
				<div class="centered">
					<img src="{{ data.url }}" class="thumbnail" draggable="false" />
				</div>
			<# } else if ( \'image\' === data.type && data.sizes ) { #>',
			$content
		);

		echo $content;
	}

	/**
	 * Allow image upscaling
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function enable_image_upscaling($default, $orig_w, $orig_h, $new_w, $new_h, $crop) {
		if(!$crop) {
			return null;
		}

		$aspect_ratio = $orig_w / $orig_h;
		$size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

		$crop_w = round($new_w / $size_ratio);
		$crop_h = round($new_h / $size_ratio);

		$s_x = floor(($orig_w - $crop_w) / 2);
		$s_y = floor(($orig_h - $crop_h) / 2);

		return array(0, 0, (int)$s_x, (int)$s_y, (int)$new_w, (int)$new_h, (int)$crop_w, (int)$crop_h);
	}

	/**
	 * Clean the uploaded attachment filename
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @param string $filename The original attachment filename
	 * @return string The clean attachment filename
	 */
	public static function clean_upload_filename($filename) {
		// @fix: remove_accents only replaces this chars when locale === de_CH
		$chars = [];
		$chars['Ä'] = 'Ae';
		$chars['ä'] = 'ae';
		$chars['Ö'] = 'Oe';
		$chars['ö'] = 'oe';
		$chars['Ü'] = 'Ue';
		$chars['ü'] = 'ue';
		$chars['ß'] = 'ss';
        $chars['Æ'] = 'Ae';
		$chars['æ'] = 'ae';
		$chars['Ø'] = 'Oe';
		$chars['ø'] = 'oe';
		$chars['Å'] = 'Aa';
		$chars['å'] = 'aa';
		$filename = strtr($filename, $chars);

		$sanitized_filename = remove_accents($filename); // Convert to ASCII

		// Standard replacements
		$invalid = array(
			' '   => '-',
			'%20' => '-',
			'_'   => '-',
		);
		$sanitized_filename = str_replace(array_keys($invalid), array_values($invalid), $sanitized_filename);

		$sanitized_filename = preg_replace('/[^A-Za-z0-9-\. ]/', '', $sanitized_filename); // Remove all non-alphanumeric except .
		$sanitized_filename = preg_replace('/\.(?=.*\.)/', '', $sanitized_filename); // Remove all but last .
		$sanitized_filename = preg_replace('/-+/', '-', $sanitized_filename); // Replace any more than one - in a row
		$sanitized_filename = str_replace('-.', '.', $sanitized_filename); // Remove last - if at the end
		$sanitized_filename = strtolower($sanitized_filename); // Lowercase

		return $sanitized_filename;
	}

	/**
	 * Clean the uploaded attachment title
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @param integer $attachment_id The attachment id
	 */
	// public static function clean_upload_attachment_title($attachment_id) {
	// 	$filename = pathinfo(get_attached_file($attachment_id), PATHINFO_FILENAME);

	// 	if(function_exists('normalizer_normalize')) {
	// 		wp_update_post(array(
	// 			'ID' => $attachment_id,
	// 			'post_title' => normalizer_normalize($filename),
	// 			'post_name' => sanitize_title(normalizer_normalize($filename))
	// 		));
	// 	} else {
	// 		wp_update_post(array(
	// 			'ID' => $attachment_id,
	// 			'post_title' => $filename,
	// 			'post_name' => sanitize_title($filename)
	// 		));
	// 	}
	// }

	/**
	 * Add alt attribute to attachment on upload
	 *
	 * @since 1.1.0
	 * @access public
	 * @static
	 * @param integer $attachment_id The attachment id
	 */
	public static function add_alt_attribute_to_attachment($attachment_id) {
		update_post_meta($attachment_id, '_wp_attachment_image_alt', get_the_title($attachment_id));
	}

	/**
	 * Remove default image sizes
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function remove_image_sizes() {
		foreach(Options::get('remove_image_sizes') as $size_name => $size_label) {
			remove_image_size($size_name);
		}
	}

	/**
	 * Remove image size names from selection
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @param array $sizes All registered image sizes
	 */
	public static function remove_image_size_names($sizes) {
		foreach($sizes as $size_index => $size_name) {
			if(in_array($size_name, (array)Options::get('remove_image_sizes'))) {
				unset($sizes[$size_index]);
			}
		}

		return $sizes;
	}
}

?>
