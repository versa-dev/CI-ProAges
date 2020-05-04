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
	
	$( '.header_manager' ).bind( 'click', function(){ 
		
		$( '.header_manager' ).css({'color': '#000', 'font-weight':'bold'} );
		
		$( '#'+this.id ).css({'color': '#06F', 'font-weight':'bold'});
		
	});
	
	var startDate;
    var endDate;
    var defaultDate = '';
    var beginField = $('#begin').val();
    var endField = $('#end').val();
    if ( ( beginField.length > 0 ) && ( endField.length > 0 ) ) {
        var beginParts = beginField.split('-', 3);
        var endParts = endField.split('-', 3);		
        if ( ( beginParts.length == 3 ) && ( endParts.length == 3) ) {
            defaultDate = beginParts[1].substr(0, 2) + '/' + beginParts[2].substr(0, 2) + '/' + beginParts[0].substr(2, 2);
            startDate = new Date(beginParts[0] + '/' + beginParts[1] + '/' +  beginParts[2]);
            endDate = new Date(endParts[0] + '/' + endParts[1] + '/' + endParts[2]);
        }
    }
	
    var selectCurrentWeek = function() {
        window.setTimeout(function () {
            $('#week').find('.ui-datepicker-current-day a').addClass('ui-state-active')
        }, 1);
    }

    $('#week').datepicker( {
        defaultDate: defaultDate,
        showOtherMonths: true,
        selectOtherMonths: true,
		firstDay:1,
		closeText: 'Cerrar',
		prevText: '&#x3c;Ant',
		nextText: 'Sig&#x3e;',
		currentText: 'Hoy',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
		'Jul','Ago','Sep','Oct','Nov','Dic'],
		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
		dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
        onSelect: function(dateText, inst) { 
            var date = $(this).datepicker('getDate');
            startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay()+1);
            endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay()+7);
            var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
            //$('#startDate').text($.datepicker.formatDate( dateFormat, startDate, inst.settings ));
            //$('#endDate').text($.datepicker.formatDate( dateFormat, endDate, inst.settings ));
            //alert( selectedWeek = $.datepicker.iso8601Week(new Date(dateText)));
			$('#startDate').text( month(startDate.getMonth()) + ' ' +  startDate.getDate() +', ' + startDate.getFullYear());
			
			$('#endDate').text( ' - '+  month(endDate.getMonth()) + ' ' +  endDate.getDate() +', ' + endDate.getFullYear() );
			
			selectCurrentWeek();
			
			var Month = startDate.getMonth()+1;
			
			if( Month < 10 ) Month = '0'+Month;
													
			$( '#begin' ).val(  startDate.getFullYear() +'-'+ Month +'-'+ startDate.getDate() );
			
			var Month = endDate.getMonth()+1;
			
			if( Month < 10 ) Month = '0'+Month;
													
			$( '#end' ).val(  endDate.getFullYear() +'-'+ Month +'-'+ endDate.getDate() );

			var parentForm = $(this).parents("form");
			var parentFormId = parentForm.attr('id');
			if ((parentFormId == 'activity-report-form') || (parentFormId == 'sales-activity-form')) {
				parentForm.submit();
			}
        },
        beforeShowDay: function(date) {
            var cssClass = '';
            if(date >= startDate && date <= endDate)
                cssClass = 'ui-datepicker-current-day';
            return [true, cssClass];
        },
        onChangeMonthYear: function(year, month, inst) {
            selectCurrentWeek();
        }
    });
    selectCurrentWeek();
    $("#sorter").tablesorter(); 
    
// The 2 lines below is causing js error:
//    $('#week .ui-datepicker-calendar tr').live('mousemove', function() { $(this).find('td a').addClass('ui-state-hover'); });
//    $('#week .ui-datepicker-calendar tr').live('mouseleave', function() { $(this).find('td a').removeClass('ui-state-hover'); });

	$("#period_opt4").on( "click", function( event ) {
		$( "#cust_period-form" ).dialog( "open" );
	});
	
	$("#periodo").on( "change", function( event ) {
		var parentForm = $(this).parents("form");
		var optionSelected = 0;
		$("#periodo option:selected").each(function () {
			$(".activity_results").hide();
			optionSelected = $(this).val();
			$("#periodo-export").val(optionSelected);
			if (optionSelected == 2) {
				$("#semana-container").show();
				parentForm.submit();
			} else {
				$("#semana-container").hide();
				if ((optionSelected == 1) || (optionSelected == 3)) {
					parentForm.submit();
				} else if (optionSelected == 4) {
					$( "#cust_period-form" ).dialog( "open" );
				}
			}
			return false;
		});
	});

});

function month( month ){
	
	var monthNames = [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
    "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ];
	return monthNames[month];
	
}