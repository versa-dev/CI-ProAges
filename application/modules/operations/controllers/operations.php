<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
class Operations extends CI_Controller {

	public $view = array();

	public $sessions = array();

	public $user_vs_rol = array();

	public $roles_vs_access = array();

	public $access = FALSE;

	public $access_report = FALSE;
	public $access_view = FALSE;
	public $access_create = FALSE;
	public $access_update = FALSE;
	public $access_export_xls = FALSE;
	public $access_all = FALSE;
//	public $access_create_ot = FALSE;

	public $access_activate = FALSE;
	public $access_delete = FALSE;
	
	public $default_period_filter = FALSE;
	public $misc_filters = FALSE;
	public $custom_period_from = FALSE;
	public $custom_period_to = FALSE;
	public $period_filter_for = FALSE;

//	public $agent = FALSE; //??
	public $operation_user = FALSE;
	public $user_id = FALSE;

	private $coordinator_select = '';
	private $inline_js = '';

/** Construct Function **/
/** Setting Load perms **/
	
	public function __construct(){
			
		parent::__construct();
		
		/** Getting Info for logged in User **/
		
		$this->load->model( array( 'usuarios/user', 'roles/rol' ) );

		// Get Session
		$this->sessions = $this->session->userdata('system');
		if( empty( $this->sessions ) and $this->uri->segment(2) != 'login'  )
			redirect( 'usuarios/login', 'refresh' );

		// Get user rol		
		$this->user_vs_rol = $this->rol->user_role( $this->sessions['id'] );

		// Get user rol access
		$this->roles_vs_access = $this->rol->user_roles_vs_access( $this->user_vs_rol );

		// Check permissions to the module and to the functions in the module
		if( !empty( $this->user_vs_rol )  and !empty( $this->roles_vs_access ) )
		{
			foreach( $this->roles_vs_access  as $value )
			{
				if ($value['module_name'] == 'Operations')
				{
					$this->access = true;
					switch ($value['action_name'])
					{
						case 'Ver reporte':
							$this->access_report = TRUE;
						break;
						case 'Ver':
							$this->access_view = TRUE;
						break;
						case 'Crear':
							$this->access_create = TRUE;
						break;
						case 'Editar':
							$this->access_update = TRUE;						
						break;
						case 'Eliminar':
							$this->access_delete = TRUE;						
						break;
						case 'Activar/Desactivar':
							$this->access_activate = TRUE;						
						break;
						case 'Export xls':
							$this->access_export_xls = TRUE;							
						break;
						case 'Ver todos los registros':
							$this->access_all = TRUE;						
						break;						
						default:
						break;
					}
				}
				elseif ($value['module_name'] == 'Orden de trabajo')
				{
					switch ($value['action_name'])
					{
						case 'Crear':
							$this->access_create_ot = TRUE;
						break;
						default:
						break;
					}
				}
			}
		}

		$this->period_filter_for = 'operations';
		$this->default_period_filter = $this->session->userdata('default_period_filter_operations');
		$this->custom_period_from = $this->session->userdata('custom_period_from_operations');
		$this->custom_period_to = $this->session->userdata('custom_period_to_operations');

		$this->misc_filter_name = 'operations_misc_filter';
		$this->misc_filters = $this->session->userdata($this->misc_filter_name);

		$options = array(
			"name" => "general",
			"page" => "operations",
			"general_open" => "<table><thead><tr>",
			"general_close" => "</tr></thead></table>",
			"filter_open" => "<th>",
			"filter_close" => "</th>",
		);
		$this->load->library('custom_filters', $options);
                
		$this->load->helper('filter');
	}

	public function index()
	{
		$this->ot();
	}

