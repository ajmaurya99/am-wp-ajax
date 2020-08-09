<?php

namespace AmWPAjax\Admin;

// Settings class.
use AmWPAjax\Admin\Admin_Menu;

/**
 * Main plugin class.
 */
if (!class_exists('AM_WP_AJAX')) :

  class AM_WP_AJAX
  {

    public function __construct()
    {
    }

    public function activate()
    {
      // error calling 3 times.
      (new Admin_Menu())->create_admin_menu();
    }

    public function deactivate()
    {
      (new Admin_Menu())->remove_admin_menu();
    }

    public function uninstall()
    {
      (new Admin_Menu())->remove_admin_menu();
    }
  }

endif;
