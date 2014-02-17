
/*
* Gui yêu cầu cho sever
* */
function send_request(data,success_cb, error_cb){
    $.ajax({
        type: "POST",
        url: "server.php",
        data: data,
        dataType: 'json',
        cache: false,
        success: function(respond){
            if(typeof (success_cb)==='function'){
                success_cb(respond);
            }
        },
        error: function(e){
            if(typeof (error_cb)==='function'){
                error_cb(e);
            }
        }
    });

}


/// lightbox
function lightbox_open(call_back){
    $('body').append('<div class="lb_overlay"></div>');
    $('body').append('<div class="lb_content"><div class="lb_box_inside"><div class="lb_box_inside_c"></div></div></div>');
    if(typeof (call_back)=='function'){
        call_back();
    }
}

function lightbox_close(){
    $('.lb_overlay, .lb_content').remove();
}


/*
$(document).ready(function(){
    lightbox_open(function(){
        $('.lb_box_inside_c').html('<a class="button">Chấp nhận</a> ');
    });

});

*/
