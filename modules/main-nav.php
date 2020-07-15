<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?php echo ROOTHOST;?>" class="nav-link">Bảng điều khiển</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?php echo ROOTHOST;?>contact" class="nav-link">Liên hệ</a>
        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="fas fa-user"></i> <?php echo getInfo('fullname');?> <i class="fa fa-caret-down" aria-hidden="true"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="<?php echo ROOTHOST;?>profile" class="dropdown-item">
                    <i class="fas fa-id-card mr-2"></i>Hồ sơ cá nhân
                </a>
                <a href="<?php echo ROOTHOST;?>changepass" class="dropdown-item">
                    <i class="fa fa-key mr-2" aria-hidden="true"></i></i>Đổi mật khẩu
                </a>
                <a href="javascript:void(0);" class="dropdown-item logout">
                    <i class="nav-icon fas fa-sign-out-alt mr-2"></i></i>Đăng xuất
                </a>
            </div>
        </li>
    </ul>
</nav>