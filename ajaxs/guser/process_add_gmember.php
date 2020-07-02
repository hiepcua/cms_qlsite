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
	$arr['par_id']=(int)antiData($_POST['par_id']);
	$arr['name']=antiData($_POST['name']);
	$arr['intro']=antiData($_POST['intro']);

	if($arr['name']!=''){
		$lastID = SysAdd('tbl_user_group',$arr);

		$rs_parent = SysGetList('tbl_user_group', array("path"), " AND id=".$arr['par_id']);
		if(count($rs_parent)>0){
			$rs_parent = $rs_parent[0];
			$path = $rs_parent['path'].'_'.$lastID;
		}else{
			$path = $lastID;
		}

		SysEdit('tbl_user_group', array('path' => $path), " id=".$lastID);
		die('success');
	}else{ die("System don't see data");}

}else{
	die("<h4>Please <a href='".ROOTHOST."'>login</a> to continue!</h4>");
}
?>