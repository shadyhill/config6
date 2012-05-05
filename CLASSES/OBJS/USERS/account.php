<?php 

include_once dirname(__FILE__)."/../magicobjs.php";

include_once dirname(__FILE__)."/../../SESSION/session.general.php";
include_once dirname(__FILE__)."/../../LIBRARY/LOGS/log.account.php";

class Account extends MagicObjs{
	
	//local class variables
	protected $_httpVars;
	
	public function __construct($vars = array()){
		parent::__construct("accounts");
		$this->_httpVars = $vars;
		
		$this->_sessionObj = new GeneralSession();
		$this->_logObj = new AccountLog();
	}
	
	public function login(){
		/*
		//recieve this data from an AJAX post that lives at /JS/MANAGER/m-login.js
		$user	= strtolower($this->_httpVars['pf_user']);
		$pass 	= $this->_httpVars['pf_pass'];
		$action = $this->_httpVars["pf_action"];
						
		$hasError = false;
		$errorTxt = "";
		
		if(strlen($user) < 8) $hasError = true;
		
		if(strlen($pass) < 6 ) $hasError = true;
		
		if($action != "account-login") $hasError = true;
		
		if($hasError){
			//log
			return "ERROR|Error processing form. Username must be at least 8 characters. Password must be at least 6 characters.";
		}else{
			
			$ePass = md5(PTC_SALT.$pass);
			
			//try to login with the credentials
			$sql = "SELECT id, account_id FROM account_logins WHERE user = '$user' AND pass = '$ePass' AND active = 1";
			$result = mysql_query($sql);
			$myrow = mysql_fetch_array($result);
						
			
			if($myrow['id'] > 0){
				$manager = new Manager();				
				$manager->makeFromData($myrow);
				$manager->makeValid();
				
				//if valid, set the session obj
				$this->_sessionObj->setManagerObj($manager);
				$this->_sessionObj->setManagerSession();
				
				//log the result as positive
				$this->_logObj->logLogin($user,1);
				
				return "SUCCESS";
			}else{
				$this->_logObj->logLogin($user,0);
					
				return "FAIL|Login Failed. Please verify login information and try again.";
			}
		}*/
	}
	
	public function makeFromData($data){
		$this->_id 			= $data['id'];
		$this->_accountID 	= $data['account_id'];
	}
	
	public function returnID(){
		return $this->_id;
	}
	
	public function returnAccountID(){
		return $this->_accountID;
	}
	
	protected function makeValid(){
		$this->_isValid = true;
	}
}	
	
	
	
?>