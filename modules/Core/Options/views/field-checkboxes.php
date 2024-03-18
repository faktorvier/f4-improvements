<?php

use F4\WPI\Core\Helpers as Core;
use F4\WPI\Core\Options\Helpers as Options;

?>

<fieldset>
	<?php foreach($args['field']['options'] as $option_value => $option_name): ?>
		<label for="<?php echo $args['field_name'] . '-' . $option_value; ?>">
			<input
				type="checkbox"
				name="<?php echo esc_attr($args['field_name']); ?>[]"
				id="<?php echo esc_attr($args['field_name'] . '-' . $option_value); ?>"
				value="<?php echo esc_attr($option_value); ?>"
				<?php checked(in_array($option_value, Options::get($args['option_name']))); ?>
			/>

			<span style="margin-right:1em;">
				<?php echo $option_name; ?>
			</span>
		</label>
	<?php endforeach; ?>
</fieldset>
