<?php 
require 'restful_api.php';

class user extends restful_api {

	function __construct(){
		parent::__construct();
	}

	function random_user(){
		if ($this->method == 'GET'){
			$connection = mysql_connect("localhost", 'root', '');
			mysql_select_db("cms_dhqg", $connection);
			$query = mysql_query("select * from tbl_users ORDER BY RAND() LIMIT 1");
			$data = array();
			while($row = mysql_fetch_object($query)){
				$data[] = $row;
			}
			$this->response(200, $data);
		}
	}
}

$user_api = new user();
?>