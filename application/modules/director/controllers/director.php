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
class Director extends CI_Controller {

	public $view = array();
	
	public $sessions = array();
	
	public $user_vs_rol = array();
	
	public $roles_vs_access = array();
	
	public $access = false; // Force security

	public $access_report = FALSE;	
	public $access_create = false;
	
	public $access_update = false;
	
	public $access_delete = false;
	
	public $access_update_profile = false;

	public $access_simulator = FALSE;
	public $access_create_simulator = FALSE;
	public $access_update_simulator = FALSE;
	public $access_delete_simulator = FALSE;
	
	public $access_export_xls = false; // Export xls
	public $access_activate = FALSE;
	public $access_ot_report = FALSE;
	public $access_update_ot = FALSE;
	public $access_delete_ot = FALSE;
	public $access_create_ot = FALSE;

	public $access_create_activity = FALSE;
	public $access_activity_list = FALSE;
	public $access_update_activity = FALSE;
	public $access_sales_activities = FALSE;
	public $access_delete_activity = FALSE;

	public $default_period_filter = FALSE;
	public $misc_filters = FALSE;
	public $custom_period_from = FALSE;
	public $custom_period_to = FALSE;
	public $period_filter_for = FALSE;

	public $user_id = FALSE;

	public $agent_array = array();
	public $other_filters = array();
	public $query_filters = array();

	public $coordinator_select = '';
	public $computed_meta_fields = array(
		'primas-meta-primer', 'primas-meta-segund', 'primas-meta-second', 'primas-meta-tercer', 'primas-meta-cuarto',
		'primas-meta-total',
		'primas-negocio-meta-primer', 'primas-negocio-meta-segund', 'primas-negocio-meta-second',	
		'primas-negocio-meta-tercer', 'primas-negocio-meta-cuarto',
		'primas-negocios-meta-total', 
		'primas-solicitud-meta-primer', 'primas-solicitud-meta-segund', 'primas-solicitud-meta-second', 
		'primas-solicitud-meta-tercer', 'primas-solicitud-meta-cuarto', 
		'primas-solicitud-meta-total', 
	);
/** Construct Function **/
/** Setting Load perms **/
	
	public function __construct()
	{
		parent::__construct();

		/** Getting Info for User **/
		$this->load->model( array( 'usuarios/user', 'roles/rol', 'termometro/termometro_model' ) );

		// Get Session
		$this->sessions = $this->session->userdata('system');
		if ( empty( $this->sessions ) )
			redirect( 'usuarios/login', 'refresh' );

		// Get user rol		
		$this->user_vs_rol = $this->rol->user_role( $this->sessions['id'] );

		// Get user rol access
		$this->roles_vs_access = $this->rol->user_roles_vs_access( $this->user_vs_rol );

		if( !empty( $this->user_vs_rol )  and !empty( $this->roles_vs_access ) )
		{
			// Compute permissions to access this module and its functions
			foreach( $this->roles_vs_access  as $value )
			{
				switch ($value['module_name'])
				{
					case 'Director':
						$this->access = TRUE;
						switch ($value['action_name'])
						{
							case 'Ver reporte':
								$this->access_ot_report = TRUE;
								$this->access_report = TRUE;
								break;
							case 'Export xls':
								$this->access_export_xls = TRUE;
								break;
							case 'Editar':
								$this->access_update = TRUE;
								break;
							case 'Eliminar':
								$this->access_delete = TRUE;
								break;
							default:
								break;
						}
						break;						
					case 'Simulador':
						$this->access_simulator = TRUE;
						switch ($value['action_name'])
						{
							case 'Crear':
								$this->access_create_simulator = TRUE;
								break;
							case 'Editar':
								$this->access_update_simulator = TRUE;
								break;
							case 'Editar':
								$this->access_delete_simulator = TRUE;
								break;								
							default:
								break;
						}
						break;
					case 'Orden de trabajo':
						switch ($value['action_name'])
						{
							case 'Crear':
								$this->access_create_ot = TRUE;
								break;						
							case 'Activar/Desactivar':
								$this->access_activate = TRUE;
								break;
							case 'Ver reporte':
								$this->access_ot_report = TRUE;
								break;
							case 'Editar':
								$this->access_update_ot = TRUE;
								break;
							case 'Eliminar':
								$this->access_delete_ot = TRUE;
								break;
							default:
								break;
						}
						break;
					case 'Actividades':
						$this->access_activity_list = TRUE;
						switch ($value['action_name'])
						{
							case 'Crear':
								$this->access_create_activity = TRUE;
								break;
							case 'Editar':
								$this->access_update_activity = TRUE;
								break;
							case 'Actividades de ventas':
								$this->access_sales_activities = TRUE;
								break;
							case 'Eliminar':
								$this->access_delete_activity = TRUE;
								break;
							default:
								break;
						}
						break;
					default:
						break;
				}
			}
		}
		
		$this->period_filter_for = 'director';
		$this->default_period_filter = $this->session->userdata('default_period_filter_director');
		$this->custom_period_from = $this->session->userdata('custom_period_from_director');
		$this->custom_period_to = $this->session->userdata('custom_period_to_director');

		$this->misc_filter_name = 'director_misc_filter';
		$this->misc_filters = $this->session->userdata($this->misc_filter_name);
		$this->load->helper('filter');

		//Load custom filters library
		$options = array(
			"name" => "general",
			"page" => "director",
			"general_open" => "<table><thead><tr>",
			"general_close" => "</tr></thead></table>",
			"filter_open" => "<th>",
			"filter_close" => "</th>",
		);
		$this->load->library('custom_filters', $options);
		$this->query_filters = $this->_init_filters();
		$this->custom_filters->set_current_filters($this->other_filters);
		$this->user_id == $this->sessions['id'];
	}

	public function index()
	{
		$this->sales_planning();
	}

