<?php
$msg 		= new \Plasticbrain\FlashMessages\FlashMessages();
if(!isset($_SESSION['flash'.'com_'.COMS])) $_SESSION['flash'.'com_'.COMS] = 2;
require_once('libs/cls.upload.php');
$obj_upload = new CLS_UPLOAD();
$file='';

if(isset($_POST['txt_name']) && $_POST['txt_name'] !== '') {
	if(isset($_FILES['txt_thumb']) && $_FILES['txt_thumb']['size'] > 0){
		$save_path 	= "medias/sites/";
		$obj_upload->setPath($save_path);
		$file = $save_path.$obj_upload->UploadFile("txt_thumb", $save_path);
	}

	$arr=array();
	$arr['title'] 		= isset($_POST['txt_name']) ? addslashes($_POST['txt_name']) : '';
	$arr['domain'] 		= isset($_POST['txt_domain']) ? addslashes($_POST['txt_domain']) : '';
	$arr['key'] 		= md5($arr['domain']);
	$arr['par_id'] 		= isset($_POST['cbo_par']) ? (int)$_POST['cbo_par'] : 0;
	$arr['phone'] 		= isset($_POST['txt_phone']) ? addslashes($_POST['txt_phone']) : '';
	$arr['email'] 		= isset($_POST['txt_email']) ? addslashes($_POST['txt_email']) : '';
	$arr['address'] 	= isset($_POST['txt_address']) ? addslashes($_POST['txt_address']) : '';
	$arr['contact'] 	= isset($_POST['txt_contact']) ? antiData($_POST['txt_contact'], 'html', true) : '';
	$arr['facebook'] 	= isset($_POST['txt_facebook']) ? addslashes($_POST['txt_facebook']) : '';
	$arr['youtube'] 	= isset($_POST['txt_youtube']) ? addslashes($_POST['txt_youtube']) : '';
	$arr['skype'] 		= isset($_POST['txt_skype']) ? addslashes($_POST['txt_skype']) : '';
	$arr['meta_title'] 	= isset($_POST['meta_title']) ? addslashes($_POST['meta_title']) : '';
	$arr['meta_descript'] = isset($_POST['meta_desc']) ? addslashes($_POST['meta_desc']) : '';
	$arr['image'] 		= $file;
	$arr['status'] 		= 1;
	$arr['cdate'] 		= time();

	$result = SysAdd('tbl_sites', $arr);
	if($result){
		$rs_parent = SysGetList('tbl_sites', array("path"), " AND id=".$arr['par_id']);
		if(count($rs_parent)>0){
			$rs_parent = $rs_parent[0];
			$path = $rs_parent['path'].'_'.$result;
		}else{
			$path = $result;
		}

		SysEdit('tbl_sites', array('path' => $path), " id=".$result);
		$_SESSION['flash'.'com_'.COMS] = 1;
	}else{
		$_SESSION['flash'.'com_'.COMS] = 0;
	}
}
?>
<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">Thêm mới trang</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="<?php echo ROOTHOST;?>">Bảng điều khiển</a></li>
					<li class="breadcrumb-item"><a href="<?php echo ROOTHOST.COMS;?>">Danh sách trang</a></li>
					<li class="breadcrumb-item active">Thêm mới trang</li>
				</ol>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<!-- Main content -->
