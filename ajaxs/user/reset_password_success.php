<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
?>
<p class="login-box-msg" style="font-size: 16px;">Reset Your Password</p>
<div>We have sent a reset password to your email. Please login your email get new password.</div>
<br/>
<div>Please check your spam folder if you do not receive it.</div>
<div class="row">
    <!-- /.col -->
    <div class="col-4">
        <a href="<?php echo ROOTHOST;?>" class="btn btn-primary btn-block">Login</a>
    </div>
    <!-- /.col -->
    <div class="col-4"></div>
</div>