	public function ot()
	{
		if ($this->default_period_filter == 5)
			set_filter_period( 2 );

		$other_filters = $this->_init_profile();
		if ( !$this->access_report )
		{	
			$this->session->set_flashdata( 'message', array
			( 
				'type' => false,	
				'message' => 'No tiene permisos para ver el reporte "Orden de trabajo" en la sección "Perfil de operaciones", informe a su administrador para que le otorge los permisos necesarios.'
			));	
			redirect( 'home', 'refresh' );
		}
		$this->load->helper( array('ot/ot', 'filter' ));
		if (count($_POST))
		{
			update_custom_period($this->input->post('cust_period_from'),
				$this->input->post('cust_period_to'), FALSE);
		}
		$base_url = base_url();
		$ramo= 55;

		$gerente_str = '';
		$agente_str = '<option value="">Todos</option>';
		$ramo_tramite_types = array();
		$patent_type_ramo = 0;
		prepare_ot_form($other_filters, $gerente_str, $agente_str, $ramo_tramite_types, $patent_type_ramo);
		$content_data = array(
			'access_all' => $this->access_all,
			'period_fields' => show_period_fields('operations', $ramo),
			'agents' => $agente_str,
			'gerentes' => $gerente_str,
			'export_url' => $base_url . 'operations/report_export/' .  $this->user_id . '.html',
			'coordinator_select' => $this->coordinator_select,
			'other_filters' => $other_filters
			);
		$sub_page_content = $this->load->view('ot/list', $content_data, true);
		$add_js = '
<script type="text/javascript">
	$( document ).ready( function(){ 
		proagesOverview.tramiteTypes = {' . 
implode(', ', $ramo_tramite_types) . '
		};
		$( "#patent-type").html(proagesOverview.tramiteTypes[0]);

		$("#export-xls").bind( "click", function(){
			$("#export-xls-input").val("export_xls");
			$(this).parents("form").submit();
		})

	});
	var submitThisForm = function() {
		proagesOverview.getOts($( "#ot-form").serialize());
		getLinks();
	}
//	getLinks();
</script>
';
		// Config view
		$this->view = array(
			'title' => 'Perfil de operaciones',
			 // Permisions
			'user' => $this->sessions,
			'user_vs_rol' => $this->user_vs_rol,
			'roles_vs_access' => $this->roles_vs_access,
			'css' => array(
				'<link href="' . $base_url . 'ot/assets/style/report.css" rel="stylesheet">',
				'<link href="' . $base_url . 'ot/assets/style/theme.default.css" rel="stylesheet">',
				'<link rel="stylesheet" href="' . $base_url . 'ot/assets/style/main.css">',
				'<link rel="stylesheet" href="'. $base_url .'agent/assets/style/agent.css">', // TO CHECK
				'
<style>
.filterstable {margin-left: 2em; width:80%;}
.filterstable th {text-align: left;}
</style>',
			),
			'scripts' => array(
'
<script type="text/javascript">
	$( document ).ready( function(){ 
		Config.findUrl = "operations/find/' . $this->user_id. '.html";
	});
</script>
',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.tablesorter-2.14.5.js"></script>',
				'<script src="' . $base_url . 'ot/assets/scripts/list_js.js"></script>',
				'<script src="' . $base_url . 'scripts/config.js"></script>',
				'<script src="' . $base_url . 'ot/assets/scripts/overview.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'scripts/select_period.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'operations/assets/scripts/operations.js"></script>',				
				$add_js,
				$this->inline_js
			),
			'content' => 'operations/operation_profile', // View to load
			'message' => $this->session->flashdata('message'),
			'data' => $this->operation_user,
			'sub_page_content' => $sub_page_content,
		);
		// Render view 
		
		$this->load->view( 'index', $this->view );	
	}

