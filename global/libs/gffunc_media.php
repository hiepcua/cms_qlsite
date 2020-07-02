<?php
/*
creat by: TUYENNX
creat date: 17/03/2020
--------------------------------
php.ini config
upload_max_filesize = 50M
post_max_size = 50M
max_input_time = 300
max_execution_time = 300
--------------------------------
htaccess config
php_value upload_max_filesize 50M
php_value post_max_size 50M
php_value max_input_time 300
php_value max_execution_time 300
*/
function verbose($ok=1,$info=""){
  if ($ok==0) { http_response_code(400); }
  die(json_encode(["ok"=>$ok, "info"=>$info]));
}
function uploadMedia($filePath,$fileSource,$chunk,$chunks){
	$out = fopen("{$filePath}.part", "wb");
	if ($out) {
		$in = fopen($fileSource, "rb");
		if ($in) {
			while ($buff = fread($in, $chunks)) { fwrite($out, $buff); }
		} else {
			verbose(1, "Failed to open input stream");
		}
		@fclose($in);
		@fclose($out);
		@unlink($fileSource);
	} else {
		verbose(1, "Failed to open output stream");
	}
	rename("{$filePath}.part", $filePath);
}
function uploadFile($_file, $allowed=null, $filename=null) {
	if(!$filename) $filename = date('ymdHis');
	$f = 'uploads/'.$filename.'.'.$extension; 
	$file = $filename.'.'.$extension;
	if(move_uploaded_file($_FILES[$_file]['tmp_name'], $f)){
		$miniType = mime_content_type($f);
		if($miniType == "application/octet-stream"){
			$im = imagecreatefromwebp($f);
			imagejpeg($im, 'uploads/'.$filename.'.jpeg', 100);
			imagedestroy($im);
			$file = $filename.'.jpeg';
			unlink($f);
		}
		return $file; 
	}
	else return false;
}
function downloadFile($url, $allowed = null, $k = '')
{
	if (stripos($url, '?')) {
		$tmp_exp = explode('?', $url);
		$url = array_shift($tmp_exp);
	}
	$tmp_t = explode('.', $url);
	$extension = strtolower(end($tmp_t));
	$filename = $this->toSlug(basename(rtrim($url, '.' . $extension))) . '-' . date('His') . $k;
	if ($allowed && !in_array($extension, $allowed)) return false;
	$data = @file_get_contents($this->encodeURI($url));
	if ($data) {
		$upload = file_put_contents('uploads/' . $filename . '.' . $extension, $data);
		if ($upload) return $filename . '.' . $extension;
	} else return false;
}

function moveMedia($filename, $username, $time = null, $svr = 'IMAGE')
{
	if (!$filename) return false;
	if (!$time) $time = time();
	$clsSystem = new System($svr);
	$folder = '/files/' . $username . '/' . date('Y/m/d/', $time);
	if(USE_FTP&&isset($_COOKIE['thang'])){
		$conn_id = ftp_connect(FTP_SERVER);
		$login_result = ftp_login($conn_id, FTP_USERNAME, FTP_PASSWORD);
		if ((!$conn_id) || (!$login_result)) die("FTP connection has encountered an error 1!");
		$paths = explode('/', $folder);
		foreach($paths as $part){
		  if($part!=''&&!@ftp_chdir($conn_id, $part)){
			 ftp_mkdir($conn_id, $part);
			 ftp_chdir($conn_id, $part);
			 // ftp_chmod($conn_id, 0755, $part);
		  }
		}
		// turn passive mode on
		ftp_pasv($conn_id, true);
		$upload = ftp_put($conn_id, $folder.$filename, 'uploads/' .$filename, FTP_ASCII);
		ftp_close($conn_id);
		if (!$upload) {
			return false; //{print_r(error_get_last()); die();}
		}else {
			unlink('uploads/' .$filename);
			return $folder.$filename;
		}
	}else{
		
		$res = $folder . $filename;
		$folder = $clsSystem->home_dir . $folder;
		$clsSystem->ssh('mkdir -p ' . $folder . '; chmod 777 ' . $folder . '/;');
		$clsSystem->sendFile('uploads/' . $filename, $folder . $filename);
		sleep(2);
		unlink('uploads/' . $filename);
		return $res;
	}
	
}