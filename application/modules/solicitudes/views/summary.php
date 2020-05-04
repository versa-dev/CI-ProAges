<?= form_open('', 'id="ot-form"'); ?>
	<table class="filterstable no-more-tables">
		<thead>
			<th>Período :<br />
				<?php echo $period_fields ?>
				<select id="periodo_form" name="periodo" style="width: 175px" title="Período">
					  <option value="<?php echo $selected_period ?>"></option>
				</select>
				<input type="hidden" value="<?php echo $selected_period ?>" id="periodo" name="query[periodo]" />
			</th>
			<th>Ramo:<br />
			<?= form_dropdown('ramo', $ramos, $other_filters["ramo"], 'id="ramo" class="filter-field filter-select"') ?>
			</th>
			<th>Agentes:<br />
			<?= form_dropdown('agent', $agents, $other_filters["agent"], 'id="agent" class="filter-field filter-select" style="width:250px"') ?>
			</th>
			<th>Gerentes:<br />
			<?= form_dropdown('gerente', $gerentes, $other_filters["gerente"], 'id="gerente" class="filter-field filter-select" style="width:250px"') ?>
			</th>
			<th>Producto:<br />
			<?= form_dropdown('product', $products, $other_filters["product"], 'id="product" class="filter-field filter-select" style="width: 150px"') ?>
			</th>
			
			<th>Estatus:<br />
			<?= form_dropdown('status', $status, $other_filters["status"], 'id="status" class="filter-field filter-select"') ?>
			</th>
			<?php render_custom_filters() ?>
		</thead>

	</table>
<?= form_close(); ?>
<div>
	<?php
		$this->load->model('work_order');
		$imported_date_selo = $this->work_order->getLastPaymentImportedDate($other_filters["ramo"], "selo");
		echo ("Información de pagos SELO actualizada al: " . $imported_date_selo );
	?>
</div>
<div class="row" id="indicators">
	<div class="span3 indicator">
		<span class="title">Solicitudes</span>
		<?= $general_indicators["solicitudes"] ?>
		<span class="comparative <?= sign($comparative_indicators["solicitudes"]) ?>">
			<i class="fa <?= sign($comparative_indicators["solicitudes"] ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
			<?= number_format(abs($comparative_indicators["solicitudes"]), 2 ) ?>%
		</span>
		
	</div>
	<div class="span3 indicator">
		<span class="title">Primas Solicitadas</span>
		$<?= number_format($general_indicators["primas_solicitadas"],2) ?>
		<span class="comparative <?= sign($comparative_indicators["primas_solicitadas"]) ?>">
			<i class="fa <?= sign($comparative_indicators["primas_solicitadas"] ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
			<?= number_format(abs($comparative_indicators["primas_solicitadas"]), 2 ) ?>%
		</span>
	</div>
	<div class="span3 indicator">
		<span class="title">Prima Promedio</span>
		$<?= number_format($general_indicators["prima_promedio"],2) ?>
		<span class="comparative <?= sign($comparative_indicators["prima_promedio"]) ?>">
			<i class="fa <?= sign($comparative_indicators["prima_promedio"] ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
			<?= number_format(abs($comparative_indicators["prima_promedio"]), 2 ) ?>%
		</span>
	</div>
	<div class="span3 indicator">
		<span class="title">Agentes</span>
		<?= $general_indicators["agentes"] ?>
		<span class="comparative <?= sign($comparative_indicators["agentes"]) ?>">
			<i class="fa <?= sign($comparative_indicators["agentes"] ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
			<?= number_format(abs($comparative_indicators["agentes"]), 2 ) ?>%
		</span>
	</div>
</div>
<ul class="nav nav-tabs" id="myTab">
  <li class="<?= printEquals($selected_tab, "reporte", "active") ?>"><a href="#reporte">Reporte general</a></li>
  <li class="<?= printEquals($selected_tab, "graficos", "active") ?>"><a href="#graficos">Estadisticas</a></li>
</ul>
<h5 style="text-align: right">
	<?php if(isset($last_date)): ?>
	    Información actualizada al: <?= $last_date ?>
	<?php endif; ?>
</h5>
 