<section class="content">
	<div class="container-fluid">
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
		<div id='action'>
			<div class="card">
				<form name="frm_action" id="frm_action" action="" method="post" enctype="multipart/form-data">
					<div class="mess"></div>
					<input type="hidden" id="txt_status" name="txt_status" value="">

					<div class="widget_control">
						<button type="button" class="btn_status btn blue" data-key="0">Thêm mới</button>
					</div><hr>

					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div  class="form-group">
								<label>Tên trang<font color="red"><font color="red">*</font></font></label>
								<input type="text" id="txt_name" name="txt_name" class="form-control" value="" placeholder="Tên trang">
							</div>
						</div>
						<div class="col-md-6 col-sm-6">
							<label>Nhóm</label>
							<select class="form-control" name="cbo_par" id="cbo_par">
								<option value="0">-- Chọn một --</option>
								<?php getListComboboxSites(0,0);?>
							</select>
						</div>
						<div class="col-md-6 col-sm-6">
							<div  class="form-group">
								<label>Tên miền</label>
								<input type="text" id="txt_domain" name="txt_domain" class="form-control" value="" placeholder="Tên miền website">
							</div>
						</div>
					
						<div class="col-md-6 col-sm-6">
							<div  class="form-group">
								<label>Email</label>
								<input type="text" id="txt_email" name="txt_email" class="form-control" value="">
							</div>
						</div>
						<div class="col-md-6 col-sm-6">
							<div  class="form-group">
								<label>Số điện thoại</label>
								<input type="text" id="txt_phone" name="txt_phone" class="form-control" value="">
							</div>
						</div>
						<div class="col-md-12 col-sm-12">
							<div class="form-group">
								<label>Địa chỉ</label>
								<textarea class="form-control" id="txt_address" name="txt_address" placeholder="Địa chỉ..." rows="2"></textarea>
							</div>
						</div>
					</div>

					<div class='form-group'>
						<label>Ảnh đại diện </label><small> (Dung lượng < 10MB)</small>
						<div id="response_img">
							<input type="file" name="txt_thumb" accept="image/jpg, image/jpeg">
						</div>
					</div>

					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div  class="form-group">
								<label>Facebook</label>
								<input type="text" id="txt_facebook" name="txt_facebook" class="form-control" value="">
							</div>
						</div>
						<div class="col-md-6 col-sm-6">
							<div  class="form-group">
								<label>Youtube</label>
								<input type="text" id="txt_youtube" name="txt_youtube" class="form-control" value="">
							</div>
						</div>
						<div class="col-md-6 col-sm-6">
							<div  class="form-group">
								<label>Skype</label>
								<input type="text" id="txt_skype" name="txt_skype" class="form-control" value="">
							</div>
						</div>
					</div>

					<div class="form-group">
						<label>Meta title</label>
						<textarea class="form-control" id="meta_title" name="meta_title" placeholder="Meta title..." rows="1"></textarea>
					</div>

					<div class="form-group">
						<label>Meta desciption</label>
						<textarea class="form-control" id="meta_desc" name="meta_desc" placeholder="Meta desciption..." rows="2"></textarea>
					</div>

					<div class="form-group" id="type_text">
						<label>Contact</label>
						<textarea class="form-control" id="txt_contact" name="txt_contact" placeholder="Thông tin liên hệ ở chân website..." rows="5"></textarea>
					</div>

					<div class="toolbar">
						<div class="widget_control">
							<button type="button" class="btn_status btn blue" data-key="0">Thêm mới</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>
<!-- /.row -->
<!-- /.content-header -->
<script type="text/javascript">
	$(document).ready(function(){
		$('#frm_action').submit(function(){
			return validForm();
		})

		$('#txt_contact').summernote({
			placeholder: 'Mô tả ...',
			height: 100,
			toolbar: [
			['style', ['style']],
			['font', ['bold', 'italic', 'underline', 'superscript', 'subscript', 'strikethrough', 'clear']],
			['fontname', ['fontname']],
			['fontsize', ['fontsize']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['height', ['height']],
			['table', ['table']],
			['insert', ['link', 'picture', 'video', 'hr']],
			['view', ['fullscreen', 'codeview', 'help']],
			],
		});

		$('.widget_control .btn_status').click(function(){
			var key = $(this).attr('data-key');
			$('#txt_status').val(key);
			$('#frm_action').submit();
		});
	});

	function validForm(){
		var flag = true;
		var title = $('#txt_name').val();
		// var cate = parseInt($('#cbo_par').val());

		if(title==''){
			alert('Các mục đánh dấu * không được để trống');
			flag = false;
		}
		return flag;
	}
</script>