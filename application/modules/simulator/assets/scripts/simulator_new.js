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

	if(Config.currentRamo==2){
		$('#noNegocios_1').hide();
		$('#span_negocios').hide();
	}

	$( '#considerar-meta').bind( 'click', function(){
		if ( confirm( "¿Esta seguro que desea cambiar los campos 'Primas afectas iniciales' y 'No. de Negocios PAI' ?" ) ) {
			if (updateConsiderarMeta() == 'checked') { // reset with data coming from meta
				$( '.simulator-primas-period').each(function( index ) {
					$(this).val($(this).siblings('.meta-ori').eq(0).text());
				});
				$( '.noNegocios').each(function( index ) {
					$(this).val($(this).siblings('.meta-ori').eq(0).text());
				});
			} else { // reset with blank fields
				$( '.simulator-primas-period').each(function( index ) {
					$(this).val('');
				});
				$( '.noNegocios').each(function( index ) {
					$(this).val('');
				});
			}
			var periodCount = $( '.simulator-primas-period').length;
			for (var i = 1; i <= periodCount; i++) {
				updateLeftCol(i);
//				updateRightCol(i);
			}
			updateBottomRecap();
		}
	});
	$( '#save-simulator' ).bind( 'click', function(){
		$.ajax({
			url:  Config.base_url()+'simulator/save_simulator_new.html',
			type: "POST",
			data: $( '#form' ).serialize(),
			success: function(data){
				if (data != '0') {
					alert( 'La simulacion se ha guardado correctamente.');
					$( '#id' ).val(data);
				} else
					alert( 'Ocurrio un error. Consulte a su administrador.');
			}
		});
	});

	// % Conservacion (vida)
	$( ".conservacion-percent" ).bind( 'change', function(){
		var rank = $(this).attr('id').replace('porcentajeConservacion_', '');
	// Update data related to Prima Venta Inicial:
		updateLeftCol(rank);
	// Update data related to Prima Renovacion:conservacion
		updateRightCol(rank);
	// Update recaps:
		updateBottomRecap();
	});

	// % Siniestralidad (gmm)
	$( ".siniestridad-percent" ).bind( 'change', function(){
		var rank = $(this).attr('id').replace('porsiniestridad_', '');
	// Update data related to Prima Venta Inicial:
		updateLeftCol(rank);
	// Update data related to Prima Renovacion:
		updateRightCol(rank);
	// Update recaps:
		updateBottomRecap();
	});

	$( '.simulator-primas-period').bind( 'keyup', function(){
		var rank = getTrimestreRank($(this).attr('id'));
		if (rank == -1)
			return false;
		updateLeftCol(rank);
		updateRightCol(rank);		
		updateBottomRecap();
	});

	$( '.primasRenovacion' ).bind( 'keyup', function(){
		var rank = $(this).attr('id').replace('primasRenovacion_', '');
		updateRightCol(rank);
		updateBottomRecap();
	});

	$( '.XAcotamiento' ).bind( 'keyup', function(){
		var rank = $(this).attr('id').replace('XAcotamiento_', '');
		updateLeftCol(rank);
		updateRightCol(rank);
		updateBottomRecap();
	});

	$( '.comisionVentaInicial' ).bind( 'keyup', function(){ 
		var rank = $(this).attr('id').replace('comisionVentaInicial_', '');
		updateLeftCol(rank);
		updateBottomRecap();
	});
	$( '.comisionVentaRenovacion' ).bind( 'keyup', function(){
		var rank = $(this).attr('id').replace('comisionVentaRenovacion_', '');
		updateRightCol(rank);
		updateBottomRecap();
	});

	$( '.noNegocios').bind( 'keyup', function(){
		var rank = $(this).attr('id').replace('noNegocios_', '');
		updateLeftCol(rank);
		updateBottomRecap();
	});

	$( '.auto-submit' ).bind( 'change', function(){
		$( "#form").submit();
		return false;
	});

	updateConsiderarMeta() ;
	updateBottomRecap(); //??		
});

	function getTrimestreRank(currentId) {
		var primaFields = [ 'simulatorPrimasPeriod_1', 'simulatorPrimasPeriod_2',
			'simulatorPrimasPeriod_3', 'simulatorPrimasPeriod_4'];
		var result = jQuery.inArray(currentId, primaFields);
		if (result > -1)
			result++;
		return result;
	}

	function getTrimestreId(rank) {
		var primaFields = [ 'simulatorPrimasPeriod_1', 'simulatorPrimasPeriod_2',
			'simulatorPrimasPeriod_3', 'simulatorPrimasPeriod_4'];
		var key = parseInt(rank) - 1;
		if (primaFields[key] !== undefined)
			return primaFields[key];
		else
			return '';		
	}

