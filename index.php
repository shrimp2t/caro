<?php
include('config.php');
$action=  strtolower($_REQUEST['action']);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Caro game</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script type="text/javascript" src="js/jquery.js"></script>
    <link rel="stylesheet" href="css/style.css" media="all">
</head>
<body>
<div class="wrap">
    <?php
    if(!is_user_logged_in()){
        switch($action){
            case 'lost-password':
                include('templates/lost-password-form.php');
            break;

            case 'register':
                include('templates/register-form.php');
            break;

            default:
                include('templates/login-form.php');
        }
    }else{
        if($action=='update-user'){
            include('templates/register-form.php');
        }else{
            go_to('rooms.php');
        }

    }
    ?>

</div>

</body>
</html>