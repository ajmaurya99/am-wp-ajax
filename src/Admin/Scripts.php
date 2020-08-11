<?php

// Current class namespace.
namespace AmWPAjax\Admin;

/**
 * Class Scripts loads all the required assets for our plugin.
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Scripts' ) ) :

	class Scripts {


		/**
		 * Load the plugin script and style.
		 *
		 * @since 1.0.0
		 */
		public function load_scripts() {
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
		}
	}

endif;
