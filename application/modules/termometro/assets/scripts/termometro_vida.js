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
	$('#button_simulation').click(function() {
        update_data();
    });
    $('#button_restore').click(function() {
        $("#cell_cartera_real_0").text("$ "+agent_values['cartera_real'][0]);
        $("#cell_cartera_real_1").text("$ "+agent_values['cartera_real'][1]);
        $("#cell_cartera_real_2").text("$ "+agent_values['cartera_real'][2]);
        $("#cell_cartera_real_3").text("$ "+agent_values['cartera_real'][3]);
        $("#cell_cartera_real_4").text("$ "+agent_values['cartera_real'][4]);
        $("#cell_cartera_real_5").text("$ "+agent_values['cartera_real'][5]);

        $("#cell_prima_ubicar_0").text("$ "+agent_values['prima_ubicar'][0]);
        $("#cell_prima_ubicar_1").text("$ "+agent_values['prima_ubicar'][1]);
        $("#cell_prima_ubicar_2").text("$ "+agent_values['prima_ubicar'][2]);
        $("#cell_prima_ubicar_3").text("$ "+agent_values['prima_ubicar'][3]);
        $("#cell_prima_ubicar_4").text("$ "+agent_values['prima_ubicar'][4]);
        $("#cell_prima_ubicar_5").text("$ "+agent_values['prima_ubicar'][5]);

        $("#cell_prima_bonos_0").text("$ "+agent_values['prima_pago'][0]);
        $("#cell_prima_bonos_1").text("$ "+agent_values['prima_pago'][1]);
        $("#cell_prima_bonos_2").text("$ "+agent_values['prima_pago'][2]);
        $("#cell_prima_bonos_3").text("$ "+agent_values['prima_pago'][3]);
        $("#cell_prima_bonos_4").text("$ "+agent_values['prima_pago'][4]);
        $("#cell_prima_bonos_5").text("$ "+agent_values['prima_pago'][5]);

        $("#cell_numero_negocios_0").text(agent_values['numero_negocios'][0]);
        $("#cell_numero_negocios_1").text(agent_values['numero_negocios'][1]);
        $("#cell_numero_negocios_2").text(agent_values['numero_negocios'][2]);
        $("#cell_numero_negocios_3").text(agent_values['numero_negocios'][3]);
        $("#cell_numero_negocios_4").text(agent_values['numero_negocios'][4]);
        $("#cell_numero_negocios_5").text(agent_values['numero_negocios'][5]);

        $("#cell_conservacion_0").text(agent_values['conservacion'][0]+' %');
        $("#cell_conservacion_1").text(agent_values['conservacion'][1]+' %');
        $("#cell_conservacion_2").text(agent_values['conservacion'][2]+' %');
        $("#cell_conservacion_3").text(agent_values['conservacion'][3]+' %');

        /*
        $("#cell_cartera_estimada_0").text("$ "+agent_values['cartera_estimada'][0]);
        $("#cell_cartera_estimada_1").text("$ "+agent_values['cartera_estimada'][1]);
        $("#cell_cartera_estimada_2").text("$ "+agent_values['cartera_estimada'][2]);
        $("#cell_cartera_estimada_3").text("$ "+agent_values['cartera_estimada'][3]);
        */

        $("#cell_comisiones_directas_1").text("$ "+agent_values['comisiones_directas'][0]);
        $("#cell_comisiones_directas_2").text("$ "+agent_values['comisiones_directas'][1]);
        $("#cell_comisiones_directas_3").text("$ "+agent_values['comisiones_directas'][2]);
        $("#cell_comisiones_directas_4").text("$ "+agent_values['comisiones_directas'][3]);
        $("#cell_comisiones_directas_5").text("$ "+agent_values['comisiones_directas'][4]);
        $("#cell_comisiones_directas_6").text("$ "+agent_values['comisiones_directas'][5]);

        $("#cell_perce_bono_primer_anio_1").text(agent_values['perce_bono_primer_anio'][0]+" %");
        $("#cell_perce_bono_primer_anio_2").text(agent_values['perce_bono_primer_anio'][1]+" %");
        $("#cell_perce_bono_primer_anio_3").text(agent_values['perce_bono_primer_anio'][2]+" %");
        $("#cell_perce_bono_primer_anio_4").text(agent_values['perce_bono_primer_anio'][3]+" %");

        $("#cell_bono_primer_anio_1").text("$ "+agent_values['bono_primer_anio'][0]);
        $("#cell_bono_primer_anio_2").text("$ "+agent_values['bono_primer_anio'][1]);
        $("#cell_bono_primer_anio_3").text("$ "+agent_values['bono_primer_anio'][2]);
        $("#cell_bono_primer_anio_4").text("$ "+agent_values['bono_primer_anio'][3]);
        $("#cell_bono_primer_anio_5").text("$ "+agent_values['bono_primer_anio'][4]);
        $("#cell_bono_primer_anio_6").text("$ "+agent_values['bono_primer_anio'][5]);

        $("#cell_faltante_bono_1").text("$ "+agent_values['faltante_bono'][0]);
        $("#cell_faltante_bono_2").text("$ "+agent_values['faltante_bono'][1]);
        $("#cell_faltante_bono_3").text("$ "+agent_values['faltante_bono'][2]);
        $("#cell_faltante_bono_4").text("$ "+agent_values['faltante_bono'][3]);

        $("#cell_comision_cartera_1").text("$ "+agent_values['comision_cartera'][0]);
        $("#cell_comision_cartera_2").text("$ "+agent_values['comision_cartera'][1]);
        $("#cell_comision_cartera_3").text("$ "+agent_values['comision_cartera'][2]);
        $("#cell_comision_cartera_4").text("$ "+agent_values['comision_cartera'][3]);

        $("#cell_perce_bono_cartera_1").text(agent_values['perce_bono_cartera'][0]+" %");
        $("#cell_perce_bono_cartera_2").text(agent_values['perce_bono_cartera'][1]+" %");
        $("#cell_perce_bono_cartera_3").text(agent_values['perce_bono_cartera'][2]+" %");
        $("#cell_perce_bono_cartera_4").text(agent_values['perce_bono_cartera'][3]+" %");

        $("#cell_bono_cartera_1").text("$ "+agent_values['bono_cartera'][0]);
        $("#cell_bono_cartera_2").text("$ "+agent_values['bono_cartera'][1]);
        $("#cell_bono_cartera_3").text("$ "+agent_values['bono_cartera'][2]);
        $("#cell_bono_cartera_4").text("$ "+agent_values['bono_cartera'][3]);
        $("#cell_bono_cartera_5").text("$ "+agent_values['bono_cartera'][4]);
        $("#cell_bono_cartera_6").text("$ "+agent_values['bono_cartera'][5]);

        $("#cell_faltante_bono_cartera_1").text("$ "+agent_values['faltate_bono_cartera'][0]);
        $("#cell_faltante_bono_cartera_2").text("$ "+agent_values['faltate_bono_cartera'][1]);
        $("#cell_faltante_bono_cartera_3").text("$ "+agent_values['faltate_bono_cartera'][2]);
        $("#cell_faltante_bono_cartera_4").text("$ "+agent_values['faltate_bono_cartera'][3]);

        $("#cell_puntos_vida_1").text(agent_values['puntos_vida'][0]);
        $("#cell_puntos_vida_2").text(agent_values['puntos_vida'][1]);
        $("#cell_puntos_vida_3").text(agent_values['puntos_vida'][2]);
        $("#cell_puntos_vida_4").text(agent_values['puntos_vida'][3]);
        $("#cell_puntos_vida_5").text(agent_values['puntos_vida'][4]);

        $("#cell_puntos_gmm_1").text(agent_values['puntos_gmm'][0]);
        $("#cell_puntos_gmm_2").text(agent_values['puntos_gmm'][1]);
        $("#cell_puntos_gmm_3").text(agent_values['puntos_gmm'][2]);
        $("#cell_puntos_gmm_4").text(agent_values['puntos_gmm'][4]);

        $("#cell_puntos_auto_1").text(agent_values['puntos_autos'][0]);
        $("#cell_puntos_auto_2").text(agent_values['puntos_autos'][1]);
        $("#cell_puntos_auto_3").text(agent_values['puntos_autos'][2]);
        $("#cell_puntos_auto_4").text(agent_values['puntos_autos'][4]);

        $("#cell_bono_cartera_no_ganado_1").text("$ "+agent_values['bono_cartera_no_ganado'][0]);
        $("#cell_bono_cartera_no_ganado_2").text("$ "+agent_values['bono_cartera_no_ganado'][1]);
        $("#cell_bono_cartera_no_ganado_3").text("$ "+agent_values['bono_cartera_no_ganado'][2]);
        $("#cell_bono_cartera_no_ganado_4").text("$ "+agent_values['bono_cartera_no_ganado'][3]);
        $("#cell_bono_cartera_no_ganado_5").text("$ "+agent_values['bono_cartera_no_ganado'][4]);
        $("#cell_bono_cartera_no_ganado_6").text("$ "+agent_values['bono_cartera_no_ganado'][5]);

        $("#cell_suma_ingresos_totales_1").text("$ "+agent_values['suma_ingresos_totales'][0]);
        $("#cell_suma_ingresos_totales_2").text("$ "+agent_values['suma_ingresos_totales'][1]);
        $("#cell_suma_ingresos_totales_3").text("$ "+agent_values['suma_ingresos_totales'][2]);
        $("#cell_suma_ingresos_totales_4").text("$ "+agent_values['suma_ingresos_totales'][3]);
        $("#cell_suma_ingresos_totales_5").text("$ "+agent_values['suma_ingresos_totales'][4]);
        $("#cell_suma_ingresos_totales_6").text("$ "+agent_values['suma_ingresos_totales'][5]);

        $("#cell_club_elite").text(agent_values['club_elite']);
        $("#cell_faltante_elite_neg").text(agent_values['faltante_elite_neg']);
        $("#cell_faltante_elite_prod").text("$ "+agent_values['faltante_elite_prod']);
        $("#cell_congreso").text(agent_values['congreso']);
        $("#cell_congreso_siguiente").text(agent_values['congreso_siguiente']);
        $("#cell_faltante_congreso_neg").text(agent_values['faltante_congreso_neg']);
        $("#cell_faltante_congreso_prod").text("$ "+agent_values['faltante_congreso_prod']);
        $("#cell_bono_integral").text("$ "+agent_values['bono_integral']);
        $("#cell_ingresos_totales").text("$ "+agent_values['ingresos_totales']);
        $("#cell_ptos_standing").text(agent_values['puntos_standing']);
        $("#cell_faltante_ptos_standing_pro").text("$ "+agent_values['faltante_ptos_standing_pro']);
        $("#cell_faltante_ptos_standing_neg").text(agent_values['faltante_ptos_standing_neg']);
    });

	function update_data(){
		var cartera_real = [0,0,0,0];
		var primas_pagos = [0,0,0,0];
		var prima_ubicar = [0,0,0,0];
		var num_negocios = [0,0,0,0];
		var conservacion = [0,0,0,0];
		for (var i = 0; i < 4; i++) {
			var text_cartera = $("#cell_cartera_real_" + i).text();
			cartera_real[i] = parseFloat(text_cartera.replace(/[\%$, ]/g, ''));

			var text_primas = $("#cell_prima_bonos_" + i).text();
			primas_pagos[i] = parseFloat(text_primas.replace(/[\%$, ]/g, ''));

			var text_primas_ubi = $("#cell_prima_ubicar_" + i).text();
			prima_ubicar[i] = parseFloat(text_primas_ubi.replace(/[\%$, ]/g, ''));

			var text_negocios = $("#cell_numero_negocios_" + i).text();
			num_negocios[i] = parseFloat(text_negocios.replace(/[\%$, ]/g, ''));

			var text_conser = $("#cell_conservacion_" + i).text();
			conservacion[i] = parseFloat(text_conser.replace(/[\%$, ]/g, ''));
		}
		var generation = agent_values['generation'];
		var data_values = {cartera_real,prima_ubicar,primas_pagos,num_negocios,conservacion,agent_id,generation,year};
		$.ajax({
			url: Config.url + 'termometro/simulate_vida',
			type:'POST',
			data: data_values,
			success:function(result){
				var array = JSON.parse(result);

                $("#cell_cartera_real_0").text("$ "+array['cartera_estimada'][0]);
                $("#cell_cartera_real_1").text("$ "+array['cartera_estimada'][1]);
                $("#cell_cartera_real_2").text("$ "+array['cartera_estimada'][2]);
                $("#cell_cartera_real_3").text("$ "+array['cartera_estimada'][3]);
                $("#cell_cartera_real_4").text("$ "+array['cartera_estimada'][4]);
                $("#cell_cartera_real_5").text("$ "+array['cartera_estimada'][5]);

                $("#cell_prima_ubicar_0").text("$ "+array['prima_ubicar'][0]);
                $("#cell_prima_ubicar_1").text("$ "+array['prima_ubicar'][1]);
                $("#cell_prima_ubicar_2").text("$ "+array['prima_ubicar'][2]);
                $("#cell_prima_ubicar_3").text("$ "+array['prima_ubicar'][3]);
                $("#cell_prima_ubicar_4").text("$ "+array['prima_ubicar'][4]);
                $("#cell_prima_ubicar_5").text("$ "+array['prima_ubicar'][5]);

                $("#cell_prima_bonos_0").text("$ "+array['prima_pago'][0]);
                $("#cell_prima_bonos_1").text("$ "+array['prima_pago'][1]);
                $("#cell_prima_bonos_2").text("$ "+array['prima_pago'][2]);
                $("#cell_prima_bonos_3").text("$ "+array['prima_pago'][3]);
                $("#cell_prima_bonos_4").text("$ "+array['prima_pago'][4]);
                $("#cell_prima_bonos_5").text("$ "+array['prima_pago'][5]);

                $("#cell_numero_negocios_0").text(array['numero_negocios'][0]);
                $("#cell_numero_negocios_1").text(array['numero_negocios'][1]);
                $("#cell_numero_negocios_2").text(array['numero_negocios'][2]);
                $("#cell_numero_negocios_3").text(array['numero_negocios'][3]);
                $("#cell_numero_negocios_4").text(array['numero_negocios'][4]);
                $("#cell_numero_negocios_5").text(array['numero_negocios'][5]);

                $("#cell_conservacion_0").text(array['conservacion'][0]+' %');
                $("#cell_conservacion_1").text(array['conservacion'][1]+' %');
                $("#cell_conservacion_2").text(array['conservacion'][2]+' %');
                $("#cell_conservacion_3").text(array['conservacion'][3]+' %');

				$("#cell_comisiones_directas_1").text("$ "+array['comisiones_directas'][0]);
				$("#cell_comisiones_directas_2").text("$ "+array['comisiones_directas'][1]);
				$("#cell_comisiones_directas_3").text("$ "+array['comisiones_directas'][2]);
				$("#cell_comisiones_directas_4").text("$ "+array['comisiones_directas'][3]);
				$("#cell_comisiones_directas_5").text("$ "+array['comisiones_directas'][4]);
				$("#cell_comisiones_directas_6").text("$ "+array['comisiones_directas'][5]);

				$("#cell_perce_bono_primer_anio_1").text(array['perce_bono_primer_anio'][0]+" %");
				$("#cell_perce_bono_primer_anio_2").text(array['perce_bono_primer_anio'][1]+" %");
				$("#cell_perce_bono_primer_anio_3").text(array['perce_bono_primer_anio'][2]+" %");
				$("#cell_perce_bono_primer_anio_4").text(array['perce_bono_primer_anio'][3]+" %");

				$("#cell_bono_primer_anio_1").text("$ "+array['bono_primer_anio'][0]);
				$("#cell_bono_primer_anio_2").text("$ "+array['bono_primer_anio'][1]);
				$("#cell_bono_primer_anio_3").text("$ "+array['bono_primer_anio'][2]);
				$("#cell_bono_primer_anio_4").text("$ "+array['bono_primer_anio'][3]);
				$("#cell_bono_primer_anio_5").text("$ "+array['bono_primer_anio'][4]);
				$("#cell_bono_primer_anio_6").text("$ "+array['bono_primer_anio'][5]);

				$("#cell_faltante_bono_1").text("$ "+array['faltante_bono'][0]);
				$("#cell_faltante_bono_2").text("$ "+array['faltante_bono'][1]);
				$("#cell_faltante_bono_3").text("$ "+array['faltante_bono'][2]);
				$("#cell_faltante_bono_4").text("$ "+array['faltante_bono'][3]);

				$("#cell_comisiones_cartera_1").text("$ "+array['comision_cartera'][0]);
				$("#cell_comisiones_cartera_2").text("$ "+array['comision_cartera'][1]);
				$("#cell_comisiones_cartera_3").text("$ "+array['comision_cartera'][2]);
				$("#cell_comisiones_cartera_4").text("$ "+array['comision_cartera'][3]);
                $("#cell_comisiones_cartera_5").text("$ "+array['comision_cartera'][4]);
                $("#cell_comisiones_cartera_6").text("$ "+array['comision_cartera'][5]);

				$("#cell_perce_bono_cartera_1").text(array['perce_bono_cartera'][0]+" %");
				$("#cell_perce_bono_cartera_2").text(array['perce_bono_cartera'][1]+" %");
				$("#cell_perce_bono_cartera_3").text(array['perce_bono_cartera'][2]+" %");
				$("#cell_perce_bono_cartera_4").text(array['perce_bono_cartera'][3]+" %");

				$("#cell_bono_cartera_1").text("$ "+array['bono_cartera'][0]);
				$("#cell_bono_cartera_2").text("$ "+array['bono_cartera'][1]);
				$("#cell_bono_cartera_3").text("$ "+array['bono_cartera'][2]);
				$("#cell_bono_cartera_4").text("$ "+array['bono_cartera'][3]);
				$("#cell_bono_cartera_5").text("$ "+array['bono_cartera'][4]);
				$("#cell_bono_cartera_6").text("$ "+array['bono_cartera'][5]);

				$("#cell_faltante_bono_cartera_1").text("$ "+array['faltate_bono_cartera'][0]);
				$("#cell_faltante_bono_cartera_2").text("$ "+array['faltate_bono_cartera'][1]);
				$("#cell_faltante_bono_cartera_3").text("$ "+array['faltate_bono_cartera'][2]);
				$("#cell_faltante_bono_cartera_4").text("$ "+array['faltate_bono_cartera'][3]);

				$("#cell_puntos_vida_1").text(array['puntos_vida'][0]);
				$("#cell_puntos_vida_2").text(array['puntos_vida'][1]);
				$("#cell_puntos_vida_3").text(array['puntos_vida'][2]);
				$("#cell_puntos_vida_4").text(array['puntos_vida'][3]);
				$("#cell_puntos_vida_5").text(array['puntos_vida'][4]);

				$("#cell_puntos_gmm_1").text(array['puntos_gmm'][0]);
				$("#cell_puntos_gmm_2").text(array['puntos_gmm'][1]);
				$("#cell_puntos_gmm_3").text(array['puntos_gmm'][2]);
				$("#cell_puntos_gmm_4").text(array['puntos_gmm'][4]);

				$("#cell_puntos_auto_1").text(array['puntos_autos'][0]);
				$("#cell_puntos_auto_2").text(array['puntos_autos'][1]);
				$("#cell_puntos_auto_3").text(array['puntos_autos'][2]);
				$("#cell_puntos_auto_4").text(array['puntos_autos'][4]);

				$("#cell_bono_cartera_no_ganado_1").text("$ "+array['bono_cartera_no_ganado'][0]);
				$("#cell_bono_cartera_no_ganado_2").text("$ "+array['bono_cartera_no_ganado'][1]);
				$("#cell_bono_cartera_no_ganado_3").text("$ "+array['bono_cartera_no_ganado'][2]);
				$("#cell_bono_cartera_no_ganado_4").text("$ "+array['bono_cartera_no_ganado'][3]);
				$("#cell_bono_cartera_no_ganado_5").text("$ "+array['bono_cartera_no_ganado'][4]);
				$("#cell_bono_cartera_no_ganado_6").text("$ "+array['bono_cartera_no_ganado'][5]);

				$("#cell_suma_ingresos_totales_1").text("$ "+array['suma_ingresos_totales'][0]);
				$("#cell_suma_ingresos_totales_2").text("$ "+array['suma_ingresos_totales'][1]);
				$("#cell_suma_ingresos_totales_3").text("$ "+array['suma_ingresos_totales'][2]);
				$("#cell_suma_ingresos_totales_4").text("$ "+array['suma_ingresos_totales'][3]);
				$("#cell_suma_ingresos_totales_5").text("$ "+array['suma_ingresos_totales'][4]);
				$("#cell_suma_ingresos_totales_6").text("$ "+array['suma_ingresos_totales'][5]);

                /*
				$("#cell_cartera_estimada_0").text("$ "+agent_values['cartera_estimada'][0]);
		        $("#cell_cartera_estimada_1").text("$ "+agent_values['cartera_estimada'][1]);
		        $("#cell_cartera_estimada_2").text("$ "+agent_values['cartera_estimada'][2]);
		        $("#cell_cartera_estimada_3").text("$ "+agent_values['cartera_estimada'][3]);
                */

				$("#cell_club_elite").text(array['club_elite']);
				$("#cell_faltante_elite_neg").text(array['faltante_elite_neg']);
				$("#cell_faltante_elite_prod").text("$ "+array['faltante_elite_prod']);
				$("#cell_congreso").text(array['congreso']);
				$("#cell_congreso_siguiente").text(array['congreso_siguiente']);
				$("#cell_faltante_congreso_neg").text(array['faltante_congreso_neg']);
				$("#cell_faltante_congreso_prod").text("$ "+array['faltante_congreso_prod']);
				$("#cell_bono_integral").text("$ "+array['bono_integral']);
				$("#cell_ingresos_totales").text("$ "+array['ingresos_totales']);
				$("#cell_ptos_standing").text(array['puntos_standing']);
				$("#cell_faltante_ptos_standing_pro").text("$ "+array['faltante_ptos_standing_pro']);
				$("#cell_faltante_ptos_standing_neg").text(array['faltante_ptos_standing_neg']);
				
			},
			error: function(xhr, status, error) {
				console.log(error);
			}
		});
	}
    function enable_number_function_cells(){
        for (var i = 0; i < 4; i++) {
            $("#cell_cartera_real_" + i).click(function(){
                $(this).text("");
            });
            $("#cell_prima_bonos_" + i).click(function(){
                $(this).text("");
            });
            $("#cell_prima_ubicar_" + i).click(function(){
                $(this).text("");
            });
            $("#cell_numero_negocios_" + i).click(function(){
                $(this).text("");
            });
            $("#cell_conservacion_" + i).click(function(){
                $(this).text("");
            });
        }
    }

    $("#simulate_form_export").on('submit', function(event){
        var cartera_real = [0,0,0,0];
        var primas_pagos = [0,0,0,0];
        var prima_ubicar = [0,0,0,0];
        var num_negocios = [0,0,0,0];
        var conservacion = [0,0,0,0];
        for (var i = 0; i < 4; i++) {
            var text_cartera = $("#cell_cartera_real_" + i).text();
            cartera_real[i] = parseFloat(text_cartera.replace(/[\%$, ]/g, ''));

            var text_primas = $("#cell_prima_bonos_" + i).text();
            primas_pagos[i] = parseFloat(text_primas.replace(/[\%$, ]/g, ''));

            var text_primas_ubi = $("#cell_prima_ubicar_" + i).text();
            prima_ubicar[i] = parseFloat(text_primas_ubi.replace(/[\%$, ]/g, ''));

            var text_negocios = $("#cell_numero_negocios_" + i).text();
            num_negocios[i] = parseFloat(text_negocios.replace(/[\%$, ]/g, ''));

            var text_conser = $("#cell_conservacion_" + i).text();
            conservacion[i] = parseFloat(text_conser.replace(/[\%$, ]/g, ''));
        }
        var generation = agent_values['generation'];
        var data_values = {cartera_real,prima_ubicar,primas_pagos,num_negocios,conservacion,agent_id,generation,year};
        var input = $("<input>").attr("type", "hidden").attr("name", "data_test").val(JSON.stringify(data_values));
        $('#simulate_form_export').append(input);
    });
	$(this).keypress(function(e) {
	    if(e.which == 13) {
	    	e.preventDefault();
	    	$("#button_simulation").click();
	    }
	});
});