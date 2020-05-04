<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
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
$base_url = base_url();
$selected_period = get_filter_period();
?>

<?php if ($filter['ramo'] != 3): ?>
            <div class="row">
<?php echo $report_columns; ?>
            </div>
<?php endif; ?>

            <div class="row">
                <form id="form" method="post" action="<?php echo current_url() ?>">
                    <button value="1" class="btn-ramo link-ramo <?php if (($filter['ramo'] == 1) || ($filter['query']['ramo'] == 1)) echo 'btn-ramo-current' ?>">Vida</button>
                    <button value="2" class="btn-ramo link-ramo <?php if (($filter['ramo'] == 2) || ($filter['query']['ramo'] == 3))  echo 'btn-ramo-current' ?>">GMM</button>
                    <button value="3" class="btn-ramo link-ramo <?php if (($filter['ramo'] == 3) || ($filter['query']['ramo'] == 3))  echo 'btn-ramo-current' ?>">Autos</button>
                    <input type="hidden" name="query[ramo]" id="hidden-ramo" value="<?php echo $filter['ramo'] ?>" />
                    <p class="line">&nbsp;</p>

<?php echo $period_fields ?>
<select id="periodo_form" name="periodo" style="width: 175px" title="Período">
	  <option value="<?php echo $selected_period ?>"></option>
</select>
<input type="hidden" value="<?php echo $selected_period ?>" id="periodo" name="query[periodo]" />
<?php
//if ($this->access_simulator):
if (TRUE):
$segments = $this->uri->rsegment_array();
$segments[1] = 'simulator';
$segments[2] = 'index';
$meta_url = $base_url . implode('/', $segments) . '.html';
$segments[2] = 'simulate';
$simulator_url = $base_url . implode('/', $segments) . '.html';
?>
                    <a style="margin-left: 3em" href="<?php echo $meta_url; ?>" class="btn btn-primary" target="_blank">
                    VER META</a>
                    <a style="margin-left: 3em" href="<?php echo $simulator_url; ?>" class="btn btn-primary" target="_blank">
                    SIMULAR INGRESO</a>
<?php endif; ?>
                </form>
 <!--
<?php if (isset($export_xls) && $export_xls): ?>
                <a href="javascript:void(0);" class="download"><img src="<?php echo $base_url ?>ot/assets/images/down.png"></a>
<?php endif; ?>
 -->

<?php
$post_data = isset($_POST['query']) ? ',prev_post:'. json_encode($_POST['query']) : '';
?>
<script type="text/javascript">
	function report_popup(agent_id, wrk_ord_ids,poliza,gmm)
	{
		$.fancybox.showLoading();
		$.post("<?php echo $base_url ?>agent/reporte_popup.html",
			{agent_id: agent_id, wrk_ord_ids:wrk_ord_ids,is_poliza:poliza,gmm:gmm<?php echo $post_data ?>},function(data)
        {
			if (data) {
				$.fancybox({
					content:data
				});
				return false;
			}
		});
	}
</script>

