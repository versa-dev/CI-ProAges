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

<div>
    <ul class="breadcrumb">
        <li>
            <a href="<?php echo base_url() ?>">Admin</a> <span class="divider">/</span>
        </li>
        
        <li>
            <a href="<?php echo base_url() ?>ot.html">Orden de trabajo</a> <span class="divider">/</span>
        </li>
        
        <li>
            Activar
        </li>
    </ul>
</div>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2></h2>
            <div class="box-icon">
            </div>
        </div>

        <div class="box-content">

            <form id="form" action="<?php echo base_url() ?>ot/activar/<?php echo $ot ?>.html" class="form-horizontal" method="post">
                <fieldset>

                   <div class="control-group subtype">
                    <label class="control-label text-error" for="inputError">Razón</label>
                    <div class="controls">
                      <select class="input-xlarge focused required" id="work_order_reason_id" name="work_order_reason_id">
                      	<?php echo $reason ?>
                      </select>
                    </div>
                  </div>

                  <div class="control-group poliza">
                    <label class="control-label text-error" for="inputError">Responsable</label>
                    <div class="controls">
                      <select class="input-xlarge focused" id="work_order_responsible_id" name="work_order_responsible_id">
                      	<?php echo $responsibles ?>
                      </select>
                    </div>
                  </div>

                  <div class="control-group poliza">
                    <label class="control-label text-error" for="inputError">Comentarios</label>
                    <div class="controls">
                      <textarea name="comments" class="required" rows="6"><?php echo $data[0]['comments'] ?></textarea>
                    </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label" for="inputError">¿Con notificacion por correo electrónico?</label>
                    <div class="controls">
                      <input type="checkbox" value="1" name="notification" checked="checked" />&nbsp;Si&nbsp;&nbsp;
					  </div>
                  </div>

                  <input type="hidden" id="creation_date" name="creation_date" />

                  <div id="actions-buttons-forms" class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <input type="button" class="btn" onclick="javascript: history.back()" value="Cancelar">
                  </div>
                </fieldset>
              </form>

        </div>
    </div><!--/span-->

</div><!--/row-->
			