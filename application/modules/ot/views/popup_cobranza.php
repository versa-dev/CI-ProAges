<?php
$base_url = base_url();
?>

<link rel="stylesheet" href="<?php echo $base_url;?>ot/assets/style/main.css">
<link href="<?php echo $base_url;?>ot/assets/style/report.css" rel="stylesheet">
<script src="<?php echo $base_url;?>ot/assets/scripts/report.js"></script>

<script type="text/javascript">
	var currentModule = "";
<?php
	$segments = $this->uri->rsegment_array();
	if (isset($segments[1]))
		echo 'var currentModule = "' . $segments[1] . '";';

?>
</script>

<script type="text/javascript">
	$( document ).ready(function() {
		$('.adjusted-prima').hide();
		$('.show-hide-due').bind( 'click', function(){
//			$(this).siblings().not('.detailed_dates').toggle();
			$(this).siblings().toggle();
			$('.paid').hide();
			return false;
		});

		$('.delete-cobranza').bind( 'click', function(){
			var current = $(this);
			if (confirm('¿Realmente desea eliminar el registro?')) {
				var ids = [];
				var single = current.hasClass('single');
				var toHandle = {};
				if (single) {
					toHandle = current.parent();
					ids.push(toHandle.attr( "id" ));
				} else {
					toHandle = current.parent().parent('.payment_row');
					current.siblings('.detailed_dates').children('.unpaid').each(function() {
						ids.push($(this).attr( "id" ));
					});
				}
				$.ajax({
					url: Config.base_url() + currentModule + '/delete_cobranza.html',
					type: 'POST',
					data: { ids: ids },
					dataType : 'json',
					beforeSend: function(){
						$("#wait-response").show();
						$("#payment-table").hide();
					},
					success: function(response){
						switch (response) {
							case '-1':
								alert ('No se pudo eliminar los registros. Informe a su administrador.');
								break;
							case '0':
								alert ('Ocurrio un error, no se pudo eliminar los registros, consulte a su administrador.');
								break;
							case '1':
								if (single) {
									var cobranzaCell = current.parent().parent().parent().siblings('.cobranza-v').children('span');
									var adjustedPrima = current.siblings('.adjusted-prima');
									var newCobranza = parseFloat(cobranzaCell.text().replace(/\,/, '')) - 
										parseFloat(adjustedPrima.text().replace(/\,/, ''));
									if (Math.round(newCobranza * 100) > 0)
										cobranzaCell.text(newCobranza.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"));
									else
										current.parents('.payment_row').hide();
								}
								if ( confirm( 'Se pudo eliminar los registros correctamente. ¿Quiere usted recargar la página web para actualizar las cifras?' ) )
									window.location.reload();
								else
									toHandle.remove();
								break;
							default:
								alert ('Hay un error en la respuesta del sitio web, consulte a su administrador.');
								break;
						}
						$("#wait-response").hide();
						$("#payment-table").show();
					}
				});
			}
			return false;
		});

	});
</script>

<style type="text/css">
	.payment_table th { width: 100px;}
	.payment_table td { padding: 0; margin: 0}
	.delete-cobranza, .delete-cobranza:hover {
		color: #F00;
		text-decoration: none;
	} 

</style>

<?php if ($values):
$policies = array();
$posted = $this->input->post('for_agent_id');
foreach ($values as $key => $value)
{
	if ($posted == $key)
	{
		$policies = $value['policy_uid'];
		break;
	}
}
$payment_interval_translate = array(
	1 => 'Mensual',
	2 => 'Trimestrial',
	3 => 'Semestral',
	4 => 'Anual');
$semaphores = array(
	'green' =>
'<div style="background-color:#0C0; width: 10px;  height: 10px; border-radius: 50%; float:left; margin-top:5px;"></div>',
	'yellow' =>
'<div style="background-color:#FF0; width: 10px;  height: 10px; border-radius: 50%; float:left; margin-top:5px;"></div>',
	'red' =>
'<div style="background-color:#F30; width: 10px;  height: 10px; border-radius: 50%; float:left; margin-top:5px;"></div>',
);
?>
<div id="wait-response" style="display: none; text-align: center">Espere usted un momento, por favor...</div>
<table class="altrowstable payment_table" id="payment-table">
    <thead>
        <tr id="popup_tr">
            <th style="width: 20px"></th>
            <th>Poliza</th>
            <th style="text-align: right; padding-right: 3em">Cobranza instalada</th>
			<th style="text-align: right; padding-right: 3em">Prima total</th>
			<th style="text-align: right; padding-right: 3em">Prima a ubicar</th>
			<th style="text-align: right; padding-right: 3em">Prima para pago de bono</th>
            <th>Producto</th>
            <th style="width: 220px">Asegurado</th>
            <th style="width: 150px">Forma de pago</th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($policies as $key => $value):
	$relative_paid = ((int)$value['paid'] / (int)$value['prima_due_past']) * 100;
	if ($relative_paid > 99)
		$semaphore = $semaphores['green'];
	elseif ($relative_paid > 90)
		$semaphore = $semaphores['yellow'];
	else
		$semaphore = $semaphores['red'];
		$policy_cobranza = $value['prima_due_future'] + $value['prima_due_past'] - $value['paid'];
?>
<?php if($policy_cobranza > 0): ?>
        <tr class="payment_row" id="tr-<?php echo $value['policy_id'] ?>">
            <td><?php echo $semaphore ?></td>
            <td>
            	<?php if($value['work_order_uid']):?>
                    <?php $ot_url= $base_url."/ot/ver_ot/".$value['work_order_uid'].".html"?>
                    <a href="<?php echo $ot_url ?>" class="payment_row" target="_blank"><?php echo $key ?></a>
                <?php else:?>
                    <?php echo $key ?>
                <?php endif?>
            </td>
			<td class="cobranza-v" style="text-align: right; padding-right: 2.5em">$ 
				<span>

					<?php echo number_format($policy_cobranza , 2); ?>
					
				</span>
			</td>
			<td class="cobranza-v" style="text-align: right; padding-right: 2.5em">$ 
				<span>

					<?php echo number_format($value[prima], 2); ?>
					
				</span>
			</td>
			<td class="cobranza-v" style="text-align: right; padding-right: 2.5em">$ 
				<span>

					<?php echo number_format($prima_ubicar , 2); ?>
					
				</span>
			</td>
			<td class="cobranza-v" style="text-align: right; padding-right: 2.5em">$ 
				<span>

					<?php echo number_format($prima_bono , 2); ?>
					
				</span>
			</td>
            <td><?php echo $value['product_name']; ?></td>
            <td><?php if ($value['asegurado']) echo $value['asegurado'] ; else echo 'No disponible'; ?></td>
            <td>
<?php
if (isset($payment_interval_translate[$value['payment_interval_id']]))
	echo '<a href="javascript: void(0);" class="show-hide-due">' . $payment_interval_translate[$value['payment_interval_id']] . '</a>' . 
		'&nbsp;&nbsp;<a href="javascript: void(0);" class="delete-cobranza" title="Click para eliminar registro" id="' . $value['policy_id'] . '">x</a>';
else echo '-';
echo '<span style="display: none"> ($&nbsp;' . number_format($value['adjusted_prima'], 2) . ')</span>';

$past_due_dates_arr = explode('|', $value['due_dates_past']);
$future_due_dates_arr = explode('|', $value['due_dates_future']);
$paid_v = (int)$value['paid'];
$adjusted_prima = (int)$value['adjusted_prima'];
?>
<?php endif ?>
				<ul class="detailed_dates" style="display: none">
<?php foreach($past_due_dates_arr as $date_value) :
if ($date_value) :
	if ($paid_v >= $adjusted_prima)
	{
		$style = 'style="color: #0C0" class="paid"';
		$delete_cobranza_payment = '';
	}
	else
	{
		$style = 'style="color: #F30" class="unpaid" id="' . $value['policy_id'] . '_' . $date_value . '"';
		$delete_cobranza_payment = '&nbsp;&nbsp;<a href="javascript: void(0);" class="delete-cobranza single" title="Click para eliminar registro">x</a>' . 
			'<span class="adjusted-prima">' . number_format($value['adjusted_prima'], 2) . '</span>';
	}
	$paid_v = $paid_v - $adjusted_prima;
?>
					<li <?php echo $style; ?>><?php echo $date_value . $delete_cobranza_payment ?> </li>
<?php endif; ?>
<?php endforeach; ?>
<?php foreach($future_due_dates_arr as $date_value) :
if ($date_value) :
	if ($paid_v >= $adjusted_prima)
	{
		$style = 'style="color: #0C0" class="paid"';
		$delete_cobranza_payment = '';
	}
	else
	{
		$style = 'style="color: #F30" class="unpaid" id="' . $value['policy_id'] . '_' . $date_value . '"';
		$delete_cobranza_payment = '&nbsp;&nbsp;<a href="javascript: void(0);" class="delete-cobranza single" title="Click para eliminar registro">x</a>' . 
			'<span class="adjusted-prima">' . $value['adjusted_prima'] . '</span>';
	}		
	$paid_v = $paid_v - $adjusted_prima;
?>
					<li <?php echo $style; ?>><?php echo $date_value . $delete_cobranza_payment ?></li>
<?php endif; ?>
<?php endforeach; ?>
				</ul>
			</td>
        </tr>
<?php endforeach; ?>
    </tbody>
</table>

<?php else: ?>
	No hay datos.
<?php endif; ?>
<?php
// To make sure UTF8 w/o BOM ùà
?>
