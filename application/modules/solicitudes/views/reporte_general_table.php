<table class="table table-striped" id="<?= !$this->input->is_ajax_request() ? "tablesorted" : "tableajax" ?>">
	<thead>
		<tr>
			<th>#</th>
			<th style="width: 125px">Número de OT</th>
			<th style="width: 60px">Fecha alta</th>
			<th>Agente</th>
			<th>Ramo</th>
			<th>Asegurado</th>
			<th>Estatus</th>
			<th>Prima</th>
			<th>Poliza</th>
		</tr>
	</thead>
	<tbody>
		<?php $total_primas = 0; $i = 1;?>
		<?php foreach ($general_data as $order): ?>
			<tr>
				<td><?= $i++; ?></td>
				<td>
					<?php if(!$this->input->is_ajax_request()): ?>
						<?= $order["uid"] ?>
						<a href="<?= base_url("ot/ver_ot/".$order["id"]) ?>" target="_blank">
							<i class="icon-eye-open" title="Ver OT <?= $order["uid"]  ?>"></i>
						</a>
					<?php else: ?>
						<?= anchor("ot/ver_ot/".$order["id"], $order["uid"], "target = '_blank'");  ?>
					<?php endif; ?>
				</td>
				<td><?= date("Y-m-d", strtotime($order["creation_date"])) ?></td>
				<td><?= $order["name"]." ".$order["lastnames"] ?></td>
				<td><?= $order["ramo"] ?></td>
				<td><?= $order["asegurado"] ?></td>
				<td><?= $order["status"] ?></td>
				<td>$<?= number_format($order["prima"], 2) ?></td>
				<td><?= $order["poliza"] ?></td>
				<?php $total_primas += $order["prima"] ?>
			</tr>
		<?php endforeach; ?>
	</tbody>
	<?php if(!isset($show_tfoot)): ?>
		<tr>
			<th></th>
			<th class="total">Total</th>
			<th></th>
			<th><?= number_format(count($general_data), 0) ?> Solicitudes
			</th>
			<th></th>
			<th></th>
			<th></th>
			<th>
				$<?= number_format($total_primas,2) ?>
				<br />
				Primas Totales
			</th>
			<th>
				$<?= number_format($total_primas / count($general_data),2) ?>
				<br />
				Promedio Primas
			</th>
		</tr>
	<?php else: ?>
		<tfoot class="<?= issetor($tfoot_class) ?>">
			<tr>
				<th></th>
				<th class="total">Total</th>
				<th></th>
				<th><?= number_format(count($general_data), 0) ?>
					<br />
					Solicitudes
				</th>
				<th></th>
				<th></th>
				<th></th>
				<th>$<?= number_format($total_primas,2) ?> 
					<br />
					Primas
				</th>
				<th></th>
			</tr>
		</tfoot>
	<?php endif; ?>
</table>