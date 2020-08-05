<?php
$msg = new \Plasticbrain\FlashMessages\FlashMessages();
if(!isset($_SESSION['flash'.'com_'.COMS])) $_SESSION['flash'.'com_'.COMS] = 2;
require_once('libs/cls.upload.php');
$obj_upload = new CLS_UPLOAD();
$file='';
$GetID = isset($_GET['id']) ? (int)$_GET["id"] : 0;
$get_tab = isset($_GET['tab']) ? antiData($_GET["tab"]) : 'account';

if(isset($_POST['txt_username']) && $_POST['txt_username'] !== '') {
	$Fullname 		= isset($_POST['txt_fullname']) ? addslashes($_POST['txt_fullname']) : '';
	$Group 			= isset($_POST['cbo_group']) ? (int)$_POST['cbo_group'] : '';
	$Email 			= isset($_POST['txt_email']) ? addslashes($_POST['txt_email']) : '';
	$Phone 			= isset($_POST['txt_phone']) ? addslashes($_POST['txt_phone']) : '';
	$Butdanh 		= isset($_POST['txt_pseudonym']) ? addslashes($_POST['txt_pseudonym']) : $Username;
	$Site_id 		= isset($_POST['cbo_sites']) ? json_encode($_POST['cbo_sites']) : [];

	$arr=array();
	$arr['group'] 		= $Group;
	$arr['email'] 		= $Email;
	$arr['phone'] 		= $Phone;
	$arr['site_id'] 	= $Site_id;
	$arr['fullname'] 	= $Fullname;
	$arr['pseudonym'] 	= $Butdanh;

	$result = SysEdit('tbl_users', $arr, "id=".$GetID);
	if($result){
		$_SESSION['flash'.'com_'.COMS] = 1;
		echo "<script language=\"javascript\">window.location.href='".ROOTHOST.COMS.'/edit/'.$GetID."?tab=payment'</script>";
	}else{
		$_SESSION['flash'.'com_'.COMS] = 0;
	}
}

$res_Users = SysGetList('tbl_users', array(), ' AND id='. $GetID);
if(count($res_Users) <= 0){
	echo 'Không có dữ liệu.'; 
	return;
}
$row = $res_Users[0];
$ar_sites = json_decode($row['site_id']);

