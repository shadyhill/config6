<?php 

class Log{
	protected $_agent;
	protected $_ip;
	protected $_sessID;
	
	public function __construct(){
		$this->_agent = $_SERVER['HTTP_USER_AGENT'];
		$this->_ip = $_SERVER['REMOTE_ADDR'];
		$this->_sessID = session_id();
	}
	
}

?>