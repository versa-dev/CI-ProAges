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
class Activities extends CI_Controller {

	public $view = array();
	
	public $sessions = array();
	
	public $user_vs_rol = array();
	
	public $roles_vs_access = array();
	
	public $access = false; // Force security
	
	public $access_create = false;
	
	public $access_update = false;
	
	public $access_delete = false;

	public $access_report = false;

	public $access_viewall = false;

	public $access_sales_activities = false;
	public $access_ot_update = false;
	public $access_ot_delete = false;

	public $default_period_filter = FALSE;
	public $misc_filters = FALSE;
	public $custom_period_from = FALSE;
	public $custom_period_to = FALSE;
	public $period_filter_for = FALSE;

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
		
		// Get user rol access
		$this->roles_vs_access = $this->rol->user_roles_vs_access( $this->user_vs_rol );
		
		
	
		// If exist the module name, the user accessed
		if( !empty( $this->user_vs_rol )  and !empty( $this->roles_vs_access ) )	
		foreach( $this->roles_vs_access  as $value ): if( in_array( 'Actividades', $value ) ):

			$this->access = true;
			
		break; endif; endforeach;


		// Added Acctions for user, change the bool access
		if( !empty( $this->user_vs_rol ) and !empty( $this->roles_vs_access ) )
		{
			foreach( $this->roles_vs_access  as $value )
			{
				if( in_array( 'Actividades', $value ) )
				{

					if( $value['action_name'] == 'Crear' )
						$this->access_create = true;

					if( $value['action_name'] == 'Editar' )
						$this->access_update = true;

					if( $value['action_name'] == 'Eliminar' )
						$this->access_delete = true;	

					if( $value['action_name'] == 'Ver reporte' )
						$this->access_report = true;	

					if( $value['action_name'] == 'Ver todos los registros' )
						$this->access_viewall = true;	

					if( $value['action_name'] == 'Export xls' )
						$this->access_export = true;
					if( $value['action_name'] == 'Actividades de ventas' )
						$this->access_sales_activities = true;
				}
				elseif ($value['module_name'] == 'Orden de trabajo')
				{
					if ($value['action_name'] == 'Editar')
						$this->access_ot_update = TRUE;
					elseif ($value['action_name'] == 'Eliminar')
						$this->access_ot_delete = TRUE;
				}
			}
		}					
								
		if( empty( $this->sessions ) and $this->uri->segment(2) != 'login'  ) redirect( 'usuarios/login', 'refresh' );

		$this->period_filter_for = 'activities_report';
		$this->default_period_filter = $this->session->userdata('default_period_filter_activities_report');
		$this->custom_period_from = $this->session->userdata('custom_period_from_activities_report');
		$this->custom_period_to = $this->session->userdata('custom_period_to_activities_report');

		$this->misc_filter_name = 'activity_misc_filter';
		$this->misc_filters = $this->session->userdata($this->misc_filter_name);
	}

