<?php ini_set('max_execution_time',90000);
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*

  Authors       Ulises Rodríguez
  Site:         http://www.ulisesrodriguez.com
  Twitter:      https://twitter.com/#!/isc_ulises
  Facebook:     http://www.facebook.com/ISC.Ulises
  Github:       https://github.com/ulisesrodriguez
  Email:        ing.ulisesrodriguez@gmail.com
  Skype:        systemonlinesoftware
  Location:     Guadalajara Jalisco Mexíco


*/
  class Ot extends CI_Controller {

  	public $reader = null;

  	public $view = array();

  	public $sessions = array();

  	public $user_vs_rol = array();

  	public $roles_vs_access = array();

	public $access = false; // Force security

	public $access_create = false;

	public $access_update = false;

	public $access_delete = false;

	public $access_activate = false;
	//public $access_create_policy = false;

	public $access_all = false;

	public $access_export_xls = false; // Import payments

	public $access_import_payments = false; // Import payments

	public $access_report = false; // View report

	public $default_period_filter = FALSE;
	public $ot_r_misc_filter = FALSE;
	public $misc_filters = FALSE;
	public $misc_filter_name = FALSE;
	public $custom_period_from = FALSE;
	public $custom_period_to = FALSE;
	public $period_filter_for = FALSE;

	/** Construct Function **/
	/** Setting Load perms **/

	public function __construct(){

		parent::__construct();

		/** Getting Info bu login User **/

		$this->load->model( array( 'usuarios/user', 'roles/rol', 'termometro/termometro_model' ) );

		// Get Session
		$this->sessions = $this->session->userdata('system');

		// Get user rol
		$this->user_vs_rol = $this->rol->user_role( $this->sessions['id'] );

		// Get user rol access
		$this->roles_vs_access = $this->rol->user_roles_vs_access( $this->user_vs_rol );



		// If exist the module name, the user accessed
		if( !empty( $this->user_vs_rol ) )
			foreach( $this->roles_vs_access  as $value ): if( in_array( 'Orden de trabajo', $value ) ):

				$this->access = true;

				break; endif; endforeach;


		// Added Acctions for user, change the bool access
				if( !empty( $this->user_vs_rol ) )
					foreach( $this->roles_vs_access  as $value ):
						if( in_array( 'Orden de trabajo', $value ) ):


							if( $value['action_name'] == 'Crear' )
								$this->access_create = true;

							if( $value['action_name'] == 'Editar' )
								$this->access_update = true;

							if( $value['action_name'] == 'Eliminar' )
								$this->access_delete = true;

							if( $value['action_name'] = 'Activar/Desactivar' )
								$this->access_activate = true;

							if( $value['action_name'] ='Ver todos los registros' )
								$this->access_all = true;

							if( $value['action_name'] ='Importar payments' )
								$this->access_import_payments = true;

							if( $value['action_name'] ='Ver reporte' )
								$this->access_report = true;

							if( $value['action_name'] ='Export xls' )
								$this->access_export_xls = true;
						endif;
						if( in_array( 'Operations', $value ) ):
							if( $value['action_name'] == 'Crear' )
								$this->access_create = true;
						endif;
					endforeach;
					if( empty( $this->sessions ) and $this->uri->segment(2) != 'login'  )
					{
						redirect( 'usuarios/login', 'refresh' );
					}
					$uri_segments = $this->uri->rsegment_array();
					if (count($uri_segments) == 1)
						$uri_segments[2] = 'index';
					if ((count($uri_segments) >= 2) && ($uri_segments[1] == 'ot'))
					{
						if (($uri_segments[2] == 'index') || ($uri_segments[2] == 'find'))
						{
							$this->period_filter_for = 'ot_index';
							$this->default_period_filter = $this->session->userdata('default_period_filter_ot_index');
							$this->custom_period_from = $this->session->userdata('custom_period_from_ot_index');
							$this->custom_period_to = $this->session->userdata('custom_period_to_ot_index');
							$this->misc_filter_name = 'ot_misc_filter';
							$this->misc_filters = $this->session->userdata($this->misc_filter_name);
						}
						else
						{
							$this->period_filter_for = 'ot_reporte';
							$this->default_period_filter = $this->session->userdata('default_period_filter_ot_reporte');
							$this->custom_period_from = $this->session->userdata('custom_period_from_ot_reporte');
							$this->custom_period_to = $this->session->userdata('custom_period_to_ot_reporte');
//              $this->misc_filter_name = 'ot_r_misc_filter';
//              $this->misc_filters = $this->session->userdata($this->misc_filter_name);
						}
					}
					$this->ot_r_misc_filter = $this->session->userdata('ot_r_misc_filter');

					$this->load->model('exchange_rate_model');
					//$response = $this->exchange_rate_model->get_from_banxico();
				}

// Show all records
				public function index(){

		// Load Model
					$this->load->model( array( 'work_order', 'user' ) );
		// Load Helpers
					$this->load->helper( array('date', 'filter', 'ot') );

					if (count($_POST))
					{
						update_custom_period($this->input->post('cust_period_from'),
							$this->input->post('cust_period_to'), FALSE);
					}

					$other_filters = array();
					get_generic_filter($other_filters, array());

					$gerente_str = '';
					$agente_str = '<option value="">Todos</option>';
					$ramo_tramite_types = array();
					$patent_type_ramo = 0;
					prepare_ot_form($other_filters, $gerente_str, $agente_str, $ramo_tramite_types, $patent_type_ramo);

					$add_js = '
					<script type="text/javascript">
					$( document ).ready( function(){
						proagesOverview.tramiteTypes = {' .
						implode(', ', $ramo_tramite_types) . '
					};
					$( "#patent-type").html(proagesOverview.tramiteTypes[' . $patent_type_ramo . ']);
				});
				</script>
				';

//      $ramo = isset($other_filters['ramo']) ? $other_filters['ramo'] : 1;
				$ramo = 55;
		// Config view
				$this->view = array(

					'title' => 'Orden de trabajo',
		   // Permisions
					'user' => $this->sessions,
					'user_vs_rol' => $this->user_vs_rol,
					'roles_vs_access' => $this->roles_vs_access,
					'access_create' => $this->access_create,
					'access_update' => $this->access_update,
					'access_delete' => $this->access_delete,
					'access_all' => $this->access_all,
					'css' => array(
						'<link href="'. base_url() .'ot/assets/style/theme.default.css" rel="stylesheet">',
						'<link href="'. base_url() .'ot/assets/style/report.css" rel="stylesheet">',
						'<link rel="stylesheet" href="'. base_url() .'ot/assets/style/main.css">',
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
							Config.findUrl = "ot/find.html";
						});
						</script>
						',
						'<script type="text/javascript" src="'. base_url() .'ot/assets/scripts/jquery.tablesorter-2.14.5.js"></script>',
						'<script src="'.base_url().'ot/assets/scripts/list_js.js"></script>',
						'<script src="'.base_url().'scripts/config.js"></script>',
						$add_js,
						'<script src="'.base_url().'ot/assets/scripts/overview.js"></script>',
						'<script type="text/javascript" src="'. base_url() .'scripts/select_period.js"></script>',
					),
		  'content' => 'ot/list', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'period_fields' => show_period_fields('ot_reporte', $ramo),
		  'agents' => $agente_str,
		  'gerentes' => $gerente_str,
		  'other_filters' => $other_filters
		);

		// Render view
				$this->load->view( 'index', $this->view );
			}


// Getting Filter
// Copied and pasted to the code of agent/find:
			public function find(){

		// If is not ajax request redirect
				if( !$this->input->is_ajax_request() )  redirect( '/', 'refresh' );

		// Load Model
				$this->load->model( 'work_order' );

		// Load Helper
				$this->load->helper( array( 'ot', 'date', 'filter' ) );
				if (count($_POST))
				{
					update_custom_period($this->input->post('cust_period_from'),
						$this->input->post('cust_period_to'), FALSE);
				}
				$other_filters = array();
				$data = get_ot_data($other_filters, $this->access_all);

				$view_data = array('data' => $data);
				$this->load->view('ot/list_render', $view_data);
			}


			public function find_scripts(){

		// If is not ajax request redirect
				if( !$this->input->is_ajax_request() )  redirect( '/', 'refresh' );


				echo " $( '.popup' ).hide(); $( '.btn-hide' ).bind( 'click', function(){ $( '.popup' ).hide(); });  ";exit;

			}









	// Create new role
	public function create(){

				// Check access teh user for create
				if( $this->access_create == false ){
					// Set false message
					$this->session->set_flashdata( 'message', array(
						'type' => false,
						'message' => 'No tiene permisos para ingresar en esta sección "Orden de trabajo Crear", Informe a su administrador para que le otorge los permisos necesarios.'
					));
					redirect( 'ot', 'refresh' );
				}

				if( !empty( $_POST ) ){
					$this->form_validation->set_rules('ramo', 'Ramo', 'required');
					$this->form_validation->set_rules('ot', 'Número de OT', 'is_unique[work_order.uid]');
					$this->form_validation->set_rules('work_order_type_id', 'Tipo de tramite', 'required');
					$this->form_validation->set_rules('subtype', 'Sub tipo', 'required');

					// IF IS A NEW BUSSINESS
					if( $this->input->post( 'work_order_type_id' ) == '90' or $this->input->post( 'work_order_type_id' ) == '47' )
					{
						// Validations
						$this->form_validation->set_rules('product_id', 'Producto', 'required|xxs_clean');
						$this->form_validation->set_rules('currency_id', 'Moneda', 'required|xxs_clean');
						$this->form_validation->set_rules('prima', ' Prima anual',
							'trim|decimal_or_integer');
						$this->form_validation->set_rules('payment_interval_id', 'Conducto', 'required|xxs_clean');
						$this->form_validation->set_rules('payment_method_id', 'Forma de pago', 'required|xxs_clean');
						$this->form_validation->set_rules('name', 'Nombre', 'required|strtoupper|xxs_clean');
						//$this->form_validation->set_rules('lastname_father', 'Apellido paterno', 'required|xxs_clean');
						//$this->form_validation->set_rules('lastname_mother', 'Apellido materno', 'required|xxs_clean');
					}
					else
					{
						$this->form_validation->set_rules('name', 'Nombre', 'strtoupper');
					}

					// Run Validation
					if ( $this->form_validation->run() == TRUE ){
						// Load Model
						$this->load->model( 'work_order' );
						$controlSaved = true;
						$policyId = 0;

						// Save new bussiness
				//if( $this->input->post( 'work_order_type_id' ) == '90' or $this->input->post( 'work_order_type_id' ) == '47' ){
						if( !empty( $_POST['product_id'] ) )
						{
							$current_date = date( 'Y-m-d H:i:s' );
							$field_values = array(
								'last_updated' => $current_date,
							);
							if (! $this->_process_update_db_policy_prima(
								$current_date, $field_values))
							{
								// Set false message
								$this->session->set_flashdata( 'message', array(
									'type' => false,
									'message' => 'No se pudo convertir la prima.'
								));
								redirect( 'ot/create', 'refresh' );
							}
							$policy = array_merge($field_values, array(
								'product_id' => $this->input->post( 'product_id' ),
								'period' => $this->input->post( 'period' ),
								'payment_method_id' => $this->input->post( 'payment_method_id' ),
								'uid' => $this->input->post( 'uid' ),
								'name' => $this->input->post( 'name' ),
								'lastname_father' => $this->input->post( 'lastname_father' ),
								'lastname_mother' => $this->input->post( 'lastname_mother' ),
								'year_premium' => $this->input->post( 'year_premium' ),
								'expired_date' => $this->input->post( 'expired_date' ),
								'date' => $current_date
							));

						} else {
							$policy = array(
								'name' => $this->input->post( 'name' ),
								'uid' => $this->input->post( 'uid' ),
								'last_updated' => date( 'Y-m-d H:i:s' ),
								'date' => date( 'Y-m-d H:i:s' )
							);
						}
						if ( $controlSaved &&
							$this->work_order->create( 'policies', $policy ) == false )
							$controlSaved = false;

							$policyId = $this->work_order->insert_id();

							// Agents Adds
							$agents = array();
							for( $i=0; $i<=count( $this->input->post('agent') ); $i++ )
								if( !empty(  $_POST['agent'][$i] ) )
									$agents[] = array(
										'user_id' => $_POST['agent'][$i],
										'policy_id' => $policyId,
										'percentage' => $_POST['porcentaje'][$i],
										'since' => date( 'Y-m-d H:i:s' ),
										'agent_generation' => ($this->input->post( 'product_id' ) == 1) ? 
											$this->user->generationByAgentIdVida($this->input->post('creation_date'),$_POST['agent'][$i]) : 
											$this->user->generationByAgentIdGmm($this->input->post('creation_date'),$_POST['agent'][$i])
									);

								if( $this->work_order->create_banch( 'policies_vs_users', $agents ) == false )
									$controlSaved = false;
				//}
								if( $controlSaved == false ){
									// Set false message
									$this->session->set_flashdata( 'message', array(
										'type' => false,
										'message' => 'No se puede crear la orden de trabajo Poliza, consulte a su administrador.'
									));
									redirect( 'ot', 'refresh' );
								}

								$ot = array(
									'user' => $this->sessions['id'],
									'policy_id' => $policyId,
									'product_group_id' => $this->input->post( 'ramo' ),
									'work_order_type_id' => $this->input->post( 'subtype' ),
									'work_order_status_id' => 5,
									'work_order_responsible_id' => 0,
									'uid' => $this->input->post( 'ot' ),
									'creation_date' => $this->input->post('creation_date'),
									'comments' => $this->input->post('comments'),
									'duration' => '',
									'last_updated' => date( 'Y-m-d H:s:i' ),
									'date' => date( 'Y-m-d H:s:i' )
								);

								// Save OT
								if( $this->work_order->create( 'work_order', $ot ) == false )
									$controlSaved = false;

								if( $controlSaved == false ){
									// Set false message
									$this->session->set_flashdata( 'message', array(
										'type' => false,
										'message' => 'No se puede crear la orden de trabajo, consulte a su administrador.'
									));
									redirect( 'ot', 'refresh' );
								}

								// Send Email
								$notification = $this->input->post('notification');
								if ($notification !== FALSE)
								{
									//Get aditional emails
									$aditional_emails = trim($this->input->post('emails'));

									//Filter string for valid emails only
									if(!empty($aditional_emails)){
										$arr_emails = explode(",", $aditional_emails);
										$valid_emails = array();
										foreach ($arr_emails as $email_aditional) {
											if(filter_var($email_aditional, FILTER_VALIDATE_EMAIL))
												$valid_emails[] = $email_aditional;
										}
										$aditional_emails = implode(",", $valid_emails);
									}


									$this->load->library( 'mailer' );
									$notification = $this->work_order->getNotification();
									$this->mailer->notifications( $notification, null, null, array(
										'from' => $this->sessions['email'],
										'reply-to' => $this->sessions['email'],
										'bcc' => /*$this->sessions['email'].(!empty($this->sessions['email2']) ? ",".$this->sessions['email2'] : "").*/(!empty($aditional_emails) ? ",".$aditional_emails : "")
									)
								);
								}

								if( $controlSaved == true ){
									// Set false message
									$this->session->set_flashdata( 'message', array(
										'type' => true,
										'message' => 'Se ha creado el registro correctamente.'
									));
									redirect( 'ot', 'refresh' );
								}
							}
						}
						// Load Model
						$this->load->model( 'work_order' );
						// Get products
						$product = $this->work_order->getProductsOptions();
						//Get Currency
						$currency = $this->work_order->getCurrencyOptions();
						// Get Payments intervals
						$payment_intervals = $this->work_order->getPaymentIntervalOptions();
						// Get Conduct (payment mode)
						$payment_conduct = $this->work_order->getPaymentMethodsConductoOptions();
						// Get Agents
						$agents = $this->user->getAgents();

						$add_js = '
						<script type="text/javascript">
						$("#prima").on("change keyup", function(event) {
							if ( event.target.validity.valid ) {
//      if ( ! event.target.validity.patternMismatch ) {
								$("#prima-error").hide();
							} else {
								$("#prima-error").show();
							}
						});
						';

						$base_url = base_url();
						// Config view
						$this->view = array(
							'title' => 'Crear OT',
		   					// Permisions
							'user' => $this->sessions,
							'user_vs_rol' => $this->user_vs_rol,
							'roles_vs_access' => $this->roles_vs_access,
							'css' => array(),
							'scripts' =>  array(
								'<script type="text/javascript" src="' .
								$base_url . 'plugins/jquery-validation/jquery.validate.js"></script>',
								'<script type="text/javascript" src="' .
								$base_url . 'plugins/jquery-validation/es_validator.js"></script>',
								//'<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.js"></script>',
								'<script src="' .
								$base_url . 'ot/assets/scripts/create.js?'.time().'"></script>',
								'<script src="' .
								$base_url . 'scripts/config.js"></script>',
								$add_js
							),
		  'content' => 'ot/create', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'product' => $product,
		  'currency' => $currency,
		  'payment_intervals' => $payment_intervals,
		  'payment_conduct' => $payment_conduct,
		  'agents' => $agents
		);

						// Render view
						$this->load->view( 'index', $this->view );
					}

					// Getting type tramite
					public function typetramite(){

						// If is not ajax request redirect
						if( !$this->input->is_ajax_request() )  redirect( '/', 'refresh' );

						// Load Model
						$this->load->model( 'work_order' );

						$options = $this->work_order->getTypeTramite( $this->input->post( 'ramo' ) );
						echo $options;

						return;
					}

	// Getting sub type
					public function subtype(){

		// If is not ajax request redirect
						if( !$this->input->is_ajax_request() )  redirect( '/', 'refresh' );

		// Load Model
						$this->load->model( 'work_order' );

						$options = $this->work_order->getSubType( $this->input->post( 'type' ) );
						echo $options;
						return;
					}

					public function period(){

		// If is not ajax request redirect
						if( !$this->input->is_ajax_request() )  redirect( '/', 'refresh' );
		// Load Model
						$this->load->model( 'work_order' );
						$options = $this->work_order->getPeriod( $this->input->post( 'id' ) );
		//print_r($_POST);
						echo $options;
						return;
					}
