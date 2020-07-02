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
	<h3 class='text-center'>Tạo tài khoản</h3>
	<div class='mess' style='color:#f00;'></div>
	<div class='form-group'>
		<label>Nhóm người dùng</label>
		<select class='form-control' id='cbo_group'>
			<option value="0">-- Chọn một --</option>
			<?php getListComboboxGroup(0,0); ?>
		</select>
	</div>
	<div class='form-group'>
		<label>Tên</label>
		<input type='text' class='form-control' id='txt_fullname' placeholder='Tên người dùng'/>
	</div>
	<div class='form-group'>
		<label><i class="fas fa-envelope"></i> EMail</label>
		<input type='text' class='form-control' id='txt_user' placeholder='EMail'/>
	</div>
	<div class='form-group'>
		<label><i class="fas fa-mobile-alt"></i> Số điện thoại</label>
		<input type='text' class='form-control' id='txt_phone' placeholder='Số điện thoại'/>
	</div>
	<div class='form-group text-center' >
		<button class='btn btn-primary' id='btn-add-account'><i class="fas fa-save"></i> Tạo tài khoản</button>
	</div>
	<script>
		$('#btn-add-account').click(function(){
			if($('#txt_user').val()!='' && $('#txt_fullname').val()!=''){
				var _url='<?php echo ROOTHOST;?>ajaxs/user/process_add.php';
				var _data={
					'group':$('#cbo_group').val(),
					'user':$('#txt_user').val(),
					'fullname':$('#txt_fullname').val(),
					'phone':$('#txt_phone').val()
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