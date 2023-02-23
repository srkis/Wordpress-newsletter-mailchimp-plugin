

jQuery(document).ready( function () {

       let params = {};
       params.action = 'get_subscribers';

      jQuery.ajax({
        url: ajaxurl,
        type: 'POST',
        dataType: 'json',

        data:{
          action: params.action,

        },

        success: function( data ){

          populateDataTable(data);
        }
      });

  function populateDataTable(data) {

          var table = jQuery('#myTable').dataTable({

          });

          var row = 1;
          jQuery.each(data, function (index, value) {
              jQuery('#myTable').dataTable().fnAddData( [
                  value.email,
                  value.status,
                  value.first_name,
                  value.last_name,
                ]);
             row++;
          });

      }


    function getBaseURL() {
    var url = location.href;
    var baseURL = url.substring(0, url.indexOf('/', 14));

    if (baseURL.indexOf('http://localhost') != -1) {

        var url = location.href;
        var pathname = location.pathname;
        var index1 = url.indexOf(pathname);
        var index2 = url.indexOf("/", index1 + 1);
        var baseLocalUrl = url.substr(0, index2);

        return baseLocalUrl + "/";
    }
    else {

        return baseURL + "/";
      }
    }


    //Send campaigns
    jQuery("#send_campaign").click( function() {

       let campaignId = jQuery("#campaign_select").val();
       let params = {};
       params.action = 'send_campaign';

     jQuery.ajax({
       url: ajaxurl,
       type: 'POST',
       dataType: 'json',

       data:{
         action: params.action,
         campaignId: campaignId

       },

       success: function( data ){

         jQuery('#show_success').show();
       }
     });

   });


//Get All $campaigns

jQuery.ajax({
  url: ajaxurl,
  type: 'POST',
  dataType: 'json',

  data:{
    action: 'get_all_campaigns',

  },

  success: function( data ){

    var sel = $("#campaign_select");
   sel.empty();
   for (var i=0; i<data.length; i++) {
     sel.append('<option value="' + data[i].campaign_id + '">' + data[i].campaignTitle + '</option>');
   }
  }
});



}); // Document ready