// Show all records	
	public function index( $userid = null, $filter = null ){
// Copied / pasted to the code of agent/activity_details.html
// But without the pagination below that does not work

		// Check access teh user for create
		if( $this->access == false ){
				
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Actividad", Informe a su administrador para que le otorgue los permisos necesarios.'
							
			));	
			
			
			redirect( 'home', 'refresh' );
		
		}
		$this->load->helper('filter');
		if (isset($this->misc_filters['begin']) && isset($this->misc_filters['end']))
		{
			$filter['begin'] = $this->misc_filters['begin'];
			$filter['end'] = $this->misc_filters['end'];
		}
		// Load Model
		$this->load->model( array( 'activity', 'user' ) );

		// Pagination config	
		$this->load->library('pagination');
		
		$begin = $this->uri->segment(4);
		
		if( empty( $begin ) ) $begin = 0;
		
		if( !empty( $userid ) ){	
			$agentid = $this->user->getAgentIdByUser( $userid );
			$url = base_url().'activities/index/'.$userid.'/';
			$user = $this->user->getForUpdateOrDelete($userid);
		}else{
			$agentid = $this->user->getAgentIdByUser( $this->sessions['id'] );
			$url = base_url().'activities/index/';
			$user = $this->user->getForUpdateOrDelete($this->sessions['id']);
		}

		$config['full_tag_open'] = '<div class="pagination pagination-right"><ul>'; 
		$config['full_tag_close'] = '</ul></div>';
		$config['first_link'] = false;
		$config['last_link'] = false;
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = '&larr; Anterior';
		$config['prev_tag_open'] = '<li class="prev">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = 'Siguiente &rarr;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] =  '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';					
		$config['base_url'] = $url;
		$config['total_rows'] = $this->activity->record_count( $agentid, $filter );
		$config['per_page'] = 150;
		$config['num_links'] = 5;
		$config['uri_segment'] = 4;
		$config['use_page_numbers'] = TRUE;
		
		$this->pagination->initialize($config); 
	
					 
		// Config view
		$this->view = array(
				
		  'title' => 'Actividad',
		   // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'access_create' => $this->access_create,
		  'access_update' => $this->access_update,
		  'access_delete' => $this->access_delete,
		  'access_report' => $this->access_report,
		  'access_viewall' => $this->access_viewall,
		  'content' => 'activities/list', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'data' => $this->activity->overview( $begin, $agentid, $filter ),
		  'userid' => $userid,
		  'usersupdate' => $user[0]		  	   	  		
		);
	
		// Render view 
		$this->load->view( 'index', $this->view );	
	}

// Create new user
	public function create( $userid = null ){
// Copied / pasted to the code of agent/create_activity.html

		$redirect_page = !empty( $userid ) ?  'activities/index/'.$userid : 'activities';
		// Check access the user for create
		if( $this->access_create == false ){
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Actividad crear", Informe a su administrador para que le otorge los permisos necesarios.'
			));	
			redirect($redirect_page, 'refresh');
		}

		if( !empty( $userid ) )	
			$user = $this->user->getForUpdateOrDelete($userid);
		else
			$user = $this->user->getForUpdateOrDelete($this->sessions['id']);

		if (!$user || !isset($user[0]['id']))
		{
		// Set false message		
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Actividad crear", Informe a su administrador para que le otorge los permisos necesarios.'
			));	
			redirect($redirect_page, 'refresh');
		}
		$period_fields = $this->_common_create_update(null, $redirect_page, $user[0]['id']);

		$base_url = base_url();
		$js = activity_create_update_js();
		// Config view
		$this->view = array(
		  'title' => 'Crear Actividad',
		    // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'access_create' => $this->access_create,
		  'access_update' => $this->access_update,
		  'access_delete' => $this->access_delete,
		  'access_report' => $this->access_report,
		  'access_viewall' => $this->access_viewall,
		  'css' => array(
		  	'<link href="'. $base_url .'activities/assets/style/create.css" rel="stylesheet" media="screen">'
		  ),
		  'scripts' => array(
			'<script src="'.$base_url.'scripts/config.js"></script>',
			'<script type="text/javascript" src="'. $base_url .'scripts/select_period.js"></script>',
			$js,
		  ),
			'period_fields' => $period_fields,
			'content' => 'activities/create', // View to load
			'message' => $this->session->flashdata('message'), // Return Message, true and false if have
			'userid' => $userid,
			'usersupdate' => $user[0]
		);
		// Render view 
		$this->load->view( 'index', $this->view );	
	}

