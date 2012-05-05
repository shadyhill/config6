<?php 
	include_once dirname(__FILE__)."/../../../OBJS/IOFILES/iofiles.view.php";
	include_once dirname(__FILE__)."/../../../OBJS/IOFILES/iofiles.php";
	
	$fID = mysql_real_escape_string(trim($this->_urlVars[1]));
	
	$file = new Iofiles();
	$file->makeFromID($fID);
	
	$fView = new IofilesView();
?>

<div style="width: 922px; margin: 10px auto; border: 1px solid #DDDDDD; padding: 18px;">
	
	<h1 style="color: #222; margin-bottom: 18px;"><a href="<?php echo CUR_URL?>">ISENBERG ONLINE FILE REPOSITORY</a> &nbsp; >> &nbsp;  VIEW FILE</h1>
	
	<p style="text-align: right;">
		<a href="<?php echo CUR_URL?>edit/<?php echo $file->id?>/">Edit Content Information</a>
	</p>
	
	<h2 style="margin-bottom: 20px;">Viewing <?php echo stripslashes($file->title)?> Content</h2>
	
	<?php
		$fView->renderSingleView($file);
	?>
	
</div>