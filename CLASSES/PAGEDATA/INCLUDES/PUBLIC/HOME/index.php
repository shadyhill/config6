<div id="drawing" style="width: 100%; height: 100%; position: absolute; background: rgb(244,244,244); overflow: hidden;">
	
</div>

<script>
	var width = $('#drawing').width(),
    	height = $('#drawing').height(),
    	fill = d3.scale.category20();		//not sure I want to use their color scale...
    
    width = 600;
    height = 400;
    
   var x = d3.scale.identity().domain([0,600]);
   var y = d3.scale.identity().domain([0,400]);
    
    var drawing = d3.select("#drawing")
    	.append("svg:svg")
    		.attr("class","axis")
    		.attr("width", width)   
    		.attr("height", height); 
   
   drawing.append("g")         
        .attr("class", "grid")
        .attr("transform", "translate(0," + height + ")")
        .call(make_x_axis()
            .tickSize(-height, 0, 0)
            .tickFormat("")
        )
        
    drawing.append("g")         
        .attr("class", "grid")
        .call(make_y_axis()
            .tickSize(-width, 0, 0)
            .tickFormat("")
        )
   
   function make_x_axis() {        
    return d3.svg.axis()
        .scale(x)
//         .orient("bottom")
         .ticks(5)
}

function make_y_axis() {        
    return d3.svg.axis()
        .scale(y)
//        .orient("left")
        .ticks(5)
}
    /*
drawing.selectAll("line.x")
    	.data(x.ticks(20))
    	.enter().append("line")
    		.attr("class", "x")
    		.attr("x1", x)
    		.attr("x2", x)
    		.attr("y1", 0)
    		.attr("y2", 400)
    		.style("stroke", "#ccc");
    		
    // Draw Y-axis grid lines
    drawing.selectAll("line.y")
    	.data(y.ticks(20))
    	.enter().append("line")
    		.attr("class", "y")
    		.attr("x1", 0)
    		.attr("x2", 600)
    		.attr("y1", y)
    		.attr("y2", y)
    		.style({"stroke":"#ccc","stroke-width":0});
*/
   /*
 
    for(x = 0; x < width; x+=40){
    	for(y = 0; y < height; y++){ 
		y = 0;
    		var myLine = drawing.append("svg:line")
    			.attr("x1", x)
    			.attr("y1", y)
    			.attr("x2", x)
    			.attr("y2", y+height)
    			.style({"stroke": "rgb(6,120,155)","stroke-width":.1	});
    		//}
    }
*/
</script>