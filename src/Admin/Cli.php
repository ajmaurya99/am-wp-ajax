<?php

// Current class namespace.
namespace AmWPAjax\Admin;

/**
 * Class Cli is responsible for creating the cli command to force fetch new data.
 * Cli Command:  wp am-wp-ajax-reset
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Cli' ) ) :

	class Cli {

		/**
		 * Add's the WP_CLI command to fetch new data.
		 *
		 * @since   1.0.0
		 */
		public function refresh_data_using_cli() {
			/**
			 * CLI Command to get new data
			 * source ~/.bash_profile
			 * wp am-wp-ajax-reset
			 * */

			if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
				WP_CLI::add_command( 'am-wp-ajax-reset', array( $this, 'get_new_data' ) );
			}
		}

		/**
		 * Delete the transient value and fetch new value from the endpoint.
		 *
		 * @since   1.0.0
		 */
		public function get_new_data() {
			// This function can only be accessed via CLI.
			if ( ! defined( 'WP_CLI' ) ) {
				return;
			}

			// Delete the saved transient data.
			delete_transient( 'am_wp_ajax_miusage_data' );
			// Fetch new data from the endpoint.
			$get_new_data = ( new Get_Data() );
			$get_new_data->display_table();

			WP_CLI::success( 'New data is being fetched from: ' . esc_url( $get_new_data->endpoint ) . '' );
		}
	}

endif;
