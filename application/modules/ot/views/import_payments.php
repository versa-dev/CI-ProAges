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
  	$min_year = 2001;
  	$max_year = date('Y');
  	$current_month = date('n');

  	$posted_month = $this->input->post('month');
  	$selected_month = $posted_month ? $posted_month : $current_month;
  	$posted_year = $this->input->post('year');
  	$selected_year = $posted_year ? $posted_year : $max_year;

  	$posted_month_delete = $this->input->post('month_delete');
  	$selected_month_delete = $posted_month_delete ? $posted_month_delete : $current_month;
  	$posted_year_delete = $this->input->post('year_delete');
  	$selected_year_delete = $posted_year_delete ? $posted_year_delete : $max_year;

 	$month_texts = array(
		1 => 'Enero',
		2 => 'Febrero',
		3 => 'Marzo',
		4 => 'Abril',
		5 => 'Mayo',
		6 => 'Junio',
		7 => 'Julio',
		8 => 'Agosto',
		9 => 'Septiembre',
		10 => 'Octubre',
		11 => 'Noviembre',
		12 => 'Diciembre',
 	);
  	$head_cells = array(
		'is_new' => 'Es nuevo negocio',
		'year_prime' => 'Año prima',
		'wathdo' => '¿Asignar el pago a OT?',
		'payment_date' => 'Fecha de pago real',
		'clave' => 'Clave',
		'agent_uidsnational' => 'Folio nacional',
		'agent_uidsprovincial' => 'Folio provincial',
		'agent' => 'Agente',
		'uid' => 'Poliza',
		'amount' => 'Prima',
		'percentage' => 'Porcentaje',
		'product_id' => 'Ramo',
	  //'name' => 'Asegurado',
		'name' => 'Nombre del agente importado',
	    'allocated_prime' => 'Prima a ubicar',
		'bonus_prime' => 'Prima para pago de bono',
		'interl' => 'Clave de Agente',
		'inter' => 'Nombre de Agente',
		'fla_num' => 'Folio Agente',
		'poliza' => 'Poliza',
		'po_ubi1_da' => 'Porcentaje para Prima a Ubicar',
		'po_pag1_da' => 'Porcentaje para Prima Pago Bono',
		'insured' => 'Asegurado',
		'clave_gmm' => 'Nombre de Agente',
		'plan_type' => 'Tipo de plan',
		'tipo_deducible' => 'Tipo Deducible'
	
	);
	$fields_not_shown = array('wathdo', 'imported_folio', 'imported_agent_name', 'import_date', '');
	$is_posted = (count($_POST) > 0);
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
			Importar Payments
		</li>
	</ul>
</div>
 
