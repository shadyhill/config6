<?php 
include_once dirname(__FILE__)."/magicobjs.php";

class Pages extends MagicObjs{
	
	//local class variables
	protected $_hVars;
	protected $_sessionObj;
	
	public function __construct($vars = array(), $session = ""){
		parent::__construct("page_data");
		
		$this->_hVars 		= $vars;
		$this->_sessionObj 	= $session;
	}
	
	
	public function create(){
		
		//path to include file
		$filePath = FILE_PATH."CLASSES/PAGEDATA/INCLUDES/";
		
		//check to see if this url is already in the database
		$this->page_url			= $this->_hVars['ppage_url'];
		
		
		
		$this->page_name 		= $this->_hVars['ppage_name'];
				
		$this->is_index			= $this->_hVars['pis_index'];
		$this->override_path 	= $this->_hVars['poverride_path'];
		
		//WE NEED TO FORCE PAGE_URL TO HAVE A TRAILING SLASH
		if(substr($this->page_url, -1) != "/") $this->page_url .= "/";
		
		if($this->override_path == 1){
			$this->include_file = $this->_hVars['pinclude_file'];
		}else{
			$iFile = "";
			$paths = explode("/", $this->page_url);
			for($p = 0; $p < count($paths) - 1; $p++){
				if($p == count($paths) -2){
					if($this->is_index == 1)	$iFile .= strtoupper($paths[$p])."/index.php";
					else $iFile .= $paths[$p].".php";
				}else{
					$iFile .= strtoupper($paths[$p])."/";
				}
			}
						
			$this->include_file = $iFile;
		}
				
		
		$this->type				= $this->_hVars['ptype'];
		$this->meta_description = $this->_hVars['pmeta_description'];
		$this->meta_keywords 	= $this->_hVars['pmeta_keywords'];
		$this->meta_title		= $this->_hVars['pmeta_title'];
		
		$stmt = $this->save();
		
		//check the id to see if worked
		if($stmt === FALSE)	return json_encode(array("status" => "error","msg" => "This page already exists in the database"));			
		
		//if the save worked, try to create the file
		//NEED TO ADD TEMPLATE LOGIC INTO FILE CREATION AND UPDATE
		if(!file_exists($filePath.$this->include_file)){
			$dirname = dirname($filePath.$this->include_file);
			if (!is_dir($dirname)){
				mkdir($dirname, 0755, true);
			}
			$stamp = "<?php //file created by manager on ".date("Y/m/d H:i:s")." ?>";
			$fp = fopen($filePath.$this->include_file,'w');
			//fwrite($fp, '//file created by manager on '+date("Y/m/d H:i:s"));
			fwrite($fp,sprintf("%s", $stamp));
			fclose($fp);
		}else{
			
		}
		
		
	}
	
}	
?>