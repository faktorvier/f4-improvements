
<?php

use F4\WPI\Core\Helpers as Core;
use F4\WPI\Core\Options\Helpers as Options;

if(!defined('ABSPATH')) exit;

// Get tabs
$tabs = apply_filters('F4/WPI/register_options_tabs', []);
$tab_active = isset($_GET['tab']) ? $_GET['tab'] : array_keys($tabs)[0];

// Register fields
Options::register_fields();

?>

<script>
	jQuery(function() {
		jQuery('.nav-tab').on('click', function(e) {
			e.preventDefault();

			let $tab = jQuery(this);
			let $tabs = jQuery('.nav-tab');
			let $contents = jQuery('[data-tab-content]');
			let tabName = $tab.attr('data-tab');

			$tabs.removeClass('nav-tab-active');
			$tab.addClass('nav-tab-active');

			$contents.hide();
			$contents.filter('[data-tab-content="' + tabName + '"]').show();

			if(typeof URLSearchParams !== 'undefined') {
				let params = new URLSearchParams(jQuery('[name="_wp_http_referer"]').val());
				params.set('tab', tabName);
				jQuery('[name="_wp_http_referer"]').val(decodeURIComponent(params.toString()));
			}

			window.history.pushState(null, null, $tab.attr('href'));
		});
	});
</script>

<div class="wrap">
	<div class="f4-options-form">
		<!-- Headline -->
		<h1>
			<?php _e('Improvements for WordPress', 'f4-improvements'); ?>
		</h1>

		<!-- Tabs -->
		<nav class="nav-tab-wrapper">
			<?php foreach($tabs as $tab_name => $tab_args): ?>
				<a
					href="<?php echo admin_url('options-general.php?page=' . F4_WPI_OPTION_PAGE_NAME); ?>&tab=<?php echo $tab_name; ?>"
					data-tab="<?php echo $tab_name; ?>"
					class="nav-tab<?php if($tab_name === $tab_active): ?> nav-tab-active<?php endif; ?>"
					>
					<?php echo $tab_args['label']; ?>
				</a>
			<?php endforeach; ?>
		</nav>

		<!-- Options form -->
		<form method="POST" action="options.php" novalidate="novalidate">
			<?php settings_fields(F4_WPI_OPTION_GROUP_NAME); ?>

			<?php foreach($tabs as $tab_name => $tab_args): ?>
				<div
					<?php if($tab_name !== $tab_active): ?>style="display:none;"<?php endif; ?>
					data-tab-content="<?php echo $tab_name; ?>"
					>

					<?php do_settings_sections(F4_WPI_OPTION_PAGE_NAME . '-' . $tab_name); ?>
				</div>
			<?php endforeach; ?>

			<?php submit_button(); ?>
		</form>
	</div>

	<div class="f4-options-sidebar">
		<a class="f4-options-sidebar-link" href="https://www.f4dev.ch" target="_blank">
			<img src="<?php echo F4_WPI_URL . 'assets/img/made-with-love-by-f4.png'; ?>" alt="F4" />
		</a>
	</div>
</div>
