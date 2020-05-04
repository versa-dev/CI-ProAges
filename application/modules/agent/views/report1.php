<script type="text/javascript">

    function report_popup(wrk_ord_ids,poliza,gmm)
    {
       $.fancybox.showLoading();
        
        $.post("ot/reporte_popup",{wrk_ord_ids:wrk_ord_ids,is_poliza:poliza,gmm:gmm},function(data)
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
    $total_negocio=0;
    $total_negocio_pai=0;
    $total_primas_pagadas=0;
    $total_negocios_tramite=0;
    $total_primas_tramite=0;
    $total_negocio_pendiente=0;
    $total_primas_pendientes=0;
    $total_negocios_proyectados=0;
    $total_primas_proyectados=0;
    $tata;
?>


<table  class="sortable altrowstable tablesorter" id="sorter"  style="width:100%">
    <thead class="head">
        <tr>
            <th id="total_negocio" class="header_manager" style="width:70px; text-align:center; ">Negocios<br>Pagados</th>
            <th id="total_negocio_pai" class="header_manager" style="width:70px; text-align:center; ">Negocios<br>PAI</th>
            <th id="total_primas_pagadas" class="header_manager" style="width:100px; text-align:center; ">Primas<br>Pagadas</th>
            <th id="total_negocios_tramite" class="header_manager" style="width:70px; text-align:center; ">Negocios <br> en  Tramite</th>
            <th id="total_primas_tramite" class="header_manager" style="width:100px; text-align:center; ">Primas <br> en Tramite</th>
            <th id="total_negocio_pendiente" class="header_manager" style="width:70px; text-align:center; ">Negocios Pendientes</th>
            <th id="total_primas_pendientes" class="header_manager" style="width:100px; text-align:center; ">Primas <br> Pendientes</th>
            <th id="total_negocios_proyectados" class="header_manager" style="width:70px; text-align:center; ">Negocios Proyectados</th>
            <th id="total_primas_proyectados" class="header_manager" style="width:100px; text-align:center; ">Primas <br> Proyectadas</th>
        </tr>
    </thead>
    
    <tbody class="tbody">        
        <?php  
        if( !empty($data)): ?>
            <?php  
            foreach( $data as $key=>$value ):  ?>
        
            <?php            
                $negocio = 0;
		$prima = 0;		
		$negocio += (int)$value['negocio'];		
		$negocio += (int)$value['tramite']['count'];		
		if( isset( $value['aceptadas']['count'] ) ) 
                    $negocio += (int)$value['aceptadas']['count'];	
		else 
                    $negocio += (int)$value['aceptadas'];	
		$prima += (float)$value['prima'];
		$prima += (float)$value['tramite']['prima'];		
		if(isset($value['aceptadas']['prima'])) 			
                    $prima += (float)$value['aceptadas']['prima']; 		
		else			
                    $prima += (float)$value['aceptadas'];		
		if( $value['disabled'] == 1 ) $value['disabled'] = 'Vigente'; else $value['disabled'] = 'Cancelado';		
		$total_negocio += $value['negocio'];		
		$total_negocio_pai += $value['negociopai'];		
		$total_primas_pagadas +=$value['prima'];
		$total_negocios_tramite +=$value['tramite']['count'];
		$total_primas_tramite +=  $value['tramite']['prima'];		
		
		if(isset($value['aceptadas']['count']))			
			$total_negocio_pendiente +=  $value['aceptadas']['count']; 		
		else 			
			$total_negocio_pendiente += $value['aceptadas'];
		
		if( isset( $value['aceptadas']['prima'] ) ) 			
			$total_primas_pendientes +=  $value['aceptadas']['prima'];		
		else			
			$total_primas_pendientes += $value['aceptadas'];	
		$total_negocios_proyectados +=$negocio;
		$total_primas_proyectados +=$prima;          
            ?>															
            <tr id="tr_<?php echo $value['id'] ?>">
                <td class="celda_gris"><div class="numeros" style="text-align:center;"><?php echo $value['negocio'] ; ?></div></td>
                <td class="celda_gris"><div class="numeros" style="text-align:center;"><?php echo $value['negociopai']; ?></div></td>
                <td class="celda_gris"><div class="numeros" style="text-align:right">$<?php echo number_format($value['prima'],2) ; ?></div></td>
                <td class="celda_roja" style="text-align:center" >
                    <a class="numeros fancybox"  <?php if($value['tramite']['work_order_ids']){?> href="javascript:void" title="Haga click aqui para ver los detalles" onclick='report_popup(<?php echo json_encode($value['tramite']['work_order_ids']);?>,"no","<?php echo  $tata; ?>")' <?php }?>>                    
                        <?php if(isset($value['tramite']['count'])) echo $value['tramite']['count']; else echo 0; ?>
                    </a>
                </td>
                <td class="celda_roja" style="text-align:right" >
                    <a class="numeros fancybox"  <?php if($value['tramite']['work_order_ids']){?> href="javascript:void" title="Haga click aqui para ver los detalles" onclick='report_popup(<?php echo json_encode($value['tramite']['work_order_ids']);?>,"no","<?php echo  $tata; ?>")' <?php }?>>
                        $<?php if( isset( $value['tramite']['prima'] ) ) echo number_format($value['tramite']['prima'],2); else echo number_format ('0',2); ?>
                    </a>
                </td>
                <td class="celda_amarilla" style="text-align:center" >
                    <a class="numeros fancybox"  style="text-align:center;" <?php if($value['aceptadas']['work_order_ids']){?> href="javascript:void" title="Haga click aqui para ver los detalles"  onclick='report_popup(<?php echo json_encode($value['aceptadas']['work_order_ids']);?>,"yes","<?php echo  $tata; ?>")' <?php }?>>
                        <?php if( isset( $value['aceptadas']['count'] ) ) echo  $value['aceptadas']['count']; else  echo $value['aceptadas'] ?>
                    </a>
                </td>
                <td class="celda_amarilla">
                    <a class="numeros fancybox"  style="text-align:right" <?php if($value['aceptadas']['work_order_ids']){?> href="javascript:void" title="Haga click aqui para ver los detalles"  onclick='report_popup(<?php echo json_encode($value['aceptadas']['work_order_ids']);?>,"yes","<?php echo $tata; ?>")' <?php }?>>
                        $ <?php if( isset( $value['aceptadas']['prima'] ) ) echo number_format($value['aceptadas']['prima'],2); else  echo number_format($value['aceptadas'],2); ?>
                    </a>
                </td>
                <td class="celda_verde"><div class="numeros"style="text-align:center;"><?php echo $negocio ?></div></td>
                <td class="celda_verde"><div class="numeros" style="text-align:right">$<?php echo number_format($prima,2); ?></div></td>
            </tr>
        
        
    <div id="info_<?php echo $value['id'] ?>" style="display: none;">
        
        <a href="javascript:" class="btn btn-link btn-hide">
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
		$simulator_url = base_url().'simulator/index/'.$value['id'];
			if( isset( $_POST['query']['ramo'] ) )
				$simulator_url .= '/'.$_POST['query']['ramo'];
			else
				$simulator_url .= '/1';
		$simulator_url .= '.html';		
		
		
		$perfil_url = base_url().'agent/index/'.$value['id'];
			if( isset( $_POST['query']['ramo'] ) )
				$perfil_url .= '/'.$_POST['query']['ramo'];
			else
				$perfil_url .= '/1';
		$perfil_url .= '.html';	
		
		
		?>

            |<a href="<?php echo $simulator_url ?>" class="btn btn-link">Definir meta</a>|<a href="<?php echo $simulator_url ?>" class="btn btn-link">Simular resultado</a>|
           <a href="#" class="btn btn-link">Desempeño en campo</a>|<a href="<?php echo $perfil_url ?>" class="btn btn-link">Perfil</a><br />            
    </div>
        
            <div id="info_<?php echo $value['id'] ?>" style="display: none;">
                <a href="javascript:" class="btn btn-link btn-hide">
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

<!--
<div id="contentFoot" style="height: 38px; width:77% !important;" class="theader">
    <table  class="sortable altrowstable tablesorter" id="Tfoot" style="min-width:100% !important;" >
        <thead>
            <tr>
                <td style="display:none"><div class="text_total">Agentes</div></td>
                <td style="width:70px;"><div class="numeros"></div>Negocios Pagados</td>
                <td style="width:70px;"><div class="numeros"></div>Negocios</td>
                <td style="width:100px;"><div class="numeros"></div>Primas Pagadas</td>
                <td style="width:70px;" class="celda_gris_roja"><div class="numeros"></div> Negocios en <br>  Tramite</td>
                <td style="width:100px;" class="celda_gris_roja"><div class="numeros"></div> En Tramite</td>
                <td style="width:70px;" class="celda_gris_amarilla"><div class="numeros"></div> Negocios Pendientes</td>
                <td style="width:100px;" class="celda_gris_amarilla"><div class="numeros"></div> Pendientes</td>
                <td  style="width:70px;"class="celda_gris_verde"><div class="numeros"></div> Negocios Proyectados</td>
                <td  style="width:100px;"class="celda_gris_verde"><div class="numeros"></div> Proyectadas</td>
            </tr>
        </thead>
    </table>
</div>  


<div id="contentFoot" style="width:77% !important; display:none;">
    <table  class="sortable altrowstable tablesorter" id="Tfoot" style="min-width:100% !important;" >
        <tr>
            <td style="display:none"><div class="text_total">Totales</div></td>
            <td style="width:70px; text-align:center;"><div class="numeros"><?php echo $total_negocio?></div>Negocios Pagados</td>
            <td style="width:70px; text-align:center;"><div class="numeros"><?php echo $total_negocio_pai?></div> Negocios</td>
            <td style="width:100px;text-align:right"><div class="numeros">$<?php echo $total_primas_pagadas ?></div> Pagados</td>
            <td style="width:70px; text-align:left;" class="celda_gris_roja"><div class="numeros"><?php echo $total_negocios_tramite ?></div> Negocios en <br>  Tramite</td>
            <td style="width:100px;text-align:left" class="celda_gris_roja"><div class="numeros">$<?php echo $total_primas_tramite ?></div> En Tramite</td>
            <td style="width:70px; text-align:left;" class="celda_gris_amarilla"><div class="numeros"><?php echo $total_negocio_pendiente ?></div> Negocios Pendientes</td>
            <td style="width:100px;text-align:left" class="celda_gris_amarilla"><div class="numeros">$<?php echo $total_primas_pendientes?></div> Pendientes</td>
            <td  style="width:70px; text-align:center;"class="celda_gris_verde"><div class="numeros"><?php echo $total_negocios_proyectados ?></div> Negocios Proyectados</td>
            <td  style="width:100px;text-align:right"class="celda_gris_verde"><div class="numeros">$<?php echo $total_primas_proyectados ?></div> Proyectadas</td>
        </tr>
    </table>
</div>  
-->
<script type="text/javascript">
	
	function total_negocio(){
		
		return <?php echo $total_negocio?>;
	}
	
	function total_negocio_pai(){
		
		return <?php echo $total_negocio_pai?>;
	}
	
	function total_primas_pagadas(){
		
		return <?php echo $total_primas_pagadas?>;
	}
	
	
</script>