<?php

class View{
	
	public function __construct(){
	
	}
	
	protected function renderSectionHeader($h,$lnkName = "",$lnk = ""){
		?>
		<h2 style="border-bottom: 1px solid; line-height: 24px; margin-top: 30px; margin-bottom: 12px;">
			<span style="font-size: 15px; font-weight: 600;"><?php echo $h?></span>
			<?php
				if($lnkName != ""){
					if($lnk != ""){
						?>
						<a style="float: right; font-size: 12px;" href="<?php echo S_CUR_URL.$lnk?>"><?php echo $lnkName?></a>
						<?php
					}else{
						?>
						<span style="float: right; font-size: 12px;"><?php echo $lnkName?></span>
						<?php 
					}
				}
			?>
		</h2>
		<?php 
	}
	
	protected function renderLabelValue($label,$value){
		?>
		<div style="margin-bottom: 25px;">
			<div style="float: left; display: inline; width: 150px; padding-right: 25px; font-size: 12px; color: #777;">
				<?php echo $label?>
			</div>
			<div style="float: left; display: inline; width: 450px;">
				<?php echo $value?>
			</div>
			<br style="clear: both;" />
		</div>
		<?php 
	}
		
}

?>