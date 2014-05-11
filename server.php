<?php
include('config.php');

$game_action =  $_REQUEST['game_action'];

if($game_action!='' && is_file(SERVER_DIR.$game_action.'.php')){
    include SERVER_DIR.$game_action.'.php';
    die();
}
