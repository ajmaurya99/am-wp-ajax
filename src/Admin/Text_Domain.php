<?php

// Current class namespace.
namespace AmWPAjax\Admin;

/**
 * Class Text_Domain loads the plugin text domain.
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Text_Domain' ) ) :

	class Text_Domain {

		/**
		 * Constructs the Text_Domain class.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			// Load plugin textdomain.
			add_action( 'admin_init', array( $this, 'am_wp_ajax_load_textdomain' ) );
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
