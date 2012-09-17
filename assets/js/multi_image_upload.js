$(document).ready(function(){
   $('#add_more').click(function(){
      var current_count = $('input[type="file"]').length;
      var next_count = current_count + 1;
      $('#image_upload').prepend('<p><input type="file" name="image_' + next_count + '"</p>');
   });
});