<?php

include_once dirname(__FILE__)."/pagedata.php";
include_once dirname(__FILE__)."/../SESSION/session.manager.php";
include_once dirname(__FILE__)."/../OBJS/USERS/manager.php";
/* include_once dirname(__FILE__)."/../OBJS/notifications.manager.php"; */

class ManagerPageData extends PageData{
	
	//local class variables
	protected $_activeURL;
	
	public function __construct($urlVars,$session){
		$mysqli = $session->returnMySQLi();
		parent::__construct($mysqli,$urlVars);
		
		$this->_sessionObj 	= $session;
		$this->_managerID 	= $this->_sessionObj->returnSessionManagerID();

		$this->_activeURL 	= S_CUR_URL;
		
		$this->initialize();
	}
	
	
	private function initialize(){
		
		$this->_jsFiles[101] 	= "JS/BOTH/jquery.dataTables.min.js";
		$this->_jsFiles[102] 	= "JS/BOTH/jDataTableBootstrap.js";
		$THIS->_cssFiles[101] 	= "CSS/BOTH/jquery.dataTables.css";
		
		//custom page content rules before the data search
		switch($this->_urlVars[0]){
			default: $pageQ = $this->_url; 
		}
		
		
		
		if(isset($this->_urlVars[2])){
			switch($this->_urlVars[2]){
				case "view":
				case "edit":				
				case "create":
				case "delete":
					$pageQ = $this->_urlVars[0]."/".$this->_urlVars[1]."/".$this->_urlVars[2]."/";
				
			}
		}
		
		if(isset($this->_urlVars[3])){
			switch($this->_urlVars[3]){
				case "view":
				case "edit":				
				case "create":
				case "delete":
				case "add-field":
					$pageQ = $this->_urlVars[0]."/".$this->_urlVars[1]."/".$this->_urlVars[2]."/".$this->_urlVars[3]."/";
				
			}
		}
		
		
		//database query to get incldue file, css files, and js files	
		$this->getDBPageData($pageQ);
	}
	
	
	public function render(){
		$this->startHTML();
		$this->renderTopBar();
		$this->startPage();
		$this->renderMainNav();
		$this->startContent();
		$this->renderSideNav();
		$this->loadPageContent();
		$this->endPageContent();
		$this->endHTML();
	}

	//since this is just an include file, maybe this should be template driven now?
	private function loadPageContent(){
		
		include_once dirname(__FILE__)."/INCLUDES/".$this->_includeFile;
		
	}
	
	private function startPage(){
		echo '<div class="container" style="margin-top: 20px;">';
	}
	
	private function startContent(){
		echo '<div class="row">';
	}
	
	private function endPageContent(){
		echo '</div></div>';
	}
	
	private function renderTopBar(){
		?>
		<div style="width: 100%; height: 36px; line-height: 36px; background : #636D75;"></div>
		<div style="width: 100%; height: 2px; background: #009ec3;"></div>
		<?php 
	}
	
	private function renderMainNav(){
		?>
		<div class="row" style="margin-bottom: 20px;">
		    <div class="span12" >
		    <div id="managerNav" class="navbar">
		    	<ul class="nav">
		    		<li><a href="<?php echo S_CUR_URL?>manager/dashboard/"><i class="icon-dashboard icon-large"></i> Dashboard</a></li>
		    		<li class="divider-vertical"></li>
		    		<li><a href="<?php echo S_CUR_URL?>manager/orders/"><i class="icon-home icon-large"></i> Orders</a></li>
		    		<li class="divider-vertical"></li>
		    		<li><a href="<?php echo S_CUR_URL?>manager/accounts/"><i class="icon-user icon-large"></i> Accounts</a></li>
		    		<li class="divider-vertical"></li>
		    		<li><a href="<?php echo S_CUR_URL?>manager/inventory/"><i class="icon-barcode icon-large"></i> Inventory</a></li>
		    		<li class="divider-vertical"></li>
		    		<li><a href="<?php echo S_CUR_URL?>manager/reports/"><i class="icon-list-ul icon-large"></i> Reports</a></li>
		    		<li class="divider-vertical"></li>
		    		<li><a href="<?php echo S_CUR_URL?>manager/settings/" class="active"><i class="icon-cog  icon-large"></i> Settings</a></li>
		    		<li class="divider-vertical"></li>
		    	</ul>
		    </div>
		    </div>
		</div>
		<?php 
	}
	
	private function renderSideNav(){
		?>
		<div class="span3 mSideNav">
			<ul class="nav nav-list">
				<li>
					<a href="<?php echo S_CUR_URL?>manager/settings/pages/">Pages</a>
					<ul class="nav nav-list">
						<li><a href="<?php echo S_CUR_URL?>manager/settings/pages/list/">List</a></li>
						<!-- <li><a href="<?php echo S_CUR_URL?>manager/settings/pages/create/">Create</a></li> -->
						<li><a href="<?php echo S_CUR_URL?>manager/settings/pages/import/">Import</a></li>
					</ul>
				</li>
				<li>
					<a href="<?php echo S_CUR_URL?>manager/settings/forms/">Forms</a>
					<ul class="nav nav-list">
						<li><a href="<?php echo S_CUR_URL?>manager/settings/forms/list/">List</a></li>
						<li><a href="<?php echo S_CUR_URL?>manager/settings/forms/create/">Create</a></li>
					</ul>
				</li>
				<li><a href="<?php echo S_CUR_URL?>manager/settings/css/">CSS</a></li>
				<li><a href="<?php echo S_CUR_URL?>manager/settings/javascript/">Javascript</a></li>
				<li><a href="<?php echo S_CUR_URL?>manager/settings/managers/">Managers</a></li>
			</ul>
		</div>
		<?php 
	}
		

	
}

?>
