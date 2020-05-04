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

   $( '#total_negocio_pai_text' ).html( total_negocio_pai() );
   $( '#total_primas_pagadas_text' ).html( '$ '+ moneyFormat( parseFloat( total_primas_pagadas() ) ) );
	
   var citas = parseInt( $( '#citas' ).val() );	
   var primas = parseFloat( total_primas_pagadas() );	
	  
   $( '#indicador_txt' ).html( '$ '+ moneyFormat( parseFloat( primas/citas ) ) );
  
   var total_negocios = parseFloat( total_negocio() );	
   
   $( '#efectividad' ).html( Math.round((total_negocios/citas)*100) + '%' );
  
});