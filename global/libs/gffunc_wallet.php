<?php
function getTotalBonus($tbl_wallet,$type,$username){
	$strWhere='';
	if($type>0) $strWhere.=" AND money>=0";
	else $strWhere.=" AND money<0";
	$sql="SELECT SUM(`money`) as total FROM $tbl_wallet WHRE username='$username' $strWhere";
	$obj=new CLS_MYSQL;
	$obj->Query($sql);
	$r=$obj->Fetch_Assoc();
	return $r['total'];
}
function getWalletBalance($tbl_wallet,$username){
	$sql="SELECT SUM(`money`) as total FROM $tbl_wallet WHERE `status`=1 AND `username`='$username'";
	$obj=new CLS_MYSQL;
	$obj->Query($sql);
	$r=$obj->Fetch_Assoc();
	return $r['total'];
}