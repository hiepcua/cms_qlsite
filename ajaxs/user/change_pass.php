<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');

$isAdmin = getInfo('isadmin');
$user_id = antiData($_POST['user_id']);

if(!$isAdmin) die("Bạn không có quyền thực hiện hành động này!");

if($user_id != ''){
	$strWhere=" AND `id` = '".$user_id."'";
	$res_Users = SysGetList('tbl_users', [], $strWhere);
	$row = $res_Users[0];
	?>
	<br>
	<h3 class='text-center'>Đổi mật khẩu</h3>
	<div class='jaxs_mess' style='color:#f00;'></div>

	<div class='form-group'>
		<label><i class="fa fa-user-circle" aria-hidden="true"></i> Tên đăng nhập</label>
		<input type='text' id='ajax_txt_username' class='form-control' value="<?php echo $row['username'];?>" readonly placeholder='Tên đăng nhập'/>
	</div>

	<div class='form-group'>
		<label><i class="fa fa-key" aria-hidden="true"></i> Mật khẩu mới</label>
		<input type='text' class='form-control' id='ajax_txt_fullname' placeholder='Mật khẩu ít nhất 6 ký tự.'/>
	</div>
	
	<div class='form-group text-center' >
		<button class='btn btn-primary' id='btn-save'><i class="fas fa-save"></i> Đổi mật khẩu</button>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			$('#btn-save').on('click', function(){
				var pass = $('#ajax_txt_fullname').val();
				if(pass.length < 6){
					$('.jaxs_mess').html('Mật khẩu ít nhất 6 ký tự.');
				}else{
					var _url='<?php echo ROOTHOST;?>ajaxs/user/process_change_pass.php';
					var _data={
						'pass': pass,
						'user': $('#ajax_txt_username').val()
					}
					$.post(_url,_data,function(req){
						if(req=='1'){
							$('#popup_modal .modal-body').html('<p style="padding-top: 20px;">Đổi mật khẩu thành công!</p>');
						}else{
							$('.jaxs_mess').html('Lỗi, xin vui lòng thử lại.');
						}
					})
				}
			});
		});
	</script>
	<?php
}else{ die("Data is empty");}
?>