<?php
	include_once dirname(__FILE__)."/../../../../../OBJS/forms.php";
	
	$gfid = $this->_mysqli->real_escape_string($this->_urlVars[4]);
	
	$form = new Forms();	
	$form->makeFromID($gfid);
	$form->makeFields();
	
?>
		
	<div id="mContent" class="span9" >
	    <h1>View Form: <?php echo $form->form_name?></h1>			
	    
	    <h2>
	    	Form Data 
	    	<a href="<?php echo S_CUR_URL?>manager/settings/forms/edit/<?php echo $form->id?>/" class="pull-right">Edit <i class="icon-edit"></i></a>
	    </h2>
	    
	    
	    <table class="table table-striped table-hover table-condensed">
	    	<tr>
	    		<td>Form Name</td>
	    		<td><?php echo $form->form_name?></td>
	    	</tr>
	    	<tr>
	    		<td>AJAX Url</td>
	    		<td><?php echo $form->ajax_url?></td>
	    	</tr>
	    	<tr>
	    		<td>Form Type</td>
	    		<td><?php echo $form->form_type?></td>
	    	</tr>
	    	<tr>
	    		<td>Action</td>
	    		<td><?php echo $form->action?></td>
	    	</tr>
	    	<tr>
	    		<td>Method</td>
	    		<td><?php echo $form->method?></td>
	    	</tr>
	    	<tr>
	    		<td>Form Class</td>
	    		<td><?php echo $form->form_class?></td>
	    	</tr>
	    	<tr>
	    		<td>Button Text</td>
	    		<td><?php echo $form->button_txt?></td>
	    	</tr>	    	
	    </table>
	    
	    
	    <h2>
	    	Form Fields
			<a href="<?php echo S_CUR_URL?>manager/settings/forms/add-field/<?php echo $form->id?>/" class="pull-right">Add Field <i class="icon-plus"></i></a>
			<a href="<?php echo S_CUR_URL?>manager/settings/forms/add-field/<?php echo $form->id?>/" class="pull-right">Order Fields <i class="icon-reorder"></i></a>
	    </h2>
	    
	    <table class="table table-striped table-hover table-condensed">
	    	<thead>
	    		<th>Label</th>
	    		<th>Type</th>
	    		<th>Name/ID</th>
	    		<th>Placeholder</th>
	    		<th>Class</th>
	    		<th>Style</th>
	    	</thead>
	    	<tbody>
	    		<?php
	    			foreach($form->_fields as $field){
		    			?>
		    		<tr>
						<td><?php echo $field['label']?></td>
						<td><?php echo $field['type']?></td>
						<td><?php echo $field['name_id']?></td>
						<td><?php echo $field['placeholder']?></td>
						<td><?php echo $field['class_override']?></td>
						<td><?php echo $field['style_override']?></td>
					</tr>
		    			<?php 
	    			}
	    		?>
	    		
	    	</tbody>
	    </table>
	    
	</div>