<?php 
include_once dirname(__FILE__)."/../../OBJS/objs.php";

class Log extends Objs{
	protected $_agent;
	protected $_ip;
	protected $_sessID;
	
	public function __construct(){
		parent::__construct();
		$this->_agent = $_SERVER['HTTP_USER_AGENT'];
		$this->_ip = $_SERVER['REMOTE_ADDR'];
		$this->_sessID = session_id();
	}
	
}

?>