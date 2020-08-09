<?php

namespace AmWPAjax\Admin;

if (!class_exists('Get_Data')) :

  class Get_Data
  {

    /**
     * Data endpoint.
     *
     * @var Endpoint $endpoint
     * @since   1.0.0
     */
    protected $endpoint = 'https://miusage.com/v1/challenge/1/';

    public function display_table()
    {
      // Get any existing copy of our transient data.
      if (false === ($response = get_transient('am_wp_ajax_miusage_data'))) {
        // Transient expired, refresh the data.
        $response = wp_remote_get($this->endpoint);
        $this->http_code = wp_remote_retrieve_response_code($response);
        if ($this->http_code == 200) :
          set_transient('am_wp_ajax_miusage_data', $response, 60 * 60);
        endif;
      }

      // same can be done by wp_remote_retrieve_response_code.
      if ($response['response']['code'] == 200) : // if response is OK.

        // $data = (json_decode($response['body'], true));
        $data = (json_decode(wp_remote_retrieve_body($response), true));

        $headers = $data['data']['headers'];
        $users   = $data['data']['rows'];

        // Setup display data.
        $result  = '<table class="form-table am-wp-ajax-table">';
        $result .= '<thead>';
        $result .= '<tr valign="top">';
        foreach ($headers as $header) {
          $result .= '<th>' . esc_attr($header) . '</th>';
        }
        $result .= '</tr>';
        $result .= '</thead>';
        $result .= '<tbody>';

        /**
         * Sort result based on ID
         *
         * @param int $a
         * @param int $b
         * @return int greater number
         */
        function cmp($a, $b)
        {
          if ($a == $b) {
            return 0;
          }
          return ($a < $b) ? -1 : 1;
        }
        // namespace added for cmp function
        usort($users, 'AmWPAjax\Admin\cmp');
        foreach ($users as $user) {
          $result .= '<tr valign="top">';
          $result .= '<td>' . esc_attr($user['id']) . '</td>';
          $result .= '<td>' . esc_attr($user['fname']) . '</td>';
          $result .= '<td>' . esc_attr($user['lname']) . '</td>';
          $result .= '<td>' . esc_attr($user['email']) . '</td>';
          $result .= '<td>' . date_i18n('F d, Y', $user['date']) . '</td>';
          $result .= '</tr>';
        }

        $result .= '</tbody>';
        $result .= '</table>';
        return $result;

      endif;
    }

    /**
     * Get title value from the given endpoint
     *
     * @since   1.0.0
     *
     * @return string title
     */
    public function get_title()
    {
      $response = get_transient('am_wp_ajax_miusage_data');
      if ($response) {
        return json_decode(wp_remote_retrieve_body($response), true)['title'];
      }
    }
  }

endif;
