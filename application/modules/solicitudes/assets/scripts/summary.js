var Colors = ["#4d4d4d","#5da5da","#faa43a","#60bd68","#f17cB0","#b2912f","#b276b2","#decf3f","#f15854"];
var ColorsExtended = randomColor({
	count: 40,
	luminosity: 'bright',
	seed: 'gus'
})
var AgentsGraph;
$(document).ready( function(){ 
	var ctxAgents = document.getElementById("agentsContainer").getContext("2d");
	AgentsGraph = new Chart(ctxAgents, {
	    type: "horizontalBar",
	    data: {
			labels: WO_Agents.map(obj => obj.name),
			datasets: [{
			    label: "# Solicitudes",
			    data: WO_Agents.map(obj => obj.conteo),
			    xAxisID: "y-axis-1",
			    backgroundColor: "rgba(54, 162, 235, 0.4)",
			    borderWidth: 1
			},
			{
				label: "Primas totales",
			    data: WO_Agents.map(obj => obj.prima),
			    xAxisID: "y-axis-2",
			    backgroundColor: "rgba(230, 25, 75, 0.4)",
			    borderWidth: 1
			}]
		},
		options: {
			scales: {
				xAxes: [{
                    type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                    display: true,
                    position: "top",
                    id: "y-axis-1",
                    gridLines: {
                        drawOnChartArea: false
                    },
                    ticks: {
			            beginAtZero:true
			        },
                },{
                    type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                    display: true,
                    position: "bottom",
                    id: "y-axis-2",
                    gridLines: {
                        drawOnChartArea: false
                    },
                    ticks: {
                    	beginAtZero:true,
			            // Return an empty string to draw the tick line but hide the tick label
			           	// Return "null" or "undefined" to hide the tick line entirely
			           	userCallback: function(value, index, values) {
			           		// Convert the number to a string and splite the string every 3 charaters from the end
			           		value = Math.round(value*100)/100;
			                if(value >= 1000){
				                value = value.toString();
				                x = value.split(".");
								x1 = x[0];
								x2 = x.length > 1 ? "." + x[1] : "";
				                x1 = x1.split(/(?=(?:...)*$)/);
				                // Convert the array to a string and format the output
				                value = x1.join(",");
				                value = value+x2;
			                }
			                return "$" + value;
			            	}
			          }
                }],
			},
			maintainAspectRatio: false,
			tooltips: {
				callbacks: {
					label: function(tooltipItem, data) {
						var allData = data.datasets[tooltipItem.datasetIndex].data;
						var tooltipLabel = data.datasets[tooltipItem.datasetIndex].label;
						var tooltipData = allData[tooltipItem.index];
						if(tooltipItem.datasetIndex == 1){
							nStr = tooltipData;
							nStr += "";
							x = nStr.split(".");
							x1 = x[0];
							x2 = x.length > 1 ? "." + x[1] : "";
							var rgx = /(\d+)(\d{3})/;
							while (rgx.test(x1)) {
								x1 = x1.replace(rgx, "$1" + "," + "$2");
							}
							return tooltipLabel + ": $" + x1 + x2;
						}
						return tooltipLabel + ": " + tooltipData;
					}
				}
			},
			bezierCurve : false,
            animation: false
		}
	});


	var ctxStatus = document.getElementById("statusContainer").getContext("2d");
	var StatusGraph = new Chart(ctxStatus, {
	    type: "pie",
	    data: {
			labels: WO_Status.map(obj => obj.status),
			datasets: [{
			    label: "# Solicitudes",
			    data:  WO_Status.map(obj => obj.conteo),
			    borderWidth: 1,
			    backgroundColor: Colors,
			}]
		},	
		options: {
			scales: {
			    yAxes: [{
			        ticks: {
			            beginAtZero:true
			        },
			    }],
			},
			maintainAspectRatio: false,
			tooltips: {
				callbacks: {
					label: function(tooltipItem, data) {
						var allData = data.datasets[tooltipItem.datasetIndex].data;
						var tooltipLabel = data.labels[tooltipItem.index];
						var tooltipData = allData[tooltipItem.index];
						var total = 0;
						for (var i in allData) {
							total += parseFloat(allData[i]);
						}
						var tooltipPercentage = Math.round((tooltipData / total) * 100);
						return tooltipLabel + ": " + tooltipData + " (" + tooltipPercentage + "%)";
					}
				}
			}
		}
	});
	var ctxProducts = document.getElementById("productsContainer").getContext("2d");
	ProductsGraph = new Chart(ctxProducts, {
	    type: "pie",
	    data: {
			labels: WO_Products.map(obj => obj.producto),
			datasets: [{
			    label: "# Solicitudes",
			    data: WO_Products.map(obj => obj.conteo),
			    borderWidth: 1,
			    backgroundColor: ColorsExtended,	
			}]
		},
		options: {
			scales: {
			    yAxes: [{
			        ticks: {
			            beginAtZero:true
			        },
			    }],
			},
			maintainAspectRatio: false,
			tooltips: {
				callbacks: {
					label: function(tooltipItem, data) {
						var allData = data.datasets[tooltipItem.datasetIndex].data;
						var tooltipLabel = data.labels[tooltipItem.index];
						var tooltipData = allData[tooltipItem.index];
						var total = 0;
						for (var i in allData) {
							total += parseFloat(allData[i]);
						}
						var tooltipPercentage = Math.round((tooltipData / total) * 100);
						return tooltipLabel + ": " + tooltipData + " (" + tooltipPercentage + "%)";
					}
				}
			}
		}
	});

	//Get Variables ordered by Prima
	var WO_Status_Prima = WO_Status.sort(dynamicSort("-prima"));
	var WO_Products_Prima = WO_Products.sort(dynamicSort("-prima"));

	var ctxStatusPrima = document.getElementById("statusPrimaContainer").getContext("2d");
	var StatusPrimaGraph = new Chart(ctxStatusPrima, {
	    type: "pie",
	    data: {
			labels: WO_Status_Prima.map(obj => obj.status),
			datasets: [{
			    label: "# Solicitudes",
			    data:  WO_Status_Prima.map(obj => obj.prima),
			    borderWidth: 1,
			    backgroundColor: Colors,
			}]
		},	
		options: {
			scales: {
			    yAxes: [{
			        ticks: {
			            beginAtZero:true
			        },
			    }],
			},
			maintainAspectRatio: false,
			tooltips: {
				callbacks: {
					label: function(tooltipItem, data) {
						var allData = data.datasets[tooltipItem.datasetIndex].data;
						var tooltipLabel = data.labels[tooltipItem.index];
						var tooltipData = allData[tooltipItem.index];
						var total = 0;
						for (var i in allData) {
							total += parseFloat(allData[i]);
						}
						var tooltipPercentage = Math.round((tooltipData / total) * 100);

						var nStr = tooltipData;
						nStr += "";
						x = nStr.split(".");
						x1 = x[0];
						x2 = x.length > 1 ? "." + x[1] : "";
						var rgx = /(\d+)(\d{3})/;
						while (rgx.test(x1)) {
							x1 = x1.replace(rgx, "$1" + "," + "$2");
						}

						return tooltipLabel + ": $" + x1 + x2 + " (" + tooltipPercentage + "%)";
					}
				}
			}
		}
	});
	var ctxProductsPrima = document.getElementById("productsPrimaContainer").getContext("2d");
	ProductsPrimaGraph = new Chart(ctxProductsPrima, {
	    type: "pie",
	    data: {
			labels: WO_Products_Prima.map(obj => obj.producto),
			datasets: [{
			    label: "# Solicitudes",
			    data: WO_Products_Prima.map(obj => obj.prima),
			    borderWidth: 1,
			    backgroundColor: ColorsExtended,	
			}]
		},
		options: {
			scales: {
			    yAxes: [{
			        ticks: {
			            beginAtZero:true
			        },
			    }],
			},
			maintainAspectRatio: false,
			tooltips: {
				callbacks: {
					label: function(tooltipItem, data) {
						var allData = data.datasets[tooltipItem.datasetIndex].data;
						var tooltipLabel = data.labels[tooltipItem.index];
						var tooltipData = allData[tooltipItem.index];
						var total = 0;
						for (var i in allData) {
							total += parseFloat(allData[i]);
						}
						var tooltipPercentage = Math.round((tooltipData / total) * 100);

						var nStr = tooltipData;
						nStr += "";
						x = nStr.split(".");
						x1 = x[0];
						x2 = x.length > 1 ? "." + x[1] : "";
						var rgx = /(\d+)(\d{3})/;
						while (rgx.test(x1)) {
							x1 = x1.replace(rgx, "$1" + "," + "$2");
						}

						return tooltipLabel + ": $" + x1 + x2 + " (" + tooltipPercentage + "%)";
					}
				}
			}
		}
	});

	var WO_Products_PrimaAvg = WO_Products.sort(dynamicSort("-avgPrima"));

	var ctxProductsPrimaAvg = document.getElementById("productsPrimaAvgContainer").getContext("2d");
	ProductsPrimaAvgGraph = new Chart(ctxProductsPrimaAvg, {
	    type: "pie",
	    data: {
			labels: WO_Products_PrimaAvg.map(obj => obj.producto),
			datasets: [{
			    label: "# Solicitudes",
			    data: WO_Products_PrimaAvg.map(obj => obj.avgPrima),
			    borderWidth: 1,
			    backgroundColor: ColorsExtended,	
			}]
		},
		options: {
			scales: {
			    yAxes: [{
			        ticks: {
			            beginAtZero:true
			        },
			    }],
			},
			maintainAspectRatio: false,
			tooltips: {
				callbacks: {
					label: function(tooltipItem, data) {
						var allData = data.datasets[tooltipItem.datasetIndex].data;
						var tooltipLabel = data.labels[tooltipItem.index];
						var tooltipData = allData[tooltipItem.index];
						var total = 0;
						for (var i in allData) {
							total += parseFloat(allData[i]);
						}
						var tooltipPercentage = Math.round((tooltipData / total) * 100);

						var nStr = tooltipData;
						nStr += "";
						x = nStr.split(".");
						x1 = x[0];
						x2 = x.length > 1 ? "." + x[1].substring(0,2) : "";
						var rgx = /(\d+)(\d{3})/;
						while (rgx.test(x1)) {
							x1 = x1.replace(rgx, "$1" + "," + "$2");
						}

						return tooltipLabel + ": $" + x1 + x2 + " (" + tooltipPercentage + "%)";
					}
				}
			}
		}
	});

	var ctxGenerations = document.getElementById("generationsContainer").getContext("2d");
	GenerationsGraph = new Chart(ctxGenerations, {
	    type: "pie",
	    data: {
			labels: WO_Generations.map(obj => obj.title),
			datasets: [{
			    label: "# Solicitudes",
			    data: WO_Generations.map(obj => obj.solicitudes),
			    borderWidth: 1,
			    backgroundColor: Colors,	
			}]
		},
		options: {
			scales: {
			    yAxes: [{
			        ticks: {
			            beginAtZero:true
			        },
			    }],
			},
			maintainAspectRatio: false,
			tooltips: {
				callbacks: {
					label: function(tooltipItem, data) {
						var allData = data.datasets[tooltipItem.datasetIndex].data;
						var tooltipLabel = data.labels[tooltipItem.index];
						var tooltipData = allData[tooltipItem.index];
						var total = 0;
						for (var i in allData) {
							total += parseFloat(allData[i]);
						}
						var tooltipPercentage = Math.round((tooltipData / total) * 100);
						return tooltipLabel + ": " + tooltipData + " (" + tooltipPercentage + "%)";
					}
				}
			}
		}
	});

	$(".sorter").click(function (e){
		e.preventDefault();
		var sort_by = $(this).attr("data-sort-by");
		var agents_arr = [];
		changeUrl();
		if(sort_by == "primas"){
			agents_arr = WO_Agents.sort(dynamicSort("-prima"));
			$(this).attr("data-sort-by", "requests");
			$(this).find("span").html("S");
		}
		else{
			agents_arr = WO_Agents.sort(dynamicSort("-conteo"));
			$(this).attr("data-sort-by", "primas");
			$(this).find("span").html("P");
		}

		var tableBody = $("#agentsTable table tbody");
		tableBody.html("");
		$.each(WO_Agents, function(i, order){
			var tr = $("<tr></tr>");
			var link = $("<a href='#' class='popup' data-search='agent'></a>")
			link.attr("data-value", order.id);
			var agente = $("<td>"+order.name+"</td>");
			link.html("$"+number_format(order.prima, 2));
			var primas = $("<td></td>");
			primas.append(link);
			var linkCopy = link.clone();
			linkCopy.html(order.conteo);
			var solicitudes = $("<td></td>");
			solicitudes.append(linkCopy);
			tr.append(agente).append(primas).append(solicitudes);
			tableBody.append(tr);
		});

		AgentsGraph.data.labels = agents_arr.map(obj => obj.name);
		AgentsGraph.config.data.datasets[0].data = agents_arr.map(obj => obj.conteo);
		AgentsGraph.config.data.datasets[1].data = agents_arr.map(obj => obj.prima);
		AgentsGraph.update();

	});

	$("#myTab a").click(function (e) {
	  e.preventDefault();
	  $(this).tab("show");
	  changeUrl();
	  $(window).trigger("resize");
	});
	$(".toggleTable").on("click", function(e){
		e.preventDefault();
		var target = $(this).attr("data-target");
		var resize_target = $(this).attr("data-resize");
		var itag = $(this).find("i");
		itag.toggleClass("icon-signal");
		itag.toggleClass("icon-list-alt");

		var resize_cell = $(this).closest(".row").find(resize_target);
		resize_cell.toggle("fast");
		$(target).toggle("fast");

	});
	$("#tablesorted")
		.tablesorter({theme : "default", widthFixed: true, widgets: ["saveSort", "zebra"]});
	var agentsHeight
	$(".imprimir").click(function(e){
		e.preventDefault();
		$(".print").removeClass("print");
		var printable = $(this.closest(".printable"))
		printable.addClass("print");

		if(printable.attr("id") == "AgentsSection"){
			canvas = printable.find("canvas")[0];
			AgentsGraph.update();
			AgentsGraph.render();
			var dataUrl = AgentsGraph.toBase64Image();
			chartImage = new Image();
			chartImage.onload = function(){
				//printable.find("table").css("margin-top", chartImage.height+"px");
				window.print();
			}
	      	chartImage.src = dataUrl;
	      	chartImage.id = "waitLoad";
	      	$(canvas).css("display", "none");
	      	$(canvas).parent().append(chartImage);
		}
		else
			window.print();
	});
	$(".tab-content").on("click", ".popup", function(e){
		e.preventDefault();
		var search_obj = {};
		search_obj.search = $(this).attr("data-search");
		search_obj.value = $(this).attr("data-value");
		solicitudes_popup(search_obj);
	});
	$(window).on("resize", function(){
		$(".tfoot").css("display", "none")
		var activeTab = $(".nav-tabs .active").index();
		if(activeTab == 0){
			var table = $("#tablesorted");
			$(".tfoot").css({
				"left" : table.offset().left+"px",
				"width": table.width()+"px",
			});
			table.find("thead tr th").each(function(i, val){
				$(".tfoot tr th").eq(i).width($(val).width());
			});
			$(".tfoot").css("display", "table-footer-group");
		}
	});
	$(window).trigger("resize");
});

