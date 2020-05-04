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
$selected_period = get_filter_period();
$agent_profile_page = ($this->uri->segment(1) == 'agent');
if (!$agent_profile_page):
?>
<div>
    <ul class="breadcrumb">
       
		
        <li>
            <a href="<?php echo base_url() ?>">Inicio</a> <span class="divider">/</span>
        </li>
        <li>
           <a href="<?php echo base_url() ?>activities/activities.html">Actividades</a> <span class="divider">/</span>
        </li>
        <li>
            Overview <span class="divider">/</span>
        </li>       
        <li>
            <?php if( !empty( $usersupdate['company_name'] ) ) echo $usersupdate['company_name']; else echo $usersupdate['name'].' '.$usersupdate['lastnames']; ?>
        </li>
    </ul>
</div>

<div class="row-fluid sortable">		
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2></h2>
            <div class="box-icon">

                   <?php if( $access_create == true ): ?>
                   		<a href="<?php echo base_url() ?>activities/create<?php if( !empty( $userid ) ) echo '/'.$userid  ?>.html" class="btn btn-link" title="Crear"><i class="icon-plus"></i></a>
				   <?php endif; ?>

            </div>
        </div>
        <div class="box-content">
            <?php // Show Messages ?>
            <?php if( isset( $message['type'] ) ): ?>
                <?php if( $message['type'] == true ): ?>
                    <div class="alert alert-success">
                          <button type="button" class="close" data-dismiss="alert">×</button>
                          <img src="<?php echo base_url() ?>images/true.png" width="20" height="20" />
                          <strong>Listo: </strong> <?php  echo $message['message']; // Show Dinamical message Success ?>
                    </div>
                <?php endif; ?>
                <?php if( $message['type'] == false ): ?>
                    <div class="alert alert-error">
                          <button type="button" class="close" data-dismiss="alert">×</button>
                          <img src="<?php echo base_url() ?>images/false.png" width="20" height="20" />
                          <strong>Error: </strong> <?php  echo $message['message']; // Show Dinamical message error ?>
                    </div>
                <?php endif; ?>
			<?php endif; ?>
<?php endif; ?>

<?php if ($agent_profile_page):
	if ( isset($_POST['periodo']) &&
		($_POST['periodo'] >= 1) && ($_POST['periodo'] <= 4) )
	{
		$selected_filter_period = array(1 => '', 2 => '', 3 => '', 4 => '');
		$selected_filter_period[$_POST['periodo']] = ' selected="selected"';
	}
	else
		$selected_filter_period = get_selected_filter_period();

	$current_page = $this->uri->segment(2);
	if ($current_page === FALSE)
		$current_page = 'index';
	$span_count = 0;
?>
<div style="padding-bottom: 3.5em">
<?php if ($this->access_create_activity): ?>
                  <a href="<?php echo $base_url ?>agent/create_activity/<?php echo $this->user_id ?>.html" id="add-activity" class="span4 subpage-link">
                    <i style="color: #365b9d; font-size: x-large" class="icon-plus" title="Capturar nuevo registro"></i>
                    Capturar nuevo registro
                  </a>
<?php else:
	$span_count += 4;
endif; ?>
<?php if ($this->access_activity_list): ?>
                  <a href="<?php echo $base_url ?>agent/activity_details/<?php echo $this->user_id ?>.html" id="view-details" class="span4 subpage-link subpage-link-current">
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

                  <form id="sales-activity-form" action="<?php echo current_url() ?>" class="row form-horizontal" method="post">
                      <fieldset>
                          <input type="hidden" id="activity-view" name="activity_view" value="normal" />
                          <div class="row">
                            <div class="span5 offset1">

<?php if ($agent_profile_page): ?>
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
			                  <input type="hidden" id="agent-name" name="agent_name" value="<?php echo $other_filters['agent_name']; ?>" />
