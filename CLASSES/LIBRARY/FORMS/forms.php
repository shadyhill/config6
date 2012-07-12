<?php 
include_once dirname(__FILE__)."/../MARKDOWN/markdownify.php";

class Forms{
	
	//local class variables
	protected $_formIDs;
	protected $_defaultWidth;
	protected $_class;
	
	
	protected $_formName;
	protected $_formAction;
	protected $_formMethod;
	protected $_formType;
	protected $_formOnSubmit;
	protected $_formClass;
	protected $_formEncoding;
	protected $_formDisplayError;
	
	protected $_dbSelectMenus;
	
	protected $_submitTxt;
	
	protected $_formFields;
	
	protected $_markDownify;
	
	public function __construct($class = ""){
		$this->_formIDs = array();
		$this->_defaultWidth = 300;
		if($class != "") $this->_class = $class;
		else $this->_class = "input";
		
		$this->_markDownify = new Markdownify();
		
		$this->_dbSelectMenus = array();
	}
	
	public function makeFromDB($name){
		
		$this->_formFields = array();
		$first = TRUE;
		
		$name = mysql_real_escape_string(trim($name));
		$sql = "SELECT * 
					FROM forms f 
					LEFT JOIN form_fields ff on f.id = ff.form_id 
					WHERE f.form_name = '$name'
					ORDER BY ff.f_order ASC";
		$result = mysql_query($sql);
		while($myrow = mysql_fetch_array($result)){
			
			//grab the form details
			if($first){
				$this->_formName 			= $myrow['form_name'];
				$this->_formAction 			= $myrow['action'];
				$this->_formMethod 			= $myrow['method'];
				$this->_formType 			= $myrow['type'];
				$this->_formOnSubmit 		= $myrow['onsubmit'];
				$this->_formClass 			= $myrow['class'];
				$this->_formDisplayError 	= $myrow['has_error_field'];
				$this->_formEncoding		= $myrow['encoding'];
				$this->_submitTxt 			= $myrow['button_txt'];
				
				$first = FALSE;
				
				if($this->_formClass == "") $this->_formClass = "defaultForm";
			}
			
			//probably need to add a check to see if the fields are actually populated before adding them to the array
			$this->_formFields[] = $myrow;
		}
	}
	
	public function addDBSelectMenu($index,$values,$displays){		
		$this->_dbSelectMenu[$index] = array("values" => implode(",",$values),"displays" => implode(",",$displays));
	}
	
	//renderDBForm will accept values as an associative array to handle existin values
	public function renderDBForm($values = ""){
		
		$this->renderStartForm();
		
		foreach($this->_formFields as $f){
			if(isset($values[$f["name_id"]])) $value = $values[$f["name_id"]];
			else $value = '';
			
			switch($f["type"]){
				case "text":
				case "password":
					$this->renderTextField($f["type"],$f["name_id"],$f["label"],$f["placeholder"],$value,$f["width"]);
					break;
				case "textarea":
					$this->renderTextArea($f["label"],$f["name_id"],$f["placeholder"],$this->_markDownify->parseString($value),$f['width']);
					break;
				case "file":
					$this->renderFileField($f['name_id'],$f['label']);
					break;
				case "hidden":
					$this->renderHiddenField($f['name_id'],$value);
					break;
				case "selectmenu":
					$this->renderSelectMenu($f['name_id'],$f['label'],$f['dd_displays'],$f['dd_values'],$value,$f['width']);
					break;
				case "dbselectmenu":
					//db entry uses index in value and display fields
					$index 		= $f['dd_displays'];
					$dbDisplays = $this->_dbSelectMenu[$index]['displays'];
					$dbValues 	= $this->_dbSelectMenu[$index]['values'];
					$this->renderSelectMenu($f['name_id'],$f['label'],$dbDisplays,$dbValues,$value,$f['width']);
					break;
			}
			
			
		}
		
		$this->renderSubmit($this->_submitTxt);
		$this->renderEnd();
		if($this->_formDisplayError) $this->renderFormE();
	}
	
	//yeah, it just calls helper functions
	protected function renderStartForm(){
		echo "<form ";
		$this->renderMethod();
		$this->renderAction();
		$this->renderOnSubmit();
		$this->renderEncoding();
		$this->renderClass();
		echo ">";
	}
	
	private function renderMethod(){
		echo "method='$this->_formMethod' ";
	}
	
	private function renderAction(){
		if($this->_formAction != ""){
			if($this->_formType == "AJAX") echo "action='$this->_formAction' ";
			else echo "action='".S_CUR_URL."$this->_formAction'";
		}
	}
	
	private function renderOnSubmit(){
		if($this->_formOnSubmit != "") echo "onsubmit='return $this->_formOnSubmit();' ";
	}
	