// Export	
	public function exportar( $userid = null ){

		// Check access for export
		if( $this->access_export == false ){

			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Report Exportar", Informe a su administrador para que le otorge los permisos necesarios.'
			));

			if( !empty( $userid ) )				
				redirect( 'activities/index/'.$userid, 'refresh' );
			else
				redirect( 'activities', 'refresh' );
		}
		$default_filter = 2;
		$default_week = array();
		$params = array();
		$data = $this->_prepare_report($default_filter, $default_week, $params);

		if ( validation_errors() ) {

			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'Fecha no válida.'
			));

			if( !empty( $userid ) )				
				redirect( 'activities/index/'.$userid, 'refresh' );
			else
				redirect( 'activities', 'refresh' );
		}

		// Generate csv report and output it
		$title_row = array(
				'Agente', 'Citas', 'Entrevistas', 'Prospectos', 'Solicitudes Vida', 
				'Negocios Vida', 'Solicitudes GMM', 'Negocios GMM', 'Negocios Autos',
			);
		if ($default_filter == 2)
			$title_row[] = 'Comentarios';
		$report_data = array(0 => $title_row);

		foreach ($data['rows'] as $value) {
			$data_row = array(
				$value['name'] . ' ' . $value['lastnames'],
				$value['cita'], $value['interview'],
				$value['prospectus'], $value['vida_requests'],
				$value['vida_businesses'], $value['gmm_requests'], $value['gmm_businesses'],
				$value['autos_businesses'],
			);
			if ($default_filter == 2)
				$data_row[] = $value['comments'];
			$report_data[] = $data_row;
		}

		$report_data[] = array(
			'TOTALS', $data['totals']['cita'], $data['totals']['interview'],
			$data['totals']['prospectus'], $data['totals']['vida_requests'],
			$data['totals']['vida_businesses'], $data['totals']['gmm_requests'],
			$data['totals']['gmm_businesses'], $data['totals']['autos_businesses']
		);

		// Load csv helper
		$this->load->helper('usuarios/csv');
		$filename = "proages_actividades_report_" . $params['begin'] . '_' . $params['end'] . ".csv";
		header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="$filename"');
	 	array_to_csv($report_data, $filename);
		
		if( is_file( $filename ) )
			echo file_get_contents( $filename );
		
		if( is_file( $filename ) )
			unlink( $filename );
				
		exit;

	}
// Prepare report (common to page report and export
	private function _prepare_report(&$default_filter, &$default_week, &$params)
	{
		$this->load->helper(array('filter', 'date_report'));
		$default_filter = get_filter_period();
		$default_week = get_calendar_week();
		$params = array(
			'periodo' => $default_filter,
			'begin' => $default_week['start'],
			'end' => $default_week['end'],
		);
		//Load Model
		$this->load->model( array( 'activity', 'user' ) );

		if( !empty( $_POST['begin'] ) ){

			// Generals validations
			$this->form_validation->set_rules('periodo', 'Período', 'required|is_natural_no_zero|less_than[5]');
			$this->form_validation->set_rules('begin', 'Semana', 'required');
			$this->form_validation->set_rules('end', 'Semana', 'required');
		
			// Run Validation
			if (( $this->form_validation->run() == TRUE ) &&
				checkdate_from_to( $_POST['begin'], $_POST['end'] ))
			{
				$default_week = array('start' => $_POST['begin'], 'end' => $_POST['end']);
				$default_filter = $_POST['periodo'];
				set_filter_period($default_filter);
				$params = array(
					'periodo' => $_POST['periodo'],
					'begin' => $_POST['begin'],
					'end' => $_POST['end'],				
				);
				get_period_start_end($params);
				$data = $this->activity->report( 'agents_activity', $params );
			}
		}
		else {
			get_period_start_end($params);
			$data = $this->activity->report( 'agents_activity', $params );
		}
		return $data;
	}

