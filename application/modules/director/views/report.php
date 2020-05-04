<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
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
$selected_period = get_filter_period();
$base_url = base_url();
?>
            <div class="row">
                <div class="span11" style="margin-left:40px; width:95%">
                    <div class="main-container">
<?php
$segments = $this->uri->rsegment_array();
$is_sales_planning = ((count($segments) >= 2) && 
	(($segments[2] == 'index') || ($segments[2] == 'sales_planning')));
if ($is_sales_planning) :?>
<div>
<button id="add-payment-btn" class="btn btn-primary">Crear un pago</button>
</div>

<div style="display: none" id="add-payment-container" title="Crear un pago">
		<div id="add-payment-error" class="alert alert-error" style="display: none"></div>
        <form id="add-payment-form" class="form-horizontal" style="margin-left: -3em" method="post">
          <div class="control-group">
            <label class="control-label" for="payment-product-group">Ramo</label>
            <div class="controls">
              <select required class="span3 required" id="payment-product-group" name="product_group" placeholder="Ramo">                
                <option value="">Selecciona el ramo</option>
                <option value="1">Vida</option>
                <option value="2">GMM</option>
              </select>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="payment-agent-id">Agente</label>
            <div class="controls">
              <input type="text" title="Escriba el nombre del agente que desea buscar y selecciónelo de la lista que aparece." required class="span3" id="payment-agent-id" name="agent_id" placeholder="Agente">                
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="payment-amount">Monto pagado</label>
            <div class="controls">
              <input type="text" required id="payment-amount" name="amount" placeholder="Monto pagado">
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="payment-date">Fecha de pago</label>
            <div class="controls">
              <input type="text" required id="payment-date" name="payment_date" placeholder="Fecha de pago">
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="payment-business">¿Es negocio?</label>
            <div class="controls">
<!--              <input type="text" required id="payment-business" name="business" placeholder="¿Es negocio?">
-->
              <select required class="span3 required" id="payment-business" name="business" placeholder="¿Es negocio?">                
                <option value="">Selecciona</option>
                <option value="-1">-1</option>
                <option value="0">0</option>
                <option value="1">1</option>
              </select>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="payment-policy-number">Número de póliza</label>
            <div class="controls">
              <input type="text" title="Escriba el número de póliza que desea buscar y selecciónelo de la lista que aparece." required id="payment-policy-number" name="policy_number" placeholder="Número de póliza">
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="payment-year-prime">Año prima</label>
            <div class="controls">
              <input type="text" required id="payment-year-prime" name="year_prime" placeholder="Año prima">
            </div>
          </div>
          <div class="control-group">
            <div class="controls">
              <label class="checkbox">
                <input type="checkbox" id="payment-valid-for-report" name="valid_for_report" checked="checked">¿Válido para el reporte?
              </label>
            </div>
          </div>
        </form>
</div>
<?php endif; ?>

<?php if ($is_sales_planning && ($other_filters['ramo'] != 3)) echo $report_columns; ?>

                        <div class="main clearfix">                               
                            <a href="javascript:void(0);" class="links-menu btn btn-link link-ramo" id="vida" <?php if ($other_filters['ramo'] == 1) echo 'style="color:#06F"' ?>>Vida</a>
                            <a href="javascript:void(0);" class="links-menu btn btn-link link-ramo" id="gmm" <?php if ($other_filters['ramo'] == 2) echo 'style="color:#06F"' ?>>GMM</a>
                            <a href="javascript:void(0);" class="links-menu btn btn-link link-ramo" id="autos" <?php if ($other_filters['ramo'] == 3) echo 'style="color:#06F"' ?>>Autos</a>

                            <p class="line" style="margin-bottom: 0">
                              <?php if(isset($last_date)): ?>
                                Información de pagos actualizada al: <?= $last_date ?>
                              <?php endif; ?>
                              &nbsp;
                              <br>
                              <?php if(isset($last_date)): ?>
                                Información de pagos SELO actualizada al: <?= $last_date_selo ?>
                              <?php endif; ?>
                              &nbsp;
                            </p>
                            <form id="form" method="post">
								<input type="hidden" name="query[ramo]" id="ramo" value="<?php echo $other_filters['ramo'] ?>" />
								<input type="hidden" name="ver_meta" id="ver-meta" value="<?php echo $page ?>" />

<?php echo $filter_view ?>

                            </form>

<?php
echo $report_lines;
?>
                        </div> <!-- #main -->
                    </div> <!-- #main-container -->
                </div>  <!-- .span11 -->
            </div> <!-- .row -->
