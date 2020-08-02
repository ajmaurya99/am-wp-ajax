<?php

/**
 * Main plugin class.
 *
 * @package AM WP AJAX
 */
if (!class_exists('AM_WP_AJAX')) :

  class AM_WP_AJAX
  {

    public function __construct()
    {
      // Enqueue the admin scripts.
      add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));

      // Define Ajax 
      add_action('wp_ajax_get_miusage_data', array($this, 'get_miusage_data'));
      add_action('wp_ajax_nopriv_get_miusage_data', array($this, 'get_miusage_data'));
    }

    public function admin_scripts()
    {
      // detect current page
      global $pagenow;

      // jQuery is needed.
      wp_enqueue_script('jquery');

      // local plugin js
      wp_enqueue_script(
        'am-wp-script',
        AMWPAJAX_PLUGIN_URL . 'assets/js/main.js',
        ['jquery'],
        false,
        true
      );

      // ajax localized URL
      wp_localize_script(
        'am-wp-script',
        'ajax_initialize_script',
        array(
          'ajax_url' => admin_url('admin-ajax.php'),
          'security' => wp_create_nonce('am-wp-security-nonce'),
        )
      );

      // Show admin notices 
      if (isset($_GET['page'])) {
        // If plugin settings page
        if (in_array($pagenow, array('options-general.php')) && ($_GET['page'] == 'am-wp-ajax')) {
          if (get_transient('am_wp_ajax_miusage_data')) {
            add_action('admin_notices', array($this, 'show_notice_success'));
          } else {
            add_action('admin_notices', array($this, 'show_notice_error'));
          }
        }
      }
    }

    // Called on plugin activation
    public function activate()
    {
      // Hook settings
      add_action('admin_init', array($this, 'am_wp_ajax_register_settings'));

      // Add Admin Menu
      add_action('admin_menu', array($this, 'am_wp_ajax_register_options_page'));

      // Create shortcode to display data in frontend
      add_shortcode('amwpajax', array($this, 'get_table'));

      /**
       * CLI Command to get new data
       * source ~/.bash_profile
       * wp am-wp-ajax-reset
       * */

      if (defined('WP_CLI') && WP_CLI) {
        WP_CLI::add_command('am-wp-ajax-reset', array($this, 'get_new_data'));
      }
    }

    // Called on plugin deactivation
    public function deactivate()
    {
      // Delete settings on plugin deactivate
      unregister_setting('am_wp_ajax_options_group', 'am_wp_ajax_option_name');
    }

    // Called on plugin uninstalled / delete
    public function uninstall()
    {
      // Delete settings on plugin uninstall
      unregister_setting('am_wp_ajax_options_group', 'am_wp_ajax_option_name');
    }

    public function am_wp_ajax_register_settings()
    {
      // Regsiter plugin settings
      register_setting('am_wp_ajax_options_group', 'am_wp_ajax_option_name');
    }

    public function am_wp_ajax_register_options_page()
    {
      // Add plugin options page
      add_options_page(__('AM WP AJAX', 'am_wp_ajax'), __('AM WP AJAX Options', 'am_wp_ajax'), 'manage_options', 'am-wp-ajax', array($this, 'am_wp_ajax_options_page'));
    }



    public function am_wp_ajax_options_page()
    {

      // Get users data
      $users_data = $this->get_table();

      echo '<div class="wrap">';
      echo '<h1>AM WP Plugin</h1>';
      echo ' <table class="form-table">';
      echo '<tr valign="top">';
      echo '<th scope="row">' . $this->get_title() . '</th>'; // Dynamic title
      echo '<td class="show-content">';
      echo $users_data;
      echo '</td>';
      echo ' </tr>';
      echo ' </table>';
      $other_attributes = array('id' => 'get-ajax-data');
      submit_button(__('Refresh Data', 'am_wp_ajax'), 'primary', '', true, $other_attributes);
      echo '</div>';
    }

    // Function called on "Refresh" button clicked via Ajax
    public function get_miusage_data()
    {
      // Nonce Check
      if ('GET' === $_SERVER['REQUEST_METHOD']) { // Check if post method
        if (!check_ajax_referer('am-wp-security-nonce', 'security', false)) {
          wp_send_json_error('Unauthorized Request');
          wp_die();
        }
      }

      $table_response = $this->get_table();

      $data_response = array(
        'type' => 'success',
        'data' => $table_response,
      );

      echo json_encode($data_response);
      wp_die();
    }

    // Display data in table format
    public function get_table()
    {
      // Get any existing copy of our transient data
      if (false === ($response = get_transient('am_wp_ajax_miusage_data'))) {
        // Transient expired, refresh the data
        $response = wp_remote_get('https://miusage.com/v1/challenge/1/');
        set_transient('am_wp_ajax_miusage_data', $response, 60 * 60);
      }

      // same can be done by wp_remote_retrieve_response_code
      if ($response['response']['code'] == 200) : // if response is OK

        // $data = (json_decode($response['body'], true));
        $data = (json_decode(wp_remote_retrieve_body($response), true));

        $headers = $data['data']['headers'];
        $users   = $data['data']['rows'];

        // Setup display data
        $result  = '<table class="form-table">';
        $result .= '<thead>';
        $result .= '<tr valign="top">';
        foreach ($headers as $header) {
          $result .= '<th>' . $header . '</th>';
        }
        $result .= '</tr>';
        $result .= '</thead>';
        $result .= '<tbody>';


        // Sort result based on ID
        function cmp($a, $b)
        {
          return strcmp($a['id'], $b['id']);
        }

        usort($users, "cmp");
        foreach ($users as $user) {
          $result .= '<tr valign="top">';
          $result .= '<td>' . $user['id'] . '</td>';
          $result .= '<td>' . $user['fname'] . '</td>';
          $result .= '<td>' . $user['lname'] . '</td>';
          $result .= '<td>' . $user['email'] . '</td>';
          $result .= '<td>' . date_i18n('F d, Y', $user['date']) . '</td>';
          $result .= '</tr>';
        }

        $result .= '</tbody>';
        $result .= '</table>';
        return $result;

      endif;
    }

    public function get_new_data()
    {
      // This function can only be accessed via CLI
      if (!defined('WP_CLI')) {
        return;
      }

      delete_transient('am_wp_ajax_miusage_data');
      // $this->get_table();
      WP_CLI::success('New data is being fetched from https://miusage.com/v1/challenge/1/');
    }

    public function get_title()
    {
      $response = get_transient('am_wp_ajax_miusage_data');
      if ($response) {
        return json_decode(wp_remote_retrieve_body($response), true)['title'];
      }
    }

    public function show_notice_success()
    {
      $notice  = '<div class="notice notice-success">';
      $notice .= '<p>This date is served from cache!</p>';
      $notice .= '</div>';

      echo $notice;
    }

    public function show_notice_error()
    {
      $notice  = '<div class="notice notice-error">';
      $notice .= '<p>The cache has expired, Please refresh the data!</p>';
      $notice .= '</div>';

      echo $notice;
    }
  }

endif;

$am_wp_ajax = new AM_WP_AJAX();

// Register hooks for activation, deactivation and uninstall instances.
register_activation_hook(AMWPAJAX_PLUGIN_FILE, $am_wp_ajax->activate());
register_deactivation_hook(AMWPAJAX_PLUGIN_FILE, $am_wp_ajax->deactivate());
register_uninstall_hook(AMWPAJAX_PLUGIN_FILE, $am_wp_ajax->uninstall());

/**
 * CSS
 * Heaading and desc
 * admin notices logic
 */