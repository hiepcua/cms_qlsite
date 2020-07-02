<?php
$arr=array();
$obj = SysGetList('tbl_configsite', $arr, " AND config_id=1 ");
$r = $obj[0];
?>
<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">Contact</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="<?php echo ROOTHOST;?>">Home</a></li>
					<li class="breadcrumb-item active">Contact</li>
				</ol>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<!-- Main content -->
<section class="content">
	<div class='container-fluid'>
		<div class="card" style="padding: 20px 15px;">
			<h3>Contact info</h3>
			<ul class="list-unstyle">
				<li>- <?php echo $r['address'];?></li>
				<li>- Hotline: <?php echo $r['phone'];?></li>
				<li>- Email: <?php echo $r['email'];?></li>
				<li>- Facebook: <a href="<?php echo $r['facebook'];?>" target="_blank">Facebook</a></li>
				<li>- Twitter: <a href="<?php echo $r['twitter'];?>" target="_blank">Twitter</a></li>
				<li>- Work hours: <?php echo $r['work_time'];?></li>
			</ul>
		</div>
	</div>
</section>