<?php
switch ($filter['ramo'])
{
	case 1:
	case 2:
?>
<table class="sortable altrowstable tablesorter" id="sorter-report1">
    <thead class="head">
        <tr>
            <th id="table_agents" class="header_manager" style="text-align:center; display: none">Agentes</th>
            <th id="total_negocio" class="header_manager" style="width:70px; text-align:center; ">Negocios Pagados</th>
<?php if ($filter['ramo'] == 1): ?>
            <th id="total_negocio_pai" class="header_manager" style="width:70px; text-align:center; ">Negocios<br>PAI</th>
<?php endif; ?>
            <th id="total_primas_pagadas" class="header_manager" style="width:100px; text-align:center; ">Primas<br>Pagadas</th>
            <th id="total_negocios_tramite" class="header_manager" style="width:70px; text-align:center; ">Negocios <br> en  Tramite</th>
            <th id="total_primas_tramite" class="header_manager" style="width:100px; text-align:center; ">Primas <br> en Tramite</th>
            <th id="total_negocio_pendiente" class="header_manager" style="width:70px; text-align:center; ">Negocios Pendientes</th>
            <th id="total_primas_pendientes" class="header_manager" style="width:100px; text-align:center; ">Primas <br> Pendientes</th>
            <th id="total_cobranza" class="header_manager" style="width:70px; text-align:center; ">Cobranza instalada</th>
            <th id="total_negocios_proyectados" class="header_manager" style="width:70px; text-align:center; ">Negocios Proyectados</th>
            <th id="total_primas_proyectados" class="header_manager" style="width:100px; text-align:center; ">Primas <br> Proyectadas</th>
            <th id="total_cartera" class="header_manager" style="width:70px; text-align:center; ">Cartera</th>
        </tr>
    </thead>
    
    <tbody class="tbody">        
    <?php if( !empty($value)):
		if(isset($value['aceptadas']['count']))			
			$negocios_pendientes_pago =  $value['aceptadas']['count']; 		
		else 			
			$negocios_pendientes_pago = $value['aceptadas'];
		if( isset( $value['aceptadas']['adjusted_prima'] ) ) 			
			$primas_pendientes_pago = $value['aceptadas']['adjusted_prima'];		
		else			
			$primas_pendientes_pago = $value['aceptadas'];

		$cobranza_v = 0;
		foreach ($value['cobranza'] as $k_cobranza => $v_cobranza)
		{
			$cobranza_v = $v_cobranza['total_due_past'] + 
				$v_cobranza['total_due_future'] - $v_cobranza['total_paid'];
			break;
		}

		if (is_array($value['negociopai']))
		{
			foreach ($value['negociopai'] as $negocio_pai)
				break;
		}
		else
			$negocio_pai = $value['negociopai'];
		if ($filter['ramo'] == 1)
			$negocio = (int)($negocio_pai + $value['tramite']['count'] + $negocios_pendientes_pago);
		else
			$negocio = (int)($value['negocio'] + $value['tramite']['count'] + $negocios_pendientes_pago);

		$prima = (float)($value['prima'] + $value['tramite']['adjusted_prima'] + $primas_pendientes_pago + $cobranza_v);

	?>
        <tr id="tr_<?php echo $value['id'] ?>">
            <td class="" style="display: none">
                <div style="color: #1186C1; font-size: 14px;" id="<?php echo $value['id'] ?>">
                    <?php echo $value['name'] ?>
                </div> 
            </td>
            <td class="celda_gris<?php if ($filter['ramo'] == 1) echo ' not-in-proyectados'; ?>" style="text-align:right;">
                <a class="numeros fancybox_gris" href="javascript:void" title="Haga click aqui para ver los detalles" onclick="payment_popup({for_agent_id: <?php echo (int)$value['agent_id'] ?>, type: 'negocio'})"><?php echo $value['negocio'] ; ?></a>
            </td>
<?php if ($filter['ramo'] == 1): ?>
            <td class="celda_gris" style="text-align:right;">
                <a class="numeros fancybox_gris" href="javascript:void" title="Haga click aqui para ver los detalles" onclick="payment_popup({for_agent_id: <?php echo (int)$value['agent_id'] ?>, type: 'negociopai'})"><?php echo $negocio_pai; ?></a>
            </td>
<?php endif; ?>
            <td class="celda_gris prima" style="text-align:right">
                <a class="numeros fancybox_gris" href="javascript:void" title="Haga click aqui para ver los detalles" onclick="payment_popup({for_agent_id: <?php echo (int)$value['agent_id'] ?>, type: 'prima'})">$<?php echo number_format($value['prima'],2) ; ?></a>
				</td>
            <td class="celda_roja" style="text-align:center;">
<?php if (!$value['tramite']): ?>
                <span class="numeros fancybox">0</span>
<?php else: ?>
                <a class="numeros fancybox" style="text-align:right" <?php if($value['tramite']['work_order_ids']){?> href="javascript:void" title="Haga click aqui para ver los detalles" onclick='report_popup(<?php echo $value['id'] ?>, <?php echo json_encode($value['tramite']['work_order_ids']);?>,"no","<?php echo $filter['ramo']; ?>")' <?php }?>><?php if(isset($value['tramite']['count'])) echo $value['tramite']['count']; else echo 0; ?></a>
<?php endif; ?>
            </td>
            <td class="celda_roja prima" style="text-align:right;" >
<?php if (!$value['tramite']): ?>
                <span class="numeros fancybox">$0.00</span>
<?php else: ?>
                <a class="numeros fancybox" <?php if($value['tramite']['work_order_ids']){?> href="javascript:void" title="Haga click aqui para ver los detalles" onclick='report_popup(<?php echo $value['id'] ?>, <?php echo json_encode($value['tramite']['work_order_ids']);?>,"no","<?php echo $filter['ramo']; ?>")' <?php }?>>$<?php if( isset( $value['tramite']['adjusted_prima'] ) ) echo number_format($value['tramite']['adjusted_prima'],2); else echo number_format ('0',2); ?></a>
<?php endif; ?>
            </td>
            <td class="celda_amarilla" style="text-align:center;">
<?php if (!$value['aceptadas']): ?>
                <span class="numeros fancybox">0</span>
<?php else: ?>
                <a class="numeros fancybox" style="text-align:center;" <?php if($value['aceptadas']['work_order_ids']){?> href="javascript:void" title="Haga click aqui para ver los detalles"  onclick='report_popup(<?php echo $value['id'] ?>, <?php echo json_encode($value['aceptadas']['work_order_ids']);?>,"yes","<?php echo $filter['ramo']; ?>")' <?php }?>><?php if( isset( $value['aceptadas']['count'] ) ) echo  $value['aceptadas']['count']; else  echo $value['aceptadas'] ?></a>
<?php endif; ?>
            </td>
            <td class="celda_amarilla prima" style="text-align:right;">
<?php if (!$value['aceptadas']): ?>
                <span class="numeros fancybox">$0.00</span>
<?php else: ?>
                <a class="numeros fancybox" <?php if($value['aceptadas']['work_order_ids']){?> href="javascript:void" title="Haga click aqui para ver los detalles" onclick='report_popup(<?php echo $value['id'] ?>, <?php echo json_encode($value['aceptadas']['work_order_ids']);?>,"yes","<?php echo $filter['ramo']; ?>")' <?php }?>>$<?php if( isset( $value['aceptadas']['adjusted_prima'] ) ) echo number_format($value['aceptadas']['adjusted_prima'],2); else  echo number_format($value['aceptadas'],2); ?></a>
<?php endif; ?>
            </td>
            <td class="celda_cobranza prima" style="text-align:right;">
               <a class="numeros fancybox_gris" href="javascript:void" title="Haga click aqui para ver los detalles" onclick="payment_popup({for_agent_id: <?php echo (int)$value['agent_id'] ?>, type: 'cobranza'})">$<?php echo number_format($cobranza_v, 2) ; ?></a>
            </td>
            <td class="celda_verde"><div class="numeros" style="text-align:center;"><?php echo $negocio ?></div></td>
            <td class="celda_verde prima"><div class="numeros" style="text-align:right">$<?php echo number_format($prima,2); ?></div></td>
			<td class="celda_cartera prima" style="text-align:right;">
				<a class="numeros fancybox_gris" href="javascript:void" title="Haga click aqui para ver los detalles" onclick="payment_popup({for_agent_id: <?php echo (int)$value['agent_id'] ?>, type: 'cartera'})">$<?php echo number_format($value['cartera'], 2) ; ?></a>
			</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
<?php
	break;
	case 3:
?>
<table class="sortable altrowstable tablesorter" id="sorter-report3" style="width:100%;">
    <thead class="head">
        <tr>
            <th id="table_agents" class="header_manager" style="width:100px; display: none">Agentes</th>
            <th id="total_negocio" class="header_manager" style="width:70px;">Iniciales </th>
            <th id="total_negocio_pai" class="header_manager" style="width:70px;">Renovación</th>
            <th id="total_primas_pagadas" class="header_manager" style="width:100px;">Totales</th>
        </tr>
    </thead>

    <tbody class="tbody">
      <?php  if( !empty( $value ) ): ?>
        <tr>
            <td style="width:100px; display: none">
                <div style="color: #1186C1; font-size: 14px;" id="<?php echo $value['id'] ?>">
                    <?php echo $value['name'] ?>
                </div> 
            </td>
            <td class="celda_gris"><div class="numeros" style="text-align:right">$ <?php echo $value['iniciales'] ?></div></td>
            <td class="celda_gris"><div class="numeros" style="text-align:right">$ <?php echo $value['renovacion']; ?></div></td>
            <td class="celda_gris"><div class="numeros" style="text-align:right">$ <?php echo (float)$value['iniciales']+(int)$value['renovacion'] ?></div></td>	
        </tr>
    <?php endif; ?>  
    </tbody>
</table>
<?php
	break;
	default:
	break;
}
?>
            </div>