	public function get_links()
	{
		$base_url = base_url();
		$result = array(
			$base_url . 'operations/ot.html',
			$base_url . 'operations/statistics/recap.html',					
			);
		$coordinator_name = $this->input->post('coordinator_name');
		if ($coordinator_name)
		{
			$coordinators = $this->_extract_coordinator_name($coordinator_name);
			$result = array(
				$base_url . 'operations/ot/' . $coordinators . '.html',
				$base_url . 'operations/statistics/recap/' . $coordinators . '.html',					
				);
		}
		echo json_encode($result);
	}
// Export the OTs
	public function report_export()
	{
		$this->_init_profile();
		if ( !$this->access_export_xls )
		{	
			$this->session->set_flashdata( 'message', array
			( 
				'type' => false,	
				'message' => 'No tiene permisos para exportar el reporte "Orden de trabajo" en la sección "Perfil de operaciones", informe a su administrador para que le otorge los permisos necesarios.'
			));	
			redirect( 'home', 'refresh' );
		}
		$data_report = array(
			array(
				"Número de OT",
				"Fecha de alta de la OT",
				"Agente - %",
				"Gerente",
				"Ramo",
				"Tipo de trámite",
				"Nombre del asegurado",
				"Estado",
				"Prima",
				"Poliza")
		);

		$data = $this->_read_ots();
		foreach ($data as $value)
		{
			$agents = array();
			$agent_gerentes = array();
			if( !empty( $value['agents'] ) )
			{
				foreach( $value['agents'] as $agent ) 
				{
					if( !empty( $agent['company_name'] ) )
						$agents[] = $agent['company_name'] . ' - '. $agent['percentage'];
					else
						$agents[] = $agent['name'] . ' '. $agent['lastnames']. ' - '. $agent['percentage'];
					$agent_gerentes[] = (!empty($agent['manager_name'])) ? $agent['manager_name'] : '_';
				}
			}
			$agent_value = implode('|', $agents);
			$agent_gerente_value = implode('|', $agent_gerentes);
			$prima = '_';
			if ($value['is_nuevo_negocio'] && ($value['policy_prima'] != 'NULL'))
				$prima = number_format($value['policy_prima'], 2);
			$data_report[] = array(
				$value['uid'],
				$value['creation_date'],
				$agent_value,
				$agent_gerente_value,
				$value['group_name'],
				$value['parent_type_name'],
				$value['asegurado'],
				ucwords(str_replace( 'desactivada', 'en trámite', $value['status_name'])),
				$prima,
				$value['poliza_number'],
			);
		}

		// Export
		$this->load->helper('usuarios/csv');
		$filename = 'operaciones_ot.csv';
		header('Content-Type: application/csv');
		header('Content-Disposition: attachement; filename="$filename"');
	 	array_to_csv($data_report, $filename);
		
		if( is_file( $filename ) )
			echo file_get_contents( $filename );
		if( is_file( $filename ) )
			unlink( $filename );
		exit;
	}

	
	public function statistics()
	{
		$valid_stat_types = array('recap' => 'recap', 'vida' => 1, 'gmm' => 2, 'autos' => 3);
		$stat_type = $this->uri->segment(3);
		if (!isset($valid_stat_types[$stat_type]))
			show_404();
		$stat_type = $valid_stat_types[$stat_type];

		$this->_init_profile(4);
		if ( !$this->access_report )
		{	
			$this->session->set_flashdata( 'message', array
			( 
				'type' => false,	
				'message' => 'No tiene permisos para ver el reporte "Estadística operativa" en la sección "Perfil de operaciones", informe a su administrador para que le otorge los permisos necesarios.'
			));	
			redirect( 'home', 'refresh' );
		}
		$this->load->helper( array('ot/ot', 'filter' ));
		if (count($_POST))
		{
			update_custom_period($this->input->post('cust_period_from'),
				$this->input->post('cust_period_to'), FALSE);
		}
		if ($stat_type == 'recap')
			$stats = $this->_read_stats();
		else
			$stats = $this->_read_stats($stat_type);

		$base_url = base_url();
		$ramo = 55;
		$content_data = array(
			'access_all' => $this->access_all,
			'period_fields' => show_period_fields('operations', $ramo),
			'stats' => $stats,
			'coordinator_select' => $this->coordinator_select
			);
		if ($stat_type == 'recap')
			$sub_page_content = $this->load->view('stats_recap', $content_data, true);
		else
			$sub_page_content = $this->load->view('stats_details', $content_data, true);		

		$add_js = '
<script type="text/javascript">
	$( document ).ready( function(){ 

		$("#export-xls").bind( "click", function(){
			$("#export-xls-input").val("export_xls");
			$(this).parents("form").submit();
		})

		$(".stat-link").bind( "click", function(){
			var linkId = $(this).attr("id");
			linkId = linkId.replace(/_link/, "");
			var detailUrl = "' . $base_url . 
				'operations/stat_details/' . $stat_type . '/" + linkId ' . ' + "/' . $this->user_id . '.html";
			$("#right-col").html("Cargando ...");
			$("#right-col").load(detailUrl, $("#coordinador-name").serializeArray(), function(){
				});
			return false;
		})
	});
	
	var submitThisForm = function() {
		$("#operation-stats-form").submit();
		getLinks();
	}

</script>
';

		// Config view
		$this->view = array(
			'title' => 'Perfil de operaciones',
			 // Permisions
			'user' => $this->sessions,
			'user_vs_rol' => $this->user_vs_rol,
			'roles_vs_access' => $this->roles_vs_access,
			'css' => array(
				'<link rel="stylesheet" href="'.$base_url .'ot/assets/style/jquery.fancybox.css">',
				'<link href="' . $base_url . 'ot/assets/style/report.css" rel="stylesheet">',
				'<link href="' . $base_url . 'ot/assets/style/theme.default.css" rel="stylesheet">',
				'<link rel="stylesheet" href="' . $base_url . 'ot/assets/style/main.css">',
				'<link rel="stylesheet" href="'. $base_url .'agent/assets/style/agent.css">', // TO CHECK
				'<link rel="stylesheet" href="'. $base_url .'operations/assets/style/operations.css">',
			),
			'scripts' => array(

				'<script type="text/javascript" src="'. $base_url .'scripts/jquery.cookie.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'plugins/jquery-validation/jquery.validate.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'plugins/jquery-validation/es_validator.js"></script>',
				'<script src="'. $base_url .'ot/assets/scripts/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.ddslick.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.tablesorter-2.14.5.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.tablesorter.widgets-2.14.5.js"></script>',			
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/main.js"></script>',			
				'<script src="'. $base_url .'scripts/config.js"></script>'	,	
				'<script src="'. $base_url .'ot/assets/scripts/report.js"></script>',
				'<script src="'. $base_url .'ot/assets/scripts/jquery.fancybox.js"></script>',

				'<script src="' . $base_url . 'scripts/config.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'scripts/select_period.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'operations/assets/scripts/operations.js"></script>',
				$add_js,
				$this->inline_js
			),
			'content' => 'operations/operation_profile', // View to load
			'message' => $this->session->flashdata('message'),
			'data' => $this->operation_user,
			'sub_page_content' => $sub_page_content,
		);

		// Render view 
		$this->load->view( 'index', $this->view );
	}

