<?php
	session_start();

	//db connection stuff
	$connect1 = "host";
	$connect2 = "user";
	$connect3 = "pass";
	$db_name = "db";
		
	
	//these are commented out until you need to connect to a database
	$db = mysql_connect($connect1,$connect2,$connect3);
	mysql_select_db($db_name,$db);
	
	mysql_query("SET NAMES 'utf8'"); //this is new
	mysql_query("SET CHARACTER SET 'utf8'"); //this is new

	//set the time
	date_default_timezone_set('America/Chicago');
	
	//for money formatting
	//setlocale(LC_MONETARY, 'en_US.utf8');  -- for live server
	setlocale(LC_MONETARY, 'en_US');
	
	//constants
	define('CUR_URL', 'http://shsair.local/~shsair/git/config6/');
	define('S_CUR_URL', 'http://shsair.local/~shsair/git/config6/');
	define('FILE_PATH','/Users/shsair/Sites/git/config6/');
	
	
	define('SALT',"ADD SALT HERE");
	
	define('ADMIN_AUTH_STRING','ADD AUTH');
	define('M_AUTH_STRING','ADD AUTH');
	define('USER_AUTH_STRING','ADD AUTH');
	
	//define('','');
?>