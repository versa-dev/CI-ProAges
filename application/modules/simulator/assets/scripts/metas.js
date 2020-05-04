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
function stickyFooter(){
    positionFooter(); 
    function positionFooter(){
		//$(".table-totals").css({position: "absolute",top:($(window).scrollTop()+$(window).height()-$(".table-totals").height())+"px"})    
    } 
    $(window)
        .scroll(positionFooter)
        .resize(positionFooter)
}
$( document ).ready(function() {

	function selectDefaultYear() {
		var selectedPeriod = $( '#displayed-period option:selected' ).val();
		var yearToSelect = new Date().getFullYear();
		if ((selectedPeriod > 10) && (selectedPeriod < 100))
			yearToSelect++;
		var current;
		$( '#year option' ).each(function() {
			current = $(this);
			if (current.val() == yearToSelect)
				current.prop('selected', true);
			else
				current.prop('selected', false);
		})
	}

	$( '#displayed-period' ).on( 'change', function(){

//		selectDefaultYear();
		var selectedPeriod = $( '#displayed-period option:selected' ).val();
		if (selectedPeriod == 0) { // whole year selected
			for (var month = 1; month <= 12; month++) 
				$( "#month-row-" + month).show();
			for (var trimestre = 1; trimestre <= 4; trimestre++)
				$( "#total-trimestre-" + trimestre).show();
			for (var cuatrimestre = 1; cuatrimestre <= 3; cuatrimestre++)
				$( "#total-cuatrimestre-" + cuatrimestre).show();
		} else {
			for (var month = 1; month <= 12; month++) 
				$( "#month-row-" + month).hide();
			for (var trimestre = 1; trimestre <= 4; trimestre++)
				$( "#total-trimestre-" + trimestre).hide();
			for (var cuatrimestre = 1; cuatrimestre <= 3; cuatrimestre++)
				$( "#total-cuatrimestre-" + cuatrimestre).hide();
			if ((selectedPeriod > 0) && (selectedPeriod <= 12)) { // month selection
				$( "#month-row-" + selectedPeriod).show();
			} else { // cuatrimestre or trimestre selection
				switch (selectedPeriod) {
					case '121': // Cuatrimestre 1
						for (var month = 1; month <= 4; month++) 
							$( "#month-row-" + month).show();
						$( "#total-cuatrimestre-1").show();
						break;
					case '122': // Cuatrimestre 2
						for (var month = 5; month <= 8; month++) 
							$( "#month-row-" + month).show();
						$( "#total-cuatrimestre-2").show();
						break;
					case '123': // Cuatrimestre 3
						for (var month = 9; month <= 12; month++) 
							$( "#month-row-" + month).show();
						$( "#total-cuatrimestre-3").show();
						break;
					case '111': // Trimestre 1
						for (var month = 1; month <= 3; month++) 
							$( "#month-row-" + month).show();
						$( "#total-trimestre-1").show();
						break;
					case '112': // Trimestre 2
						for (var month = 4; month <= 6; month++) 
							$( "#month-row-" + month).show();
						$( "#total-trimestre-2").show();
						break;
					case '113': // Trimestre 3
						for (var month = 7; month <= 9; month++) 
							$( "#month-row-" + month).show();
						$( "#total-trimestre-3").show();
						break;
					case '114': // Trimestre 4
						for (var month = 10; month <= 12; month++) 
							$( "#month-row-" + month).show();
						$( "#total-trimestre-4").show();
						break;
					default:
						break;
				}
			}
		}
	});

	$( '.auto-submit' ).bind( 'change', function(){
		$( "#form").submit();
		return false;
	});

	stickyFooter(); 	
	$( '.simulator' ).hide();
	$( '.primas-meta-selector' ).bind( 'click', function(){		
		$( '#'+this.id ).hide();		
		$( '#'+this.id+'-field' ).show();		
	});
	$( '.primas-meta-field' ).bind( 'blur', function(){		
		var total=0;		
		for( var i=1; i<=12; i++ ){			
			if(  !isNaN( $( '#primas-meta-'+i ).val() ) )
				total += parseFloat( $( '#primas-meta-'+i ).val());			
		}			
		$( '#primasAfectasInicialesUbicar' ).val( total );
		$( '#primasnetasiniciales' ).val( total );		
		$( '#prima-total-anual' ).val( total );		
		$( '.primas-meta-selector' ).show();		
		$( '.primas-meta' ).hide();		
		getMetas();
		//save();		
	});	
	$(window).resize(function() {
		stickyFooter();
		var percent = ($("body").height() * 10)/100;
		$(".table-totals").css("height", percent+'px');
	});		
	$( '#open_simulator' ).bind( 'click', function(){		
		$( '.metas' ).hide();
		$( '.simulator' ).show();		
	});

// Do not know when the below is triggered
	$( '.link-ramo' ).bind( 'click', function(){
		var year = $( '#year' );
		if (year.length == 0) {
			var dataS = { userid: $( '#userid' ).val() };
		} else {
			var dataS = { period: $( '#period' ).val(), userid: $( '#userid' ).val(), year:$( '#year option:selected' ).val() };
		}
		$( '#vida' ).css({ 'color': '#000' });
		$( '#gmm' ).css({ 'color': '#000' });
		$( '#autos' ).css({ 'color': '#000' });	
		if( this.id == 'vida' ){							
			$( '#ramo' ).val(1);			
			$( '#vida' ).css( 'color', '#06F' );			
			$( '#ramo' ).val( 1 );
			$( '#periodo' ).val(3);	
			dataS.ramo = 'vida';
			$.ajax({
				url:  Config.base_url()+'simulator/getSimulator.html',
				type: "POST",
//				data: { ramo: 'vida', userid: $( '#userid' ).val(), period: $( '#period' ).val(), year: $( '#year option:selected' ).val() },
				data: dataS,
				cache: false,
				async: false,
				success: function(data){
					$( '.simulator' ).html(data);
					dataS.varx = true;
					$.ajax({
						url:  Config.base_url()+'simulator/getSimulator.html',
						type: "POST",
//						data: { ramo: 'vida', userid: $( '#userid' ).val(), period: $( '#period' ).val(), year: $( '#year option:selected' ).val(), varx:true },
						data: dataS,
						cache: false,
						async: false,
						success: function(data){
							$( '#id' ).val(data);	
						}					
					});					
				}					
			});
			$.getScript(Config.base_url()+'simulator/assets/scripts/simulator_vida.js' )
			  .done(function( script, textStatus ) {
				console.log( textStatus );
			  })
			  .fail(function( jqxhr, settings, exception ) {
				alert( 'El script no se puede cargar' );
			});
			getMetasPeriod( 'vida' );
			$( '#periodo' ).bind( 'change', function(){		
				if( $( '#ramo' ).val() == 1 ) getMetasPeriod( 'vida' );
				if( $( '#ramo' ).val() == 2 ) getMetasPeriod( 'gmm' );
				if( $( '#ramo' ).val() == 3 ) getMetasPeriod( 'autos' );
			});
			$( '#primasAfectasInicialesUbicar' ).bind( 'keyup', function(){
				getMetas(); 
			});
			$( '#primasnetasiniciales' ).bind( 'keyup', function(){
				getMetas(); 
			});	
		}		
		if( this.id == 'gmm' ){		
			$( '#ramo' ).val(2);			
			$( '#gmm' ).css( 'color', '#06F' );			
			$( '#ramo' ).val( 2 );			
			$( '#periodo' ).val(4);
			dataS.ramo = 'gmm';
			$.ajax({
				url:  Config.base_url()+'simulator/getSimulator.html',
				type: "POST",
//				data: { ramo: 'gmm', userid: $( '#userid' ).val(), period: $( '#period' ).val(), year: $( '#year option:selected' ).val() },
				data: dataS,
				cache: false,
				async: false,
				success: function(data){
					$( '.simulator' ).html(data);
					dataS.varx = true;
					$.ajax({
						url:  Config.base_url()+'simulator/getSimulator.html',
						type: "POST",
//						data: { ramo: 'gmm', userid: $( '#userid' ).val(), period: $( '#period' ).val(), year: $( '#year option:selected' ).val(), varx:true },
						data: dataS,
						cache: false,
						async: false,
						success: function(data){
							$( '#id' ).val(data);	
						}					
					});		
				}					
			});
			$.getScript(Config.base_url()+'simulator/assets/scripts/simulator_gmm.js' )
			  .done(function( script, textStatus ) {
				console.log( textStatus );
			  })
			  .fail(function( jqxhr, settings, exception ) {
				alert( 'El script no se puede cargar' );
			});	
			$( '#periodo' ).bind( 'change', function(){		
				if( $( '#ramo' ).val() == 1 ) getMetasPeriod( 'vida' );
				if( $( '#ramo' ).val() == 2 ) getMetasPeriod( 'gmm' );
				if( $( '#ramo' ).val() == 3 ) getMetasPeriod( 'autos' );
			});
			$( '#primasAfectasInicialesUbicar' ).bind( 'keyup', function(){
				getMetas();
			});
			$( '#primasnetasiniciales' ).bind( 'keyup', function(){
				getMetas();
			});		
			getMetasPeriod( 'gmm' );
		}		
		if( this.id == 'autos' ){			
			$( '#ramo' ).val(3);			
			$( '#autos' ).css( 'color', '#06F' );			
			$( '#ramo' ).val( 3 );
			$( '#periodo' ).val(4);
			dataS.ramo = 'autos';
			$.ajax({
				url:  Config.base_url()+'simulator/getSimulator.html',
				type: "POST",
//				data: { ramo: 'autos', userid: $( '#userid' ).val(), period: $( '#period' ).val(), year: $( '#year option:selected' ).val() },
				data: dataS,
				cache: false,
				async: false,
				success: function(data){
					$( '.simulator' ).html(data);
					dataS.varx = true;
					$.ajax({
						url:  Config.base_url()+'simulator/getSimulator.html',
						type: "POST",
//						data: { ramo: 'autos', userid: $( '#userid' ).val(), period: $( '#period' ).val(), year: $( '#year option:selected' ).val(), varx:true },
						data: dataS,
						cache: false,
						async: false,
						success: function(data){
							$( '#id' ).val(data);	
						}					
					});		
				}					
			});
			$.getScript(Config.base_url()+'simulator/assets/scripts/simulator_autos.js' )
			  .done(function( script, textStatus ) {
				console.log( textStatus );
			  })
			  .fail(function( jqxhr, settings, exception ) {
				alert( 'El script no se puede cargar' );
			});
			getMetasPeriod( 'autos' );
			$( '#periodo' ).bind( 'change', function(){		
				if( $( '#ramo' ).val() == 1 ) getMetasPeriod( 'vida' );
				if( $( '#ramo' ).val() == 2 ) getMetasPeriod( 'gmm' );
				if( $( '#ramo' ).val() == 3 ) getMetasPeriod( 'autos' );
			});
			$( '#primasAfectasInicialesUbicar' ).bind( 'keyup', function(){
				getMetas();
			});
			$( '#primasnetasiniciales' ).bind( 'keyup', function(){
				getMetas();
			});		
		}
		//getMetasPeriod( this.id );
	});	
	
	$( '#periodo' ).bind( 'change', function(){		
		if( $( '#ramo' ).val() == 1 ) getMetasPeriod( 'vida' );
		if( $( '#ramo' ).val() == 2 ) getMetasPeriod( 'gmm' );
		if( $( '#ramo' ).val() == 3 ) getMetasPeriod( 'autos' );
	});		
	$( '#primasAfectasInicialesUbicar' ).bind( 'keyup', function(){
		getMetas();
	});
	$( '#primasnetasiniciales' ).bind( 'keyup', function(){
		getMetas();
	});	
		
	$( '.primas-meta-field' ).hide();
	
});


