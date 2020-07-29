<?php
$_user_global_permission = array();
if(!isset($_SESSION['G_PERMISSION_USER'])){
	$_SESSION['G_PERMISSION_USER'] = $_PERMISSIONS_CONTENT['CTV'];
}

$_user_global_permission = $_SESSION['G_PERMISSION_USER'];
?>