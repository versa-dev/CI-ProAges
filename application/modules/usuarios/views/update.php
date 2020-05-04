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
            Editar
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
            
            
                    
            <form id="form" action="<?php echo base_url() ?>usuarios/update/<?php echo $data[0]['id']?>.html" class="form-horizontal" method="post" enctype="multipart/form-data">
                <fieldset>
                  
                  
                  
                  <?php $rol=''; if( !empty( $data['users_vs_user_roles'] ) ): foreach( $data['users_vs_user_roles']  as $value ) $rol .= $value['user_role_id'].',' ?>
                  	<input type="hidden" id="setrole" value="<?php echo $rol ?>" />
                  <?php endif; ?>
                  
                  
                  <div class="control-group ">
                    <label class="control-label text-error" for="inputError">Rol</label>
                    <div class="controls">
                      
                      <?php echo $group ?>
                      
                    </div>
                  </div>
                                                      
                  
                  <div class="control-group input-agente">
                    <label class="control-label text-error" for="inputError">Persona</label>
                    <div class="controls">
                      
                      <?php if( empty( $data['representatives'] ) ): $fisica=true; $moral=false; else: $fisica=false; $moral=true; endif;?>
                      <input type="radio" value="fisica" name="persona" class="persona" <?php echo set_radio('persona', 'fisica', $fisica); ?> />&nbsp;&nbsp;Física
                      <input type="radio" value="moral" name="persona"  class="persona" <?php echo set_radio('persona', 'moral', $moral); ?> />&nbsp;&nbsp;Moral
                    
                    </div>
                  </div>
                  
                  
                  
                  
                  
                  
                  <div class="control-group input-agente">
                    <label class="control-label text-error" for="inputError">En proceso de conexión</label>
                    <div class="controls">
                      
                      <?php if( empty( $data['agent_uids'] ) ): $si=true; $no=false; else: $si=false; $no=true; endif; ?>
                      <input type="radio" value="Si" name="type" class="agente" <?php echo set_radio('type', 'Si', $si); ?>/>&nbsp;&nbsp;Si
                      <input type="radio" value="No" name="type" class="agente"  <?php echo set_radio('type', 'No', $no); ?>/>&nbsp;&nbsp;No
                    </div>
                  </div>
                  
                 
                 
                 
                                   
                  <?php $clave = false; // For is not exist ?>
                  
                  <?php if( !empty( $data['agent_uids'] ) ): foreach( $data['agent_uids'] as $agent_uids ):?>                  
                  	
                      <?php if( in_array( 'clave', $agent_uids ) ): $clave = true; ?>
                      <div class="control-group input-novel-agente">
                        <label class="control-label text-error" for="inputError">Clave</label>
                        <div class="controls">
                          <input class="input-xlarge focused required" id="clave" name="clave" type="text" value="<?php echo set_value('clave', $agent_uids['uid']) ?>">
                        </div>
                      </div>
                      <?php endif; ?>
                  
                  <?php endforeach; endif; ?>
                  
                  <?php if( empty( $data['agent_uids'] ) or $clave == false ): // Add Field id not exist ?>
                  		
                       <div class="control-group input-agente input-novel-agente">
                        <label class="control-label text-error" for="inputError">Clave</label>
                        <div class="controls">
                          <input class="input-xlarge focused required" id="clave" name="clave" type="text" value="<?php echo set_value('clave') ?>">
                        </div>
                      </div>
                    
                  <?php endif; ?>
                  
                  
                 
                  
				  
				  
				  <?php $i=0; $clave = false; // For is not exist ?>
                  
                  <?php if( !empty( $data['agent_uids'] ) ): ?>         
                  <div class="control-group input-agente input-novel-agente">
                    <label class="control-label text-error" for="inputError">Folio Nacional</label>
                    <div class="controls">
                      
                      
					  
					  <?php $i=0; foreach( $data['agent_uids'] as $agent_uids ): if( in_array( 'national', $agent_uids ) ): $clave = true; ?>
                      	 
                         <?php if( $i > 0 ): ?>
                         <div id="fieldNational-<?php echo $i ?>"><br>
                         <?php endif; ?>	
                            
                            <input class="input-xlarge focused required" name="folio_nacional[]" type="text" value="<?php echo $agent_uids['uid']?>">
                        
						<?php if( $i > 0 ): ?>
                        <a href="javascript:void(0)" onclick="moral_folio_national(<?php echo $i ?>)" class="btn btn-link" >-</a><br></div>
						<?php endif; ?>
                        
                        
						<?php if( $i== 0 ): ?><a href="javascript:void(0)" id="folio_nacional_add" class="btn btn-link" >+</a> <?php endif; ?>
                        
                        
                      <?php  $i++; endif; endforeach;  ?> 
                      
					 <?php if( $clave == false ): // Add Field id not exist ?>
                      <input class="input-xlarge focused required" name="folio_nacional[]" type="text"><a href="javascript:void(0)" id="folio_nacional_add" class="btn btn-link" >+</a> 
                     <?php endif; ?>
                      
                      
                      
                      <div id="folio_nacional_fields"></div>
                    </div>
                  </div>
                  <?php endif; ?>
                  
                  
                  <?php if( empty( $data['agent_uids'] ) or $clave == false ): ?>
				  
                      <div class="control-group input-agente input-novel-agente">
                        <label class="control-label text-error" for="inputError">Folio Nacional</label>
                        <div class="controls">
                          <input class="input-xlarge focused required" name="folio_nacional[]" type="text"> 
                           <a href="javascript:void(0)" id="folio_nacional_add" class="btn btn-link" >+</a>
                          <div id="folio_nacional_fields"></div>
                        </div>
                      </div>
                  
                  <?php endif; ?>
                  <input type="hidden" id="countFolioNational" value="<?php echo $i+1 ?>" />
                  
                  
                  
                         
                         
                         
                         
                         
                         
                         
                         
                                    
                  <?php $i=0; $clave = false; // For is not exist ?>
                  
                  <?php if( !empty( $data['agent_uids'] ) ): ?>     
                  <div class="control-group input-novel-agente">
                    <label class="control-label text-error" for="inputError">Folio Provicional</label>
                    <div class="controls">
                       
                       
                                              
					  	<?php $i=0; foreach( $data['agent_uids'] as $agent_uids ): if( in_array( 'provincial', $agent_uids ) ): $clave = true; ?>
                      	
                        <?php if( $i > 0 ): ?>
                         <div id="fieldProvincial-<?php echo $i ?>"><br>
                        <?php endif; ?>	
                        
                        	<input class="input-xlarge focused required" name="folio_provincial[]" type="text" value="<?php echo $agent_uids['uid']?>">
                      
                      
						<?php if( $i > 0 ): ?>
                         <a href="javascript:void(0)" onclick="moral_folio_provincial(<?php echo $i ?>)" class="btn btn-link" >-</a><br></div>
                        <?php endif; ?>
                          
					  
					  	<?php if( $i== 0 ): ?><a href="javascript:void(0)" id="folio_provicional_add" class="btn btn-link" >+</a> <?php endif; ?>
                      
                      <?php $i++;  endif; endforeach;  ?> 
                      
					 
					 <?php if( $clave == false ): // Add Field id not exist ?>
                     
                      <input class="input-xlarge focused required" name="folio_provincial[]" type="text"><a href="javascript:void(0)" id="folio_provicional_add" class="btn btn-link" >+</a> 
                     <?php endif; ?>
                       
                                             
                       
                      <div id="folio_provicional_fields"></div>
                    </div>
                  </div>
                  <?php endif; ?>
                  
                  
                  <?php if( empty( $data['agent_uids'] ) or $clave == false ): ?>
				  
                      <div class="control-group input-agente input-novel-agente">
                        <label class="control-label text-error" for="inputError">Folio Provicional</label>
                        <div class="controls">
                          <input class="input-xlarge focused required" name="folio_provincial[]" type="text">
                           <a href="javascript:void(0)" id="folio_provicional_add" class="btn btn-link" >+</a>
                          <div id="folio_provicional_fields"></div>
                        </div>
                      </div>
                  
                  <?php endif; ?>
                  
                  <input type="hidden" id="countFolioProvincial" value="<?php echo $i+1 ?>" />
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                 <?php if( isset( $data['agents'][0]['connection_date'] ) ) $connection_date = $data['agents'][0]['connection_date']; else  $connection_date=''; ?>
                 <div class="control-group input-novel-agente">
                    <label class="control-label" for="inputError">Fecha de conexión</label>
                    <div class="controls">
                      <input class="input-xlarge focused" id="connection_date" name="connection_date" type="text" readonly="readonly" value="<?php echo set_value('connection_date',  $connection_date) ?>">
                    </div>
                  </div>
                  
                  <?php if( isset( $data['agents'][0]['license_expired_date'] ) ) $license_expired_date = $data['agents'][0]['license_expired_date']; else  $license_expired_date=''; ?>
                  <div class="control-group input-novel-agente">
                    <label class="control-label" for="inputError">Vencimiento de cédula</label>
                    <div class="controls">
                      <input class="input-xlarge focused" id="license_expired_date" name="license_expired_date" type="text" readonly="readonly" value="<?php echo set_value('license_expired_date', $license_expired_date) ?>">
                    </div>
                  </div>
                 
                 
                 
                 
                 
                 
                 <div class="control-group input-agente">
                    <label class="control-label" for="inputError">Gerente</label>
                    <div class="controls">
                      <select class="input-xlarge focused" id="manager_id" name="manager_id">
                      	<option value="0">Seleccione</option>
                        <?php echo $gerentes ?>
                      </select>
                    </div>
                  </div>
                 
                 
                 
                 <div class="control-group  input-moral">
                    <label class="control-label text-error" for="inputError"> Nombre de compañía:</label>
                    <div class="controls">
                      <input class="input-xlarge focused" id="company_name" name="company_name" type="text" value="<?php echo set_value('company_name', $data[0]['company_name']) ?>">
                    </div>
                  </div>
                
                 
                 
                 
                 <div class="control-group input-fisica">
                    <label class="control-label text-error" for="inputError">Nombre</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" id="name" name="name" type="text" value="<?php echo set_value('name', $data[0]['name']) ?>">
                    </div>
                  </div>
                  
                  
                  <div class="control-group input-fisica">
                    <label class="control-label text-error" for="inputError">Apellidos</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" id="lastname" name="lastname" type="text" value="<?php echo set_value('lastname', $data[0]['lastnames']) ?>">
                    </div>
                  </div>
                 
                  <?php if( $data[0]['birthdate'] != '0000-00-00' ) $birthdate=$data[0]['birthdate']; else $birthdate=''; ?>
                  <div class="control-group input-fisica">
                    <label class="control-label" for="inputError">Fecha de nacimiento</label>
                    <div class="controls">
                      <input class="input-xlarge focused" id="birthdate" name="birthdate" type="text" readonly="readonly" value="<?php echo set_value('birthdate', $birthdate) ?>">
                    </div>
                  </div>
                	
                   
                   
                   <!-- ¨Persona Moral Settings -->
                   <fieldset class="block-moral">
                   	  
                       <?php $i=0;if( !empty( $data['representatives'] ) ): foreach( $data['representatives'] as $representatives ): ?>
                       
                       <?php if( $i > 0 ): ?> 
                       <div id="moral<?php echo $i ?>"><br><hr> 
                       <?php endif; ?>
                        
                   	   <h5 class="input-moral">Datos de representante</h5>                      
                       <div class="control-group input-moral">
                        <label class="control-label text-error" for="inputError">Nombre</label>
                        <div class="controls">
                          <input class="input-xlarge focused required" name="name_r[]" type="text" value="<?php echo $representatives['name'] ?>">
                        </div>
                      </div>
                      
                      
                      <div class="control-group input-moral">
                        <label class="control-label text-error" for="inputError">Apellidos</label>
                        <div class="controls">
                          <input class="input-xlarge focused required" name="lastname_r[]" type="text" value="<?php echo $representatives['lastnames'] ?>">
                        </div>
                      </div>
                       
                       
                     
                       <div class="control-group input-moral">
                        <label class="control-label text-error" for="inputError">Teléfono oficina</label>
                        <div class="controls">
                          <input class="input-xlarge focused required" name="office_phone[]" type="text" value="<?php echo $representatives['office_phone'] ?>">
                        </div>
                      </div>
                      
                       <div class="control-group input-moral">
                        <label class="control-label text-error" for="inputError">Extensión</label>
                        <div class="controls">
                          <input class="input-xlarge focused required" name="office_ext[]" type="text" value="<?php echo $representatives['office_ext'] ?>"> 
                        </div>
                      </div>
                      
                      <div class="control-group input-moral">
                        <label class="control-label text-error" for="inputError">Teléfono movil</label>
                        <div class="controls">
                          <input class="input-xlarge focused required" name="mobile[]" type="text" value="<?php echo $representatives['mobile'] ?>">
                        </div>
                      </div>
                  	  
                      <?php if( $i > 0 ): ?> 
                       <a href="javascript:void(0)" onclick="moral_remove(<?php echo $i ?>)" class="btn btn-link input-moral" >- Eliminar este grupo.</a>
                      	
                      </div>
                      <?php endif; ?>
                     
                      	
                      <?php $i++; endforeach; ?>
                      
                      <div id="moral-fields" class="input-moral"></div>
                  	  
                       <a href="javascript:void(0)" id="moral_add" class="btn btn-link input-moral pull-right" >+ Agregar campos para representantes morales.</a> 
                      
                      
                      <?php endif; ?>
                      	
                      
                      <input type="hidden" id="countMoralPerson" value="<?php echo $i+1 ?>" />
                      
                      
                      
                      <?php if( empty( $data['representatives'] ) ): ?>
                      		
                            <h5 class="input-moral">Datos de representante</h5>                      
                       <div class="control-group input-moral">
                        <label class="control-label text-error" for="inputError">Nombre</label>
                        <div class="controls">
                          <input class="input-xlarge focused required" name="name_r[]" type="text">
                        </div>
                      </div>
                      
                      
                      <div class="control-group input-moral">
                        <label class="control-label text-error" for="inputError">Apellidos</label>
                        <div class="controls">
                          <input class="input-xlarge focused required" name="lastname_r[]" type="text">
                        </div>
                      </div>
                       
                       
                     
                       <div class="control-group input-moral">
                        <label class="control-label text-error" for="inputError">Teléfono oficina</label>
                        <div class="controls">
                          <input class="input-xlarge focused required" name="office_phone[]" type="text">
                        </div>
                      </div>
                      
                       <div class="control-group input-moral">
                        <label class="control-label text-error" for="inputError">Extensión</label>
                        <div class="controls">
                          <input class="input-xlarge focused required" name="office_ext[]" type="text">
                        </div>
                      </div>
                      
                      <div class="control-group input-moral">
                        <label class="control-label text-error" for="inputError">Teléfono movil</label>
                        <div class="controls">
                          <input class="input-xlarge focused required" name="mobile[]" type="text">
                        </div>
                      </div>
                  		
                      <div id="moral-fields" class="input-moral"></div>
                  	  
                       <a href="javascript:void(0)" id="moral_add" class="btn btn-link input-moral pull-right" >+ Agregar campos para representantes morales.</a>
                      
                      <?php endif; ?>
                      
                  </fieldset>
                  
                  
                   
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                 
                  
                                 
                  
                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Usuario</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" id="username" name="username" type="text" value="<?php echo set_value('username', $data[0]['username']) ?>">
                    </div>
                  </div>
                 
                 
                 <div class="control-group">
                    <label class="control-label" for="inputError">Contraseña</label>
                    <div class="controls">
                      <input class="input-xlarge focused" id="password" name="password" type="password" value="<?php echo set_value('password') ?>">
                    </div>
                  </div>
                  
                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Correo</label>
                    <div class="controls">
                      <input class="input-xlarge focused required email" id="email" name="email" type="text" value="<?php echo set_value('email', $data[0]['email']) ?>">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Correo Alternativo</label>
                    <div class="controls">
                      <input class="input-xlarge focused email" id="email2" name="email2" type="text" value="<?php echo set_value('email2', $data[0]['email2']) ?>">
                    </div>
                  </div>
                  
                  <div class="control-group">
                    <label class="control-label error" for="inputError">Imagen: </label>
                    <div class="controls">
                      <img src="<?php echo base_url() . 'usuarios/assets/profiles/' . $data[0]['picture'] ?>" />
                      <input type="file" name="imagen" />
                      <small><br />Tamaño máximo: <?php echo ini_get('upload_max_filesize') ?></small>
                    </div>
                  </div>
                  
                  
                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Activar</label>
                    <div class="controls">
                      <?php if( $data[0]['disabled'] == 1 ) $check = true; else $check = false; ?>
                      <input type="radio" value="Si" name="disabled"  <?php echo set_radio('disabled', 'Si', $check); ?>/>&nbsp;&nbsp;Si
                      <?php if( $data[0]['disabled'] == 0 ) $check = true; else $check = false; ?>
                      <input type="radio" value="No" name="disabled" <?php echo set_radio('disabled', 'No', $check); ?>/>&nbsp;&nbsp;No
                    </div>
                  </div>
                  
                  
                  
                  
                  
                  
                                    
                  <div id="actions-buttons-forms" class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <input type="button" class="btn" onclick="javascript: history.back();" value="Cancelar">
                  </div>
                </fieldset>
              </form>
        
        </div>
    </div><!--/span-->

</div><!--/row-->
			