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
            <a href="<?php echo base_url() ?>ot/create.html">Crear</a> <span class="divider">/</span>
        </li>
        
        <li>
            Crear Politica
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

            <form id="form" action="<?php echo base_url() ?>ot/create_policy.html" class="form-horizontal" method="post">
                <fieldset>

                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Ramo</label>
                    <div class="controls">
                      <input type="radio" value="1" name="ramo" class="ramo"/>&nbsp;&nbsp;Vida
                      <input type="radio" value="2" name="ramo" class="ramo"/>&nbsp;&nbsp;GMM
                      <input type="radio" value="3" name="ramo" class="ramo"/>&nbsp;&nbsp;Auto
                    </div>
                  </div>

                  <div class="control-group typtramite">
                    <label class="control-label text-error" for="inputError">Producto<br /><div id="loadproduct"></div></label>
                    <div class="controls">
                      <select class="input-xlarge focused required" id="product_id" name="product_id">
						<?php echo $product ?>
                      </select>
                    </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Prima anual</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" id="id" name="id" type="text">
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

                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Nombre</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" id="name" name="name" type="text" value="<?php echo set_value( 'name' ) ?>">
                    </div>
                  </div>
                  
                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Apellido paterno</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" id="lastname_father" name="lastname_father" type="text" value="<?php echo set_value( 'lastname_father' ) ?>">
                    </div>
                  </div>
                  
                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Apellido materno</label>
                    <div class="controls">
                      <input class="input-xlarge focused required" id="lastname_mother" name="lastname_mother" type="text" value="<?php echo set_value( 'lastname_mother' ) ?>">
                    </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Agente</label>
                    <div class="controls">
                       <select class="input-xlarge focused required" name="agent[]">
                      	<?php echo $agents ?>
                       </select>
                       <input class="input-small focused required" id="agent-1" name="porcentaje[]" type="text" onblur="javascript: setFields( 'agent-1' )">
                    </div>
                  </div>
                  
                  <input type="hidden" id="countAgent" value="1" />
                 
                                   
                  <div id="dinamicagent"></div>
  
                  <div id="actions-buttons-forms" class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button class="btn" onclick="javascript: history.back()">Cancelar</button>
                  </div>
                </fieldset>
              </form>
        
        </div>
    </div><!--/span-->

</div><!--/row-->
			