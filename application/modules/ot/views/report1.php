<?php
$post_data = isset($_POST['query']) ? ',prev_post:'. json_encode($_POST['query']) : '';
$base_url = base_url();
$segments = $this->uri->rsegment_array();
$is_director_module = ($segments[1] == 'director');
$popup_segment = $is_director_module ? 'director' : 'ot';
?>
<script type="text/javascript">

    function report_popup(agent_id, wrk_ord_ids,poliza,gmm)
    {
		$.fancybox.showLoading();

		$.post("<?php echo $base_url . $popup_segment ?>/reporte_popup",
			{agent_id: agent_id, wrk_ord_ids:wrk_ord_ids,is_poliza:poliza,gmm:gmm<?php echo $post_data ?>},function(data)
        { 
            if(data)
            {
                $.fancybox({
                  content:data
            });
               
                return false;
            }
        });
    }

</script>

<?php
    $total_negocio = 0;
    $total_negocio_pai = 0;
    $total_primas_pagadas = 0;
    $total_negocios_tramite = 0;
    $total_primas_tramite = 0;
    $total_negocio_pendiente = 0;
    $total_primas_pendientes = 0;
    $total_cartera = 0;
    $total_negocios_proyectados = 0;
    $total_primas_proyectados = 0;
    $total_cobranza = 0;
    
    $prime_tag_array = array('amount' => 'Primas<br>Pagadas','allocated_prime' => 'Primas <br> para Ubicar', 'bonus_prime' => 'Primas para <br> pago de Bono');
    $prime_tag = $prime_tag_array[$_POST['query']['prime_type']];

    if (empty($_POST['query']['prime_type']))
        $prime_tag = $prime_tag_array['amount'];
//    $tata = json_encode($tata);
	if (is_array($tata))
		$tata = json_encode($tata);
?>