// Update activity	
	public function update( $activity_id = null, $userid = null ){

		$redirect_page = !empty( $userid ) ? 'activities/index/' . $userid : 'activities/index';

		// Check access for update
		if( $this->access_update == false ){

			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Actividad Editar", Informe a su administrador para que le otorge los permisos necesarios.'
			));
			redirect( $redirect_page, 'refresh' );
		}

		$this->load->model( array( 'activity', 'user' ) );
		if( !empty( $userid ) ) {	
			$agentid = $this->user->getAgentIdByUser( $userid );
		} else {
			$agentid = $this->user->getAgentIdByUser( $this->sessions['id'] );
		}

		$data = $this->activity->getForUpdateOrDelete( 'agents_activity', $activity_id, $agentid );
		$activity_id = (int) $activity_id;
		$userid = (int) $userid;
		// Check Record if exist
		if( empty( $data ) ){
			
			// Set false message		
			$this->session->set_flashdata( 'message', array( 

				'type' => false,	
				'message' => 'No existe el registro. No puede editar este registro.'
			));	
			redirect( $redirect_page, 'refresh' );
		}

		$period_fields = $this->_common_create_update($activity_id, $redirect_page, $userid);

		$begin = $_POST ? $this->input->post('begin') : $data->begin;
		$end =  $_POST ? $this->input->post('end') : $data->end;

		$base_url = base_url();
		$js = activity_create_update_js();

		// Config view
		$this->view = array(
		  'title' => 'Editar Actividad',
		    // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'css' => array(
			  '<link href="'. $base_url .'activities/assets/style/create.css" rel="stylesheet" media="screen">'
		  ),
		  'scripts' =>  array(
				'<script src="'.$base_url.'scripts/config.js"></script>',
				'<script type="text/javascript" src="'. $base_url .'scripts/select_period.js"></script>',
			$js,
'
<script type="text/javascript">
	$( document ).ready( function(){ 
		$( "#cust_period_from").val("' . $begin . '");
		$( "#cust_period_to").val("' . $end . '");
		$( "#periodo_form").children().text("' . $begin . ' - ' . $end . '");
	});
</script>',

		  ),
		  'content' => 'activities/update', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'userid' => $userid,
		  'data' => $data,
			'period_fields' => $period_fields,
		); 

		// Render view 
		$this->load->view( 'index', $this->view );	
	}

	// Common to create and update activity
	private function _common_create_update($activity_id, $redirect_page = 'activities', $user = null)
	{
		$this->load->helper( array('filter', 'activity' ));
		$this->load->model('activity');
		create_update_activity($activity_id, $redirect_page, $user);
		$ramo = 1;
		$period_fields = show_period_fields('ot_reporte', $ramo);
		return $period_fields;
	}

	// Delete Activity
	public function delete( $activity_id = null, $userid = null ){

		$redirect_page = !empty( $userid ) ? 'activities/index/' . $userid : 'activities/index';
		// Check access for delete
		if( $this->access_delete == false ){

			// Set false message		
			$this->session->set_flashdata( 'message', array( 

				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Actividad  Eliminar", Informe a su administrador para que le otorge los permisos necesarios.'
			));	
			redirect( $redirect_page, 'refresh' );
		}

		// Load Model
		$this->load->model( array( 'activity', 'user' ) );
		if( !empty( $userid ) ) {  
			$agentid = $this->user->getAgentIdByUser( $userid );
		} else {
			$agentid = $this->user->getAgentIdByUser( $this->sessions['id'] );
		}
		$data = $this->activity->getForUpdateOrDelete( 'agents_activity', $activity_id, $agentid );
		$activity_id = (int) $activity_id;

		// Check Record if exist
		if( empty( $data ) ){
			
			// Set false message		
			$this->session->set_flashdata( 'message', array( 

				'type' => false,	
				'message' => 'No existe el registro. No puede eliminar este registro.'
			));	
			redirect( $redirect_page, 'refresh' );
		}

		if( !empty( $_POST ) and isset( $_POST['delete'] ) and $_POST['delete'] == true ) {

			// Delete from DB
			if ( $this->activity->delete( 'agents_activity', 'id',  $activity_id ) == false )
				// Set false message		
				$this->session->set_flashdata( 'message', array(
					'type' => false,
					'message' => 'No se puede borrar el registro. Actividad, ocurrio un error en la base de datos. Pongase en contacto con el desarrollador.'		
				));	
			else
				// Set ok message		
				$this->session->set_flashdata( 'message', array( 
					'type' => true,	
					'message' => 'La actividad se elimino correctamente.'
				));

			redirect( $redirect_page, 'refresh' );
		}

		// Config view
		$this->view = array(

		  'title' => 'Eliminar actividad',
		    // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'css' => array(),
		  'scripts' =>  array(
			  '<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/jquery.validate.js"></script>',
			  '<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/es_validator.js"></script>',
			  '<script src="'.base_url().'activities/assets/scripts/delete.js"></script>',		
			  	
		  ),
		  'content' => 'activities/delete', // View to load
		  'message' => $this->session->flashdata('message') ,// Return Message, true and false if have
		  'userid' => $userid,
		  'data' => $data
		);

		// Render view 
		$this->load->view( 'index', $this->view );	
	}

