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
    
	 var toDay = new Date();
		toDay = toDay.getFullYear();	
	

	
	// Field Dates
	$( '#birthdate' ).datepicker({ dateFormat: "yy-mm-dd", changeYear: true, changeMonth:true,yearRange: "1788:"+toDay });		
	$( '#license_expired_date' ).datepicker({ dateFormat: "yy-mm-dd", changeYear: true, changeMonth:true, yearRange: "1788:"+toDay });		
			
	$( '.filters' ).bind( 'click', function(){ 
		var checked = [];
		$("input[name='advanced[]']:checked").each(function ()
		{
			var element = $(this).val();
										
			checked.push( [$(this).val(), $( '#'+element ).val() ] );
		});

		var Data = { find: $('#find').val(), rol: $( '#rolsearch' ).val(), advanced: checked };
		$.ajax({
			url:  Config.base_url()+'usuarios/find.html',
			type: "POST",
			data: Data,
			cache: false,
			async: false,
			beforeSend: function(){
				$( '#loading' ).html( '<img src="'+Config.base_url()+'images/ajax-loaders/ajax-loader-1.gif">   Cargando...' );
			},
			success: function(data){
				$( '#loading' ).html( '' );	
				$( '#data' ).html( data );
				$("#tablesorted").trigger("update"); 
			}
		});
	});

	// Advanced find options
	$( '.advanced' ).hide();
	$( '.hide' ).hide();
	$( '.link-advanced' ).bind( 'click', function(){

		if( this.id == 'showadvanced' ){
			
			$( '.link-advanced' ).attr( 'id', 'hideadvanced' );
			$( '.advanced' ).show();
			var x=$('.link-advanced'); 
				x.text("Ocultar Filtro");
			
		}else{
			
			$( '.link-advanced' ).attr( 'id', 'showadvanced' );
			$( '.advanced' ).hide();
			var x=$('.link-advanced'); 
				x.text("Mostrar Filtro");
			
		}
			
			
	});
	
	$( '.checkboxadvance' ).bind( 'click', function(){
		
		if( this.checked == true )
			
			$( '#'+this.value ).show();
		
		else{
			$( '#'+this.value ).hide();
			$( '#'+this.value ).val('');
		}
		
	});
	
	// Rol search
	$( '.rol-search' ).bind( 'click', function(){

		// Reset Color
		$( '.rol-search' ).removeClass( 'btn btn-primary' );
		$(this).addClass( 'btn btn-link' );
		$( '.rol-search' ).css( 'margin-left', 15 ) ;
		// Set Color
		$(this).addClass( 'btn-primary' );
		$(this).removeClass( 'btn-link' );	

		$( '#rolsearch' ).val( this.id );
		var Data = { rol: this.id };
		$.ajax({
			url:  Config.base_url()+'usuarios/find.html',
			type: "POST",
			data: Data,
			cache: false,
			async: false,
			beforeSend: function(){
				$( '#loading' ).html( '<img src="'+Config.base_url()+'images/ajax-loaders/ajax-loader-1.gif">   Cargando...' );
			},
			success: function(data){
				$( '#loading' ).html( '' );	
				$( '#data' ).html( data );
				$("#tablesorted").trigger("update"); 
			}
		});
	});

	// Export Info
	$( '#pagactual' ).bind( 'click', function(){
		$('#typeexport').val('pagactual');
		$( '#search' ).attr( 'action', $( '#pag' ).val() );
		$( '#search' ).submit();
	});
	
	// Export Info
	$( '#busactual' ).bind( 'click', function(){
		
		if( $( '#find' ) .val().length > 0 ){
			$('#typeexport').val('busactual');
			$( '#search' ).attr( 'action', $( '#pag' ) .val() );
			$( '#search' ).submit();
		}else{
			alert( 'El campo de busqueda esta vacio' );
		}
	});

	 $( "#dialog-form" ).dialog({
		autoOpen: false,
		height: 200,
		width: 350,
		modal: true,
		buttons: {
		  Cancel: function() {
			$( this ).dialog( "close" );
		  }
		},
		close: function() {
		}
	  });

	   $( "#create-export" )
		.button()
		.click(function() {
//		  $( "#dialog-form" ).dialog( "open" );
			$('#typeexport').val('pagactual');
			$( '#search' ).attr( 'action', $( '#pag' ).val() );
			$( '#search' ).submit();
		});
	  $( "#create-export" ).removeClass( " ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" );
});