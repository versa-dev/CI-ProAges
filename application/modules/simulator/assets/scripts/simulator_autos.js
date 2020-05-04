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

  Autos Scripts	
*/
 
$( document ).ready(function() {
			
	$( '#primasnetasiniciales' ).bind( 'keyup', function(){ 
		var total = parseFloat( $( '#primasnetasiniciales' ).val() ) + parseFloat( $( '#primasdecarteras' ).val() );	
		$( '#primastotales_text' ).html( '$ '+moneyFormat(total) );
		$( '#primastotales' ).val( total );
		var total = parseFloat( $( '#primasnetasiniciales' ).val() ) / parseFloat($( '#primaspromedio' ).val().replace( '%', '' ));	
		$( '#nonegocios' ).val( Math.ceil(total) );
		var total = parseFloat( $( '#primasnetasiniciales' ).val() ) / parseFloat($( '#nonegocios' ).val().replace( '%', '' ));	
		//$( '#primaspromedio' ).val( Math.ceil(total) );
		//primastotales
		var total = parseFloat( $( '#primastotales' ).val() ) * parseFloat($( '#comisionVentaInicial' ).val().replace( '%', '' )/100);	
		$( '#ingresoComisionesVentaInicial_text' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoComisionesVentaInicial' ).val( total );	
		//BONO INICIAL
		var primasnetasiniciales = parseFloat( $( '#primasnetasiniciales' ).val() );
		if( primasnetasiniciales/3 >= 300000 ){
			$( '#bonoAplicado' ).val( 10.0+'%' );
			var total = parseFloat( $( '#primasnetasiniciales' ).val() ) * parseFloat( 10/100 );	
			$( '#ingresoBonoInicial_text' ).html( '$ '+moneyFormat(total) );
			$( '#ingresoBonoInicial' ).val( total );
		}
		if( primasnetasiniciales/3 >= 250000 && primasnetasiniciales/3 < 300000 ){
			$( '#bonoAplicado' ).val( 9.0+'%' );
			var total = parseFloat( $( '#primasnetasiniciales' ).val() ) * parseFloat( 9/100 );	
			$( '#ingresoBonoInicial_text' ).html( '$ '+moneyFormat(total) );
			$( '#ingresoBonoInicial' ).val( total );
		}
		if( primasnetasiniciales/3 >= 200000 && primasnetasiniciales/3 < 250000 ){
			$( '#bonoAplicado' ).val( 8.0+'%' );
			var total = parseFloat( $( '#primasnetasiniciales' ).val() ) * parseFloat( 8/100 );	
			$( '#ingresoBonoInicial_text' ).html( '$ '+moneyFormat(total) );
			$( '#ingresoBonoInicial' ).val( total );
		}
		if( primasnetasiniciales/3 >= 160000 && primasnetasiniciales/3 < 200000 ){
			$( '#bonoAplicado' ).val( 7.0+'%' );
			var total = parseFloat( $( '#primasnetasiniciales' ).val() ) * parseFloat( 7/100 );	
			$( '#ingresoBonoInicial_text' ).html( '$ '+moneyFormat(total) );
			$( '#ingresoBonoInicial' ).val( total );
		}		
		if( primasnetasiniciales/3 >= 120000 && primasnetasiniciales/3 < 160000 ){
			$( '#bonoAplicado' ).val( 6.0+'%' );
			var total = parseFloat( $( '#primasnetasiniciales' ).val() ) * parseFloat( 6/100 );	
			$( '#ingresoBonoInicial_text' ).html( '$ '+moneyFormat(total) );
			$( '#ingresoBonoInicial' ).val( total );
		}
		if( primasnetasiniciales/3 >= 80000 && primasnetasiniciales/3 < 120000 ){
			$( '#bonoAplicado' ).val( 5.0+'%' );
			var total = parseFloat( $( '#primasnetasiniciales' ).val() ) * parseFloat( 5/100 );	
			$( '#ingresoBonoInicial_text' ).html( '$ '+moneyFormat(total) );
			$( '#ingresoBonoInicial' ).val( total );
		}
		if( primasnetasiniciales/3 < 80000 ){
			$( '#bonoAplicado' ).val( 0+'%' );
			var total = parseFloat( $( '#primasnetasiniciales' ).val() ) * parseFloat( 0/100 );	
			$( '#ingresoBonoInicial_text' ).html( '$ '+moneyFormat(total) );
			$( '#ingresoBonoInicial' ).val( total );
		}
		//BONO DE CARTERA
		var primastotales = parseFloat( $( '#primastotales' ).val() );
		var porincrementoenprimas = $( '#porincrementoenprimas' ).val();
		if( primastotales/3 >= 650000 ){
			if( porincrementoenprimas == '0' ){
				$( '#porbonoganado' ).val( 5+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (5/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
			if( porincrementoenprimas == '5' ){
				$( '#porbonoganado' ).val( 7+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (7/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
			if( porincrementoenprimas == '10' ){
				$( '#porbonoganado' ).val( 10+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (10/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
		}
		if( primastotales/3 >= 500000 && primastotales/3 < 650000 ){
			if( porincrementoenprimas == '0' ){
				$( '#porbonoganado' ).val( 4+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (4/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
			if( porincrementoenprimas == '5' ){
				$( '#porbonoganado' ).val( 6+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (6/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
			if( porincrementoenprimas == '10' ){
				$( '#porbonoganado' ).val( 9+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (9/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
		}
		if( primastotales/3 >= 350000 && primastotales/3 < 500000 ){
			if( porincrementoenprimas == '0' ){
				$( '#porbonoganado' ).val( 3+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (3/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
			if( porincrementoenprimas == '5' ){
				$( '#porbonoganado' ).val( 5+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (5/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
			if( porincrementoenprimas == '10' ){
				$( '#porbonoganado' ).val( 8+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (8/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
		}
		if( primastotales/3 >= 200000 && primastotales/3 < 350000 ){
			if( porincrementoenprimas == '0' ){
				$( '#porbonoganado' ).val( 2+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (2/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
			if( porincrementoenprimas == '5' ){
				$( '#porbonoganado' ).val( 4+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (4/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
			if( porincrementoenprimas == '10' ){
				$( '#porbonoganado' ).val( 7+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (7/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
		}		
		if( primastotales/3 < 200000 ){
			if( porincrementoenprimas == '0' ){
				$( '#porbonoganado' ).val( 0+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (0/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
			if( porincrementoenprimas == '5' ){
				$( '#porbonoganado' ).val( 0+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (0/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
			if( porincrementoenprimas == '10' ){
				$( '#porbonoganado' ).val( 0+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (0/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
		}		
		autos_ingresototal(); autos_ingresopromedio(); getMetas();
	});
	$( '#primasdecarteras' ).bind( 'keyup', function(){ 
		var total = parseFloat( $( '#primasnetasiniciales' ).val() ) + parseFloat( $( '#primasdecarteras' ).val() );	
		$( '#primastotales_text' ).html( '$ '+moneyFormat(total) );
		$( '#primastotales' ).val( total );		
		autos_ingresototal(); autos_ingresopromedio(); getMetas();
	});	
	$( '#nonegocios' ).bind( 'keyup', function(){
		var total = parseFloat( $( '#primasnetasiniciales' ).val() ) / parseFloat($( '#nonegocios' ).val().replace( '%', '' ));	
		//$( '#primaspromedio' ).val(Math.ceil(total) );
		autos_ingresototal(); autos_ingresopromedio(); getMetas();
	});	
	$( '#primaspromedio' ).bind( 'keyup', function(){
		var total = parseFloat( $( '#primasnetasiniciales' ).val() ) / parseFloat($( '#primaspromedio' ).val().replace( '%', '' ));	
		$( '#nonegocios' ).val( Math.ceil(total) );
		autos_ingresototal(); autos_ingresopromedio(); getMetas();
	});	
	$( '#comisionVentaInicial' ).bind( 'keyup', function(){
		var total = parseFloat( $( '#primastotales' ).val() ) * parseFloat($( '#comisionVentaInicial' ).val().replace( '%', '' )/100);	
		$( '#ingresoComisionesVentaInicial_text' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoComisionesVentaInicial' ).val( total );	
		autos_ingresototal(); autos_ingresopromedio(); getMetas();
	});	
	$( '#porincrementoenprimas' ).bind( 'change', function(){
		var primastotales = parseFloat( $( '#primastotales' ).val() );
		var porincrementoenprimas = $( '#porincrementoenprimas' ).val();
		if( primastotales/3 >= 650000 ){
			if( porincrementoenprimas == '0' ){
				$( '#porbonoganado' ).val( 5+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (5/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
			if( porincrementoenprimas == '5' ){
				$( '#porbonoganado' ).val( 7+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (7/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
			if( porincrementoenprimas == '10' ){
				$( '#porbonoganado' ).val( 10+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (10/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
		}
		if( primastotales/3 >= 500000 && primastotales/3 < 650000 ){
			if( porincrementoenprimas == '0' ){
				$( '#porbonoganado' ).val( 4+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (4/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
			if( porincrementoenprimas == '5' ){
				$( '#porbonoganado' ).val( 6+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (6/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
			if( porincrementoenprimas == '10' ){
				$( '#porbonoganado' ).val( 9+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (9/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
		}
		if( primastotales/3 >= 350000 && primastotales/3 < 500000 ){
			if( porincrementoenprimas == '0' ){
				$( '#porbonoganado' ).val( 3+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (3/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
			if( porincrementoenprimas == '5' ){
				$( '#porbonoganado' ).val( 5+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (5/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
			if( porincrementoenprimas == '10' ){
				$( '#porbonoganado' ).val( 8+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (8/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
		}
		if( primastotales/3 >= 200000 && primastotales/3 < 350000 ){
			if( porincrementoenprimas == '0' ){
				$( '#porbonoganado' ).val( 2+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (2/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
			if( porincrementoenprimas == '5' ){
				$( '#porbonoganado' ).val( 4+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (4/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
			if( porincrementoenprimas == '10' ){
				$( '#porbonoganado' ).val( 7+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (7/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
		}
		if( primastotales/3 < 200000 ){
			if( porincrementoenprimas == '0' ){
				$( '#porbonoganado' ).val( 0+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (0/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
			if( porincrementoenprimas == '5' ){
				$( '#porbonoganado' ).val( 0+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (0/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
			if( porincrementoenprimas == '10' ){
				$( '#porbonoganado' ).val( 0+'%' );
				var total = parseFloat( $( '#primastotales' ).val() ) * (0/100);	
				$( '#ingresoBonoCartera_text' ).html( '$ '+moneyFormat(total) );
				$( '#ingresoBonoCartera' ).val( total );	
			}
		}
		autos_ingresototal(); autos_ingresopromedio(); getMetas();
	});
		autos_ingresototal(); autos_ingresopromedio(); 
});

function autos_ingresototal(){
	var ingresoComisionesVentaInicial = parseFloat( $( '#ingresoComisionesVentaInicial' ).val() );
	var ingresoBonoInicial = parseFloat( $( '#ingresoBonoInicial' ).val() );
	var ingresoBonoCartera = parseFloat( $( '#ingresoBonoCartera' ).val() );
	var total = ingresoComisionesVentaInicial;
		total += ingresoBonoInicial;
		total += ingresoBonoCartera;
		$( '#ingresoTotal_text' ).html( '$ '+moneyFormat(total) );
		$( '#ingresoTotal' ).val( total );
	
}
function autos_ingresopromedio(){
	var ingresoTotal = parseFloat( $( '#ingresoTotal' ).val() );
	var periodo = parseInt( $( '#periodo' ).val() );
	var total = ingresoTotal/periodo;
		$( '#inresoPromedioMensual_text' ).html( '$ '+moneyFormat(total) );
		$( '#inresoPromedioMensual' ).val( total );
	
}