<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
  /** 
   * Termometro de Ventas - Vista Detalle General
   * 
   * @author     Jesus Castilla & José Gilberto Pérez Molina
   * Date:       December, 2018
   * Locaion:    Veracruz, Mexico
   * Mail:       jesuscv1821@gmail.com
   */

  $post_data = isset($_POST['query']) ? ',prev_post:'. json_encode($_POST['query']) : '';
  $base_url = base_url();
  $is_update_page = ($this->uri->segment(2, '') == 'update');

  //Ratios de comparación
  //Ratios de comparacion General
  $grl_ia   = comparationRatio($data['data_general_year1']['ingresos_agentes'],$data['data_general_year2']['ingresos_agentes']);
  $grl_ac   = comparationRatio($data['data_general_year1']['agentes_congresistas'],$data['data_general_year2']['agentes_congresistas']);
  $grl_ab   = comparationRatio($data['data_general_year1']['agentes_bono_integral'],$data['data_general_year2']['agentes_bono_integral']);
  $grl_ar   = comparationRatio($data['data_general_year1']['agentes_reclutados'],$data['data_general_year2']['agentes_reclutados']);
  $grl_at   = comparationRatio($data['data_general_year1']['agentes_total'],$data['data_general_year2']['agentes_total']);
  $grl_ap   = comparationRatio($data['data_general_year1']['agentes_reclutados_produccion'],$data['data_general_year2']['agentes_reclutados_produccion']);
  $grl_aa   = comparationRatio($data['data_general_year1']['percentage_agentes_activos'],$data['data_general_year2']['percentage_agentes_activos']);
  $grl_pr   = comparationRatio($data['data_general_year1']['produccion_g1_g4'], $data['data_general_year2']['produccion_g1_g4']);
  $grl_mdrt = comparationRatio($data['data_general_year1']['agentes_mdrt'],$data['data_general_year2']['agentes_mdrt']);

  //Ratios de Comparacion Vida
  $vi_ia  = comparationRatio($data['data_vida_year1']['vida_ingresos_acumulados'], $data['data_vida_year2']['vida_ingresos_acumulados']);
  $vi_ac  = comparationRatio($data['data_vida_year1']['vida_agentes_congresistas'], $data['data_vida_year2']['vida_agentes_congresistas']);
  $vi_ppi = comparationRatio($data['data_vida_year1']['vida_primas_iniciales'], $data['data_vida_year2']['vida_primas_iniciales']);
  $vi_cr  = comparationRatio($data['data_vida_year1']['vida_perc_conserv_real'], $data['data_vida_year2']['vida_perc_conserv_real']);
  $vi_avn = comparationRatio($data['data_vida_year1']['vida_agentes_venta_nueva'], $data['data_vida_year2']['vida_agentes_venta_nueva']);
  $vi_cac = comparationRatio($data['data_vida_year1']['vida_perc_conserv_real_acot'], $data['data_vida_year2']['vida_perc_conserv_real_acot']);
  $vi_pin = comparationRatio($data['data_vida_year1']['vida_prod_inicial'], $data['data_vida_year2']['vida_prod_inicial']);
  $vi_pub = comparationRatio($data['data_vida_year1']['vida_primas_ubicar'], $data['data_vida_year2']['vida_primas_ubicar']);
  $vi_ppa = comparationRatio($data['data_vida_year1']['vida_primas_pagar'], $data['data_vida_year2']['vida_primas_pagar']);
  $vi_neg = comparationRatio($data['data_vida_year1']['vida_negocios_pai'], $data['data_vida_year2']['vida_negocios_pai']); 
  $vi_sta = comparationRatio($data['data_vida_year1']['vida_puntos_standing'], $data['data_vida_year2']['vida_puntos_standing']); 
  $vi_pca = comparationRatio($data['data_vida_year1']['vida_perc_cancel'], $data['data_vida_year2']['vida_perc_cancel']);
  //$vi_pac = comparationRatio($data['data_vida_year1']['vida_percs']['vida_perc_acot'], $data['data_vida_year2']['vida_percs']['vida_perc_acot']);

  //Ratios de Comparacion GMM
  $gmm_ia   = comparationRatio($data['data_gmm_year1']['gmm_ingresos_acumulados'], $data['data_gmm_year2']['gmm_ingresos_acumulados']);
  $gmm_ac   = comparationRatio($data['data_gmm_year1']['gmm_agentes_congresistas'], $data['data_gmm_year2']['gmm_agentes_congresistas']);
  $gmm_ppi  = comparationRatio($data['data_gmm_year1']['gmm_primas_ubicar'], $data['data_gmm_year2']['gmm_primas_ubicar']);
  $gmm_con  = comparationRatio($data['data_gmm_year1']['gmm_perc_conserv'],$data['data_gmm_year2']['gmm_perc_conserv']);
  $gmm_pubi = comparationRatio($data['data_gmm_year1']['gmm_primas_ubicar'],$data['data_gmm_year2']['gmm_primas_ubicar']);
  $gmm_sre  = comparationRatio($data['data_gmm_year1']['gmm_perc_sinis_real'], $data['data_gmm_year2']['gmm_perc_sinis_real']);
  $gmm_ap   = comparationRatio($data['data_gmm_year1']['gmm_agentes_productivos'], $data['data_gmm_year2']['gmm_agentes_productivos']);
  $gmm_an   = comparationRatio($data['data_gmm_year1']['gmm_asegurados_nuevos'], $data['data_gmm_year2']['gmm_asegurados_nuevos']);
  $gmm_sac  = comparationRatio($data['data_gmm_year1']['gmm_perc_sinis_acot'], $data['data_gmm_year2']['gmm_perc_sinis_acot']);
  $gmm_pd   = comparationRatio($data['data_gmm_year1']['gmm_puntos_productivos'],$data['data_gmm_year2']['gmm_puntos_productivos']);
  $gmm_avn  = comparationRatio($data['data_gmm_year1']['gmm_agentes_venta_nueva'],$data['data_gmm_year2']['gmm_agentes_venta_nueva']);
  $gmm_pin  = comparationRatio($data['data_gmm_year1']['gmm_prod_incial_ubi'],$data['data_gmm_year2']['gmm_prod_incial_ubi']);
