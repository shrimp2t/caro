<?php
global $db;

$list_users = $db->get_rows("SELECT * FROM  `users` ORDER BY `last_admin_check`   ASC  LIMIT 0, 10");
$now = time();


$db->query('DELETE FROM `rooms`  WHERE player_1 = 0 AND player_2 = 0 ');

foreach($list_users as  $u){
     if($now - $u->last_log >= AUTO_LOGOUT_TIMEOUT){
         $db->update('users', array('last_admin_check'=>$now,'status'=>'logged_out'), 'user_id='.$u->user_id);
         $db->update('rooms', array('player_1'=>0,'player_1_stt'=>''), 'player_1='.$u->user_id);
         $db->update('rooms', array('player_2'=>0,'player_2_stt'=>''), 'player_2='.$u->user_id);

     }else{
         $db->update('users', array('last_admin_check'=>$now), 'user_id='.$u->user_id);
     }

   // $r['content'].= "<p>".($now - $u->last_log)."</p>";
}



$r['content'] .= 'Last check: '.date('Y-m-d H:i:s').'<br/>';

echo json_encode($r);


