<?php 

include_once dirname(__FILE__)."/../magicobjs.php";

include_once dirname(__FILE__)."/../../SESSION/session.general.php";
include_once dirname(__FILE__)."/../../LIBRARY/LOGS/log.account.php";

class Account extends MagicObjs{
	
	//local class variables
	protected $_hVars;
	protected $_sessionObj;
	protected $_logObj;
	
	protected $_id;
	protected $_uuid;
	
	public function __construct($vars = array(),$sess = ""){
		parent::__construct("accounts");
		$this->_hVars = $vars;
		
		if($sess != "") $this->_sessionObj = $sess;
		else $this->_sessionObj = new GeneralSession($this->_mysqli);
		$this->_logObj = new AccountLog();
	}
	
	//this is the public facing create call
	public function create(){
		$this->createInDB();
		if($this->id > 0){		
			//forward to order page
			return json_encode(array("status" => "success", "url" => "account/please-confirm/"));
		}else{
			return json_encode(array("status" => "error", "msg" => "Unable to create an account. An account with this email address may already exist."));
		}
	}
	
	private function createInDB(){
		//get the serialized post data
		parse_str($_POST['fdata'], $pData);
		$pAction	= $this->_hVars['pf_action'];
		
		$pEmail = strtolower($pData['email']);
		$pPass	= $pData['password'];
		$pPass2 = $pData['password2'];
		
		//verify data
		if($pAction != "create-account") return json_encode(array("status" => "error", "msg" => "Unauthorized form submission."));			
		if(!filter_var($pEmail,FILTER_VALIDATE_EMAIL)) return json_encode(array("status" => "error", "msg" => "Please provide a valid email address."));		
		if(strlen($pPass) < 6) return json_encode(array("status" => "error", "msg" => "Passwords must be at least 6 characters in length."));		
		if($pPass != $pPass2) return json_encode(array("status" => "error", "msg" => "Passwords do not match."));
		
		//create uuid
		$this->uuid = uniqid('',true);
		
		//encrypt email		
		$this->username	= base64_encode($pEmail);
		
		$this->salt	= $this->randID(12);		
		
		//create the hash		
		$this->password = substr(crypt($pPass,'$2a$13$'.md5($this->salt)),28);
		
		//defaults for now
		$this->created		= date('Y-m-d');
		$this->status		= 0;
		
		//confirmation info
		$this->confirmation = md5($pEmail).$this->randID(8);
		
		$stmt = $this->save();
		
		$this->id = $stmt->insert_id;
		
		if($this->id > 0){
			//send email
			$url = S_CUR_URL."EMAIL/accountRegistrationConfirmation/";
			$salted = md5(CONFIG6_SALT.$pEmail);
			$params = array("email" => $pEmail, "confirm" => $this->confirmation, "verify" => $salted);
			$this->fsockSend($url,$params);
		}
	}
	
	public function confirmation(){
		//in this case we need to get the url vars out of the get
		//but the url is going to be different than what the processor has
		$gURL = $_GET['url'];
		$urlVars = explode("/",$gURL);
		$gCode = $urlVars[2];
		
		if(strlen($gCode) != 40){
			header("Location: ".S_CUR_URL."account/sign-in/s=invalid-confirmation");
			exit();
		}
		
		$sql = "SELECT id, mm_id, username FROM accounts WHERE confirmation = ? AND status = 0";
		
		$stmt = $this->_mysqli->prepare($sql);
		$stmt->bind_param('s',$bConfirm);	
		$bConfirm = $gCode;
		$stmt->execute();
		
		$res = $stmt->get_result();
		$row = $res->fetch_array(MYSQLI_ASSOC);
					
		if($row['id'] > 0){
			//get existing data from db
			$this->makeFromData($row);
			
			//update with active information
			$this->status 		= 1;
			$this->status_txt 	= "ACTIVE";
			$this->confirmation = "CONFIRMED";		//confirmation gets set to null?
			$this->confirmed	= 1;
			$this->token		= md5($this->uuid.$this->randID(24));
			$this->save();
												
			//save user to session
			//$this->makeValid();
			//$this->_sessionObj->setAccountObj($account);
			//$this->_sessionObj->setAccountSession();
			
			//write new cookie
			//setcookie("mm-tk",$this->token, time()+(60*60*24*365*2),"/");	
			
			//redirect user to sign in 			
			header("Location: ".S_CUR_URL."account/sign-in/?s=confirmed");
			exit();
			
		}else{
			header("Location: ".S_CUR_URL."account/sign-in/?s=invalid-confirmation");
			exit();
		}
		
	}
	