	public function sales_planning()
	{
		$this->load->model('policy_model');
		$this->policy_model->reset_values_primas();
		$page = 'sales_planning';
		if ($this->input->post('ver_meta') == 'metas')
			$page = 'metas';

		$this->_init_profile();
		if ( !$this->access_ot_report )
		{
			$this->session->set_flashdata( 'message', array
			( 
				'type' => false,	
				'message' => 'No tiene permisos para ver el reporte "PLANEACIÓN Y DESEMPEÑO DE VENTAS" en la sección "Director Commercial". Informe a su administrador para que le otorge los permisos necesarios.'
			));	
			redirect( 'home', 'refresh' );
		}

		$this->load->helper( array('ot/ot' ));
		$this->load->model('groups/group');
		if (count($_POST))
		{
			update_custom_period($this->input->post('cust_period_from'),
				$this->input->post('cust_period_to'), FALSE);
		}
		$data = $this->_init_planning_report($page == 'metas');

		$inline_js = get_agent_autocomplete_js($this->agent_array);

		$inline_js .= '
<script type="text/javascript">

	$( document ).ready( function(){ 
		$("#payment-date").datepicker( {
			dateFormat: "yy-mm-dd",
			changeMonth: true,
			showOtherMonths: true,
			selectOtherMonths: true,
			firstDay:1,
			closeText: "Cerrar",
			prevText: "&#x3c;Ant",
			nextText: "Sig&#x3e;",
			currentText: "Hoy",
			monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio",
				"Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
			monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun",
				"Jul","Ago","Sep","Oct","Nov","Dic"],
			dayNames: ["Domingo","Lunes","Martes","Mi&eacute;rcoles","Jueves","Viernes","S&aacute;bado"],
			dayNamesShort: ["Dom","Lun","Mar","Mi&eacute;","Juv","Vie","S&aacute;b"],
			dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","S&aacute;"],
		});

		$("#add-payment-btn").bind( "click", function(){
			$( "#add-payment-container" ).dialog( "open" );
			return false;
		})

		$("#add-payment-form").find(":input").each(function(index){
			if ($(this).prop("required")) {
				var forId = $(this).attr("id");
				$("#add-payment-form").find("label").each(function(index){
					if ($(this).attr("for") == forId) {
						$(this).append("<span style=\'color: #FF0000\'> * </span>")
						return false;
					}
				});
			}
		});

		var viewportHeight = window.innerHeight - 20;
		if (viewportHeight > 1000)
			viewportHeight = 1000;
		var allRowsHeight = parseInt($("#add-payment-container").css("line-height"), 10);
		allRowsHeight = Math.floor(allRowsHeight * (8 + 1.2) * 3);

		$( "#add-payment-container" ).dialog({
/*			open: function (event, ui) {
				$(this).prepend($(this).parent().find(".ui-dialog-buttonpane"));
			},*/
			autoOpen: false,
			width: 450,
//			height: viewportHeight,
			height: allRowsHeight,
			resizable: true,
			modal: true,
			buttons: {
				"Guardar": function() {
					var errorMessage = "";
					$("#add-payment-form").find(":input").each(function(index){
						if ($(this).prop("required") && ($.trim($(this).val()).length == 0)) {
							if (errorMessage.length == 0)
								errorMessage += "Los campos con una * son obligatorios.";
						}
					});
					var toTest = parseFloat($("#payment-amount").val());
					if ( !toTest ) {
						errorMessage += "<br>El campo Pago debe contener un número decimal.";
					}
					var regexp = /^[0-9]+$/;
					toTest = $("#payment-year-prime").val();
					if ( !( regexp.test( toTest) ) || (toTest == 0)) {
						errorMessage += "<br>El campo Año prima debe contener un número entero mayor a cero.";
					}

					toTest = parseInt($("#payment-business").val());
					if ((toTest < -1) || (toTest > 1))
						errorMessage += "<br>El campo Es negocio debe ser -1, 0 o 1.";

					if (errorMessage.length > 0) {
						alert("Campos invalidos!");
						$("#add-payment-error").html(errorMessage);
						$("#add-payment-error").show();
						return false;
					}

					$.ajax({
						type: "POST",
						url: Config.base_url() + "director/add_payment.html",
						data: $("#add-payment-form").serialize(),
						success: function(response){
							switch (response) {
								case "true":
									alert("Se guardo el pago correctamente.");
									if ( confirm( "¿Quiere recargar la página web?" ) ) 
										window.location.reload();
								break;

								default:
									alert("No se pudo guardar el pago.");
									$("#add-payment-error").html(response);
									$("#add-payment-error").show();
								break;
							}
						}
					});
					$( this ).dialog( "close" );
					return false;
				},
				"Cancelar": function() {
					$( this ).dialog( "close" );
					return false;
				}
			},
			close: function() {
				$("#add-payment-error").html("");
				$("#add-payment-error").hide();
			}
		});

		$("#payment-agent-id").autocomplete({
			source: agentList
		});

		$( "#payment-policy-number" ).autocomplete({
			minLength: 3,
			source: function( request, response ) {
				$.getJSON( Config.base_url() + "ot/search_polizas.html", {
					term: request.term 
				}, response );
			},				
		})

	});
</script>
';

		$base_url = base_url();

		$report_lines = '';
		$imported_date_requested = "amount";
		$ramo = 1;
		if (isset($this->query_filters['query']) && isset($this->query_filters['query']['ramo']))
			$ramo = $this->query_filters['query']['ramo'];
		$imported_date = $this->work_order->getLastPaymentImportedDate($ramo, "vida");
		$imported_date_selo = $this->work_order->getLastPaymentImportedDate($ramo, "selo");
		if ($page == 'sales_planning')
		{
			unset( $data[0] );
			switch ($ramo)
			{
				case 2:
					$report_lines = $this->load->view('ot/report2', array('data' => $data, 'tata' => 2, "last_date" => $imported_date, "last_date_selo" => $imported_date_selo), TRUE);
				break;
				case 3:
					$report_lines = $this->load->view('ot/report3', array('data' => $data, 'tata' => 3), TRUE);
				break;
				default:
					$report_lines = $this->load->view('ot/report1', array('data' => $data, 'tata' => 1, "last_date" => $imported_date, "last_date_selo" => $imported_date_selo), TRUE);
				break;
			}
		}
		else 
			$report_lines = $this->load->view('meta_overview', array('data' => $data, 'ramo' => $ramo), TRUE);

		/*call the helper*/
		$this->load->helper('agent/generations');
		$generations_list = getGenerationDropDown();
		/*call the helper*/

		$content_data = array(
			'manager' => $this->user->getSelectsGerentes2(),
			'groups' => $this->group->all(0, 0, "", $ramo),
			'period_form' => show_custom_period(),
			'period_fields' => show_period_fields('director', $this->other_filters['ramo']),
			'other_filters' => $this->other_filters,
			'report_lines' => $report_lines,
			'export_xls' => $this->access_export_xls,
			'page' => $page,
			'generations_list'=> $generations_list,
			'report_columns' => $this->load->view('filters/report_columns', array(), true)
			);
		$filter_view = $this->load->view('filters/report', $content_data, true);
		
		$sub_page_content = $this->load->view('director/report',
			array_merge($content_data, array('filter_view' => $filter_view)), true);

