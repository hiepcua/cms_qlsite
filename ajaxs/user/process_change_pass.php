<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');

$user = antiData($_POST['user']);
$pass = antiData($_POST['pass']);
if($user !== ''){
	$newPass = $pass;
	$pass = antiData($newPass);
	$pass = md5($pass);
	$pass = hash('sha256', $user).'|'.hash('sha256', $pass);

	$arr = array('password'=>$pass);
	$strwhere = ' `username`="'.$user.'"';
	$res = SysEdit('tbl_users', $arr, $strwhere);

	if($res){
		die('1');
	}else{
		die('0');
	}
}else{ die("Data is empty");}
?>