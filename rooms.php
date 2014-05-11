<?php
include('config.php');
go_home_if_not_logged_in();

// -------  Rời phòng -------

if($_REQUEST['action']=='left-room'){

    $room = get_room_by_id($_SESSION['room_id']);
    if($room->room_id>0){

        if($_SESSION['player_id']==2){ // khách thoát
            $data['player_2'] =null;
            $data['status'] ='waiting';
            $data['tracking'] ='';
            $data['winner'] = $room->player_1;


            $db->update('rooms',$data,' room_id= '.$_SESSION['room_id']);

        }else{ // _SESSION['player_id'] = 1 chủ phòng thoát, Người khác sẽ làm chủ phòng thay thế hoặc xóa phòng
            $data['player_1'] = null;
            $data['status'] ='waiting';
            $data['tracking'] ='';
            $data['winner'] = $room->player_2;
            $db->update('rooms',$data,' room_id= '.$_SESSION['room_id']);
        }
    }

    // kiểm tra xem còn ai ko trong phòng ko nếu ko ở trong phòng thì sẽ xóa phòng nay
    $room = get_room_by_id($room->room_id);
    if($room->player_1<=0 && $room->player_2<=0){
        $db->delete('rooms', array('room_id'=> $room->room_id));
    }

   unset($_SESSION['room_id'], $_SESSION['player_id']);
}


// ------- Hết  Rời phòng ------


//-----Tạo phòng ------

$msg = '';
if($_REQUEST['create_room']!=''){
    $data['player_1'] = $_SESSION['user']->user_id;
    $data['status'] = 'waiting';
    $data['player_1'] = $_SESSION['user']->user_id;

    if(trim($_REQUEST['room_name'])==''){
        $data['room_name']= $settings['default_room_name'];
    }else{
        $data['room_name']= trim($_REQUEST['room_name']);
    }

    $data['turn'] = rand(1,2);
    $data['first_turn'] = $data['turn'];

    if( $db->insert('rooms',$data)){
        $id = $db->get_insert_id();
        $_SESSION['room_id'] = $id;
        $_SESSION['player_id'] = 1;
        go_to('waiting.php?r='.$id);
    }else{
        $msg ='<div class="error"><p>Lỗi tạo phòng, Vui lòng thử lại sau.</p></div>';
    }

}

// -------HẾt mục tạo phòng ---------

// kiểm tra xem user hiện có ở trong room nào ko ?
global $db;

if($_SESSION['room_id']>0){
    $room =  get_room_by_id($_SESSION['room_id']);

    if($room->room_id>0 && ( $_SESSION['user']->user_id==$room->payer_1 || $_SESSION['user']->user_id==$room->payer_2  ) ){
        go_to('waiting.php?r='.$_SESSION['room_id']);
    }else{
        $_SESSION['room_id']='';
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Phòng chơi caro online</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/request.js"></script>
        <script type="text/javascript" src="js/rooms.js"></script>
        <link rel="stylesheet" href="css/style.css" media="all">

    </head>
    <body>
        <div class="wrap">
            <div class="usr-logged-in">
                xin chào: <?php display_user_name(); ?>
                <a target="_blank" href="help.php">Luật chơi</a>
                <a href="index.php?action=logout">Thoát</a>

            </div>

            <div id="rooms">

            </div>

            <div class="divider"></div>
            <?php echo $msg; ?>
            <form class="create-room clearfix" method="post" action="rooms.php">
                <input type="text" class="inp-name" name="room_name" value="" placeholder="<?php echo esc_html($settings['default_room_name']); ?>">
                <input type="submit" value="Tạo phòng" class="button" name="create_room">
            </form>


        </div>

    </body>
</html>