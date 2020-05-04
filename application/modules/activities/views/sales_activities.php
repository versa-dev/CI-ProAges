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
$base_url = base_url();
$selected_period = get_filter_period();
if ( isset($_POST['periodo']) &&
	($_POST['periodo'] >= 1) && ($_POST['periodo'] <= 4) )
{
	$selected_filter_period = array(1 => '', 2 => '', 3 => '', 4 => '');
	$selected_filter_period[$_POST['periodo']] = ' selected="selected"';
}
else
	$selected_filter_period = get_selected_filter_period();

$divided_by_zero = 'N/D';
$span_count = 0;
$segment1 = $this->uri->segment(1);
$agent_profile_page = ($segment1 === 'agent');
$activity_page =  ($segment1 === 'activities');
if (!$agent_profile_page):
?>

<div>
    <ul class="breadcrumb">
        <li>
            <a href="<?php echo $base_url ?>">Admin</a> <span class="divider">/</span>
        </li>
        <li>
            <a href="<?php echo $base_url ?>activities.html">Actividades </a> <span class="divider">/</span>
        </li>               
        <li>
            Actividad de ventas
        </li>
        <li class="activity_results"><?php echo $report_period ?></li>
    </ul>
</div>

<div class="row-fluid sortable">
    <div class="box span12">      
        <div class="box-content">
            <?php if (isset($message['type'])): ?>            
                <?php if ($message['type'] == true): ?>
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <img src="<?php echo $base_url ?>images/true.png" width="20" height="20" />
                        <strong>Listo: </strong> <?php echo $message['message']; // Show Dinamical message Success  ?>
                    </div>
                <?php elseif ($message['type'] == false): ?>
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <img src="<?php echo $base_url ?>images/false.png" width="20" height="20" />
                        <strong>Error: </strong> <?php echo $message['message']; // Show Dinamical message error  ?>
                    </div>
                <?php endif; ?>			
            <?php endif; ?>

<?php endif; ?>
            <div class="row">
<?php if ($agent_profile_page):
	$current_page = $this->uri->segment(2);
	if ($current_page === FALSE)
		$current_page = 'index';
	$span_count = 0;
?>
<div style="padding-bottom: 3.5em">
<?php if ($this->access_create_activity): ?>
                  <a href="<?php echo $base_url ?>agent/create_activity/<?php echo $this->user_id ?>.html" id="add-activity" class="span4 subpage-link <?php if ($current_page == 'create_activity') echo ' subpage-link-current' ?>">
                    <i style="color: #365b9d; font-size: x-large" class="icon-plus" title="Capturar nuevo registro"></i>
                    Capturar nuevo registro
                  </a>
<?php else:
	$span_count += 4;
endif; ?>
<?php if ($this->access_activity_list): ?>
                  <a href="<?php echo $base_url ?>agent/activity_details/<?php echo $this->user_id ?>.html" id="view-details" class="span4 subpage-link <?php if ($current_page == 'activity_details') echo ' subpage-link-current' ?>">
                    <i style="color: #365b9d; font-size: x-large" class="icon-zoom-in" title="Ver detalle"></i>
                    Ver detalle
                  </a>
<?php else:
	$span_count += 4;
endif;
	$span_count +=4;
 ?>
                  <span class="span<?php echo $span_count?>"></span>
</div>
<?php endif; ?>
                  <form id="sales-activity-form" action="<?php echo current_url() ?>" class="row form-horizontal" method="post">
                      <fieldset>
<?php if (!$agent_profile_page): ?>
                          <div class="control-group">
                            <label class="control-label text-error" for="inputError">Vista :</label>
                            <div class="controls">
                              <input type="radio" id="view-normal" name="activity_view" value="normal" <?php /*if ($other_filters['activity_view'] == 'normal')*/ echo 'checked="checked"'?>>&nbsp;&nbsp;Normal&nbsp;&nbsp;&nbsp;&nbsp;
                              <input type="radio" id="view-efectividad" name="activity_view" value="efectividad" <?php /*if ($other_filters['activity_view'] == 'efectividad') echo 'checked="checked"' */ ?>>&nbsp;&nbsp;Efectividad					  
                            </div>
                          </div>
<?php else: ?>

			                  <input type="hidden" id="activity-view" name="activity_view" value="normal" />
<?php endif; ?>

                          <div class="row">