<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header well" data-original-title>
			<h2></h2>
			<div class="box-icon"></div>
		</div>

		<div class="box-content">
			<?php if( isset( $message ) && isset( $message['type'] ) ): ?>
				<?php if( $message['type'] == true ): ?>
					<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<img src="<?php echo base_url() ?>images/true.png" width="20" height="20" />
					<strong>Listo: </strong>
				<?php else: ?>
					<div class="alert alert-error">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>Error: </strong>
				<?php endif; ?>
				<?php
					if (!is_array($message['message'])):
						echo $message['message'];
					else:
						foreach( $message['message'] as $raiz ):
							if( empty( $raiz ) ) break;
								foreach( $raiz as $array ):
									if( empty( $array ) ) break;
										foreach( $array as $messagetext ):
											if( empty( $messagetext ) ) break;
												echo $messagetext.'<br>';
										endforeach;
								endforeach;
						endforeach;
					endif;
				?>
				</div>
			<?php endif; ?>

			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab1" data-toggle="tab">Importar Pagos</a></li>
					<li><a href="#tab2" data-toggle="tab">Importar Pagos SELO</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab1">
						<form id="formfile" action="<?php echo base_url() ?>ot/import_payments.html" class="form-horizontal <?php if (!$is_posted && $access_delete) echo ' span7' ?>" method="post" enctype="multipart/form-data" <?php if (!$is_posted && $access_delete): ?>style="border-right: 1px solid #CCCCCC"<?php endif?>>
							<h4>Importar pagos :</h4>
							<fieldset>
								<div class="control-group">
									<label class="control-label text-error" for="inputError">Archivo: </label>
									<div class="controls">
										<input  class="input-file uniform_on required" id="fileInput" name="file" type="file" multiple><br />
										<small class="text">Archivo CSV o EXCEL</small>
									</div>
								</div>

								<label class="control-label text-error" for="inputError">Ramo: </label>
								<div class="controls">
									<select id="product_select" name="product" class="required" style="width: 13em">
			                            <option value="">Seleccione</option>
			                            <option value="1">Vida - Previo</option>
			                            <option value="4">Vida - SELO</option>
										<option value="2">GMM - Previo</option>
			                            <option value="5">GMM - SELO</option>
										<option value="8">Generar SELO Vida</option>
										<option value="9">Generar SELO GMM</option>
										<option value="10">(TEST) Importacion SELO Termometro</option>
										<?php //if( isset( $products ) and !empty( $products ) ) echo $products; ?>
									</select>
								</div>
								<br>
							</fieldset>
							<div id="actions-buttons-forms" class="form-actions">
								<button id="btnImport" type="submit" class="btn btn-primary" data-toggle="collapse" data-target="#table_collapse_import">Cargar</button>
								<input type="button" class="btn" onclick="javascript: history.back()" value="Cancelar">
							</div>
							<div id="table_collapse_import" class="accordion-body collapse">
								<div id="content_collapse" class="accordion-inner">
									<!--
									<table id="table_progress" class="table text-center">
										<thead class="thead-light">
											<tr>
												<th scope="col">Nombre de la hoja</th>
												<th scope="col">Progreso</th>
											</tr>
										</thead>
										<tbody id="body_table_import">
										</tbody>
									</table>
									-->
								</div>
							</div>
						</form>
						<?php if (!$is_posted && $access_delete): ?>
							<form id="import-delete" action="<?php echo base_url() ?>ot/delete_payments.html" class="form-horizontal span5" style="min-width: 320px" method="post">
								<h4>Borrar pagos :</h4>
								<div class="control-group">
									<label class="control-label text-error" for="month_year">Del mes / año: &nbsp;</label>
									<div class="controls">
										<select name="month_delete" id="month-delete" class="required" style="width: 8em">
											<?php foreach ($month_texts as $key => $month_text):
												$selected = ($key == $selected_month_delete) ? ' selected="selected"' : '';?>
												<option value="<?php echo sprintf("%02d", $key) ?>" <?php echo $selected ?>><?php echo $month_text ?></option>
											<?php endforeach; ?>
										</select>
										<select name="year_delete" id="year-delete" class="required" style="width: 8em">
											<?php for ($i = $max_year; $i > $min_year; $i--):
												$selected = ($i == $selected_year_delete) ? ' selected="selected"' : '';?>
												<option value="<?php echo $i ?>" <?php echo $selected ?>><?php echo $i ?></option>
											<?php endfor; ?>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label text-error" for="inputError">Ramo: </label>
									<div class="controls">
										<select name="product_type_delete" class="required" id="product-type-delete" style="width: 8em">
			                            	<option value="">Seleccione</option>
			                                <option value="1">Vida</option>
			                                <option value="2">GMM</option>
			                                <!--<option value="3">Auto</option>-->
											<?php //if( isset( $products ) and !empty( $products ) ) echo $products; ?>
										</select>
									</div>
								</div>
								<div id="borrar-button-form" class="form-actions">
									<button type="submit" id="delete-submit" class="btn btn-primary">Borrar</button>
								</div>
								
							</form>
						<?php endif; ?>
						<div style="clear: both">&nbsp;</div>
						<?php
							/**
						 *	Change Index, Selectes options fields
						 **/
						?>
						<?php if( isset( $tmp_file ) and $process == 'change-index' ): // Is is load a file?>
							<form action="<?php echo base_url() ?>ot/import_payments.html" id="import-form" class="form-horizontal" method="post">
								<input type="hidden" name="tmp_file" value="<?php echo $tmp_file ?>">
								<input type="hidden" name="process" value="<?php echo $process ?>">
								<input type="hidden" name="product" value="<?php echo $product ?>" />
								<?php if (($posted_month !== FALSE) && ($posted_year !== FALSE) ): ?>
									<input name="month" value="<?php echo $posted_month ?>" />
									<input name="year" value="<?php echo $posted_year ?>" />
								<?php else: ?>
									<div class="alert alert-info">
										Se encontró la siguiente fecha de importación, presione "Pre importar" para continuar.
									</div>
									<div class="control-group">
										<label class="control-label text-error" for="month_year">Mes - Año de la importacion:</label>
										<div class="controls">
											<select name="month" class="required span3" disabled>
												<?php foreach ($month_texts as $key => $month_text):
													$selected = ($key == $month) ? ' selected="selected"' : '';
												?>
													<option value="<?php echo $posted_month ?>" <?php echo $selected ?>><?php echo $month_text ?></option>
												<?php endforeach; ?>
											</select>
											<select name="year" class="required span2" disabled>
												<?php for ($i = $max_year; $i > $min_year; $i--):
												$selected = ($i == $selected_year) ? ' selected="selected"' : '';
												?>
													<option value="<?php echo $i ?>" <?php echo $selected ?>><?php echo $year ?></option>
												<?php endfor; ?>
											</select>
										</div>
									</div>
								<?php endif; ?>
								<div style="display: none" class="alert alert-info">
									Especifique a qué campos corresponde la información que está importando en las siguientes cajas de selección
								</div>
								<div style="max-width:100%; overflow:scroll; max-height:400px; display: none">
									<table class="table table-rounder">
										<?php
											if( !empty( $file_array ) ):  // Create select for fields
												foreach( $file_array as $rows ):
													if( !empty( $rows ) ):
														echo '<tr>';
														$i=1;
														foreach( $rows as $value ):
															echo'<td class="column'.$i.'">
																	<select class="required" id="select'.$i.'" name="'.$i.'" style="width:100px;" onchange="hide('.$i.')">
																		<option value="">Seleccione</option>
																		<option value="nonimport">No importar</option>
																		<optgroup label="----------"></optgroup>
																		<option value="year_prime">Año Prima</option>
																		<option value="is_new">Es nuevo negocio</option>
																		<option value="payment_date">Fecha de pago real</option>
																		<option value="clave">Clave del agente</option>
																		<option value="agent_uidsnational">Folio nacional del agente</option>
																		<option value="agent_uidsprovincial">Folio provincial del agente</option>
																		<!--<option value="">Nombre del agente</option>-->
																		<option value="name">Nombre del Asegurado</option>
																		<option value="uid">Número de póliza</option>
																		<option value="percentage">Porcentaje de participación</option>
																		<option value="amount">Prima</option>
																		<option value="product_id">Ramo</option>
																	</select>
																</td>';
															$i++;
														endforeach;
														break;
														echo '</tr>';
													endif;
												endforeach;
											endif;
										?>
										<?php
											if( !empty( $file_array ) ):  // Show data
												foreach( $file_array as $rows ):
													if( !empty( $rows ) ): 
														$i=0;
														echo '<tr>';
														foreach( $rows as $value ):
															echo '<td class="column'.$i.'">'.$value.'</td>';
															$i++;
														endforeach;
														echo '</tr>';
													endif;
												endforeach;
											endif;
										?>
									</table>
								</div>

								<div id="actions-buttons-forms-send" class="form-actions">
										<button type="submit" class="btn btn-primary">Pre Importar</button>
									<input type="button" class="btn" onclick="javascript: history.back()" value="Cancelar">
								</div>
							</form>
						<?php endif; ?>
						<?php
							/**
							 *	Choose Agents
							 **/
						?>
						<?php if ($is_posted): ?>
							<div id="dialog-form" title="Request new user" style="display:none">
								<iframe src="<?php echo base_url() ?>usuarios/create_request_new_user.html" width="800" height="600"></iframe>
							</div>
						<?php endif; ?>
							<input type="hidden" id="control" />
							<?php if( isset( $tmp_file ) and $process == 'choose-agents' ): // Is is load a file?>
								<form action="<?php echo base_url() ?>ot/import_payments.html" id="import-form" method="post">
									<input type="hidden" name="tmp_file" value="<?php echo $tmp_file ?>">
									<input type="hidden" name="process" value="<?php echo $process ?>">
									<input type="hidden" name="product" value="<?php echo $product ?>" />
									<div class="alert alert-info">
										Verifique los agentes relacionados
									</div>
									<div style="max-width:100%; overflow:scroll; max-height:400px;">
										<table class="table table-rounder">
											<?php
												if( !empty( $file_array ) ):  // Show data
													$i=0;
													foreach( $file_array as $rows ):
														if( $i > 0 ) break;
														if( !empty( $rows ) ):
															echo "\n<tr>\n";
															foreach( $rows as $key => $value ):
																if (!in_array($key, $fields_not_shown))
																	echo '<th>' . $head_cells[$key] . '</th>';
															endforeach;
															echo "\n</tr>";
														endif;
														$i++;
													endforeach;
													foreach( $file_array as $rows ):
														if( !empty( $rows ) ):
															$style = (isset($rows['agent']) && (strpos($rows['agent'], '<select name=') === 0)) ? '' : 'style="display: none"';
															echo "\n<tr $style>\n";
															foreach( $rows as $key => $value ):
																if (!in_array($key, $fields_not_shown))
																	echo '<td>'.$value.'</td>';
															endforeach;
															echo "\n</tr>";
														endif;
													endforeach;
												endif;
											?>
										</table>
									</div>
									<div id="actions-buttons-forms-send" class="form-actions">
										<button type="submit" class="btn btn-primary">Vista de importación</button>
										<input type="button" class="btn" onclick="javascript: history.back()" value="Cancelar">
									</div>
								</form>
							<?php endif; ?>
							<?php
								/**
								 *	Preview save data
								 **/
							?>
							<?php if( isset( $tmp_file ) and $process == 'preview' ): // Is is load a file?>
								<form action="<?php echo base_url() ?>ot/import_payments.html" id="import-form" method="post">
									<input type="hidden" name="tmp_file" value="<?php echo $tmp_file ?>">
									<input type="hidden" name="process" value="<?php echo $process ?>">
									<input type="hidden" name="product" value="<?php echo $product ?>" />
									<div class="alert alert-info">
										Revise la información.
									</div>

									<div style="max-width:100%; overflow:scroll; max-height:400px;">
										<table class="table table-rounder">
											<?php
												if( !empty( $file_array ) ):  // Show data
											  		$i=0;
											  		foreach( $file_array as $rows ):
														if( $i > 0 ) break;
														if( !empty( $rows ) ):
															echo '<tr>';
															foreach( $rows as $key => $value ):
																if (!in_array($key, $fields_not_shown))
																	echo '<th>' . $head_cells[$key] .  '</th>';
															endforeach;
															echo '</tr>';
														endif;
														$i++;
											  		endforeach;
											  		foreach( $file_array as $rows ):
														if( !empty( $rows ) ):
															echo '<tr>';
															foreach( $rows as $key => $value ):
																if (!in_array($key, $fields_not_shown))
																	echo '<td>'.$value.'</td>';
															endforeach;
															echo '</tr>';
														endif;
											  		endforeach;
												endif;
											?>
										</table>

									</div>

									<div id="actions-buttons-forms-send" class="form-actions">
										<button type="submit" id="btnImport" class="btn btn-primary">Importar</button>
										<input type="button" class="btn" onclick="javascript: history.back()" value="Cancelar">
									</div>
								</form>
							<?php endif; ?>
							<div id="table_collapse_preview" class="accordion-body collapse">
								<div id="content_collapse_preview" class="accordion-inner">
									<div class="alert alert-info">Revise la información una vez pre-importada.</div>
									<div style="max-width:100%; overflow:scroll; max-height:400px;">
										<table id="table_preview" class="table text-center table-rounder">
											<thead class="thead-light" id="head_preview"></thead>
											<tbody id="body_table_import_preview"></tbody>
										</table>
									</div>
								</div>
							</div>
					</div>
					<div class="tab-pane" id="tab2">
						<div class="row-fluid">
							<div class="span12">
								<div class="span7">
									<form id="form-test" action="<?php echo base_url() ?>ot/import_payments.html" class="form-horizontal <?php if (!$is_posted && $access_delete) echo '' ?>" method="post" enctype="multipart/form-data" <?php if (!$is_posted && $access_delete): ?>style="border-right: 1px solid #CCCCCC"<?php endif?>>
										<h4>Importar pagos de SELO :</h4>
										<fieldset>
											<div class="control-group">
												<label class="control-label text-error" for="inputError">Archivo: </label>
												<div class="controls">
													<input  class="input-file uniform_on required" id="fileInput" name="file" type="file" multiple><br />
													<small class="text">Archivo CSV o EXCEL</small>
												</div>
											</div>
											<label class="control-label text-error" for="inputError">Ramo: </label>
											<label class="controls" for="inputError">Importacion SELO Termometro</label>
											<br>
										</fieldset>
										<div id="actions-buttons-forms" class="form-actions">
											<button id="btnImport_selo" type="submit" class="btn btn-primary btn-lg" data-toggle="collapse" data-target="#table_collapse">
												<i class="fa fa-circle-o-notch fa-spin"></i> Cargar
											</button>
											<input type="button" class="btn" onclick="javascript: history.back()" value="Cancelar">
										</div>
										<div id="table_collapse" class="accordion-body collapse">
											<div class="accordion-inner">
												<table id="table_progress" class="table text-center">
													<thead class="thead-light">
														<tr>
															<th scope="col">Nombre de la hoja</th>
															<th scope="col">Progreso</th>
														</tr>
													</thead>
													<tbody id="body_table">
													</tbody>
												</table>
											</div>
										</div>
									</form>

								</div>
								

								<div class="span5">
									<div id="errors_import">						
									</div>
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div><!--/span-->
</div><!--/row-->
