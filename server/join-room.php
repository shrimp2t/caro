<?php

$row =  $db->get_row(" SELECT * FROM `rooms` WHERE `room_id`= ".intval($_REQUEST['room_id']));

$respond= array();
if(empty($row->room_id) || $row->room_id<=0){
    $respond['logged']= -1;
    $respond['status'] = '';
}else{
    $respond['status'] = $row->status;

    if($row->status=='waiting'){
        $respond['logged']= 1;
        if($row->player_2>0){
            $data = array('player_1'=>$_SESSION['user']->user_id, 'status'=>'full');
            $_SESSION['player_id'] = 1;
        }else{
            $data = array('player_2'=>$_SESSION['user']->user_id, 'status'=>'full');
            $_SESSION['player_id'] = 2;
        }

        $db->update('rooms', $data, '`room_id` ='.$row->room_id);
        $_SESSION['room_id'] = $row->room_id;

    }else{
        $respond['logged']= 0;

    }
}



echo json_encode($respond);

