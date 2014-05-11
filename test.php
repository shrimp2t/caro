<?php

include('config.php');
/*
$row =  $db->get_row(" SELECT * FROM `rooms` WHERE `room_id`= ".$room_id);


echo var_dump(unserialize('a:9:{i:11;a:2:{i:15;i:-1;i:7;i:1;}i:6;a:2:{i:12;i:1;i:9;i:-1;}i:8;a:2:{i:15;i:1;i:5;i:1;}i:13;a:1:{i:12;i:-1;}i:18;a:1:{i:13;i:-1;}i:5;a:2:{i:16;i:-1;i:5;i:-1;}i:1;a:1:{i:14;i:1;}i:2;a:1:{i:5;i:1;}i:14;a:1:{i:6;i:-1;}}'));

*/

$f = unserialize('a:5:{i:7;a:6:{i:9;i:2;i:10;i:1;i:11;i:1;i:12;i:1;i:13;i:1;i:14;i:1;}i:8;a:1:{i:10;i:2;}i:11;a:1:{i:10;i:2;}i:9;a:1:{i:9;i:2;}i:10;a:1:{i:11;i:2;}}');


$check = check_win(7, 14, 1, $f );

var_dump($check);






















