<?php

// Current class namespace.
namespace AmWPAjax\Admin;

// Load Get_Data Class.
use AmWPAjax\Admin\Get_Data;

/**
 * Class Options_Page is responsible for the display of the plugins options page.
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Options_Page' ) ) :

	class Options_Page {

		/**
		 * Displays the plugins options page content.
		 *
		 * @since 1.0.0
		 */
		public function display_plugin_content() {       ?>
			<?php

			// Get users data in table format.
			$users_data = ( new Get_Data() )->display_table();
			// Get title field from the data endpoint
			$title = ( new Get_Data() )->get_title();
			?>

	  <div class="wrap">
   <!-- Add Logo Here -->
		<div id="am-wp-ajax-header">
			<img class="am-wp-ajax-header-logo" src="<?php echo esc_url( 'https://s.w.org/style/images/about/WordPress-logotype-standard.png' ); ?>" alt="AM WP AJAX"/>
		</div>

		<h1><?php esc_html( __( 'AM WP AJAX', 'am_wp_ajax' ) ); ?></h1>
			<?php settings_fields( 'am_wp_ajax_options_group' ); ?>
			<?php do_settings_sections( 'am-wp-ajax-settings' ); ?>
		<div id="am-wp-ajax-content-wrapper">
		<table class="form-table">
		  <tr valign="top">
			<th scope="row"><?php echo esc_html__( $title, 'am_wp_ajax' ); ?></th>
			<td class="show-content">
			  <?php
				echo $users_data; // use wp_kses.
				?>
			</td>
		  </tr>
	</table>
	</div>
			<?php
			$other_attributes = array( 'id' => 'get-ajax-data' );
			submit_button( __( 'Refresh Data', 'am_wp_ajax' ), 'primary get-ajax-data', '', true, $other_attributes );
			?>
	  </div>

			<?php
		}
	}

endif;
