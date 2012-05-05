<?php 
	
require_once dirname(__FILE__)."/admin.php";

class ModelAdmin extends Admin{
	
	//local class variables
	protected $_httpVars;
	
	public function __construct($vars){
		parent::__construct();
		$this->_httpVars = $vars;
	}
	
	public function login(){
		echo "you got here";
	}
	
}	
	
	
	
?>