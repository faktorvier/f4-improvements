<?php

use F4\WPI\Core\Helpers as Core;
use F4\WPI\Core\Options\Helpers as Options;

$args['field'] = wp_parse_args($args['field'], [
	'placeholder' => '',
	'rows' => 4
]);

?>

<textarea
	name="<?php echo esc_attr($args['field_name']); ?>"
	id="<?php echo esc_attr($args['field_name']); ?>"
	placeholder="<?php echo esc_attr($args['field']['placeholder']); ?>"
	rows="<?php echo esc_attr($args['field']['rows']); ?>"
	class="regular-text"
><?php echo esc_textarea(Options::get($args['option_name'])); ?></textarea>
