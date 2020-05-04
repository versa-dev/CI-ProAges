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
$this->load->helper('simulator/simulator');
$period_labels = array(	1 => 'er', 2 => 'o', 3 => 'er', 4 => 'o');
if ($ramo == 'vida')
{
	$period_name = 'trimestre';
	$period_max = 4;
}
else
{
	$period_name = 'cuatrimestre';
	$period_max = 3;
}
?>
<div class="row-fluid" style="margin: -1em 0">
  <input type="button" id="save-simulator" value="Guardar Simulator" class="pull-right btn-save-meta screen-view" />
</div> 
<input type="hidden" name="periodo" id="periodo" value="12" />

<div class="row-fluid" id="global">
  <div class="span6 offset6 screen-view">
      <label class="checkbox inline smaller">
        <input id="considerar-meta" type="checkbox"> Considerar Meta (habilitar o deshabilitar)
     </label>
  </div>   <!-- END right column -->
</div>

<div id="tabs">
    <ul id="tabs-ul" class="screen-view">
<?php for ($i = 1; $i <= $period_max; $i++): ?>
      <li><a href="#<?php echo $period_name ?>-<?php echo $i; ?>"><?php echo $i . $period_labels[$i] . ' ' . ucfirst($period_name); ?></a></li>
<?php endfor; ?>
      <li><a href="#anual">Anual</a></li>
    </ul>
<?php
for ($i = 1; $i <= $period_max; $i++)
	$simulator_prima_fields[$i] = 'simulatorPrimasPeriod_' . $i;

$meta_prima_fields = array(
	1 => 'primas-meta-primer',
	2 => 'primas-meta-segund',
	3 => 'primas-meta-tercer',
	4 => 'primas-meta-cuarto');

// workaround for fields having non homogenuous field names for GMM and Vida:
if (isset($meta_data->{'primas-meta-second'}) && isset($meta_data->{'primas-meta-segund'}))
{
	$meta_data->{'primas-meta-segund'} = max( $meta_data->{'primas-meta-second'}, $meta_data->{'primas-meta-segund'});
	$meta_data->{'primas-meta-second'} = $meta_data->{'primas-meta-segund'};
}

$prima_values = array();
$prima_renovacion_values = array();
$negocio_values = array();
$percent_conservacion_values = array();
$percent_siniestrad_values = array();
$ingreso_comm_venta_inicial = array('total' => 0);
$ingreso_comm_renovacion = array('total' => 0);
$ingreso_bono_venta_inicial = array('total' => 0);
$ingreso_bono_renovacion = array('total' => 0);

$bono_venta_inicial = array();
$bono_renovacion = array();
$percent_bono_venta_inicial = array();
$percent_bono_renovacion = array();

