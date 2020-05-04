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
            Importar
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

                        
            <?php if( isset( $message ) and !empty( $message ) ): ?>
            	<?php if( count($message)>0  ): ?>
            		<div class="alert alert-error">
		                  <button type="button" class="close" data-dismiss="alert">×</button>
		                  <strong>Error: </strong> 
						  <?php  // Show Dinamical message error 
						  		
								foreach( $message as $raiz ):
																
									if( empty( $raiz ) ) break;
										
																								
										foreach( $raiz as $array ):
											
											if( empty( $array ) ) break;
											
												foreach( $array as $messagetext ):
													
													if( empty( $messagetext ) ) break;
															
															echo $messagetext.'<br>';
														
												endforeach;
											
											
										endforeach;
										
										
															
								endforeach;
						  
						  ?>
		            </div>
            	<?php endif; ?>
            	<?php if( count($message)==0  ): ?>
            		<div class="alert alert-success">
					  Se han agregado correctamente los agentes.
					</div>
            	<?php endif; ?>
            
            <?php endif; ?>
            
            
                    	
            <form id="form" action="<?php echo base_url() ?>usuarios/importar.html" class="form-horizontal" method="post" enctype="multipart/form-data">
              <fieldset>
                <div class="control-group error">
                  <label class="control-label" for="inputError">Archivo de usuario: </label>
                  <div class="controls">
                    <input  class="input-file uniform_on" id="fileInput" name="file" type="file"><br />
                    <small class="text">Archivo CSV</small>
                  </div>
                </div>
               
               
                <div id="actions-buttons-forms" class="form-actions">
                  <button type="submit" class="btn btn-primary">Cargar</button>
                  <button  type="button" class="btn" onclick="javascript: history.back()">Cancelar</button>
                </div>
              </fieldset>
            </form>
        	
            
            <?php if( isset( $tmp_file ) ): // Is is load a file?>
            
            <form action="<?php echo base_url() ?>usuarios/importar.html" id="create-users-form-csv" method="post">
            
            <input type="hidden" name="tmp_file" value="<?php echo $tmp_file ?>">
            
            <div class="alert alert-info">
            	Especifique a qué campos corresponde la información que está importando en las siguientes cajas de selección
            </div>
            
            <div style="max-width:100%; overflow:scroll">
            
            <table class="table table-rounder">
            
            
            <?php
           		  if( !empty( $file_array ) ):  // Create select for fields
				  		
						foreach( $file_array as $rows ):
							
							if( !empty( $rows ) ):
								
								echo '<tr>';
								
								$i=0;
								
								foreach( $rows as $value ):
																							
								echo'<td class="column'.$i.'">
										<select class="required" id="select'.$i.'" name="'.$i.'" style="width:100px;" onchange="hide('.$i.')">
											<option value="">Seleccione</option>
											<option value="disabled">Activar</option>
											<option value="lastname">Apellidos</option>
											<option value="lastname_r">Apellidos de representante persona Moral</option>
											<option value="password">Contraseña</option>
											<option value="email">Correo</option>
											<option value="clave">Clave</option>
											<option value="office_ext">Extensión</option>
											<option value="license_expired_date">Expiración de licencia</option>											
											<option value="connection_date">Fecha de conexión</option>
											<option value="type">En proceso de conexión</option>
											<option value="birthdate">Fecha de nacimiento</option>											
											<option value="folio_nacional"">Folio Nacional</option>
											<option value="folio_provincial">Folio Provincial</option>
											<option value="manager_id">Gerente</option>
											<option value="name">Nombre</option>
											<option value="company_name">Nombre de compañía</option>
											<option value="name_r">Nombre de representante persona Moral</option>											
											<option value="office_id">Oficina</option>	
											<option value="group">Rol</option>
											<option value="office_phone">Teléfono oficina</option>											
											<option value="mobile">Teléfono movil</option>
											<option value="persona">Tipo de persona</option>
											<option value="username">Usuario</option>
											
											
											
										</select>
										
									 </td>'; 
								
								$i++;
									 
								endforeach;
            	  				break;
								echo '</tr>'; 
								
							endif;
				  
				  		endforeach;
				  
				  endif; 
			?>
            
                        
            	              
            <?php
           		  if( !empty( $file_array ) ):  // Show data
				  		
						foreach( $file_array as $rows ):
							
							if( !empty( $rows ) ): $i=0;
								
								echo '<tr>';
								
								foreach( $rows as $value ): 
								
									echo '<td class="column'.$i.'">'.$value.'</td>'; 
								$i++;
								endforeach;
            	  				
								echo '</tr>'; 
								
							endif;
				  
				  		endforeach;
				  
				  endif; 
			?>
            
            </table>
            
            </div>
            
            <div id="actions-buttons-forms-send" class="form-actions">
              <button type="submit" class="btn btn-primary">Importar</button>
              <button  type="button" class="btn" onclick="javascript: history.back()">Cancelar</button>
            </div>
            
            
            </form>
           
            <?php endif; ?>
            
        </div>
    </div><!--/span-->

</div><!--/row-->
			