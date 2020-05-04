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
    
	$( '#form' ).validate({
		
		 submitHandler: function(form) {
			// some other code
			// maybe disabling submit button
			// then:
			$( '#actions-buttons-forms' ).html( '<img src="'+Config.base_url()+'images/ajax-loaders/ajax-loader-5.gif">' );
		  	
			
			//$( '#password' ).val( calcMD5( $( '#password' ).val() ) );
			
			form.submit();
		  }		
		
	});
	
	
	// Hide And Show block agent inputs
	$( '.input-agente' ).hide();
	$( '.input-novel-agente' ).hide();
	//$( '.input-novel-agente2' ).hide();
	//$( '.input-fisica' ).hide();
	$( '.input-moral' ).hide();
	
	
	
	
	$( '.roles' ).bind( 'click', function(){ 
				
		if( this.value == 1 && this.checked == true ){
			$( '.input-agente' ).show();
			$( '.input-novel-agente' ).show();
		}else if( this.value == 1 && this.checked == false ){
			$( '.input-agente' ).hide();	
		}
	
	})
	
	
	$( '.agente' ).bind( 'click', function(){ 	
		if( this.value == 'No' ){
			$( '.input-novel-agente' ).show();
			//$( '.input-novel-agente2' ).hide();
		}else{
			$( '.input-novel-agente' ).hide();
			//$( '.input-novel-agente2' ).show();
		}
	})
	
	
		
	
	$( '.persona' ).bind( 'click', function(){ 	
		
		if( this.value == 'fisica' ){
			
			$( '.input-moral' ).hide();	
			$( '.input-fisica' ).show();
			$( '.block-moral' ).removeClass( 'moral' );
			
		}else{
			
			$( '.input-fisica' ).hide();
			$( '.input-moral' ).show();	
			$( '.block-moral' ).addClass( 'moral' );
			
		}
	
	})
	
	
		
	
	
	// Addes Fields 
	$( '#folio_nacional_add' ).bind( 'click', function(){
			
			var id =  parseInt($( '#countFolioNational' ).val() );
				id++;
				$( '#countFolioNational' ).val(id);
			
			var fields  = '<div id="fieldNational-'+id+'"><br><input class="input-xlarge focused" name="folio_nacional[]" type="text">';
				fields += '<a href="javascript:void(0)" onclick="moral_folio_national('+id+')" class="btn btn-link" >-</a><br></div>';
			$( '#folio_nacional_fields' ).append( fields ) ;
			
	});
	
	$( '#folio_provicional_add' ).bind( 'click', function(){
			
			
			var id =  parseInt($( '#countFolioProvincial' ).val() );
				id++;
				$( '#countFolioProvincial' ).val(id);
			
			
			var fields  = '<div id="fieldProvincial-'+id+'"><br><input class="input-xlarge focused" name="folio_provincial[]" type="text">';
				fields += '<a href="javascript:void(0)" onclick="moral_folio_provincial('+id+')" class="btn btn-link" >-</a><br></div>';
			
			$( '#folio_provicional_fields' ).append( fields ) ;
			//$( '#folio_provicional_fields' ).append( '<br><input class="input-xlarge focused" name="folio_provicional[]" type="text"><br>' )   
	});
	
	
	$( '#moral_add' ).bind( 'click', function(){
			
			var id =  parseInt($( '#countMoralPerson' ).val() );
				id++;
				$( '#countMoralPerson' ).val(id);

			
			var fields =	'<div id="moral'+id+'"><br><hr>';
				fields +=	'<h5>Datos de representante moral</h5>';
				fields +=   '<div class="control-group input-moral">';
                fields +=  ' <label class="control-label" for="inputError">Nombre</label>';
                fields +=   ' <div class="controls">';
                fields +=     '<input class="input-xlarge focused" name="name_r[]" type="text">';
                fields +=    '</div>';
                fields +=  '</div>';
                  
                  
               fields +=   '<div class="control-group input-moral">';
               fields +=     '<label class="control-label" for="inputError">Apellidos</label>';
               fields +=     '<div class="controls">';
               fields +=      ' <input class="input-xlarge focused" name="lastname_r[]" type="text">';
               fields +=    ' </div>';
               fields +=  ' </div>';
                   
                   
                 
               fields +=    '<div class="control-group input-moral">';
               fields +=     '<label class="control-label" for="inputError">Teléfono oficina</label>';
               fields +=     '<div class="controls">';
               fields +=       '<input class="input-xlarge focused" name="office_phone[]" type="text">';
               fields +=    ' </div>';
               fields +=   '</div>';
                  
               fields +=    '<div class="control-group input-moral">';
               fields +=     '<label class="control-label" for="inputError">Extensión</label>';
               fields +=      '<div class="controls">';
               fields +=      ' <input class="input-xlarge focused" name="office_ext[]" type="text">';
               fields +=    ' </div>';
               fields +=  ' </div>';
                  
               fields +=  '<div class="control-group input-moral">';
               fields +=    '<label class="control-label" for="inputError">Teléfono movil</label>';
               fields +=     '<div class="controls">';
               fields +=      ' <input class="input-xlarge focused" name="mobile[]" type="text">';
               fields +=    ' </div>';
               fields +=   '</div>';
				
			   fields += '<br>'	;
			   
			   fields += ' <a href="javascript:void(0)" onclick="moral_remove('+id+')" class="btn btn-link input-moral" >- Eliminar este grupo.</a> '	;
			   
			   fields +=   '</div>';
				
			$( '#moral-fields' ).append( fields );   
	});
	
	
	var toDay = new Date();
		toDay = toDay.getFullYear();	
		
	var	expired = + parseInt(toDay)+10;
				
	// Field Dates
	$( '#birthdate' ).datepicker({ dateFormat: "yy-mm-dd", changeYear: true, changeMonth:true, yearRange: "1788:"+toDay });
	$( '#connection_date' ).datepicker({ dateFormat: "yy-mm-dd", changeYear: true, changeMonth:true, yearRange: "1788:"+toDay });
	$( '#license_expired_date' ).datepicker({ dateFormat: "yy-mm-dd", changeYear: true, changeMonth:true, yearRange: "1788:"+expired });
	
	
	
});

function moral_remove( id ){
	$( '#moral'+id ).hide('');
	$( '#moral'+id ).html('');
}

function moral_folio_national( id ){
	$( '#fieldNational-'+id ).hide('');
	$( '#fieldNational-'+id ).html('');
}

function moral_folio_provincial( id ){
	$( '#fieldProvincial-'+id ).hide('');
	$( '#fieldProvincial-'+id ).html('');
}