		$base_url = base_url();
		if ($page == 'sales_planning')
		{
			$inline_js .= 
'
<script type="text/javascript">
	function payment_popup(params) {
		$.fancybox.showLoading();
		$.post("' . $base_url . 'director/payment_popup.html", jQuery.param(params) + "&" + $("#form").serialize(), function(data) { 
			if (data) {
				$.fancybox({
					content:data
				});
				return false;
			}
		});
	}
	$( document ).ready(function() {
		$( "#activity-link-sub-metas" ).bind( "click", function(){
			$( "#ver-meta" ).val("metas");
			$( "#form" ).attr( "action", "' . $base_url . 'director/sales_planning.html" );
			$( "#form" ).submit();
			return false;
		});

		$( "#plan-link-sub-results" ).bind( "click", function(){
			$( "#ver-meta" ).val("");
			$( "#form" ).attr( "action", "' . $base_url . 'director/sales_planning.html" );
			$( "#form" ).submit();
		});
	});	
</script>';
		}
		else
		{
			$inline_js .= 
'
<script type="text/javascript">
	$( document ).ready(function() {
		$(".tablesorter-childRow td").hide();
		$("#sorter-meta")
			.tablesorter({ 
				theme : "default",
				stringTo: "max",
				headers: {  // non-numeric content is treated as a MIN value
					1: { sorter: "digit", string: "min" },
					2: { sorter: "digit", string: "min" },
					3: { sorter: "digit", string: "min" },
					4: { sorter: "digit", string: "min" },
					5: { sorter: "digit", string: "min" },
					6: { sorter: "digit", string: "min" },
				},
				sortList: [[0,0]],
				// use save sort widget
				widgets: ["saveSort"],
				// this is the default setting
				cssChildRow: "tablesorter-childRow"
			});
		// Toggle child row content (td), not hiding the row since we are using rowspan
		// Using delegate because the pager plugin rebuilds the table after each page change
		// "delegate" works in jQuery 1.4.2+; use "live" back to v1.3; for older jQuery - SOL
		$("#sorter-meta").delegate(".toggle", "click" ,function(){
			// use "nextUntil" to toggle multiple child rows
			// toggle table cells instead of the row
			$(this).closest("tr").nextUntil("tr:not(.tablesorter-childRow)").find("td").toggle();
			return false;
		});
	});
</script>';		
		}
			$inline_js .= 
'
<script type="text/javascript">
	$( document ).ready(function() {

		$("#export").bind( "click", function(){
			$( "#form" ).attr( "action", "' . $base_url . 'director/report_export.html" );
			$( "#form" ).submit();
		});
	});
</script>
';
		// Config view
		$this->view = array(
			'title' => 'Director',
			'user' => $this->sessions,
			'user_vs_rol' => $this->user_vs_rol,
			'roles_vs_access' => $this->roles_vs_access,
			'css' => array(
				'<link href="'. $base_url .'ot/assets/style/theme.default.css" rel="stylesheet">',
				'<link rel="stylesheet" href="'. $base_url .'ot/assets/style/main.css">',
				'<link rel="stylesheet" href="'. $base_url .'director/assets/style/director.css">',
				'<link rel="stylesheet" href="'. $base_url .'ot/assets/style/jquery.fancybox.css">',
				'<link href="'. $base_url .'ot/assets/style/report.css" rel="stylesheet">',
				'<style>
.fancybox_blanco {color: #CCCCFF;}
.fancybox_blanco:hover{color: #FFFFFF;}
.ui-dialog-titlebar-close:after { content: "X"; font-weight:bold; }
</style>'
			),
			'scripts' =>  array(
				'<script type="text/javascript" src="'.$base_url.'scripts/jquery.cookie.js"></script>',
				'<script type="text/javascript" src="'.$base_url.'plugins/jquery-validation/jquery.validate.js"></script>',
				'<script type="text/javascript" src="'.$base_url.'plugins/jquery-validation/es_validator.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.ddslick.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.tablesorter-2.14.5.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.tablesorter.widgets-2.14.5.js"></script>',
				'<script src="'. $base_url .'scripts/config.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/main.js"></script>',			
				'<script type="text/javascript" src="'.$base_url.'ot/assets/scripts/report.js"></script>',
				'<script type="text/javascript" src="'.$base_url.'ot/assets/scripts/jquery.fancybox.js"></script>',
				'<script type="text/javascript" src="'.$base_url.'director/assets/scripts/director.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'scripts/select_period.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'scripts/report_columns.js"></script>',
				$inline_js,
				$this->custom_filters->render_javascript(),
			),
			'content' => 'director/director_profile',
			'message' => $this->session->flashdata('message'),
			'sub_page_content' => $sub_page_content,
			'page' => $page,
			'ramo' => $ramo
		);
		// Render view 
		$this->load->view( 'index', $this->view );		
	}

	public function report_export()
	{
		$page = 'sales_planning';
		if ($this->input->post('ver_meta') == 'metas')
			$page = 'metas';

		$this->_init_profile();
		if ( !$this->access_export_xls )
		{
			$this->session->set_flashdata( 'message', array
			( 
				'type' => false,	
				'message' => 'No tiene permisos para exportar el reporte "PLANEACIÓN Y DESEMPEÑO DE VENTAS" en la sección "Director Commercial". Informe a su administrador para que le otorge los permisos necesarios.'
			));	
			redirect( 'home', 'refresh' );
		}

		$this->load->helper( array( 'usuarios/csv', 'ot/ot' ));
		if (count($_POST))
		{
			update_custom_period($this->input->post('cust_period_from'),
				$this->input->post('cust_period_to'), FALSE);
		}
		$data = $this->_init_planning_report($page == 'metas');
		if ($page == 'sales_planning')
			$data_report = $this->user->format_export_report($data, 'generic', $this->other_filters['ramo']);
		else
			$data_report = $this->user->format_export_report($data, 'metas');

		// Generates the .csv
	 	array_to_csv($data_report, 'proages_report.csv');

		if( is_file( 'proages_report.csv' ) )
			echo file_get_contents( 'proages_report.csv' );

		if( is_file( 'proages_report.csv' ) )
			unlink( 'proages_report.csv' );
	}

	public $for_print = false;
	public $print_meta = true;
	public $show_meta = true;

	public function meta_old( $userid = null, $ramo = null )
	{
		$this->_common_simulator($userid, $ramo);
	}

	public function simulator_old( $userid = null, $ramo = null )
	{
		$this->show_meta = false;
		$this->_common_simulator($userid, $ramo);
	}

	public function print_index_old( $userid = null, $ramo = null )
	{
		$segments = $this->uri->rsegment_array();
		$segments[1] = 'simulator';
		redirect(implode('/', $segments), 'refresh' );
	}

	public function print_index_simulator_old( $userid = null, $ramo = null )
	{
		$segments = $this->uri->rsegment_array();
		$segments[1] = 'simulator';
		redirect(implode('/', $segments), 'refresh' );
	}

	private function _common_simulator($userid = null, $ramo = null)
	{
		$this->_init_profile();
		$agentid = $this->user->getAgentIdByUser( $userid );
		if (!$this->access || !$agentid)
		{
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Simulador" o no tiene permisos ver el simulator de este usuario. Informe a su administrador.'
			));
			redirect( 'home', 'refresh' );
		}
		$users = $this->user->getForUpdateOrDelete( $userid );
		$this->access_create = $this->access_create_simulator;
		$this->access_update = $this->access_update_simulator;
		$this->access_update = $this->access_update_simulator;
		
		$this->load->helper('simulator/simulator');
		$content_data = simulator_view( $users, $userid, $agentid, $ramo );

		$sub_page_content = $this->load->view('simulator/overview', $content_data, true);

		if ($this->show_meta)
			$show_hide = '$("#simulator-section").hide(); $("#meta-section").show();';
		else
			$show_hide = '$("#meta-section").hide(); $("#simulator-section").show();';		

		$content_data['scripts'][] = '
<script type="text/javascript">
$( document ).ready( function(){
	' . $show_hide . 
'
});
</script>
';
		$base_url = base_url();
		// Config view
		$this->view = array(
			'title' => 'Director',
			'user' => $this->sessions,
			'user_vs_rol' => $this->user_vs_rol,
			'roles_vs_access' => $this->roles_vs_access,
			'css' => array_merge(
				array(
					'<link rel="stylesheet" href="'. $base_url .'director/assets/style/director.css">',
				),
				$content_data['css']),
			'scripts' =>  array_merge(
				array(
				'<script src="'. $base_url .'scripts/config.js"></script>',
				'<script type="text/javascript" src="'.$base_url.'director/assets/scripts/director.js"></script>',
				),
				$content_data['scripts']),
			'content' => 'director/director_profile',
			'message' => $this->session->flashdata('message'),
			'sub_page_content' => $sub_page_content,
		);

		// Render view 
		$this->load->view( 'index', $this->view );	
	}
///////////////////

	public function meta( $userid = null, $ramo = null )
	{
		$this->_new_common_simulator($userid, $ramo, TRUE);
	}

	public function simulate( $userid = null, $ramo = null )
	{
		$this->show_meta = false;
		$this->_new_common_simulator($userid, $ramo, FALSE);
	}

	public function print_index( $userid = null, $ramo = null )
	{
		$segments = $this->uri->rsegment_array();
		$segments[1] = 'simulator';
		redirect(implode('/', $segments), 'refresh' );
	}

	public function print_simulate( $userid = null, $ramo = null )
	{
		$segments = $this->uri->rsegment_array();
		$segments[1] = 'simulator';
		redirect(implode('/', $segments), 'refresh' );
	}

	private function _new_common_simulator($userid = null, $ramo = null, $is_meta = TRUE)
	{
		$this->_init_profile();
		$agentid = $this->user->getAgentIdByUser( $userid );
		if (!$this->access || !$agentid)
		{
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Simulador" o no tiene permisos ver el simulator de este usuario. Informe a su administrador.'
			));
			redirect( 'home', 'refresh' );
		}
		$users = $this->user->getForUpdateOrDelete( $userid );
		
		$this->load->helper('simulator/simulator');
		$content_data = meta_simulator_view( $users, $userid, $agentid, $ramo, false, false, $is_meta );

		$sub_page_content = $this->load->view('simulator/overview_new', $content_data, true);

		if ($this->show_meta)
			$show_hide = '$("#simulator-section").hide(); $("#meta-section").show();';
		else
			$show_hide = '$("#meta-section").hide(); $("#simulator-section").show();';		

		$content_data['scripts'][] = '
