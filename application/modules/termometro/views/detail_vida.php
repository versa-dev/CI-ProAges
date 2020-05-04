<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
  /*
    Termometro de Ventas - Vista Detalle por Agente (Vida)
    Autor:		Jesus Castilla || José Gilberto Pérez Molina
    Un Milagro de Navidad 2018
   */
  $post_data = isset($_POST['query']) ? ',prev_post:'. json_encode($_POST['query']) : '';
  $base_url = base_url();
  $is_update_page = ($this->uri->segment(2, '') == 'update');
  $id = 0;
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
  			Detalle Vida
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
        $cartera_real = array(
          $data['values_first_agent']['cartera_real'][0],
          $data['values_first_agent']['cartera_real'][1],
          $data['values_first_agent']['cartera_real'][2],
          $data['values_first_agent']['cartera_real'][3],
          $data['values_first_agent']['cartera_real'][4],
          $data['values_first_agent']['cartera_real'][5]
        );
        $prima_ubicar = array(
          $data['values_first_agent']['prima_ubicar'][0],
          $data['values_first_agent']['prima_ubicar'][1],
          $data['values_first_agent']['prima_ubicar'][2],
          $data['values_first_agent']['prima_ubicar'][3],
          $data['values_first_agent']['prima_ubicar'][4],
          $data['values_first_agent']['prima_ubicar'][5]
        );
        $prima_pago = array(
          $data['values_first_agent']['prima_pago'][0],
          $data['values_first_agent']['prima_pago'][1],
          $data['values_first_agent']['prima_pago'][2],
          $data['values_first_agent']['prima_pago'][3],
          $data['values_first_agent']['prima_pago'][4],
          $data['values_first_agent']['prima_pago'][5]
        );
        $numero_negocios = array(
          $data['values_first_agent']['numero_negocios'][0],
          $data['values_first_agent']['numero_negocios'][1],
          $data['values_first_agent']['numero_negocios'][2],
          $data['values_first_agent']['numero_negocios'][3],
          $data['values_first_agent']['numero_negocios'][4],
          $data['values_first_agent']['numero_negocios'][5]
        );
        $comisiones_directas = array(
          $data['values_first_agent']['comisiones_directas'][0],
          $data['values_first_agent']['comisiones_directas'][1],
          $data['values_first_agent']['comisiones_directas'][2],
          $data['values_first_agent']['comisiones_directas'][3],
          $data['values_first_agent']['comisiones_directas'][4],
          $data['values_first_agent']['comisiones_directas'][5]
        );
        $perce_bono_primer_anio = array(
          $data['values_first_agent']['perce_bono_primer_anio'][0],
          $data['values_first_agent']['perce_bono_primer_anio'][1],
          $data['values_first_agent']['perce_bono_primer_anio'][2],
          $data['values_first_agent']['perce_bono_primer_anio'][3]
        );
        $bono_primer_anio = array(
          $data['values_first_agent']['bono_primer_anio'][0],
          $data['values_first_agent']['bono_primer_anio'][1],
          $data['values_first_agent']['bono_primer_anio'][2],
          $data['values_first_agent']['bono_primer_anio'][3],
          $data['values_first_agent']['bono_primer_anio'][4],
          $data['values_first_agent']['bono_primer_anio'][5]
        );
        $conservacion = array(
          $data['values_first_agent']['conservacion'][0],
          $data['values_first_agent']['conservacion'][1],
          $data['values_first_agent']['conservacion'][2],
          $data['values_first_agent']['conservacion'][3],
          $data['values_first_agent']['conservacion'][4],
          $data['values_first_agent']['conservacion'][5]
        );
        $comision_cartera = array(
          $data['values_first_agent']['comision_cartera'][0],
          $data['values_first_agent']['comision_cartera'][1],
          $data['values_first_agent']['comision_cartera'][2],
          $data['values_first_agent']['comision_cartera'][3],
          $data['values_first_agent']['comision_cartera'][4],
          $data['values_first_agent']['comision_cartera'][5]
        );
        $bono_cartera = array(
          $data['values_first_agent']['bono_cartera'][0],
          $data['values_first_agent']['bono_cartera'][1],
          $data['values_first_agent']['bono_cartera'][2],
          $data['values_first_agent']['bono_cartera'][3],
          $data['values_first_agent']['bono_cartera'][4],
          $data['values_first_agent']['bono_cartera'][5]
        );
        $bono_cartera_perce = array(
          $data['values_first_agent']['perce_bono_cartera'][0],
          $data['values_first_agent']['perce_bono_cartera'][1],
          $data['values_first_agent']['perce_bono_cartera'][2],
          $data['values_first_agent']['perce_bono_cartera'][3]
        );
        $bono_cartera_no_ganado = array(
          $data['values_first_agent']['bono_cartera_no_ganado'][0],
          $data['values_first_agent']['bono_cartera_no_ganado'][1],
          $data['values_first_agent']['bono_cartera_no_ganado'][2],
          $data['values_first_agent']['bono_cartera_no_ganado'][3],
          $data['values_first_agent']['bono_cartera_no_ganado'][4],
          $data['values_first_agent']['bono_cartera_no_ganado'][5]
        );
        $ingresos_totales = array(
          $data['values_first_agent']['suma_ingresos_totales'][0],
          $data['values_first_agent']['suma_ingresos_totales'][1],
          $data['values_first_agent']['suma_ingresos_totales'][2],
          $data['values_first_agent']['suma_ingresos_totales'][3],
          $data['values_first_agent']['suma_ingresos_totales'][4],
          $data['values_first_agent']['suma_ingresos_totales'][5]
        );
        $faltante_bono_primer_anio = array(
          $data['values_first_agent']['faltante_bono'][0],
          $data['values_first_agent']['faltante_bono'][1],
          $data['values_first_agent']['faltante_bono'][2],
          $data['values_first_agent']['faltante_bono'][3]
        );
        $faltante_bono_cartera = array(
          $data['values_first_agent']['faltate_bono_cartera'][0],
          $data['values_first_agent']['faltate_bono_cartera'][1],
          $data['values_first_agent']['faltate_bono_cartera'][2],
          $data['values_first_agent']['faltate_bono_cartera'][3]
        );
        $puntos_vida = array(
          $data['values_first_agent']['puntos_vida'][0],
          $data['values_first_agent']['puntos_vida'][1],
          $data['values_first_agent']['puntos_vida'][2],
          $data['values_first_agent']['puntos_vida'][3],
          $data['values_first_agent']['puntos_vida'][4],
          $data['values_first_agent']['puntos_vida'][5]
        );
        $puntos_gmm = array(
          $data['values_first_agent']['puntos_gmm'][0],
          $data['values_first_agent']['puntos_gmm'][1],
          $data['values_first_agent']['puntos_gmm'][2],
          $data['values_first_agent']['puntos_gmm'][3],
          $data['values_first_agent']['puntos_gmm'][4],
          $data['values_first_agent']['puntos_gmm'][5]
        );
        $puntos_auto = array(
          $data['values_first_agent']['puntos_autos'][0],
          $data['values_first_agent']['puntos_autos'][1],
          $data['values_first_agent']['puntos_autos'][2],
          $data['values_first_agent']['puntos_autos'][3],
          $data['values_first_agent']['puntos_autos'][4],
          $data['values_first_agent']['puntos_autos'][5]
        );
        $cartera_estimada = array(
          $data['values_first_agent']['cartera_estimada'][0],
          $data['values_first_agent']['cartera_estimada'][1],
          $data['values_first_agent']['cartera_estimada'][2],
          $data['values_first_agent']['cartera_estimada'][3]
        );
        $cartera_pronosticada = array(
          $data['values_first_agent']['cartera_pronosticada'][0],
          $data['values_first_agent']['cartera_pronosticada'][1],
          $data['values_first_agent']['cartera_pronosticada'][2],
          $data['values_first_agent']['cartera_pronosticada'][3]
        );
        $periodos = array(
          "1".$data['other_filters']['periodo'],
          "2".$data['other_filters']['periodo'],
          "3".$data['other_filters']['periodo'],
          "4".$data['other_filters']['periodo'],
        );
      ?>
      <h3><?= $data['values_first_agent']['name'] ?> (<?= $data['values_first_agent']['generation'] ?>)</h3>
      <h3>Vida | <a href="<?php echo base_url().'termometro/detail_gmm/?id='.$data['other_filters']['agent'].'' ?>">GMM</a></h3>
      <div class="tabbable">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab1" data-toggle="tab">Resumen</a></li>
          <li><a href="#tab2" data-toggle="tab">Simulación</a></li>
        </ul>
      </div>
      <div class="tab-content">
        <div class="tab-pane active" id="tab1">
          <div class="row-fluid">
            <form id="sales-activity-form" target="_blank" action="<?php echo base_url().'termometro/show_pdf_vida' ?>" class="" method="post">
              <button class="btn pull-right" type="submit" ><i class="icon-file"></i> Exportar a PDF</button>
            </form>
          </div>
          <div class="row-fluid">
            <div class="box span4">
              <h4 align="center">Club Élite</h4>
              <p align="center"><?= $data['values_first_agent']['club_elite'] ?></p>
              <h4 align="center">Faltante Negocios PAI</h4>
              <p align="center"><?= $data['values_first_agent']['faltante_elite_neg'] ?></p>
              <h4 align="center">Faltante Producción</h4>
              <p align="center">$ <?= $data['values_first_agent']['faltante_elite_prod'] ?></p>
            </div>
            <div class="box span4">
              <h4 align="center">Congreso</h4>
              <p align="center"><?= $data['values_first_agent']['congreso'] ?></p>
              <h4 align="center">Proximo Congreso</h4>
              <p align="center"><?= $data['values_first_agent']['congreso_siguiente'] ?></p>
              <h4 align="center">Faltante Negocios</h4>
              <p align="center"><?= $data['values_first_agent']['faltante_congreso_neg'] ?></p>
              <h4 align="center">Faltante Producción</h4>
              <p align="center">$ <?= $data['values_first_agent']['faltante_congreso_prod'] ?></p>
            </div>
            <div class="box span4">
              <h4 align="center">Puntos Standing</h4>
              <p align="center"><?= $data['values_first_agent']['puntos_standing'] ?></p>
              <h4 align="center">Faltante Negocios</h4>
              <p align="center"><?= $data['values_first_agent']['faltante_ptos_standing_neg'] ?></p>
              <h4 align="center">Faltante Producción</h4>
              <p align="center">$ <?= $data['values_first_agent']['faltante_ptos_standing_pro'] ?></p>
            </div>    
          </div>
          <table class="table table-hover table-bordered">
            <thead class="thead-dark">
              <tr>
                <th> </th>
                <th>1er Trimestre</th>
                <th>2o Trimestre</th>
                <th>3er Trimestre</th>
                <th>4o Trimestre</th>
                <th class="celda_amarilla">Acumulado Anual</th>
                <th class="celda_verde">Promedio Mensual </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="text-left">Cartera Pronosticada</td>
                <td class="text-info">$ <?= $cartera_pronosticada[0] ?> </a></td>
                <td class="text-info">$ <?= $cartera_pronosticada[1] ?> </a></td>
                <td class="text-info">$ <?= $cartera_pronosticada[2] ?> </a></td>
                <td class="text-info">$ <?= $cartera_pronosticada[3] ?> </a></td>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td class="text-left">Cartera Real</td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'cartera_real', periodo: '<?php echo $periodos[0]?>', id:'<?php echo $id ?>'})">$ <?= $cartera_real[0] ?> </a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'cartera_real', periodo: '<?php echo $periodos[1]?>', id:'<?php echo $id ?>'})">$ <?= $cartera_real[1] ?> </a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'cartera_real', periodo: '<?php echo $periodos[2]?>', id:'<?php echo $id ?>'})">$ <?= $cartera_real[2] ?> </a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'cartera_real', periodo: '<?php echo $periodos[3]?>', id:'<?php echo $id ?>'})">$ <?= $cartera_real[3] ?> </a></td>
                <td class="celda_amarilla text-info">$ <?= $cartera_real[4] ?></td>
                <td class="celda_verde text-info">$ <?= $cartera_real[5]  ?></td>
              </tr>
              <tr class="">
                <td colspan="8"></td>
              </tr>
              <tr>
                <td class="text-left">Prima P/ Ubicar</td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'prima_ubicar', periodo: '<?php echo $periodos[0]?>', id:'<?php echo $id ?>'})">$ <?= $prima_ubicar[0] ?> </a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'prima_ubicar', periodo: '<?php echo $periodos[1]?>', id:'<?php echo $id ?>'})">$ <?= $prima_ubicar[1] ?> </a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'prima_ubicar', periodo: '<?php echo $periodos[2]?>', id:'<?php echo $id ?>'})">$ <?= $prima_ubicar[2] ?> </a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'prima_ubicar', periodo: '<?php echo $periodos[3]?>', id:'<?php echo $id ?>'})">$ <?= $prima_ubicar[3] ?> </a></td>
                <td class="celda_amarilla text-info">$ <?= $prima_ubicar[4] ?></td>
                <td class="celda_verde text-info">$ <?= $prima_ubicar[5] ?></td>
              </tr>
              <tr>
                <td class="text-left">Prima P/ Pago de Bono</td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'prima_pagar', periodo: '<?php echo $periodos[0]?>', id:'<?php echo $id ?>'})">$ <?= $prima_pago[0] ?> </a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'prima_pagar', periodo: '<?php echo $periodos[1]?>', id:'<?php echo $id ?>'})">$ <?= $prima_pago[1] ?> </a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'prima_pagar', periodo: '<?php echo $periodos[2]?>', id:'<?php echo $id ?>'})">$ <?= $prima_pago[2] ?> </a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'prima_pagar', periodo: '<?php echo $periodos[3]?>', id:'<?php echo $id ?>'})">$ <?= $prima_pago[3] ?> </a></td>
                <td class="celda_amarilla text-info">$ <?= $prima_pago[4] ?></td>
                <td class="celda_verde text-info">$ <?= $prima_pago[5] ?></td>
              </tr>
              <tr>
                <td class="text-left">Número de Negocios</td>
                <td class="text-info"><?= $numero_negocios[0] ?></td>
                <td class="text-info"><?= $numero_negocios[1] ?></td>
                <td class="text-info"><?= $numero_negocios[2] ?></td>
                <td class="text-info"><?= $numero_negocios[3] ?></td>
                <td class="celda_amarilla text-info"><?= $numero_negocios[4] ?></td>
                <td class="celda_verde text-info"><?= $numero_negocios[5] ?></td>
              </tr>
              <tr>
                <td colspan="8"></td>
              </tr>
              <tr>
                <td class="text-left">Comisiones Directas</td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'comisiones_directas', periodo: '<?php echo $periodos[0]?>', id:'<?php echo $id ?>'})">$ <?= $comisiones_directas[0] ?></a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'comisiones_directas', periodo: '<?php echo $periodos[1]?>', id:'<?php echo $id ?>'})">$ <?= $comisiones_directas[1] ?></a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'comisiones_directas', periodo: '<?php echo $periodos[2]?>', id:'<?php echo $id ?>'})">$ <?= $comisiones_directas[2] ?></a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'comisiones_directas', periodo: '<?php echo $periodos[3]?>', id:'<?php echo $id ?>'})">$ <?= $comisiones_directas[3] ?></a></td>
                <td class="celda_amarilla text-info">$ <?= $comisiones_directas[4] ?></td>
                <td class="celda_verde text-info">$ <?= $comisiones_directas[5] ?></td>
              </tr>
              <tr>
              <td class="text-left">% Bono Primer Año</td>
                <td class="text-info" class="text-info"><?= $perce_bono_primer_anio[0] ?>%</td>
                <td class="text-info" class="text-info"><?= $perce_bono_primer_anio[1] ?>%</td>
                <td class="text-info" class="text-info"><?= $perce_bono_primer_anio[2] ?>%</td>
                <td class="text-info" class="text-info"><?= $perce_bono_primer_anio[3] ?>%</td>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td class="text-left">Bono Primer Año</td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'bono_primer_anio', periodo: '<?php echo $periodos[0]?>', id:'<?php echo $id ?>'})">$ <?= $bono_primer_anio[0] ?> </a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'bono_primer_anio', periodo: '<?php echo $periodos[1]?>', id:'<?php echo $id ?>'})">$ <?= $bono_primer_anio[1] ?> </a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'bono_primer_anio', periodo: '<?php echo $periodos[2]?>', id:'<?php echo $id ?>'})">$ <?= $bono_primer_anio[2] ?> </a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'bono_primer_anio', periodo: '<?php echo $periodos[3]?>', id:'<?php echo $id ?>'})">$ <?= $bono_primer_anio[3] ?> </a></td>
                <td class="celda_amarilla text-info">$ <?= $bono_primer_anio[4] ?></td>
                <td class="celda_verde text-info">$ <?= $bono_primer_anio[5] ?></td>
              </tr>
              <tr>
                <td class="text-left">Faltante Bono Primer Año</td>
                <td class="text-info">$ <?= $faltante_bono_primer_anio[0] ?> </td>
                <td class="text-info">$ <?= $faltante_bono_primer_anio[1] ?> </td>
                <td class="text-info">$ <?= $faltante_bono_primer_anio[2] ?> </td>
                <td class="text-info">$ <?= $faltante_bono_primer_anio[3] ?> </td>
                <td colspan="2"> </td>
              </tr>
              <tr>
                <td colspan="7"></td>
              </tr>
              <tr>
                <td class="text-left">Conservación</td>
                <td class="text-info"><?= $conservacion[0] ?>%</td>
                <td class="text-info"><?= $conservacion[1] ?>%</td>
                <td class="text-info"><?= $conservacion[2] ?>%</td>
                <td class="text-info"><?= $conservacion[3] ?>%</td>
                <td colspan="2"> </td>
              </tr>
              <tr>
              <td class="text-left">Comision Cartera</td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'comision_cartera', periodo: '<?php echo $periodos[0]?>', id:'<?php echo $id ?>'})">$ <?= $comision_cartera[0] ?></a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'comision_cartera', periodo: '<?php echo $periodos[1]?>', id:'<?php echo $id ?>'})">$ <?= $comision_cartera[1] ?></a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'comision_cartera', periodo: '<?php echo $periodos[2]?>', id:'<?php echo $id ?>'})">$ <?= $comision_cartera[2] ?></a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'comision_cartera', periodo: '<?php echo $periodos[3]?>', id:'<?php echo $id ?>'})">$ <?= $comision_cartera[3] ?></a></td>
                <td class="celda_amarilla text-info">$ <?= $comision_cartera[4] ?></td>
                <td class="celda_verde text-info">$ <?= $comision_cartera[5] ?></td>
              </tr>
              <tr>
                <td class="text-left">% Bono Cartera</td>
                <td class="text-info"><?= $bono_cartera_perce[0] ?>%</td>
                <td class="text-info"><?= $bono_cartera_perce[1] ?>%</td>
                <td class="text-info"><?= $bono_cartera_perce[2] ?>%</td>
                <td class="text-info"><?= $bono_cartera_perce[3] ?>%</td>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td class="text-left">Bono Cartera</td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'bono_cartera', periodo: '<?php echo $periodos[0]?>', id:'<?php echo $id ?>'})">$ <?= $bono_cartera[0] ?></a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'bono_cartera', periodo: '<?php echo $periodos[1]?>', id:'<?php echo $id ?>'})">$ <?= $bono_cartera[1] ?></a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'bono_cartera', periodo: '<?php echo $periodos[2]?>', id:'<?php echo $id ?>'})">$ <?= $bono_cartera[2] ?></a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'bono_cartera', periodo: '<?php echo $periodos[3]?>', id:'<?php echo $id ?>'})">$ <?= $bono_cartera[3] ?></a></td>
                <td class="celda_amarilla text-info">$ <?= $bono_cartera[4] ?></td>
                <td class="celda_verde text-info">$ <?= $bono_cartera[5] ?></td>
              </tr>
              <tr>
                <td class="text-left">Faltante Bono Cartera</td>
                <td class="text-info">$ <?= $faltante_bono_cartera[0] ?></td>
                <td class="text-info">$ <?= $faltante_bono_cartera[1] ?></td>
                <td class="text-info">$ <?= $faltante_bono_cartera[2] ?></td>
                <td class="text-info">$ <?= $faltante_bono_cartera[3] ?></td>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td colspan="8"></td>
              </tr>
              <tr class="error">
                <td class="text-left">Bono Cartera No Ganado</td>
                <td class="text-info">$ <?= $bono_cartera_no_ganado[0] ?></td>
                <td class="text-info">$ <?= $bono_cartera_no_ganado[1] ?></td>
                <td class="text-info">$ <?= $bono_cartera_no_ganado[2] ?></td>
                <td class="text-info">$ <?= $bono_cartera_no_ganado[3] ?></td>
                <td class="celda_amarilla text-info">$ <?= $bono_cartera_no_ganado[4] ?></td>
                <td class="celda_verde text-info">$ <?= $bono_cartera_no_ganado[5] ?></td>
              </tr>
              <tr>
                <td colspan="8"></td>
              </tr>
              <tr class="info">
                <td class="text-left">Suma de Ingresos Totales</td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'suma_ingresos_totales', periodo: '<?php echo $periodos[0]?>', id:'<?php echo $id ?>'})">$ <?= $ingresos_totales[0] ?></a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'suma_ingresos_totales', periodo: '<?php echo $periodos[1]?>', id:'<?php echo $id ?>'})">$ <?= $ingresos_totales[1] ?></a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'suma_ingresos_totales', periodo: '<?php echo $periodos[2]?>', id:'<?php echo $id ?>'})">$ <?= $ingresos_totales[2] ?></a></td>
                <td class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'suma_ingresos_totales', periodo: '<?php echo $periodos[3]?>', id:'<?php echo $id ?>'})">$ <?= $ingresos_totales[3] ?></a></td>
                <td class="celda_amarilla text-info">$ <?= $ingresos_totales[4] ?></td>
                <td class="celda_verde text-info">$ <?= $ingresos_totales[5] ?></td>
              </tr>
              <tr>
                <td colspan="8"></td>
              </tr>
              <tr>
                <td class="text-left">Puntos</td>
                <td  class="text-left" colspan="4">Períodos</td>
                <td class="text-left">Total</td>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td class="text-left">Puntos Vida</td>
                <td class="text-info"> <?= $puntos_vida[0] ?> </td>
                <td class="text-info"> <?= $puntos_vida[1] ?> </td>
                <td class="text-info"> <?= $puntos_vida[2] ?> </td>
                <td class="celda_amarilla text-info"> <?= $puntos_vida[3] ?> </td>
                <td class="text-info"> <?= $puntos_vida[4] ?> </td>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td class="text-left">Puntos GMM</td>
                <td class="text-info"> <?= $puntos_gmm[0] ?></td>
                <td class="text-info"> <?= $puntos_gmm[1] ?></td>
                <td class="celda_amarilla text-info" colspan="2"> <?= $puntos_gmm[2] ?></td>
                <td class="text-info" > <?= $puntos_gmm[4] ?> </td>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td class="text-left">Puntos Autos</td>
                <td class="text-info"> <?= $puntos_auto[0] ?></td>
                <td class="text-info"> <?= $puntos_auto[1] ?></td>
                <td class="celda_amarilla text-info" colspan="2"> <?= $puntos_auto[2] ?></td>
                <td class="text-info" > <?= $puntos_auto[4] ?> </td>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td colspan="7"></td>
              </tr>
              <tr>
                <td class="text-left">Bono Integral</td>
                <td class="text-info text-left" colspan="6">$ <?= $data['values_first_agent']['bono_integral'] ?></td>
              </tr>
              <tr>
                <td colspan="7"></td>
              </tr>
              <tr>
                <td class="text-left">Total de ingresos</td>
                <td class="text-info text-left" colspan="6"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail_vida({type: 'total_ingresos', periodo: '<?php echo $periodos[0]?>', id:'<?php echo $id ?>'})">$ <?= $data['values_first_agent']['ingresos_totales'] ?> </a></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="tab-pane" id="tab2"> 
          
          <div class="row-fluid">
            <form id="simulate_form_export" target="_blank" action="<?php echo base_url().'termometro/show_pdf_vida_simulation' ?>" class="" method="post">
              <button class="btn pull-right" type="submit" id="btn_export" ><i class="icon-file"></i> Exportar a PDF</button>
            </form>
          </div>
          
          
          <h3>Instrucciones</h3>
          <p class="pagination-centered">Para realizar una simulación realice los cambios pertinentes a las celdas remarcadas en verde, siguiendo su formato correspondiente, una vez completos haga clic en el botón de "Simular"</p>
          <h3><button type="button" class="btn-large center-block" id="button_simulation">Simular</button>  <button type="button" class="btn-large" id="button_restore">Restaurar</button></h3>
          <br></br>
          <div class="row-fluid">
              <div class="box span4">
                  <h4 align="center">Club Elite</h4>
                  <p id="cell_club_elite" align="center"><?= $data['values_first_agent']['club_elite'] ?></p>
                  <h4 align="center">Faltante Negocios PAI</h4>
                  <p id="cell_faltante_elite_neg" align="center"><?= $data['values_first_agent']['faltante_elite_neg'] ?></p>
                  <h4 align="center">Faltante Producción</h4>
                  <p id="cell_faltante_elite_prod" align="center">$ <?= $data['values_first_agent']['faltante_elite_prod'] ?></p>
              </div>
              <div class="box span4">
                  <h4 align="center">Congreso</h4>
                  <p id="cell_congreso" align="center"><?= $data['values_first_agent']['congreso'] ?></p>
                  <h4 align="center">Proximo Congreso</h4>
                  <p id="cell_congreso_siguiente" align="center"><?= $data['values_first_agent']['congreso_siguiente'] ?></p>
                  <h4 align="center">Faltante Negocios</h4>
                  <p id="cell_faltante_congreso_neg" align="center"><?= $data['values_first_agent']['faltante_congreso_neg'] ?></p>
                  <h4 align="center">Faltante Producción</h4>
                  <p id="cell_faltante_congreso_prod" align="center">$ <?= $data['values_first_agent']['faltante_congreso_prod'] ?></p>
              </div>
              <div class="box span4">
                  <h4 align="center">Puntos Standing</h4>
                  <p id="cell_ptos_standing" align="center"><?= $data['values_first_agent']['puntos_standing'] ?></p>
                  <h4 align="center">Faltante Negocios</h4>
                  <p id="cell_faltante_ptos_standing_neg" align="center"><?= $data['values_first_agent']['faltante_ptos_standing_neg'] ?></p>
                  <h4 align="center">Faltante Producción</h4>
                  <p id="cell_faltante_ptos_standing_pro" align="center">$ <?= $data['values_first_agent']['faltante_ptos_standing_pro'] ?></p>  
              </div>    
          </div>
          <table class="table table-hover table-bordered" id="table_simulation_vida">
            <thead class="thead-dark">
              <tr>
                <th> </th>
                <th>1er Trimestre</th>
                <th>2o Trimestre</th>
                <th>3er Trimestre</th>
                <th>4o Trimestre</th>
                <th class="celda_amarilla">Total(Simulación)</th>
                <th class="celda_verde">Promedio Anual</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="text-left">Cartera Estimada</td>
                <td class="celda_verde" contenteditable="true" id="cell_cartera_real_0" style="background-color: #5cb85c; color: white;">$ <?= $cartera_estimada[0] ?> </a></td>
                <td class="celda_verde" contenteditable="true" id="cell_cartera_real_1" style="background-color: #5cb85c; color: white;">$ <?= $cartera_estimada[1] ?> </a></td>
                <td class="celda_verde" contenteditable="true" id="cell_cartera_real_2" style="background-color: #5cb85c; color: white;">$ <?= $cartera_estimada[2] ?> </a></td>
                <td class="celda_verde" contenteditable="true" id="cell_cartera_real_3" style="background-color: #5cb85c; color: white;">$ <?= $cartera_estimada[3] ?> </a></td>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td class="text-left">Cartera Pronosticada</td>
                <td class="text-info">$ <?= $cartera_pronosticada[0] ?> </a></td>
                <td class="text-info">$ <?= $cartera_pronosticada[1] ?> </a></td>
                <td class="text-info">$ <?= $cartera_pronosticada[2] ?> </a></td>
                <td class="text-info">$ <?= $cartera_pronosticada[3] ?> </a></td>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td class="text-left">Cartera Real</td>
                <td class="text-info">$ <?= $cartera_real[0] ?> </td>
                <td class="text-info">$ <?= $cartera_real[1] ?> </td>
                <td class="text-info">$ <?= $cartera_real[2] ?> </td>
                <td class="text-info">$ <?= $cartera_real[3] ?> </td>
                <td class="celda_amarilla text-info">$ <?= $cartera_real[4] ?></td>
                <td class="celda_verde text-info">$ <?= $cartera_real[5]  ?></td>
              </tr>
              <tr class="">
                <td colspan="8"></td>
              </tr>
              <tr>
                <td class="text-left">Prima P/ Ubicar</td>
                <td class="celda_verde" contenteditable="true" id="cell_prima_ubicar_0" style="background-color: #5cb85c; color: white;">$ <?= $prima_ubicar[0] ?> </td>
                <td class="celda_verde" contenteditable="true" id="cell_prima_ubicar_1" style="background-color: #5cb85c; color: white;">$ <?= $prima_ubicar[1] ?> </td>
                <td class="celda_verde" contenteditable="true" id="cell_prima_ubicar_2" style="background-color: #5cb85c; color: white;">$ <?= $prima_ubicar[2] ?> </td>
                <td class="celda_verde" contenteditable="true" id="cell_prima_ubicar_3" style="background-color: #5cb85c; color: white;">$ <?= $prima_ubicar[3] ?> </td>
                <td class="celda_amarilla text-info" id="cell_prima_ubicar_4">$ <?= $prima_ubicar[4] ?></td>
                <td class="celda_verde text-info" id="cell_prima_ubicar_5">$ <?= $prima_ubicar[5] ?></td>
              </tr>
              <tr>
              <td class="text-left">Prima P/ Pago de Bono</td>
                <td class="celda_verde" contenteditable="true" id="cell_prima_bonos_0" style="background-color: #5cb85c; color: white;">$ <?= $prima_pago[0] ?> </td>
                <td class="celda_verde" contenteditable="true" id="cell_prima_bonos_1" style="background-color: #5cb85c; color: white;">$ <?= $prima_pago[1] ?> </td>
                <td class="celda_verde" contenteditable="true" id="cell_prima_bonos_2" style="background-color: #5cb85c; color: white;">$ <?= $prima_pago[2] ?> </td>
                <td class="celda_verde" contenteditable="true" id="cell_prima_bonos_3" style="background-color: #5cb85c; color: white;">$ <?= $prima_pago[3] ?> </td>
                <td class="celda_amarilla text-info" id="cell_prima_bonos_4">$ <?= $prima_pago[4] ?></td>
                <td class="celda_verde text-info" id="cell_prima_bonos_5">$ <?= $prima_pago[5] ?></td>
              </tr>
              <tr>
                <td class="text-left">Número de Negocios</td>
                <td class="celda_verde" contenteditable="true" id="cell_numero_negocios_0" style="background-color: #5cb85c; color: white;"><?= $numero_negocios[0] ?></td>
                <td class="celda_verde" contenteditable="true" id="cell_numero_negocios_1" style="background-color: #5cb85c; color: white;"><?= $numero_negocios[1] ?></td>
                <td class="celda_verde" contenteditable="true" id="cell_numero_negocios_2" style="background-color: #5cb85c; color: white;"><?= $numero_negocios[2] ?></td>
                <td class="celda_verde" contenteditable="true" id="cell_numero_negocios_3" style="background-color: #5cb85c; color: white;"><?= $numero_negocios[3] ?></td>
                <td class="celda_amarilla text-info" id="cell_numero_negocios_4"><?= $numero_negocios[4] ?></td>
                <td class="celda_verde text-info" id="cell_numero_negocios_5"><?= $numero_negocios[5] ?></td>
              </tr>
              <tr>
                <td colspan="8"></td>
              </tr>
              <tr>
                <td class="text-left">Comisiones Directas</td>
                <td class="text-info" id="cell_comisiones_directas_1">$ <?= $comisiones_directas[0] ?></td>
                <td class="text-info" id="cell_comisiones_directas_2">$ <?= $comisiones_directas[1] ?></td>
                <td class="text-info" id="cell_comisiones_directas_3">$ <?= $comisiones_directas[2] ?> </td>
                <td class="text-info" id="cell_comisiones_directas_4">$ <?= $comisiones_directas[3] ?></td>
                <td class="celda_amarilla text-info" id="cell_comisiones_directas_5">$ <?= $comisiones_directas[4] ?></td>
                <td class="celda_verde text-info" id="cell_comisiones_directas_6">$ <?= $comisiones_directas[5] ?></td>
              </tr>
              <tr>
              <td class="text-left">% Bono Primer Año</td>
                <td class="text-info" id="cell_perce_bono_primer_anio_1"><?= $perce_bono_primer_anio[0] ?>%</td>
                <td class="text-info" id="cell_perce_bono_primer_anio_2"><?= $perce_bono_primer_anio[1] ?>%</td>
                <td class="text-info" id="cell_perce_bono_primer_anio_3"><?= $perce_bono_primer_anio[2] ?>%</td>
                <td class="text-info" id="cell_perce_bono_primer_anio_4"><?= $perce_bono_primer_anio[3] ?>%</td>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td class="text-left">Bono Primer Año</td>
                <td class="text-info" id="cell_bono_primer_anio_1">$ <?= $bono_primer_anio[0] ?> </td>
                <td class="text-info" id="cell_bono_primer_anio_2">$ <?= $bono_primer_anio[1] ?> </td>
                <td class="text-info" id="cell_bono_primer_anio_3">$ <?= $bono_primer_anio[2] ?> </td>
                <td class="text-info" id="cell_bono_primer_anio_4">$ <?= $bono_primer_anio[3] ?> </td>
                <td class="celda_amarilla text-info" id="cell_bono_primer_anio_5">$ <?= $bono_primer_anio[4] ?></td>
                <td class="celda_verde text-info" id="cell_bono_primer_anio_6">$ <?= $bono_primer_anio[5] ?></td>
              </tr>
              <tr>
                <td class="text-left">Faltante Bono Primer Año</td>
                <td class="text-info" id="cell_faltante_bono_1">$ <?= $faltante_bono_primer_anio[0] ?> </td>
                <td class="text-info" id="cell_faltante_bono_2">$ <?= $faltante_bono_primer_anio[1] ?> </td>
                <td class="text-info" id="cell_faltante_bono_3">$ <?= $faltante_bono_primer_anio[2] ?> </td>
                <td class="text-info" id="cell_faltante_bono_4">$ <?= $faltante_bono_primer_anio[3] ?> </td>
                <td colspan="2"> </td>
              </tr>
              <tr>
                <td colspan="7"></td>
              </tr>
              <tr>
                <td class="text-left">Conservación</td>
                <td class="celda_verde" contenteditable="true" id="cell_conservacion_0" style="background-color: #5cb85c; color: white;"><?= $conservacion[0] ?>%</td>
                <td class="celda_verde" contenteditable="true" id="cell_conservacion_1" style="background-color: #5cb85c; color: white;"><?= $conservacion[1] ?>%</td>
                <td class="celda_verde" contenteditable="true" id="cell_conservacion_2" style="background-color: #5cb85c; color: white;"><?= $conservacion[2] ?>%</td>
                <td class="celda_verde" contenteditable="true" id="cell_conservacion_3" style="background-color: #5cb85c; color: white;"><?= $conservacion[3] ?>%</td>
                <td colspan="2"> </td>
              </tr>
              <tr>
              <td class="text-left">Comision Cartera</td>
                <td class="text-info" id="cell_comisiones_cartera_1">$ <?= $comision_cartera[0] ?></td>
                <td class="text-info" id="cell_comisiones_cartera_2">$ <?= $comision_cartera[1] ?></td>
                <td class="text-info" id="cell_comisiones_cartera_3">$ <?= $comision_cartera[2] ?></td>
                <td class="text-info" id="cell_comisiones_cartera_4">$ <?= $comision_cartera[3] ?></td>
                <td class="celda_amarilla text-info" id="cell_comisiones_cartera_5">$ <?= $comision_cartera[4] ?></td>
                <td class="celda_verde text-info" id="cell_comisiones_cartera_6">$ <?= $comision_cartera[5] ?></td>
              </tr>
              <tr>
                <td class="text-left">% Bono Cartera</td>
                <td class="text-info" id="cell_perce_bono_cartera_1"><?= $bono_cartera_perce[0] ?>%</td>
                <td class="text-info" id="cell_perce_bono_cartera_2"><?= $bono_cartera_perce[1] ?>%</td>
                <td class="text-info" id="cell_perce_bono_cartera_3"><?= $bono_cartera_perce[2] ?>%</td>
                <td class="text-info" id="cell_perce_bono_cartera_4"><?= $bono_cartera_perce[3] ?>%</td>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td class="text-left">Bono Cartera</td>
                <td class="text-info" id="cell_bono_cartera_1">$ <?= $bono_cartera[0] ?></td>
                <td class="text-info" id="cell_bono_cartera_2">$ <?= $bono_cartera[1] ?></td>
                <td class="text-info" id="cell_bono_cartera_3">$ <?= $bono_cartera[2] ?></td>
                <td class="text-info" id="cell_bono_cartera_4">$ <?= $bono_cartera[3] ?></td>
                <td class="celda_amarilla text-info" id="cell_bono_cartera_5">$ <?= $bono_cartera[4] ?></td>
                <td class="celda_verde text-info" id="cell_bono_cartera_6">$ <?= $bono_cartera[5] ?></td>
              </tr>
              <tr>
                <td class="text-left">Faltante Bono Cartera</td>
                <td class="text-info" id="cell_faltante_bono_cartera_1">$ <?= $faltante_bono_cartera[0] ?></td>
                <td class="text-info" id="cell_faltante_bono_cartera_2">$ <?= $faltante_bono_cartera[1] ?></td>
                <td class="text-info" id="cell_faltante_bono_cartera_3">$ <?= $faltante_bono_cartera[2] ?></td>
                <td class="text-info" id="cell_faltante_bono_cartera_4">$ <?= $faltante_bono_cartera[3] ?></td>
                <td colspan="2"></td>
              </tr>
            
              <tr>
                <td colspan="8"></td>
              </tr>
              <tr class="error">
                <td class="text-left">Bono Cartera No Ganado</td>
                <td class="text-info" id="cell_bono_cartera_no_ganado_1">$ <?= $bono_cartera_no_ganado[0] ?></td>
                <td class="text-info" id="cell_bono_cartera_no_ganado_2">$ <?= $bono_cartera_no_ganado[1] ?></td>
                <td class="text-info" id="cell_bono_cartera_no_ganado_3">$ <?= $bono_cartera_no_ganado[2] ?></td>
                <td class="text-info" id="cell_bono_cartera_no_ganado_4">$ <?= $bono_cartera_no_ganado[3] ?></td>
                <td class="celda_amarilla text-info" id="cell_bono_cartera_no_ganado_5">$ <?= $bono_cartera_no_ganado[4] ?></td>
                <td class="celda_verde text-info" id="cell_bono_cartera_no_ganado_6">$ <?= $bono_cartera_no_ganado[5] ?></td>
              </tr>
              <tr>
                <td colspan="8"></td>
              </tr>
              <tr class="info">
                <td class="text-left">Suma de Ingresos Totales</td>
                <td class="text-info" id="cell_suma_ingresos_totales_1">$ <?= $ingresos_totales[0] ?></td>
                <td class="text-info" id="cell_suma_ingresos_totales_2">$ <?= $ingresos_totales[1] ?></td>
                <td class="text-info" id="cell_suma_ingresos_totales_3">$ <?= $ingresos_totales[2] ?></td>
                <td class="text-info" id="cell_suma_ingresos_totales_4">$ <?= $ingresos_totales[3] ?></td>
                <td class="celda_amarilla text-info" id="cell_suma_ingresos_totales_5">$ <?= $ingresos_totales[4] ?></td>
                <td class="celda_verde text-info" id="cell_suma_ingresos_totales_6">$ <?= $ingresos_totales[5] ?></td>
              </tr>
              <tr>
                <td colspan="8"></td>
              </tr>
              <tr>
                <td class="text-left">Puntos</td>
                <td colspan="4">Períodos</td>
                <td class="text-left">Total</td>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td class="text-left">Puntos Vida</td>
                <td class="text-info" id="cell_puntos_vida_1"> <?= $puntos_vida[0] ?> </td>
                <td class="text-info" id="cell_puntos_vida_2"> <?= $puntos_vida[1] ?> </td>
                <td class="text-info" id="cell_puntos_vida_3"> <?= $puntos_vida[2] ?> </td>
                <td class="text-info celda_amarilla" id="cell_puntos_vida_4"> <?= $puntos_vida[3] ?> </td>
                <td class="text-info" id="cell_puntos_vida_5"> <?= $puntos_vida[4] ?> </td>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td class="text-left">Puntos GMM</td>
                <td class="text-info" id="cell_puntos_vida_1"> <?= $puntos_gmm[0] ?></td>
                <td class="text-info" id="cell_puntos_vida_2"> <?= $puntos_gmm[1] ?></td>
                <td class="text-info celda_amarilla" id="cell_puntos_vida_3" colspan="2"> <?= $puntos_gmm[2] ?></td>
                <td class="text-info" id="cell_puntos_vida_4"> <?= $puntos_gmm[4] ?> </td>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td class="text-left">Puntos Autos</td>
                <td class="text-info" id="cell_puntos_vida_1"> <?= $puntos_auto[0] ?></td>
                <td class="text-info" id="cell_puntos_vida_2"> <?= $puntos_auto[1] ?></td>
                <td id="cell_puntos_vida_3" class="celda_amarilla text-info" colspan="2"> <?= $puntos_auto[2] ?></td>
                <td class="text-info" id="cell_puntos_vida_4" > <?= $puntos_auto[4] ?> </td>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td id="" colspan="7"></td>
              </tr>
              <tr>
                <td class="text-left">Bono Integral</td>
                <td class="text-info text-left" id="cell_bono_integral" colspan="6">$ <?= $data['values_first_agent']['bono_integral'] ?> </td>
              </tr>
              <tr>
                <td colspan="7"></td>
              </tr>
              <tr>
                <td class="text-left">Total de ingresos</td>
                <td class="text-info text-left" id="cell_ingresos_totales" colspan="6"> $ <?= $data['values_first_agent']['ingresos_totales'] ?> </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div><!--/row-->
