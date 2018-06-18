<?php
/**
 * Register the required plugins for this theme.
 */
function mb_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		// This is an example of how to include a plugin bundled with a theme.
		array(
			'name'               => 'Advanced Custom Fields PRO', // The plugin name.
			// The plugin slug (typically the folder name).
			'slug'               => 'advanced-custom-fields-pro',
			// If false, the plugin is only 'recommended' instead of required.
			'required'           => true,
			// E.g. 1.0.0. If set, the active plugin must be this version or higher.
			// If the plugin version is higher than the plugin version installed,
			// the user will be notified to update the plugin.
			'version'            => '',
			// If true, plugin is activated upon theme activation and
			// cannot be deactivated until theme switch.
			'force_activation'   => true,
			// If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'force_deactivation' => false,
			// If set, overrides default API URL and points to an external URL.
			'external_url'       => 'https://www.advancedcustomfields.com/pro/',
			// If set, this callable will be be checked for availability
			// to determine if a plugin is active.
			'is_callable'        => '',
		),
	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you
	 * already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us
	 * access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		// Unique ID for hashing notices for multiple instances of TGMPA.
		'id'           => 'tgmpa',
		// Default absolute path to bundled plugins.
		'default_path' => '',
		// Menu slug.
		'menu'         => 'tgmpa-install-plugins',
		// Parent menu slug.
		'parent_slug'  => 'themes.php',
		// Capability needed to view plugin install page, should be a capability
		// associated with the parent menu used.
		'capability'   => 'edit_theme_options',
		// Show admin notices or not.
		'has_notices'  => true,
		// If false, a user cannot dismiss the nag message.
		'dismissable'  => false,
		// If 'dismissable' is false, this message will be output at top of nag.
		'dismiss_msg'  => '',
		// Automatically activate plugins after installation or not.
		'is_automatic' => false,
		// Message to output right before the plugins table.
		'message'      => '',
	);
	tgmpa( $plugins, $config );
}