function CalcPercBonoAplicado(primaAfectadas, negocios, base) {
	var porcentaje = 0;
	if (isNaN(base) || (base == 89))
		return porcentaje;
	if( primaAfectadas >= 830000 ){
		if( negocios >= 3 && negocios < 5 )	porcentaje = 15;
		if( negocios >= 5 && negocios < 7 )	porcentaje = 30;
		if( negocios >= 7 && negocios < 9 )	porcentaje = 35;
		if( negocios >= 9 )	porcentaje = 40;
	}		
	if( primaAfectadas >= 620000 && primaAfectadas < 830000 ){
		if( negocios >= 3 && negocios < 5 )	porcentaje = 13;
		if( negocios >= 5 && negocios < 7 )	porcentaje = 28;
		if( negocios >= 7 && negocios < 9 )	porcentaje = 32.5;
		if( negocios >= 9 )	porcentaje = 36;
	}		
	if( primaAfectadas >= 460000 && primaAfectadas < 620000 ){
		if( negocios >= 3 && negocios < 5 )	porcentaje = 11;
		if( negocios >= 5 && negocios < 7 )	porcentaje = 26;
		if( negocios >= 7 && negocios < 9 )	porcentaje = 30;
		if( negocios >= 9 )	porcentaje = 32.5;
	}
	if( primaAfectadas >= 335000 && primaAfectadas < 460000 ){
		if( negocios >= 3 && negocios < 5 )	porcentaje = 8;
		if( negocios >= 5 && negocios < 7 )	porcentaje = 19;
		if( negocios >= 7 && negocios < 9 )	porcentaje = 22.5;
		if( negocios >= 9 )	porcentaje = 25;
	}
	if( primaAfectadas >= 260000 && primaAfectadas < 335000 ){
		if( negocios >= 3 && negocios < 5 )	porcentaje = 7;
		if( negocios >= 5 && negocios < 7 )	porcentaje = 16;
		if( negocios >= 7 && negocios < 9 )	porcentaje = 20;
		if( negocios >= 9 )	porcentaje = 22.5;
	}
	if( primaAfectadas >= 200000 && primaAfectadas < 260000 ){
		if( negocios >= 3 && negocios < 5 )	porcentaje = 6;
		if( negocios >= 5 && negocios < 7 )	porcentaje = 13;
		if( negocios >= 7 && negocios < 9 )	porcentaje = 17.5;
		if( negocios >= 9 )	porcentaje = 20;
	}
	if( primaAfectadas >= 135000 && primaAfectadas < 200000 ){
		if( negocios >= 3 && negocios < 5 )	porcentaje = 5;
		if( negocios >= 5 && negocios < 7 )	porcentaje = 10;
		if( negocios >= 7 && negocios < 9 )	porcentaje = 15;
		if( negocios >= 9 )	porcentaje = 17.5;
	}
	return porcentaje;
}