	public function stat_details()
	{
		$stat_type = '';
		$status = '';
		$this->_check_request_for_details($stat_type, $status, 5);
		
		$stats = $this->_read_details($stat_type, $status);
		if (!$stats)
		{
			echo 'Ocurrio un error.';
			exit();
		}
		$data = array('stats' => $stats, 'stat_type' => $stat_type, 'status' => $status);
		$this->load->view( 'details_ramo', $data );
	}

	public function ot_per_prod()
	{
		$stat_type = '';
		$status = '';
		$this->_check_request_for_details($stat_type, $status, 6);
		$prod_segment = $this->uri->segment(5, 0);
		if ($prod_segment && ($prod_segment != 'total'))
			$prod_id = array('products.id' => (int) $prod_segment);
		else
			$prod_id = array();

		$ots = $this->_read_details($stat_type, $status, true, $prod_id);
		$gerente_array = array();
		if ($ots)
		{
			$gerentes = $this->user->getSelectsGerentes2();
			foreach ($gerentes as $gerente)
				$gerente_array[$gerente['id']] = $gerente['name'];
		}
		$this->load->view( 'ot_list', array('data' => $ots, 'gerentes' => $gerente_array) );
	}

	private function _check_request_for_details(&$stat_type, &$status, $user_seg_num)
	{
		$valid_stat_types = array(1 => 1, 2 => 2, 3 => 3);
		$valid_status = array(
			'tramite' => 'tramite', 'pagada' => 'pagada',
			'canceladas' => 'canceladas', 'NTU' => 'NTU',
			'pendientes_pago' => 'pendientes_pago', 'activadas' => 'activadas',
			'todos' => 'todos');
		$stat_type = $this->uri->segment(3, 0);
		$status = $this->uri->segment(4, 0);
		if (!isset($valid_stat_types[$stat_type]) || !isset($valid_status[$status]))
		{
			echo 'Ocurrio un error.';
			exit();
		}

		$this->_init_profile($user_seg_num);
		if ( !$this->access_report )
		{
			echo 'No tiene permisos para ver el reporte "Estadística operativa" en la sección "Perfil de operaciones", informe a su administrador para que le otorge los permisos necesarios.';
			exit();
		}
	}

// Export stat recap
	public function stat_recap_export()
	{
		$valid_stat_types = array('recap' => 'recap', 'vida' => 1, 'gmm' => 2, 'autos' => 3);
		$stat_type = $this->uri->segment(3);
		if (!isset($valid_stat_types[$stat_type]))
			show_404();

		$filename = 'operaciones_estadistica_' . $stat_type . '.csv';
		$stat_type = $valid_stat_types[$stat_type];

		$this->_init_profile(4);

		if ( !$this->access_export_xls )
		{	
			$this->session->set_flashdata( 'message', array
			( 
				'type' => false,	
				'message' => 'No tiene permisos para exportar el reporte "Orden de trabajo" en la sección "Perfil de operaciones", informe a su administrador para que le otorge los permisos necesarios.'
			));	
			redirect( 'home', 'refresh' );
		}

		$na_value = 'N/D';
		if ($stat_type == 'recap')
		{
			$stats = $this->_read_stats();
			$data_report = array(
				array(
					"Ordenes de Trabajo Procesadas",
					$stats['recap-left']
				),
			);
			$per_tramite = array(1 => 'Vida', 2 => 'Gastos Médicos', 3 => 'Automóviles');
			foreach ($per_tramite as $tramite_key => $tramite_value)
			{
				$data_report[] = array(
					$tramite_value,
					$stats['per_ramo_tramite'][$tramite_key]['all'],
					$stats['recap-left'] ? round(100 * $stats['per_ramo_tramite'][$tramite_key]['all'] / $stats['recap-left']) . '%' : $na_value,
				);
				foreach ($stats['per_ramo_tramite'][$tramite_key] as $key => $value)
				{
					if ($key != 'all')
					{
						$data_report[] = array(
							$value['label'],
							$value['value'], 
							$stats['recap-left'] ? round(100 * $value['value'] / $stats['recap-left']) . '%' : $na_value
						);
					}
				}
				$data_report[] = array('');
			}

			$per_status = array('tramite' => 'En trámite',
				'terminada' => 'Terminadas',
				'canceladas' => 'Canceladas',
				'activadas' => 'Activadas');
			foreach ($per_status as $key_status => $value_status)
			{
				$data_report[] = array(
					$value_status,
					$stats['per_status'][$key_status],
					$stats['recap-middle'] ? round(100 * $stats['per_status'][$key_status] / $stats['recap-middle']) . '%' : $na_value
				);
			}
			$data_report[] = array(
				'Ordenes de Trabajo Procesadas',
				$stats['recap-middle'],
				$stats['recap-middle'] ? '100%' : $na_value
				);

			$data_report[] = array('');
			$data_report[] = array(
				"RESPONSABLES DE ACTIVACIÓN Y/O CANCELACIÓN",
				);

			foreach ($stats['per_responsible'] as  $value)
			{
				$data_report[] = array(
					$value['label'],
					$value['value'],
					$stats['recap-right'] ? round(100 * $value['value'] / $stats['recap-right']) . '%' : $na_value
				);
			}
			$data_report[] = array(
				'Ordenes de Activadas / canceladas',
				$stats['recap-right'],
				$stats['recap-right'] ? '100%' : $na_value
			);
		}
		else
		{
			$stats = $this->_read_stats($stat_type);
			$data_report = array(
				array(
					'Nuevos de Negocios ' . ucfirst($this->uri->rsegment(3))
				),
			);
			$per_status = array('tramite' => 'En trámite',
				'pagada' => 'Pagados',
				'canceladas' => 'Cancelados',
				'NTU' => 'NTU',
				'pendientes_pago' => 'Pendientes de pago',
				'activadas' => 'Activados');
			$total = 0;
			foreach ($per_status as $key_status => $value_status)
			{
				$data_report[] = array(
					$value_status,
					$stats['per_status'][$key_status],
				);
				$total += $stats['per_status'][$key_status];
			}
			$data_report[] = array(
				'Trámites de nuevos negocios:',
				$total,
				);
		}

		// Export
		$this->load->helper('usuarios/csv');

		header('Content-Type: application/csv');
		header('Content-Disposition: attachement; filename="$filename"');
	 	array_to_csv($data_report, $filename);
		
		if( is_file( $filename ) )
			echo file_get_contents( $filename );
		if( is_file( $filename ) )
			unlink( $filename );
		exit;
	}

