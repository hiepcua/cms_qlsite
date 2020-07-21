<?php
$msg 		= new \Plasticbrain\FlashMessages\FlashMessages();
if(!isset($_SESSION['flash'.'com_'.COMS])) $_SESSION['flash'.'com_'.COMS] = 2;
require_once('libs/cls.upload.php');
$obj_upload = new CLS_UPLOAD();
$file='';

if(isset($_POST['cmdsave_tab1']) && $_POST['txt_name']!='') {
	$Title 			= isset($_POST['txt_name']) ? addslashes($_POST['txt_name']) : '';
	$Intro 			= isset($_POST['txt_intro']) ? addslashes($_POST['txt_intro']) : '';
	$Meta_title 	= isset($_POST['meta_title']) ? addslashes($_POST['meta_title']) : '';
	$Meta_desc 		= isset($_POST['meta_desc']) ? addslashes($_POST['meta_desc']) : '';
	$Cate_id 		= isset($_POST['cbo_cate']) ? (int)$_POST['cbo_cate'] : 0;

	if(isset($_FILES['txt_thumb']) && $_FILES['txt_thumb']['size'] > 0){
		$save_path 	= "medias/events/";
		$obj_upload->setPath($save_path);
		$file = $save_path.$obj_upload->UploadFile("txt_thumb", $save_path);
	}

	$arr=array();
	$arr['title'] = $Title;
	$arr['cate_id'] = $Cate_id;
	$arr['code'] = un_unicode($Title);
	$arr['intro'] = $Intro;
	$arr['meta_title'] = $Meta_title;
	$arr['meta_desc'] = $Meta_desc;
	$arr['image'] = $file;

	$result = SysAdd('tbl_events', $arr);
	if($result){
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
				<h1 class="m-0 text-dark">Thêm mới chuyên mục</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="<?php echo ROOTHOST;?>">Bảng điều khiển</a></li>
					<li class="breadcrumb-item"><a href="<?php echo ROOTHOST.COMS;?>">Danh sách sự kiện</a></li>
					<li class="breadcrumb-item active">Thêm mới sự kiện</li>
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
				$msg->success('Cập nhật thành công.');
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
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Tiêu đề<font color="red">*</font></label>
								<input type="text" id="txt_name" name="txt_name" class="form-control" value="" placeholder="Tiêu đề sự kiện">
							</div>
						</div>

						<div class="col-md-6">
							<label>Chuyên mục</label>
							<select class="form-control" name="cbo_cate" id="cbo_cate" onchange="changeSite(this)">
								<option value="0">-- Chọn một --</option>
								<?php getListComboboxCategories(0,0);?>
							</select>
						</div>
						
						<div class="col-md-6">
							<div class='form-group'>
								<label>Ảnh</label><small> (Dung lượng < 10MB)</small>
								<div id="response_img">
									<input type="file" name="txt_thumb" accept="image/jpg, image/jpeg">
								</div>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label>Mô tả</label>
						<textarea class="form-control" name="txt_intro" placeholder="Mô tả về sự kiện..." rows="2"></textarea>
					</div>

					<div class="form-group">
						<label>Meta title</label>
						<textarea class="form-control" name="meta_title" placeholder="Meta title..." rows="2"></textarea>
					</div>

					<div class="form-group">
						<label>Meta description</label>
						<textarea class="form-control" name="meta_desc" placeholder="Meta description..." rows="3"></textarea>
					</div>
					
					<div class="text-center toolbar">
						<input type="submit" name="cmdsave_tab1" id="cmdsave_tab1" class="save btn btn-success" value="Lưu thông tin" class="btn btn-primary">
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
		});
	});

	function validForm(){
		var flag = true;
		var title = $('#txt_name').val();

		if(title==''){
			alert('Các mục đánh dấu * không được để trống');
			flag = false;
		}
		return flag;
	}
</script>