/**
 *  Condig Policies
 **/
public function policies(){

		// If is not ajax request redirect
	if( !$this->input->is_ajax_request() )  redirect( '/', 'refresh' );
		// Load Model
	$this->load->model( 'work_order' );
	$options = $this->work_order->getPolicies( $this->input->post( 'ramo' ) );
		//print_r($_POST);
	echo $options;

	return;
}

/**
 *  Create policies
 **/

public function create_policy(){

	exit;
		// Check access teh user for create
	if( $this->access_create == false ){
			// Set false message
		$this->session->set_flashdata( 'message', array(
			'type' => false,
			'message' => 'No tiene permisos para ingresar en esta sección "Orden de trabajo Crear Politica.", Informe a su administrador para que le otorge los permisos necesarios.'
		));
		redirect( 'ot', 'refresh' );
	}
		// Load model
	$this->load->model( array( 'work_order', 'user' ) );
		// Save the record
	if( !empty( $_POST ) ){
			// Validations
		$this->form_validation->set_rules('ramo', 'Ramo', 'required|xxs_clean');
		$this->form_validation->set_rules('product_id', 'Producto', 'required|xxs_clean');
		$this->form_validation->set_rules('currency_id', 'Moneda', 'required|xxs_clean');
		$this->form_validation->set_rules('payment_interval_id', 'Conducto', 'required|xxs_clean');
		$this->form_validation->set_rules('payment_method_id', 'Forma de pago', 'required|xxs_clean');
		$this->form_validation->set_rules('name', 'Nombre', 'required|strtoupper|xxs_clean');
		$this->form_validation->set_rules('lastname_father', 'Apellido paterno', 'required|xxs_clean');
		$this->form_validation->set_rules('lastname_mother', 'Apellido materno', 'required|xxs_clean');
			//$this->form_validation->set_rules('agent[]', 'Agente', 'required|xxs_clean');
			//$this->form_validation->set_rules('porcentaje[]', 'Porcentaje', 'required|xxs_clean');
			// Run Validation
		if ( $this->form_validation->run() == TRUE ){
			$controlSave = true;
			$policy = array(
				'product_id' => $this->input->post( 'product_id' ),
				'currency_id' => $this->input->post( 'currency_id' ),
				'payment_interval_id' => $this->input->post( 'payment_interval_id' ),
				'payment_method_id' => $this->input->post( 'payment_method_id' ),
				'uid' => $this->input->post( 'uid' ),
				'name' => $this->input->post( 'name' ),
				'lastname_father' => $this->input->post( 'lastname_father' ),
				'lastname_mother' => $this->input->post( 'lastname_mother' ),
				'year_premium' => $this->input->post( 'year_premium' ),
				'expired_date' => $this->input->post( 'expired_date' ),
				'last_updated' => date( 'Y-m-d H:i:s' ),
				'date' => date( 'Y-m-d H:i:s' )

			);

			if( $this->work_order->create( 'policies', $policy ) == false )
				$controlSave = false;
			$policyId = $this->work_order->insert_id();

				// Agents Adds
			$agents = array();
			for( $i=0; $i<=count( $this->input->post('agent') ); $i++ )
				if( !empty(  $_POST['agent'][$i] ) )
					$agents[] = array(
						'user_id' => $_POST['agent'][$i],
						'policy_id' => $policyId,
						'percentage' => $_POST['porcentaje'][$i],
						'since' => date( 'Y-m-d H:i:s' )
					);

				if( $this->work_order->create_banch( 'policies_vs_users', $agents ) == false );
				$controlSave = false;

				if( $controlSave == true ){
					  // Set true message
					$this->session->set_flashdata( 'message', array(
						'type' => true,
						'message' => 'Se agrego la nueva politica.'
					));
					redirect( 'ot/create', 'refresh' );

				}else{
					// Set true message
					$this->session->set_flashdata( 'message', array(
						'type' => false,
						'message' => 'Ocurrio un error no se puede guardar la nueva politica, consulte a su administrador.'
					));
					redirect( 'ot/create', 'refresh' );
				}
			}
			exit;
		}

		// Get products
		$product = $this->work_order->getProductsOptions();

		//Get Currency
		$currency = $this->work_order->getCurrencyOptions();

		// Get Payment intervals
		$payment_intervals = $this->work_order->getPaymentIntervalOptions();

		// Get Conduct
		$payment_conduct = $this->work_order->getPaymentMethodsConductoOptions();

		// Get Agents
		$agents = $this->user->getAgents();

		// Config view
		$this->view = array(
			'title' => 'Crear Politica',
		   // Permisions
			'user' => $this->sessions,
			'user_vs_rol' => $this->user_vs_rol,
			'roles_vs_access' => $this->roles_vs_access,
			'css' => array(),
			'scripts' =>  array(
				'<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/jquery.validate.js"></script>',
				'<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/es_validator.js"></script>',
				'<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.js"></script>',
				'<script src="'.base_url().'ot/assets/scripts/create_polocy.js"></script>',
				'<script src="'.base_url().'scripts/config.js"></script>'
			),
		  'content' => 'ot/create_policy', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'product' => $product,
		  'currency' => $currency,
		  'payment_intervals' => $payment_intervals,
		  'payment_conduct' => $payment_conduct,
		  'agents' => $agents

		);
		// Render view
		$this->load->view( 'index', $this->view );
	}


	// Get Options for a new select
	public function getSelectAgents(){

		if( !$this->input->is_ajax_request() ){ echo 'Access denied'; exit; }

		// Load model
		$this->load->model( 'user' );
		// Get Agents
		$agents = $this->user->getAgents();
		echo $agents;
	}

	// Load Products by Produc Group
	public function getPolicyByGroup(){

		if( !$this->input->is_ajax_request() ){ echo 'Access denied'; exit; }

		// Load model
		$this->load->model( 'work_order' );

		$product = $this->work_order->getProductsOptions( $this->input->post( 'product_group' ) );
		echo $product;
		exit;
	}

	/**
	 *  set Pay
	 **/

	public function setPay(){

		if( !$this->input->is_ajax_request() ){ echo 'Access denied'; exit; }

		// Load Model
		$this->load->model( 'work_order' );
		$work_order = array(
			'work_order_status_id' => 4
		);
		$ot = $this->input->post( 'id' );
		if ( $this->work_order->update( 'work_order', $ot, $work_order ) &&
			( ($updated = $this->work_order->generic_get( 'work_order', array('id' => $ot), 1))
				!== FALSE)
		)
		{
			// Send Email
			if ($this->input->post( 'notification' ) == 1)
				$this->_send_notification($ot, $updated);
			echo 'Ot Marcada como pagada correctamente';
		}
		else
			echo 'Ocurrio un error. El registro no puede ser guardado, consulte a su administrador.';
		exit;
	}

	/**
	 *  Activate/Desactivate
	 **/
	public function activar( $ot = null ){

		// Check access teh user for create
		if( $this->access_activate == false ){
			// Set false message
			$this->session->set_flashdata( 'message', array(
				'type' => false,
				'message' => 'No tiene permisos para ingresar en esta sección "Orden de trabajo Activar.", Informe a su administrador para que le otorge los permisos necesarios.'
			));
			redirect( 'ot', 'refresh' );
		}

		// Load Model
		$this->load->model( 'work_order' );

		// Save Record
		if( !empty( $_POST ) ){
			$comments_posted = $this->input->post('comments');
			if ($comments_posted === FALSE)
				$comments_posted = '';
			$work_order = array(
				'work_order_status_id' => 6,
				'work_order_reason_id' => $this->input->post( 'work_order_reason_id' ),
				'work_order_responsible_id' => $this->input->post( 'work_order_responsible_id' ),
//              'creation_date' => '0000-00-00 00:00:00', // Quitar el tiempo
				'comments' => $comments_posted,
				'last_updated' => date( 'd-m-Y H:i:s' )
			);

			if ( $ot && $this->work_order->update( 'work_order', $ot, $work_order ) &&
				( ($updated = $this->work_order->generic_get( 'work_order', array('id' => $ot), 1))
					!== FALSE)
			)
			{
				// Send Email
				$notification = $this->input->post('notification');
				if ($notification !== FALSE)
					$this->_send_notification($ot, $updated);

				// Set true message
				$this->session->set_flashdata( 'message', array(
					'type' => true,
					'message' => 'Se ha guardado el registro correctamente.'
				));
				redirect( 'ot', 'refresh' );
			}else{
				// Set true message
				$this->session->set_flashdata( 'message', array(
					'type' => false,
					'message' => 'Ocurrio un error el registro no puede ser guardado, consulte a su administrador.'
				));
				redirect( 'ot', 'refresh' );
			}
			exit;
		}
		$data = $this->work_order->getOtActivateDesactivate( $ot );

		// Config view
		$this->view = array(
			'title' => 'Activar OT',
		   // Permisions
			'user' => $this->sessions,
			'user_vs_rol' => $this->user_vs_rol,
			'roles_vs_access' => $this->roles_vs_access,
			'css' => array(),
			'scripts' =>  array(
				'<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/jquery.validate.js"></script>',
				'<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/es_validator.js"></script>',
				'<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.js"></script>',
				'<script src="'.base_url().'ot/assets/scripts/activate_desactivate.js"></script>',
				'<script src="'.base_url().'scripts/config.js"></script>'
			),
		  'content' => 'ot/activate', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'ot' => $ot,
		  'reason' => $this->work_order->getReason( $data[0]['product_group_id'], 6, $data[0]['work_order_reason_id'] ),
		  'responsibles' => $this->work_order->getResponsibles(  $data[0]['work_order_responsible_id'] ),
		  'data' => $data
		);
		// Render view
		$this->load->view( 'index', $this->view );
	}

	public function desactivar( $ot = null ){

		$this->load->model( 'work_order' );
		$work_order = array(
			'work_order_status_id' => 9,
			'creation_date' => date( 'Y-m-d H:i:s' ), // Quitar el tiempo
			'last_updated' => date( 'Y-m-d H:i:s' )
		);
		if ( $ot &&
			$this->work_order->update( 'work_order', $ot, $work_order ) &&
			( ($updated = $this->work_order->generic_get( 'work_order', array('id' => $ot), 1))
				!== FALSE)
		)
		{
			// Send Email
			if ($this->uri->rsegment(4, 1) == 1)
				$this->_send_notification($ot, $updated);
			// Set true message
			$this->session->set_flashdata( 'message', array(
				'type' => true,
				'message' => 'Se ha guardado el registro correctamente.'
			));
			redirect( 'ot', 'refresh' );
		}else{
			// Set true message
			$this->session->set_flashdata( 'message', array(
				'type' => false,
				'message' => 'Ocurrio un error el registro no puede ser guardado, consulte a su administrador.'
			));
			redirect( 'ot', 'refresh' );
		}
		exit;
	}

	public function cancelar( $ot = null ){

		// Check access teh user for create
		if( $this->access_delete == false ){
			// Set false message
			$this->session->set_flashdata( 'message', array(
				'type' => false,
				'message' => 'No tiene permisos para ingresar en esta sección "Orden de trabajo Cancelar.", Informe a su administrador para que le otorge los permisos necesarios.'
			));
			redirect( 'ot', 'refresh' );
		}

		// Load Model
		$this->load->model( 'work_order' );
		// Save Record
		if( !empty( $_POST ) ){
			$work_order = array(
				'work_order_status_id' => 2,
				'work_order_reason_id' => $this->input->post( 'work_order_reason_id' ),
				'work_order_responsible_id' => $this->input->post( 'work_order_responsible_id' ),
				'notes' => $this->input->post( 'notes' ),
				'last_updated' => date( 'd-m-Y H:i:s' )
			);

			if ( $this->work_order->update( 'work_order', $ot, $work_order ) &&
				( ($updated = $this->work_order->generic_get( 'work_order', array('id' => $ot), 1))
					!== FALSE)
			)
			{
				// Send Email
				$notification = $this->input->post('notification');
				if ($notification !== FALSE)
					$this->_send_notification($ot, $updated);

				// Set true message
				$this->session->set_flashdata( 'message', array(
					'type' => true,
					'message' => 'Se ha guardado el registro correctamente.'

				));
				redirect( 'ot', 'refresh' );
			}else{
				// Set true message
				$this->session->set_flashdata( 'message', array(
					'type' => false,
					'message' => 'Ocurrio un error el registro no puede ser guardado, consulte a su administrador.'
				));
				redirect( 'ot', 'refresh' );
			}
			exit;
		}
		$data = $this->work_order->getOtActivateDesactivate( $ot );

		// Config view
		$this->view = array(
			'title' => 'Desactivar OT',
		   // Permisions
			'user' => $this->sessions,
			'user_vs_rol' => $this->user_vs_rol,
			'roles_vs_access' => $this->roles_vs_access,
			'css' => array(),
			'scripts' =>  array(
				'<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/jquery.validate.js"></script>',
				'<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/es_validator.js"></script>',
				'<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.js"></script>',
				'<script src="'.base_url().'ot/assets/scripts/activate_desactivate.js"></script>',
				'<script src="'.base_url().'scripts/config.js"></script>'
			),
		  'content' => 'ot/cancelar', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'ot' => $ot,
		  'reason' => $this->work_order->getReason( $data[0]['product_group_id'], 2, $data[0]['work_order_reason_id'] ),
		  'responsibles' => $this->work_order->getResponsibles(  $data[0]['work_order_responsible_id'] ),
		  'data' => $data
		);

		// Render view
		$this->load->view( 'index', $this->view );
	}

	private function _send_notification($order_id, $updated)
	{
		$notification = $this->work_order->getNotification( $order_id );
		if ($notification && isset($notification[0]))
		{
			if(!empty($this->sessions['email2']))
			{
				$from_reply_to = array(
					'from' => $this->sessions['email'],
					'reply-to' => $this->sessions['email'],
					'bcc' => $this->sessions['email2']
				);
			}
			else
			{
				$from_reply_to = array(
					'from' => $this->sessions['email'],
					'reply-to' => $this->sessions['email']/*,
					'bcc' => $this->sessions['email'].(!empty($this->sessions['email2']) ? ",". $this->sessions['email2'] : "")*/
				);
			}
			
/*          $creator = $this->work_order->generic_get( 'users', array('id' => $updated[0]->user), 1);
			if ($creator)
			{
				$from_reply_to = array(
					'from' => $creator[0]->email,
					'reply-to' =>  $creator[0]->email);
				}*/
				$responsible = $this->work_order->getResponsiblesById( $notification[0]['work_order_responsible_id'] );
				$reason = $this->work_order->getReasonById( $notification[0]['work_order_reason_id'] );
				$this->load->library( 'mailer' );
				if (!$responsible || !$reason || !isset($responsible[0]) || !isset($reason[0]))
				{
					$this->mailer->notifications( $notification, null, null, $from_reply_to );
				}
				else
				{
					$this->mailer->notifications( $notification, $reason[0]['name'], $responsible[0]['name'], $from_reply_to );
				}
			}
		}

	/**
	 *  Aceptar y rechazar
	 **/
	public function aceptar( $ot = null, $send_notification = null, $poliza = null, $pago = null ){

		// Load Model
		$this->load->model( 'work_order' );
		$work_order = array(
			'work_order_status_id' => 7,
			'last_updated' => date( 'd-m-Y H:i:s' )
		);
		if ( $pago == "true" )
			$work_order['work_order_status_id'] = 4;

		$this->work_order->setPolicy( $ot, $poliza );

		if ( $this->work_order->update( 'work_order', $ot, $work_order ) &&
			( ($updated = $this->work_order->generic_get( 'work_order', array('id' => $ot), 1))
				!== FALSE)
		)
		{
			$this->load->model('policy_model');
			$this->policy_model->add_adjusted_primas($ot);
			// Send Email
			if ($send_notification == 1)
				$this->_send_notification($ot, $updated);

			// Set true message
			$this->session->set_flashdata( 'message', array(
				'type' => true,
				'message' => 'Se ha guardado el registro correctamente.'
			));
			redirect( 'ot', 'refresh' );
		}else{
			// Set true message
			$this->session->set_flashdata( 'message', array(
				'type' => false,
				'message' => 'Ocurrio un error el registro no puede ser guardado, consulte a su administrador.'
			));
			redirect( 'ot', 'refresh' );
		}
	}

	public function rechazar( $ot = null){

	// Load Model
		$this->load->model( 'work_order' );
		if( !empty( $_POST ) ){
			$work_order = array(
				'work_order_status_id' => 8,
				'notes' => $this->input->post('notes'),
				'last_updated' => date( 'd-m-Y H:i:s' )
			);

			if ( $this->work_order->update( 'work_order', $ot, $work_order ) &&
				(($updated = $this->work_order->generic_get( 'work_order', array('id' => $ot), 1))
					!== FALSE))
			{
			// Send Email
				$send_notification = $this->input->post('notification');
				if ($send_notification == 1)
					$this->_send_notification($ot, $updated);

			// Set true message
				$this->session->set_flashdata( 'message', array(
					'type' => true,
					'message' => 'Se ha guardado el registro correctamente.'
				));
				redirect( 'ot', 'refresh' );
			}else{
			// Set true message
				$this->session->set_flashdata( 'message', array(
					'type' => false,
					'message' => 'Ocurrio un error el registro no puede ser guardado, consulte a su administrador.'
				));
				redirect( 'ot', 'refresh' );
			}
		}
	// Config view
		$this->view = array(
			'title' => 'Desactivar OT',
	   // Permisions
			'user' => $this->sessions,
			'user_vs_rol' => $this->user_vs_rol,
			'roles_vs_access' => $this->roles_vs_access,
			'css' => array(),
			'scripts' =>  array(
				'<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/jquery.validate.js"></script>',
				'<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/es_validator.js"></script>',
				'<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.js"></script>',
				'<script src="'.base_url().'ot/assets/scripts/activate_desactivate.js"></script>',
				'<script src="'.base_url().'scripts/config.js"></script>'
			),
	  'content' => 'ot/cancelar', // View to load
	  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
	  'ot' => $ot,
	  'rechazar' => 1
	);

	// Render view
		$this->load->view( 'index', $this->view );
	}

