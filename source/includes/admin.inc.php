<?php

/**
 * Define settings fields
 *
 * @return array
 */
function soabox_profile_settings_fields() {
	$settings = [
		'id' => 'soabox_profile',
		'kabob' => 'soapbox-profile',
		'label' => __('Soapbox Profile'),
		'settings' => [
			[
				'id' => 'soabox_profile_add_roles',
				'label' => __('Add User Role'),
				'description' => 
					__('Add a class to the body tag for the current user\'s role'),
				'type' => 'boolean',
				'default' => true
			], [
				'id' => 'soabox_profile_add_user_name',
				'label' => __('Add Username'),
				'description' => 
					__('Add a class to the body tag for the current user\'s username'),
				'type' => 'boolean',
				'default' => false
			]
		]
	];

	return $settings;
}


/**
 * action admin_menu
 */
add_action('admin_menu', 'soabox_profile_create_menu');

/**
 * Create admin menu item
 *
 * @return void
 */
function soabox_profile_create_menu() {
	add_submenu_page(
		'options-general.php',
		'Soapbox Profile',
		'Soapbox Profile',
		'administrator',
		__FILE__,
		'soabox_profile_admin',
		plugins_url('/images/icon.png', __FILE__)
	);
}

/**
 * action admin_init
 */
add_action('admin_init', 'soabox_profile_settings');

/**
 * Register custom settings
 *
 * @return void
 */
function soabox_profile_settings() {
	$settings = soabox_profile_settings_fields();

	//register settings
	foreach ($settings['settings'] as $setting) {
		register_setting($settings['kabob'] . '-settings-group', $setting['id']);
	}	
}

/**
 * Admin settings
 *
 * @return void
 */
function soabox_profile_admin() {
	if (!function_exists('soabox_profile_get_formatted_field')) {
		require_once realpath(__DIR__) . '/form.inc.php';
	}

	//load user settings
	$settings = soabox_profile_settings_fields();
	?>
	<div class="wrap">
	<h1><?php echo $settings['label']; ?></h1>

	<form method="post" action="options.php">
		<?php settings_fields($settings['kabob'] . '-settings-group'); ?>
		<?php do_settings_sections($settings['kabob'] . '-settings-group'); ?>

		<table class="form-table">
			<?php
			foreach ($settings['settings'] as $setting) {
				$setting['saved'] = get_option($setting['id'], $setting['default']);

				echo soapbox_profile_get_formatted_field($setting);
				?>
			<?php
			}
			?>
		</table>

		<?php submit_button(); ?>
	</form>

</div>
<?php
}
