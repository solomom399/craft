<?php

require_once 'index.php';

/**
* 
*/
class App extends Craft {
	
	public $msg = array();


	function __construct() {
		$this->SobanjoConnect();

		if (isset($_POST['key']) && $_POST['key'] == 'signup') {
			$user_id = uniqid();
			$fullname = addslashes($_POST['name']);
			$username = addslashes($_POST['username']);
			$email = addslashes($_POST['email']);
			$phone = addslashes($_POST['phone']);
			$password = md5($_POST['password']);
			
			$sql = "INSERT INTO #__user (user_id, fullname, username, email, phone, password) VALUES ('$user_id', '$fullname', '$username', '$email', '$phone', '$password')";

			if ($this->SobanjoQuery($sql)) {
				echo json_encode(array('msg' => $fullname));
			} else {
				echo json_encode(array('msg' => 'error'));
			}
			
		}



		if (isset($_POST['key']) && $_POST['key'] == 'signin') {

			$username = addslashes($_POST['username']);
			$password = md5($_POST['password']);
			
			$sql = "SELECT * FROM #__user WHERE username = '$username' OR email = '$username'";
			$details = $this->SobanjoRow($sql);

			if ($details['password'] == $password) {
				echo json_encode(array('msg' => $details));
			} else {
				echo json_encode(array('msg' => 'error', 'text' => 'Invalid Details'));
			}
			
		}





		if (isset($_POST['key']) && $_POST['key'] == 'check') {

			$input = addslashes($_POST['input']);
			$type = addslashes($_POST['type']);
			
			
			$sql = "SELECT $type FROM #__user WHERE $type = '$input'";

			$row = $this->SobanjoRow($sql);

			if (count($row) != 0) {
				echo json_encode(array('msg' => 'found', 'text' => $type.' exist already!'));
			} else {
				echo json_encode(array('msg' => 'not-found'));
			}
			
		}

	

	}


}



$app = new App();


?>