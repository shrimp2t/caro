<?php

$room_id = $_SESSION['room_id'];
$user_id =  $_SESSION['user']->user_id;
$payer_id=  $_SESSION['player_id'];
$i =  intval($_REQUEST['i']);
$j =  intval($_REQUEST['j']);
$type = $_REQUEST['type'];

// user_update_last_log($user_id);

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

    $check = check_win($i, $j, $payer_id, $data['tracking'] );

    if($check){
        $data['winner'] = $user_id;
        $data['player_1_stt']='';
        $data['player_2_stt']='';
    }else{
        if(count($data['tracking'])>=400){ //  20x20
            $data['player_1_stt']='';
            $data['player_2_stt']='';
            $data['winner']=-2;
        }
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
        echo 'tracked '.$payer_id.'---'.($check ? ' WIN' : 'NO' );
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
    }elseif($data['winner']==-2){
        $respond['winner'] =  'no_body'; // không ai thắng cả do đã hết chỗ đánh
    }

    if($row->player_1<=0 || $row->player_2<=0){
        $respond['status'] =  'out';
    }else{

        $ids  = array();
        if($row->player_1>0){
            $ids[] = $row->player_1;
        }

        if($row->player_2>0){
            $ids[] = $row->player_2;
        }

        if(!empty($ids)){
            $players =  $db ->get_rows("SELECT * FROM `users` WHERE user_id IN (".join(',',$ids).")");
            foreach($players as  $p){
                if($p->status=='logged_out'){
                    $respond['status'] =  'out';
                    if($db->update('rooms',array('player_1_stt'=>'', 'player_2_stt'=>'','status'=>''),' room_id = '.$room_id) ){
                        echo 'tracked';
                    }
                }
            }
        }

    }



    echo json_encode($respond);
    die();


}
