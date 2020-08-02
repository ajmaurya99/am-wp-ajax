<?php

/**
 * Register the "book" custom post type
 */
function wp_ajax_setup_post_type()
{
  register_post_type('book', ['public' => true]);
}
add_action('init', 'wp_ajax_setup_post_type');


/**
 * Activate the plugin.
 */
function wp_ajax_activate()
{
  // Trigger our function that registers the custom post type plugin.
  wp_ajax_setup_post_type();
  // Clear the permalinks after the post type has been registered.
  flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'wp_ajax_activate');


/**
 * Deactivation hook.
 */
function wp_ajax_deactivate()
{
  // Unregister the post type, so the rules are no longer in memory.
  unregister_post_type('book');
  // Clear the permalinks to remove our post type's rules from the database.
  flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'wp_ajax_deactivate');



function wporg_settings_init()
{
  // register a new setting for "reading" page
  register_setting('reading', 'wporg_setting_name');

  // register a new section in the "reading" page
  add_settings_section(
    'wporg_settings_section',
    'WPOrg Settings Section',
    'wporg_settings_section_cb',
    'reading'
  );

  // register a new field in the "wporg_settings_section" section, inside the "reading" page
  add_settings_field(
    'wporg_settings_field',
    'WPOrg Setting',
    'wporg_settings_field_cb',
    'reading',
    'wporg_settings_section'
  );
}

/**
 * register wporg_settings_init to the admin_init action hook
 */
add_action('admin_init', 'wporg_settings_init');

/**
 * callback functions
 */

// section content cb
function wporg_settings_section_cb()
{
  echo '<p>WPOrg Section Introduction.</p>';
  $response = wp_remote_head('https://miusage.com/v1/challenge/1/');
  $http_code = wp_remote_retrieve_response_code($response);
  echo "<pre>";
  print_r($response);
  echo "</pre>";
}

// field content cb
function wporg_settings_field_cb()
{
  // get the value of the setting we've registered with register_setting()
  $setting = get_option('wporg_setting_name');
  // output the field
?>
  <input type="text" name="wporg_setting_name" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>">
<?php
}

/**
 * https://developer.wordpress.org/plugins/plugin-basics/best-practices/
 * https://github.com/DevinVinson/WordPress-Plugin-Boilerplate
 * https://iandunn.name/content/presentations/wp-oop-mvc/mvc.php
 * https://developer.wordpress.org/plugins/plugin-basics/determining-plugin-and-content-directories/
 * https://developer.wordpress.org/plugins/security/
 * https://developer.wordpress.org/plugins/security/checking-user-capabilities/
 * https://developer.wordpress.org/plugins/security/data-validation/
 * https://developer.wordpress.org/plugins/security/securing-input/
 * https://developer.wordpress.org/plugins/security/securing-output/
 * https://developer.wordpress.org/plugins/security/nonces/
 * https://developer.wordpress.org/plugins/administration-menus/top-level-menus/
 * https://developer.wordpress.org/plugins/shortcodes/shortcodes-with-parameters/
 * https://developer.wordpress.org/plugins/http-api/  // IMp
 * https://developer.wordpress.org/apis/handbook/transients/
 * https://developer.wordpress.org/plugins/javascript/enqueuing/
 * https://developer.wordpress.org/plugins/internationalization/localization/
 * https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/
 * https://developer.wordpress.org/plugins/developer-tools/debug-bar-and-add-ons/
 * 
 * 
 */

/**
 * https://github.com/ptahdunbar/wp-skeleton-plugin
 * https://developer.wordpress.org/cli/commands/scaffold/plugin/
 * https://make.wordpress.org/plugins/2013/11/24/how-to-fix-the-intentionally-vulnerable-plugin/
 * https://wordpress.tv/2011/01/29/mark-jaquith-theme-plugin-security/
 * https://developer.wordpress.org/plugins/security/securing-input/
 * https://developer.wordpress.org/plugins/security/nonces/
 * https://markjaquith.wordpress.com/2006/06/02/wordpress-203-nonces/
 * https://generatewp.com/shortcodes/
 *
 */

 /**
  * https://wisdmlabs.com/blog/create-settings-options-page-for-wordpress-plugin/
  * https://codex.wordpress.org/Settings_API
  https://github.com/DevinVinson/WordPress-Plugin-Boilerplate
  https://tommcfarlin.com/secure-ajax-requests-in-wordpress/
  https://awesomemotive.com/career/developer-applicant-challenge/
  */

  https://plugins.trac.wordpress.org/browser/simple-podcasting/trunk/simple-podcasting.php
  https://plugins.trac.wordpress.org/browser/maps-block-apple/trunk/maps-block-apple.php
  https://plugins.trac.wordpress.org/browser/autoshare-for-twitter/trunk/includes/class-publish-tweet.php
  https://plugins.trac.wordpress.org/browser/wp-mail-smtp/trunk/wp_mail_smtp.php
  https://plugins.trac.wordpress.org/browser/wpforms-lite/trunk/wpforms.php
  https://plugins.trac.wordpress.org/browser/floating-social-bar/trunk/floating-social-bar.php
  https://plugins.trac.wordpress.org/browser/subscribe-bar-youtube/trunk/youtube-subscribe-bar.php
?>

