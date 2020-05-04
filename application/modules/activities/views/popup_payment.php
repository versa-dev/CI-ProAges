<link rel="stylesheet" href="<?php echo base_url();?>ot/assets/style/main.css">
<link href="<?php echo base_url();?>ot/assets/style/report.css" rel="stylesheet">
<script src="<?php echo base_url();?>ot/assets/scripts/report.js"></script>
<style type="text/css">
	.payment_table th { width: 150px;}
	.payment_table td { padding: 0; margin: 0}
</style>

<?php if ($values):
$base_url = base_url();
$additional_form_fields = '';
if (($type = $this->input->post('type')) !== FALSE)
	$additional_form_fields .= '
<input type="hidden" name="type" value="' .  $type . '" />';
$query = $this->input->post('query');
if (($query !== FALSE) && is_array($query))
{
	foreach ($query as $key => $value)
		$additional_form_fields .= '
<input type="hidden" name="query[' . $key . ']" value="' .  $value . '" />';
}
$delete_image = '';
if ( $this->access_ot_delete )
{
	$delete_image = '
&nbsp;&nbsp;<img style="cursor: pointer" class="payment_delete action_option" alt="Borrar" title="Borrar" src="' . $base_url . 'images/payment_delete.jpg" />';
}
$ignore_image = '
<img style="cursor: pointer" class="mark_ignored action_option" alt="Ignorar" title="Ignorar" src="' . $base_url . 'images/payment_ignore.jpg" />';
?>
<table class="altrowstable payment_table">
    <thead>
        <tr id="popup_tr">
            <th width="200px">Agente</th>
            <th>Fecha de pago</th>
            <th>Poliza</th>
            <th>Asegurado</th>
            <th>Agente importado</th>
            <th>Folio importado</th>
            <th style="text-align: right; padding-right: 3em">Prima (en $)</th>
            <th style="text-align: right; padding-right: 7em">Negocio</th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($values as $value): ?>
        <tr class="payment_row" >
            <td>
<?php
	if (!$value->first_name && !$value->last_name)
		echo $value->company_name;
	else
		echo $value->first_name . ' ' . $value->last_name;
?>
			
            </td>
            <td><?php echo $value->payment_date ?></td>
            <td><?php echo $value->policy_number ?></td>
            <td><?php echo $value->asegurado ? $value->asegurado : 'No disponible'?></td>
            <td><?php echo $value->imported_agent_name ? $value->imported_agent_name : 'No disponible'?></td>
            <td><?php echo $value->imported_folio ? $value->imported_folio : 'No disponible'?></td>
			<td style="text-align: right; padding-right: 2.5em"><?php echo number_format($value->amount, 2);?></td>
			<td style="width: 110px; text-align: right; padding-right: 2.5em">
<span style="padding-left: 2.5em; padding-right: 1.5em; text-align: right;"><?php echo $value->business;?></span>
<?php
if ( $this->access_ot_update && $value->valid_for_report ) :
	echo $ignore_image;
endif;
echo $delete_image;
?>
<form class="payment_detail_form" method="post" action="#">
<input type="hidden" name="for_agent_id" value="<?php echo $value->agent_id ?>" />
<input type="hidden" name="amount" value="<?php echo $value->amount ?>" />
<input type="hidden" name="payment_date" value="<?php echo $value->payment_date ?>" />
<input type="hidden" name="policy_number" value="<?php echo $value->policy_number ?>" />
<input type="hidden" class="payment_action" name="payment_action" value="" />
<?php
echo $additional_form_fields;
?>
</form>
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
