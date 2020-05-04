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
			
			form.submit();
		  }		
		
	});
	
	
	// Click on group products
	$( '.ramo' ).bind( 'click', function(){
		
		
		
		var Data = { product_group : this.value };
		
		$.ajax({

			url:  Config.base_url()+'ot/getPolicyByGroup.html',
			type: "POST",
			//dataType: "json",
			data: Data,
			cache: false,
			async: false,
			beforeSend: function(){
	
				
				$( '#loadproduct' ).html( '<img src="'+Config.base_url()+'images/ajax-loaders/ajax-loader-1.gif">   Cargando...' );
				
			},
			success: function(data){
				
				
				$( '#loadproduct' ).html( '' );	
				$( '#product_id' ).html( data );								
				
			}						
	
		});
		
		
		
	});
		
});


function setFields( id ){
				
	var	array = id.split('-');
		
	var field = array[0];	
	
	var field_count = array[1];		
		
	
	var countAgent = parseInt( $( '#countAgent' ).val() );
		
		countAgent++;		
		
		$( '#countAgent' ).val(countAgent);	
		
		var maxValue=0;
		
		$("input[name='porcentaje[]']").each(function ()
		{
			var val = $(this).val();
			
				val = parseInt(val.replace( '%','' ));
				
			maxValue = maxValue+val;
			
		});
				
		maxValue = 100-maxValue;

		if( $( '#'+id ).val() == 0 ){
			
			var	array = id.split('-');
		
			var field = array[0];	
			
			var field_count = array[1];		
			
			$( '#agent-field-'+field_count ).html('');
		
		}
		
		if( maxValue > 0 ){
		
			var fields  =	'<div id="agent-field-'+countAgent+'" class="control-group">';
					fields +=	'	<label class="control-label text-error" for="inputError">Agente</label>';
					fields +=	'				<div class="controls">';
					fields +=	'				   <select class="input-xlarge focused required" id="sel-agent-'+countAgent+'" name="agent[]">';
					fields +=	'';					
					fields +=	'				   </select>';
					fields +=	'				   <input class="input-small focused required porcentaje" id ="agent-'+countAgent+'" name="porcentaje[]" type="text"  onblur="javascript: setFields( \'agent-'+countAgent+'\' )" value="'+maxValue+'">';
					fields +=	'				</div>';
					fields +=	'			  </div>';
			
		
				$( '#dinamicagent' ).append( fields );
			
			$('#agent-'+countAgent+'').rules('add', {
				max: maxValue
			});
					
		}
		
		$( '#'+id ).val( $( '#'+id ).val()+'%' );
		
		// LLenar el select agents
		$.ajax({

			url:  Config.base_url()+'ot/getSelectAgents.html',
			type: "POST",
			cache: false,
			async: false,
			success: function(data){
				
				
				$( '#sel-agent-'+countAgent ).html( data );	
						
				
			}						
	
		});
		
	
}