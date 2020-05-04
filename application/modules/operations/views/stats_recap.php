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
$base_url = base_url();
$this->load->helper('filter');
$selected_filter_period = get_selected_filter_period();
$selected_period = get_filter_period();
$na_value = 'N/D';

$segments = $this->uri->rsegment_array();
unset($segments[4]);
?>
            <form id="operation-stats-form" method="post" action="<?php echo current_url()?>">
              <div class="row">
                  <div class="span2">
Coordinadores&nbsp;:
                  </div>
                  <div class="span10">
<?php echo $coordinator_select ?>
                  </div>
              </div>
              <div class="row">
                  <div class="span2">
Período&nbsp;:
                  </div>
                  <div class="span6">
<?php echo $period_fields ?>
<select id="periodo_form" name="periodo" style="width: 175px" title="Período">
	  <option value="<?php echo $selected_period ?>"></option>
</select>
<input type="hidden" value="<?php echo $selected_period ?>" id="periodo" name="query[periodo]" />
                  </div>
                  <div class="span4" style="text-align: right">
<?php if ($this->access_export_xls) :
$export_segments = $segments;
$export_segments[2] = 'stat_recap_export';
?>
                <a href="<?php echo $base_url . implode('/', $export_segments); ?>.html" id="recap-export-xls" title="Exportar" style="font-size: larger;">
                    <img src="<?php echo $base_url ?>ot/assets/images/down.png" title="Exportar" />
				</a>
<?php endif; ?>
                  </div>
              </div>
            </form>

            <div class="row" id="operations-stats">
		        <div class="span4" id="left-col">
                  <table>
                    <tbody style="border-bottom: 1em">
                      <tr style="background-color: white">
                        <td style="border: 1px solid #000000">Ordenes de Trabajo Procesadas</td>
                        <td style="border: 1px solid #000000"><?php echo $stats['recap-left']; ?></td>
                      </tr>
                      <tr><td colspan="2">&nbsp;</td></tr>
                    </tbody>
                    <tbody>
                      <tr>
                        <td>Vida</td>
                        <td>
<?php $segments[3] = 'vida';
echo $stats['per_ramo_tramite'][1]['all']; ?>
                        </td>
                        <td>
                        </td>
                        <td><?php $percent = $stats['recap-left'] ? round(100 * $stats['per_ramo_tramite'][1]['all'] / $stats['recap-left']) . '%' : $na_value; echo $percent; ?></td>
                      </tr>
<?php foreach ($stats['per_ramo_tramite'][1] as $key => $value) :
if ($value['label'] == 'NUEVO NEGOCIO')
	$value['label'] = '<a id="vida_link" href="' . $base_url . implode('/', $segments) .
		'.html" title="Ver Detalles">' . 
		$value['label'] . '</a>';
if ($key != 'all') : ?>
                      <tr>
                        <td style="text-align: left"><?php echo $value['label'] ?></td>
                        <td><?php echo $value['value'] ?></td>
                        <td></td>
                        <td><?php $percent = $stats['recap-left'] ? round(100 * $value['value'] / $stats['recap-left']) . '%' : $na_value; echo $percent; ?></td>
                      </tr>
<?php endif;
endforeach;?>
                    </tbody>

                    <tbody>
                      <tr>
                        <td>Gastos Médicos</td>
                        <td>
<?php
$segments[3] = 'gmm';
echo $stats['per_ramo_tramite'][2]['all'];
?>
                        </td>
                        <td>
                        </td>
                        <td><?php $percent = $stats['recap-left'] ? round(100 * $stats['per_ramo_tramite'][2]['all'] / $stats['recap-left']) . '%' : $na_value; echo $percent; ?></td>
                      </tr>
<?php foreach ($stats['per_ramo_tramite'][2] as $key => $value) :
if ($value['label'] == 'NUEVO NEGOCIO')
	$value['label'] = '<a id="gmm_link" href="' . $base_url . implode('/', $segments) .
		'.html" title="Ver Detalles">' . 
		$value['label'] . '</a>';
if ($key != 'all') : ?>
                      <tr>
                        <td style="text-align: left"><?php echo $value['label'] ?></td>
                        <td><?php echo $value['value'] ?></td>
                        <td></td>
                        <td><?php $percent = $stats['recap-left'] ? round(100 * $value['value'] / $stats['recap-left']) . '%' : $na_value; echo $percent; ?></td>
                      </tr>
