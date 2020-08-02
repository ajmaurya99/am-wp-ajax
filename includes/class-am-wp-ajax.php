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

      add_action('wp_ajax_get_miusage_data', array($this, 'get_miusage_data'));
      add_action('wp_ajax_nopriv_get_miusage_data', array($this, 'get_miusage_data'));
    }

    public function admin_scripts()
    {
      // jQuery is needed.
      wp_enqueue_script('jquery');

      wp_enqueue_script(
        'am-wp-script',
        AMWPAJAX_PLUGIN_URL . 'assets/js/main.js',
        ['jquery'],
        false,
        true
      );

      wp_localize_script(
        'am-wp-script',
        'ajax_initialize_script',
        [
          'ajax_url'  => admin_url('admin-ajax.php'),
          'security'  => wp_create_nonce('am-wp-security-nonce'),
        ]
      );
    }

    public function activate()
    {
      // Hook settings
      add_action('admin_init', array($this, 'am_wp_ajax_register_settings'));

      // Add Admin Menu
      add_action('admin_menu', array($this, 'am_wp_ajax_register_options_page'));
    }

    public function deactivate()
    {
    }

    public function uninstall()
    {
    }

    public function am_wp_ajax_register_settings()
    {
      register_setting('am_wp_ajax_options_group', 'am_wp_ajax_option_name');
    }

    public function am_wp_ajax_register_options_page()
    {
      add_options_page(__('AM WP AJAX', 'am_wp_ajax'), __('AM WP AJAX Options', 'am_wp_ajax'), 'manage_options', 'am-wp-ajax', array($this, 'am_wp_ajax_options_page'));
    }



    public function am_wp_ajax_options_page()
    {
      echo '<div class="wrap">';
      echo '<h1>Hello World</h1>';
      // $response = wp_remote_get('https://miusage.com/v1/challenge/1/');
      // $http_code = wp_remote_retrieve_response_code($response);
      // echo '<pre>';
      // print_r($response);
      // echo '</pre>';
      echo '<div class="show-content">';
      echo $this->get_table();
      echo '</div>';
      $other_attributes = array('id' => 'get-ajax-data');
      submit_button(__('Get New Data', 'am_wp_ajax'), 'primary', '', true, $other_attributes);
      echo '</div>';




      /* <div class="wrap">
      <h2><?php _e('My Plugin Options', 'am_wp_ajax'); ?></h2>
      <?php $other_attributes = array('tabindex' => '1'); ?>
      <?php submit_button(__('Go!', 'textdomain'), 'secondary', '', true, $other_attributes); ?>
    </div> */
    }

    public function get_miusage_data()
    {

      if ('GET' === $_SERVER['REQUEST_METHOD']) { // Check if post method
        if (!check_ajax_referer('am-wp-security-nonce', 'security', false)) {
          wp_send_json_error('Unauthorized Request');
          wp_die();
        }
      }

      $table_response = $this->get_table();

      $data_response = array(
        'type' => "success",
        'data' => $table_response
      );

      echo json_encode($data_response);
      // print_r($data);

      wp_die();

      // echo json_decode(stripslashes($response));

      // $data = json_decode((stripslashes($_GET['data'])));
    }

    public function get_table()
    {
      $response = wp_remote_get('https://miusage.com/v1/challenge/1/');

      $data = (json_decode($response['body'], true));
      $title = $data['title'];
      $headers = $data['data']['headers'];
      $users = $data['data']['rows'];

      $result = "<table>";
      $result .= "<thead>";
      $result .= "<tr>";
      foreach ($headers as $header) {
        $result .= "<th>" . $header  . "</th>";
      }
      $result .= "</tr>";
      $result .= "</thead>";

      $result .= "<tbody>";

      foreach ($users as $user) {
        $result .= "<tr>";
        $result .= "<td>" . $user['id']  . "</td>";
        $result .= "<td>" . $user['fname']  . "</td>";
        $result .= "<td>" . $user['lname']  . "</td>";
        $result .= "<td>" . $user['email']  . "</td>";
        $result .= "<td>" . $user['date']  . "</td>";
        $result .= "</tr>";
      }

      $result .= "</tbody>";
      $result .= "</table>";
      return $result;
    }
  }

endif;

$am_wp_ajax = new AM_WP_AJAX;

// Register hooks for activation, deactivation and uninstall instances.
register_activation_hook(AMWPAJAX_PLUGIN_FILE, $am_wp_ajax->activate());
register_deactivation_hook(AMWPAJAX_PLUGIN_FILE, $am_wp_ajax->deactivate());
register_uninstall_hook(AMWPAJAX_PLUGIN_FILE, $am_wp_ajax->uninstall());
