<?php

// Current class namespace.
namespace AmWPAjax\Admin;

/**
 * Class Refresh_Data gets the data from the endpoint on action of the button click.
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Refresh_Data' ) ) :

	class Refresh_Data {


		/**
		 * Function called on "Refresh" button clicked via Ajax
		 *
		 * @since   1.0.0
		 */
		public function get_miusage_data() {
			// Nonce Check.
			if ( 'GET' === $_SERVER['REQUEST_METHOD'] ) { // Check if post method.
				if ( ! check_ajax_referer( 'am-wp-security-nonce', 'security', false ) ) {
					wp_send_json_error( 'Unauthorized Request' );
					wp_die();
				}
			}

			/**
			 * Delete the transient cache if refresh dataa button is pressed.
			 */
			delete_transient( 'am_wp_ajax_miusage_data' );

			// get the data in table format.
			$table_response = ( new Get_Data() )->display_table();

			$data_response = array(
				'type' => 'success',
				'data' => $table_response,
			);

			// send data back to the calling script in decoded format.
			echo json_encode( $data_response );
			wp_die();
		}
	}

endif;
