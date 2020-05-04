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
$uri_segments = $this->uri->rsegment_array();
$product_group_id = isset($uri_segments[4]) ? $uri_segments[4] : 1;
$without_ramo = $uri_segments;
unset($without_ramo[4]);
$without_ramo = base_url() . implode('/', $without_ramo);

$is_director_page = ($uri_segments[1] == 'director');
$is_simulator_page = ($uri_segments[1] == 'simulator');
//$is_meta_page = $is_simulator_page && isset($uri_segments[2]) &&
$is_meta_page = isset($uri_segments[2]) &&
	(($uri_segments[2] == 'index') || ($uri_segments[2] == 'getConfigMeta') ||
	($uri_segments[2] == 'print_index') || ($uri_segments[2] == 'meta'));

//$is_simulate_page = $is_simulator_page && isset($uri_segments[2]) &&
$is_simulate_page = isset($uri_segments[2]) &&
	(($uri_segments[2] == 'simulate') || ($uri_segments[2] == 'print_simulate') ||
	($uri_segments[2] == 'simulator'));

$markup = $is_director_page ? 'h5' : 'h3';

$switch_url = '';
//if ($is_simulator_page)
//{
	$default_month = date('n');
	$default_year = $selected_year;
	$switch_segments = $uri_segments;
	if ($switch_segments[2] == 'simulate')
	{
		$switch_segments[2] = $is_simulator_page ? 'index' : 'meta';
		$switch_text = 'Ver meta';
	}
	else
	{
		$switch_segments[2] = 'simulate';
		$switch_text = 'Ver simulator';
	}	
	$switch_url = base_url() . implode('/', $switch_segments) . '.html';
//}
$selected_period = 0;
?>

<?php if (!$for_print && !$is_director_page): ?>
<div class="row-fluid sortable">		
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <p></p>
            <div class="box-icon">
            </div>
        </div>
        <div class="box-content">
<?php endif; ?>

<?php if ($switch_url): ?>
<div style="text-align: right; margin-right: 3em" class="screen-view">
<a href="<?php echo $switch_url; ?>" class="btn btn-primary">
<?php echo $switch_text ?></a>
</div>
<?php endif ?>

<?php if (!$is_director_page): ?>
		  <?php // Show Messages ?>
          <?php if( isset( $message['type'] ) ): ?>
              <?php if( $message['type'] == true ): ?>
                  <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <img src="<?php echo base_url() ?>images/true.png" width="20" height="20" />
                        <strong>Listo: </strong> <?php  echo $message['message']; // Show Dinamical message Success ?>
                  </div>
              <?php endif; ?>

              <?php if( $message['type'] == false ): ?>
                  <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <img src="<?php echo base_url() ?>images/false.png" width="20" height="20" />
                        <strong>Error: </strong> <?php  echo $message['message']; ?>
                  </div>
              <?php endif; ?>
          <?php endif; ?>
<?php endif; ?>
          <?php
//			$uri_segments = $this->uri->rsegment_array();
			$uri_segments[1] = 'simulator';
			if ($uri_segments[2] == 'index')
				$uri_segments[2] = 'print_index';
			elseif ($uri_segments[2] == 'simulate')
				$uri_segments[2] = 'print_simulate';
			if (!$for_print) {
				$link_attributes = 'class="btn btn-primary print-preview" id="print-button" target="_blank"';
				$link_text = 'Vista previa de impresión';
			}
			else {
				$link_attributes = 'class="btn btn-primary" id="print-button"';
				$link_text = 'Imprimir';
				if (!$print_meta)
				{
					if ($uri_segments[2] != 'print_simulate')
						$uri_segments[2] = 'print_index_simulator';
				}
			}
			if (isset($selected_period) && isset($selected_year))
			{
				$uri_segments[5] = $selected_period;
				$uri_segments[6] = $selected_year;
			}

			if (!$users[0]['name']) $users[0]['name'] = $users[0]['company_name'];
		  ?> 
          <p style="float: right; padding-top: 10px; margin-right: 3em"><?php echo anchor(implode('/', $uri_segments), $link_text, $link_attributes); ?></p>
          <<?php echo $markup ?>>Simulador de metas de <?php echo ucfirst($ramo);?> para <?php echo $users[0]['name'] . " " . $users[0]['lastnames']?></<?php echo $markup ?>>

          <a href="<?php echo $without_ramo ?>/1.html" class="links-menu btn btn-link" id="vida" style="color:#<?php if ($product_group_id == 1) echo '000'; else echo '06F' ?>">Vida</a>
          <a href="<?php echo $without_ramo ?>/2.html" class="links-menu btn btn-link" id="gmm" style="color:#<?php if ($product_group_id == 2) echo '000'; else echo '06F' ?>">GMM</a>

          <form action="" method="post" id="form">

            <div class="row" style="margin: 0 1em; <?php if ($for_print) echo 'display: none' ?>">
              <input type="hidden" name="period" id="period" value="0" />
              <select name="displayed_period" class="input-medium" id="displayed-period" <?php if ($is_simulate_page) echo 'style= "display: none"' ?>>
                <option <?php if ($selected_period == $default_month) echo 'selected'; ?> value="<?php echo $default_month; ?>">Mensual</option>
