<?php
	include_once dirname(__FILE__)."/../../../../../LIBRARY/FORMS/forms.php";
	
	$gfid = $this->_mysqli->real_escape_string($this->_urlVars[4]);
	
	$form = new Forms();
	$form->makeFromDB('m-form-add-field');
	
?>
		
	<div id="mContent" class="span9" >
	    <h1>Add Field</h1>			
	    
	    <?php 
	    	$form->renderDBForm(array("form_id"=>$gfid));
	    ?>
	    
	    
	    
	    
	</div>