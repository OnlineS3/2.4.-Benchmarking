
/*
 * renders kernel graph by var
 * @param {json} kernel_array
 * @param {string} prop
 * @param {string} ref_col
 * @param {string} container  
 * @param {number} width
 * @param {number} height
 */
function showKernelByVar(args) {
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var prop = args.prop;
            var kernel_arr = args.kernel_array;
            var data_by_var = filter_kernel(prop,kernel_arr);
            
            renderKernelGraph({
                data: data_by_var, title:prop, ref_val: data_by_var[args.ref_indx], container:args.container, width:args.width, height:args.height, export_id:args.export_id});
            
            
            jQuery('.kernel .new-btns').removeClass('hide');
        }
    };
    xmlhttp.open("POST", window.location.href, true);
    xmlhttp.send();
}

/*
 * filters kernel data by var
 */
function filter_kernel(variable,array){
    // converts json to string
    var out=[],res=[];
    out = JSON.parse(JSON.stringify(array));
    res = out[variable];

    return res;
}

/*
 * renders kernel graph
 * @param {json} data 
 * @param {string} container  
 * @param {number} width
 * @param {number} height
 */
function renderKernelGraph(args) {
    var input_data=args.data;
    var title=args.title;
    var ref_val=args.ref_val;
    
    
    
    try {
        // calculates max & min values
        var max = Math.max.apply(null, input_data);
        var min = Math.min.apply(null, input_data);
        max = max + .2 *max, min = min - .2 *min;
        
        var container = (args.container) ? '#' + args.container : 'kernel';
        width = (args.width) ? args.width : 720;
        height = (args.height) ? args.height : 420;
        
        $(container).empty();  // clear container div
        
        // set margins
        var margin = {top: 20, right: 30, bottom: 30, left: 40},
            width = width - margin.left - margin.right,
                    height = height - margin.top - margin.bottom;

        // appends d3 to div
        var div = d3.select("body").append("div")
            .attr("class", "tooltip")
            .style("opacity", 0);

        var x = d3.scale.linear()
            .domain([min, max])
            .range([0, width]);

        var histogram = d3.layout.histogram()
            .frequency(false)
            .bins(x.ticks(40));
            
        var data = histogram(input_data),
            kde = kernelDensityEstimator(epanechnikovKernel(9.9), x.ticks(100));

        var max_y=[];
        for (i=0;i<data.length;i++) {
            max_y[i] = data[i]['y'];
        }

        var max_per = Math.max.apply(null, max_y);
        max_per += .2*max_per;

        var y = d3.scale.linear()
            .domain([0, max_per])
            .range([height, 0]);

        var xAxis = d3.svg.axis()
            .scale(x)
            .orient("bottom");

        var yAxis = d3.svg.axis()
            .scale(y)
            .orient("left")
            .tickFormat(d3.format("%"));

        var line = d3.svg.line()
            .x(function (d) {
                return x(d[0]);
            })
            .y(function (d) {
                return y(d[1]);
            });

        var tip = d3.tip()
            .attr('class', 'd3-tip')
            .offset([-10, 0])
            .html(function(d) {
                return formatNumber(d.y,2);
            });

        var svg = d3.select(container).append("svg")
            .attr("id", "export-svg")
            .attr("style", "background-color:white")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        svg.call(tip);

        svg.append("g")
            .attr("transform", "translate(0," + height + ")")
            .call(customXAxis)
            .append("text")
            .attr("x", width)
            .attr("y", -6)
            .style("text-anchor", "end")
            .style('font-size','12px')
            .style('font-size','bold')
            .style('font-family','tahoma')
            .text(title);

        svg.append("g")
            .call(customYAxis);

        svg.selectAll(".bar")
            .data(data)
            .enter().insert("rect", ".axis")
            .attr("class", "bar")
            .attr("x", function (d) {
                return x(d.x) + 1;
            })
            .attr("y", function (d) {
                return y(d.y);
            })
            .attr("width", x(data[0].dx + data[0].x) - x(data[0].x) - 1)
            .attr("height", function (d) {
                return height - y(d.y);
            })
            .style("fill", function (d) {
                // alert(d.length);
                // alert('ref_val: ' + Number(ref_val) + ' first: ' + Number(d[0]) + ' last: ' + Number(d[d.length-1]));
                if (d.length > 0) {
                    if (Number(ref_val) >= Number(d[0]) && Number(ref_val) <= Number(d[d.length-1])) {
                        return "#feca4e";
                    } else {
                        return "#bbb";
                    } 
                } else {
                    return "#bbb";
                }
                
        
                //return (d.length > 0) ? ((Number(ref_val) >= Number(d[0]) && Number(ref_val) <= Number(d[d.length-1])) ? "#f89c6b" : "#bbb") : "#bbb";
            })
            .on('mouseover', tip.show)
            .on('mouseout', tip.hide);

        svg.append("path")
            .datum(kde(input_data))
            .attr("class", "line")
            .attr("d", line);

        d3.selectAll("text")
            .style("fill","#000");

        d3.selectAll(".bar")
            .style("shape-rendering","crispEdges");

        d3.selectAll(".line")
            .style("fill","none")
            .style("stroke","#000")
            .style("stroke-width","1.5px");

        d3.selectAll(".axis path, .axis line")
            .style("fill","none")
            .style("stroke","#000")
            .style("shape-rendering","crispEdges");

        d3.selectAll(".y.axis path")
            .style("display","none");

        d3.selectAll("g")
            .style("fill","red");
            
        // displays graph
        $(container).parent().removeClass('hide');
        
        svg2img({container:args.export_id});
        
        function customYAxis(g) {
            g.call(yAxis);
            g.selectAll("line").style('fill','none').style('stroke','#000').style('stroke-width','1px').style('shape-rendering','crispEdges');
            g.selectAll("text").style('font-size','12px').style('text-anchor','end').style('font-family','tahoma');
            g.selectAll("path").style('display', 'none');
        }
        
        function customXAxis(g) {
            g.call(xAxis);
            g.selectAll("line").style('fill','none').style('stroke','#000').style('stroke-width','1px').style('shape-rendering','crispEdges');
            g.selectAll("text").style('font-size','12px').style('text-anchor','end').style('font-family','tahoma');
            g.selectAll("path").style('fill','none').style('stroke','#000');
        }
        
    } catch (err) {
        // displays exception error
        $(".app-alert").removeClass('hide').find('p').html(err.message);
        return false;
    }
   
    return false;
}


function kernelDensityEstimator(kernel, x) {
    return function(sample) {
        return x.map(function(x) {
            return [x, d3.mean(sample, function(v) { return kernel(x - v); })];
        });
    };
}

function epanechnikovKernel(scale) {
    return function(u) {
        return Math.abs(u /= scale) <= 1 ? .75 * (1 - u * u) / scale : 0;
    };
}