//Report of Ventas
	public function sales_activities_stats( $userid = null ){

		// Check user privilege
		if( !$this->access_sales_activities) {
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Actividad de ventas". Informe a su administrador para que le otorgue los permisos necesarios.'
			));	

			if( !empty( $userid ) )				
				redirect( 'activities/index/'.$userid, 'refresh' );
			else
				redirect( 'activities', 'refresh' );
		}
		$this->load->model( 'activity' );

		$agent_array = array();
		$other_filters = array();
		$default_week = array();
		$data = $this->_init_report($agent_array, $other_filters, $default_week);

		$inline_js = get_agent_autocomplete_js($agent_array, '#sales-activity-form');
		$inline_js .=
'
<script type="text/javascript">
	$( document ).ready( function(){

//$("#sorter").tablesorter(); 

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
	});
</script>
';

		switch ($other_filters['periodo'])
		{
			case 1:
			case 2:
			case 3:
			case 4:
				$report_period = ' desde ' . $other_filters['begin'] . ' hasta ' . $other_filters['end'];
				break;
			default:
				$report_period = '';
				break;
		}
		$base_url = base_url();
		$this->view = array(
		  'title' => 'Actividad de ventas',
		    // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'access_create' => $this->access_create,
		  'access_update' => $this->access_update,
		  'access_delete' => $this->access_delete,
		  'access_report' => $this->access_report,
		  'access_viewall' => $this->access_viewall,
		  'access_export' => $this->access_export,
		  'css' => array(
		  	'<link href="'. $base_url . 'activities/assets/style/create.css" rel="stylesheet" media="screen">',
			'<link href="'. $base_url . 'activities/assets/style/table_sorter.css" rel="stylesheet">',
			'<link rel="stylesheet" href="'. $base_url .'ot/assets/style/jquery.fancybox.css">',
			'<link rel="stylesheet" href="'. $base_url .'activities/assets/style/activity_sales.css">',
		  ),
		  'scripts' =>  array(
			'<script type="text/javascript" src="'.$base_url.'plugins/jquery-validation/jquery.validate.js"></script>',
			'<script type="text/javascript" src="'.$base_url.'plugins/jquery-validation/es_validator.js"></script>',
			'<script src="'.$base_url.'scripts/config.js"></script>',
//			'<script src="'.$base_url.'activities/assets/scripts/activities.js"></script>',
			'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.tablesorter-2.14.5.js"></script>',
			'<script type="text/javascript" src="'. $base_url .'ot/assets/scripts/jquery.tablesorter.widgets-2.14.5.js"></script>',
			'<script src="' . $base_url . 'activities/assets/scripts/sales_activity_report.js"></script>',
			'<script src="' . $base_url . 'ot/assets/scripts/jquery.fancybox.js"></script>',
//			'<script type="text/javascript" src="'. $base_url .'scripts/custom-period.js"></script>',
			'<script type="text/javascript" src="'. $base_url .'scripts/select_period.js"></script>',
			$inline_js,
		  ),
		  'content' => 'activities/sales_activities',
		  'data' => $data,
		  'period_form' => show_custom_period(), // custom period configuration form
		  'other_filters' => $other_filters,
		  'selection_filters' => $other_filters,
		  'report_period' => $report_period,
		  'default_week' => $default_week,
		  'period_fields' => show_period_fields('ot_reporte', 1),
		  'message' => $this->session->flashdata('message')
		);

		// Render view 
		$this->load->view( 'index', $this->view );		
	
	}

