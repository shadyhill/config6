<?php 
include_once dirname(__FILE__)."/../../OBJS/magicobjs.php";
include_once dirname(__FILE__)."/../MARKDOWN/markdownify.php";
include_once dirname(__FILE__)."/fields.php";

class Forms extends MagicObjs{
	
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
		parent::__construct('forms');
		
		$this->_formIDs = array();
		$this->_defaultWidth = 300;
		if($class != "") $this->_class = $class;
		else $this->_class = "input";
		
		$this->_markDownify = new Markdownify();
		
		$this->_dbSelectMenus = array();
		$this->_formFields = array();
	}
	
	public function makeFromDB($name){
		
		$this->_formFields = array();
		$first = TRUE;
		
		$sql = "SELECT *
					FROM forms f 
					LEFT JOIN form_fields ff on f.id = ff.form_id 
					WHERE f.form_name = ?
					ORDER BY ff.f_order ASC";
		
		$stmt = $this->_mysqli->prepare($sql);
		
		$stmt->bind_param('s',$formName);
		$formName 	= $name;

		$stmt->execute();
		
		$res = $stmt->get_result();
		while($myrow = $res->fetch_array(MYSQLI_ASSOC)){
			
			//grab the form details
			if($first){
				$this->_formName 			= $myrow['form_name'];
				$this->_formAction 			= $myrow['action'];
				$this->_formMethod 			= $myrow['method'];
				$this->_formType 			= $myrow['form_type'];
				$this->_formOnSubmit 		= $myrow['onsubmit'];
				$this->_formClass 			= $myrow['form_class'];
				$this->_formDisplayError 	= $myrow['has_error_field'];
				$this->_formEncoding		= $myrow['encoding'];
				$this->_submitTxt 			= $myrow['button_txt'];
				$this->_preProcess			= $myrow['pre_process'];
				$this->_postProcess			= $myrow['post_process'];
				$this->_ajaxURL				= $myrow['ajax_url'];
				
				$first = FALSE;
				
				if($this->_formClass == "") $this->_formClass = "defaultForm";
			}
			
			//probably need to add a check to see if the fields are actually populated before adding them to the array
			$field = new FormField();
			$field->makeFromData($myrow);
			$this->_formFields[] = $field;
		}
	}
	
	public function addDBSelectMenu($index,$values,$displays){		
		$this->_dbSelectMenu[$index] = array("values" => implode(",",$values),"displays" => implode(",",$displays));
	}
	
	//renderDBForm will accept values as an associative array to handle existin values
	public function renderDBForm($values = array()){
		
		$this->renderStartForm();
		foreach($this->_formFields as $field){
			$field->renderField($values[$field->name_id]);
		}
		
		/*
foreach($this->_formFields as $f){
			
			switch($f["type"]){
				
				case "multiselect":
					$this->renderMultiSelect($f['name_id'],$f['label'],$f['dd_displays'],$f['dd_values'],$value,$f['width']);
					break;
				case "dbmultiselect":
					$index 		= $f['dd_displays'];
					$dbDisplays = $this->_dbSelectMenu[$index]['displays'];
					$dbValues 	= $this->_dbSelectMenu[$index]['values'];
					$this->renderMultiSelect($f['name_id'],$f['label'],$dbDisplays,$dbValues,$value,$f['width']);
					break;
				case "dbselectmenu":
					//db entry uses index in value and display fields
					$index 		= $f['dd_displays'];
					$dbDisplays = $this->_dbSelectMenu[$index]['displays'];
					$dbValues 	= $this->_dbSelectMenu[$index]['values'];
					$this->renderSelectMenu($f['name_id'],$f['label'],$dbDisplays,$dbValues,$value,$f['width']);
					break;			
				case "date":
					$this->renderDateField($f["type"],$f["name_id"],$f["label"],$value,$f["width"]);
					break;
				case "money":
					$this->renderTextField($f["type"],$f["name_id"],$f["label"],$f["placeholder"],$this->formatMoney($value),$f["width"]);
					break;
			}
			
			
		}
*/
		
		$this->renderSubmit($this->_submitTxt);
		$this->renderEnd();
		if($this->_formDisplayError) $this->renderFormE();
	}
	
	//yeah, it just calls helper functions
	protected function renderStartForm(){
		echo "<form ";
		$this->renderFormID();
		$this->renderMethod();
		$this->renderAction();
		$this->renderOnSubmit();
		$this->renderEncoding();
		$this->renderClass();
		echo ">";
		$this->renderHiddenFormSettings();
	}
	
	private function renderHiddenFormSettings(){
		if($this->_preProcess != "") $this->renderHiddenField("pre_process",$this->_preProcess);
		if($this->_postProcess != "") $this->renderHiddenField("post_process",$this->_postProcess);
		if($this->_ajaxURL != "") $this->renderHiddenField("ajax_url",$this->_ajaxURL);
	}
	
	private function renderFormID(){
		echo "id='$this->_formName' ";
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
		if($this->_formOnSubmit != "") echo "onsubmit='return $this->_formOnSubmit(this);' ";
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
		echo '<div class="control-group">';
		echo '<div class="controls">';
		echo "<input type='submit' value='$label &rarr;' class='submit' />";
		echo '</div>';
		echo '</div>';
	}
	
	
	protected function renderMultiSelect($id,$label,$displays,$values,$select = "",$width = ""){
		if($width == "") $width = $this->_defaultWidth;
		$this->_formIDs[] = $id;
		
		
		$displays 	= explode(",",$displays);
		$values 	= explode(",",$values);
		
		$vals = array();

		if($select != ""){
			$vals = explode("~", $select);
		}
		
		?>
		<div class="control-group">
		<?php 
		$this->renderLabel($label,$id);
		echo '<div class="controls">';
		echo '<select multiple="multiple" id="'.$id.'[]" name="'.$id.'[]" style="width: '.$width.'px; height: 70px;">';
		foreach($displays as $key => $d){
			echo '<option value="'.$values[$key].'"';
			if(in_array($values[$key], $vals)) echo 'selected="selected"';
			echo '>'.$d.'</option>';
		}
		echo '</select>';
		echo '</div>';
		echo '</div>';

	}
	
	protected function renderHiddenField($id,$value){
		$this->_formIDs[] = $id;
		echo "<input type='hidden' name='$id' id='$id' value='$value' />";
	}
		
	
	protected function renderFileField($id,$label,$value = ""){
		$this->_formIDs[] = $id;
		$this->renderLabel($label,$id);
		echo '<div class="'.$this->_class.'Div">';
		echo "<input type='file' name='$id' style='margin-bottom:20px;' id='$id' />";
		if($value != "") echo "<span> Current File: $value</span>";
		echo '</div>';
	}
	
	public function renderDateField($type,$id,$label, $value ="", $width = "", $right = false){
		if($width == "") $width = $this->_defaultWidth;
		$this->_formIDs[] = $id;				
		$this->renderLabel($label,$id);
		if($value == '0000-00-00'){
			$value = "";
		}
		if(($value!='')&&($value!='0000-00-00')){
			$value = date('m/d/Y',strtotime($value));
		}
		if($this->_multiObjs){
			$id .= '-'.$this->_multiCount;	
		}
		?>
		<div class="<?php echo $this->_class?>Div">
			<input type="text" name="<?php echo $id?>" id="<?php echo $id?>" value="<?php echo $value;?>" class="datepicker <?php echo $this->_class?>Txt <?php if($right) echo "aRight"; ?>" style="width: <?php echo $width?>px;"  />
		</div>
		<?php 
	}
	
		
}

?>