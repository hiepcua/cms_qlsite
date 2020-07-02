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
	$arr['id']=(int)antiData($_POST['id']);

	if($arr['id']!=0){
		$number = SysCount('tbl_users', " AND `group`=".$arr['id']);
		if($number == 0){
			SysDel('tbl_user_group', " id=".$arr['id']);
			die('success');
		}else{
			die("Các thành viên của nhóm phải bị xóa trước khi xóa thành viên nhóm.");
		}
	}else{ die("System don't see data");}

}else{
	die("<h4>Please <a href='".ROOTHOST."'>login</a> to continue!</h4>");
}
?>