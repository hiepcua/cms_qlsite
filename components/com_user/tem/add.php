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
						<div class="col-md-6">
							<label>Nhóm người dùng</label><font color="red">*</font>
							<select class='form-control' id='cbo_group'>
								<option value="0">-- Chọn một --</option>
								<?php getListComboboxGroup(0,0); ?>
							</select>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
								<label>Tên<font color="red">*</font></label>
								<input type="text" id="txt_name" name="txt_name" class="form-control" value="" placeholder="Tên người dùng">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label><i class="fas fa-envelope"></i> Email</label>
								<input type="text" id="txt_email" name="txt_email" class="form-control" value="">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label><i class="fas fa-mobile-alt"></i> Số điện thoại</label>
								<input type="text" id="txt_phone" name="txt_phone" class="form-control" value="">
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
</section>
<!-- /.row -->
<!-- /.content-header -->
<script type="text/javascript">
	$(document).ready(function(){
		$('#frm_action').submit(function(){
			return validForm();
		});
	});

	function changeSite(){
		var site_id = $('#cbo_site').val();
		$.post('<?php echo ROOTHOST;?>ajaxs/categories/getCategoriesBySiteId.php', {'id': site_id}, function(req){
			if(req !== '0'){
				var html="<option value='0'>-- Root --</option>";
				var res = JSON.parse(req);
				for (const property in res) {
					var item = res[property];
					html+= "<option value='"+item['id']+"'>"+item['title']+"</option>";

					if(item['childs'].length >0){
						for (const pr in item['childs']) {
							var char = "";
							var childs = item['childs'][pr];

							var lv = childs['path'].split('_');
							if (lv.length>0) {
								for(var i_lv=0; i_lv < lv.length; i_lv++)
									char+="|-----";
							}
							html+= "<option value='"+childs['id']+"'>"+char+childs['title']+"</option>";
						}
					}
				}
				$('#cbo_par').html(html);
			}else{
				console.log('err');
			}
		});
	}

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