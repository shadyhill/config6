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
		
	public function __construct($urlVars){
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
		
		if(file_exists(FILE_PATH."CLASSES/OBJS/$path")){
			include_once dirname(__FILE__)."/../OBJS/$path";
			$ref = new ReflectionClass(ucfirst($obj));				//to create dynamic obj from string we need to use reflection class
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
		if (!is_array($word)) {
			$word = mysql_real_escape_string(trim($word));
		}
		return $word;
	}
	
	protected function onlyLetters($word){
		$word = preg_replace("/[^a-zA-Z]/", "", $word);
		return $word;
	}
	
	protected function onlyNumbers($word){
		$onlynums = preg_replace('/[^0-9]/','',$word);
		return $onlynums;
	}

	protected function cleanFileName($name){
		$name 		= mysql_real_escape_string(trim($name));
		$name 		= strtolower(trim($name));

		//characters that are  illegal on any of the 3 major OS's 
		$reserved 	= preg_quote('\/:*?"<>|', '/');
		
		//replaces all characters up through space and all past ~ along with the above reserved characters 
		return preg_replace("/([\\x00-\\x20\\x7f-\\xff{$reserved}])/e", "_", $name); 
		
	}
	
	protected function redirectPage($url, $page,$letter,$message){
		header("Location: $url$page/?$letter=$message");
		exit();
	}

	protected function explodeName($fullName) {
		$suffixArray = array("II", "III", "IV", "V", "JR", "SR", "JR.", "SNR.","JNR", "SNR", "JUNIOR", "SENIOR");
		
		$nameArray = preg_split("/[\s]+/", $fullName);
		$cnt = count($nameArray);
		
		// should never happen. Catch in validation or before function call
		if ($cnt == 0) {
			$lname = "";
			$fname = "";
		// should never happen. Catch in validation
		} else if ($cnt == 1) {
			$lname = $nameArray[0];
			$fname = "";
		} else if ($cnt == 2) {
			$fname = $nameArray[0];
			$lname = $nameArray[1];
		} else if($cnt == 3){
			$fname = $nameArray[0];
			$mname = $nameArray[1];
			$lname = $nameArray[2];
		} else if ($cnt > 3) {
			$last = $cnt-1;
			$penult = $cnt-2;
		
			// We treat last word as a suffix if penultimate word ends in comma or if last word in suffix array
			if ((strpos($nameArray[$penult],',')===TRUE) || in_array(strtoupper($nameArray[$last]), $suffixArray)){		
				$lname = $nameArray[$penult] . " " . $nameArray[$last];
				$end = $penult;
			} else {
				$lname = $nameArray[$last];
				$end = $last;
			}
			
			$fname = $nameArray[0];
			for ($i=1; $i<$end; $i++){
				$fname .= " " . $nameArray[$i];
			}
		}
		
		return array($fname,$lname,$mname);
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