<?php

use F4\WPI\Core\Helpers as Core;
use F4\WPI\Core\Options\Helpers as Options;


$args['fields'] = wp_parse_args($args['fields'], [
	'show_option_none' => __('&mdash; Select &mdash;', 'f4-improvements'),
	'option_none_value' => '0',
	'post_status' => array('publish')
])

?>

<?php
	wp_dropdown_pages(
		array(
			'name' => $args['field_name'],
			'show_option_none' => $args['fields']['show_option_none'],
			'option_none_value' => $args['fields']['option_none_value'],
			'selected' => Options::get($args['option_name']),
			'post_status' => $args['fields']['post_status']
		)
	);
?>
