<?php

// Current class namespace.
namespace AmWPAjax\Frontend;

// Load Get_Data class.
use AmWPAjax\Admin\Get_Data;

/**
 * Class Shortcode adds the shortcode support to the plugin.
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Shortcode' ) ) :

	class Shortcode {

		public function load_shortcode() {
			// Get the data from the Get_Data class.
			$get_table = new Get_Data();
			// Create shortcode to display data in frontend. [amwpajax]
			add_shortcode( 'amwpajax', array( $get_table, 'display_table' ) );
		}
	}

endif;
