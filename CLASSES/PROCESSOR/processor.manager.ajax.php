<?php 

require_once dirname(__FILE__)."/processor.php";

class AJAXManagerProcessor extends Processor{
	
	//local class variables
	
	public function __construct($urlVars,$session){
		$mysqli = $session->returnMySQLi();
		parent::__construct($mysqli, $urlVars);
		
		//need to advance the obj and fx index because we are at manager/AJAX/
		$this->_objIndex 	= 2;	
		$this->_fxIndex 	= 3;
	}

	protected function postProcess($p){
		//need to send back JSON or other data
		//right now, just echoing the text back, but could be wrapped into JSON in the future
		echo $p;
	}

	
	
	
}

?>