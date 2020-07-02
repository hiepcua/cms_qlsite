<?php
$isAdmin=getInfo('isadmin');
?>
<nav class="mt-2">
	<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
		<li class="nav-item has-treeview menu-open">
			<a href="<?php echo ROOTHOST;?>" class="nav-link <?php activeMenu('home');?>">
				<i class="nav-icon fas fa-tachometer-alt"></i>
				<p>Dashboard</p>
			</a>
		</li>
		
		<li class="nav-item">
			<a href="<?php echo ROOTHOST;?>site" class="nav-link <?php activeMenu('site');?>">
				<i class="nav-icon far fa-calendar-alt"></i>
				<p>Quản lý trang<i class="right fas fa-angle-left"></i></p>
			</a>
			<ul class="nav nav-treeview">
				<li class="nav-item">
					<a href="<?php echo ROOTHOST;?>site/add" class="nav-link <?php activeMenu('add','viewtype');?>">
						<i class="far fa-circle nav-icon"></i>
						<p>Thêm mới</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?php echo ROOTHOST;?>site/new?status=0" class="nav-link <?php activeMenu('new','viewtype'); activeVodMenuByStatus(0);?>">
						<i class="far fa-circle nav-icon"></i>
						<p>Chưa kích hoạt<span class="badge badge-info right">6</span></p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?php echo ROOTHOST;?>site/public?status=1" class="nav-link <?php activeMenu('public','viewtype'); activeVodMenuByStatus(1);?>">
						<i class="far fa-circle nav-icon"></i>
						<p>Đã kích hoạt</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?php echo ROOTHOST;?>site/timeout?status=2" class="nav-link <?php activeMenu('timeout','viewtype'); activeVodMenuByStatus(2);?>">
						<i class="far fa-circle nav-icon"></i>
						<p>Hết hạn</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?php echo ROOTHOST;?>site/deleted?is_trash=1" class="nav-link <?php activeMenu('deleted','viewtype');?>">
						<i class="far fa-circle nav-icon"></i>
						<p>Đã xóa</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?php echo ROOTHOST;?>site/" class="nav-link <?php activeMenu('site');?>">
						<i class="far fa-circle nav-icon"></i>
						<p>Ds tất cả trang<span class="badge badge-info right">6</span></p>
					</a>
				</li>
			</ul>
		</li>
		
		<li class="nav-item">
			<a href="<?php echo ROOTHOST;?>user" class="nav-link <?php activeMenu('user');?>">
				<i class="nav-icon fas fa-user"></i>
				<p>User </p>
			</a>
		</li>
		<li class="nav-item">
			<a href="<?php echo ROOTHOST;?>groupuser" class="nav-link <?php activeMenu('groupuser');?>">
				<i class="nav-icon fas fa-users"></i>
				<p>Group user </p>
			</a>
		</li>
		
		<li class="nav-item has-treeview">
			<a href="javascript:void(0);" class="nav-link logout">
				<i class="nav-icon fas fa-sign-out-alt"></i>
				<p>Logout </p>
			</a>
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