	public function login(){
		
		//recieve this data from an AJAX post
		parse_str($_POST['fdata'], $pData);
		$pAction	= $this->_hVars['pf_action'];
		
		$pEmail = strtolower($pData['email']);
		$pPass	= $pData['pass'];
		
		if($pAction != "account-login") return json_encode(array("status" => "error", "msg" => "Unauthorized form submission."));			
		if(!filter_var($pEmail,FILTER_VALIDATE_EMAIL)) return json_encode(array("status" => "error", "msg" => "Please provide a valid email address."));		
		if(strlen($pPass) < 6) return json_encode(array("status" => "error", "msg" => "Passwords must be at least 6 characters in length."));
		
		//get the salt for the user
		$sql = "SELECT salt FROM accounts WHERE username = ? AND status = 1";
		
		$stmt = $this->_mysqli->prepare($sql);
		$stmt->bind_param('s',$bUser);	
		$bUser = base64_encode($pEmail);
		$stmt->execute();	
		
		$res = $stmt->get_result();
		$row = $res->fetch_array(MYSQLI_ASSOC);
		
		$dbSalt = $row['salt'];
		//the salt must be 12 characters long
		if(strlen($dbSalt) != 12) return json_encode(array("status" => "error", "msg" => "Invalid username. Please provide a valid email address."));	    
		
		//try to login with the credentials
		$today = date('Y-m-d');	//used for pass rest
		$sql = "SELECT id, mm_id, username, token, created FROM accounts WHERE (username = ? AND (password = ? OR (pass_reset = ? AND pass_reset_exp >= '$today'))) AND status = 1";
		
		$cryptPass = substr(crypt($pPass,'$2a$13$'.md5($dbSalt)),28);
		
		$stmt = $this->_mysqli->prepare($sql);
		$stmt->bind_param('sss',$bUser,$bPass,$bPass2);
		$bUser 	= base64_encode($pEmail);
		$bPass 	= $cryptPass;
		$bPass2 = $cryptPass;
		
		$stmt->execute();
		
		$res = $stmt->get_result();
		$row = $res->fetch_array(MYSQLI_ASSOC);
		    	
		if($row['id'] > 0){
		    $account = new Account();				
		    $account->makeFromData($row);
		    $account->makeValid();
		    
		    //if valid, set the session obj
		    $this->_sessionObj->setAccountObj($account);
			$this->_sessionObj->setAccountSession();
		    
		    //log the result as positive
		    $this->_logObj->logLogin(base64_encode($pEmail),1);
		    
		    //eventually send this to the post login logic lookup
		    //return PASSWORD RESET PAGE IF USING TEMP PASSWORD...
		    return json_encode(array("status" => "success","url" => "orders/"));
		}else{
		    $this->_logObj->logLogin(base64_encode($pEmail),0);
		    	
		    return json_encode(array("status" => "fail", "msg" => "Invalid login, please try again."));
		}
	}
	
	
	
	public function formatMadeData(){
		$this->_id 		= $this->id;
		$this->_uuid 	= $this->uuid;
	}
	
	public function returnID(){
		return $this->_id;
	}
	
	public function returnUUID(){
		return $this->_uuid;
	}
	
	public function returnAccountID(){
		return $this->returnUUID();
	}
	
	protected function makeValid(){
		$this->_isValid = true;
	}
}	
	
?>