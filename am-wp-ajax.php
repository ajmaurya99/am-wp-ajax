<?php

/**
 * Challenge: API Based Plugin
 *
 * @link              https://github.com/ajmaurya99/am-wp-ajax
 * @since             1.0.0
 * @package           am_wp_ajax
 *
 * @wordpress-plugin
 * Plugin Name:       AM WP AJAX
 * Plugin URI:        https://github.com/ajmaurya99/am-wp-ajax
 * Description:       Challenge: API Based Plugin
 * Version:           1.0.0
 * Requires PHP:      5.6.0
 * Author:            Ajay Maurya
 * Author URI:        https://github.com/ajmaurya99/am-wp-ajax
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

// Minimum PHP Version
if ( ! defined( 'MIN_PHP_VER' ) ) {
	define( 'MIN_PHP_VER', '5.6.0' );
}

// If the file exists, require it.
if ( is_readable( AMWPAJAX_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
	require_once AMWPAJAX_PLUGIN_DIR . 'vendor/autoload.php';
}


// NameSpace decleration.
use AmWPAjax\Admin as Admin;
use AmWPAjax\Frontend;
use AmWPAjax\Admin\Get_Data;

// Create main class object to register activation and deactivation hooks.
$am_wp_ajax = new Admin\AM_WP_AJAX();

register_activation_hook( AMWPAJAX_PLUGIN_FILE, $am_wp_ajax->activate() );
register_deactivation_hook( AMWPAJAX_PLUGIN_FILE, $am_wp_ajax->deactivate() );
register_uninstall_hook( AMWPAJAX_PLUGIN_FILE, $am_wp_ajax->uninstall() );


/**
 * CLI Command to get new data
 * source ~/.bash_profile
 * wp am-wp-ajax-reset
 * */


	/**
		 * Delete the transient value and fetch new value from the endpoint.
		 *
		 * @since   1.0.0
		 */
 $cli = function () {
	// This function can only be accessed via CLI.
	if ( ! defined( 'WP_CLI' ) ) {
		return;
	}

	// Delete the saved transient data.
	delete_transient( 'am_wp_ajax_miusage_data' );
	// Fetch new data from the endpoint.
	$get_new_data = ( new Get_Data() );
	$get_new_data->display_table();

	WP_CLI::success( 'New data is being fetched from: ' . esc_url( $get_new_data->getEndpoint() ) . '' );
};

	/**
		 * Add's the WP_CLI command to fetch new data.
		 *
		 * @since   1.0.0
		 */
	if ( class_exists( 'WP_CLI' ) ) { // execute only if ran via command line.
		if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			WP_CLI::add_command( 'am-wp-ajax-reset', $cli );
		}
	}