<script type="text/javascript">
$( document ).ready( function(){
	' . $show_hide . 
'
});
</script>
';
		$base_url = base_url();
		// Config view
		$this->view = array(
			'title' => 'Director',
			'user' => $this->sessions,
			'user_vs_rol' => $this->user_vs_rol,
			'roles_vs_access' => $this->roles_vs_access,
			'css' => array_merge(
				array(
					'<link rel="stylesheet" href="'. $base_url .'director/assets/style/director.css">',
				),
				$content_data['css']),
			'scripts' =>  array_merge(
				array(
				'<script src="'. $base_url .'scripts/config.js"></script>',
				'<script type="text/javascript" src="'.$base_url.'director/assets/scripts/director.js"></script>',
				),
				$content_data['scripts']),
			'content' => 'director/director_profile',
			'message' => $this->session->flashdata('message'),
			'sub_page_content' => $sub_page_content,
		);

		// Render view 
		$this->load->view( 'index', $this->view );	
	}

//////////////////
	public function sales_activities()
	{
		$this->_init_profile();
		if ( !$this->access_activity_list )
		{
			$this->session->set_flashdata( 'message', array
			( 
				'type' => false,	
				'message' => 'No tiene permisos para ver la "ACTIVIDAD DE VENTAS" en la sección "Director Commercial". Informe a su administrador para que le otorge los permisos necesarios.'
			));	
			redirect( 'home', 'refresh' );
		}

		if (isset($this->query_filters['query']) && isset($this->query_filters['query']['ramo']))
			$ramo = $this->query_filters['query']['ramo'];
		else
			$ramo = 1;

		$this->load->helper( array('ot/ot' ));
		if (count($_POST))
		{
			update_custom_period($this->input->post('cust_period_from'),
				$this->input->post('cust_period_to'), FALSE);
		}
		$periodo = get_filter_period();

		$users = $this->user->get_filtered_agents($this->query_filters);
		if (is_array($users) && !count($users))
		{
			$stats = $this->work_order->init_operation_result($ramo, TRUE);
		}
		else
		{
			$this->work_order->init_operations(null, $periodo, $ramo);
			if ($users)
				$this->work_order->add_operation_where(array('policies_vs_users.user_id' => array_keys($users)));
			$add_where = $ramo ? array('t2.name' => 'NUEVO NEGOCIO') : NULL;
			$stats = $this->work_order->operation_stats($ramo, $add_where, TRUE);
		}

		$inline_js = get_agent_autocomplete_js($this->agent_array);
		$base_url = base_url();

		$text_ramo_arr = array(1 => 'vida', 2 => 'gmm', 3 => 'autos');
		$report_lines = $this->load->view('operations/stats_details',
			array('stats' => $stats, 'ramo' => $text_ramo_arr[$ramo]), TRUE);
		/*call the helper*/
		$this->load->helper('agent/generations');
		$generations_list = getGenerationDropDown();
		/*call the helper*/
		$content_data = array(
			'manager' => $this->user->getSelectsGerentes2(),
			'period_form' => show_custom_period(),
			'period_fields' => show_period_fields('director', $this->other_filters['ramo']),
			'other_filters' => $this->other_filters,
			'report_lines' => $report_lines,
			'export_xls' => $this->access_export_xls,
			'page' => 'activity_distribution',
			'generations_list'=> $generations_list
			);
		$filter_view = $this->load->view('filters/report', $content_data, true);

		$sub_page_content = $this->load->view('director/report',
			array_merge($content_data, array('filter_view' => $filter_view)), true);

		$base_url = base_url();

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
				'director/stat_details/' . $ramo . '/" + linkId ' . ' + ".html";
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

			$inline_js .= 
'
<script type="text/javascript">
	$( document ).ready(function() {
		$("#export").bind( "click", function(){
			$( "#form" ).attr( "action", "' . $base_url . 'director/sales_activity_export.html" );
			$( "#form" ).submit();
		});

	});
</script>
';
		// Config view
		$this->view = array(
			'title' => 'Director',
			'user' => $this->sessions,
			'user_vs_rol' => $this->user_vs_rol,
			'roles_vs_access' => $this->roles_vs_access,
			'css' => array(
				'<link href="'. $base_url .'ot/assets/style/theme.default.css" rel="stylesheet">',
				'<link rel="stylesheet" href="'. $base_url .'ot/assets/style/main.css">',
				'<link rel="stylesheet" href="'. $base_url .'director/assets/style/director.css">',
				'<link href="'. $base_url .'ot/assets/style/report.css" rel="stylesheet">',
                            '<link rel="stylesheet" href="'. $base_url .'ot/assets/style/jquery.fancybox.css">',
			),
			'scripts' =>  array(
				'<script type="text/javascript" src="'.$base_url.'plugins/jquery-validation/jquery.validate.js"></script>',
				'<script type="text/javascript" src="'.$base_url.'plugins/jquery-validation/es_validator.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'scripts/config.js"></script>',
				'<script type="text/javascript" src="' . $base_url . 'director/assets/scripts/filter_helper.js"></script>',
				'<script type="text/javascript" src="' . $base_url .'director/assets/scripts/director.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'scripts/select_period.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'operations/assets/scripts/operations.js"></script>',
                                '<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.tablesorter-2.14.5.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.tablesorter.widgets-2.14.5.js"></script>',
                            '<script type="text/javascript" src="'.$base_url.'ot/assets/scripts/jquery.fancybox.js"></script>',
				$add_js,
				$inline_js,
			),
			'content' => 'director/director_profile',
			'message' => $this->session->flashdata('message'),
			'sub_page_content' => $sub_page_content,
		);

		// Render view 
		$this->load->view( 'index', $this->view );	
	}

	public function sales_activities_export()
	{
		echo 'TODO';
	}

	private function _init_profile()
	{
		if( !$this->access )
		{
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Director". Informe a su administrador.'
			));
			redirect( 'home', 'refresh' );
		}
		
		$this->load->model( array( 'activities/activity', 'simulator/simulators', 'ot/work_order' ) );
	}

// Popup pertaining to payments (NOTE: copied and pasted of ot/payment_popup.html code)
	public function payment_popup()
	{
		$data = array('values' => FALSE,
			'access_update' => $this->access_update_ot,
			'access_delete' => $this->access_delete_ot,
		);
		$this->load->model('usuarios/user');
		$filter = array();
		$posted_filter_query = $this->input->post('query');
		if ( ($posted_filter_query !== FALSE) )
			$filter['query'] = $posted_filter_query;

		$request_type = $this->input->post('type');
        
        //Switch statement to determine what kind of popup will get called, depending on the type requested by post.
		switch ( $request_type )
		{
			case 'negocio':
				$data['values'] = $this->user->getNegocioDetails( $this->input->post('for_agent_id'), $filter );
				break;
			case 'negociopai':
				$data['values'] = $this->user->getPrimaDetails( $this->input->post('for_agent_id'), $filter, true);
				$data['negociopai'] = array ("negociopai" => true);
				break;
			case 'prima':
				$data['values'] = $this->user->getPrimaDetails( $this->input->post('for_agent_id'), $filter );
				$variable = $this->user->getPrimaDetails( $this->input->post('for_agent_id'), $filter );
				foreach ($variable as $key ) {
					$array_wo[] = $this->user->getWOId($key->policy_number);
				}
				$data['wo'] = $array_wo;
				break;
			case 'cartera':
				$data['values'] = $this->user->getCarteraDetails( $this->input->post('for_agent_id'), $filter );
				break;
			case 'cobranza':
				$data['values'] = $this->user->getCobranzaDetails( $this->input->post('for_agent_id'), $filter );
				$this->load->view('ot/popup_cobranza', $data);
				return;
				break;	
			default:
				exit('Ocurrio un error. Consulte a su administrador.');
				break;
		}
		$this->load->view('ot/popup_payment', $data);
	}

