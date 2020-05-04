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
           Cancelar
        </li>
    </ul>
</div>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-edit"></i> Cancelar OT</h2>
            <div class="box-icon">
               
            </div>
        </div>
        
        <div class="box-content">
        	
            
			<h3>Cancelar la orden de trabajo</h3>           
            
            <p>Esta seguro de Canselar este registro.</p>
            
        
            <form id="form" action="<?php echo base_url() ?>ot/cancel/<?php echo $data[0]['id'] ?>.html" class="form-horizontal" method="post">
                <fieldset>
                  <input type="hidden" name="delete" value="true" />
                  <div id="actions-buttons-forms" class="form-actions">
                    <button type="submit" class="btn btn-danger">Cancelar</button>
                    <input type="button" class="btn" onclick="history.back()" value="Regresar">
                  </div>
                </fieldset>
              </form>
        
        </div>
    </div><!--/span-->

</div><!--/row-->
			