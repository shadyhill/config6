<?php 

require_once dirname(__FILE__)."/processor.php";

include_once dirname(__FILE__)."/../SESSION/session.general.php";

class GeneralProcessor extends Processor{
	//local class variables
	
	public function __construct($urlVars, $session){
		parent::__construct($urlVars);

		$this->_sessionObj = $session;
	}

	protected function postProcess($p){
		header("Location: ".CUR_URL.$p);
		exit();
	}
			
}

?>