// Export stat recap
// Copied from operations code
	public function stat_recap_export()
	{
		$this->_init_profile();

		if ( !$this->access_export_xls || !$this->access_activity_list)
		{	
			$this->session->set_flashdata( 'message', array
			( 
				'type' => false,	
				'message' => 'No tiene permisos para exportar el reporte "Actividad de ventas" en la sección "Perfil del director", informe a su administrador para que le otorge los permisos necesarios.'
			));	
			redirect( 'home', 'refresh' );
		}

		$na_value = 'N/D';

		$periodo = get_filter_period();

		if (isset($this->query_filters['query']) && isset($this->query_filters['query']['ramo']))
			$ramo = $this->query_filters['query']['ramo'];
		else
			$ramo = 1;

		$users = $this->user->get_filtered_agents($this->query_filters);
		if (is_array($users) && !count($users))
		{
			$stats = $this->work_order->init_operation_result($ramo, TRUE);
		}
		else
		{
			$this->work_order->init_operations(null, $periodo, $ramo);
			if ($users)
				$this->work_order->add_operation_where(array('policies_vs_users.user_id' => array_keys($users)));
			$add_where = $ramo ? array('t2.name' => 'NUEVO NEGOCIO') : NULL;
			$stats = $this->work_order->operation_stats($ramo, $add_where, TRUE);
		}
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

		// Export
		$this->load->helper('usuarios/csv');
		$ramo_text_arr = array(1 => 'vida', 2 => 'gmm', 3 => 'autos');
		$filename = 'director_ventas_distribucion_' . $ramo_text_arr[$ramo] . '.csv';

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
// Copied from operations code
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

		$this->_init_profile();
		if ( !$this->access_export_xls || !$this->access_activity_list)
		{	
			$this->session->set_flashdata( 'message', array
			( 
				'type' => false,	
				'message' => 'No tiene permisos para exportar el reporte "Actividad de ventas" en la sección "Perfil del director", informe a su administrador para que le otorge los permisos necesarios.'
			));	
			redirect( 'home', 'refresh' );
		}
		$stats = $this->_read_details($stat_type, $status);
		if (!$stats)
		{
			echo 'Ocurrio un error.';
			exit();
		}
		$full = ($this->uri->segment(5, '') == 'full');
		if ($full)
			$data_report = array(array(
				'Producto',
				'Negocios',
				'%',
				'Primas Totales',
				'%',
				'Prima Promedio',
			));
		else
			$data_report = array(array(
				'Producto',
				'Negocios',
				'%'
			));		
		$na_value = 'N/D';
		foreach ($stats as $key => $value)
		{
			if ($key && $value['value'])
			{
				if ($full)
					$data_report[] = array(
						$value['label'],
						$value['value'],
						$stats[0]['value'] ? round(100 * $value['value'] / $stats[0]['value']) . '%' : $na_value,
						'$ ' . number_format($value['prima'], 2),
						$stats[0]['prima'] ? round(100 * $value['prima'] / $stats[0]['prima']) . '%' : $na_value,
						$value['value'] ? '$ ' . number_format($value['prima'] / $value['value'], 2) : $na_value
					);
				else
					$data_report[] = array(
						$value['label'],
						$value['value'],
						$stats[0]['value'] ? round(100 * $value['value'] / $stats[0]['value']) . '%' : $na_value,
					);				
			}
		}
		if ($full)
			$data_report[] = array(
				'Total',
				$stats[0]['value'],
				$stats[0]['value'] ? '100%' : $na_value,
				'$ ' . number_format($stats[0]['prima'], 2),
				$stats[0]['prima'] ? '100%' : $na_value,
				$stats[0]['value'] ? '$ ' . number_format($stats[0]['prima'] / $stats[0]['value'], 2) : $na_value
				);
		else
			$data_report[] = array(
				'Total',
				$stats[0]['value'],
				$stats[0]['value'] ? '100%' : $na_value,
				);
		$types_text = array(1 => 'vida', 2 => 'gmm', 3 => 'autos');
		$filename = 'director_ventas_distribucion_' . $types_text[$stat_type] . '_' . $status . '_detalles.csv';

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

	
// Init report processing (reporte and exportar)
// To do: factorise with _init_report in ot module
	private function _init_planning_report($meta = false)
	{
		$data = array();
		if( !empty( $_POST ) )
		{
			$filter = $_POST;
//			$data = $this->user->getReport($_POST, $meta );
		}
		else
		{
			$default_filter = get_filter_period();
			$query = array_merge($this->other_filters, array('periodo' => $default_filter));
			$filter = array('query' => $query );
//			$data = $this->user->getReport( array('query' => $query ), $meta );
		}
		$data = $this->user->getReport($filter, $meta );

		if ($meta && $data)
		{
			$month_start = 1;	// To refine
			$month_end = 12;	// To refine
			$agent_ids = array();
			foreach ($data as $key => $datum)
			{
				if (isset($datum['agent_id']))
					$agent_ids[$key] = $datum['agent_id'];
			}
			$meta_data = $this->simulators->meta_rows( 'meta_new', $agent_ids, $filter['query']['ramo']);
			$meta_period = $this->simulators->get_meta_period();
			$this->work_order->init_operations(null, $filter['periodo'], $filter['query']['ramo']);
			if ($agent_ids)
				$this->work_order->add_operation_where(array('policies_vs_users.user_id' => $agent_ids));
			$add_where =  array('t2.name' => 'NUEVO NEGOCIO');
			$nuevo_negocios = $this->work_order->solicitudes_ingresadas($filter['query']['ramo'], $add_where);

			$negocios_primas = $this->user->get_negocios_primas($agent_ids, $filter);

			foreach ($data as $key => $value)
			{
				if ($key)
				{
					if (isset($meta_data[$data[$key]['agent_id']]))
					{
						foreach ($meta_data[$data[$key]['agent_id']] as $year => $meta_row)
						{
							$min_month = 1;
							$max_month = 12;
							if ($year == $meta_period['start_year'])
								$min_month = (int)$meta_period['start_month'];
							if ($year == $meta_period['end_year'])
								$max_month = (int)$meta_period['end_month'];
							for ($i = $min_month; $i <= $max_month; $i++)
							{
								$prima_promedio = (float)$meta_data[$data[$key]['agent_id']][$year]->{'primas-meta-' . $i}  / 
									(float)$meta_data[$data[$key]['agent_id']][$year]->primas_promedio ;
								$data[$key]['solicitudes_meta'] += 100 * $prima_promedio / $meta_data[$data[$key]['agent_id']][$year]->efectividad;
								$data[$key]['negocios_meta'] += $prima_promedio;								
								$data[$key]['primas_meta'] += $meta_data[$data[$key]['agent_id']][$year]->{'primas-meta-' . $i};
							}
						}
					}
					if (isset($nuevo_negocios[$data[$key]['agent_id']]))
						$data[$key]['solicitudes_ingresadas'] = $nuevo_negocios[$data[$key]['agent_id']];
					if (isset($negocios_primas['negocios'][$data[$key]['agent_id']]))
						$data[$key]['negocios_pagados'] = $negocios_primas['negocios'][$data[$key]['agent_id']];
					if (isset($negocios_primas['primas'][$data[$key]['agent_id']]))
						$data[$key]['primas_pagadas'] = $negocios_primas['primas'][$data[$key]['agent_id']];
				}
			}
		}
		return $data;
	}

	private function _init_filters()
	{
		$this->agent_array = $this->user->getAgents( FALSE );

		$this->load->helper('filter');
		$default_filter = get_filter_period();
		$this->other_filters = array(
			'ramo' => 1,
			'gerente' => '',
			'agent' => '',
            'prime_type' => 'amount',
			'generacion' => '', // not sure if this should not be 1 instead
			'agent_name' => '',
			'policy_num' => '',
			'activity_view' => 'normal',
			'coordinators' => '',
		);
		$this->custom_filters->set_array_defaults($this->other_filters);
		get_generic_filter($this->other_filters, $this->agent_array);

		$page = $this->uri->segment(2, '');
		if (($this->other_filters['ramo'] < 1) && ($page != 'ot_list') && ($page != 'find'))
			$this->other_filters['ramo'] = 1;

		if( $this->input->post())
		{
			if ( isset($_POST['query']['periodo']) && $this->form_validation->is_natural_no_zero($_POST['query']['periodo']) &&
				($_POST['query']['periodo'] <= 4) )
				set_filter_period($_POST['query']['periodo']);

			$filters_to_save = array();
			if ( isset($_POST['query']['ramo']) && $this->form_validation->is_natural_no_zero($_POST['query']['ramo']) &&
				($_POST['query']['ramo'] <= 3) )
				$filters_to_save['ramo'] = $_POST['query']['ramo'];
			if ( isset($_POST['query']['gerente']) && 
				(($_POST['query']['gerente'] == '') || 
				$this->form_validation->is_natural_no_zero($_POST['query']['gerente']))
				)
				$filters_to_save['gerente'] = $_POST['query']['gerente'];
			if ( isset($_POST['query']['agent']) &&
				(  ($_POST['query']['agent'] == '') || 
				( $this->form_validation->is_natural_no_zero($_POST['query']['agent']) &&
				($_POST['query']['agent'] <= 3))   )
				)
				$filters_to_save['agent'] = $_POST['query']['agent'];
			if ( isset($_POST['query']['generacion']) )
				$filters_to_save['generacion'] = $_POST['query']['generacion'];
			if ( isset($_POST['query']['agent_name']))
				$filters_to_save['agent_name'] = $_POST['query']['agent_name'];
			if ( isset($_POST['query']['policy_num']))
				$filters_to_save['policy_num'] = $_POST['query']['policy_num'];

			if ( isset($_POST['activity_view']) && 
				(($_POST['activity_view'] == 'normal') || ($_POST['activity_view'] != 'efectividad')) )
				$filters_to_save['activity_view'] = $_POST['activity_view'];
			if (isset($_POST['query']['prime_type'])) {
				$filters_to_save['prime_type'] = $_POST['query']['prime_type'];
			}
			if (isset($_POST['coordinator_name']))
				$filters_to_save['coordinators'] = extract_coordinator_name($_POST['coordinator_name']);
			$this->custom_filters->set_filters_to_save($filters_to_save);
			generic_set_report_filter( $filters_to_save, $this->agent_array );
			foreach ($filters_to_save as $key => $value)
				$this->other_filters[$key] = $value;
			$result = $_POST;
		}
		else
		{
			$result = array('query' => array_merge($this->other_filters, array('periodo' => $default_filter)) );
		}
		return $result;
	}

	public function stat_details()
// Inspired from operations/stat_details code
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

		$this->_init_profile();

		if ( !$this->access_report )
		{
			echo 'No tiene permisos para ver el reporte "Actividad de ventas" en la sección "Perfil de director", informe a su administrador para que le otorge los permisos necesarios.';
			exit();
		}
		$stats = $this->_read_details($stat_type, $status);

		if (!$stats)
		{
			echo 'Ocurrio un error.';
			exit();
		}
		$data = array('stats' => $stats, 'stat_type' => $stat_type, 'status' => $status);
		$this->load->view( 'operations/details_ramo', $data );
	}
        public function ot_por_producto(){
            	$valid_stat_types = array(1 => 1, 2 => 2, 3 => 3);
		$valid_status = array(
			'tramite' => 'tramite', 'pagada' => 'pagada',
			'canceladas' => 'canceladas', 'NTU' => 'NTU',
			'pendientes_pago' => 'pendientes_pago', 'activadas' => 'activadas',
			'todos' => 'todos');
		$stat_type = $this->uri->segment(3, 0);
		$status = $this->uri->segment(4, 0);
        $periodo = $this->uri->segment(5, 0);
        $user = $this->uri->segment(6, 0);
        if ($periodo == 4){
            $cust_periodo = $this->uri->segment(7, 0);
            $prod_segment = $this->uri->segment(8, 0);
        } else {
            $cust_periodo = null;
            $prod_segment = $this->uri->segment(7, 0);
        }
		$query_settings = array('periodo' => $periodo, 'user_id' => $user, 'cust_periodo' => $cust_periodo, 'source' => 'ot_por_producto');

                $this->_init_profile();
		if (!isset($valid_stat_types[$stat_type]) || !isset($valid_status[$status]))
		{
			echo 'Ocurrio un error.';
			exit();
		}
                
		if ($prod_segment && ($prod_segment != 'total'))
			$prod_id = array('products.id' => (int) $prod_segment);
		else
			$prod_id = array();
		$ots = $this->_read_details($stat_type, $status,FALSE,$prod_id,$query_settings);
		$this->load->view( 'ot_list', array('data' => $ots) ); 
        }

        private function _read_details($ramo = NULL, $status = NULL,$get_ot_list = TRUE, $prod_id = NULL, $qs = NULL)
	{
		$this->load->helper('filter');
		$periodo = get_filter_period();
                get_generic_filter($this->other_filters, array());
                
		$users = $this->user->get_filtered_agents(array('query' => $this->other_filters)); 
                if (is_array($users) && !count($users))
		{
//			$result = $this->work_order->init_operation_result($ramo, FALSE, FALSE);
			$result = $this->work_order->init_operation_result($ramo, FALSE, TRUE);
		}
		else
		{
			$this->work_order->init_operations(null, $periodo, $ramo);
			if ($users)
				$this->work_order->add_operation_where(array('policies_vs_users.user_id' => array_keys($users)));
                        if (!is_null($prod_id)){
			$this->work_order->add_operation_where($prod_id);
                        $result = $this->work_order->operation_detailed($ramo, $status, $get_ot_list,TRUE,$qs);    
                        }
                        else{
                        $result = $this->work_order->operation_detailed($ramo, $status, $get_ot_list);
                        }
		}

		return $result;		
	}

	public function activities( $userid = null )
	{
		// Check user privilege
		if( !$this->access_sales_activities) {
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				'type' => false,
				'message' => 'No tiene permisos para ver la "ACTIVIDAD DE VENTAS" en la sección "Director Commercial". Informe a su administrador para que le otorge los permisos necesarios.'
			));
			redirect( 'home', 'refresh' );
		}
		$this->load->model( 'activities/activity' );

		if (count($_POST))
		{
			update_custom_period($this->input->post('cust_period_from'),
				$this->input->post('cust_period_to'), FALSE);
		}

		$this->load->helper('tri_cuatrimester');
		$selection_filters = array(
			'periodo' => $this->query_filters['query']['periodo'],
			'begin' => '', 'end' => '');
		get_new_period_start_end($selection_filters);
		/*call the helper*/
		$this->load->helper('agent/generations');
		$generations_list = getGenerationDropDown();
		/*call the helper*/
		$filter_data = array(
			'manager' => $this->user->getSelectsGerentes2(),
			'period_form' => show_custom_period(),
			'period_fields' => show_period_fields('director', $this->other_filters['ramo']),
			'other_filters' => $this->other_filters,
			'selection_filters' => $selection_filters,
//			'report_lines' => '',
			'export_xls' => false,
//			'page' => 'activity_distribution'
			'generations_list'=> $generations_list
			);
		$filter_view = $this->load->view('filters/report', $filter_data, true);
		$data = array('totals' => 
			array('VIDA_negocios' => 0,
				'GMM_negocios' => 0));

		$user_string = '';
		$filter_on_users = $this->query_filters['query']['gerente'] ||
			$this->query_filters['query']['agent'] ||
			$this->query_filters['query']['generacion'] ||
			$this->query_filters['query']['agent_name'] ||
			$this->query_filters['query']['policy_num'];
		if ($filter_on_users)
		{
			$users = $this->user->get_filtered_agents($this->query_filters);
			if ($users)
			{
				foreach ($users as $u_key => $u_value)
					$user_string .= sprintf("A N [ID: %d]\n", $u_key);
			}
		}
		if (!$filter_on_users || $user_string)
		{
			$selection_filters['agent_name'] = $user_string;
			$data = $this->activity->sales_activity($selection_filters, TRUE);
		}

		$content_data = array(
			'other_filters' => $this->other_filters,
			'filter_view' => $filter_view,
			'data' => $data,
		);
		$sub_page_content = $this->load->view('sales_activities', $content_data, true);

		$inline_js = get_agent_autocomplete_js($this->agent_array, '#sales-activity-form');
		$inline_js .=
