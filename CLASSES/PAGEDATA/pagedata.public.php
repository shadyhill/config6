<?php

include_once "pagedata.php";

class PublicPageData extends PageData{
	
	//local class variables
	protected $_sessionObj;
	protected $_accountObj;		//do we need this?
	
	
	public function __construct($urlVars,$session){
		parent::__construct($urlVars);
		$this->_sessionObj = $session;
		$this->initialize();
		//$this->fetchSEOandJS();		
	}	
	
	
	private function initialize(){
		
		//custom page content rules before the data search
		switch($this->_urlVars[0]){
			case "view":
				$pageQ = "view/";	break;
			case "edit":
				$pageQ = "edit/";	break;
				
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