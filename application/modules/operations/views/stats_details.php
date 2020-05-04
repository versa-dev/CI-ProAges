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
$base_url = base_url();
$this->load->helper('filter');
$selected_filter_period = get_selected_filter_period();
$selected_period = get_filter_period();
$segments = $this->uri->rsegment_array();

$is_director_page = ($segments[1] == 'director');
$export_segments = $segments;
$export_segments[2] = 'stat_recap_export';

$valid_ramos = array('vida', 'gmm', 'autos');
if (!isset($ramo) || !in_array($ramo, $valid_ramos))
	$ramo = $this->uri->segment(3, 'vida');
$recap_details = 0;
?>

<?php if (!$is_director_page) :?>
            <form id="operation-stats-form" method="post" action="<?php echo current_url()?>">
              <div class="row">
                  <div class="span12">
                      <a href="javascript:history.back();" title="Regresar">&lt;- Click aquí para regresar</a>
                  </div>
              </div>
              <div class="row">
                  <div class="span2">
Coordinadores&nbsp;:
                  </div>
                  <div class="span10">
<?php echo $coordinator_select ?>
                  </div>
              </div>
              <div class="row">
                  <div class="span2">
Período&nbsp;:
                  </div>
                  <div class="span10">
<?php echo $period_fields ?>
<select id="periodo_form" name="periodo" style="width: 175px" title="Período">
	  <option value="<?php echo $selected_period ?>"></option>
</select>
<input type="hidden" value="<?php echo $selected_period ?>" id="periodo" name="query[periodo]" />

                  </div>
              </div>
            </form>
<?php endif; ?>
		
            <div class="row" id="operations-stats">
		        <div class="span5" id="left-col" style="padding-left: 2em">
                  <p><span>Nuevos Negocios de <?php echo ucfirst($ramo) ?></span></p>
<?php if ($this->access_export_xls) :?>
                  <a href="<?php echo $base_url . implode('/', $export_segments); ?>.html" id="detail-export-xls" title="Exportar" style="font-size: larger;">
                      <img src="<?php echo $base_url ?>ot/assets/images/down.png" title="Exportar" />
				  </a>
<?php endif; ?>
                  <table class="head">
                      <tr>
                        <td>
                          <a class="stat-link" id="tramite_link" href="javascript:void(0);" class="btn btn-link" title="Ver Detalles">
                          En trámite</a>
                        </td>
                        <td>
<?php $recap_details += $stats['per_status']['tramite'];?>
                          <a class="stat-link" id="tramite_link" href="javascript:void(0);" class="btn btn-link" title="Ver Detalles">
                          <?php echo $stats['per_status']['tramite'];?></a>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <a class="stat-link" id="pagada_link" href="javascript:void(0);" class="btn btn-link" title="Ver Detalles">
                          Pagados</a>
                        </td>
                        <td>
<?php $recap_details += $stats['per_status']['pagada'];?>
                          <a class="stat-link" id="pagada_link" href="javascript:void(0);" class="btn btn-link" title="Ver Detalles">
                          <?php echo $stats['per_status']['pagada'];?></a>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <a class="stat-link" id="canceladas_link" href="javascript:void(0);" class="btn btn-link" title="Ver Detalles">
                          Cancelados</a>
                        </td>
                        <td>
<?php $recap_details += $stats['per_status']['canceladas']; ?>
                          <a class="stat-link" id="canceladas_link" href="javascript:void(0);" class="btn btn-link" title="Ver Detalles">
                          <?php echo $stats['per_status']['canceladas'];?></a>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <a class="stat-link" id="NTU_link" href="javascript:void(0);" class="btn btn-link" title="Ver Detalles">
                          NTU</a>
                        </td>
                        <td>
<?php $recap_details += $stats['per_status']['NTU']; ?>
                          <a class="stat-link" id="NTU_link" href="javascript:void(0);" class="btn btn-link" title="Ver Detalles">
                          <?php echo $stats['per_status']['NTU']; ?></a>
                        </td>
                      </tr>

                      <tr>
                        <td>
                          <a class="stat-link" id="pendientes_pago_link" href="javascript:void(0);" class="btn btn-link" title="Ver Detalles">
                          Pendientes de pago</a>
                        </td>
                        <td>
<?php $recap_details += $stats['per_status']['pendientes_pago']; ?>
                          <a class="stat-link" id="pendientes_pago_link" href="javascript:void(0);" class="btn btn-link" title="Ver Detalles">
                          <?php echo $stats['per_status']['pendientes_pago']; ?></a>
                        </td>
                      </tr>
                      <tr style="border-bottom: 2em solid white">
                        <td>
                          <a class="stat-link" id="activadas_link" href="javascript:void(0);" class="btn btn-link" title="Ver Detalles">
                          Activados</a>
                        </td>
                        <td>
<?php $recap_details += $stats['per_status']['activadas']; ?>
                          <a class="stat-link" id="activadas_link" href="javascript:void(0);" class="btn btn-link" title="Ver Detalles">
                          <?php echo $stats['per_status']['activadas']; ?></a>
                        </td>
                      </tr>

                      <tr>
                        <td colspan="2"></td>
                      </tr>
                      <tr style="background-color: yellow;">
                        <td>
                          <a class="stat-link" id="todos_link" href="javascript:void(0);" class="btn btn-link" title="Ver Detalles">
                          Trámites de nuevos negocios:</a>
                        </td>
                        <td>
                          <a class="stat-link" id="todos_link" href="javascript:void(0);" class="btn btn-link" title="Ver Detalles">
                          <?php echo $recap_details ?></a>
						</td>
                      </tr>
                  </table>
		        </div>

		        <div class="span7" id="right-col">

		        </div>
            </div>			

