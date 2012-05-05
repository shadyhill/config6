<?php 
	include_once dirname(__FILE__)."/../../../OBJS/IOFILES/iofiles.php";
	
	$fID = mysql_real_escape_string(trim($this->_urlVars[1]));
	
	$file = new Iofiles();
	$file->makeFromID($fID);
	
?>

<div style="width: 922px; margin: 10px auto; border: 1px solid #DDDDDD; padding: 18px;">
	
	<h1 style="color: #222; margin-bottom: 18px;"><a href="<?php echo CUR_URL?>">ISENBERG ONLINE FILE REPOSITORY</a> &nbsp; >> &nbsp;  <a href="<?php echo CUR_URL?>view/<?php echo $file->id?>/">VIEW FILE</a> &nbsp; >> &nbsp; EDIT FILE CONTENT</h1>
	
	<p>Use the form below to edit a existing file's content.</p>
	
	<?php
		include_once dirname(__FILE__)."/../../../LIBRARY/FORMS/forms.php";
		$form = new Forms();
		$form->makeFromDB('edit-content');
		$form->renderDBForm($file->_row);
	?>
	
</div>