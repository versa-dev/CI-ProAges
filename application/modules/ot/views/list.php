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
$agents = str_replace('<option value="">Seleccione</option>', '<option value="">Todos</option>', $agents);
$this->load->helper('filter');
$estado_selected = array(
	'activadas' => '',
	'tramite' => '',
	'terminada' => '',
	'canceladas' => '',
	'NTU' => '',
	'pagada' => '',
	'todas' => '');
if (isset($other_filters['work_order_status_id']) && 
	isset($estado_selected[$other_filters['work_order_status_id']]))
	$estado_selected[$other_filters['work_order_status_id']] = ' selected="selected"';
else
	$estado_selected['todas'] = ' selected="selected"';

$agent_profile_page = ($this->uri->segment(1) == 'agent');
$operation_profile_page = ($this->uri->segment(1) == 'operations');
$director_profile_page = ($this->uri->segment(1) == 'director');
$selected_period = get_filter_period();
if (!$agent_profile_page && !$operation_profile_page && !$director_profile_page):
?>
<div>
    <ul class="breadcrumb">
        <li>
            <a href="<?php echo base_url() ?>">Admin</a> <span class="divider">/</span>
        </li>
        <li>
           <a href="<?php echo base_url() ?>ot.html">Orden de trabajo</a> <span class="divider">/</span>
        </li>
        <li>
            Overview
        </li>
    </ul>
</div>

<div class="row-fluid sortable">		
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2></h2>
            <div class="box-icon">
               <a href="<?php echo base_url() ?>ot/create.html" class="btn btn-round"><i class="icon-plus"></i></a>
            </div>
        </div>
        <div class="box-content">

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
                          <strong>Error: </strong> <?php  echo $message['message']; // Show Dinamical message error ?>
                    </div>
                <?php endif; ?>
			<?php endif; ?>
               
            <div class="row">
            	<div class="span1"></div>
            	<div class="span7">
<?php
	$class_mios = 'btn-primary';
	$class_todos = 'btn-link';
	$todas_mias_value = 'mios';
	if (isset($other_filters['user']))
	{
		if ($other_filters['user'] !== 'mios')
		{
			$class_mios = 'btn-link';
			$class_todos = 'btn-primary';
			$todas_mias_value = 'todos';			
		}
	}
	if( $access_all == true ):
					
?>
                    <a href="javascript:void(0);" class="btn find <?php echo $class_todos ?>" id="todos">Todas</a>
<?php endif; ?>
                    <a href="javascript:void(0);" class="btn find <?php echo $class_mios ?>" id="mios">Mias</a>
                </div>

                <div class="span2"></div>
                <div class="span1"></div>

            </div>
<?php endif; ?>

            <div class="row"><br />
                <form id="ot-form" method="post" <?php if ($operation_profile_page) echo 'action="' . $export_url . '"' ?>>
<?php if (!$agent_profile_page && !$operation_profile_page && !$director_profile_page): ?>
                  <input class="filter-field" type="hidden" name="user" id="todas-mias" value="<?php echo $todas_mias_value ?>" />
<?php else: ?>
                  <input class="filter-field" type="hidden" name="user" id="todas-mias" value="todos" />
<?php endif; ?>

                  <table class="filterstable">
                    <thead>
                      <tr>
					    <th colspan="6">
<?php if ($operation_profile_page || $director_profile_page): ?>
					    <input type="hidden" value="" id="export-xls-input" name="export_xls_input" disabled="disabled" />
<?php if (isset($coordinator_select)): ?>
<div class="row" style="padding-left: 2em">
	<div class="span2">
Coordinadores&nbsp;:
	</div>
	<div class="span10">
<?php echo $coordinator_select ?>
	</div>
</div>	
<?php endif; ?>	
<div class="row" style="padding-left: 2em">	
	<div class="span2">
	Número&nbsp;:
	</div>
	<div class="span4">
<?php $numero = isset($other_filters['id']) ?  $other_filters['id'] : '';?>	

        <input value="<?php echo $numero ?>" class="filter-field" type="text" id="id" name="id" title="Pulse la tecla Tab para validar un número a buscar" />
	</div>
	<div class="span3">	
<?php if ($this->access_create) :?>
                <a href="<?php echo $base_url ?>ot/create.html" style="font-size: larger;" target="_blank" class="btn btn-link" title="Crear">
                    <i class="icon-plus"></i></a>
<?php endif; ?>
	</div>
	<div class="span3" style="text-align: right">	
<?php if ($this->access_export_xls) :?>
                <a href="javascript:void(0);" id="export-xls" title="Exportar" style="font-size: larger;">
                    <img src="<?php echo $base_url ?>ot/assets/images/down.png" title="Exportar" />
				</a>
