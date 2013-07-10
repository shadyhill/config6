<?php
include_once "objs.php";
include_once dirname(__FILE__)."/../LIBRARY/MARKDOWN/markdown.php";

class MagicObjs extends Objs {

	protected $_table;
	protected $_row;

	public function __construct($table) {
		parent::__construct();
		$this->_table = $table;
		$this->_row = array();
	}
	
	public function makeFromID($id) {
		$table = $this->_table;
		$sql = "SELECT * FROM $table WHERE id = $id LIMIT 1;";
		$res = $this->_mysqli->query($sql);
		$this->makeFromData($res->fetch_array(MYSQLI_ASSOC));
	}
	
	public function makeFromData($row) {
		$this->_row = $row;
		$this->formatMadeData();
	}
	
	protected function formatMadeData(){
		//this can be overriden
	}
	
	protected function tableOverride($table){
		$this->_table = $this->_mysqli->real_escape_string($table);
	}
	
	protected function mapPostVars(){
		foreach($this->_hVars as $key => $val){
			$type 	= substr($key, 0,1);
			$field 	= substr($key, 1);
			if($type == "p" && ($field != "pre_process" &&  $field != "post_process" && $field != "ajax_url")){
				$this->$field = $val;
			}
		}
	}
	
	//magic method for accessing an unassigned variable
	public function __get($what) {
		if (array_key_exists($what,$this->_row)) {
			return $this->_row[$what];
		}
		else {
			return null;
		}
	}
	
	//magic method for setting an unassigned variable
	public function __set($name,$value) {
		$this->_row[$name] = $value;
		return true;
	}
	
	//returns the protected row data
	public function returnRow(){
		return $this->_row;
	}
	
	protected function save() {
		// build the INSERT statement
		$sqlA = $sqlB = "";

		$table = $this->_table;
		
		$types 	= "";		//stores the types for binding
		$params = array();	//stores parameters in order (same as $this->_row, but may need it)
		
		if(isset($this->_row['id']) && $this->_row['id'] != "") {
			//need a string for the types			
			$sql = "UPDATE $table SET ";
			$len = count($this->_row);
			foreach ($this->_row as $key => $value) {
				//add the variable line
				$sql .= "$key = ?";
				
				//check the type
				if(is_int($value)) 			$types .= "i";	//ints
				else if(is_float($value)) 	$types .= "d";	//floats and doubles
				else if(is_string($value)) 	$types .= "s";	//strings
				else						$types .= "b";	//blobs and others (WE MAY WANT STRINGS TO BE CATCH ALL)
				
				$keys[] 	= $$key;
				$params[] 	= $value;
				
				//handle concatination
				if ($len > 1)	$sql .= ", ";
				$len = $len - 1;	//so we know when to stop
				
			}
			
			//build the where and add the info for the id
			$sql .= " WHERE id = ?;";
			$types .= "i";
			$params[] = $this->_row['id'];
			
		}
		else {
			$sql = "INSERT INTO $table ( ";
			$len = count($this->_row);
			
			foreach ($this->_row as $key => $value) {
				$sqlA .= "$key";
			
				$sqlB .= "?";
				
				//check the type
				if(is_int($value)) 			$types .= "i";	//ints
				else if(is_float($value)) 	$types .= "d";	//floats and doubles
				else if(is_string($value)) 	$types .= "s";	//strings
				else						$types .= "b";	//blobs and others (WE MAY WANT STRINGS TO BE CATCH ALL)
				
				$params[] 	= $value;
				
				if ($len > 1) {
					$sqlA .= ", ";
					$sqlB .= ", ";
				}
				$len = $len - 1;
			}
			$sql .= $sqlA;
			$sql .= ") VALUES ( ";
			$sql .= $sqlB;
			$sql .= ");";
		}		
		
		//prepare the statement
		$stmt = $this->_mysqli->prepare($sql);
				
			//got this code from http://www.devmorgan.com/blog/?s=dydl
			$bind_names[] = $types;
			
			for ($i=0; $i<count($params);$i++) {//go through incoming params and added em to array
        	    $bind_name = 'bind' . $i;       //give them an arbitrary name
        	    $$bind_name = $params[$i];      //add the parameter to the variable variable
        	    $bind_names[] = &$$bind_name;   //now associate the variable as an element in an array
        	}
        
        	call_user_func_array(array($stmt,'bind_param'),$bind_names);
		
		//run the statement		
		$res = $stmt->execute();
		
		return $stmt;
	}
	
	
}

?>