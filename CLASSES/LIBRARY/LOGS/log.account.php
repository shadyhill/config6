<?php 

/**
  * These classes should probably just rewrite the base class functions (i.e. logLogin, logAction, etc and the constructor should carry table names
 **/

require_once dirname(__FILE__)."/log.php";

class AccountLog extends Log{
	
	//local class variables
	protected $_loginTable;
	protected $_actionTable;
	
	public function __construct(){
		parent::__construct();
		
		$this->_loginTable = "log_acct_logins";
		$this->_actionTable = "log_acct_actions";
	}
	
	public function logLogin($user,$login){
		$eUser = base64_encode(PTC_SALT.$user);
		$sql = "INSERT $this->_loginTable (id, user, ip, sess_id, user_agent, status) values (0,'$eUser','$this->_ip','$this->_sessID','$this->_agent',$login)";
		$result = $this->_mysqli->query($sql);
	}
	
	public function logAAction($user,$action){
		$sql = "INSERT INTO $this->_actionTable (id, user_id, action) values (0,'$user','$action')";
		$result = $this->_mysqli->query($sql);
	}
	
}

?>