// Init actividades de venta report processing
// Copied / pasted to the code of agent/agent_sales_activity/89.html
	private function _init_report(&$agent_array, &$other_filters, &$default_week)
	{
		$data = array();
		$agent_array = $this->user->getAgents( FALSE );

		$this->load->helper(array('filter', 'date_report', 'tri_cuatrimester'));
		$default_filter = get_filter_period();
		$default_week = get_calendar_week();

		$other_filters = array(
			'agent_name' => '',
			'activity_view' => 'normal',
			'begin' => $default_week['start'],
			'end' => $default_week['end']
		);
		get_generic_filter($other_filters, $agent_array);

		if ( count( $_POST ) && 
//(($periodo = $this->input->post('periodo')) !== FALSE) &&
			((($periodo = $this->input->post('periodo')) !== FALSE) ||
			(($periodo = $this->input->post("query['periodo']")) !== FALSE)
			) &&
			(($agent_name = $this->input->post('agent_name')) !== FALSE) && 
			(($activity_view = $this->input->post('activity_view')) !== FALSE) &&
//			(($begin = $this->input->post('begin')) !== FALSE) &&
			((($begin = $this->input->post('begin')) !== FALSE) ||
			(($begin = $this->input->post('start_d')) !== FALSE)
			) &&
//			(($end = $this->input->post('end')) !== FALSE)
			((($end = $this->input->post('end')) !== FALSE) ||
			(($end = $this->input->post('end_d')) !== FALSE)
			)
			)
		{

			update_custom_period($this->input->post('cust_period_from'),
				$this->input->post('cust_period_to'), FALSE);

			$default_week = array('start' => $begin, 'end' => $end);
			if ( $this->form_validation->is_natural_no_zero($periodo) &&
				($periodo <= 4))
				set_filter_period($periodo);

			if (($activity_view != 'normal') && ($activity_view != 'efectividad'))
				$activity_view = 'normal';

			$other_filters['agent_name'] = $agent_name;				
			$other_filters['periodo'] = $periodo;
			$other_filters['begin'] = $begin;
			$other_filters['end'] = $end;
//			get_period_start_end($other_filters);
			get_new_period_start_end($other_filters);
			$filters_to_save = array(
				'agent_name' => $agent_name,
				'activity_view' => $activity_view,
				'begin' => $other_filters['begin'],
				'end' => $other_filters['end']
				);
			generic_set_report_filter( $filters_to_save, $agent_array );
//			foreach ($filters_to_save as $key => $value)
//				$other_filters[$key] = $value;
			$data = $this->activity->sales_activity($other_filters, TRUE);
		}
		else
		{
			$other_filters = array_merge(
				$other_filters, array('periodo' => $default_filter));
//			get_period_start_end($other_filters);
			get_new_period_start_end($other_filters);
			$data = $this->activity->sales_activity($other_filters, TRUE);
		}
		return $data;
	}