<?php else: ?>
			                  <input type="hidden" id="agent-name" name="agent_name" value="<?php echo $selection_filters['agent_name']; ?>" />
<?php endif; ?>
                          </div>
                      </fieldset>
                    </form>
<div>Actividades <?php echo $report_period ?> :</div>
<?php endif; ?>

        	<?php if( !empty( $data ) ): ?>
            <table class="table table-striped table-bordered bootstrap-datatable datatable">
              <thead>
                  <tr style="font-size:11px">
                      <th>Inicio</th>
                      <th>Fin</th> 
                      <th>Citas</th>
                      <th>Entrevistas</th>
                      <th>Prospectos</th>
                      <th>Solicitudes Vida</th>
                      <th>Negocios Vida</th>
                      <th>Solicitudes GMM</th>
                      <th>Negocios GMM</th>
                      <th>Negocios Autos</th>
                      <th>Comentarios</th>
                      <th>Creado</th>
                      <th>Última modificación</th>
                      <th>Acciones</th>
                  </tr>
              </thead>   
              <tbody id="data">
                <?php  foreach( $data as $value ): ?>
               <tr style="font-size:11px">
                	<td class="center" nowrap><?php echo $value['begin'] ?></td>
                    <td class="center" nowrap><?php echo $value['end'] ?></td>
                    <td class="center"><?php echo $value['cita'] ?></td>
                    <td class="center"><?php echo $value['interview'] ?></td>
                    <td class="center"><?php echo $value['prospectus'] ?></td>
                    <td class="center"><?php echo $value['vida_requests'] ?></td>
                    <td class="center"><?php echo $value['vida_businesses'] ?></td>
                    <td class="center"><?php echo $value['gmm_requests'] ?></td>
                    <td class="center"><?php echo $value['gmm_businesses'] ?></td>
                    <td class="center"><?php echo $value['autos_businesses'] ?></td>
                    <td class="center"><?php echo $value['comments'] ?></td>
                    <td class="center"><?php echo $value['date'] ?></td>
                    <td class="center"><?php echo $value['last_updated'] ?></td>
                    <td>
                         <?php if( $access_update == true ): ?>
                        <a target="_blank" class="btn btn-info" href="<?php echo base_url() ?>activities/update/<?php echo $value['activity_id'] ?><?php if( !empty( $userid ) ) echo '/'.$userid  ?>.html" title="Editar Actividad">
                            <i class="icon-edit icon-white"></i>            
                        </a>
                        <?php endif; ?>
                        <?php if( $access_delete == true ): ?>
                        <a target="_blank" class="btn btn-danger" href="<?php echo base_url() ?>activities/delete/<?php echo $value['activity_id'] ?><?php if( !empty( $userid ) ) echo '/'.$userid  ?>.html" title="Eliminar Actividad">
                            <i class="icon-trash icon-white"></i> 
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach;  ?>                
              </tbody>
          </table>    
          
          
          
          
          <?php else: ?>
		  
		  	<div class="alert alert-block">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Atención: </strong> No hay registro de actividades. . <a href="<?php echo base_url() ?>activities/create<?php if( !empty( $userid ) ) echo '/'.$userid  ?>.html" class="btn btn-link">Haga click aquí para capturar un nuevo registro</a>
            </div>
		  <?php endif; ?>
<?php if (!$agent_profile_page): ?>
        </div>
    </div><!--/span-->

</div><!--/row-->

<?php $pagination = $this->pagination->create_links(); // Set Pag ?>

<?php if( !empty( $pagination ) ): ?>
    
    <div class="row-fluid sortable">		
        <div class="box span12">
            <div class="box-content">
            
              <?php echo $pagination?>
                      
            </div>
        </div><!--/span-->
    
    </div><!--/row-->

<?php endif; ?>
<?php else: ?>
<div style="margin-top: 10em">
<?php if (!$agent_profile_page): ?>
<?php echo $period_form ?>
<?php endif; ?>
</div>
<?php endif;?>

