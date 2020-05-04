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
    
	$( '.input' ).hide();
			
	
	$( '.value' ).bind( 'click', function(){
						
		$( '#'+this.id ).hide();
			
		$( '#value-input-'+this.id ).show();
					
	});
	
		
	
});

function save( item, value ){
	
	$( '#'+item ).html(value);
	
	$( '#'+item ).show();
			
	$( '#value-input-'+item ).hide();
	
	
		 	 	
	$.ajax({

		  url:  Config.base_url()+'simulator/configSave.html',
		  type: "POST",
		  data: { item: item, value: value },
		  cache: false,
		  async: false,
		  success: function(data){
			
		  }						
  
	  });
		
	
	
	
}