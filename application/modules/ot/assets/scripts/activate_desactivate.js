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
			
			
			// Set Date
			var toDay = new Date();
			var year = toDay.getFullYear();
			var month = toDay.getMonth()+1;
				if( month < 10 )
					month='0'+month;
			var day = toDay.getDate();
				if( day < 10 )
					day='0'+day;
			var seconds = toDay.getSeconds();
			var minutes = toDay.getMinutes();
			var hour = toDay.getHours();
			
			
			
			toDay = year+'-'+month+'-'+day+' '+hour+':'+minutes+':'+seconds;
			
			$( '#creation_date' ).val( toDay);	
			
			
			form.submit();
		  }		
		
	});
		
	
});