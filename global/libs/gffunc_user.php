<?php
function isLogin(){
	if(isset($_SESSION['OTT_USER_LOGIN']['status']) && $_SESSION['OTT_USER_LOGIN']['status']=='yes'){
		$user=getInfo('username');
		if(checkExpires($user)===true) return false;
		return true;
	}
	return false;
}
function getInfo($field){
	$info=isset($_SESSION['OTT_USER_LOGIN']['data'][$field])?$_SESSION['OTT_USER_LOGIN']['data'][$field]:'N/a';
	return $info;
}
function setInfo($field,$val){
	if(isset($_SESSION['OTT_USER_LOGIN']))$_SESSION['OTT_USER_LOGIN']['data'][$field]=$val;
}
function checkExpires($user){
	// sau 1 phút sẽ check lại hệ thống, nếu thấy user có đăng nhập từ một ip mới, sẽ tiến hành logout user hiện tại
	// get session login
	$now=time();
	if(isset($_SESSION['OTT_USER_LOGIN']) && $now-$_SESSION['OTT_USER_LOGIN']['data']['action_time']>=ACTION_TIMEOUT){
		$obj=new CLS_MYSQL;
		$sql="SELECT session FROM tbl_user_login WHERE username='$user' AND isactive=1 ORDER BY id DESC";
		$obj->Query($sql);
		if($obj->Num_rows()>0){
			$r=$obj->Fetch_Assoc();
			if($_SESSION['OTT_USER_LOGIN']['data']['session']!=$r['session']){
				LogOut($user);
				return true;
			}
		}else{
			die('Check Expire error. Please contact administrator!');
		}
	}
	// check time out login
	if(isset($_SESSION['OTT_USER_LOGIN']) && $now-$_SESSION['OTT_USER_LOGIN']['data']['action_time']>=USER_TIMEOUT){
		LogOut($user);
	}
	$_SESSION['OTT_USER_LOGIN']['data']['action_time']=$now;
	return false;
}
function LogIn($user,$pass){
	$arr=array('status'=>'no','data'=>null);
	if($user==''||$pass=='')	return $arr;
	$pass=hash('sha256', $user).'|'.hash('sha256', $pass);
	$fields=array();
	$obj=new CLS_MYSQL;
	if(SysCount("tbl_users"," AND `isactive`=1 AND `username`='$user'")!=1) return $arr;
	$r=SysGetList("tbl_users",$fields," AND `isactive`=1 AND `username`='$user'");
	if($r[0]['password']!=$pass) return $arr;
	$r[0]['action_time']=time();
	$r[0]['session']=time();
	$arr['status']='yes';
	$arr['data']=$r[0];
	$arrAdd=array();
	$arrAdd['username']=$user;
	$arrAdd['session']=time();
	$arrAdd['cdate']=time();
	SysAdd('tbl_user_login',$arrAdd);
	$_SESSION['OTT_USER_LOGIN']=$arr;
	return $arr;
}
function LogOut($user){
	if(isset($_SESSION['OTT_USER_LOGIN'])){
		unset($_SESSION['OTT_USER_LOGIN']);
		unset($_SESSION['G_PERMISSION_USER']);
		
		$sql="UPDATE tbl_user_login SET `isactive`=0 WHERE username='$user'";
		$obj=new CLS_MYSQL;
		$obj->Exec($sql);
	}
}