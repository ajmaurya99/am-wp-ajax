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
			$notice = '<div class="notice notice-success is-dismissible">';

			$notice .= '<p>' . sprintf(
				wp_kses( /* translators: %s - Refresh Data  anchor link. */
					__( 'The below data is fetched from cache and will be refreshed every one hour!, <strong>Click to fetch new data. <a class="get-ajax-data" href="%s">Refresh Data</a></strong>.', 'wp-mail-smtp' ),
					array(
						'a'      => array(
							'href'  => array(),
							'class' => array(),
						),
						'strong' => array(),
					)
				),
				'javascript:void(0)'
			) . '</p>';

			$notice .= '</div>';

			echo $notice;
		}

		/**
		 * Show error admin notice
		 *
		 * @since   1.0.0
		 */
		public function show_notice_error() {

			$notice = '<div class="notice notice-error is-dismissible">';

			$notice .= '<p>' . sprintf(
				wp_kses( /* translators: %s - Refresh Data  anchor link. */
					__( 'The cache got expired and new data was fetched after an interval of one hour, <strong>Click to fetch new data. <a class="get-ajax-data" href="%s">Refresh Data</a></strong>.', 'wp-mail-smtp' ),
					array(
						'a'      => array(
							'href'  => array(),
							'class' => array(),
						),
						'strong' => array(),
					)
				),
				'javascript:void(0)'
			) . '</p>';
			$notice .= '</div>';

			echo $notice;
		}
	}

endif;
