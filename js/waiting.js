
/**
 gameStatus:
 -1: Unknown
 0: Waiting user join room
 1: Logged in room
 2: Waiting confirm
 3: Playing
 4: Paused
 5: Stopped.
 6: Left room

 */
var gameStatus= 0;
var gameTimeOut;


function start_game(){
    $('.start-game').click(function(){
        $(this).remove();
        send_request({
            'game_action': 'check-player',
            'room_id': GAME_SETTINGS.room_id,
            'do': 'ready'
        }, function(respond){
            if(respond.ready==1){

            }else{
                alert('Có lỗi xảy ra. vui lòng trở lại chọn phòng.')
            }

        });
    });
}




function check_players_stt(){
    send_request({
        'game_action': 'check-player',
        'room_id': GAME_SETTINGS.room_id,
        'confirm': 0
    }, function(respond){

       if(respond.player_2!=null && respond.player_2!=''){
           $('.user-2').html(respond.player_2);
       }else{
           $('.user-2').html('( Đang chờ... )');
       }

        switch(respond.short_msg){
            case 'room_not_exists':
                window.location ='rooms.php';
             break;

            case 'waiting':

            break;

            case 'waiting_player2_join':

            break;

            case 'player_2_logged':
                $('#caro-canvas').html('<div class="loading"><div class="loading-content"><button class="button start-game">Bắt đầu</button></div>');
                start_game();
            break;

            case 'waiting_player2_ready':  case 'waiting_player1_ready':

                    $('#caro-canvas').html('<div class="loading"><div class="loading-content"><span class="icon"></span>Đang chờ người chơi  bắt đầu...</div></div>');

                    if(GAME_SETTINGS.player_id==1){
                        if(respond.status=='player1_ready_player2_not_ready'){

                        }else if( respond.status == 'player2_ready_player1_not_ready'){
                            $('#caro-canvas').html('<div class="loading"><div class="loading-content"><button class="button start-game">Bắt đầu</button></div>');
                            start_game();
                        }

                    }else{
                        if(respond.status=='player1_ready_player2_not_ready'){
                            $('#caro-canvas').html('<div class="loading"><div class="loading-content"><button class="button start-game">Bắt đầu</button></div>');
                            start_game();
                        }else if( respond.status == 'player2_ready_player1_not_ready'){

                        }
                    }



            break;

            case 'play1_ready':

            break;

            case 'play2_ready':

            break;

            case 'all_ready': // tất cả đã sẵn sàng chuyển đến trang chơi game
                clearTimeout(gameTimeOut);

                send_request({
                    'game_action': 'check-player',
                    'room_id': GAME_SETTINGS.room_id,
                    'clear_winner': 1
                },function(){
                    window.location ='play.php';
                });


            break;


        }

    });

   gameTimeOut =  setTimeout(check_players_stt, 1000);

}



function waiting_game(){
    $('#caro-canvas').html('<div class="loading"><div class="loading-content"><span class="icon"></span>Đang chờ người chơi tham gia...</div></div>');
    // preparing_game();
    check_players_stt();

}

$(document).ready(function(){



    if(gameStatus==0){
        waiting_game();
    }

});
