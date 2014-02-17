<?php
include('config.php');
go_home_if_not_logged_in();

if($_SESSION['room_id']>0){
    $room =  get_room_by_id($_SESSION['room_id']);
    if($room->room_id<=0){
        go_to('rooms.php');
    }

}

$game_settings = array(
    'room_id'=> $_SESSION['room_id'],
    'player_id' =>  $_SESSION['player_id']
);

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Chờ bạn chơi ....</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/request.js"></script>
        <script type="text/javascript">
            var GAME_SETTINGS = <?php echo json_encode($game_settings); ?>;
        </script>
        <script type="text/javascript" src="js/waiting.js"></script>
        <link rel="stylesheet" href="css/style.css" media="all">
    </head>
    <body>
        <div class="wrap">
            <div class="toolbar">
                <a href="rooms.php?action=left-room">Rời phòng</a>
                <a href="index.php?action=logout">Thoát Game</a>
            </div>
            <div class="clock turning">

            </div>
            <div id="caro-canvas"></div>
            <div class="users-info">
                <div class="user-1 user"><?php echo display_user_name(); ?></div>
                <div class="sp">vs</div>
                <div class="user-2 user">(Đang chờ...)</div>
            </div>
            <div>Bạn đi quân <img src="images/s1.png"> Đối phương quân <img src="images/s-1.png"> </div>
        </div>
        

    </body>
</html>