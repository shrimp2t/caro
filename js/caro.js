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
var gameTimeOut ;
var playingTimeout;
var game_over =  false;

boardSize=20;
userSq= 1;
machSq=-1;
blinkSq="b-1";
myTurn=false;
winningMove=9999999;



image_path ='images/';
if (document.images) {
    uImg=new Image(16,16); uImg.src=image_path+'s'+userSq+'.png';
    mImg=new Image(16,16); mImg.src=image_path+'/s'+machSq+'.png';
    bImg=new Image(16,16); bImg.src=image_path+'/s0.png';
}

f=new Array();
s=new Array();
q=new Array();
for (i=0;i<20;i++) {
    f[i]=new Array();
    s[i]=new Array();
    q[i]=new Array();
    for (j=0;j<20;j++) {
        f[i][j]=0;
        s[i][j]=0;
        q[i][j]=0;
    }
}

iLastUserMove=0;
jLastUserMove=0;



// hiện thị thông báo của game
function display_game_noti(content, win_or_lose){

    lightbox_open(function(){
        $('.lb_box_inside_c').html('<div class="note">'+content+'</div>');
        $('.lb_box_inside_c').append('<a href="#" class="button lb_close">Chấp nhận</a> ');
        if(typeof (win_or_lose)!='undefined'){
            $('.lb_box_inside_c').addClass(win_or_lose);
        }

        $('.lb_close').click(function(){
            lightbox_close();
            window.location='waiting.php';
            return false;
        });


    });
}





function display_turning(turn){
    if(turn==true){
        $('.turning').html('Lượt đối phương');
    }else{
        $('.turning').html('Lượt bạn');
    }
}


function clk(iMove,jMove) {

    if(game_over){
        return;
    }

    if (myTurn) return;

    if(typeof (f[iMove][jMove])=='undefined'){
          f[iMove][jMove] = 0;
    }

    if (f[iMove][jMove]!=0) {
      // alert('Ô này đã được chọn. Vui lòng chọn ô khác !');
        // ô này đã dc tick vui lòng chọn ô khác
        return;
    }


    f[iMove][jMove]=userSq;
    drawSquare(iMove,jMove,userSq);
    myTurn=true;
    display_turning(myTurn);
    send_request({
        'game_action': 'playing',
        'type': 'move',
        i: iMove,
        j: jMove
    }, function(){

    });

    $( "body" ).trigger({
        type:"user_moved",
        item: [iMove,jMove, userSq ]
    });


    return false;
}



// may tu dong di -- ngươi khác đi
function machineMove() {

    //return false;

    clearTimeout(playingTimeout);

    if(game_over ==  true){

        return;
    }

    send_request({
        'game_action': 'playing',
        'type': 'machine_move'
    }, function(respond){


        if(respond.status=='out'){
            display_game_noti('Đối phương đã thoát !', 'lose');
            game_over =  true;
            clearTimeout(playingTimeout);
            return ;
        }

        if(respond.winner=='you'){
            writeBoard();
           // request_winner();
            display_game_noti('Bạn đã giành chiến thắng !', 'win');
            game_over =  true;
            clearTimeout(playingTimeout);

            return ;
        }else if(respond.winner=='other'){
            writeBoard();
            //request_loser();
            display_game_noti('Bạn đã thua !', 'lose');
            game_over =  true;
            clearTimeout(playingTimeout);
            return ;
        }else if(respond.winner=='no_body'){
            writeBoard();
            display_game_noti('Hòa  -  Hai bạn thật là kỳ phòng dịch thủ !!!', 'lose');
            game_over =  true;
            clearTimeout(playingTimeout);
            return ;
        }


        if(respond.status==1){

            if(respond.turn==GAME_SETTINGS.player_id){
                myTurn = false;
            }else{
                myTurn = true;
            }

            display_turning(myTurn);

            if(respond.turn==GAME_SETTINGS.player_id){
                f[respond.last_pos.i] = new Array();
                f[respond.last_pos.i][respond.last_pos.j]=machSq;

                drawSquare(respond.last_pos.i,respond.last_pos.j,machSq);

            }
        }

        playingTimeout  = setTimeout(machineMove,1000);
    });


}