<div class="tab-content">
  <div class="tab-pane <?= printEquals($selected_tab, "graficos", "active") ?>" id="graficos">
	  <div class="row">
	  	<div id="AgentsSection" class=" printable" style="margin-left: 30px;">
		  	<h3 class="span12">
		  		Solicitudes
				<div class="opciones">
					<a href="#" class="btn btn-primary sorter" data-sort-by="<?= $orderhash ?>">
						<i class="fa fa-sort-amount-desc" aria-hidden="true"></i> 
						<span><?= $orderlabel ?></span>
					</a>
					<a href="#" class="btn btn-primary toggleTable" data-target="#agentsTable" data-resize="#agentsCell">
						<i class="icon-list-alt"></i>
					</a>
					<?php if($access_export_xls): ?>
						<button type="button" class="btn btn-primary imprimir">
							<i class="icon-print"></i>
						</button>
						<a class="btn btn-primary" href="<?= base_url("solicitudes/download/agents") ?>">
							<i class="icon-download-alt"></i>
						</a>
					<?php endif; ?>
			  	</div>
		  	</h3>
	  	  	<div id="agentsCell" class="span12 chart-container" style="height: <?= !empty($wo_agents) ? (ceil(count($wo_agents) / 10)*125)+100 : 250?>px">
		  		<canvas id="agentsContainer"></canvas>
		  	</div>
		  	<div class="span12 table-container" id="agentsTable" style="display: none;">
		  		<table class="table table-striped">
					<thead>
						<tr>
							<th>Agente</th>
							<th>Primas</th>
							<th>Solicitudes</th>
						</tr>
					</thead>
					<tbody>
						<?php $total = 0; ?>
						<?php $totalaux = 0; ?>
						<?php foreach ($wo_agents as $order): ?>
							<tr>
								<td><?= $order["name"] ?></td>
								<td>
									<a href="#" class="popup" data-search="agent" data-value="<?= $order["id"] ?>">
										$<?= number_format($order["prima"],2) ?>
									</a>
								</td>
								<td>
									<a href="#" class="popup" data-search="agent" data-value="<?= $order["id"] ?>">
										<?= $order["conteo"] ?>
									</a>
								</td>
								<?php $total += $order["conteo"] ?>
								<?php $totalaux += $order["prima"] ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<th>Total</th>
							<th>$<?= number_format($totalaux, 2) ?></th>
							<th><?= $total ?></th>
						</tr>
					</tfoot>
				</table>
		  	</div>
	  	</div>
	  </div>
	  <?php 
	  	sort_object($wo_products, "prima");
	  ?>
	  <div class="row">
	  	  <div class="span6 printable">
		  	<h3 class="span12">
		  		Primas por Producto
				<div class="opciones">
					<a href="#" class="btn btn-primary toggleTable" data-target="#productsPrimaTable" data-resize="#productsPrimaCell">
						<i class="icon-list-alt"></i>
					</a>
					<?php if($access_export_xls): ?>
						<button type="button" class="btn btn-primary imprimir">
							<i class="icon-print"></i>
						</button>
						<a class="btn btn-primary" href="<?= base_url("solicitudes/download/primaproduct") ?>">
							<i class="icon-download-alt"></i>
						</a>
					<?php endif; ?>
			  	</div>
		  	</h3>
			<div class="span12 graph-container" id="productsPrimaCell" style="height: 450px">
				<canvas id="productsPrimaContainer"></canvas>
			</div>
			<div class="span12 table-container" id="productsPrimaTable" style="display: none">
				<table class="table table-striped" style="margin-left: 30px; height: 350px; overflow-y: auto;">
					<thead>
						<tr>
							<th>PRODUCTO</th>
							<th>PRIMA</th>
						</tr>
					</thead>
					<tbody>
						<?php $total = 0; ?>
						<?php foreach ($wo_products as $order): ?>
							<tr>
								<td><?= $order["producto"] ?></td>
								<td>
									<a href="#" class="popup" data-search="product" data-value="<?= $order["id"] ?>">
										$<?= number_format($order["prima"], 2) ?>
									</a>
								</td>
								<?php $total += $order["prima"]; ?>
							</tr>
						<?php endforeach; ?>
						<tfoot>
							<tr>
								<th>Total</th>
								<th>$<?= number_format($total, 2) ?></th>
							</tr>
						</tfoot>
					</tbody>
				</table>
			</div>
		 </div>
		 <?php 
		 	//Sorting Array by primaAvg
	 		sort_object($wo_products, "avgPrima");
		 ?>
		 <div class="span6 printable">
		  	<h3 class="span12">
		  		P. Promedio Producto
				<div class="opciones">
					<a href="#" class="btn btn-primary toggleTable" data-target="#productsPrimaAvgTable" data-resize="#productsPrimaAvgCell">
						<i class="icon-list-alt"></i>
					</a>
					<?php if($access_export_xls): ?>
						<button type="button" class="btn btn-primary imprimir">
							<i class="icon-print"></i>
						</button>
						<a class="btn btn-primary" href="<?= base_url("solicitudes/download/primaavgproduct") ?>">
							<i class="icon-download-alt"></i>
						</a>
					<?php endif; ?>
			  	</div>
		  	</h3>
			<div class="span12 graph-container" id="productsPrimaAvgCell" style="height: 450px">
				<canvas id="productsPrimaAvgContainer"></canvas>
			</div>
			<div class="span12 table-container" id="productsPrimaAvgTable" style="display: none">
				<table class="table table-striped" style="margin-left: 30px; height: 350px; overflow-y: auto;">
					<thead>
						<tr>
							<th>PRODUCTO</th>
							<th>PRIMA PROMEDIO</th>
						</tr>
					</thead>
					<tbody>
						<?php $total = 0; ?>
						<?php foreach ($wo_products as $order): ?>
							<tr>
								<td><?= $order["producto"] ?></td>
								<td>
									<a href="#" class="popup" data-search="product" data-value="<?= $order["id"] ?>">
										$<?= number_format($order["avgPrima"], 2) ?>
									</a>
								</td>
								<?php $total += $order["avgPrima"]; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<th>Promedio Total</th>
							<th>
								$<?= count($wo_products) > 0 ? number_format($total / count($wo_products), 2) : 0 ?>	
							</th>
						</tr>
					</tfoot>
				</table>
			</div>
		 </div>
		 

	 </div>
	 <?php  
	 	//Sorting Arrays by solicitudes
	 	sort_object($wo_products, "conteo");
	 	
	 ?>
	 <div class="row">
		  
		  <div class="span6 printable">
		  	<h3 class="span12">
		  		Solicitación por Generación
				<div class="opciones">
					<a href="#" class="btn btn-primary toggleTable" data-target="#generationsTable" data-resize="#generationsCell">
						<i class="icon-list-alt"></i>
					</a>
					<?php if($access_export_xls): ?>
						<button type="button" class="btn btn-primary imprimir">
							<i class="icon-print"></i>
						</button>
						<a class="btn btn-primary" href="<?= base_url("solicitudes/download/generations") ?>">
							<i class="icon-download-alt"></i>
						</a>
					<?php endif; ?>
			  	</div>
		  	</h3>
			<div class="span12 graph-container" id="generationsCell" style="height: 450px">
				<canvas id="generationsContainer"></canvas>
			</div>
			<div class="span12 table-container" id="generationsTable" style="display: none">
				<table class="table table-striped" style="margin-left: 30px; height: 350px; overflow-y: auto;">
					<thead>
						<tr>
							<th>GENERACION</th>
							<th>SOLICITUDES</th>
						</tr>
					</thead>
					<tbody>
						<?php $total = 0; ?>
						<?php foreach ($wo_generations as $order): ?>
							<tr>
								<td><?= $order["title"] ?></td>
								<td>
									<?= $order["solicitudes"]; ?>
								</td>
								<?php $total += $order["solicitudes"]; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<th>Total</th>
							<th>
								<?= $total ?>	
							</th>
						</tr>
					</tfoot>
				</table>
			</div>
		 </div>

		 <div class="span6 printable">
		  	<h3 class="span12">
		  		Productos Solicitados
				<div class="opciones">
					<a href="#" class="btn btn-primary toggleTable" data-target="#productsTable" data-resize="#productsCell">
						<i class="icon-list-alt"></i>
					</a>
					<?php if($access_export_xls): ?>
						<button type="button" class="btn btn-primary imprimir">
							<i class="icon-print"></i>
						</button>
						<a class="btn btn-primary" href="<?= base_url("solicitudes/download/products") ?>">
							<i class="icon-download-alt"></i>
						</a>
					<?php endif; ?>
			  	</div>
		  	</h3>
			<div class="span12 graph-container" id="productsCell" style="height: 450px">
				<canvas id="productsContainer"></canvas>
			</div>
			<div class="span12 table-container" id="productsTable" style="display: none">
				<table class="table table-striped" style="margin-left: 30px; height: 350px; overflow-y: auto;">
					<thead>
						<tr>
							<th>PRODUCTO</th>
							<th>OT'S</th>
						</tr>
					</thead>
					<tbody>
						<?php $total = 0; ?>
						<?php foreach ($wo_products as $order): ?>
							<tr>
								<td><?= $order["producto"] ?></td>
								<td>
									<a href="#" class="popup" data-search="product" data-value="<?= $order["id"] ?>">
										<?= $order["conteo"] ?>
									</a>	
								</td>
								<?php $total += $order["conteo"] ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<th>Total</th>
							<th><?= $total ?></th>
						</tr>
					</tfoot>
				</table>
			</div>
		 </div>
	 </div>
	 <?php 
	 	//Sort Status by Prima
	 	sort_object($wo_status, "prima");
	 ?>
	 <div class="row">
		
		<div class="span6 printable">
		 	<h3 class="span12">
		  		Primas por Estatus
				<div class="opciones">
					<a href="#" class="btn btn-primary toggleTable" data-target="#statusPrimaTable" data-resize="#statusPrimaCell">
						<i class="icon-list-alt"></i>
					</a>
					<?php if($access_export_xls): ?>
						<button type="button" class="btn btn-primary imprimir">
							<i class="icon-print"></i>
						</button>
						<a class="btn btn-primary" href="<?= base_url("solicitudes/download/primastatus") ?>">
							<i class="icon-download-alt"></i>
						</a>
					<?php endif; ?>
			  	</div>
		  	</h3>
			<div class="span12 graph-container" id="statusPrimaCell" style="height: 450px">
				<canvas id="statusPrimaContainer"></canvas>
			</div>
			<div class="span12 table-container" id="statusPrimaTable" style="display: none">
				<table class="table table-striped" style="margin-left: 30px">
				<thead>
					<tr>
						<th>Estatus</th>
						<th>PRIMA</th>
					</tr>
				</thead>
				<tbody>
					<?php $total = 0; ?>
					<?php foreach ($wo_status as $order): ?>
						<tr>
							<td><?= $order["status"] ?></td>
							<td>
								<a href="#" class="popup" data-search="status" data-value="<?= $order["status"] ?>">
									$<?= number_format($order["prima"], 2) ?>
								</a>
							</td>
							<?php $total += $order["prima"]; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<th>Total</th>
						<th>$<?= number_format($total, 2) ?></th>
					</tr>
				</tfoot>
			</table>
			</div>
		  </div>
		<?php 
		 	//Sort Status by Solicitudes
		 	sort_object($wo_status, "conteo");
		 ?>
		<div class="span6 printable">
		  	<h3 class="span12">
		  		OT's por Estatus
				<div class="opciones">
					<a href="#" class="btn btn-primary toggleTable" data-target="#statusTable" data-resize="#statusCell">
						<i class="icon-list-alt"></i>
					</a>
					<?php if($access_export_xls): ?>
						<button type="button" class="btn btn-primary imprimir">
							<i class="icon-print"></i>
						</button>
						<a class="btn btn-primary" href="<?= base_url("solicitudes/download/status") ?>">
							<i class="icon-download-alt"></i>
						</a>
					<?php endif; ?>
			  	</div>
		  	</h3>
			<div class="span12 graph-container" id="statusCell" style="height: 450px">
				<canvas id="statusContainer"></canvas>
			</div>
			<div class="span12 table-container" id="statusTable" style="display: none">
				<table class="table table-striped" style="margin-left: 30px">
				<thead>
					<tr>
						<th>Estatus</th>
						<th>OT'S</th>
					</tr>
				</thead>
				<tbody>
					<?php $total = 0; ?>
					<?php foreach ($wo_status as $order): ?>
						<tr>
							<td><?= $order["status"] ?></td>
							<td>
								<a href="#" class="popup" data-search="status" data-value="<?= $order["status"] ?>">
									<?= $order["conteo"] ?>
								</a>	
							</td>
							<?php $total += $order["conteo"] ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<th>Total</th>
						<th><?= $total ?></th>
					</tr>
				</tfoot>
			</table>
			</div>
		  </div>

	 </div>
  </div>
  <div class="tab-pane <?= printEquals($selected_tab, "reporte", "active") ?>" id="reporte">
  	<div class="printable">
	  	<h3 class="span12">
			Reporte General
	  		<?php if($access_export_xls): ?>
				  	<div class="opciones">
				  		<button type="button" class="btn btn-primary imprimir">
							<i class="icon-print"></i>
						</button>
						<a class="btn btn-primary" href="<?= base_url("solicitudes/download/summary") ?>">
							<i class="icon-download-alt"></i>
						</a>
				  	</div>
		  	<?php endif; ?>
		</h3>
		<?php $this->load->view('solicitudes/reporte_general_table', array("general_data" => $wo_general, "tfoot_class" => "tfoot", "show_tfoot" => 1)); ?>
	</div>
  </div>
</div>