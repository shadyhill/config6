<style>
	svg {
		shape-rendering: crispEdges;
	}
	
	.axis path, .axis line {
		fill: none;
		stroke: #fff;
	}
	
</style>

<div id="drawing" style="width: 100%; height: 100%; position: absolute; background: rgb(244,244,244); overflow: hidden;">
	
</div>

<script>
	var margin = {top: 10, right: 10, bottom: 10, left: 10};    
    var width = 600;
    var height = 400;
        
    var drawing = d3.select("#drawing")
    	.append("svg:svg")    		
    		.attr("width", width)   
    		.attr("height", height);
   
		
	var x = d3.scale.linear()
    	.domain([0,width/100])
		.range([0, width]);

	var y = d3.scale.linear()
    	.domain([0,height/50])
		.range([height, 0]);

	var xAxis = d3.svg.axis()
    	.scale(x)
		.orient("bottom")
		.tickSize(-height);

	var yAxis = d3.svg.axis()
    	.scale(y)
		.orient("left")
		.tickSize(-width);
	
	drawing.append("g")
    	.attr("class", "x axis")
		.attr("transform", "translate(0," + height + ")")
		.call(xAxis);
	
	drawing.append("g")
    	.attr("class", "y axis")
		.call(yAxis);
	
	
	/*
drawing.append("svg:rect")
		.attr("x",100)
		.attr("y",100)
		.attr("width",100)
		.attr("height",100)
		.attr("fill","#006699");
*/

	
	//sets the hashes for every 50 over a range of the width?
	/*
var x = d3.scale.linear()
			.domain([0, 50])
			.range([0, width]);
	
	var y = d3.scale.linear()
			.domain([0, 50]) 
			.rangeRound([0, height]);
	
	drawing.append("g")
		.data(d3.range(0,20))
		.enter()
			.append("svg:line")
				.attr("y1", 0)
				.attr("y2", height)
				.attr("x1", x)
				.attr("x2", x)
*/
				
	
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