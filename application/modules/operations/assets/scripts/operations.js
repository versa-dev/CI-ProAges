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

	var getLinks = function() {
		$.ajax({
			url:  Config.base_url() + "operations/get_links.html",
			type: "POST",
			data: $("#coordinador-name").serializeArray(),
			dataType: "json",
			success: function(data){
				if (data.length == 2) {
					$("#ot-link").attr("href", data[0]);
					$("#stats-link").attr("href", data[1]);
				}
			}
		});
	}

