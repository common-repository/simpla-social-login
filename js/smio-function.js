/*!
 * Copyright (c) 2016 Smart IO Labs
 * Project repository: http://smartiolabs.com
 * license: Is not allowed to use any part of the code.
 */
var $ = jQuery;

$(document).ready(function() {
  $('#smio-submit').click(function(){
    var form = $(this).parents('form');
    if(!validateForm(form))
      return false;
  });
  $('.smio-delete').click(function(event){
    var confirmtxt = $(this).attr("data-confirm");
    if(typeof confirmtxt == "undefined"){
      confirmtxt = "There is no undo for this process. Are you sure ?";
    }
    if (!confirm(confirmtxt)){
      event.preventDefault();
    }
  });
  
  var smioslogin_upload_field; 
  jQuery('.smioslogin_upload_file_btn').click(function() {
    smioslogin_upload_field = jQuery(this).attr('data-container');
    formfield = jQuery('.'+smioslogin_upload_field).attr('name');
    tb_show('', 'media-upload.php?type=image&TB_iframe=1');
    return false;
  });
  window.send_to_editor = function(html) {
    imgurl = jQuery('img', html).attr('src');
    jQuery('.'+smioslogin_upload_field).val(imgurl);
    tb_remove();
  }
});