	// Export stat details
	public function stat_detail_export()
	{
		$valid_stat_types = array(1 => 1, 2 => 2, 3 => 3);
		$valid_status = array(
			'tramite' => 'tramite', 'pagada' => 'pagada',
			'pendientes_pago' => 'pendientes_pago', 'activadas' => 'activadas',			
			'canceladas' => 'canceladas', 'NTU' => 'NTU',
			'todos' => 'todos'
			);
		$stat_type = $this->uri->segment(3, 0);

		$status = $this->uri->segment(4, 0);		
		if (!isset($valid_stat_types[$stat_type]) || !isset($valid_status[$status]))
		{
			echo 'Ocurrio un error.';
			exit();
		}

		$this->_init_profile(5);
		if ( !$this->access_report )
		{
			echo 'No tiene permisos para ver el reporte "Estadística operativa" en la sección "Perfil de operaciones", informe a su administrador para que le otorge los permisos necesarios.';
			exit();
		}
		$stats = $this->_read_details($stat_type, $status);
		if (!$stats)
		{
			echo 'Ocurrio un error.';
			exit();
		}
		$data_report = array();
		$na_value = 'N/D';
		foreach ($stats as $key => $value)
		{
			if ($key && $value['value'])
			{
				$data_report[] = array(
					$value['label'],
					$value['value'],
                    $stats[0]['value'] ? round(100 * $value['value'] / $stats[0]['value']) . '%' : $na_value
				);
			}
		}
		$data_report[] = array(
			'Total',
			$stats[0]['value'],
			$stats[0]['value'] ? '100%' : $na_value
			);

		$types_text = array(1 => 'vida', 2 => 'gmm', 3 => 'autos');
		$filename = 'operaciones_estadistica_' . $types_text[$stat_type] . '_' . $status . '_detalles.csv';

		// Export
		$this->load->helper('usuarios/csv');
		header('Content-Type: application/csv');
		header('Content-Disposition: attachement; filename="$filename"');
	 	array_to_csv($data_report, $filename);
		
		if( is_file( $filename ) )
			echo file_get_contents( $filename );
		if( is_file( $filename ) )
			unlink( $filename );
		exit;
	}

