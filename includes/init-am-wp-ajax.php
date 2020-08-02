<?php

register_activation_hook(__FILE__, 'am_wp_ajax_activate');
register_deactivation_hook(__FILE__, 'am_wp_ajax_deactivate');
register_uninstall_hook(__FILE__, 'am_wp_ajax_uninstall');


function am_wp_ajax_activate()
{
  function am_wp_ajax_register_options_page()
  {
    add_options_page(__('AM WP AJAX', 'am_wp_ajax'), __('AM WP AJAX Options', 'am_wp_ajax'), 'manage_options', 'am-wp-ajax', 'am_wp_ajax_options_page');
  }
  add_action('admin_menu', 'am_wp_ajax_register_options_page');

  function am_wp_ajax_register_settings()
  {
    register_setting('am_wp_ajax_options_group', 'am_wp_ajax_option_name');
  }
  add_action('admin_init', 'am_wp_ajax_register_settings');
}
