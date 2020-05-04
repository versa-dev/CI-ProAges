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
if ($data):
$totals = array(
	'solicitudes_meta' => 0,
	'solicitudes_ingresadas' => 0,
	'negocios_meta' => 0,
	'negocios_pagados' => 0,
	'primas_meta' => 0,
	'primas_pagadas' => 0,
);
$total_negocios = 0;
$total_primas_iniciales = 0;
?>
<table class="sortable altrowstable tablesorter" id="sorter-meta" style="width:70%;">
  <colgroup>
    <col width="30%" />
    <col width="10%" />
    <col width="10%" />
    <col width="10%" />
    <col width="10%" />
    <col width="15%" />
    <col width="15%" />
  </colgroup>
  <thead class="head">
    <tr>
      <th><?php echo $data[0]['name'];?></th> 
      <th><?php echo $data[0]['solicitudes_meta'];?></th>
      <th><?php echo $data[0]['solicitudes_ingresadas'];?></th>
      <th><?php echo $data[0]['negocios_meta'];?></th>
      <th><?php echo $data[0]['negocios_pagados'];?></th>
      <th><?php echo $data[0]['primas_meta'];?></th>
      <th><?php echo $data[0]['primas_pagadas'];?></th>	  
    </tr>
  </thead>
<?php
	$count = count($data);
	if ($count > 1):
?>
  <tbody class="tbody" id="data">
<?php for ($i = 1; $i < $count; $i++):
	$total_negocios += (int)$data[$i]['negocios'];
	$total_primas_iniciales += (float)$data[$i]['primas_iniciales'];

	$totals['solicitudes_meta'] += $data[$i]['solicitudes_meta'];
	$totals['solicitudes_ingresadas'] += $data[$i]['solicitudes_ingresadas'];
	$totals['negocios_meta'] += $data[$i]['negocios_meta'];
	$totals['negocios_pagados'] += $data[$i]['negocios_pagados'];
	$totals['primas_meta'] += $data[$i]['primas_meta'];
	$totals['primas_pagadas'] += $data[$i]['primas_pagadas'];
?>
    <tr>
      <td>
<?php if (($ramo == 1) || ($ramo == 2)) :?>
	  <a href="#" class="toggle"><?php echo $data[$i]['name'];?></a>
<?php else:
	echo $data[$i]['name'];
endif; ?>	  
      </td>
      <td style="padding-right: 1em; text-align: right"><?php echo number_format($data[$i]['solicitudes_meta'], 0);?></td>
      <td style="padding-right: 1em; text-align: right"><?php echo $data[$i]['solicitudes_ingresadas'];?></td>
      <td style="padding-right: 1em; text-align: right"><?php echo number_format($data[$i]['negocios_meta'], 0);?></td>
      <td style="padding-right: 1em; text-align: right"><?php echo $data[$i]['negocios_pagados'];?></td>
      <td style="padding-right: 1em; text-align: right"><?php echo number_format($data[$i]['primas_meta'], 2);?></td>
      <td style="padding-right: 1em; text-align: right"><?php echo number_format($data[$i]['primas_pagadas'], 2);?></td>
    </tr>
    <tr class="tablesorter-childRow">
      <td colspan="7" style="background-color: #E0E0E0; padding-left: 1.5em">
<?php if (($ramo == 1) || ($ramo == 2)) :
		$link_meta = $base_url . 'director/meta/' . $data[$i]['id'] . '/' . $ramo . '.html';
		$link_simulator = $base_url . 'director/simulate/' . $data[$i]['id'] . '/' . $ramo . '.html';
		echo '<a href="' . $link_meta . '">Modificar meta</a> | <a href="' . $link_simulator . '">Simular ingreso</a>';
endif; ?>
	  </td>
    </tr>
<?php endfor; ?>
  </tbody>
  <tbody style="font-size: 1.2em; font-weight: bold">
	<tr>
      <td>TOTALES</td> 
      <td style="padding-right: 1em; text-align: right"><?php echo number_format($totals['solicitudes_meta'], 0);?></td>
      <td style="padding-right: 1em; text-align: right"><?php echo $totals['solicitudes_ingresadas'];?></td>
      <td style="padding-right: 1em; text-align: right"><?php echo number_format($totals['negocios_meta'], 0);?></td>
      <td style="padding-right: 1em; text-align: right"><?php echo $totals['negocios_pagados'];?></td>
      <td style="padding-right: 1em; text-align: right"><?php echo number_format($totals['primas_meta'], 2);?></td>
      <td style="padding-right: 1em; text-align: right"><?php echo number_format($totals['primas_pagadas'], 2);?></td>
	</tr>
  </tbody>
<?php endif; ?>
 </table>
 <?php endif; ?>
