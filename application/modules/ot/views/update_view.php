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
$update = ($function == 'update');
$update_nn_editable = $update && $is_nuevo_negocio;
$update_others_editable = $update && !$is_nuevo_negocio;
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
            <?php echo $title ?> número <b><?php echo $data['uid'] ?></b>&nbsp;
			<?php if ($this->access_update && !$update) echo anchor('ot/update_ot/' . $data['id'], '<i class="icon-pencil" title="Editar OT"></i>', array('title' => 'Editar OT')) ?>
        </li>
    </ul>
</div>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2></h2>
            <?php if (( $function == 'editar') && !isset( $message['type'] ) ): ?>
            <p style="float: right;">
			  <a style="font-weight: normal" href="javascript:void(0)" class="btn" id="view-details">Mostrar / ocultar los detalles</a>
            </p>
            <?php endif; ?>

            <div class="box-icon">
            </div>
        </div>

        <div class="box-content">
           <?php $validation = validation_errors(); ?>
            
            <?php if( !empty( $validation ) ): ?>
            <div class="alert alert-error">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Error: </strong> <?php echo $validation; ?>
            </div>
            <?php endif; ?>

            <?php if( isset( $message['type'] ) ): ?>
                <?php if( $message['type'] == true ): ?>
                    <div class="alert alert-success">
                          <button type="button" class="close" data-dismiss="alert">×</button>
                          <img src="<?php echo base_url() ?>images/true.png" width="20" height="20" />
                          <strong>Listo: </strong> <?php echo $message['message']; // Show Dinamical message Success ?>
                    </div>
                <?php elseif( $message['type'] == false ): ?>
                    <div class="alert alert-error">
                          <button type="button" class="close" data-dismiss="alert">×</button>
                          <img src="<?php echo base_url() ?>images/false.png" width="20" height="20" />
                          <strong>Error: </strong> <?php  echo $message['message']; // Show Dinamical message error ?>
                    </div>
                <?php endif; ?>
			<?php else: ?>

            <form id="form" action="<?php echo current_url() ?>" class="form-horizontal" method="post">
                <fieldset>
                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Número OT</label>
                    <div class="controls">
<?php if ($update): ?>
                      <input class="input-small focused" id="otnumber" type="text" readonly="readonly" value="<?php echo substr($data['uid'], 0, 5); ?>">
                      <input class="input-xlarge focused required" id="ot" name="ot" type="text" value="<?php echo substr($data['uid'], 5); ?>">
<?php else: ?>
                      <input class="input-xlarge focused required update-editable" id="ot" name="ot" type="text" value="<?php echo $data['uid'] ?>" readonly="readonly">
<?php endif; ?>
                    </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Fecha de tramite</label>
                    <div class="controls">
                      <input class="input-xlarge focused required update-editable" id="creation_date" name="creation_date" value="<?php echo substr($data['creation_date'], 0, 10) ?>" type="text"  <?php if (!$update) echo 'readonly="readonly"'?>>
                    </div>
                  </div>
<?php if (!$update) : ?>
                  <div class="control-group *new-bussiness">
                    <label class="control-label text-error" for="inputError">Agente</label>
                    <div class="controls">
                       <select class="input-xxlarge focused required update-editable" name="agent[]" id="agent-select" readonly="readonly" multiple="multiple">
<?php
$selected_agents = array();
foreach ($data['agents'] as $value)
	$selected_agents[ $value['agent_id'] ] = sprintf("%s %s (porcentaje : %s)", $value['name'], $value['lastnames'], $value['percentage']);
foreach ($agents as $key => $value) {
	if (isset($selected_agents[$key])) {
		echo '<option value="' . $key . '" selected="selected">' . $selected_agents[$key] . '</option>';
	}
}
?>
                       </select>
                   </div>
                  </div>
<!--                  <input type="hidden" id="agenconfirm" value="true" />  -->
<?php else:
if ($data['agents']):
	$count = 0;
	foreach ($data['agents'] as $key => $value):
		$count++;
