<?php

namespace AmWPAjax\Admin;

use AmWPAjax\Admin\Get_Data;

if (!class_exists('Options_Page')) :

  class Options_Page
  {
    public function display_plugin_content()
    { ?>
      <?php

      // Get users data.
      $users_data =  (new Get_Data())->display_table();
      $title =  (new Get_Data())->get_title();
      ?>

      <div class="wrap">
        <h1><?php esc_html(__('AM WP AJAX', 'am_wp_ajax')); ?></h1>
        <?php settings_fields('am_wp_ajax_options_group'); ?>
        <?php do_settings_sections('am-wp-ajax-settings'); ?>
        <h2><?php esc_html_e('Users Table', 'am_wp_ajax'); ?></h2>
        <table class="form-table">
          <tr valign="top">
            <th scope="row"><?php echo esc_html__($title, 'am_wp_ajax'); ?></th>
            <td class="show-content">
              <?php
              echo $users_data; // use wp_kses.
              ?>
            </td>
          </tr>
        </table>
        <?php
        $other_attributes = array('id' => 'get-ajax-data');
        submit_button(__('Refresh Data', 'am_wp_ajax'), 'primary', '', true, $other_attributes);
        ?>
      </div>

<?php
    }
  }

endif;
