<?php
	include_once dirname(__FILE__)."/../../../../../LIBRARY/FORMS/forms.php";
	$form = new Forms();
	$form->makeFromDB('m-settings-page-create');
?>

<div style="width: 100%; height: 36px; line-height: 36px; background : #636D75;"></div>
<div style="width: 100%; height: 2px; background: #009ec3;"></div>


<div class="container" style="margin-top: 20px;">
	
	<div class="row" style="margin-bottom: 20px;">
		<div class="span12" >
		<div id="managerNav" class="navbar">
			<ul class="nav">
				<li><a href="<?php echo S_CUR_URL?>manager/orders/">Orders</a></li>
				<li class="divider-vertical"></li>
				<li><a href="<?php echo S_CUR_URL?>manager/accounts/">Accounts</a></li>
				<li class="divider-vertical"></li>
				<li><a href="<?php echo S_CUR_URL?>manager/inventory/">Inventory</a></li>
				<li class="divider-vertical"></li>
				<li><a href="<?php echo S_CUR_URL?>manager/reports/">Reports</a></li>
				<li class="divider-vertical"></li>
				<li><a href="<?php echo S_CUR_URL?>manager/settings/" class="active">Settings</a></li>
				<li class="divider-vertical"></li>
			</ul>
		</div>
		</div>
	</div>
	
	<div class="row">
		<div class="span3">
			<ul class="nav nav-list">
				<li><a>link 1</a></li>
				<li><a>link 2</a></li>
			</ul>
		</div>
		<div class="span9" >
			<?php
				$form->renderDBForm();
			?>
		</div>
	</div>
</div>