// Sale activity details
	public function sales_popup()
	{
		$this->load->model(array('activity', 'ot/work_order'));
		$agent_user_id = $this->input->post('agent_id');
		$parts = explode('_', $agent_user_id);
		if (count($parts) >= 2)
		{
			$agent_id = $parts[0];
			$user_id = $parts[1];
		}
		else
		{
			$agent_id = 0;
			$user_id = 0;
		}
		$begin = $this->input->post('begin');
		$end = $this->input->post('end');
		if (!$begin || !$end)
		{
			$begin = $this->input->post('start_d');
			$end = $this->input->post('end_d');		
		}
		if ($agent_id && $user_id && $begin && $end)
		{
			$values = array('begin' => $begin, 'end' => $end);
			$agents_selected = array($agent_id => $agent_id);
			$totals_work_order = array('VIDA_solicitudes' => 0, 'GMM_solicitudes' => 0,
				'VIDA_negocios' => 0, 'GMM_negocios' => 0);
			$agents_with_activity = array();
			$add_where = array(
				'agents.id' => $agent_id);
			$request_type = $this->input->post('type');
			switch ( $request_type )
			{
				case 'vida-solicitudes': // from work_order
					$add_where = array_merge($add_where, 
						array('product_group.name' => 'Vida'));
					$this->_details_from_ots($values, $agents_selected, $totals_work_order, 
						$agents_with_activity, 'solicitudes', $add_where, $user_id, 1);
					break;
				case 'gmm-solicitudes': // from work_order
					$add_where = array_merge($add_where, 
						array('product_group.name' => 'GMM'));
					$this->_details_from_ots($values, $agents_selected, $totals_work_order, 
						$agents_with_activity, 'solicitudes', $add_where, $user_id, 2);
					break;
				case 'vida-negocios': // from work_order
					$add_where = array_merge($add_where, 
						array('work_order_status_id' => 4, 'product_group.name' => 'Vida'));
					$this->_details_from_ots($values, $agents_selected, $totals_work_order, 
						$agents_with_activity, 'negocios', $add_where, $user_id, 2);
					break;
				case 'gmm-negocios': // from work_order
					$add_where = array_merge($add_where, 
						array('work_order_status_id' => 4, 'product_group.name' => 'GMM'));
					$this->_details_from_ots($values, $agents_selected, $totals_work_order, 
						$agents_with_activity, 'negocios', $add_where, $user_id, 2);
					break;
				default:
					exit('Ocurrio un error. Consulte a su administrador.');
					break;
			}
		}
		else
			exit('Ocurrio un error. Consulte a su administrador.');			
	}

	private function _details_from_ots($values, $agents_selected, $totals_work_order, 
		$agents_with_activity, $field, $add_where, $user_id, $ramo)
	{
		$data = array(
			'access_update' => $this->access_update,
			'access_delete' => $this->access_delete,
			'values' => array()
		);
		$this->load->vars(array('gmm' => $ramo, 'is_poliza' => 'yes'));
// is_poliza = 'no' for columns negocios and primas en tramite (work_order.work_order_status_id=5 OR  work_order.work_order_status_id=9)
// is_poliza = 'yes' for columns negocios and primas aceptadas (work_order.work_order_status_id=7)
		$details = array();
		$ots = $this->activity->get_ot_list($values, $agents_selected,
			$totals_work_order, $agents_with_activity, 'solicitudes', $add_where);
		$work_order_ids = array();
		foreach ($ots as $row)
			$work_order_ids[$row->work_order_id] = $row->work_order_id;
		if ($work_order_ids)
			$details = $this->work_order->pop_up_data($work_order_ids, $user_id);
		if ($details)
		{
			$ot = array('director' => $details['director']);
			foreach ($details['general'] as $row_result)
			{
				$row_result->adjusted_prima = $row_result->prima;

			// For OTs en tramite and pendientes, adjust prima:
				if (($row_result->work_order_status_id == 5) ||
					($row_result->work_order_status_id == 9) ||
					($row_result->work_order_status_id == 7) )
				{
					$row_result->adjusted_prima = 
						$this->user->get_adjusted_prima($row_result->policy_id)
						* ($row_result->p_percentage / 100);
				}
				$ot['value'] = $row_result;
				$data['values'][$row_result->work_order_id] = array(
					'main' => $this->load->view('popup_report_main_row', $ot, TRUE),
					'menu' => $this->load->view('popup_report_menu_row', $ot, TRUE));
			}
		}
		$this->load->view('popup_report', $data);
	}

/* End of file activities.php */
/* Location: ./application/controllers/activities.php */
}
?>