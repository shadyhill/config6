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
	
	function tableOverride($table){
		$this->_table = $this->_mysqli->real_escape_string($table);
	}
	
	function __get($what) {
		if (array_key_exists($what,$this->_row)) {
			return $this->_row[$what];
		}
		else {
			return null;
		}
	}
	
	function __set($name,$value) {
		$this->_row[$name] = $value;
		return true;
	}
	
	protected function save() {
		// build the INSERT statement
		
		$table = $this->_table;
		
		if(isset($this->_row['id']) && $this->_row['id'] != "") {
			$sql = "UPDATE $table SET ";
			$len = count($this->_row);
			foreach ($this->_row as $key => $value) {
				$sql .= "$key = '$value'";
				if ($len > 1) {
					$sql .= ", ";
				}
				$len = $len - 1;
			}
			$id = $this->_row['id'];
			$sql .= " WHERE id = $id;";
		}
		else {
			$sql = "INSERT INTO $table ( ";
			$len = count($this->_row);
			foreach ($this->_row as $key => $value) {
				$sqlA .= "$key";
			
				$v = $this->_mysqli->real_escape_string($value);
				$sqlB .= "'$v'";
			
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
		
		//echo "the sql is $sql";
		
		// the actual query is anti-climactic
		$res = $this->_mysqli->query($sql);
		return $res;
	}
	
	public function returnRow(){
		return $this->_row;
	}
}

?>