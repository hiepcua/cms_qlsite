<?php
defined('ISHOME') or die('Can not acess this page, please come back!');
$id='';
if(isset($_GET['id']))
	$id=(int)$_GET['id'];

SysEdit('tbl_sites', array('is_trash' => 1), "id=".$id);
echo "<script language=\"javascript\">window.location='".ROOTHOST.COMS."'</script>";
?>