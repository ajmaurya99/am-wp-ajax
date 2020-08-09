<?php

/**
 * The Awesome Motive -  Developer Applicant Challenge
 *
 * @link              https://awesomemotive.com
 * @since             1.0.0
 * @package           am_wp_ajax
 *
 * @wordpress-plugin
 * Plugin Name:       AM WP AJAX
 * Plugin URI:        https://awesomemotive.com
 * Description:       The Awesome Motive -  Developer Applicant Challenge
 * Version:           1.0.0
 * Author:            Ajay Maurya
 * Author URI:        https://awesomemotive.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       am_wp_ajax
 * Domain Path:       /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin version.
if ( ! defined( 'AMWPAJAX_VERSION' ) ) {
	define( 'AMWPAJAX_VERSION', '1.0.0' );
}

// Plugin Folder Path.
if ( ! defined( 'AMWPAJAX_PLUGIN_DIR' ) ) {
	define( 'AMWPAJAX_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

// Plugin Folder URL.
if ( ! defined( 'AMWPAJAX_PLUGIN_URL' ) ) {
	define( 'AMWPAJAX_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// Plugin Root File.
if ( ! defined( 'AMWPAJAX_PLUGIN_FILE' ) ) {
	define( 'AMWPAJAX_PLUGIN_FILE', __FILE__ );
}

	// If the file exists, require it.
if ( is_readable( AMWPAJAX_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
  require_once AMWPAJAX_PLUGIN_DIR . 'vendor/autoload.php';
}

use AmWPAjax\Admin as Admin;
use AmWPAjax\Frontend;

$am_wp_ajax = new Admin \AM_WP_AJAX();

register_activation_hook( AMWPAJAX_PLUGIN_FILE, $am_wp_ajax->activate() );
register_deactivation_hook( AMWPAJAX_PLUGIN_FILE, $am_wp_ajax->deactivate() );
register_uninstall_hook( AMWPAJAX_PLUGIN_FILE, $am_wp_ajax->uninstall() );