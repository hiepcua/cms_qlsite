<div class="login-box">
    <div class="login-logo">
        <a href="javascript:void(0);"><?php echo SITE_NAME;?></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <div class='mess text-center red' style='color:red;'></div>
            <form action="#" method="post">
                <div class="input-group mb-3">
                    <input type="email" id='txt_email' class="form-control" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" id='txt_pass' class="form-control" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember"> Remember Me </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="button" id='btn_login' class="btn btn-primary btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <!--<p class="mb-1">
                <a href="<?php echo ROOTHOST;?>forgot-password">I forgot my password</a>
            </p>-->
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<script>
    $(document).ready(function(){
        function login(){
            var checked=$('#remember').is(':checked')?"yes":"no";
            var _url='<?php echo ROOTHOST;?>ajaxs/user/login.php';
            var _data={'user':$('#txt_email').val(),'pass':$('#txt_pass').val(),'remember':checked}
            $.post(_url,_data,function(req){
                if(req=='success') window.location.reload();
                else $('.mess').html(req);
            })
        }
        $('#txt_pass,#txt_email').keyup(function(e){
            var code = (e.keyCode ? e.keyCode : e.which);
            if (code==13) {
                e.preventDefault();
                if($('#txt_email').val()!='' && $('#txt_pass').val()!=''){
                    login();
                }else{
                    $('.mess').html('Username or password is empty!');
                }
            }
        })
        $('#btn_login').click(function(){
            if($('#txt_email').val()!='' && $('#txt_pass').val()!=''){
                login();
            }else{
                $('.mess').html('Username or password is empty!');
            }
        })
    });
</script>
