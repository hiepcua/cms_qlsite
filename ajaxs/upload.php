<?php
session_start();
define('incl_path','../global/libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gffunc.php');
// Allowed origins to upload images

$accepted_origins = array("http://localhost");

// Images upload path
$imageFolder = MEDIA_HOST.$_POST['folder'];

reset($_FILES);
$temp = current($_FILES);
if(is_uploaded_file($temp['tmp_name'])){

    function ajx_ReName($name){
        $un_name="";
        $un_name = str_replace(".","_",$name);
        $un_name = un_unicode($un_name);
        $temp = explode("_", $un_name);
        $n = count($temp)-1;
        $name='';
        for ($i=0; $i < $n; $i++) { 
            $name.=$temp[$i];
        }
        return $newfilename = $name.'.'.end($temp);
    }

    function ajx_renameExit($filename, $folder){
        $filename = ajx_ReName($filename);
        if(file_exists($folder.$filename)){
            $temp = explode(".",$filename);
            $newfilename = un_unicode($temp[0]).'_'.jax_rand_string(3).'.'.$temp[1];
            return $newfilename;
        }
        else{
            return $filename;
        }
    }

    function jax_rand_string($length){
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $size = strlen( $chars );
        $str='';
        for( $i = 0; $i < $length; $i++ ) {
            $str .= $chars[ rand( 0, $size - 1 ) ];
        }
        return $str;
    }


    if(isset($_SERVER['HTTP_ORIGIN'])){
        // Same-origin requests won't set an origin. If the origin is set, it must be valid.
        if(in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)){
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
        }else{
            header("HTTP/1.1 403 Origin Denied");
            return;
        }
    }
  
    // Sanitize input
    if(preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])){
        header("HTTP/1.1 400 Invalid file name.");
        return;
    }
  
    // Verify extension
    if(!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))){
        header("HTTP/1.1 400 Invalid extension.");
        return;
    }
  
    // Accept upload if there was no origin, or if it is an accepted origin
    $temp['name'] = ajx_renameExit($temp['name'], $imageFolder);
    $filetowrite = $imageFolder. $temp['name'];
    move_uploaded_file($temp['tmp_name'], $filetowrite);
  
    // Respond to the successful upload with JSON.
    echo json_encode(array('location' => IMAGE_HOST.$_POST['folder'].$temp['name']));
} else {
    // Notify editor that the upload failed
    header("HTTP/1.1 500 Server Error");
}
?>