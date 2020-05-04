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
<form id="cust_period-form" title="Editar el período personalizado" method="post">
	<label for="cust_period_from">Desde</label>
	<input type="text" id="cust_period_from" name="cust_period_from" value="<?php echo $from ?>" />
	<label for="cust_period_to">Hasta</label>
	<input type="text" id="cust_period_to" name="cust_period_to" value="<?php echo $to ?>" />
	<input type="hidden" id="filter_for" name="filter_for" value="<?php echo (string) $this->period_filter_for; ?>" />
</form>
