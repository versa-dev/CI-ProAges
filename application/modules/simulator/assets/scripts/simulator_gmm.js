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
  
  GMM Scripts
  	
*/
$( document ).ready(function(i) {
	getMetas(); clickPrimasPromedio(); clickPrimasIniciales(); clickPorSiniestridad(); clickPrimasRenovacion(); clickXAcotamiento(); calculoBonoPrimerAnio(); clickComisionVentaInicial(); clickComisionVentaRenovacion(); gmm_ingresototal(); gmm_ingresopromedio(); 
	$( '#primasnetasiniciales' ).bind( 'keyup', function(){ 
		getMetas(); clickPrimasPromedio(); clickPrimasIniciales(); clickPorSiniestridad(); clickPrimasRenovacion(); clickXAcotamiento(); calculoBonoPrimerAnio(); clickComisionVentaInicial(); clickComisionVentaRenovacion(); gmm_ingresototal(); gmm_ingresopromedio(); 
	});			
	$( '#primaspromedio' ).bind( 'keyup', function(){
		getMetas(); clickPrimasPromedio(); clickPrimasIniciales(); clickPorSiniestridad(); clickPrimasRenovacion(); clickXAcotamiento(); calculoBonoPrimerAnio(); clickComisionVentaInicial(); clickComisionVentaRenovacion(); gmm_ingresototal(); gmm_ingresopromedio(); 
	});
	
	$( '#porAcotamiento' ).bind( 'keyup', function(){
		getMetas(); clickPrimasPromedio(); clickPrimasIniciales(); clickPorSiniestridad(); clickPrimasRenovacion(); clickXAcotamiento(); calculoBonoPrimerAnio(); clickComisionVentaInicial(); clickComisionVentaRenovacion(); gmm_ingresototal(); gmm_ingresopromedio(); 
	});
	
	for (var $i = 1; $i <= 3; $i++) {
		$( '#primasRenovacion\\['+$i+'\\]' ).bind( 'keyup', function(){
		getMetas(); clickPrimasPromedio(); clickPrimasIniciales(); clickPorSiniestridad(); clickPrimasRenovacion(); clickXAcotamiento(); calculoBonoPrimerAnio(); clickComisionVentaInicial(); clickComisionVentaRenovacion(); gmm_ingresototal(); gmm_ingresopromedio(); 
		});
	}
	for ($i=1;$i<=3;$i++) {		
		$( '#XAcotamiento\\['+$i+'\\]' ).bind( 'keyup', function(){ 
		getMetas(); clickPrimasPromedio(); clickPrimasIniciales(); clickPorSiniestridad(); clickPrimasRenovacion(); clickXAcotamiento(); calculoBonoPrimerAnio(); clickComisionVentaInicial(); clickComisionVentaRenovacion(); gmm_ingresototal(); gmm_ingresopromedio(); 
		});
	}	
	for ($i=1;$i<=3;$i++) {		
		$( '#comisionVentaInicial\\['+$i+'\\]' ).bind( 'keyup', function(){ 
		getMetas(); clickPrimasIniciales(); clickPorSiniestridad(); clickPrimasRenovacion(); clickXAcotamiento(); calculoBonoPrimerAnio(); clickComisionVentaInicial(); clickComisionVentaRenovacion(); gmm_ingresototal(); gmm_ingresopromedio(); 
		});
		getMetas(); clickPrimasPromedio(); clickPrimasIniciales(); clickPorSiniestridad(); clickPrimasRenovacion(); clickXAcotamiento(); calculoBonoPrimerAnio(); clickComisionVentaInicial(); clickComisionVentaRenovacion(); gmm_ingresototal(); gmm_ingresopromedio(); 
	}	
	for ($i=1;$i<=3;$i++) {		
		$( '#comisionVentaRenovacion\\['+$i+'\\]' ).bind( 'keyup', function(){
		getMetas(); clickPrimasPromedio(); clickPrimasIniciales(); clickPorSiniestridad(); clickPrimasRenovacion(); clickXAcotamiento(); calculoBonoPrimerAnio(); clickComisionVentaInicial(); clickComisionVentaRenovacion(); gmm_ingresototal(); gmm_ingresopromedio(); 
		});
	}		
	for ($i=1;$i<=3;$i++) {		
		$( '#porsiniestridad\\['+$i+'\\]' ).bind( 'change', function(){
		getMetas(); clickPrimasPromedio(); clickPrimasIniciales(); clickPorSiniestridad(); clickPrimasRenovacion(); clickXAcotamiento(); calculoBonoPrimerAnio(); clickComisionVentaInicial(); clickComisionVentaRenovacion(); gmm_ingresototal(); gmm_ingresopromedio(); 
		});
	}
});
function clickPrimasIniciales () {
	if ($( '#porAcotamiento' ).length == 0)
		return false;
	for ($i=1;$i<=3;$i++) {
		var total = parseFloat( $( '#simulatorPrimasPeriod\\['+$i+'\\]' ).val() ) * parseFloat($( '#porAcotamiento' ).val().replace( '%', '' )/100);
		$( '#primasAfectasInicialesPagar_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
		$( '#primasAfectasInicialesPagar\\['+$i+'\\]' ).val( total ); 
		var total = parseFloat( $( '#primasnetasiniciales' ).val() ) / parseFloat($( '#primaspromedio' ).val().replace( '%', '' ));	
		$( '#nonegocios' ).val( Math.ceil(total) );
		var total = parseFloat( $( '#simulatorPrimasPeriod\\['+$i+'\\]' ).val() ) / parseFloat($( '#primaspromedio' ).val().replace( '%', '' ));
		//alert(total);
		$( '#noNegocios\\['+$i+'\\]' ).val( Math.ceil(total) );
		//$( '#primaspromedio' ).val( Math.ceil(total) );
		//primasRenovacion
		var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * parseFloat($( '#XAcotamiento\\['+$i+'\\]' ).val().replace( '%', '' )/100);	
		$( '#primasRenovacionPagar_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
		$( '#primasRenovacionPagar\\['+$i+'\\]' ).val( total );
		//comisionVentaInicial
		var total = parseFloat( $( '#simulatorPrimasPeriod\\['+$i+'\\]' ).val() ) * parseFloat($( '#comisionVentaInicial\\['+$i+'\\]' ).val().replace( '%', '' )/100);	
		$( '#ingresoComisionesVentaInicial_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoComisionesVentaInicial\\['+$i+'\\]' ).val( total );	
		//Ingreso por bono de renovación:
	}			
}
function clickPrimasPromedio () {
	if (($( '#primasnetasiniciales' ).length == 0) || ($( '#primaspromedio' ).length == 0) ||
		($( '#simulatorPrimasPeriod[1]' ).length == 0))
		return false;
	for ($i=1;$i<=3;$i++) {
		var total = parseFloat( $( '#primasnetasiniciales' ).val() ) / parseFloat($( '#primaspromedio' ).val().replace( '%', '' ));	
		var totalPeriod = parseFloat( $( '#simulatorPrimasPeriod\\['+$i+'\\]' ).val() ) / parseFloat($( '#primaspromedio' ).val().replace( '%', '' ));	
		$( '#nonegocios' ).val( Math.ceil(total) );
		$( '#noNegocios\\['+$i+'\\]' ).val( Math.ceil(totalPeriod) );
	}	
}
function calculoBonoPrimerAnio () {
	// Bonos de primer año
	for ($i=1;$i<=3;$i++) {
		var primasnetasinicialesParaBono = parseFloat( $( '#primasAfectasInicialesPagar\\['+$i+'\\]' ).val() );
		if( primasnetasinicialesParaBono >= 420000 ){
			$( '#bonoAplicado\\['+$i+'\\]' ).val( 15.0+'%' );
			var total = parseFloat( $( '#simulatorPrimasPeriod\\['+$i+'\\]' ).val() ) * parseFloat( 15/100 );	
			$( '#ingresoBonoProductividad_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
			$( '#ingresoBonoProductividad\\['+$i+'\\]' ).val( total );
		}
		if( primasnetasinicialesParaBono >= 310000 && primasnetasinicialesParaBono < 420000 ){
			$( '#bonoAplicado\\['+$i+'\\]' ).val( 12.0+'%' );
			var total = parseFloat( $( '#simulatorPrimasPeriod\\['+$i+'\\]' ).val() ) * parseFloat( 12/100 );	
			$( '#ingresoBonoProductividad_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
			$( '#ingresoBonoProductividad\\['+$i+'\\]' ).val( total );
		}
		if( primasnetasinicialesParaBono >= 210000 && primasnetasinicialesParaBono < 310000 ){
			$( '#bonoAplicado\\['+$i+'\\]' ).val( 10.0+'%' );
			var total = parseFloat( $( '#simulatorPrimasPeriod\\['+$i+'\\]' ).val() ) * parseFloat( 10/100 );	
			$( '#ingresoBonoProductividad_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
			$( '#ingresoBonoProductividad\\['+$i+'\\]' ).val( total );
		}
		if( primasnetasinicialesParaBono >= 150000 && primasnetasinicialesParaBono < 210000 ){
			$( '#bonoAplicado\\['+$i+'\\]' ).val( 7.5+'%' );
			var total = parseFloat( $( '#simulatorPrimasPeriod\\['+$i+'\\]' ).val() ) * parseFloat( 7.5/100 );	
			$( '#ingresoBonoProductividad_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
			$( '#ingresoBonoProductividad\\['+$i+'\\]' ).val( total );
		}	
		if( primasnetasinicialesParaBono >= 90000 && primasnetasinicialesParaBono < 150000 ){
			$( '#bonoAplicado\\['+$i+'\\]' ).val( 5+'%' );
			var total = parseFloat( $( '#simulatorPrimasPeriod\\['+$i+'\\]' ).val() ) * parseFloat( 5/100 );	
			$( '#ingresoBonoProductividad_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
			$( '#ingresoBonoProductividad\\['+$i+'\\]' ).val( total );
		}
		if( primasnetasinicialesParaBono < 90000 ){
			$( '#bonoAplicado\\['+$i+'\\]' ).val( 0+'%' );
			var total = parseFloat( $( '#simulatorPrimasPeriod\\['+$i+'\\]' ).val() ) * parseFloat( 0/100 );	
			$( '#ingresoBonoProductividad_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
			$( '#ingresoBonoProductividad\\['+$i+'\\]' ).val( total );
		}
	}
}
function clickPrimasRenovacion () {
	if ($( '#primasRenovacion[1]' ).length == 0)
		return false;
	for (var $i = 1; $i <= 3; $i++) {
		var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * parseFloat($( '#XAcotamiento\\['+$i+'\\]' ).val().replace( '%', '' )/100);	
		$( '#primasRenovacionPagar_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
		$( '#primasRenovacionPagar\\['+$i+'\\]' ).val( total );
		//comisionVentaRenovacion
		var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * parseFloat($( '#comisionVentaRenovacion\\['+$i+'\\]' ).val().replace( '%', '' )/100);	
		$( '#ingresoComisionRenovacion_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoComisionRenovacion\\['+$i+'\\]' ).val( total );
	}
}
function clickXAcotamiento () {
	if ($( '#primasRenovacion[1]' ).length == 0)
		return false;
	for ($i=1;$i<=3;$i++) {		
		var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * parseFloat($( '#XAcotamiento\\['+$i+'\\]' ).val().replace( '%', '' )/100);	
		$( '#primasRenovacionPagar_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
		$( '#primasRenovacionPagar\\['+$i+'\\]' ).val( total );		
	}
}
function clickComisionVentaInicial () {
	if ($( '#simulatorPrimasPeriod[1]' ).length == 0)
		return false;
	for ($i=1;$i<=3;$i++) {		
		var total = parseFloat( $( '#simulatorPrimasPeriod\\['+$i+'\\]' ).val() ) * parseFloat($( '#comisionVentaInicial\\['+$i+'\\]' ).val().replace( '%', '' )/100);
		$( '#ingresoComisionesVentaInicial_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoComisionesVentaInicial\\['+$i+'\\]' ).val( total );	
	}		
}
function clickComisionVentaRenovacion() {
	if ($( '#primasRenovacion[1]' ).length == 0)
		return false;
	for ($i=1;$i<=3;$i++) {		
		var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * parseFloat($( '#comisionVentaRenovacion\\['+$i+'\\]' ).val().replace( '%', '' )/100);	
		$( '#ingresoComisionRenovacion_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoComisionRenovacion\\['+$i+'\\]' ).val( total );
	}
}
function clickPorSiniestridad () {
	if ($( '#primasRenovacionPagar[1]' ).length == 0)
		return false;
	for ($i=1;$i<=3;$i++) {		
		var primasRenovacionPagar = parseFloat( $( '#primasRenovacionPagar\\['+$i+'\\]' ).val() );
		var siniestridad = $( '#porsiniestridad\\['+$i+'\\]' ).val();
		if( primasRenovacionPagar > 470000 ){
			if( siniestridad == '68' ){
				$( '#porbonoganado\\['+$i+'\\]' ).val( 3+'%' );
				var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * (3/100);	
				$( '#ingresoBonoRenovacion_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoRenovacion\\['+$i+'\\]' ).val( total );	
			}
			if( siniestridad == '64' ){
				$( '#porbonoganado\\['+$i+'\\]' ).val( 5+'%' );
				var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * (5/100);	
				$( '#ingresoBonoRenovacion_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoRenovacion\\['+$i+'\\]' ).val( total );	
			}
			if( siniestridad == '60' ){
				$( '#porbonoganado\\['+$i+'\\]' ).val( 8+'%' );
				var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * (8/100);	
				$( '#ingresoBonoRenovacion_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoRenovacion\\['+$i+'\\]' ).val( total );	
			}
		}
		if( primasRenovacionPagar >= 370000 && primasRenovacionPagar < 470000 ){
			if( siniestridad == '68' ){
				$( '#porbonoganado\\['+$i+'\\]' ).val( 2+'%' );
				var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * (2/100);	
				$( '#ingresoBonoRenovacion_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoRenovacion\\['+$i+'\\]' ).val( total );	
			}
			if( siniestridad == '64' ){
				$( '#porbonoganado\\['+$i+'\\]' ).val( 4+'%' );
				var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * (4/100);	
				$( '#ingresoBonoRenovacion_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoRenovacion\\['+$i+'\\]' ).val( total );	
			}
			if( siniestridad == '60' ){
				$( '#porbonoganado\\['+$i+'\\]' ).val( 6+'%' );
				var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * (6/100);	
				$( '#ingresoBonoRenovacion_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoRenovacion\\['+$i+'\\]' ).val( total );	
			}
		}		
		if( primasRenovacionPagar >= 260000 && primasRenovacionPagar < 370000 ){
			if( siniestridad == '68' ){
				$( '#porbonoganado\\['+$i+'\\]' ).val( 1.5+'%' );
				var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * (1.5/100);	
				$( '#ingresoBonoRenovacion_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoRenovacion\\['+$i+'\\]' ).val( total );	
			}
			if( siniestridad == '64' ){
				$( '#porbonoganado\\['+$i+'\\]' ).val( 3+'%' );
				var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * (3/100);	
				$( '#ingresoBonoRenovacion_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoRenovacion\\['+$i+'\\]' ).val( total );	
			}
			if( siniestridad == '60' ){
				$( '#porbonoganado\\['+$i+'\\]' ).val( 4.5+'%' );
				var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * (4.5/100);	
				$( '#ingresoBonoRenovacion_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoRenovacion\\['+$i+'\\]' ).val( total );	
			}
		}
		if( primasRenovacionPagar >= 190000 && primasRenovacionPagar < 260000 ){
			if( siniestridad == '68' ){
				$( '#porbonoganado\\['+$i+'\\]' ).val( 1+'%' );
				var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * (1/100);	
				$( '#ingresoBonoRenovacion_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoRenovacion\\['+$i+'\\]' ).val( total );	
			}
			if( siniestridad == '64' ){
				$( '#porbonoganado\\['+$i+'\\]' ).val( 2+'%' );
				var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * (2/100);	
				$( '#ingresoBonoRenovacion_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoRenovacion\\['+$i+'\\]' ).val( total );	
			}
			if( siniestridad == '60' ){
				$( '#porbonoganado\\['+$i+'\\]' ).val( 3+'%' );
				var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * (3/100);	
				$( '#ingresoBonoRenovacion_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoRenovacion\\['+$i+'\\]' ).val( total );	
			}
		}
		if( primasRenovacionPagar >= 140000 && primasRenovacionPagar < 190000 ){
			if( siniestridad == '68' ){
				$( '#porbonoganado\\['+$i+'\\]' ).val( .5+'%' );
				var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * (.5/100);	
				$( '#ingresoBonoRenovacion_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoRenovacion\\['+$i+'\\]' ).val( total );	
			}
			if( siniestridad == '64' ){
				$( '#porbonoganado\\['+$i+'\\]' ).val( 1+'%' );
				var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * (1/100);	
				$( '#ingresoBonoRenovacion_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoRenovacion\\['+$i+'\\]' ).val( total );	
			}
			if( siniestridad == '60' ){
				$( '#porbonoganado\\['+$i+'\\]' ).val( 2+'%' );
				var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * (2/100);	
				$( '#ingresoBonoRenovacion_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoRenovacion\\['+$i+'\\]' ).val( total );	
			}
		}
		if( primasRenovacionPagar < 140000 ){
			if( siniestridad == '68' ){
				$( '#porbonoganado\\['+$i+'\\]' ).val( 0+'%' );
				var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * (0/100);	
				$( '#ingresoBonoRenovacion_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoRenovacion\\['+$i+'\\]' ).val( total );	
			}
			if( siniestridad == '64' ){
				$( '#porbonoganado\\['+$i+'\\]' ).val( 1+'%' );
				var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * (0/100);	
				$( '#ingresoBonoRenovacion_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoRenovacion\\['+$i+'\\]' ).val( total );	
			}
			if( siniestridad == '60' ){
				$( '#porbonoganado\\['+$i+'\\]' ).val( 2+'%' );
				var total = parseFloat( $( '#primasRenovacion\\['+$i+'\\]' ).val() ) * (0/100);	
				$( '#ingresoBonoRenovacion_text\\['+$i+'\\]' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoRenovacion\\['+$i+'\\]' ).val( total );	
			}
		}
	}
}	
function gmm_ingresototal(){
	if ($( '#ingresoComisionesVentaInicial[1]' ).length == 0)
		return false;
	var total = 0;
	for ($j=1;$j<=3;$j++) {
		var ingresoComisionesVentaInicial = parseFloat( $( '#ingresoComisionesVentaInicial\\['+$j+'\\]' ).val() );
		var ingresoComisionRenovacion = parseFloat( $( '#ingresoComisionRenovacion\\['+$j+'\\]' ).val() );
		var ingresoBonoProductividad = parseFloat( $( '#ingresoBonoProductividad\\['+$j+'\\]' ).val() );
		var ingresoBonoRenovacion = parseFloat( $( '#ingresoBonoRenovacion\\['+$j+'\\]' ).val() );
		var totalPerdiodo = ingresoComisionesVentaInicial;
			totalPerdiodo += ingresoComisionRenovacion;
			totalPerdiodo += ingresoBonoProductividad;
			totalPerdiodo += ingresoBonoRenovacion;
			total += totalPerdiodo;
			$( '#simulatorIngresosPeriod_text\\['+$j+'\\]' ).html( '$ '+moneyFormat(totalPerdiodo) );
			$( '#simulatorIngresosPeriod\\['+$j+'\\]' ).val( totalPerdiodo );
			$( '#ingresoTotal_text' ).html( '$ '+moneyFormat(total) );
			$( '#ingresoTotal' ).val( total );
	}
}
function gmm_ingresopromedio(){
	if ($( '#ingresoTotal' ).length == 0)
		return false;
	var ingresoTotal = parseFloat( $( '#ingresoTotal' ).val() );
	var periodo = parseInt( $( '#periodo' ).val() );
	var total = ingresoTotal/periodo;
		$( '#ingresoPromedioMensual_text' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoPromedioMensual' ).val( total );
	
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