function dynamicSort(property) {
    var sortOrder = 1;
    if(property[0] === "-") {
        sortOrder = -1;
        property = property.substr(1);
    }
    return function (a,b) {
    	a[property] = parseFloat(a[property]);
    	b[property] = parseFloat(b[property]);
        var result = (a[property] < b[property]) ? -1 : (a[property] > b[property]) ? 1 : 0;
        return result * sortOrder;
    }
}

var beforePrint = function() {
};
var afterPrint = function() {
	var printable = $(".printable");
    if(printable.attr("id") == "AgentsSection"){
    	canvas = printable.find("canvas")[0];
    	table = printable.find("table")[0];
    	$(canvas).css("display", "block");
		$(canvas).parent().find("img").remove();
	}
};

if (window.matchMedia) {
    var mediaQueryList = window.matchMedia('print');
    mediaQueryList.addListener(function(mql) {
        if (mql.matches) {
            beforePrint();
        } else {
            afterPrint();
        }
    });
}

window.onbeforeprint = beforePrint;
window.onafterprint = afterPrint;

function number_format(number, decimals){
	number = parseFloat(Math.round(number * 100) / 100).toFixed(decimals);
	number = number.toString();
    x = number.split(".");
	x1 = x[0];
	x2 = x.length > 1 ? "." + x[1] : "";
    x1 = x1.split(/(?=(?:...)*$)/);
    // Convert the array to a string and format the output
    number = x1.join(",");
    number = number+x2;
    return number;
}

function changeUrl(){
	var selected_tab = $(".nav-tabs .active a").attr("href").substr(1);
	var selected_order = $(".sorter").attr("data-sort-by");
	var newUrl = Config.base_url()+"solicitudes/summary/"+selected_tab+"/"+selected_order+".html";
	$("#ot-form").attr("action", newUrl);
	history.replaceState({}, null, newUrl);
}

function solicitudes_popup(search_obj){
	var url = Config.base_url()+"solicitudes/popup";
	$.fancybox.showLoading();
	$.post(url, search_obj,function(data)
    { 
        if(data)
        {
            $.fancybox({
              content:data
        	});    
        	$("#tableajax").tablesorter({theme : "default", widthFixed: true, widgets: ["zebra"]});
            return false;
        }
    })
    .fail(function() {
	    $.fancybox({
              content: "Ha ocurrido un error, intente mas tarde"
        });    
	});
}