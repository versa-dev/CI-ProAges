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
class Simulator extends CI_Controller {

	public $view = array();
	
	public $sessions = array();
	
	public $user_vs_rol = array();
	
	public $roles_vs_access = array();
	
	public $access = false; // Force security
	
	public $access_create = false;
	
	public $access_update = false;
	
	public $access_delete = false;
	
	private $for_print = false;
	private $print_meta = true;

	public $input_meta_fields = array(
		'agent_id', 
//'agent_name',
		'efectividad', 'id', 'ramo', 'period', 'year',
		'primas-cuarto-primer', 'primas-meta-1', 'primas-meta-10', 'primas-meta-11', 'primas-meta-12',
		'primas-meta-2', 'primas-meta-3', 'primas-meta-4', 'primas-meta-5', 'primas-meta-6',
		'primas-meta-7', 'primas-meta-8', 'primas-meta-9',
		'primas-negocios-meta-1', 'primas-negocios-meta-10', 'primas-negocios-meta-11', 'primas-negocios-meta-12',
		'primas-negocios-meta-2', 'primas-negocios-meta-3', 'primas-negocios-meta-4', 'primas-negocios-meta-5',
		'primas-negocios-meta-6', 'primas-negocios-meta-7', 'primas-negocios-meta-8', 'primas-negocios-meta-9',
		'primas-segund-primer',
		'primas-solicitud-meta-1', 'primas-solicitud-meta-10', 'primas-solicitud-meta-11',
		'primas-solicitud-meta-12', 'primas-solicitud-meta-2', 'primas-solicitud-meta-3',
		'primas-solicitud-meta-4', 'primas-solicitud-meta-5', 'primas-solicitud-meta-6',
		'primas-solicitud-meta-7', 'primas-solicitud-meta-8', 'primas-solicitud-meta-9',
		'primas-tercer-primer',
		'primaspromedio', 'primas_promedio',
		'prima_total_anual',
	);

	public $input_simulator_fields = array(
		'agent_id',
		'id', 'ramo', 'period', 'year',
		'comisionVentaInicial_1', 'comisionVentaInicial_2',
		'comisionVentaInicial_3', 'comisionVentaInicial_4',
		'comisionVentaRenovacion_1', 'comisionVentaRenovacion_2',
		'comisionVentaRenovacion_3', 'comisionVentaRenovacion_4',
		'noNegocios_1', 'noNegocios_2',
		'noNegocios_3', 'noNegocios_4',
		'periodo',
		'porcentajeConservacion_1', 'porcentajeConservacion_2', // Vida
		'porcentajeConservacion_3', 'porcentajeConservacion_4', // Vida
		'porsiniestridad_1', 'porsiniestridad_2', // GMM
		'porsiniestridad_3', 'porsiniestridad_4', // GMM
		'simulatorPrimasPeriod_1', 'simulatorPrimasPeriod_2',
		'simulatorPrimasPeriod_3', 'simulatorPrimasPeriod_4',
		'primasRenovacion_1', 'primasRenovacion_2',
		'primasRenovacion_3', 'primasRenovacion_4',

		'XAcotamiento_1', 'XAcotamiento_2',
		'XAcotamiento_3', 'XAcotamiento_4',
	);

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
	public $extra_db_fields = array(
		'date', 
	);
	private $indices = array(
		1 => array('min' => 1, 'max' => 1), 	// month results
		2 => array('min' => 2, 'max' => 2),
		3 => array('min' => 3, 'max' => 3),
		4 => array('min' => 4, 'max' => 4),
		5 => array('min' => 5, 'max' => 5),
		6 => array('min' => 6, 'max' => 6),
		7 => array('min' => 7, 'max' => 7),
		8 => array('min' => 8, 'max' => 8),
		9 => array('min' => 9, 'max' => 9),
		10 => array('min' => 10, 'max' => 10),
		11 => array('min' => 11, 'max' => 11),
		12 => array('min' => 12, 'max' => 12),
		121 => array('min' => 1, 'max' => 4),  // Cuatrimestre results
		122 => array('min' => 5, 'max' => 8),
		123 => array('min' => 9, 'max' => 12),
		111 => array('min' => 1, 'max' => 3), // Trimestre results
		112 => array('min' => 4, 'max' => 6),
		113 => array('min' => 7, 'max' => 9),
		114 => array('min' => 10, 'max' => 12),
		0 => array('min' => 1, 'max' => 12),	// year results
		);

	public $misc_filters = FALSE;
	public $agent_array = array();
	public $other_filters = array();

	private $is_agent_only = TRUE;
/** Construct Function **/
/** Setting Load perms **/
	
	public function __construct(){
			
		parent::__construct();
		
		/** Getting Info bu login User **/
		
		$this->load->model( array( 'usuarios/user', 'roles/rol' ) );
				
		// Get Session
		$this->sessions = $this->session->userdata('system');

		// Get user rol		
		$this->user_vs_rol = $this->rol->user_role( $this->sessions['id'] );

		$this->is_agent_only = (count($this->user_vs_rol) == 1) &&
			($this->user_vs_rol[0]['user_role_id'] == 1);

		// Get user rol access
		$this->roles_vs_access = $this->rol->user_roles_vs_access( $this->user_vs_rol );

		// If exist the module name, the user accessed
		if( !empty( $this->user_vs_rol )  and !empty( $this->roles_vs_access ) )	
		foreach( $this->roles_vs_access  as $value ): if( in_array( 'Simulador', $value ) ):

			$this->access = true;
			
		break; endif; endforeach;

		// Added Acctions for user, change the bool access
		if( !empty( $this->user_vs_rol ) and !empty( $this->roles_vs_access ) )	
		foreach( $this->roles_vs_access  as $value ): if( in_array( 'Simulador', $value ) ):

			if( $value['action_name'] == 'Crear' )
				$this->access_create = true;
			if( $value['action_name'] == 'Editar' )
				$this->access_update = true;
			if( $value['action_name'] == 'Eliminar' )
				$this->access_delete = true;	

		endif; endforeach;

		if( empty( $this->sessions ) and $this->uri->segment(2) != 'login'  )
			redirect( 'usuarios/login', 'refresh' );

		$this->misc_filter_name = 'director_misc_filter';
		$this->misc_filters = $this->session->userdata($this->misc_filter_name);
	}

