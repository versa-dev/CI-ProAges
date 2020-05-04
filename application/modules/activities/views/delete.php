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
            <a href="<?php echo base_url() ?>">Inicio</a> <span class="divider">/</span>
        </li>
        <li>
           <a href="<?php echo base_url() ?>activities/activities.html">Actividades</a> <span class="divider">/</span>
        </li>
        <li>
            Eliminar
        </li>
    </ul>
</div>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
        </div>

        <div class="box-content">

			<h3>Eliminar una actividad (inicio <?php echo $data->begin ?>)</h3>           

            <p>Esta seguro de eliminar este registro.</p>
            
        
            <form id="form" action="<?php echo base_url() ?>activities/delete/<?php echo $data->id; if( !empty( $userid ) ) echo '/'.$userid  ?>.html" class="form-horizontal" method="post">
                <fieldset>
                  <input type="hidden" name="delete" value="true" />
                  <div id="actions-buttons-forms" class="form-actions">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                    <input type="button" class="btn" onclick="javascript: history.back()" value="Cancelar">
                  </div>
                </fieldset>
              </form>
        
        </div>
    </div><!--/span-->

</div><!--/row-->
			