	private function renderEncoding(){
		if($this->_formEncoding != "") echo "enctype='$this->_formEncoding' ";
	}
	
	private function renderClass(){
		echo "class='$this->_formClass' ";
	}
	
	protected function renderStart($action,$isFile = false){
		echo "<form action='".S_CUR_URL."$action' method='post' id='".$this->_class."Form' ";
		if($isFile) echo 'enctype="multipart/form-data" ';
		echo ">";
	}
	
	protected function renderAJAXStart($action,$onsubmit){
		echo "<form method='post' action='#$action' onsubmit='return $onsubmit;' id='".$this->_class."Form'>";
	}
	
	protected function renderEnd(){
		echo "</form>";
	}
	
	protected function renderFormE(){
		echo "<div id='formE'>";
			if(isset($_GET['e'])){
				switch($_GET['e']){
					case "failed-creation":	echo "Failed to create entry in database. Please try again.";		break;
					case "failed-update":	echo "Failed to update entry in the database. Please try again.";	break;
					default: echo "&nbsp;";
				}
			}else echo "&nbsp;";
		echo "</div>";
	}
	
	protected function renderSubmit($label = "Submit"){
		echo "<input type='submit' value='$label &raquo;' class='submit' />";
	}
	
	protected function renderLabel($label,$target = ""){
		echo "<label for='$target'>$label</label>";
	}
	
	
	protected function renderSelectMenu($id,$label,$displays,$values,$select = "",$width = ""){
		if($width == "") $width = $this->_defaultWidth;
		$this->_formIDs[] = $id;
		$this->renderLabel($label,$id);
		
		$displays 	= explode(",",$displays);
		$values 	= explode(",",$values);
		
		echo '<div class="'.$this->_class.'Div">';
		echo '<select id="'.$id.'" name="'.$id.'" style="width: '.$width.'px;">';
		foreach($displays as $key => $d){
			echo '<option value="'.$values[$key].'"';
			if($values[$key] == $select) echo 'selected="selected"';
			echo '>'.$d.'</option>';
		}
		echo '</select>';
		echo '</div>';

	}
	
	protected function renderSelectMenuOld($id,$label,$list,$select = "",$width = ""){
		if($width == "") $width = $this->_defaultWidth;
		$this->_formIDs[] = $id;
		$this->renderLabel($label,$id);
		
		echo '<div class="'.$this->_class.'Div">';
		echo '<select id="'.$id.'" name="'.$id.'" style="width: '.$width.'px;">';
		foreach($list as $l){
			echo '<option value="'.$l['value'].'"';
			if($l['value'] == $select) echo 'selected="selected"';
			echo '>'.$l['display'].'</option>';
		}
		echo '</select>';
		echo '</div>';

	}
	
	protected function renderHiddenField($id,$value){
		$this->_formIDs[] = $id;
		echo "<input type='hidden' name='$id' id='$id' value='$value' />";
	}
	
	protected function renderTextField($type,$id,$label,$placeholder = "", $value = "", $width = ""){
		if($width == "") $width = $this->_defaultWidth;
		$this->_formIDs[] = $id;
		$placeholder = htmlentities(stripslashes($placeholder), ENT_QUOTES);				
		$this->renderLabel($label,$id);
		?>
		<div class="<?php echo $this->_class?>Div">
			<input type="<?php echo $type?>" name="<?php echo $id?>" id="<?php echo $id?>" value="<?php echo $value?>" placeholder="<?php echo $placeholder?>" class="<?php echo $this->_class?>Txt <?php if($right) echo "aRight"; ?>" style="width: <?php echo $width?>px;"  />
		</div>
		<?php 
	}
	
	protected function renderTextArea($label,$id,$placeholder = "", $value = "", $width = ""){
		if($width == "") $width = $this->_defaultWidth;
		$this->_formIDs[] = $id;
		$this->renderLabel($label,$id);
		$placeholder = htmlentities(stripslashes($placeholder), ENT_QUOTES);	
		?>
		<div class="<?php echo $this->_class?>Div">			
			<textarea id="<?php echo $id?>" name="<?php echo $id?>" placeholder="<?php echo $placeholder?>" class="<?php echo $this->_class."TxtArea"?>" style="width: <?php echo $width."px;"?>"><?php echo $value?></textarea>
		</div>
		<?php 
	}
	
	protected function renderFileField($id,$label){
		$this->_formIDs[] = $id;
		$this->renderLabel($label,$id);
		echo '<div class="'.$this->_class.'Div">';
		echo "<input type='file' name='$id' style='margin-bottom:20px;' id='$id' />";
		echo '</div>';
	}
	
	
}

?>