<?php endif;
endforeach;?>
                    </tbody>

                    <tbody>
                      <tr>
                        <td>Automóviles</td>
                        <td>
<?php $segments[3] = 'autos';
echo $stats['per_ramo_tramite'][3]['all']; ?>
                        </td>
						
                        <td>
                        </td>
                        <td><?php $percent = $stats['recap-left'] ? round(100 * $stats['per_ramo_tramite'][3]['all'] / $stats['recap-left']) . '%' : $na_value; echo $percent; ?></td>
                      </tr>
<?php foreach ($stats['per_ramo_tramite'][3] as $key => $value) :
if ($value['label'] == 'NUEVO NEGOCIO')
	$value['label'] = '<a id="autos_link" href="' . $base_url . implode('/', $segments) .
		'.html" title="Ver Detalles">' . 
		$value['label'] . '</a>';
if ($key != 'all') : ?>
                      <tr>
                        <td style="text-align: left"><?php echo $value['label'] ?></td>
                        <td><?php echo $value['value'] ?></td>
                        <td></td>
                        <td><?php $percent = $stats['recap-left'] ? round(100 * $value['value'] / $stats['recap-left']) . '%' : $na_value; echo $percent; ?></td>
                      </tr>
<?php endif;
endforeach;?>
                    </tbody>
                  </table>
		        </div>
		        <div class="span4" id="center-col">
<br />&nbsp;
                  <table>
                      <tr>
                        <td>En trámite</td>
                        <td><?php echo $stats['per_status']['tramite'] ?></td>
                        <td><?php $percent = $stats['recap-middle'] ? round(100 * $stats['per_status']['tramite'] / $stats['recap-middle']) . '%' : $na_value; echo $percent; ?></td>
                      </tr>
                      <tr>
                        <td>Terminadas</td>
                        <td><?php echo $stats['per_status']['terminada'] ?></td>
                        <td><?php $percent = $stats['recap-middle'] ? round(100 * $stats['per_status']['terminada'] / $stats['recap-middle']) . '%' : $na_value; echo $percent; ?></td>
                      </tr>
                      <tr>
                        <td>Canceladas</td>
                        <td><?php echo $stats['per_status']['canceladas'] ?></td>
                        <td><?php $percent = $stats['recap-middle'] ? round(100 * $stats['per_status']['canceladas'] / $stats['recap-middle']) . '%' : $na_value; echo $percent; ?></td>
                      </tr>
                      <tr style="border-bottom: 2em solid white">
                        <td>Activadas</td>
                        <td><?php echo $stats['per_status']['activadas'] ?></td>
                       <td><?php $percent = $stats['recap-middle'] ? round(100 * $stats['per_status']['activadas'] / $stats['recap-middle']) . '%' : $na_value; echo $percent; ?></td>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                      </tr>
                      <tr>
                        <td style="border: 1px solid #000000">Ordenes de Trabajo Procesadas</td>
                        <td style="border: 1px solid #000000"><?php echo $stats['recap-middle'] ?></td>
                        <td style="border: 1px solid #000000"><?php $percent = $stats['recap-middle'] ? '100%' : $na_value; echo $percent; ?></td>
                      </tr>
                  </table>
		        </div>
		        <div class="span4" id="right-col">
<br />&nbsp;
                  <table>
                      <tr>
                        <td colspan="3">RESPONSABLES DE ACTIVACIÓN Y/O CANCELACIÓN</td>
                      </tr>
<?php foreach ($stats['per_responsible'] as  $value) : ?>
                      <tr>
                        <td><?php echo $value['label'] ?></td>
                        <td><?php echo $value['value'] ?></td>
                        <td><?php $percent = $stats['recap-right'] ? round(100 * $value['value'] / $stats['recap-right']) . '%' : $na_value; echo $percent; ?></td>
                      </tr>
<?php endforeach;?>					  
                      <tr>
                        <td colspan="3"></td>
                      </tr>
                      <tr>
                        <td style="border: 1px solid #000000">Ordenes de Activadas / canceladas</td>
                        <td style="border: 1px solid #000000"><?php echo $stats['recap-right'] ?></td>
                        <td style="border: 1px solid #000000"><?php $percent = $stats['recap-right'] ? '100%' : $na_value; echo $percent; ?></td>
                      </tr>
                  </table>
		        </div>
            </div>			

