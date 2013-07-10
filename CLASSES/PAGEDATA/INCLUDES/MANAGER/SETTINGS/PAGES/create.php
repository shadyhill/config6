<?php
	include_once dirname(__FILE__)."/../../../../../LIBRARY/FORMS/forms.php";
	$form = new Forms();
	$form->makeFromDB('m-settings-page-create');
?>

	<div id="mContent" class="span9" >
	    <h1>Create a New Page</h1>
	    <p>
	    	Use the form below to create a new page.<br />
	    	This form will create the required .php file in the correct PAGEDATA/INCLUDES/ directory.
	    </p>
	    
	    <?php
	    	$form->renderDBForm();
	    ?>
	</div>
