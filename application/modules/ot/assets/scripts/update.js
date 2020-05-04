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
			
			if( maxValue == 100 ){
			
			
			
				$( '#actions-buttons-forms' ).html( '<img src="'+Config.base_url()+'images/ajax-loaders/ajax-loader-5.gif">' );
				
				var toDay = new Date();
				var seconds = toDay.getSeconds();
				var minutes = toDay.getMinutes();
				var hour = toDay.getHours();
				
				toDay = hour+':'+minutes+':'+seconds;
				
				$( '#creation_date' ).val( $( '#creation_date' ).val()+' '+ toDay);	
				
				
				form.submit();
			}else{
				alert( "Debe de cubir un porcentaje total de 100%" );
			}	
		  }		
		
	});
	
	
	
	// Hide Fields
	$( '.typtramite' ).hide();
	$( '.subtype' ).hide();
	$( '.new-poliza' ).hide();
	$( '.poliza' ).hide();
	
	
	
	
	// Setting Today
	var toDay = new Date();
	var year = toDay.getFullYear();
	var month = toDay.getMonth();
		if( month < 10 )
			month='0'+month;
	var day = toDay.getDay();
		if( day < 10 )
			day='0'+day;
	toDay = year+'-'+month+'-'+day;
	
	
	$( '#creation_date' ).val(toDay);	
	
	$( '#creation_date' ).datepicker();
	
	
	
/**
 *	Load And set currently data record
 **/
	
	var ramo = $('#otvalue').val();
	
	if( ramo == 1 )
		$('#ot').val('0725V'); 
	if( ramo == 2 )
		$('#ot').val('0725G'); 
	if( ramo == 3 )
		$('#ot').val('0725A'); 	
	
	var Data = { ramo : ramo };
	
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
			  
			  
			 			  
			  $( '#work_order_type_id' ).val( $( '#type' ).val() );
								
			  
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
			  
			  
			  if( $('#policy_id_vale' ).val() != 0 ){
			  
			  	$( '#policy_id' ).val( $('#policy_id_vale' ).val() );
				
				$( '.poliza' ).show();
			  } 
		  }						
  
	  });
	
	 
	 var Data = { type :  $( '#work_order_type_id' ).val() };
		
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
					
				 $( '#subtype' ).val( $( '#subtype_value' ).val() );								
				
			}						
	
		});
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	// Getting type tramite
	$( '.ramo' ).bind( 'click', function(){
		
		var Data = { ramo : this.value };
		
		if( this.value == 1 )
			$('#ot').val('0725V'); 
		if( this.value == 2 )
			$('#ot').val('0725G'); 
		if( this.value == 3 )
			$('#ot').val('0725A'); 	
		
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
		
		
	});




/**
 *	Config Poliza
 **/	
	$( '#subtype' ).bind( 'change', function(){
		
		$( '.poliza' ).show();
	
	});
	
	
	
	
	
});