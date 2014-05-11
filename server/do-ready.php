<?php
$row =  $db->get_row(" SELECT * FROM `rooms` WHERE `room_id`= ".intval($_REQUEST['room_id']));

$data = array('player_'.$_SESSION['player_id'].'_stt'=>'R');
$respond = array();

if($row->room_id>0 && $db->update('rooms',$data,' room_id= '.$row->room_id)){
    $respond['ready']=1;
    $respond['short_msg'] = 'im_ready'; // Tôi đã sẵn sàng

}else{
    $respond['ready']=0; // tôi chưa sẵn sàng
}




echo json_encode($respond);
die();
