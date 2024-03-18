<?php

use F4\WPI\Core\Helpers as Core;
use F4\WPI\Core\Options\Helpers as Options;

$args['field'] = wp_parse_args($args['field'], [
	'placeholder' => ''
]);

?>

<input
	type="text"
	name="<?php echo esc_attr($args['field_name']); ?>"
	id="<?php echo esc_attr($args['field_name']); ?>"
	value="<?php echo esc_attr(Options::get($args['option_name'])); ?>"
	class="regular-text"
	placeholder="<?php echo esc_attr($args['field']['placeholder']); ?>"
/>