<table class="sortable altrowstable tablesorter" id="sorter-report1">
    <thead class="head">
        <tr>
            <th id="table_agents" class="header_manager" style="text-align:center; ">Agentes</th>
            <th id="total_negocio_pai" class="header_manager" style="width:70px; text-align:center; ">Negocios<br>PAI</th>
            <th id="total_primas_pagadas" class="header_manager" style="width:100px; text-align:center; "><?php echo $prime_tag; ?></th>
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
        <?php  
        error_log(print_r('holi',true));
        if( !empty($data)):?>
            <?php
            foreach( $data as $key=>$value ): ?>

            <?php
		$negocio = 0;
		$prima = 0;	
		$negocios_pendientes_pago = 0;
		$primas_pendientes_pago = 0;

		if( $value['disabled'] == 1 ) 
			$value['disabled'] = 'Vigente';
		else
			$value['disabled'] = 'Cancelado';		
		//$total_negocio += $value['negocio'];		
		$total_negocio_pai += $value['negociopai'];
		$total_primas_pagadas +=$value['prima'];
		$total_negocios_tramite +=$value['tramite']['count'];
		if (isset($value['tramite']['adjusted_prima']))
			$total_primas_tramite +=  $value['tramite']['adjusted_prima'];		
		
		if(isset($value['aceptadas']['count']))			
			$negocios_pendientes_pago +=  $value['aceptadas']['count']; 		
		else 			
			$negocios_pendientes_pago += $value['aceptadas'];
			
		$total_negocio_pendiente += $negocios_pendientes_pago;
		
		if( isset( $value['aceptadas']['adjusted_prima'] ) ) 			
			$primas_pendientes_pago +=  $value['aceptadas']['adjusted_prima'];		
		else			
			$primas_pendientes_pago += $value['aceptadas'];
		$total_primas_pendientes += $primas_pendientes_pago;

		$negocio += (float)($value['negociopai']+$value['tramite']['count']+$negocios_pendientes_pago);
		$prima += (float)($value['prima']+$value['tramite']['adjusted_prima']+$primas_pendientes_pago);
		$prima += (float)($value['cobranza']['total_due_past'] + $value['cobranza']['total_due_future'] 
			- $value['cobranza']['total_paid']);
		$total_cartera += $value['cartera'];
		$total_negocios_proyectados += $negocio;
		$total_primas_proyectados += $prima;
		$total_cobranza += ($value['cobranza']['total_due_past'] + $value['cobranza']['total_due_future'] 
            - $value['cobranza']['total_paid']);
        
        foreach ($value['cobranza'] as $keys) {
            foreach ($keys as $key => $val) {
                $cobranza = $value['cobranza']['total_due_past'] + $value['cobranza']['total_due_future'] - $val['paid'];
                if ($cobranza < 0){
                    $cobranza = 0;
                }
                
            }
        }
            ?>															
            <tr id="tr_<?php echo $value['id'] ?>">
                <td class="">                
                    <div class="text_azulado" id="<?php echo $value['id'] ?>">
                        <?php echo $value['name'] ?>
                    </div> 
                </td>            
                <td class="celda_gris" style="text-align:right;">
                    <a class="numeros fancybox_gris" href="javascript:void" title="Haga click aqui para ver los detalles" onclick="payment_popup({for_agent_id: <?php echo (int)$value['agent_id'] ?>, type: 'negociopai'})"><?php echo $value['negociopai']; ?></a>
				</td>
                <td class="celda_gris prima" style="text-align:right">
                    <a class="numeros fancybox_gris" href="javascript:void" title="Haga click aqui para ver los detalles" onclick="payment_popup({for_agent_id: <?php echo (int)$value['agent_id'] ?>, type: 'prima'})">$<?php echo number_format($value['prima'],2) ; ?></a>
				</td>
                <td class="celda_roja" style="text-align:center;">
                    <a class="numeros fancybox" style="text-align:right" <?php if($value['tramite']['work_order_ids']){?> href="javascript:void" title="Haga click aqui para ver los detalles" onclick='report_popup(<?php echo $value['id'] ?>, <?php echo json_encode($value['tramite']['work_order_ids']);?>,"no","<?php echo  $tata; ?>")' <?php }?>><?php if(isset($value['tramite']['count'])) echo $value['tramite']['count']; else echo 0; ?></a>
                </td>
                <td class="celda_roja prima" style="text-align:right;" >
                    <a class="numeros fancybox" <?php if($value['tramite']['work_order_ids']){?> href="javascript:void" title="Haga click aqui para ver los detalles" onclick='report_popup(<?php echo $value['id'] ?>, <?php echo json_encode($value['tramite']['work_order_ids']);?>,"no","<?php echo  $tata; ?>")' <?php }?>>$<?php if( isset( $value['tramite']['adjusted_prima'] ) ) echo number_format($value['tramite']['adjusted_prima'],2); else echo number_format ('0',2); ?></a>
                </td>
                <td class="celda_amarilla" style="text-align:center;">
                    <a class="numeros fancybox" style="text-align:center;" <?php if($value['aceptadas']['work_order_ids']){?> href="javascript:void" title="Haga click aqui para ver los detalles"  onclick='report_popup(<?php echo $value['id'] ?>, <?php echo json_encode($value['aceptadas']['work_order_ids']);?>,"yes","<?php echo  $tata; ?>")' <?php }?>><?php if( isset( $value['aceptadas']['count'] ) ) echo  $value['aceptadas']['count']; else  echo $value['aceptadas'] ?></a>
                </td>
                <td class="celda_amarilla prima" style="text-align:right;">
                    <a class="numeros fancybox" <?php if($value['aceptadas']['work_order_ids']){?> href="javascript:void" title="Haga click aqui para ver los detalles"  onclick='report_popup(<?php echo $value['id'] ?>, <?php echo json_encode($value['aceptadas']['work_order_ids']);?>,"yes","<?php echo $tata; ?>")' <?php }?>>$<?php if( isset( $value['aceptadas']['adjusted_prima'] ) ) echo number_format($value['aceptadas']['adjusted_prima'],2); else  echo number_format($value['aceptadas'],2); ?></a>
                </td>
                <td class="celda_cobranza prima" style="text-align:right;">
                    <a class="numeros fancybox_gris" href="javascript:void" title="Haga click aqui para ver los detalles" onclick="payment_popup({for_agent_id: <?php echo (int)$value['agent_id'] ?>, type: 'cobranza'})">
                    $<?php echo number_format($cobranza, 2) ; ?></a>
                </td>
                <td class="celda_gris"><div class="numeros" style="text-align:center;"><?php echo $negocio ?></div></td>
                <td class="celda_verde prima"><div class="numeros" style="text-align:right">$<?php echo number_format($prima,2); ?></div></td>
                <td class="celda_cartera prima" style="text-align:right;">
                    <a class="numeros fancybox_gris" href="javascript:void" title="Haga click aqui para ver los detalles" onclick="payment_popup({for_agent_id: <?php echo (int)$value['agent_id'] ?>, type: 'cartera'})">$<?php echo number_format($value['cartera'], 2) ; ?></a>
                </td>
            </tr>
        <? $cobranza = 0;?>
        
    <div id="info_<?php echo $value['id'] ?>" style="display: none;">
        
        <a href="javascript:void" class="btn btn-link btn-hide">
            <i class="icon-arrow-up" id="<?php echo $value['id'] ?>"></i>
        </a>
        <?php 
            if( !empty( $value['uids'][0]['uid'] ) )
                echo $value['uids'][0]['uid']. ' - '; 
            else 
                echo 'Sin clave asignada - '; 
        ?>

        <?php 
            echo $value['disabled'] .' - '. $value['generacion']. ' - '; 
        ?>
        <?php 
            if( $value['connection_date'] != '0000-00-00' and $value['connection_date'] != ''): 
        ?>
            Conectado <?php echo getFormatDate($value['connection_date']) ?>

        <?php else: ?>
                En proceso de conexión
        <?php endif; ?> 
         
        <?php /*
        - Definir meta -si la meta no está definida- o Editar meta -si está definida-  
            - Simular resultado
            - Ver desempeño en campo (es la liga a las actividades de ese agente)
            - Ver perfil (esta página aún no está creada, es el siguiente punto)
        */

		if (!$is_director_module)
		{
			$simulator_url = $base_url .'simulator/index/'.$value['id'];
			if( isset( $_POST['query']['ramo'] ) )
				$simulator_url .= '/'.$_POST['query']['ramo'];
			else
				$simulator_url .= '/1';
			$simulator_url .= '.html';		
			$activities_url = $base_url . 'activities/index/'.$value['id'].'.html';
		}
		$perfil_url = $base_url .'agent/index/'.$value['id'];
		if( isset( $_POST['query']['ramo'] ) )
			$perfil_url .= '/'.$_POST['query']['ramo'];
		else
			$perfil_url .= '/1';
		$perfil_url .= '.html';
		if (!$is_director_module): 
		?>
           |<a href="<?php echo $simulator_url ?>" class="btn btn-link">Simular resultado y definir meta</a>|
           <a href="<?php echo $activities_url ?>" class="btn btn-link">Actividades en campo</a>
		<?php endif; ?>		   
		   |
           <a href="<?php echo $perfil_url ?>" class="btn btn-link" target="_blank">Perfil</a><br />
    </div>
        
            <div id="info_<?php echo $value['id'] ?>" style="display: none;">
                <a href="javascript:void" class="btn btn-link btn-hide">
                    <i class="icon-arrow-up" id="<?php echo $value['id'] ?>"></i>
                </a>
                <?php 
                    if( !empty( $value['uids'][0]['uid'] ) )
                        echo $value['uids'][0]['uid']. ' - '; 
                    else 
                        echo 'Sin clave asignada - '; 
                ?>
                <?php 
                    echo $value['disabled'] .' - '. $value['generacion']. ' - '; 
                ?>
                <?php 
                    if( $value['connection_date'] != '0000-00-00' and $value['connection_date'] != ''): 
                ?>
                    Conectado <?php echo getFormatDate($value['connection_date']) ?>
                <?php else: ?>
                        En proceso de conexión
                <?php endif; ?>          
                <?php /*
                - Definir meta -si la meta no está definida- o Editar meta -si está definida-  
                    - Simular resultado
                    - Ver desempeño en campo (es la liga a las actividades de ese agente)
                    - Ver perfil (esta página aún no está creada, es el siguiente punto)
                */?>
                    |<a href="#" class="btn btn-link">Definir meta</a>|<a href="#" class="btn btn-link">Simular resultado</a>|
                <a href="#" class="btn btn-link">Desempeño en campo</a>|<a href="#" class="btn btn-link">Perfil</a><br />            
            </div>        
	<?php endforeach;?>         
    <?php endif; ?>            							 
    </tbody>
