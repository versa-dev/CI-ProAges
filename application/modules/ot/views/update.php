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
            <a href="<?php echo base_url() ?>ot.html">Orden de trabajo</a> <span class="divider">/</span>
        </li>
        
        <li>
            Modificar
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
        	
            
			           
            
        
            <form id="form" action="<?php echo base_url() ?>ot/update/<?php echo $data[0]['id'] ?>.html" class="form-horizontal" method="post">
                <fieldset>
                     
                  
                  
                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Número OT</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" id="ot" name="ot" type="text">
                    </div>
                  </div>
                   
                   <input type="hidden" id="otvalue" value="<?php echo $data[0]['product_group_id'] ?>" />
                  
                   <div class="control-group">
                    <label class="control-label text-error" for="inputError">Fecha de tramite</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" id="creation_date" name="creation_date" type="text" readonly="readonly">
                    </div>
                  </div>
                                                                                      
                  
                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Ramo</label>
                    <div class="controls">
                      <?php if( $data[0]['product_group_id'] == 1 ) $ch = 'checked="checked"'; else $ch = ''; ?>
                      <input type="radio" value="1" name="ramo" class="ramo" <?php echo $ch ?>/>&nbsp;&nbsp;Vida
                      
                      <?php if( $data[0]['product_group_id'] == 2 ) $ch = 'checked="checked"'; else $ch = ''; ?>
                      <input type="radio" value="2" name="ramo" class="ramo" <?php echo $ch ?>/>&nbsp;&nbsp;GMM
                      
                      <?php if( $data[0]['product_group_id'] == 3 ) $ch = 'checked="checked"'; else $ch = ''; ?>
                      <input type="radio" value="3" name="ramo" class="ramo" <?php echo $ch ?>/>&nbsp;&nbsp;Auto
                    </div>
                  </div>
                  
                  
                  
                  
                  
                  <div class="control-group typtramite">
                    <label class="control-label text-error" for="inputError">Tipo tramite<br /><div id="loadtype"></div></label>
                    <div class="controls">
                      <select class="input-xlarge focused required" id="work_order_type_id" name="work_order_type_id">
                      	<option value="">Seleccione</option> 

                      </select>
                    </div>
                  </div>
                  
                  <input type="hidden" id="type" value="<?php echo $data[0]['type'] ?>" />
                  
                  
                  
                   <div class="control-group subtype">
                    <label class="control-label text-error" for="inputError">Sub tipo<br /><div id="loadsubtype"></div></label>
                    <div class="controls">
                      <select class="input-xlarge focused required" id="subtype" name="subtype">
                      	<option value="">Seleccione</option>

                      </select>
                    </div>
                  </div>
                  
                  <input type="hidden" id="subtype_value" value="<?php echo $data[0]['subtype'] ?>" />
                  
                  <div id="loadpolicies"></div>
                  
                  
                  
                   <div class="control-group poliza">
                    <label class="control-label text-error" for="inputError"></label>
                    <div class="controls">
                      <a href="<?php echo base_url() ?>ot/create_poliza.html" class="btn btn-link">Requerir nueva poliza</a>
                    </div>
                  </div> 
                  
                  <div class="control-group poliza">
                    <label class="control-label text-error" for="inputError">Poliza</label>
                    <div class="controls">
                      <select class="input-xlarge focused" id="policy_id" name="policy_id">
                      	<option value="">Seleccione</option>

                      </select>
                    </div>
                  </div>
                 
                 <input type="hidden" id="policy_id_vale" value="<?php echo $data[0]['policy_id'] ?>" />
                  
                  
                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Comentarios</label>
                    <div class="controls">
                      <textarea class="input-xlarge focused required" id="comments" name="comments" rows="6"><?php echo $data[0]['comments'] ?></textarea>
                    </div>
                  </div>
                  
                                     
                   <!-- ¨Persona Moral Settings -->
                   <fieldset class="block-privacy">
                   
                   	   <a href="javascript:void(0)" id="privacy_add" class="btn btn-link input-moral pull-right" >+</a>
                                         
                      <div id="privacy-fields" class="input-privacy"></div>
                  
                  </fieldset>
                  
                  
                   
                  
                  
                  
                  
                  
                                   
                  
                                    
                  <div id="actions-buttons-forms" class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <input type="button" class="btn" onclick="javascript: history.back();" value="Cancelar">
                  </div>
                </fieldset>
              </form>
        
        </div>
    </div><!--/span-->

</div><!--/row-->
			