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




		if (isset($_POST['key']) && $_POST['key'] == 'create_shop') {

			$name_of_business = addslashes($_POST['name_of_business']);
			$business_owner = addslashes($_POST['business_owner']);
			$location = addslashes($_POST['location']);
			$business_contact = addslashes($_POST['business_contact']);
			$shop_id = substr(md5(uniqid()), 3, 7);
			$owner_id = addslashes($_POST['user_id']);
			
			$sql = "INSERT INTO #__shop (owner_id, name_of_business, business_owner, location, business_contact, shop_id, date_created, time_created) VALUES ('$owner_id', '$name_of_business', '$business_owner', '$location', '$business_contact', '$shop_id', now(), now())";

			if ($this->SobanjoQuery($sql)) {
				echo json_encode(array('msg' => 'success', 'text' => 'Shop Created'));
			} else {
				echo json_encode(array('msg' => 'error'));
			}
			
		}

	}


}



$app = new App();


?>