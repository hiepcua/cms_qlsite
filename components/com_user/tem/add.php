<?php
$msg = new \Plasticbrain\FlashMessages\FlashMessages();
if(!isset($_SESSION['flash'.'com_'.COMS])) $_SESSION['flash'.'com_'.COMS] = 2;
require_once('libs/cls.upload.php');
$obj_upload = new CLS_UPLOAD();
$file='';

if(isset($_POST['cmdsave_tab1']) && $_POST['txt_name']!='') {
	$Title 			= isset($_POST['txt_name']) ? addslashes($_POST['txt_name']) : '';
	$Intro 			= isset($_POST['txt_intro']) ? addslashes($_POST['txt_intro']) : '';
	$Meta_title 	= isset($_POST['meta_title']) ? addslashes($_POST['meta_title']) : '';
	$Meta_desc 		= isset($_POST['meta_desc']) ? addslashes($_POST['meta_desc']) : '';
	$Par_id 		= isset($_POST['cbo_par']) ? (int)$_POST['cbo_par'] : 0;
	$Site_id 		= isset($_POST['cbo_site']) ? (int)$_POST['cbo_site'] : 0;

	$per_vb = isset($_POST['chk_cates_vb']) ? $_POST['chk_cates_vb'] : [];
	$per_bt = isset($_POST['chk_cates_bt']) ? $_POST['chk_cates_bt'] : [];
	$per_xb = isset($_POST['chk_cates_xb']) ? $_POST['chk_cates_xb'] : [];
	$per_gb = isset($_POST['chk_cates_gb']) ? $_POST['chk_cates_gb'] : [];

	$arr_permission['1101'] = $per_vb;
	$arr_permission['1102'] = $per_vb;
	$arr_permission['1103'] = $per_vb;

	$arr=array();
	$arr['title'] = $Title;
	$arr['par_id'] = $Par_id;
	$arr['site_id'] = $Site_id;
	$arr['alias'] = un_unicode($Title);
	$arr['intro'] = $Intro;

	$result = SysAdd('tbl_categories', $arr);
	if($result){
		$rs_parent = SysGetList('tbl_categories', array("path"), " AND id=".$Par_id);
		if(count($rs_parent)>0){
			$rs_parent = $rs_parent[0];
			$path = $rs_parent['path'].'_'.$result;
		}else{
			$path = $result;
		}

		SysEdit('tbl_categories', array('path' => $path), " id=".$result);
		$_SESSION['flash'.'com_'.COMS] = 1;
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
<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">Thêm mới người dùng</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="<?php echo ROOTHOST;?>">Bảng điều khiển</a></li>
					<li class="breadcrumb-item"><a href="<?php echo ROOTHOST.COMS;?>">Danh sách người dùng</a></li>
					<li class="breadcrumb-item active">Thêm mới người dùng</li>
				</ol>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container-fluid -->
</div>

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
						<div class="row">
							<div class="col-md-12 mx-0">
								<form id="msform">
									<!-- progressbar -->
									<ul id="progressbar">
										<li class="active" id="account"><strong>Tài khoản</strong></li>
										<li id="personal"><strong>Website/Nhóm</strong></li>
										<li id="payment"><strong>Quyền</strong></li>
										<li id="confirm"><strong>Kết quả</strong></li>
									</ul> <!-- fieldsets -->

									<fieldset>
										<div class="form-card">
											<h2 class="fs-title">Thông tin tài khoản</h2>
											<div class="row">
												<div class="col-md-6 col-sm-6">
													<div class="form-group">
														<label>Tên đăng nhập<font color="red">*</font></label>
														<input type="text" id="txt_name" name="txt_name" class="form-control" value="" placeholder="Tên đăng nhập" required="">
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
										<input type="button" id="btn_next_step1" name="next" class="next action-button" value="Tiếp theo" />
									</fieldset>

									<fieldset>
										<div class="form-card">
											<div class="form-group">
												<label>Nhóm người dùng</label>
												<select class='form-control' id='cbo_group'>
													<option value="0">-- Chọn một --</option>
													<?php
													foreach ($_GROUP_USER as $key => $value) {
														echo '<option value="'.$key.'">'.$value.'</option>';
													}
													?>
												</select>
											</div>

											<div class="form-group">
												<label>Trang</label>
												<select class="form-control" name="cbo_sites[]" id="cbo_sites" multiple="multiple">
													<option value="0">-- Chọn một --</option>
													<?php getListComboboxSites(0,0);?>
												</select>
											</div>
										</div> 
										<input type="button" name="previous" class="previous action-button-previous" value="Previous" /> 
										<input type="button" id="btn_next_step2" name="next" class="next action-button" value="Next Step" />
									</fieldset>

									<fieldset>
										<div class="form-card">
											<div id="list-permissions"></div>
										</div> 
										<input type="button" name="previous" class="previous action-button-previous" value="Previous" /> 
										<input type="button" name="make_payment" class="next action-button" value="Confirm" />
									</fieldset>

									<fieldset>
										<div class="form-card">
											<h2 class="fs-title text-center">Success !</h2> <br><br>
											<div class="row justify-content-center">
												<div class="col-3"> <img src="https://img.icons8.com/color/96/000000/ok--v2.png" class="fit-image"> </div>
											</div> <br><br>
											<div class="row justify-content-center">
												<div class="col-7 text-center">
													<h5>You Have Successfully Signed Up</h5>
												</div>
											</div>
										</div>
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
		var title = $('#txt_name').val();

		if(title==''){
			alert('Các mục đánh dấu * không được để trống');
			flag = false;
		}
		return flag;
	}

	

	$(document).ready(function(){
		$('#frm_action').submit(function(){
			return validForm();
		});

		$('#cbo_sites').select2();

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

		$('#btn_next_step1').on('click', function(){
			var username = $('#txt_name').val();
			if(username.length == 0 || username.length <= 5){
				alert('Các mục đánh dấu * không được để trống');
				return false;
			}else{
				current_fs = $(this).parent();
				next_fs = $(this).parent().next();

				//Add Class Active
				$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

				//show the next fieldset
				next_fs.show();
				//hide the current fieldset with style
				current_fs.animate({opacity: 0}, {
					step: function(now) {
						// for making fielset appear animation
						opacity = 1 - now;

						current_fs.css({
							'display': 'none',
							'position': 'relative'
						});
						next_fs.css({'opacity': opacity});
					},
					duration: 600
				});
			}
		});

		$('#btn_next_step2').on('click', function(){
			var sites = $('#cbo_sites').val();
			if(sites.length <= 0){
				alert('Bạn chưa chọn site nào.');
				return false;
			}else{
				$.get('<?php echo ROOTHOST;?>ajaxs/user/get_permission_by_site.php', {'sites': sites.toString()}, function(res){
					$('#list-permissions').html(res);
				});
				

				// Next step
				current_fs = $(this).parent();
				next_fs = $(this).parent().next();

				//Add Class Active
				$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

				//show the next fieldset
				next_fs.show();
				//hide the current fieldset with style
				current_fs.animate({opacity: 0}, {
					step: function(now) {
						// for making fielset appear animation
						opacity = 1 - now;

						current_fs.css({
							'display': 'none',
							'position': 'relative'
						});
						next_fs.css({'opacity': opacity});
					},
					duration: 600
				});
			}
		});

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

		$(".submit").click(function(){
			return false;
		});
	});
</script>