/*
// Cancel work order
	public function cancelar( $ot = null ){




		// Check access teh user for create
		if( $this->access_delete == false ){

			// Set false message
			$this->session->set_flashdata( 'message', array(

				'type' => false,
				'message' => 'No tiene permisos para ingresar en esta sección "Orden de trabajo Canselar", Informe a su administrador para que le otorge los permisos necesarios.'

			));


			redirect( 'ot', 'refresh' );

		}

		if( empty( $ot ) ){

			// Set false message
			$this->session->set_flashdata( 'message', array(

				'type' => false,
				'message' => 'No existe esta orden de trabajo..'

			));


			redirect( 'ot', 'refresh' );

		}


		// Load Model
		$this->load->model( 'work_order' );



		if( !empty( $_POST ) ){


				$otupdate = array(

					'work_order_status_id' => 2,
					'last_updated' => date( 'Y-m-d H:s:i' )

				);

				if( $this->work_order->update( 'work_order', $ot, $otupdate ) == true ){

					// Set false message
					$this->session->set_flashdata( 'message', array(

						'type' => true,
						'message' => 'Se ha guardado el registro correctamente.'

					));


					redirect( 'ot', 'refresh' );

				}else{

					// Set false message
					$this->session->set_flashdata( 'message', array(

						'type' => false,
						'message' => 'El registro no puede ser modificado, consulte a su administrador..'

					));


					redirect( 'ot', 'refresh' );

				}

			exit;
		}



		$data = $this->work_order->getById( $ot );


		// Config view
		$this->view = array(

		  'title' => 'Cancelar OT',
		   // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'css' => array(),
		  'scripts' =>  array(
			  '<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/jquery.validate.js"></script>',
			  '<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/es_validator.js"></script>',
			  '<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.js"></script>',
			  '<script src="'.base_url().'ot/assets/scripts/delete.js"></script>',
			  '<script src="'.base_url().'scripts/config.js"></script>'

		  ),
		  'content' => 'ot/delete', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'data' => $data

		);


		// Render view
		$this->load->view( 'index', $this->view );

	}
*/

// update work order
	public function update( $ot = null ){

		// Check access teh user for create
		if( $this->access_create == false ){
			// Set false message
			$this->session->set_flashdata( 'message', array(
				'type' => false,
				'message' => 'No tiene permisos para ingresar en esta sección "Orden de trabajo Editar", Informe a su administrador para que le otorge los permisos necesarios.'
			));
			redirect( 'ot', 'refresh' );
		}
		if( empty( $ot ) ){
			// Set false message
			$this->session->set_flashdata( 'message', array(
				'type' => false,
				'message' => 'No existe esta orden de trabajo..'
			));
			redirect( 'ot', 'refresh' );
		}
		// Load Model
		$this->load->model( 'work_order' );
		if( !empty( $_POST ) ){
			$this->form_validation->set_rules('ramo', 'Ramo', 'required');
			$this->form_validation->set_rules('work_order_type_id', 'Tipo de tramite', 'required');
			$this->form_validation->set_rules('subtype', 'Sub tipo', 'required');
			$this->form_validation->set_rules('comments', 'Comentarios', 'required');

			// Run Validation
			if ( $this->form_validation->run() == TRUE ){
				$otupdate = array(
					'policy_id' => $this->input->post( 'policy_id' ),
					'product_group_id' => $this->input->post( 'ramo' ),
					'work_order_type_id' => $this->input->post( 'subtype' ),
					'creation_date' => $this->input->post('creation_date'),
					'comments' => $this->input->post('comments'),
					'duration' => '',
					'last_updated' => date( 'Y-m-d H:s:i' )
				);

				if( $this->work_order->update( 'work_order', $ot, $otupdate ) == true ){
					// Set false message
					$this->session->set_flashdata( 'message', array(
						'type' => true,
						'message' => 'Se ha guardado el registro correctamente.'
					));
					redirect( 'ot', 'refresh' );
				}else{
					// Set false message
					$this->session->set_flashdata( 'message', array(
						'type' => false,
						'message' => 'El registro no puede ser modificado, consulte a su administrador..'
					));
					redirect( 'ot', 'refresh' );
				}
			}
			exit;
		}

		$data = $this->work_order->getById( $ot );

		// Config view
		$this->view = array(
			'title' => 'Modificar OT',
		   // Permisions
			'user' => $this->sessions,
			'user_vs_rol' => $this->user_vs_rol,
			'roles_vs_access' => $this->roles_vs_access,
			'css' => array(),
			'scripts' =>  array(
				'<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/jquery.validate.js"></script>',
				'<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/es_validator.js"></script>',
				'<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.js"></script>',
				'<script src="'.base_url().'ot/assets/scripts/update.js"></script>',
				'<script src="'.base_url().'scripts/config.js"></script>'
			),
		  'content' => 'ot/update', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'data' => $data
		);

		// Render view
		$this->load->view( 'index', $this->view );
	}

	private $imported_fields = array(
		1 => array( // Vida / Previo de Ventas
			4 => 'agent_uidsnational',
			5 => 'uid',
			6 => 'year_prime',
			7 => 'amount',
			8 => 'payment_date',
			9 => 'is_new',
			11 => 'name',
		),
		2 => array( // GMM
			4 => 'agent_uidsprovincial',
			5 => 'uid',
			6 => 'year_prime',
			7 => 'amount',
			8 => 'payment_date',
			9 => 'is_new',
			11 => 'name',
		),
		3 => array( // Autos
			3 => 'agent_uidsprovincial',
			6 => 'name',
			7 => 'payment_date',
			8 => 'uid',
			10 => 'amount',
//          11 => 'is_new',
			11 => 'year_prime',
		),
		4 => array( // Vida / SELO Produccion
			4 => 'agent_uidsnational',
			5 => 'uid',
			6 => 'year_prime',
			7 => 'amount',
			8 => 'payment_date',
			9 => 'is_new',
			11 => 'name',	
            15 => 'allocated_prime',
            16 => 'bonus_prime'
		),
		5 => array( // GMM / SELO Produccion
			4 => 'agent_uidsprovincial',
			5 => 'uid',
			6 => 'year_prime',
			7 => 'amount',
			8 => 'payment_date',
			9 => 'is_new',
			11 => 'name',
            15 => 'allocated_prime',
            16 => 'bonus_prime'
		),
		8 => array( // Generar SELO Vida
			1 => 'interl',
			2 => 'inter',
			3 => 'agent_uidsnational',
			4 => 'uid',
			5 => 'year_prime',
			7 => 'payment_date',
			8 => 'amount',
			9 => 'po_ubi1_da',
			10 => 'po_pag1_da',
			11 => 'insured',
		),
		9 => array( // Generar SELO GMM	
			1 => 'payment_date',
			2 => 'clave_gmm',
			3 => 'uid',
			4 => 'st_nueren',
			5 => 'amount',
			6 => 'allocated_prime',
			7 => 'bonus_prime',
			8 => 'insured', 
			9 => 'plan_type',
			11 => 'tipo_deducible'
		),
	);
/**
 *  Payments
 **/
//funcion que regersa un array que tiene solo los nombres de las paginas para insertar en la tabla de jquery
public function get_sheets_names()
{
	$usable_sheets = array('produccion_','bona','bong_sa','negocios_','conservacion_','puntos_','siniestralidad_','asegurados_','bona_vi','produccion_sa','asegurados_','BONA_SA' );
	$final_sheets = array();
	if( !empty( $_FILES ) )
	{
		$name = explode( '.', $_FILES['file']['name'] );
		if( $name[1] == 'xls' || $name[1] == 'xlsx' )
		{
			$this->load->library( 'excelreader' );
			$file = $this->excelreader->upload();
			if (!empty($file))
			{
				$this->excelreader->setInstance( $file ); //use $this->reader
				$spreadsheetreader = $this->excelreader->setSpreadsheetReader();
				$sheets = $spreadsheetreader->Sheets();
				foreach ($sheets as $sheet) {
					if(in_array($sheet, $usable_sheets))
					{
						array_push($final_sheets, $sheet);
					}
				}

			}
		}
	}

	$data = json_encode($final_sheets);
	echo $data;
}

//funcion que lee regresa un array con los datos de una sola pagina del xlsx
public function read_sheet_name()
{
	$namesheets_array= array(
		"bona",
		"produccion_",
		"negocios_",
		"conservacion_",
		"puntos_",
		"bona_vi",
		"bong_sa",
		"produccion_sa",
		"siniestralidad_",
		"asegurados_",
		"BONA_SA",
	);//todos los nombres de las paginas de todos los excel
	$name_sheet = $_POST['name'];
	$sheet_data = array();
	if( !empty( $_FILES ) )
	{
		$name = explode( '.', $_FILES['file']['name'] );
		if( $name[1] == 'xls' || $name[1] == 'xlsx' )
		{
			$this->load->library( 'excelreader' );
			$file = $this->excelreader->upload();

			if (!empty($file))
			{
				$this->excelreader->setInstance( $file );
				$this->excelreader->set_last_update();
				if(in_array($name_sheet, $namesheets_array))
				{
					if( $_POST['index'] != null )
					{
						$sheet_data = $this->excelreader->readSheetData($name_sheet, $_POST['index']); 
					}
					else
					{
						$sheet_data = $this->excelreader->readSheetData($name_sheet);
					}
				}
			}
		}
	}
	$result = json_encode($sheet_data);
	echo $result;
}
public function vinculate_selo_agents()
{
	$this->load->model('termometro/termometro_model');
	$this->termometro_model->complete_data_table();
	echo 'success';
}
public function fix_agents_uids()
{
	$this->load->model('termometro/termometro_model');
	$this->termometro_model->rebuild_agents_uids();
	echo 'success';
}
public function get_lost_data()
{
	$this->load->model('termometro/termometro_model');
	$result = $this->termometro_model->get_incomplete_data();
	$data = json_encode($result);
	echo $data;
}

public function exec_last_funct_termometro()
{
	$this->load->model('termometro/termometro_model');
	$this->termometro_model->set_ingresos_generales_agente();
	echo 'success';
}

