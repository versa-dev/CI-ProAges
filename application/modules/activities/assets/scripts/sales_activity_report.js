// JavaScript Document
/*

  Author		Ulises Rodríguez
  Site:			http://www.ulisesrodriguez.com	
  Twitter:		https://twitter.com/#!/isc_ulises
  Facebook:		http://www.facebook.com/ISC.Ulises
  Github:		https://github.com/ulisesrodriguez
  Email:		ing.ulisesrodriguez@gmail.com
  Skype:		systemonlinesoftware
  Location:		Guadalajara Jalisco Mexíco

  	
*/

$( document ).ready(function() {

	$(".tablesorter-childRow td").hide();	
	$("#sales-activity-normal")
		.tablesorter({ 
			theme : 'default',
			stringTo: 'max',
			headers: {  // non-numeric content is treated as a MIN value
				9: { sorter: "digit", string: "min" },
				11: { sorter: "digit", string : "min" },
				13: { sorter: "digit", string : "min" },
				15: { sorter: "digit", string: "min" }
			},
			// this is the default setting
			cssChildRow: "tablesorter-childRow"
		});

	$("#sales-activity-efectividad")
		.tablesorter({ 
			theme : 'default',
			stringTo: 'max',
			headers: {  // non-numeric content is treated as a MIN value
				4: { sorter: "digit", string : "min" },
				9: { sorter: "digit", string: "min" },
				12: { sorter: "digit", string: "min" }
			},
			// this is the default setting
			cssChildRow: "tablesorter-childRow"
		});
	// Toggle child row content (td), not hiding the row since we are using rowspan
	// Using delegate because the pager plugin rebuilds the table after each page change
	// "delegate" works in jQuery 1.4.2+; use "live" back to v1.3; for older jQuery - SOL
	$(".sales-activity-results").delegate(".toggle", "click" ,function(){
		// use "nextUntil" to toggle multiple child rows
		// toggle table cells instead of the row
		$(this).closest('tr').nextUntil('tr:not(.tablesorter-childRow)').find('td').toggle();

		return false;
	});

	//assign the sortStart and sortEnd events (for the moment, no special handling)
	$(".sales-activity-results")
		.on("sortStart",function(e, t) {
		})
		.on("sortEnd",function(e, t) {
		});

	$(".solicitudes-negocios").attr("title", "Haga click aqui para ver los detalles")
		.on("click", function() {
			var current = $(this);
			var parentTrId = current.parents("tr").attr("id");
			agentId = parentTrId.replace(/normal-agent-id-/, "").replace(/efectividad-agent-id-/, "");
			var type = "";
			if (current.hasClass("vida-solicitudes")) {
				type = "vida-solicitudes";
			}
			else if (current.hasClass("vida-negocios")) {
				type = "vida-negocios";
			}
			else if (current.hasClass("gmm-solicitudes")) {
				type = "gmm-solicitudes";
			}
			else if (current.hasClass("gmm-negocios")) {
				type = "gmm-negocios";
			}
			if (type.length > 0) {
				$.fancybox.showLoading();
				var postData = 'agent_id=' + agentId + '&type=' + type + "&" + $("#sales-activity-form").serialize();
				$.post(Config.base_url() + "activities/sales_popup.html",
					postData,
					function(data) { 
						if	(data) {
							$.fancybox({
								content:data
							});
							return false;
						}
					});
			}
			return false;
		});

});