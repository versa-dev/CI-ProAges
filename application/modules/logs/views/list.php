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
            Logs
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
        
        	            
        	<?php if( !empty( $data ) ): ?>
            <table class="table table-striped table-bordered bootstrap-datatable datatable">
              <thead>
                  <tr>
                      <th>Módulo</th>
                      <th>Label</th>
                      <th>Creado</th>
                      <th>Última modificación</th>
                      <th>Actions</th>
                  </tr>
              </thead>   
              <tbody>
                <?php  foreach( $data as $value ):  ?>
                <tr>
                    <td><?php echo $value['name'] ?></td>
                    <td><?php echo $value['label'] ?></td>
                    <td class="center"><?php echo $value['date'] ?></td>
                    <td class="center"><?php echo $value['last_updated'] ?></td>
                    <td class="center">
                        <?php if( $access_update == true ): ?>
                        <a class="btn btn-info" href="<?php echo base_url() ?>modulos/update/<?php echo $value['id'] ?>.html" title="Editar rol">
                            <i class="icon-edit icon-white"></i>            
                        </a>
                        <?php endif; ?>
                        <?php if( $access_delete == true ): ?>
                        <a class="btn btn-danger" href="<?php echo base_url() ?>modulos/delete/<?php echo $value['id'] ?>.html" title="Eliminar rol">
                            <i class="icon-trash icon-white"></i> 
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach;  ?>                
              </tbody>
          </table>    
          
          
          
          
          <?php else: ?>
		  
		  	<div class="alert alert-block">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Atención: </strong> No hay registros todavía, Agrega uno <a href="<?php echo base_url() ?>modulos/create.html" class="btn btn-link">aquí</a>
            </div>
		  <?php endif; ?>
                           
        </div>
    </div><!--/span-->

</div><!--/row-->

<?php $pagination = $this->pagination->create_links(); // Set Pag ?>

<?php if( !empty( $pagination ) ): ?>
    
    <div class="row-fluid sortable">		
        <div class="box span12">
            <div class="box-content">
            
              <?php echo $pagination?>
                      
            </div>
        </div><!--/span-->
    
    </div><!--/row-->

<?php endif; ?>