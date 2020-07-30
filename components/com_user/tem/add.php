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

	if(isset($_FILES['txt_thumb']) && $_FILES['txt_thumb']['size'] > 0){
		$save_path 	= "medias/categories/";
		$obj_upload->setPath($save_path);
		$file = $save_path.$obj_upload->UploadFile("txt_thumb", $save_path);
	}

	$arr=array();
	$arr['title'] = $Title;
	$arr['par_id'] = $Par_id;
	$arr['site_id'] = $Site_id;
	$arr['alias'] = un_unicode($Title);
	$arr['intro'] = $Intro;
	$arr['meta_title'] = $Meta_title;
	$arr['meta_desc'] = $Meta_desc;
	$arr['image'] = $file;

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

function getListComboboxGroup($parid=0, $level=0){
	$sql="SELECT * FROM tbl_user_group WHERE `par_id`='$parid' AND `isactive`='1' ";
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
		$title=$rows['name'];
		echo "<option value='$id'>$char $title</option>";
		$nextlevel=$level+1;
		getListComboboxGroup($id,$nextlevel);
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
					<div class="row">
						<div class="col-md-8 col-sm-9">
							<div class="form-group">
								<label>Tên<font color="red">*</font></label>
								<input type="text" id="txt_name" name="txt_name" class="form-control" value="" placeholder="Tên người dùng">
							</div>

							<div class="form-group">
								<label><i class="fas fa-envelope"></i> Email</label>
								<input type="text" id="txt_email" name="txt_email" class="form-control" value="">
							</div>

							<div class="form-group">
								<label><i class="fas fa-mobile-alt"></i> Số điện thoại</label>
								<input type="text" id="txt_phone" name="txt_phone" class="form-control" value="">
							</div>
						</div>

						<div class="col-md-4 col-sm-3">
							<div class="form-group">
								<label>Nhóm người dùng</label>
								<select class='form-control' id='cbo_group'>
									<option value="0">-- Chọn một --</option>
									<?php getListComboboxGroup(0,0); ?>
								</select>
							</div>

							<div class="form-group">
								<label>Trang</label>
								<select class="form-control" name="cbo_par" id="cbo_par">
									<option value="0">-- Chọn một --</option>
									<?php getListComboboxSites(0,0);?>
								</select>
							</div>

							<div class="form-group">
								<label>Bút danh</label>
								<input type="text" id="txt_pseudonym" name="txt_pseudonym" class="form-control" value="">
							</div>
						</div>
					</div>
					<?php $res_categories = SysGetList('tbl_categories', array('id', 'title'), "AND isactive=1");?>
					<h4>Quyền người dùng</h4><hr/>
					<label>Quyền bài viết</label>
					<div class="row" id="wg-permission">
						<div class="col-sm-3 col-md-3 col-xs-6 item">
							<div class="header">Quyền viết bài <label class="check-all"><input type="checkbox" id="vb-check-all" name="chk_cates[]" value="">All</label></div>
							<ul class="list-unstyle vb-check-all">
								<?php
								foreach ($res_categories as $k => $v) {
									echo '<li><label><input type="checkbox" name="chk_cates[]" value="'.$v['id'].'"> '.$v['title'].'</label></li>';
								}
								?>
							</ul>
						</div>

						<div class="col-sm-3 col-md-3 col-xs-6 item">
							<div class="header">Quyền biên tập <label class="check-all"><input type="checkbox" id="bt-check-all" name="chk_cates[]" value="">All</label></div>
							<ul class="list-unstyle bt-check-all">
								<?php
								foreach ($res_categories as $k => $v) {
									echo '<li><label><input type="checkbox" name="chk_cates[]" value="'.$v['id'].'"> '.$v['title'].'</label></li>';
								}
								?>
							</ul>
						</div>

						<div class="col-sm-3 col-md-3 col-xs-6 item">
							<div class="header">Quyền xuất bản <label class="check-all"><input type="checkbox" id="xb-check-all" name="chk_cates[]" value="">All</label></div>
							<ul class="list-unstyle xb-check-all">
								<?php
								foreach ($res_categories as $k => $v) {
									echo '<li><label><input type="checkbox" name="chk_cates[]" value="'.$v['id'].'"> '.$v['title'].'</label></li>';
								}
								?>
							</ul>
						</div>

						<div class="col-sm-3 col-md-3 col-xs-6 item">
							<div class="header">Quyền gỡ bài <label class="check-all"><input type="checkbox" id="gb-check-all" name="chk_cates[]" value="">All</label></div>
							<ul class="list-unstyle gb-check-all">
								<?php
								foreach ($res_categories as $k => $v) {
									echo '<li><label><input type="checkbox" name="chk_cates[]" value="'.$v['id'].'"> '.$v['title'].'</label></li>';
								}
								?>
							</ul>
						</div>
					</div>

					<!-- <table class="table table-bordered text-center" id="tbl_permission">
						<tr>
							<th>#</th>
							<th>All</th>
							<th>Thêm mới</th>
							<th>Cập nhật</th>
							<th>Phê duyệt</th>
							<th>Xuất bản</th>
							<th>Trả BTV</th>
							<th>Trả PV</th>
							<th>Gỡ bài</th>
							<th>Xóa</th>
						</tr>
						<tr class="ctv_chk_all">
							<td>CTV</td>
							<td><input type="checkbox" name="" id="ctv_chk_all"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1001"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1002"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1003"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1004"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1005"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1006"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1007"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1008"></td>
						</tr>
						<tr class="pv_chk_all">
							<td>PV</td>
							<td><input type="checkbox" name="" id="pv_chk_all"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1001"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1002"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1003"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1004"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1005"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1006"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1007"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1008"></td>
						</tr>
						<tr class="btv_chk_all">
							<td>BTV</td>
							<td><input type="checkbox" name="" id="btv_chk_all"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1001"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1002"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1003"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1004"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1005"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1006"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1007"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1008"></td>
						</tr>
						<tr class="tk_chk_all">
							<td>TK</td>
							<td><input type="checkbox" name="" id="tk_chk_all"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1001"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1002"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1003"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1004"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1005"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1006"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1007"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1008"></td>
						</tr>
						<tr class="pbt_chk_all">
							<td>PBT</td>
							<td><input type="checkbox" name="" id="pbt_chk_all"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1001"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1002"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1003"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1004"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1005"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1006"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1007"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1008"></td>
						</tr>
						<tr class="tbt_chk_all">
							<td>TBT</td>
							<td><input type="checkbox" name="" id="tbt_chk_all"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1001"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1002"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1003"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1004"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1005"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1006"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1007"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1008"></td>
						</tr>
						<tr class="admin_chk_all">
							<td>Admin</td>
							<td><input type="checkbox" name="" id="admin_chk_all"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1001"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1002"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1003"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1004"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1005"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1006"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1007"></td>
							<td><input type="checkbox" name="chk_permission[]" value="1008"></td>
						</tr>
					</table> -->
					
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

	$("#vb-check-all, #bt-check-all, #xb-check-all, #gb-check-all").on('click', function(){
		var id = this.id;
		if(this.checked == true){
			$('.'+id).find('input').attr('checked','checked');
		}else{
			$('.'+id).find('input').removeAttr('checked');
		}
	});
</script>