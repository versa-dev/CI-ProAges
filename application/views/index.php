<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php 
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
?>
<?php 
// Getting include files
$header = array();
if( isset( $title ) ) $header['title'] = $title;   
if( isset( $css ) ) $header['css'] = $css;
?>

<?php 
// Render Header
$this->load->view( 'admin/header', $header ); ?>



<?php
/**
 * Config array vars and call template 
 */		
// Render content
	$this->load->view( $content );
	

?>



		  
<?php 
// Getting include file for footer
$footer = array(); 
if( isset( $cssscripts) ) $footer['scripts'] = $scripts;
?>


<?php 
// Render Footer
$this->load->view( 'admin/footer', $footer ); ?>      

