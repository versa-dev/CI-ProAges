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
function hide( id ){
	
	//$( '#select'+id ).hide();
	//$( '.column'+id ).hide();
}

$( document ).ready(function() {
    
	$( '#create-users-form-csv' ).validate({
		
		 submitHandler: function(form) {
			// some other code
			// maybe disabling submit button
			// then: actions-buttons-forms-send
			$( '#actions-buttons-forms-send' ).html( '<img src="'+Config.base_url()+'images/ajax-loaders/ajax-loader-5.gif">' );
		  				
			form.submit();
		  }		
		
	});
	
});
    		
	