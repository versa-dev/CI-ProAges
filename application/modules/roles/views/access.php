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
            <a href="<?php echo base_url() ?>roles.html">Roles</a> <span class="divider">/</span>
        </li>
        <li>
             <a href="<?php echo base_url() ?>roles/access/<?php echo $data['id'] ?>.html"> Acceso</a> <span class="divider">/</span> 
        </li>
        <li>
            <?php echo $data['name'] ?>
        </li>
    </ul>
</div>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-edit"></i> Editar rol</h2>
           
        </div>
        
        <div class="box-content">
        	
            
			
			<?php // Return Message error ?>
            
            <?php $validation = validation_errors(); ?>
            
            <?php if( !empty( $message ) ): ?>
            <div class="alert alert-error">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Error: </strong> <?php  echo $validation; // Show Dinamical message error ?>
            </div>
            <?php endif; ?>
            
            
            
        
            <form id="form" action="<?php echo base_url() ?>roles/access/<?php echo $data['id'] ?>.html" class="form-horizontal" method="post">
                
                <input type="hidden" name="accesss" value="true" />
                 <fieldset>
                  <div class="control-group error">
                    <label class="control-label" for="inputError"></label>
                    <div class="controls">
                      <?php echo $rol_access ?>
                    </div>
                  </div>
                  
                  <div id="actions-buttons-forms" class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button class="btn" onclick="history.back()">Cancelar</button>
                  </div>
                </fieldset>
				
				
				
				
            </form>
        
        </div>
    </div><!--/span-->

</div><!--/row-->
			