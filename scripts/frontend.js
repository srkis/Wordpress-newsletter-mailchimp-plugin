
$("#show_unsubscribe_form").on('click', function (e) {

  $("#parse_form").css('display', 'none');
  $("#unsubscribe_form").css('display', 'block');

});

$("#show_subscribe_form").on('click', function (e) {

  $("#parse_form").css('display', 'block');
  $("#unsubscribe_form").css('display', 'none');
});



$("#unsubscribe_form").on('submit', function (e) {
   //stop form submission
   e.preventDefault();

   let subscirberEmail =  $('#unsubscribe_email').val();

   $.ajax({
     url: getBaseURL()+"/wp-admin/admin-ajax.php",
     type: 'POST',
     dataType: 'json',

     data:{
       action: 'unsubscribe',
       subscirberEmail: subscirberEmail

     },

     success: function( data ){

       $('#show_unsubscribe_msg').html(data.msg);
       $('#show_unsubscribe_msg').show().delay(4000).fadeOut('slow');
       $('#unsubscribe_email').val('');

       $("#unsubscribe_form").delay(200).fadeOut('slow');
       $("#parse_form").delay(700).fadeIn();

     }
   });

});


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
