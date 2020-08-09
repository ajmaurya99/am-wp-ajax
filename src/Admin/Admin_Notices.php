<?php

// Current class namespace.
namespace AmWPAjax\Admin;

/**
 * Class Admin_Notice is responsible for displaying the notices on the plugins options page.
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Admin_Notices' ) ) :

	class Admin_Notices {

		/**
		 * Construct the Admin_Notices class.
		 *
		 * @since   1.0.0
		 */
		public function __construct() {
			// Detect current page.
			global $pagenow;

			// Show admin notices.
			if ( isset( $_GET['page'] ) ) {
				// If plugin settings page.
				if ( in_array( $pagenow, array( 'options-general.php' ) ) && ( $_GET['page'] == 'am-wp-ajax' ) ) {
					if ( get_transient( 'am_wp_ajax_miusage_data' ) ) {
						add_action( 'admin_notices', array( $this, 'show_notice_success' ) );
					} else {
						add_action( 'admin_notices', array( $this, 'show_notice_error' ) );
					}
				}
			}
		}

		/**
		 * Show success admin notice
		 *
		 * @since   1.0.0
		 */
		public function show_notice_success() {
			$notice  = '<div class="notice notice-success">';
			$notice .= '<p>' . esc_html( __( 'This data is served from cache!', 'am_wp_ajax' ) ) . '</p>';
			$notice .= '</div>';

			echo $notice;
		}

		/**
		 * Show error admin notice
		 *
		 * @since   1.0.0
		 */
		public function show_notice_error() {
			$notice  = '<div class="notice notice-error">';
			$notice .= '<p>' . esc_html( __( 'The cache has expired, Please refresh the data!', 'am_wp_ajax' ) ) . '</p>';
			$notice .= '</div>';

			echo $notice;
		}
	}

endif;
