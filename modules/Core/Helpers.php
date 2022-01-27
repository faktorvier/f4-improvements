<?php

namespace F4\WPI\Core;

/**
 * Core Helpers
 *
 * Helpers for the Core module
 *
 * @since 1.0.0
 * @package F4\WPI\Core
 */
class Helpers {
	/**
	 * Get plugin infos
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @param string $info_name The info name to show
	 * @return string The requested plugin info
	 */
	public static function get_plugin_info($info_name) {
		if(!function_exists('get_plugins')) {
			require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}

		$info_value = null;
		$plugin_infos = get_plugin_data(F4_WPI_PLUGIN_FILE_PATH);

		if(isset($plugin_infos[$info_name])) {
			$info_value = $plugin_infos[$info_name];
		}

		return $info_value;
	}

	/**
	 * Checks if any/all of the values are in an array
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @param array $needle An array with values to search
	 * @param array $haystack The array
	 * @param bool $must_contain_all TRUE if all needes must be found in the haystack, FALSE if only one is needed
	 * @return bool Returns TRUE if one of the needles is found in the array, FALSE otherwise.
	 */
	public static function array_in_array($needle, $haystack, $must_contain_all = false) {
		if($must_contain_all) {
			return !array_diff($needle, $haystack);
		} else {
			return (count(array_intersect($haystack, $needle))) ? true : false;
		}
	}

	/**
	 * Forces a variable to be an array
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @param mixed $value An array with values to search
	 * @param bool $append_value TRUE if the value should be appended to the array, FALSE if only an empty array should be returned
	 * @return array The value as array
	 */
	public static function maybe_force_array($value, $append_value = true) {
		if(!is_array($value)) {
			if($append_value && $value) {
				$value = array($value);
			} else {
				$value = array();
			}
		}

		return $value;
	}

	/**
	 * Insert one or more elements before a specific key
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @param array $array The original array
	 * @param string|array $search_key One or more keys to insert the values before
	 * @param array $target_values The associative array to insert
	 * @return array The new array
	 */
	public static function insert_before_key($array, $search_key, $target_values) {
		$array_new = array();

		if(!is_array($target_values)) {
			$target_values = array($target_values);
		}

		foreach($array as $key => $value) {
			if($key === $search_key) {
				foreach($target_values as $target_key => $target_value) {
					$array_new[$target_key] = $target_value;
				}
			}

			$array_new[$key] = $value;
		}

		return $array_new;
	}

	/**
	 * Insert one or more elements after a specific key
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @param array $array The original array
	 * @param string|array $search_key One or more keys to insert the values after
	 * @param array $target_values The associative array to insert
	 * @return array The new array
	 */
	public static function insert_after_key($array, $search_key, $target_values) {
		$array_new = array();

		if(!is_array($target_values)) {
			$target_values = array($target_values);
		}

		foreach($array as $key => $value) {
			$array_new[$key] = $value;

			if($key === $search_key) {
				foreach($target_values as $target_key => $target_value) {
					$array_new[$target_key] = $target_value;
				}
			}
		}

		return $array_new;
	}

	/**
	 * Sort array by key
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @param array $array The unsorted array
	 * @param array $key The key name to sort the array
	 * @return array The sorted array
	 */
	public static function sort_array_by_key($array, $key) {
		$array_sorted = $array;

		uasort($array_sorted, function($a, $b) use ($key) {
			return strcasecmp($a[$key], $b[$key]);
		});

		return $array_sorted;
	}

	/**
	 * Get post id of post object, or or null
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function maybe_get_post_id($post = null) {
		if(!$post && function_exists('get_the_ID')) {
			$post = get_the_ID();
		} elseif(is_object($post)) {
			$post = $post->ID;
		} elseif(!is_numeric($post)) {
			$post = null;
		}

		return $post;
	}

	/**
	 * Get post id of post object, or or null
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function maybe_get_post_object($post = null) {
		if(!$post && function_exists('get_post')) {
			$post = get_post();
		} elseif(is_numeric($post)) {
			$post = get_post($post);
		} elseif(!is_object($post)) {
			$post = null;
		}

		return $post;
	}

	/**
	 * Check if current post is specific post type
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function is_post_type($post_type, $post = null) {
		$is_post_type = false;

		if(function_exists('get_post_type')) {
			$is_post_type = get_post_type($post) === $post_type;
		}

		return $is_post_type;
	}

	/**
	 * Removes all content from a directory
	 *
	 * @since 1.3.1
 	 * @access public
 	 * @static
	 * @param string $dir The path to the directory
	 */
	public static function rmdir_content($dir) {
		$files = glob($dir . '/*');

		foreach($files as $file){
			if(is_file($file)) {
				unlink($file);
			} else {
				self::rmdir_recursive($file);
			}
		}
	}
}