$meta_ori = array('prima' => array(), 'negocios' => array());
$promedio = isset($meta_data->primas_promedio) ? $meta_data->primas_promedio : 0;
for ($i = 1; $i <= $period_max; $i++) :
	$meta_ori['prima'][$i] = '';
	$meta_ori['negocios'][$i] = '';
	if (isset($meta_data->{$meta_prima_fields[$i]}))
	{
		$meta_ori['prima'][$i] = $meta_data->{$meta_prima_fields[$i]};
		if ($promedio != 0)
			$meta_ori['negocios'][$i] = ceil($meta_ori['prima'][$i] / $promedio);
	}
	if (isset($data->{$simulator_prima_fields[$i]}))
		$prima_values[$i] = $data->{$simulator_prima_fields[$i]};
	elseif (isset($data->{$meta_prima_fields[$i]}))
		$prima_values[$i] = $data->{$meta_prima_fields[$i]};
	else
		$prima_values[$i] = 0;

	if (isset( $data->{'primasRenovacion_' . $i}))
		$prima_renovacion_values[$i] = $data->{'primasRenovacion_' . $i};
	else
		$prima_renovacion_values[$i] = 0;

	if (isset( $data->{'noNegocios_' . $i}))
		$negocio_values[$i] = $data->{'noNegocios_' . $i};
	else
		$negocio_values[$i] = 0;

	if ( isset( $data->{'porcentajeConservacion_' . $i} ))
		$percent_conservacion_values[$i] = $data->{'porcentajeConservacion_' . $i};
	else
		$percent_conservacion_values[$i] = 0;

	if ( isset( $data->{'porsiniestridad_' . $i} ))
		$percent_siniestrad_values[$i] = $data->{'porsiniestridad_' . $i};
	else
		$percent_siniestrad_values[$i] = 0;

	if ( isset( $data->{'XAcotamiento_' . $i} ) ) 
	{
		$bono_venta_inicial[$i] = $data->{'XAcotamiento_' . $i} * $prima_values[$i] / 100;
		$bono_renovacion[$i] = $data->{'XAcotamiento_' . $i} * $prima_renovacion_values[$i] / 100;
	}
	else
	{
		$bono_venta_inicial[$i] = 0;
		$bono_renovacion[$i] = 0;
	}
	if ($ramo == 'vida')
	{
//		$percent_bono_venta_inicial[$i] = get_inicial_gmm_percent($prima_values[$i]);
		$percent_bono_venta_inicial[$i] = calc_perc_bono_aplicado($prima_values[$i], $negocio_values[$i], $percent_conservacion_values[$i]);
//		$percent_bono_renovacion[$i] = calc_perc_conservacion($percent_conservacion_values[$i], $prima_renovacion_values[$i]);
		$percent_bono_renovacion[$i] = calc_perc_conservacion($percent_conservacion_values[$i], $prima_values[$i]);
	}
	else
	{
		$percent_bono_venta_inicial[$i] = get_inicial_gmm_percent($prima_values[$i], $negocio_values[$i], $percent_conservacion_values[$i]);
//		$percent_bono_renovacion[$i] = get_renovacion_gmm_percent($prima_renovacion_values[$i], $percent_siniestrad_values[$i]);
		$percent_bono_renovacion[$i] = get_renovacion_gmm_percent($prima_values[$i], $percent_siniestrad_values[$i]);
	}
?>
<div class="<?php echo $period_name ?>" id="<?php echo $period_name . '-' . $i?>">
<div class="row-fluid">
  <div class="span6 left-column">
    <h4 class="print-view"><br /><?php echo $i . $period_labels[$i] . ' ' . ucfirst($period_name); ?></h4>
  </div>   <!-- END left column -->
  <div class="span6 right-column">
      <div class="row-fluid">
        <div class="span3 smaller">% de acotamiento:</div>
        <div class="span3">
          <input type="text" class="span12 smaller XAcotamiento" name="XAcotamiento_<?php echo $i ?>" id="XAcotamiento_<?php echo $i ?>" value="<?php if( isset( $data->{'XAcotamiento_' . $i} ) ) echo $data->{'XAcotamiento_' . $i}; else echo 80; ?>" />
        </div>
        <div class="span6">
        </div>
      </div> 
  </div>   <!-- END right column -->
</div>
<div class="row-fluid">
  <div class="span6 left-column">
      <h3 class="row-fluid subtitle subtitle-yellow">INFORMACIÓN DE VENTA INICIAL</h3>

      <div class="row-fluid">
        <div class="span3 smaller">Primas afectas iniciales:</div>
        <div class="span3">
          <span style="display: none" class="meta-ori" id="meta-prima-ori-<?php echo $i ?>"><?php echo $meta_ori['prima'][$i] ?></span>
          <input type="text" class="span12 smaller simulator-primas-period" name="<?php echo $simulator_prima_fields[$i] ?>" id="<?php echo $simulator_prima_fields[$i] ?>" value="<?php echo $prima_values[$i]; ?>">
        </div>
        <div class="span3 smaller" id="span_negocios">
          <?php if ($ramo == 'vida'): ?>
            No. de Negocios PAI:
          <?php elseif($ramo == 'gmm'): ?>
            Nuevos Negocios
          <?php endif ?>  
        </div>
        <div class="span3">
