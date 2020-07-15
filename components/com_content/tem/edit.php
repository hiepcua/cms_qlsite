<?php
$msg 		= new \Plasticbrain\FlashMessages\FlashMessages();
if(!isset($_SESSION['flash'.'com_'.COMS])) $_SESSION['flash'.'com_'.COMS] = 2;
require_once('libs/cls.upload.php');
$obj_upload = new CLS_UPLOAD();
$file='';
$GetID = isset($_GET['id']) ? (int)$_GET["id"] : 0;

if(isset($_POST['txt_name']) && $_POST['txt_name']!=='') {
	$Title 			= isset($_POST['txt_name']) ? addslashes($_POST['txt_name']) : '';
	$Sapo 			= isset($_POST['txt_sapo']) ? addslashes($_POST['txt_sapo']) : '';
	$Cate 			= isset($_POST['cbo_cate']) ? (int)$_POST['cbo_cate'] : 0;
	$Album 			= isset($_POST['cbo_album']) ? (int)$_POST['cbo_album'] : 0;
	$Chanel 		= isset($_POST['cbo_chanel']) ? (int)$_POST['cbo_chanel'] : 0;
	$Type 			= isset($_POST['cbo_type']) ? (int)$_POST['cbo_type'] : 0;
	$Images 		= isset($_POST['txt_thumb2']) ? addslashes($_POST['txt_thumb2']) : '';
	$Status 		= isset($_POST['txt_status']) ? (int)$_POST['txt_status'] : 0;

	if($Type == 1){
		$Fulltext = isset($_POST['txt_video_links']) ? json_encode($_POST['txt_video_links']) : '[]';
	}else if($Type == 2){
		$Fulltext = isset($_POST['txt_audio_links']) ? json_encode($_POST['txt_audio_links']) : '[]';
	}else if($Type == 3){
		$Fulltext = htmlentities($_POST['txt_fulltext']);
	}

	if(isset($_FILES['txt_thumb']) && $_FILES['txt_thumb']['size'] > 0){
		$save_path 	= "medias/vods/videos/";
		$obj_upload->setPath($save_path);
		$file = $save_path.$obj_upload->UploadFile("txt_thumb", $save_path);
	}else{
		$file = $Images;
	}

	$arr=array();
	$arr['title'] = $Title;
	$arr['alias'] = un_unicode($Title);
	$arr['sapo'] = $Sapo;
	$arr['fulltext'] = $Fulltext;
	$arr['cat_id'] = $Cate;
	$arr['album_id'] = $Album;
	$arr['chanel_id'] = $Chanel;
	$arr['type'] = $Type;
	$arr['images'] = $file;
	$arr['mdate'] = time();
	$arr['status'] = $Status;

	$result = SysEdit('tbl_content', $arr, " id=".$GetID);

	if($result) $_SESSION['flash'.'com_'.COMS] = 1;
	else $_SESSION['flash'.'com_'.COMS] = 0;
}