?>

                  <div class="control-group *new-bussiness">
                    <label class="control-label text-error" for="inputError">Agente</label>
                    <div class="controls">
                       <select class="input-xlarge focused required" name="agent[]" id="agent-select">
<?php foreach ($agents as $a_key => $a_value):
	if ($value['agent_id'] == $a_key)
		echo '<option value="' . $a_key . '" selected="selected">' . $a_value . "</option>\n";
	else
		echo '<option value="' . $a_key . '">' . $a_value . "</option>\n";
endforeach; ?>
                       </select>
                       <input value="<?php echo $value['percentage']; ?>%" class="input-small focused required" id="agent-<?php echo $count; ?>" name="porcentaje[]" type="text" onblur="javascript: setFields( 'agent-<?php echo $count; ?>' )" placeholder="%">
                    </div>
                  </div>
<?php endforeach;
else :
	$count = 1;?>
                  <div class="control-group *new-bussiness">
                    <label class="control-label text-error" for="inputError">Agente</label>
                    <div class="controls">
                       <select class="input-xlarge focused required" name="agent[]" id="agent-select">
<?php foreach ($agents as $a_key => $a_value):
	echo '<option value="' . $a_key . '">' . $a_value . "</option>\n";
endforeach; ?>
                       </select>
                       <input value="100%" class="input-small focused required" id="agent-1" name="porcentaje[]" type="text" onblur="javascript: setFields( 'agent-1' )" placeholder="%">
                    </div>
                  </div>
<?php endif; ?>
                  <input type="hidden" id="countAgent" value="<?php echo $count; ?>" />
                  <div id="dinamicagent"></div>
<?php endif; ?>

                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Ramo</label>
                    <div class="controls">
<?php
$checked = array();
$display = array();
for ( $i = 1; $i < 4; $i++) {
	$checked[$i] = '';
	$display[$i] = ' style="display: none" ';
}
$checked[ $data['product_group_id'] ] = ' checked="checked" ';
$display[ $data['product_group_id'] ] = '';
?>

                      <span <?php echo $display[1] ?>><input type="radio" value="1" name="ramo" class="ramo" <?php echo $checked[1] ?> readonly="readonly" />&nbsp;&nbsp;Vida</span>
                      <span <?php echo $display[2] ?>><input type="radio" value="2" name="ramo" class="ramo" <?php echo $checked[2] ?> readonly="readonly" />&nbsp;&nbsp;GMM</span>
                      <span <?php echo $display[3] ?>><input type="radio" value="3" name="ramo" class="ramo" <?php echo $checked[3] ?> readonly="readonly" />&nbsp;&nbsp;Auto</span>
					  </div>
                  </div>

                  <div class="control-group typtramite">
                    <label class="control-label text-error" for="inputError">Tipo tramite</label>
                    <div class="controls">
<?php if ($update): ?>
<?php foreach ($tramite_types as $value) {
		if ($value->id == $data['parent_type_name']['id'])
			echo $value->name;
	}
?>
                      <select style="display: none" class="input-xlarge focused required" id="work_order_type_id" name="work_order_type_id" readonly="readonly">

<?php else: ?>
                      <select class="input-xlarge focused required" id="work_order_type_id" name="work_order_type_id" readonly="readonly">
<?php foreach ($tramite_types as $value) {
	if ($value->id == $data['parent_type_name']['id'])
		echo '<option value="' . $value->id . '" selected="selected">' . $value->name . '</option>';
	else
		echo '<option value="' . $value->id . '" disabled="disabled">' . $value->name . '</option>';
} ?>
<?php endif; ?>
                      </select>
                    </div>
                  </div>

                  <div class="control-group subtype">
                    <label class="control-label text-error" for="inputError">Sub tipo<br /><div id="loadsubtype"></div></label>
                    <div class="controls">
<?php if ($update): ?>
<?php foreach ($sub_types as $value) {
		if ($value->id == $data['type_id'])
			echo $value->name;
	}
