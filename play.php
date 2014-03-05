<?php
include('config.php');
go_home_if_not_logged_in();

$room =  get_room_by_id($_SESSION['room_id']);
$data = array();


$data['winner']=0;
if($db->update('rooms',$data,' room_id = '.$room->room_id) ){

}


if($room->room_id<=0){
    go_to('rooms.php');
}elseif($room->player_1_stt!='R' || $room->player_2_stt!='R'){
    go_to('waiting.php?r='.$_SESSION['room_id']);
}


$game_settings = array(
    'room_id'=> $_SESSION['room_id'],
    'player_id' =>  $_SESSION['player_id']
);

if($_SESSION['player_id']==1){
    $user_2 = get_user_by_id($room->player_2);
}else{
    $user_2 = get_user_by_id($room->player_1);
}


?>
<!DOCTYPE html>
<html>
    <head>
        <title>Caro</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/request.js"></script>
        <script type="text/javascript">
            var GAME_SETTINGS = <?php echo json_encode($game_settings); ?>;
            var game_tracking =  new Array();
            var game_last_turn = <?php echo $room->turn; ?>;
            var game_last_pos=  <?php echo json_encode(explode('-',$room->last_pos)); ?>;
            <?php
             $tracking = unserialize(stripslashes($room->tracking));
              if(is_array($tracking)){


              for($i=0; $i<20; $i++){
                  echo " game_tracking[$i] = new Array() ; \n ";
                  for($j=0; $j<20; $j++){
                        echo " game_tracking[$i][$j]  =  0 ; \n ";
                  }
              }

               echo "\n\n // ------ Restart positions ------ \n\n ";

                if($_SESSION['player_id']==2 && $room->turn==$_SESSION['player_id']){
                    //$last =  array_pop($tracking);
                }


                foreach($tracking as $i => $ar){
                     foreach($ar as $j => $u){
                     $uv = $u == $_SESSION['player_id'] ?  1 : -1;
                         echo " game_tracking[$i][$j] =  $uv ; \n ";
                     }
                }
             }

             ?>
        </script>
        <script type="text/javascript" src="js/caro.js"></script>
        <link rel="stylesheet" href="css/style.css" media="all">
    </head>
    <body>
        <div class="wrap">

            <div class="toolbar">
                <a href="" class="stop-playing">Rời phòng</a>
                <a href="index.php?action=logout">Thoát Game</a>
            </div>

            <div class="clock turning">

            </div>
            <div id="caro-canvas"></div>
            <div class="users-info">
                <div class="user-1 user"><?php echo display_user_name(); ?></div>
                <div class="sp">vs</div>
                <div class="user-2 user"><?php echo $user_2->user_nice_name !='' ? $user_2->user_nice_name  :  $user_2->user_login; ?></div>
            </div>
            <div>Bạn đi quân <img src="images/s1.png"> Đối phương quân <img src="images/s-1.png"> </div>
        </div>
        

    </body>
</html>