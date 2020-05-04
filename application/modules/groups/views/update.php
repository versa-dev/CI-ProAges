<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div>
    <ul class="breadcrumb">
        <li>
            <a href="<?php echo base_url("groups.html") ?>">Grupos</a> <span class="divider">/</span>
        </li>
        <li>
            <a href="<?php echo base_url("groups/update/".$data['id'].".html") ?>">Editar </a> <span class="divider">/</span> 
        </li>
        <li>
            <?php echo $data['description'] ?>
        </li>
    </ul>
</div>

<div class="row-fluid sortable">
    <?php $last_saved = 0; ?>
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-edit"></i> Editar Grupo</h2>
            <div class="box-icon">
                <a href="<?php echo base_url("groups/delete/".$data["id"].".html") ?>" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
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
            <?= form_open('', 'id="form" class="form-horizontal"'); ?>
                <fieldset>
                  <div class="control-group">
                    <label class="control-label">Nombre</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" id="name" name="name" type="text" placeholder="El nombre del nuevo grupo" value="<?= $data["description"]  ?>">
                    </div>
                  </div>
                  
                  <div class="control-group">
                    <label class="control-label">Filtro en Ramo</label>
                    <div class="controls">
                      <?= form_dropdown('ramo', $ramos, $data["filter_type"], "class='form-control required'"); ?>
                    </div>
                  </div>
                  <div class="miembros-container">
                    <?php if(count($data["agents"]) > 0): ?>
                      <?php foreach ($data["agents"] as $i => $agent): ?>
                        <div class="control-group miembro">
                          <label class="control-label"><?= ($i==0)? "Miembros" : "" ?></label>
                          <div class="controls">
                            <?= form_input('miembro_name[]', $agent["agent_name"], "class='input-xlarge miembro-text' disabled"); ?>
                            <input type="hidden" name="miembros[]" class="miembro-hidden" value="<?= $agent["agent_id"] ?>">
                            <a href="#" class="btn btn-link search-agent" style="display:none">
                              <i class="icon-search"></i>
                            </a>
                            <a href="#" class="btn btn-link <?= ($i==0)? "add-agent" : "remove-agent" ?>" style="<?= ($i==0)? "display:none" : "display:inline" ?>">
                              <i class="<?= ($i==0)? "icon-plus" : "icon-minus" ?>"></i>
                            </a>
                          </div>
                        </div>
                        <?php $last_saved = $agent["agent_id"]; ?>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <div class="control-group miembro">
                        <label class="control-label">Miembros</label>
                        <div class="controls">
                          <?= form_input('miembro_name[]', null, "class='input-xlarge miembro-text' disabled"); ?>
                          <input type="hidden" name="miembros[]" class="miembro-hidden">
                          <a href="#" class="btn btn-link search-agent" style="display:none">
                            <i class="icon-search"></i>
                          </a>
                          <a href="#" class="btn btn-link add-agent">
                            <i class="icon-plus"></i>
                          </a>
                        </div>
                      </div>
                    <?php endif; ?>
                  </div>

                  <div>
                    <input type = "hidden" name="last-saved" class="last-saved-hidden" value="<?= $last_saved; ?>">
                  </div>
                 
                  <div id="actions-buttons-forms" class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn" onclick="history.back()">Cancelar</button>
                  </div>
                </fieldset>
              </form>

              <div>
              <form class="form-horizontal span5" style="text-align: center">
                <h4>Buscar agentes :</h4>
                <input type="text" id="search-query" class="input-medium search-query">
                <br>
                <br>
                <br>
                <br>
                <div id="actions-buttons-forms" class="form-actions"> 
                  <input type="button" id="search-button" class="btn" value="Buscar">
                  <button class="btn" id="search-all">Mostrar todos los Agentes</button>
                </div>

              </form>

              </div>
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