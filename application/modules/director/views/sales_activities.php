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
$divided_by_zero = 'N/D';

$agent_th = '<th rowspan="2" class="medium-grey">AGENTE</th>';
$total_cell = '<td>TOTALES</td>';

$vida_negocios_link = $data['totals']['VIDA_negocios'] ?
	'<a style="cursor: pointer;" class="vida negocios_class">' . $data['totals']['VIDA_negocios'] . '</a>' : $data['totals']['VIDA_negocios'];
$gmm_negocios_link = $data['totals']['GMM_negocios'] ?
 	'<a style="cursor: pointer;" class="gmm negocios_class">' . $data['totals']['GMM_negocios'] . '</a>' : $data['totals']['GMM_negocios'];
?>
            <div class="row">

                  <form id="sales-activity-form" action="<?php echo current_url() ?>" class="row form-horizontal" method="post">
                      <fieldset>
                          <div class="control-group">
                            <label class="control-label text-error" for="inputError">Vista :</label>
                            <div class="controls">
                              <input type="radio" id="view-normal" name="activity_view" value="normal" <?php /*if ($other_filters['activity_view'] == 'normal')*/ echo 'checked="checked"'?>>&nbsp;Normal&nbsp;&nbsp;&nbsp;&nbsp;
                              <input type="radio" id="view-efectividad" name="activity_view" value="efectividad" <?php /*if ($other_filters['activity_view'] == 'efectividad') echo 'checked="checked"' */ ?>>&nbsp;Efectividad					  
                            </div>
                          </div>

<?php if (!$other_filters['agent_name']): ?>						  
                          <div class="control-group">
                            <label class="control-label text-error" for="inputError">Vista de los agentes :</label>
                            <div class="controls">
                              <input class="agent-view all-agents-totals" type="radio" id="all-agents-totals" name="agent_view" value="totales" checked="checked">&nbsp;Sólo la fila <span style="font-weight: bold">TOTALES</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input class="agent-view all-agents-detailed" type="radio" id="all-agents-detailed" name="agent_view" value="detailed">&nbsp;Todas las filas
                            </div>
                          </div>						  
<?php endif; ?>

                          <div class="row">
                            <div class="span12" style="margin-left: 4em">
<?php echo $filter_view ?>
                            </div>
                          </div>

                      </fieldset>
                    </form>