?>
                      <select style="display: none" class="input-xlarge focused required" id="work_order_type_id" name="work_order_type_id" readonly="readonly">

<?php else: ?>
                      <select class="input-xlarge focused required" id="subtype" name="subtype" readonly="readonly">
<?php foreach ($sub_types as $value) {
	if ($value->id == $data['type_id'])
		echo '<option value="' . $value->id . '" selected="selected">' . $value->name . '</option>';
	else
		echo '<option value="' . $value->id . '" disabled="disabled">' . $value->name . '</option>';
} ?>
<?php endif; ?>
                      </select>
                    </div>
                  </div>

                  <div class="control-group typtramite hide-update-others">
                    <label class="control-label text-error" for="inputError">Producto<br /><div id="loadproduct"></div></label>
                    <div class="controls">
<?php if ($update): ?>
<?php foreach ($products_by_group as $value) {
		if ($value['id'] == $data['policy'][0]['products'][0]['id'])
			echo ($value['name']);
	}
?>
                      <select style="display: none" class="input-xlarge focused required" id="work_order_type_id" name="work_order_type_id" readonly="readonly">

<?php else: ?>
                      <select class="input-xlarge focused required" id="product_id" name="product_id" readonly="readonly">
<?php foreach ($products_by_group as $value) {
	if ($value['id'] == $data['policy'][0]['products'][0]['id'])
		echo '<option value="' . $value['id'] . '" selected="selected">' . $value['name'] . '</option>';
	else
		echo '<option value="' . $value['id'] . '" disabled="disabled">' . $value['name'] . '</option>';
} ?>
<?php endif; ?>
                      </select>
                    </div>
                  </div>

                  <div class="control-group typtramite hide-update-others" style="display: none;">
                    <label class="control-label text-error" for="inputError">ID de Producto<br /><div id="loadproduct"></div></label>
                    <div class="controls" id="id_product-2" style="display: none;">

<?php foreach ($products_by_group as $value) {
		if ($value['id'] == $data['policy'][0]['products'][0]['id']){
      echo ('<label id="value_id" name="value_id">');
      echo set_value('value_id', $value['id']);
      echo  '</label>';
        //'<div id="value_id">' . $value['id'] . '</div>');

    }
			
	}
?>
                    </div>
                  </div>

                  <div class="control-group period hide-update-others">
                    <label class="control-label" for="inputError">Plazo</label>
                    <div class="controls">
                      <select class="input-xlarge focused" id="period" name="period" <?php if (!$update || ($update && !$is_nuevo_negocio)) echo 'readonly="readonly"' ?>>
<?php foreach ($periods as $value) {
	if ($value == $data['policy'][0]['period'])
		echo '<option value="' . $value . '" selected="selected">' . $value . '</option>';
	elseif ($update_nn_editable)
		echo '<option value="' . $value . '">' . $value . '</option>';	
	else
		echo '<option value="' . $value . '" disabled="disabled">' . $value . '</option>';
} ?>
                      </select>
                    </div>
                  </div>

                  <div class="control-group typtramite hide-update-others">
                    <label class="control-label text-error" for="inputError">Moneda</label>
                    <div class="controls">
                      <select class="input-xlarge focused required update-nn-editable" id="currency_id" name="currency_id" <?php if (($function == 'ver') || ($update && !$is_nuevo_negocio)) echo 'readonly="readonly"' ?>>
