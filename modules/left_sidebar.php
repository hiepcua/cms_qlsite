<?php
$isAdmin=getInfo('isadmin');
$userLogin = getInfo('username');
$permis = $_SESSION['G_PERMISSION_USER']; // global permission "modules/get-permission-user.php"

$n_content0 = SysCount('tbl_content', 'AND status=0 AND author="'.$userLogin.'"');
$n_content1 = SysCount('tbl_content', 'AND status=1 AND author="'.$userLogin.'"');
$n_content2 = SysCount('tbl_content', 'AND status=2 AND author="'.$userLogin.'"');
$n_content3 = SysCount('tbl_content', 'AND status=3 AND author="'.$userLogin.'"');
$n_content4 = SysCount('tbl_content', 'AND status=4 AND author="'.$userLogin.'"');
$n_content5 = SysCount('tbl_content', 'AND status=5 AND author="'.$userLogin.'"');

?>
<style type="text/css">
	.nav-sidebar>.nav-item .nav-icon.fa, .nav-sidebar>.nav-item .nav-icon.fab, .nav-sidebar>.nav-item .nav-icon.far, .nav-sidebar>.nav-item .nav-icon.fas, .nav-sidebar>.nav-item .nav-icon.glyphicon, .nav-sidebar>.nav-item .nav-icon.ion{font-size: 1rem;}