public function insert_termometro_data($array, $sheet_name){
	//Funcion similar a insert_sheet_data
	//Logica de la función: Despues de procesar la hoja de excel, se deberia mandar a llamar esta función
	//En esta función, se esperaria hacer un foreach de la sección $sheet_data["data"]

	$db_table_alias = array( //Se definen los nombres de las tablas en la base de datos y a la hoja que corresponden
		"produccion_" => "production_",
		"negocios_" => "business_",
		"conservacion_" => "conservation_",
		"puntos_" => "points_",
		"produccion_sa" => "production_gmm",
		"siniestralidad_" => "siniestralidad_",
		"asegurados_" => "asegurados_",
	);

	$set_table = $db_table_alias[$sheet_name]; //Se guarda el nombre de la tabla en la cual se van a hacer los inserts de acuerdo al alias


	foreach ($array as $key => $item){
		//Buscar la clave del agente de acuerdo al folio obtenido en el array de datos, y despues de recuperarla,
		//agregarla de nuevo al array de datos con un array_push con el identificador "agent_id"
		//Se utiliza la función "$this->user->getIdAgentByFolio();"
		$this->db->insert($set_table,$item); //INSERT INTO produccion_ (array de ids para la tabla) VALUES (datos);
	}
}


//funcion de insertar los datos obtenidos de la funcion anterior y meterlos en db
public function insert_sheet_data($array)
{
	$message = "success";
	try
	{
		$this->db->insert($array->name, $array->data);
	}
	catch (Exception $E)
	{
		$message = "failure: " . $E -> getMessage();
	}
	echo $message;
}

//actual function of import_payments
public function get_date_import()
{
	$fecha;
	$this->load->library( 'reader_csv' );
	$file = $this->reader_csv->upload();
	
	if( !empty( $file ) )
	{
		$this->reader_csv->setInstance( $file );
		$fecha = $this->reader_csv->get_dates();
		echo json_encode(array('month' => $fecha[0], 'year' => $fecha[1]));
	}
}
public function get_preview_import()
{
	$this->load->library( 'reader_csv' );
	$file = $this->reader_csv->upload();
	if( !empty( $file ) )
	{
		$this->reader_csv->setInstance( $file );
		echo $this->reader_csv->read_csv_preview();
	}
}
public function import_file_payments()
{
	$this->load->library( 'reader_csv' );
	$file = $this->reader_csv->upload();
	if( !empty( $file ) )
	{
		$this->reader_csv->setInstance( $file );
		echo $this->reader_csv->import_csv_file();
	}
}
public function import_payments_new()
{
	// Check access teh for import
	if( $this->access_import_payments == false ){
		// Set false message
		$this->session->set_flashdata( 'message', array(
			'type' => false,
			'message' => 'No tiene permisos para ingresar en esta sección "Orden de trabajo Importar", Informe a su administrador para que le otorge los permisos necesarios.'
		));
		redirect( '/', 'refresh' );
	}
	
	//Upload file to server
	if( !empty( $_FILES ) ){
		// 1: Upload the file to import
		$process = 'change-index';
		$product = $_POST['product'];
		$name = explode( '.', $_FILES['file']['name'] );

		if( $name[1] == 'xls' || $name[1] == 'xlsx' ){
			if ($selected_import_option == 10){
				$this->load->library( 'excelreader' );
				$file = $this->excelreader->upload();
				if (!empty($file)){
					$this->excelreader->setInstance( $file );
					$file_array = $this->excelreader->reader();
				}
				$tmp_file = $file;
			} else {
				// Load Library
				$this->load->library( 'reader_excel' );
				$file = $this->reader_excel->upload();
				if( !empty( $file ) ){
					$this->reader_excel->setInstance( $file );
					$file_array = $this->reader_excel->reader();
					$this->_check_import_file_length($file_array, $file); 
				}
				$tmp_file = $file;
			}

		}
		elseif( $name[1] == 'csv' ){
			// Load Library
			$this->load->library( 'reader_csv' );
			$file = $this->reader_csv->upload();
			if( !empty( $file ) ){
				$this->reader_csv->setInstance( $file );
				$file_array = $this->reader_csv->reader();
				$this->_check_import_file_length($file_array, $file);
			}
			$tmp_file = $file;
		}
		if ($selected_import_option == 8 || $selected_import_option == 9){
			if ($selected_import_option == 8){
				$fecha = $file_array[0][7];
			} else if ($selected_import_option == 9) {
				$fecha = $file_array[0][1];
			}
			
			$posted_month = substr($fecha, -4, 2);
			$posted_year = substr($fecha, 0, 4);
		} else {
			$fecha = explode("-", $file_array[0][8]);
			$posted_month = $file_array[0][10];
			$posted_year = $fecha[0];
		}
		if (!$tmp_file)
		{
			$this->session->set_flashdata( 'message', array(
				'type' => false,
				'message' => 'El archivo esta inválido.'
			));
			redirect( '/ot/import_payments', 'refresh' );
		}
	}
}
public function get_agents_options(){
	$sql = "SELECT agents.id, CONCAT(name,' ',lastnames) as name, company_name FROM agents JOIN users on users.id = agents.user_id";
	$query = $this->db->query($sql);
	$res = $query->result_array();
	$str = '';
	foreach ($res as $key => $value) {
		error_log(print_r($value,true));
	}

}
public function update_payment(){
	$sql = "UPDATE payments SET agent_id='".$_POST['id_agent']."' WHERE pay_tbl_id='".$_POST['id_payment']."'";
	$query = $this->db->query($sql);
}
public function import_payments()
{
	// Check access teh for import
	if( $this->access_import_payments == false ){
			// Set false message
		$this->session->set_flashdata( 'message', array(
			'type' => false,
			'message' => 'No tiene permisos para ingresar en esta sección "Orden de trabajo Importar", Informe a su administrador para que le otorge los permisos necesarios.'
		));
		redirect( '/', 'refresh' );
	}
	set_time_limit (3600);
	$save_setting = @ini_get('auto_detect_line_endings');
	@ini_set('auto_detect_line_endings', true);
	$posted_month = null;
	$posted_year = null;
	$tmp_file = null;
	$file_array = array();
	$process = '';
	$work_orders = array();
	$months_to_delete = array();
	$selected_import_option = $_POST['product'];
	if( !empty( $_FILES ) ){
// 1: Upload the file to import
		$process = 'change-index';
		$product = $_POST['product'];
		$name = explode( '.', $_FILES['file']['name'] );
		if( $name[1] == 'xls' || $name[1] == 'xlsx' ){
			if ($selected_import_option == 10){
				echo " start ";
				$this->load->library( 'excelreader' );
				echo (" library loaded ");
				$file = $this->excelreader->upload();
				echo " file upload passed ";
				if (!empty($file)){
					$this->excelreader->setInstance( $file );
					$file_array = $this->excelreader->reader();
					print_r("file_array:");
					print_r("<br />");
					print_r($file_array);
				}
				$tmp_file = $file;
			} else {
				// Load Library
				echo "start";
				$this->load->library( 'reader_excel' );
				echo ("library loaded");
				$file = $this->reader_excel->upload();
				echo "file upload passed";
				if( !empty( $file ) ){
					$this->reader_excel->setInstance( $file );
					$file_array = $this->reader_excel->reader();
					$this->_check_import_file_length($file_array, $file); 
				}
				$tmp_file = $file;
			}

		}
		elseif( $name[1] == 'csv' ){
				// Load Library
			$this->load->library( 'reader_csv' );
			$file = $this->reader_csv->upload();
			if( !empty( $file ) ){
				$this->reader_csv->setInstance( $file );
				$file_array = $this->reader_csv->reader();
				$this->_check_import_file_length($file_array, $file);
			}
			$tmp_file = $file;
		}
		if ($selected_import_option == 8 || $selected_import_option == 9){
			if ($selected_import_option == 8){
				$fecha = $file_array[0][7];
			} else if ($selected_import_option == 9) {
				$fecha = $file_array[0][1];
			}
			
			$posted_month = substr($fecha, -4, 2);
			$posted_year = substr($fecha, 0, 4);
		} else {
			$fecha = explode("-", $file_array[0][8]);
			$posted_month = $file_array[0][10];
			$posted_year = $fecha[0];
		}
		if (!$tmp_file)
		{
			$this->session->set_flashdata( 'message', array(
				'type' => false,
				'message' => 'El archivo esta inválido.'
			));
			redirect( '/ot/import_payments', 'refresh' );
		}
	}
	// Change index
	// 2: "Pre import"
	if( !empty( $_POST ) and isset( $_POST['process'] ) and $_POST['process'] == 'change-index' ){
		if (!isset($_POST['product']) || ($_POST['product'] < 1) || ($_POST['product'] > 9))
		{
			$this->session->set_flashdata( 'message', array(
				'type' => false,
				'message' => 'Ocurrio un error. Consulte a su administrador.'
			));
			redirect( '/ot/import_payments', 'refresh' );
		}
		$process = 'choose-agents';
		$select_agents = false;
		$product = (int) $_POST['product'];
			// Load Model

		$this->load->model( array( 'work_order', 'usuarios/user' ) );
		$tmp_file = $_POST['tmp_file'];
		$name = explode( '.', $tmp_file );
		if( $name[1] == 'xls' ){
				// Load Library
			$this->load->library( 'reader_excel' );
			if( !empty( $tmp_file ) ){
				$this->reader_excel->setInstance( $tmp_file );
				$file_array = $this->reader_excel->reader();
			}
		}else{
				// Load Library
			$this->load->library( 'reader_csv' );
			if( !empty( $tmp_file ) ){
				$this->reader_csv->setInstance( $tmp_file );
				$file_array = $this->reader_csv->reader();
			}
		}
        
		//Section used to check the month included on the imported file
		
		if ($selected_import_option == 8 || $selected_import_option == 9){
			if ($selected_import_option == 8){
				$fecha = $file_array[0][7];
			} else {
				$fecha = $file_array[0][1];
			}
			
			$posted_month = substr($fecha, -4, 2);
			$posted_year = substr($fecha, 0, 4);
		} else {
			$fecha = explode("-", $file_array[0][8]);
			$posted_month = $file_array[0][10];
			$posted_year = $fecha[0];
		}
		unset( $_POST['tmp_file'], $_POST['process'], $_POST['product'] );

		$this->load->helper('date');
		$fields_to_import = $this->imported_fields[$product];
		for( $i=0; $i<=count( $file_array ); $i++ ){
			$this->db->close();
			$this->db->initialize();
// Prepare the import
			if( isset( $file_array[$i] ) )
			{
				if ($selected_import_option == 8 || $selected_import_option == 9){
					$temp_agent_name = explode(" - ",$file_array[$i][2]);
					$always_imported = array(
						'imported_folio' => $file_array[$i][3],
						'imported_agent_name' => $temp_agent_name[1],
						'import_date' => sprintf("%04d-%02d-01", $posted_year, $posted_month));
				} else {
					$always_imported = array(
						'imported_folio' => $file_array[$i][4],
						'imported_agent_name' => $file_array[$i][11],
						'import_date' => sprintf("%04d-%02d-01", $posted_year, $posted_month));
				}
				

///////////////////////////////
				$sometimes_imported = array();
				foreach ($fields_to_import as $key => $value)
				{
					$sometimes_imported[$value] = $file_array[$i][$key];
					switch ($value)
					{
						case 'clave_gmm':
							$temp_agent_name = explode(" - ",$sometimes_imported[$value]);
							$sometimes_imported['agent_uidsprovincial'] = $temp_agent_name [0];
							$sometimes_imported['agent'] = $this->user->getAgentByFolio( $sometimes_imported['agent_uidsprovincial'], 'provincial', $i  );
							$sometimes_imported['agent_id'] = $this->user->getIdAgentByFolio( $sometimes_imported['agent_uidsprovincial'], 'provincial' );
							$sometimes_imported[$value] = $temp_agent_name[1];
						break;
						case 'clave':
							$sometimes_imported['agent'] = $this->user->getAgentByFolio( $sometimes_imported[$value], 'clave', $i  );
							$sometimes_imported['agent_id'] = $this->user->getIdAgentByFolio( $sometimes_imported[$value], 'clave' );
						break;
						case 'agent_uidsnational':
							$sometimes_imported['agent'] = $this->user->getAgentByFolio( $sometimes_imported[$value], 'national', $i  );
							$sometimes_imported['agent_id'] = $this->user->getIdAgentByFolio( $sometimes_imported[$value], 'national' );
						break;
						case 'agent_uidsprovincial':
							$sometimes_imported['agent'] = $this->user->getAgentByFolio( $sometimes_imported[$value], 'provincial', $i  );
							$sometimes_imported['agent_id'] = $this->user->getIdAgentByFolio( $sometimes_imported[$value], 'provincial' );
						break;
						case 'inter':
							$temp_agent_name = explode(" - ",$sometimes_imported[$value]);
							$sometimes_imported[$value] = $temp_agent_name[1];
						break;
						case 'uid':
/*                              $file_array[$i]['uid']=ltrim( $file_array[$i]['uid'], '00000' );
								$file_array[$i]['uid']=ltrim( $file_array[$i]['uid'], '0000' );
								$file_array[$i]['uid']=ltrim( $file_array[$i]['uid'], '000' );
								$file_array[$i]['uid']=ltrim( $file_array[$i]['uid'], '00' );
								$file_array[$i]['uid']=ltrim( $file_array[$i]['uid'], '0' );*/
								$sometimes_imported[$value] = ltrim( $sometimes_imported[$value], '0' );
								break;
								case 'year_prime':
								if ($product == 3)
									$sometimes_imported[$value] = (strtoupper($sometimes_imported[$value]) == 'N') ? 1 : 2;
								break;
								case 'st_nueren':
									if (strtolower($sometimes_imported[$value]) == "nuevo"){
										$sometimes_imported['year_prime'] = 1;
									} else if (strtolower($sometimes_imported[$value]) == "renovado"){
										$sometimes_imported['year_prime'] = 2;
									}
								break;
								case 'amount':
									if ($product == 8 || $product == 9){
										$sometimes_imported[$value] = str_replace(',','.',$sometimes_imported[$value]);
									}	
								break;
								case 'allocated_prime':
									if ($product == 8 || $product == 9){
										$sometimes_imported[$value] = str_replace(',','.',$sometimes_imported[$value]);
									}	
								break;
								case 'bonus_prime':
									if ($product == 8 || $product == 9){
										$sometimes_imported[$value] = str_replace(',','.',$sometimes_imported[$value]);
									}	
								break;
								case 'payment_date':
								if (!my_check_date($sometimes_imported[$value], $product))
								{
									$this->session->set_flashdata( 'message', array(
										'type' => false,
										'message' => 'No se pudo importar el archivo porque contiene fecha(s) de pago invalida(s).'
									));
									redirect( '/ot/import_payments', 'refresh' );
								} else {
									if ($product == 8 || $product == 9){
										$sometimes_imported[$value] = substr_replace($sometimes_imported[$value], '-', 4, 0);
										$sometimes_imported[$value] = substr_replace($sometimes_imported[$value], '-', 7, 0);
										$temp_test = substr($sometimes_imported[$value],0,7);
										if (!in_array($temp_test, $months_to_delete)) {
											array_push($months_to_delete, $temp_test);
										}
									}
								}
								break;
								case 'is_new':
//                              if ($product == 3)
//                                  $sometimes_imported[$value] = (strtoupper($sometimes_imported[$value]) == 'N') ? 1 : 0;
								break;
								case 'name':
								break;
								default:
								break;
							}
							$file_array[$i]['wathdo'] = '';
							$sometimes_imported['wathdo'] = '';
/*                      if( isset( $sometimes_imported['year_prime'] ) and ($sometimes_imported['year_prime'] == 1 ) &&
							isset($sometimes_imported['uid']))
						{
							$policy = $this->work_order->getPolicyByUid( $sometimes_imported['uid'] );
							if( empty( $policy ) )
								$sometimes_imported['wathdo'] = $this->work_order->getWathdo( $i );
							else
								$sometimes_imported['wathdo'] =  $this->work_order->getByPolicyUid( $sometimes_imported['uid'] );
						}
*/
					}
					$file_array[$i] = array_merge($sometimes_imported, $always_imported);
					if (!$select_agents &&
						(strpos($sometimes_imported['agent'], '<select name=') === 0))
						$select_agents = TRUE;
					}
				}

			if (!$select_agents) // agent assignment not needed => go directly to preview
			$process = 'preview';

// Here: $file_array contains an array of arrays - each of these arrays are indexed by the fields to import
			// Load Model
			$this->load->helper('file');
			$time = time();
			$filename = "./application/modules/ot/assets/tmp/payments_".$time.".json";
			write_file($filename, json_encode($file_array));
			$this->session->set_userdata('tmp_payment_file', $filename);
			//var_dump($months_to_delete);
			if ($product == 8 || $product == 9){
				$this->deleteSeloPayments($months_to_delete, $product);		
			}
			for( $i=0; $i<=count( $file_array ); $i++ )
				unset( $file_array[$i]['agent_id'] );
		}

// 3: Allow user to assign agents to payments
		// Change Selects Agents
		if( !empty( $_POST ) and isset( $_POST['process'] ) and $_POST['process'] == 'choose-agents' ){
			// Load Helper
			$this->load->helper('file');
			$filename = $this->session->userdata('tmp_payment_file');
			$file_array = read_file($filename);
			unlink($filename);
			if (!$file_array)
				$this->_abort_import(1);
			$file_array = json_decode( $file_array );
			if (!$file_array)
				$this->_abort_import(2);
			$tmp_file = $_POST['tmp_file'];
			$process = 'preview';
			$product = $_POST['product'];
			unset( $_POST['tmp_file'], $_POST['process'],  $_POST['product'] );
			$i=0;
			foreach( $file_array as $value ){
				if( isset( $_POST['agent_id'][$i] ) and is_numeric( $_POST['agent_id'][$i] ) ){
					$file_array[$i]->agent_id =  $_POST['agent_id'][$i];
					$file_array[$i]->agent = $this->user->getAgentsById( $_POST['agent_id'][$i] );
					$timestamp = strtotime( date( 'Y-m-d H:s:i' ) );

					if( isset( $file_array[$i]->agent_uidsnational ) ){
						$exist = $this->user->getIdAgentByFolio( $file_array[$i]->agent_uidsnational, 'national'  );
						$uids_agens = array(
							'agent_id' => $file_array[$i]->agent_id,
							'type' => 'national',
							'uid' =>  $file_array[$i]->agent_uidsnational,
							'last_updated' => $timestamp,
							'date' => $timestamp
						);
						if( empty( $exist ) )
							$this->user->create( 'agent_uids', $uids_agens );
					}

					if(  isset( $file_array[$i]->agent_uidsprovincial ) ){
						$exist = $this->user->getIdAgentByFolio( $file_array[$i]->agent_uidsprovincial, 'provincial' );
						$uids_agens = array(
							'agent_id' => $file_array[$i]->agent_id,
							'type' => 'provincial',
							'uid' =>  $file_array[$i]->agent_uidsprovincial,
							'last_updated' => $timestamp,
							'date' => $timestamp
						);
						if( empty( $exist ) )
							$this->user->create( 'agent_uids', $uids_agens );
					}
				}

				$i++;
			}

			write_file($filename, json_encode($file_array));
			for( $i=0; $i<=count( $file_array ); $i++ )
				if( isset( $file_array[$i]->agent_id ) )
					unset( $file_array[$i]->agent_id );
			}

		// Preview
			if( !empty( $_POST ) and isset( $_POST['process'] ) and $_POST['process'] == 'preview' ){
		  // Load Model
				$this->load->model( array( 'work_order', 'usuarios/user' ) );
				$this->load->helper('file');
				$filename = $this->session->userdata('tmp_payment_file');
				$file_array = read_file($filename);
				unlink($filename);
				if (!$file_array) 
					$this->_abort_import(1);
				$file_array = json_decode( $file_array );
				if (!$file_array)
					$this->_abort_import(2);
				$product = $_POST['product'];
				$controlSaved = true;
				$i = 1;
				$message = array( 'type' => false );
      
                if ($product == 4 || $product == 8){ //If the selectbox option picked was 4 (Vida SELO), then we have to set it as 1 again for proper storing on the database
                    $product = 1;
				}
				
				if ($product == 5 || $product == 9){ //If the selectbox option picked was 5 (GMM SELO), then we have to set it as 2 again for proper storing on the database
                    $product = 2;
                }

				if (!count($file_array))
				{
					$message['message'][0][0]['saved'] = 'No se pudo importar el archivo: los datos de preimportacion no existen mas.';
				}
				else
				{
					$posted_ramo = $this->input->post('product');
					if (isset($file_array[0]) && isset($file_array[0]->import_date) && $posted_ramo)
					{
						$where = array(
							'import_date' => $file_array[0]->import_date,
							'product_group' => (int)$posted_ramo
						);
						$this->work_order->generic_delete('payments', $where);
					}

					foreach( $file_array as $item ){
				// Verify policy
				//$policy = $this->work_order->getPolicyByUid( $item->uid );
						$payment_date = strtotime( $item->payment_date );
						$stringed_payment_date = date( 'Y-m-d', $payment_date );
                        $pai = 0;
                        if ($item->year_prime == 1){
                            $pai = $this->user->create_negocio_pai($item->uid, $product, $stringed_payment_date,$item->amount);
						}
						//Process file to SELO
						
						if ($selected_import_option == 8){ //En caso de que se importe Archivo Vida para SELO
							$allocated_prime_temp = $item->amount * ($item->po_ubi1_da / 100);
							$bonus_prime_temp = $item->amount * ($item->po_pag1_da / 100);
							$payment = array(
								'product_group' => $product, //Obtained from POST
								'agent_id' => $item->agent_id, 
								'year_prime' => $item->year_prime, //Obtained from csv
								'agent_clave' => $item->interl,
								'currency_id' => 1,
								'amount' => $item->amount, //Obtained from csv
								'payment_date' => $stringed_payment_date, 
								'business' => 0,
								'pai_business' => $pai,
								'policy_number' => $item->uid,
								'last_updated' => date( 'Y-m-d H:i:s' ),
								'date' => date( 'Y-m-d H:i:s' ),
								'import_date' => $item->import_date,
								'imported_agent_name' => $item->imported_agent_name,
								'imported_folio' => $item->imported_folio,
								'agent_generation' => ($this->input->post( 'ramo' ) == 1) ? 
									$this->user->generationByAgentIdNew($stringed_payment_date,$item->agent_id,'vida') : 
									$this->user->generationByAgentIdNew($stringed_payment_date,$item->agent_id,'gmm'),
								'allocated_prime' => $allocated_prime_temp,
								'bonus_prime' => $bonus_prime_temp
							);

						}else if ($selected_import_option == 9){ //En caso de que se importe Archivo GMM para Selo

							$payment = array(
								'product_group' => $product,
								'agent_id' => $item->agent_id,
								'year_prime' => $item->year_prime,
								'currency_id' => 1,
								'amount' => $item->amount,
								'payment_date' => $stringed_payment_date,
								'business' => $item->is_new,
								'pai_business' => $pai,
								'policy_number' => $item->uid,
								'last_updated' => date( 'Y-m-d H:i:s' ),
								'date' => date( 'Y-m-d H:i:s' ),
								'import_date' => $item->import_date,
								'imported_agent_name' => $item->imported_agent_name,
								'imported_folio' => $item->imported_folio,
								'agent_generation' => ($this->input->post( 'ramo' ) == 1) ? 
									$this->user->generationByAgentIdNew($stringed_payment_date,$item->agent_id,'vida') : 
									$this->user->generationByAgentIdNew($stringed_payment_date,$item->agent_id,'gmm'),
								'allocated_prime' => $item->allocated_prime,
								'bonus_prime' => $item->bonus_prime,
								'insured_name' => $item->insured,
								'plan_type' => $item->plan_type,
								'deducible_type' => $item->tipo_deducible
							);
						} else {

							$payment = array(
								'product_group' => $product,
								'agent_id' => $item->agent_id,
								'year_prime' => $item->year_prime,
								'currency_id' => 1,
								'amount' => $item->amount,
								'payment_date' => $stringed_payment_date,
								'business' => $item->is_new,
								'pai_business' => $pai,
								'policy_number' => $item->uid,
								'last_updated' => date( 'Y-m-d H:i:s' ),
								'date' => date( 'Y-m-d H:i:s' ),
								'import_date' => $item->import_date,
								'imported_agent_name' => $item->imported_agent_name,
								'imported_folio' => $item->imported_folio,
								'agent_generation' => ($this->input->post( 'ramo' ) == 1) ? 
									$this->user->generationByAgentIdNew($stringed_payment_date,$item->agent_id,'vida') : 
									$this->user->generationByAgentIdNew($stringed_payment_date,$item->agent_id,'gmm'),
								'allocated_prime' => $item->allocated_prime,
								'bonus_prime' => $item->bonus_prime
							);

						}

						$user_id = $this->user->getUserIdByAgentId( $item->agent_id);
						if (!$user_id)
						{
							$message['message'][0][$i]['saved'] = 'La linea '.$i.' no se ha podido importar';
						}

						else
						{
							if( $this->work_order->replace( 'payments', $payment ) == false )
								$controlSaved = false;

							$policy = $this->work_order->getPolicyByUid( $item->uid, false );
							if ($controlSaved && $policy)
							{
								if ($policy[0]['currency_id'] == 1)
									$item_amount = $item->amount;
							else // if policy in USD, convert payment amount from MXN to USD
								// using the exchange rate at payment's payment date
							{
								$this->load->model('exchange_rate_model');
								$item_amount = $this->exchange_rate_model->convert_prima(
									$item->amount, 1, 2, $stringed_payment_date);
							}
							$prima_total = $policy[0]['prima_entered'];
							if ($policy[0]['payment_interval_id'] == 1) {
								$interval_pay = 12;
							} elseif ($policy[0]['payment_interval_id'] == 2) {
								$interval_pay = 3;
							}elseif ($policy[0]['payment_interval_id'] == 3) {
								$interval_pay = 2;
							}elseif ($policy[0]['payment_interval_id'] == 4) {
								$interval_pay = 1;
							}
							if (($item_amount != FALSE) && ($prima_total/$interval_pay) >= $item_amount)
							{
								$ot = $this->work_order->getWorkOrderByPolicy(  $policy[0]['id'] );
								if( !empty( $ot ) )
								{
									$work_order = array( 'work_order_status_id' => 4 );
									$this->work_order->update( 'work_order',
										$ot[0]['id'], $work_order );
								}
							}
						}

						if($policy){
							$po = $this->work_order->getWorkOrderByPolicy($policy[0]['id']);
							$work_orders[] = $po[0];
						}
						if( $controlSaved == false )
							$message['message'][0][$i]['saved'] = 'La linea '.$i.' no se ha podido importar';
					}
					$i++;
				}
			}
			if( !isset( $message['message'] ) ){

				$message['type'] = true;
				$message['message'] = 'El archivo se importo correctamente.';
			}
			// Clean System
			$tmp_file = $_POST['tmp_file'];
			$name = explode( '.', $tmp_file );
			if( $name[1] == 'xls' ){
				// Load Library
				$this->load->library( 'reader_excel' );
				if( !empty( $tmp_file ) ){
					$this->reader_excel->setInstance( $tmp_file );
					$this->reader_excel->drop();
				}
			}else{
				// Load Library
				$this->load->library( 'reader_csv' );
				if( !empty( $tmp_file ) ){
					$this->reader_csv->setInstance( $tmp_file );
					$this->reader_csv->drop();
				}
			}
			$process = "finished";
		}

		@ini_set('auto_detect_line_endings', $save_setting);

		// Load Model
		$this->load->model( 'work_order' );
		$products = $this->work_order->getProductsGroupsOptions();

		// Config view
		$this->view = array(

			'title' => 'Ot Importar',
		   // Permisions
			'user' => $this->sessions,
			'user_vs_rol' => $this->user_vs_rol,
			'roles_vs_access' => $this->roles_vs_access,
			'css' => array(
				'<link href="'. base_url() .'ot/assets/style/import_payments.css" rel="stylesheet">'
			),
			'scripts' =>  array(
				'<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/jquery.validate.js"></script>',
				'<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/es_validator.js"></script>',
//            '<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.js"></script>',
				'<script type="text/javascript">$( document ).ready( function(){ $( "#formfile" ).validate(); });</script>',
				'<script src="'.base_url().'scripts/config.js"></script>',
				'<script src="'.base_url().'ot/assets/scripts/import.js?'.time().'"></script>'
			),
		  'content' => 'ot/import_payments', // View to load
		  'products' => $products,
		  'access_delete' => $this->access_delete,
		  'message' => $this->session->flashdata('message') // Return Message, true and false if have
		);
		if( isset( $message ) ){
			$this->view['message'] = $message;
			unset( $tmp_file, $file_array );
		}
		if( isset( $tmp_file ) and !empty( $tmp_file ) )
			$this->view['tmp_file'] = $tmp_file;
		if( isset( $process ) and !empty( $process ) )
			$this->view['process'] = $process;
		if( isset( $product ) and !empty( $product ) )
			$this->view['product'] = $product;
		if( isset( $file_array ) and !empty( $file_array ) )
			$this->view['file_array'] = $file_array;
		if( isset( $work_orders ) and !empty( $work_orders ) )
			$this->view['work_orders'] = $work_orders;
		$this->view['month'] = $posted_month;
		$this->view['year'] = $posted_year;

		// Render view
		$this->load->view('index',$this->view );
	}


