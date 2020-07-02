<?php 
var_dump("111"); die();
// require 'resful_api.php';

class api extends resful_api{

	function __construct(){
		parent:: __construct();
	}

	function user(){
		var_dump("expression");
		if($this->method = 'GET'){
			/* Code xử lý LẤY dữ liệu ở đây */
			/* Trả về dữ liệu bằng cách gọi $this->response(200, $data) */

		}elseif($this->method = 'POST'){
			/* Code xử lý THÊM dữ liệu ở đây */
			/* Trả về dữ liệu bằng cách gọi $this->response(200, $data) */

		}elseif($this->method = 'PUT'){
			/* Code xử lý CẬP NHẬT dữ liệu ở đây */
			/* Trả về dữ liệu bằng cách gọi $this->response(200, $data) */

		}elseif($this->method = 'DELETE'){
			/* Code xử lý XÓA dữ liệu ở đây */
			/* Trả về dữ liệu bằng cách gọi $this->response(200, $data) */

		}else{
			var_dump("sdgfsjdhfgdsjfh");
		}
	}
}

$user_api = new api();
?>