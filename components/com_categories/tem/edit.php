<?php
$msg 		= new \Plasticbrain\FlashMessages\FlashMessages();
if(!isset($_SESSION['flash'.'com_'.COMS])) $_SESSION['flash'.'com_'.COMS] = 2;
require_once('libs/cls.upload.php');
$obj_upload = new CLS_UPLOAD();
$file='';
$GetID = isset($_GET['id']) ? (int)$_GET["id"] : 0;

if(isset($_POST['cmdsave_tab1']) && $_POST['txt_name']!='') {
	$Title 			= isset($_POST['txt_name']) ? addslashes($_POST['txt_name']) : '';
	$Intro 			= isset($_POST['txt_intro']) ? addslashes($_POST['txt_intro']) : '';
	$Meta_title 	= isset($_POST['meta_title']) ? addslashes($_POST['meta_title']) : '';
	$Meta_desc 		= isset($_POST['meta_desc']) ? addslashes($_POST['meta_desc']) : '';
	$Par_id 		= isset($_POST['cbo_par']) ? (int)$_POST['cbo_par'] : 0;
	$Images 		= isset($_POST['txt_thumb2']) ? addslashes($_POST['txt_thumb2']) : '';

	if(isset($_FILES['txt_thumb']) && $_FILES['txt_thumb']['size'] > 0){
		$save_path 	= "medias/categories/";
		$obj_upload->setPath($save_path);
		$file = $save_path.$obj_upload->UploadFile("txt_thumb", $save_path);
	}else{
		$file = $Images;
	}

	$arr=array();
	$rs_parent = SysGetList('tbl_categories', array("path"), " AND id=".$Par_id);
	if(count($rs_parent)>0){
		$rs_parent = $rs_parent[0];
		$arr['path'] = $rs_parent['path'].'_'.$GetID;
	}else{
		$arr['path'] = $GetID;
	}

	$arr['title'] = $Title;
	$arr['par_id'] = $Par_id;
	$arr['alias'] = un_unicode($Title);
	$arr['intro'] = $Intro;
	$arr['meta_title'] = $Meta_title;
	$arr['meta_desc'] = $Meta_desc;
	$arr['image'] = $file;

	$result = SysEdit('tbl_categories', $arr, " id=".$GetID);

	if($result) $_SESSION['flash'.'com_'.COMS] = 1;
	else $_SESSION['flash'.'com_'.COMS] = 0;
}

$res_Cate = SysGetList('tbl_categories', array(), ' AND id='. $GetID);
if(count($res_Cate) <= 0){
	echo 'Không có dữ liệu.'; 
	return;
}
$row = $res_Cate[0];

$arr_childs = array();
$res_children = SysGetList('tbl_categories', array('id'), " AND `path` LIKE '".$row['path']."%'");
if(count($res_children) >0){
	foreach ($res_children as $key => $value) {
		$arr_childs[] = $value['id'];
	}
}

$sites = SysGetList('tbl_sites', array('title'), "AND id=".$row['site_id']);
$site = $sites[0];
?>
<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">Cập nhật chuyên mục bài viết</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="<?php echo ROOTHOST;?>">Bảng điều khiển</a></li>
					<li class="breadcrumb-item"><a href="<?php echo ROOTHOST.COMS;?>">Danh sách chuyên mục bài viết</a></li>
					<li class="breadcrumb-item active">Cập nhật chuyên mục bài viết</li>
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
					<div class="mess"></div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Trang</label><font color="red">*</font>
								<input type="text" name="txt_site" value="<?php echo $site['title'];?>" readonly class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Nhóm cha</label>
								<select class="form-control" name="cbo_par" id="cbo_par">
									<option value="0">-- Chọn một --</option>
									<?php getListComboboxCategories(0,0, $arr_childs, $row['site_id']);?>
								</select>
								<script type="text/javascript">
									$(document).ready(function(){
										cbo_Selected('cbo_par', <?php echo $row['par_id']; ?>);
									});
								</script>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Tiêu đề<font color="red"><font color="red">*</font></font></label>
								<input type="text" id="txt_name" name="txt_name" class="form-control" value="<?php echo $row['title'];?>" placeholder="Tiêu đề chuyên mục">
							</div>
						</div>
					</div>

					<div class='form-group'>
						<label>Ảnh </label><small> (Dung lượng < 10MB)</small>
						<div class="widget_thumb80">
							<?php if($row['image'] != ''){ ?>
								<div class="wrap_thumb80">
									<img src="<?php echo ROOTHOST.$row['image'];?>" class="thumb80">
								</div>
							<?php } ?>
							
							<div id="response_img">
								<input type="hidden" name="txt_thumb2" value="<?php echo $row['image'];?>">
								<input type="file" name="txt_thumb" accept="image/jpg, image/jpeg">
							</div>
						</div>
					</div>

					<div class="form-group">
						<label>Mô tả</label>
						<textarea class="form-control" name="txt_intro" placeholder="Mô tả về chuyên mục..." rows="2"><?php echo $row['intro'];?></textarea>
					</div>

					<div class="form-group">
						<label>Meta title</label>
						<textarea class="form-control" name="meta_title" placeholder="Meta title..." rows="2"><?php echo $row['meta_title'];?></textarea>
					</div>

					<div class="form-group">
						<label>Meta description</label>
						<textarea class="form-control" name="meta_desc" placeholder="Meta description..." rows="3"><?php echo $row['meta_desc'];?></textarea>
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
		})
	});

	function validForm(){
		var flag = true;
		var title = $('#txt_name').val();
		var par_id = parseInt($('#cbo_par').val());

		if(title==''){
			alert('Các mục đánh dấu * không được để trống');
			flag = false;
		}
		if(par_id == '<?php echo $GetID?>'){
			flag = false;
			$('.mess').html('Một đối tượng không thể được chọn làm con của chính nó');
		}
		return flag;
	}
</script>