function getMetasPeriod( ramo ){
	var year = $( '#year' );
	if (year.length == 0)
		var dataS = { ramo: ramo, periodo: 12, userid: $( '#userid' ).val() };
	else
		var dataS = { ramo: ramo, period: $( '#period' ).val(), userid: $( '#userid' ).val(), year: $( '#year option:selected' ).val() };
	$.ajax({
			url:  Config.base_url()+'simulator/getConfigMeta.html',
			type: "POST",
			//data: { ramo: ramo, periodo: $( '#periodo' ).val(), userid: $( '#userid' ).val() },
//			data: { ramo: ramo, periodo: 12, userid: $( '#userid' ).val() },
			data: dataS,
			cache: false,
			async: false,
			success: function(data){
				//alert( data );
				$( '.metas' ).html(data);				
				$( '.primas-meta-field' ).hide();
				var field = '';
				if ($( '#primas_promedio' ).length > 0)
					field = '#primas_promedio';
				else if ($( '#primaspromedio' ).length > 0)
					field = '#primaspromedio';
				if (field.length)
					$( "#metas-prima-promedio" ).val( $( field ).val() ); 

/*				var i = $( '#primas_promedio' ).val();
					if( i == 0 || i > 0 )
						$( "#metas-prima-promedio" ).val( $( '#primas_promedio' ).val() ); 
					else 
						$( "#metas-prima-promedio" ).val( $( '#primaspromedio' ).val() ); */
				getMetas();
				if ($( '#primasAfectasInicialesUbicar' ).length > 0) {
					var i = $( '#primasAfectasInicialesUbicar' ).val();
					if( i == 0 || i > 0 )
						$( "#prima-total-anual" ).val( $( '#primasAfectasInicialesUbicar' ).val() ); 
					else if ($( '#primasnetasiniciales' ).length > 0)
						$( "#prima-total-anual" ).val( $( '#primasnetasiniciales' ).val() );
				}
				getMetas();
				$( document ).ready( function(){
					$( "#prima-total-anual" ).bind( 'keyup', function(){	
						$( '#primasAfectasInicialesUbicar' ).val(Math.ceil(this.value));	
						$( '#primasnetasiniciales' ).val(Math.ceil(this.value));
						updatePrimasMes();					
						getMetas();
					});	
					$( "#primasnetasiniciales" ).bind( 'keyup', function(){	
						$( '#prima-total-anual' ).val(Math.ceil(this.value));	
						updatePrimasMes();					
						getMetas();
					});	
					$( "#metas-prima-promedio" ).bind( 'keyup', function(){ 		
						$( '#primas_promedio' ).val(Math.ceil(this.value));	
						$( '#primaspromedio' ).val(Math.ceil(this.value));							
						getMetas();
					});	
					$( "#primas_promedio" ).bind( 'keyup', function(){ 		
						$( '#metas-prima-promedio' ).val(this.value);	
						getMetas();
					});	
					$( "#primaspromedio" ).bind( 'keyup', function(){ 		
						$( '#metas-prima-promedio' ).val(this.value);	
						getMetas();
					});	
					$( "#efectividad" ).bind( 'keyup', function(){ 		
						getMetas();
					});	
					$( '#open_simulator' ).bind( 'click', function(){		
						$( '.metas' ).hide();
						$( '.simulator' ).show();	
						//save();					
					});
					$( '#open_meta' ).bind( 'click', function(){		
						$( '.metas' ).show();
						$( '.simulator' ).hide();	
						//save();					
					});
					// Change the value for prima on the event click
					$( '.primas-meta' ).hide();					
					$( '.primas-meta-field' ).show();					
					$( '.primas-meta-selector' ).bind( 'click', function(){						
						$( '#'+this.id ).hide();						
						$( '#'+this.id+'-field' ).show();												
					});		
					$( '.primas-meta-field' ).bind( 'keypress', function(e){	
						var code = e.keyCode || e.which;							
						if(code == 13) { //Enter keycode

							var total=0;						
							for( var i=1; i<=12; i++ ){							
								if(  !isNaN( $( '#primas-meta-'+i ).val() ) ){
									$( '#primas-meta-text-'+i ).html( '$ '+moneyFormat(parseFloat($( '#primas-meta-'+i ).val())));
									total += parseFloat( $( '#primas-meta-'+i ).val());
								}
							}							
							$( '#primasAfectasInicialesUbicar' ).val( total );	
							$( '#primasnetasinicialeso' ).val( total );	
							$( '#prima-total-anual' ).val( total );		
							$( '.primas-meta-selector' ).show();						
							$( '.primas-meta' ).hide();						
							
							for( var i=1; i<=12; i++ ){				
/*								var primas = $( '#primasAfectasInicialesUbicar' ).val();
								if( !$( '#primasAfectasInicialesUbicar' ).val() )
									primas = $( '#primasnetasiniciales' ).val();
								var porcentaje =  Math.round((parseInt($( '#primas-meta-'+i ).val()) / parseFloat(primas)*10000)*100)/10000;	*/
								var porcentaje =  Math.round((parseInt($( '#primas-meta-'+i ).val()) / parseFloat(total)*10000)*100)/10000;								
								$( '#mes-'+i ).val(porcentaje);
								$( '#mes-text-'+i ).html(porcentaje);
							}	
							getMetas();	
							save();									
						}
					});
					$( '#periodo' ).bind( 'change', function(){		
						if( $( '#ramo' ).val() == 1 ) getMetasPeriod( 'vida' );
						if( $( '#ramo' ).val() == 2 ) getMetasPeriod( 'gmm' );
						if( $( '#ramo' ).val() == 3 ) getMetasPeriod( 'autos' );
					});
					$( '#primasAfectasInicialesUbicar' ).bind( 'keyup', function(){
						getMetas();
					});
					$( '#primasnetasiniciales' ).bind( 'keyup', function(){
						getMetas();
					});

					$( '#reset-meta' ).on( 'click', function(){
						if ( confirm( '¿Esta seguro que quiere borrar la meta?' ) ) {
							var data = {
								ramo: $( '#ramo' ).val(),
								period: $( '#period' ).val(),
								agent_id: $( '#agent_id' ).val(),
								year: $( '#year option:selected' ).val(),
								efectividad: $( '#efectividad' ).val(),
								prima_total_anual: $( '#prima-total-anual' ).val(),
								primas_promedio: $( '#metas-prima-promedio' ).val(),
							};
							var url = Config.base_url() + 'simulator/reset_meta.html';
							$.ajax({
								url: url,
								type: 'POST',
								data: data,
								dataType : 'json',
								success: function(response){
									switch (response) {
										case '-1':
											alert ('No se pudo borrar la meta. Informe a su administrador.');
											break;
										case '1':
											alert ('La meta esta borrada correctamente.');
											window.location.reload();
											break;
										default:
										case '0':
											alert ('Ocurrio un error, no se pudo borrar la meta. Consulte a su administrador.');
											break;
									}
								}
							});
						}
						return false;
					});
				
					$( '#save_meta' ).bind( 'click', function(){
						var promedio = $( '#metas-prima-promedio').val();
						if (!(/^\d+$/.test(promedio)) || (promedio == 0)) {					
							alert('El campo Prima Promedio debe contener un número más que 0.');
							return false;
						}
						var message = save();
						if (message.length)
							alert(message);
						else
							alert( "La meta se ha guardado correctamente" );
					});
				});
				getMetas();
			}
		});
}
function save(){
	var year = $( '#year' );
	$( '.metas' ).show();
	$( '.simulator' ).hide();
	var resultMessage = '';
	if (year.length == 0) {	// Old simulator
		var id = $( '#id' ).val();	
		var saves = parseInt( $( '#save' ).val() );		

		if( id == 0 ){	 	 	
			$.ajax({
				url:  Config.base_url()+'simulator/save.html',
				type: "POST",
				data: $( '#form' ).serialize(),
				cache: false,
				async: false,
				success: function(data){
					$( '#id' ).val(data);
				}			
			});
		}else{
			$.ajax({
				url:  Config.base_url()+'simulator/update.html',
				type: "POST",
				data: $( '#form' ).serialize(),
				cache: false,
				async: false,
				success: function(data){
					$( '#save' ).val(data);				
				}		
			});
		}
		alert( "La meta se ha guardado correctamente" );
	} else  { // New simulator
		$.ajax({
			url:  Config.base_url()+'simulator/save_meta_new.html',
			type: "POST",
			data: $( '#form' ).serialize(),
			async: false,
			success: function(data){
				if (data != '0') {
					resultMessage = 'La meta se ha guardado correctamente.';
					$( '#id' ).val(data);
				} else
					resultMessage = 'Ocurrio un error. Consulte a su administrador.';
			}
		});
	}
	return resultMessage;
}

