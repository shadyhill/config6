<?php 

require_once dirname(__FILE__)."/../LIBRARY/FX/fx.php";
include_once dirname(__FILE__)."/../LIBRARY/LOGS/log.php";

class Processor{
	
	//local class variables
	protected $_fxObj;
	protected $_httpVars;
	protected $_logObj;
	protected $_accountObj;		//this is set by the child class depending on account type
	protected $_sessionObj;		//this is set by the child class depending on the session type
	
	protected $_urlVars;
	protected $_url;
		
	protected $_objIndex;		//this variable keeps track of what index to find the obj
	protected $_fxIndex;		//this variable keeps track of what index to find the function
	
	protected $_mysqli;
		
	public function __construct($mysqli, $urlVars){
		$this->_fxObj 		= new FX();
		$this->_logObj 		= new Log();
		$this->_httpVars 	= array();
		
		$this->_urlVars 	= $urlVars;
		$this->_url 		= implode("/",$this->_urlVars);
		
		$this->_objIndex 	= 1;
		$this->_fxIndex 	= 2;
		
		$this->cleanHTTPVariables();
	}
	
	public function process(){
		
		//echo "passed in vars with: ".var_dump($this->_httpVars);
		
		$obj 	= $this->_urlVars[$this->_objIndex];
		$fx 	= $this->_urlVars[$this->_fxIndex];		
				
		//run a switch case on any special cases
		switch($obj){
			case "account":		
			case "manager":
			case "admin":	
				$path = "USERS/";		
				break;
				
			
			default: 	$path = '';
		}
		
		$path .= "$obj.php";
		
		//for compound classes
		if(strpos($obj, ".") > 0){
			$objNames = array_reverse(explode(".", $obj));
			$first = true;
			$newObj = "";
			foreach($objNames as $on){
				if($first){
					$newObj .= $on;
					$first = false;
				}else $newObj .= ucfirst($on);
			}
			$obj = $newObj;
		}
		
		if(file_exists(FILE_PATH."CLASSES/OBJS/$path")){
			include_once dirname(__FILE__)."/../OBJS/$path";
			$ref = new ReflectionClass(ucfirst($obj));		//to create dynamic obj from string we need to use reflection class
			$object = $ref->newInstance($this->_httpVars);			//then we copy that class to $object and pass the http vars
			$processed = $object->{$fx}();							//and then we can call the function we want
			
			//tell the sub classes to figure out what to do next
			$this->postProcess($processed);
			
			
		}else{
			echo "could not find the model class with ".FILE_PATH."/CLASSES/OBJS/$path";
			//need to redirect?
		}
	
	}
	
	protected function postProcess($p){
		//this should be implemented by sub classes
		echo "Please create postProcess function in appropriate processing class";
	}
	
	
	/**
	  * Function loops through array of POST and GET variables
	  * Cleans all of the variables according to the clean function
	  * Prepends POST variables with a 'p'
	  * Prepends GET variables with a 'g'
	  * Stores all variables in class variable $_httpVars which is an array
	  */
	protected function cleanHTTPVariables(){		
		foreach($_POST as $key => $value) 	$this->_httpVars["p$key"] = $this->clean($value);
		foreach($_GET as $key => $value)	$this->_httpVars["g$key"] = $this->clean($value);
	}
	
	protected function clean($word){
		$word = $this->_mysqli->real_escape_string(trim($word));
		return $word;
	}
	
	protected function fsockSend($url,$params){
	//fire the script for the notification emails
		foreach ($params as $key => &$val) {
		  if (is_array($val)) $val = implode(',', $val);
		    $post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);
		
		$parts=parse_url($url);
		
		$fp = fsockopen($parts['host'],
		    isset($parts['port'])?$parts['port']:80,
		    $errno, $errstr, 30);
		
		$out = "POST ".$parts['path']." HTTP/1.1\r\n";
		$out.= "Host: ".$parts['host']."\r\n";
		$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out.= "Content-Length: ".strlen($post_string)."\r\n";
		$out.= "Connection: Close\r\n\r\n";
		if (isset($post_string)) $out.= $post_string;

		
		fwrite($fp, $out);
		fclose($fp);
	}	
	
}


?>