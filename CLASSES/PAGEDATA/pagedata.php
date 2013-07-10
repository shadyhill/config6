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
	
	protected $_mysqli;
	
	//constructor
	public function __construct($mysqli, $urlVars){
		$this->_mysqli 	= $mysqli;
		$this->_urlVars = $urlVars;
		$this->_url 	= $this->_mysqli->real_escape_string(trim(implode("/",$this->_urlVars)));
		
		$this->_includeFile = "";
		$this->_jsFiles = $this->_cssFiles = array();
	}	

	
	protected function startHTML(){
		?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
    
    <meta name="description" content="<?php echo $this->returnMeta('description');?>" />
    <meta name="keywords" content="<?php echo $this->returnMeta('keys');?>" />
    <meta name="author" content="Shady Hill Studios - www.shadyhillstudios.com" />      
    
    <title><?php echo $this->returnMeta('title');?></title>
    
    <link rel="stylesheet" href="<?php echo A_URL?>CSS/BOOTSTRAP/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo A_URL?>CSS/BOOTSTRAP/bootstrap-responsive.min.css">
    <link rel="stylesheet" href="<?php echo A_URL?>CSS/FONT-AWESOME/css/font-awesome.min.css">
    
<!--
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
	<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
-->
    <link href="<?php echo A_URL?>CSS/site.css" rel="stylesheet" type="text/css" />
    <?php $this->renderCSS()?>
    
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400italic,300,400,700" rel="stylesheet" type="text/css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
<!--     <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script> -->
    <!-- <script src="<?php echo A_URL?>JS/BOTH/deethreemin.js" charset="utf-8"></script> -->
    
    <script src="<?php echo A_URL?>JS/jScripts.js" type="text/javascript"></script>
    <?php $this->renderJS();?>
</head>
<?php flush();?>
<body>
		<?php
	}
	
	protected function endHTML(){
		?>
		<!-- handle submitting forms -->
		<script>
			function submitAJAX(el){				
				var fData 	= $('#'+el.id).serialize();		//entire form
				var url		= $('#ajax_url').val();			//url to post to
				var pre		= $('#pre_process').val();		//pre processing function (if any)
				var post	= $('#post_process').val();		//post processing function (if any)
				
				$.post("<?php echo S_CUR_URL?>"+url, fData, function(data) {
					console.log("Data Loaded: " + data);
					data = JSON.parse(data);
					if(data.status == "success"){
						if(data.action == "redirect"){
							linkAPage(data.url);
						}
					}
				});
				
				return false;
			}
		</script>
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
				
		$result = $this->_mysqli->query($sql);
		$myrow = $result->fetch_array(MYSQLI_ASSOC);
		
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
			$this->_pageTitle 	= ($myrow['meta_title'] != "") 		 	? $myrow['meta_title'] 			: 'Edifio - Sheds with a Purpose';
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