<?php if ($span_count): ?>
                            <div class="span<?php echo $span_count?> offset1">
<?php else: ?>
                            <div class="span5 offset1">
<?php endif; ?>

<?php if ($agent_profile_page || $activity_page): ?>
<?php echo $period_fields ?>
<select id="periodo_form" name="periodo" style="width: 175px" title="Período">
	  <option value="<?php echo $selected_period ?>"></option>
</select>
<input type="hidden" value="<?php echo $selected_period ?>" id="periodo" name="query[periodo]" />
<input type="hidden" value="<?php echo $selection_filters['begin'] ?>" id="start-d" name="start_d" />
<input type="hidden" value="<?php echo $selection_filters['end'] ?>" id="end-d" name="end_d" />

<?php else: ?>
                              <div>
                                <select id="periodo" name="periodo" title="Período" >
                                  <option value="2" <?php echo $selected_filter_period[2] ?>>Una Semana</option>
                                  <option value="1" <?php echo $selected_filter_period[1] ?>>Mes actual</option>
                                  <option value="3" <?php echo $selected_filter_period[3] ?>>Año actual</option>
                                  <option value="4" id="period_opt4" <?php echo $selected_filter_period[4] ?>>Período personalizado</option>
                                </select>
                                <span>
                                    &nbsp;&nbsp;<i style="cursor: pointer; vertical-align: top" class="icon-calendar" id="cust_update-period" title="Click para editar el período personalizado"></i>
                                    &nbsp;&nbsp;<i style="cursor: pointer; vertical-align: top; color: #06be1d; display: none" class="icon-calendar" id="week_update-period" title="Click para seleccionar otra semana"></i>
                                </span>
                              </div>		  
                              <div id="semana-container" <?php if (!$selected_filter_period[2]) echo 'style="display: none"' ?> title="Seleccione una Semana">
                                <div id="week"></div>
                                <label></label> <span id="startDate"></span>  <span id="endDate"></span>
                                 <input id="begin" name="begin" type="hidden" readonly="readonly" value="<?php echo set_value('begin', isset($other_filters['begin']) ? $other_filters['begin'] : '')  ?>">
                                 <input id="end" name="end" type="hidden" readonly="readonly" value="<?php echo set_value('end', isset($other_filters['end']) ? $other_filters['end'] : '')  ?>">
                              </div>
<?php endif; ?>

                            </div>

                            <div class="span6">
<?php if (!$agent_profile_page): ?>
                              <textarea placeholder="AGENTES" id="agent-name" name="agent_name" rows="1" class="input-xlarge select4" style="min-width: 250px; max-width: 300px; height: 1.5em"><?php echo $other_filters['agent_name']; ?></textarea>
			                  <span>
                                  <i style="cursor: pointer; vertical-align: top" class="icon-filter submit-form" id="submit-form1" title="Filtrar"></i>
                                  <i style="cursor: pointer; vertical-align: top" class="icon-list-alt" id="clear-agent-filter" title="Mostrar todos los agentes"></i>
                              </span>
<?php else: ?>

			                  <input type="hidden" id="agent-name" name="agent_name" value="<?php echo $selection_filters['agent_name']; ?>" />
<?php endif; ?>

                            </div>
                          </div>
                      </fieldset>
                    </form>