w=new Array(0,20,17,15.4,14,10);
nPos=new Array();
dirA=new Array();


function displayWin(track){
    return ;
    //console.debug(track);
    if(track.length==0){
        return;
    }
    for(k=0; k<track.length; k++ ){
       ij=  track[k];

       $('.ij-'+ij).addClass('track-win');
    }

}


// ve hinh x hoac o

function drawSquare(par1,par2,par3) {
    $('#s'+par1+'_'+par2+' img').attr('src','images/s'+par3+'.png');
}


buf='';

function writeBoard () {

    buf='';
    for (i=0;i<boardSize;i++) {
        for (j=0;j<boardSize;j++) {

            if(typeof (f[i][j])==='undefined'){
                f[i][j]=0;
            }

           // buf+='\n><a href="#s" onClick="top.clk('+i+','+j+');if(top.ie4)this.blur();return false;" ><img name="s'+i+'_'+j+'" src="images/s'+f[i][j]+'.gif" width=16 height=16 border=0></a';
            buf+= '<a class="i-'+i+' j-'+j+'  ij-'+i+'-'+j+'" href="#s" id="s'+i+'_'+j+'" onclick="clk('+i+','+j+');return false;" ><img  name="s'+i+'_'+j+'" src="'+image_path+'s'+f[i][j]+'.png"></a> ';

        }
        buf+='<div class="clear"></div>';

    }

    $('#caro-canvas').html(buf);

}



function resetGame() {

    for (i=0;i<20;i++) {
        for (j=0;j<20;j++) {
            f[i][j]=0;
        }
    }
    if (document.images) {
       // if (!top.f1.document.s9_9) return;

        for (i=0;i<boardSize;i++) {
            for (j=0;j<boardSize;j++) {

            }
        }
    }
    else writeBoard();
    myTurn=false;

}


function check_ready_paying(){

    send_request({
        'game_action': 'check-player',
        'room_id': GAME_SETTINGS.room_id,
        'get_ready':1
    }, function(respond){

        if(respond.short_msg=='all_ready'){
            writeBoard();
            if(respond.turn==GAME_SETTINGS.player_id){
                myTurn = false;
            }else{
                myTurn = true;
            }


            display_turning(myTurn);
            playingTimeout =  setTimeout(machineMove, 1000);
            clearTimeout(gameTimeOut);
        }else{
            gameTimeOut = setTimeout(check_ready_paying, 1000);
        }
    });


}

function do_ready_playing(){
    $('#caro-canvas').html('');
    // writeBoard();
    check_ready_paying();
}


function init() {
  // writeBoard();
   //  resetGame();
    gameTimeOut = setTimeout(check_ready_paying, 1000);
}


// xác nhận khi rời phòng
/*
window.onbeforeunload = bunload;

function bunload(){
    return 'Thoát Game - Bạn sẽ bị xử thua ! \n Bạn có muốn tiếp tục';
}
*/


function request_winner(){
    send_request({
        'game_action': 'playing',
        'room_id': GAME_SETTINGS.room_id,
        'winner': 1
    }, function(respond){

    });

}


function request_loser(){
    send_request({
        'game_action': 'playing',
        'room_id': GAME_SETTINGS.room_id,
        'loser': 1
    }, function(respond){

    });

}





$(document).ready(function(){
    init();
    // kiểm tra chiến thắng
    // display_game_noti('Bạn đã giành chiến thắng !', 'win');
   // display_game_noti('Bạn đã thua !', 'lose');

    /*
    $('body').on('user_moved',function(item){
        var data =  item.item;
        data[2] = parseInt(data[2]);


        clearTimeout(gameTimeOut);
        clearTimeout(playingTimeout);

        game_over =  true;

    });
    */

    if(typeof(game_tracking)){
        //f = $.parseJSON(game_tracking);
        if(game_tracking.length>0){
            f = game_tracking;
            writeBoard();

        }
    }

    // khi đang chơi mà có ai đó rời phòng
    $('.stop-playing').click(function(){
        var c = confirm('Rời khỏi phòng ? -  Bạn sẽ bị sử thua, Bạn có muốn tiếp tục');
        if(c){
            window.location='rooms.php?action=left-room';
        }
        return false;
    });


});

