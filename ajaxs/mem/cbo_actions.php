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
	$isAdmin = getInfo('isadmin');
	$ids = antiData($_POST['ids']);
	$action = antiData($_POST['action']);

	if($ids!=='' && $isAdmin==1 && $action !==''){
		$ids = substr($ids,0,strlen($ids)-1);
		$ids = str_replace(",","','",$ids);

		switch ($action){
			case "public": 
				SysEdit('tbl_member', array('isactive' => 1), "id IN ('".$ids."')");
				break;
			case "unpublic":
				SysEdit('tbl_member', array('isactive' => 0), "id IN ('".$ids."')");
				break;
			case "delete":
				SysDel('tbl_member', "id IN ('".$ids."')");
		        break;
		}
		die('1');
	}else{ die("System don't see data");}

}else{
	die("<h4>Please <a href='".ROOTHOST."'>login</a> to continue!</h4>");
}
?>