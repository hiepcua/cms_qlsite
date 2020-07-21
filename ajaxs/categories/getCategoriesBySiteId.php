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
	$site_id = (int)antiData($_POST['id']);
	$arr = array();
	
	if($site_id !== 0){
		$lv0 = SysGetList('tbl_categories', array('id', 'title', 'path'), " AND `site_id`=".$site_id." AND `par_id`=0 AND isactive=1");
		if(count($lv0) > 0){
			foreach ($lv0 as $k => $v) {
				$arr[$v['id']] = $v;
				$childs = SysGetList('tbl_categories', array('id', 'title', 'path'), " AND `site_id`=".$site_id." AND `path` LIKE '".$v['id']."_%' AND isactive=1");

				if(count($childs) > 0){
					foreach ($childs as $ck => $cv) {
						$char = "";
						$level = explode('_', $cv['path']);
						$n_level = count($level);
						if($n_level > 0){
							for($i = 1; $i < $n_level; $i++) $char.="|-----";
						}
						$childs[$ck]['title'] = $char.$cv['title'];
					}
				}
				$arr[$v['id']]['childs'] = $childs;
			}
		}

		echo json_encode($arr);
	}else{
		die('0');
	}
}else{
	die("<h4>Please <a href='".ROOTHOST."'>login</a> to continue!</h4>");
}
?>