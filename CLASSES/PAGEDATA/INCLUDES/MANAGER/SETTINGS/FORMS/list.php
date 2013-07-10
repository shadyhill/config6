<?php //file created by manager on 2013/07/03 23:47:22 ?>
<?php
	include_once dirname(__FILE__)."/../../../../../OBJS/forms.php";
	
	$forms = array();
	
	$sql = "SELECT * FROM forms ORDER BY form_name";
	$res = $this->_mysqli->query($sql);
	while($row = $res->fetch_array(MYSQLI_ASSOC)){
		$form = new Forms();
		$form->makeFromData($row);
		$forms[] = $form;
	}
?>
		
	<div id="mContent" class="span9" >
	    <h1>List of Forms</h1>			
	    
	    <table class="table table-striped table-condensed table-hover dataTable" id="objList">
	    	<thead>
	    		<tr>
	    			<th>Form Name</th>
	    			<th>URL</th>
	    			<th>Type</th>
	    		</tr>
	    	</thead>
	    	<tbody>
	    <?php
	    	foreach($forms as $f){
	    		echo "<tr>";
	    		echo "<td><a href='".S_CUR_URL."manager/settings/forms/view/$f->id/'>$f->form_name</a></td>";
	    		echo "<td>$f->ajax_url</td>";
	    		echo "<td>$f->form_type</td>";
	    		echo "</tr>";
	    	}
	    ?>
	    	</tbody>
	    </table>
	    <script>
	    	/* Table initialisation */
	    	$(document).ready(function() {
	    	    $('#objList').dataTable( {
	    	    	"sDom": "<'row'<'span4'l><'span5'f>r>t<'row'<'span4'i><'span5'p>>",
	    	    	"sPaginationType": "bootstrap",
	    	    	"oLanguage": {
	    	    		"sLengthMenu": "Show _MENU_ "
	    	    	}
	    	    } );
	    	} );
	    </script>
	</div>