<?php
	if (isset($data['rows']) && count($data['rows']))
	{
		echo '
<table class="sortable altrowstable tablesorter sales-activity-results" id="sales-activity-normal">
<thead class="head">
<tr>' . $agent_th . '
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
<tbody class="tbody agent-row">';

		foreach ($data['rows'] as $key => $value)
		{
			if ($value['weeks_reported'])
			{
				$vida_solicitudes_p = number_format($value['vida_solicitudes'] / $value['weeks_reported'], 2);
				$vida_negocios_p = number_format($value['vida_negocios'] / $value['weeks_reported'], 2);
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
	<td rowspan="2"><a href="#" class="toggle">' . $value['name'] . '</a></td>
	<td class="sales-activity-numeric">' . $value['weeks_reported'] . '</td>
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
	<a href="' . $value['perfil_url'] . '" class="btn btn-link">Vision general</a>
</td>
</tr>
';
		}
		echo '
</tbody>';

		if ($data['totals']['weeks_reported'])
		{
			$vida_solicitudes_p = number_format($data['totals']['VIDA_solicitudes'] / $data['totals']['weeks_reported'], 2);
			$vida_negocios_p = number_format($data['totals']['VIDA_negocios'] / $data['totals']['weeks_reported'], 2);
			$gmm_solicitudes_p = number_format($data['totals']['GMM_solicitudes'] / $data['totals']['weeks_reported'], 2);
			$gmm_negocios_p = number_format($data['totals']['GMM_negocios'] / $data['totals']['weeks_reported'], 2);
			$data['totals']['citaP'] = number_format($data['totals']['cita'] / $data['totals']['weeks_reported'], 2);
			$data['totals']['interviewP'] = number_format($data['totals']['interview'] / $data['totals']['weeks_reported'], 2);
			$data['totals']['prospectusP'] = number_format($data['totals']['prospectus'] / $data['totals']['weeks_reported'], 2);
		}
		else
		{
			$vida_solicitudes_p = $divided_by_zero;
			$vida_negocios_p = $divided_by_zero;
			$gmm_solicitudes_p = $divided_by_zero;
			$gmm_negocios_p = $divided_by_zero;
			$data['totals']['citaP'] = $divided_by_zero;
			$data['totals']['interviewP'] = $divided_by_zero;
			$data['totals']['prospectusP'] = $divided_by_zero;
		}
		echo '
<tbody class="total-row">
<tr style="font-weight: bold">
' . 	$total_cell	. '
	<td class="sales-activity-numeric">' . $data['totals']['weeks_reported'] . '</td>
	<td class="medium-grey-body sales-activity-numeric">' . $data['totals']['cita'] . '</td>
	<td class="medium-grey-body sales-activity-numeric">' . $data['totals']['citaP'] . '</td>
	<td class="light-grey-body sales-activity-numeric">' . $data['totals']['interview'] . '</td>
	<td class="light-grey-body sales-activity-numeric">' . $data['totals']['interviewP'] . '</td>	
	<td class="light-red-body sales-activity-numeric">' . $data['totals']['prospectus'] . '</td>
	<td class="light-red-body sales-activity-numeric">' . $data['totals']['prospectusP'] . '</td>		
	<td class="light-green-body sales-activity-numeric">' . $data['totals']['VIDA_solicitudes'] . '</td>
	<td class="light-green-body sales-activity-numeric">' . $vida_solicitudes_p . '</td>
	<td class="light-green-body sales-activity-numeric">' . $vida_negocios_link . '</td>
	<td class="light-green-body sales-activity-numeric">' . $vida_negocios_p . '</td>
	<td class="light-blue-body sales-activity-numeric">' . $data['totals']['GMM_solicitudes'] . '</td>
	<td class="light-blue-body sales-activity-numeric">' . $gmm_solicitudes_p . '</td>
	<td class="light-blue-body sales-activity-numeric">' . $gmm_negocios_link . '</td>
	<td class="light-blue-body sales-activity-numeric">' . $gmm_negocios_p . '</td>
</tr>
</tbody>
</table>
<br />';

		echo '
<table class="sortable altrowstable tablesorter sales-activity-results" id="sales-activity-efectividad">
<thead class="head">
<tr>
' . $agent_th .'
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
<tbody class="tbody agent-row">';

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
	<td rowspan="2"><a href="#" class="toggle">' . $value['name'] . '</a></td>
	<td class="sales-activity-numeric">' . $value['weeks_reported'] . '</td>
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
	<a href="' . $value['perfil_url'] . '" class="btn btn-link">Vision general</a>
</td>
</tr>
';
		}

		echo '
</tbody>';
		if ($data['totals']['cita'])
			$efectividad_1 = number_format(100 * $data['totals']['interview'] / $data['totals']['cita'] , 0) . '%';
		else
			$efectividad_1 = $divided_by_zero;
		if ($data['totals']['VIDA_solicitudes'])
			$efectividad_2 = number_format(100 * $data['totals']['VIDA_negocios'] / $data['totals']['VIDA_solicitudes'], 0) . '%';
		else
			$efectividad_2 = $divided_by_zero;
		if ($data['totals']['GMM_solicitudes'])
			$efectividad_3 = number_format(100 * $data['totals']['GMM_negocios'] / $data['totals']['GMM_solicitudes'], 0) . '%';
		else
			$efectividad_3 = $divided_by_zero;
		echo '
<tbody class="total-row">
<tr style="font-weight: bold">' .	$total_cell . '
	<td class="sales-activity-numeric">' . $data['totals']['weeks_reported'] . '</td>
	<td class="medium-grey-body sales-activity-numeric">' . $data['totals']['cita'] . '</td>
	<td class="light-grey-body sales-activity-numeric">' . $data['totals']['interview'] . '</td>
	<td class="sales-activity-numeric">' . $efectividad_1 . '</td>	
	<td class="light-red-body sales-activity-numeric">' . $data['totals']['prospectus'] . '</td>
	<td class="light-red-body sales-activity-numeric">' . $data['totals']['prospectusP'] . '</td>		
	<td class="light-green-body sales-activity-numeric">' . $data['totals']['VIDA_solicitudes'] . '</td>
	<td class="light-green-body sales-activity-numeric">' . $vida_negocios_link . '</td>
	<td class="sales-activity-numeric">' . $efectividad_2 . '</td>
	<td class="light-blue-body sales-activity-numeric">' . $gmm_negocios_link . '</td>
	<td class="light-blue-body sales-activity-numeric">' . $data['totals']['GMM_negocios'] . '</td>
	<td class="sales-activity-numeric">' . $efectividad_3 . '</td>
</tr>
</tbody>
</table>';

	}
	else
	{
echo '<p class="sales-activity-results">No hay datos</p>';
	}
?>

            </div>

        </div>
    </div>
    
</div><!--/span-->
</div><!--/row-->
