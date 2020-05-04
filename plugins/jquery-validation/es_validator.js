// JavaScript Document
$(document).ready(function(){
        
   jQuery.extend(jQuery.validator.messages, {
	  
	  required: 'Requerido',
	  email: 'Correo invalido',
	  equalTo: 'Ingresa los mismos valores.',
	  number : 'Este campo tiene que ser un valor númerico',
	  minlength: 'Este campo tiene que tener minimo 8 caracteres',
	  max: 'Los campos deben de sumar un total de 100%'
	  
	});
    
});
