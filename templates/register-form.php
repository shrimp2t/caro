<?php

if(!is_user_logged_in()){
    if(isset($_POST['reg'])){
        $error='';
        $r = register($_POST['reg'],$error );

        if(!$r){
            echo '<div class="error">'.$error.'</div>';
        }else{
            go_to('index.php');
        }
    }

}else{
    if(isset($_POST['reg'])){
       $r=  update_user($_SESSION['user']->user_id, $_POST['reg']);
        if($r){
            echo '<div class="success">Cập nhận thành công.</div>';
        }else{
            echo '<div class=error">'.$r.'</div>';
        }
    }
}



?>
<form action="index.php?action=<?php echo is_user_logged_in() ? 'update-user' : 'register'; ?>" method="post">
    <div class="item">
        <label>Username</label>
        <input type="text" name="reg[user_login]" autocomplete="off" value="<?php echo is_user_logged_in() ?  $_SESSION['user']->user_login : ''; ?>" <?php echo is_user_logged_in() ? 'readonly="readonly"' : ''; ?>>
    </div>
    <div class="item">
        <label>Mật khẩu</label>
        <input type="password" autocomplete="off" name="reg[user_pass]">
    </div>
    <div class="item">
        <label>Xác nhận lại Mật khẩu</label>
        <input type="password" autocomplete="off" name="reg[user_pass2]">
    </div>

    <div class="item">
        <label>Tên bạn</label>
        <input type="text" name="reg[user_nice_name]">
    </div>
    <input type="submit" class="button" value="<?php echo is_user_logged_in() ? 'Cập nhật' : 'Đăng ký'; ?>" >
    <input type="hidden" name="is_update" value="<?php is_user_logged_in() ? 1 : 0; ?>">
</form>

<a href="index.php" class="">Đăng nhập</a><br/>
<a href="index.php?action=lost-password" class="">Quên mật khẩu</a>