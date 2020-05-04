<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
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

<span title="Escriba el nombre del coordinador que desea buscar y selecciónelo de la lista que aparece. Puede buscar más posteriormente en la siguiente línea.">
    <textarea placeholder="COORDINADORES" id="coordinador-name" name="coordinator_name" rows="1" class="input-xlarge select4" style="min-width: 250px; height: 2.2em"><?php echo $selected_coordinator_text; ?></textarea>
    &nbsp;<i style="cursor: pointer;" class="icon-filter submit-form" id="submit-form1" title="Filtrar"></i>
    &nbsp;<i style="cursor: pointer;" class="icon-list-alt" id="clear-coordinator-filter" title="Mostrar todos los coordinadores"></i>
</span>


