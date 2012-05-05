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
	
	function makeFromID($id) {
		$table = $this->_table;
		$sql = "SELECT * FROM $table WHERE id = $id LIMIT 1;";
		$res = mysql_query($sql);
		$this->makeFromData(mysql_fetch_array($res));
	}
	
	function makeFromData($row = array()) {
		$this->_row = $row;
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
			
				$v = mysql_real_escape_string($value);
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
				
		// the actual query is anti-climactic
		$res = mysql_query($sql);
		return $res;
	}
	
	public function returnRow(){
		return $this->_row;
	}
}

?>