</table>


<div id="contentFoot" style="width:77% !important;">
    <table class="sortable altrowstable tablesorter" id="Tfoot" style="min-width:100% !important;" >
        <tr>
            <td id="id-total"><div class="text_total">Totales</div></td>
            <td class="pagadas-recap" style="width:70px; text-align:center;"><div class="numeros">
            <a class="numeros fancybox_blanco" href="javascript:void" title="Haga click aqui para ver los detalles" onclick="payment_popup({type: 'negociopai'})">
              <?php echo $total_negocio_pai?></a>
            </div>Negocios PAI
            </td>
            <td class="pagadas-recap" style="width:100px;text-align:right"><div class="numeros">
            <a class="numeros fancybox_blanco" href="javascript:void" title="Haga click aqui para ver los detalles" onclick="payment_popup({type: 'prima'})">
              $<?php echo number_format($total_primas_pagadas,2) ?></a>
            </div><?php echo $prime_tag; ?>
            </td>
            <td style="width:70px; text-align:center;" class="celda_gris_roja tramite-recap"><div class="numeros"><?php echo $total_negocios_tramite ?></div> Negocios en <br>  Tramite</td>
            <td style="width:100px;text-align:right" class="celda_gris_roja tramite-recap"><div class="numeros">$<?php echo number_format($total_primas_tramite) ?></div> En Tramite</td>
            <td style="width:70px; text-align:center;" class="celda_gris_amarilla pendientes-recap"><div class="numeros"><?php echo $total_negocio_pendiente ?></div> Negocios Pendientes</td>
            <td style="width:100px;text-align:right" class="celda_gris_amarilla pendientes-recap"><div class="numeros">$<?php echo number_format($total_primas_pendientes)?></div> Pendientes</td>
            <td class="cobranza-recap" style="width:70px; text-align:center;"><div class="numeros">$<?php echo number_format($total_cobranza, 2); ?></div>Cobranza</td>
            <td style="width:70px; text-align:center;" class="celda_gris_roja tramite-recap"><div class="numeros" id="negocio-recap"><?php echo $total_negocios_proyectados ?></div> Negocios Proyectados</td>
            <td style="width:100px;text-align:right" class="celda_gris_verde"><div class="numeros" id="prima-recap">$<?php echo number_format($total_primas_proyectados,1) ?></div> Proyectadas</td>
            <td class="cartera-recap" style="width:70px; text-align:center;"><div class="numeros">$<?php echo number_format($total_cartera, 2); ?></div>Cartera</td>
        </tr>
    </table>
</div>  