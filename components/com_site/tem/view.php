<?php
$msg 		= new \Plasticbrain\FlashMessages\FlashMessages();
if(!isset($_SESSION['flash'.'com_'.COMS])) $_SESSION['flash'.'com_'.COMS] = 2;
require_once('libs/cls.upload.php');
$obj_upload = new CLS_UPLOAD();
$file='';
$GetID = isset($_GET['id']) ? (int)$_GET["id"] : 0;

if(isset($_POST['txt_name']) && $_POST['txt_name']!=='') {
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
	$arr['status'] 		= isset($_POST['txt_status']) ? (int)$_POST['txt_status'] : 0;
	$Images 			= isset($_POST['txt_thumb2']) ? addslashes($_POST['txt_thumb2']) : '';

	if(isset($_FILES['txt_thumb']) && $_FILES['txt_thumb']['size'] > 0){
		$save_path 	= "medias/vods/videos/";
		$obj_upload->setPath($save_path);
		$file = $save_path.$obj_upload->UploadFile("txt_thumb", $save_path);
	}else{
		$file = $Images;
	}
	$arr['image'] = $file;

	if($arr['status'] == 2){
		$arr['active_date'] = time();
	}

	$rs_parent = SysGetList('tbl_sites', array("path"), " AND id=".$arr['par_id']);
	if(count($rs_parent)>0){
		$rs_parent = $rs_parent[0];
		$arr['path'] = $rs_parent['path'].'_'.$GetID;
	}else{
		$arr['path'] = $GetID;
	}

	$result = SysEdit('tbl_sites', $arr, " id=".$GetID);
	if($result) $_SESSION['flash'.'com_'.COMS] = 1;
	else $_SESSION['flash'.'com_'.COMS] = 0;
}

$res_Vods = SysGetList('tbl_sites', array(), ' AND id='. $GetID);
if(count($res_Vods) <= 0){
	echo 'Không có dữ liệu.'; 
	return;
}
$row = $res_Vods[0];

$arr_childs = array();
$res_children = SysGetList('tbl_sites', array('id'), " AND `path` LIKE '".$row['path']."%'");
if(count($res_children) >0){
	foreach ($res_children as $key => $value) {
		$arr_childs[] = $value['id'];
	}
}

$_status = $row['status'];
/*
0 : Cập nhật,
1 : Gửi biên tập,
2 : Bị trả lại,
3 : Chờ xuất bản,
4 : Xuất bản,
5 : Gỡ xuống,
*/
$__permis_1 = array(0, 1, 2);
$__permis_2 = array(0, 1, 2, 3);
$__permis_3 = array(0, 1, 2, 3, 4, 5);
$__permissions = $__permis_3;

$__action = array();
$__page_title = ''; 
$fulltext = ''; 

switch ($_status) {
	case 1:
	$__action = array(
		array("id" => 1, "name" => "Cập nhật", "class" => "red"),
		array("id" => 2, "name" => "Kích hoạt", "class" => "blue")
	);
	$__page_title = "Trang chưa kích hoạt";
	break;
	
	case 2:
	$__action = array(
		array("id" => 2, "name" => "Cập nhật", "class" => "red"),
		array("id" => 4, "name" => "Gỡ xuống", "class" => "blue")
	);
	$__page_title = "Trang đã kích hoạt";
	break;
	
	case 3:
	$__action = array(
		array("id" => 3, "name" => "Cập nhật", "class" => "red"),
		array("id" => 2, "name" => "Kích hoạt lại", "class" => "blue"),
	);
	$__page_title = "Trang đã hết hạn";
	break;
	
	case 4:
	$__action = array(
		array("id" => 4, "name" => "Cập nhật", "class" => "red"),
		array("id" => 2, "name" => "Kích hoạt lại", "class" => "blue"),
	);
	break;
	
	default:
	$__action = array(
		array("id" => 1, "name" => "Cập nhật", "class" => "red"),
		array("id" => 2, "name" => "Kích hoạt", "class" => "blue")
	);
	$__page_title = "Cập nhật thông tin trang";
	break;
}
?>
<style type="text/css">
	#type_video, #type_text, #type_audio{
		display: none;
	}