<?php if ($ramo == 'vida'): ?>
	       <span style="display: none" class="meta-ori" id="meta-negocio-ori-<?php echo $i ?>"><?php echo $meta_ori['negocios'][$i] ?></span>
           <input type="text" class="span12 smaller noNegocios" name="noNegocios_<?php echo $i ?>" id="noNegocios_<?php echo $i ?>" value="<?php echo $negocio_values[$i]; ?>">
<?php elseif($ramo == 'gmm'): ?>
          <span style="display: none" class="meta-ori" id="meta-negocio-ori-<?php echo $i ?>"><?php echo $meta_ori['negocios'][$i] ?></span>
           <input type="text" class="span12 smaller noNegocios" name="noNegocios_<?php echo $i ?>" id="noNegocios_<?php echo $i ?>" value="<?php echo $negocio_values[$i]; ?>">
<?php endif ?>
        </div>
      </div>
	  
	  <div class="row-fluid">
        <div class="span3 smaller">Primas para pagar (Bono):</div>
        <div class="span3">
          <span class="smaller" style="color:#547EBD !important; float:right" id="primasAfectasInicialesPagar_text_<?php echo $i ?>">$ <?php echo number_format($bono_venta_inicial[$i], 2); ?></span>	
          <input type="hidden" disabled="disabled" name="primasAfectasInicialesPagar_<?php echo $i ?>" id="primasAfectasInicialesPagar_<?php echo $i ?>" value="<?php echo $bono_venta_inicial[$i]; ?>">
        </div>
        <div class="span3 smaller">% de Comisión:</div>
        <div class="span3">
           <input type="text" class="span12 smaller comisionVentaInicial" name="comisionVentaInicial_<?php echo $i; ?>" id="comisionVentaInicial_<?php echo $i; ?>" value="<?php if( isset( $data->{'comisionVentaInicial_' . $i} ) ) echo $data->{'comisionVentaInicial_' . $i}; else echo 0; ?>">
        </div>
      </div>

  </div>   <!-- END left column -->
  <div class="span6 right-column">
      <h3 class="row-fluid subtitle subtitle-orange">INFORMACIÓN DE CARTERA DE RENOVACIÓN</h3>

      <div class="row-fluid">
        <div class="span3 smaller">Primas de renovación:</div>
        <div class="span3">
           <input type="text" class="span12 smaller primasRenovacion" name="primasRenovacion_<?php echo $i ?>" id="primasRenovacion_<?php echo $i ?>" value="<?php echo $prima_renovacion_values[$i]; ?>">
        </div>
        <div class="span3 smaller">% <?php if ($ramo == 'vida'): ?>Conservación<?php else: ?>Siniestralidad<?php endif; ?>:</div>
        <div class="span3">
<?php if ($ramo == 'vida'): ?>
		  <select name="porcentajeConservacion_<?php echo $i; ?>" id="porcentajeConservacion_<?php echo $i; ?>" class="span12 smaller conservacion-percent">
<!--            <option value="0" <?php if ($percent_conservacion_values[$i] == 0 ) echo 'selected="selected"'; ?>>Sin base</option>
             <option value="m89" <?php if ($percent_conservacion_values[$i] == "m89" ) echo 'selected="selected"'; ?>>&lt;89%</option>
             <option value="89" <?php if ($percent_conservacion_values[$i] == 89 ) echo 'selected="selected"'; ?>>89%</option>-->
             <option value="90" <?php if ($percent_conservacion_values[$i] == 90 ) echo 'selected="selected"'; ?>>90%</option>
             <option value="93" <?php if ($percent_conservacion_values[$i] == 93 ) echo 'selected="selected"'; ?>>93%</option>
             <option value="95" <?php if ($percent_conservacion_values[$i] == 95 ) echo 'selected="selected"'; ?>>95%</option>
          </select>