	private function _extract_coordinator_name($coordinator_name)
	{
		$to_check_array2 = array();
		$to_check_array1 = explode("\n", $coordinator_name);
		$to_replace = array(']', "\n", "\r");
		foreach ($to_check_array1 as $value)
		{
			$pieces = explode( ' [ID: ', $value);
			if (isset($pieces[1]))
			{
				$pieces[1] = (int)str_replace($to_replace, '', $pieces[1]);
				if (!isset($to_check_array2[$pieces[1]]))
					$to_check_array2[] = $pieces[1];
			}
		}
		return implode('_', $to_check_array2);
	}
	
	private function _init_profile($segment = 3)
	{
		$other_filters = array();
		get_generic_filter($other_filters, array());

		$this->load->model( 'ot/work_order' );
		if (count($_POST) && ($coordinator_name = $this->input->post('coordinator_name')) !== FALSE)
			$this->user_id = $this->_extract_coordinator_name($coordinator_name);
		else
		{
			$in_segment = $this->uri->rsegment($segment);
			if ($in_segment !== FALSE)
				$this->user_id = $in_segment;
			elseif (isset($other_filters['coordinators'] ) && is_string($other_filters['coordinators']))
				$this->user_id = $other_filters['coordinators'];
		}

		if ($this->user_id === FALSE)
			redirect( 'operations/index/' . $this->sessions['id'], 'refresh' );
		if (strlen($this->user_id))
		{
			$ot_owners = explode('_', $this->user_id);
			foreach ($ot_owners as $owner_key => $owner_value)
				$ot_owners[$owner_key] = (int) $owner_value;
			$this->user_id = implode('_', $ot_owners);
			if (count($ot_owners) == 1)
			{
				// Allow user to access his/her profile page
				$this->access = $this->access || ($this->user_id == $this->sessions['id']);
				if ($this->access)
				{
					$user = $this->work_order->generic_get('users', array('id' => $ot_owners[0]), 1);
					if (!$user)
						show_404();
					$this->operation_user = $user[0];
					$this->operation_user->displayed_user_name = $this->operation_user->company_name ? 
						$this->operation_user->company_name : 
						$this->operation_user->name . ' ' . $this->operation_user->lastnames;
				}
			}
		}

		if ( !$this->access )
		{
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Operaciones" o para ingresar los operaciones del usuario. Informe a su administrador.'
			));
			redirect( 'home', 'refresh' );
		}
		$other_filters['coordinators'] = $this->user_id;
		generic_set_report_filter( $other_filters, array() );

		$selected_coordinators = explode('_', $this->user_id);
		$selected_coordinator_text	= '';

		$roles = $this->rol->get_user_roles_where(array(
			'modules.name' => 'Orden de trabajo', 'actions.name' => 'Crear'));
		foreach ($roles as $role)
			$where_in[] = $role->user_role_id;
		$where_in = array_unique(array_merge($where_in, array('2')));
		$coordinators_in_db = $this->user->get_users_with_role($where_in);
		$coordinators_array = array();
		foreach ($coordinators_in_db as $value_c)
		{
			$displayed_name = $value_c['company_name'] ?
				$value_c['company_name'] :
				$value_c['name'] . ' ' .  $value_c['lastnames'];
			$coordinators_array[$value_c['id']] = $displayed_name;
		}
		$coordinators_multi = array();
		foreach ( $coordinators_array as $key => $value )
		{
			$coordinators_multi[] = "\n'$value [ID: $key]'";
			if (in_array($key, $selected_coordinators)) 
				$selected_coordinator_text .= $value .  " [ID: $key]\n";
		}
		if (!$selected_coordinator_text && $this->operation_user)
			$selected_coordinator_text = $this->operation_user->displayed_user_name . ' [ID: ' . $this->sessions['id'] . "]\n";

		$this->inline_js = 