function getListComboboxSites($parid=0, $level=0, $selected_ids=array()){
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
		if(in_array($id, $selected_ids)){
			echo "<option value='$id' selected='selected'>$char $title</option>";
		}else{
			echo "<option value='$id'>$char $title</option>";
		}
		$nextlevel=$level+1;
		getListComboboxSites($id, $nextlevel, $selected_ids);
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
						<h2><strong>Thông tin người dùng</strong></h2>
						<p>Các thông tin được gắn dấu * là các thông tin yêu cầu bắt buộc.</p>
						<?php
						if (isset($_SESSION['flash'.'com_'.COMS])) {
							if($_SESSION['flash'.'com_'.COMS] == 1){
								$msg->success('Cập nhật thành công.');
								echo $msg->display();
							}else if($_SESSION['flash'.'com_'.COMS] == 0){
								$msg->error('Có lỗi trong quá trình cập nhật.');
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
										<li class="<?php if($get_tab == 'account') echo 'active';?>" id="account"><strong>Tài khoản</strong></li>
										<li class="<?php if($get_tab == 'payment') echo 'active';?>" id="payment"><strong>Quyền</strong></li>
									</ul> <!-- fieldsets -->

									<fieldset class="<?php if($get_tab == 'account') echo 'active';?>">
										<form id="frm-step-1" class="form-card" method="post">
											<div class="row">
												<div class="col-md-6 col-sm-6">
													<div class="form-group">
														<label>Tên đăng nhập </label>
														<input type="text" readonly class="form-control" value="<?php echo $row['username'];?>">
														<input type="hidden" name="txt_username" value="<?php echo $row['username'];?>">
													</div>
												</div>

												<div class="col-md-6 col-sm-6">
													<div class="form-group">
														<label>Nhóm người dùng</label>
														<select class='form-control' id='cbo_group' name="cbo_group">
															<?php
															foreach ($_GROUP_USER as $key => $value) {
																if($key == (int)$row['group']){
																	echo '<option value="'.$key.'" selected>'.$value.'</option>';
																}else{
																	echo '<option value="'.$key.'">'.$value.'</option>';
																}
															}
															?>
														</select>
													</div>
												</div>

												<div class="col-md-12 col-sm-12">
													<div class="form-group">
														<label>Trang người dùng được quản lý </label><font color="red"> (*)</font>
														<select class="form-control" name="cbo_sites[]" id="cbo_sites" multiple="multiple">
															<?php getListComboboxSites(0, 0, $ar_sites);?>
														</select>
													</div>
												</div>

												<div class="col-md-6 col-sm-6">
													<div class="form-group">
														<label>Tên người dùng</label>
														<input type="text" id="txt_fullname" name="txt_fullname" class="form-control" value="<?php echo $row['fullname'];?>" placeholder="Tên người dùng">
													</div>
												</div>

												<div class="col-md-6 col-sm-6">
													<div class="form-group">
														<label><i class="fas fa-envelope"></i> Email</label>
														<input type="text" id="txt_email" name="txt_email" class="form-control" value="<?php echo $row['email'];?>">
													</div>
												</div>

												<div class="col-md-6 col-sm-6">
													<div class="form-group">
														<label><i class="fas fa-mobile-alt"></i> Số điện thoại</label>
														<input type="text" id="txt_phone" name="txt_phone" class="form-control" value="<?php echo $row['phone'];?>">
													</div>
												</div>

												<div class="col-md-6 col-sm-6">
													<div class="form-group">
														<label>Bút danh</label>
														<input type="text" id="txt_pseudonym" name="txt_pseudonym" class="form-control" value="<?php echo $row['pseudonym'];?>">
													</div>
												</div>
											</div>
										</form> 
										<input type="button" id="btn-save-step1" class="action-button" value="Tiếp theo" />
									</fieldset>

									<fieldset class="<?php if($get_tab == 'payment') echo 'active';?>">
										<form id="frm-step-2" class="form-card" method="post">
											<div id="list-permissions">
												<?php
												for($i=0; $i<count($ar_sites); $i++){
													$r_sites = SysGetList('tbl_sites', ['id', 'domain'], "AND id=".$ar_sites[$i]);

													$r_categories = SysGetList('tbl_categories', [], 'AND site_id='.$ar_sites[$i]);
													$num_cate = count($r_categories);
													?>
													<p><i class="fa fa-caret-right" aria-hidden="true"></i> Quyền trang <strong><?php echo $r_sites[0]['domain'];?></strong></p>
													<div class="wg-permission row">
														<div class="col-sm-3 col-md-3 col-xs-6 item">
															<div class="header">Quyền viết bài</div>
															<label class="check-all"><input type="checkbox" class="ip-check-all" value="" >All</label>
															<ul class="list-unstyle <?php echo $r_sites[0]['domain'];?>-vb">
																<?php
																if(count($r_categories) > 0){
																	for($j=0; $j < $num_cate; $j++){
																		echo '<li><label><input type="checkbox" data-site="'.$r_sites[0]['domain'].'" class="chk_cates_vb" name="chk_cates_vb[]" value="'.$r_categories[$j]['id'].'"> '.$r_categories[$j]['title'].'</label></li>';
																	}
																}
																?>
															</ul>
														</div>

														<div class="col-sm-3 col-md-3 col-xs-6 item">
															<div class="header">Quyền biên tập </div>
															<label class="check-all"><input type="checkbox" class="ip-check-all" value="">All</label>
															<ul class="list-unstyle bt-check-all">
																<?php
																if(count($r_categories) > 0){
																	for($j=0; $j < $num_cate; $j++){
																		echo '<li><label><input type="checkbox" name="chk_cates_bt[]" value="'.$r_categories[$j]['id'].'"> '.$r_categories[$j]['title'].'</label></li>';
																	}
																}
																?>
															</ul>
														</div>

														<div class="col-sm-3 col-md-3 col-xs-6 item">
															<div class="header">Quyền xuất bản</div>
															<label class="check-all"><input type="checkbox" class="ip-check-all" value="">All</label>
															<ul class="list-unstyle xb-check-all">
																<?php
																if(count($r_categories) > 0){
																	for($j=0; $j < $num_cate; $j++){
																		echo '<li><label><input type="checkbox" name="chk_cates_xb[]" value="'.$r_categories[$j]['id'].'"> '.$r_categories[$j]['title'].'</label></li>';
																	}
																}
																?>
															</ul>
														</div>

														<div class="col-sm-3 col-md-3 col-xs-6 item">
															<div class="header">Quyền gỡ bài</div>
															<label class="check-all"><input type="checkbox" class="ip-check-all" value="">All</label>
															<ul class="list-unstyle gb-check-all">
																<?php
																if(count($r_categories) > 0){
																	for($j=0; $j < $num_cate; $j++){
																		echo '<li><label><input type="checkbox" name="chk_cates_gb[]" value="'.$r_categories[$j]['id'].'"> '.$r_categories[$j]['title'].'</label></li>';
																	}
																}
																?>
															</ul>
														</div>
													</div><br>
													<?php
												}
												?>
											</div>
										</form> 
										<input type="button" name="previous" class="previous action-button-previous" value="Quay lại" /> 
										<input type="button" name="make_payment" class="next action-button" value="Cập nhật quyền" />
									</fieldset>
								</form>
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
		var sites = $('#cbo_sites').val();

		if(sites.length <= 0){
			alert('Các mục đánh dấu * không được để trống');
			flag = false;
		}
		return flag;
	}

	$(document).ready(function(){
		$('#frm-step-1').submit(function(){
			return validForm();
		});

		$('#cbo_sites').select2({
			placeholder: "Chọn ít nhất một trang",
		});

		$('#btn-save-step1').on('click', function(){
			$('#frm-step-1').submit();
		});

		$('#frm-step-1 input, #frm-step-1 select').on('change', function(){
			$('#btn-save-step1').val('Cập nhật');
		});


		$('.ip-check-all').on('click', function(){
			var parent = $(this).parent().parent();
			var ul = parent.find('ul.list-unstyle');

			if($(this).is(':checked') == true){
				ul.find('input').attr('checked', 'checked');
			}else{
				ul.find('input').removeAttr('checked');
			}
		});

		// $(".vb-check-all, .bt-check-all, .xb-check-all, .gb-check-all").on('click', function(){
		// 	debugger;
		// 	var id = this.id;
		// 	if(this.checked == true){
		// 		$('.'+id).find('input').attr('checked','checked');
		// 	}else{
		// 		$('.'+id).find('input').removeAttr('checked');
		// 	}
		// });



		var current_fs, next_fs, previous_fs; //fieldsets
		var opacity;

		$(".previous").click(function(){
			current_fs = $(this).parent();
			previous_fs = $(this).parent().prev();

			//Remove class active
			$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

			//show the previous fieldset
			previous_fs.show();

			//hide the current fieldset with style
			current_fs.animate({opacity: 0}, {
				step: function(now) {
					// for making fielset appear animation
					opacity = 1 - now;

					current_fs.css({
						'display': 'none',
						'position': 'relative'
					});

					previous_fs.css({
						'opacity': opacity
					});
				},
				duration: 600
			});
		});
	});
</script>