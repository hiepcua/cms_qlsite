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
	$permission = $_POST['permiss'];
	$user_id = antiData($_POST['user_id']);

	$arr=array();
	$arr['permission'] = $permission;
	
	if($user_id!=''){
		SysEdit('tbl_users', $arr, ' id="'.$user_id.'"');
		die('success');
	}else{ die("System don't see data");}
}else{
	die("<h4>Please <a href='".ROOTHOST."'>login</a> to continue!</h4>");
}
?>