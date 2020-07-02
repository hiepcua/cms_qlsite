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
	$arr['par_id']=(int)antiData($_POST['par_id']);
	$arr['name']=antiData($_POST['name']);
	$arr['intro']=antiData($_POST['intro']);

	$rs_parent = SysGetList('tbl_user_group', array("path"), " AND id=".$arr['par_id']);
	if(count($rs_parent)>0){
		$rs_parent = $rs_parent[0];
		$arr['path'] = $rs_parent['path'].'_'.$arr['id'];
	}else{
		$arr['path'] = $arr['id'];
	}

	$sql="SELECT GroupMember_GetFamilyTree(".$arr['id'].") AS 'childs'";
	$objmysql = new CLS_MYSQL();
	$objmysql->Query($sql);
	$childs = $objmysql->Fetch_Assoc();
	$childID = $childs['childs'];
	$arr_childID = explode(',', $childID);
	array_push($arr_childID, $arr['id']);

	if($arr['id']!=0 && !in_array($arr['par_id'], $arr_childID)){
		SysEdit('tbl_user_group', $arr, " id=".$arr['id']);
		die('success');
	}else{ die("System don't see data");}

}else{
	die("<h4>Please <a href='".ROOTHOST."'>login</a> to continue!</h4>");
}
?>