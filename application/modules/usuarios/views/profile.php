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
            <a href="<?php echo base_url() ?>usuarios.html">Usuarios</a> <span class="divider">/</span>
        </li>
        
        <li>
            Pefil
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
        	
            
			
			<?php // Return Message error ?>
            
            <?php $validation = validation_errors(); ?>
            
            <?php if( !empty( $validation ) ): ?>
            <div class="alert alert-error">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Error: </strong> <?php  echo $validation; // Show Dinamical message error ?>
            </div>
            <?php endif; ?>
            
            
            
        
            <form id="form" action="<?php echo base_url() ?>usuarios/editar_perfil/<?php echo $data['id'] ?>.html" class="form-horizontal" method="post" enctype="multipart/form-data">
                <fieldset>
                                  
                  
                  
                 
                  
                                 
                  
                  <div class="control-group">
                    <label class="control-label  text-error" for="inputError">Usuario</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" id="username" name="username" type="text" value="<?php echo set_value('username', $data['username']) ?>">
                    </div>
                  </div>
                 
                 <div class="control-group">
                    <label class="control-label  text-error" for="inputError">Contraseña Anterior</label>
                    <div class="controls">
                      <input class="input-xlarge focused" id="passwordlast" name="passwordlast" type="password">
                    </div>
                  </div>
                 
                 <div class="control-group">
                    <label class="control-label  text-error" for="inputError">Contraseña</label>
                    <div class="controls">
                      <input class="input-xlarge focused" id="password" name="password" type="password">
                    </div>
                  </div>
                  
                  
                  <div class="control-group">
                    <label class="control-label  text-error" for="inputError">Confirmar Contraseña</label>
                    <div class="controls">
                      <input class="input-xlarge focused" id="passwordnew" name="passwordnew" type="password">
                    </div>
                  </div>
                  
                  <div class="control-group">
                    <label class="control-label  text-error" for="inputError">Correo</label>
                    <div class="controls">
                      <input class="input-xlarge focused required email" id="email" name="email" type="text" value="<?php echo set_value('email', $data['email']) ?>">
                    </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label  text-error" for="inputError">Correo Alternativo</label>
                    <div class="controls">
                      <input class="input-xlarge focused required email" id="email2" name="email2" type="text" value="<?php echo set_value('email2', $data['email2']) ?>">
                    </div>
                  </div>
                  
                  <div class="control-group">
                    <label class="control-label  text-error" for="inputError">Imagen: </label>
                    <div class="controls">
                      <img src="<?php echo base_url() . 'usuarios/assets/profiles/' . $data['picture'] ?>" />
                      <input type="file" name="imagen" /><br /><input type="hidden" name="deleteimage" id="deleteimage" value="false" /><br /><input type="button" id="delimage" value="Borrar Imagen" />
                    </div>
                  </div>
                  
                  
                  
                                    
                  <div id="actions-buttons-forms" class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button class="btn" onclick="javascript: history.back()">Cancelar</button>
                  </div>
                </fieldset>
              </form>
        
        </div>
    </div><!--/span-->

</div><!--/row-->
			