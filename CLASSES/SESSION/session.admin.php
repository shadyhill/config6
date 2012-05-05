<?php 

require_once dirname(__FILE__)."/session.php";

include_once dirname(__FILE__)."/../OBJS/USERS/ADMIN/admin.php";

class AdminSession extends Session{
	
	//local class variables
	protected $_admin;
	protected $_auth;
	protected $_activeURL;
	
	public function __construct($prePost = "POST"){
		parent::__construct();
		$this->_dir = "admin/login";
		$this->_activeURL = S_CUR_URL;
		$this->setPrePost($prePost);
		$this->_admin = "sADMIN";
		$this->_auth = "sAAuth";
	}
	
	public function verifyAdmin(){
		$this->verifySessionObj($this->_admin);
		$this->verifySessionValue($this->_auth,A_AUTH_STRING);
	}
	
	
	public function returnSessionAdminID(){
		$manager = $this->getSessionObj($this->_admin);
		if($manager != "") return $manager->returnAdminID();
		else return "";
		
	}
	
	public function logout(){
		//set the sessions to empty strings
		$_SESSION["$this->_admin"] = "";
		$_SESSION["$this->_auth"] = "";
		
		//then unset them for good measure
		unset($_SESSION["$this->_admin"]);
		unset($_SESSION["$this->_auth"]);
		
		//finally, destroy the session
		session_destroy();
		
		header("Location: ".S_CUR_URL.$this->_dir."/");
		exit();
	}
}

?>