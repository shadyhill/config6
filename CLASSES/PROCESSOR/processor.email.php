<?php 

require_once dirname(__FILE__)."/processor.php";
include_once dirname(__FILE__)."/../FX/fx.sendmail.php";

class EmailProcessor extends Processor{
	
	//local class variables
	protected $_fxEmail;
	
	public function __construct($urlVars,$session){

		parent::__construct($urlVars);
		$this->_sessionObj = $session;
		$this->_fxEmail = new SendEmailFX();
	}
	
	public function process(){
		
		switch($this->_urlVars[1]){
			
		}

		
	}

	
	
	

	
		
}

?>