function CalcPercConservacion(base, primaAfectadas) {
	var porcentaje = 0;
	if (isNaN(base))
		return porcentaje;
	if( base == 0 ){
		base = 93;
	}
	if( base != 0 ){						
		if( base == 90 ){				
			if( primaAfectadas >= 730000 )
				porcentaje = 9;				
			if( primaAfectadas >= 560000 && primaAfectadas < 730000 )
				porcentaje = 8;				
			if( primaAfectadas >= 400000 && primaAfectadas < 560000 )
				porcentaje = 7;				
			if( primaAfectadas >= 300000 && primaAfectadas < 400000 )
				porcentaje = 5;				
			if( primaAfectadas >= 230000 && primaAfectadas < 300000 )
				porcentaje = 4;	
			if( primaAfectadas >= 170000 && primaAfectadas < 230000 )
				porcentaje = 3;					
			if( primaAfectadas >= 135000 && primaAfectadas < 170000 )
				porcentaje = 2;	
		}			
		if( base == 93 ){				
			if( primaAfectadas >= 730000 )
				porcentaje = 11;				
			if( primaAfectadas >= 560000 && primaAfectadas < 730000 )
				porcentaje = 10;				
			if( primaAfectadas >= 400000 && primaAfectadas < 560000 )
				porcentaje = 9;				
			if( primaAfectadas >= 300000 && primaAfectadas < 400000 )
				porcentaje = 6;				
			if( primaAfectadas >= 230000 && primaAfectadas < 300000 )
				porcentaje = 5;	
			if( primaAfectadas >= 170000 && primaAfectadas < 230000 )
				porcentaje = 4;	
			if( primaAfectadas >= 135000 && primaAfectadas < 170000 )
				porcentaje = 3;	
		}			
		if( base == 95 ){				
			if( primaAfectadas >= 730000 )
				porcentaje = 12;				
			if( primaAfectadas >= 560000 && primaAfectadas < 730000 )
				porcentaje = 11;				
			if( primaAfectadas >= 400000 && primaAfectadas < 560000 )
				porcentaje = 10;				
			if( primaAfectadas >= 300000 && primaAfectadas < 400000 )
				porcentaje = 7;				
			if( primaAfectadas >= 230000 && primaAfectadas < 300000 )
				porcentaje = 6;	
			if( primaAfectadas >= 170000 && primaAfectadas < 230000 )
				porcentaje = 5;
			if( primaAfectadas >= 135000 && primaAfectadas < 170000 )
				porcentaje = 4;
		}
	}							
	return porcentaje;
}

// Updates data related to the Primas venta Inicial:
function updateLeftCol(rank)
{
	var ventaInicialPrimaId = getTrimestreId(rank);
	if (ventaInicialPrimaId.length == 0)
		return;
	var primaAfectadas = parseFloat( $( '#' + ventaInicialPrimaId ).val() ); // prima venta inicial
	if( isNaN( primaAfectadas ) )
		primaAfectadas = 0;
/*	var primaPromedio = parseFloat( $( '#primas_promedio' ).val() );
	var negocios = (primaPromedio != 0) ? (primaAfectadas / primaPromedio) : 0;
	$( '#noNegocios_' + rank ).val( Math.ceil(negocios) );*/
	var negocios = parseInt( $( '#noNegocios_' + rank ).val() );
	var base = parseInt( $( '#porcentajeConservacion_' + rank ).val() );
	console.log(Config.currentRamo);
	if (Config.currentRamo == 1)
		var porcentaje = CalcPercBonoAplicado(primaAfectadas, negocios, base);
	else
		var porcentaje = getInicialGmmPercent(primaAfectadas, negocios);	
//	$( '#bonoAplicado_' + rank ).val( porcentaje );
	$( '#bonoAplicado_' + rank ).text( porcentaje );
	var totalP = primaAfectadas * parseFloat($( '#XAcotamiento_' + rank ).val().replace( '%', '' )/100);
	$( '#primasAfectasInicialesPagar_text_'  + rank).html( '$ '+moneyFormat(totalP) );
	$( '#primasAfectasInicialesPagar_'  + rank ).val( total );
	var total = primaAfectadas * parseFloat($( '#comisionVentaInicial_'  + rank ).val().replace( '%', '' )/100);
	$( '#ingresoComisionesVentaInicial_text_'  + rank ).html( '$ '+moneyFormat(total) );
	$( '#ingresoComisionesVentaInicial_'  + rank ).val( total );

	total = totalP * porcentaje / 100;
	$( '#ingresoBonoProductividad_text_'  + rank ).html( '$ '+moneyFormat(total) );
	$( '#ingresoBonoProductividad_'  + rank ).val( total );
}

