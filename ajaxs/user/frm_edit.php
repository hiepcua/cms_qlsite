<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
if(isLogin()){
	$arr = array();
	$user = antiData($_POST['user']);
	$objMember = SysGetList('tbl_users', array(), " AND username='$user'", true);
	$r = $objMember[0];

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
	<br/>
	<h3 class='text-center'>Cập nhật thông tin tài khoản</h3>
	<hr>
	<div class='mess' style='color:#f00;'></div>
	<div class='form-group'>
		<label>Nhóm người dùng</label>
		<select class='form-control' id='cbo_group'>
			<option value="0">-- Chọn một --</option>
			<?php getListComboboxGroup(0,0); ?>
		</select>
		<script type="text/javascript">
			$(document).ready(function(){
				cbo_Selected('cbo_group', <?php echo $r['group']; ?>);
			});
		</script>
	</div>
	<div class='form-group'>
		<label>Tên</label>
		<input type='text' class='form-control' id='txt_fullname' value="<?php echo $r['fullname'];?>" placeholder='Tên người dùng'/>
	</div>
	<div class='form-group'>
		<label><i class="fas fa-envelope"></i> EMail</label>
		<input type='text' class='form-control' id='txt_email' value="<?php echo $r['email'];?>" placeholder='EMail'/>
		<input type='hidden' class='form-control' id='txt_user' value="<?php echo $r['username'];?>"/>
	</div>
	<div class='form-group'>
		<label><i class="fas fa-mobile-alt"></i> Số điện thoại</label>
		<input type='text' class='form-control' id='txt_phone' value="<?php echo $r['phone'];?>" placeholder='Số điện thoại'/>
	</div>
	<div class='form-group text-center' >
		<button class='btn btn-primary' id='btn-add-account'><i class="fas fa-save"></i> Cập nhật tài khoản</button>
	</div>
	<script>
		$('#btn-add-account').click(function(){
			if($('#txt_user').val()!='' && $('#txt_fullname').val()!=''){
				var _url='<?php echo ROOTHOST;?>ajaxs/user/process_edit.php';
				var _data={
					'user':$('#txt_user').val(),
					'email':$('#txt_email').val(),
					'fullname':$('#txt_fullname').val(),
					'phone':$('#txt_phone').val(),
					'group':$('#cbo_group').val(),
				}
				$.post(_url,_data,function(req){
					/*console.log(req);*/
					if(req=='success'){
						window.location.reload();
					}else{
						$('.mess').html(req);
					}
				})
			}else{
				$('.mess').html('Email là thông tin bắt buộc!');
			}
		})
	</script>
<?php }else{
	echo "<h4>Please <a href='".ROOTHOST."'>login</a> to continue!</h4>";
}
?>