'
<script type="text/javascript">
	$( document ).ready( function(){

		var coordinatorList = [' . implode(',', $coordinators_multi) . '
		];

		function split( val ) {
			return val.split( /\n\s*/ );
		}
		function extractLast( term ) {
			return split( term ).pop();
		}
		$( ".submit-form").bind("click", function( event ) {
			submitThisForm();
		})
		$( "#clear-coordinator-filter").bind("click", function( event ) {
			$( "#coordinador-name" ).val("");
			submitThisForm();
		})
		$( "#coordinador-name" )
		// don\'t navigate away from the field on tab when selecting an item
			.bind( "keydown", function( event ) {
				if ( event.keyCode === $.ui.keyCode.TAB &&
					$( this ).data( "ui-autocomplete" ).menu.active ) {
					event.preventDefault();
				}
			})
			.autocomplete({
				minLength: 0,
				source: function( request, response ) {
					// delegate back to autocomplete, but extract the last term
					response( $.ui.autocomplete.filter(
						coordinatorList, extractLast( request.term ) ) );
				},			
				focus: function() {
					// prevent value inserted on focus
					return false;
				},
				select: function( event, ui ) {
					var terms = split( this.value );
					// remove the current input
					terms.pop();
					// add the selected item
					terms.push( ui.item.value );
					// add placeholder to get the comma-and-space at the end
					terms.push( "" );
					this.value = terms.join( "\n" );
					submitThisForm();
					return false;
				}
			})
	});
