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
	$(".btn-ramo").bind( "click", function(){
		$("#hidden-ramo").val($(this).val());
	})

	$( '.link-ramos' ).bind( 'click', function(){
		if( this.id == 'vida' ){	
			$( '#ramo' ).val(1);
		}
		if( this.id == 'gmm' ){
			$( '#ramo' ).val(2);		
		}
		if( this.id == 'autos' ){
			$( '#ramo' ).val(3);		
		}
		$( '#form' ).submit();
	});
});
function moneyFormat( n ){	
	if( isNaN( n ) ) return 0;	
	return n.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");	
}