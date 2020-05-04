<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div>
    <ul class="breadcrumb">
        <li>
            <a href="<?= base_url("groups.html") ?>">Grupos</a> <span class="divider">/</span>
        </li>
        <li>
             <a href="<?= base_url("groups/update/".$data['id']."html") ?>"> Editar </a> <span class="divider">/</span> 
        </li>
        <li>
            <?php echo $data['description'] ?>
        </li>
    </ul>
</div>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-edit"></i> Eliminar Grupo</h2>
            <div class="box-icon">
               
            </div>
        </div>
        
        <div class="box-content">
        	
            
			<h3>Eliminar el Módulo <?php echo $data['description'] ?></h3>           
            
            
            
            <div class="alert alert-warning">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Atención: </strong> 
                  <p>Todos los datos se eliminaran por completo.</p>
                  <p>Las relaciónes entre datos se perderan.</p>
            </div>
                        
            <p>Si esta seguro de eliminar este registro de click en el boton eliminar.</p>
            
              <?= form_open('', 'id="form" class="form-horizontal"'); ?>
                <fieldset>
                  <input type="hidden" name="delete" value="true" />
                  <div id="actions-buttons-forms" class="form-actions">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                    <a href="javascript:void(0)" class="btn" onclick="history.back()">Cancelar</a>
                  </div>
                </fieldset>
              </form>
        
        </div>
    </div><!--/span-->

</div><!--/row-->
			