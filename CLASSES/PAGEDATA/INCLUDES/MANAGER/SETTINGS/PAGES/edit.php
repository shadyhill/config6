<?php //file created by manager on 2013/07/04 00:08:05 ?>
<?php
	include_once dirname(__FILE__)."/../../../../../OBJS/pages.php";
	include_once dirname(__FILE__)."/../../../../../LIBRARY/FORMS/forms.php";
	
	//see if we can find the url we're trying to view
	$qPageURL = implode("/",array_slice($this->_urlVars, 4));
	
	$sql = "SELECT * FROM page_data WHERE page_url = ?";
	
	$stmt = $this->_mysqli->prepare($sql);
	$stmt->bind_param('s',$bPage);	
	$bPage = $qPageURL;		
	$stmt->execute();
		
	$res = $stmt->get_result();
	$row = $res->fetch_array(MYSQLI_ASSOC);
	
	$page = new Pages();
	$page->makeFromData($row);
	$page->page_id = $page->id;		//fix for id problem with jquery
	
	//create the form
	$form = new Forms();
	$form->makeFromDB('m-settings-page-edit');
	
?>

<div id="mContent" class="span9" >
	<h1>Edit Page Data</h1>
	<?php
		$form->renderDBForm($page->returnRow());
	?>
</div>