<table class="table table-striped" id="<?= !$this->input->is_ajax_request() ? "tablesorted" : "tableajax" ?>">
	<thead>
		<tr>
			<th>Fecha de pago</th>
			<th>Poliza</th>
			<th>Asegurado</th>
			<th>Producto</th>
			<th>Plazo</th>
			<th>Agente importado</th>
			<th>Generación de Agente</th>
			<th>Folio importado</th>
			<th>Monto</th>
		</tr>
	</thead>
	<tbody>
		<?php $total_payments = 0; $i = 1;?>
		<?php
			foreach ($general_data as $payment):
				// foreach ($product["payments"] as $key => $payment):
		?>
			<tr>
				<td><?= date("Y-m-d", strtotime($payment["payment_date"])) ?></td>
				<td><?= $payment["policy_number"] ?></td>
				<td><?= !empty($payment["asegurado"]) ? $payment["asegurado"] : 'No disponible' ?></td>
				<td><?= !empty($payment["producto"]) ? $payment["producto"] : 'No disponible' ?></td>
				<td><?= !empty($payment["period"]) ? $payment["period"] : 'No disponible' ?></td>
				<td><?= $payment["imported_agent_name"] ?></td>
				<td><?= $payment["agent_generation"] ?></td>
				<td><?= $payment["imported_folio"] ?></td>
				<td>$<?= number_format($payment["amount"], 2); ?></td>
				<?php $total_payments += $payment["amount"]; ?>
			</tr>
		<?php
				// endforeach;
			endforeach;
		?>
	</tbody>
	<tfoot class="<?= issetor($tfoot_class) ?>">
		<tr>
			<th class="total">Total</th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th>$<?= number_format($total_payments, 2); ?></th>
		</tr>
	</tfoot>
</table>