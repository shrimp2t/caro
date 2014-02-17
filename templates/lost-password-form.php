<?php
if(isset($_POST['l'])){
    $error='';
    $r = lost_password($_POST['l'],$error );

    if(!$r){
        echo '<div class="error">'.$error.'</div>';
    }else{
        echo '<div class="success">Mật khẩu của bạn đã đổi thành công.</div>';
    }
}



?>
<form action="index.php?action=lost-password" method="post">
    <div class="item">
        <label>Username</label>
        <input type="text" name="l[user_login]">
    </div>
    <div class="item">
        <label>Mật khẩu mới</label>
        <input type="password" name="l[user_pass]">
    </div>
    <div class="item">
        <label>Xác nhận Mật khẩu mới</label>
        <input type="password" name="l[user_pass2]">
    </div>
    <input type="submit" class="button" value="Đổi mật khẩu" >
</form>

<a href="index.php" class="">Đăng Nhập</a><br/>
<a href="index.php?action=register" class="">Đăng ký</a>