?>

  <div>
  	<ul class="breadcrumb">
  		<li>
  			<a href="<?php echo base_url() ?>">Admin</a> <span class="divider">/</span>
  		</li>
  		<li>
  			Termómetro de Ventas
  		</li>
  	</ul>
  </div>

  <div class="box ">
    <div class="box-content">
      <div class="row-fluid">
        <?= form_open('', 'id="ot-form"'); ?>
          <table class="filterstable no-more-tables" style="margin-left: 0%; width: 430px">
            <thead>
              <th>Período :<br />
                <?= form_dropdown('periodo', $data['periods'], $data['other_filters']['periodo'], 'id="periodo" style="width: 430px" title="Período" onchange="this.form.submit();"'); ?>
              </th>
            </thead>
          </table>
        <?= form_close(); ?>
      </div>
      <div class="row-fliuid">
        <h4 class="pull-right">Fecha de ultimo pago registrado: <?= $data['last_update']?> </h4>

      </div>
      <?php if(count($data['lost_data']) >0): ?>
          <div class="row-fliuid">
            <h5><a href="" data-toggle="modal" data-target="#modal_generation" class="text-error">Hay datos que no pudieron ser importados, click para ver detalles</a></h5>
          </div>
      <?php endif; ?>

         
      <br />
      <h3>General</h3>
      <div class="row-fluid" id="indicators">
        <div class="box span4">
          <div class="span12"></div>
          <div class="span12 indicator">
            <span class="title"><p style="color: black;">Ingresos Acumulados Agentes:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_ingresos_acum'})">$ <?= number_format($data['data_general_year1']['ingresos_agentes'], 2);  ?></a></p>
            <span class="comparative <?= sign($grl_ia) ?>">
              <i class="fa <?= sign($grl_ia ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($grl_ia), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
          <div class="span12 indicator">
            <span class="title"><p style="color: black;">Producción Integral G1 a G4:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_prod_ini'})">$ <?= number_format($data['data_general_year1']['produccion_g1_g4'], 2);  ?></a></p>
            <span class="comparative <?= sign($grl_pr) ?>">
              <i class="fa <?= sign($grl_pr ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($grl_pr), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
          <div class="span12 indicator">
            <span class="title"><p style="color: black;">Agentes Reclutados:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_agent_recruit'})"><?= number_format($data['data_general_year1']['agentes_reclutados'], 0);  ?></a></p>
            <span class="comparative <?= sign($grl_ar) ?>">
              <i class="fa <?= sign($grl_ar ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($grl_ar), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
        </div>
        <div class="box span4">
          <div class="span12"></div>
          <div class="span12 indicator">
            <span class="title"><p style="color: black;">Agentes Reclutados con Producción:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_agent_recruit_prod'})"><?= number_format($data['data_general_year1']['agentes_reclutados_produccion'], 0);  ?></a></p>
            <span class="comparative <?= sign($grl_ap) ?>">
              <i class="fa <?= sign($grl_ap ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($grl_ap), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
          <div class="span12 indicator">
            <span class="title"><p style="color: black;">% de Agentes Activos:</p></span>
            <p class="text-info"><?= number_format($data['data_general_year1']['percentage_agentes_activos'],2) ?>%</p>
            <span class="comparative <?= sign($grl_aa) ?>">
              <i class="fa <?= sign($grl_aa ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($grl_aa), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
          <div class="span12 indicator">
            <span class="title"><p style="color: black;">Agentes Activos Totales:</p></span>
            <p class="text-info"><?= $data['data_general_year1']['agentes_total'] ?></p>
            <span class="comparative <?= sign($grl_at) ?>">
              <i class="fa <?= sign($grl_at ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($grl_at), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
        </div>
        <div class="box span4">
          <div class="span12"></div>
          <div class="span12 indicator">
            <span class="title"><p style="color: black;">Agentes Congresistas:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_agent_congress'})"><?= number_format($data['data_general_year1']['agentes_congresistas'], 0);  ?></a></p>
            <span class="comparative <?= sign($grl_ac) ?>">
              <i class="fa <?= sign($grl_ac ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($grl_ac), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
          <div class="span12 indicator">
            <span class="title"><p style="color: black;">Agentes Bono Integral:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_agent_bonus'})"><?= number_format($data['data_general_year1']['agentes_bono_integral'], 0);  ?></a></p>
            <span class="comparative <?= sign($grl_ab) ?>">
              <i class="fa <?= sign($grl_ab ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($grl_ab), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
          <div class="span12 indicator">
            <span class="title"><p style="color: black;">Agentes MDRT:</p></span>
            <p class="text-info"><?= $data['data_general_year1']['agentes_mdrt'] ?></p>
            <span class="comparative <?= sign($grl_mdrt) ?>">
              <i class="fa <?= sign($grl_mdrt ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($grl_mdrt), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
        </div>
        <div class="row-fluid"></div>

        <div class="box">
            <div class="row" id="indicators_">
                <div class="span4">
                  <div class="printable">
                    <div class="span12">
                      <div class="opciones span12" style="margin-left: 9%; margin-top:5%; width:95%">
                        <p style="color: black; font-family: sans-serif; font-weight: normal; font-size: 16px;" align="center">
                          Distribución de Ingresos por Generación:
                          <a href="#" class="btn btn-primary toggleTable" data-target="#generation_table" data-resize="#generation_cell">
                            <i class="icon-list-alt"></i>
                          </a>
                        </p>
                      </div>
                    </div>

                    <div id="generation_cell" class="span11 graph-container" style="position: relative; height: 300px;">
                      <canvas id="generation_container_chart" height="200"></canvas>
                    </div>
                    <div id="generation_table" class="span12 table-container" style="margin-left: 9%; margin-top:5%; width:95%; display: none">
                      <table class="table table-hover table-bordered">
                        <thead>
                          <tr>
                            <th>Generación</th>
                            <th>Ingresos en <?= substr($data['other_filters']['periodo'], 1) ?></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $total = 0; foreach ($data['data_general_year1']['ingresos_generacion'] as $generacion => $ingreso): $total = $total + $ingreso; ?>
                            <tr>
                              <td><b><?= $generacion ?></b></td>
                              <td>
                                  $<?php echo number_format($ingreso, 2); ?>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                          <tr>
                            <td><b>Total</b></td>
                            <td>
                                <?= number_format($total, 2); ?>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="span4">
                  <div class="printable">
                    <div class="span12">
                      <div class="opciones span12" style="margin-left: 9%; margin-top:5%; width:95%">
                        <p style="color: black; font-family: sans-serif; font-weight: normal; font-size: 16px;" align="center">
                          Distribución de Ingresos por Ramo: 
                          <a href="#" class="btn btn-primary toggleTable" data-target="#ramo_table" data-resize="#ramo_cell">
                            <i class="icon-list-alt"></i>
                          </a>
                        </p>
                      </div>

                    </div>

                    <div id="ramo_cell" class="span11 graph-container" style="position: relative; height: 300px;">
                      <canvas id="ramo_container_chart" height="200"></canvas>
                    </div>
                    <div id="ramo_table" class="span11 table-container table-generation" style="margin-left: 9%; margin-top:5%; width:95%; display: none">
                      <table class="table table-hover table-bordered">
                        <thead>
                          <tr>
                            <th>Ramo</th>
                            <th>Ingresos en <?= substr($data['other_filters']['periodo'], 1) ?></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $total = 0; foreach ($data['data_general_year1']['distribucion_ingreso_ramo'] as $ramo => $ingreso): $total = $total + $ingreso; ?>
                            <tr>
                              <td><b><?= $ramo ?></b></td>
                              <td>
                                $<?php echo number_format($ingreso, 2); ?>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                          <tr>
                            <td><b>Total</b></td>
                            <td>
                                <?= number_format($total, 2); ?>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="span4">

                  <div class="printable">
                    <div class="span12">
                      <div class="opciones span12" style="margin-left: 0%; margin-top:5%; width:99%">
                        <p style="color: black; font-family: sans-serif; font-weight: normal; font-size: 16px;" align="center">
                          Agentes Congresistas por Tipo de Congreso:
                          <a href="#" class="btn btn-primary toggleTable" data-target="#congreso_table" data-resize="#congreso_cell">
                            <i class="icon-list-alt"></i>
                          </a>
                        </p>
                      </div>

                    </div>

                    <div id="congreso_cell" class="span11 graph-container" style="position: relative; height: 300px; margin-left: 9%; margin-top:5%; width:87%;">
                      <canvas id="congreso_container_chart" height="200"></canvas>
                    </div>
                    <div id="congreso_table" class="span11 table-container table-generation" style="margin-left: 9%; margin-top:5%; width:87%; display: none">
                      <table class="table table-hover table-bordered">
                        <thead>
                          <tr>
                            <th>Congreso</th>
                            <th>Agentes con congreso en <?= substr($data['other_filters']['periodo'], 1) ?></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $total = 0; foreach ($data['data_general_year1']['agentes_congresistas_tipo'] as $congreso => $agentes): $total = $total + $agentes; ?>
                            <tr>
                              <td><b><?= $congreso ?></b></td>
                              <td>
                                  <?php echo number_format($agentes,0); ?>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                          <tr>
                            <td><b>Total</b></td>
                            <td>
                              <?= number_format($total,0); ?>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
            
            </div>
        </div>
      </div>
      
      <h3><a href="<?php echo site_url('termometro/detail_vida/?id=0'); ?>">Vida</a></h3>
      <div class="row-fluid" id="indicators">
        <div class="box span3">
          <div class="span12"></div>
          <div class="span12 indicator"><span class="title"><p style="color: black;">Ingresos Acumulados Agentes:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_ingresos_acum_vida'})">$ <?= number_format($data['data_vida_year1']['vida_ingresos_acumulados'], 2);  ?></a></p>
            <span class="comparative <?= sign($vi_ia) ?>">
              <i class="fa <?= sign($vi_ia ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($vi_ia), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
          <div class="span12 indicator"><span class="title"><p style="color: black;">Agentes Congresistas:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_agent_congress_vida'})"><?= number_format($data['data_vida_year1']['vida_agentes_congresistas'], 0);  ?></a></p>
            <span class="comparative <?= sign($vi_ac) ?>">
              <i class="fa <?= sign($vi_ac ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($vi_ac), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
          <div class="span12 indicator"><span class="title"><p style="color: black;">Total de Agentes Con <br/>Venta Nueva:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_agent_venta_nueva_vida'})"><?= number_format($data['data_vida_year1']['vida_agentes_venta_nueva'], 0);  ?></a></p                <span class="comparative <?= sign($vi_avn) ?>">
              <span class="comparative <?= sign($vi_avn) ?>">
                <i class="fa <?= sign($vi_avn ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
                <?= number_format(abs($vi_avn), 2 ) ?>%
              </span>
            </div>
            <div class="span12"></div>
        </div>
        <div class="box span3">
          <div class="span12"></div>
          <div class="span12 indicator"><span class="title"><p style="color: black;">Primas Pagadas Iniciales:<br/></p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_primas_pagos_ini_vida'})">$ <?= number_format($data['data_vida_year1']['vida_primas_iniciales'], 2);  ?></a></p>
            <span class="comparative <?= sign($vi_ppi) ?>">
              <i class="fa <?= sign($vi_ppi ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($vi_ppi), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
          <div class="span12 indicator"><span class="title"><p style="color: black;">Primas para Ubicar:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_primas_ubi_vida'})">$ <?= number_format($data['data_vida_year1']['vida_primas_ubicar'], 2);  ?></a></p>  
            <span class="comparative <?= sign($vi_pub) ?>">
              <i class="fa <?= sign($vi_pub ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($vi_pub), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
          <div class="span12 indicator"><span class="title"><p style="color: black;">Primas para Pagar:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_primas_pagos_vida'})">$ <?= number_format($data['data_vida_year1']['vida_primas_pagar'], 2);  ?></a></p>  
            <span class="comparative <?= sign($vi_ppa) ?>">
              <i class="fa <?= sign($vi_ppa ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($vi_ppa), 2 ) ?>%
            </span>
          </div> 
          <div class="span12"></div>
          <div class="span12"></div>
        </div>
        <div class="box span3">
          <div class="span12"></div>
          <div class="span12 indicator"><span class="title"><p style="color: black;">% de Conservación Real:</p></span>
            <p class="text-info"><?= number_format(abs($data['data_vida_year1']['vida_perc_conserv_real']* 100),2) ?> %</p>
            <span class="comparative <?= sign($vi_cr) ?>">
              <i class="fa <?= sign($vi_cr ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($vi_cr), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
          <div class="span12 indicator"><span class="title text-muted"><p style="color: black;">% de Conservación Acotada:</p></span>
            <p class="text-info"><?= number_format(abs($data['data_vida_year1']['vida_perc_conserv_real_acot']* 100),2) ?> %</p>
            <span class="comparative <?= sign($vi_cac) ?>">
              <i class="fa <?= sign($vi_cac ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($vi_cac), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
          <div class="span12 indicator"><span class="title"><p style="color: black;">% de Canceladas:</p></span>
            <p class="text-info"><?= number_format(abs($data['data_vida_year1']['vida_perc_cancel'] * 100), 2 ) ?> %</p>
            <span class="comparative <?= sign($vi_pca) ?>">
              <i class="fa <?= sign($vi_pca ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($vi_pca), 2 ) ?>%
            </span>
          </div>  
          <div class="span12"></div>
        </div>
        <div class="box span3">
          <div class="span12"></div>
          <div class="span12 indicator"><span class="title"><p style="color: black;">Negocios PAI:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_negocios_pai_vida'})"><?= number_format($data['data_vida_year1']['vida_negocios_pai'], 0);  ?></a></p>
            <span class="comparative <?= sign($vi_neg) ?>">
              <i class="fa <?= sign($vi_neg ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($vi_neg), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
          <div class="span12 indicator"><span class="title"><p style="color: black;">Puntos Standing:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_ptos_stanging_vida'})"><?= number_format($data['data_vida_year1']['vida_puntos_standing'], 0);  ?></a></p>
            <span class="comparative <?= sign($vi_sta) ?>">
              <i class="fa <?= sign($vi_sta ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($vi_sta), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
          <div class="span12 indicator"><span class="title"><p style="color: black;">Producción inicial p/ubicar <br/>Agentes G1 a G4:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_prod_ini_vida'})">$ <?= number_format($data['data_vida_year1']['vida_prod_inicial'], 2);  ?></a></p>  
            <span class="comparative <?= sign($vi_pin) ?>">
              <i class="fa <?= sign($vi_pin ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($vi_pin), 2 ) ?>%
            </span>
          </div> 
          <div class="span12"></div>
        </div>
        <div class="row-fluid"></div>
        <div class="box">
          <div class="row" id="indicators_4">
            <div class="span6">
              <div class="printable">
                <div class="span12">
                  <div class="opciones span12" style="margin-left: 9%; margin-top:5%; width:95%">
                    <p style="color: black; font-family: sans-serif; font-weight: normal; font-size: 16px;" align="center">
                      Distribución de Ingresos por Generación:
                      <a href="#" class="btn btn-primary toggleTable" data-target="#generation_table_vida" data-resize="#generation_cell_vida">
                        <i class="icon-list-alt"></i>
                      </a>
                    </p>
                  </div>
                </div>
                

                <div id="generation_cell_vida" class="span11 graph-container" style="position: relative; height: 450px;">
                  <canvas id="generation_container_chart_vida" height="200"></canvas>
                </div>
                <div id="generation_table_vida" class="span11 table-container table-generation-vida" style="margin-left: 9%; margin-top:5%; width:95%;  display: none">
                  <table class="table table-hover table-bordered">
                    <thead>
                      <tr>
                        <th>Generación</th>
                        <th>Ingresos en <?= substr($data['other_filters']['periodo'], 1) ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $total = 0; foreach ($data['data_vida_year1']['vida_ingresos_gen'] as $generacion => $ingreso): $total = $total + $ingreso; ?>
                      <tr>
                        <td><b><?= $generacion ?></b></td>
                        <td>
                            $<?php echo number_format($ingreso, 2); ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                    <tr>
                      <td><b>Total</b></td>
                      <td>
                          $ <?= number_format($total, 2); ?>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
                  
          <div class="span6">
            <div class="printable">
              <div class="span12">
                <div class="opciones span12" style="margin-left: 9%; margin-top:5%; width:95%">
                  <p style="color: black; font-family: sans-serif; font-weight: normal; font-size: 16px;" align="center">
                    Distribución de Ingresos por Generación:
                    <a href="#" class="btn btn-primary toggleTable" data-target="#concept_vida_table" data-resize="#concept_vida_cell">
                      <i class="icon-list-alt"></i>
                    </a>
                  </p>
                </div>
              </div>

              <div id="concept_vida_cell" class="span11 graph-container" style="position: relative; height: 450px;">
                <canvas id="concept_vida_container_chart" height="200"></canvas>
              </div>
              <div id="concept_vida_table" class="span11 table-container table-generation" style="margin-left: 9%; margin-top:5%; width:87%; display: none">
                <table class="table table-hover table-bordered">
                  <thead>
                    <tr>
                      <th>Concepto</th>
                      <th>Distribución de Ingresos por Generación en <?= substr($data['other_filters']['periodo'], 1) ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $total = 0; foreach ($data['data_vida_year1']['vida_ventas'] as $type => $amount): $total = $total + $amount; ?>
                      <tr>
                        <td><b><?= $type ?></b></td>
                        <td>
                            $ <?php echo number_format($amount,2); ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                    <tr>
                      <td><b>Total</b></td>
                      <td>
                        $ <?= number_format($total,2); ?>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      

      <h3><a href="<?php echo site_url('termometro/detail_gmm'); ?>">GMM</a></h3>
      <div class="row" id="indicators">
        <div class="box span4">
          <div class="span12"></div>
          <div class="span12 indicator"><span class="title"><p style="color: black;">Ingresos Acumulados Agentes:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_ingresos_acumulados_gmm'})">$ <?= number_format($data['data_gmm_year1']['gmm_ingresos_acumulados'], 2);  ?></a></p>
            <span class="comparative <?= sign($gmm_ia) ?>">
              <i class="fa <?= sign($gmm_ia ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($gmm_ia), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
          <div class="span12 indicator"><span class="title"><p style="color: black;">Producción Inicial p/Ubicar <br> Agentes G1 a G4:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_prod_ini_gmm'})">$ <?= number_format($data['data_gmm_year1']['gmm_prod_incial_ubi'], 2);  ?></a></p>
            <span class="comparative <?= sign($gmm_pin) ?>">
              <i class="fa <?= sign($gmm_pin ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($gmm_pin), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
          <div class="span12 indicator"><span class="title"><p style="color: black;">Agentes Congresistas:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_agent_congress_gmm'})"><?= number_format($data['data_gmm_year1']['gmm_agentes_congresistas'], 0);  ?></a></p>
            <span class="comparative <?= sign($gmm_ac) ?>">
              <i class="fa <?= sign($gmm_ac ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($gmm_ac), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
        </div>
        <div class="box span4">
          <div class="span12"></div>
          <div class="span12 indicator">
            <span class="title"><p style="color: black;">Agentes Productivos:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_agentes_prod_gmm'})"><?= number_format($data['data_gmm_year1']['gmm_agentes_productivos']);  ?></a></p>
            <span class="comparative <?= sign($gmm_ap) ?>">
              <i class="fa <?= sign($gmm_ap ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($gmm_ap), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
          <div class="span12 indicator"><span class="title"><p style="color: black;">Total de Agentes con <br/>Venta Nueva:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_agent_venta_nueva_gmm'})"><?= number_format($data['data_gmm_year1']['gmm_agentes_venta_nueva'], 0);  ?></a></p>
            <span class="comparative <?= sign($gmm_avn) ?>">
              <i class="fa <?= sign($gmm_avn ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($gmm_avn), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
          <div class="span12 indicator"><span class="title"><p style="color: black;">Primas para Ubicar:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_primas_ubi_gmm'})">$ <?= number_format($data['data_gmm_year1']['gmm_primas_ubicar'], 2);  ?></a></p>
            <span class="comparative <?= sign($gmm_pubi) ?>">
              <i class="fa <?= sign($gmm_pubi ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($gmm_pubi), 2 ) ?>%
            </span>
          </div>
          <div class="span12"></div>
        </div>
        <div class="box span4">
          <div class="span12"></div>
          <div class="span12 indicator"><span class="title"><p style="color: black;">Primas Pagadas Iniciales:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_primas_pagos_gmm'})">$ <?= number_format($data['data_gmm_year1']['gmm_primas_pagadas_iniciales'], 2);  ?></a></p>
            <span class="comparative <?= sign($gmm_ppi) ?>">
              <i class="fa <?= sign($gmm_ppi ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($gmm_ppi), 2 ) ?>%
            </span>
          </div>
          <div class="span12 indicator"><span class="title"><p style="color: black;">Asegurados Nuevos:</p></span>
            <p class="text-info"><a href="javascript:void" title="Haga click aqui para ver los detalles" onclick="popup_detail({type: 'grl_num_asegurados_gmm'})"><?= number_format($data['data_gmm_year1']['gmm_asegurados_nuevos'], 0);  ?></a></p>
            <span class="comparative <?= sign($gmm_an) ?>">
              <i class="fa <?= sign($gmm_an ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($gmm_an), 2 ) ?>%
            </span>
          </div>
          <div class="span12 indicator"><span class="title"><p style="color: black;">% de Siniestralidad Acotada:</p></span>
            <p class="text-info"><?= number_format(abs($data['data_gmm_year1']['gmm_perc_sinis_acot'] * 100), 2 )?> %</p>
            <span class="comparative <?= sign($gmm_sac) ?>">
              <i class="fa <?= sign($gmm_sac ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($gmm_sac), 2 ) ?>%
            </span>
          </div>
          <div class="span12 indicator"><span class="title"><p style="color: black;">% de Siniestralidad Real:</p></span>
            <p class="text-info"><?= number_format(abs($data['data_gmm_year1']['gmm_perc_sinis_real'] * 100), 2 ) ?> %</p>
            <span class="comparative <?= sign($gmm_sre) ?>">
              <i class="fa <?= sign($gmm_sre ,"fa-arrow-up", "fa-arrow-down", "") ?>"></i>
              <?= number_format(abs($gmm_sre), 2 ) ?>%
            </span>
          </div> 
          <div class="span12"></div>
        </div>
        <div class="row-fluid"></div>
        <div class="box">
          <div class="row" id="indicators_4">
            <div class="span6">
              <div class="printable">
                  <div class="span12">
                    <div class="opciones span12" style="margin-left: 9%; margin-top:5%; width:95%">
                      <p style="color: black; font-family: sans-serif; font-weight: normal; font-size: 16px;" align="center">
                        Distribución de Ingresos por Generación:
                        <a href="#" class="btn btn-primary toggleTable" data-target="#generation_table_gmm" data-resize="#generation_cell_gmm">
                          <i class="icon-list-alt"></i>
                        </a>
                      </p>
                    </div>
                  </div>
                  <div id="generation_cell_gmm" class="span11 graph-container" style="position: relative; height: 450px;">
                    <canvas id="generation_container_chart_gmm" height="200"></canvas>
                  </div>
                  <div id="generation_table_gmm" class="span11 table-container table-generation-gmm" style="margin-left: 9%; margin-top:5%; width:95%; display: none">
                    <table class="table table-hover table-bordered">
                      <thead>
                        <tr>
                          <th>Generación</th>
                          <th>Ingresos en <?= substr($data['other_filters']['periodo'], 1) ?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $total = 0; foreach ($data['data_gmm_year1']['gmm_ingresos_acumulados_gen'] as $generacion => $ingreso): $total = $total + $ingreso; ?>
                          <tr>
                            <td><b><?= $generacion ?></b></td>
                            <td>
                                $<?php echo number_format($ingreso, 2); ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                        <tr>
                          <td><b>Total</b></td>
                          <td>
                              <?= number_format($total, 2); ?>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
              </div>
            </div>  
            <div class="span6">
              <div class="printable">
                <div class="span12">
                  <div class="opciones span12" style="margin-left: 9%; margin-top:5%; width:95%">
                    <p style="color: black; font-family: sans-serif; font-weight: normal; font-size: 16px;" align="center">
                      Distribución de Ingresos por </br>Venta Nueva y Renovación por Concepto:
                      <a href="#" class="btn btn-primary toggleTable" data-target="#concept_gmm_table" data-resize="#concept_gmm_cell">
                        <i class="icon-list-alt"></i>
                      </a>
                    </p>
                  </div>
                </div>

                <div id="concept_gmm_cell" class="span11 graph-container" style="position: relative; height: 450px;">
                  <canvas id="concept_gmm_container_chart" height="200"></canvas>
                </div>
                <div id="concept_gmm_table" class="span11 table-container table-generation" style="margin-left: 9%; margin-top:5%; width:87%; display: none">
                  <table class="table table-hover table-bordered">
                    <thead>
                      <tr>
                        <th>Congreso</th>
                        <th>Ingresos por Venta Nueva y Renovación en <?= substr($data['other_filters']['periodo'], 1) ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $total = 0; foreach ($data['data_gmm_year1']['gmm_ventas'] as $type => $amount): $total = $total + $amount; ?>
                        <tr>
                          <td><b><?= $type ?></b></td>
                          <td>
                              $ <?php echo number_format($amount,0); ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                      <tr>
                        <td><b>Total</b></td>
                        <td>
                            $ <?= number_format($total,0); ?>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>  
          </div>
        </div>
      </div>
    </div>
  </div>



 <div id="modal_generation" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" 
  style="display: none; position: fixed;top: 50%;left: 50%">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel" class="text-error"><i class='icon-exclamation-sign'></i> Atención <i class='icon-exclamation-sign'></i></h3>
    </div>
    <div class="modal-body">
      <h5 id="myModalLabel" >Los siguientes datos no pudieron ser importados debido a que no se encontró un agente en el sistema con la clave o folio en el registro:</h5>
      <h6><hr style="width:100%;"></h6>
      <?php foreach($data['lost_data'] as $key => $value):?>
        
        <?php foreach($value as $row => $data):?>
          <?php if($row == 'extras'): ?>
            <?php foreach($data as $key_extras => $value_extras):?>
              <p><?= $key_extras ?> : <?= $value_extras?> </p>
            <?php endforeach;?>
          <?php else: ?>
            <p><?= $row ?> : <?= $data?> </p>
          <?php endif; ?>
        <?php endforeach;?>
        <h6><hr style="width:100%;"></h6>

      <?php endforeach;?>

    </div>
  </div>


<!--/row-->

