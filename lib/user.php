<?php

/*
 * Su dung session $_SESSION[user]
 */

function is_user_logged_in(){
    return isset($_SESSION['user']);
}

function check_user_pass($password_text, $encode_pass){
    return $password_text === $encode_pass;
}

function do_user_login($data){
    global $db;
    $_SESSION['user'] =  $data;
    $db->update('users', array('status'=>'logged_in'),'user_id='.$_SESSION['user']->user_id);

}

function do_logout(){
    global $db;
    $db->update('users', array('status'=>'logged_out'),'user_id='.$_SESSION['user']->user_id);

    if($_SESSION['room_id']>0){

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



    unset( $_SESSION['user'] );
    unset($_SESSION['room_id'], $_SESSION['player_id']);
}


function login($user_login, $pass){
    global $db;
    $sql =" SELECT  * FROM `users` WHERE user_login = '".mysql_real_escape_string($user_login)."' LIMIT 1 ";

    $row =  $db->get_row($sql);


    if(check_user_pass($pass,$row->user_pass)){
         do_user_login($row);
        return true;
    }else{
        return false;
    }
}


function get_user_by_id($user_id){
    global $db;
    $sql =" SELECT  * FROM `users` WHERE user_id = ".intval($user_id)."  LIMIT 1 ";


    return  $db->get_row($sql);
}

function display_user_name($echo =true){
    $n ='';
    if(trim($_SESSION['user']->user_nice_name)!=''){
        $n = $_SESSION['user']->user_nice_name;
    }else{
        $n = $_SESSION['user']->user_login;
    }

    if($echo){
        echo $n;
    }else{
        return  $n;
    }
}


function update_user($user_id, $data, &$error_msg='', $keep_user_login=  false){
    global $db;
    if(!$keep_user_login){
        unset($data['user_login']);
    }

    if($data['user_pass']!=''){
        if($data['user_pass']!==$data['user_pass2']){
            $error_msg.='<p>Mật khẩu xác nhận không đúng</p>';
            return false;
        }
    }else{
        unset($data['user_pass']);
    }

    if($db->update('users',$data,' user_id = '.$user_id )){
        do_user_login(get_user_by_id($user_id));
        return true;
    }
    return false;
}


function register($user_data, &$error_msg=''){
    global $db;
    $error =0;

    if($user_data['user_login']==''){
        $error_msg .='<p>User name không được để trống.</p>';
        return false;
    }

    $sql =" SELECT  * FROM `users` WHERE user_login = '".mysql_real_escape_string($user_data['user_login'])."' LIMIT 1 ";
    $row =  $db->get_row($sql);

    if($row->user_id>0){
        $error_msg .='<p>Tài khản đã dc đăng ký, vui long chọn username khác</p>';
        $error=1;
        return false;
    }else{
        if($user_data['user_pass']==''){
            $error_msg .='<p>Mật khẩu không được để trống.</p>';
            $error=1;
        }

        if($user_data['user_pass2']==''){
            $error_msg .='<p>Mật khẩu xác nhận không được để trống.</p>';
            $error=1;
        }

        if($user_data['user_pass']!==$user_data['user_pass2']){
            $error_msg .='<p>Mật xác nhận không đúng.</p>';
            $error=1;
        }

    }

    if($error==0){
        return  $db->insert('users',$user_data);
    }

    return false;

}

function lost_password($user_data, &$error_msg=''){
    global $db;
    $error =0;

    if($user_data['user_login']==''){
        $error_msg .='<p>User name KHÔNG hợp lệ.</p>';
        return false;
    }

    $sql =" SELECT  * FROM `users` WHERE user_login = '".mysql_real_escape_string($user_data['user_login'])."' LIMIT 1 ";
    $row =  $db->get_row($sql);

    if($row->user_id>0){
        if($user_data['user_pass']==''){
            $error_msg .='<p>Mật khẩu KHÔNG được để trống.</p>';
            $error=1;

        }

        if($user_data['user_pass2']==''){
            $error_msg .='<p>Mật khẩu xác nhận KHÔNG được để trống.</p>';
            $error=1;

        }

        if($user_data['user_pass']!==$user_data['user_pass2']){
            $error_msg .='<p>Mật xác nhận không đúng.</p>';

        }
    }else{
        $error=1;
        $error_msg .='<p>Tài khoản này không tồn tại.</p>';

    }

    if($error==0){
        unset($user_data['user_login']);
        return  $db->update('users',$user_data,'user_id = '.$row->user_id);
    }

    return false;
}




function user_update_last_log($user_id, $now = true, $unixtime = '' ){
    if(!is_numeric($user_id)){
        return false;
    }

    if($now==true){
        $time = time();
    }else{
        $time = $unixtime;
    }

    global $db;

    $user_data = array('last_log'=>$time,'status'=>'logged_in');
    

    return  $db->update('users',$user_data,'user_id = '.$user_id);

}

