<?php

use F4\WPI\Core\Helpers as Core;
use F4\WPI\Core\Options\Helpers as Options;

?>

<input
	type="checkbox"
	name="<?php echo esc_attr($args['field_name']); ?>"
	id="<?php echo esc_attr($args['field_name']); ?>"
	value="1"
	<?php checked(Options::get($args['option_name'])); ?>
/>
