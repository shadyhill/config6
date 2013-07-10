<?php
	include_once dirname(__FILE__)."/../../../../../LIBRARY/FORMS/forms.php";
	$form = new Forms();
	$form->makeFromDB('m-new-form');
?>

<div id="mContent" class="span9" >
    <h1>Create a New Form</h1>
    <p>
    	Use the form below to create a new form. Crazy, right?
    </p>
    
    <?php
    	$form->renderDBForm();
    ?>
</div>