'
<script type="text/javascript">
	$( document ).ready( function(){ 
		$("#sales-activity-normal").show();
		$("#sales-activity-efectividad").hide();
		$("#view-normal").bind( "click", function(){
			$("#sales-activity-normal").show();
			$("#sales-activity-efectividad").hide();
		})
		$("#view-efectividad").bind( "click", function(){
			$("#sales-activity-normal").hide();
			$("#sales-activity-efectividad").show();
		})
		$(".negocios_class").bind( "click", function(){
			var current = $(this);
			var url = "";
			if (current.hasClass("vida")) {
				url = "director/activity_details/1/pagada.html";
			} else if (current.hasClass("gmm")) {
				url = "director/activity_details/2/pagada.html";
			}
			if (url.length > 0) {
				$.fancybox.showLoading();
				$.post(Config.base_url() + url,
					function(data) { 
						if	(data) {
							$.fancybox({
								content:data
							});
							return false;
						}
					});
			}
			return false;
		})

	});
</script>
';
		if (!$this->other_filters['agent_name'])
			$inline_js .= '
<script type="text/javascript">
	$( document ).ready( function(){
		$(".agent-row").hide();
		$(".agent-view").bind( "click", function(){
			var current = $(this);
			var currentId = current.attr("id");
			if (currentId == "all-agents-totals")
				$(".agent-row").hide();
			else
				$(".agent-row").show();
		});
	});
