<?php 
include_once dirname(__FILE__)."/magicobjs.php";

class Forms extends MagicObjs{
	
	//local class variables
	protected $_hVars;
	protected $_sessionObj;
	
	public $_fields;
	
	public function __construct($vars = array(), $session = ""){
		parent::__construct("forms");
		
		$this->_hVars 		= $vars;
		$this->_sessionObj 	= $session;
		
		$this->_fields		= array();
	}
	
	public function makeFields(){
		$sql = "SELECT * FROM form_fields WHERE form_id = $this->id ORDER BY f_order";
		$res = $this->_mysqli->query($sql);
		while($row = $res->fetch_array(MYSQLI_ASSOC)){
			$this->_fields[] = $row;
		}
	}

	public function create(){
		$this->mapPostVars();
		$this->ajax_url = $this->ajax_action_url;
		unset($this->_row['ajax_action_url']);
		$this->save();
		
		return json_encode(array("status" => "success","action" => "redirect","url" => "manager/settings/forms/list/"));
	}
	
	public function addField(){
		$this->tableOverride('form_fields');
		$this->mapPostVars();		
		$this->save();
		
		return json_encode(array("status" => "success","action" => "redirect","url" => "manager/settings/forms/view/$this->form_id/"));
	}
	
	
	
}