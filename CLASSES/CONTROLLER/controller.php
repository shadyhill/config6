<?php

class Controller{

	//local class variables
	protected $_pagedata;
	protected $_processor;
	protected $_session;
	
	protected $_url;
	protected $_urlVars;
	protected $_mysqli;
	
	//constructor
	public function __construct($mysqli){
		$this->_url 	= trim($_GET['url']);				//get the url variable out of GET (posted by htaccess)
		$this->_urlVars = explode("/",$this->_url);		//turn that into an array for easy parsing
		$this->_mysqli  = $mysqli;
	}
	
	/**
	 * The purpose of the control function is to determine what we need to do with the request to the server
	 * The two primary options are to process data or to render a page
	 * secondary to processing and rendering, we may also need to check for sessions depending on where we are
	 *
	 * As a controller, we don't need to dive in to every page but can simply decide the major objects required for major sections of the website
	 *
	 **/
	
	public function control(){

		if($this->_urlVars[0] == "manager"){
			include_once dirname(__FILE__)."/../SESSION/session.manager.php";
			$this->_session = new ManagerSession($this->_mysqli);
		}else if($this->_urlVars[0] == "admin"){
			include_once dirname(__FILE__)."/../SESSION/session.admin.php";
			$this->_session = new AdminSession($this->_mysqli);
		}else{
			include_once dirname(__FILE__)."/../SESSION/session.general.php";
			$this->_session = new GeneralSession($this->_mysqli);
		}

		
		switch($this->_urlVars[0]){
			case "PROCESS":
				include_once dirname(__FILE__)."/../PROCESSOR/processor.general.php";
				$processor = new GeneralProcessor($this->_urlVars,$this->_session);
				$processor->process();	
				break;

			case "AJAX":
				include_once dirname(__FILE__)."/../PROCESSOR/processor.ajax.php";
				$processor = new AJAXProcessor($this->_urlVars,$this->_session);
				$processor->process();
				break;
			case "manager":
				//first we need to find the cases where a manager session is not required to render the page
				//these include logging in as a manager and processing the login for a manager
				if($this->_urlVars[1] == "login"){
					include_once dirname(__FILE__)."/../PAGEDATA/pagedata.manager.php";
					$pageData = new ManagerPageData($this->_urlVars, $this->_session);
					$pageData->render();
				
				}else if($this->_urlVars[1] == "AJAX" && $this->_urlVars[2] == "manager" && $this->_urlVars[3] == "login"){
					include_once dirname(__FILE__)."/../PROCESSOR/processor.manager.ajax.php";
					$processor = new AJAXManagerProcessor($this->_urlVars,$this->_session);
					$processor->process();
				}else{
					
					//verify the manager session
					$this->_session->verifyManager();
					
					switch($this->_urlVars[1]){
						case "PROCESS":
							include_once dirname(__FILE__)."/../PROCESSOR/processor.manager.php";
							$processor = new ManagerProcessor($this->_urlVars,$this->_session);
							$processor->process();
							break;
						case "AJAX":
							include_once dirname(__FILE__)."/../PROCESSOR/processor.manager.ajax.php";
							$processor = new AJAXManagerProcessor($this->_urlVars,$this->_session);
							$processor->process();
							break;
						default:
							include_once dirname(__FILE__)."/../PAGEDATA/pagedata.manager.php";
							$pageData = new ManagerPageData($this->_urlVars, $this->_session);
							$pageData->render();
					}
				}
				break;
			case "admin":
				//first we need to find the cases where a manager session is not required to render the page
				//these include logging in as a manager and processing the login for a manager
				if($this->_urlVars[1] == "login"){
					include_once dirname(__FILE__)."/../PAGEDATA/pagedata.admin.php";
					$pageData = new AdminPageData($this->_urlVars, $this->_session);
					$pageData->render();
				
				}else if($this->_urlVars[1] == "AJAX" && $this->_urlVars[2] == "manager" && $this->_urlVars[3] == "login"){
					include_once dirname(__FILE__)."/../PROCESSOR/processor.admin.ajax.php";
					$processor = new AJAXAdminProcessor($this->_urlVars,$this->_session);
					$processor->process();
				}else{
					//verify the admin session
					$this->_session->verifyAdmin();
					
					switch($this->_urlVars[1]){
						case "PROCESS":
							include_once dirname(__FILE__)."/../PROCESSOR/processor.admin.php";
							$processor = new AdminProcessor($this->_urlVars,$this->_session);
							$processor->process();
							break;
						case "AJAX":
							include_once dirname(__FILE__)."/../PROCESSOR/processor.admin.ajax.php";
							$processor = new AJAXAdminProcessor($this->_urlVars,$this->_session);
							$processor->process();
							break;
						default:
							include_once dirname(__FILE__)."/../PAGEDATA/pagedata.admin.php";
							$pageData = new AdminPageData($this->_urlVars, $this->_session);
							$pageData->render();
					}
				}
				break;
				
			default:
				include_once dirname(__FILE__)."/../PAGEDATA/pagedata.public.php";
				$pagedata = new PublicPageData($this->_urlVars,$this->_session);
				$pagedata->render();
				break;
				
		}//switch
		
	}//control()
	


}//Controller{}
?>