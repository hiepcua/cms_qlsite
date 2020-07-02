<div class="login-box">
    <div class="login-logo">
        <a href="javascript:void(0);"><?php echo SITE_NAME;?></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <h4 class="login-box-msg">Bạn quyên mật khẩu?</h4>
			<p>Hãy điền email bạn đã đăng ký để lấy lại mật khẩu</p>
            <div id="mess" style="display: none;"></div>
            <form action="#" method="post">
                <div class="input-group mb-3">
                    <input type="email" id='txt_email' class="form-control" placeholder="E-mail">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="button" id='btn_forgot_password' class="btn btn-primary btn-block">Gửi</button>
                    </div>
                    <!-- /.col -->
                    <div class="col-8 text-right">
						<a class='btn' href='<?php echo ROOTHOST;?>'>Đăng nhập!</a>
					</div>
                </div>
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<script>
    $(document).ready(function(){
        function forgot_password(){
            var _url='<?php echo ROOTHOST;?>ajaxs/mem/forgot_password.php';
            var _data={'user':$('#txt_email').val()}
            $.post(_url,_data,function(req){
                if(req=='success'){
                    $(".login-card-body").empty();
                    $(".login-card-body").load("<?php echo ROOTHOST;?>ajaxs/mem/reset_password_success.php");
                }else{
                    $('#mess').text('Error');
                    $('#mess').css('display', 'block');
                } //window.location.reload();
                console.log(req);
            })
        }
        $('#btn_forgot_password').click(function(){
            forgot_password();
        })
    });
</script>