<?php if ($ramo == 'gmm'): ?>
                <option <?php if ($selected_period == '121') echo 'selected'; ?> value="121">Cuatrimestre 1</option>
                <option <?php if ($selected_period == '122') echo 'selected'; ?>  value="122">Cuatrimestre 2</option>
                <option <?php if ($selected_period == '123') echo 'selected'; ?> value="123">Cuatrimestre 3</option>
<?php else: ?>
                <option <?php if ($selected_period == '111') echo 'selected'; ?> value="111">Trimestre 1</option>
                <option <?php if ($selected_period == '112') echo 'selected'; ?> value="112">Trimestre 2</option>
                <option <?php if ($selected_period == '113') echo 'selected'; ?> value="113">Trimestre 3</option>
                <option <?php if ($selected_period == '114') echo 'selected'; ?> value="114">Trimestre 4</option>
<?php endif ?>
                <option <?php if ($selected_period == '0') echo 'selected'; ?> value="0">Anual</option>
              </select>
              &nbsp;
              <select name="year" id="year" class="input-small auto-submit">
<?php
$min_year = -15;
$max_year = 15;
if ($is_simulate_page)
{
	$min_year = 0;
	$max_year = 2;
}
for ($i = $min_year; $i < $max_year; $i++): 
	$year_option = date('Y', mktime(0, 0, 0, date('m'),  date('d'),  date('Y') + $i));
	$selected = ($year_option == $default_year) ? 'selected' : ''
?>
	<option id="year-option-<?php echo $year_option ?>" value="<?php echo $year_option ?>" <?php echo $selected ?>><?php echo $year_option ?></option>

<?php endfor ?>
              </select>
              &nbsp;
              <span title="Escriba el nombre del agente que desea buscar y selecciónelo de la lista que aparece. Puede buscar más posteriormente en la siguiente línea.">
                <textarea placeholder="AGENTES" id="agent-name" name="agent_name" rows="1" class="input-xlarge select4" style="min-width: 250px; height: 2.2em"><?php echo $other_filters['agent_name']; ?></textarea>
			  </span>
            </div>

          <input type="hidden" id="ramo" name="ramo" value="<?php echo $product_group_id; ?>" />    
          <input type="hidden" id="userid" name="userid" value="<?php echo $userid ?>" />    
          <input type="hidden" id="agent_id" name="agent_id" value="<?php echo $agentid ?>" />  
          <input type="hidden" id="id" name="id" value="<?php if( isset( $data[0]['id'] ) ) echo $data[0]['id']; else echo 0; ?>" />    
        <!-- <img src="<?php echo base_url() ?>images/distribucion.png" /> -->
         
         <div class="row" style="margin-right: 3em">
<?php if ($is_simulate_page) :?>
         <div class="span12 simulator" id="simulator-section" style="margin-left:2em;">
            <?php
				$data_view = array('data' => array(), 'meta_data' => array());
				if ( isset( $data[0]['data'] ) )
					$data_view['data'] = $data[0]['data'];
				if ( isset( $meta_data[0]['data'] ) )
					$data_view['meta_data'] = $meta_data[0]['data'];
				$this->load->view( 'simulator_new', $data_view );
			?>
         </div>
<?php endif; ?>
<?php if ($is_meta_page) :?>
          <div class="span12 metas" id="meta-section">
            <?php
				if( isset( $config ) )
					$dataview = array( 'config' => $config );
				else
					$dataview = array();  
				$this->load->view( 'metas', array( $dataview ) );
			?>
         </div>
<?php endif; ?>
         </div>  
           
           </form>                                 
<?php if (!$for_print && !$is_director_page): ?>	                           
        </div><!-- /.box-content -->
    </div><!--/span-->

</div><!--/row-->
<?php endif; ?>