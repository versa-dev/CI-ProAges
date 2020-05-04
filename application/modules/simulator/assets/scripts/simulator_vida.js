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

	$( '#primas_promedio' ).bind( 'keyup', function(){ 		
		var negocios = parseFloat( $( '#primasAfectasInicialesUbicar' ).val() ) / parseFloat( $( '#primas_promedio' ).val() );		
		var negocios_1 = parseFloat( $( '#simulatorprimasprimertrimestre' ).val() ) / parseFloat( $( '#primas_promedio' ).val() );		
		var negocios_2 = parseFloat( $( '#simulatorprimassegundotrimestre' ).val() ) / parseFloat( $( '#primas_promedio' ).val() );		
		var negocios_3 = parseFloat( $( '#simulatorprimastercertrimestre' ).val() ) / parseFloat( $( '#primas_promedio' ).val() );		
		var negocios_4 = parseFloat( $( '#simulatorprimascuartotrimestre' ).val() ) / parseFloat( $( '#primas_promedio' ).val() );		
		if( isNaN( negocios ) ) negocios = 0;		
		if( isNaN( negocios_1 ) ) negocios_1 = 0;		
		if( isNaN( negocios_2 ) ) negocios_2 = 0;		
		if( isNaN( negocios_3 ) ) negocios_3 = 0;		
		if( isNaN( negocios_4 ) ) negocios_4 = 0;		
		$( '#noNegocios' ).val( Math.ceil(negocios) );
		$( '#noNegocios_1' ).val( Math.ceil(negocios_1) );
		$( '#noNegocios_2' ).val( Math.ceil(negocios_2) );
		$( '#noNegocios_3' ).val( Math.ceil(negocios_3) );
		$( '#noNegocios_4' ).val( Math.ceil(negocios_4) );
		$( '#metas-prima-promedio' ).val(this.value);
		updatePrimasMes(); vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});	
	// Prima Promedio Meas
	$( "#metas-prima-promedio" ).bind( 'keyup', function(){ 		
		//$( '#primas_promedio' ).val(Math.ceil(this.value));	
		updatePrimasMes(); vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});
	// % Bono Renovacion
	$( "#porcentajeConservacion_1" ).bind( 'change', function(){ 	
		var primaAfectadas = parseFloat( $( '#primasRenovacionPagar_1' ).val() );			
		var	base = parseInt( $( "#porcentajeConservacion_1" ).val() );			
		var porcentaje = CalcPercConservacion(base,primaAfectadas);
		$( '#porbonoGanado_1' ).val( porcentaje );		
		var total = parseFloat( $( '#primasRenovacion_1' ).val() ) * parseFloat($( '#porbonoGanado_1' ).val()/100);	
		$( '#ingresoBonoRenovacion_text_1' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoBonoRenovacion_1' ).val( total );		
		updatePrimasMes(); vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});
	$( "#porcentajeConservacion_2" ).bind( 'change', function(){ 	
		var primaAfectadas = parseFloat( $( '#primasRenovacionPagar_2' ).val() );			
		var	base = parseInt( $( "#porcentajeConservacion_2" ).val() );			
		var porcentaje = CalcPercConservacion(base,primaAfectadas);
		$( '#porbonoGanado_2' ).val( porcentaje );		
		var total = parseFloat( $( '#primasRenovacion_2' ).val() ) * parseFloat($( '#porbonoGanado_2' ).val()/100);	
		$( '#ingresoBonoRenovacion_text_2' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoBonoRenovacion_2' ).val( total );		
		updatePrimasMes(); vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});
	$( "#porcentajeConservacion_3" ).bind( 'change', function(){ 	
		var primaAfectadas = parseFloat( $( '#primasRenovacionPagar_3' ).val() );			
		var	base = parseInt( $( "#porcentajeConservacion_3" ).val() );			
		var porcentaje = CalcPercConservacion(base,primaAfectadas);
		$( '#porbonoGanado_3' ).val( porcentaje );		
		var total = parseFloat( $( '#primasRenovacion_3' ).val() ) * parseFloat($( '#porbonoGanado_3' ).val()/100);	
		$( '#ingresoBonoRenovacion_text_3' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoBonoRenovacion_3' ).val( total );		
		updatePrimasMes(); vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});
	$( "#porcentajeConservacion_4" ).bind( 'change', function(){ 	
		var primaAfectadas = parseFloat( $( '#primasRenovacionPagar_4' ).val() );			
		var	base = parseInt( $( "#porcentajeConservacion_4" ).val() );			
		var porcentaje = CalcPercConservacion(base,primaAfectadas);
		$( '#porbonoGanado_4' ).val( porcentaje );		
		var total = parseFloat( $( '#primasRenovacion_4' ).val() ) * parseFloat($( '#porbonoGanado_4' ).val()/100);	
		$( '#ingresoBonoRenovacion_text_4' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoBonoRenovacion_4' ).val( total );		
		updatePrimasMes(); vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});
	$( '#primasAfectasInicialesUbicar' ).bind( 'keyup', function(){				
		//PRIMER TRIMESTRE
		var primaAfectadas_1 = parseFloat( $( '#simulatorprimasprimertrimestre' ).val() );			
		var	negocios_1 = primaAfectadas_1 / parseFloat( $( '#primas_promedio' ).val() );		
		var porcentaje_1 = CalcPercBonoAplicado(primaAfectadas_1,negocios_1);
		$( '#bonoAplicado_1' ).val( porcentaje_1 );
		var total = parseFloat( $( '#simulatorprimasprimertrimestre' ).val() ) * parseFloat($( '#porAcotamiento' ).val().replace( '%', '' )/100);	
		$( '#primasAfectasInicialesPagar_text_1' ).html( '$ '+moneyFormat(total) );
		$( '#primasAfectasInicialesPagar_1' ).val( total );		
		var total = parseFloat( $( '#simulatorprimasprimertrimestre' ).val() ) * parseFloat($( '#comisionVentaInicial_1' ).val().replace( '%', '' )/100);	
		$( '#ingresoComisionesVentaInicial_text_1' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoComisionesVentaInicial_1' ).val( total );		
		var total = parseFloat( $( '#primasAfectasInicialesPagar_1' ).val() ) * parseFloat($( '#bonoAplicado_1' ).val().replace( '%', '' )/100);	
		$( '#ingresoBonoProductividad_text_1' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoBonoProductividad_1' ).val( total );		

		//SEGUNDO TRIMESTRE
		var primaAfectadas_2 = parseFloat( $( '#simulatorprimassegundotrimestre' ).val() );			
		var	negocios_2 = primaAfectadas_2 / parseFloat( $( '#primas_promedio' ).val() );		
		var porcentaje_2 = CalcPercBonoAplicado(primaAfectadas_2,negocios_2);
		$( '#bonoAplicado_2' ).val( porcentaje_2 );	
		var total = parseFloat( $( '#simulatorprimassegundotrimestre' ).val() ) * parseFloat($( '#porAcotamiento' ).val().replace( '%', '' )/100);	
		$( '#primasAfectasInicialesPagar_text_2' ).html( '$ '+moneyFormat(total) );
		$( '#primasAfectasInicialesPagar_2' ).val( total );		
		var total = parseFloat( $( '#simulatorprimassegundotrimestre' ).val() ) * parseFloat($( '#comisionVentaInicial_2' ).val().replace( '%', '' )/100);	
		$( '#ingresoComisionesVentaInicial_text_2' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoComisionesVentaInicial_2' ).val( total );		
		var total = parseFloat( $( '#primasAfectasInicialesPagar_2' ).val() ) * parseFloat($( '#bonoAplicado_2' ).val().replace( '%', '' )/100);	
		$( '#ingresoBonoProductividad_text_2' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoBonoProductividad_2' ).val( total );		

		//TERCER TRIMESTRE
		var primaAfectadas_3 = parseFloat( $( '#simulatorprimastercertrimestre' ).val() );			
		var	negocios_3 = primaAfectadas_3 / parseFloat( $( '#primas_promedio' ).val() );		
		var porcentaje_3 = CalcPercBonoAplicado(primaAfectadas_3,negocios_3);
		$( '#bonoAplicado_3' ).val( porcentaje_3 );	
		var total = parseFloat( $( '#simulatorprimastercertrimestre' ).val() ) * parseFloat($( '#porAcotamiento' ).val().replace( '%', '' )/100);	
		$( '#primasAfectasInicialesPagar_text_3' ).html( '$ '+moneyFormat(total) );
		$( '#primasAfectasInicialesPagar_3' ).val( total );		
		var total = parseFloat( $( '#simulatorprimastercertrimestre' ).val() ) * parseFloat($( '#comisionVentaInicial_3' ).val().replace( '%', '' )/100);	
		$( '#ingresoComisionesVentaInicial_text_3' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoComisionesVentaInicial_3' ).val( total );
		var total = parseFloat( $( '#primasAfectasInicialesPagar_3' ).val() ) * parseFloat($( '#bonoAplicado_3' ).val().replace( '%', '' )/100);	
		$( '#ingresoBonoProductividad_text_3' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoBonoProductividad_3' ).val( total );		

		//CUARTO TRIMESTRE
		var primaAfectadas_4 = parseFloat( $( '#simulatorprimascuartotrimestre' ).val() );			
		var	negocios_4 = primaAfectadas_4 / parseFloat( $( '#primas_promedio' ).val() );		
		var porcentaje_4 = CalcPercBonoAplicado(primaAfectadas_4,negocios_4);
		$( '#bonoAplicado_4' ).val( porcentaje_4 );	
		var total = parseFloat( $( '#simulatorprimascuartotrimestre' ).val() ) * parseFloat($( '#porAcotamiento' ).val().replace( '%', '' )/100);	
		$( '#primasAfectasInicialesPagar_text_4' ).html( '$ '+moneyFormat(total) );
		$( '#primasAfectasInicialesPagar_4' ).val( total );		
		var total = parseFloat( $( '#simulatorprimascuartotrimestre' ).val() ) * parseFloat($( '#comisionVentaInicial_4' ).val().replace( '%', '' )/100);	
		$( '#ingresoComisionesVentaInicial_text_4' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoComisionesVentaInicial_4' ).val( total );		
		var total = parseFloat( $( '#primasAfectasInicialesPagar_4' ).val() ) * parseFloat($( '#bonoAplicado_4' ).val().replace( '%', '' )/100);	
		$( '#ingresoBonoProductividad_text_4' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoBonoProductividad_4' ).val( total );		

		// Primas Promedio
		var noNegocios = parseFloat( $( '#primasAfectasInicialesUbicar' ).val() ) / parseFloat( $( '#primas_promedio' ).val() );		
		var noNegocios_1 = parseFloat( $( '#simulatorprimasprimertrimestre' ).val() ) / parseFloat( $( '#primas_promedio' ).val() );		
		var noNegocios_2 = parseFloat( $( '#simulatorprimassegundotrimestre' ).val() ) / parseFloat( $( '#primas_promedio' ).val() );
		var noNegocios_3 = parseFloat( $( '#simulatorprimastercertrimestre' ).val() ) / parseFloat( $( '#primas_promedio' ).val() );		
		var noNegocios_4 = parseFloat( $( '#simulatorprimascuartotrimestre' ).val() ) / parseFloat( $( '#primas_promedio' ).val() );		
		if( isNaN( noNegocios ) ) noNegocios = 0;		
		if( isNaN( noNegocios_1 ) ) noNegocios_1 = 0;		
		if( isNaN( noNegocios_2 ) ) noNegocios_2 = 0;		
		if( isNaN( noNegocios_3 ) ) noNegocios_3 = 0;		
		if( isNaN( noNegocios_4 ) ) noNegocios_4 = 0;		
		$( '#noNegocios' ).val( Math.ceil(noNegocios) )			
		$( '#noNegocios_1' ).val( Math.ceil(noNegocios_1) )			
		$( '#noNegocios_2' ).val( Math.ceil(noNegocios_2) )			
		$( '#noNegocios_3' ).val( Math.ceil(noNegocios_3) )			
		$( '#noNegocios_4' ).val( Math.ceil(noNegocios_4) )			
		$( '#prima-total-anual' ).val(Math.ceil(this.value));	
		updateIngreso(); updatePrimasMes(); vida_ingresoTotal(); vida_ingresoPromedio(); getMetas(); 

	});
	$( '#porAcotamiento' ).bind( 'keyup', function(){
		//PRIMER TRIMESTRE
		var total = parseFloat( $( '#simulatorprimasprimertrimestre' ).val() ) * parseFloat($( '#porAcotamiento' ).val().replace( '%', '' )/100);	
		$( '#primasAfectasInicialesPagar_text_1' ).html( '$ '+moneyFormat(total) );
		$( '#primasAfectasInicialesPagar_1' ).val( total );

		//SEGUNDO TRIMESTRE
		var total = parseFloat( $( '#simulatorprimassegundotrimestre' ).val() ) * parseFloat($( '#porAcotamiento' ).val().replace( '%', '' )/100);	
		$( '#primasAfectasInicialesPagar_text_2' ).html( '$ '+moneyFormat(total) );
		$( '#primasAfectasInicialesPagar_2' ).val( total );

		//TERCER TRIMESTRE
		var total = parseFloat( $( '#simulatorprimastercertrimestre' ).val() ) * parseFloat($( '#porAcotamiento' ).val().replace( '%', '' )/100);	
		$( '#primasAfectasInicialesPagar_text_3' ).html( '$ '+moneyFormat(total) );
		$( '#primasAfectasInicialesPagar_3' ).val( total );

		//CUARTO TRIMESTRE
		var total = parseFloat( $( '#simulatorprimascuartotrimestre' ).val() ) * parseFloat($( '#porAcotamiento' ).val().replace( '%', '' )/100);	
		$( '#primasAfectasInicialesPagar_text_4' ).html( '$ '+moneyFormat(total) );
		$( '#primasAfectasInicialesPagar_4' ).val( total );

		vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});	
	$( '#primasRenovacion_1' ).bind( 'keyup', function(){ 
		var total = parseFloat( $( '#primasRenovacion_1' ).val() ) * parseFloat($( '#XAcotamiento_1' ).val().replace( '%', '' )/100);	
		$( '#primasRenovacionPagar_text_1' ).html( '$ '+moneyFormat(total) );
		$( '#primasRenovacionPagar_1' ).val( total );		
		var total = parseFloat( $( '#primasRenovacionPagar_1' ).val() ) * parseFloat($( '#porbonoGanado_1' ).val().replace( '%', '' )/100);	
		$( '#ingresoBonoRenovacion_text_1' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoBonoRenovacion_1' ).val( total );		
		var total = parseFloat( $( '#primasRenovacion_1' ).val() ) * parseFloat($( '#comisionVentaRenovacion_1' ).val().replace( '%', '' )/100);	
		$( '#ingresoComisionRenovacion_text_1' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoComisionRenovacion_1' ).val( total );		
		vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});
	$( '#primasRenovacion_2' ).bind( 'keyup', function(){ 
		var total = parseFloat( $( '#primasRenovacion_2' ).val() ) * parseFloat($( '#XAcotamiento_2' ).val().replace( '%', '' )/100);	
		$( '#primasRenovacionPagar_text_2' ).html( '$ '+moneyFormat(total) );
		$( '#primasRenovacionPagar_2' ).val( total );		
		var total = parseFloat( $( '#primasRenovacionPagar_2' ).val() ) * parseFloat($( '#porbonoGanado_2' ).val().replace( '%', '' )/100);	
		$( '#ingresoBonoRenovacion_text_2' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoBonoRenovacion_2' ).val( total );		
		var total = parseFloat( $( '#primasRenovacion_2' ).val() ) * parseFloat($( '#comisionVentaRenovacion_2' ).val().replace( '%', '' )/100);	
		$( '#ingresoComisionRenovacion_text_2' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoComisionRenovacion_2' ).val( total );		
		vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});
	$( '#primasRenovacion_3' ).bind( 'keyup', function(){ 
		var total = parseFloat( $( '#primasRenovacion_3' ).val() ) * parseFloat($( '#XAcotamiento_3' ).val().replace( '%', '' )/100);	
		$( '#primasRenovacionPagar_text_3' ).html( '$ '+moneyFormat(total) );
		$( '#primasRenovacionPagar_3' ).val( total );		
		var total = parseFloat( $( '#primasRenovacionPagar_3' ).val() ) * parseFloat($( '#porbonoGanado_3' ).val().replace( '%', '' )/100);	
		$( '#ingresoBonoRenovacion_text_3' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoBonoRenovacion_3' ).val( total );		
		var total = parseFloat( $( '#primasRenovacion_3' ).val() ) * parseFloat($( '#comisionVentaRenovacion_3' ).val().replace( '%', '' )/100);	
		$( '#ingresoComisionRenovacion_text_3' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoComisionRenovacion_3' ).val( total );		
		vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});
	$( '#primasRenovacion_4' ).bind( 'keyup', function(){ 
		var total = parseFloat( $( '#primasRenovacion_4' ).val() ) * parseFloat($( '#XAcotamiento_4' ).val().replace( '%', '' )/100);	
		$( '#primasRenovacionPagar_text_4' ).html( '$ '+moneyFormat(total) );
		$( '#primasRenovacionPagar_4' ).val( total );		
		var total = parseFloat( $( '#primasRenovacionPagar_4' ).val() ) * parseFloat($( '#porbonoGanado_4' ).val().replace( '%', '' )/100);	
		$( '#ingresoBonoRenovacion_text_4' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoBonoRenovacion_4' ).val( total );		
		var total = parseFloat( $( '#primasRenovacion_4' ).val() ) * parseFloat($( '#comisionVentaRenovacion_4' ).val().replace( '%', '' )/100);	
		$( '#ingresoComisionRenovacion_text_4' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoComisionRenovacion_4' ).val( total );		
		vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});
	
	//PRIMER TRIMESTRE
	$( '#XAcotamiento_1' ).bind( 'keyup', function(){ 
		var total = parseFloat( $( '#primasRenovacion_1' ).val() ) * parseFloat($( '#XAcotamiento_1' ).val().replace( '%', '' )/100);	
		$( '#primasRenovacionPagar_text_1' ).html( '$ '+moneyFormat(total) );
		$( '#primasRenovacionPagar_1' ).val( total );		
		vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});
	$( '#comisionVentaInicial_1' ).bind( 'keyup', function(){ 
		updateIngreso(); vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});
	$( '#comisionVentaRenovacion_1' ).bind( 'keyup', function(){
		updateIngreso(); vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});
	
	//SEGUNDO TRIMESTRE
	$( '#XAcotamiento_2' ).bind( 'keyup', function(){ 
		var total = parseFloat( $( '#primasRenovacion_2' ).val() ) * parseFloat($( '#XAcotamiento_2' ).val().replace( '%', '' )/100);	
		$( '#primasRenovacionPagar_text_2' ).html( '$ '+moneyFormat(total) );
		$( '#primasRenovacionPagar_2' ).val( total );		
		vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});
	$( '#comisionVentaInicial_2' ).bind( 'keyup', function(){ 
		updateIngreso(); vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});
	$( '#comisionVentaRenovacion_2' ).bind( 'keyup', function(){
		updateIngreso(); vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});

	//TERCER TRIMESTRE
	$( '#XAcotamiento_3' ).bind( 'keyup', function(){ 
		var total = parseFloat( $( '#primasRenovacion_3' ).val() ) * parseFloat($( '#XAcotamiento_3' ).val().replace( '%', '' )/100);	
		$( '#primasRenovacionPagar_text_3' ).html( '$ '+moneyFormat(total) );
		$( '#primasRenovacionPagar_3' ).val( total );		
		vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});
	$( '#comisionVentaInicial_3' ).bind( 'keyup', function(){ 
		updateIngreso(); vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});
	$( '#comisionVentaRenovacion_3' ).bind( 'keyup', function(){
		updateIngreso(); vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});

	//CUARTO TRIMESTRE
	$( '#XAcotamiento_4' ).bind( 'keyup', function(){ 
		var total = parseFloat( $( '#primasRenovacion_4' ).val() ) * parseFloat($( '#XAcotamiento_4' ).val().replace( '%', '' )/100);	
		$( '#primasRenovacionPagar_text_4' ).html( '$ '+moneyFormat(total) );
		$( '#primasRenovacionPagar_4' ).val( total );		
		vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});
	$( '#comisionVentaInicial_4' ).bind( 'keyup', function(){ 
		updateIngreso(); vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});
	$( '#comisionVentaRenovacion_4' ).bind( 'keyup', function(){
		updateIngreso(); vida_ingresoTotal(); vida_ingresoPromedio(); getMetas();
	});

	vida_ingresoTotal(); vida_ingresoPromedio(); 
		
});

function updateIngreso() {	
	//PRIMER TRIMESTRE
	//alert($( '#primasAfectasInicialesPagar_1' ).val());
	var primaAfectadas_1 = parseFloat( $( '#simulatorprimasprimertrimestre' ).val() );			
	var	negocios_1 = primaAfectadas_1 / parseFloat( $( '#primas_promedio' ).val() );		
	var porcentaje_1 = CalcPercBonoAplicado(primaAfectadas_1,negocios_1);
	$( '#bonoAplicado_1' ).val( porcentaje_1 );
	var total = parseFloat( $( '#simulatorprimasprimertrimestre' ).val() ) * parseFloat($( '#comisionVentaInicial_1' ).val().replace( '%', '' )/100);	
	$( '#ingresoComisionesVentaInicial_text_1' ).html( '$ '+moneyFormat(total) );
	$( '#ingresoComisionesVentaInicial_1' ).val( total );		
	var total = parseFloat( $( '#primasAfectasInicialesPagar_1' ).val() ) * parseFloat($( '#bonoAplicado_1' ).val().replace( '%', '' )/100);	
	$( '#ingresoBonoProductividad_text_1' ).html( '$ '+moneyFormat(total) );
	$( '#ingresoBonoProductividad_1' ).val( total );
	var total = parseFloat( $( '#primasRenovacion_1' ).val() ) * parseFloat($( '#comisionVentaRenovacion_1' ).val().replace( '%', '' )/100);	
	$( '#ingresoComisionRenovacion_text_1' ).html( '$ '+moneyFormat(total) );
	$( '#ingresoComisionRenovacion_1' ).val( total );		
	var total = parseFloat( $( '#primasRenovacionPagar_1' ).val() ) * parseFloat($( '#porbonoGanado_1' ).val().replace( '%', '' )/100);	
	$( '#ingresoBonoRenovacion_text_1' ).html( '$ '+moneyFormat(total) );
	$( '#ingresoBonoRenovacion_1' ).val( total );

	//SEGUNDO TRIMESTRE
	var primaAfectadas_2 = parseFloat( $( '#simulatorprimassegundotrimestre' ).val() );			
	var	negocios_2 = primaAfectadas_2 / parseFloat( $( '#primas_promedio' ).val() );		
	var porcentaje_2 = CalcPercBonoAplicado(primaAfectadas_2,negocios_2);
	$( '#bonoAplicado_2' ).val( porcentaje_2 );
	var total = parseFloat( $( '#simulatorprimassegundotrimestre' ).val() ) * parseFloat($( '#comisionVentaInicial_2' ).val().replace( '%', '' )/100);	
	$( '#ingresoComisionesVentaInicial_text_2' ).html( '$ '+moneyFormat(total) );
	$( '#ingresoComisionesVentaInicial_2' ).val( total );		
	var total = parseFloat( $( '#primasAfectasInicialesPagar_2' ).val() ) * parseFloat($( '#bonoAplicado_2' ).val().replace( '%', '' )/100);	
	$( '#ingresoBonoProductividad_text_2' ).html( '$ '+moneyFormat(total) );
	$( '#ingresoBonoProductividad_2' ).val( total );
	var total = parseFloat( $( '#primasRenovacion_2' ).val() ) * parseFloat($( '#comisionVentaRenovacion_2' ).val().replace( '%', '' )/100);	
	$( '#ingresoComisionRenovacion_text_2' ).html( '$ '+moneyFormat(total) );
	$( '#ingresoComisionRenovacion_2' ).val( total );		
	var total = parseFloat( $( '#primasRenovacionPagar_2' ).val() ) * parseFloat($( '#porbonoGanado_2' ).val().replace( '%', '' )/100);	
	$( '#ingresoBonoRenovacion_text_2' ).html( '$ '+moneyFormat(total) );
	$( '#ingresoBonoRenovacion_2' ).val( total );

	//TERCER TRIMESTRE
	var primaAfectadas_3 = parseFloat( $( '#simulatorprimastercertrimestre' ).val() );			
	var	negocios_3 = primaAfectadas_3 / parseFloat( $( '#primas_promedio' ).val() );		
	var porcentaje_3 = CalcPercBonoAplicado(primaAfectadas_3,negocios_3);
	$( '#bonoAplicado_3' ).val( porcentaje_3 );	
	var total = parseFloat( $( '#simulatorprimastercertrimestre' ).val() ) * parseFloat($( '#comisionVentaInicial_3' ).val().replace( '%', '' )/100);	
	$( '#ingresoComisionesVentaInicial_text_3' ).html( '$ '+moneyFormat(total) );
	$( '#ingresoComisionesVentaInicial_3' ).val( total );		
	var total = parseFloat( $( '#primasAfectasInicialesPagar_3' ).val() ) * parseFloat($( '#bonoAplicado_3' ).val().replace( '%', '' )/100);	
	$( '#ingresoBonoProductividad_text_3' ).html( '$ '+moneyFormat(total) );
	$( '#ingresoBonoProductividad_3' ).val( total );
	var total = parseFloat( $( '#primasRenovacion_3' ).val() ) * parseFloat($( '#comisionVentaRenovacion_3' ).val().replace( '%', '' )/100);	
	$( '#ingresoComisionRenovacion_text_3' ).html( '$ '+moneyFormat(total) );
	$( '#ingresoComisionRenovacion_3' ).val( total );		
	var total = parseFloat( $( '#primasRenovacionPagar_3' ).val() ) * parseFloat($( '#porbonoGanado_3' ).val().replace( '%', '' )/100);	
	$( '#ingresoBonoRenovacion_text_3' ).html( '$ '+moneyFormat(total) );
	$( '#ingresoBonoRenovacion_3' ).val( total );

	//CUARTO TRIMESTRE
	var primaAfectadas_4 = parseFloat( $( '#simulatorprimascuartotrimestre' ).val() );			
	var	negocios_4 = primaAfectadas_4 / parseFloat( $( '#primas_promedio' ).val() );		
	var porcentaje_4 = CalcPercBonoAplicado(primaAfectadas_4,negocios_4);
	$( '#bonoAplicado_4' ).val( porcentaje_4 );	
	var total = parseFloat( $( '#simulatorprimascuartotrimestre' ).val() ) * parseFloat($( '#comisionVentaInicial_4' ).val().replace( '%', '' )/100);	
	$( '#ingresoComisionesVentaInicial_text_4' ).html( '$ '+moneyFormat(total) );
	$( '#ingresoComisionesVentaInicial_4' ).val( total );		
	var total = parseFloat( $( '#primasAfectasInicialesPagar_4' ).val() ) * parseFloat($( '#bonoAplicado_4' ).val().replace( '%', '' )/100);	
	$( '#ingresoBonoProductividad_text_4' ).html( '$ '+moneyFormat(total) );
	$( '#ingresoBonoProductividad_4' ).val( total );
	var total = parseFloat( $( '#primasRenovacion_4' ).val() ) * parseFloat($( '#comisionVentaRenovacion_4' ).val().replace( '%', '' )/100);	
	$( '#ingresoComisionRenovacion_text_4' ).html( '$ '+moneyFormat(total) );
	$( '#ingresoComisionRenovacion_4' ).val( total );		
	var total = parseFloat( $( '#primasRenovacionPagar_4' ).val() ) * parseFloat($( '#porbonoGanado_4' ).val().replace( '%', '' )/100);	
	$( '#ingresoBonoRenovacion_text_4' ).html( '$ '+moneyFormat(total) );
	$( '#ingresoBonoRenovacion_4' ).val( total );
}

function vida_ingresoTotal(){	
	var total = 0;		
	//PRIMER TRIMESTRE
		var total_1 = 0;
		total_1 += parseFloat( $( '#ingresoComisionesVentaInicial_1' ).val() );		
		total_1 += parseFloat( $( '#ingresoComisionRenovacion_1' ).val() );		
		total_1 += parseFloat( $( '#ingresoBonoProductividad_1' ).val() );		
		total_1 += parseFloat( $( '#ingresoBonoRenovacion_1' ).val() );
		total += total_1;
	$( '#simulator-ingresos-primer-trimestre' ).html( '$ '+moneyFormat(total_1) );
	$( '#simulatoringresosprimertrimestre' ).val( total_1 );
	$( '#ingresoTotal_text' ).html( '$ '+moneyFormat(total) );	
	$( '#ingresoTotal' ).val( total );

	//SEGUNDO TRIMESTRE
		var total_2 = 0;
		total_2 += parseFloat( $( '#ingresoComisionesVentaInicial_2' ).val() );		
		total_2 += parseFloat( $( '#ingresoComisionRenovacion_2' ).val() );		
		total_2 += parseFloat( $( '#ingresoBonoProductividad_2' ).val() );		
		total_2 += parseFloat( $( '#ingresoBonoRenovacion_2' ).val() );	
		total += total_2;
	$( '#simulator-ingresos-segundo-trimestre' ).html( '$ '+moneyFormat(total_2) );
	$( '#simulatoringresossegundotrimestre' ).val( total_2 );
	$( '#ingresoTotal_text' ).html( '$ '+moneyFormat(total) );	
	$( '#ingresoTotal' ).val( total );

	//TERCER TRIMESTRE
		var total_3 = 0;
		total_3 += parseFloat( $( '#ingresoComisionesVentaInicial_3' ).val() );		
		total_3 += parseFloat( $( '#ingresoComisionRenovacion_3' ).val() );		
		total_3 += parseFloat( $( '#ingresoBonoProductividad_3' ).val() );		
		total_3 += parseFloat( $( '#ingresoBonoRenovacion_3' ).val() );	
		total += total_3;
	$( '#simulator-ingresos-tercer-trimestre' ).html( '$ '+moneyFormat(total_3) );
	$( '#simulatoringresostercertrimestre' ).val( total_3 );
	$( '#ingresoTotal_text' ).html( '$ '+moneyFormat(total) );	
	$( '#ingresoTotal' ).val( total );

	//CUARTO TRIMESTRE
		var total_4 = 0;
		total_4 += parseFloat( $( '#ingresoComisionesVentaInicial_4' ).val() );		
		total_4 += parseFloat( $( '#ingresoComisionRenovacion_4' ).val() );		
		total_4 += parseFloat( $( '#ingresoBonoProductividad_4' ).val() );		
		total_4 += parseFloat( $( '#ingresoBonoRenovacion_4' ).val() );	
		total += total_4;
	$( '#simulator-ingresos-cuarto-trimestre' ).html( '$ '+moneyFormat(total_4) );
	$( '#simulatoringresoscuartotrimestre' ).val( total_4 );
	$( '#ingresoTotal_text' ).html( '$ '+moneyFormat(total) );	
	$( '#ingresoTotal' ).val( total );
}
function vida_ingresoPromedio(){	
	var total = parseFloat($( '#ingresoTotal' ).val());	
	total = total/parseInt( $( '#periodo' ).val() );	
	$( '#inresoPromedioMensual_text' ).html( '$ '+moneyFormat(total) );
	$( '#inresoPromedioMensual' ).val(total);		
}

function ShowHideRow($i) {
	if(document.getElementById('row'+$i).style.display=='none') {
		document.getElementById('row'+$i).style.display = '';
		document.getElementById('showRow'+$i).innerHTML='Ocultar';
		document.getElementById('Arrow'+$i).innerHTML='&uarr;';
	} else {
		document.getElementById('row'+$i).style.display = 'none';
		document.getElementById('showRow'+$i).innerHTML='Mostrar';
		document.getElementById('Arrow'+$i).innerHTML='&darr;';
	}
}

function CalcPercBonoAplicado(primaAfectadas,negocios) {
	var porcentaje = 0;
	if( primaAfectadas >= 830000 ){		
		if( negocios >= 3 && negocios < 5 )	porcentaje = 15;
		if( negocios >= 5 && negocios < 7 )	porcentaje = 30;
		if( negocios >= 7 && negocios < 9 )	porcentaje = 35;
		if( negocios >= 9 )	porcentaje = 40;

		/*
		if( negocios >= 2 && negocios < 4 )	porcentaje = 15;			
		if( negocios >= 4 && negocios < 6 )	porcentaje = 30;				
		if( negocios >= 6 && negocios < 9 )	porcentaje = 35;				
		if( negocios >= 9 )	porcentaje = 40;
		*/
	}		
	if( primaAfectadas >= 620000 && primaAfectadas < 830000 ){	
		if( negocios >= 3 && negocios < 5 )	porcentaje = 13;
		if( negocios >= 5 && negocios < 7 )	porcentaje = 28;
		if( negocios >= 7 && negocios < 9 )	porcentaje = 32.5;
		if( negocios >= 9 )	porcentaje = 36;
		/*	
		if( negocios >= 2 && negocios < 4 )	porcentaje = 13;			
		if( negocios >= 4 && negocios < 6 )	porcentage = 28;				
		if( negocios >= 6 && negocios < 9 )	porcentaje = 32.5;				
		if( negocios >= 9 )	porcentaje = 36;		
		*/	
	}		
	if( primaAfectadas >=460000 && primaAfectadas < 620000 ){	
		if( negocios >= 3 && negocios < 5 )	porcentaje = 11;
		if( negocios >= 5 && negocios < 7 )	porcentaje = 26;
		if( negocios >= 7 && negocios < 9 )	porcentaje = 30;
		if( negocios >= 9 )	porcentaje = 32.5;
	/*	
		if( negocios >= 2 && negocios < 4 )	porcentaje = 11;			
		if( negocios >= 4 && negocios < 6 )	porcentaje = 26;			
		if( negocios >= 6 && negocios < 9 )	porcentaje = 30;		
		if( negocios >= 9 )	porcentaje = 32.5;		
		*/	
	}		
	if( primaAfectadas >= 355000 && primaAfectadas < 460000 ){	
		if( negocios >= 3 && negocios < 5 )	porcentaje = 8;
		if( negocios >= 5 && negocios < 7 )	porcentaje = 19;
		if( negocios >= 7 && negocios < 9 )	porcentaje = 22.5;
		if( negocios >= 9 )	porcentaje = 25;
	/*	
		if( negocios >= 2 && negocios < 4 )	porcentaje = 8;			
		if( negocios >= 4 && negocios < 6 )	porcentaje = 19;			
		if( negocios >= 6 && negocios < 9 )	porcentaje = 22.5;				
		if( negocios >= 9 )	porcentaje = 25;		
		*/	
	}		
	if( primaAfectadas >= 260000 && primaAfectadas < 3550000 ){	
		if( negocios >= 3 && negocios < 5 )	porcentaje = 7;
		if( negocios >= 5 && negocios < 7 )	porcentaje = 16;
		if( negocios >= 7 && negocios < 9 )	porcentaje = 20;
		if( negocios >= 9 )	porcentaje = 22.5;
	/*	
		if( negocios >= 2 && negocios < 4 )	porcentaje = 7;			
		if( negocios >= 4 && negocios < 6 )	porcentaje = 16;				
		if( negocios >= 6 && negocios < 9 )	porcentaje = 20;			
		if( negocios >= 9 )	porcentaje = 22.5;		
		*/	
	}		
	if( primaAfectadas >= 200000 && primaAfectadas < 260000 ){	
		if( negocios >= 3 && negocios < 5 )	porcentaje = 6;
		if( negocios >= 5 && negocios < 7 )	porcentaje = 13;
		if( negocios >= 7 && negocios < 9 )	porcentaje = 17.5;
		if( negocios >= 9 )	porcentaje = 20;
	/*	
		if( negocios >= 2 && negocios < 4 )	porcentaje = 6;			
		if( negocios >= 4 && negocios < 6 )	porcentaje = 13;			
		if( negocios >= 6 && negocios < 9 )	porcentaje = 17.5;				
		if( negocios >= 9 )	porcentaje = 20;	
		*/	
	}		
	if( primaAfectadas >= 135000 && primaAfectadas < 200000 ){	
		if( negocios >= 3 && negocios < 5 )	porcentaje = 5;
		if( negocios >= 5 && negocios < 7 )	porcentaje = 10;
		if( negocios >= 7 && negocios < 9 )	porcentaje = 15;
		if( negocios >= 9 )	porcentaje = 17.5;
	/*	
		if( negocios >= 2 && negocios < 4 )	porcentaje = 5;			
		if( negocios >= 4 && negocios < 6 )	porcentaje = 10;			
		if( negocios >= 6 && negocios < 9 )	porcentaje = 15;			
		if( negocios >= 9 )	porcentaje = 17.5;	
		*/		
	}
	return porcentaje;
}

function CalcPercConservacion(base,primaAfectadas) {
	var porcentaje = 0;
	if( base == 0 ){						
		if( primaAfectadas >= 470000 )	porcentaje = 11;			
		if( primaAfectadas >= 370000 && primaAfectadas < 470000 )	porcentaje = 10;			
		if( primaAfectadas >= 270000 && primaAfectadas < 370000 )	porcentaje = 9;			
		if( primaAfectadas >= 220000 && primaAfectadas < 270000 )	porcentaje = 7;			
		if( primaAfectadas >= 160000 && primaAfectadas < 220000 )	porcentaje = 5;				
		if( primaAfectadas >= 130000 && primaAfectadas < 160000 )	porcentaje = 4;				
		if( primaAfectadas >= 100000 && primaAfectadas < 130000 )	porcentaje = 2;	
	}		
	if( base != 0 ){						
		if( base == 89 ){				
			if( primaAfectadas >= 470000 )	porcentaje = 9;				
			if( primaAfectadas >= 370000 && primaAfectadas < 470000 )	porcentaje = 8;				
			if( primaAfectadas >= 270000 && primaAfectadas < 370000 )	porcentaje = 7;				
			if( primaAfectadas >= 220000 && primaAfectadas < 270000 )	porcentaje = 4;				
			if( primaAfectadas >= 160000 && primaAfectadas < 220000 )	porcentaje = 3;	
			if( primaAfectadas >= 130000 && primaAfectadas < 160000 )	porcentaje = 2;				
			if( primaAfectadas >= 100000 && primaAfectadas < 130000 )	porcentaje = 1;	
		}			
		if( base == 91 ){				
			if( primaAfectadas >= 470000 )	porcentaje = 10;				
			if( primaAfectadas >= 370000 && primaAfectadas < 470000 )	porcentaje = 9;				
			if( primaAfectadas >= 270000 && primaAfectadas < 370000 )	porcentaje = 8;				
			if( primaAfectadas >= 220000 && primaAfectadas < 270000 )	porcentaje = 5;				
			if( primaAfectadas >= 160000 && primaAfectadas < 220000 )	porcentaje = 4;	
			if( primaAfectadas >= 130000 && primaAfectadas < 160000 )	porcentaje = 3;					
			if( primaAfectadas >= 100000 && primaAfectadas < 130000 )	porcentaje = 2;	
		}			
		if( base == 93 ){				
			if( primaAfectadas >= 470000 )	porcentaje = 11;				
			if( primaAfectadas >= 370000 && primaAfectadas < 470000 )	porcentaje = 10;				
			if( primaAfectadas >= 270000 && primaAfectadas < 370000 )	porcentaje = 9;				
			if( primaAfectadas >= 220000 && primaAfectadas < 270000 )	porcentaje = 6;				
			if( primaAfectadas >= 160000 && primaAfectadas < 220000 )	porcentaje = 5;	
			if( primaAfectadas >= 130000 && primaAfectadas < 160000 )	porcentaje = 4;	
			if( primaAfectadas >= 100000 && primaAfectadas < 130000 )	porcentaje = 3;	
		}			
		if( base == 95 ){				
			if( primaAfectadas >= 470000 )	porcentaje = 12;				
			if( primaAfectadas >= 370000 && primaAfectadas < 470000 )	porcentaje = 11;				
			if( primaAfectadas >= 270000 && primaAfectadas < 370000 )	porcentaje = 10;				
			if( primaAfectadas >= 220000 && primaAfectadas < 270000 )	porcentaje = 7;				
			if( primaAfectadas >= 160000 && primaAfectadas < 220000 )	porcentaje = 6;	
			if( primaAfectadas >= 130000 && primaAfectadas < 160000 )	porcentaje = 5;
			if( primaAfectadas >= 100000 && primaAfectadas < 130000 )	porcentaje = 4;
		}
	}							
	return porcentaje;
}