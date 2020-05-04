// Javascript to handle the filter custom período

$(document).ready(function() {
	var defFrom = $("#cust_period_from").val();

	var defTo = $("#cust_period_to").val();

	var updatePeriodSelect = function( selectSelector ) {
		var result = false;
		$(selectSelector + " option").each(function () {
			if ($(this).val() == 4) {
				var customText = 'Personalizado';
				defFrom = $("#cust_period_from").val();
				defTo = $("#cust_period_to").val();
				if ((defFrom.length > 0) && (defTo.length > 0)) {
					customText += " (De " + defFrom + " Hasta " + defTo + ")";
				}
				$(this).html(customText);
				if ($(this).prop("selected"))
					result = true;
				return false;
			}
		});
		return result;
	}
	updatePeriodSelect("#periodo");

	var customDateOptions = {
		changeMonth: true,
		showOtherMonths: true,
		selectOtherMonths: true,
		firstDay:1,
		closeText: 'Cerrar',
		prevText: '&#x3c;Ant',
		nextText: 'Sig&#x3e;',
		currentText: 'Hoy',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
			'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
			'Jul','Ago','Sep','Oct','Nov','Dic'],
		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
		dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
	};
	$( "#cust_period_from" ).datepicker({
		defaultDate: defFrom,
		dateFormat: "yy-mm-dd",
		onClose: function( selectedDate ) {
			$( "#cust_period_to" ).datepicker( "option", "minDate", selectedDate );
		}
	});
	$( "#cust_period_to" ).datepicker({
		defaultDate: defTo,
		dateFormat: "yy-mm-dd",
		onClose: function( selectedDate ) {
			$( "#cust_period_from" ).datepicker( "option", "maxDate", selectedDate );
		}
	});

	jQuery.each(customDateOptions, function(i, val) {
		$( "#cust_period_from" ).datepicker( "option", i, val );
		$( "#cust_period_to" ).datepicker("option", i, val );
	});		

	$( "#cust_period-form" ).dialog({
		autoOpen: false,
		height: 300,
		width: 350,
		modal: true,
		buttons: {
			"Guardar": function() {
				$.ajax({
					url: Config.url + 'ot/update_custom_period',
					type: 'POST',
					data: $("#cust_period-form").serialize(),
					dataType : 'json',
					success: function(response){
						if (response == '1') {
							alert('Se ha guardado el período personalizado correctamente.');
							var customSelected = updatePeriodSelect("#periodo");
							if (customSelected) // if the period currently selected is the custom one, refresh the page
//								window.location.reload();
								$("#periodo").parents("form").submit();
						}
					}
				});
				$( this ).dialog( "close" );
				return false;
			},
			"Cancelar": function() {
				$("#cust_period_from").val(defFrom);
				$("#cust_period_to").val(defTo);
				$(".activity_results").show();
				$( this ).dialog( "close" );
				return false;
			}
		},
		close: function() {
		}
	});

	$( "#cust_update-period" )
		.button()
		.click(function() {
			$( "#cust_period-form" ).dialog( "open" );
			return false;
		});
	
});
