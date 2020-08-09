<?php

namespace AmWPAjax\Frontend;

use AmWPAjax\Admin\Get_Data;

if (!class_exists('Shortcode')) :

  class Shortcode
  {
    public function load_shortcode()
    {
      $get_table = new Get_Data();
      // Create shortcode to display data in frontend. [amwpajax]
      add_shortcode('amwpajax', array($get_table, 'display_table'));
    }
  }

endif;
