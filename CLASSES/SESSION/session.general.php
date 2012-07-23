<?php 

require_once dirname(__FILE__)."/session.php";

include_once dirname(__FILE__)."/../OBJS/USERS/ACCOUNT.php";

class GeneralSession extends Session{
	
	//local class variables
	protected $_auth;
	protected $_activeURL;
	
	public function __construct($myslqi,$prePost = "POST"){
		parent::__construct();
		$this->_mysqli = $mysqli;
		$this->_dir = "login";
		$this->_activeURL = S_CUR_URL;
		$this->setPrePost($prePost);
		$this->_auth = "sGAuth";
	}
	
	public function verifyGeneral(){
		//$this->verifySessionObj($this->_manager);
		//$this->verifySessionValue($this->_auth,M_AUTH_STRING);
	}
	
	


	
	public function logout(){
		//set the sessions to empty strings
		$_SESSION["$this->_auth"] = "";
		
		//then unset them for good measure
		unset($_SESSION["$this->_auth"]);
		
		//finally, destroy the session
		session_destroy();
		
		header("Location: ".S_CUR_URL.$this->_dir."/");
		exit();
	}
}

?>