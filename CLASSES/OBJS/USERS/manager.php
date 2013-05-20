<?php 

include_once dirname(__FILE__)."/../magicobjs.php";

include_once dirname(__FILE__)."/../../SESSION/session.manager.php";
include_once dirname(__FILE__)."/../../LIBRARY/LOGS/log.manager.php";

class Manager extends MagicObjs{
	
	//local class variables
	protected $_hVars;
	protected $_sessionObj;
	protected $_logObj;
	
	public function __construct($vars = array()){
		parent::__construct("managers");
		$this->_hVars = $vars;
		
		$this->_sessionObj = new ManagerSession($this->_mysqli);
		$this->_logObj = new ManagerLog();
	}
	
	public function create(){
		$this->manager_id 	= $this->_httpVars['pmanager_id'];
		$this->company		= $this->_httpVars['pcompany'];
		$pUser				= $this->_httpVars['puser'];
		$pPass				= $this->_httpVars['ppass'];
		$pPhone				= $this->_httpVars['pphone'];
		$pAction			= $this->_httpVars['paction'];
		
		//verify the data
		//filter out bot attempts
		if($pAction == "" || $pAction != "new-manager")	return json_encode(array("status" => "error", "msg" => "Unauthorized form submission. Line 32."));		
		
		//make sure the email address is valid
		if(!filter_var($pUser,FILTER_VALIDATE_EMAIL))	return json_encode(array("status" => "error", "msg" => "Please provide a valid email address for the username."));
		
		//make sure the password is sufficient in length
		if(strlen($pPass) < 6)							return json_encode(array("status" => "error", "msg" => "Passwords must be at least 6 characters in length."));
				
		//make a lowercase and encrypted version of email and phone
		$lEmail = strtolower($pUser);
		$eEmail = base64_encode($lEmail);
		$ePhone = base64_encode($this->onlyNumbers($pPhone));
		
		//create the initial entry
		$this->user			= $eEmail;
		$this->phone		= $ePhone;
		
		//encrypt pass
		$this->salt			= $this->randID(10);		
		$this->pass			= md5(CONFIG6_SALT.$pPass.$this->salt);
		
		//defaults for now
		$this->created		= date('Y-m-d');
		$this->active		= 1;
		$this->permission	= 1;	//eventually this needs to be driven from datbase
		
		$stmt = $this->save();
		
		$this->id = $stmt->insert_id;
		
		if($this->id > 0){
			return json_encode(array("status" => "success", "msg" => "Made the manager with id $this->id"));
		}else{
			return json_encode(array("status" => "error", "msg" => "Unable to create manager."));
		}
		
	}
	
	public function login(){
		
		//recieve this data from an AJAX post that lives at /JS/MANAGER/m-login.js
		$user	= strtolower($this->_httpVars['pf_user']);
		$pass 	= $this->_httpVars['pf_pass'];
		$action = $this->_httpVars["pf_action"];
						
		$hasError = false;
		$errorTxt = "";
		
		if(!filter_var($user,FILTER_VALIDATE_EMAIL)) return json_encode(array("status" => "error", "msg" => "Invalid username. Username must be a valid email address."));
		
		if(strlen($pass) < 6 ) return json_encode(array("status" => "error", "msg" => "Password must be at least 6 characters."));
		
		if($action != "manager-login") return json_encode(array("status" => "error", "msg" => "Invalid status code."));
		
		//get the salt for the user
		$sql = "SELECT salt FROM managers WHERE user = ? AND active = 1";
		
		$stmt = $this->_mysqli->prepare($sql);
		$stmt->bind_param('s',$bUser);	
		$bUser = base64_encode(strtolower($user));		
		$stmt->execute();
		
		$res = $stmt->get_result();
		$row = $res->fetch_array(MYSQLI_ASSOC);
		
		$dbSalt = $row['salt'];
		
		if(strlen($dbSalt) != 10) return json_encode(array("status" => "error", "msg" => "Invalid username. Please provide a valid manager email address."));
				    
		//try to login with the credentials
		$sql = "SELECT id, manager_id FROM managers WHERE user = ? AND pass = ? AND active = 1";
		
		$stmt = $this->_mysqli->prepare($sql);
		$stmt->bind_param('ss',$bUser,$bPass);
		$bUser 	= base64_encode(strtolower($user));
		$bPass 	= md5(CONFIG6_SALT.$pass.$dbSalt);
		
		$stmt->execute();
		
		$res = $stmt->get_result();
		$row = $res->fetch_array(MYSQLI_ASSOC);
		    	
		
		if($row['id'] > 0){
		    $manager = new Manager();				
		    $manager->makeFromData($row);
		    $manager->makeValid();
		    
		    //if valid, set the session obj
		    $this->_sessionObj->setManagerObj($manager);
		    $this->_sessionObj->setManagerSession();
		    
		    //log the result as positive
		    $this->_logObj->logLogin($user,1);
		    
		    return json_encode(array("status" => "success"));
		}else{
		    $this->_logObj->logLogin($user,0);
		    	
		    return json_encode(array("status" => "fail", "msg" => "Invalid login, please try again."));
		}
		
	}
	
	//override the magicobjs makeFromData because we want protected variables and only a select number of them
	public function makeFromData($data){
		$this->_id 			= $data['id'];
		$this->_managerID 	= $data['manager_id'];
	}
	
	public function returnID(){
		return $this->_id;
	}
	
	public function returnManagerID(){
		return $this->_managerID;
	}
	
	protected function makeValid(){
		$this->_isValid = true;
	}
}	
	
?>