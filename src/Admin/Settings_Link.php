<?php

// Current class namespace.
namespace AmWPAjax\Admin;

/**
 * Class Settings_Link is responsible for displaying plugins settings link.
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Settings_Link' ) ) :

	class Settings_Link {


		// Plugin name.
		protected $plugin_name;

		/**
		 * Construct the Settings_Link class.
		 *
		 * @since   1.0.0
		 */
		public function __construct() {
			$this->plugin_name = plugin_basename( AMWPAJAX_PLUGIN_FILE );
			add_filter( "plugin_action_links_$this->plugin_name", array( $this, 'am_wp_ajax_settings_link' ) );
		}

		/**
		 * Add plugins settings link.
		 *
		 * @since   1.0.0
		 */
		public function am_wp_ajax_settings_link( $links ) {
			$settings_link = '<a href="options-general.php?page=am-wp-ajax">Settings</a>';
			array_unshift( $links, $settings_link );
			return $links;
		}
	}

endif;
