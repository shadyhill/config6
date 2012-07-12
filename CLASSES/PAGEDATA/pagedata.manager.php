<?php

include_once dirname(__FILE__)."/pagedata.php";
include_once dirname(__FILE__)."/../SESSION/session.manager.php";
include_once dirname(__FILE__)."/../OBJS/USERS/manager.php";
/* include_once dirname(__FILE__)."/../OBJS/notifications.manager.php"; */

class ManagerPageData extends PageData{
	
	//local class variables
	protected $_activeURL;
	
	public function __construct($urlVars,$session){
		
		parent::__construct($urlVars);
		
		$this->_sessionObj 	= $session;
		$this->_managerID 	= $this->_sessionObj->returnSessionManagerID();

		$this->_activeURL 	= S_CUR_URL;
		
		$this->initialize();
	}
	
	
	private function initialize(){
		
		//custom page content rules before the data search
		switch($this->_urlVars[0]){
			
			default: $pageQ = $this->_url; 
		}
		
		//database query to get incldue file, css files, and js files	
		$this->getDBPageData($pageQ);
	}
	
	
	public function render(){
		$this->startHTML();
		$this->loadPageContent();
		$this->endHTML();
	}

	//since this is just an include file, maybe this should be template driven now?
	private function loadPageContent(){
		
		include_once dirname(__FILE__)."/INCLUDES/".$this->_includeFile;
		
	}
		

	
}

?>
