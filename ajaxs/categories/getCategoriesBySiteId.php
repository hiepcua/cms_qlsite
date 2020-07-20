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
		function ajax_listComboboxSite($siteid=0, $childs=array(), $root=0){
			if($root == 0){
				$sql="SELECT * FROM tbl_categories WHERE `par_id`='0' AND site_id='$siteid' AND `isactive`='1' ";
			}else{
				$sql="SELECT * FROM tbl_categories WHERE `path` LIKE '$siteid%' AND site_id='$siteid' AND `isactive`='1' ";
			}
			
			$objdata=new CLS_MYSQL();
			$objdata->Query($sql);
			// echo $sql;
			$char="";
			$arr = array();
			
			if($objdata->Num_rows()>0){
				while($rows=$objdata->Fetch_Assoc()){
					$id=$rows['id'];
					$siteid=$rows['site_id'];
					$title=$rows['title'];

					$level = explode('_', $rows['path']);
					$n_level = count($level);
					if($n_level > 0){
						for($i = 0; $i < $n_level; $i++) $char.="|-----";
					}

					if(in_array($id, $childs)){
						array_push($arr, "<option value='$id' disabled='true' class='disabled'>$char $title</option>");
					}else{
						// echo $id.'___';
						array_push($arr, "<option value='$id'>$char $title</option>");
					}
					ajax_listComboboxSite($siteid, $childs);
				}
				// return $arr;
			}

			var_dump($arr);
		}

		$lv0 = SysGetList('tbl_categories', array('id', 'title', 'path'), " AND `site_id`=".$site_id." AND `par_id`=0 AND isactive=1");
		if(count($lv0) > 0){
			foreach ($lv0 as $k => $v) {
				$arr[$v['id']] = $v;
				$childs = SysGetList('tbl_categories', array('id', 'title', 'path'), " AND `site_id`=".$site_id." AND `path` LIKE '".$v['id']."_%' AND isactive=1");
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