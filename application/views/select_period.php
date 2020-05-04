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
<div id="selected-ramo" style="display: none"><?php echo $ramo ?></div>
<div style="display: none" id="cust_period-form" title="Selecciona el período">
    <div style="width: 40%; float: right; font-weight: bold" id="periodo-links"><br />
    Selecciona un período:<br />
        <a href="javascript: void(0);" tabindex="1" id="month-select">Mes actual</a><br />
<?php if ($ramo <= 3) : ?>
        <a href="javascript: void(0);" tabindex="2" id="tri-cuatri-select"><?php if ($ramo == 1) echo 'Trimestre'; else echo 'Cuatrimestre'; ?> actual</a><br />
        <a href="javascript: void(0);" tabindex="8" id="lastTrimester"><?php if ($ramo == 1) echo 'Trimestre'; else echo 'Cuatrimestre'; ?> Anterior</a><br />
<?php else : ?>
        <a href="javascript: void(0);" tabindex="6" id="tri-select">Trimestre actual</a><br />
        <a href="javascript: void(0);" tabindex="7" id="cuatri-select">Cuatrimestre actual</a><br />
<?php endif; ?>
        <a href="javascript: void(0);" tabindex="3" id="year-select">Año actual</a><br />
        <a href="javascript: void(0);" tabindex="5" id="week-select">Selecciona una semana &gt;</a><br />
    </div>
    <div style="width: 60%;">
	    <label for="cust_period_from">Desde</label>
       	<input tabindex="20" class="input-small" type="text" id="cust_period_from" name="cust_period_from" value="<?php echo $from ?>" />
	    <label for="cust_period_to">Hasta</label>
	    <input tabindex="11" class="input-small" type="text" id="cust_period_to" name="cust_period_to" value="<?php echo $to ?>" />
	    <input type="hidden" id="filter_for" name="filter_for" value="<?php echo $filter_for ?>" />
    </div>
    <div style="clear: both"></div>
    <div id="semana-container" title="Selecciona una Semana">
        <div id="week"></div>
        <label></label> <span id="startDate"></span>  <span id="endDate"></span>
        <input id="begin" name="begin" type="hidden" readonly="readonly" value="<?php echo $begin ?>" />
        <input id="end" name="end" type="hidden" readonly="readonly" value="<?php echo $end ?>" />
    </div>
</div>
