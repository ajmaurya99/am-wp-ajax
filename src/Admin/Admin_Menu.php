<?php

// Current class namespace.
namespace AmWPAjax\Admin;

use AmWPAjax\Admin\Options_Page; // Load Options_Page Class.
use AmWPAjax\Admin\Scripts; // Load Scripts Class.
use AmWPAjax\Admin\Refresh_Data; // Load Refresh_Data Class.
use AmWPAjax\Admin\Admin_Notices; // Load Admin_Notices Class.
use AmWPAjax\Admin\Text_Domain; // Load Text_Domain Class.
use AmWPAjax\Admin\Cli; // Load Cli Class.
use AmWPAjax\Frontend\Shortcode; // Load Shortcode Class.

/**
 * Class Admin_Menu registers the required settings for the plugin
 * and creates the options page to display the data.
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Admin_Menu' ) ) :

	class Admin_Menu {


		/**
		 * Construct the Admin_Menu class.
		 *
		 * @since   1.0.0
		 */
		public function __construct() {
			// Object of class Scripts.
			$scripts      = new Scripts();
			$refresh_data = new Refresh_Data();

			// Enqueue the admin scripts.
			add_action( 'admin_enqueue_scripts', array( $scripts, 'load_scripts' ) );

			// Define Ajax.
			add_action( 'wp_ajax_get_miusage_data', array( $refresh_data, 'get_miusage_data' ) );
			add_action( 'wp_ajax_nopriv_get_miusage_data', array( $refresh_data, 'get_miusage_data' ) );
		}

		/**
		 * Register settings required to create the admin menu page.
		 *
		 * @since   1.0.0
		 */
		public function create_admin_menu() {
			// Hook settings.
			add_action( 'admin_init', array( $this, 'am_wp_ajax_register_settings' ) );

			// Add Admin Menu.
			add_action( 'admin_menu', array( $this, 'am_wp_ajax_register_options_page' ) );

			// Plugin helper functions.
			$this->load_helpers();
		}

		/**
		 * Unregister settings on plugin delete or deactivate.
		 *
		 * @since   1.0.0
		 */
		public function remove_admin_menu() {
			// Delete settings on plugin uninstall.
			unregister_setting( 'am_wp_ajax_options_group', 'am_wp_ajax_option_name' );
		}

		/**
		 * Helper functions required on plugin load.
		 *
		 * @since   1.0.0
		 */
		public function load_helpers() {
			// Register Admin Notices.
			( new Admin_Notices() );

			// Load Text_Domain.
			( new Text_Domain() );

			// Register Shortcode.
			( new Shortcode() )->load_shortcode();

			// (new Cli())->refresh_data_using_cli();
		}

		/**
		 * Regsiter plugin settings page.
		 *
		 * @since   1.0.0
		 */
		public function am_wp_ajax_register_settings() {
			register_setting( 'am_wp_ajax_options_group', 'am_wp_ajax_option_name' );
			add_settings_section( 'am-wp-ajax-section-1', __( 'Awesome Motive', 'am_wp_ajax' ), array( $this, 'am_wp_ajax_settings_cb' ), 'am-wp-ajax-settings' );
		}

		/**
		 * Add plugin options page.
		 *
		 * @since   1.0.0
		 */
		public function am_wp_ajax_register_options_page() {
			add_options_page( __( 'AM WP AJAX', 'am_wp_ajax' ), __( 'AM WP AJAX Options', 'am_wp_ajax' ), 'manage_options', 'am-wp-ajax', array( $this, 'am_wp_ajax_options_page' ) );
		}


		/**
		 * Settings section.
		 *
		 * @since   1.0.0
		 */
		public function am_wp_ajax_settings_cb() {
			echo esc_html__( 'Developer Applicant Challenge.', 'am_wp_ajax' );
		}

		/**
		 * Display options page data.
		 *
		 * @since   1.0.0
		 */
		public function am_wp_ajax_options_page() {
			// get the data from the 'Options_Page' class.
			( new Options_Page() )->display_plugin_content();
		}
	}

endif;
