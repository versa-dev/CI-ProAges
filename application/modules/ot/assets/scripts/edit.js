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
var proagesEditOt = {};
$( document ).ready(function() {
    
	$( '#form' ).validate({

		 submitHandler: function(form) {
			// some other code
			// maybe disabling submit button
			// then:
			var maxValue=0;
			$("input[name='porcentaje[]']").each(function () {
				var val = $(this).val();
				val.replace( '%',''); // Clean %			
				val = parseInt(val.replace( '%','' ));
				$(this).val('');
				$(this).val(val);
				maxValue = maxValue+val;
			});

			var agentChecked = true;
			$("select[name='agent[]']").each(function () {
				if( $(this).val() == '' ) {
					agentChecked = false;
					return;
				}
			});

			if( (maxValue == 100) && agentChecked) {
				$( '#actions-buttons-forms' ).html( '<img src="'+Config.base_url()+'images/ajax-loaders/ajax-loader-5.gif">' );
				$( '#ot' ).val( $( '#otnumber' ).val()+$( '#ot' ).val() );
				form.submit();
			} else {
				alert( "Debe de cubir un porcentaje total de 100% y todos los campos de agentes son requeridos" );
			}
		  }		
	});

	proagesEditOt.changeDate = function(dateText) {
		var dateParts = dateText.split('-');
		$( '.ramo' ).each(function( i, val ) {
			var current = $(this);
			if (current.is(':checked')) {
				switch (current.val()) {
					case '1':
						$('#otnumber').val( dateParts[1] + dateParts[2] + 'V' ); 
						break;
					case '2':
						$('#otnumber').val( dateParts[1] + dateParts[2] + 'G' ); 
						break;
					case '3':
						$('#otnumber').val( dateParts[1] + dateParts[2] + 'A' ); 
						break;
					default:
						break;
				}
				return false;
			}
		});	
	}

	$( '#creation_date' ).bind( 'change', function(){
		var entered = $(this).val();
		if (entered.length)
			proagesEditOt.changeDate(entered);
	});
	
	$( '#creation_date' ).datepicker({
		dateFormat: "yy-mm-dd",
		changeYear: true,
		changeMonth: true,
        onSelect: function(dateText, inst) {
			proagesEditOt.changeDate(dateText);
		}
	});

});



/*
$('#prima').bind('change', function(){
	getPrimas();
});
*/
$('#prima').keyup(function() {
    var total_allocated = $('#prima').val() * value_allocated;
    var total_bonus 	= $('#prima').val() * value_allocated;
    if(typeof total_allocated != 'undefined'){
    	$('#allocatedPrime').val(total_allocated);
    }  
    if(typeof total_bonus != 'undefined'){
    	$('#bonusPrime').val(total_bonus);
    } 

});

$('#currency_id').bind('change', function () {
	getPrimas();
});

$('#period').bind('change', function () {
	getPrimas();
});

$('#value_id').bind('change', function () {
	getPrimas();
});

// Taken from create.js
function getPrimas(){
	var Data = {
		currency: parseInt($("#currency_id").val()),
		prima: parseInt($("#prima").val()),
		period: parseInt($("#period").val()),
		product: parseInt($("#value_id").text()),
	};
	$.ajax({
		type: "POST",
		url: Config.base_url() + 'ot/getNewPrimas',
		data: Data,
		success: function (response) {
			var data = JSON.parse(response);
			if (data.length != 0) {
				$("#allocatedPrime").val(data.allocatedPrime);
				$("#bonusPrime").val(data.bonusPrime);
				$(".allocatedPrime").show();
				$(".bonusPrime").show();
			} else {
				$(".allocatedPrime").hide();
				$(".bonusPrime").hide();
			}
		}, 
		error: function () {
			alert("Invalide!");
		}
	});
};

// Adding Fields
function setFields( id ){
	var	array = id.split('-');
	var field = array[0];	
	var field_count = array[1];		
	var countAgent = parseInt( $( '#countAgent' ).val() );
	countAgent++;		
	$( '#countAgent' ).val(countAgent);	
	var maxValue=0;
	$("input[name='porcentaje[]']").each(function () {
		var val = $(this).val();
		val.replace( '%',''); // Clean %			
		val = parseInt(val.replace( '%','' ));
		$(this).val('');
		$(this).val(val);
		maxValue = maxValue+val;
	});
	maxValue = 100-maxValue;
	if( $( '#'+id ).val() == 0 ){
		var	array = id.split('-');
		var field = array[0];	
		var field_count = array[1];		
		$( '#agent-field-'+field_count ).html('');
			return false;
	}

	if( maxValue > 0 ){
		var fields  =	'<div id="agent-field-'+countAgent+'" class="control-group">';
		fields +=	'	<label class="control-label text-error" for="inputError">Agente</label>';
		fields +=	'				<div class="controls">';
		fields +=	'				   <select class="input-xlarge focused" id="sel-agent-'+countAgent+'" name="agent[]">';
		fields +=	'';					
		fields +=	'				   </select>';
		fields +=	'				   <input class="input-small focused required porcentaje" id ="agent-'+countAgent+'" name="porcentaje[]" type="text"  onblur="javascript: setFields( \'agent-'+countAgent+'\' )" value="'+maxValue+'%"  placeholder="%"><small>Ponga un 0 para eliminar este campo.</small>';
		fields +=	'				</div>';
		fields +=	'			  </div>';
		$( '#dinamicagent' ).append( fields );
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