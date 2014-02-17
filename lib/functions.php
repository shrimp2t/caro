<?php
function go_to($link){
    header("Location: $link");
    die();
}

function go_home_if_not_logged_in(){
    if(!is_user_logged_in()){
        go_to('index.php');
        die();
    }
}


function _s($s){
    return stripcslashes($s);
}

function esc_html($s){
    return htmlspecialchars(stripcslashes($s));
}

//===============================================================
// gaming function

function get_room_by_id($room_id){
    global $db;
    $row =  $db->get_row(" SELECT * FROM `rooms` WHERE `room_id`= ".intval($room_id));
    return $row;
}





