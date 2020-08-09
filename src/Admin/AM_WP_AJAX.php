<?php

// Current class namespace.
namespace AmWPAjax\Admin;

// Load Admin_Menu Class.
use AmWPAjax\Admin\Admin_Menu;

/**
 * Class AM_WP_AJAX contains all the activation and deactivation hooks.
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'AM_WP_AJAX' ) ) :

	class AM_WP_AJAX {

		/**
		 * Activate plugin actions.
		 *
		 * @since 1.0.0
		 */
		public function activate() {
			// create_admin_menu add the admin menu after the plugin activation.
			( new Admin_Menu() )->create_admin_menu();  // error calling 3 times.
		}

		/**
		 * Deactivate plugin actions.
		 *
		 * @since 1.0.0
		 */
		public function deactivate() {
			// remove_admin_menu removes the admin menu after the plugin activation.
			( new Admin_Menu() )->remove_admin_menu();
		}

		/**
		 * Uninstall plugin actions.
		 *
		 * @since 1.0.0
		 */
		public function uninstall() {
			// remove_admin_menu removes the admin menu after the plugin activation.
			( new Admin_Menu() )->remove_admin_menu();
		}
	}

endif;
