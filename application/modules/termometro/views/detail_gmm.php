<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
/** 
 * Termometro de Ventas - Vista Detalle General
 * 
 * @author     Jesus Castilla & José Gilberto Pérez Molina
 * Date:       December, 2018
 * Locaion:    Veracruz, Mexico
 * Mail:       jesuscv1821@gmail.com - jgilbertopmolina@gmail.com
 */
	$post_data = isset($_POST['query']) ? ',prev_post:'. json_encode($_POST['query']) : '';
  	$base_url = base_url();
  	$is_update_page = ($this->uri->segment(2, '') == 'update');
  	if(isset($_GET['id']))
  	{
  		if ($_GET['id'] != 0)
  		{
  			$id = $_GET['id'];
  		}
  	}
  	else
  	{
  		$id = $data['other_filters']['agent'];
  	}
  	error_log(print_r($id,true));

  	
?>
<style type="text/css">
	td { text-align: right !important; }
	.text-left { text-align: left !important; }
</style>
<div>
	<div>
		<ul class="breadcrumb">
			<li>
				<a href="<?php echo base_url() ?>">Admin</a> <span class="divider">/</span>
			</li>
			<li>
				<a href="<?php echo base_url() ?>termometro.html">Termómetro de Ventas</a> <span class="divider">/</span>
			</li>
			<li>
				Detalle GMM
			</li>
		</ul>
	</div>
	<div class="row-fluid">
		<form id="sales-activity-form" action="<?php echo current_url() ?>" class="" method="post">
			<table class="filterstable no-more-tables" style="width: auto">
				<thead>
					<th>Período :<br />
						<?= form_dropdown('periodo', $data['periods'], $data['other_filters']['periodo'], 'id="periodo" style="min-width: 430px" title="Período" onchange="this.form.submit();"'); ?>  
					</th>
					<th>Agente :<br />
						<textarea placeholder="BUSCAR AGENTE" id="agent-name" name="agent_name" rows="1" class="input-xlarge select4" style="min-width: 430px; max-width: 300px; height: 2em"></textarea>
					</th>
				</thead>
			</table>        
		</form>
	</div>
	
	
	<div class="row-fluid">
	<?php if($data['values_first_agent'] != null): ?>
		<?php 
			//Definicion de variables
		  	$primas_ubicar = array(
		  		$data['values_first_agent']['primas_ubicar'][0],
		  		$data['values_first_agent']['primas_ubicar'][1],
		  		$data['values_first_agent']['primas_ubicar'][2],
		  		$data['values_first_agent']['primas_ubicar'][3],
		  		$data['values_first_agent']['primas_ubicar'][4]

		  	);
		  	$primas_pagar = array(
		  		$data['values_first_agent']['primas_pagar'][0],
		  		$data['values_first_agent']['primas_pagar'][1],
		  		$data['values_first_agent']['primas_pagar'][2],
		  		$data['values_first_agent']['primas_pagar'][3],
		  		$data['values_first_agent']['primas_pagar'][4]
		  	);
		  	$nuevos_asegurados = array(
		  		$data['values_first_agent']['nuevos_asegurados'][0],
		  		$data['values_first_agent']['nuevos_asegurados'][1],
		  		$data['values_first_agent']['nuevos_asegurados'][2],
		  		$data['values_first_agent']['nuevos_asegurados'][3],
		  		$data['values_first_agent']['nuevos_asegurados'][4]
		  	);
		  	$comisiones_directas = array(
		  		$data['values_first_agent']['comisiones_directas'][0],
		  		$data['values_first_agent']['comisiones_directas'][1],
		  		$data['values_first_agent']['comisiones_directas'][2],
		  		$data['values_first_agent']['comisiones_directas'][3],
		  		$data['values_first_agent']['comisiones_directas'][4]
		  	);
		  	$perce_bono_primer_anio = array(
		  		$data['values_first_agent']['perce_bono_primer_anio'][0],
		  		$data['values_first_agent']['perce_bono_primer_anio'][1],
		  		$data['values_first_agent']['perce_bono_primer_anio'][2]
		  	);
		  	$bono_primer_anio = array(
		  		$data['values_first_agent']['bono_primer_anio'][0],
		  		$data['values_first_agent']['bono_primer_anio'][1],
		  		$data['values_first_agent']['bono_primer_anio'][2],
		  		$data['values_first_agent']['bono_primer_anio'][3],
		  		$data['values_first_agent']['bono_primer_anio'][4]
		  	);
		  	$faltante_bono_primer_anio = array(
		  		$data['values_first_agent']['faltante_bono_primer_anio'][0],
		  		$data['values_first_agent']['faltante_bono_primer_anio'][1],
		  		$data['values_first_agent']['faltante_bono_primer_anio'][2]
		  	);
		  	$primas_netas = array(
		  		$data['values_first_agent']['primas_netas'][0],
		  		$data['values_first_agent']['primas_netas'][1],
		  		$data['values_first_agent']['primas_netas'][2],
		  		$data['values_first_agent']['primas_netas'][3],
		  		$data['values_first_agent']['primas_netas'][4]
		  	);
		  	$siniestros_pagados_real = array(
		  		$data['values_first_agent']['siniestros_pagados_real'][0],
		  		$data['values_first_agent']['siniestros_pagados_real'][1],
		  		$data['values_first_agent']['siniestros_pagados_real'][2],
		  		$data['values_first_agent']['siniestros_pagados_real'][3],
		  		$data['values_first_agent']['siniestros_pagados_real'][4]
		  	);
		  	$siniestros_pagados_acot = array(
		  		$data['values_first_agent']['siniestros_pagados_acot'][0],
		  		$data['values_first_agent']['siniestros_pagados_acot'][1],
		  		$data['values_first_agent']['siniestros_pagados_acot'][2],
		  		$data['values_first_agent']['siniestros_pagados_real'][3],
		  		$data['values_first_agent']['siniestros_pagados_real'][4]
		  	);
		  	$result_siniestralidad_real = array(
		  		$data['values_first_agent']['result_siniestralidad_real'][0],
		  		$data['values_first_agent']['result_siniestralidad_real'][1],
		  		$data['values_first_agent']['result_siniestralidad_real'][2]
		  	);
		  	$result_siniestralidad_acot = array(
		  		$data['values_first_agent']['result_siniestralidad_acot'][0],
		  		$data['values_first_agent']['result_siniestralidad_acot'][1],
		  		$data['values_first_agent']['result_siniestralidad_acot'][2]
		  	);
		  	$cartera_conservada = array(
		  		$data['values_first_agent']['cartera_conservada'][0],
		  		$data['values_first_agent']['cartera_conservada'][1],
		  		$data['values_first_agent']['cartera_conservada'][2],
		  		$data['values_first_agent']['cartera_conservada'][3],
		  		$data['values_first_agent']['cartera_conservada'][4]
		  	);
		  	$comision_cartera = array(
		  		$data['values_first_agent']['comision_cartera'][0],
		  		$data['values_first_agent']['comision_cartera'][1],
		  		$data['values_first_agent']['comision_cartera'][2],
		  		$data['values_first_agent']['comision_cartera'][3],
		  		$data['values_first_agent']['comision_cartera'][4]
		  	);
		  	$numero_asegurados = array(
		  		$data['values_first_agent']['numero_asegurados'][0],
		  		$data['values_first_agent']['numero_asegurados'][1],
		  		$data['values_first_agent']['numero_asegurados'][2],
		  		$data['values_first_agent']['numero_asegurados'][3],
		  		$data['values_first_agent']['numero_asegurados'][4]
		  	);
		  	$perce_bono_rentabilidad = array(
		  		$data['values_first_agent']['perce_bono_rentabilidad'][0],
		  		$data['values_first_agent']['perce_bono_rentabilidad'][1],
		  		$data['values_first_agent']['perce_bono_rentabilidad'][2]
		  	);
		  	$bono_rentabilidad = array(
		  		$data['values_first_agent']['bono_rentabilidad'][0],
		  		$data['values_first_agent']['bono_rentabilidad'][1],
		  		$data['values_first_agent']['bono_rentabilidad'][2],
		  		$data['values_first_agent']['bono_rentabilidad'][3],
		  		$data['values_first_agent']['bono_rentabilidad'][4]
		  	);
		  	$faltante_bono_rentabilidad = array(
		  		$data['values_first_agent']['faltante_bono_rentabilidad'][0],
		  		$data['values_first_agent']['faltante_bono_rentabilidad'][1],
		  		$data['values_first_agent']['faltante_bono_rentabilidad'][2]
		  	);
		  	$bono_rentabilidad_no_ganado = array(
		  		$data['values_first_agent']['bono_rentabilidad_no_ganado'][0],
		  		$data['values_first_agent']['bono_rentabilidad_no_ganado'][1],
		  		$data['values_first_agent']['bono_rentabilidad_no_ganado'][2],
		  		$data['values_first_agent']['bono_rentabilidad_no_ganado'][3],
		  		$data['values_first_agent']['bono_rentabilidad_no_ganado'][4]
		  	);
		  	$suma_ingresos = array(
		  		$data['values_first_agent']['suma_ingresos'][0],
		  		$data['values_first_agent']['suma_ingresos'][1],
		  		$data['values_first_agent']['suma_ingresos'][2],
		  		$data['values_first_agent']['suma_ingresos'][3],
		  		$data['values_first_agent']['suma_ingresos'][4]
		  	);
		  	$cartera_estimada = array(
		  		$data['values_first_agent']['cartera_estimada'][0],
		  		$data['values_first_agent']['cartera_estimada'][1],
		  		$data['values_first_agent']['cartera_estimada'][2],
		  		$data['values_first_agent']['cartera_estimada'][3],
		  		$data['values_first_agent']['cartera_estimada'][4]
		  	);
		  	$cartera_real = array(
		  		$data['values_first_agent']['cartera_real'][0],
		  		$data['values_first_agent']['cartera_real'][1],
		  		$data['values_first_agent']['cartera_real'][2],
		  		$data['values_first_agent']['cartera_real'][3],
		  		$data['values_first_agent']['cartera_real'][4]
		  	);
		  	$periodos = array(
		  		"1".$data['other_filters']['periodo'],
		  		"2".$data['other_filters']['periodo'],
		  		"3".$data['other_filters']['periodo'],
		  		"4".$data['other_filters']['periodo'],
	        );
		?>
		<h3><?= $data['values_first_agent']['name'] ?> (<?= $data['values_first_agent']['generation'] ?>)</h3>
		<h3><a href="<?php echo base_url().'termometro/detail_vida/?id='.$data['other_filters']['agent'].'' ?>">Vida</a> | GMM</h3>
	    <div class="tabbable">
	      <ul class="nav nav-tabs">
	            <li class="active"><a href="#tab1" data-toggle="tab">Resumen</a></li>
	            <li><a href="#tab2" data-toggle="tab">Simulación</a></li>
	          </ul>
	    </div>
	    <div class="tab-content">
	      <div class="tab-pane active" id="tab1">
	      	<div class="row-fluid">
	            <form id="sales-activity-form" target="_blank" action="<?php echo base_url().'termometro/show_pdf_gmm' ?>" class="" method="post">
	              	<button class="btn pull-right" type="submit" ><i class="icon-file"></i> Exportar a PDF</button>
	            </form>
          	</div>
	      	<div class="row-fluid">
	      		<div class="box span6">
	      			<h4 align="center">Congreso</h4>
	      			<p align="center"><?= $data['values_first_agent']['congreso'] ?></p>
	      			<h4 align="center">Proximo Congreso</h4>
	      			<p align="center"><?= $data['values_first_agent']['congreso_siguiente'] ?></p>
	      			<h4 align="center">Faltante de producción inicial</h4>
	      			<p align="center">$ <?= $data['values_first_agent']['faltante_produccion_inicial'] ?></p>
	      		</div>
	      		<div class="box span6">
	      			<h4 align="center">Agente Productivo</h4>
	      			<p align="center"><?= $data['values_first_agent']['agente_productivo'][0] ?></p>
	      			<h4 align="center">Faltante para ser Agente Productivo</h4>
	      			<p align="center">$ <?= $data['values_first_agent']['agente_productivo'][1] ?></p>
	      		</div>   
	      	</div>
	      	<table class="table table-hover table-bordered">
				<thead class="thead-dark">
					<tr>
						<th></th>
						<th>1er Cuatrimestre</th>
						<th>2o Cuatrimestre</th>
						<th>3er Cuatrimestre</th>
						<th class="celda_amarilla">Acumulado Anual</th>
						<th class="celda_verde">Promedio Mesual </th>
					</tr>
		      </thead>
		      <tbody>
		      	<tr>
		      		<td class="text-left">Cartera Pronosticada</td>
		      		<td class="text-info">$ <?= $cartera_estimada[0] ?> </td>
		      		<td class="text-info">$ <?= $cartera_estimada[1] ?> </td>
		      		<td class="text-info">$ <?= $cartera_estimada[2] ?> </td>
		      		<td colspan="2"></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Cartera Real</td>
		      		<td class="text-info">$ <?= $cartera_real[0] ?> </td>
		      		<td class="text-info">$ <?= $cartera_real[1] ?> </td>
		      		<td class="text-info">$ <?= $cartera_real[2] ?> </td>
		      		<td class="celda_amarilla text-info">$ <?= $cartera_real[3] ?> </td>
		      		<td class="celda_verde text-info">$ <?= $cartera_real[4] ?> </td>
		      	</tr>
		      	<tr>
		      		<td colspan="6"></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Prima P/Ubicar</td>
		      		<td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'prima_ubicar', periodo: '<?php echo $periodos[0]?>', id:'<?php echo $id?>'})">$ <?= $primas_ubicar[0] ?> </a></td>
		      		<td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'prima_ubicar', periodo: '<?php echo $periodos[1]?>', id:'<?php echo $id?>'})">$ <?= $primas_ubicar[1] ?> </a></td>
		      		<td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'prima_ubicar', periodo: '<?php echo $periodos[2]?>', id:'<?php echo $id?>'})">$ <?= $primas_ubicar[2] ?> </a></td>
		      		<td class="celda_amarilla text-info">$ <?= $primas_ubicar[3] ?></td>
		      		<td class="celda_verde text-info">$ <?= $primas_ubicar[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Prima P/Pagar</td>
		      		<td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'prima_pagar', periodo: '<?php echo $periodos[0]?>', id:'<?php echo $id?>'})">$ <?= $primas_pagar[0] ?> </a></td>
		      		<td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'prima_pagar', periodo: '<?php echo $periodos[1]?>', id:'<?php echo $id?>'})">$ <?= $primas_pagar[1] ?> </a></td>
		      		<td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'prima_pagar', periodo: '<?php echo $periodos[2]?>', id:'<?php echo $id?>'})">$ <?= $primas_pagar[2] ?> </a></td>
		      		<td class="celda_amarilla text-info">$ <?= $primas_pagar[3] ?></td>
		      		<td class="celda_verde text-info">$ <?= $primas_pagar[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Nuevos Asegurados</td>
		      		<td class="text-info"> <?= $nuevos_asegurados[0] ?> </td>
		      		<td class="text-info"> <?= $nuevos_asegurados[1] ?> </td>
		      		<td class="text-info"> <?= $nuevos_asegurados[2] ?> </td>
		      		<td class="celda_amarilla text-info">  <?= $nuevos_asegurados[3] ?></td>
		      		<td class="celda_verde text-info"> <?= $nuevos_asegurados[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td colspan="6"></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Comisiones Directas</td>
		      		<td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'comisiones_directas', periodo: '<?php echo $periodos[0]?>', id:'<?php echo $id?>'})">$ <?= $comisiones_directas[0] ?></a></td>
		      		<td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'comisiones_directas', periodo: '<?php echo $periodos[1]?>', id:'<?php echo $id?>'})">$ <?= $comisiones_directas[1] ?> </a></td>
		      		<td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'comisiones_directas', periodo: '<?php echo $periodos[2]?>', id:'<?php echo $id?>'})">$ <?= $comisiones_directas[2] ?> </a></td>
		      		<td class="celda_amarilla text-info">$ <?= $comisiones_directas[3] ?></td>
		      		<td class="celda_verde text-info">$ <?= $comisiones_directas[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">% de bono de 1er año</td>
		      		<td class="text-info"><?= $perce_bono_primer_anio[0] ?> %</td>
		      		<td class="text-info"><?= $perce_bono_primer_anio[1] ?> %</td>
		      		<td class="text-info"><?= $perce_bono_primer_anio[2] ?> %</td>
		      		<td colspan="2"></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Bono de 1er año</td>
		      		<td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'bono_primer_anio', periodo: '<?php echo $periodos[0]?>', id:'<?php echo $id?>'})">$ <?= $bono_primer_anio[0] ?> </a></td>
		      		<td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'bono_primer_anio', periodo: '<?php echo $periodos[1]?>', id:'<?php echo $id?>'})">$ <?= $bono_primer_anio[1] ?> </a></td>
		      		<td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'bono_primer_anio', periodo: '<?php echo $periodos[2]?>', id:'<?php echo $id?>'})">$ <?= $bono_primer_anio[2] ?> </a></td>
		      		<td class="celda_amarilla text-info">$ <?= $bono_primer_anio[3] ?></td>
		      		<td class="celda_verde text-info">$ <?= $bono_primer_anio[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Faltante para siguiente renglon de Bono</td>
		      		<td class="text-info">$ <?= $faltante_bono_primer_anio[0] ?> </td>
		      		<td class="text-info">$ <?= $faltante_bono_primer_anio[1] ?> </td>
		      		<td class="text-info">$ <?= $faltante_bono_primer_anio[2] ?> </td>
		      		<td colspan="2"></td>
		      	</tr>
		      	<tr>
		      		<td colspan="6"></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Primas netas pagadas en los utimos 12 meses</td>
		      		<td class="text-info">$ <?= $primas_netas[0] ?> </td>
		      		<td class="text-info">$ <?= $primas_netas[1] ?> </td>
		      		<td class="text-info">$ <?= $primas_netas[2] ?> </td>
		      		<td class="celda_amarilla text-info">$ <?= $primas_netas[3] ?></td>
		      		<td class="celda_verde text-info">$ <?= $primas_netas[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Siniestros Pagados-Real</td>
		      		<td class="text-info">$ <?= $siniestros_pagados_real[0] ?> </td>
		      		<td class="text-info">$ <?= $siniestros_pagados_real[1] ?> </td>
		      		<td class="text-info">$ <?= $siniestros_pagados_real[2] ?> </td>
		      		<td class="celda_amarilla text-info">$ <?= $siniestros_pagados_real[3] ?></td> 
		      		<td class="celda_verde text-info">$ <?= $siniestros_pagados_real[4] ?></td>     		
		      	</tr>
		      	<tr>
		      		<td class="text-left">Siniestros Pagados-Acotados</td>
		      		<td class="text-info">$ <?= $siniestros_pagados_acot[0] ?> </td>
		      		<td class="text-info">$ <?= $siniestros_pagados_acot[1] ?> </td>
		      		<td class="text-info">$ <?= $siniestros_pagados_acot[2] ?> </td>
		      		<td class="celda_amarilla text-info">$ <?= $siniestros_pagados_acot[3] ?></td>
		      		<td class="celda_verde text-info">$ <?= $siniestros_pagados_acot[4] ?></td>	
		      	</tr>
		      	<tr>
		      		<td class="text-left">Resultados de siniestralidad real</td>
		      		<td class="text-info"><?= $result_siniestralidad_real[0] ?> %</td>
		      		<td class="text-info"><?= $result_siniestralidad_real[1] ?> %</td>
		      		<td class="text-info"><?= $result_siniestralidad_real[2] ?> %</td>
		      		<td colspan="2"></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Resultados de siniestralidad acotado</td>
		      		<td class="text-info"><?= $result_siniestralidad_acot[0] ?> %</td>
		      		<td class="text-info"><?= $result_siniestralidad_acot[1] ?> %</td>
		      		<td class="text-info"><?= $result_siniestralidad_acot[2] ?> %</td>
		      		<td colspan="2"></td>
		      	</tr>	
		      	<tr>
		      		<td colspan="6"></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Cartera Conservada</td>
		      		<td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'cartera_conservada', periodo: '<?php echo $periodos[0]?>', id: '<?php echo $id ?>'})">$ <?= $cartera_conservada[0] ?> </a></td>
		      		<td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'cartera_conservada', periodo: '<?php echo $periodos[1]?>', id: '<?php echo $id ?>'})">$ <?= $cartera_conservada[1] ?> </a></td>
		      		<td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'cartera_conservada', periodo: '<?php echo $periodos[2]?>', id: '<?php echo $id ?>'})">$ <?= $cartera_conservada[2] ?> </a></td>
		      		<td class="celda_amarilla text-info">$ <?= $cartera_conservada[3] ?></td>
		      		<td class="celda_verde text-info">$ <?= $cartera_conservada[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Comisión Cartera</td>
		      		<td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'comision_cartera', periodo: '<?php echo $periodos[0]?>', id: '<?php echo $id ?>'})">$ <?= $comision_cartera[0] ?> </a></td>
		      		<td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'comision_cartera', periodo: '<?php echo $periodos[1]?>', id: '<?php echo $id ?>'})">$ <?= $comision_cartera[1] ?> </a></td>
		      		<td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'comision_cartera', periodo: '<?php echo $periodos[2]?>', id: '<?php echo $id ?>'})">$ <?= $comision_cartera[2] ?> </a></td>
					<td class="celda_amarilla text-info">$ <?= $comision_cartera[3] ?></td>
		      		<td class="celda_verde text-info">$ <?= $comision_cartera[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Número de Asegurados</td>
		      		<td class="text-info"> <?= $numero_asegurados[0] ?> </td>
		      		<td class="text-info"> <?= $numero_asegurados[1] ?> </td>
		      		<td class="text-info"> <?= $numero_asegurados[2] ?> </td>
		      		<td class="celda_amarilla text-info"> <?= $numero_asegurados[3] ?></td>
		      		<td class="celda_verde text-info"> <?= $numero_asegurados[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">% bono de rentabilidad</td>
		      		<td class="text-info"><?= $perce_bono_rentabilidad[0] ?> %</td>
		      		<td class="text-info"><?= $perce_bono_rentabilidad[1] ?> %</td>
		      		<td class="text-info"><?= $perce_bono_rentabilidad[2] ?> %</td>
		      		<td colspan="2"></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Bono de rentabilidad</td>
		      		<td class="text-info" class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'bono_rentabilidad', periodo: '<?php echo $periodos[0]?>', id: '<?php echo $id ?>'})">$ <?= $bono_rentabilidad[0] ?> </a></td>
		      		<td class="text-info" class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'bono_rentabilidad', periodo: '<?php echo $periodos[1]?>', id: '<?php echo $id ?>'})">$ <?= $bono_rentabilidad[1] ?> </a></td>
		      		<td class="text-info" class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'bono_rentabilidad', periodo: '<?php echo $periodos[2]?>', id: '<?php echo $id ?>'})">$ <?= $bono_rentabilidad[2] ?> </a></td>
		      		<td class="celda_amarilla text-info">$ <?= $bono_rentabilidad[3] ?></td>
		      		<td class="celda_verde text-info">$ <?= $bono_rentabilidad[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Faltante para el siguiente renglon de bono de rentabilidad</td>
		      		<td class="text-info">$ <?= $faltante_bono_rentabilidad[0] ?> </td>
		      		<td class="text-info">$ <?= $faltante_bono_rentabilidad[1] ?> </td>
		      		<td class="text-info">$ <?= $faltante_bono_rentabilidad[2] ?> </td>
		      		<td colspan="2"></td>
		      	</tr>
		      	<tr>
		      		<td colspan="6"></td>

		      	</tr>
		      	<tr class="error">
		      		<td class="text-left">Bono de rentabilidad no ganado</td>
		      		<td class="text-info">$ <?= $bono_rentabilidad_no_ganado[0] ?> </td>
		      		<td class="text-info">$ <?= $bono_rentabilidad_no_ganado[1] ?> </td>
		      		<td class="text-info">$ <?= $bono_rentabilidad_no_ganado[2] ?> </td>
		      		<td class="celda_amarilla text-info">$ <?= $bono_rentabilidad_no_ganado[3] ?></td>
		      		<td class="celda_verde text-info">$ <?= $bono_rentabilidad_no_ganado[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td colspan="6"></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Suma de ingresos</td>
		      		<td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'suma_ingresos', periodo: '<?php echo $periodos[0]?>', id: '<?php echo $id ?>'})">$ <?= $suma_ingresos[0] ?> </a></td>
		      		<td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'suma_ingresos', periodo: '<?php echo $periodos[1]?>', id: '<?php echo $id ?>'})">$ <?= $suma_ingresos[1] ?> </a></td>
		      		<td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'suma_ingresos', periodo: '<?php echo $periodos[2]?>', id: '<?php echo $id ?>'})">$ <?= $suma_ingresos[2] ?> </a></td>
		      		<td class="celda_amarilla text-info">$ <?= $suma_ingresos[3] ?></td>
		      		<td class="celda_verde text-info">$ <?= $suma_ingresos[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td colspan="6"></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Total de ingresos</td>
		      		<td class="text-info text-left" class="text-info" colspan="5"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'ingresos_totales', periodo: '<?php echo $periodos[0]?>', id: '<?php echo $id ?>'})">$ <?= $data['values_first_agent']['ingresos_totales'] ?> </a></td>
		      	</tr>
		      </tbody>
			</table>
	      </div>
	      <div class="tab-pane" id="tab2"> 
	      	<div class="row-fluid">
	        	<form id="simulate_form_export" target="_blank" action="<?php echo base_url().'termometro/show_pdf_gmm_simulation' ?>" class="" method="post">
	            	<button class="btn pull-right" type="submit" id="btn_export" ><i class="icon-file"></i> Exportar a PDF</button>
	            </form>
	         </div>
	        <h3>Instrucciones</h3>
          	<p class="pagination-centered">Para realizar una simulación realice los cambios pertinentes a las celdas remarcadas en verde, siguiendo su formato correspondiente, una vez completos haga clic en el botón de "Simular"</p>
          	<h3><button type="button" class="btn-large center-block" id="button_simulation">Simular</button>   <button type="button" class="btn-large" id="button_restore">Restaurar</button></h3>
          	<br></br>
          	<div class="row-fluid">
	      		<div class="box span6">
	      			<h4 align="center">Congreso</h4>
	      			<p id="cell_congreso" align="center"><?= $data['values_first_agent']['congreso'] ?></p>
	      			<h4 align="center">Proximo Congreso</h4>
	      			<p id="cell_congreso_siguiente" align="center"><?= $data['values_first_agent']['congreso_siguiente'] ?></p>
	      			<h4 align="center">Faltante de producción inicial</h4>
	      			<p id="cell_faltante_produccion_inicial" align="center">$ <?= $data['values_first_agent']['faltante_produccion_inicial'] ?></p>
	      		</div>
	      		<div class="box span6">
	      			<h4 align="center">Agente Productivo</h4>
	      			<p id="cell_agente_productivo" align="center"><?= $data['values_first_agent']['agente_productivo'][0] ?></p>
	      			<h4 align="center">Faltante para ser Agente Productivo</h4>
	      			<p id="cell_faltante_agente_productivo" align="center">$ <?= $data['values_first_agent']['agente_productivo'][1] ?></p>
	      		</div>   
	      	</div>
	        <table class="table table-hover table-bordered" id="table_simulation_gmm">
				<thead class="thead-dark">
					<tr>
						<th></th>
						<th>1er Cuatrimestre</th>
						<th>2o Cuatrimestre</th>
						<th>3er Cuatrimestre</th>
						<th class="celda_amarilla">Acumulado Anual</th>
						<th class="celda_verde">Promedio Mensual </th>
					</tr>
		      </thead>
		      <tbody>
		      	<tr>
		      		<td class="text-left">Cartera Pronosticada</td>
		      		<td class="text-info">$ <?= $cartera_estimada[0] ?> </td>
		      		<td class="text-info">$ <?= $cartera_estimada[1] ?> </td>
		      		<td class="text-info">$ <?= $cartera_estimada[2] ?> </td>
		      		<td colspan="2"></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Cartera Real</td>
		      		<td class="text-info">$ <?= $cartera_real[0] ?> </td>
		      		<td class="text-info">$ <?= $cartera_real[1] ?> </td>
		      		<td class="text-info">$ <?= $cartera_real[2] ?> </td>
		      		<td class="celda_amarilla text-info">$ <?= $cartera_real[3] ?> </td>
		      		<td class="celda_verde text-info">$ <?= $cartera_real[4] ?> </td>
		      	</tr>
		      	<tr>
		      		<td colspan="6"></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Prima P/Ubicar</td>
		      		<td id="cell_prima_ubicar_1" class="celda_verde" contenteditable="true" style="background-color: #5cb85c; color: white;" >$ <?= $primas_ubicar[0] ?> </td>
		      		<td id="cell_prima_ubicar_2" class="celda_verde" contenteditable="true" style="background-color: #5cb85c; color: white;" >$ <?= $primas_ubicar[1] ?> </td>
		      		<td id="cell_prima_ubicar_3" class="celda_verde" contenteditable="true" style="background-color: #5cb85c; color: white;" >$ <?= $primas_ubicar[2] ?> </td>
		      		<td class="celda_amarilla text-info" id="cell_prima_ubicar_sum">$ <?= $primas_ubicar[3] ?></td>
		      		<td class="celda_verde text-info" id="cell_prima_ubicar_avg">$ <?= $primas_ubicar[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Prima P/Pagar</td>
		      		<td id="cell_prima_pago_1" class="celda_verde" contenteditable="true" style="background-color: #5cb85c; color: white;" >$ <?= $primas_pagar[0] ?> </td>
		      		<td id="cell_prima_pago_2" class="celda_verde" contenteditable="true" style="background-color: #5cb85c; color: white;" >$ <?= $primas_pagar[1] ?> </td>
		      		<td id="cell_prima_pago_3" class="celda_verde" contenteditable="true"  id="cell_prima_pago_3" style="background-color: #5cb85c; color: white;">$ <?= $primas_pagar[2] ?> </td>
		      		<td class="celda_amarilla text-info" id="cell_prima_pago_sum">$ <?= $primas_pagar[3] ?></td>
		      		<td class="celda_verde text-info" id="cell_prima_pago_avg">$ <?= $primas_pagar[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Nuevos Asegurados</td>
		      		<td id="cell_nuevos_asegurados_1" class="celda_verde" contenteditable="true" style="background-color: #5cb85c; color: white;" > <?= $nuevos_asegurados[0] ?> </td>
		      		<td id="cell_nuevos_asegurados_2" class="celda_verde" contenteditable="true" style="background-color: #5cb85c; color: white;" > <?= $nuevos_asegurados[1] ?> </td>
		      		<td id="cell_nuevos_asegurados_3" class="celda_verde" contenteditable="true" style="background-color: #5cb85c; color: white;" > <?= $nuevos_asegurados[2] ?> </td>
		      		<td class="celda_amarilla text-info" id="cell_nuevos_asegurados_sum">  <?= $nuevos_asegurados[3] ?></td>
		      		<td class="celda_verde text-info" id="cell_nuevos_asegurados_avg"> <?= $nuevos_asegurados[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td colspan="6"></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Comisiones Directas</td>
		      		<td class="text-info" id="cell_comisiones_directas_1">$ <?= $comisiones_directas[0] ?> </td>
		      		<td class="text-info" id="cell_comisiones_directas_2">$ <?= $comisiones_directas[1] ?> </td>
		      		<td class="text-info" id="cell_comisiones_directas_3">$ <?= $comisiones_directas[2] ?> </td>
		      		<td class="celda_amarilla text-info" id="cell_comisiones_directas_sum">$ <?= $comisiones_directas[3] ?></td>
		      		<td class="celda_verde text-info" id="cell_comisiones_directas_avg">$ <?= $comisiones_directas[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">% de bono de 1er año</td>
		      		<td class="text-info" id="cell_bono_primer_anio_perc_1">% <?= $perce_bono_primer_anio[0] ?> </td>
		      		<td class="text-info" id="cell_bono_primer_anio_perc_2">% <?= $perce_bono_primer_anio[1] ?> </td>
		      		<td class="text-info" id="cell_bono_primer_anio_perc_3">% <?= $perce_bono_primer_anio[2] ?> </td>
		      		<td colspan="2"></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Bono de 1er año</td>
		      		<td class="text-info" id="cell_bono_primer_anio_1">$ <?= $bono_primer_anio[0] ?> </td>
		      		<td class="text-info" id="cell_bono_primer_anio_2">$ <?= $bono_primer_anio[1] ?> </td>
		      		<td class="text-info" id="cell_bono_primer_anio_3">$ <?= $bono_primer_anio[2] ?> </td>
		      		<td class="celda_amarilla text-info" id="cell_bono_primer_anio_sum">$ <?= $bono_primer_anio[3] ?></td>
		      		<td class="celda_verde text-info" id="cell_bono_primer_anio_avg">$ <?= $bono_primer_anio[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Faltante para siguiente renglon de Bono</td>
		      		<td class="text-info" id="cell_faltante_bono_primer_anio_1">$ <?= $faltante_bono_primer_anio[0] ?> </td>
		      		<td class="text-info" id="cell_faltante_bono_primer_anio_2">$ <?= $faltante_bono_primer_anio[1] ?> </td>
		      		<td class="text-info" id="cell_faltante_bono_primer_anio_3">$ <?= $faltante_bono_primer_anio[2] ?> </td>	
		      		<td colspan="2"></td>
		      	</tr>
		      	<tr>
		      		<td colspan="6"></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Primas netas pagadas en los utimos 12 meses</td>
		      		<td class="text-info" id="cell_primas_netas_1">$ <?= $primas_netas[0] ?> </td>
		      		<td class="text-info" id="cell_primas_netas_2">$ <?= $primas_netas[1] ?> </td>
		      		<td class="text-info" id="cell_primas_netas_3">$ <?= $primas_netas[2] ?> </td>
		      		<td class="celda_amarilla text-info" id="cell_primas_netas_sum">$ <?= $primas_netas[3] ?></td>
		      		<td class="celda_verde text-info" id="cell_primas_netas_avg">$ <?= $primas_netas[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Siniestros Pagados-Real</td>
		      		<td class="text-info" id="cell_siniestros_pagados_real_1">$ <?= $siniestros_pagados_real[0] ?> </td>
		      		<td class="text-info" id="cell_siniestros_pagados_real_2">$ <?= $siniestros_pagados_real[1] ?> </td>
		      		<td class="text-info" id="cell_siniestros_pagados_real_3">$ <?= $siniestros_pagados_real[2] ?> </td>
		      		<td class="celda_amarilla text-info" id="cell_siniestros_pagados_real_sum">$ <?= $siniestros_pagados_real[3] ?></td> 
		      		<td class="celda_verde text-info" id="cell_siniestros_pagados_real_avg">$ <?= $siniestros_pagados_real[4] ?></td>     		
		      	</tr>
		      	<tr>
		      		<td class="text-left">Siniestros Pagados-Acotados</td>
		      		<td class="text-info" id="cell_siniestros_pagados_acot_1">$ <?= $siniestros_pagados_acot[0] ?> </td>
		      		<td class="text-info" id="cell_siniestros_pagados_acot_2">$ <?= $siniestros_pagados_acot[1] ?> </td>
		      		<td class="text-info" id="cell_siniestros_pagados_acot_3">$ <?= $siniestros_pagados_acot[2] ?> </td>
		      		<td class="celda_amarilla text-info" id="cell_siniestros_pagados_acot_sum">$ <?= $siniestros_pagados_acot[3] ?></td>
		      		<td class="celda_verde text-info" id="cell_siniestros_pagados_acot_avg">$ <?= $siniestros_pagados_acot[4] ?></td>	
		      	</tr>
		      	<tr>
		      		<td class="text-left">Resultados de siniestralidad real</td>
		      		<td class="text-info" class="text-info" id="cell_result_siniestralidad_real_1">% <?= $result_siniestralidad_real[0] ?> </td>
		      		<td class="text-info" class="text-info" id="cell_result_siniestralidad_real_2">% <?= $result_siniestralidad_real[1] ?> </td>
		      		<td class="text-info" class="text-info" id="cell_result_siniestralidad_real_3">% <?= $result_siniestralidad_real[2] ?> </td>
		      		<td colspan="2"></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Resultados de siniestralidad acotado</td>
		      		<td class="text-info" id="cell_result_siniestralidad_acot_1">% <?= $result_siniestralidad_acot[0] ?> </td>
		      		<td class="text-info" id="cell_result_siniestralidad_acot_2">% <?= $result_siniestralidad_acot[1] ?> </td>
		      		<td class="text-info" id="cell_result_siniestralidad_acot_3">% <?= $result_siniestralidad_acot[2] ?> </td>
		      		<td colspan="2"></td>
		      	</tr>	
		      	<tr>
		      		<td colspan="6"></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Cartera Conservada</td>
		      		<td class="text-info" id="cell_cartera_conservada_1" class="celda_verde" contenteditable="true" style="background-color: #5cb85c; color: white;" >$ <?= $cartera_conservada[0] ?> </td>
		      		<td class="text-info" id="cell_cartera_conservada_2" class="celda_verde" contenteditable="true" style="background-color: #5cb85c; color: white;" >$ <?= $cartera_conservada[1] ?> </td>
		      		<td class="text-info" id="cell_cartera_conservada_3" class="celda_verde" contenteditable="true" style="background-color: #5cb85c; color: white;" >$ <?= $cartera_conservada[2] ?> </td>
		      		<td class="celda_amarilla text-info" id="cell_cartera_conservada_sum">$ <?= $cartera_conservada[3] ?></td>
		      		<td class="celda_verde text-info"id="cell_cartera_conservada_avg">$ <?= $cartera_conservada[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Comisión Cartera</td>
		      		<td class="text-info" id="cell_comision_cartera_1">$ <?= $comision_cartera[0] ?> </td>
		      		<td class="text-info" id="cell_comision_cartera_2">$ <?= $comision_cartera[1] ?> </td>
		      		<td class="text-info" id="cell_comision_cartera_3">$ <?= $comision_cartera[2] ?> </td>
					<td class="celda_amarilla text-info" id="cell_comision_cartera_sum">$ <?= $comision_cartera[3] ?></td>
		      		<td class="celda_verde text-info" id="cell_comision_cartera_avg">$ <?= $comision_cartera[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Número de Asegurados</td>
		      		<td class="text-info" id="cell_numero_asegurados_1"> <?= $numero_asegurados[0] ?> </td>
		      		<td class="text-info" id="cell_numero_asegurados_2"> <?= $numero_asegurados[1] ?> </td>
		      		<td class="text-info" id="cell_numero_asegurados_3"> <?= $numero_asegurados[2] ?> </td>
		      		<td class="celda_amarilla text-info" id="cell_numero_asegurados_sum"> <?= $numero_asegurados[3] ?></td>
		      		<td class="celda_verde text-info" id="cell_numero_asegurados_avg"> <?= $numero_asegurados[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">% bono de rentabilidad</td>
		      		<td class="text-info" id="cell_perce_bono_rentabilidad_1">% <?= $perce_bono_rentabilidad[0] ?> </td>
		      		<td class="text-info" id="cell_perce_bono_rentabilidad_2">% <?= $perce_bono_rentabilidad[1] ?> </td>
		      		<td class="text-info" id="cell_perce_bono_rentabilidad_3">% <?= $perce_bono_rentabilidad[2] ?> </td>
		      		<td colspan="2"></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Bono de rentabilidad</td>
		      		<td class="text-info" id="cell_bono_rentabilidad_1">$ <?= $bono_rentabilidad[0] ?> </td>
		      		<td class="text-info" id="cell_bono_rentabilidad_2">$ <?= $bono_rentabilidad[1] ?> </td>
		      		<td class="text-info" id="cell_bono_rentabilidad_3">$ <?= $bono_rentabilidad[2] ?> </td>
		      		<td class="celda_amarilla text-info" id="cell_bono_rentabilidad_sum">$ <?= $bono_rentabilidad[3] ?></td>
		      		<td class="celda_verde text-info" id="cell_bono_rentabilidad_avg">$ <?= $bono_rentabilidad[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Faltante para el siguiente renglon de bono de rentabilidad</td>
		      		<td class="text-info" id="cell_faltante_bono_rentabilidad_1">$ <?= $faltante_bono_rentabilidad[0] ?> </td>
		      		<td class="text-info" id="cell_faltante_bono_rentabilidad_2">$ <?= $faltante_bono_rentabilidad[1] ?> </td>
		      		<td class="text-info" id="cell_faltante_bono_rentabilidad_3">$ <?= $faltante_bono_rentabilidad[2] ?> </td>
		      		<td colspan="2"></td>
		      	</tr>
		      	<tr>
		      		<td colspan="6"></td>

		      	</tr>
		      	<tr class="error">
		      		<td class="text-left">Bono de rentabilidad no ganado</td>
		      		<td class="text-info" id="cell_bono_rentabilidad_no_ganado_1">$ <?= $bono_rentabilidad_no_ganado[0] ?> </td>
		      		<td class="text-info" id="cell_bono_rentabilidad_no_ganado_2">$ <?= $bono_rentabilidad_no_ganado[1] ?> </td>
		      		<td class="text-info" id="cell_bono_rentabilidad_no_ganado_3">$ <?= $bono_rentabilidad_no_ganado[2] ?> </td>
		      		<td class="celda_amarilla text-info" id="cell_bono_rentabilidad_no_ganado_sum">$ <?= $bono_rentabilidad_no_ganado[3] ?></td>
		      		<td class="celda_verde text-info" id="cell_bono_rentabilidad_no_ganado_avg">$ <?= $bono_rentabilidad_no_ganado[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td colspan="6"></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Suma de ingresos</td>
		      		<td class="text-info" id="cell_suma_ingresos_1">$ <?= $suma_ingresos[0] ?> </td>
		      		<td class="text-info" id="cell_suma_ingresos_2">$ <?= $suma_ingresos[1] ?> </td>
		      		<td class="text-info" id="cell_suma_ingresos_3">$ <?= $suma_ingresos[2] ?> </td>
		      		<td class="celda_amarilla text-info" id="cell_suma_ingresos_sum">$ <?= $suma_ingresos[3] ?></td>
		      		<td class="celda_verde text-info" id="cell_suma_ingresos_avg">$ <?= $suma_ingresos[4] ?></td>
		      	</tr>
		      	<tr>
		      		<td colspan="6"></td>
		      	</tr>
		      	<tr>
		      		<td class="text-left">Total de ingresos</td>
		      		<td class="text-info text-left" colspan="5" id="cell_ingresos_totales">$ <?= $data['values_first_agent']['ingresos_totales'] ?> </td>
		      	</tr>
		      </tbody>
			</table>
	      </div>
	    </div>
	<?php endif; ?>
	
  </div>
</div>
