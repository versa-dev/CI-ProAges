/** 
 * Termometro de Ventas - Script Javascript
 * 
 * @author     Jesus Castilla & José Gilberto Pérez Molina
 * Date:       December, 2018
 * Locaion:    Veracruz, Mexico
 * Mail:       jesuscv1821@gmail.com
 */

$(document).ready(function() { 

	//grafica de ingresos por generación
	//para conocer la documentacion de la grafica visitar https://www.chartjs.org/docs/latest/
	var ctx7 = document.getElementById("generation_container_chart").getContext("2d");
    var chart7 = new Chart(ctx7, {
        type: 'pie',
        responsive: true,
	    data: {
	        labels: names_generations,
	        datasets: [{
	            label: 'Generaciones',
	            data: values_generation,
	            backgroundColor: colors_generation,
	            borderColor: colors_generation,
	            borderWidth: 1
	        }],
	        legend: {
                display: true,
                labels: {
                    fontColor: "#999999"
                }
            }
	    },
	    options: {
	    	tooltips: {
                mode: "index",
                callbacks: {
                    label: function (tooltipItem, data) {
                        var allData = data.datasets[tooltipItem.datasetIndex].data;
                        var tooltipLabel = data.labels[tooltipItem.index];
                        var tooltipData = allData[tooltipItem.index];
                        return tooltipLabel + " : $" + number_format(tooltipData, 2);
                    }
                },
                displayColors: false
            },
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero:true
	                }
	            }]
	        }
	    }
    });
    //grafica de ingresos por ramo en vida
    var ctx_vida_ingresos = document.getElementById("generation_container_chart_vida").getContext("2d");
    var chart_vida_ingresos = new Chart(ctx_vida_ingresos, {
        type: 'pie',
        responsive: true,
	    data: {
	        labels: names_generations,
	        datasets: [{
	            label: 'Generaciones',
	            data: values_generation_vida,
	            backgroundColor: colors_generation,
	            borderColor: colors_generation,
	            borderWidth: 1
	        }],
	        legend: {
                display: true,
                labels: {
                    fontColor: "#999999"
                }
            }
	    },
	    options: {
	    	tooltips: {
                mode: "index",
                callbacks: {
                    label: function (tooltipItem, data) {
                        var allData = data.datasets[tooltipItem.datasetIndex].data;
                        var tooltipLabel = data.labels[tooltipItem.index];
                        var tooltipData = allData[tooltipItem.index];
                        return tooltipLabel + " : $" + number_format(tooltipData, 2);
                    }
                },
                displayColors: false
            },
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero:true
	                }
	            }]
	        }
	    }
    });

    //grafica de ingresos por ramo en gmm
    var ctx_gmm_ingresos = document.getElementById("generation_container_chart_gmm").getContext("2d");
    var chart_gmm_ingresos = new Chart(ctx_gmm_ingresos, {
        type: 'pie',
        responsive: true,
	    data: {
	        labels: names_generations,
	        datasets: [{
	            label: 'Generaciones',
	            data: values_generation_gmm,
	            backgroundColor: colors_generation,
	            borderColor: colors_generation,
	            borderWidth: 1
	        }],
	        legend: {
                display: true,
                labels: {
                    fontColor: "#999999"
                }
            }
	    },
	    options: {
	    	tooltips: {
                mode: "index",
                callbacks: {
                    label: function (tooltipItem, data) {
                        var allData = data.datasets[tooltipItem.datasetIndex].data;
                        var tooltipLabel = data.labels[tooltipItem.index];
                        var tooltipData = allData[tooltipItem.index];
                        return tooltipLabel + " : $" + number_format(tooltipData, 2);
                    }
                },
                displayColors: false
            },
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero:true
	                }
	            }]
	        }
	    }
    });
  

    //grafica de ingresos por ramo
    var ctx_ramo = document.getElementById("ramo_container_chart").getContext("2d");
    var chart_ramo = new Chart(ctx_ramo, {
        type: 'pie',
        responsive: true,
	    data: {
	        labels: names_ramo,
	        datasets: [{
	            label: 'Ramos',
	            data: values_ramo,
	            backgroundColor: colors_generation,
	            borderColor: colors_generation,
	            borderWidth: 1
	        }],
	        legend: {
                display: true,
                labels: {
                    fontColor: "#999999"
                }
            }
	    },
	    options: {
	    	tooltips: {
                mode: "index",
                callbacks: {
                    label: function (tooltipItem, data) {
                        var allData = data.datasets[tooltipItem.datasetIndex].data;
                        var tooltipLabel = data.labels[tooltipItem.index];
                        var tooltipData = allData[tooltipItem.index];
                        return tooltipLabel + " : $" + number_format(tooltipData, 2);
                    }
                },
                displayColors: false
            },
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero:true
	                }
	            }]
	        }
	    }
    });

    //grafica de congresistas Gmm
    /*var ctx_congress_gmm = document.getElementById("congreso_gmm_container_chart").getContext("2d");
    var chart_congress_gmm = new Chart(ctx_congress_gmm, {
        type: 'pie',
        responsive: true,
	    data: {
	        labels: names_congreso,
	        datasets: [{
	            label: 'Congresos',
	            data: values_congreso_gmm,
	            backgroundColor: colors_generation,
	            borderColor: colors_generation,
	            borderWidth: 1
	        }],
	        legend: {
                display: true,
                labels: {
                    fontColor: "#999999"
                }
            }
	    },
	    options: {
	    	tooltips: {
                mode: "index",
                callbacks: {
                    label: function (tooltipItem, data) {
                        var allData = data.datasets[tooltipItem.datasetIndex].data;
                        var tooltipLabel = data.labels[tooltipItem.index];
                        var tooltipData = allData[tooltipItem.index];
                        return tooltipLabel + " : " + number_format(tooltipData,0);
                    }
                },
                displayColors: false
            },
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero:true
	                }
	            }]
	        }
	    }
    });*/

    //grafica gmm por concepto
    
    var ctx_concept_gmm = document.getElementById("concept_gmm_container_chart").getContext("2d");
    var chart_concept_gmm = new Chart(ctx_concept_gmm, {
        type: 'pie',
        responsive: true,
        data: {
            labels: names_concept,
            datasets: [{
                label: 'Congresos',
                data: values_concept_gmm,
                backgroundColor: colors_generation,
                borderColor: colors_generation,
                borderWidth: 1
            }],
            legend: {
                display: true,
                labels: {
                    fontColor: "#999999"
                }
            }
        },
        options: {
            tooltips: {
                mode: "index",
                callbacks: {
                    label: function (tooltipItem, data) {
                        var allData = data.datasets[tooltipItem.datasetIndex].data;
                        var tooltipLabel = data.labels[tooltipItem.index];
                        var tooltipData = allData[tooltipItem.index];
                        return tooltipLabel + " : $" + number_format(tooltipData,0);
                    }
                },
                displayColors: false
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });

    //grafica vida por concepto
    var ctx_concept_vida = document.getElementById("concept_vida_container_chart").getContext("2d");
    var chart_concept_vida = new Chart(ctx_concept_vida, {
        type: 'pie',
        responsive: true,
        data: {
            labels: names_concept,
            datasets: [{
                label: 'Congresos',
                data: values_concept_vida,
                backgroundColor: colors_generation,
                borderColor: colors_generation,
                borderWidth: 1
            }],
            legend: {
                display: true,
                labels: {
                    fontColor: "#999999"
                }
            }
        },
        options: {
            tooltips: {
                mode: "index",
                callbacks: {
                    label: function (tooltipItem, data) {
                        var allData = data.datasets[tooltipItem.datasetIndex].data;
                        var tooltipLabel = data.labels[tooltipItem.index];
                        var tooltipData = allData[tooltipItem.index];
                        return tooltipLabel + " : $" + number_format(tooltipData,0);
                    }
                },
                displayColors: false
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
    
    //grafica de congresistas por tipo
    var ctx_congress = document.getElementById("congreso_container_chart").getContext("2d");
    var chart_congress = new Chart(ctx_congress, {
        type: 'pie',
        responsive: true,
	    data: {
	        labels: names_congreso,
	        datasets: [{
	            label: 'Congresos',
	            data: values_congreso,
	            backgroundColor: colors_generation,
	            borderColor: colors_generation,
	            borderWidth: 1
	        }],
	        legend: {
                display: true,
                labels: {
                    fontColor: "#999999"
                }
            }
	    },
	    options: {
	    	tooltips: {
                mode: "index",
                callbacks: {
                    label: function (tooltipItem, data) {
                        var allData = data.datasets[tooltipItem.datasetIndex].data;
                        var tooltipLabel = data.labels[tooltipItem.index];
                        var tooltipData = allData[tooltipItem.index];
                        return tooltipLabel + " : " + number_format(tooltipData,0);
                    }
                },
                displayColors: false
            },
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero:true
	                }
	            }]
	        }
	    }
    });

});

$(".table-generation").on("click", ".popup", function (e) {
    e.preventDefault();
    var search_obj = {};
    search_obj.search = $(this).attr("data-search");
    search_obj.value = $(this).attr("data-value");
    generaciones_popup(search_obj);
});