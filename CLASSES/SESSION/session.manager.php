<?php 

require_once dirname(__FILE__)."/session.php";

include_once dirname(__FILE__)."/../OBJS/USERS/manager.php";

class ManagerSession extends Session{
	
	//local class variables
	protected $_manager;
	protected $_auth;
	protected $_activeURL;
	
	public function __construct($mysqli,$prePost = "POST"){
		parent::__construct();
		$this->_mysqli = $mysqli;
		$this->_dir = "manager/login";
		$this->_activeURL = S_CUR_URL;
		$this->setPrePost($prePost);
		$this->_manager = "sMANAGER";
		$this->_auth = "sMAuth";
	}
	
	public function verifyManager(){		
		$this->verifySessionObj($this->_manager);
		$this->verifySessionValue($this->_auth,M_AUTH_STRING);
	}
	
	public function returnSessionManagerID(){
		$manager = $this->getSessionObj($this->_manager);
		if($manager != "") return $manager->returnManagerID();
		else return "";
		
	}
	
	public function returnManagerObj(){
		return $this->getSessionObj($this->_manager);
	}
	
	public function setManagerSession(){
		$this->setSessionValue($this->_auth,M_AUTH_STRING);
	}
	
	public function setManagerObj($manager){
		$this->setSessionObj($this->_manager,$manager);
	}
	
	public function logout(){
		//set the sessions to empty strings
		$_SESSION["$this->_manager"] = "";
		$_SESSION["$this->_auth"] = "";
		
		//then unset them for good measure
		unset($_SESSION["$this->_manager"]);
		unset($_SESSION["$this->_auth"]);
		
		//finally, destroy the session
		session_destroy();
		
		header("Location: ".S_CUR_URL.$this->_dir."/");
		exit();
	}
}

?>