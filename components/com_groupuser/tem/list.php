<?php
$user=getInfo('username');
$isAdmin=getInfo('isadmin');
define('OBJ_PAGE','GUSER');
if($isAdmin==1){
	?>
	<!-- Content Header (Page header) -->
	<div class="widget-tree" style="padding-left: 7px;">
		<header class="header">
			<h2><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp&nbspThêm mới nhóm người dùng</h2>
		</header>
	</div>

	<!-- /.content-header -->
	<!-- Main content -->
	<section class="content">
		<div class='container-fluid'>
			<div class="card">
				<form id="frm_add" class="smart-form" method="post" action="" style="padding: 15px 10px;">
					<div class="mess" style="color: red;"></div>
					<div class="row">
						<div class="col-md-6 form-group">
							<label class="label">Nhóm<small class="cred">(*)</small></label>
							<select id="cbo_par" name="cbo_par" class="form-control">
								<option value="0">-- Chọn một --</option>
								<?php getListCombobox(0,0); ?>
							</select>
						</div>
						<div class="col-md-6 form-group">
							<label class="label">Tên danh mục<small class="cred">(*)</small></label>
							<input type="text" name="txt_name" id="txt_name" class="form-control">
						</div>
					</div>
					<div class="rows">
						<div class="col-md-12 form-group">
							<label class="label">Mô tả</label>
							<textarea class="form-control" rows="3" id="txt_desc" name="txt_desc"></textarea>
						</div>
					</div>
					<div class="rows">
						<div class="col-md-12 form_wrap_tool">
							<a href="#" onclick="AddGroupMember()" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>Thêm</a>
							<button type="button" class="btn btn-primary" name="cmd_cancel" id="cmd_cancel" onclick="backPage()"><i class="fa fa-refresh" aria-hidden="true"></i> Hủy bỏ</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>
	<script>
		function AddGroupMember(){
			if(frm_validate()){
				var _url="<?php echo ROOTHOST;?>ajaxs/guser/process_add_gmember.php";
				var _data={
					'par_id': $('#cbo_par').val(),
					'name': $('#txt_name').val(),
					'intro': $('#txt_desc').val()
				}
				$.post(_url, _data, function(req){
					if(req=='success'){
						window.location.reload();
					}else{
						$('.mess').html(req);
					}
				})
			}
		};

		function frm_validate(){
			var flag=true;
			var par_id = parseInt($('#cbo_par').val());
			var name = $('#txt_name').val();
			if(name == '') {
				flag = false;
				$('.mess').html('Tên danh mục không được để trống.');
			}
			if(par_id == 0){
				flag = false;
				$('.mess').html('Nhóm danh mục không được bỏ trống');
			}
			return flag;
		};

		function backPage() {
			window.history.back();
		}
	</script>
<?php }else{
	echo "<h3 class='text-center'>You haven't permission</h3>";
}?>