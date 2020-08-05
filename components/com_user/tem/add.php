<?php
$msg = new \Plasticbrain\FlashMessages\FlashMessages();
if(!isset($_SESSION['flash'.'com_'.COMS])) $_SESSION['flash'.'com_'.COMS] = 2;
require_once('libs/cls.upload.php');
$obj_upload = new CLS_UPLOAD();
$file='';

if(isset($_POST['cmdsave_tab1']) && $_POST['txt_name']!='') {
	$Username 		= isset($_POST['txt_name']) ? addslashes($_POST['txt_name']) : '';
	$Fullname 		= isset($_POST['txt_fullname']) ? addslashes($_POST['txt_fullname']) : '';
	$Group 			= isset($_POST['cbo_group']) ? (int)$_POST['cbo_group'] : '';
	$Email 			= isset($_POST['txt_email']) ? addslashes($_POST['txt_email']) : '';
	$Phone 			= isset($_POST['txt_phone']) ? addslashes($_POST['txt_phone']) : '';
	$Butdanh 		= isset($_POST['txt_pseudonym']) ? addslashes($_POST['txt_pseudonym']) : $Username;
	$Site_id 		= isset($_POST['cbo_sites']) ? json_encode($_POST['cbo_sites']) : [];

	$arr=array();
	$arr['username'] 	= $Username;
	$arr['group'] 		= $Group;
	$arr['email'] 		= $Email;
	$arr['phone'] 		= $Phone;
	$arr['site_id'] 	= $Site_id;
	$arr['fullname'] 	= $Fullname;
	$arr['pseudonym'] 	= $Butdanh;
	$arr['cdate'] 		= time();
	$pass="123456";
	$arr['password']=hash('sha256',$arr['username']).'|'.hash('sha256',md5($pass));

	$result = SysAdd('tbl_users', $arr);
	if($result){
		echo "<script language=\"javascript\">window.location.href='".ROOTHOST.COMS.'/edit/'.$result."?tab=payment'</script>";
	}else{
		$_SESSION['flash'.'com_'.COMS] = 0;
	}
}

