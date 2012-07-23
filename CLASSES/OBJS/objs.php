<?php

class Objs{
	
	//local variables
	protected $_isValid;
	protected $_mysqli;
	
	public function __construct(){
		$this->_isValid = false;
		global $mysqli;
		$this->_mysqli = $mysqli;
	}
	
	public function isValid(){
		return $this->_isValid;
	}
	
	
	protected function cleanHTTPVariables(){		
		foreach($_POST as $key => $value) 	$this->_httpVars["p$key"] = $this->clean($value);
		foreach($_GET as $key => $value)	$this->_httpVars["g$key"] = $this->clean($value);
	}
	
	public function clean($word){
		$word = $this->_mysqli->real_escape_string(trim($word));
		return $word;
	}
	
	public function onlyLetters($word){
		$word = preg_replace("/[^a-zA-Z]/", "", $word);
		return $word;
	}
	
	public function onlyNumbers($word){
		$onlynums = preg_replace('/[^0-9]/','',$word);
		return $onlynums;
	}

	public function cleanFileName($name){
		$name 		= $this->_mysqli->real_escape_string(trim($name));
		$name 		= strtolower(trim($name));

		//characters that are  illegal on any of the 3 major OS's 
		$reserved = preg_quote('\/:*?"<>|', '/');
		
		//replaces all characters up through space and all past ~ along with the above reserved characters 
		return preg_replace("/([\\x00-\\x20\\x7f-\\xff{$reserved}])/e", "_", $name); 
		
	}
	
	public function generateSlug($phrase){
    	$result = strtolower($phrase);
    	$result = preg_replace("/[^a-z0-9\s-]/", "", $result);
    	$result = trim(preg_replace("/[\s-]+/", " ", $result));
    	$result = preg_replace("/\s/", "-", $result);
    	return $result;
	}
	
	public function convertToActive($a){
		if($a == 1) return "ACTIVE";
		else return "INACTIVE";
	}
	
	public function phoneFormat($word){
		$phone = $word;
		if(strlen($word) == 10) $phone = "(".substr($word, 0, 3).") ".substr($word, 3, 3)."-".substr($word, 6, 4);
		else if(strlen($word) == 7) $phone = substr($word, 0, 3)."-".substr($word, 3, 4);
		return $phone;
	}
	
	public function emailClickable($word){
		return "<a href='mailto:$word'>$word</a>";
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
	
	public function fsockSend($url,$params){
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



