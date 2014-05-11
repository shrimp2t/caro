<?php
if($_REQUEST['user_name']!=''){

    $r = login($_REQUEST['user_name'], $_REQUEST['user_pass']);
    if(!$r){
        echo '<div class="error">Tài khoản hoặc mật khẩu không đúng, vui lòng thử lại.</div>';
    }else{
        go_to('rooms.php');
    }
}

?>


<form action="index.php?action=do-login" method="post">
    <div class="item">
        <label>Username</label>
        <input type="text" name="user_name">
    </div>
    <div class="item">
        <label>Mật khẩu</label>
        <input type="password" name="user_pass">
    </div>
    <input type="submit" class="button" value="Đăng nhập" >
</form>

<a href="index.php?action=register" class="">Đăng ký</a><br/>
<a href="index.php?action=lost-password" class="">Quên mật khẩu</a>