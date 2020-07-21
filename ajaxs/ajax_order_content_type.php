<?php
session_start();
define('incl_path','../global/libs/');
define('libs_path','../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
if(isLogin()){
	$id = (int)antiData($_POST['id']);
	$val = (int)antiData($_POST['val']);
	
	if($id !== 0){
		SysEdit('tbl_content_type', array('order' => $val), "id=".$id);
		die('1');
	}else{
		die('0');
	}
}else{
	die("<h4>Please <a href='".ROOTHOST."'>login</a> to continue!</h4>");
}
?>