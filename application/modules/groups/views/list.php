<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div>
    <ul class="breadcrumb">
        <li>
            Grupos
        </li>
    </ul>
</div>

<div class="row-fluid sortable">		
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2></h2>
            <div class="box-icon">
               <?php if( $access_create == true ): ?>
               <!--<<a href="<?php //echo base_url("groups/create.html") ?>" class="btn btn-round"><i class="icon-plus"></i></a>-->
               <form action="groups/create.html">
                <button class="btn btn-primary">Crear grupo nuevo</button>
               </form>
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
                          <strong>Correcto: </strong> <?php  echo $message['message']; // Show Dinamical message Success ?>
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
            
        
        
        	<?php if( !empty( $data ) ): ?>
            <table class="table table-striped table-bordered bootstrap-datatable datatable">
              <thead>
                  <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Filtro de Ramo</th>
                      <th># Miembros</th>
                      <th>Creador</th>
                      <th>Actions</th>
                  </tr>
              </thead>   
              <tbody>
                <?php  foreach( $data as $value ):  ?>
                <tr>
                    <td><?= $value["id"] ?></td>
                    <td><?= $value["description"]?></td>
                    <td><?= $value["ramo"]?></td>
                    <td class="center"><?= $value["agents"]?></td>
                    <td class="center"><?= $value["owner_name"]?></td>
                    <td class="center">
                        <?php if( $access_update == true ): ?>
                        <a class="btn btn-info" href="<?= base_url("groups/update/".$value['id'].".html") ?>" title="Editar grupo">
                            <i class="icon-edit icon-white"></i>            
                        </a>
                        <?php endif; ?>
                        <?php if( $access_delete == true ): ?>
                        <a class="btn btn-danger" href="<?= base_url("groups/delete/".$value['id'].".html") ?>" title="Eliminar grupo">
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
                  <strong>Atención: </strong> No hay registros todavía, Agrega uno <a href="<?php echo base_url("groups/create.html") ?>" class="btn btn-link">aquí</a>
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