</style>
<nav class="mt-2 pb-5">
	<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
		<li class="nav-item menu-open">
			<a href="<?php echo ROOTHOST;?>content" class="nav-link <?php activeMenu('content', '', 'com');?>">
				<i class="nav-icon far fa-calendar-alt"></i>
				<p>Bài viết <i class="right fas fa-angle-left"></i></p>
			</a>
			<ul class="nav nav-treeview">
				<?php if(in_array('1001', $permis)){ ?>
					<li class="nav-item">
						<a href="<?php echo ROOTHOST;?>content/add" class="nav-link <?php activeMenu('content','add','viewtype');?>">
							<i class="far fa-circle nav-icon"></i>
							<p>Thêm mới</p>
						</a>
					</li>
				<?php } ?>
				<?php if(in_array('1002', $permis)){ ?>
					<li class="nav-item">
						<a href="<?php echo ROOTHOST;?>content/write?status=0" class="nav-link <?php activeMenu('content','write','viewtype'); activeVodMenuByStatus(0);?>">
							<i class="far fa-circle nav-icon"></i>
							<p>Đang biên tập <span class="badge badge-info right"><?php echo $n_content0;?></span></p>
						</a>
					</li>
				<?php } ?>
				<?php if(in_array('1003', $permis)){ ?>
					<li class="nav-item">
						<a href="<?php echo ROOTHOST;?>content/pending?status=1" class="nav-link <?php activeMenu('content','pending','viewtype'); activeVodMenuByStatus(1);?>">
							<i class="far fa-circle nav-icon"></i>
							<p>Chờ duyệt <span class="badge badge-info right"><?php echo $n_content1;?></span></p>
						</a>
					</li>
				<?php } ?>
				<?php if(in_array('1004', $permis)){ ?>
					<li class="nav-item">
						<a href="<?php echo ROOTHOST;?>content/waiting_public?status=3" class="nav-link <?php activeMenu('content','waiting_public','viewtype'); activeVodMenuByStatus(3);?>">
							<i class="far fa-circle nav-icon"></i>
							<p>Chờ xuất bản <span class="badge badge-info right"><?php echo $n_content3;?></span></p>
						</a>
					</li>
				<?php } ?>
				<?php if(in_array('1005', $permis)){ ?>
					<li class="nav-item">
						<a href="<?php echo ROOTHOST;?>content/public?status=4" class="nav-link <?php activeMenu('content','public','viewtype'); activeVodMenuByStatus(4);?>">
							<i class="far fa-circle nav-icon"></i>
							<p>Đã xuất bản <span class="badge badge-info right"><?php echo $n_content4;?></span></p>
						</a>
					</li>
				<?php } ?>
				<?php if(in_array('1006', $permis)){ ?>
					<li class="nav-item">
						<a href="<?php echo ROOTHOST;?>content/return?status=2" class="nav-link <?php activeMenu('content','return','viewtype'); activeVodMenuByStatus(2);?>">
							<i class="far fa-circle nav-icon"></i>
							<p>Trả về<span class="badge badge-info right"><?php echo $n_content2;?></span></p>
						</a>
					</li>
				<?php } ?>
				<?php if(in_array('1007', $permis)){ ?>
					<li class="nav-item">
						<a href="<?php echo ROOTHOST;?>content/takedown?status=5" class="nav-link <?php activeMenu('content','takedown','viewtype'); activeVodMenuByStatus(5);?>">
							<i class="far fa-circle nav-icon"></i>
							<p>Bị gỡ xuống<span class="badge badge-info right"><?php echo $n_content5;?></span></p>
						</a>
					</li>
				<?php } ?>
				<?php if(in_array('1008', $permis)){ ?>
					<li class="nav-item">
						<a href="<?php echo ROOTHOST;?>content/deleted?is_trash=1" class="nav-link <?php activeMenu('content','deleted','viewtype');?>">
							<i class="far fa-circle nav-icon"></i>
							<p>Đã xóa</p>
						</a>
					</li>
				<?php } ?>
			</ul>
		</li>

		<li class="nav-item <?php menuOpen(array('setting', 'categories', 'site', 'user', 'groupuser', 'album', 'event', 'content_type'), 'com'); ?>">
			<a href="<?php echo ROOTHOST;?>setting" class="nav-link <?php activeMenus(array('setting', 'categories', 'site', 'user', 'groupuser', 'album', 'event', 'content_type'), 'com'); ?>">
				<i class="nav-icon fas fa-wrench" aria-hidden="true"></i>
				<p>Cấu hình<i class="right fas fa-angle-left"></i></p>
			</a>

			<ul class="nav nav-treeview">
				<li class="nav-item">
					<a href="<?php echo ROOTHOST;?>categories" class="nav-link <?php activeMenu('categories', '', 'com');?> ">
						<i class="nav-icon fa fa-server" aria-hidden="true"></i>
						<p>Chuyên mục bài viết</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?php echo ROOTHOST;?>site" class="nav-link <?php activeMenu('site', '', 'com');?>">
						<i class="nav-icon far fa-calendar-alt"></i>
						<p>Quản lý trang</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?php echo ROOTHOST;?>user" class="nav-link <?php activeMenu('user', '', 'com');?>">
						<i class="nav-icon fas fa-user"></i>
						<p>Người dùng</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?php echo ROOTHOST;?>groupuser" class="nav-link <?php activeMenu('groupuser', '', 'com');?>">
						<i class="nav-icon fas fa-users"></i>
						<p>Nhóm người dùng</p>
					</a>
				</li>

				<li class="nav-item">
					<a href="<?php echo ROOTHOST;?>album" class="nav-link <?php activeMenu('album', '', 'com');?>">
						<i class="nav-icon far fa-circle "></i>
						<p>Album</p>
					</a>
				</li>

				<li class="nav-item">
					<a href="<?php echo ROOTHOST;?>event" class="nav-link <?php activeMenu('event', '', 'com');?>">
						<i class="nav-icon far fa-circle "></i>
						<p>Event</p>
					</a>
				</li>

				<li class="nav-item">
					<a href="<?php echo ROOTHOST;?>content_type" class="nav-link <?php activeMenu('content_type', '', 'com');?>">
						<i class="nav-icon far fa-circle "></i>
						<p>Kiểu bài viết</p>
					</a>
				</li>

			</ul>
		</li>
	</ul>
</nav>
<script>
	$('.logout').click(function(){
		var _url="<?php echo ROOTHOST;?>ajaxs/user/logout.php";
		$.get(_url,function(){
			window.location.reload();
		})
	})
</script>