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
$na_value = 'N/D';
$segments = $this->uri->rsegment_array();
$this->load->helper('filter');
$selected_filter_period = get_selected_filter_period();
$selected_period = get_filter_period();
$user_id = $this->user_id ? $this->user_id : 'null';
$with_primas = (($segments[1] == 'director') && isset($segments[2]) && ($segments[2] == 'stat_details'));
$segments[2] = 'stat_detail_export';
if ($with_primas)
	$segments[5] = 'full';

$cust_periodo = $this->custom_period_from . '%' . $this->custom_period_to;
$ot_url = $base_url . "director/ot_por_producto/$stat_type/$status/$selected_period/$user_id/";
if ($selected_period == 4){
  $ot_url = $ot_url . $cust_periodo . "/";
}
$user = $this->user_id ? $this->user_id : '';

?>

<script type="text/javascript">
	$( document ).ready( function(){
		$(".detailed").on( "click", function(){
			var linkId = $(this).attr("id");
			linkId = linkId.replace(/detailed-/, "");
			var detailUrl = "<?php echo $ot_url ?>" + linkId  +  ".html";

			$.fancybox.showLoading();
			$.post(detailUrl, function(data) {
                               if (data) {
					$.fancybox({
						content:data
					});
					return false;
				}
			});
		});
	})

</script>

                  <p>
<?php if ($this->access_export_xls) :?>
                    <a href="<?php echo $base_url . implode('/', $segments); ?>.html" id="detail_detail-export-xls" title="Exportar" style="font-size: larger;">
                        <img src="<?php echo $base_url ?>ot/assets/images/down.png" title="Exportar" />
				    </a>
<?php endif; ?>
                  </p>
                  <table style="font-size: 0.8em; border-collapse: separate; border-spacing: 1em 0; text-align: center">
<?php if ($with_primas):?>
                      <tr style="background-color: #F0F8FF;">
                        <td>Producto&nbsp;</td>
                        <td>Negocios&nbsp;</td>
                        <td>%&nbsp;</td>
                        <td>Primas Totales&nbsp;</td>
                        <td>%&nbsp;</td>
                        <td>Prima Promedio&nbsp;</td>
                      </tr>

<?php endif ; ?>
<?php foreach ($stats as $key => $value) :
	if ($key && $value['value']):?>
                      <tr>
                        <td style="border: 0px;"><a class="detailed" id="detailed-<?php echo $key ?>" href="javascript:void(0)"><?php echo $value['label'] ?></a></td>
                        <td style="border: 0px;"><?php echo $value['value'] ?></td>
                        <td style="border: 0px;"><?php $percent = $stats[0]['value'] ? round(100 * $value['value'] / $stats[0]['value']) . '%' : $na_value; echo $percent; ?></td>
<?php if ($with_primas):?>
                        <td style="border: 0px; text-align: right">$ <?php echo number_format($value['prima'], 2) ?></td>
                        <td style="border: 0px;"><?php $percent = $stats[0]['prima'] ? round(100 * $value['prima'] / $stats[0]['prima']) . '%' : $na_value; echo $percent; ?></td>
                        <td style="border: 0px; text-align: right"><?php $promedio = $value['value'] ? '$ ' . number_format($value['prima'] / $value['value'], 2) : $na_value; echo $promedio; ?></td>
<?php endif ; ?>
                      </tr>
<?php endif;
endforeach;?>	
                      <tr style="background-color: yellow">
                        <td style="border: 0px;"><a class="detailed" id="detailed-total" href="javascript:void(0)">Total</a></td>
                        <td style="border: 0px;"><?php echo $stats[0]['value'] ?></td>
                        <td style="border: 0px;"><?php $percent = $stats[0]['value'] ? '100%' : $na_value; echo $percent; ?></td>
<?php if ($with_primas):?>
                        <td style="border: 0px;text-align: right">$ <?php echo number_format($stats[0]['prima'], 2) ?></td>
                        <td style="border: 0px;"><?php $percent = $stats[0]['prima'] ? '100%' : $na_value; echo $percent; ?></td>
                        <td style="border: 0px; text-align: right"><?php $promedio =  $stats[0]['value'] ? '$ ' . number_format($stats[0]['prima'] / $stats[0]['value'], 2) : $na_value; echo $promedio;  ?></td>
<?php endif ; ?>
                      </tr>
                  </table>
