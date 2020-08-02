jQuery(document).ready(function ($) {
  jQuery('#get-ajax-data').on('click', function () {

    jQuery.ajax({
      type: "get",
      contentType: "application/json",
      dataType: 'json',
      url: ajax_initialize_script.ajax_url,
      data: { action: "get_miusage_data", security: ajax_initialize_script.security },
      success: function (response) {
        if (response.type == "success") {
          jQuery(".show-content").html(response.data);
        }
        else {
          // handle condition
          // console.log(response);
        }
      },
      error: function (error) {
        // handle condition
        // console.log(error);
      }
    });
  });

});