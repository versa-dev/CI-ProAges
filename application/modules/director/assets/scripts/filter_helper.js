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
/////////
	
	$( ".link-ramo" ).bind( "click", function(){
		$( "#vida" ).css({"color": "#000"});
		$( "#gmm" ).css({"color": "#000"});
		$( "#autos" ).css({"color": "#000"});

		if( this.id == "vida" ){
			$( "#ramo" ).val(1);
			$( "#vida" ).css( "color", "#06F" );
			$( ".set_periodo" ).html( "Trimestre" );
			// "Trimestre" );
		}
		if( this.id == "gmm" ){
			$( "#ramo" ).val(2);
			$( "#gmm" ).css( "color", "#06F" );
			$( ".set_periodo" ).html( "Cuatrimestre" );
		}
		if( this.id == "autos" ){
			$( "#ramo" ).val(3);
			$( "#autos" ).css( "color", "#06F" );
			$( ".set_periodo" ).html( "Cuatrimestre" );
		}
		$( "#form" ).attr( "action", "" );
		$( "#form" ).submit();
	});
			
	$( ".filter" ).bind( "click", function(){
		$( "#form" ).attr( "action", "" );
	});

	function explode( val ) {
		return val.split( /\n\s*/ );
	}
	function extractLastRemote( term ) {
		return explode( term ).pop();
	}
	$( ".submit-form").bind("click", function( event ) {
		$( "#form").submit();
	})
	$( "#clear-policy-filter").bind("click", function( event ) {
		$( "#policy-num" ).val("");
		$( "#form").submit();
	})
	$( "#policy-num" )
	// don\'t navigate away from the field on tab when selecting an item
		.bind( "keydown", function( event ) {
			if ( event.keyCode === $.ui.keyCode.TAB &&
				$( this ).data( "ui-autocomplete" ).menu.active ) {
				event.preventDefault();
			}
		})
/*			.bind( "change", function( event ) {
alert("changed!");
			})*/
		.autocomplete({
			minLength: 3,
/*			source: function( request, response ) {
				// delegate back to autocomplete, but extract the last term
				response( $.ui.autocomplete.filter(
					agentList, extractLastRemote( request.term ) ) );
			},*/
			source: function( request, response ) {
				$.getJSON( Config.base_url() + 'ot/search_polizas.html', {
					term: extractLastRemote( request.term )
				}, response );
			},				
			focus: function() {
				// prevent value inserted on focus
				return false;
			},
			select: function( event, ui ) {
				var terms = explode( this.value );
				// remove the current input
				terms.pop();
				// add the selected item
				terms.push( ui.item.value );
				// add placeholder to get the line feed at the end
				terms.push( "" );
				this.value = terms.join( "\n" );
				$( "#form").submit();
				return false;
			}
		})
});