</script>
';
		$this->coordinator_select = $this->load->view('coordinator_select', array(
			'coordinators' => $coordinators_in_db,
			'selected_coordinator_text' => $selected_coordinator_text,
			), TRUE);
		$this->custom_filters->set_array_defaults($other_filters);
		$this->custom_filters->set_current_filters($other_filters);
		return $other_filters;
	}

// List OTs
// Inspired from the code of agent/find:
	public function find()
	{
		// If is not ajax request redirect
		if	( !$this->input->is_ajax_request() )
			redirect( '/', 'refresh' );
		$this->_init_profile();
		$data = $this->_read_ots();
		$view_data = array('data' => $data);
//		$this->access_create = $this->access_create_ot;
		$this->load->view('ot/list_render', $view_data);
	}

	private function _read_ots()
	{
		// Load Helpers
		$this->load->helper( array( 'ot/ot', 'ot/date', 'filter' ) );
		if (count($_POST))
		{
			update_custom_period($this->input->post('cust_period_from'),
				$this->input->post('cust_period_to'), FALSE);
		}
		$other_filters = array('coordinators' => $this->user_id);
		$data = get_ot_data($other_filters, $this->access_all);
		return $data;
	}

	private function _read_stats($ramo = NULL)
	{
		$this->load->helper('filter');
		$periodo = get_filter_period();
		if (( ($posted_periodo = $this->input->post('periodo')) !== FALSE) && 
			$this->form_validation->is_natural_no_zero($posted_periodo) &&
			($posted_periodo <= 5))
		{
			set_filter_period($posted_periodo);
			$periodo = $posted_periodo;
		}
		$this->work_order->init_operations($this->user_id, $periodo, $ramo);
		$add_where = $ramo ? array('t2.name' => 'NUEVO NEGOCIO') : NULL;
		$result = $this->work_order->operation_stats($ramo, $add_where);
		return $result;
	}

	private function _read_details($ramo = NULL, $status = NULL, $get_ot_list = FALSE, $prod_id = array())
	{
		$this->load->helper('filter');
		$periodo = get_filter_period();
		$this->work_order->init_operations($this->user_id, $periodo, $ramo);
		if ($prod_id)
			$this->work_order->add_operation_where($prod_id);
		$result = $this->work_order->operation_detailed($ramo, $status, false, $get_ot_list);
		return $result;		
	}
}

/* End of file operations.php */
/* Location: ./application/modules/operations/controllers/operations.php */