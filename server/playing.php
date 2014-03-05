<?php

$room_id = $_SESSION['room_id'];
$user_id =  $_SESSION['user']->user_id;
$payer_id=  $_SESSION['player_id'];
$i =  intval($_REQUEST['i']);
$j =  intval($_REQUEST['j']);
$type = $_REQUEST['type'];

user_update_last_log($user_id);

$row =  $db->get_row(" SELECT * FROM `rooms` WHERE `room_id`= ".$room_id);

if($_REQUEST['winner']==1 ||  $_REQUEST['loser']==1){

    $data = array();
    if($_REQUEST['winner']==1){
        $data['winner'] = $payer_id;
    }else{
        $data['winner'] = $payer_id ==1  ? $row->player_2 : $row->player_1;
    }


    // xóa data cũ đi
    $data['tracking'] = '';
    $data['turn'] = rand(1,2);
    $data['last_pos'] = '';
    $data['player_1_stt'] = '';
    $data['player_2_stt'] = '';

    if($db->update('rooms',$data,' room_id = '.$room_id) ){
        //echo 'tracked';
    }
    die();
}


if($type=='move'){
    $data = array();
    if($row->tracking==''){
        $data['tracking'] = array();
        $data['tracking'][$i][$j]= $payer_id;
    }else{
        $row->tracking =  stripslashes($row->tracking);
        $data['tracking'] = unserialize($row->tracking);
        $data['tracking'][$i][$j] = $payer_id;
    }

    $data['tracking'] = serialize($data['tracking']);


    if($row->turn==1){
        $data['turn']=2;
    }else{
        $data['turn']=1;
    }

    //$data['player_'.$data['turn'].'_stt'] = '';

    $data['last_pos']= "$i-$j";

    if($db->update('rooms',$data,' room_id = '.$room_id) ){
        echo 'tracked';
    }

}elseif($type=='machine_move'){

    $row =  $db->get_row(" SELECT * FROM `rooms` WHERE `room_id`= ".$room_id);
    $respond = array();
    if($row->last_pos==''){
        $respond['status']==0 ; // no last post
    }else{
        $respond['status']=1;
    }

    $respond['winner'] ='';

    $pos = explode('-', $row->last_pos);
    $respond['last_pos']  = array('i'=>$pos[0], 'j'=>$pos[1]);
    $respond['turn']= $row->turn;


    if($row->winner>0){
        if($row->winner==$user_id){
            $respond['winner'] =  'you';
        }else{
            $respond['winner'] =  'other';
        }
    }



    echo json_encode($respond);
    die();


}
