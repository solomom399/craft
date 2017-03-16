<?php

ini_set("log_errors" , "1");
ini_set("error_log" , "errors.txt");
ini_set("display_errors" , "0");
error_log("Oh there");


/**
* 
*/


class Craft {
	

	private $con = false; // Check to see if the connection is active
    private $myconn = "";
    private $eresult = array();
    private $prefix = "fjkd_";

	function __construct()	{


	}

	public function SobanjoConnect(){

		if(!$this->con){
			$this->myconn = new mysqli("127.0.0.1", "root", "", "craft");  // mysql_connect() with variables defined at the start of Database class
            if($this->myconn->connect_errno > 0){
                array_push($this->eresult,$this->myconn->connect_error);
                return false; // Problem selecting database return FALSE
            }else{
                $this->con = true;
                return true; // Connection has been made return TRUE
            } 
        }else{  
            return true; // Connection has already been made return TRUE 
        }  	
	}


	public function SobanjoError ()	{
		for ($i=0; $i < count($this->eresult); $i++) { 
			return $this->eresult[$i];
		}
	}


	public function sql ($sql) {
		return str_replace("#__", $this->prefix, $sql);
	}


	public function SobanjoQuery ($sql)	{
		if(empty($sql)) return false;
			if($this->myconn->query($this->sql($sql))){
				return true;
			} else {
				array_push($this->eresult,$this->myconn->error);
				return false;
			}
	}


	public function SobanjoRows ($sql)	{
		if(empty($sql)) return false;
			if($res = $this->myconn->query($this->sql($sql))){
				$arr = array();
				    // output data of each row
				while($row = $res->fetch_assoc()) {
				    $arr[] = $row;
				}
				
				return $arr;
			} else {
				array_push($this->eresult,$this->myconn->error);
				return false;
			}
	}



	

	public function SobanjoRow ($sql)	{
		if(empty($sql)) return false;
			if($res = $this->myconn->query($this->sql($sql))){
				$row = $res->fetch_assoc();
				return $row;
			} else {
				array_push($this->eresult,$this->myconn->error);
				return false;
			}
	}
}

?>