<div style="width: 922px; margin: 10px auto; border: 1px solid #DDDDDD; padding: 18px;">
	
	<h1 style="color: #222; margin-bottom: 18px;"><a href="<?php echo CUR_URL?>">ISENBERG ONLINE FILE REPOSITORY</a> &nbsp; >> &nbsp;  CREATE A NEW FILE</h1>
	
	<p>Use the form below to create a new content element.</p>
	
	<?php
		include_once dirname(__FILE__)."/../../../LIBRARY/FORMS/forms.php";
		$form = new Forms();
		$form->makeFromDB('add-content');
		$form->renderDBForm();
	?>
	
</div>