<?php
$row =  $db->get_row(" SELECT * FROM `rooms` WHERE `room_id`= ".intval($_REQUEST['room_id']));
$respond['short_msg']='waiting';

if(empty($row->room_id) || $row->room_id<=0){
    if($_REQUEST['clear_winner']==1){
        $data = array('winner'=>0);
        $db->update('rooms',$data,' room_id= '.$row->room_id);
    }

}



if($_SESSION['player_id']==1){
    $user_2 = get_user_by_id($row->player_2);
    $respond['player_2'] = $user_2->user_nice_name !='' ? $user_2->user_nice_name  :  $user_2->user_login; ;
    $respond['player_1'] = $_SESSION['user']->user_nice_name !='' ? $_SESSION['user']->user_nice_name  :  $_SESSION['user']->user_login ;
}else{
    $user_2 = get_user_by_id($row->player_1);
    $respond['player_2'] = $user_2->user_nice_name !='' ? $user_2->user_nice_name  :  $user_2->user_login; ;
    $respond['player_1'] = $_SESSION['user']->user_nice_name !='' ? $_SESSION['user']->user_nice_name  :  $_SESSION['user']->user_login; ;
}




if($_REQUEST['do']=='ready'){

    $data = array('player_'.$_SESSION['player_id'].'_stt'=>'R');
    $respond = array();

    if($row->room_id>0 && $db->update('rooms',$data,' room_id= '.$row->room_id)){
        $respond['ready']=1;
        if($_SESSION['player_id']==1){
            $respond['short_msg'] = 'player1_ready'; // Tôi đã sẵn sàng
        }else{
            $respond['short_msg'] = 'player2_ready'; // đối phương đã sẵn sàng
        }

    }else{
        $respond['ready']=0; // tôi chưa sẵn sàng
        $respond['short_msg'] = 'player2_ready';
    }



    echo json_encode($respond);
    die();

}

//---------------------------------------------

if(empty($row->room_id) || $row->room_id<=0){
    $respond['status'] = '-1';
    $respond['short_msg']='room_not_exists'; // phòng không tồn tại
}else{
    if($row->player_2>0){


        $respond['status'] = '1';
        $respond['short_msg']='player_2_logged'; // Người chơi khác đã vào phòng
        $respond['debug'] ='';

        if($row->player_2_stt=='R' && $row->player_1_stt=='R'){ // ready

            $respond['status'] = 3;
            $respond['ready']=1;
            $respond['short_msg'] = 'all_ready'; // cả 2 người đã sẵn sàng
            //$data = array('tracking'=>'');
            $respond['turn']= $row->turn;
            $db->update('rooms',$data,' room_id= '.$row->room_id);

        }elseif($row->player_1_stt=='R' && $row->player_2_stt!=='R'){

            $respond['status'] = 'player1_ready_player2_not_ready';
            if($_SESSION['player_id']==2){
                $respond['short_msg']='player_2_logged';
                $respond['debug'] ='';
            }else{
                $respond['short_msg']='waiting_player2_ready';
                $respond['debug'] ='';
            }

        }elseif($row->player_2_stt=='R' && $row->player_1_stt!=='R'){

            $respond['status'] = 'player2_ready_player1_not_ready';
            $respond['short_msg']='waiting_player1_ready';

        }

    }else{
        $respond['status'] = '0';
        $respond['short_msg']='waiting_player2_join';
    }
}

echo json_encode($respond);
die();