// Updates data related to the Primas Renovacion:
function updateRightCol(rank)
{
	var ventaInicialPrimaId = getTrimestreId(rank);
	if (ventaInicialPrimaId.length == 0)
		return;

	var primasRenovacion = parseFloat($( '#primasRenovacion_' + rank ).val());
	if( isNaN( primasRenovacion ) )
		primasRenovacion = 0;
	var totalP = primasRenovacion * parseFloat($( '#XAcotamiento_' + rank ).val().replace( '%', '' )/100);	
	$( '#primasRenovacionPagar_text_' + rank ).html( '$ '+moneyFormat(totalP) );
	$( '#primasRenovacionPagar_' + rank ).val( totalP );

	var primaAfectadas = parseFloat( $( '#' + ventaInicialPrimaId ).val() );
	if( isNaN( primaAfectadas ) )
		primaAfectadas = 0;
		
	if (Config.currentRamo == 1) {
		var base = parseInt( $( '#porcentajeConservacion_' + rank ).val() );
		var porcentaje = CalcPercConservacion(base, primaAfectadas);
	} else {
		var base = parseInt( $( '#porsiniestridad_' + rank ).val() );
		var porcentaje = getRenovacionGmmPercent(primaAfectadas, base);
	}
//	$( '#porbonoGanado_' + rank ).val( porcentaje );
	$( '#porbonoGanado_' + rank ).text( porcentaje );
	var totalB = totalP * porcentaje/100;
	$( '#ingresoBonoRenovacion_text_' + rank ).html( '$ '+moneyFormat(totalB) );
	$( '#ingresoBonoRenovacion_' + rank ).val( totalB );

	var total = primasRenovacion * parseFloat($( '#comisionVentaRenovacion_' + rank ).val().replace( '%', '' )/100);
	$( '#ingresoComisionRenovacion_text_' + rank ).html( '$ '+moneyFormat(total) );
	$( '#ingresoComisionRenovacion_' + rank ).val( total );		
}
// Updates bottom line and year recap
function updateBottomRecap() {

	var periodCount = $( '.simulator-primas-period').length;
	var ingresoComisionesVentaInicial = [0, 0, 0, 0, 0];
	var ingresoComisionesRenovacion = [0, 0, 0, 0, 0];
	var ingresoBonosVentaInicial = [0, 0, 0, 0, 0];
	var ingresoBonosRenovacion = [0, 0, 0, 0, 0];
	var ingresoTotal = [0, 0, 0, 0, 0];

	for(  var i = 1; i <= periodCount; i++ ){	
		ingresoComisionesVentaInicial[i] = parseFloat($( '#ingresoComisionesVentaInicial_' + i ).val());
		ingresoComisionesVentaInicial[0] += ingresoComisionesVentaInicial[i];
		ingresoComisionesRenovacion[i] = parseFloat($( '#ingresoComisionRenovacion_' + i ).val());
		ingresoComisionesRenovacion[0] += ingresoComisionesRenovacion[i];
		
		ingresoBonosVentaInicial[i] = parseFloat($( '#ingresoBonoProductividad_' + i ).val());
		ingresoBonosVentaInicial[0] += ingresoBonosVentaInicial[i];
		ingresoBonosRenovacion[i] = parseFloat($( '#ingresoBonoRenovacion_' + i ).val());
		ingresoBonosRenovacion[0] += ingresoBonosRenovacion[i];
	
		$( '#inicial-comm-recap-' + i ).text(moneyFormat(ingresoComisionesVentaInicial[i]));
		$( '#inicial-bono-recap-' + i ).text(moneyFormat(ingresoBonosVentaInicial[i]));

		$( '#renovacion-comm-recap-' + i ).text(moneyFormat(ingresoComisionesRenovacion[i]));
		$( '#renovacion-bono-recap-' + i ).text(moneyFormat(ingresoBonosRenovacion[i]));

		ingresoTotal[i] = ingresoComisionesVentaInicial[i] + ingresoComisionesRenovacion[i] 
			+ ingresoBonosVentaInicial[i] + ingresoBonosRenovacion[i];
		$( '#bottom_' + i ).text(moneyFormat(ingresoTotal[i]));

		ingresoTotal[0] += ingresoTotal[i];
	}
	$( '#inicial-comm-recap' ).text(moneyFormat(ingresoComisionesVentaInicial[0]));
	$( '#inicial-bono-recap' ).text(moneyFormat(ingresoBonosVentaInicial[0]));
	$( '#renovacion-comm-recap').text(moneyFormat(ingresoComisionesRenovacion[0]));
	$( '#renovacion-bono-recap' ).text(moneyFormat(ingresoBonosRenovacion[0]));
	$( '#year-bottom' ).text(moneyFormat(ingresoTotal[0]));

	var recap = 0;
	$( '.XAcotamiento' ).each(function( index ) {
		recap += parseFloat($(this).val());
	});
	$( '#XAcotamiento_recap').text(parseInt(recap / periodCount));

	recap = 0;
	$( '.simulator-primas-period' ).each(function( index ) {
		recap += parseFloat($(this).val());
	});
	$( '#simulator-primas-period-recap').text(moneyFormat(recap));

	if (Config.currentRamo == 1) {
		recap = 0;
		$( '.noNegocios' ).each(function( index ) {
			recap += parseFloat($(this).val());
		});
		$( '#noNegocios_recap').text(parseInt(recap));	
	}

	recap = 0;
	$( '.primasRenovacion' ).each(function( index ) {
		recap += parseFloat($(this).val());
	});
	$( '#primasRenovacion_recap').text(moneyFormat(recap));
}
// Updates fields that may come from meta
function updateConsiderarMeta() {
	if ($( '#considerar-meta').prop('checked')) {
		$( '.XAcotamiento' ).prop('readonly', false);
		$( '.primasRenovacion' ).prop('readonly', false);
		$( '.conservacion-percent' ).prop('readonly', false);
		$( '.comisionVentaInicial' ).prop('readonly', false);
		$( '.comisionVentaRenovacion' ).prop('readonly', false);
		$( '.simulator-primas-period' ).prop('readonly', true);
		$( '.noNegocios' ).prop('readonly', true);
		return 'checked';
	} else {
		$( '.XAcotamiento' ).prop('readonly', false);
		$( '.primasRenovacion' ).prop('readonly', false);
		$( '.conservacion-percent' ).prop('readonly', false);
		$( '.comisionVentaInicial' ).prop('readonly', false);
		$( '.comisionVentaRenovacion' ).prop('readonly', false);
		$( '.simulator-primas-period' ).prop('readonly', false);
		$( '.noNegocios' ).prop('readonly', false);
		return 'unchecked';
	}
}

	function getInicialGmmPercent(prima, negocios) {
// 2017: takes only in account the 'Requisitos de Nuevos Asegurados' equal to 8
		var porcentaje = 0;
		switch (true)
		{
			case (prima >= 600000 ):
				porcentaje = 15;
				break;
			
			case ((prima >= 450000) && (prima < 600000) ):
				porcentaje = 12;
				break;
			
			case ((prima >= 320000) && (prima < 450000) ):
				porcentaje = 9.5;
				break;
			
			case ((prima >= 220000) && (prima < 320000) ):
				porcentaje = 7.5;
				break;
			
			case ((prima >= 135000) && (prima < 220000) ):
				porcentaje = 6;
				break;
			
			case (prima < 135000):
				porcentaje = 0;
				break;
			default:
				break;
		}
		return porcentaje;
	}

	function getRenovacionGmmPercent(prima, sinistrad) {
		porcentaje = 0;
		switch (true)
		{
			case ((sinistrad == 66) && (prima >= 690000)):
				porcentaje = 3;
				break;
			case ((sinistrad == 62) && (prima >= 690000)):
				porcentaje = 5;
				break;
			case ((sinistrad == 58) && (prima >= 690000)):
				porcentaje = 8;
				break;				
/////////////
			case ((sinistrad == 66) && (prima >= 520000) && (prima < 690000)):
				porcentaje = 2;
				break;
			case ((sinistrad == 62) && (prima >= 520000) && (prima < 690000)):
				porcentaje = 4;
				break;
			case ((sinistrad == 58) && (prima >= 520000) && (prima < 690000)):
				porcentaje = 6;
				break;					
/////////////
			case ((sinistrad == 66) && (prima >= 380000) && (prima < 520000)):
				porcentaje = 1.5;
				break;
			case ((sinistrad == 62) && (prima >= 380000) && (prima < 520000)):
				porcentaje = 3;
				break;
			case ((sinistrad == 58) && (prima >= 380000) && (prima < 520000)):
				porcentaje = 4.5;
				break;	
/////////////
			case ((sinistrad == 66) && (prima >= 270000) && (prima < 380000)):
				porcentaje = 1;
				break;
			case ((sinistrad == 62) && (prima >= 270000) && (prima < 380000)):
				porcentaje = 2;
				break;
			case ((sinistrad == 58) && (prima >= 270000) && (prima < 380000)):
				porcentaje = 3;
				break;
/////////////
			case ((sinistrad == 66) && (prima >= 180000) && (prima < 270000)):
				porcentaje = 0.5;
				break;
			case ((sinistrad == 62) && (prima >= 180000) && (prima < 270000)):
				porcentaje = 1;
				break;
			case ((sinistrad == 58) && (prima >= 180000) && (prima < 270000)):
				porcentaje = 2;
				break;
/////////////

			default:
				break;
		}
		return porcentaje;
	}