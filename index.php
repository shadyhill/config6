<?php 
	//include config file
	include_once dirname(__FILE__)."/SUPERS/sGlobals.php";
	
	//include layout file (handles pagedata
	include_once dirname(__FILE__)."/CLASSES/CONTROLLER/controller.php";
	
	//create layout obj and render
	$site = new Controller();
	$site->control();
?>