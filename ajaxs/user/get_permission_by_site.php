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
	$arr=array();
	$sites = antiData($_GET['sites']);
	$ar_sites = explode(",", $sites);

	foreach ($ar_sites as $key => $value) {
		$arr[$value] = SysGetList('tbl_categories', array('id', 'title', 'site_id'), "AND isactive=1 AND site_id=".$value);
		$sites = SysGetList('tbl_sites', array('domain'), "AND isactive=1 AND id=".$value);
		$arr[$value]['site_doamain'] = $sites[0]['domain'];
	}

	if(count($arr) <= 0) return 'Không có dữ liệu';
	foreach ($arr as $k => $v) {
		?>
		<div class="wg-permission">
			<h4>Quyền trang <?php echo $v['site_doamain']; ?></h4>
			<div class="row">
				<div class="col-sm-3 col-md-3 col-xs-6 item">
					<div class="header">Quyền viết bài <label class="check-all"><input type="checkbox" data-site="<?php echo $v['site_doamain'];?>" data-active="chk_cates_vb_<?php echo $v['site_doamain'];?>" onclick="check_all_childs(this)" value="">All</label></div>
					<ul class="list-unstyle <?php echo $v['site_doamain'];?>-vb-check-all">
						<?php
						if(count($v) > 1){
							for($i=0; $i < count($v)-1; $i++){
								echo '<li><label><input type="checkbox" class="chk_cates_vb_'.$v['site_doamain'].'" name="chk_cates_vb[]" value="'.$v[$i]['id'].'"> '.$v[$i]['title'].'</label></li>';
							}
						}
						?>
					</ul>
				</div>

				<div class="col-sm-3 col-md-3 col-xs-6 item">
					<div class="header">Quyền biên tập <label class="check-all"><input type="checkbox" data-site="<?php echo $v['site_doamain'];?>" class="bt-check-all" value="">All</label></div>
					<ul class="list-unstyle bt-check-all">
						<?php
						if(count($v) > 1){
							for($i=0; $i < count($v)-1; $i++){
								echo '<li><label><input type="checkbox" name="chk_cates_bt[]" value="'.$v[$i]['id'].'"> '.$v[$i]['title'].'</label></li>';
							}
						}
						?>
					</ul>
				</div>

				<div class="col-sm-3 col-md-3 col-xs-6 item">
					<div class="header">Quyền xuất bản <label class="check-all"><input type="checkbox" data-site="<?php echo $v['site_doamain'];?>" class="xb-check-all" value="">All</label></div>
					<ul class="list-unstyle xb-check-all">
						<?php
						if(count($v) > 1){
							for($i=0; $i < count($v)-1; $i++){
								echo '<li><label><input type="checkbox" name="chk_cates_xb[]" value="'.$v[$i]['id'].'"> '.$v[$i]['title'].'</label></li>';
							}
						}
						?>
					</ul>
				</div>

				<div class="col-sm-3 col-md-3 col-xs-6 item">
					<div class="header">Quyền gỡ bài <label class="check-all"><input type="checkbox" data-site="<?php echo $v['site_doamain'];?>" class="gb-check-all" value="">All</label></div>
					<ul class="list-unstyle gb-check-all">
						<?php
						if(count($v) > 1){
							for($i=0; $i < count($v)-1; $i++){
								echo '<li><label><input type="checkbox" name="chk_cates_gb[]" value="'.$v[$i]['id'].'"> '.$v[$i]['title'].'</label></li>';
							}
						}
						?>
					</ul>
				</div>
			</div>
		</div><br/>
		<?php
	}
}else{
	die("<h4>Please <a href='".ROOTHOST."'>login</a> to continue!</h4>");
}
?>
<script type="text/javascript">
	function check_all_childs(props){
		var clas = props.attributes['data-active'].value;
		var el = document.getElementsByClassName(clas);
		if(props.checked == true){
			// debugger;
			for (var item in el) {
				el[item].setAttribute('checked', 'checked');
			}
		}else{
			// debugger;
			for (var item in el) {
				el[item].removeAttribute('checked', 'checked');
			}
		}
	}
</script>