<?php else: ?>
		  <select name="porsiniestridad_<?php echo $i; ?>" id="porsiniestridad_<?php echo $i; ?>" class="span12 smaller siniestridad-percent">
             <option value="">Seleccione</option>
             <option value="66" <?php if ($percent_siniestrad_values[$i] == 66 ) echo 'selected="selected"'; ?>>&lt;= 66</option>
             <option value="62" <?php if ($percent_siniestrad_values[$i] == 62 ) echo 'selected="selected"'; ?>>&lt;= 62</option>
             <option value="58" <?php if ($percent_siniestrad_values[$i] == 58 ) echo 'selected="selected"'; ?>>&lt;= 58</option>
          </select>
<?php endif ?>
        </div>
      </div>

	  <div class="row-fluid">
        <div class="span3 smaller">Primas para pagar (Bono):</div>
        <div class="span3">
           <span class="smaller" style="color:#547EBD !important; float:right" id="primasRenovacionPagar_text_<?php echo $i ?>">$ <?php echo number_format($bono_renovacion[$i], 2); ?></span>	
           <input type="hidden" disabled="disabled" name="primasRenovacionPagar_<?php echo $i ?>" id="primasRenovacionPagar_<?php echo $i ?>" value="<?php echo $bono_renovacion[$i]; ?>">
        </div>
        <div class="span3 smaller">% de Comisión:</div>
        <div class="span3">
           <input type="text" class="span12 smaller comisionVentaRenovacion" name="comisionVentaRenovacion_<?php echo $i; ?>" id="comisionVentaRenovacion_<?php echo $i; ?>" value="<?php if( isset( $data->{'comisionVentaRenovacion_' . $i} ) ) echo $data->{'comisionVentaRenovacion_' . $i}; else echo 0; ?>">
        </div>
      </div>

  </div>   <!-- END right column -->
</div>

<div class="row-fluid subsubtitle">
COMISIONES
</div>

<div class="row-fluid">
<?php
$ingreso_comm_venta_inicial[$i] = 0;
$ingreso_comm_renovacion[$i] = 0;
if (isset($data->{'comisionVentaInicial_' . $i}))
	$ingreso_comm_venta_inicial[$i] = $data->{'comisionVentaInicial_' . $i} * $prima_values[$i] / 100;
if (isset($data->{'comisionVentaRenovacion_' . $i}) && isset($data->{'primasRenovacion_' . $i}))
	$ingreso_comm_renovacion[$i] = $data->{'comisionVentaRenovacion_' . $i} * $data->{'primasRenovacion_' . $i} / 100;
?>
  <div class="span6 left-column">
    <span style="color:#547EBD !important; float:right" id="ingresoComisionesVentaInicial_text_<?php echo $i; ?>">$ <?php echo number_format($ingreso_comm_venta_inicial[$i], 2); ?></span>	
    <input type="hidden" disabled="disabled" name="ingresoComisionesVentaInicial_<?php echo $i; ?>" id="ingresoComisionesVentaInicial_<?php echo $i; ?>" value="<?php echo $ingreso_comm_venta_inicial[$i]; ?>">
  </div>   <!-- END left column -->
 
  <div class="span6 right-column">
    <span style="color:#547EBD !important; float:right" id="ingresoComisionRenovacion_text_<?php echo $i; ?>">$ <?php echo number_format($ingreso_comm_renovacion[$i], 2); ?></span>	
    <input type="hidden" disabled="disabled" name="ingresoComisionRenovacion_<?php echo $i; ?>" id="ingresoComisionRenovacion_<?php echo $i; ?>" value="<?php echo $ingreso_comm_renovacion[$i]; ?>">
  </div>   <!-- END right column -->
</div>

<div class="row-fluid subsubtitle">
BONOS
</div>

<div class="row-fluid">
<?php