	public function index( $userid = null, $ramo = null )
	{
		$uri_segments = $this->uri->rsegment_array();
		$ramo = $this->_check_submitted_agent($uri_segments);
		$this->_index_common( $userid, $ramo);
	}

	public function print_index( $userid = null, $ramo = null ) {

		$this->for_print = true;
		$this->print_meta = true;
		$this->_index_common( $userid, $ramo);
	}

	public function print_index_simulator( $userid = null, $ramo = null ) {

		$this->for_print = true;
		$this->print_meta = false;
		$this->_index_common( $userid, $ramo);
	}

	// Show all records	
	private function _index_common( $userid = null, $ramo = null ){

		// Check access user access
		if (!$this->access && ($userid != $this->sessions['id']) )
		{
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Simulador" o no tiene permisos ver el simulator de este usuario. Informe a su administrador.'
			));
			redirect( 'home', 'refresh' );
		}
		$simulator = 'vida';
		if( $ramo == 1 ) $simulator = 'vida';
		if( $ramo == 2 ) $simulator = 'gmm';
		if( $ramo == 3 ) $simulator = 'autos';
			
		$this->load->model( array( 'user', 'simulators' ) );

		$agentid = $this->user->getAgentIdByUser( $userid );
		$users = $this->user->getForUpdateOrDelete( $userid );
//		$data = $this->simulators->getByAgent( $agentid, $ramo );

		$period = $this->input->post('period');
		if ($period === FALSE)
			$period = $this->uri->rsegment(5);
		$period = ($period !== FALSE) ? $period : 0;
		$year = $this->input->post('year');
		if ($year === FALSE)
			$year = $this->uri->rsegment(6);
		$year = ($year !== FALSE) ? $year : date('Y');