</script>
';
		$base_url = base_url();
		$this->view = array(
		  'title' => 'Director',
		    // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'css' => array(
			'<link rel="stylesheet" href="'. $base_url .'director/assets/style/director.css">',
		  	'<link href="'. $base_url . 'activities/assets/style/create.css" rel="stylesheet">',
			'<link href="'. $base_url . 'activities/assets/style/table_sorter.css" rel="stylesheet">',
			'<link rel="stylesheet" href="'. $base_url .'ot/assets/style/jquery.fancybox.css">',
			'<link rel="stylesheet" href="'. $base_url .'activities/assets/style/activity_sales.css">',
		  ),
		  'scripts' =>  array(
			'<script type="text/javascript" src="'.$base_url.'plugins/jquery-validation/jquery.validate.js"></script>',
			'<script type="text/javascript" src="'.$base_url.'plugins/jquery-validation/es_validator.js"></script>',
			'<script src="'.$base_url.'scripts/config.js"></script>',
			'<script src="'.$base_url.'activities/assets/scripts/activities.js"></script>',
			'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.tablesorter-2.14.5.js"></script>',
			'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.tablesorter.widgets-2.14.5.js"></script>',
			'<script src="' . $base_url . 'activities/assets/scripts/sales_activity_report.js"></script>',
			'<script src="' . $base_url . 'ot/assets/scripts/jquery.fancybox.js"></script>',
			'<script type="text/javascript" src="'. $base_url .'scripts/select_period.js"></script>',
			$inline_js,
		  ),
		  'content' => 'director/director_profile',
		  'message' => $this->session->flashdata('message'),
		  'sub_page_content' => $sub_page_content,
		);

		// Render view 
		$this->load->view( 'index', $this->view );
	}

	public function activity_details()
	{
		$valid_stat_types = array(1 => 1, 2 => 2, 3 => 3);
		$stat_type = $this->uri->segment(3, 0);
		$status = $this->uri->segment(4, 0);
		if (!isset($valid_stat_types[$stat_type]) || ($status != 'pagada'))
		{
			echo 'Ocurrio un error.';
			exit();
		}

		$this->_init_profile();

		if ( !$this->access_report )
		{
			echo 'No tiene permisos para ver el reporte "Actividad de ventas" en la sección "Perfil de director", informe a su administrador para que le otorge los permisos necesarios.';
			exit();
		}
		$stats = $this->_read_details($stat_type, $status);
		
		if (!$stats)
		{
			echo 'Ocurrio un error.';
			exit();
		}
		$data = array('stats' => $stats, 'stat_type' => $stat_type, 'status' => $status);
		$this->load->view( 'operations/details_ramo', $data );
	}

	public function ot()
// Inspired from the code operations/statistics/recap
	{
		$this->_init_profile();
		if ( !$this->access_report )
		{	
			$this->session->set_flashdata( 'message', array
			( 
				'type' => false,	
				'message' => 'No tiene permisos para ver el reporte "Ordones de trabajo" en la sección "Perfil de director", informe a su administrador para que le otorge los permisos necesarios.'
			));	
			redirect( 'home', 'refresh' );
		}
		$this->load->helper( array('ot/ot' ));
		if (count($_POST))
		{
			update_custom_period($this->input->post('cust_period_from'),
				$this->input->post('cust_period_to'), FALSE);
		}
		$periodo = get_filter_period();
		$ramo = NULL;

		if ($this->other_filters['coordinators'])
			$selected_coordinators = explode('_', $this->other_filters['coordinators']);		
		else
			$selected_coordinators = array();

		$users = $this->user->get_filtered_agents($this->query_filters);
		if (is_array($users) && !count($users))
		{
			$stats = $this->work_order->init_operation_result($ramo, TRUE);
		}
		else
		{
			$this->work_order->init_operations($this->other_filters['coordinators'], $periodo, $ramo);
			if ($users)
				$this->work_order->add_operation_where(array('policies_vs_users.user_id' => array_keys($users)));
			$add_where = $ramo ? array('t2.name' => 'NUEVO NEGOCIO') : NULL;
			$stats = $this->work_order->operation_stats($ramo, $add_where, TRUE);
		}

		$inline_js = get_coordinator_form_field($selected_coordinators) . 
			get_agent_autocomplete_js($this->agent_array);
		$base_url = base_url();
		$content_data = array(
			'period_fields' => show_period_fields('director', 55),
			'other_filters' => $this->other_filters,
			'stats' => $stats,
			'coordinator_select' => $this->coordinator_select
			);
		$sub_page_content = $this->load->view('stats_recap', $content_data, true);

		$add_js = '
<script type="text/javascript">
	var submitThisForm = function() {
		$("#form").submit();
	}
</script>
';

		// Config view
		$this->view = array(
			'title' => 'Director',
			 // Permisions
			'user' => $this->sessions,
			'user_vs_rol' => $this->user_vs_rol,
			'roles_vs_access' => $this->roles_vs_access,
			'css' => array(
				'<link href="' . $base_url . 'ot/assets/style/theme.default.css" rel="stylesheet">',
				'<link href="' . $base_url . 'ot/assets/style/report.css" rel="stylesheet">',
				'<link rel="stylesheet" href="' . $base_url . 'ot/assets/style/main.css">',
				'<link rel="stylesheet" href="'. $base_url .'agent/assets/style/agent.css">', // TO CHECK
				'<link rel="stylesheet" href="'. $base_url .'operations/assets/style/operations.css">',
			),
			'scripts' => array(
				'<script src="' . $base_url . 'scripts/config.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'scripts/select_period.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'operations/assets/scripts/operations.js"></script>',
				$add_js,
				$inline_js
			),
			'content' => 'director/director_profile',
			'message' => $this->session->flashdata('message'),
			'sub_page_content' => $sub_page_content,
		);

		// Render view 
		$this->load->view( 'index', $this->view );
	}

	public function ot_list()
	{
		if ($this->default_period_filter == 5)
			set_filter_period( 2 );

		$this->_init_profile();
		if ( !$this->access_report )
		{	
			$this->session->set_flashdata( 'message', array
			( 
				'type' => false,	
				'message' => 'No tiene permisos para ver el reporte "Ordones de trabajo" en la sección "Perfil de operaciones", informe a su administrador para que le otorge los permisos necesarios.'
			));	
			redirect( 'home', 'refresh' );
		}
		$this->load->helper( array('ot/ot'));
		if (count($_POST))
		{
			update_custom_period($this->input->post('cust_period_from'),
				$this->input->post('cust_period_to'), FALSE);
		}
		$base_url = base_url();
		$ramo = $this->uri->segment(3, 1);
		$this->other_filters['ramo'] = $ramo;
//generic_set_report_filter
		
		$gerente_str = '';
		$agente_str = '<option value="">Todos</option>';
		$ramo_tramite_types = array();
		$patent_type_ramo = 0;
		prepare_ot_form($this->other_filters, $gerente_str, $agente_str, $ramo_tramite_types, $patent_type_ramo);

		if ($this->other_filters['coordinators'])
			$selected_coordinators = explode('_', $this->other_filters['coordinators']);  
		else
			$selected_coordinators = array();
		$inline_js = get_coordinator_form_field($selected_coordinators);

		$content_data = array(
			'access_all' => $this->access_report,
			'period_fields' => show_period_fields('director', $ramo),
			'agents' => $agente_str,
			'gerentes' => $gerente_str,
			'export_url' => $base_url . 'operations/report_export/' .  $this->user_id . '.html',
			'coordinator_select' => $this->coordinator_select,
			'other_filters' => $this->other_filters
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
//		getLinks();
	}
//	getLinks();
</script>
';
		// Config view
		$this->view = array(
			'title' => 'Director',
			 // Permisions
			'user' => $this->sessions,
			'user_vs_rol' => $this->user_vs_rol,
			'roles_vs_access' => $this->roles_vs_access,
			'css' => array(
				'<link href="' . $base_url . 'ot/assets/style/theme.default.css" rel="stylesheet">',
				'<link href="' . $base_url . 'ot/assets/style/report.css" rel="stylesheet">',
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
		Config.findUrl = "director/find.html";
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
				$inline_js
			),
			'content' => 'director/director_profile',
			'message' => $this->session->flashdata('message'),
			'sub_page_content' => $sub_page_content,
		);

		// Render view 
		$this->load->view( 'index', $this->view );	
	
	}

	