$ingreso_bono_venta_inicial[$i] = $bono_venta_inicial[$i] * $percent_bono_venta_inicial[$i] / 100;
$ingreso_bono_renovacion[$i] = $bono_renovacion[$i] * $percent_bono_renovacion[$i] / 100;
?>
  <div class="span6 left-column">
    <div style="float:right">
      <span style="color:#547EBD !important;" id="ingresoBonoProductividad_text_<?php echo $i; ?>">$ <?php echo number_format($ingreso_bono_venta_inicial[$i], 2); ?></span>
      &nbsp;(<span id="bonoAplicado_<?php echo $i; ?>"><?php echo $percent_bono_venta_inicial[$i]; ?></span> %)
      <input type="hidden" disabled="disabled" name="ingresoBonoProductividad_<?php echo $i; ?>" id="ingresoBonoProductividad_<?php echo $i; ?>" value="<?php echo $ingreso_bono_venta_inicial[$i]; ?>">
    </div>
  </div>   <!-- END left column -->
 
  <div class="span6 right-column">
    <div style="float:right">
      <span style="color:#547EBD !important" id="ingresoBonoRenovacion_text_<?php echo $i; ?>">$ <?php echo number_format($ingreso_bono_renovacion[$i], 2); ?></span>
      &nbsp;(<span id="porbonoGanado_<?php echo $i; ?>"><?php echo $percent_bono_renovacion[$i]; ?></span> %)
      <input type="hidden" disabled="disabled" name="ingresoBonoRenovacion_<?php echo $i; ?>" id="ingresoBonoRenovacion_<?php echo $i; ?>" value="<?php echo $ingreso_bono_renovacion[$i]; ?>" >
    </div>
  </div>   <!-- END right column -->
</div>

<h3 class="row-fluid period-bottom">
    Ingresos Totales: $ <span id="bottom_<?php echo $i; ?>">
<?php
echo number_format($ingreso_comm_venta_inicial[$i] + $ingreso_comm_renovacion[$i] +
$ingreso_bono_venta_inicial[$i] + $ingreso_bono_renovacion[$i], 2) ?>
</span>

</h3>
</div> <!-- END trimestre / cuatrimestre -->

<div class="page-break"></div>
<?php
$ingreso_comm_venta_inicial['total'] += $ingreso_comm_venta_inicial[$i];
$ingreso_comm_renovacion['total'] += $ingreso_comm_renovacion[$i];
$ingreso_bono_venta_inicial['total'] += $ingreso_bono_venta_inicial[$i];
$ingreso_bono_renovacion['total'] += $ingreso_bono_renovacion[$i];

endfor; ?>

<div id="anual">
<h4 class="row-fluid print-view"><br />Anual</h4>

<div class="row-fluid">
  <div class="span6 left-column">
  </div>   <!-- END left column -->
  <div class="span6 right-column">
      <div class="row-fluid">
        <div class="span3 smaller">% de acotamiento:</div>
        <div class="span3">
          <span class="span12 smaller XAcotamiento-recap" id="XAcotamiento_recap"></span>
        </div>
        <div class="span6">
        </div>
      </div> 
  </div>   <!-- END right column -->
</div>

<!-- Additions to Anual: end -->

<div class="row-fluid anual">
  <div class="span6 left-column">
      <h3 class="row-fluid subtitle subtitle-yellow">INFORMACIÓN DE VENTA INICIAL</h3>
<!-- Additions to Anual: start 2 -->
      <div class="row-fluid">
        <div class="span3 smaller">Primas afectas iniciales:</div>
        <div class="span3">
          <span style="display: none" class="meta-ori" id="meta-prima-ori-recap"></span>
          <span class="span12 smaller simulator-primas-period-recap" id="simulator-primas-period-recap"></span>
        </div>
        <div class="span3 smaller"><?php if ($ramo == 'vida'): ?>No. de Negocios PAI:<?php endif ?></div>
        <div class="span3">
<?php if ($ramo == 'vida'): ?>
	       <span style="display: none" class="meta-ori" id="meta-negocio-ori-recap"></span>
           <span class="span12 smaller noNegocios-recap" id="noNegocios_recap"><span>
<?php endif ?>
        </div>
      </div> 
