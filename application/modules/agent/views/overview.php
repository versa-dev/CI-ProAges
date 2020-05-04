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
?>

<div class="row-fluid sortable">		
    <div class="box span12">      
        <div class="box-content">
            <?php // Show Messages ?>            
            <?php if (isset($message['type'])): ?>            
                <?php if ($message['type'] == true): ?>
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <img src="<?php echo base_url() ?>images/true.png" width="20" height="20" />
                        <strong>Listo: </strong> <?php echo $message['message']; // Show Dinamical message Success  ?>
                    </div>
                <?php endif; ?>


                <?php if ($message['type'] == false): ?>
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <img src="<?php echo base_url() ?>images/false.png" width="20" height="20" />
                        <strong>Error: </strong> <?php echo $message['message']; // Show Dinamical message error  ?>
                    </div>
                <?php endif; ?>			
            <?php endif; ?> 
			
            <form id="form" action="" method="post">
                      
                      <input type="hidden" name="query[ramo]" id="ramo" value="<?php if (isset($_POST['query']['ramo'])) echo $_POST['query']['ramo']; else echo 1; ?>" />
            
            <table class="table">
             	<tr>
                    <td rowspan="2" style="border:none !important;"><img src="<?php echo base_url() ?>usuarios/assets/profiles/<?php if( !empty( $data[0]['picture'] ) ) echo $data[0]['picture']?>" width="150" height="100" /></td>
                    <td style="border:none !important;">
                    	<div class="row">
                        	<div class="span10">
                            	<h3><?php if( !empty( $data[0]['name'] ) ) echo $data[0]['name']?></h3>                                
                             </div>
                             <?php if( isset( $access_update_profile ) and $access_update_profile == true ): ?>
                                <br /><br /><br />
                                <a href="<?php echo base_url() ?>usuarios/editar_perfil/<?php if( !empty( $userid ) ) echo $userid ?>.html" class="btn btn-inverse pull-right" style="display:none;"/>Editar Pefil</a>
                             <?php endif; ?>
                                                          
                        </div>
                        <div class="row">
                        	<div class="11">
                            	
                                <?php 
									if( !empty( $data[0]['uids'][0]['uid'] ) )
										echo $data[0]['uids'][0]['uid']. ' - '; 
									else 
										echo 'Sin clave asignada - '; 
								?>
						
								<?php 
									$data[0]['generacion']. ' - '; 
								?>
								<?php 
									if( $data[0]['connection_date'] != '0000-00-00' and $data[0]['connection_date'] != ''): 
								?>
									Conectado <?php echo getFormatDate($data[0]['connection_date']) ?>
						
								<?php else: ?>
										En proceso de conexión
								<?php endif; ?> 
                                                                                    	
                            </div>
                        </div>
                    </td>
                    <td rowspan="2" style="border:none !important;">                    	
                        <div class="efectividad">
                            <?php if( $report == 1 ): ?>
                            <h4>Efectividad<br><span style="font-weight:100; font-size:14px">(Negocios/Citas)</span></h4>
                            <h3 id="efectividad">0%</h3>
                            <?php endif; ?>
                        </div>
                        <?php $activities_url = base_url().'activities/index/'.$data[0]['id'].'.html';?>
						<?php $activities_url = base_url().'activities.html';?>
                        <!--<a href="<?php echo $activities_url ?>" class="btn btn-primary" style="margin-top:12px;"/>Ver/Editar Actividad</a>-->
                    </td>
                </tr>	
                
                <tr>
                	<td style="border:none !important;">
                                              	                        
                       <?php if( isset( $data[0]['product_group_id'] ) and $data[0]['product_group_id'] == 1 or isset( $ramo ) and $ramo == 'vida' ): ?>  						  <a href="javascript:void(0);" class="links-menu btn btn-link link-ramos" id="vida" style="color:#06F">Vida</a>
                      <?php else: ?>   
                          <a href="javascript:void(0);" class="links-menu btn btn-link link-ramos" id="vida" style="color:#000">Vida</a>
                      <?php endif; ?>              
                                          
                      <?php if( isset( $data[0]['product_group_id'] ) and $data[0]['product_group_id'] == 2 or isset( $ramo ) and $ramo == 'gmm' ): ?> 
                          <a href="javascript:void(0);" class="links-menu btn btn-link link-ramos" id="gmm" style="color:#06F">GMM</a>
                      <?php else: ?>   
                          <a href="javascript:void(0);" class="links-menu btn btn-link link-ramos" id="gmm" style="color:#000">GMM</a>
                      <?php endif; ?>     
                      
                      
                      <?php if( isset( $data[0]['product_group_id'] ) and $data[0]['product_group_id'] == 3 or isset( $ramo ) and $ramo == 'autos' ): ?> 
                          <a href="javascript:void(0);" class="links-menu btn btn-link link-ramos" id="autos" style="color:#06F">Autos</a>
                      <?php else: ?>   
                          <a href="javascript:void(0);" class="links-menu btn btn-link link-ramos" id="autos" style="color:#000">Autos</a>
                      <?php endif; ?>   
                     
					  
                                             
                      <select id="periodo" name="query[periodo]" class="input-small" onchange="this.form.submit();">
                          <option value="3" <?php if (isset($_POST['query']['periodo']) and $_POST['query']['periodo'] == 3) echo 'selected="selected"' ?>>Año</option>
                          <!--<option value="1" <?php if (isset($_POST['query']['periodo']) and $_POST['query']['periodo'] == 1) echo 'selected="selected"' ?>>Mes</option>
                          <?php if (!isset($_POST['query']['ramo']) or isset($_POST['query']['ramo']) and $_POST['query']['ramo'] == 1): ?> 
                              <option value="2" <?php if (isset($_POST['query']['periodo']) and $_POST['query']['periodo'] == 2) echo 'selected="selected"' ?> class="set_periodo">Trimestre</option>
                          <?php else: ?>
                              <option value="2" <?php if (isset($_POST['query']['periodo']) and $_POST['query']['periodo'] == 2) echo 'selected="selected"' ?> class="set_periodo">Cuatrimestre</option>
                          <?php endif; ?>-->
                         
                      </select>  
                      
                      
                      
                      <?php 
					  	$simulator_url = base_url().'simulator/index/'.$data[0]['id'];
						if( isset( $_POST['query']['ramo'] ) )
							$simulator_url .= '/'.$_POST['query']['ramo'];
						else
							$simulator_url .= '/1';
						$simulator_url .= '.html';		
					  ?>
                      
                      <a href="<?php echo $simulator_url ?>" class="btn btn-primary pull-right" style="margin-top:20px;" />  Simulador/Meta</a>
                     
                    
                                                            
                    </td>
                </tr>
                                
             </table>
            
             </form>
            
             <p class="line" style="margin-top:-30px;">&nbsp; </p>
             
             
             <div class="row">
                <div class="span11" style="width:95%; margin-left:50px;">
                    
                    <table class="table">
                    	<tr>
                        	<td colspan="7"  style="border:none !important;"><h3>Resumen de resultados</h3></td>
                            <td  style="border:none !important;">
                            	
                            </td>
                        </tr>
                        <tr>
                        	<td style="border:none !important;"><span class="title">Citas</span></td>                            
                            <td style="border:none !important;"><span class="title">Entrevistas</span></td>
                            <td style="border:none !important;"><span class="title">Prospectos</span></td>
                            <td style="border:none !important;"><span class="title">Meta de<br /> Negocios</span></td>     
                            <td style="border:none !important; <?php if( $report == 3 ) echo 'display:none' ?>"><span class="title">Negocios</span></td>
                            <td style="border:none !important;"><span class="title">Meta de<br /> Primas</span></td>   
                            <td style="border:none !important;"><span class="title">Primas</span></td>    
                            <td style="border:none !important;">
                            	<h4 class="blue">Valor $ por cita</h4>
                            </td>       
                        </tr>
                        <?php $simulator = object2array($simulator);// echo '<pre>'; print_r( $simulator  ); echo '</pre>';?>
                        
                        <input type="hidden" id="citas" value="<?php if( !empty( $activities[0]['cita'] ) ) echo $activities[0]['cita']; else 0; ?>" />
                        
                        <tr>
                        	<td style="border:none !important;"><span class="value"><?php if( !empty( $activities[0]['cita'] ) ) echo $activities[0]['cita']; else 0; ?></span></td>                            
                            <td style="border:none !important;"><span class="value"><?php if( !empty( $activities[0]['interview'] ) ) echo $activities[0]['interview']; else 0; ?></span></td>
                            <td style="border:none !important;"><span class="value"><?php if( !empty( $activities[0]['prospectus'] ) ) echo $activities[0]['prospectus']; else 0; ?> </span></td>
                            
                            <td class="celda_amarilla" style="border:none !important; <?php if( $report == 3 ) echo 'display:none' ?>">
                            	<span class="value"><?php if( isset( $simulator['noNegocios'] ) ) echo $simulator['noNegocios']; else if( isset( $simulator['nonegocios'] ) ) echo $simulator['nonegocios']; else echo 0; ?></span>
                            </td>     
                            <td class="celda_amarilla" style="border:none !important;"><div id="total_negocio_pai_text" class="value">0</div></td>
                            <td class="celda_verde" style="border:none !important;"><span class="value">$ <?php if( isset( $simulator['primasnetasiniciales'] ) ) echo number_format( $simulator['primasnetasiniciales'],2); else if( isset( $simulator['primasAfectasInicialesUbicar'] ) ) echo number_format($simulator['primasAfectasInicialesUbicar'],2); else echo 0; ?></span></td>   
                            <td class="celda_verde" style="border:none !important;"><div id="total_primas_pagadas_text" class="value">$ 0</div></td>    
                            <td style="border:none !important;">
                            	<h3 id="indicador_txt" class="blue">$ 0.00</h3>
                            </td>       
                        </tr>
                        
                    </table>
                    
                    
                    
                </div>                                                                                                 	
            </div>
            
            <div class="row">
                <div class="span11" style="width:95%; margin-left:50px;">
                	<h3>Detalle de ventas</h3>
            	</div>
            </div>        
            <div class="row">
                <div class="span11" style="margin-left:40px; width:95%">
                    <div class="main-container">
                        <div class="main  clearfix">  
            				<?php $this->load->view('report'.$report, array( 'data' => $data ) ) ; ?>
            			</div>
                    </div>
               </div>
            </div>            
                       
                       
        </div>               
    </div>
    
</div><!--/span-->
</div><!--/row-->
<?php 
function object2array($object) {
	if (is_object($object)) {
		foreach ($object as $key => $value) {
			$array[$key] = $value;
		}
	}
	else {
		$array = $object;
	}
	return $array;
}	
// Getting format date
function getFormatDate( $date = null ){
		
	 if( empty( $date ) or $date == '0000-00-00' ) return false;
	 
	 	 
	 $date = explode( '-', $date );
	 
	 
	 $meses= array('01'=>"Enero",'02'=>"Febrero",'03'=>"Marzo",'04'=>"Abril",'05'=>"Mayo",'06'=>"Junio",'07'=>"Julio",'08'=>"Agosto",'09'=>"Septiembre",'10'=>"Octubre",'11'=>"Noviembre",'12'=>"Diciembre");
	 
	 return 'el '.$date[2].' de '.$meses[$date[1]].' del '.$date[0]; 
			 
}	

?>