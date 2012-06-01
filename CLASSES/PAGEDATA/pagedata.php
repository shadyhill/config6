<?php

abstract class PageData{

	//local class variables
	protected $_metaDesc;
	protected $_metaKeys;
	protected $_pageTitle;
	
	protected $_urlVars;		//array of url directories
	protected $_url;			//url path as string
	protected $_activeURL;		//base site url
	
	protected $_includeFile;
	protected $_jsFiles;	
	protected $_cssFiles;
	
	protected $_pageType;
	protected $_templateType;
	
	//constructor
	public function __construct($urlVars){
		$this->_urlVars = $urlVars;
		$this->_url = mysql_real_escape_string(trim(implode("/",$this->_urlVars)));		//creates the string representation of the path for looking up in db
		
		$this->_includeFile = "";
		$this->_jsFiles = $this->_cssFiles = array();
	}	

	
	protected function startHTML(){
		?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="description" content="<?php echo $this->returnMeta('description');?>" />
    <meta name="keywords" content="<?php echo $this->returnMeta('keys');?>" />
    <meta name="author" content="Shady Hill Studios - www.shadyhillstudios.com" />      
    
    <title><?php echo $this->returnMeta('title');?></title>
    
    <link href="<?php echo CUR_URL?>CSS/shs-reset.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo CUR_URL?>CSS/io.css" rel="stylesheet" type="text/css" />
    <?php $this->renderCSS()?>
    
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,300,400,600,700" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    
    <script src="<?php echo CUR_URL?>JS/jScripts.js" type="text/javascript"></script>
    <?php $this->renderJS();?>
</head>
<?php flush();?>
<body>
		<?php
	}
	
	protected function endHTML(){
		?>
</body>
</html>		
		<?php 
	}

	
	private function returnMeta($meta){
		switch($meta){
			case "description":		return $this->_metaDesc;		break;
			case "keys":			return $this->_metaKeys;		break;
			case "title":			return $this->_pageTitle;		break;
		}
	}
	
	protected function getDBPageData($page){

		$sql = "SELECT pd.*, group_concat(concat_ws('~',pj.js_order,pj.js_file),'') AS js_files, group_concat(concat_ws('~',pc.css_order,pc.css_file),'') AS css_files
				FROM page_data pd 
				LEFT JOIN page_js pj ON pd.page_url = pj.page_url
				LEFT JOIN page_css pc on pd.page_url = pc.page_url
				WHERE pd.page_url = '$page' AND pd.active = 1";
				
		$result = mysql_query($sql);
		$myrow = mysql_fetch_array($result);
		
		$this->_includeFile = $myrow['include_file'];
		$this->_pageType 	= $myrow['type'];
		$this->_template 	= $myrow['template'];
		
		if($this->_includeFile == "" || !file_exists(FILE_PATH."CLASSES/PAGEDATA/INCLUDES/".$this->_includeFile)){
			//need to serve up a 404
			$this->_includeFile = "STATUS-CODES/404.php";
		}else{
			
			//get the javascript include files
			$jsFiles = explode(',',$myrow['js_files']);
			foreach($jsFiles as $dbVal){
				$parts 		= explode('~',$dbVal);
				$jsOrder 	= $parts[0];
				$jsFile 	= $parts[1];
				
				if($jsFile != "" && file_exists(FILE_PATH."JS/".$jsFile))	$this->_jsFiles[$jsOrder] = "JS/".$jsFile;				
			}
			
			ksort($this->_jsFiles);
			
			//get the csss include files
			$cssFiles = explode(',',$myrow['css_files']);
			foreach($cssFiles as $dbVal){
				$parts 		= explode('~',$dbVal);
				$cssOrder 	= $parts[0];
				$cssFile 	= $parts[1];
				
				if($cssFile != "" && file_exists(FILE_PATH."CSS/".$cssFile))	$this->_cssFiles[$cssOrder] = "CSS/".$cssFile;				
			}
			
			ksort($this->_cssFiles);
			
			//get the meta data
			$this->_pageTitle 	= ($myrow['meta_title'] != "") 		 	? $myrow['meta_title'] 			: 'A Shady Hill Studios Website';
			$this->_metaDesc 	= ($myrow['meta_description'] != "") 	? $myrow['meta_description'] 	: 'Default description.';
			$this->_metaKeys 	= ($myrow['meta_keywords'] != "")		? $myrow['meta_keywords']		: 'Default keywords';
		}
	}
	
	
	private function renderJS(){
		foreach($this->_jsFiles as $js){
			if($js != "") echo '<script src="'.CUR_URL.$js.'" type="text/javascript"></script>';
		}
	}	
	
	private function renderCSS(){
		foreach($this->_cssFiles as $css){
			if($css != "") echo '<link href="'.CUR_URL.$css.'" rel="stylesheet" type="text/css" />';
		}	
	}


}

?>