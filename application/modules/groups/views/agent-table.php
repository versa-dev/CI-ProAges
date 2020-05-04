<table class="table table-striped" style="margin-bottom: 0">
	<thead>
		<th>Sel</th>
		<th>Nombre</th>
		<th>Apellido</th>
		<th>Clave</th>
	</thead>
	<tbody>
		<?php foreach ($agents as $agent): ?>
			<tr>
				<td><?= form_checkbox("agent_id[]", $agent["agent_id"]); ?></td>
				<td data-name="<?= $agent["agent_id"] ?>"><?= $agent["name"] ?></td>
				<td data-last-name="<?= $agent["agent_id"] ?>"><?= $agent["lastnames"] ?></td>
				<td><?= $agent["clave"] ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>