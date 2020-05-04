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
$selected_filter_period = get_selected_filter_period();
$selected_period = get_filter_period();
$na_value = 'N/D';

$segments = $this->uri->rsegment_array();
unset($segments[4]);
?>
            <form id="form" method="post" action="<?php echo current_url()?>">

              <table class="filterstable" style="width:99%;">
                <thead>
                   <tr style="vertical-align: top;">
                    <th>
<?php echo $period_fields ?>
<select id="periodo_form" name="periodo" style="width: 175px" title="Período">
	  <option value="<?php echo $selected_period ?>"></option>
</select>
<input type="hidden" value="<?php echo $selected_period ?>" id="periodo" name="query[periodo]" />
                    </th>
                    <th>
                      <span title="Escriba el nombre del agente que desea buscar y selecciónelo de la lista que aparece. Puede buscar más posteriormente en la siguiente línea.">
<textarea placeholder="AGENTES" id="agent-name" name="query[agent_name]" rows="1" class="input-xlarge select4" style="min-width: 250px; height: 2.2em"><?php echo $other_filters['agent_name']; ?></textarea>
&nbsp;<i style="cursor: pointer;" class="icon-filter submit-form" id="submit-form1" title="Filtrar"></i>
&nbsp;<i style="cursor: pointer;" class="icon-list-alt" id="clear-agent-filter" title="Mostrar todos los agentes"></i>
                      </span>
					</th>
                    <th>
<?php echo $coordinator_select ?>

<?php if ($this->access_export_xls) :
$export_segments = $segments;
$export_segments[2] = 'stat_recap_export';
?>
	                  <a style="display: none" href="<?php echo $base_url . implode('/', $export_segments); ?>.html" id="recap-export-xls" title="Exportar" style="font-size: larger;">
	                     <img src="<?php echo $base_url ?>ot/assets/images/down.png" title="Exportar" />
	                  </a>
<?php endif; ?>
	                </th>
                   </tr>
                </thead>
              </table>

            </form>

            <div style="padding-left: 2em" class="row" id="operations-stats">

		        <div class="span4" id="center-col">
                  <table>
                      <tr>
                        <td style="border: 1px solid #000000">Ordenes de Trabajo Procesadas</td>
                        <td style="border: 1px solid #000000"><?php echo $stats['recap-middle'] ?></td>
                        <td style="border: 1px solid #000000"><?php $percent = $stats['recap-middle'] ? '100%' : $na_value; echo $percent; ?></td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
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
                  </table>
		        </div>
				
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
                        <td><a target="_self" href="<?php echo $base_url ?>director/ot_list/1.html" title="Ver Detalles">Vida</a></td>
                        <td><a target="_self" href="<?php echo $base_url ?>director/ot_list/1.html" title="Ver Detalles">
<?php echo $stats['per_ramo_tramite'][1]['all']; ?>
                        </a></td>
                        <td>
                        </td>
                        <td><a target="_self" href="<?php echo $base_url ?>director/ot_list/1.html" title="Ver Detalles">
<?php $percent = $stats['recap-left'] ? round(100 * $stats['per_ramo_tramite'][1]['all'] / $stats['recap-left']) . '%' : $na_value; echo $percent; ?>
                        </a></td>
                      </tr>
<?php foreach ($stats['per_ramo_tramite'][1] as $key => $value) :
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
                        <td><a target="_self" href="<?php echo $base_url ?>director/ot_list/2.html" title="Ver Detalles">Gastos Médicos</a></td>
                        <td><a target="_self" href="<?php echo $base_url ?>director/ot_list/2.html" title="Ver Detalles">
<?php echo $stats['per_ramo_tramite'][2]['all']; ?>
                        </a></td>
                        <td>
                        </td>
                        <td><a target="_self" href="<?php echo $base_url ?>director/ot_list/2.html" title="Ver Detalles"><?php $percent = $stats['recap-left'] ? round(100 * $stats['per_ramo_tramite'][2]['all'] / $stats['recap-left']) . '%' : $na_value; echo $percent; ?>
                        </a></td>
                      </tr>
<?php foreach ($stats['per_ramo_tramite'][2] as $key => $value) :
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
                        <td><a target="_self" href="<?php echo $base_url ?>director/ot_list/3.html" title="Ver Detalles">Automóviles</a></td>
                        <td><a target="_self" href="<?php echo $base_url ?>director/ot_list/3.html" title="Ver Detalles">
<?php echo $stats['per_ramo_tramite'][3]['all']; ?>
                        </a></td>

                        <td>
                        </td>
                        <td><a target="_self" href="<?php echo $base_url ?>director/ot_list/3.html" title="Ver Detalles">
<?php $percent = $stats['recap-left'] ? round(100 * $stats['per_ramo_tramite'][3]['all'] / $stats['recap-left']) . '%' : $na_value; echo $percent; ?>
                        </a></td>
                      </tr>
<?php foreach ($stats['per_ramo_tramite'][3] as $key => $value) :
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

