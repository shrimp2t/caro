<?php
include('config.php');

?>
<!DOCTYPE html>
<html>
<head>
    <title> Admin checker </title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/request.js"></script>
    <link rel="stylesheet" href="css/style.css" media="all">
    <script type="text/javascript">
        function run(){
            send_request({
                'game_action': 'admin'
            }, function(respond){

                $('.admin-check-info').html(respond.content);
            });

            setTimeout( run ,2000);
        }
        run();
    </script>
</head>
<body>

<div class="wrap">
    <div class="admin-check-info">

    </div>
</div>

</body>
</html>