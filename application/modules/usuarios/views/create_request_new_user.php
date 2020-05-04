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
            Crear
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
        	
            
			
			<?php // Return Message text-error ?>
            
            <?php $validation = validation_errors(); ?>
            
            <?php if( !empty( $validation ) ): ?>
            <div class="alert alert-text-error">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>text-error: </strong> <?php  echo $validation; // Show Dinamical message text-error ?>
            </div>
            <?php endif; ?>
            
            
            
        
            <form id="form" action="<?php echo base_url() ?>usuarios/create_request_new_user.html" class="form-horizontal" method="post" enctype="multipart/form-data">
                <fieldset>
                  
                  <div class="control-group ">
                    <label class="control-label text-error" for="inputtext-error">Rol</label>
                    <div class="controls">
                      
                      <?php echo $group ?>
                      
                    </div>
                  </div>
                                                      
                  
                  <div class="control-group input-agente">
                    <label class="control-label text-error" for="inputtext-error">Persona</label>
                    <div class="controls">
                      <input type="radio" value="fisica" name="persona" class="persona" <?php echo set_radio('persona', 'fisica', true); ?> />&nbsp;&nbsp;Física
                      <input type="radio" value="moral" name="persona"  class="persona" <?php echo set_radio('persona', 'moral'); ?> />&nbsp;&nbsp;Moral
                    </div>
                  </div>
                  
                  <input type="hidden" id="countMoralPerson" value="1" />
                  <input type="hidden" id="countFolioNational" value="1" />
                  <input type="hidden" id="countFolioProvincial" value="1" />
                  
                  <div class="control-group input-agente">
                    <label class="control-label text-error" for="inputtext-error">En proceso de conexión</label>
                    <div class="controls">
                      <input type="radio" value="Si" name="type" class="agente" <?php echo set_radio('type', 'Si'); ?>/>&nbsp;&nbsp;Si
                      <input type="radio" value="No" name="type" class="agente"  <?php echo set_radio('type', 'No', true); ?>/>&nbsp;&nbsp;No
                    </div>
                  </div>
                  
                          
                  
                  <div class="control-group input-novel-agente">
                    <label class="control-label text-error" for="inputtext-error">Clave</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" id="clave" name="clave" type="text" value="<?php echo set_value('clave') ?>">
                    </div>
                  </div>
                  
                  <div class="control-group input-novel-agente">
                    <label class="control-label text-error" for="inputtext-error">Folio Nacional</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" name="folio_nacional[]" type="text"> 
                       <a href="javascript:void(0)" id="folio_nacional_add" class="btn btn-link" >+</a>
                      <div id="folio_nacional_fields"></div>
                    </div>
                  </div>
                  
                  
                  <div class="control-group input-novel-agente">
                    <label class="control-label text-error" for="inputtext-error">Folio Provicional</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" name="folio_provincial[]" type="text">
                       <a href="javascript:void(0)" id="folio_provicional_add" class="btn btn-link" >+</a>
                      <div id="folio_provicional_fields"></div>
                    </div>
                  </div>
                  
                  
                  
                  
                 <div class="control-group hide input-novel-agente">
                    <label class="control-label" for="inputtext-error">Fecha de conexión</label>
                    <div class="controls">
                      <input class="input-xlarge" id="connection_date" name="connection_date" type="text" readonly="readonly" value="<?php echo set_value('connection_date') ?>">
                    </div>
                  </div>
                  
                  
                  <div class="control-group hide input-novel-agente">
                    <label class="control-label" for="inputtext-error">Vencimiento de cédula</label>
                    <div class="controls">
                      <input class="input-xlarge" id="license_expired_date" name="license_expired_date" type="text" readonly="readonly" value="<?php echo set_value('license_expired_date') ?>">
                    </div>
                  </div>
                 
                 
                 
                 
                 
                 
                 <div class="control-group input-agente">
                    <label class="control-label" for="inputtext-error">Gerente</label>
                    <div class="controls">
                      <select class="input-xlarge focused" id="manager_id" name="manager_id">
                      	<option value="0">Seleccione</option>
                        <?php echo $gerentes ?>
                      </select>
                    </div>
                  </div>
                 
                 
                 
                 <div class="control-group input-moral">
                    <label class="control-label text-error" for="inputtext-error"> Nombre de compañía:</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" id="company_name" name="company_name" type="text" value="<?php echo set_value('company_name') ?>">
                    </div>
                  </div>
                
                 
                 
                 
                 <div class="control-group input-fisica ">
                    <label class="control-label text-error" for="inputtext-error">Nombre</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" id="name" name="name" type="text" value="<?php echo set_value('name') ?>">
                    </div>
                  </div>
                  
                  
                  <div class="control-group input-fisica ">
                    <label class="control-label text-error" for="inputtext-error">Apellidos</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" id="lastname" name="lastname" type="text" value="<?php echo set_value('lastname') ?>">
                    </div>
                  </div>
                 
                  
                  <div class="control-group input-fisica ">
                    <label class="control-label" for="inputtext-error">Fecha de nacimiento</label>
                    <div class="controls">
                      <input class="input-xlarge focused" id="birthdate" name="birthdate" type="text" readonly="readonly" value="<?php echo set_value('birthdate') ?>">
                    </div>
                  </div>
                	
                   
                   <!-- ¨Persona Moral Settings -->
                   <fieldset class="block-moral">
                   
                   	   <h5 class="input-moral">Datos de representante</h5>                      
                       <div class="control-group input-moral">
                        <label class="control-label" for="inputtext-error">Nombre</label>
                        <div class="controls">
                          <input class="input-xlarge focused" name="name_r[]" type="text">
                        </div>
                      </div>
                      
                      
                      <div class="control-group input-moral">
                        <label class="control-label" for="inputtext-error">Apellidos</label>
                        <div class="controls">
                          <input class="input-xlarge focused" name="lastname_r[]" type="text">
                        </div>
                      </div>
                       
                       
                     
                       <div class="control-group input-moral">
                        <label class="control-label" for="inputtext-error">Teléfono oficina</label>
                        <div class="controls">
                          <input class="input-xlarge focused" name="office_phone[]" type="text">
                        </div>
                      </div>
                      
                       <div class="control-group input-moral">
                        <label class="control-label" for="inputtext-error">Extensión</label>
                        <div class="controls">
                          <input class="input-xlarge focused" name="office_ext[]" type="text">
                        </div>
                      </div>
                      
                      <div class="control-group input-moral">
                        <label class="control-label" for="inputtext-error">Teléfono movil</label>
                        <div class="controls">
                          <input class="input-xlarge focused" name="mobile[]" type="text">
                        </div>
                      </div>
                  		
                      <div id="moral-fields" class="input-moral"></div>
                  	  
                       <a href="javascript:void(0)" id="moral_add" class="btn btn-link input-moral pull-right" >+ Agregar campos para representantes morales.</a> 
                      
                  </fieldset>
                  
                  
                   
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                 
                  
                                 
                  
                  <div class="control-group">
                    <label class="control-label text-error" for="inputtext-error">Usuario</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" id="username" name="username" type="text" value="<?php echo set_value('username') ?>">
                    </div>
                  </div>
                 
                 
                 <div class="control-group">
                    <label class="control-label text-error" for="inputtext-error">Contraseña</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" id="password" name="password" type="password" value="<?php echo set_value('password') ?>">
                    </div>
                  </div>
                  
                  <div class="control-group">
                    <label class="control-label text-error" for="inputtext-error">Correo</label>
                    <div class="controls">
                      <input class="input-xlarge focused required email" id="email" name="email" type="text" value="<?php echo set_value('email') ?>">
                    </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Correo Alternativo</label>
                    <div class="controls">
                      <input class="input-xlarge focused email" id="email2" name="email2" type="text" value="<?php echo set_value('email2') ?>">
                    </div>
                  </div>
                  
                  <div class="control-group">
                    <label class="control-label text-error" for="inputtext-error">Imagen: </label>
                    <div class="controls">
                      <input type="file" name="imagen" />
                      <small><br />Tamaño máximo: <?php echo ini_get('upload_max_filesize') ?></small>
                    </div>
                  </div>
                  
                  <input type="hidden" name="disabled" value="No" />
                                  
                  
                  
                  
                  
                  
                                    
                  <div id="actions-buttons-forms" class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button class="btn" onclick="javascript: history.back()">Cancelar</button>
                  </div>
                </fieldset>
              </form>
        
        </div>
    </div><!--/span-->

</div><!--/row-->
			