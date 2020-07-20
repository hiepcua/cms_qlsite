<?php
ob_start();
session_start();
ini_set('display_errors',1);
define('incl_path','global/libs/');
define('libs_path','libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
require_once(libs_path."FlashMessages.php");   // Flash message

header('Content-type: text/html; charset=utf-8');
header('Pragma: no-cache');//header('Pragma: public');
header('Expires: '.gmdate('D, d M Y H:i:s',time()+600).' GMT');
header('Cache-Control: max-age=600');
header('User-Cache-Control: max-age=600');
//------------------set cookie for member giới thiệu--------------------------
$req=isset($_GET['req'])?antiData($_GET['req']):'';
if($req!='') setcookie('RES_USER',$req,time() + (86400 * 30), "/");
define('ISHOME',true);
$bodyClass=isLogin()?'sidebar-mini layout-fixed':'login-page'
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo SITE_NAME;?> | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta property="og:url"           content="<?php echo ROOTHOST; ?>" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="<?php echo SITE_NAME;?>" />
    <meta property="og:description"   content="<?php echo SITE_NAME;?>" />
    <meta property="og:image"         content="" />

    <link rel="stylesheet" href="<?php echo ROOTHOST;?>global/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo ROOTHOST;?>global/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo ROOTHOST;?>global/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo ROOTHOST;?>global/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?php echo ROOTHOST;?>global/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="<?php echo ROOTHOST;?>global/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="<?php echo ROOTHOST;?>global/css/bootstrap-datetimepicker.css">
    <link rel="stylesheet" href="<?php echo ROOTHOST;?>global/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?php echo ROOTHOST;?>global/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo ROOTHOST;?>global/plugins/summernote/summernote-bs4.css">
    <link rel="stylesheet" href="<?php echo ROOTHOST;?>global/css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <script src="<?php echo ROOTHOST;?>global/plugins/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    
    <script src="<?php echo ROOTHOST;?>global/plugins/jquery/jquery.min.js"></script>
    <script src="<?php echo ROOTHOST;?>global/plugins/jquery-ui/jquery-ui.min.js"></script>
</head>
<body id="body" class="hold-transition <?php echo $bodyClass;?>">
    <?php
    if(!isLogin()){
        include_once('modules/login.php');
    }else{?>
        <div class="wrapper">
            <!-- Navbar -->
            <?php include_once('modules/main-nav.php');?>
            <!-- /.navbar -->
            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="<?php echo ROOTHOST;?>" class="brand-link">
                    <img src="<?php echo ROOTHOST;?>global/dist/img/AdminLTELogo.png" alt="<?php echo SITE_NAME;?>" class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light"><?php echo SITE_NAME;?></span>
                </a>
                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar user panel (optional) -->
                    <!-- Sidebar Menu -->
                    <?php include_once('modules/left_sidebar.php');?>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <?php 
                $com=isset($_GET['com'])?$_GET['com']:'frontpage';
                include_once('components/com_'.$com.'/layout.php');
                ?>
            </div>
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <strong>Copyright &copy; 2019-<?php echo date('Y');?> <a href="#"><?php echo SITE_NAME;?></a>.</strong>
                All rights reserved.
                <div class="float-right d-none d-sm-inline-block">
                    <b>Version</b> 1.0-pre
                </div>
            </footer>
            <div class="modal fade" id='popup_modal' role="dialog">
                <div class="modal-dialog modal-border ">
                    <div class="modal-content">
                        <div class="modal-body" id="data-frm">
                            <p>One fine body&hellip;</p>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>
        <script>
            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove(); 
                });
            }, 2500);
            /* When the user scrolls down, hide the navbar. When the user scrolls up, show the navbar */
            var prevScrollpos = window.pageYOffset;
            window.onscroll = function() {
                var currentScrollPos = window.pageYOffset;
                if (prevScrollpos > currentScrollPos) {
                    document.getElementById("body").classList.add("layout-navbar-fixed");
                    // document.getElementById("navbar").style.top = "0";
                } else {
                    document.getElementById("body").classList.remove("layout-navbar-fixed");
                    // document.getElementById("navbar").style.top = "-50px";
                }
                prevScrollpos = currentScrollPos;
            }
        </script>
        <!-- Bootstrap 4 -->
        <script src="<?php echo ROOTHOST;?>global/plugins/moment/moment.min.js"></script>
        <script src="<?php echo ROOTHOST;?>global/js/bootstrap-datetimepicker.js"></script>
        <script src="<?php echo ROOTHOST;?>global/js/bootstrap-timepicker.js"></script>
        <script src="<?php echo ROOTHOST;?>global/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="<?php echo ROOTHOST;?>global/plugins/select2/js/select2.full.min.js"></script>
        <script src="<?php echo ROOTHOST;?>global/plugins/daterangepicker/daterangepicker.js"></script>
        <script src="<?php echo ROOTHOST;?>global/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
        <script src="<?php echo ROOTHOST;?>global/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
        <script src="<?php echo ROOTHOST;?>global/plugins/summernote/summernote-bs4.min.js"></script>
        <script src="<?php echo ROOTHOST;?>global/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <?php }?>
    <script src="<?php echo ROOTHOST;?>global/dist/js/adminlte.js"></script>
    <script src="<?php echo ROOTHOST;?>global/js/script.min.js"></script>
</body>
</html>
