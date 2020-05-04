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
            <a href="<?php echo base_url() ?>modulos.html">Role</a> <span class="divider">/</span>
        </li>
        <li>
             <a href="<?php echo base_url() ?>modulos/update/<?php echo $data['id'] ?>.html"> Editar </a> <span class="divider">/</span> 
        </li>
        <li>
            <?php echo $data['name'] ?>
        </li>
    </ul>
</div>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-edit"></i> Editar Módulo</h2>
            <div class="box-icon">
                <a href="<?php echo base_url() ?>modulos/delete/<?php echo $data['id'] ?>.html" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
            </div>
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
            
            
            
        
            <form id="form" action="<?php echo base_url() ?>modulos/update/<?php echo $data['id'] ?>.html" class="form-horizontal" method="post">
                <fieldset>
                  
                  <div class="control-group error">
                    <label class="control-label" for="inputError">Nombre</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" id="name" name="name" type="text" placeholder="El nombre del módulo" value="<?php echo $data['name'] ?>">
                    </div>
                  </div>
                  
                  <div class="control-group">
                    <label class="control-label" for="inputError">Label</label>
                    <div class="controls">
                      <input class="input-xlarge focused" id="label" name="label" type="text" value="<?php echo $data['label'] ?>">
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
			