<?php
foreach ($currencies as $key => $value) {
	if ($key == $data['policy'][0]['currency_id'])
		echo '<option value="' . $key . '" selected="selected">' . $value . '</option>';
//	elseif ($update_nn_editable)
	else
		echo '<option value="' . $key . '">' . $value . '</option>';
//	else
//		echo '<option value="' . $key . '" disabled="disabled">' . $value . '</option>';
} ?>

                      </select>
                    </div>
                  </div>

                  <div class="control-group hide-update-others">
                    <label class="control-label text-error" for="inputError">Prima anual
                    <br>en la moneda seleccionada</label>
                    <div class="controls">
                      <input <?php if (($function == 'ver') || ($update && !$is_nuevo_negocio)) echo 'readonly="readonly"' ?> style="height: 1.7em" type="number" pattern="[0-9]+([\.][0-9]+)?" step="0.01" value="<?php echo set_value('prima', $data['policy'][0]['prima_entered']); ?>" class="input-xlarge focused required" id="prima" name="prima" />
                      <span id="prima-error" style="display: none">Campo invalido</span>
                    </div>
                  </div>

                  <div class="control-group allocatedPrime">
                    <label class="control-label text-error" for="inputError">Prima para ubicar
                    <br>en pesos mexicanos</label>
                    <div class="controls">

                      <input <?php if (($function == 'ver') || ($update && !$is_nuevo_negocio)) echo 'readonly="readonly"' ?>  style="height: 1.7em; background:rgba(0,0,0,0); border: 1px solid rgba(0,0,0,0)" type="number" pattern="[0-9]+([\.][0-9]+)?" step="0.01" value="<?php echo set_value('allocatedPrime', $data['policy'][0]['allocated_prime']); ?>" class="input-xlarge focused" id="allocatedPrime" name="allocatedPrime" readonly />
                      <span id="prima-error" style="display: none">Campo invalido</span>

                    </div>
                  </div>

                  <div class="control-group bonusPrime">
                    <label class="control-label text-error" for="inputError">Prima para pago de bono
                    <br>en pesos mexicanos</label>
                    <div class="controls">
                      <input <?php if (($function == 'ver') || ($update && !$is_nuevo_negocio)) echo 'readonly="readonly"' ?>  style="height: 1.7em; background:rgba(0,0,0,0); border: 1px solid rgba(0,0,0,0)" type="number" pattern="[0-9]+([\.][0-9]+)?" step="0.01" value="<?php echo set_value('bonusPrime', $data['policy'][0]['bonus_prime']); ?>" class="input-xlarge focused" id="bonusPrime" name="bonusPrime" readonly />
                      <span id="prima-error" style="display: none">Campo invalido</span>

                    </div>
                  </div>





                  <div class="control-group typtramite hide-update-others">
                    <label class="control-label text-error" for="inputError">Conducto<br /></label>
                    <div class="controls">
                      <select class="input-xlarge focused required update-nn-editable" id="payment_method_id" name="payment_method_id" <?php if (!$update_nn_editable) echo 'readonly="readonly"'?>>
<?php foreach ($payment_conducts as $key => $value) {
	if ($key == $data['policy'][0]['payment_method_id'])
		echo '<option value="' . $key . '" selected="selected">' . $value . '</option>';
	elseif ($update_nn_editable)
		echo '<option value="' . $key . '">' . $value . '</option>';
	else
		echo '<option value="' . $key . '" disabled="disabled">' . $value . '</option>';
} ?>

                      </select>
                    </div>
                  </div>

                  <div class="control-group typtramite hide-update-others">
                    <label class="control-label text-error" for="inputError">Forma de pago<br /></label>
                    <div class="controls">
                      <select <?php if (($function == 'ver') || (!$update_nn_editable && $update)) echo 'readonly="readonly"' ?> class="input-xlarge focused required update-nn-editable" id="payment_interval_id" name="payment_interval_id">
<?php
if ($function == 'editar')
	foreach ($payment_intervals as $key => $value) {
		if ($key == $data['policy'][0]['payment_interval_id'])
			echo '<option value="' . $key . '" ' . set_select('payment_interval_id', $key, TRUE) . '>' . $value . '</option>';
		else
			echo '<option value="' . $key . '" ' . set_select('payment_interval_id', $key) . '>' . $value . '</option>';
	}