<!-- Additions to Anual: end 2 -->	 
	  
	  
      <table class="simulator-table">
        <colgroup>
          <col width="42%" />
          <col width="29%" />
          <col width="29%" />
        </colgroup>
	    <thead>
	      <tr>
			<th></th>
			<th>COMISIONES</th>
			<th>BONOS</th>
	      </tr>
	    </thead>
	    <tfoot>
	      <tr>
			<td>TOTAL</td>
			<td id="inicial-comm-recap"><?php echo number_format($ingreso_comm_venta_inicial['total'], 2); ?></td>
			<td id="inicial-bono-recap"><?php echo number_format($ingreso_bono_venta_inicial['total'], 2); ?></td>
	      </tr>
	    </tfoot>			
	    <tbody>
<?php for ($i = 1; $i <= $period_max; $i++):
$row_style = ($i & 1) ? 'odd' : 'even'; ?>
	      <tr class="simulator-row-<?php echo $row_style?>">
			<td><?php echo $i . $period_labels[$i] . ' ' . $period_name; ?></td>
			<td id="inicial-comm-recap-<?php echo $i; ?>"><?php echo number_format($ingreso_comm_venta_inicial[$i], 2); ?></td>
			<td id="inicial-bono-recap-<?php echo $i; ?>"><?php echo number_format($ingreso_bono_venta_inicial[$i], 2) ?></td>
	      </tr>
<?php endfor; ?>
	    </tbody>
      </table>
  </div>   <!-- END left column -->
  <div class="span6 right-column">
      <h3 class="row-fluid subtitle subtitle-orange">INFORMACIÓN DE CARTERA DE RENOVACIÓN</h3>


 <!-- Additions to Anual: start 3 -->
      <div class="row-fluid">
        <div class="span3 smaller">Primas de renovación:</div>
        <div class="span3">
           <span class="span12 smaller primasRenovacion_recap" id="primasRenovacion_recap"></span>
        </div>
        <div class="span3 smaller">&nbsp;</div>
        <div class="span3">&nbsp;
        </div>
      </div>	  
 <!-- Additions to Anual: end 3 -->	  


      <table class="simulator-table">
        <colgroup>
          <col width="42%" />
          <col width="29%" />
          <col width="29%" />
        </colgroup>
	    <thead>
	      <tr>
			<th></th>
			<th>COMISIONES</th>
			<th>BONOS</th>
	      </tr>
	    </thead>
	    <tfoot>
	      <tr>
			<td>TOTAL</td>
			<td id="renovacion-comm-recap"><?php echo number_format($ingreso_comm_renovacion['total'], 2); ?></td>
			<td id="renovacion-bono-recap"><?php echo number_format($ingreso_bono_renovacion['total'], 2); ?></td>
	      </tr>
	    </tfoot>			
	    <tbody>
<?php for ($i = 1; $i <= $period_max; $i++):
$row_style = ($i & 1) ? 'odd' : 'even'; ?>
	      <tr class="simulator-row-<?php echo $row_style?>">
			<td><?php echo $i . $period_labels[$i] . ' ' . $period_name; ?></td>
			<td id="renovacion-comm-recap-<?php echo $i; ?>"><?php echo number_format($ingreso_comm_renovacion[$i], 2); ?></td>
			<td id="renovacion-bono-recap-<?php echo $i; ?>"><?php echo number_format($ingreso_bono_renovacion[$i], 2) ?></td>
	      </tr>
<?php endfor; ?>
	    </tbody>
      </table>

  </div>   <!-- END right column -->
</div>
<h3 class="row-fluid period-bottom">
    Ingresos Totales: $ <span id="year-bottom"><?php echo number_format(
	$ingreso_comm_venta_inicial['total'] + $ingreso_comm_renovacion['total'] +
$ingreso_bono_venta_inicial['total'] + $ingreso_bono_renovacion['total'], 2) ?></span>
</h3>
</div> <!-- END anual -->
</div> <!-- END tabs -->
