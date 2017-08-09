$(document).ready(function() {
    $('#question-form-ID').submit(function(){
        var form_data = $(this).serialize();
        $.ajax({
            method: "post",
            url: "index.php?route=module/faq/sendQuestion",
            data: form_data,
            dataType: 'json',
            success: function(json) {
                if(json['error']) {
                    $.each(json['error'], function (key, text_error) {
                        if(!$('*[name=\''+key+'\']').hasClass('has-error')) {
                            $('*[name=\'' + key + '\']').addClass('has-error');
                            $('*[name=\'' + key + '\']').after('<div class="text-danger">' + text_error + '</div>');
                        }
                    });
                } else if(json['success']) {
                    $('#question-form-ID .form-control').each(function(el) {
                       $(this).val('');
                    });
                    $('#question-form-ID button').before('<div class="text-success">'+json["success"]+'</div>');
                    setTimeout(function() {
                        $('.text-success').fadeOut();
                    }, 7000);
                    setTimeout(function() {
                        $('.text-success').remove();
                    }, 9000);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
        return false;
    });

    $('body').on('focus', '.has-error', function() {
        $(this).removeClass('has-error');
        $(this).next().remove();
    });

});