// List OTs
// Inspired from the code of agent/find and operations/find:
	public function find()
	{
		// If is not ajax request redirect
		if	( !$this->input->is_ajax_request() )
			redirect( '/', 'refresh' );
		$this->_init_profile();
		$data = $this->_read_ots();
		$view_data = array('data' => $data);
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
		$data = get_ot_data($this->other_filters, $this->access_report);
		return $data;
	}

	public function add_payment()
	{
		if	( !$this->input->is_ajax_request() )
			redirect( '/', 'refresh' );

		$this->_init_profile();
		$this->load->library('form_validation');

		$this->form_validation->set_rules('product_group', 'Ramo', 'trim|required|in_list[1,2]');
		$this->form_validation->set_rules('agent_id', 'Agente', 'trim|required|exists_in_db[agents.id]');
		$this->form_validation->set_rules('amount', 'Monto pagado', 'trim|required|decimal_or_integer');
		$this->form_validation->set_rules('payment_date', 'Fecha de pago', 'trim|required|is_date');
		$this->form_validation->set_rules('year_prime', 'Año prima', 'trim|required|is_natural_no_zero');
		$this->form_validation->set_rules('business', '¿Es negocio?', 'trim|required|abs_le[1]');

		$result = $this->form_validation->run();
		if (!$result)
		{
			$result = strtr(validation_errors(), array("\n" => '', "\r" => ''));
			echo $result;
			return;
		}
		$now = date( 'Y-m-d H:i:s' );
		$product_group = $this->input->post('product_group');
		$parts = explode('[ID: ', $this->input->post('agent_id'));
		$agent_id = str_replace(']', '', $parts[1]);
		$valid_for_report = $this->input->post('valid_for_report');
		$payment_date = $this->input->post('payment_date');
		$amount = $this->input->post('amount');

		$folio = $this->user->generic_get('agent_uids',
			array('agent_id' => $agent_id, 'type' => $type),
				1, 0, 'id asc');
        $pai = 0;
        if ($this->input->post('year_prime') == 1){
            $pai = $this->user->create_negocio_pai($payment['policy_number'],$product_group, $payment_date, $amount);
        }
		$payment = array(
			'product_group' => $product_group,
			'agent_id' => $agent_id,
			'year_prime' => $this->input->post('year_prime'),
			'currency_id' => 1,
			'amount' => $amount,
			'payment_date' => $payment_date,
			'business' => (int)$this->input->post('business'),
            'pai_business' => $pai,
			'policy_number' => trim($this->input->post('policy_number')),
			'valid_for_report' => $valid_for_report ? 1 : 0,
			'last_updated' => $now,
			'date' => $now,
			'import_date' => $payment_date,
			'imported_agent_name' => trim($parts[0]),
			'imported_folio' => isset($folio[0]) ? $folio[0]->uid : '',
			'agent_generation' => ($product_group == 1) ? 
				$this->user->generationByAgentIdVida($payment_date,$agent_id) : 
				$this->user->generationByAgentIdGmm($payment_date,$agent_id)
		);
        
		$user_id = $this->user->getUserIdByAgentId( $agent_id);

		if ($user_id && ($result = $this->work_order->create( 'payments', $payment )))
		{
			$policy = $this->work_order->getPolicyByUid(  $payment['policy_number'] );
			if ($policy && 
				( (float)$policy[0]['prima'] >= (float)$payment['amount'] ))
			{
				$ot = $this->work_order->getWorkOrderByPolicy(  $policy[0]['id'] );
				if( !empty( $ot ) )
				{
					$work_order = array( 'work_order_status_id' => 4 );
					$this->work_order->update( 'work_order', $ot[0]['id'], $work_order );

				}
			}
			$result = 'true';
		}
		else
			$result = 'Ocurrio un error, consulte a su administrador.';
		echo $result;
	}

//////// Below are page duplicated in ot, agent and director modules
    
	//Function used to update negocio pai using the select box.
    public function change_negocio_pai()
	{
		if ( !$this->input->is_ajax_request() )
			redirect( 'director.html', 'refresh' );

		if ( !$this->access_update )
		{
			echo json_encode('-1');
			exit;
		}
		$negocio_pai = $this->input->post('negocio_pai');
		if (is_array($negocio_pai))
		{
			$this->load->model( 'ot/work_order' );
			foreach ($negocio_pai as $id => $value)
			{
				$result = $this->work_order->generic_update(
					'payments', array('pai_business' => (int) $value), array('pay_tbl_id' => (int)$id), 1, 0) ?
						'1' : '0';
				echo json_encode($result);
				exit();
			}
		}
		echo json_encode('2');
		exit;
	}

	public function change_add_perc()
	{
		if ( !$this->input->is_ajax_request() )
			redirect( 'director.html', 'refresh' );

		if ( !$this->access_update )
		{
			echo json_encode('-1');
			exit;
		}
		$add_perc = $this->input->post('add_perc');

		if (is_array($add_perc))
		{
			$this->load->model( 'ot/work_order' );
			foreach ($add_perc as $id => $value)
			{
				$result = $this->work_order->generic_update(
					'payments', array('add_perc' => (int) $value), array('pay_tbl_id' => (int) $id), 1, 0) ?
						'1' : '0';
				echo json_encode($result);
				exit();
			}
		}
		echo json_encode('2');
		exit;
	}

// mark OT as paid
	public function mark_paid()
	{
		$this->load->helper('ot/ot');
		change_ot_status(4, 'director');
	}

// mark OT as ntu
	public function mark_ntu()
	{
		$this->load->helper('ot/ot');
		change_ot_status(10, 'director');
	}

// actions on payment (ignore, delete)
	public function payment_actions()
	{
		$this->load->helper('ot/ot');
		payment_actions('director');
	}

	public function reporte_popup()
	{
		$this->load->helper('ot/ot');
		reporte_popup('director');
	}

// Delete cobranza payments
	public function delete_cobranza()
	{
		$this->load->helper('ot/ot');
		delete_cobranza('director');
	}
        
        public function repair_negocios_pai(){
            $this->load->model( 'ot/work_order' );
            $this->work_order->mark_polizas_as_paid();
        }

//////// Above are page duplicated in ot, agent and director modules
/* End of file director.php */
/* Location: ./application/modules/director/controllers/director.php */
}
