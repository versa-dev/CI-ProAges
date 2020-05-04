<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class solicitudes extends CI_Controller {
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
				if ($value['module_name'] == 'Solicitudes')
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

		$this->period_filter_for = 'requests';
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
		$this->summary();
	}

	public function summary($tab = "graficos", $orderby = "primas"){
		$Available_tabs = array("graficos", "reporte");
		//Url Validations
		if(!in_array($tab, $Available_tabs))
			$tab = "graficos";

		//Validation of order
		$Available_orderbys = array("primas", "requests");
		if(!in_array($orderby, $Available_orderbys))
			$orderby = "primas";
		switch ($orderby) {
			case 'primas':
				$orderby = "prima";
				$orderhash = "requests";
				$orderlabel = "S";
				break;
			case 'requests':
				$orderby = "conteo";
				$orderhash = "primas";
				$orderlabel = "P";
				break;
		}
		if ( !$this->access_report )
		{	
			$this->session->set_flashdata( 'message', array
			( 
				'type' => false,	
				'message' => 'No tiene permisos para ver el reporte "Resumen de solicitacion" en la sección "Solicitudes", informe a su administrador para que le otorge los permisos necesarios.'
			));	
			redirect( 'home', 'refresh' );
		}

		if ($this->default_period_filter == 5)
			set_filter_period( 2 );

		// Getting filters
		$other_filters = $this->_init_profile();
		// Aditional configuration
		$other_filters["nuevos_negocios"] = 1;
		$other_filters["periodo"] = get_filter_period();
		
		$this->load->model( 'ot/work_order');
		$this->load->model( 'users/user');

		//General Report work orders
		$work_orders_general = $this->work_order->getWorkOrdersGroupBy($other_filters);
		//Formating names
		foreach ($work_orders_general as $i => $order){
			if(empty($order["name"]) && empty($order["lastnames"]))
				$work_orders_general[$i]["name"] = $order["company_name"];
			$work_orders_general[$i]["lastnames"].= " - <b>". $order["percentage"]."</b>";
		}

		//Agents Report
		$work_orders_agents = $this->work_order->getWorkOrdersGroupByAgents($other_filters, $orderby);
		foreach ($work_orders_agents as $i => $order){
			$work_orders_agents[$i]["name"] = empty($order["name"]) && empty($order["lastnames"]) ? $order["company_name"] :$order["name"]." ".$order["lastnames"];
		}
		$work_orders_data = json_encode($work_orders_agents);

		//Status Report
		$work_orders_status = $this->work_order->getWorkOrdersGroupByStatus($other_filters);
		$work_orders_status_data = json_encode($work_orders_status);

		//Products Report
		$work_orders_products = $this->work_order->getWorkOrdersGroupByProducts($other_filters);
		//Calculate Average Prima per product
		foreach ($work_orders_products as $i => $row) 
			$work_orders_products[$i]["avgPrima"] = ($row["conteo"] != 0) ? $row["prima"] / $row["conteo"] : 0;
		$work_orders_products_data = json_encode($work_orders_products);

		//Getting Generation group
		$work_orders_generations = $this->work_order->getWorkOrdersGroupByGeneracion($other_filters);
		$work_orders_generations_data = json_encode($work_orders_generations);
		$ramo = 1;
		if (isset($this->query_filters['query']) && isset($this->query_filters['query']['ramo']))
			$ramo = $this->query_filters['query']['ramo'];
		$imported_date = $this->work_order->getLastPaymentImportedDate($ramo);


		$base_url = base_url();
		$ramo= 55;
		$ramos = makeDropdown($this->work_order->getProductsGroups(), "id", "name", FALSE);
		$products = makeDropdown($this->work_order->getProducts(), "id", "name");

		//Remove NTU, Excedido and Cancelada
		$args = array( "not_in" => array("id" => array(2, 3, 10)) );
		$status = makeDropdown($this->work_order->getStatusArray($args), "name", "name");
		$agents = makeDropdown($this->user->getAgentsArray(), "id", "name");
		$gerentes = makeDropdown($this->user->getSelectsGerentes2(),"id","name");
		unset($ramos[3]);

		$this->load->helper('sort');
		$this->load->helper('render');

		//Create Indicators array
		$indicators = array(
			"solicitudes" => 0,
			"primas_solicitadas" => 0,
			"prima_promedio" => 0,
			"agentes" => 0
		);

		//Calculate solicitudes and primas_solicitadas indicator
		foreach ($work_orders_agents as $order) {
			$indicators["solicitudes"] += $order["conteo"];
			$indicators["primas_solicitadas"] += $order["prima"];
		}
		$indicators["prima_promedio"] = $indicators["solicitudes"] > 0 ? $indicators["primas_solicitadas"] / $indicators["solicitudes"] : 0;
		$indicators["agentes"] = count($work_orders_agents);

		/* 
			Calculate previous year same period
		 */
		$this->load->helper('date');
		//Auxiliar variables
		$from = $this->custom_period_from;
		$to = $this->custom_period_to;
		$period = $other_filters["periodo"];

		//Get last year date
		$this->custom_period_from = sameDayLastYear($this->custom_period_from);
		$this->custom_period_to = sameDayLastYear($this->custom_period_to);
		$other_filters["periodo"] = 4;

		//Configuration agent group
		$args = array(
			"select" => "users.company_name, users.name, users.lastnames, policies_vs_users.percentage, agents.id",
			"sum" => "policies.prima",
			"by" => "users.company_name, agents.id, policies_vs_users.percentage",
			"order" => "$orderby desc",
		);
		$work_orders_agents_prev = $this->work_order->getWorkOrdersGroupBy($other_filters, $args);
		$prev_indicators = array(
			"solicitudes" => 0,
			"primas_solicitadas" => 0,
			"prima_promedio" => 0,
			"agentes" => 0
		);

		//Calculate solicitudes and primas_solicitadas indicator
		foreach ($work_orders_agents_prev as $order) {
			$prev_indicators["solicitudes"] += $order["conteo"];
			$prev_indicators["primas_solicitadas"] += $order["prima"];
		}
		$prev_indicators["prima_promedio"] = $prev_indicators["solicitudes"] > 0 ? $prev_indicators["primas_solicitadas"] / $prev_indicators["solicitudes"] : 0;
		$prev_indicators["agentes"] = count($work_orders_agents_prev);

		$comparative = array(
			"solicitudes" => $prev_indicators["solicitudes"] > 0 ? ($indicators["solicitudes"] - $prev_indicators["solicitudes"])*100/ $prev_indicators["solicitudes"] : 100,
			"primas_solicitadas" => $prev_indicators["primas_solicitadas"] > 0 ? ($indicators["primas_solicitadas"] - $prev_indicators["primas_solicitadas"])*100/ $prev_indicators["primas_solicitadas"] : 100,
			"prima_promedio" => $prev_indicators["prima_promedio"] > 0 ? ($indicators["prima_promedio"] - $prev_indicators["prima_promedio"])*100/ $prev_indicators["prima_promedio"] : 100,
			"agentes" => $prev_indicators["agentes"] > 0 ? ($indicators["agentes"] - $prev_indicators["agentes"])*100/ $prev_indicators["agentes"] : 100,
		);
		
		//Return to the original state
		$this->custom_period_from = $from;
		$this->custom_period_to = $to;
		$other_filters["periodo"] = $period;


		$content_data = array(
			'access_all' => $this->access_all,
			'access_export_xls' => $this->access_export_xls,
			'period_fields' => show_period_fields('requests', $ramo),
			'selected_period' => get_filter_period(),
			'other_filters' => $other_filters,
			'ramos' => $ramos,
			'products' => $products,
			'status' => $status,
			'agents' => $agents,
			'gerentes' => $gerentes,
			'wo_general' => $work_orders_general,
			'wo_agents' => $work_orders_agents,
			'wo_status' => $work_orders_status,
			'wo_products' => $work_orders_products,
			'wo_generations' => $work_orders_generations,
			'selected_tab' => $tab,
			'selected_order' => $orderby,
			'orderhash' => $orderhash,
			'orderlabel' => $orderlabel,
			'general_indicators' => $indicators,
			'last_date' => $imported_date,
			'comparative_indicators' => $comparative,
		);

		$sub_page_content = $this->load->view('solicitudes/summary', $content_data, TRUE);

		$add_js = '
			<script type="text/javascript">
				var WO_Agents = '.$work_orders_data.';
				var WO_Status = '.$work_orders_status_data.';
				var WO_Products = '.$work_orders_products_data.';
				var WO_Generations = '.$work_orders_generations_data.';
			</script>
			';
		$this->view = array(
			'title' => 'Resumen de Solicitación',
			 // Permisions
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
				'<script type="text/javascript" src="'. $base_url .'scripts/select_period.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'operations/assets/scripts/jquery.canvasjs.min.js"></script>',
				'<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.tablesorter-2.14.5.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.tablesorter.widgets-2.14.5.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.fancybox.js"></script>',
				'<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/randomcolor/0.5.2/randomColor.min.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'solicitudes/assets/scripts/summary.js?'.time().'"></script>',
				'<script src="https://use.fontawesome.com/884297e135.js"></script>',
				$add_js,
				$this->custom_filters->render_javascript(),
			),
			'content' => 'report_template', // View to load
			'message' => $this->session->flashdata('message'),
			'data' => array(),
			'sub_page_content' => $sub_page_content,
		);

		// Render view 
		$this->load->view( 'index', $this->view );
	}

	public function download($typeFile = "summary"){
		//Validation of export
		$exportTypes = array("summary", "agents", "status", "products", "primastatus", "primaproduct", "primaavgproduct", "generations");
		if(!in_array($typeFile, $exportTypes))
			redirect('solicitudes');
		if ( !$this->access_export_xls )
		{	
			$this->session->set_flashdata( 'message', array
			( 
				'type' => false,	
				'message' => 'No tiene permisos para exportar a XLS este reporte.'
			));	
			redirect( 'home', 'refresh' );
		}

		$other_filters = $this->_init_profile();
		$other_filters["nuevos_negocios"] = 1;
		$other_filters["periodo"] = get_filter_period();
		
		$this->load->model( 'ot/work_order');
		switch ($typeFile) {
			case 'summary':
				//General work orders
				$work_orders_general = $this->work_order->getWorkOrdersGroupBy($other_filters);
				//Formating names
				foreach ($work_orders_general as $i => $order){
					if(empty($order["name"]) && empty($order["lastnames"]))
						$work_orders_general[$i]["name"] = $order["company_name"];
					$work_orders_general[$i]["lastnames"].= " - ".$order["percentage"];
				}

				$clean_arr[] = array(
					"Numero de OT", "Fecha alta", "Agente", "Ramo", "Asegurado", "Estatus", "Prima", "Poliza"
				);
				foreach ($work_orders_general as $order) {
					$clean_arr[] = array(
						$order["uid"], $order["creation_date"], $order["name"]." ".$order["lastnames"], $order["ramo"], $order["asegurado"], $order["status"], $order["prima"], $order["poliza"] 
					);
				}
				break;
			case 'agents':
				//Configuration agent group
				$args = array(
					"select" => "users.company_name, users.name, users.lastnames, policies_vs_users.percentage",
					"sum" => "policies.prima",
					"by" => "users.company_name, agents.id, policies_vs_users.percentage",
					"order" => "conteo desc",
				);
				$work_orders_agents = $this->work_order->getWorkOrdersGroupBy($other_filters, $args);
				foreach ($work_orders_agents as $i => $order){
					$work_orders_agents[$i]["name"] = empty($order["name"]) && empty($order["lastnames"]) ? $order["company_name"] :$order["name"]." ".$order["lastnames"];
				}
				$clean_arr[] = array(
					"Agente", "Solicitudes", "Primas Totales"
				);
				foreach ($work_orders_agents as $order) {
					$clean_arr[] = array(
						$order["name"], $order["conteo"], $order["prima"]
					);
				}
				break;
			case 'status':
				$args = array(
					"select" => "work_order_status.name status",
					"by" => "work_order_status.name",
					"order" => "conteo desc",
				);
				$work_orders_status = $this->work_order->getWorkOrdersGroupBy($other_filters, $args);
				$clean_arr[] = array(
					"Status", "Solicitudes"
				);
				foreach ($work_orders_status as $order) {
					$clean_arr[] = array(
						$order["status"], $order["conteo"]
					);
				}
				break;
			case 'products':
				//Configuration products group
				$args = array(
					"select" => "products.name producto",
					"sum" => "policies.prima",
					"by" => "products.id",
					"order" => "conteo desc",
				);
				$work_orders_products = $this->work_order->getWorkOrdersGroupBy($other_filters, $args);
				$clean_arr[] = array(
					"Producto", "Solicitudes"
				);
				foreach ($work_orders_products as $order) {
					$clean_arr[] = array(
						$order["producto"], $order["conteo"]
					);
				}
				break;
			case 'primastatus':
				$args = array(
					"select" => "work_order_status.name status",
					"sum" => "policies.prima",
					"by" => "work_order_status.name",
					"order" => "prima desc",
				);
				$work_orders_status = $this->work_order->getWorkOrdersGroupBy($other_filters, $args);
				$clean_arr[] = array(
					"Status", "Prima"
				);
				foreach ($work_orders_status as $order) {
					$clean_arr[] = array(
						$order["status"], $order["prima"]
					);
				}
				break;
			case 'primaproduct':
				//Configuration products group
				$args = array(
					"select" => "products.name producto",
					"sum" => "policies.prima",
					"by" => "products.id",
					"order" => "prima desc",
				);
				$clean_arr[] = array(
					"Producto", "Prima"
				);
				$work_orders_products = $this->work_order->getWorkOrdersGroupBy($other_filters, $args);
				foreach ($work_orders_products as $order) {
					$clean_arr[] = array(
						$order["producto"], $order["prima"]
					);
				}
				break;
			case 'primaavgproduct':
				//Configuration products group
				$args = array(
					"select" => "products.name producto",
					"sum" => "policies.prima",
					"by" => "products.id",
					"order" => "conteo desc",
				);
				$work_orders_products = $this->work_order->getWorkOrdersGroupBy($other_filters, $args);
				//Calculate Average Prima per product
				foreach ($work_orders_products as $i => $row) 
					$work_orders_products[$i]["avgPrima"] = ($row["conteo"] != 0) ? $row["prima"] / $row["conteo"] : 0;
				//Sorting Array by primaAvg
				$this->load->helper('sort');
	 			sort_object($work_orders_products, "avgPrima");

				$clean_arr[] = array(
					"Producto", "Prima Promedio"
				);
				foreach ($work_orders_products as $order) {
					$clean_arr[] = array(
						$order["producto"], $order["avgPrima"]
					);
				}
				break;
			case 'generations':

				$work_orders_generations = $this->work_order->getWorkOrdersGroupByGeneracion($other_filters);

				$clean_arr[] = array(
					"Generacion", "Solicitudes"
				);
				foreach ($work_orders_generations as $order) {
					$clean_arr[] = array(
						$order["title"], $order["solicitudes"]
					);
				}
		}

		// Export
		$this->load->helper('usuarios/csv');
		$filename = 'solicitudes_'.$typeFile.'_'.date("Ymd_Hi").'.csv';
		array_to_csv($clean_arr, $filename);
		
		if( is_file( $filename ) )
			echo file_get_contents( $filename );
		if( is_file( $filename ) )
			unlink( $filename );
		exit;
	}

	public function popup(){
		if(!$this->input->is_ajax_request())
			show_404();

		$search = $this->input->post("search");
		$value = $this->input->post("value");

		$Available_searchs = array("status", "agent", "product");
		if(!in_array($search, $Available_searchs))
			show_404();

		switch ($search) {
			case 'status':
				$search = "work_order_status.name";	
				break;
			case 'agent':
				$search = "agents.id";
				break;
			case 'product':
				$search = "products.id";
				break;
		}

		$other_filters = $this->_init_profile();

		$other_filters["nuevos_negocios"] = 1;
		$other_filters["periodo"] = get_filter_period();
		$other_filters["where"] = array($search => $value);
		
		$this->load->model( 'ot/work_order');
		$this->load->model( 'users/user');
		//General work orders
		$work_orders_general = $this->work_order->getWorkOrdersGroupBy($other_filters);
		//Formating names
		foreach ($work_orders_general as $i => $order){
			if(empty($order["name"]) && empty($order["lastnames"]))
				$work_orders_general[$i]["name"] = $order["company_name"];
			$work_orders_general[$i]["lastnames"].= " - <b>". $order["percentage"]."</b>";
		}
		$this->load->view('solicitudes/reporte_general_table', array("general_data" => $work_orders_general));
	}

	public function _init_profile(){
		$this->load->helper('ot/ot');

		//Generic Filters
		$other_filters = array(
			"periodo" => 2,
			"ramo" => '',
			"agent" => '',
			"gerente" => '',
			"status" => '',
			"product" => '',

		);
		$this->custom_filters->set_array_defaults($other_filters);
		if(!empty($this->misc_filters))
			$other_filters = array_merge($other_filters, $this->misc_filters);

		if(empty($this->custom_period_to) || empty($this->custom_period_from)){
			$this->custom_period_from = date("Y-m-d", strtotime("last week monday"));
			$this->custom_period_to = date("Y-m-d", strtotime("last week sunday"));
		}

		if(empty($other_filters["ramo"]))
			$other_filters["ramo"] = 1;

		//Filters
		if($this->input->post()){
			update_custom_period($this->input->post('cust_period_from'),
				$this->input->post('cust_period_to'), FALSE);

			if ( isset($_POST['periodo']) && $this->form_validation->is_natural_no_zero($_POST['periodo']) &&
				($_POST['periodo'] <= 4) )
				set_filter_period($_POST['periodo']);

			if ( isset($_POST['ramo']) && (($this->form_validation->is_natural_no_zero($_POST['ramo']) &&
				($_POST['ramo'] <= 3)) || (($_POST['ramo']) === '')) )
				$other_filters['ramo'] = $_POST['ramo'];

			if (isset($_POST['agent']) && ($this->form_validation->is_natural_no_zero($_POST['agent']) || 
				$_POST['agent'] === ''))
				$other_filters['agent'] = $_POST['agent'];

			if (isset($_POST['gerente']) && ($this->form_validation->is_natural_no_zero($_POST['gerente']) || 
				$_POST['gerente'] === ''))
				$other_filters['gerente'] = $_POST['gerente'];

			if (isset($_POST['product']) && ($this->form_validation->is_natural_no_zero($_POST['product']) || 
				$_POST['product'] === ''))
				$other_filters['product'] = $_POST['product'];

			if (isset($_POST['status']))
				$other_filters['status'] = $_POST['status'];
		}
		
		$this->custom_filters->set_filters_to_save($other_filters);
		$this->custom_filters->set_current_filters($other_filters);
		generic_set_report_filter( $other_filters, array() );
		return $other_filters;
	}
}

/* End of file solicitudes.php */
/* Location: ./application/modules/solicitudes/controllers/solicitudes.php */