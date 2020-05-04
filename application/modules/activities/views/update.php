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

$selected_period = get_filter_period();
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
            Editar
        </li>
    </ul>
</div>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
        </div>

        <div class="box-content">
			<?php // Return Message error ?>
            <?php $validation = validation_errors(); ?>

            <?php if( !empty( $validation ) ): ?>
            <div class="alert alert-error">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Error: </strong> <?php  echo $validation; // Show Dinamical message error ?>
            </div>
            <?php endif; ?>

            <form id="form" action="<?php echo base_url() ?>activities/update/<?php echo $data->id; if( !empty( $userid ) ) echo '/'.$userid  ?>.html" class="form-horizontal" method="post">
                <fieldset>

                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Periodo</label>
                    <div class="controls">
<?php echo $period_fields ?>
<select id="periodo_form" name="periodo" style="width: 175px" title="Período">
	  <option value="<?php echo $selected_period ?>"></option>
</select>
<input type="hidden" value="<?php echo $selected_period ?>" id="periodo" name="query[periodo]" />
                    </div>
                  </div>

<!--
                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Semana</label>
                    <div class="controls">
                      <div id="week"></div>
                      <label></label> <span id="startDate"></span>  <span id="endDate"></span>
                       <input id="begin" name="begin" type="hidden" readonly="readonly" value="<?php echo set_value('begin')  ?>">
                       <input id="end" name="end" type="hidden" readonly="readonly" value="<?php echo set_value('end')  ?>">
                    </div>
                  </div>
-->

                  <div class="row">
                    <div class="span2">
                     	<div class="control-group" style="width:350px;">
                          	<div class="controls">
          	                   	<div id="container">
								    <div id="left">
								        <label class="text-error" for="inputError">Citas</label>
								        <input style="width:50px" type="number" required min="0" step="1" class="focused required number" id="cita" name="cita" type="text" value="<?php echo set_value('cita', $data->cita)  ?>" maxlength="3">
								    </div>

								    <div id="middle">
								        <label class="text-error" for="inputError">Entrevistas</label>
								        <input style="width:50px" type="number" required min="0" step="1" maxlength="3" class="focused required number" id="interview" name="interview" type="text" value="<?php echo set_value('interview', $data->interview)  ?>">
								    </div>

								    <div id="right">
								        <label class="text-error" for="inputError">Prospectos</label>
								        <input style="width:50px" type="number" required min="0" step="1" maxlength="3" class="focused required number" id="prospectus" name="prospectus" type="text" value="<?php echo set_value('prospectus', $data->prospectus)  ?>">
								    </div>
								</div>
                          </div>
                        </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="span2">
                     	<div class="control-group" style="width:350px;">
                          	<div class="controls">
          	                   	<div id="container">
								    <div id="left">
								        <label class="text-error" for="inputError">Solicitudes Vida</label>
								        <input style="width:50px" type="number" required min="0" step="1" class="focused required number" id="vida_requests" name="vida_requests" type="text" value="<?php echo set_value('vida_requests', $data->vida_requests)  ?>" maxlength="3">
								    </div>

								    <div id="middle">
								    </div>

								    <div id="right">
								        <label class="text-error" for="inputError">Negocios Vida</label>
								        <input style="width:50px" type="number" required min="0" step="1" maxlength="3" class="focused required number" id="vida_businesses" name="vida_businesses" type="text" value="<?php echo set_value('vida_businesses', $data->vida_businesses)  ?>">
								    </div>
								</div>
                          </div>
                        </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="span2">
                     	<div class="control-group" style="width:350px;">
                          	<div class="controls">
          	                   	<div id="container">
								    <div id="left">
								        <label class="text-error" for="inputError">Solicitudes GMM</label>
								        <input style="width:50px" type="number" required min="0" step="1" class="focused required number" id="gmm_requests" name="gmm_requests" type="text" value="<?php echo set_value('gmm_requests', $data->gmm_requests)  ?>" maxlength="3">
								    </div>

								    <div id="middle">
								    </div>

								    <div id="right">
								        <label class="text-error" for="inputError">Negocios GMM</label>
								        <input style="width:50px" type="number" required min="0" step="1" maxlength="3" class="focused required number" id="gmm_businesses" name="gmm_businesses" type="text" value="<?php echo set_value('gmm_businesses', $data->gmm_businesses)  ?>">
								    </div>
								</div>
                          </div>
                        </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="span2">
                     	<div class="control-group" style="width:350px;">
                          	<div class="controls">
          	                   	<div id="container">
								    <div id="left">
								    </div>

								    <div id="middle">
								        <label class="text-error" for="inputError">Negocios Autos</label>
								        <input style="width:50px" type="number" required min="0" step="1" maxlength="3" class="focused required number" id="autos_businesses" name="autos_businesses" type="text" value="<?php echo set_value('autos_businesses', $data->autos_businesses)  ?>">
								    </div>

								    <div id="right">
								    </div>
								</div>
                          </div>
                        </div>
                    </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label" for="inputError">Comentarios</label>
                    <div class="controls">
                     <textarea name="comments" class="input-xlarge" rows="10"><?php echo set_value('comments', $data->comments)  ?></textarea>
                    </div>
                  </div>

                  <div id="actions-buttons-forms" class="form-actions">
                    <button type="submit" id="save-activity" class="btn btn-primary">Guardar</button>
                    <button class="btn" onclick="history.back()">Cancelar</button>
                  </div>
                </fieldset>
              </form>

        </div>
    </div><!--/span-->

</div><!--/row-->
			