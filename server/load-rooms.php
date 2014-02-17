<?php

$rooms = $db->get_rows('SELECT * FROM `rooms` WHERE 1 ');
$new_rooms=  array();
foreach($rooms as $r){
    if($r->status==''){
        $r->status='waiting';
    }
    $new_rooms[$r->room_id]=  $r;
}

echo json_encode($new_rooms);