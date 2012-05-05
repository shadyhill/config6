<?php 

require_once dirname(__FILE__)."/processor.php";

include_once dirname(__FILE__)."/../LIBRARY/LOGS/log.manager.php";
include_once dirname(__FILE__)."/../OBJS/USERS/MANAGER/manager.php";
include_once dirname(__FILE__)."/../SESSION/session.manager.php";

class ManagerProcessor extends Processor{
	//local class variables
	protected $_logObj;
	protected $_managerObj;
	protected $_sessionObj;
	
	public function __construct($urlVars, $session){
		parent::__construct($urlVars);

		$this->_logObj = new ManagerLog();
		$this->_sessionObj = $session;
		$this->_managerObj = $this->_sessionObj->returnManagerObj();
		$this->_objIndex 	= 2;
		$this->_fxIndex 	= 3;
	}

	protected function postProcess($p){
		header('Location: '.S_CUR_URL.$p);
		exit();
	}
	
}

?>