<?php
$user=getInfo('username');
$isAdmin=getInfo('isadmin');
$sql="SELECT * FROM tbl_users WHERE username='".$user."'";
$objmysql = new CLS_MYSQL;
$objmysql->Query($sql);
$row = $objmysql->Fetch_Assoc();

$file='';
if(isset($_POST['cmdsave_tab1'])){
	$Fullname 	= isset($_POST['txtfullname']) ? trim(addslashes($_POST['txtfullname'])) : '';
	$Mobile 	= isset($_POST['txtmobile']) ? trim(addslashes($_POST['txtmobile'])) : '';
	$Email 		= isset($_POST['txtemail']) ? trim(addslashes($_POST['txtemail'])) : '';
	$Cdate 		= time();

	$arr=array('fullname'=>$Fullname, 'phone'=>$Mobile, 'email'=>$Email);
	$result = SysEdit('tbl_users', $arr, "username='$user'");
	if($result) $_SESSION['flash'.'com_'.$COM] = 1;
	else $_SESSION['flash'.'com_'.$COM] = 0;
}?>
<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">CẬP NHẬT THÔNG TIN CÁ NHÂN</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="<?php echo ROOTHOST;?>">Bảng điều khiển</a></li>
					<li class="breadcrumb-item active">Cập nhật thông tin cá nhân</li>
				</ol>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<!-- Main content -->
<section class="content">
	<div class='container-fluid'>
		<!-- Main content -->
		<section id="profile" class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-4 col-lg-3">
						<ul class="list-group">
							<li class="list-group-item"><strong>Tài khoản:</strong> <div><?php echo $row['username'];?></div></li>
							<li class="list-group-item"><span class="pull-left"><strong>Ngày tham gia:</strong></span> <div><?php echo date('d-m-Y', $row['cdate']);?></div></li>
						</ul>
					</div>

					<div class="col-sm-8 col-lg-9">
						<div class="box-tabs">
							<!-- Nav tabs -->
							<ul class="nav nav-tabs">
								<li class="nav-item">
									<a class="nav-link active" data-toggle="tab" href="#info">Thông tin cá nhân</a>
								</li>
							</ul>
						</div>

						<div class="tab-content card">
							<div class="tab-pane container-fluid active" id="info">
								<form id="frm_action" class="form" action="" method="post" enctype="multipart/form-data">
									<p>
										<?php
										if (isset($_SESSION['flash'.'com_'.$COM])) {
											if($_SESSION['flash'.'com_'.$COM] == 1){
												$msg->success('Cập nhật thành công.');
												echo $msg->display();
											}else if($_SESSION['flash'.'com_'.$COM] == 0){
												$msg->error('Có lỗi trong quá trình cập nhật.');
												echo $msg->display();
											}
											unset($_SESSION['flash'.'com_'.$COM]);
										}
										?>
									</p>
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label>Name<small class="cred"> (*)</small><span id="err_firstname" class="mes-error"></span></label>
												<input class="form-control" id="txtfullname" name="txtfullname" value="<?php echo $row['fullname'];?>" type="text" required>
											</div>
										</div>

										<div class="col-sm-6">
											<div class="form-group">
												<label>Phone</label>
												<input class="form-control" name="txtmobile" type="tel" id="txtmobile" value="<?php echo $row['phone'];?>"/>
											</div>
										</div>

										<div class="col-sm-6">
											<div class="form-group">
												<label>Email</label>
												<input class="form-control" name="txtemail" type="email" id="txtemail" value="<?php echo $row['email'];?>"/>
											</div>
										</div>
									</div>

									<div class="text-center toolbar">
										<input type="submit" name="cmdsave_tab1" id="cmdsave_tab1" class="save btn btn-success" value="Lưu thông tin" class="btn btn-primary">
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</section>