</style>
<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark"><?php echo $__page_title; ?></h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="<?php echo ROOTHOST;?>">Bảng điều khiển</a></li>
					<li class="breadcrumb-item"><a href="<?php echo ROOTHOST.COMS;?>">Danh sách trang</a></li>
					<li class="breadcrumb-item active"><?php echo $__page_title; ?></li>
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
				$msg->error('Có lỗi trong quá trình cập nhật.');
				echo $msg->display();
			}
			unset($_SESSION['flash'.'com_'.COMS]);
		}
		?>
		<div id='action'>
			<div class="card">
				<form name="frm_action" id="frm_action" action="" method="post" enctype="multipart/form-data">
					<input type="hidden" name="txtid" value="<?php echo $GetID;?>">
					<input type="hidden" id="txt_status" name="txt_status" value="">
					<div class="widget_control form-group">
						<?php
						foreach ($__action as $k => $v) {
							if(in_array($v['id'], $__permissions)){
								echo '<button type="button" class="btn_status btn '.$v['class'].'" data-key="'.$v['id'].'">'.$v['name'].'</button>';
							}
						}
						?>
					</div>

					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<div><label>Tên miền </label><span id="checkDomainExist" style="display: none; padding-left:15px;"></span></div>
								<input type="text" id="txt_domain" name="txt_domain" class="form-control" value="<?php echo $row['domain'];?>" placeholder="Tên miền website">
								<input type="hidden" id="chk_domain" name="chk_domain" value="0">
							</div>

							<div class="form-group">
								<label>Tên trang<font color="red"><font color="red">*</font></font></label>
								<input type="text" id="txt_name" name="txt_name" class="form-control" value="<?php echo $row['title'];?>" placeholder="Tên trang">
							</div>

							<div class="form-group">
								<label>Email</label>
								<input type="text" id="txt_email" name="txt_email" class="form-control" value="<?php echo $row['email'];?>">
							</div>

							<div class="form-group">
								<label>Số điện thoại</label>
								<input type="text" id="txt_phone" name="txt_phone" class="form-control" value="<?php echo $row['phone'];?>">
							</div>

							<div class="form-group">
								<label>Địa chỉ</label>
								<textarea class="form-control" id="txt_address" name="txt_address" placeholder="Địa chỉ..." rows="2"><?php echo $row['address'];?></textarea>
							</div>

							<div class="form-group">
								<label>Meta title</label>
								<textarea class="form-control" id="meta_title" name="meta_title" placeholder="Meta title..." rows="1"><?php echo $row['meta_title'];?></textarea>
							</div>

							<div class="form-group">
								<label>Meta desciption</label>
								<textarea class="form-control" id="meta_desc" name="meta_desc" placeholder="Meta desciption..." rows="2"><?php echo $row['meta_descript'];?></textarea>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Trang cha</label>
								<select class="form-control" name="cbo_par" id="cbo_par">
									<option value="0">-- Chọn một --</option>
									<?php getListComboboxSites(0,0, $arr_childs, $row['id']);?>
								</select>
								<script type="text/javascript">
									$(document).ready(function(){
										cbo_Selected('cbo_par', <?php echo $row['par_id']; ?>);
									});
								</script>
							</div>

							<div class='form-group'>
								<div class="widget-fileupload fileupload fileupload-new" data-provides="fileupload">
									<label>Ảnh đại diện</label><small> (Dung lượng < 10MB)</small>
									<div class="widget-avatar mb-2">
										<div class="fileupload-new thumbnail">
											<?php
											if(strlen($row['image'])>0){
												echo '<img src="'.ROOTHOST.$row['image'].'" id="img_image_preview">';
											}else{
												echo '<img src="'.ROOTHOST.'global/img/no-photo.jpg" id="img_image_preview">';
											}
											?>
										</div>
										<div class="fileupload-preview fileupload-exists thumbnail" style="line-height: 20px;"></div>
										<input type="hidden" name="txt_thumb2" value="<?php echo $row['image'];?>">
									</div>
									<div class="control">
										<span class="btn btn-file">
											<span class="fileupload-new">Tải lên</span>
											<span class="fileupload-exists">Thay đổi</span>
											<input type="file" id="file_image" name="txt_thumb" accept="image/jpg, image/jpeg">
										</span>
										<a href="javascript:void(0)" class="btn fileupload-exists" data-dismiss="fileupload" onclick="cancel_fileupload()">Hủy</a>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label>Facebook</label>
								<input type="text" id="txt_facebook" name="txt_facebook" class="form-control" value="<?php echo $row['facebook'];?>">
							</div>

							<div class="form-group">
								<label>Youtube</label>
								<input type="text" id="txt_youtube" name="txt_youtube" class="form-control" value="<?php echo $row['youtube'];?>">
							</div>

							<div class="form-group">
								<label>Skype</label>
								<input type="text" id="txt_skype" name="txt_skype" class="form-control" value="<?php echo $row['skype'];?>">
							</div>
						</div>
					</div>

					<div class="form-group">
						<label>Contact</label>
						<textarea class="form-control" id="txt_contact" name="txt_contact" placeholder="Thông tin liên hệ ở chân website..." rows="5"><?php echo $row['contact'];?></textarea>
					</div>
					
					<div class="toolbar">
						<div class="widget_control">
							<?php
							foreach ($__action as $k => $v) {
								if(in_array($v['id'], $__permissions)){
									echo '<button type="button" class="btn_status btn '.$v['class'].'" data-key="'.$v['id'].'">'.$v['name'].'</button>';
								}
							}
							?>
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

		$('#txt_fulltext').summernote({
			placeholder: 'Mô tả ...',
			height: 500,
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

	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function(e) {
				var img = document.createElement("img");
				img.src = e.target.result;
				// Hidden fileupload new
				$('.fileupload').removeClass('fileupload-new');
				$('.fileupload').addClass('fileupload-exists');
				$('.fileupload-preview').html(img);
			}

			reader.readAsDataURL(input.files[0]); // convert to base64 string
		}
	}

	$("#file_image").on('change', function(){
		readURL(this);
	});

	function cancel_fileupload(){
		$('.fileupload').removeClass('fileupload-exists');
		$('.fileupload').addClass('fileupload-new');
		$('.fileupload-preview').empty();
		$("#file_image").val('');
	}

	$("#txt_domain").on('change', function(){
		var domain = $(this).val();
		var _url = "<?php echo ROOTHOST;?>ajaxs/site/checkDomainExist.php";
		$.post(_url, {'domain': domain, 'site_id': '<?php echo $row["id"];?>'}, function(res){
			if(parseInt(res)==1){
				$('#checkDomainExist').html('<span style="color: red">Domain đã tồn tại.</span>');
				$('#chk_domain').val('1');
			}else{
				$('#checkDomainExist').html('<span style="color: #3cc051">Ok!</span>');
				$('#chk_domain').val('0');
			}
			$('#checkDomainExist').css('display','inline-block');
		});
	});

	function validForm(){
		var flag = true;
		var title = $('#txt_name').val();
		var domain = $('#txt_domain').val();
		var chk_domain = $('#chk_domain').val();

		if(title=='' || chk_domain==1 || domain==''){
			alert('Các mục đánh dấu * không được để trống');
			flag = false;
		}
		return flag;
	}
</script>