<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class rpventas extends CI_Controller {
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
		$this->production();
	}

	public function production(){
		if ( !$this->access_report )
		{	
			$this->session->set_flashdata( 'message', array
			( 
				'type' => false,	
				'message' => 'No tiene permisos para ver el reporte "Resumen de ventas" en la sección "Reporte de ventas", informe a su administrador para que le otorge los permisos necesarios.'
			));	
			redirect( 'home', 'refresh' );
		}

		// Getting filters
		$other_filters = $this->_init_profile();
		
		//Loading Models
		$this->load->model( 'ot/work_order');
		$this->load->model( 'rpventas/rpm');

		//Loading helpers
		$this->load->helper('render');
		$this->load->helper('date');

		//Configure filters of the report
		$base_url = base_url();
		$ramo= 55;
		$ramos = makeDropdown($this->work_order->getProductsGroups(), "id", "name", FALSE);
		$months = getMonths();
		unset($ramos[3]);

		$productsFilter = makeDropdown($this->work_order->getProducts(), "id", "name");
		$agents = makeDropdown($this->user->getAgentsArray(), "id", "name");

		$prime_types = array( 'amount' => 'Primas totales', 'allocated_prime' => 'Primas a ubicar', 'bonus_prime' => 'Primas para pago de bono');
		
		//Create year dropdown
		$periods = array();
        $minYear = $this->rpm->getFirstPaymentYear();
        $auxYear = date("Y");
        do{
        	$periods[$auxYear] = $auxYear;
        	$auxYear--;
        }while ($auxYear > $minYear);
        
		//Get sales graphic info
        $year1 = $other_filters["periodo"];
        $year2 = $year1 - 1;
		$sramo = $other_filters["ramo"];

		$prime_requested = $other_filters["prime_type"];
		log_message('error', 'sramo' . $sramo);
		$ventasy1  = $this->rpm->getAllData($year1, $sramo, $other_filters, $prime_requested);
        $ventasy2  = $this->rpm->getAllData($year2, $sramo, $other_filters, $prime_requested);
        $primasy1 = $this->rpm->getPrimasList($year1, $sramo, $other_filters);
        $primasy2 = $this->rpm->getPrimasList($year2, $sramo, $other_filters);
        $negociosy1 = $this->rpm->getNegociosList($year1, $sramo, $other_filters);
        $negociosy2 = $this->rpm->getNegociosList($year2, $sramo, $other_filters);
        $ramo = 1;
		if (isset($this->query_filters['query']) && isset($this->query_filters['query']['ramo']))
			$ramo = $this->query_filters['query']['ramo'];
        $imported_date = $this->work_order->getLastPaymentImportedDate($ramo);
		
		$generationsTotal = $this->rpm->getDataByGeneracion($year1, $sramo, $other_filters);

		log_message('error',json_encode($generationsTotal));
        $negociosp = $this->rpm->getNegociosProduct($year1, $sramo, $other_filters);
        $primasp = $this->rpm->getPrimasProduct($year1, $sramo, $other_filters);
        $agentsm = $this->rpm->getAgentsMonth($other_filters, $prime_requested);
        $agentsp = $this->rpm->getAgentsProduct($other_filters);

        //Get products graphic info
        $products = $this->rpm->getDataByProduct($year1, $sramo, $other_filters, $prime_requested);
        //Generate DataSet array
        $colors = array("#0e606b", "#179381", "#2dfca2", "#32fc6b", "#34f937", "#6bf738", "#a3f73d", "#d7f442", "#f9e848", "#f2b148", "#f2844d", "#f25d52", "#ef5677", "#f45da8", "#f461d2", "#eb63f2", "#b962e5", "#9967e5", "#1b04c9", "#0727c6", "#095ac4", "#0d8ec1", "#11c1c1", "#16cc9b", "#1acc6a", "#1ecc3b", "#31cc20", "#66ce25", "#97ce29", "#c8d12e", "#e5bf37", "#d38434", "#cc5737", "#ce3b43", "#ce406f", "#e04aa6", "#e04ecf", "#c850e0", "#8847bc", "#6748b5");

        $agentsCantM = array();
        $agentMColor = array();

        foreach ($agentsm as $key => $value) {
        	$agentsCantM[] = $value["agents"];
        	$agentMColor[] = $colors[$key];
        }

        $agentsNameP = array();
        $agentsCantP = array();
        $agentPColor = array();

        foreach ($agentsp as $key => $value) {
        	$agentsNameP[] = $value["name"];
        	$agentsCantP[] = $value["agents"];
        	$agentPColor[] = $colors[$key];
        }

        $negociospName = array();
        $negociospCant = array();
        $negocioPrColor = array();

        foreach ($negociosp as $key => $value) {
        	$negociospName[] = $value["name"];
        	$negociospCant[] = $value["cantidad"];
        	$negocioPrColor[] = $colors[$key];
        }

        $primaspName = array();
        $primaspCant = array();
        $primasPrColor = array();

        foreach ($primasp as $key => $value) {
        	$primaspName[$key] = $value["name"];
        	$primaspCant[$key] = $value["prima"] / $negociosp[$key]["cantidad"];
        	$primasPrColor[$key] = $colors[$key];
        	$primasp[$key]["negocios"] = $negociosp[$key]["cantidad"];
        }

		$productsDS = array();
		$productosNombres = array();
		$productosTAnual = array();
		$productosColor = array();
		$generacionsColorArray = array("#0e606b", "#179381", "#f2844d", "#6bf738", "#f25d52");
		$productosGeneral = array();

		$i = 0;
		foreach ($products as $product) {
			// if(!empty($product["id"])){
				$totalpvpy = 0;
				foreach ($product["payments"] as $value) {
					$totalpvpy += $value;
				}
				array_push($productosNombres, $product["name"]);
				array_push($productosTAnual, $totalpvpy);
				array_push($productosColor, $colors[$i]);
				$productosGeneral[$product["name"]] = $totalpvpy;

				$productsDS[] = array(
					"label" => $product["name"],
					"backgroundColor" => $colors[$i],
					"borderColor" => $colors[$i],
					"data" => $product["payments"],
					"fill" => FALSE,
					"totaly" => $totalpvpy
				);
				$i++;
			// }
		}


        //Nombres de las generaciones
        $this->load->helper('agent/generations');
        $generacionesNombres = array();
		foreach (getGenerationList() as $key => $value) {
		    log_message('error', $value['title']);
		    array_push($generacionesNombres, $value['title']);
        }
		$totalGenerations = $generationsTotal[0] + $generationsTotal[1] + $generationsTotal[2] + $generationsTotal[3] + $generationsTotal[4];
        $generacionAnualArray = array(
            "Generacion 1" => $generationsTotal[0],
            "Generacion 2" => $generationsTotal[1],
            "Generacion 3" => $generationsTotal[2],
            "Generacion 4" => $generationsTotal[3],
			"Consolidado" => $generationsTotal[4],
			"Total" => $totalGenerations);
        //$generacionesColor = array(colors[0], colors[1], colors[2], colors[3], colors[4]);

		//Get the indicators
        $totalnidy1 = $this->rpm->getNegocios($year1, $sramo, $other_filters);
        $totalnidy2 = $this->rpm->getNegocios($year2, $sramo, $other_filters);
        $indebusins = comparationRatio($totalnidy1, $totalnidy2);
        $primessmy1 = $this->rpm->getPrimas($year1, $sramo, $other_filters);
        $primessmy2 = $this->rpm->getPrimas($year2, $sramo, $other_filters);
        $indeprimes = comparationRatio(((!empty($primessmy1) && !empty($totalnidy1)) ? ($primessmy1/$totalnidy1) : 0), ((!empty($primessmy2) && !empty($totalnidy2)) ? ($primessmy2/$totalnidy2) : 0));
        $numagentsa = $this->rpm->getNumAgents($year1, $sramo, $other_filters, $prime_requested);
        $numagentsa2 = $this->rpm->getNumAgents($year2, $sramo, $other_filters, $prime_requested);
        $indeagents = comparationRatio($numagentsa, $numagentsa2);
        $businespai = $this->rpm->getNumBusiness($year1, $sramo, $other_filters);
        $businespai2 = $this->rpm->getNumBusiness($year2, $sramo, $other_filters);
        if($sramo==2){ $businespai=0; $businespai2=0; }
        $indebusines = comparationRatio($businespai, $businespai2);

		$content_data = array(
			'access_all' => $this->access_all,
			'access_export_xls' => $this->access_export_xls,
			'other_filters' => $other_filters,
			'ramos' => $ramos,
			'products' => $productsFilter,
			'agents' => $agents,
			'agentsm' => $agentsm,
			'agentsp' => $agentsp,
			'periodos' => $periods,
			'months' => $months,
			'prime_types' => $prime_types,
			'year1' => $year1,
			'year2' => $year2,
			'y1' => $ventasy1,
			'y2' => $ventasy2,
			'primasy1' => $primasy1,
			'primasy2' => $primasy2,
			'primasp' => $primasp,
			'negociosy1' => $negociosy1,
			'negociosy2' => $negociosy2,
			'negociosp' => $negociosp,
			"productos" => $products,
			'nya' => $totalnidy1,
			'idb' => $indebusins,
			'pya' => $primessmy1,
			'idp' => $indeprimes,
			'naa' => $numagentsa,
			'naa2' => $numagentsa2,
			'ida' => $indeagents,
			'ngp' => $businespai,
			'ngp2' => $businespai2,
			'idn' => $indebusines,
			'productosAnual' => $productosGeneral,
			'last_date' => $imported_date,
            'generacionAnual' => $generacionAnualArray
		);
		$sub_page_content = $this->load->view('rpventas/summary', $content_data, TRUE);

		$add_js = '
			<script type="text/javascript">
				var P1 = '.json_encode($primasy1).'
				var P2 = '.json_encode($primasy2).'
				var N1 = '.json_encode($negociosy1).'
				var N2 = '.json_encode($negociosy2).'
				var V1 = '.json_encode($ventasy1).'
				var V2 = '.json_encode($ventasy2).'
				var Y1Title = '.$year1.'
				var Y2Title = '.$year2.'
				var months = '.json_encode($months).'
				var ProdDs = '.json_encode($productsDS).'
				var productosName = '.json_encode($productosNombres).'
				var generacionesName = '.json_encode($generacionesNombres).'
				var generacionTAnual = '.json_encode($generationsTotal).'
				var generacionColor = '.json_encode($generacionsColorArray).'
				var productosTAnual = '.json_encode($productosTAnual).'
				var productosColor = '.json_encode($productosColor).'
				var negocioPrName = '.json_encode($negociospName).'
				var negocioPrCant = '.json_encode($negociospCant).'
				var negocioPrColor = '.json_encode($negocioPrColor).'
				var primaspName = '.json_encode($primaspName).'
				var primaspCant = '.json_encode($primaspCant).'
				var primasPrColor = '.json_encode($primasPrColor).'
				var agentsCantM = '.json_encode($agentsCantM).'
				var agentMColor = '.json_encode($agentMColor).'
				var agentsNameP = '.json_encode($agentsNameP).'
				var agentsCantP = '.json_encode($agentsCantP).'
				var agentPColor = '.json_encode($agentPColor).'
			</script>
			';
		$this->view = array(
			'title' => 'Reporte de Producción',
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
				'<script type="text/javascript" src="'. $base_url .'operations/assets/scripts/jquery.canvasjs.min.js"></script>',
				'<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.tablesorter-2.14.5.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.tablesorter.widgets-2.14.5.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.fancybox.js"></script>',
				'<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/randomcolor/0.5.2/randomColor.min.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'scripts/report-utilities.js?'.time().'"></script>',
				'<script type="text/javascript" src="'. $base_url .'rpventas/assets/scripts/summary.js?'.time().'"></script>',
				'<script src="https://use.fontawesome.com/884297e135.js"></script>',
				$add_js,
			),
			'content' => 'report_template', // View to load
			'message' => $this->session->flashdata('message'),
			'data' => array(),
			'sub_page_content' => $sub_page_content,
		);

		// Render view 
		$this->load->view( 'index', $this->view );
	}

	public function exportar($type = ""){
		if( $this->access_export_xls == false ){
				
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Reporte de Ventas Exportar", Informe a su administrador para que le otorge los permisos necesarios.'
							
			));	
			
			
			redirect( 'rpventas', 'refresh' );
		
		}

		// Getting filters
		$other_filters = $this->_init_profile();
		
		//Loading Models
		$this->load->model( 'ot/work_order');
		$this->load->model( 'rpventas/rpm');

		//Loading helpers
		$this->load->helper('render');
		$this->load->helper('date');
		$this->load->helper('usuarios/csv');

		//Configure filters of the report
		$base_url = base_url();
		$year1 = $other_filters["periodo"];
        $year2 = $year1 - 1;
    	$sramo = $other_filters["ramo"];
    	$data = array();

		switch ($type) {
			case 'ventasp':
				$products = $this->rpm->getDataByProduct($year1, $sramo);
				$namefile = "proages_ventas_anual_producto.csv";
				array_push($data, array('Producto', 'Pagado '.$year1));

				$total = 0;
				foreach ($products as $product) {
					$totalpvpy = 0;
					foreach ($product["payments"] as $value) {
						$totalpvpy += $value;
					}
					$total += $totalpvpy;
					array_push($data, array($product["name"], "$".number_format($totalpvpy, 2)));
				}

				array_push($data, array('Total:', "$".number_format($total, 2)));
				break;

			case 'negociosp':
				$negociosp = $this->rpm->getNegociosProduct($year1, $sramo);
				$namefile = "proages_negocios_anual_producto.csv";
				array_push($data, array('Producto', 'Negocios '.$year1));

				$total = 0;
				foreach ($negociosp as $key => $value) {
					$total += $value["cantidad"];
					array_push($data, array($value["name"], $value["cantidad"]));
				}

				array_push($data, array("Total: ", $total));
				break;

			case 'primapromediop':
				$negociosp = $this->rpm->getNegociosProduct($year1, $sramo);
				$primasp = $this->rpm->getPrimasProduct($year1, $sramo);
				$namefile = "proages_prima_promedio_anual_producto.csv";
				array_push($data, array('Producto', 'Prima promedio '.$year1));

				foreach ($primasp as $key => $value) {
					array_push($data, array($value["name"], "$".number_format(($value["prima"]/$negociosp[$key]["cantidad"]))));
				}

				break;

			case 'distribucionmensualp':
				$products = $this->rpm->getDataByProduct($year1, $sramo);
				$namefile = "proages_distribucion_venta_mensual_producto.csv";
				$months = getMonths();

				$totalesMes = array();
				for ($i=0; $i < 12; $i++) { 
					$totalesMes[$i] = 0;
				}

				$data[0][] = 'Producto';
				foreach ($months as $month):
					$data[0][] = substr($month, 0, 3);
				endforeach;
				$data[0][] = 'Total';

				$i = 1;

				foreach ($products as $producto):
					$total = 0;
					$data[$i][] = $producto["name"];

					foreach ($producto["payments"] as $j => $payment):
						$total = $total + $payment;
						$totalesMes[$j] = $totalesMes[$j] + $payment;

						$data[$i][] = "$".number_format($payment, 2);
					endforeach;

					$data[$i][] = "$".number_format($total, 2);
					$i++;
				endforeach;

				$data[$i][] = 'Total';
				$total = 0;
				foreach ($totalesMes as $key => $value) {
					$total = $total + $value;
					$data[$i][] = "$".number_format($value, 2);
				}
				$data[$i][] = "$".number_format($total, 2);

				break;

			case 'general':
				$ventasy1  = $this->rpm->getAllData($year1, $sramo);
		        $ventasy2  = $this->rpm->getAllData($year2, $sramo);
		        $primasy1 = $this->rpm->getPrimasList($year1, $sramo);
		        $primasy2 = $this->rpm->getPrimasList($year2, $sramo);
		        $negociosy1 = $this->rpm->getNegociosList($year1, $sramo);
		        $negociosy2 = $this->rpm->getNegociosList($year2, $sramo);
		        $totalnidy1 = $this->rpm->getNegocios($year1, $sramo);
				$months = getMonths();

				$t1=0;$t2=0;$ny2=0;$py2=0;
				foreach ($months as $i => $month):
					$t1 += $ventasy1[$i];
					$t2 += $ventasy2[$i];
					$ny2 += $negociosy2[$i];
					$py2 += $primasy2[$i];
				endforeach;

				$namefile = "proages_reporte_ventas.csv";
				array_push($data, array('Mes', 'Pagado '.$year1, '% de participación sobre la venta anual', 'Negocios '.$year1, 'Variación contra periodo anterior', 'Prima promedio', 'Variación contra periodo anterior'));

				foreach ($months as $i => $month):
					array_push($data, array(
						$month,
						"$".number_format($ventasy1[$i], 2),
						number_format(percentageRatio($ventasy1[$i], $t1), 2)."%",
						$negociosy1[$i],
						number_format(comparationRatio($ventasy1[$i], $ventasy2[$i]), 2)."%",
						((!empty($negociosy1[$i]) && !empty($primasy1[$i])) ? number_format(($primasy1[$i]/$negociosy1[$i]), 2) : 0),
						number_format(comparationRatio(((!empty($negociosy1[$i]) && !empty($primasy1[$i])) ? ($primasy1[$i]/$negociosy1[$i]) : 0), ((!empty($negociosy2[$i]) && !empty($primasy2[$i])) ? ($primasy2[$i]/$negociosy2[$i]) : 0)), 2)
					));
				endforeach;

				array_push($data, array(
					'Total:',
					'$'.number_format($t1, 2),
					$totalnidy1,
					'',
					'',
					''
				));

				break;

			case 'ventasam':
				$agentsm = $this->rpm->getAgentsMonth($other_filters, $prime_requested);
				$months = getMonths();
				$namefile = "proages_ventas_agentes_mensual.csv";

				array_push($data, array('Mes', 'Agentes '.$year1));

				foreach ($agentsm as $key => $agent):
					array_push($data, array($months[$agent["month"]], $agent["agents"]));
				endforeach;
				break;

            case 'generacionesp':
                $year1 = $other_filters["periodo"];
                $sramo = $other_filters["ramo"];
                $generaciones_data = $this->rpm->getDataByGeneracion($year1, $sramo, $other_filters);
                $generacionAnualArray = array(
                    "Generacion 1" => $generaciones_data[0],
                    "Generacion 2" => $generaciones_data[1],
                    "Generacion 3" => $generaciones_data[2],
                    "Generacion 4" => $generaciones_data[3],
                    "Consolidado" => $generaciones_data[4]);
                $namefile = "proages_ventas_anual_generacion.csv";

                array_push($data, array('Generacion', 'Cantidad'));

                foreach ($generacionAnualArray as $key => $total):
                    array_push($data, array($key, $total));
                endforeach;
                break;

			case 'ventasap':
				$agentsp = $this->rpm->getAgentsProduct($other_filters);
				$namefile = "proages_ventas_agentes_producto.csv";

				array_push($data, array('Producto', 'Agentes '.$year1));

				foreach ($agentsp as $key => $agent):
					array_push($data, array($agent["name"], $agent["agents"]));
				endforeach;
				break;
			
			default:
				$ventasy1  = $this->rpm->getAllData($year1, $sramo);
		        $ventasy2  = $this->rpm->getAllData($year2, $sramo);
		        $primasy1 = $this->rpm->getPrimasList($year1, $sramo);
		        $primasy2 = $this->rpm->getPrimasList($year2, $sramo);
		        $negociosy1 = $this->rpm->getNegociosList($year1, $sramo);
		        $negociosy2 = $this->rpm->getNegociosList($year2, $sramo);
		        $totalnidy1 = $this->rpm->getNegocios($year1, $sramo);
				$months = getMonths();

				$t1=0;$t2=0;$ny2=0;$py2=0;
				foreach ($months as $i => $month):
					$t1 += $ventasy1[$i];
					$t2 += $ventasy2[$i];
					$ny2 += $negociosy2[$i];
					$py2 += $primasy2[$i];
				endforeach;

				$namefile = "proages_reporte_ventas.csv";
				array_push($data, array('Mes', 'Pagado '.$year1, '% de participación sobre la venta anual', 'Negocios '.$year1, 'Variación contra periodo anterior', 'Prima promedio', 'Variación contra periodo anterior'));

				foreach ($months as $i => $month):
					array_push($data, array(
						$month,
						"$".number_format($ventasy1[$i], 2),
						number_format(percentageRatio($ventasy1[$i], $t1), 2)."%",
						$negociosy1[$i],
						number_format(comparationRatio($ventasy1[$i], $ventasy2[$i]), 2)."%",
						((!empty($negociosy1[$i]) && !empty($primasy1[$i])) ? number_format(($primasy1[$i]/$negociosy1[$i]), 2) : 0),
						number_format(comparationRatio(((!empty($negociosy1[$i]) && !empty($primasy1[$i])) ? ($primasy1[$i]/$negociosy1[$i]) : 0), ((!empty($negociosy2[$i]) && !empty($primasy2[$i])) ? ($primasy2[$i]/$negociosy2[$i]) : 0)), 2)
					));
				endforeach;

				array_push($data, array(
					'Total:',
					'$'.number_format($t1, 2),
					$totalnidy1,
					'',
					'',
					''
				));
				break;
		}

		header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="'.$namefile.'"');
		
	 	array_to_csv($data, $namefile);
		
		if( is_file( $namefile ) )
			echo file_get_contents( $namefile );
		
		if( is_file( $namefile ) )
			unlink( $namefile );
				
		exit;
	}

	public function popup(){
		// Getting filters
		$other_filters = $this->_init_profile();
		
		//Loading Models
		$this->load->model( 'ot/work_order');
		$this->load->model( 'rpventas/rpm');

		//Loading helpers
		$this->load->helper('render');
		$this->load->helper('date');

		$other_filters["producto"] = $this->input->post('value');
		$other_filters["month_search"] = $this->input->post('month');

    	//Get products graphic info
        $products = $this->rpm->getDataByProductMonth($other_filters);

        $this->load->view('rpventas/reporte_general_table', array("general_data" => $products));
	}

    public function generacionPopup(){
        // Getting filters
        $other_filters = $this->_init_profile();

        //Loading Models
        $this->load->model( 'ot/work_order');
        $this->load->model( 'rpventas/rpm');

        //Loading helpers
        $this->load->helper('render');
        $this->load->helper('date');

        $other_filters["generacion"] = $this->input->post('value');

        //Get generaciones graphic input
		$generaciones_data = $this->rpm->getDataByGeneration($other_filters);

        $this->load->view('rpventas/reporte_general_table', array("general_data" => $generaciones_data));
    }

	public function _init_profile(){
		$this->load->helper('ot/ot');

		//Generic Filters
		$other_filters = array(
			"periodo" => '',
			"ramo" => '',
			"agent" => '',
			"product" => '',
			"prime_type" => '',
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

			if ( isset($_POST['periodo']) && $this->form_validation->is_natural_no_zero($_POST['periodo']) &&
				($_POST['periodo'] >= 2000) )
				$other_filters["periodo"] = $_POST["periodo"];

			if ( isset($_POST['prime_type']))
				$other_filters["prime_type"] = $_POST["prime_type"];

			if ( isset($_POST['ramo']) && (($this->form_validation->is_natural_no_zero($_POST['ramo']) &&
				($_POST['ramo'] <= 2)) || (($_POST['ramo']) === '')) )
				$other_filters['ramo'] = $_POST['ramo'];
			
			if (isset($_POST['agent']) && ($this->form_validation->is_natural_no_zero($_POST['agent']) || 
				$_POST['agent'] === ''))
				$other_filters['agent'] = $_POST['agent'];

			if (isset($_POST['product']) && ($this->form_validation->is_natural_no_zero($_POST['product']) || 
				$_POST['product'] === ''))
				$other_filters['product'] = $_POST['product'];
		}
		
		$this->custom_filters->set_filters_to_save($other_filters);
		$this->custom_filters->set_current_filters($other_filters);
		generic_set_report_filter( $other_filters, array() );
		return $other_filters;
	}
}

/* End of file rpventas.php */
/* Location: ./application/modules/rpventas/controllers/rpventas.php */