<?php

namespace AmWPAjax\Admin;

// Scripts class.
use AmWPAjax\Admin\Scripts;

/**
 * Main plugin class.
 */
if (!class_exists('AM_WP_AJAX')) :

  class AM_WP_AJAX
  {

    /**
     * Data endpoint.
     *
     * @var Endpoint $endpoint
     * @since   1.0.0
     */
    protected $endpoint = 'https://miusage.com/v1/challenge/1/';

    public function __construct()
    {
      // Call the init function to load all the required scripts.
      $this->init();
    }

    /**
     * Assign all hooks to proper places.
     *
     * @since 1.0.0
     */
    public function init()
    {
      // Object of class Scripts.
      $scripts = new Scripts();

      // Enqueue the admin scripts.
      add_action('admin_enqueue_scripts', array($scripts, 'load_scripts'));

      // Define Ajax.
      // add_action('wp_ajax_get_miusage_data', array($this, 'get_miusage_data'));
      // add_action('wp_ajax_nopriv_get_miusage_data', array($this, 'get_miusage_data'));

    }
  }

endif;
