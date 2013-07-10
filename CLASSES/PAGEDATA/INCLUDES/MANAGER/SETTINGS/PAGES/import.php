<?php //file created by manager on 2013/07/03 23:47:22 ?>
<?php
	include_once dirname(__FILE__)."/../../../../../OBJS/pages.php";
	
	$dbFiles = array();
	
	$sql = "SELECT include_file FROM page_data";
	$res = $this->_mysqli->query($sql);
	while($row = $res->fetch_array(MYSQLI_ASSOC)){
		$dbFiles[] = $row['include_file'];
	}
	
	
	$dirFiles = array();
	
	$includePath 	= FILE_PATH."CLASSES/PAGEDATA/INCLUDES/";
	$pathLength		= strlen($includePath);
	
	$path = realpath($includePath);	
	
	$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
	foreach($objects as $name => $object){
    	if(substr($name, -4) == ".php") $dirFiles[] = substr($name,$pathLength);
	}
	
	//find the existing files in the filesystem that are not in the database
	$importFiles = array_diff($dirFiles, $dbFiles);
	
?>
		
	<div id="mContent" class="span9" >
	    <h1>Import Pages</h1>			
	    
	    <p>The table below shows all pages that have been created in the include path but have not been imported into the database. Click on the link to add them to the database.</p>
	    <p>&nbsp;</p>
	    
	    <table class="table table-striped dataTable" id="importList">
	    	<thead>
	    		<tr>
	    			<th>Include File</th>
	    		</tr>
	    	</thead>
	    	<tbody>
	    <?php
	    	$counter = 0;
	    	foreach($importFiles as $iFile){
	    		echo "<tr id='$counter'>";
	    		echo "<td><a onclick='addPage($counter);'>$iFile</a></td>";
	    		echo "</tr>";
	    		$counter++;
	    	}
	    ?>
	    	</tbody>
	    </table>
	    <script>
	    	/* Table initialisation */
	    	$(document).ready(function() {
	    	    $('#importList').dataTable( {
	    	    	"sDom": "<'row'<'span4'l><'span5'f>r>t<'row'<'span4'i><'span5'p>>",
	    	    	"sPaginationType": "bootstrap",
	    	    	"oLanguage": {
	    	    		"sLengthMenu": "Show _MENU_ "
	    	    	}
	    	    } );
	    	} );
	    	
	    	function addPage(rowID){
	    		var url = $('#'+rowID).find("a").html();
		    	$.post(s_cur_url+"manager/AJAX/pages/import/", {"url":url}, function(data) {
		    		console.log(data);
		    		$('#'+rowID).fadeOut();
		    	});
	    	}
	    </script>
	</div>