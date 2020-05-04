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
class Settings extends CI_Controller {

	public $view = array();

	public $sessions = array();

	public $user_vs_rol = array();

	public $roles_vs_access = array();

	public $access = FALSE;

	public $access_view = FALSE;
	public $access_update = FALSE;

/** Construct Function **/
/** Setting Load perms **/
	
	public function __construct(){
			
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
				if ($value['module_name'] == 'Settings')
				{
					$this->access = true;
					switch ($value['action_name'])
					{
						case 'Editar':
							$this->access_update = TRUE;
						break;
						case 'Ver':
							$this->access_view = TRUE;
						break;
						default:
						break;
					}
				}
			}
		}
		if ( !$this->access )
		{	
			$this->session->set_flashdata( 'message', array
			( 
				'type' => false,	
				'message' => 'No tiene permisos para entrar en la sección "Configuración". Informe a su administrador para que le otorge los permisos necesarios.'
			));	
			redirect( 'home', 'refresh' );
		}
	}

	public function index()
	{
		$this->_update_view('view');
	}

	public function view()
	{
		if ( !$this->access_view )
		{	
			$this->session->set_flashdata( 'message', array
			( 
				'type' => false,	
				'message' => 'No tiene permisos para ver la configuración. Informe a su administrador para que le otorge los permisos necesarios.'
			));	
			redirect( 'home', 'refresh' );
		}
		$this->_update_view('view');
	}

	public function update()
	{
		if ( !$this->access_update )
		{	
			$this->session->set_flashdata( 'message', array
			( 
				'type' => false,	
				'message' => 'No tiene permisos para editar la configuración. Informe a su administrador para que le otorge los permisos necesarios.'
			));	
			redirect( 'home', 'refresh' );
		}
		$this->_update_view('update');
	}

	private function _update_view($function)
	{
		$other_errors = array();
		$this->load->model( 'settings_model' );
		$this->settings_model->set_table('settings');
		$settings = $this->settings_model->get(null, 'rank');
		if ($settings)
		{
			$upload_config = array();
			foreach ($settings as $field_name => $field_value)
			{
				if ($field_value->form_type == 'image')
				{
					$upload_config[$field_name] = array(
						'upload_path' => FCPATH . 'images',
						'encrypt_name' => TRUE
					);
					list($upload_config[$field_name]['allowed_types'], $upload_config[$field_name]['max_size'],
						$upload_config[$field_name]['max_width'], $upload_config[$field_name]['max_height']) = 
						explode(',', $field_value->form_validation);
					$this->load->library('upload');
					$settings[$field_name]->validation_arr = $upload_config;
				}
				else
				{
					$settings[$field_name]->validation_arr = explode('|', $field_value->form_validation);
					$this->form_validation->set_rules($field_name, $field_value->name, $field_value->form_validation);
				}
			}
			if( ($function == 'update') && (!empty( $_POST ) || !empty( $_FILES ) ))
			{
				if ( $this->form_validation->run() )
				{
					$values = array();
					$to_delete = array();
					foreach ($settings as $field_name => $field_value)
					{
						if ($field_value->form_type == 'image')
						{
							if ($_FILES[$field_name]['name'])
							{
								$this->upload->initialize($upload_config[$field_name]);
								if ($this->upload->do_upload($field_name))
								{
									$image_data = $this->upload->data();
									$values[] = array(
										'key' => $field_name,
										'value' => $image_data['file_name']
										);
									$to_delete[] = $field_value->value;
								}
								else
									$other_errors[] = $this->upload->display_errors();
							}
						}
						else
							$values[] = array(
								'key' => $field_name,
								'value' => $this->input->post($field_name)
								);
					}
					$days_yellow = $this->input->post('days_yellow');
					$days_red = $this->input->post('days_red');
					if ($days_yellow > $days_red)
						$other_errors[] = 
						'<p>El Número de días para marcar un OT amarillo debe estar inferior a el Número de días para marcar un OT rojo.</p>';
					if (!$other_errors)
					{
						if ($this->settings_model->update_batch_manual($values))
						{
							foreach ($to_delete as $file_name)
							{
								@unlink(FCPATH . 'images/' . $file_name);
							}
							$this->session->set_flashdata( 'message', array(
								'type' => true,	
								'message' => 'Se ha guardado la configuración correctamente.'
								));	
						}
						else
							$this->session->set_flashdata( 'message', array(
								'type' => false,	
								'message' => 'Ocurrio un error. La configuración no puede ser guardada. Consulte a su administrador.'
								));							
						redirect( 'settings/view', 'refresh' );
					}
				}
			}
		}
		$base_url = base_url();

		$this->view = array(
			'title' => 'Configuración del sitio web',
			'user' => $this->sessions,
			'user_vs_rol' => $this->user_vs_rol,
			'roles_vs_access' => $this->roles_vs_access,
			'content' => 'settings',
			'message' => $this->session->flashdata('message'),
			'other_errors' => $other_errors,
			'data' => $settings,
		);
		$this->load->view( 'index', $this->view );	
	}

	public function xrate_list()
	{
		$selected_date = $this->input->post('selected_date');
		if (!$selected_date)
		{
			$selected_date = date('Y-m-d');
		}
		$this->load->model( 'exchange_rate_model' );
		$where = null;
		if ($selected_date)
		{
			$parts = explode('-', $selected_date);
			if (!empty($parts[0]) && !empty($parts[1]) &&
				!empty($parts[2]) && 
				checkdate($parts[1], $parts[2], $parts[0]))
			{
				$selected_date = sprintf("%04d-%02d-%02d",
					$parts[0], $parts[1], $parts[2]);
				$where = "date <= '$selected_date'";
			}
		}

		$rates = $this->exchange_rate_model->get($where);

		$inline_js = "
<script type=\"text/javascript\">
	var selectedDate = '';
	$( document ).ready( function(){ 
		selectedDate = $('#selectedDate').val();

		$( '#fechapicker' ).datepicker({
			defaultDate: selectedDate,
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			showOtherMonths: true,
			selectOtherMonths: true,
			firstDay:1,
			closeText: 'Cerrar',
			prevText: '&#x3c;Ant',
			nextText: 'Sig&#x3e;',
			currentText: 'Hoy',
			monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
				'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
			monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
				'Jul','Ago','Sep','Oct','Nov','Dic'],
			dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles',
				'Jueves','Viernes','S&aacute;bado'],
			dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
			dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
			maxDate: 0,
			onSelect: function(dateText, inst) {
				var date = $(this).datepicker('getDate');
				var theMonth = date.getMonth() + 1;
				if( theMonth < 10 ) {
					theMonth = '0' + theMonth;
				}
				var theDay = date.getDate();
				if ( theDay < 10 ) {
					theDay = '0' + theDay;
				}
				$( '#selectedDate' ).val(  date.getFullYear() + '-' + theMonth + '-' + theDay );
				$( '#rate-form' ).hide();
				$( '#rate-form' ).submit();
			}
		});

	});
</script>";

		$this->view = array(
			'title' => 'Tipos de cambio MXN/USD',
			'user' => $this->sessions,
			'user_vs_rol' => $this->user_vs_rol,
			'roles_vs_access' => $this->roles_vs_access,
			'content' => 'xrate_list',
			'message' => $this->session->flashdata('message'),
			'data' => $rates,
			'selected_date' => $selected_date,		
			'scripts' => array(
				$inline_js
			),
		);
		$this->load->view( 'index', $this->view );

	}


}

/* End of file settings.php */
/* Location: ./application/modules/settings/controllers/settings.php */