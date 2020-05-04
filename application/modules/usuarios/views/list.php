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
$base_url = base_url();
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
            Overview
        </li>
    </ul>
</div>

<div class="row-fluid sortable">		
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2></h2>
            <div class="box-icon">
                  
                   <?php if( $access_create == true ): ?>
                   		<a href="<?php echo base_url() ?>usuarios/create.html" class="btn btn-link" title="Crear"><i class="icon-plus"></i></a>
				   <?php endif; ?>
                   <?php if( $access_request_new_user == true ): ?>
                   		<a href="<?php echo base_url() ?>usuarios/create_request_new_user.html" class="btn btn-link" title="Crear Petición nuevo usuario"><i class="icon-plus"></i></a>
				   <?php endif; ?>
                              
            </div>
        </div>
        <div class="box-content">
        
        	
            
            <?php // Show Messages ?>
            
            <?php if( isset( $message['type'] ) ): ?>
               
               
                <?php if( $message['type'] == true ): ?>
                    <div class="alert alert-success">
                          <button type="button" class="close" data-dismiss="alert">×</button>
                          <img src="<?php echo base_url() ?>images/true.png" width="20" height="20" />
                          <strong>Listo: </strong> <?php  echo $message['message']; // Show Dinamical message Success ?>
                    </div>
                <?php endif; ?>
               
                
                <?php if( $message['type'] == false ): ?>
                    <div class="alert alert-error">
                          <button type="button" class="close" data-dismiss="alert">×</button>
                          <img src="<?php echo base_url() ?>images/false.png" width="20" height="20" />
                          <strong>Error: </strong> <?php  echo $message['message']; // Show Dinamical message error ?>
                    </div>
                <?php endif; ?>
            
			
			
			<?php endif; ?>
            
            
            <div class="row">
            	
                <div class="span6"></div>
                
                <div class="span4"></div>
                
                <div class="span1">
                	                   
				   <?php if( $access_import == true ): ?><a href="<?php echo base_url() ?>usuarios/importar.html" class="btn pull-right">Importar</a><?php endif; ?>
                    
                </div>
                
                <div class="span1">
                	                  
				   <?php if( $access_export == true ): ?><button id="create-export" class="btn pull-right">Exportar</button><?php endif; ?>
                   
                    
                </div>
                
            </div>
            
            <br /><br />
            
            <form id="search" method="post">
            
                <div class="row">
                
                    
                    <div class="span1"></div>
                    <div class="span4 hide">Buscar <input type="text" id="find" name="find" class="searchfind"/></div>
                    <div class="span4"><a href="javascript:void(0)" id="showadvanced" class="btn- btn-link link-advanced">Mostrar Filtro</a></div>
                    
                </div>
                
               
                <div class="row advanced">
                    <div class="span7 pull-right">
                        <?php if( !empty( $rol ) ): foreach(  $rol  as $value ):if( isset( $filter ) and $filter == $value['id'] ) $class = 'btn-primary'; else $class = 'btn-link';?>
                            
                            <a href="javascript:void(0)" id="<?php echo $value['id'] ?>" class="btn  rol-search <?php echo $class ?>"><?php echo $value['name'] ?></a>
                        
                        <?php endforeach; endif; ?>
                        
                        <a href="javascript:void(0)" id="" class="btn btn-link rol-search">Todos</a>
                        
                    </div>
                </div>
                
                
                <div class="row">
                
                    
                    <div class="span1"></div>
                    
                    <div class="span7">
                       
                          <div class="row advanced">
                                
                                <input type="hidden" id="pag" name="pag" value="<?php echo base_url() . $pag ?>" /><input type="hidden" id="typeexport" />
                                <!--<input type="button" id="searchfind" value="Filtrar"  class="btn btn-link"/>-->
                                
                                <br /><br />
                                 <table class="table table-bordered bootstrap-datatable datatable">           	
                                        <tr>
                                          <td><input type="checkbox" name="advanced[]" class="checkboxadvance"  value="clave" /> Clave</td>
                                          <td> <input type="text" id="clave" class="hide input searchfind" /></td>
                                        </tr>
                                        
                                        <tr>
                                          <td><input type="checkbox" name="advanced[]" class="checkboxadvance"  value="birthdate" /> Fecha nac. </td>
                                          <td> <input type="text" id="birthdate" class="hide input searchfind" readonly="readonly"/></td>
                                        </tr>
                                        <tr>
                                          <td><input type="checkbox" name="advanced[]" class="checkboxadvance"  value="national" /> Folio Nac. </td>
                                          <td><input type="text" id="national" class="hide input searchfind" /></td>
                                        </tr>
                                        <tr>
                                          <td><input type="checkbox" name="advanced[]" class="checkboxadvance"  value="provincial" /> Folio prov. </td>
                                          <td><input type="text" id="provincial" class="hide input searchfind" /></td>
                                        </tr>
                                        <tr>
                                          <td><input type="checkbox" name="advanced[]" class="checkboxadvance"  value="manager_id" /> Gerente</td>
                                          <td><select id="manager_id" class="hide input searchfind" ><option value="">Seleccione</option><?php echo $gerentes ?></select></td>
                                        </tr>   
                                        <tr>
                                          <td><input type="checkbox" name="advanced[]" class="checkboxadvance"  value="name" /> Nombre. </td>
                                          <td><input type="text" id="name" class="hide input searchfind" /></td>
                                        </tr>     
                                        <tr>
                                          <td><input type="checkbox" name="advanced[]" class="checkboxadvance"  value="lastname" /> Apellido. </td>
                                          <td> <input type="text" id="lastname" class="hide input searchfind" /></td>
                                        </tr>     
                                        <tr>
                                          <td><input type="checkbox" name="advanced[]" class="checkboxadvance"  value="email" /> Correo. </td>
                                          <td> <input type="text" id="email" class="hide input searchfind" /></td>
                                        </tr>  
                                        <tr>
                                          <td><input type="checkbox" name="advanced[]" class="checkboxadvance"  value="license_expired_date" /> Fecha de ven. </td>
                                          <td><input type="text" id="license_expired_date" class="hide input searchfind"  readonly="readonly"/></td>
                                        </tr> 
                                        
                                        <tr>
                                          <td></td>
                                          <td><input type="button" class="btn btn-inverse searchfind filters" value="Filtrar"/></td>
                                        </tr>     
                                                                   
                                     </table>
                                
                                        
                                     <input type="hidden" name="rol" id="rolsearch" />
                                 
                                
                          </div>
                                                
                                            
                    </div>
                    
                </div>
            </form> 

            <div id="dialog-form" title="">
  				Que quiere exportar?
                <br /><br />
                <a href="javascript:void(0)" class="btn btn-link" id="pagactual">Página Actual</a>
                <a href="javascript:void(0)" class="btn btn-link" id="busactual">Resultado Actual</a>
			</div>

            <div id="loading"></div>
            
        
        	<?php if( !empty( $data ) ): ?>
            <table id="tablesorted" class="tablesorter table table-striped table-bordered bootstrap-datatable datatable">
              <thead>
                  <tr>
                      <th>Clave</th>
                      <th>Folio Nal</th> 
                      <th>Folio Prov</th>
                      <th>Gerente</th>
                      <th>Nombre</th>
                      <th>Correo</th>
                      <th>Tipo</th>
                      <th>Creado</th>
                      <th>Última modificación</th>
                      <th class="sorter-false">Acciones</th>
                  </tr>
              </thead>   
              <tbody id="data">
                <?php  foreach( $data as $value ):  ?>
                <tr>
                	<td class="center"><?php echo $value['clave'] ?></td>
                    <td class="center"><?php echo $value['national'] ?></td>
                    <td class="center"><?php echo $value['provincial'] ?></td>
                    <td class="center"><?php echo $value['manager_id'] ?></td>
                    <td class="center"><?php if( !empty( $value['company_name'] ) ) echo $value['company_name']; else  echo $value['name'] . ' ' . $value['lastnames'];  ?></td>
                    <td class="center"><?php echo $value['email'] ?></td>
                    <td class="center"><?php echo $value['tipo'] ?></td>
                    <td class="center"><?php echo $value['date'] ?></td>
                    <td class="center"><?php echo $value['last_updated'] ?></td>
                    <td>
                    	
                                                                        
                         <?php if( $access_update == true ): ?>
                        <a class="btn btn-info" href="<?php echo base_url() ?>usuarios/update/<?php echo $value['id'] ?>.html" title="Editar Usuario">
                            <i class="icon-edit icon-white"></i>            
                        </a>
                        <?php endif; ?>
                        <?php if( $access_delete && $value['is_deletable']): ?>
                        <a class="btn btn-danger" href="<?php echo base_url() ?>usuarios/delete/<?php echo $value['id'] ?>.html" title="Eliminar Usuario">
                            <i class="icon-trash icon-white"></i> 
                        </a>
                        <?php endif; ?>
                    </td>
                        
                    </td>
                </tr>
                <?php endforeach;  ?>                
              </tbody>
          </table>    
<div id="pager" class="pager">
	<form>
		<img src="<?php echo $base_url ?>ot/assets/style/icons/first.png" class="first"/>
		<img src="<?php echo $base_url ?>ot/assets/style/icons/prev.png" class="prev"/>
		<input type="text" class="pagedisplay" style="margin-top: 8px"/>
		<img src="<?php echo $base_url ?>ot/assets/style/icons/next.png" class="next"/>
		<img src="<?php echo $base_url ?>ot/assets/style/icons/last.png" class="last"/>
		<select class="pagesize input-mini" style="height: 20px; margin-top: 0px; font-size: 0.9em">
			<option selected="selected" value="10">10</option>
			<option value="20">20</option>
			<option value="30">30</option>
			<option value="40">40</option>
		</select>
	</form>
</div>
          <?php else: ?>
		  
		  	<div class="alert alert-block">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Warning: </strong> No records found, Add one <a href="<?php echo base_url() ?>usuarios/create.html" class="btn btn-link">here</a>
            </div>
		  <?php endif; ?>
                           
        </div>
    </div><!--/span-->

</div><!--/row-->