/**
 *  Reports
 **/
public function reporte()
{
		// Check access for report
	if( $this->access_report == false )
	{
			// Set false message
		$this->session->set_flashdata( 'message', array
			(
				'type' => false,
				'message' => 'No tiene permisos para ingresar en esta sección "Orden de trabajo Ver Reporte", Informe a su administrador para que le otorge los permisos necesarios.'
			));
		redirect( '/', 'refresh' );
	}

	$agent_array = array();
	$other_filters = array();
	$this->load->helper( array('ot', 'filter' ));
	if (count($_POST))
	{
		update_custom_period($this->input->post('cust_period_from'),
			$this->input->post('cust_period_to'), FALSE);
	}
	$data = $this->_init_report($agent_array, $other_filters);

		// Load model
	$this->load->model( array( 'usuarios/user', 'work_order' ) );

	unset( $data[0] );

	$agent_multi = array();
	foreach ( $agent_array as $key => $value )
	{
		$agent_multi[] = "\n'$value [ID: $key]'";
	}
	$inline_js =
	'
	<script type="text/javascript">
	$( document ).ready( function(){
		var agentList = [' . implode(',', $agent_multi) . '
	];

	function split( val ) {
	return val.split( /\n\s*/ );
}
function extractLast( term ) {
	return split( term ).pop();
}
$( ".submit-form").bind("click", function( event ) {
	$( "#form").submit();
})
$( "#clear-agent-filter").bind("click", function( event ) {
	$( "#agent-name" ).val("");
	$( "#form").submit();
})
$( "#agent-name" )
		// don\'t navigate away from the field on tab when selecting an item
.bind( "keydown", function( event ) {
	if ( event.keyCode === $.ui.keyCode.TAB &&
		$( this ).data( "ui-autocomplete" ).menu.active ) {
			event.preventDefault();
		}
	})
	/*          .bind( "change", function( event ) {
		alert("changed!");
	})*/
	.autocomplete({
		minLength: 0,
		source: function( request, response ) {
					// delegate back to autocomplete, but extract the last term
			response( $.ui.autocomplete.filter(
				agentList, extractLast( request.term ) ) );
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
				$( "#form").submit();
				return false;
			}
		})
	});
	</script>
	';

	$filter_data = array(
		'manager' => $this->user->getSelectsGerentes2(),
		'period_fields' => show_period_fields('ot_reporte', $other_filters['ramo']),
		'other_filters' => $other_filters,
		'export_xls' => $this->access_export_xls,
		'report_columns' => $this->load->view('filters/report_columns', array(), true)
	);
	$filter_view = $this->load->view('filters/report', $filter_data, true);

		// Config view
	$this->view = array(

		'title' => 'Ot Ver reporte',
		   // Permisions
		'user' => $this->sessions,
		'user_vs_rol' => $this->user_vs_rol,
		'roles_vs_access' => $this->roles_vs_access,
//        'export_xls' => $this->access_export_xls,
		'css' => array(
//          '<link href="'. base_url() .'ot/assets/style/report.css" rel="stylesheet">',
			'<link href="'. base_url() .'ot/assets/style/theme.default.css" rel="stylesheet">',
			'<!--<link rel="stylesheet" href="'. base_url() .'ot/assets/style/normalize.min.css">-->',
			'<link rel="stylesheet" href="'. base_url() .'ot/assets/style/jquery.fancybox.css">',
			'<link href="'. base_url() .'ot/assets/style/report.css" rel="stylesheet">',
			'<link rel="stylesheet" href="'. base_url() .'ot/assets/style/main.css">',
			'<style>
			.fancybox_blanco {color: #CCCCFF;}
			.fancybox_blanco:hover{color: #FFFFFF;}
			</style>',
		),
		'scripts' =>  array(
			'<script type="text/javascript" src="'.base_url().'scripts/jquery.cookie.js"></script>',
			'<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/jquery.validate.js"></script>',
			'<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/es_validator.js"></script>',
			//'<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.js"></script>',
			//'<script type="text/javascript" language="javascript" src="'. base_url() .'ot/assets/plugins/DataTables/media/js/jquery.dataTables.js"<script>',
			'<script src="'. base_url() .'ot/assets/scripts/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>',
			'<script>window.jQuery || document.write ("<script src='. base_url() .'ot/assets/scripts/vendor/jquery-1.10.1.min.js><\/script>");</script>',
			'<script type="text/javascript" src="'. base_url() .'ot/assets/scripts/jquery.ddslick.js"></script>',
			'<script type="text/javascript" src="'. base_url() .'ot/assets/scripts/jquery.tablesorter-2.14.5.js"></script>',
			'<script type="text/javascript" src="'. base_url() .'ot/assets/scripts/jquery.tablesorter.widgets-2.14.5.js"></script>',
			'<script type="text/javascript" src="'. base_url() .'ot/assets/scripts/main.js"></script>',
			'<script src="'.base_url().'scripts/config.js"></script>'   ,
			'<script src="'.base_url().'ot/assets/scripts/report.js"></script>',
			'<script src="'.base_url().'ot/assets/scripts/jquery.fancybox.js"></script>',
			'<script type="text/javascript" src="'. base_url() .'scripts/select_period.js"></script>',
			'<script type="text/javascript" src="'. base_url() .'scripts/report_columns.js"></script>',
			$inline_js,
			'
			<script type="text/javascript">
			function payment_popup(params) {
				$.fancybox.showLoading();
				$.post("' . base_url() . 'ot/payment_popup.html", jQuery.param(params) + "&" + $("#form").serialize(), function(data) {

					if (data) {
						$.fancybox({
							content:data
						});
						return false;
					}
				});
			}
			</script>
			'         ),
//        'manager' => $this->user->getSelectsGerentes2(),
		  'content' => 'ot/report', // View to load
		  'data' => $data,
		  'tata' => $_POST,
//        'period_fields' => show_period_fields('ot_reporte', $other_filters['ramo']),
//        'other_filters' => $other_filters,
		  'filter_view' => $filter_view,
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		);
		// Render view
	$this->load->view( 'index', $this->view );
}

/**
 *  Reports Popup
 **/
// Copied and pasted to the code of agent/reporte_popup.html:
public function reporte_popup()
{
	$this->load->helper('ot/ot');
	reporte_popup('ot');
}

// Popup pertaining to payments (NOTE: copied and pasted of agent/.../payment_popup.html code)
public function payment_popup()
{
	$data = array('values' => FALSE,
		'access_update' => $this->access_update,
		'access_delete' => $this->access_delete,
	);
	$this->load->model('usuarios/user');
	$filter = array();
	$posted_filter_query = $this->input->post('query');
	if ( ($posted_filter_query !== FALSE) )
		$filter['query'] = $posted_filter_query;

	$request_type = $this->input->post('type');
	switch ( $request_type )
	{
		case 'negocio':
		$data['values'] = $this->user->getNegocioDetails( $this->input->post('for_agent_id'), $filter );
		break;
		case 'negociopai':
		$data['values'] = $this->user->getNegocioPai( $this->input->post('for_agent_id'), $filter );
		break;
		case 'prima':
		$data['values'] = $this->user->getPrimaDetails( $this->input->post('for_agent_id'), $filter );
		break;
		case 'cartera':
		$data['values'] = $this->user->getCarteraDetails( $this->input->post('for_agent_id'), $filter );
		break;
		case 'cobranza':
		$data['values'] = $this->user->getCobranzaDetails( $this->input->post('for_agent_id'), $filter );
		$this->load->view('popup_cobranza', $data);
		return;
		break;
		default:
		exit('Ocurrio un error. Consulte a su administrador.');
		break;
	}
	$this->load->view('popup_payment', $data);
}

public function reporte_popupa()
{
	$work_order_ids = $this->input->post('wrk_ord_ids');
	$data['is_poliza'] = $this->input->post('is_poliza');
	$data['gmm'] = $this->input->post('gmm');
	$work_ids = explode(',',$work_order_ids);
	$this->load->model('work_order');

	$this->view = array(
		'css' => array(
			'<link href="'. base_url() .'ot/assets/style/report.css" rel="stylesheet">',
			'<!--<link rel="stylesheet" href="'. base_url() .'ot/assets/style/normalize.min.css">-->
			<link rel="stylesheet" href="'. base_url() .'ot/assets/style/main.css">',
			'<link rel="stylesheet" href="'. base_url() .'ot/assets/style/jquery.fancybox.css">'
		),
		'scripts' =>  array(
			'<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/jquery.validate.js"></script>',
			'<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/es_validator.js"></script>',
			'<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.js"></script>',
			//'<script type="text/javascript" language="javascript" src="'. base_url() .'ot/assets/plugins/DataTables/media/js/jquery.dataTables.js"<script>',
			'<script src="'. base_url() .'ot/assets/scripts/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>',
			'<script>window.jQuery || document.write ("<script src='. base_url() .'ot/assets/scripts/vendor/jquery-1.10.1.min.js><\/script>");</script>',
			'<script type="text/javascript" src="'. base_url() .'ot/assets/scripts/jquery.ddslick.js"></script>',
			'<script type="text/javascript" src="'. base_url() .'ot/assets/scripts/jquery.tablesorter.js"></script>',
			'<script type="text/javascript" src="'. base_url() .'ot/assets/scripts/main.js"></script>',
			'<script src="'.base_url().'scripts/config.js"></script>'   ,
			'<script src="'.base_url().'ot/assets/scripts/report.js"></script>',
			'<script src="'.base_url().'ot/assets/scripts/jquery.fancybox.js"></script>'
		));

	$results = array();
	foreach($work_ids as $work_order_id)
	{
		$results[] = $this->work_order->pop_up_data($work_order_id);
	}
	$data['values'] = $results;
	$this->load->view('popup_report',$data);
}

 /**
 *  Reports Popup
 **/
 public function reporte_popup_later()
 {
		//$data['value'] = $this->uri->segment(3);
 	$this->load->model(array('work_order'));
 	$data['values'] = $this->work_order->pop_up_data();
 	$result = $this->load->view('popup_report',$data);
 	echo json_encode($result);
 }

/**
 *  Email Popup
 **/
public function email_popup()
{
		//$data['email_address'] = $this->uri->segment(3);
	$tata = $this->input->post("work_ids");
	$email = $this->input->post("email");
	$data['Id'] = substr($tata,9, -1);
	$data['username'] = $this->sessions['username'];
	$this->load->view('popup_email',$data);
}

 /**
 *  Send Email
 **/
 public function send_email()
 {
 	$email_address = $this->input->post('email_address');
 	$email_body = $this->input->post('email_body');
 	$this->load->library('email');
 	$this->email->set_mailtype("html");
 	$this->email->from('proAges@example.com','proAges');
 	$this->email->to($email_address);
 	$this->email->subject('Email from proAges');
 	$this->email->message($email_body);

 	$result = $this->email->send();
 	echo json_encode($result);
 }

 private function _report_export_helper($value, $ramo = 'vida_gmm')
 {
 	if ($ramo == 'vida_gmm')
 		$data_row = array(
 			'name' => $value['name'],
 			'uids' => '',
 			'connection_date' => '',
 			'negocio' => $value['negocio'],
 			'negociopai' => 0,
 			'prima' => $value['prima'],
 			'tramite' => 0,
 			'tramite_prima' => 0,
 			'pendientes' => 0,
 			'pendientes_primas' => 0,
 			'cobranza' => 0,
 			'negocios_proyectados' => 0,
 			'negocios_proyectados_primas' => 0,
 			'cartera' => 0,
 		);
 	else
 		$data_row = array(
 			'name' => $value['name'],
 			'uids' => '',
 			'connection_date' => '',
 			'iniciales' => $value['iniciales'],
 			'renovaciones' => $value['renovacion'],
 			'totales' => (int)$value['iniciales']+(int)$value['renovacion']
 		);
 	if ( !empty( $value['uids'][0]['type'] ) && ($value['uids'][0]['type'] == 'clave')
 		&& !empty( $value['uids'][0]['uid'] ))
 		$data_row['uids'] =  $value['uids'][0]['uid'];
 	else
 		$data_row['uids'] = 'Sin clave asignada';

 	if ( !empty( $value['connection_date'] ) && ($value['connection_date'] != '0000-00-00' ))
 		$data_row['connection_date'] =  $value['connection_date'];
 	else
 		$data_row['connection_date'] = 'No Conectado';

 	return $data_row;
 }

 public function report_export()
 {
 	$agent_array = array();
 	$other_filters = array();
 	$data = $this->_init_report($agent_array, $other_filters);
 	$data_report = array();
		// Load Helper
 	$this->load->helper( array( 'usuarios/csv', 'ot' ) );

 	if( empty( $_POST ) or isset( $_POST['query']['ramo'] )  and $_POST['query']['ramo'] !=3  )
		{ // Vida or GMM
			$total_negocio=0;
			$total_negocio_pai=0;
			$total_primas_pagadas=0;
			$total_negocios_tramite=0;
			$total_primas_tramite=0;
			$total_negocio_pendiente=0;
			$total_primas_pendientes=0;
			$total_cartera = 0;
			$total_negocios_proyectados=0;
			$total_primas_proyectados=0;
			$total_cobranza = 0;

			if( !empty( $data ) )
			{
				foreach ( $data as $key => $value )
				{
					if ($key == 0)
						$data_report[] = array(
							'name' => 'Agentes',
							'uids' => 'Clave única',
							'connection_date' => 'Fecha de conexión',
							'negocio' => 'Negocios Pagados',
							'negociopai' => 'Negocios PAI',
							'prima' => 'Primas Pagadas',
							'tramite' => 'Negocios en Tramite',
							'tramite_prima' => 'Primas en Tramite', //  (not in $data)
							'pendientes' => 'Negocios Pendientes',
							'pendientes_primas' => 'Primas Pendientes', //  (not in $data)
							'cobranza' => 'Cobranza instalada',
							'negocios_proyectados' => 'Negocios Proyectados',
							'negocios_proyectados_primas' => 'Primas Proyectadas',
							'cartera' => 'Cartera',
						);
					else
					{
						$data_row = $this->_report_export_helper($value, 'vida_gmm');

						if ( is_array( $value['negociopai'] ))
							$data_row['negociopai'] = count( $value['negociopai'] );
						else
							$data_row['negociopai'] = $value['negociopai'];
						if ( isset( $value['tramite']['count'] ) )
						{
							$data_row['tramite'] = $value['tramite']['count'];
							$data_row['tramite_prima'] = $value['tramite']['adjusted_prima'];
						}
						if( isset( $value['aceptadas']['count'] ) )
						{
							$data_row['pendientes'] = $value['aceptadas']['count'];
							$data_row['pendientes_primas'] = $value['aceptadas']['adjusted_prima'];
						}
						if ( isset( $value['cartera'] ) )
							$data_row['cartera'] = $value['cartera'];
						if ( isset( $value['cobranza']) && isset($value['cobranza']['total_due_past'])
							&& isset($value['cobranza']['total_due_future'])
							&& isset($value['cobranza']['total_paid']) )
							$data_row['cobranza'] = $value['cobranza']['total_due_past'] +
						$value['cobranza']['total_due_future'] -
						$value['cobranza']['total_paid'];

						$data_row['negocios_proyectados'] = (int)$data_row['pendientes'] +
						(int)$data_row['tramite'];
						if ($_POST['query']['ramo'] == 1)
							$data_row['negocios_proyectados'] += (int)$data_row['negociopai'];
						else
							$data_row['negocios_proyectados'] += (int)$data_row['negocio'];

						$data_row['negocios_proyectados_primas'] =
						(float)$data_row['prima'] +
						(float)$data_row['pendientes_primas'] + (float)$data_row['tramite_prima'] +
						(float)$data_row['cobranza'];

						$total_negocio += (int)$data_row['negocio'];
						$total_negocio_pai += (int)$data_row['negociopai'];
						$total_primas_pagadas += (float)$data_row['prima'];
						$total_negocios_tramite += (int)$data_row['tramite'];
						$total_primas_tramite += (float)$data_row['tramite_prima'];
						$total_negocio_pendiente += (int)$data_row['pendientes'];
						$total_primas_pendientes += (float)$data_row['pendientes_primas'];
						$total_negocios_proyectados += (int)$data_row['negocios_proyectados'];
						$total_primas_proyectados += (float)$data_row['negocios_proyectados_primas'];
						$total_cartera += (float)$data_row['cartera'];
						$total_cobranza += (float)$data_row['cobranza'];

						$data_row['prima'] = '$ '.$data_row['prima'];
						$data_row['tramite_prima'] = '$ '.$data_row['tramite_prima'];
						$data_row['cartera'] = '$ '.$data_row['cartera'];
						$data_row['cobranza'] = '$ '. number_format($data_row['cobranza']);

						$data_row['pendientes_primas'] = '$ '.$data_row['pendientes_primas'];
						$data_row['negocios_proyectados_primas'] = '$ '.$data_row['negocios_proyectados_primas'];
						$data_report[] = $data_row;
					}
				}

				$data_report[] = array(
					'name' => 'Totales: ',
					'uids' => '',
					'connection_date' => '',
					'negocio' => $total_negocio,
					'negociopai' => $total_negocio_pai,
					'prima' => '$ '.$total_primas_pagadas,
					'tramite' => $total_negocios_tramite,
					'tramite_prima' => '$ '.$total_primas_tramite,
					'pendientes' => $total_negocio_pendiente,
					'pendientes_primas' => '$ '.$total_primas_pendientes,
					'cobranza' => '$ ' . number_format($total_cobranza),
					'negocios_proyectados' => $total_negocios_proyectados,
					'negocios_proyectados_primas' => '$ ' . number_format($total_primas_proyectados),
					'cartera' => '$ ' . number_format($total_cartera),
				);
			}
		} else
		{ // Autos
			$iniciales=0;
			$renovacion=0;
			$totalgeneral=0;
			if( !empty( $data ) )
			{
				foreach ( $data as $key => $value )
				{
					if ($key == 0)
						$data_report[] = array(
							'name' => 'Agentes',
							'uids' => 'Clave única',
							'connection_date' => 'Fecha de conexión',
							'iniciales' => 'Iniciales',
							'renovaciones' => 'Renovaciones',
							'totales' =>  'Totales'
						);
					else
					{
						$data_row = $this->_report_export_helper($value, 'autos');
						$iniciales += (int)$value['iniciales'];
						$renovacion +=(int) $value['renovacion'];
						$totalgeneral += $data_row['totales'];

						$data_report[] = $data_row;
					}
				}
				$data_report[] = array(
					'name' => 'Totales: ',
					'uids' => '',
					'connection_date' => '',
					'iniciales' => $iniciales,
					'renovaciones' => $renovacion,
					'totalgeneral' => $totalgeneral
				);
			}
		}

		array_to_csv($data_report, 'proages_report.csv');

		if( is_file( 'proages_report.csv' ) )
			echo file_get_contents( 'proages_report.csv' );

		if( is_file( 'proages_report.csv' ) )
			unlink( 'proages_report.csv' );
	}

// Init report processing (reporte and exportar)
	private function _init_report(&$agent_array, &$other_filters)
	{
		$data = array();
		$agent_array = $this->user->getAgents( FALSE );

		$this->load->helper('filter');
		$default_filter = get_filter_period();
		$other_filters = array(
			'ramo' => 1,
			'gerente' => '',
			'agent' => '',
			'generacion' => '', // not sure if this should not be 1 instead
			'agent_name' => '',
			'policy_num' => ''
		);
		get_ot_report_filter($other_filters, $agent_array);

		if( !empty( $_POST ) )
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
			if ( isset($_POST['query']['generacion']) &&
				(  ($_POST['query']['generacion'] == '') ||
					( $this->form_validation->is_natural_no_zero($_POST['query']['generacion']) &&
						($_POST['query']['generacion'] <= 6)) )
			)
				$filters_to_save['generacion'] = $_POST['query']['generacion'];
			if ( isset($_POST['query']['agent_name']))
				$filters_to_save['agent_name'] = $_POST['query']['agent_name'];
			if ( isset($_POST['query']['policy_num']))
				$filters_to_save['policy_num'] = $_POST['query']['policy_num'];
			set_ot_report_filter( $filters_to_save, $agent_array );
//          $other_filters = array_merge($other_filters, $filters_to_save);
			foreach ($filters_to_save as $key => $value)
				$other_filters[$key] = $value;
			$data = $this->user->getReport($_POST );
		}
		else
		{
			$query = array_merge($other_filters, array('periodo' => $default_filter));
			$data = $this->user->getReport( array('query' => $query ) );
		}
		return $data;
	}

// Ver OT
	public function ver_ot( $id = null ){

		$this->_update_ver( 'ver', $id);
	}

// Update OT (prima and forma de pago)
	public function update_poliza( $id = null ){

		$this->_update_ver( 'editar', $id);
	}

// Update OT (update more fields)
	public function update_ot( $id = null ){

		$this->_update_ver( 'update', $id);
	}

// Common to Ver and Update OT
	private function _update_ver( $function, $id = null )
	{
		$this->load->model('work_order');
		$this->load->model('policy_model');
		$ot = $this->work_order->getWorkOrderById($id);
		// Check if OT exists
		if ( $ot === FALSE ) {
			$this->session->set_flashdata( 'message', array(
				'type' => false,
				'message' => 'No existe esta orden de trabajo.'
			));
			redirect( 'ot', 'refresh' );
		}

		if (!$this->access_update)
		{
			foreach ($ot[0]['agents'] as $ot_agent)
			{
				if ( ($ot_agent['user_id'] == $this->sessions['id']) && ($function == 'ver') )
				{
					$this->access_update = TRUE;
					$break;
				}
			}
		}

		// Check access to the function
		if ( !$this->access_update )
		{
			if ($function == 'ver')
				$message = 'No tiene permisos para ingresar la sección "Ver Orden de trabajo" o no puede ver esta Orden de trabajo. Informe a su administrador.';
			else
				'No tiene permisos para ingresar la sección "Orden de trabajo Editar". Informe a su administrador para que le otorge los permisos necesarios.';
			$this->session->set_flashdata( 'message', array(
				'type' => false,
				'message' => $message
			));
			redirect( 'ot', 'refresh' );
		}

		// Check if OT is editable
/*      if (! $this->work_order->is_editable( $ot[0]['product_group_id'],
			$ot[0]['parent_type_name']['id'], $ot[0]['status_id'] ) ) {

			$this->session->set_flashdata( 'message', array(
				'type' => false,
				'message' => "No puede $function esta orden de trabajo."
			));
			redirect( 'ot', 'refresh' );
		}*/

		$is_nuevo_negocio = ($ot[0]['parent_type_name']['id'] == 47) || ($ot[0]['parent_type_name']['id'] == 90);

		if ( (($function == 'editar') || ($function == 'update') ) && !empty( $_POST ) )
		{
			if ($function == 'editar') {
				$this->form_validation->set_rules('prima', ' Prima anual',
					'trim|required|decimal_or_integer');
				$this->form_validation->set_rules('currency_id', ' Moneda',
					'trim|required|is_natural_no_zero|less_than[3]');
				$this->form_validation->set_rules('payment_interval_id', ' Forma de pago',
					'trim|required|is_natural_no_zero|less_than[5]');
			} else {
				$this->form_validation->set_rules('ot', 'Número de OT', "is_unique_but[work_order.uid.id.$id]");
				$this->form_validation->set_rules('creation_date', 'Fecha de tramite', 'trim|required|min_length[10]');
				$this->form_validation->set_rules('agent[]', 'Agente', 'trim|required|is_natural_no_zero');
				$this->form_validation->set_rules('name', 'Nombre del asegurado / contratante', 'trim|strtoupper|required|xxs_clean');
				$this->form_validation->set_rules('ot_status', 'Estado', 'trim|required|is_natural_no_zero');
				$this->form_validation->set_rules('comments', 'Comentarios', 'trim|xxs_clean');
				if ($is_nuevo_negocio) {
					if (in_array($ot[0]['status_id'], array(7, 4, 10)))
						$this->form_validation->set_rules('policy_uid', 'Poliza', 'trim|xxs_clean');
					$this->form_validation->set_rules('currency_id', 'Moneda', 'trim|required|is_natural_no_zero|less_than[3]');
					$this->form_validation->set_rules('payment_method_id', 'Conducto', 'trim|required|is_natural_no_zero|less_than[7]');
					$this->form_validation->set_rules('payment_interval_id', ' Forma de pago', 'trim|required|is_natural_no_zero|less_than[5]');
					$this->form_validation->set_rules('prima', 'Prima anual', 'trim|decimal_or_integer');
					$this->form_validation->set_rules('period', 'Plazo', 'trim|xxs_clean');
				}
				else
					$this->form_validation->set_rules('policy_uid', 'Poliza', 'trim|xxs_clean');
			}
			// Run Validation
			if ( $this->form_validation->run() ) {

				$error = false;
				$current_date = date( 'Y-m-d H:i:s' );
				if ($function == 'editar')
				{

					$field_values = array();
					$error = !$this->_process_update_db_policy_prima(
						$current_date, $field_values);
				} else {

	// 1. update table `work_order`

					$new_status = $this->input->post( 'ot_status' );
					$ot_fields = array(
						'uid' => $this->input->post( 'ot' ),
						'creation_date' => $this->input->post('creation_date') . ' ' . date( 'H:s:i' ),
						'comments' => $this->input->post('comments'),
						'last_updated' => $current_date,
						'work_order_status_id' => $new_status,
						'notes' => $this->input->post('notes')
					);

					if ( !$this->work_order->update( 'work_order', $ot[0]['id'], $ot_fields) )
						$error = true;
					else {
						//Get aditional emails
						$aditional_emails = trim($this->input->post('emails'));

						//Filter string for valid emails only
						if(!empty($aditional_emails)){
							$arr_emails = explode(",", $aditional_emails);
							$valid_emails = array();
							foreach ($arr_emails as $email_aditional) {
								if(filter_var($email_aditional, FILTER_VALIDATE_EMAIL))
									$valid_emails[] = $email_aditional;
							}
							$aditional_emails = implode(",", $valid_emails);
						}


						// send email notification of status update
						if (($ot[0]['status_id'] != $new_status) && ($this->input->post( 'email_notification' )))
							$this->_notify_ot_status_change($ot[0]['id'], $aditional_emails);

	// 2. update table `policies_vs_users`
						$posted_agents = $this->input->post('agent');
						$percentages = $this->input->post('porcentaje');
						if (($posted_agents !== FALSE) && is_array($posted_agents) &&
							is_array($percentages) && (count($posted_agents) == count($percentages))) {
							$agents = array();
						foreach ($posted_agents as $key => $value)
							$agents[] = array(
								'user_id' => $value,
								'policy_id' => $ot[0]['policy_id'],
								'percentage' => $percentages[$key],
								'since' => $current_date
							);
						$this->work_order->generic_delete( 'policies_vs_users',
							array('policy_id' => $ot[0]['policy_id']));
						if (! $this->work_order->create_banch( 'policies_vs_users', $agents ))
							$error = true;
					}
				}

				if (!$error) {
	// 4. update table `policies`
					$field_values = array(
						'name' => $this->input->post( 'name' ),
						'last_updated' => $current_date,
//                          'uid' => $this->input->post( 'uid' )
					);
					if ($is_nuevo_negocio)
					{
						$error = !$this->_process_update_db_policy_prima(
							$current_date, $field_values);

						$field_values = array_merge($field_values, array(
							'payment_method_id' => $this->input->post(
								'payment_method_id' ),
							'period' => $this->input->post( 'period' ),
						));

						if (in_array($ot[0]['status_id'], array(7, 4, 10)))
							$field_values['uid'] = $this->input->post( 'uid' );
					}
					else
						$field_values['uid'] = $this->input->post( 'uid' );
				}

			}
			if ( !$error && isset($field_values) )
				$error = !$this->work_order->update( 'policies', $ot[0]['policy_id'], $field_values);

			if ( !$error )  {
				$message = array(
					'type' => true,
					'message' => 'Se guardo el registro correctamente.'
				);
											//check if work order existes on ajusted_primas table,if it exists replace it
				$this->load->model('policy_model');
				$primas_cached = $this->policy_model->get_ot_adjusted_primas($id);

				if (isset($primas_cached[$id]) &&
					isset($primas_cached[$id]['adjusted_prima']))
				{

					$wo = $this->work_order->getWorkOrderById($id);
					$this->policy_model->recalculate_adjusted_primas($id,$wo[0]['policy_id']);

				}
			}else
			$message = array(
				'type' => false,
				'message' => 'No se pudo guardar el registro (ocurrio un error en la base de datos). Pongase en contacto con el desarrollador.'
			);
			$this->session->set_flashdata( 'message', $message);
			redirect( 'ot', 'refresh' );
		}
	}

		// Get Agents
	$agents = $this->user->getAgents( FALSE );

		// Tramite types
		// (depends on ramo : see $this->work_order->getTypeTramite( $ramo ))
	$tramite_types = $this->work_order->generic_get( 'work_order_types',
		array( 'patent_id' => $ot[0]['product_group_id'], 'duration' => 0 ) );

		// Subtypes
	$sub_types = $this->work_order->generic_get( 'work_order_types',
		array( 'patent_id' =>$ot[0]['parent_type_name']['id'], 'duration !=' => 0 ) );

		// Products by Group
	$products_by_group = $this->work_order->getProducts( $ot[0]['product_group_id'] );

		// Plazo (period) (options are already in $products_array[]
	$periods = $this->work_order->getPeriod( $ot[0]['policy'][0]['products'][0]['id'], FALSE );

		// Get Currencies
	$currency_array = array();
	$currencies = $this->work_order->getCurrency();
	if ($currencies) {
		foreach ($currencies as $value)
			$currency_array[ $value['id'] ] = $value['name'];
	}

		// Get Payment modes
	$payment_conduct_array = array();
	$payment_conducts = $this->work_order->getPaymentMethodsConducto();
	if ($payment_conducts ) {
		foreach ($payment_conducts as $value)
			$payment_conduct_array[ $value['id'] ] = $value['name'];
	}

		// Get Payment intervals
	$payment_interval_array = array();
	$payment_intervals = $this->work_order->getPaymentIntervals();
	if ($payment_intervals) {
		foreach ($payment_intervals as $value)
			$payment_interval_array[ $value['id'] ] = $value['name'];
	}

	$add_js = '
	<script type="text/javascript">
	$("#prima").on("change keyup", function(event) {
		if ( event.target.validity.valid ) {
//      if ( ! event.target.validity.patternMismatch ) {
			$("#prima-error").hide();
		} else {
			$("#prima-error").show();
		}
	});
	';
	if ($function == 'editar')
		$add_js .= '
	$(":input[readonly=\'readonly\']").parents(".control-group").hide();
	$("#view-details").bind( "click", function(){
		$(":input[readonly=\'readonly\']").parents(".control-group").toggle();
		return false;
	});';

	$edit_script = '';
	if ($function == 'update') {
		$edit_script = '<script src="' . base_url() . 'ot/assets/scripts/edit.js?'.time().'"></script>';
		if ($is_nuevo_negocio)
			$add_js .= '
		$(".hide-update-nuevo").hide();
		';
		else
			$add_js .= '
		$(".hide-update-others").hide();
		';
	}
	$add_js .= '
	$("#ot-status").on("change", function(e){
		if($(this).val() == 8)
			$("#notas_adicionales").css("display","block");
		else
			$("#notas_adicionales").css("display","none");

	});
	</script>
	';
	$products_percentages = $this->policy_model->get_percentages_products($ot[0]['policy'][0]['products'][0]['id'],$ot[0]['policy'][0]['period']);
	$product_val = '
		<script type="text/javascript">
			var value_allocated = '.$products_percentages[0]['allocated_prime'].';
			var value_bonus 	= '.$products_percentages[0]['bonus_prime'].';
		</script>
	';

		// Config view
	$this->view = array(

		'title' => ucfirst($function) . ' OT',
			// Permisions
		'user' => $this->sessions,
		'user_vs_rol' => $this->user_vs_rol,
		'roles_vs_access' => $this->roles_vs_access,
		'css' => array(
		),
		'scripts' =>  array(
			'<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/jquery.validate.js"></script>',
			'<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/es_validator.js"></script>',
			$edit_script,
			'<script src="'.base_url().'scripts/config.js"></script>',
			$add_js,
			$product_val
		),
		  'content' => 'ot/update_view', // View to load
		  'message' => $this->session->flashdata('message'),
		  'agents' => $agents,
		  'tramite_types' => $tramite_types,
		  'sub_types' => $sub_types,
		  'products_by_group' => $products_by_group,
		  'periods' => $periods,
		  'currencies' => $currency_array,
		  'payment_conducts' => $payment_conduct_array,
		  'payment_intervals' => $payment_interval_array,
		  'data' => $ot[0],
		  'is_nuevo_negocio' => $is_nuevo_negocio,
		  'function' => $function
		);
		// Render view
	$this->load->view( 'index', $this->view );
}

private function _process_update_db_policy_prima($current_date, &$field_values)
{
	$prima_entered = $this->input->post( 'prima' );
	$currency_id = $this->input->post('currency_id');
	$allocated_prime = $this->input->post('allocatedPrime');
	$bonus_prime = $this->input->post('bonusPrime');
	if ($currency_id == 2)
	{
		// if entered in USD, compute prima by converting to MXN
		$this->load->model('exchange_rate_model');
		$prima = $this->exchange_rate_model->convert_prima(
			$prima_entered, 2, 1);
		if ($prima === FALSE)
			return FALSE;
	}
	else
	{
		$prima = $prima_entered;
	}
	$field_values = array_merge($field_values, array(
		'prima' => $prima,
		'prima_entered' => $prima_entered,
		'allocated_prime' => $allocated_prime,
		'bonus_prime' => $bonus_prime,
		'currency_id' => $currency_id,
		'payment_interval_id' => $this->input->post( 'payment_interval_id' ),
		'last_updated' => $current_date,
	));
	return TRUE;
}

private function _notify_ot_status_change($order_id, $emails = "")
{
	if ( ($updated = $this->work_order->generic_get( 'work_order', array('id' => $order_id), 1))
		!== FALSE)
	{
		$creator = $this->work_order->generic_get( 'users', array('id' => $updated[0]->user), 1);
		// Send Email
		$this->load->library( 'mailer' );
		$notification = $this->work_order->getNotification( $order_id );
		$from_reply_to = array();
		if ($creator)
		{
			$recipient = array(
				'agent_id' => 0,
				'percentage' => 100,
				'name' => $creator[0]->name,
				'lastnames' => $creator[0]->lastnames,
				'company_name' => $creator[0]->company_name,
				'email' =>  $creator[0]->email
			);
			$notification[0]['agents'][] = $recipient;
			$from_reply_to = array(
/*                  'from' => $creator[0]->email,
'reply-to' =>  $creator[0]->email);*/
'from' => $this->sessions['email'],
'reply-to' => $this->sessions['email'],
'bcc' => /*$this->sessions['email'].(!empty($this->sessions['email2']) ? ",".$this->sessions['email2'] : "").*/(!empty($emails) ? ",".$emails : "")
);

		}
		$this->mailer->notifications( $notification, null, null, $from_reply_to);
	}
}

// mark OT as paid
public function mark_paid()
{
	$this->_change_ot_status(4);
}

// mark OT as ntu
public function mark_ntu()
{
	$this->_change_ot_status(10);
}


// Handle ajax request to change OT status
private function _change_ot_status($new_status)
{
	if ( !$this->input->is_ajax_request() )
		redirect( 'ot.html', 'refresh' );

	if ( !$this->access_update ){
		echo json_encode('-1');
		exit;
	}
	$result = json_encode('0');
	$order_id = $this->input->post('order_id');
	$gmm = $this->input->post('gmm');
	$is_poliza = $this->input->post('is_poliza');
	$user_id = $this->input->post('user_id');
	$send_notification = $this->input->post('send_notification');
	if (($order_id !== FALSE) && ($gmm !== FALSE) && ($is_poliza !== FALSE) && ($user_id !== FALSE))
	{
		$order_id = (int)$order_id;
		$user_id = (int)$user_id;
		$this->load->model( 'work_order' );
		$work_order = array(
			'work_order_status_id' => $new_status
		);

		if ( $this->work_order->update( 'work_order', $order_id, $work_order ) &&
			( ($updated = $this->work_order->generic_get( 'work_order', array('id' => $order_id), 1))
				!== FALSE)
		)
		{
			$creator = $this->work_order->generic_get( 'users', array('id' => $updated[0]->user), 1);
			// Send Email
			if (($send_notification === FALSE) || ($send_notification == 1))
			{
				$this->load->library( 'mailer' );
				$notification = $this->work_order->getNotification( $order_id );
				$from_reply_to = array();
				if ($creator)
				{
					$recipient = array(
						'agent_id' => 0,
						'percentage' => 100,
						'name' => $creator[0]->name,
						'lastnames' => $creator[0]->lastnames,
						'company_name' => $creator[0]->company_name,
						'email' =>  $creator[0]->email
					);
					$notification[0]['agents'][] = $recipient;
					$from_reply_to = array(
/*                          'from' => $creator[0]->email,
'reply-to' =>  $creator[0]->email);*/
'from' => $this->sessions['email'],
'reply-to' => $this->sessions['email']);
				}
				$this->mailer->notifications( $notification, null, null, $from_reply_to);
			}
			$row_result = array(
				'is_poliza' => $is_poliza,
				'gmm' => $gmm,
				'access_update' => $this->access_update);
			$row_result['value'] = $this->work_order->pop_up_data($order_id, $user_id);
			$this->load->model( 'user' );
			$row_result['value']['general'][0]->adjusted_prima = $this->user->get_adjusted_prima(
				$row_result['value']['general'][0]->policy_id );
			$data['main'] = $this->load->view('popup_report_main_row', $row_result, TRUE);
			$data['menu'] = $this->load->view('popup_report_menu_row', $row_result, TRUE);
			$result = json_encode($data);
		}
	}
	echo $result;
	exit;
}
/*
Display custom filter period
*/
function show_custom_period()
{
	$this->load->helper('filter');
	return show_custom_period();
}
/*
	Update custom filter period in session data
*/
	function update_custom_period()
	{
		$result = 0;
		if ( $this->input->is_ajax_request() )
		{
			$filter_for = $this->input->post('filter_for');
			$valid_filter_for_s = array('ot_index', 'ot_reporte', 'activities_report', 'agent_profile',
				'operations');
			if (in_array($filter_for, $valid_filter_for_s))
			{
				$this->period_filter_for = $filter_for;
				$this->load->helper('filter');
				$result = update_custom_period(
					$this->input->post('cust_period_from'), $this->input->post('cust_period_to')
				);
			}
		}
		echo $result;
	}

// actions on payment (ignore, delete)
	public function payment_actions()
	{
		$this->load->helper('ot');
		payment_actions('ot');
	}

// Delete cobranza payments
	public function delete_cobranza()
	{
		$this->load->helper('ot/ot');
		delete_cobranza('ot');
	}

	// delete (imported) payments of given month/year
	public function delete_payments()
	{
		if ( !$this->input->is_ajax_request() )
			redirect( 'ot.html', 'refresh' );

		if ( !$this->access_delete ){
			echo json_encode('-1');
			exit;
		}
		$result = json_encode('-2');
		$month = $this->input->post('month_delete');
		$year = $this->input->post('year_delete');
		$ramo = $this->input->post('product_type_delete');
		if ($month && $year && $ramo)
		{
			$this->load->model( 'work_order' );
			$month = (int) $month;
			$year = (int) $year;
			$lastday = date('t',strtotime(sprintf("%04d-%02d-01", (int) $year, (int) $month)));
			$where = array(
				'payment_date >=' => sprintf("%04d-%02d-01", (int) $year, (int) $month),
				'payment_date <=' => sprintf("%04d-%02d-%02d", (int) $year, (int) $month, (int) $lastday),
				'product_group' => (int) $ramo
			);
			$db_result = $this->work_order->generic_get('payments', $where);
			if (!$db_result)
				$result = json_encode('0');
			else
			{
				$db_result = $this->work_order->generic_delete('payments', $where);
				if ( $db_result )
					$result = json_encode('1');
			}
		}
		echo $result;
		exit;
	}

	// Get suggestions for polizas
	public function search_polizas()
	{
		$result = array();
		if ( !$this->input->is_ajax_request() ||
			!$this->access_report ){
			echo json_encode($result);
		exit;
	}
	$searched = $this->input->get('term');
	if (($searched === FALSE) || (($searched = trim($searched))) === ''  )
	{
		echo json_encode($result);
		exit;
	}
	$this->load->model( 'work_order' );
	$from_db = $this->work_order->generic_search( 'payments',  'DISTINCT `policy_number`',
		array('policy_number', $searched, 'after'));
	$from_db_policies = $this->work_order->generic_search( 'policies',  'DISTINCT `uid` AS `policy_number`',
		array('uid', $searched, 'after'));
	if (is_array($from_db) && is_array($from_db_policies))
		$from_db = array_merge($from_db, $from_db_policies);
	elseif (!is_array($from_db))
		$from_db = $from_db_policies;

	if ($from_db)
	{
		foreach ($from_db as $value)
			$result[] = array('id' => $value->policy_number, 'label'=> $value->policy_number, 'value' => strip_tags($value->policy_number));
	}
	echo json_encode($result);
}

public function change_negocio_pai()
{
	if ( !$this->input->is_ajax_request() )
		redirect( 'ot.html', 'refresh' );

	if ( !$this->access_update )
	{
		echo json_encode('-1');
		exit;
	}
	$negocio_pai = $this->input->post('negocio_pai');
	if (is_array($negocio_pai))
	{
		$this->load->model( 'work_order' );
		foreach ($negocio_pai as $id => $value)
		{
			$result = $this->work_order->generic_update(
				'policy_negocio_pai', array('negocio_pai' => (int) $value), array('id' => (int) $id), 1, 0) ?
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
		redirect( 'ot.html', 'refresh' );

	if ( !$this->access_update )
	{
		echo json_encode('-1');
		exit;
	}
	$add_perc = $this->input->post('add_perc');

	if (is_array($add_perc))
	{
		$this->load->model( 'work_order' );
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

private function _check_import_file_length($records, $file_name = '')
{
	$max_file_length = $this->config->item('payment_import_max');
	if ($max_file_length === FALSE)
		$max_file_length = 10000;
	$message_file_too_long = 'El archivo es demasiado largo. Debe dividirlo en trozos de un máximo de ' .
	$max_file_length . ' líneas antes de importar cada trozo.';
	$message_file_empty = 'El archivo es vacío.';

	$count = count($records);
	if (!$count || ($count > $max_file_length))
	{
		if (!$count)
			$message = $message_file_empty;
		else
			$message = $message_file_too_long;
		$this->session->set_flashdata( 'message', array(
			'type' => false,
			'message' => $message
		));
		if ($file_name)
			@unlink(APPPATH . 'modules/ot/assets/tmp/' . $file_name);
		redirect( '/ot/import_payments', 'refresh' );
	}
}

private function _abort_import($error_code = NULL)
{
	$error_messages = array(
		0 => 'error desconocido. Informe a su administrador.',
		1 => 'los datos de importación temporal están vacías. Informe a su administrador.',
		2 => 'no se pudo decodificar los datos de importación. Divida el archivo de importación antes de importar de nuevo.'
	);
	if (!$error_code || !isset($error_messages[$error_code]))
		$error_code = 0;

	if (isset($_POST['tmp_file']) && $_POST['tmp_file'])
	{
		$tmp_file = $_POST['tmp_file'];
		$name = explode( '.', $tmp_file );
		if( $name[1] == 'xls' ){
			$this->load->library( 'reader_excel' );
			$this->reader_excel->setInstance( $tmp_file );
			$this->reader_excel->drop();
		}
		else
		{
			$this->load->library( 'reader_csv' );
			$this->reader_csv->setInstance( $tmp_file );
			$this->reader_csv->drop();
		}
	}
	$this->session->set_flashdata( 'message', array(
		'type' => false,
		'message' => $error_messages[$error_code]
	));
	redirect( '/ot/import_payments', 'refresh' );
}

public function markAsPaid(){
	$this->load->model('work_order');
	$wo = $this->input->post('wo');
	$fallados = array();
	foreach ($wo as $value) {
		if( !empty( $value ) )
		{
			$work_order = array( 'work_order_status_id' => 4 );
			if(!$this->work_order->update( 'work_order',$value, $work_order )){
				$fallados[] = $value;
			}
		}
	}
	if($fallados){
		$msj = 'Las siguientes ordenes fallaron: '. implode(",", $fallados);
	}else{
		$msj = 'Las ordenes se marcaron como pagadas.';
	}
	$this->session->set_flashdata( 'message', array(
		'type' => true,
		'message' => $msj
	));
	redirect( 'ot', 'refresh' );
}

public function getNewPrimas(){

	$product = $this->input->post( 'product' );
	$prima_entered = $this->input->post( 'prima' );
	$currency_id = $this->input->post('currency');
	$period = $this->input->post( 'period' );

	$prima = $this->convertCurrency($prima_entered, $currency_id);

	$primasCal = $this->calculateBonusUbicar($prima,$period, $product, $currency_id );
	
	echo json_encode($primasCal);
}

public function convertCurrency($prima_entered, $currency_id){
	$this->load->model('exchange_rate_model');
	return ($currency_id == 2) ? 
		$this->exchange_rate_model->convert_prima($prima_entered, $currency_id,1) :
		$prima_entered;
}

public function calculateBonusUbicar($prima, $period, $product, $currency){
	$this->load->model('user');
	return $this->user->getPercentagePrimas($prima, $period, $product,$currency);
}

public function deleteSeloPayments($months_to_delete, $product){
	if ($product == 8){
		$product = 1;
	}
	else if ($product == 9){ 
		$product = 2;
	}

	if (!empty($months_to_delete)){
		foreach ($months_to_delete as $date){
			$deletin = explode("-", $date);
			$this->db->where('YEAR(payment_date) = '. $deletin[0] . ' AND MONTH(payment_date) ='. $deletin[1] . ' AND product_group = ' . $product);
			$this->db->delete('payments');
		}
	}
}


public function recalculate_adjusted($ot){
	$this->load->model('policy_model');
	$this->load->model( 'work_order' );
	$id = $this->uri->segment(3);
	echo "<pre>";
			//var_export($this->policy_model->recalculate_adjusted_primas($this->uri->segment(3),$this->uri->segment(4),$this->uri->segment(5)));
	$ot1 = $this->work_order->getWorkOrderById(  $id);
	print_r($ot1);
			/*$this->uri->segment(4);
					$this->uri->segment(5)
					$this->policy_model->getPolicyBuId();*/
				}
	
	public function calculatePrimas(){
		$this->load->model('work_order');
		$primes = array();
		$percentages = array();

		$primes = $this->work_order->getTotalNewBusiness();
		if ($primes > 0) {
			foreach($primes as $prime){
				// print_r($prime);
				$prima = $this->convertCurrency($prime->prima_entered, $prime->currency_id);
				$percentages = $this->calculateBonusUbicar(
					$prima, $prime->period, $prime->product_id, $prime->currency_id);
				// print_r($percentages);
				$this->work_order->updatePrimes($prime->policy_id,$percentages->allocatedPrime, $percentages->bonusPrime);
			}
		}

		redirect('director', 'refresh');
		
	}

  public function rebuildpai(){
      $total = $this->user->rebuildNegociosPai();
      if ($total>0){
        redirect('director', 'refresh');
      }    
  }   

  public function rbAgentGeneration(){
	  $total = $this->user->rebuildAgentGenerations();
	  redirect('director', 'refresh');
}  

public function rbAgentGenerationTable(){
	$total1 = $this->user->rebuildAgentGenerationsTableVida();
	$total2 = $this->user->rebuildAgentGenerationsTableGMM();
	redirect('director', 'refresh');
}  

				/* End of file ot.php */
				/* Location: ./application/controllers/ot.php */
			}
			?>