<?php
	if (isset($data['rows']) && count($data['rows']))
	{
		if ($agent_profile_page)
			echo '<h4 class="sales-subheader">Actividad</h4>';
		echo '
<table class="sortable altrowstable tablesorter sales-activity-results" id="sales-activity-normal">
<thead class="head">
<tr>
<th rowspan="2" class="medium-grey"';
		if ($agent_profile_page)
			echo ' style="display: none"';
		echo '>AGENTE</th>
<th rowspan="2" class="medium-grey">N° DE SEMANAS REPORTADAS</th>
<th colspan="2" class="light-grey">CITAS</th><th colspan="2" class="light-grey">ENTREVISTAS</th>
<th colspan="2" class="light-grey">PROSPECTOS</th>
<th colspan="4" class="light-grey">VIDA</th>
<th colspan="4" class="light-grey">GMM</th>
</tr>
<tr>
<th>TOTALES</th><th>PROM</th>
<th>TOTALES</th><th>PROM</th>
<th>TOTALES</th><th>PROM</th>
<th>SOLICITUDES</th><th>PROM</th><th>NEGOCIOS</th><th>PROM</th>
<th>SOLICITUDES</th><th>PROM</th><th>NEGOCIOS</th><th>PROM</th>
</tr></thead>
<tbody class="tbody">';

		foreach ($data['rows'] as $key => $value)
		{
			if ($value['weeks_reported'])
			{
				$vida_solicitudes_p = number_format($value['vida_solicitudes'] / $value['weeks_reported'], 2);
				$vida_negocios_p =  number_format($value['vida_negocios'] / $value['weeks_reported'], 2);
				$gmm_solicitudes_p = number_format($value['gmm_solicitudes'] / $value['weeks_reported'], 2);
				$gmm_negocios_p =  number_format($value['gmm_negocios'] / $value['weeks_reported'], 2);
			}
			else
			{
				$vida_solicitudes_p = $divided_by_zero;
				$vida_negocios_p = $divided_by_zero;
				$gmm_solicitudes_p = $divided_by_zero;
				$gmm_negocios_p = $divided_by_zero;
			}

			echo '
<tr id="normal-agent-id-' . $key . '_' . $value['user_id'] . '">
	<td rowspan="2"';
			if ($agent_profile_page)
				echo ' style="display: none"';
			echo '><a href="#" class="toggle">' . $value['name'] . '</a></td>
	<td class="sales-activity-numeric">' . number_format($value['weeks_reported'], 2) . '</td>
	<td class="medium-grey-body sales-activity-numeric">' . $value['citaT'] . '</td>
	<td class="medium-grey-body sales-activity-numeric">' . number_format($value['citaP'], 2) . '</td>
	<td class="light-grey-body sales-activity-numeric">' . $value['interviewT'] . '</td>
	<td class="light-grey-body sales-activity-numeric">' . number_format($value['interviewP'], 2) . '</td>	
	<td class="light-red-body sales-activity-numeric">' . $value['prospectusT'] . '</td>
	<td class="light-red-body sales-activity-numeric">' . number_format($value['prospectusP'], 2) . '</td>		
	<td class="light-green-body sales-activity-numeric">
		<a class="vida-solicitudes solicitudes-negocios" href="#">' . $value['vida_solicitudes'] . '</a>
	</td>

	<td class="light-green-body sales-activity-numeric">
		<a class="vida-solicitudes solicitudes-negocios" href="#">' . $vida_solicitudes_p . '</a>
	</td>

	<td class="light-green-body sales-activity-numeric">
		<a class="vida-negocios solicitudes-negocios" href="#">' . $value['vida_negocios'] . '</a>
	</td>

	<td class="light-green-body sales-activity-numeric">
		<a class="vida-negocios solicitudes-negocios" href="#">' . $vida_negocios_p . '</a>
	</td>

	<td class="light-blue-body sales-activity-numeric">
		<a class="gmm-solicitudes solicitudes-negocios" href="#">' . $value['gmm_solicitudes'] . '</a>
	</td>

	<td class="light-blue-body sales-activity-numeric">
		<a class="gmm-solicitudes solicitudes-negocios" href="#">' . $gmm_solicitudes_p . '</a>
	</td>

	<td class="light-blue-body sales-activity-numeric">
		<a class="gmm-negocios solicitudes-negocios" href="#">' . $value['gmm_negocios'] . '</a>
	</td>

	<td class="light-blue-body sales-activity-numeric">
		<a class="gmm-negocios solicitudes-negocios" href="#">' . $gmm_negocios_p . '</a>
	</td>
</tr>
<tr class="tablesorter-childRow">
<td colspan="15">
	<a href="' . $value['activities_url'] . '" class="btn btn-link">Ver actividad de ventas</a> |
	<a href="' . $value['simulator_url'] . '" class="btn btn-link">Simular resultado y definir meta</a> |
	<a href="' . $value['perfil_url'] . '" class="btn btn-link">Vision general</a> |
	<a href="' . $value['add_activities_url'] . '" class="btn btn-link">Agregar nueva actividad</a> 
</td>
</tr>
';
		}

		echo '
</tbody>
</table>
<br />';

		if ($agent_profile_page)
			echo '<h4 class="sales-subheader">Efectividad</h4>';

		echo '
<table class="sortable altrowstable tablesorter sales-activity-results" id="sales-activity-efectividad">
<thead class="head">
<tr>
<th rowspan="2" class="medium-grey"';
		if ($agent_profile_page)
			echo ' style="display: none"';
		echo '>AGENTE</th>
<th rowspan="2" class="medium-grey">N° DE SEMANAS REPORTADAS</th>
<th colspan="3" class="light-grey">CITAS - ENTREVISTAS</th>
<th colspan="2" class="light-grey">PROSPECTOS POR ENTREVISTA</th>
<th colspan="3" class="light-grey">VIDA</th>
<th colspan="3" class="light-grey">GMM</th>
</tr>
<tr>
<th>CITAS</th><th>ENTREVISTAS</th><th>EFECTIVIDAD</th>
<th>TOTALES</th><th>PROM</th>
<th>SOLICITUDES</th><th>NEGOCIOS</th><th>EFECTIVIDAD</th>
<th>SOLICITUDES</th><th>NEGOCIOS</th><th>EFECTIVIDAD</th>
</tr></thead>
<tbody class="tbody">';

		foreach ($data['rows'] as $key => $value)
		{
			if ($value['citaT'])
				$efectividad_1 = number_format(100 * $value['interviewT'] / $value['citaT'] , 0) . '%';
			else
				$efectividad_1 = $divided_by_zero;
			if ($value['vida_solicitudes'])
				$efectividad_2 = number_format(100 * $value['vida_negocios'] / $value['vida_solicitudes'], 0) . '%';
			else
				$efectividad_2 = $divided_by_zero;
			if ($value['gmm_solicitudes'])
				$efectividad_3 = number_format(100 * $value['gmm_negocios'] / $value['gmm_solicitudes'], 0) . '%';
			else
				$efectividad_3 = $divided_by_zero;
				
			echo '
<tr id="efectividad-agent-id-' . $key . '_' . $value['user_id'] . '">
	<td rowspan="2"';
			if ($agent_profile_page)
				echo ' style="display: none"';
			echo '><a href="#" class="toggle">' . $value['name'] . '</a></td>
	<td class="sales-activity-numeric">' . number_format($value['weeks_reported'], 2) . '</td>
	<td class="medium-grey-body sales-activity-numeric">' . $value['citaT'] . '</td>
	<td class="light-grey-body sales-activity-numeric">' . $value['interviewT'] . '</td>
	<td class="sales-activity-numeric">' . $efectividad_1 . '</td>	
	<td class="light-red-body sales-activity-numeric">' . $value['prospectusT'] . '</td>
	<td class="light-red-body sales-activity-numeric">' . number_format($value['prospectusP'], 2) . '</td>		
	<td class="light-green-body sales-activity-numeric">
		<a class="vida-solicitudes solicitudes-negocios" href="#">' . $value['vida_solicitudes'] . '</a>
	</td>
	<td class="light-green-body sales-activity-numeric">
		<a class="vida-negocios solicitudes-negocios" href="#">' . $value['vida_negocios'] . '</a>
	</td>
	<td class="sales-activity-numeric">' . $efectividad_2 . '</td>
	<td class="light-blue-body sales-activity-numeric">
		<a class="gmm-solicitudes solicitudes-negocios" href="#">' . $value['gmm_solicitudes'] . '</a>
	</td>
	<td class="light-blue-body sales-activity-numeric">
		<a class="gmm-negocios solicitudes-negocios" href="#">' . $value['gmm_negocios'] . '</a>
	</td>
	<td class="sales-activity-numeric">' . $efectividad_3 . '</td>
</tr>
<tr class="tablesorter-childRow">
<td colspan="12">
	<a href="' . $value['activities_url'] . '" class="btn btn-link">Ver actividad de ventas</a> |
	<a href="' . $value['simulator_url'] . '" class="btn btn-link">Simular resultado y definir meta</a> |
	<a href="' . $value['perfil_url'] . '" class="btn btn-link">Vision general</a> |
	<a href="' . $value['add_activities_url'] . '" class="btn btn-link">Agregar nueva actividad</a> 
</td>
</tr>
';
		}

		echo '
</tbody>
</table>';

	}
	else
	{
echo '<p class="sales-activity-results" style="text-align:center">No hay datos</p>';
	}
?>

            </div>
<?php if (!$agent_profile_page): ?>
        </div>               
    </div>
    
</div><!--/span-->
</div><!--/row-->
<?php endif ?>

<?php if (!$agent_profile_page && !$activity_page): ?>
<div style="margin-top: 10em">
<?php echo $period_form ?>

</div>
<?php endif ?>