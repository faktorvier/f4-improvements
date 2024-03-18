<?php

use F4\WPI\Core\Helpers as Core;
use F4\WPI\Core\Options\Helpers as Options;

?>

<select
	name="<?php echo esc_attr($args['field_name']); ?>"
	id="<?php echo esc_attr($args['field_name']); ?>"
>
	<?php foreach($args['field']['options'] as $option_value => $option_name): ?>
		<option
			value="<?php echo $option_value; ?>"
			<?php selected($option_value === Options::get($args['option_name'])); ?>
			>
			<?php echo $option_name; ?>
		</option>
	<?php endforeach; ?>
</select>
