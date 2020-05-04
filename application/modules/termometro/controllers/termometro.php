<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ini_set('display_errors', 'On');
error_reporting(E_ALL);
/** 
 * Termometro de Ventas - Vista Detalle General
 * 
 * @author     Jesus Castilla & José Gilberto Pérez Molina
 * Date:       December, 2018
 * Locaion:    Veracruz, Mexico
 * Mail:       jesuscv1821@gmail.com - jgilbertopmolina@gmail.com
 */

class Termometro extends CI_Controller {
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
	public $access_activate = FALSE;
	public $access_delete = FALSE;
	public $default_period_filter = FALSE;
	public $misc_filters = FALSE;
	public $custom_period_from = FALSE;
	public $custom_period_to = FALSE;
	public $period_filter_for = FALSE;
	public $operation_user = FALSE;
	public $user_id = FALSE;
	private $coordinator_select = '';
	private $inline_js = '';

	/** Construct Function **/
	/** Setting Load perms **/
	public function __construct()
	{
		parent::__construct();

		/** Getting Info for logged in User **/
		$this->load->model( array( 'usuarios/user', 'roles/rol' ) );
					
		// Get Session
		$this->sessions = $this->session->userdata('system');

		// Get user rol		
		$this->user_vs_rol = $this->rol->user_role( $this->sessions['id'] );

		// Get user rol access
		if (!empty($this->user_vs_rol))
			$this->roles_vs_access = $this->rol->user_roles_vs_access( $this->user_vs_rol );

		// Check permissions to the module and to the functions in the module
		if( !empty( $this->user_vs_rol )  and !empty( $this->roles_vs_access ) )
		{
			foreach( $this->roles_vs_access  as $value )
			{
				if ($value['module_name'] == 'Reporte de produccion')
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
			}
		}

		// Check if there's an user currently logged in, if it's not, then redirect to login page.
		if( empty( $this->sessions ) and $this->uri->segment(2) != 'login'  ) redirect( 'usuarios/login', 'refresh' );

		$this->period_filter_for = 'ventas';
		$this->default_period_filter = $this->session->userdata('default_period_filter_'.$this->period_filter_for);
		$this->custom_period_from = $this->session->userdata('custom_period_from_'.$this->period_filter_for);
		$this->custom_period_to = $this->session->userdata('custom_period_to_'.$this->period_filter_for);

		$this->misc_filter_name = $this->period_filter_for.'_misc_filter';
		$this->misc_filters = $this->session->userdata($this->misc_filter_name);
		$options = array(
			"name" => "general",
			"page" => "requests",
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
		$this->start_thermometer();	
	}

	/****** FUNCIONES PRINCIPALES DEL TERMOMETRO *******/
	public function start_thermometer()
	{
		// Getting filters
		$other_filters = $this->_init_profile();

		//Loading Models
		$this->load->model( 'ot/work_order');
		$this->load->model( 'termometro/termometro_model');

		//Loading helpers
		$this->load->helper('render');
		$this->load->helper('date');

		//Configure filters of the report
		$base_url = base_url();
		$months = getMonths();
		$agents = makeDropdown($this->user->getAgentsArray(), "id", "name");

		//Create year dropdown
		$periods = $this->termometro_model->get_global_years();

		/* SET DE LOS DATOS PRINCIPALES */
		$actual_year = date("Y");
		$year1 = '';
		$year2 = '';
		if($actual_year == $other_filters['periodo'])
		{
			$year1 = $other_filters["periodo"].'-'.date('m-d');
			$year2 = $other_filters["periodo"] - 1 .'-'.date('m-d'); 
		}
		else
		{
			$year1 = $other_filters["periodo"].'-12-31';
			$year2 = $other_filters["periodo"] - 1 .'-12-31'; 
		}
		
		$indicators_y2 = $this->termometro_model->get_all_indicators($year2,$other_filters["periodo"] - 1);
		$indicators_y1 = $this->termometro_model->get_all_indicators($year1,$other_filters["periodo"]);

		/* FECHA DE LA ULTIMA VEZ QUE SE ACTUALIZO EL SELO */
		$last_update = $indicators_y1['last_update'];

		/* DATOS DE LA VISTA Y TABLAS INTEGRADAS A LAS GRAFICAS */
		$data_view_grl_y1  = $indicators_y1['grl_view'];
		$data_view_grl_y2  = $indicators_y2['grl_view'];
		$data_view_vida_y1 = $indicators_y1['vida_view'];
		$data_view_vida_y2 = $indicators_y2['vida_view'];
		$data_view_gmm_y1  = $indicators_y1['gmm_view'];
		$data_view_gmm_y2  = $indicators_y2['gmm_view'];
		$data_lost 		   = $indicators_y1['lost_data'];

        /* DATOS DE LAS GRAFICAS DE LA VISTA*/
        //Arrays de datos
        $data_js_grl_y1  = $indicators_y1['grl_js'];
		$data_js_vida_y1 = $indicators_y1['vida_js'];
		$data_js_gmm_y1  = $indicators_y1['gmm_js'];
		//Arrays de nombres de datos de las grafuicas
		$this->load->helper('agent/generations');
		$names_ramo 	= array("Vida", "GMM");
		$names_congreso = array("Oro", "Platino", "Diamante", "Consejo");
		$names_concept  = array("Venta Nueva","Renovacion");
		$names_generations = array();
		foreach (getGenerationList() as $key => $value) {
		    array_push($names_generations, $value['title']);
        }
        //Array de colores
        $colors_generation = array("#0e606b", "#179381", "#f2844d", "#6bf738", "#f25d52");
        //Arrays de cada una de las tablas y valores
        $values_generation 		= $data_js_grl_y1['ingresos_generacion'];
        $values_congreso 		= $data_js_grl_y1['agentes_congresistas_tipo'];
        $values_ramo 			= $data_js_grl_y1['distribucion_ingreso_ramo'];
		$values_generation_vida = $data_js_vida_y1['ingresos_generacion'];
		$values_concept_vida 	= $data_js_vida_y1['ingresos_tipo_venta'];
		$values_generation_gmm  = $data_js_gmm_y1['ingresos_generacion'];
		$values_concept_gmm 	= $data_js_gmm_y1['ingresos_tipo_venta'];

        /* DATOS DE LA VISTA */
        //Los datos que necesita el archivo termometro.js necesitan estar en un script js
        $add_js = '
			<script type="text/javascript">
				var colors_generation 		= '.json_encode($colors_generation).'
				var names_generations 		= '.json_encode($names_generations).'
				var names_congreso 	  		= '.json_encode($names_congreso).'
				var names_concept 	  		= '.json_encode($names_concept).'
				var names_ramo 		  		= '.json_encode($names_ramo).'
				var values_ramo 	  		= '.json_encode($values_ramo).'
				var values_congreso   		= '.json_encode($values_congreso).'
				var values_generation 		= '.json_encode($values_generation).'
				var values_generation_vida  = '.json_encode($values_generation_vida).'
				var values_generation_gmm 	= '.json_encode($values_generation_gmm).'
				var values_concept_vida 	= '.json_encode($values_concept_vida).'
				var values_concept_gmm 		= '.json_encode($values_concept_gmm).'
			</script>
		';
		//auxiliar for fancybox
		$add_fancybox = '
			<script type="text/javascript">
				function popup_detail(params) {
					$.fancybox.showLoading();
					$.post("'.$base_url.'termometro/popup_detail.html", jQuery.param(params) + "&" + $("#form").serialize(), function(data) {
						if (data) {
							$.fancybox({
								content:data
							});
							return false;
						}
					});
				}
			</script>
		' ;

		//Los datos que la vista necesita se deben enviar de la siguiente manera
        $content_data = array(
        	'data_general_year1' => $data_view_grl_y1,
			'data_general_year2' => $data_view_grl_y2,
			'data_gmm_year1'     => $data_view_gmm_y1,
			'data_gmm_year2'     => $data_view_gmm_y2,
        	'data_vida_year1'    => $data_view_vida_y1,
        	'data_vida_year2'    => $data_view_vida_y2,
        	'periods'			 => $periods,
        	'other_filters'		 => $other_filters,
        	'last_update' 		 => $last_update,
        	'lost_data' 		 => $data_lost
        );

        $this->view = array(
			'title' => 'Termometro de Ventas',
			'user' => $this->sessions,
			'user_vs_rol' => $this->user_vs_rol,
			'roles_vs_access' => $this->roles_vs_access,
			'css' => array(
				'<link href="' . $base_url . 'ot/assets/style/theme.default.css" rel="stylesheet">',
				'<link rel="stylesheet" href="' . $base_url . 'ot/assets/style/main.css">',
				'<link rel="stylesheet" href="'. $base_url .'agent/assets/style/agent.css">',
				'<link rel="stylesheet" href="'. $base_url .'ot/assets/style/jquery.fancybox.css">',
				'<link rel="stylesheet" href="'. $base_url .'/style/style-report.css?'.time().'">',
				'<link rel="stylesheet" href="'. $base_url .'/style/print-reset.css?'.time().'">',
			),
			'scripts' => array(
				'<script type="text/javascript" src="'. $base_url .'scripts/jquery.cookie.js"></script>',
				'<script src="'. $base_url .'ot/assets/scripts/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>',
				'<script src="' . $base_url . 'scripts/config.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'operations/assets/scripts/jquery.canvasjs.min.js"></script>',
				'<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.tablesorter-2.14.5.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.tablesorter.widgets-2.14.5.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.fancybox.js"></script>',
				'<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/randomcolor/0.5.2/randomColor.min.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'scripts/report-utilities.js?'.time().'"></script>',
				'<script type="text/javascript" src="'. $base_url .'termometro/assets/scripts/termometro.js?'.time().'"></script>',
				'<script type="text/javascript" src="'. $base_url .'termometro/assets/scripts/jquery.number.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'termometro/assets/scripts/jquery.number.min.js"></script>',
				'<script src="https://use.fontawesome.com/884297e135.js"></script>',
				'<link rel="stylesheet" href="'. $base_url .'ot/assets/style/theme.default.css" rel="stylesheet">',
				'<link rel="stylesheet" href="'. $base_url .'ot/assets/style/report.css" rel="stylesheet">',
				'<style>
					.filterstable {margin-left: 2em; width:80%;}
					.filterstable th {text-align: left;}
				</style>',
				$add_js,
				$add_fancybox	
			),
			'content' => 'termometro/main',
			'data' => $content_data
		);
		$this->load->view( 'index', $this->view );	
	}
	public function detail_vida()
	{
		//Configure filters of the report
		$base_url = base_url();

		// Getting filters
		$other_filters = $this->_init_profile();
		if(array_key_exists('id', $_GET))
		{
			$other_filters['agent'] = intval($_GET['id']);
		}

		//Loading Models
		$this->load->model( 'ot/work_order');
		$this->load->model( 'termometro/termometro_model');
		$this->load->model( 'termometro/termometro_vida_model');

		//Create year dropdown
		$periods = array();
        $minYear = 2018;
        $auxYear = date("Y");
        do
        {
        	$periods[$auxYear] = $auxYear;
        	$auxYear--;
        }
		while ($auxYear >= $minYear);

		$values_agent = array();
		$first_agent = 0;
		//Getting agent info
		if(isset($other_filters['agent']) && $other_filters['agent'] != null)
		{
			$first_agent = $other_filters['agent'];
			$values_agent = $this->termometro_model->get_vida_agent_details($other_filters['periodo'], $first_agent);
		} 

		//loading autocomplete 
        $this->load->helper('filter');
        $agent_array = $this->user->getAgents( FALSE );
        $inline_js = '<!-- jQuery -->
		<!-- jQuery UI -->
		<!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
		<script type="text/javascript">' . $this->termometro_model->autocomplete_js_vida($agent_array);
		
		

		//Values of indicators for View
		$content_view = array(
			'values_first_agent' 	=> $values_agent,
			'periods'				=> $periods,
			'other_filters'		 	=> $other_filters
		);

		//auxiliar values for js
		$add_js = '
			<script type="text/javascript">
				var agent_id 	 = '.json_encode($first_agent).'
				var year 	 	 = '.json_encode($other_filters['periodo']).'
				var agent_values = '.json_encode($values_agent).' 
			</script>
		';

		//auxiliar for fancybox
		$add_fancybox = '
			<script type="text/javascript">
				function popup_detail_vida(params) {
					$.fancybox.showLoading();
					$.post("'.$base_url.'termometro/popup_detail_vida.html", jQuery.param(params) + "&" + $("#form").serialize(), function(data) {
						if (data) {
							$.fancybox({
								content:data
							});
							return false;
						}
					});
				}
			</script>
		' ;

		//Values of indicators for graphs

		$filter_css = '  <style>
		.ui-autocomplete {
		  max-height: 100px;
		  overflow-y: auto;
		  /* prevent horizontal scrollbar */
		  overflow-x: hidden;
		}
		
		* html .ui-autocomplete {
		  height: 100px;
		}
		</style>';
		$this->view = array(
			'title' => 'Termometro de Ventas',
			'user' => $this->sessions,
			'user_vs_rol' => $this->user_vs_rol,
			'roles_vs_access' => $this->roles_vs_access,
			'css' => array(
				'<link href="' . $base_url . 'ot/assets/style/theme.default.css" rel="stylesheet">',
				'<link rel="stylesheet" href="' . $base_url . 'ot/assets/style/main.css">',
				'<link rel="stylesheet" href="'. $base_url .'agent/assets/style/agent.css">',
				'<link rel="stylesheet" href="'. $base_url .'ot/assets/style/jquery.fancybox.css">',
				'<link rel="stylesheet" href="'. $base_url .'/style/style-report.css?'.time().'">',
				'<link rel="stylesheet" href="'. $base_url .'/style/print-reset.css?'.time().'">',
				$filter_css,
			),
			'scripts' => array(
				'<script type="text/javascript" src="'. $base_url .'scripts/jquery.cookie.js"></script>',
				'<script src="'. $base_url .'ot/assets/scripts/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>',
				'<script src="' . $base_url . 'scripts/config.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'operations/assets/scripts/jquery.canvasjs.min.js"></script>',
				'<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.tablesorter-2.14.5.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.tablesorter.widgets-2.14.5.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.fancybox.js"></script>',
				'<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/randomcolor/0.5.2/randomColor.min.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'scripts/report-utilities.js?'.time().'"></script>',
				'<script type="text/javascript" src="'. $base_url .'termometro/assets/scripts/termometro_vida.js?'.time().'"></script>',
				'<script src="https://use.fontawesome.com/884297e135.js"></script>'	,
				$inline_js,
				$add_js,
				$add_fancybox
			),
			'content' => 'termometro/detail_vida',
			'data' => $content_view
		);
		$this->load->view( 'index', $this->view );	
	}
	public function detail_gmm()
	{
		//Configure filters of the report
		$base_url = base_url();

		// Getting filters
		$other_filters = $this->_init_profile();
		if(array_key_exists('id', $_GET))
		{
			$other_filters['agent'] = intval($_GET['id']);
		}

		//Loading Models
		$this->load->model( 'ot/work_order');
		$this->load->model( 'termometro/termometro_model');

		//Create year dropdown
		$periods = array();
        $minYear = 2018;
        $auxYear = date("Y");
        do
        {
        	$periods[$auxYear] = $auxYear;
        	$auxYear--;
        }
		while ($auxYear >= $minYear);

		$first_agent = 0;
		$values_agent = array();
		//Getting agent info
		if(isset($other_filters['agent']) && $other_filters['agent'] != null)
		{
			$first_agent = $other_filters['agent'];
			//Get individual indicators
			$values_agent = $this->termometro_model->get_gmm_agent_details($other_filters['periodo'], $first_agent);
		} 
		//loading autocomplete 
        $this->load->helper('filter');
        $agent_array = $this->user->getAgents( FALSE );
        $inline_js = '<!-- jQuery -->
		<!-- jQuery UI -->
		<!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
		<script type="text/javascript">' . $this->termometro_model->autocomplete_js_vida($agent_array);

		//Values of indicators for View
		$content_view = array(
			'first_agent'			=> $first_agent,
			'periods'				=> $periods,
			'values_first_agent' 	=> $values_agent,
			'other_filters'		 	=> $other_filters
		);

		//extra values
		$add_js = '
		<script type="text/javascript">
			var agent_id 	 = '.json_encode($first_agent).'
			var year 	 	 = '.json_encode($other_filters['periodo']).'
			var agent_values = '.json_encode($values_agent).'
		</script>';

		$filter_css = '  <style>
		.ui-autocomplete {
		  max-height: 100px;
		  overflow-y: auto;
		  /* prevent horizontal scrollbar */
		  overflow-x: hidden;
		}
		
		* html .ui-autocomplete {
		  height: 100px;
		}
		</style>';

		$add_fancybox = '
			<script type="text/javascript">
				function popup_detail_vida(params) {
					$.fancybox.showLoading();
					$.post("'.$base_url.'termometro/popup_detail_gmm.html", jQuery.param(params) + "&" + $("#form").serialize(), function(data) {
						if (data) {
							$.fancybox({
								content:data
							});
							return false;
						}
					});
				}
			</script>
		' ;

		$this->view = array(
			'title' => 'Termometro de Ventas',
			'user' => $this->sessions,
			'user_vs_rol' => $this->user_vs_rol,
			'roles_vs_access' => $this->roles_vs_access,
			'css' => array(
				'<link href="' . $base_url . 'ot/assets/style/theme.default.css" rel="stylesheet">',
				'<link rel="stylesheet" href="' . $base_url . 'ot/assets/style/main.css">',
				'<link rel="stylesheet" href="'. $base_url .'agent/assets/style/agent.css">',
				'<link rel="stylesheet" href="'. $base_url .'ot/assets/style/jquery.fancybox.css">',
				'<link rel="stylesheet" href="'. $base_url .'/style/style-report.css?'.time().'">',
				'<link rel="stylesheet" href="'. $base_url .'/style/print-reset.css?'.time().'">',
				$filter_css,
			),
			'scripts' => array(
				'<script type="text/javascript" src="'. $base_url .'scripts/jquery.cookie.js"></script>',
				'<script src="'. $base_url .'ot/assets/scripts/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>',
				'<script src="' . $base_url . 'scripts/config.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'operations/assets/scripts/jquery.canvasjs.min.js"></script>',
				'<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.tablesorter-2.14.5.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.tablesorter.widgets-2.14.5.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.fancybox.js"></script>',
				'<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/randomcolor/0.5.2/randomColor.min.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'scripts/report-utilities.js?'.time().'"></script>',
				'<script type="text/javascript" src="'. $base_url .'termometro/assets/scripts/termometro_gmm.js?'.time().'"></script>',
				'<script type="text/javascript" src="'. $base_url .'termometro/assets/scripts/jquery.number.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'termometro/assets/scripts/jquery.number.min.js"></script>',
				'<script src="https://use.fontawesome.com/884297e135.js"></script>'	,
				$inline_js,
				$add_js,
				$add_fancybox
			),
			'content' => 'termometro/detail_gmm',
			'data' => $content_view
		);
		$this->load->view( 'index', $this->view );	
	}

	/****** FUNCIONES DE SIMULACION DEL TERMOMETRO *******/
	public function simulate_vida()
	{
		$this->load->model('termometro/termometro_model');
		
		$result = $this->termometro_model->get_vida_agent_simulation($_POST);
		echo $result;
	}
	public function simulate_gmm()
	{
		$this->load->model('termometro/termometro_model');
		$result = $this->termometro_model->get_gmm_agent_simulation($_POST);
		echo $result;
	}

	/****** FUNCIONES DE EXPORTAR A PDF DEL TERMOMETRO *******/
	public function show_pdf_vida()
	{
		// Load termometro model
		$this->load->model('termometro/termometro_model');
		// Load filters
		$other_filters = $this->_init_profile();
		// Load PDF view 
		$pdf_view = $this->termometro_model->export_pdf_vida($other_filters['periodo'], $other_filters['agent']);
	}
	public function show_pdf_vida_simulation()
	{
		// Load termometro model
		$this->load->model('termometro/termometro_model');
		// Load filters
		$other_filters = $this->_init_profile();
		// Simulate data
		$array_vi = $this->termometro_model->get_vida_agent_simulation((array) json_decode($_POST['data_test']));
		// Load PDF view 
		$pdf_view = $this->termometro_model->export_pdf_vida($other_filters['periodo'], $other_filters['agent'],$array_vi);
	}
	public function show_pdf_gmm()
	{
		// Load termometro model
		$this->load->model('termometro/termometro_model');
		// Load filters
		$other_filters = $this->_init_profile();
		// Load PDF view 
		$pdf_view = $this->termometro_model->export_pdf_gmm($other_filters['periodo'], $other_filters['agent']);
	}
	public function show_pdf_gmm_simulation()
	{
		$this->load->model('termometro/termometro_model');
		// Load filters
		$other_filters = $this->_init_profile();
		// Simulate data
		$array_vi = $this->termometro_model->get_gmm_agent_simulation((array) json_decode($_POST['data_test']));
		// Load PDF view 
		$pdf_view = $this->termometro_model->export_pdf_gmm($other_filters['periodo'], $other_filters['agent'],$array_vi);
	}


	/****** FUNCIONES EXTRAS DEL TERMOMETRO *******/
	public function popup_detail()
	{
		$other_filters = $this->_init_profile();
		$this->load->model( 'termometro/termometro_model');
		$request_type = $this->input->post('type');
		$data = array();
		switch ( $request_type )
		{
			case 'grl_ingresos_acum':
				$data['values'] = $this->termometro_model->get_data_popup_grl($other_filters['periodo'], array('vi_grl_income','gmm_grl_income'), array('Nombre', 'Ingresos Vida', 'Ingresos GMM', 'Total'),false, false,false,false);
				break;
			case 'grl_prod_ini':
				$data['values'] = $this->termometro_model->get_data_popup_grl($other_filters['periodo'], array('vi_primas_ubi','gmm_primas_ubi'), array('Nombre', 'Producción Vida', 'Producción GMM', 'Total'),false, true, false, false);
				break;
			case 'grl_agent_recruit':
				$data['values'] = $this->termometro_model->get_data_popup_grl($other_filters['periodo'], array(), array('Nombre', 'Fecha de conexión'),false, false, true, false);
				break;
			case 'grl_agent_recruit_prod':
				$data['values'] = $this->termometro_model->get_data_popup_grl($other_filters['periodo'], array('vi_primas_ubi','gmm_primas_ubi'), array('Nombre', 'Producción Total'),false , false, false, true);
				break;
			case 'grl_agent_congress':
				$data['values'] = $this->termometro_model->get_data_popup_grl($other_filters['periodo'], array('vi_congreso','gmm_congreso'), array('Nombre', 'Congreso Vida', 'Congreso GMM'),true, false);
				break;
			case 'grl_agent_bonus':
				$data['values'] = $this->termometro_model->get_data_popup_grl($other_filters['periodo'], array('vi_primas_ubi','gmm_primas_ubi'), array('Nombre', 'Bono'),false, false, false, false, true);
				break;
			case 'grl_ingresos_acum_vida':
				$data['values'] = $this->termometro_model->get_data_popup($other_filters['periodo'], 'vi_grl_income', array('Nombre','Ingresos Vida'), 2 ,true, false, false);
				break;
			case 'grl_primas_pagos_ini_vida':
				$data['values'] = $this->termometro_model->get_grl_detail_primas_pagos_ini_vida($other_filters['periodo']);
				break;
			case 'grl_primas_pagos_vida':
				$data['values'] = $this->termometro_model->get_data_popup($other_filters['periodo'], 'vi_primas_pag', array('Nombre','Primas Para Pagar'), 2 ,true, false, false);
				break;	
			case 'grl_primas_ubi_vida':
				$data['values'] = $this->termometro_model->get_data_popup($other_filters['periodo'], 'vi_primas_ubi', array('Nombre','Primas Para Ubicar'), 2 ,true, false, false);
				break;	
			case 'grl_prod_ini_vida':
				$data['values'] = $this->termometro_model->get_data_popup($other_filters['periodo'], 'vi_primas_ubi', array('Nombre','Ingresos Vida'), 2 ,true, false, true);
				break;
			case 'grl_agent_congress_vida':
				$data['values'] = $this->termometro_model->get_data_popup($other_filters['periodo'], 'vi_congreso', array('Nombre','Primas Para Ubicar'), 0 ,true, true, false);;
				break;
			case 'grl_agent_venta_nueva_vida':
				$data['values'] = $this->termometro_model->get_data_popup($other_filters['periodo'], 'vi_new_income', array('Nombre','Ingresos Venta Nueva'), 2 ,true, false, false);
				break;	
			case 'grl_negocios_pai_vida':
				$data['values'] = $this->termometro_model->get_data_popup($other_filters['periodo'], 'vi_pai_buss', array('Nombre','Negocios PAI'), 0 ,true, false, false);
				break;
			case 'grl_ptos_stanging_vida':
				$data['values'] = $this->termometro_model->get_data_popup($other_filters['periodo'], 'vi_ptos_stand', array('Nombre','Puntos Standing'), 0 ,true, false, false);
				break;
			case 'grl_ingresos_acumulados_gmm':
				$data['values'] = $this->termometro_model->get_data_popup($other_filters['periodo'], 'gmm_grl_income', array('Nombre','Ingresos Vida'), 2 ,false, false, false);
				break;
			case 'grl_primas_pagos_gmm':
				$data['values'] = $this->termometro_model->get_data_popup($other_filters['periodo'], 'gmm_primas_pag', array('Nombre','Primas Para Pagar'), 2 ,false, false, false);
				break;
			case 'grl_primas_ubi_gmm':
				$data['values'] = $this->termometro_model->get_data_popup($other_filters['periodo'], 'gmm_primas_ubi', array('Nombre','Primas Para Ubicar'), 2 ,false, false, false);
				break;
			case 'grl_prod_ini_gmm':
				$data['values'] = $this->termometro_model->get_data_popup($other_filters['periodo'], 'gmm_primas_ubi', array('Nombre','Produccion Primas Para Ubicar'), 2 ,false, false, true);
				break;
			case 'grl_agent_congress_gmm':
				$data['values'] = $this->termometro_model->get_data_popup($other_filters['periodo'], 'gmm_congreso', array('Nombre','Congreso'), 0,false, true, false);
				break;
			case 'grl_agent_venta_nueva_gmm':
				$data['values'] = $this->termometro_model->get_data_popup($other_filters['periodo'], 'gmm_new_income', array('Nombre','Ingresos Venta Nueva'), 2,false, false, false);
				break;
			case 'grl_num_asegurados_gmm':
				$data['values'] = $this->termometro_model->get_data_popup($other_filters['periodo'], 'gmm_num_buss', array('Nombre','Numero de Asegurados'), 0, false, false, false);
				break;
			case 'grl_agentes_prod_gmm':
				$data['values'] = $this->termometro_model->get_data_popup($other_filters['periodo'], 'gmm_primas_ubi', array('Nombre','Primas para ubicar'), 0, false, false, false, true);
				break;
			default:
			exit('Ocurrio un error. Consulte a su administrador.');
			break;
		}
		$this->load->view('detail_popup', $data);
	}
	public function popup_detail_vida()
	{
		$this->load->model( 'termometro/termometro_model');
		$other_filters  = $this->_init_profile();
		$request_type   = $this->input->post('type');
		$request_period = $this->input->post('periodo');
		$agent_id 		= $this->input->post('id');
		$data['values'] = $this->termometro_model->popup_detail_vida($agent_id, $request_period, $request_type);
		$this->load->view('detail_popup', $data);
	}
	public function popup_detail_gmm()
	{
		$this->load->model( 'termometro/termometro_model');
		$other_filters  = $this->_init_profile();
		$request_type   = $this->input->post('type');
		$request_period = $this->input->post('periodo');
		$agent_id 		= $this->input->post('id');
		$data['values'] = $this->termometro_model->popup_detail_gmm($agent_id, $request_period, $request_type);
		$this->load->view('detail_popup', $data);
	}
	public function _init_profile()
	{
		$this->load->helper('ot/ot');
		//Generic Filters
		$other_filters = array(
			"periodo" => '',
			"ramo" => '',
			"agent" => '',
			"product" => '',
			"prime_type" => '',
			"agent_name" => '',
			"period_trimester" => ''
		);
		$this->custom_filters->set_array_defaults($other_filters);
		if(!empty($this->misc_filters))
			$other_filters = array_merge($other_filters, $this->misc_filters);

		if(empty($other_filters["ramo"]))
			$other_filters["ramo"] = 1;

		if(empty($other_filters["periodo"]))
			$other_filters["periodo"] = date("Y");

		if(empty($other_filters["prime_type"]))
			$other_filters["prime_type"] = "amount";

		//Filters
		if($this->input->post()){
			update_custom_period($this->input->post('cust_period_from'),
				$this->input->post('cust_period_to'), FALSE);

			if ( isset($_POST['periodo']) && $this->form_validation->is_natural_no_zero($_POST['periodo']) && ($_POST['periodo'] >= 2000) )
				$other_filters["periodo"] = $_POST["periodo"];

			if ( isset($_POST['prime_type']))
				$other_filters["prime_type"] = $_POST["prime_type"];

			if ( isset($_POST['ramo']) && (($this->form_validation->is_natural_no_zero($_POST['ramo']) && ($_POST['ramo'] <= 2)) || (($_POST['ramo']) === '')) )
				$other_filters['ramo'] = $_POST['ramo'];

			if (isset($_POST['product']) && ($this->form_validation->is_natural_no_zero($_POST['product']) || 
				$_POST['product'] === ''))
				$other_filters['product'] = $_POST['product'];

			if (isset($_POST['agent_name']) && $_POST['agent_name'] !=  '')
			{
				$other_filters['agent_name'] = $_POST['agent_name'];
				preg_match("/ID: [0-9]+/", $_POST['agent_name'], $match);
				$other_filters['agent'] = substr($match[0],4);
			}
				
				
		}
		
		$this->custom_filters->set_filters_to_save($other_filters);
		$this->custom_filters->set_current_filters($other_filters);
		generic_set_report_filter( $other_filters, array() );
		return $other_filters;
	}
}
/* End of file settings.php */
/* Location: ./application/modules/termometro/controllers/termometro.php */