$res_Vods = SysGetList('tbl_content', array(), ' AND id='. $GetID);
if(count($res_Vods) <= 0){
	echo 'Không có dữ liệu.'; 
	return;
}
$row = $res_Vods[0];
$_status = $row['status'];
$_type = $row['type'];
/*
0 : Lưu nháp,
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
$video_sourses = $audio_sourses = array();
switch ($_type) {
	case 1:
		$video_sourses = json_decode($row['fulltext']);
		break;
	case 2:
		$audio_sourses = json_decode($row['fulltext']);
		break;
	case 3:
		$fulltext = html_entity_decode($row['fulltext']);
		break;
	default:
		$video_sourses = json_decode($row['fulltext']);
		break;
}
switch ($_status) {
	case 0:
	$__action = array(
		array("id" => 0, "name" => "Lưu nháp", "class" => "red"),
		array("id" => 1, "name" => "Gửi biên tập", "class" => "blue"),
		array("id" => 3, "name" => "Chờ xuất bản", "class" => "blue"),
		array("id" => 4, "name" => "Xuất bản", "class" => "blue")
	);
	$__page_title = "Bài đang biên tập";
	break;
	
	case 1:
	$__action = array(
		array("id" => 1, "name" => "Cập nhật", "class" => "red"),
		array("id" => 3, "name" => "Duyệt tin", "class" => "blue"),
		array("id" => 4, "name" => "Xuất bản", "class" => "blue"),
		array("id" => 2, "name" => "Trả lại cho phóng viên", "class" => "blue")
	);
	$__page_title = "Bài chờ duyệt";
	break;
	
	case 2:
	$__action = array(
		array("id" => 2, "name" => "Cập nhật", "class" => "red"),
		array("id" => 3, "name" => "Nhận lại tin này", "class" => "blue"),
	);
	$__page_title = "Bài chờ xuất bản";
	break;
	
	case 3:
	$__action = array(
		array("id" => 3, "name" => "Cập nhật", "class" => "red"),
		array("id" => 4, "name" => "Xuất bản", "class" => "blue"),
		array("id" => 2, "name" => "Trả lại cho phóng viên", "class" => "blue"),
		array("id" => 1, "name" => "Trả lại cho BTV", "class" => "blue")
	);
	break;
	
	case 4:
	$__action = array(
		array("id" => 4, "name" => "Cập nhật", "class" => "red"),
		array("id" => 5, "name" => "Gỡ tin", "class" => "blue")
	);
	$__page_title = "Bài đã xuất bản";
	break;

	case 5:
	$__action = array(
		array("id" => 5, "name" => "Cập nhật", "class" => "red"),
		array("id" => 4, "name" => "Xuất bản lại", "class" => "blue"),
		array("id" => 2, "name" => "Trả lại cho phóng viên", "class" => "blue")
	);
	$__page_title = "Bài bị trả về";
	break;
	
	default:
	$__action = array(
		array("id" => 0, "name" => "Lưu nháp", "class" => "red"),
		array("id" => 1, "name" => "Gửi biên tập", "class" => "blue"),
		array("id" => 3, "name" => "Chờ xuất bản", "class" => "blue"),
		array("id" => 4, "name" => "Xuất bản", "class" => "blue")
	);
	$__page_title = "Sửa VOD";
	break;
}

$audio_sourses_1 = $audio_sourses_2 = '';
$video_sourses_1 = $video_sourses_2 = $video_sourses_3 = $video_sourses_4 = $video_sourses_5 = $video_sourses_6 = $video_sourses_7 = '';
if(count($video_sourses) > 0){
	$video_sourses_1 = isset($video_sourses[0]) ? $video_sourses[0] : '';
	$video_sourses_2 = isset($video_sourses[1]) ? $video_sourses[1] : '';
	$video_sourses_3 = isset($video_sourses[2]) ? $video_sourses[2] : '';
	$video_sourses_4 = isset($video_sourses[3]) ? $video_sourses[3] : '';
	$video_sourses_5 = isset($video_sourses[4]) ? $video_sourses[4] : '';
	$video_sourses_6 = isset($video_sourses[5]) ? $video_sourses[5] : '';
	$video_sourses_7 = isset($video_sourses[6]) ? $video_sourses[6] : '';
}
if(count($audio_sourses) > 0){
	$audio_sourses_1 = isset($audio_sourses[0]) ? $audio_sourses[0] : '';
	$audio_sourses_2 = isset($audio_sourses[1]) ? $audio_sourses[1] : '';
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
					<li class="breadcrumb-item"><a href="<?php echo ROOTHOST;?>vod">Danh sách bài viết</a></li>
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
					<div class="row">
						<div class="col-md-9">
							<div class="widget_control">
								<?php
								foreach ($__action as $k => $v) {
									if(in_array($v['id'], $__permissions)){
										echo '<button type="button" class="btn_status btn '.$v['class'].'" data-key="'.$v['id'].'">'.$v['name'].'</button>';
									}
								}
								?>
							</div><hr>
							<div  class="form-group">
								<label>Tiêu đề<font color="red"><font color="red">*</font></font></label>
								<input type="text" id="txt_name" name="txt_name" class="form-control" value="<?php echo $row['title']; ?>" placeholder="Tiêu đề VOD">
							</div>

							<div class='form-group'>
								<label>Ảnh đại diện </label><small> (Dung lượng < 10MB)</small>
								<div class="widget_thumb80">
									<?php if($row['images'] != ''){ ?>
										<div class="wrap_thumb80">
											<img src="<?php echo ROOTHOST.$row['images'];?>" class="thumb80">
										</div>
									<?php } ?>
									<div id="response_img">
										<input type="hidden" name="txt_thumb2" value="<?php echo $row['images'];?>">
										<input type="file" name="txt_thumb" accept="image/jpg, image/jpeg">
									</div>
								</div>
							</div>

							<div class="row mb-3" id="type_video" style="<?php if($_type==1) echo 'display: flex;';?>">
								<div class="col-md-12"><label>Video sources </label><small> (Allow type: video/mp4)</small></div>
								<div class="col-md-6">
									<div class="input-group">
										<input type="text" class="form-control" name="txt_video_links[]" placeholder="Source 1080p, video/mp4..." value="<?php echo $video_sourses_1;?>">
										<div class="input-group-append">
											<span class="input-group-text">1080p</span>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="input-group">
										<input type="text" class="form-control" name="txt_video_links[]" placeholder="Source 720p, video/mp4..." value="<?php echo $video_sourses_2;?>">
										<div class="input-group-append">
											<span class="input-group-text">720p</span>
										</div>
									</div>
								</div>
								<div class="col-md-6 mt-3">
									<div class="input-group">
										<input type="text" class="form-control" name="txt_video_links[]" placeholder="Source 576p, video/mp4..." value="<?php echo $video_sourses_3;?>">
										<div class="input-group-append">
											<span class="input-group-text">576p</span>
										</div>
									</div>
								</div>
								<div class="col-md-6 mt-3">
									<div class="input-group">
										<input type="text" class="form-control" name="txt_video_links[]" placeholder="Source 480p, video/mp4..." value="<?php echo $video_sourses_4;;?>">
										<div class="input-group-append">
											<span class="input-group-text">480p</span>
										</div>
									</div>
								</div>
								<div class="col-md-6 mt-3">
									<div class="input-group">
										<input type="text" class="form-control" name="txt_video_links[]" placeholder="Source 360p, video/mp4..." value="<?php echo $video_sourses_5;?>">
										<div class="input-group-append">
											<span class="input-group-text">360p</span>
										</div>
									</div>
								</div>
								<div class="col-md-6 mt-3">
									<div class="input-group">
										<input type="text" class="form-control" name="txt_video_links[]" placeholder="Source 240p, video/mp4..." value="<?php echo $video_sourses_6;?>">
										<div class="input-group-append">
											<span class="input-group-text">240p</span>
										</div>
									</div>
								</div>
								<div class="col-md-6 mt-3">
									<div class="input-group">
										<input type="text" class="form-control" name="txt_video_links[]" placeholder="Source 144p, video/mp4..." value="<?php echo $video_sourses_7;?>">
										<div class="input-group-append">
											<span class="input-group-text">144p</span>
										</div>
									</div>
								</div>
							</div>
							
							<div class="row mb-3" id="type_audio" style="<?php if($_type==2) echo 'display: flex;';?>">
								<div class="col-md-12"><label>Audio sources </label><small> (Allow type: audio/mp3, audio/ogg)</small></div>
								<div class="col-md-6">
									<div class="input-group">
										<input type="text" class="form-control" name="txt_audio_links[]" value="<?php echo $audio_sourses_1;?>">
										<div class="input-group-append">
											<span class="input-group-text">audio/mp3</span>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="input-group">
										<input type="text" class="form-control" name="txt_audio_links[]" value="<?php echo $audio_sourses_2;?>">
										<div class="input-group-append">
											<span class="input-group-text">audio/ogg</span>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label>Sapo</label>
								<textarea class="form-control" id="txt_sapo" name="txt_sapo" placeholder="Sapo..." rows="3"><?php echo $row['sapo']; ?></textarea>
							</div>
							
							<div class="form-group" id="type_text" style="<?php if($_type==3) echo 'display: block;';?>">
								<label>Nội dung</label>
								<textarea class="form-control" id="txt_fulltext" name="txt_fulltext" placeholder="Nội dung chính..." rows="5"><?php echo $fulltext; ?></textarea>
							</div>

						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Nhóm<font color="red"><font color="red">*</font></font></label>
								<select class="form-control" name="cbo_cate" id="cbo_cate">
									<option value="0">-- Chọn một --</option>
									<?php getListComboboxCategories(0, 0); ?>
								</select>
								<script type="text/javascript">
									$(document).ready(function(){
										cbo_Selected('cbo_cate', <?php echo $row['cat_id']; ?>);
									});
								</script>
							</div>

							<div class="form-group">
								<label>Album<font color="red"><font color="red">*</font></font></label>
								<select class="form-control" name="cbo_album" id="cbo_album">
									<option value="0">-- Chọn một --</option>
									<option value="1">Album 1</option>
									<option value="2">Album 2</option>
									<option value="3">Album 3</option>
									<option value="4">Album 4</option>
								</select>
								<script type="text/javascript">
									$(document).ready(function(){
										cbo_Selected('cbo_album', <?php echo $row['album_id']; ?>);
									});
								</script>
							</div>

							<div class="form-group">
								<label>Chanel<font color="red"><font color="red">*</font></font></label>
								<select class="form-control" name="cbo_chanel" id="cbo_chanel">
									<option value="0">-- Chọn một --</option>
									<?php
									$rschanels = SysGetList('tbl_channels', array(), ' AND isactive=1');
									$n_chanel = count($rschanels);
									for ($i=0; $i < $n_chanel; $i++) { 
										echo '<option value="'.$rschanels[$i]['id'].'">'.$rschanels[$i]['title'].'</option>';
									}
									?>
								</select>
								<script type="text/javascript">
									$(document).ready(function(){
										cbo_Selected('cbo_chanel', <?php echo $row['chanel_id']; ?>);
									});
								</script>
							</div>

							<div class="form-group">
								<label>Type</label>
								<select class="form-control" name="cbo_type" id="cbo_type" onchange="selectVodType()">
									<option value="1">Video</option>
									<option value="2">Audio</option>
									<option value="3">Text</option>
								</select>
								<script type="text/javascript">
									$(document).ready(function(){
										cbo_Selected('cbo_type', <?php echo $row['type']; ?>);
									});
								</script>
							</div>
						</div>
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
		// Hidden left sidebar
		$('#body').addClass('sidebar-collapse');
		$('#frm_action').submit(function(){
			return validForm();
		})

		$('#txt_sapo').summernote({
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

	function selectVodType(){
		var e = document.getElementById("cbo_type");
		var type_id = parseInt(e.options[e.selectedIndex].value);
		if(type_id == 1){
			document.getElementById("type_video").style.display = "flex";
			document.getElementById("type_audio").style.display = "none";
			document.getElementById("type_text").style.display = "none";
		}else if(type_id == 2){
			document.getElementById("type_video").style.display = "none";
			document.getElementById("type_audio").style.display = "flex";
			document.getElementById("type_text").style.display = "none";
		}else if(type_id == 3){
			document.getElementById("type_video").style.display = "none";
			document.getElementById("type_audio").style.display = "none";
			document.getElementById("type_text").style.display = "block";
		}
	}

	function validForm(){
		var flag = true;
		var title = $('#txt_name').val();
		var cate = parseInt($('#cbo_cate').val());
		var album = parseInt($('#cbo_album').val());
		var chanel = parseInt($('#cbo_chanel').val());

		if(title=='' || cate==0 || album==0 || chanel==0){
			alert('Các mục đánh dấu * không được để trống');
			flag = false;
		}
		return flag;
	}
</script>