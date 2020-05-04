<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
/*

  Author
  Site:
  Twitter:
  Facebook:
  Github:
  Email:
  Skype:
  Location:		 Mexíco

  	
*/
$base_url = base_url();
$this->load->helper(array('filter', 'ot/date'));
?>

<script src="<?php echo $base_url ?>ot/assets/scripts/list_js.js"></script>
<script type="text/javascript">
function chooseOption( choose, is_new ){

	var choose = choose.split('-');
	if( choose[0] == 'activar' )
		window.location=Config.base_url()+"ot/activar/"+choose[1]+".html";
	if( choose[0] == 'desactivar' ) {
		var sendNotification = (confirm( "¿Con notificacion por correo electrónico?" ) ) ? "1": "0";
		window.location=Config.base_url()+"ot/desactivar/"+choose[1]+ '/' + sendNotification + ".html";
	}
	if( choose[0] == 'aceptar' ){
		if( confirm( '¿Seguro quiere marcar como aceptada?' ) ){
			if( is_new == true ){
				var poliza=prompt("Ingresa un número de poliza","");	
				if (( poliza!=null ) && (poliza.length > 0)){
					var pago=confirm("¿Quiere marcar la Póliza como pagada?");
					var sendNotification = (confirm( "¿Con notificacion por correo electrónico?" ) ) ? "1": "0";
					window.location=Config.base_url()+"ot/aceptar/"+choose[1]+ "/" + sendNotification +"/"+poliza+"/"+pago+".html";
				}
			}else{
				var sendNotification = (confirm( "¿Con notificacion por correo electrónico?" ) ) ? "1": "0";
				window.location=Config.base_url()+"ot/aceptar/"+choose[1]+ "/" + sendNotification + ".html";	
			}
		}
	}
	if( choose[0] == 'rechazar' ) {
		if( confirm( 'Seguro quiere marcar como rechazada' ) ) {
			var sendNotification = (confirm( "¿Con notificacion por correo electrónico?" ) ) ? "1": "0";
			window.location=Config.base_url()+"ot/rechazar/"+choose[1]+ '/' + sendNotification + ".html";
		}
	}
	if( choose[0] == 'cancelar' )
		window.location=Config.base_url()+"ot/cancelar/"+choose[1]+".html";
}
function setPay( id ){
	if( confirm( "¿Está seguro que quiere marcar la OT como pagada?" ) ){
		var sendNotification = (confirm( "¿Con notificacion por correo electrónico?" ) ) ? "1": "0";
		var Data = { id: id, notification: sendNotification };		
		$.ajax({
			url:  Config.base_url()+'ot/setPay.html',
			type: "POST",
			data: Data,
			cache: true,
			async: false,
			success: function(data){
				alert(data);
				window.location.reload();
			}
		});
	}	
}
</script>
<div id="loading"></div>

<div id="ot-list">
  <table class="sortable altrowstable tablesorter" id="sorter" style="width:100%;">
    <colgroup>
      <col width="14%" />
      <col width="10%" />
      <col width="10%" />
      <col width="8%" />
      <col width="6%" />
      <col width="10%" />

      <col width="7%" />
      <col width="7%" />

      <col width="10%" />
      <col width="8%" />
      <col width="10%" />
    </colgroup>
    <thead class="head">
      <tr id="popup_tr">
        <th>Número de OT&nbsp;</th>
        <th>Fecha de alta de la OT&nbsp;</th> 
        <th>Agente - %&nbsp;</th>
        <th>Gerente&nbsp;</th>
        <th>Ramo&nbsp;</th>
        <th>Tipo de trámite&nbsp;</th>

        <th>Producto&nbsp;</th>
        <th>Plazo&nbsp;</th>

        <th>Nombre del asegurado&nbsp;</th>
        <th>Estado&nbsp;</th>
        <th>Prima&nbsp;</th>
      </tr>
    </thead>   
    <tbody class="tbody" id="data">
<?php if( !empty( $data ) ): ?>
<?php foreach( $data as $value ):

?>
	<tr class="data-row-class" id="data-row-<?php echo $value['id'] ?>">
	<td class="center">
<?php 
	echo $value['uid'];
?>
    </td>
    <td class="center"><?php if( $value['creation_date'] != '0000-00-00 00:00:00' ) echo $value['creation_date'] ?></td>
    <td class="center">
<?php
	$agent_gerente_arr = array();
	$agent_arr = array();
	if( !empty( $value['agents'] ) )
	{
		foreach( $value['agents'] as $agent ) 
		{
			if( !empty( $agent['company_name'] ) )
				$agent_arr[] = $agent['company_name'] . ' '. $agent['percentage'] . '%';
			elseif (isset($agent['name']) && isset($agent['lastnames']))
				$agent_arr[] = $agent['name'] . ' '. $agent['lastnames']. ' '. $agent['percentage'] . '%';

			if (!empty($agent['manager_name']))
				$agent_gerente_arr[] = $agent['manager_name'];
			else
				$agent_gerente_arr[] = '-';
		}
		echo implode('<br>', $agent_arr);
	}
?>
    </td>
    <td style="text-align: center"><?php echo implode('<br>', $agent_gerente_arr); ?></td>
    <td class="center"><?php echo $value['group_name'] ?></td>
    <td class="center"><?php echo $value['parent_type_name'] ?></td>

    <td class="center"><?php echo $value['product_name'] ?></td>
    <td class="center"><?php echo $value['plazo'] ?></td>

    <td class="center"><?php echo $value['asegurado'] ?></td>
    <td class="center" ><?php echo ucwords(str_replace( 'desactivada', 'en trámite', $value['status_name'])); ?></td>
    <td class="center">
<?php
if ($value['is_nuevo_negocio'] && ($value['policy_prima'] != 'NULL'))
	echo number_format($value['policy_prima'], 2);
else
	echo '-';
?>

    </td>
</tr> 
<?php endforeach;  ?> 
<?php endif; ?> 

    </tbody>
  </table>
</div>
