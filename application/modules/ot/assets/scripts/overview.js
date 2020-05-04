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
/*
function menu( item ){
  $( '#'+item ).show();
  proagesOt.menuRowShown[item] = item;
}*/
var proagesOverview = {};

$( document ).ready( function(){

	proagesOverview.getOts = function(Data) {
		$.ajax({
			url:  Config.base_url() + Config.findUrl,
			type: "POST",
			data: Data,
			cache: false,
			//dataType: 'json',
			beforeSend: function(){
				$( '#loading' ).html( '<img src="'+Config.base_url()+'images/ajax-loaders/ajax-loader-1.gif">   Cargando...' );
			},
			success: function(data){
				$( '#loading' ).html( '' );	
				$( '#data' ).html( data );
				var resort = true;
				$("#sorter").trigger("update", [resort]);
				$(".tablesorter-childRow td").hide();	
			}
		});
	}
	
	proagesOverview.triCuatrimestre = function(selectedRamo) {
		var triCuatri = '';
		switch ( selectedRamo ) {
			case '1': // Vida
				triCuatri = 'Trimestre';
				break;
			case '2': // GMM
			case '3': // Autos
				triCuatri = 'Cuatrimestre';
				break;
			default:
				triCuatri = 'Trimestre';
				selectedRamo = 0;
				break;
		}
		$( '.set_periodo' ).html(triCuatri );
		$( '#periodo option' ).each(function () {
			if ($(this).val() == '2') {
				$(this).html(triCuatri);
				return false;
			}
		});
	}

	$( '.filter-field').bind( 'change', function(){
		if ( this.id == 'ramo' ) {
			var selectedRamo = $(this).val();
			var currentTramiteType = '';
			$( '#patent-type option:selected' ).each(function () {
				currentTramiteType = $(this).val();
				return false;
			});
			proagesOverview.triCuatrimestre(selectedRamo);
			if (selectedRamo.length)
				$( '#patent-type').html(proagesOverview.tramiteTypes[selectedRamo]);
			else
				$( '#patent-type').html(proagesOverview.tramiteTypes[0]);
			if ((selectedRamo == 2) || (selectedRamo == 3)) {
				$("#tri-cuatri-select").html("Cuatrimestre actual");
				$("#selected-ramo").html("2");
			} else {
				$("#tri-cuatri-select").html("Trimestre actual");
				$("#selected-ramo").html("1");
			}
			$( '#patent-type option' ).each(function () {
				if ( $(this).val() == currentTramiteType ) {
					$(this).prop('selected', true);
					return false;
				}
			});
		}
		var Data = $( "#ot-form").serialize();
		proagesOverview.getOts(Data);
	});
  
	$( '.find' ).bind( 'click', function(){

		var currentUser = $( '#todas-mias').val();
		if ( currentUser != (this.id) ) {
			// Reset Color
			$( '.find' ).removeClass( 'btn btn-primary' );
			$(this).addClass( 'btn btn-link' );
			$( '.find' ).css( 'margin-left', 15 ) ;
			// Set Color
			$(this).addClass( 'btn-primary' );
			$(this).removeClass( 'btn-link' );

			$( '#todas-mias').val( this.id );

			var Data = $( "#ot-form").serialize();
			proagesOverview.getOts(Data);
		}
	});

	$( '#ot-form').submit( function () {
		if ($("#export-xls-input").val() == "export_xls") {
			$("#export-xls-input").val("");
		} else {
			proagesOverview.getOts($( "#ot-form").serialize());
			return false;
		}
	});

	// Filters
	$( '.hide' ).hide();
	proagesOverview.getOts($( "#ot-form").serialize());

});
function chooseOption( choose, is_new ){
	
	var choose = choose.split('-');
		
		if( choose[0] == 'activar' )
			window.location=Config.base_url()+"ot/activar/"+choose[1]+".html";
		if( choose[0] == 'desactivar' ) {
			var sendNotification = (confirm( "¿Con notificacion por correo electrónico?" ) ) ? "1": "0";
			window.location=Config.base_url()+"ot/desactivar/"+choose[1]+ '/' + sendNotification + ".html";
		}
		if( choose[0] == 'aceptar' ){
			if( confirm( '¿Seguro quiere marcar como aceptada?' ) ){
				if( is_new == true ){
					var poliza=prompt("Ingresa un número de poliza","");	
					if (( poliza!=null ) && (poliza.length > 0)){
						var pago=confirm("¿Quiere marcar la Póliza como pagada?");
						var sendNotification = (confirm( "¿Con notificacion por correo electrónico?" ) ) ? "1": "0";
						window.location=Config.base_url()+"ot/aceptar/"+choose[1]+ "/" + sendNotification +"/"+poliza+"/"+pago+".html";
					}
				}else{
					var sendNotification = (confirm( "¿Con notificacion por correo electrónico?" ) ) ? "1": "0";
					window.location=Config.base_url()+"ot/aceptar/"+choose[1]+ "/" + sendNotification + ".html";	
				}
			}
		}
		if( choose[0] == 'rechazar' ) {
			window.location=Config.base_url()+"ot/rechazar/"+choose[1]+".html";
		}
		if( choose[0] == 'cancelar' )
			window.location=Config.base_url()+"ot/cancelar/"+choose[1]+".html";
}

function setPay( id ){
	if( confirm( "¿Está seguro que quiere marcar la OT como pagada?" ) ){
		var sendNotification = (confirm( "¿Con notificacion por correo electrónico?" ) ) ? "1": "0";
		var Data = { id: id, notification: sendNotification };		
		$.ajax({

			url:  Config.base_url()+'ot/setPay.html',
			type: "POST",
			data: Data,
			cache: true,
			async: false,
			success: function(data){
				alert(data);
				window.location=Config.base_url()+"ot.html";
			}						
	
		});
	}	
}