function getListComboboxSites($parid=0, $level=0, $childs=array()){
	$sql="SELECT * FROM tbl_sites WHERE `par_id`='$parid' AND `isactive`='1' ";
	$objdata=new CLS_MYSQL();
	$objdata->Query($sql);
	$char="";
	if($level!=0){
		for($i=0;$i<$level;$i++)
			$char.="|-----";
	}
	if($objdata->Num_rows()<=0) return;
	while($rows=$objdata->Fetch_Assoc()){
		$id=$rows['id'];
		$parid=$rows['par_id'];
		$title=$rows['domain'];
		if(in_array($id, $childs)){
			echo "<option value='$id' disabled='true' class='disabled'>$char $title</option>";
		}else{
			echo "<option value='$id'>$char $title</option>";
		}
		$nextlevel=$level+1;
		getListComboboxSites($id,$nextlevel, $childs);
	}
}
?>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css">
<!-- /.content-header -->
<section id="widget_steps">
	<div class="container-fluid">
		<!-- MultiStep Form -->
		<div class="container-fluid" id="grad1">
			<div class="row justify-content-center mt-0">
				<div class="text-center col-xs-12 col-sm-12 col-md-12">
					<div class="card px-0 pt-4 pb-0 mt-3 mb-3">
						<h2><strong>Thêm mới người dùng</strong></h2>
						<p>Các thông tin được gắn dấu * là các thông tin yêu cầu bắt buộc.</p>
						<?php
						if (isset($_SESSION['flash'.'com_'.COMS])) {
							if($_SESSION['flash'.'com_'.COMS] == 1){
								$msg->success('Thêm mới thành công.');
								echo $msg->display();
							}else if($_SESSION['flash'.'com_'.COMS] == 0){
								$msg->error('Có lỗi trong quá trình thêm mới.');
								echo $msg->display();
							}
							unset($_SESSION['flash'.'com_'.COMS]);
						}
						?>
						<div class="row">
							<div class="col-md-12 mx-0">
								<section id="msform">
									<!-- progressbar -->
									<ul id="progressbar">
										<li class="active" id="account"><strong>Tài khoản</strong></li>
										<li id="payment"><strong>Quyền</strong></li>
									</ul> <!-- fieldsets -->

									<fieldset class="active">
										<form method="post" action="">
											<div class="form-card">
												<div class="row">
													<div class="col-md-6 col-sm-6">
														<div class="form-group">
															<label>Tên đăng nhập </label><font color="red"> (*) </font><span id="checkExist"></span>
															<input type="text" id="txt_name" name="txt_name" class="form-control" value="" placeholder="Tên đăng nhập" required="">
															<input type="hidden" id="inp_checkExit" name="inp_checkExit" value="1">
														</div>
													</div>

													<div class="col-md-6 col-sm-6">
														<div class="form-group">
															<label>Nhóm người dùng</label>
															<select class='form-control' id='cbo_group' name="cbo_group">
																<?php
																foreach ($_GROUP_USER as $key => $value) {
																	echo '<option value="'.$key.'">'.$value.'</option>';
																}
																?>
															</select>
														</div>
													</div>

													<div class="col-md-12 col-sm-12">
														<div class="form-group">
															<label>Trang người dùng được quản lý </label><font color="red"> (*)</font>
															<select class="form-control" name="cbo_sites[]" id="cbo_sites" multiple="multiple">
																<?php getListComboboxSites(0,0);?>
															</select>
														</div>
													</div>

													<div class="col-md-6 col-sm-6">
														<div class="form-group">
															<label>Tên người dùng</label>
															<input type="text" id="txt_fullname" name="txt_fullname" class="form-control" value="" placeholder="Tên người dùng">
														</div>
													</div>

													<div class="col-md-6 col-sm-6">
														<div class="form-group">
															<label><i class="fas fa-envelope"></i> Email</label>
															<input type="text" id="txt_email" name="txt_email" class="form-control" value="">
														</div>
													</div>

													<div class="col-md-6 col-sm-6">
														<div class="form-group">
															<label><i class="fas fa-mobile-alt"></i> Số điện thoại</label>
															<input type="text" id="txt_phone" name="txt_phone" class="form-control" value="">
														</div>
													</div>

													<div class="col-md-6 col-sm-6">
														<div class="form-group">
															<label>Bút danh</label>
															<input type="text" id="txt_pseudonym" name="txt_pseudonym" class="form-control" value="">
														</div>
													</div>
												</div>
											</div> 
											<input type="submit" name="cmdsave_tab1" class="action-button" value="Tạo tài khoản" />
										</form>
									</fieldset>

									<fieldset>
										<div class="form-card">
											<div id="list-permissions"></div>
										</div> 
										<input type="button" name="previous" class="previous action-button-previous" value="Previous" /> 
										<input type="button" name="make_payment" class="next action-button" value="Confirm" />
									</fieldset>
								</section>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	function validForm(){
		var flag = true;
		var title = $('#txt_name').val();
		var sites = $('#cbo_sites').val();
		var exits = $('#inp_checkExit').val();

		if(title=='' || sites.length <= 0){
			alert('Các mục đánh dấu * không được để trống');
			flag = false;
		}

		if(parseInt(exits) == 1){
			alert('Tên đăng nhập đã có người sử dụng');
			flag = false;
		}
		return flag;
	}

	$(document).ready(function(){
		$('#frm_action').submit(function(){
			return validForm();
		});

		$('#cbo_sites').select2({
			placeholder: "Chọn ít nhất một trang",
		});

		$('#txt_name').on('change', function(){
			var username = $(this).val();
			var _url = '<?php echo ROOTHOST;?>ajaxs/user/checkExist.php';

			$.post(_url, {'username': username}, function(res){
				if(parseInt(res) == 0){
					$('#checkExist').html('<i class="fa fa-check-square cgreen" aria-hidden="true"></i>');
					$('#inp_checkExit').val('0');
				}else{
					$('#checkExist').html('<i class="fa fa-times-circle cred" aria-hidden="true"></i> Tên đăng nhập đã có người sử dụng.');
					$('#inp_checkExit').val('1');
				}
			});
		});
	});
</script>