else
	foreach ($payment_intervals as $key => $value) {
		if ($key == $data['policy'][0]['payment_interval_id'])
			echo '<option value="' . $key . '" selected="selected">' . $value . '</option>';
		elseif ($update_nn_editable)
			echo '<option value="' . $key . '">' . $value . '</option>';
		else
			echo '<option value="' . $key . '" disabled="disabled">' . $value . '</option>';
	}
 ?>

                      </select>
                    </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Nombre del asegurado / contratante</label>
                    <div class="controls">
                      <input class="input-xlarge focused required uppercase update-editable" id="name" name="name" type="text" value="<?php echo $data['policy'][0]['name'] ?>"  <?php if(!$update) echo 'readonly="readonly"'?>>
                    </div>
                  </div>

<?php if (($function != 'update') ||
		(($function == 'update') && (
		($is_nuevo_negocio && in_array($data['status_id'], array(7, 4, 10))) ||
		(!$is_nuevo_negocio)) )) :?>
                  <div class="control-group poliza">
                    <label class="control-label text-error" for="inputError">Poliza</label>
                    <div class="controls">
                       <input class="input-xlarge focused required update-editable" id="uid" name="uid" type="text" value="<?php echo $data['policy'][0]['uid'] ?>" <?php if(!$update) echo 'readonly="readonly"'?>>
                    </div>
                  </div>
<?php endif ?>

<?php if (($function == 'update') 
		// && in_array($data['status_id'], array(7, 4, 10))
		) :?>
                  <div class="control-group">
                    <label class="control-label text-error" for="inputError">Estado</label>
                    <div class="controls">
                       <select class="input-xlarge focused required" name="ot_status" id="ot-status">
<option value="5" <?php if ($data['status_id'] == 5) echo 'selected="selected"' ?>>En trámite</option>
<option value="7" <?php if ($data['status_id'] == 7) echo 'selected="selected"' ?>>Aceptada</option>
<option value="8" <?php if ($data['status_id'] == 8) echo 'selected="selected"' ?>>Rechazada</option>
<option value="10" <?php if ($data['status_id'] == 10) echo 'selected="selected"' ?>>Póliza NTU</option>
<option value="4" <?php if ($data['status_id'] == 4) echo 'selected="selected"' ?>>Pagada</option>
                       </select>

                       <label class="checkbox">
                         <input type="checkbox" name="email_notification" id="email-notification" value="1">Con notificacion por correo electrónico
                       </label>
                   </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label" for="inputError">Emails notificados adicionalmente</label>
                    <div class="controls">
                      <input class="input-xlarge focused" id="emails" name="emails" type="text" value="<?php echo set_value( 'emails' ) ?>">
                      <p>Ingrese los emails a notificar separados por comas</p>
                    </div>
                  </div>
<?php endif ?>
                  <div id="notas_adicionales" class="control-group" <?php if($data['status_id'] != 2 && $data['status_id'] != 8) echo "style='display:none'"?>>
                    <label class="control-label" for="inputError">Notas adicionales</label>
                    <div class="controls">
                      <textarea class="input-xlarge focused update-editable" id="notes" name="notes" rows="6" <?php if (!$update) echo 'readonly="readonly"'?>><?php echo $data['notes'] ?></textarea>
                    </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label" for="inputError">Comentarios</label>
                    <div class="controls">
                      <textarea class="input-xlarge focused update-editable" id="comments" name="comments" rows="6" <?php if (!$update) echo 'readonly="readonly"'?>><?php echo $data['comments'] ?></textarea>
                    </div>
                  </div>

                  <div id="actions-buttons-forms" class="form-actions">
<?php if (($function == 'editar') || ($function == 'update')): ?>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <input type="button" class="btn" onclick="javascript: history.back();" value="Cancelar">
<?php else: ?>
                    <input type="button" class="btn" onclick="javascript: window.close();" value="Cancelar">
<?php endif; ?>
				</div>
                </fieldset>
              </form>
			<?php endif; ?>
			
        </div>
    </div><!--/span-->

</div><!--/row-->