function updatePrimasMes() {
	for(  var i = 1; i<=12; i++ ){
			$( '#primas-meta-'+i ).val(parseFloat( $( "#prima-total-anual" ).val() ) * (parseFloat( $( '#mes-'+i ) .val() ) /100));
			$( '#primas-meta-text-'+i ).val(parseFloat( $( "#prima-total-anual" ).val() ) * (parseFloat( $( '#mes-'+i ) .val() ) /100));
	}
}

function getMetas(){
		//if( $( '#primasAfectasInicialesUbicar' ).val() == 0 ) return false;	
		var primas = $( '#primasAfectasInicialesUbicar' ).val();
		if( !$( '#primasAfectasInicialesUbicar' ).val() )
			primas = $( '#primasnetasiniciales' ).val();
		
		// Metas
		var totalprimameta = 0;		
		var totalnegociometa = 0; 			
		var totalesnegociometa = 0;		
		var totalsolicitudmeta= 0;	
		var totalessolicitudmeta = 0;
		var totaltrimestre = 0;
		for(  var i = 1; i<=12; i++ ){						
			if ($( '#primas-meta-'+i ) .val() == "NaN" ) var total = parseFloat( primas ) * (parseFloat( $( '#mes-'+i ) .val() ) /100);
			else var total = parseFloat( $( '#primas-meta-'+i ) .val() );
			var meta =  total;//;Math.round( total* 100 )/100;			
			var primapromedio =  Math.round( ( total /  parseFloat( $( '#metas-prima-promedio' ).val() ) ) );
			var efectividad = $( '#efectividad' ) .val();	
			efectividad = efectividad.replace( '%', '' );
			efectividad =  parseInt( efectividad ) / 100;	
			var solicitud = primapromedio / efectividad ;		
			$( '#primas-meta-'+i ).val( Math.round(total) );
			$( '#primas-meta-text-'+i ).html( '$ ' + moneyFormat(Math.round(total)) );			
			// Negocios Meta
			$( '#primas-negocios-meta-'+i ).val( Math.round(primapromedio) );
			$( '#primas-negocios-meta-text-'+i ).html( Math.round(primapromedio) );			
			// Solicitud Meta
			$( '#primas-solicitud-meta-'+i ).val( Math.round(solicitud) );
			$( '#primas-solicitud-meta-text-'+i ).html( Math.round(solicitud));	
			if( !isNaN( meta ) )  totaltrimestre+=meta;
			if( !isNaN( meta ) ) totalprimameta+=meta;
			if( !isNaN( primapromedio ) ) 
				totalnegociometa += primapromedio;
			if( !isNaN( totalnegociometa ) )
				totalesnegociometa += primapromedio;
//			console.log(totalesnegociometa);
			if( !isNaN( solicitud ) )
				totalsolicitudmeta += solicitud; 
			if( !isNaN( totalsolicitudmeta ) )
				totalessolicitudmeta += solicitud;
			// Totales
			// $( '#ramo' ).val() == 1			
			if( i == 3 && $( '#ramo' ).val() == 1 ){														
				$( '#primas-meta-primer' ).val( Math.round(totaltrimestre) );
				$( '#primas-meta-primer-text' ).html( '$ ' + moneyFormat(Math.round(totaltrimestre)));
				$( '#simulatorprimasprimertrimestre' ).val( Math.round(totaltrimestre) );			
				//alert($( '#simulatorprimasprimertrimestre' ).val() );
				$( '#simulator-primas-primer-trimestre' ).html( '$ ' + moneyFormat(Math.round(totaltrimestre)));			
				$( '#primas-negocio-meta-primer' ).val( Math.round(totalnegociometa) );
				$( '#primas-negocio-meta-primer-text' ).html( Math.round(totalnegociometa) );				
				$( '#primas-solicitud-meta-primer' ).val( Math.round(totalsolicitudmeta) );
				$( '#primas-solicitud-meta-primer-text' ).html( Math.round(totalsolicitudmeta) );				
				totaltrimestre = 0;				
				totalnegociometa= 0;				
				totalsolicitudmeta= 0;				
			} 			
			if( i == 6 && $( '#ramo' ).val() == 1 ){													
				$( '#primas-meta-segund' ).val( Math.round(totaltrimestre) );
				$( '#primas-meta-segund-text' ).html( '$ ' + moneyFormat(Math.round(totaltrimestre)) );				
				$( '#simulatorprimassegundotrimestre' ).val( Math.round(totaltrimestre) );			
				$( '#simulator-primas-segundo-trimestre' ).html( '$ ' + moneyFormat(Math.round(totaltrimestre)));		
				$( '#primas-negocio-meta-segund' ).val( Math.round(totalnegociometa) );
				$( '#primas-negocio-meta-segund-text' ).html( Math.round(totalnegociometa) );				
				$( '#primas-solicitud-meta-segund' ).val( Math.round(totalsolicitudmeta) );
				$( '#primas-solicitud-meta-segund-text' ).html( Math.round(totalsolicitudmeta) );				
				totaltrimestre = 0;				
				totalnegociometa= 0;				
				totalsolicitudmeta= 0;				
			} 			
			if( i == 9 && $( '#ramo' ).val() == 1 ){				
				$( '#primas-meta-tercer' ).val( Math.round(totaltrimestre) );
				$( '#primas-meta-tercer-text' ).html( '$ ' + moneyFormat(Math.round(totaltrimestre)) );				
				$( '#simulatorprimastercertrimestre' ).val( Math.round(totaltrimestre) );			
				$( '#simulator-primas-tercer-trimestre' ).html( '$ ' + moneyFormat(Math.round(totaltrimestre)));		
				$( '#primas-negocio-meta-tercer' ).val( Math.round(totalnegociometa) );
				$( '#primas-negocio-meta-tercer-text' ).html( Math.round(totalnegociometa) );				
				$( '#primas-solicitud-meta-tercer' ).val( Math.round(totalsolicitudmeta) );
				$( '#primas-solicitud-meta-tercer-text' ).html( Math.round(totalsolicitudmeta) );				
				totaltrimestre = 0;				
				totalnegociometa= 0;				
				totalsolicitudmeta= 0;
			} 
			if( i == 12 && $( '#ramo' ).val() == 1 ){
				$( '#primas-meta-cuarto' ).val( Math.round(totaltrimestre) );
				$( '#primas-meta-cuarto-text' ).html('$ ' + moneyFormat( Math.round(totaltrimestre)) );				
				$( '#simulatorprimascuartotrimestre' ).val( Math.round(totaltrimestre) );			
				$( '#simulator-primas-cuarto-trimestre' ).html( '$ ' + moneyFormat(Math.round(totaltrimestre)));		
				$( '#primas-negocio-meta-cuarto' ).val( Math.round(totalnegociometa) );
				$( '#primas-negocio-meta-cuarto-text' ).html( Math.round(totalnegociometa) );				
				$( '#primas-solicitud-meta-cuarto' ).val( Math.round(totalsolicitudmeta) );
				$( '#primas-solicitud-meta-cuarto-text' ).html( (Math.round(totalsolicitudmeta)) );				
				totaltrimestre = 0;				
				totalnegociometa= 0;				
				totalsolicitudmeta= 0;				
			} 
			// $( '#ramo' ).val() == 1
			if( i == 4 && ( $( '#ramo' ).val() == 2 || $( '#ramo' ).val() == 3 ) ){	
				$( '#primas-meta-primer' ).val( Math.round(totaltrimestre) );
				$( '#primas-meta-primer-text' ).html( '$ ' + moneyFormat(Math.round(totaltrimestre)) );				
				$( '#simulatorPrimasPeriod\\[1\\]' ).val( Math.round(totaltrimestre));			
				$( '#simulatorPrimasPeriod_text\\[1\\]' ).html( '$ ' + moneyFormat(Math.round(totaltrimestre)));			
				$( '#primas-negocio-meta-primer' ).val( Math.round(totalnegociometa) );
				$( '#primas-negocio-meta-primer-text' ).html( Math.round(totalnegociometa) );				
				$( '#primas-solicitud-meta-primer' ).val( totalsolicitudmeta );
				$( '#primas-solicitud-meta-primer-text' ).html( (Math.round(totalsolicitudmeta)) );				
				totaltrimestre = 0;				
				totalnegociometa= 0;				
				totalsolicitudmeta= 0;				
			} 
			if( i == 8 && ( $( '#ramo' ).val() == 2 || $( '#ramo' ).val() == 3 ) ){														
				$( '#primas-meta-second' ).val( Math.round(totaltrimestre) );
				$( '#primas-meta-second-text' ).html( '$ ' + moneyFormat(Math.round(totaltrimestre)) );				
				$( '#simulatorPrimasPeriod\\[2\\]' ).val( Math.round(totaltrimestre));
				$( '#simulatorPrimasPeriod_text\\[2\\]' ).html( '$ ' + moneyFormat(Math.round(totaltrimestre)));			
				$( '#primas-negocio-meta-second' ).val( Math.round(totalnegociometa) );
				$( '#primas-negocio-meta-second-text' ).html( Math.round(totalnegociometa) );				
				$( '#primas-solicitud-meta-second' ).val( totalsolicitudmeta );
				$( '#primas-solicitud-meta-second-text' ).html( (Math.round(totalsolicitudmeta)) );				
				totaltrimestre = 0;				
				totalnegociometa= 0;				
				totalsolicitudmeta= 0;				
			} 			
			if( i == 12 && ( $( '#ramo' ).val() == 2 || $( '#ramo' ).val() == 3 ) ){														
				$( '#primas-meta-tercer' ).val( Math.round(totaltrimestre) );
				$( '#primas-meta-tercer-text' ).html('$ ' + moneyFormat( Math.round(totaltrimestre)) );				
				$( '#simulatorPrimasPeriod\\[3\\]' ).val( Math.round(totaltrimestre));
				$( '#simulatorPrimasPeriod_text\\[3\\]' ).html( '$ ' + moneyFormat(Math.round(totaltrimestre)));			
				$( '#primas-negocio-meta-tercer' ).val( Math.round(totalnegociometa) );
				$( '#primas-negocio-meta-tercer-text' ).html( Math.round(totalnegociometa) );				
				$( '#primas-solicitud-meta-tercer' ).val( totalsolicitudmeta );
				$( '#primas-solicitud-meta-tercer-text' ).html( (Math.round(totalsolicitudmeta)) );				
				totaltrimestre = 0;				
				totalnegociometa= 0;				
				totalsolicitudmeta= 0;				
			} 
		}		
		//$( '#prima-total-anual' ).val(Math.round(totalprimameta) );
		$( '#primas-meta-total' ).val(Math.round(totalprimameta) );
		$( '#primas-meta-total-text' ).html( '$ ' + moneyFormat( Math.round(totalprimameta)) );
		
		$( '#primas-negocios-meta-total' ).val( Math.round(totalesnegociometa) );
		$( '#primas-negocios-meta-total-text' ).html( Math.round(totalesnegociometa) );
		
		$( '#primas-solicitud-meta-total' ).val( Math.round(totalessolicitudmeta) );
		$( '#primas-solicitud-meta-total-text' ).html( (Math.round(totalessolicitudmeta)) );
		
		if (($( '#year' ).length == 0 ) && ($( '#ramo' ).val() == 2)) {
			clickPrimasPromedio();
			clickPrimasIniciales();
			clickPorSiniestridad();
			clickPrimasRenovacion();
			clickXAcotamiento();
			calculoBonoPrimerAnio();
			clickComisionVentaInicial();
			clickComisionVentaRenovacion();
			gmm_ingresototal();
			gmm_ingresopromedio();
		}
}

