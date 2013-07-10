<?php //file created by manager on 2013/07/03 23:47:22 ?>
<?php
	include_once dirname(__FILE__)."/../../../../../OBJS/pages.php";
	
	$pages = array();
	
	$sql = "SELECT * FROM page_data ORDER BY page_url";
	$res = $this->_mysqli->query($sql);
	while($row = $res->fetch_array(MYSQLI_ASSOC)){
		$page = new Pages();
		$page->makeFromData($row);
		$pages[] = $page;
	}
?>
		
	<div id="mContent" class="span9" >
	    <h1>List of Pages</h1>			
	    
	    <table class="table table-striped table-condensed table-hover dataTable" id="pageList">
	    	<thead>
	    		<tr>
	    			<th>Page Name</th>
	    			<th>URL</th>
	    			<th>Type</th>
	    		</tr>
	    	</thead>
	    	<tbody>
	    <?php
	    	foreach($pages as $p){
	    		echo "<tr>";
	    		echo "<td><a href='".S_CUR_URL."manager/settings/pages/edit/$p->page_url'>$p->page_name</a></td>";
	    		echo "<td>$p->page_url</td>";
	    		echo "<td>$p->type</td>";
	    		echo "</tr>";
	    	}
	    ?>
	    	</tbody>
	    </table>
	    <script>
	    	/* Table initialisation */
	    	$(document).ready(function() {
	    	    $('#pageList').dataTable( {
	    	    	"sDom": "<'row'<'span4'l><'span5'f>r>t<'row'<'span4'i><'span5'p>>",
	    	    	"sPaginationType": "bootstrap",
	    	    	"oLanguage": {
	    	    		"sLengthMenu": "Show _MENU_ "
	    	    	}
	    	    } );
	    	} );
	    </script>
	</div>