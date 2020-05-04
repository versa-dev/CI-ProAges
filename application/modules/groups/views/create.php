<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div>
    <ul class="breadcrumb">
        <li>
            <a href="<?php echo base_url("groups.html") ?>">Grupos</a> <span class="divider">/</span>
        </li>
        <li>
            Crear
        </li>
    </ul>
</div>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-edit"></i> Agregar nuevo grupo</h2>
            <div class="box-icon">
                
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
            
            
            
            <?= form_open('', 'id="form" class="form-horizontal span5" style="border-right: 1px solid #CCCCCC"'); ?>
                <fieldset>
                  <div class="control-group">
                    <label class="control-label">Nombre</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" id="name" name="name" type="text" placeholder="El nombre del nuevo grupo" value="<?= set_value("name")  ?>">
                    </div>
                  </div>
                  
                  <div class="control-group">
                    <label class="control-label">Filtro en Ramo</label>
                    <div class="controls">
                      <?= form_dropdown('ramo', $ramos, set_value("ramo"), "class='form-control required'"); ?>
                    </div>
                  </div>
                  <div class="miembros-container">
                    <div class="control-group miembro">
                      <label class="control-label">Miembros</label>
                      <div class="controls">
                        <?= form_input('miembro_name[]', null, "class='input-xlarge miembro-text' disabled"); ?>
                        <input type="hidden" name="miembros[]" class="miembro-hidden">
                        <a href="#" class="btn btn-link search-agent" style="display:none">
                          <i class="icon-search"></i>
                        </a>
                        <a href="#" class="btn btn-link add-agent" style="display:none">
                          <i class="icon-plus"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                 
                  <div id="actions-buttons-forms" class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button class="btn" onclick="history.back()">Cancelar</button>
                  </div>
                </fieldset>
              </form>
              <form class="form-horizontal span5" style="text-align: center">
                <h4>Buscar agentes :</h4>
                <input type="text" id="search-query" class="input-medium search-query">
                <br>
                <br>
                <div id="actions-buttons-forms" class="form-actions"> 
                  <input type="button" id="search-button" class="btn" value="Buscar">
                  <button class="btn" id="search-all">Mostrar todos los Agentes</button>
                </div>

              </form>
 
        </div>
    </div><!--/span-->

</div><!--/row-->

<div class="row-fluid sortable" id="user-result-set">
<div class="box-header well" data-original-title>
            <h2><i class="icon-edit"></i> Busqueda de Agentes</h2>
            <div class="box-icon">
                
            </div>
        </div>
  <div class="box-content">

    <div class="result">
    
    </div>
    <div class="modal-footer">
      <button class="btn btn-primary" id="add-button">Agregar agentes</button>
    </div>

  </div>
</div>

<!--<div id="search-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true" style="top:50%">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="searchModalLabel">Busqueda de Agente</h3>
  </div>
  <div class="modal-body">
    <form class="form-search" style="text-align: center">
      <input type="text" id="search-query" class="input-medium search-query">
      <input type="button" id="search-button" class="btn" value="Buscar">
    </form>
    <div class="result">
      
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary" id="add-button">Agregar agentes</button>
  </div>
</div>-->