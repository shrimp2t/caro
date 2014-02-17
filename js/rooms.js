var Rooms = function(){
    var id_rooms= $('#rooms');

    var rooms_status= {
        'waiting' : 'Đang chờ',
        'playing' :'Đang Chơi',
        'full' :'Đầy'
    }


   var add_room = function(room){
    html = '<div id="room-'+room.room_id+'" data-id="'+room.room_id+'" class="room '+room.status+'">';
        html+= ' <span class="room-id">'+room.room_id+'</span>';
        html+= ' <div class="room-name">'+room.room_name+'</div>';
        html+='<span class="status">'+rooms_status[room.status]+'</span>';
    html+='</div>';
        id_rooms.append(html);
       join_room();
    };

    var update_room = function(room){
        var this_room = $ ('#room-'+room.room_id);
        this_room.remove('waiting playing full');
        this_room.addClass(room.status);
        $('.status',this_room).html(rooms_status[room.status]);
        $('.room-name',this_room).html(rooms_status[room.room_name]);
    }



    var process_rooms = function(rooms){

        // Xóa phòng ko còn tồn tại
        $('.room',id_rooms).each(function(){
            var id = $(this).attr('data-id')  || '0';
            if(typeof (rooms[id])=='undefined'){
                $(this).remove();
            }
        });

        $.each(rooms, function( index, room ) {

            if($('#room-'+room.room_id,id_rooms).length>0){
                update_room(room);
            }else{
                add_room(room);
            }

        });
    };


    var load_rooms= function(){
        send_request(
            {
                'game_action': 'load-rooms'
            },
            function(respond){
                process_rooms(respond);
            });

        setTimeout(load_rooms,1000);
    };


   var join_room = function(){
        $('.room',id_rooms).on('click',function(){
            var r= $(this);
            var room_id =  r.attr('data-id') || 0;

             if(r.hasClass('waiting')){
                send_request({
                   'game_action': 'join-room',
                   'room_id' : room_id
                }, function(respond){

                   if(respond['logged']==-1){
                       alert('Có lỗi sảy ra, vui lòng thử lại');
                   }else if(respond['logged']==0){
                       alert('Phòng đã đầy vui lòng chọn phòng khác.');
                   }else if(respond['logged']==1){
                        //window.location = 'play.php';
                        window.location = 'waiting.php?r='+room_id;
                    }else{
                       alert('Có lỗi sảy ra, vui lòng thử lại');
                   }

                });
             }else{
                 return false;
             }
        });
    }

    this.init = function(){
        load_rooms();
    }

}

$(document).ready(function(){

    var  r = new Rooms();
    r.init();
});