<?php endif; ?>
	</div>
</div>
<?php else:
	$numero = isset($other_filters['id']) ?  $other_filters['id'] : '';
?>
					  Número :
					  <input value="<?php echo $numero ?>" class="filter-field" type="text" id="id" name="id" title="Pulse la tecla Tab para validar un número a buscar" />
<?php endif; ?>
					  </th>
                      </tr>
                      <tr>					  
					    <th>
Período :<br />
<?php echo $period_fields ?>
<select id="periodo_form" name="periodo" style="width: 175px" title="Período">
	  <option value="<?php echo $selected_period ?>"></option>
</select>
<input type="hidden" value="<?php echo $selected_period ?>" id="periodo" name="query[periodo]" />
					    </th>
					    <th>Ramo :<br />
						  <select class="filter-field" id="ramo" name="ramo">
<?php
	$selected = array('selected="selected"', '', '', '');
	if (isset($other_filters['ramo']))
	{
		$selected[0] = '';
		$selected[$other_filters['ramo']] = 'selected="selected"';
	}
?>
						    <option value="" <?php echo $selected [0] ?>>Todos</option>
						    <option value="1" <?php echo $selected [1] ?> >Vida</option>
						    <option value="2" <?php echo $selected [2] ?>>GMM</option>
						    <option value="3" <?php echo $selected [3] ?>>Autos</option>
						  </select>
					    </th>

					    <th <?php if ($agent_profile_page) echo 'style="display: none"' ?>>Gerente :<br />
						  <select class="filter-field" id="gerente" name="gerente">
						  <option value="" <?php if ($agent_profile_page) echo 'selected="selected"' ?>>Todos</option>
						  <?php echo $gerentes ?>

						  </select>
					    </th>
					    <th <?php if ($agent_profile_page) echo 'style="display: none"' ?>>Agente :<br />
						  <select class="filter-field" id="agent" name="agent">
						  <?php echo $agents ?>

						  </select>
					    </th>
					    <th>Tipo de trámite :<br />
						  <select class="filter-field" id="patent-type" name="patent_type" style="width: 10em">
						    <option value="" selected="selected">Todos</option>
						  </select>
					    </th>
					    <th>Estado :<br />
                          <select class="filter-field" id="work_order_status_id" name="work_order_status_id">
                            <option value="activadas" <?php echo $estado_selected['activadas'] ?>>Activadas</option>
                            <option value="tramite" <?php echo $estado_selected['tramite'] ?>>En trámite</option>
                            <option value="terminada" <?php echo $estado_selected['terminada'] ?>>Terminadas</option>
                            <option value="canceladas" <?php echo $estado_selected['canceladas'] ?>>Canceladas</option>
                            <option value="NTU" <?php echo $estado_selected['NTU'] ?>>Póliza NTU</option>
                            <option value="pagada" <?php echo $estado_selected['pagada'] ?>>Pagadas</option>
                            <option value="todas" <?php echo $estado_selected['todas'] ?>>Todas</option>
                          </select>
					    </th>
                      <?php render_custom_filters() ?>
                      </tr>
                    </thead>

                  </table>
                </form>
            </div>				
            <div id="loading"></div>

<div id="ot-list">
            <table class="sortable altrowstable tablesorter" id="sorter" style="width:100%;">
              <colgroup>
<?php if ($operation_profile_page): ?> 
				<col width="14%" />
				<col width="10%" />
				<col width="10%" />
				<col width="10%" />
<?php else: ?> 
				<col width="14%" />
				<col width="15%" />
				<col width="15%" />
<?php endif ?> 
				<col width="6%" />
				<col width="17%" />
				<col width="15%" />
				<col width="8%" />
				<col width="10%" />
              </colgroup>
              <thead class="head">
				<tr>
                      <th>Número de OT&nbsp;</th>
                      <th>Fecha de alta de la OT&nbsp;</th> 
                      <th>Agente - %&nbsp;</th>
<?php if ($operation_profile_page): ?>
                      <th>Gerente&nbsp;</th>
<?php endif ?>
                      <th>Ramo&nbsp;</th>
                      <th>Tipo de trámite&nbsp;</th>
                      <th>Nombre del asegurado&nbsp;</th>
                      <th>Estado&nbsp;</th>
                      <th>Prima&nbsp;</th>
                  </tr>
              </thead>   
              <tbody class="tbody" id="data">

              </tbody>
          </table>
</div>
<?php if (!$agent_profile_page && !$operation_profile_page && !$director_profile_page): ?>

        </div><!-- box-content -->

    </div><!--/span-->

</div><!--/row-->
<?php endif ?>
