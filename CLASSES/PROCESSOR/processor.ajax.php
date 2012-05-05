<?php 

require_once dirname(__FILE__)."/processor.php";

class AJAXProcessor extends Processor{
	
	//local class variables
	
	public function __construct($urlVars, $session){
		parent::__construct($urlVars);

		$this->_sessionObj = $session;
	}

	protected function postProcess($p){
		//need to send back JSON
	}
	
	
}

?>