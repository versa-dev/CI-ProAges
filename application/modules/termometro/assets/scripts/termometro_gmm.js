/** 
 * Termometro de Ventas - Script Javascript
 * 
 * @author     Jesus Castilla & José Gilberto Pérez Molina
 * Date:       December, 2018
 * Locaion:    Veracruz, Mexico
 * Mail:       jesuscv1821@gmail.com
 */

$(document).ready(function() {

	enable_number_function_cells();

	var editable_rows = ['prima_ubicar', 'prima_pago', 'nuevos_asegurados', 'cartera_conservada'];
	
	$('#button_simulation').click(function() {
		update_data();
	});

	$('#button_confirm_simulation').click(function() {
		enable_cell_editing();
	});

	$('#button_cancel_simulation').click(function() {
		disable_cell_editing();
	});
	

	$('#button_restore').click(function() {

		$("#cell_prima_ubicar_1").text("$ "+agent_values['primas_ubicar'][0]);
		$("#cell_prima_ubicar_2").text("$ "+agent_values['primas_ubicar'][1]);
		$("#cell_prima_ubicar_3").text("$ "+agent_values['primas_ubicar'][2]);
		$("#cell_prima_ubicar_sum").text("$ "+agent_values['primas_ubicar'][3]);
		$("#cell_prima_ubicar_avg").text("$ "+agent_values['primas_ubicar'][4]);

		$("#cell_prima_pago_1").text("$ "+agent_values['primas_pagar'][0]);
		$("#cell_prima_pago_2").text("$ "+agent_values['primas_pagar'][1]);
		$("#cell_prima_pago_3").text("$ "+agent_values['primas_pagar'][2]);
		$("#cell_prima_pago_sum").text("$ "+agent_values['primas_pagar'][3]);
		$("#cell_prima_pago_avg").text("$ "+agent_values['primas_pagar'][4]);

		$("#cell_nuevos_asegurados_1").text(agent_values['nuevos_asegurados'][0]);
		$("#cell_nuevos_asegurados_2").text(agent_values['nuevos_asegurados'][1]);
		$("#cell_nuevos_asegurados_3").text(agent_values['nuevos_asegurados'][2]);
		$("#cell_nuevos_asegurados_sum").text(agent_values['nuevos_asegurados'][3]);
		$("#cell_nuevos_asegurados_avg").text(agent_values['nuevos_asegurados'][4]);

		$("#cell_cartera_conservada_1").text("$ "+agent_values['cartera_conservada'][0]);
		$("#cell_cartera_conservada_2").text("$ "+agent_values['cartera_conservada'][1]);
		$("#cell_cartera_conservada_3").text("$ "+agent_values['cartera_conservada'][2]);
		$("#cell_cartera_conservada_sum").text("$ "+agent_values['cartera_conservada'][3]);
		$("#cell_cartera_conservada_avg").text("$ "+agent_values['cartera_conservada'][4]);

		$("#cell_comisiones_directas_1").text("$ "+agent_values['comisiones_directas'][0]);
		$("#cell_comisiones_directas_2").text("$ "+agent_values['comisiones_directas'][1]);
		$("#cell_comisiones_directas_3").text("$ "+agent_values['comisiones_directas'][2]);
		$("#cell_comisiones_directas_sum").text("$ "+agent_values['comisiones_directas'][3]);
		$("#cell_comisiones_directas_avg").text("$ "+agent_values['comisiones_directas'][4]);

		$("#cell_bono_primer_anio_perc_1").text(agent_values['perce_bono_primer_anio'][0]+" %");
		$("#cell_bono_primer_anio_perc_2").text(agent_values['perce_bono_primer_anio'][1]+" %");
		$("#cell_bono_primer_anio_perc_3").text(agent_values['perce_bono_primer_anio'][2]+" %");

		$("#cell_bono_primer_anio_1").text("$ "+agent_values['bono_primer_anio'][0]);
		$("#cell_bono_primer_anio_2").text("$ "+agent_values['bono_primer_anio'][1]);
		$("#cell_bono_primer_anio_3").text("$ "+agent_values['bono_primer_anio'][2]);
		$("#cell_bono_primer_anio_sum").text("$ "+agent_values['bono_primer_anio'][3]);
		$("#cell_bono_primer_anio_avg").text("$ "+agent_values['bono_primer_anio'][4]);

		$("#cell_faltante_bono_primer_anio_1").text("$ "+agent_values['faltante_bono_primer_anio'][0]);
		$("#cell_faltante_bono_primer_anio_2").text("$ "+agent_values['faltante_bono_primer_anio'][1]);
		$("#cell_faltante_bono_primer_anio_3").text("$ "+agent_values['faltante_bono_primer_anio'][2]);

		$("#cell_primas_netas_1").text("$ "+agent_values['primas_netas'][0]);
		$("#cell_primas_netas_2").text("$ "+agent_values['primas_netas'][1]);
		$("#cell_primas_netas_3").text("$ "+agent_values['primas_netas'][2]);
		$("#cell_primas_netas_sum").text("$ "+agent_values['primas_netas'][3]);
		$("#cell_primas_netas_avg").text("$ "+agent_values['primas_netas'][4]);

		$("#cell_siniestros_pagados_real_1").text("$ "+agent_values['siniestros_pagados_real'][0]);
		$("#cell_siniestros_pagados_real_2").text("$ "+agent_values['siniestros_pagados_real'][1]);
		$("#cell_siniestros_pagados_real_3").text("$ "+agent_values['siniestros_pagados_real'][2]);
		$("#cell_siniestros_pagados_real_sum").text("$ "+agent_values['siniestros_pagados_real'][3]);
		$("#cell_siniestros_pagados_real_avg").text("$ "+agent_values['siniestros_pagados_real'][4]);

		$("#cell_siniestros_pagados_acot_1").text("$ "+agent_values['siniestros_pagados_acot'][0]);
		$("#cell_siniestros_pagados_acot_2").text("$ "+agent_values['siniestros_pagados_acot'][1]);
		$("#cell_siniestros_pagados_acot_3").text("$ "+agent_values['siniestros_pagados_acot'][2]);
		$("#cell_siniestros_pagados_acot_sum").text("$ "+agent_values['siniestros_pagados_acot'][3]);
		$("#cell_siniestros_pagados_acot_avg").text("$ "+agent_values['siniestros_pagados_acot'][4]);

		$("#cell_result_siniestralidad_real_1").text(agent_values['result_siniestralidad_real'][0] +" %");
		$("#cell_result_siniestralidad_real_2").text(agent_values['result_siniestralidad_real'][1]+" %");
		$("#cell_result_siniestralidad_real_3").text(agent_values['result_siniestralidad_real'][2]+" %");

		$("#cell_result_siniestralidad_acot_1").text(agent_values['result_siniestralidad_acot'][0]+" %");
		$("#cell_result_siniestralidad_acot_2").text(agent_values['result_siniestralidad_acot'][1]+" %");
		$("#cell_result_siniestralidad_acot_3").text(agent_values['result_siniestralidad_acot'][2]+" %");

		$("#cell_comision_cartera_1").text("$ "+agent_values['comision_cartera'][0]);
		$("#cell_comision_cartera_2").text("$ "+agent_values['comision_cartera'][1]);
		$("#cell_comision_cartera_3").text("$ "+agent_values['comision_cartera'][2]);
		$("#ccell_comision_cartera_sum").text("$ "+agent_values['comision_cartera'][3]);
		$("#cell_comision_cartera_avg").text("$ "+agent_values['comision_cartera'][4]);

		$("#cell_numero_asegurados_1").text(agent_values['numero_asegurados'][0]);
		$("#cell_numero_asegurados_2").text(agent_values['numero_asegurados'][1]);
		$("#cell_numero_asegurados_3").text(agent_values['numero_asegurados'][2]);
		$("#cell_numero_asegurados_sum").text(agent_values['numero_asegurados'][3]);
		$("#cell_numero_asegurados_avg").text(agent_values['numero_asegurados'][4]);

		$("#cell_perce_bono_rentabilidad_1").text(agent_values['perce_bono_rentabilidad'][0]+" %");
		$("#cell_perce_bono_rentabilidad_2").text(agent_values['perce_bono_rentabilidad'][1]+" %");
		$("#cell_perce_bono_rentabilidad_3").text(agent_values['perce_bono_rentabilidad'][2]+" %");

		$("#cell_bono_rentabilidad_1").text("$ "+agent_values['bono_rentabilidad'][0]);
		$("#cell_bono_rentabilidad_2").text("$ "+agent_values['bono_rentabilidad'][1]);
		$("#cell_bono_rentabilidad_3").text("$ "+agent_values['bono_rentabilidad'][2]);
		$("#cell_bono_rentabilidad_sum").text("$ "+agent_values['bono_rentabilidad'][3]);
		$("#cell_bono_rentabilidad_avg").text("$ "+agent_values['bono_rentabilidad'][4]);

		$("#cell_faltante_bono_rentabilidad_1").text("$ "+agent_values['faltante_bono_rentabilidad'][0]);
		$("#cell_faltante_bono_rentabilidad_2").text("$ "+agent_values['faltante_bono_rentabilidad'][1]);
		$("#cell_faltante_bono_rentabilidad_3").text("$ "+agent_values['faltante_bono_rentabilidad'][2]);

		$("#cell_bono_rentabilidad_no_ganado_1").text("$ "+agent_values['bono_rentabilidad_no_ganado'][0]);
		$("#cell_bono_rentabilidad_no_ganado_2").text("$ "+agent_values['bono_rentabilidad_no_ganado'][1]);
		$("#cell_bono_rentabilidad_no_ganado_3").text("$ "+agent_values['bono_rentabilidad_no_ganado'][2]);
		$("#cell_bono_rentabilidad_no_ganado_sum").text("$ "+agent_values['bono_rentabilidad_no_ganado'][3]);
		$("#cell_bono_rentabilidad_no_ganado_avg").text("$ "+agent_values['bono_rentabilidad_no_ganado'][4]);

		$("#cell_suma_ingresos_1").text("$ "+agent_values['suma_ingresos'][0]);
		$("#cell_suma_ingresos_2").text("$ "+agent_values['suma_ingresos'][1]);
		$("#cell_suma_ingresos_3").text("$ "+agent_values['suma_ingresos'][2]);
		$("#cell_suma_ingresos_sum").text("$ "+agent_values['suma_ingresos'][3]);
		$("#cell_suma_ingresos_avg").text("$ "+agent_values['suma_ingresos'][4]);

		$("#cell_congreso").text(agent_values['congreso']);
		$("#cell_congreso_siguiente").text(agent_values['congreso_siguiente']);
		$("#cell_faltante_produccion_inicial").text("$ "+agent_values['faltante_produccion_inicial']);
		$("#cell_agente_productivo").text(agent_values['agente_productivo'][0]);
		$("#cell_faltante_agente_productivo").text("$ "+agent_values['agente_productivo'][1]);
		$("#cell_ingresos_totales").text("$ "+agent_values['ingresos_totales']);
	});
	
	function update_data(){
		var primas_pagos = [0,0,0];
		var prima_ubicar = [0,0,0];
		var nuev_asegurados = [0,0,0];
		var cartera_conservada = [0,0,0];

		for (var i = 1; i < 4; i++) {
			//console.log(i);
			var text_primas = $("#cell_prima_pago_" + i).text();
			primas_pagos[i-1] = parseFloat(text_primas.replace(/[\%$, ]/g, ''));

			var text_primas_ubi = $("#cell_prima_ubicar_" + i).text();
			prima_ubicar[i-1] = parseFloat(text_primas_ubi.replace(/[\%$, ]/g, ''));

			var text_asegurados = $("#cell_nuevos_asegurados_" + i).text();
			nuev_asegurados[i-1] = parseFloat(text_asegurados.replace(/[\%$, ]/g, ''));

			var text_cartera = $("#cell_cartera_conservada_" + i).text();
			cartera_conservada[i-1] = parseFloat(text_cartera.replace(/[\%$, ]/g, ''));
		}

		var generation = agent_values['generation'];
		var data_values = {prima_ubicar,primas_pagos,nuev_asegurados,cartera_conservada,agent_id,generation,year};
		//console.log(data_values);
		$.ajax({
			url: Config.url + 'termometro/simulate_gmm',
			type:'POST',
			data: data_values,
			success:function(result){
				var array = JSON.parse(result);

				$("#cell_prima_ubicar_1").text("$ "+array['primas_ubicar'][0]);
				$("#cell_prima_ubicar_2").text("$ "+array['primas_ubicar'][1]);
				$("#cell_prima_ubicar_3").text("$ "+array['primas_ubicar'][2]);
				$("#cell_prima_ubicar_sum").text("$ "+array['primas_ubicar'][3]);
				$("#cell_prima_ubicar_avg").text("$ "+array['primas_ubicar'][4]);

				$("#cell_prima_pago_1").text("$ "+array['primas_pagar'][0]);
				$("#cell_prima_pago_2").text("$ "+array['primas_pagar'][1]);
				$("#cell_prima_pago_3").text("$ "+array['primas_pagar'][2]);
				$("#cell_prima_pago_sum").text("$ "+array['primas_pagar'][3]);
				$("#cell_prima_pago_avg").text("$ "+array['primas_pagar'][4]);

				$("#cell_nuevos_asegurados_1").text(array['nuevos_asegurados'][0]);
				$("#cell_nuevos_asegurados_2").text(array['nuevos_asegurados'][1]);
				$("#cell_nuevos_asegurados_3").text(array['nuevos_asegurados'][2]);
				$("#cell_nuevos_asegurados_sum").text(array['nuevos_asegurados'][3]);
				$("#cell_nuevos_asegurados_avg").text(array['nuevos_asegurados'][4]);

				$("#cell_comisiones_directas_1").text("$ "+array['comisiones_directas'][0]);
				$("#cell_comisiones_directas_2").text("$ "+array['comisiones_directas'][1]);
				$("#cell_comisiones_directas_3").text("$ "+array['comisiones_directas'][2]);
				$("#cell_comisiones_directas_sum").text("$ "+array['comisiones_directas'][3]);
				$("#cell_comisiones_directas_avg").text("$ "+array['comisiones_directas'][4]);

				$("#cell_bono_primer_anio_perc_1").text(array['perce_bono_primer_anio'][0]+" %");
				$("#cell_bono_primer_anio_perc_2").text(array['perce_bono_primer_anio'][1]+" %");
				$("#cell_bono_primer_anio_perc_3").text(array['perce_bono_primer_anio'][2]+" %");

				$("#cell_bono_primer_anio_1").text("$ "+array['bono_primer_anio'][0]);
				$("#cell_bono_primer_anio_2").text("$ "+array['bono_primer_anio'][1]);
				$("#cell_bono_primer_anio_3").text("$ "+array['bono_primer_anio'][2]);
				$("#cell_bono_primer_anio_sum").text("$ "+array['bono_primer_anio'][3]);
				$("#cell_bono_primer_anio_avg").text("$ "+array['bono_primer_anio'][4]);

				$("#cell_faltante_bono_primer_anio_1").text("$ "+array['faltante_bono_primer_anio'][0]);
				$("#cell_faltante_bono_primer_anio_2").text("$ "+array['faltante_bono_primer_anio'][1]);
				$("#cell_faltante_bono_primer_anio_3").text("$ "+array['faltante_bono_primer_anio'][2]);

				$("#cell_primas_netas_1").text("$ "+array['primas_netas'][0]);
				$("#cell_primas_netas_2").text("$ "+array['primas_netas'][1]);
				$("#cell_primas_netas_3").text("$ "+array['primas_netas'][2]);
				$("#cell_primas_netas_sum").text("$ "+array['primas_netas'][3]);
				$("#cell_primas_netas_avg").text("$ "+array['primas_netas'][4]);

				$("#cell_siniestros_pagados_real_1").text("$ "+array['siniestros_pagados_real'][0]);
				$("#cell_siniestros_pagados_real_2").text("$ "+array['siniestros_pagados_real'][1]);
				$("#cell_siniestros_pagados_real_3").text("$ "+array['siniestros_pagados_real'][2]);
				$("#cell_siniestros_pagados_real_sum").text("$ "+array['siniestros_pagados_real'][3]);
				$("#cell_siniestros_pagados_real_avg").text("$ "+array['siniestros_pagados_real'][4]);

				$("#cell_siniestros_pagados_acot_1").text("$ "+array['siniestros_pagados_acot'][0]);
				$("#cell_siniestros_pagados_acot_2").text("$ "+array['siniestros_pagados_acot'][1]);
				$("#cell_siniestros_pagados_acot_3").text("$ "+array['siniestros_pagados_acot'][2]);
				$("#cell_siniestros_pagados_acot_sum").text("$ "+array['siniestros_pagados_acot'][3]);
				$("#cell_siniestros_pagados_acot_avg").text("$ "+array['siniestros_pagados_acot'][4]);

				$("#cell_result_siniestralidad_real_1").text(array['result_siniestralidad_real'][0] +" %");
				$("#cell_result_siniestralidad_real_2").text(array['result_siniestralidad_real'][1]+" %");
				$("#cell_result_siniestralidad_real_3").text(array['result_siniestralidad_real'][2]+" %");

				$("#cell_result_siniestralidad_acot_1").text(array['result_siniestralidad_acot'][0]+" %");
				$("#cell_result_siniestralidad_acot_2").text(array['result_siniestralidad_acot'][1]+" %");
				$("#cell_result_siniestralidad_acot_3").text(array['result_siniestralidad_acot'][2]+" %");

				$("#cell_cartera_conservada_1").text("$ "+array['cartera_conservada'][0]);
				$("#cell_cartera_conservada_2").text("$ "+array['cartera_conservada'][1]);
				$("#cell_cartera_conservada_3").text("$ "+array['cartera_conservada'][2]);
				$("#cell_cartera_conservada_sum").text("$ "+array['cartera_conservada'][3]);
				$("#cell_cartera_conservada_avg").text("$ "+array['cartera_conservada'][4]);

				$("#cell_comision_cartera_1").text("$ "+array['comision_cartera'][0]);
				$("#cell_comision_cartera_2").text("$ "+array['comision_cartera'][1]);
				$("#cell_comision_cartera_3").text("$ "+array['comision_cartera'][2]);
				$("#ccell_comision_cartera_sum").text("$ "+array['comision_cartera'][3]);
				$("#cell_comision_cartera_avg").text("$ "+array['comision_cartera'][4]);

				$("#cell_numero_asegurados_1").text(array['numero_asegurados'][0]);
				$("#cell_numero_asegurados_2").text(array['numero_asegurados'][1]);
				$("#cell_numero_asegurados_3").text(array['numero_asegurados'][2]);
				$("#cell_numero_asegurados_sum").text(array['numero_asegurados'][3]);
				$("#cell_numero_asegurados_avg").text(array['numero_asegurados'][4]);

				$("#cell_perce_bono_rentabilidad_1").text(array['perce_bono_rentabilidad'][0]+" %");
				$("#cell_perce_bono_rentabilidad_2").text(array['perce_bono_rentabilidad'][1]+" %");
				$("#cell_perce_bono_rentabilidad_3").text(array['perce_bono_rentabilidad'][2]+" %");

				$("#cell_bono_rentabilidad_1").text("$ "+array['bono_rentabilidad'][0]);
				$("#cell_bono_rentabilidad_2").text("$ "+array['bono_rentabilidad'][1]);
				$("#cell_bono_rentabilidad_3").text("$ "+array['bono_rentabilidad'][2]);
				$("#cell_bono_rentabilidad_sum").text("$ "+array['bono_rentabilidad'][3]);
				$("#cell_bono_rentabilidad_avg").text("$ "+array['bono_rentabilidad'][4]);

				$("#cell_faltante_bono_rentabilidad_1").text("$ "+array['faltante_bono_rentabilidad'][0]);
				$("#cell_faltante_bono_rentabilidad_2").text("$ "+array['faltante_bono_rentabilidad'][1]);
				$("#cell_faltante_bono_rentabilidad_3").text("$ "+array['faltante_bono_rentabilidad'][2]);

				$("#cell_bono_rentabilidad_no_ganado_1").text("$ "+array['bono_rentabilidad_no_ganado'][0]);
				$("#cell_bono_rentabilidad_no_ganado_2").text("$ "+array['bono_rentabilidad_no_ganado'][1]);
				$("#cell_bono_rentabilidad_no_ganado_3").text("$ "+array['bono_rentabilidad_no_ganado'][2]);
				$("#cell_bono_rentabilidad_no_ganado_sum").text("$ "+array['bono_rentabilidad_no_ganado'][3]);
				$("#cell_bono_rentabilidad_no_ganado_avg").text("$ "+array['bono_rentabilidad_no_ganado'][4]);

				$("#cell_suma_ingresos_1").text("$ "+array['suma_ingresos'][0]);
				$("#cell_suma_ingresos_2").text("$ "+array['suma_ingresos'][1]);
				$("#cell_suma_ingresos_3").text("$ "+array['suma_ingresos'][2]);
				$("#cell_suma_ingresos_sum").text("$ "+array['suma_ingresos'][3]);
				$("#cell_suma_ingresos_avg").text("$ "+array['suma_ingresos'][4]);
				
				$("#cell_congreso").text(array['congreso']);
				$("#cell_congreso_siguiente").text(array['congreso_siguiente']);
				$("#cell_faltante_produccion_inicial").text("$ "+array['faltante_produccion_inicial']);
				$("#cell_agente_productivo").text(array['agente_productivo'][0]);
				$("#cell_faltante_agente_productivo").text("$ "+array['agente_productivo'][1]);
				$("#cell_ingresos_totales").text("$ "+array['ingresos_totales']);

			},
			error: function(xhr, status, error) {
				console.log(error);
			}
		});
		
	}

	function enable_cell_editing(){
		editable_rows.forEach(enable_row);
	}

	function disable_cell_editing(){
		editable_rows.forEach(disable_row);
	}

	function enable_row(item){
		$(".row_" + item).addClass('celda_verde');
		$(".row_" + item).prop('contenteditable', true);
	}


	function disable_row(item){
		$(".row_" + item).removeClass('celda_verde');
		$(".row_" + item).prop('contenteditable', false);
	}

	function enable_number_function_cells(){
        for (var i = 1; i < 4; i++) {
            $("#cell_prima_ubicar_" + i).click(function(){
                $(this).text("");
            });
            $("#cell_prima_pago_" + i).click(function(){
                $(this).text("");
            });
            $("#cell_nuevos_asegurados_" + i).click(function(){
                $(this).text("");
            });
            $("#cell_cartera_conservada_" + i).click(function(){
                $(this).text("");
            });

        }
    }

	$(this).keypress(function(e) {
	    if(e.which == 13) {
	    	e.preventDefault();
	    	$("#button_simulation").click();
	    }
	});

	$("#simulate_form_export").on('submit', function(event){
        var primas_pagos = [0,0,0];
		var prima_ubicar = [0,0,0];
		var nuev_asegurados = [0,0,0];
		var cartera_conservada = [0,0,0];

		for (var i = 1; i < 4; i++) {
			//console.log(i);
			var text_primas = $("#cell_prima_pago_" + i).text();
			primas_pagos[i-1] = parseFloat(text_primas.replace(/[\%$, ]/g, ''));

			var text_primas_ubi = $("#cell_prima_ubicar_" + i).text();
			prima_ubicar[i-1] = parseFloat(text_primas_ubi.replace(/[\%$, ]/g, ''));

			var text_asegurados = $("#cell_nuevos_asegurados_" + i).text();
			nuev_asegurados[i-1] = parseFloat(text_asegurados.replace(/[\%$, ]/g, ''));

			var text_cartera = $("#cell_cartera_conservada_" + i).text();
			cartera_conservada[i-1] = parseFloat(text_cartera.replace(/[\%$, ]/g, ''));
		}

		var generation = agent_values['generation'];
		var data_values = {prima_ubicar,primas_pagos,nuev_asegurados,cartera_conservada,agent_id,generation,year};
        var input = $("<input>").attr("type", "hidden").attr("name", "data_test").val(JSON.stringify(data_values));
        $('#simulate_form_export').append(input);
    });
});