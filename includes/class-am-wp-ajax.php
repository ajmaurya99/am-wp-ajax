<?php

/**
 * @file
 * This is the plugins main file, all logic resides here.
 *
 * @package am_wp_ajax
 * @since   1.0.0
 */

/**
 * Main plugin class.
 */

if ( ! class_exists( 'AM_WP_AJAX' ) ) :

	class AM_WP_AJAX {

		/**
		 * Data endpoint.
		 *
		 * @var Endpoint $endpoint
		 * @since   1.0.0
		 */
		protected $endpoint = 'https://miusage.com/v1/challenge/1/';

		/**
		 * Construct the AM_WP_AJAX class.
		 *
		 * @since   1.0.0
		 */
		public function __construct() {
			// Enqueue the admin scripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );

			// Define Ajax.
			add_action( 'wp_ajax_get_miusage_data', array( $this, 'get_miusage_data' ) );
			add_action( 'wp_ajax_nopriv_get_miusage_data', array( $this, 'get_miusage_data' ) );
		}

		/**
		 * Load admin assets
		 *
		 * @since   1.0.0
		 */
		public function admin_assets() {
			// Detect current page.
			global $pagenow;

			// jQuery is needed.
			wp_enqueue_script( 'jquery' );

			// local plugin js.
			wp_enqueue_script(
				'am-wp-script',
				AMWPAJAX_PLUGIN_URL . 'assets/js/main.js',
				array( 'jquery' ),
				'0.1.0',
				true
			);

			// local plugin css.
			wp_enqueue_style(
				'am-wp-style',
				AMWPAJAX_PLUGIN_URL . 'assets/css/main.css',
				array(),
				'0.1.0',
				'all'
			);

			// ajax localized URL.
			wp_localize_script(
				'am-wp-script',
				'ajax_initialize_script',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'security' => wp_create_nonce( 'am-wp-security-nonce' ),
				)
			);

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

			// Load plugin textdomain.
			add_action( 'admin_init', array( $this, 'am_wp_ajax_load_textdomain' ) );
		}

		/**
		 * Called on plugin activation
		 *
		 * @since   1.0.0
		 */
		public function activate() {
			// Hook settings.
			add_action( 'admin_init', array( $this, 'am_wp_ajax_register_settings' ) );

			// Add Admin Menu.
			add_action( 'admin_menu', array( $this, 'am_wp_ajax_register_options_page' ) );

			// Create shortcode to display data in frontend.
			add_shortcode( 'amwpajax', array( $this, 'get_table' ) );

			/**
			 * CLI Command to get new data
			 * source ~/.bash_profile
			 * wp am-wp-ajax-reset
			 * */

			if ( defined( 'WP_CLI' ) && WP_CLI ) {
				WP_CLI::add_command( 'am-wp-ajax-reset', array( $this, 'get_new_data' ) );
			}
		}

		/**
		 * Called on plugin deactivation
		 *
		 * @since   1.0.0
		 */
		public function deactivate() {
			// Delete settings on plugin deactivate.
			unregister_setting( 'am_wp_ajax_options_group', 'am_wp_ajax_option_name' );
		}

		/**
		 * Called on plugin uninstalled / delete
		 *
		 * @since   1.0.0
		 */
		public function uninstall() {
			// Delete settings on plugin uninstall.
			unregister_setting( 'am_wp_ajax_options_group', 'am_wp_ajax_option_name' );
		}

		/**
		 * Regsiter plugin settings
		 *
		 * @since   1.0.0
		 */
		public function am_wp_ajax_register_settings() {
			register_setting( 'am_wp_ajax_options_group', 'am_wp_ajax_option_name' );
			add_settings_section( 'am-wp-ajax-section-1', __( 'Awesome Motive', 'am_wp_ajax' ), array( $this, 'am_wp_ajax_settings_cb' ), 'am-wp-ajax-settings' );
		}

		/**
		 * Add plugin options page
		 *
		 * @since   1.0.0
		 */
		public function am_wp_ajax_register_options_page() {
			add_options_page( __( 'AM WP AJAX', 'am_wp_ajax' ), __( 'AM WP AJAX Options', 'am_wp_ajax' ), 'manage_options', 'am-wp-ajax', array( $this, 'am_wp_ajax_options_page' ) );
		}

		/**
		 * Settings section
		 *
		 * @since   1.0.0
		 */
		public function am_wp_ajax_settings_cb() {
			echo esc_html__( 'Developer Applicant Challenge.', 'am_wp_ajax' );
		}

		/**
		 * Plugins settings page content
		 *
		 * @since   1.0.0
		 */
		public function am_wp_ajax_options_page() {        ?>

				<?php
				// Get users data.
				$users_data = $this->get_table();
				?>

	  <div class="wrap">
		<h1><?php esc_html( __( 'AM WP AJAX', 'am_wp_ajax' ) ); ?></h1>
			<?php settings_fields( 'am_wp_ajax_options_group' ); ?>
			<?php do_settings_sections( 'am-wp-ajax-settings' ); ?>
		<h2><?php esc_html_e( 'Users Table', 'am_wp_ajax' ); ?></h2>
		<table class="form-table">
		  <tr valign="top">
			<th scope="row"><?php echo esc_html__( $this->get_title(), 'am_wp_ajax' ); ?></th>
			<td class="show-content">
			  <?php
				echo $users_data; // use wp_kses.
				?>
			</td>
		  </tr>
		</table>
				<?php
				$other_attributes = array( 'id' => 'get-ajax-data' );
				submit_button( __( 'Refresh Data', 'am_wp_ajax' ), 'primary', '', true, $other_attributes );
				?>
	  </div>

				<?php
		}

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

			$table_response = $this->get_table();

			$data_response = array(
				'type' => 'success',
				'data' => $table_response,
			);

			echo json_encode( $data_response );
			wp_die();
		}

		/**
		 * Display data in table format
		 *
		 * @since   1.0.0
		 *
		 * @return table HTML
		 */
		public function get_table() {
			// Get any existing copy of our transient data.
			if ( false === ( $response = get_transient( 'am_wp_ajax_miusage_data' ) ) ) {
				// Transient expired, refresh the data.
				$response = wp_remote_get( $this->endpoint );
				set_transient( 'am_wp_ajax_miusage_data', $response, 60 * 60 );
			}

			// same can be done by wp_remote_retrieve_response_code.
			if ( $response['response']['code'] == 200 ) : // if response is OK.

				// $data = (json_decode($response['body'], true));
				$data = ( json_decode( wp_remote_retrieve_body( $response ), true ) );

				$headers = $data['data']['headers'];
				$users   = $data['data']['rows'];

				// Setup display data.
				$result  = '<table class="form-table am-wp-ajax-table">';
				$result .= '<thead>';
				$result .= '<tr valign="top">';
				foreach ( $headers as $header ) {
					$result .= '<th>' . esc_attr( $header ) . '</th>';
				}
				$result .= '</tr>';
				$result .= '</thead>';
				$result .= '<tbody>';

				/**
				 * Sort result based on ID
				 *
				 * @param int $a
				 * @param int $b
				 * @return int greater number
				 */
				function cmp( $a, $b ) {
					if ( $a == $b ) {
						return 0;
					}
					return ( $a < $b ) ? -1 : 1;
				}

				usort( $users, 'cmp' );
				foreach ( $users as $user ) {
					$result .= '<tr valign="top">';
					$result .= '<td>' . esc_attr( $user['id'] ) . '</td>';
					$result .= '<td>' . esc_attr( $user['fname'] ) . '</td>';
					$result .= '<td>' . esc_attr( $user['lname'] ) . '</td>';
					$result .= '<td>' . esc_attr( $user['email'] ) . '</td>';
					$result .= '<td>' . date_i18n( 'F d, Y', $user['date'] ) . '</td>';
					$result .= '</tr>';
				}

				$result .= '</tbody>';
				$result .= '</table>';
				return $result;

		  endif;
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

			delete_transient( 'am_wp_ajax_miusage_data' );
			$this->get_table();
			WP_CLI::success( 'New data is being fetched from: ' . esc_url( $this->endpoint ) . '' );
		}

		/**
		 * Get title value from the given endpoint
		 *
		 * @since   1.0.0
		 *
		 * @return string title
		 */
		public function get_title() {
			$response = get_transient( 'am_wp_ajax_miusage_data' );
			if ( $response ) {
				return json_decode( wp_remote_retrieve_body( $response ), true )['title'];
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

		/**
		 * Load plugin textdomain.
		 *
		 * @since   1.0.0
		 */
		public function am_wp_ajax_load_textdomain() {
			load_plugin_textdomain( 'am_wp_ajax', false, AMWPAJAX_PLUGIN_DIR . '/languages' );
		}
	}

endif;

$am_wp_ajax = new AM_WP_AJAX();

// Register hooks for activation, deactivation and uninstall instances.
register_activation_hook( AMWPAJAX_PLUGIN_FILE, $am_wp_ajax->activate() );
register_deactivation_hook( AMWPAJAX_PLUGIN_FILE, $am_wp_ajax->deactivate() );
register_uninstall_hook( AMWPAJAX_PLUGIN_FILE, $am_wp_ajax->uninstall() );
