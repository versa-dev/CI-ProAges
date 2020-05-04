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

			<?php // Return Message error ?>
            
            <?php $validation = validation_errors(); ?>
            
            <?php if( !empty( $validation ) ): ?>
            <div class="alert alert-error">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Error: </strong> <?php  echo $validation; // Show Dinamical message error ?>
            </div>
            <?php endif; ?>
            
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

            <form id="form" action="<?php echo base_url() ?>ot/create.html" class="form-horizontal" method="post">
                <fieldset>

                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Número OT</label>
                    <div class="controls">
                      <input class="input-small focused" id="otnumber" type="text" readonly="readonly">
                      <input class="input-xlarge focused required" id="ot" name="ot" type="text">
                    </div>
                  </div>
                  
                  
                   <div class="control-group">
                    <label class="control-label text-error" for="inputError">Fecha de tramite</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" id="creation_date" name="creation_date" type="text" readonly>
                    </div>
                  </div>
                  
                  <input type="hidden" id="agenconfirm" value="true" />
                  
                  <div class="control-group *new-bussiness">
                    <label class="control-label text-error" for="inputError">Agente</label>
                    <div class="controls">
                       <select class="input-xlarge focused required" name="agent[]" id="agent-select">
                      	<?php echo $agents ?>
                       </select>
                       <input value="100%" class="input-small focused required" id="agent-1" name="porcentaje[]" type="text" onblur="javascript: setFields( 'agent-1' )" placeholder="%">
                    </div>
                  </div>
                  
                  <input type="hidden" id="countAgent" value="1" />

                  <div id="dinamicagent"></div>                                                                    
                  
                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Ramo</label>
                    <div class="controls">
                      <input type="radio" value="1" name="ramo" class="ramo"/>&nbsp;&nbsp;Vida
                      <input type="radio" value="2" name="ramo" class="ramo"/>&nbsp;&nbsp;GMM
                      <input type="radio" value="3" name="ramo" class="ramo"/>&nbsp;&nbsp;Auto
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

                   <div class="control-group subtype">
                    <label class="control-label text-error" for="inputError">Sub tipo<br /><div id="loadsubtype"></div></label>
                    <div class="controls">
                      <select class="input-xlarge focused required" id="subtype" name="subtype">
                      	<option value="">Seleccione</option>

                      </select>
                    </div>
                  </div>

                  <div id="loadpolicies"></div>

                  <div id="formpoliza">

                  <div class="control-group typtramite">
                    <label class="control-label text-error" for="inputError">Producto<br /><div id="loadproduct"></div></label>
                    <div class="controls">
                      <select class="input-xlarge focused required" id="product_id" name="product_id">
						<?php echo $product ?>
                      </select>
                    </div>
                  </div>
                  
                  <div class="control-group period">
                    <label class="control-label text-error" for="inputError">Plazo<br /><div id="loadperiod"></div></label>
                    <div class="controls">
                      <select class="input-xlarge focused required" id="period" name="period">

                      </select>
                    </div>
                  </div>

                   <div class="control-group typtramite">
                    <label class="control-label text-error" for="inputError">Moneda<br /><div id="loadcurrency"></div></label>
                    <div class="controls">
                      <select class="input-xlarge focused required" id="currency_id" name="currency_id">
                      	<?php echo $currency ?>						
                      </select>
                    </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Prima anual
                    <br>en la moneda seleccionada</label>
                    <div class="controls">

                      <input style="height: 1.7em" type="number" pattern="[0-9]+([\.][0-9]+)?" step="0.01" value="<?php echo set_value('prima'); ?>" class="input-xlarge focused required" id="prima" name="prima" />
                      <span id="prima-error" style="display: none">Campo invalido</span>

                    </div>
                  </div>

                  <div class="control-group allocatedPrime">
                    <label class="control-label text-error" for="inputError">Prima para ubicar
                    <br>en pesos mexicanos</label>
                    <div class="controls">

                      <input style="height: 1.7em; background:rgba(0,0,0,0); border: 1px solid rgba(0,0,0,0)" type="number" pattern="[0-9]+([\.][0-9]+)?" step="0.01" value="<?php echo set_value('allocatedPrime'); ?>" class="input-xlarge focused" id="allocatedPrime" name="allocatedPrime" readonly />
                      <span id="prima-error" style="display: none">Campo invalido</span>

                    </div>
                  </div>

                  <div class="control-group bonusPrime">
                    <label class="control-label text-error" for="inputError">Prima para pago de bono
                    <br>en pesos mexicanos</label>
                    <div class="controls">
                      <input style="height: 1.7em; background:rgba(0,0,0,0); border: 1px solid rgba(0,0,0,0)" type="number" pattern="[0-9]+([\.][0-9]+)?" step="0.01" value="<?php echo set_value('bonusPrime'); ?>" class="input-xlarge focused" id="bonusPrime" name="bonusPrime" readonly />
                      <span id="prima-error" style="display: none">Campo invalido</span>

                    </div>
                  </div>

                  <div class="control-group typtramite">
                    <label class="control-label text-error" for="inputError">Conducto<br /><div id="loadpaymentmethod"></div></label>
                    <div class="controls">
                      <select class="input-xlarge focused required" id="payment_method_id" name="payment_method_id">
                      	<?php echo $payment_conduct ?>
						
                      </select>
                    </div>
                  </div>

                  <div class="control-group typtramite">
                    <label class="control-label text-error" for="inputError">Forma de pago<br /><div id="loadpaymentinterval"></div></label>
                    <div class="controls">
                      <select class="input-xlarge focused required" id="payment_interval_id" name="payment_interval_id">
                      	<?php echo $payment_intervals ?>
                      </select>
                    </div>
                  </div>

                  <div class="control-group hide">
                    <label class="control-label text-error" for="inputError">Apellido paterno</label>
                    <div class="controls">
                      <input class="input-xlarge focused" id="lastname_father" name="lastname_father" type="text" value="<?php echo set_value( 'lastname_father' ) ?>">
                    </div>
                  </div>
                  
                  <div class="control-group hide">
                    <label class="control-label text-error" for="inputError">Apellido materno</label>
                    <div class="controls">
                      <input class="input-xlarge focused" id="lastname_mother" name="lastname_mother" type="text" value="<?php echo set_value( 'lastname_mother' ) ?>">
                    </div>
                  </div>
                  

                  </div>

                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Nombre del asegurado / contratante</label>
                    <div class="controls">
                      <input class="input-xlarge focused required uppercase" id="name" name="name" type="text" value="<?php echo set_value( 'name' ) ?>">
                    </div>
                  </div> 
                  
                  <div class="control-group poliza">
                    <label class="control-label text-error" for="inputError">Poliza</label>
                    <div class="controls">
                       <input class="input-xlarge focused required" id="uid" name="uid" type="text" value="<?php echo set_value( 'uid' ) ?>">
                    </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label" for="inputError">Comentarios</label>
                    <div class="controls">
                      <textarea class="input-xlarge focused" id="comments" name="comments" rows="6"></textarea>
                    </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label" for="inputError">¿Con notificacion por correo electrónico?</label>
                    <div class="controls">
                      <input type="checkbox" value="1" name="notification" checked="checked" />&nbsp;Si&nbsp;&nbsp;
					  </div>
                  </div>
                  
                  <div class="control-group">
                    <label class="control-label" for="inputError">Emails notificados adicionalmente</label>
                    <div class="controls">
                      <input class="input-xlarge focused" id="emails" name="emails" type="text" value="<?php echo set_value( 'emails' ) ?>">
                      <p>Ingrese los emails a notificar separados por comas</p>
                    </div>
                  </div>


                  <div id="actions-buttons-forms" class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button class="btn" onclick="javascript: history.back()">Cancelar</button>
                  </div>
                </fieldset>
              </form>
        
        </div>
    </div><!--/span-->

</div><!--/row-->
			