/* Blog title feedback*/
$(document).ready(function(){
    var text_max = 255;
    $('#blog_title_feedback').html(text_max + ' characters remaining');

    $('#blog_title').keyup(function(){
        var text_length = $('#blog_title').val().length;
        var text_remaining = text_max - text_length;

        $('#blog_title_feedback').html(text_remaining + ' characters remaining');
    });
});


/* album title feedback*/
$(document).ready(function(){
    var text_max = 55;
    $('#album_name_feedback').html(text_max + ' characters remaining');

    $('#album_name').keyup(function(){
        var text_length = $('#album_name').val().length;
        var text_remaining = text_max - text_length;

        $('#album_name_feedback').html(text_remaining + ' characters remaining');
    });
});

/* album description feedback*/
$(document).ready(function(){
    var text_max = 255;
    $('#album_description_feedback').html(text_max + ' characters remaining');

    $('#album_description').keyup(function(){
        var text_length = $('#album_description').val().length;
        var text_remaining = text_max - text_length;

        $('#album_description_feedback').html(text_remaining + ' characters remaining');
    });
});

/* Status update feedback*/
$(document).ready(function(){
    var text_max = 200;
    $('#status_update_feedback').html(text_max + ' characters remaining');

    $('#status_update').keyup(function(){
        var text_length = $('#status_update').val().length;
        var text_remaining = text_max - text_length;

        $('#status_update_feedback').html(text_remaining + ' characters remaining');
    });
});