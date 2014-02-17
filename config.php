<?php

// Thong tin Database
define('DB_USER','root');
define('DB_PASS','root');
define('DB_NAME','caro');
define('DB_HOST','localhost');

define('SERVER_DIR','server/');

define('AUTO_LOGOUT_TIMEOUT',180 );// Tự động đăng xuất nếu thoát khỏi trình duyệt quá 180 - đơn vị là giây


$settings = array(
    'default_room_name'=>'Hãy vào chơi cùng tôi !!!!',
);


session_start();
ob_start();

include('lib/functions.php');
include('lib/db.php');

$db =  new db(DB_HOST,DB_USER,DB_PASS,DB_NAME);

include('lib/user.php');



if(is_user_logged_in()){
    user_update_last_log($_SESSION['user']->user_id);
}

if($_REQUEST['action']=='logout'){
    do_logout();
    go_to('index.php');
}


