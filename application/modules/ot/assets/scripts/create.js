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
			
			
			var maxValue=0;
			
			$("input[name='porcentaje[]']").each(function ()
			{
				
				var val = $(this).val();
				
				val.replace( '%',''); // Clean %
				
				val = parseInt(val.replace( '%','' ));
				
				$(this).val('');
				
				$(this).val(val);
				
				
				maxValue = maxValue+val;
				
			});
			
			
			$("select[name='agent[]']").each(function ()
			{
				
				if( $(this).val() == '' ){
					$( '#agenconfirm' ).val(false);
					return;
					
				}else{
					$( '#agenconfirm' ).val(true);
				}
				
				
			});
			
			
			if( maxValue == 100 && $( '#agenconfirm' ).val() == 'true' ){
				
				$( '#actions-buttons-forms' ).html( '<img src="'+Config.base_url()+'images/ajax-loaders/ajax-loader-5.gif">' );
				var toDay = new Date();
				var seconds = toDay.getSeconds();
				var minutes = toDay.getMinutes();
				var hour = toDay.getHours();
				
				toDay = hour+':'+minutes+':'+seconds;
				
				$( '#creation_date' ).val( $( '#creation_date' ).val()+' '+ toDay);
				
				
				$( '#ot' ).val( $( '#otnumber' ).val()+$( '#ot' ).val() );
				
				form.submit();
			}else{
				
				alert( "Debe de cubir un porcentaje total de 100% y todos los campos de agentes son requeridos" );
			}
		}
		
	});
	
	
	
	// Hide Fields
	$( '.typtramite' ).hide();
	$( '.subtype' ).hide();
	$( '#formpoliza' ).hide();
	$( '.poliza' ).hide();
	$(".allocatedPrime").hide();
	$(".bonusPrime").hide();
	
	
	
	
	// Setting Today
	var toDay = new Date();
	var year = toDay.getFullYear();
	var month = toDay.getMonth()+1;
	if( month < 10 )
	month='0'+month;
	var day = toDay.getDate();
	if( day < 10 )
	day='0'+day;
	
	
	
	
	toDay = year+'-'+month+'-'+day;
	
	$( '#creation_date' ).val(toDay);
	
	$( '#creation_date' ).datepicker({ dateFormat: "yy-mm-dd", changeYear: true, changeMonth:true });
	
	
	
	
	// Getting type tramite
	$( '.ramo' ).bind( 'click', function(){
		
		var Data = { ramo : this.value };
		
		// XXYYZ
		
		var date = $( '#creation_date' ).val();
		
		date = date.split('-');
		
		if( this.value == 1 )
		$('#otnumber').val( date[1]+date[2]+'V' );
		if( this.value == 2 )
		$('#otnumber').val( date[0].substr(-2)+date[1]+date[2]+'G' );
		if( this.value == 3 )
		$('#otnumber').val( date[1]+date[2]+'A' );
		
		$.ajax({
			
			url:  Config.base_url()+'ot/typetramite.html',
			type: "POST",
			data: Data,
			cache: false,
			async: false,
			beforeSend: function(){
				
				
				$( '#loadtype' ).html( '<img src="'+Config.base_url()+'images/ajax-loaders/ajax-loader-1.gif">   Cargando...' );
				$( '#work_order_type_id' ).html('');
				
			},
			success: function(data){
				
				$( '#loadtype' ).html( '' );
				$( '#work_order_type_id' ).html( data );
				$( '.typtramite' ).show();
				
			}
			
		});
		
		$.ajax({
			
			url:  Config.base_url()+'ot/policies.html',
			type: "POST",
			data: Data,
			cache: false,
			async: false,
			success: function(data){
				
				$( '#policy_id' ).html(data);
				
			}
			
		});
		
		
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
	
	
	// Getting sub type
	$( '#work_order_type_id' ).bind( 'change', function(){
		
		var Data = { type : this.value };
		
		$.ajax({
			
			url:  Config.base_url()+'ot/subtype.html',
			type: "POST",
			data: Data,
			cache: false,
			async: false,
			beforeSend: function(){
				
				
				$( '#loadsubtype' ).html( '<img src="'+Config.base_url()+'images/ajax-loaders/ajax-loader-1.gif">   Cargando...' );
				$( '#subtype' ).html('');
				
			},
			success: function(data){
				
				$( '#loadsubtype' ).html( '' );
				$( '#subtype' ).html( data );
				$( '.subtype' ).show();
				
				
			}
			
		});
		
		
		if( this.value != 47 && this.value != 90 ){
			
			$( '.new-bussiness' ).hide();
			$( '#agent-1' ).val( '100%' );
			$( '#agent-select' ).removeClass( 'required' );
		}else{
			
			$( '.new-bussiness' ).show();
			$( '#agent-1' ).val( '100%' );
			$( '#agent-select' ).addClass( 'required' );
			
		}
		
		
	});
	
	
	
	
	/**
	*	Config Poliza
	**/
	$( '#subtype' ).bind( 'change', function(){
		
		
		if( $( '#work_order_type_id' ).val() == '90' || $( '#work_order_type_id' ).val() == '47' ){
			$( '#formpoliza' ).show();
			$( '.poliza' ).hide();
		}else{
			$( '.poliza' ).show();
			$( '#formpoliza' ).hide();
		}
		
		
		if( $( '#work_order_type_id' ).val() == 109 ){
			
			$( '.poliza' ).hide();
			$( '#uid' ).removeClass( 'required' );
		}
		
		
	});
	
	
	$( '#product_id' ).bind( 'change', function(){
		
		var Data = { id : this.value };
		
		$.ajax({
			
			url:  Config.base_url()+'ot/period.html',
			type: "POST",
			data: Data,
			cache: false,
			async: false,
			beforeSend: function(){
				
				
				$( '#loadperiod' ).html( '<img src="'+Config.base_url()+'images/ajax-loaders/ajax-loader-1.gif">   Cargando...' );
				$( '#period' ).html('');
				
			},
			success: function(data){
				
				$( '#loadperiod' ).html( '' );
				
				if( data !== '	<option value="">Seleccione</option>' ){
					$( '#period' ).html( data );
					$( '.period' ).show();
					$( '#period' ).addClass( 'required' );
				}else{
					$( '.period' ).hide();
					$( '#period' ).removeClass( 'required' );
				}
				
				
			}
			
		});
		
	});
	
	$('#prima').bind('change', function(){
		getPrimas();
	});
	
	$('#currency_id').bind('change', function () {
		getPrimas();
	});

	$('#period').bind('change', function () {
		getPrimas();
	});

	$('#product_id').bind('change', function () {
		getPrimas();
	});

	function getPrimas(){
		var Data = {
			currency: parseInt($("#currency_id").val()),
			prima: parseInt($("#prima").val()),
			period: parseInt($("#period").val()),
			product: parseInt($("#product_id").val())
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
	}
 });


// Adding Fields
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
		dataType: 'json',
		async: false,
		success: function(data){
			
			
			$( '#sel-agent-'+countAgent ).html( data );
			
			
		}
		
	});
	
	
}