// TODO: check if data and logrados are also needed here
		$data = $this->simulators->getByAgentNew('meta_new', $agentid, $ramo, $period, $year);

		$trimestre = null;	
		$cuatrimestre = null;
		$logrados = $this->_get_logrados_new($agentid, $ramo, $year, $period, $trimestre, $cuatrimestre);

		$requestPromedio = '<script type="text/javascript">$( document ).ready( function(){ getMetasPeriod( "' .
			$simulator .
			'" ); getMetas(); }); </script>';

		if( !empty( $data ) )
		{		
			if( $data[0]['data']->ramo == 1 )
				$requestPromedio = '<script type="text/javascript">$( document ).ready( function(){ getMetasPeriod( "vida" ); $( "#metas-prima-promedio" ).val( '
					. $data[0]['data']->primas_promedio . 
					' ); getMetas(); }); </script>'; 
			else if( $data[0]['data']->ramo == 2 )
				$requestPromedio = '<script type="text/javascript">$( document ).ready( function(){ getMetasPeriod( "gmm" ); $( "#metas-prima-promedio" ).val( '
					.$data[0]['data']->primaspromedio . 
					' ); getMetas(); }); </script>'; 
			else if( $data[0]['data']->ramo == 3 )
				$requestPromedio = '<script type="text/javascript">$( document ).ready( function(){ getMetasPeriod( "autos" ); $( "#metas-prima-promedio" ).val( '
					.$data[0]['data']->primaspromedio . 
					' ); getMetas(); }); </script>'; 
		}
			
		// Config view
		$js_assets = array(
			'<script type="text/javascript" src="'.base_url().'scripts/config.js"></script>',
			'<script type="text/javascript">Config.currentModule = "simulator";</script>',
			'<script type="text/javascript" src="'.base_url().'simulator/assets/scripts/metas_simulator.js"></script>',			
		);

		if ($this->for_print)
		{
			$css = array(
		  	'<link href="'. base_url() .'simulator/assets/style/simulator.css" rel="stylesheet">',
		  	'<link href="'. base_url() .'simulator/assets/style/simulator_print.css" rel="stylesheet">',
		  );
		  $add_js = '
<script type="text/javascript">
$( document ).ready( function(){
	$("#open_meta").hide();
	$("#print-button").bind( "click", function(){
		$(this).hide(); window.print(); window.close(); return false;}
	);
	$(".main-menu-span").removeClass("span2");
	$("#content").removeClass("span10").addClass("span12");
	$("#meta-footer td").css("font-size", "10px");

	$(".screen-view").hide();
	$(".print-view").show();
	$(":input").prop("readonly", true);

	if (!$("#print-button").hasClass("print-preview"))
		$("#reset-meta").hide();
});
</script>
';
			if ($this->print_meta)
				$js_assets[] = '<script type="text/javascript" src="'.base_url().'simulator/assets/scripts/metas.js"></script>';
			else {
				$js_assets[] = '<script type="text/javascript" src="'.base_url().'simulator/assets/scripts/simulator_'.$simulator.'.js"></script>';
				$requestPromedio = '';
			}
		}
		else
		{
			$css = array(
		  	'<link href="'. base_url() .'simulator/assets/style/simulator.css" rel="stylesheet" media="screen">'
				);
			$uri_segments_meta = $this->uri->rsegment_array();
			$uri_segments_meta[5] = $period;
			$uri_segments_meta[6] = $year;			
			$uri_segments_simulator = $uri_segments_meta;
			$uri_segments_meta[2] = 'print_index';
			$uri_segments_simulator[2] = 'print_index_simulator';
			
			$add_js = '
<script type="text/javascript">
$( document ).ready( function(){
	$("#meta-footer td").css("font-size", "18px");
	$("#print-button").bind( "click", function(){
		if ($(".simulator:visible").length > 0) {
			$(this).attr("href", "' . site_url($uri_segments_simulator) . '");
		} else {
			$(this).attr("href", "' . site_url($uri_segments_meta) . '");
		}
	});

	$(".screen-view").show();
	$(".print-view").hide();
});
</script>
';

			$allow_simulator = $this->config->item('allow_simulator');
			if (($allow_simulator !== FALSE) && !$allow_simulator
				&& $this->is_agent_only)
				$add_js .= '
<script type="text/javascript">
$( document ).ready( function(){
	$("#save_meta").hide();
	$("#reset-meta").hide();
	$("#meta-section :input").prop("disabled", true);
});
</script>
';

			$js_assets[] = '<script type="text/javascript" src="'.base_url().'simulator/assets/scripts/metas.js"></script>';
			$js_assets[] = '<script type="text/javascript" src="'.base_url().'simulator/assets/scripts/simulator_'.$simulator.'.js"></script>';
		}
		$js_assets[] = $requestPromedio;
		$js_assets[] = $add_js;

		$this->load->helper('filter');
		$this->agent_array = $this->user->getAgents( FALSE );
		$js_assets[] = get_agent_autocomplete_js($this->agent_array, '#form');
		$this->other_filters = array(
			'agent_name' => '',
		);
		get_generic_filter($this->other_filters, $this->agent_array);

		$this->view = array(
				
		  'title' => 'Simulador',
		   // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'access_create' => $this->access_create,
		  'access_update' => $this->access_update,
		  'access_delete' => $this->access_delete,
		  'css' => $css,
		  'scripts' => $js_assets,		  
		  'content' => 'simulator/overview', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'no_visible_elements_2' => true,
		  'userid' =>  $userid,
		  'agentid' =>  $agentid,
		  'data' => $data,
		  'config' => $logrados['config'],		  
		  'SolicitudesLogradas' => $logrados['solicitudes'],
		  'NegociosLogrados' => $logrados['negocios'],	
		  'PrimasLogradas' => $logrados['primas'],
		  'trimestre' => $trimestre,
		  'cuatrimestre' => $cuatrimestre,
		  'periodo' => $this->_get_periodo($period, $trimestre, $cuatrimestre),
		  'ramo' => $simulator,
		  'product_group_id' => $ramo,
		  'users' => $users,
		  'for_print' => $this->for_print,
		  'print_meta' => $this->print_meta,
		  'other_filters' => $this->other_filters,
		  'selected_period' => $period,
		  'selected_year' => $year,
		);

		// Render view 
		$this->load->view( 'index', $this->view );	
	}

	public function getSimulator(){
		
		if( !$this->input->is_ajax_request() ) exit;
		$this->load->model( array( 'user', 'simulators' ) );
		$userid = $_POST['userid']; 
		$agentid = $this->user->getAgentIdByUser( $userid );
		if ($_POST['ramo']=="vida") $product_group_id = 1;
		elseif ($_POST['ramo']=="gmm") $product_group_id = 2;
		elseif ($_POST['ramo']=="autos") $product_group_id = 3;

		if (isset($_POST['period']) && isset($_POST['year']))
			$data =  $this->simulators->getByAgentNew( 'meta_new', $agentid, $product_group_id, (int)$_POST['period'], (int)$_POST['year'] );  // TO TEST (how?)
//			$data =  $this->simulators->getByAgentNew( 'simulator_new', $agentid, $product_group_id, (int)$_POST['period'], (int)$_POST['year'] );  // TO TEST (how?)
		else
			$data = $this->simulators->getByAgent( $agentid, $product_group_id );
		
		if( !isset( $_POST['varx'] ) ){
			if( isset( $data[0]['data'] ) )
			 $dataview = array( 'data' => $data[0]['data'] );
			else $dataview = array(); 
			$this->load->view( 'simulator_'.$_POST['ramo'], $dataview );
		}else{
			if( isset( $data[0]['data'] ) )
				echo $data[0]['data']->id;
		}
		
	}

	public function getConfigMeta(){
	
		if( !$this->input->is_ajax_request() ) exit;
		$this->load->model( array( 'user', 'simulators' ) );
		$userid = $_POST['userid'];		
		$agentid = $this->user->getAgentIdByUser( $userid );
		//$agent = $this->user->getAgentsById( $agentid );
		//$userid = $this->user->getUserIdByAgentId( $agentid );
		$trimestre = null;
		$cuatrimestre = null;
		$product_group_id = null;
	    if( isset( $_POST['ramo'] ) and  $_POST['ramo'] == 'vida' ){ 
			$trimestre = $this->simulators->trimestre( date('m') ); 
			$product_group_id = 1; 
		}	
	    if( isset( $_POST['ramo'] ) and  $_POST['ramo'] == 'gmm' ){ 
			$cuatrimestre = $this->simulators->cuatrimestre( date('m') );
			$product_group_id = 2;	
		}
		if( isset( $_POST['ramo'] ) and  $_POST['ramo'] == 'autos' ){
			$cuatrimestre = $this->simulators->cuatrimestre( date('m') );
			$product_group_id = 3;	
		}
		
		if( isset( $_POST['periodo'] ) && ($_POST['periodo'] == 12 )){
			$trimestre = null;
			$cuatrimestre = null; 
		}
		if (isset($_POST['year']) && isset($_POST['period']))
		{
			$posted_year = (int)$_POST['year'];
			$posted_period = (int)$_POST['period'];

			$logrados = $this->_get_logrados_new($agentid, $product_group_id, $posted_year, $posted_period, $trimestre, $cuatrimestre);
			$SolicitudesLogradas = $logrados['solicitudes'];
			$NegociosLogrados = $logrados['negocios'];
			$PrimasLogradas = $logrados['primas'];
			$config = $logrados['config'];
		}
		else
		{
			$posted_year = null;
			$posted_period = null;
			$logrados = $this->_get_logrados_old($agentid, $product_group_id, $trimestre, $cuatrimestre);
			$SolicitudesLogradas = $logrados['solicitudes'];
			$NegociosLogrados = $logrados['negocios'];
			$PrimasLogradas = $logrados['primas'];
			$config = $logrados['config'];
		}

		if( isset( $config ) )
			$dataview = array( 'config' => $config );
		else
			$dataview = array();  

		if ($posted_year === null)
			$data = $this->simulators->getByAgent( $agentid, $product_group_id );
		else
			$data = $this->simulators->getByAgentNew( 'meta_new', $agentid, $product_group_id, $posted_period, $posted_year );

		if (isset($data[0]))
			$dataview['data'] = $data[0]['data'];
		else
			$dataview['data'] = array();

		$dataview['ramo'] = $_POST['ramo'];
		$dataview['SolicitudesLogradas'] = $SolicitudesLogradas;
		$dataview['NegociosLogrados'] = $NegociosLogrados;
		$dataview['PrimasLogradas'] = $PrimasLogradas;
		$dataview['trimestre'] = $trimestre;
		$dataview['cuatrimestre'] = $cuatrimestre;
		$dataview['periodo'] = $this->_get_periodo($posted_period, $trimestre, $cuatrimestre);

		$this->load->view( 'metas', $dataview );

	}

	private function _get_periodo($posted_period, $trimestre, $cuatrimestre)
	{
		if (isset($_POST['periodo']))
			return $_POST['periodo'];
		else
		{
			if ($posted_period === 0)
				return 12;
			elseif ($trimestre !== null)
				return 3;
			elseif ($cuatrimestre !== null)
				return 4;
			else
				return 0;
		}
	}

	private function _get_logrados_new($agentid, $product_group_id, $posted_year, $posted_period, &$trimestre, &$cuatrimestre)
	{
		if (!isset($this->indices[$posted_period]))
			$posted_period = 0;
		$logrados = array(
			'solicitudes' => array(),
			'negocios' => array(),
			'primas' => array(),
			'config' => array()
		);
		for ($i = $this->indices[$posted_period]['min']; $i <= $this->indices[$posted_period]['max']; $i++)
		{
			$key = sprintf("%02d", $i);
			$logrados['solicitudes'][$key] = $this->simulators->getSolicitudLograda( $agentid, $product_group_id, $key, $posted_year );
			$logrados['negocios'][$key] = $this->simulators->getNegociosLograda( $agentid, $product_group_id, $key, $posted_year );
			$logrados['primas'][$key] = $this->simulators->getPrimasLograda( $agentid, $product_group_id, $key, $posted_year );
		}
		$logrados['config'] = $this->simulators->getNewConfigMetas(array(
			'id >= ' => $this->indices[$posted_period]['min'],
			'id <= ' => $this->indices[$posted_period]['max']));

		if (($posted_period >= 111) && ($posted_period <= 114))
		{
			$trimestre = $posted_period - 110;
			$cuatrimestre = null;
		}
		elseif (($posted_period >= 121) && ($posted_period <= 123))
		{
			$trimestre = null;
			$cuatrimestre = $posted_period - 120;
		}
		elseif ($posted_period == 0)
		{
			$trimestre = null;
			$cuatrimestre = null;
		}
		return $logrados;
	}

	private function _get_logrados_old($agentid, $product_group_id, $trimestre, $cuatrimestre)
	{
		$logrados = array();
		if( $trimestre != null and $cuatrimestre == null ){
			if( $trimestre == 1 ){
			
				$logrados['solicitudes'] = array(
					'01' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '01', date( 'Y' ) ),
					'02' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '02', date( 'Y' ) ),
					'03' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '03', date( 'Y' ) )
				);
				
				$logrados['negocios'] = array(
					'01' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '01', date( 'Y' ) ),
					'02' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '02', date( 'Y' ) ),
					'03' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '03', date( 'Y' ) )
				);
				
				$logrados['primas'] = array(
					'01' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '01', date( 'Y' ) ),
					'02' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '02', date( 'Y' ) ),
					'03' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '03', date( 'Y' ) )
				);
				
			} 	
			
			
			if( $trimestre == 2 ){
				
				$logrados['solicitudes'] = array(
					'04' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '04', date( 'Y' ) ),
					'05' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '05', date( 'Y' ) ),
					'06' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '06', date( 'Y' ) )
					
				);
				
				$logrados['negocios'] = array(
					'04' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '04', date( 'Y' ) ),
					'05' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '05', date( 'Y' ) ),
					'06' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '06', date( 'Y' ) ),
				);
				
				
				$logrados['primas'] = array(
					'04' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '04', date( 'Y' ) ),
					'05' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '05', date( 'Y' ) ),
					'06' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '06', date( 'Y' ) ),
				);
				
			} 	
			
			
			if( $trimestre == 3 ){
				
				$logrados['solicitudes'] = array(
					'07' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '07', date( 'Y' ) ),
					'08' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '08', date( 'Y' ) ),
					'09' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '09', date( 'Y' ) ),
					
				);
				
				$logrados['negocios'] = array(
					'07' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '07', date( 'Y' ) ),
					'08' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '08', date( 'Y' ) ),
					'09' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '09', date( 'Y' ) ),
				);
				
				$logrados['primas'] = array(
					'07' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '07', date( 'Y' ) ),
					'08' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '08', date( 'Y' ) ),
					'09' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '09', date( 'Y' ) ),
				);
				
			} 	
			
			
			if( $trimestre == 4 ){
								
				
				$logrados['solicitudes'] = array(
					'10' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '10', date( 'Y' ) ),
					'11' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '11', date( 'Y' ) ),
					'12' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '12', date( 'Y' ) )
					
				);
				
				$logrados['negocios'] = array(
					'10' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '10', date( 'Y' ) ),
					'11' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '11', date( 'Y' ) ),
					'12' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '12', date( 'Y' ) )
				);
				
				$logrados['primas'] = array(
					'10' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '10', date( 'Y' ) ),
					'11' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '11', date( 'Y' ) ),
					'12' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '12', date( 'Y' ) )
				);
				
			} 
			
			
			$logrados['config'] = $this->simulators->getConfigMetas( false, $trimestre, null );
			
		}

		if( $trimestre == null and $cuatrimestre != null ){
			if( $cuatrimestre == 1 ){
			
				$logrados['solicitudes'] = array(
					'01' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '01', date( 'Y' ) ),
					'02' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '02', date( 'Y' ) ),
					'03' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '03', date( 'Y' ) ),
					'04' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '04', date( 'Y' ) )
				);
				
				$logrados['negocios'] = array(
					'01' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '01', date( 'Y' ) ),
					'02' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '02', date( 'Y' ) ),
					'03' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '03', date( 'Y' ) ),
					'04' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '04', date( 'Y' ) )
				);
				
				$logrados['primas'] = array(
					'01' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '01', date( 'Y' ) ),
					'02' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '02', date( 'Y' ) ),
					'03' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '03', date( 'Y' ) ),
					'04' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '04', date( 'Y' ) )
				);
				
			} 	
			
			
			if( $cuatrimestre == 2 ){
				
				$logrados['solicitudes'] = array(
					'05' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '05', date( 'Y' ) ),
					'06' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '06', date( 'Y' ) ),
					'07' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '07', date( 'Y' ) ),
					'08' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '08', date( 'Y' ) )
				);
				
				$logrados['negocios'] = array(
					'05' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '05', date( 'Y' ) ),
					'06' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '06', date( 'Y' ) ),
					'07' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '07', date( 'Y' ) ),
					'08' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '08', date( 'Y' ) )
				);
				
				$logrados['primas'] = array(
					'05' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '05', date( 'Y' ) ),
					'06' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '06', date( 'Y' ) ),
					'07' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '07', date( 'Y' ) ),
					'08' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '08', date( 'Y' ) )
				);
				
			}
			
			
			if( $cuatrimestre == 3 ){
				
				$logrados['solicitudes'] = array(
					'09' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '09', date( 'Y' ) ),
					'10' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '10', date( 'Y' ) ),
					'11' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '11', date( 'Y' ) ),
					'12' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '12', date( 'Y' ) )
				);
				
				$logrados['negocios'] = array(
					'09' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '09', date( 'Y' ) ),
					'10' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '10', date( 'Y' ) ),
					'11' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '11', date( 'Y' ) ),
					'12' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '12', date( 'Y' ) )
				);
				
				$logrados['primas'] = array(
					'09' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '09', date( 'Y' ) ),
					'10' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '10', date( 'Y' ) ),
					'11' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '11', date( 'Y' ) ),
					'12' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '12', date( 'Y' ) )
				);
			}
			
			$logrados['config'] = $this->simulators->getConfigMetas( false, null, $cuatrimestre );
			
		}
		
		
		if( $trimestre == null and $cuatrimestre == null ){
			$logrados['solicitudes'] = array(
				'01' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '01', date( 'Y' ) ),
				'02' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '02', date( 'Y' ) ),
				'03' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '03', date( 'Y' ) ),
				'04' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '04', date( 'Y' ) ),
				'05' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '05', date( 'Y' ) ),
				'06' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '06', date( 'Y' ) ),
				'07' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '07', date( 'Y' ) ),
				'08' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '08', date( 'Y' ) ),
				'09' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '09', date( 'Y' ) ),
				'10' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '10', date( 'Y' ) ),
				'11' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '11', date( 'Y' ) ),
				'12' => $this->simulators-> getSolicitudLograda( $agentid, $product_group_id, '12', date( 'Y' ) )
			);
			
			
			$logrados['negocios'] = array(
				'01' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '01', date( 'Y' ) ),
				'02' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '02', date( 'Y' ) ),
				'03' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '03', date( 'Y' ) ),
				'04' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '04', date( 'Y' ) ),
				'05' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '05', date( 'Y' ) ),
				'06' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '06', date( 'Y' ) ),
				'07' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '07', date( 'Y' ) ),
				'08' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '08', date( 'Y' ) ),
				'09' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '09', date( 'Y' ) ),
				'10' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '10', date( 'Y' ) ),
				'11' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '11', date( 'Y' ) ),
				'12' => $this->simulators-> getNegociosLograda( $agentid, $product_group_id, '12', date( 'Y' ) )
			);
			
			
			$logrados['primas'] = array(
				'01' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '01', date( 'Y' ) ),
				'02' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '02', date( 'Y' ) ),
				'03' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '03', date( 'Y' ) ),
				'04' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '04', date( 'Y' ) ),
				'05' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '05', date( 'Y' ) ),
				'06' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '06', date( 'Y' ) ),
				'07' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '07', date( 'Y' ) ),
				'08' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '08', date( 'Y' ) ),
				'09' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '09', date( 'Y' ) ),
				'10' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '10', date( 'Y' ) ),
				'11' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '11', date( 'Y' ) ),
				'12' => $this->simulators-> getPrimasLograda( $agentid, $product_group_id, '12', date( 'Y' ) )
			);
			
			$logrados['config'] = $this->simulators->getConfigMetas( true, null, null );
				
		}	
		return $logrados;
	}

	public function save_simulator_new()
	{
		$this->_save_new('simulator');
	}
	public function save_meta_new()
	{
		$this->_save_new('meta');
	}
	private function _save_new($page)
	{
		if( $this->input->is_ajax_request() == false ){
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'No se puede acceder a esta sección "Simulador Crear".'
			));	
			redirect( 'home', 'refresh' );
		}

		if( !$this->access_create )
		{
			echo '0';
			exit();
		}
		if ( count( $_POST ) == 0)
		{
			echo '0';
			exit();
		}
		$allow_simulator = $this->config->item('allow_simulator');
		if (($allow_simulator !== FALSE) && !$allow_simulator
				&& $this->is_agent_only)
		{
			echo '0';
			exit();
		}
		$this->load->model( array( 'simulators' ) );
		$to_save = array('year' => date('Y'));
		$not_processed = $_POST;
		if ($page == 'simulator')
			$expected_fields = $this->input_simulator_fields;
		else
			$expected_fields = $this->input_meta_fields;

		foreach ($expected_fields as $field)
		{
			if (($pos = strpos($field, '_') ) !== FALSE)	// some fields are submitted as array (GMM) or as scalar (Vida)
			{
				$field_name = substr($field, 0, $pos);
				if (($input = $this->input->post($field_name)) !== FALSE)
				{
					if (is_array($input))
					{
						foreach ($input as $key => $value)
						{
							$value = trim(str_replace('%', '', $value));
							if (!strlen($value) || is_numeric($value) || ($value == 'NaN'))
								$to_save[$field_name . '_' . $key] = ($value == 'NaN') ? 0 : $value;
							else
							{
								echo '0';
								exit();
							}
						}
						unset($not_processed[$field_name]);
					}
					else
					{
						$input = trim(str_replace('%', '', $input));
						$to_save[$field] = ($input == 'NaN') ? 0 : $input;
						unset($not_processed[$field_name]);
					}
				}
				elseif ((($input = $this->input->post($field)) !== FALSE) &&
					!is_array($input))
				{
					$input = trim(str_replace('%', '', $input));
					if (!strlen($input) || is_numeric($input) || ($input == 'NaN') ||
						(strpos($field, 'porcentajeConservacion') !== FALSE))
					{
						$to_save[$field] = ($input == 'NaN') ? 0 : $input;
						unset($not_processed[$field]);
					}
					else
					{
						echo '0';
						exit();
					}
				}
			}
			elseif ((($input = $this->input->post($field)) !== FALSE) &&
				!is_array($input))
			{
				$input = trim(str_replace('%', '', $input));
				if (!strlen($input) || is_numeric($input) || ($input == 'NaN'))
				{
					$to_save[$field] = ($input == 'NaN') ? 0 : $input;
					unset($not_processed[$field]);
				}
				else
				{
					echo '0';
					exit();
				}
			}
		}
		if ($page == 'meta')
			$result = $this->simulators->create_update( 'meta_new', $to_save );
		else
			$result = $this->simulators->create_update( 'simulator_new', $to_save );
		if ($result)
			echo $result;
		else
			echo '0';
		exit();
	}

	public function save(){
		
		
		if( $this->input->is_ajax_request() == false ){
			
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'No se puede acceder a esta sección "Simulador Crear".'
							
			));	
			
			
			redirect( 'home', 'refresh' );
			
		}
		
		
		// Check access teh user for create
		if( $this->access_create == false ){
				
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Simulador Crear", Informe a su administrador para que le otorgue los permisos necesarios.'
							
			));	
			
			
			redirect( 'home', 'refresh' );
		
		}
		
		if( empty( $_POST ) ) exit;
		
		$this->load->model( array( 'simulators' ) );
		
		$simulator = array(
			'agent_id' => $_POST['agent_id'],
			'product_group_id' => $_POST['ramo'],
			'data' => json_encode($_POST)
		);
		
		if( $this->simulators->create( 'simulator', $simulator ) == true ){
			
			
			$id = $this->simulators->getByAgent( $_POST['agent_id'], $product_group_id );
							
			echo $id[0]['id'];
			
		}else
			
			echo false;
		
			
	}
	
	public function update()
	{
		if( !$this->input->is_ajax_request() ){
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'No se puede acceder a esta sección "Simulador Editar".'
			));	
			redirect( 'home', 'refresh' );
		}
		// Check access teh user for create
		if( $this->access_update == false )
		{
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Simulador Editar", Informe a su administrador para que le otorge los permisos necesarios.'
			));	
			redirect( 'home', 'refresh' );
		}
		if( empty( $_POST ) )
			exit;
		$this->load->model( array( 'simulators' ) );
		$id = $_POST['id'];
		unset( $_POST['id'] );
		$simulator = array(
			'agent_id' => $_POST['agent_id'],
			'product_group_id' => $_POST['ramo'],
			'data' => json_encode($_POST)
		);
		
		if( $this->simulators->update( 'simulator', $id, $simulator ) == true )
			echo true;
		else
			echo false;
	}
	
	
	
	
	
	
	public function config(){
		
		// Check access teh user for create
		if( $this->access_update == false ){
				
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Simulador Configuración Default", Informe a su administrador para que le otorge los permisos necesarios.'
							
			));	
			
			
			redirect( 'home', 'refresh' );
		
		}
		
		
		$this->load->model( array( 'simulators' ) );
							 
		// Config view
		$this->view = array(
				
		  'title' => 'Simulador | Configuración Default',
		   // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'access_create' => $this->access_create,
		  'access_update' => $this->access_update,
		  'access_delete' => $this->access_delete,
		  'scripts' =>  array(
		  	
			'<script type="text/javascript" src="'.base_url().'scripts/config.js"></script>',
			'<script type="text/javascript" src="'.base_url().'simulator/assets/scripts/config.js"></script>'
			
		  ),
		  'content' => 'simulator/config', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'data' => $this->simulators->getConfig()		  	  	  	  		
		);
	
		
		// Render view 
		$this->load->view( 'index', $this->view );	
		
	}
	
	public function configSave(){
		
		if( !$this->input->is_ajax_request() ){
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'No se puede acceder a esta sección "Simulador Configuración Default".'
			));	
			redirect( 'home', 'refresh' );
		}

		// Check access teh user for create
		if( $this->access_update == false ){
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Simulador Editar", Informe a su administrador para que le otorge los permisos necesarios.'
			));	
			redirect( 'home', 'refresh' );
		}
		
		$item = explode( '-', $_POST['item'] );
		
		$id  = $item[0];
		
		if( $item[1] == 1 ) 	$field = 'vida';
		if( $item[1] == 2 ) 	$field = 'gmm';
		if( $item[1] == 3 ) 	$field = 'autos';
		
		
		$data = array( $field => $_POST['value'] );
		
		$this->load->model( array( 'simulators' ) );
		
		if( $this->simulators-> update( 'simulator_default_estacionalidad', $id, $data ) == true )
			
			echo true;
		
		else
			
			echo false;
		
		exit;		
		
	}

	public function reset_meta()
	{
		if ( !$this->input->is_ajax_request() )
			redirect( 'home', 'refresh' );

		if ( !$this->access_update )
		{
			echo json_encode('-1');
			exit;
		}
		$allow_simulator = $this->config->item('allow_simulator');
		if (($allow_simulator !== FALSE) && !$allow_simulator
				&& $this->is_agent_only)
		{
			echo json_encode('-1');
			exit();
		}
		$result = json_encode('0');
		
		$key_fields = array(
			'ramo', 'period', 'agent_id', 'year',
		);
/*		$other_fields = array(
			'efectividad', 'prima_total_anual', 'primas_promedio',
		);*/
		$where = array();
		foreach ($key_fields as $field_name)
		{
			$field_value = $this->input->post($field_name);
			if ($field_value === FALSE)
			{
				echo $result;
				exit;
			}
			$field_value = trim($field_value);
			if (strlen($field_value) == 0)
			{
				echo $result;
				exit;
			}
			$where[$field_name] = (int)$field_value;
		}
		$this->load->model( array( 'simulators' ) );
		if ( ! $this->simulators->generic_delete( 'meta_new', $where))
		{
			echo $result;
			exit;
		}
		$simulator_where = array(
			'product_group_id' => $where['ramo'],
			'agent_id' => $where['agent_id'],
		);
		if ( ! $this->simulators->generic_delete( 'simulator', $simulator_where))
		{
			echo $result;
			exit;
		}
		echo json_encode('1');
		exit;
	}

	public function simulate()
	{
		$uri_segments = $this->uri->rsegment_array();
		$ramo = $this->_check_submitted_agent($uri_segments);
		$this->_simulate_common( $uri_segments[3], $ramo);
	}

	public function print_simulate( $userid = null, $ramo = null )
	{
		$this->for_print = true;
		$this->print_meta = false;
		$this->_simulate_common( $userid, $ramo);
	}

	private function _simulate_common( $userid = null, $ramo = null )
	{
		if (!$this->access && ($userid != $this->sessions['id']) )
		{
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Simulador" o no tiene permisos ver el simulator de este usuario. Informe a su administrador.'
			));
			redirect( 'home', 'refresh' );
		}
		$ramo_to_simulator = array(
			1 => 'vida', 2 => 'gmm', 3 => 'autos');
		$simulator = isset($ramo_to_simulator[$ramo]) ? $ramo_to_simulator[$ramo] : 'vida';

		$this->load->model( array('simulators' ) );

		$agentid = $this->user->getAgentIdByUser( $userid );
		$users = $this->user->getForUpdateOrDelete( $userid );

		$period = 0;
		$year = $this->input->post('year');
		if ($year === FALSE)
			$year = $this->uri->rsegment(6);
		$year = ($year !== FALSE) ? $year : date('Y');

		$data = $this->simulators->getByAgentNew('simulator_new', $agentid, $ramo, $period, $year);
		$meta_data = $this->simulators->getByAgentNew('meta_new', $agentid, $ramo, $period, $year);

		$js_assets = array(
			'<script type="text/javascript" src="'.base_url().'scripts/config.js"></script>',
			'<script type="text/javascript">Config.currentModule = "simulator"; Config.currentRamo = "' . $ramo . '";</script>',
			'<script type="text/javascript" src="'.base_url().'simulator/assets/scripts/metas_simulator.js"></script>',			
		);

		if ($this->for_print)
		{
			$css = array(
		  	'<link href="'. base_url() .'simulator/assets/style/simulator.css" rel="stylesheet">',
		  	'<link href="'. base_url() .'simulator/assets/style/simulator_print.css" rel="stylesheet">',
		  );
		  $add_js = '
<script type="text/javascript">
$( document ).ready( function(){
	$("#open_meta").hide();
	$("#print-button").bind( "click", function(){
		$(this).hide(); window.print(); window.close(); return false;}
	);
	$(".main-menu-span").removeClass("span2");
	$("#content").removeClass("span10").addClass("span12");
	$("#meta-footer td").css("font-size", "10px");
	
	if (!$("#print-button").hasClass("print-preview"))
		$("#reset-meta").hide();

	$(".screen-view").hide();
	$(".print-view").show();
	$(":input").prop("readonly", true);
});
</script>
';
			if ($this->print_meta)
				$js_assets[] = '<script type="text/javascript" src="'.base_url().'simulator/assets/scripts/metas.js"></script>';
		}
		else
		{
			$css = array(
		  	'<link href="'. base_url() .'simulator/assets/style/simulator.css" rel="stylesheet" media="screen">'
				);
			$uri_segments_meta = $this->uri->rsegment_array();
			$uri_segments_meta[5] = $period;
			$uri_segments_meta[6] = $year;			
			$uri_segments_simulator = $uri_segments_meta;
			$uri_segments_meta[2] = 'print_index';
			$uri_segments_simulator[2] = 'print_simulate';
			
			$add_js = '
<script type="text/javascript">
$( document ).ready( function(){
	$("#meta-footer td").css("font-size", "18px");
	$("#print-button").bind( "click", function(){
		if ($(".simulator:visible").length > 0) {
			$(this).attr("href", "' . site_url($uri_segments_simulator) . '");
		} else {
			$(this).attr("href", "' . site_url($uri_segments_meta) . '");
		}
	});

	$( "#tabs" ).tabs();
	$(".screen-view").show();
	$(".print-view").hide();
});
</script>
';

			$allow_simulator = $this->config->item('allow_simulator');
			if (($allow_simulator !== FALSE) && !$allow_simulator
				&& $this->is_agent_only)
				$add_js .= '
<script type="text/javascript">
$( document ).ready( function(){
	$("#save-simulator").hide();
	$("#simulator-section :input").prop("disabled", true);
});
</script>
';
		}
		$js_assets[] = '<script type="text/javascript" src="'.base_url().'simulator/assets/scripts/simulator_new.js?'.time().'"></script>';
		$js_assets[] = $add_js;

		$this->load->helper('filter');
		$this->agent_array = $this->user->getAgents( FALSE );
		$js_assets[] = get_agent_autocomplete_js($this->agent_array, '#form');
		$this->other_filters = array(
			'agent_name' => '',
		);
		get_generic_filter($this->other_filters, $this->agent_array);

		$this->view = array(
		  'title' => 'Simulador',
		   // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'access_create' => $this->access_create,
		  'access_update' => $this->access_update,
		  'access_delete' => $this->access_delete,
		  'css' => $css,
		  'scripts' => $js_assets,		  
		  'content' => 'simulator/overview',
		  'message' => $this->session->flashdata('message'),
		  'userid' =>  $userid,
		  'agentid' =>  $agentid,
		  'data' => $data,
		  'meta_data' => $meta_data,
		  'users' => $users,
		  'for_print' => $this->for_print,
		  'print_meta' => $this->print_meta,
		  'other_filters' => $this->other_filters,
		  'selected_year' => $year,
		  'ramo' => $simulator,
		);

		// Render view 
		$this->load->view( 'index', $this->view );	
	}

	private function _check_submitted_agent($uri_segments)
	{
		if (count($uri_segments) < 3)
			show_404();

		$agent_name = '';
		$agent_posted = $this->input->post('agent_name');
		if (($agent_posted !== FALSE) && $agent_posted)
		{
			$pieces = explode( ' [ID: ', $agent_posted);
			if (isset($pieces[1]))
			{
				$user_id = $this->user->getUserIdByAgentId( (int)$pieces[1] );
				if ($user_id != $uri_segments[3])
				{
					$uri_segments[3] = $user_id;
					redirect( join('/', $uri_segments), 'refresh' );
				}
			}
		}
		if (!isset($uri_segments[4]) || ($uri_segments[4] < 1) || ($uri_segments[4] > 3))
			$ramo = 1;
		else
			$ramo = $uri_segments[4];
		return $ramo;
	}
/* End of file simulator.php */
/* Location: ./application/controllers/simulator.php */
}
?>