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
class Roles extends CI_Controller {
	
	public $data = array();
	
	public $view = array();
	
	public $sessions = array();
	
	public $user_vs_rol = array();
	
	public $roles_vs_access = array();
	
	public $access = false; // Force security
	
	public $access_create = false;
	
	public $access_update = false;
	
	public $access_delete = false;
	
	
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
		foreach( $this->roles_vs_access  as $value ): if( in_array( 'Rol', $value ) ):
			
			$this->access = true;
			
		break; endif; endforeach;						
		
		
		
		// Check if is empty rol 
		if( empty( $this->roles_vs_access ) or $this->access == false ){ 
			
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Rol", Informe a su administrador para que le otorge los permisos necesarios.'
							
			));	
			
			
			redirect( 'home', 'refresh' );
		}
			
		
		
		// Added Acctions for user, change the bool access
		foreach( $this->roles_vs_access  as $value ): if( in_array( 'Rol', $value ) ):
			
			
			if( $value['action_name'] == 'Crear' )
				$this->access_create = true;
			
			
			if( $value['action_name'] == 'Editar' )
				$this->access_update = true;
				
			if( $value['action_name'] == 'Eliminar' )
				$this->access_delete = true;	
			
					
		endif; endforeach;
							
								
		if( empty( $this->sessions ) and $this->uri->segment(2) != 'login'  ) redirect( 'usuarios/login', 'refresh' );
				
	}


// Show all records	
	public function index(){
		
		
		
		// Load Model
		$this->load->model( 'rol' );
		
		
		
		// Pagination config	
		$this->load->library('pagination');
		
		$begin = $this->uri->segment(3);
		
		if( empty( $begin ) ) $begin = 0;
		
					
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
		$config['base_url'] = base_url().'roles/index/';
		$config['total_rows'] = $this->rol->record_count();
		$config['per_page'] = 20;
		$config['num_links'] = 5;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		
		$this->pagination->initialize($config); 
				
						
		// Config view
		$this->view = array(
				
		  'title' => 'Role',
		  // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'access_create' => $this->access_create,
		  'access_update' => $this->access_update,
		  'access_delete' => $this->access_delete,

		  'content' => 'roles/list', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'data' => $this->rol->all( $begin )		  
		  		
		);
				
		// Render view 
		$this->load->view( 'index', $this->view );	
	}
	

// Create new role
	public function create(){
		
		// Check access teh user for create
		if( $this->access_create == false ){
				
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Rol Crear", Informe a su administrador para que le otorge los permisos necesarios.'
							
			));	
			
			
			redirect( 'roles', 'refresh' );
		
		}
			
			
		
		if( !empty( $_POST ) ){
			
			
			// Load model
			$this->load->model( 'rol' );
			
			
			// Validations
			$this->form_validation->set_rules('name', 'Nombre de Rol', 'required');
			
			
			// Run Validation
			if ( $this->form_validation->run() == TRUE ){
					
					
				// Save Record	
				if( $this->rol->create( $this->input->post() ) == true ){
					
					
					// Set true message		
					$this->session->set_flashdata( 'message', array( 
						
						'type' => true,	
						'message' => 'Se guardo el registro correctamente'
									
					));												
					
					
					redirect( 'roles', 'refresh' );
					
					
					
				}else{
					
					
					// Set false message		
					$this->session->set_flashdata( 'message', array( 
						
						'type' => false,	
						'message' => 'No se pudo guardar el registro, ocurrio un error en la base de datos. Pongase en contacto con el desarrollador'
									
					));												
					
					
					redirect( 'roles', 'refresh' );
						
				}						
					
			}	
			
						
		}
		
		
		
		// Config view
		$this->view = array(
				
		  'title' => 'Crear role',
		  // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'scripts' =>  array(
			  '<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/jquery.validate.js"></script>',
			  '<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/es_validator.js"></script>',
			  '<script src="'.base_url().'roles/assets/scripts/roles.js"></script>',		
			  '<script src="'.base_url().'scripts/config.js"></script>'				
		  ),
		  'content' => 'roles/create', // View to load
		  'message' => $this->session->flashdata('message') // Return Message, true and false if have
				
		);
		
		
		// Render view 
		$this->load->view( 'index', $this->view );	
	
	}
	
	
// Update role	
	public function update( $id = null ){
		
		
		// Check access teh user for create
		if( $this->access_update == false ){
				
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Rol editar", Informe a su administrador para que le otorge los permisos necesarios.'
							
			));	
			
			
			redirect( 'roles', 'refresh' );
		
		}
		
		
		
		
		// Validation of id number
		if( empty( $id ) or !is_numeric( $id ) ){
			
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'No es valido. El registro no se puede encontrar.'
							
			));												
			
			
			redirect( 'roles', 'refresh' );
			
		}
		
		
		
		// Load model
		$this->load->model( 'rol' );
		
		
		$data = $this->rol->id( $id );
		
		// Validation rol
		if( empty( $data ) ){
			
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'El registro no existe'
							
			));												
			
			
			redirect( 'roles', 'refresh' );
			
		}

		$home_pages = $this->rol->get_home_pages();
		$data['all_home_pages'] = array();
		foreach ($home_pages as $home_page) {

			$data['all_home_pages'][$home_page['page_id']] = $home_page['page_name'];
		}
		if ( !isset( $home_pages[$data['x_home_page']] ) )
			$data['x_home_page'] = 1;
        $this->config->set_item('x_home_page', $data['all_home_pages']);

		if( !empty( $_POST ) ){
			
			
			// Load model
			$this->load->model( 'rol' );
			
			
			// Validations
			$this->form_validation->set_rules('name', 'Nombre de Rol', 'required');
			$this->form_validation->set_rules('x_home_page', 'Home Page', 'required|is_natural_no_zero|src_is_array_index[x_home_page]');

			// Run Validation
			if ( $this->form_validation->run() == TRUE ){
					
					
				// Save Record	
				if( $this->rol->update( $id, $this->input->post() ) == true ){
					
					
					// Set true message		
					$this->session->set_flashdata( 'message', array( 
						
						'type' => true,	
						'message' => 'Se guardo el registro correctamente'
									
					));												
					
					
					redirect( 'roles', 'refresh' );
					
					
					
				}else{
					
					
					// Set false message		
					$this->session->set_flashdata( 'message', array( 
						
						'type' => false,	
						'message' => 'No se pudo guardar el registro, ocurrio un error en la base de datos. Pongase en contacto con el desarrollador'
									
					));												
					
					
					redirect( 'roles', 'refresh' );
						
				}						
					
			} elseif (! form_error('x_home_page') )
				$data['x_home_page'] = $this->input->post('x_home_page');
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		// Config view
		$this->view = array(
				
		  'title' => 'Editar role -' .$data['name'],
		  // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'scripts' =>  array(
			  '<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/jquery.validate.js"></script>',
			  '<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/es_validator.js"></script>',
			  '<script src="'.base_url().'roles/assets/scripts/roles.js"></script>',		
			  '<script src="'.base_url().'scripts/config.js"></script>'				
		  ),
		  'content' => 'roles/update', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'data' => $data		
		);
		
		
		// Render view 
		$this->load->view( 'index', $this->view );	
		
		
	}





// Edit the access role	
	public function access( $id = null ){
		
		
		// Check access teh user for create
		if( $this->access_update == false ){
				
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Rol access", Informe a su administrador para que le otorge los permisos necesarios.'
							
			));	
			
			
			redirect( 'roles', 'refresh' );
		
		}
		
		
		
		
		// Validation of id number
		if( empty( $id ) or !is_numeric( $id ) ){
			
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'No es valido. El registro no se puede encontrar.'
							
			));												
			
			
			redirect( 'roles', 'refresh' );
			
		}
		
		
		
		// Load model
		$this->load->model( 'rol' );
		
		
		$data = $this->rol->id( $id );
		
		// Validation rol
		if( empty( $data ) ){
			
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'El registro no existe'
							
			));												
			
			
			redirect( 'roles', 'refresh' );
			
		}
		
		
		
		
		$rol_access = $this->rol->rol_access_checkbox( $id );
		
		
		
		
		
		
		
		if( !empty( $_POST['accesss'] ) and $_POST['accesss'] == true ){
			
			$items = array();
			
			unset( $_POST['accesss'] );
			
			// Set timestamp unix
			$timestamp = strtotime( date( 'd-m-Y H:i:s' ) );
			
			// Set timestamp unix
			$values['last_updated'] = $timestamp;
			$values['date'] = $timestamp;	
			
			
			
			
			// Load model
			$this->load->model( 'rol' );
			
			// Delete and clean Rol access
			$this->rol->delete_rol_vs_access( $id );
			
			
			
			if( empty( $_POST['access'] ) ){
			
				
				// Set true message		
				$this->session->set_flashdata( 'message', array( 
					
					'type' => true,	
					'message' => 'Se edito el acceso del rol. '.$data['name']
								
				));												
				
				
				redirect( 'roles', 'refresh' );
			}
			
			
			
			
			
			
			
			
			
			
			
			
			
						
			foreach( $this->input->post( 'access' ) as $value ){
								
				$explode = explode( '-', $value );
				if( !empty( $explode ) )
				array_push( $items, array( 'user_role_id' => $id, 'module_id' => $explode[0], 'action_id' =>$explode[1],  'last_updated' => $timestamp, 'date' => $timestamp ) );
			
			}
			
					
			
			
					
					
			// Save Record	
			if( $this->rol->create_banch( 'user_roles_vs_access', $items ) == true ){
				
				
				// Set true message		
				$this->session->set_flashdata( 'message', array( 
					
					'type' => true,	
					'message' => 'Se edito el acceso del rol. '.$data['name']
								
				));												
				
				
				redirect( 'roles', 'refresh' );
				
				
				
			}else{
				
				
				// Set false message		
				$this->session->set_flashdata( 'message', array( 
					
					'type' => false,	
					'message' => 'No se pudo guardar el registro, ocurrio un error en la base de datos. Pongase en contacto con el desarrollador'
								
				));												
				
				
				redirect( 'roles', 'refresh' );
					
							
			}
			
						
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		// Config view
		$this->view = array(
				
		  'title' => 'Editar acceso rol -' . $data['name'],
		  // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'scripts' =>  array(
			  '<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/jquery.validate.js"></script>',
			  '<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/es_validator.js"></script>',
			  '<script src="'.base_url().'roles/assets/scripts/access.js"></script>',		
			  '<script src="'.base_url().'scripts/config.js"></script>'				
		  ),
		  'content' => 'roles/access', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'rol_access' => $rol_access,
		  'data' => $data		
		);
		
		
		// Render view 
		$this->load->view( 'index', $this->view );	
		
		
	}








// Delete role	
	public function delete( $id = null ){
		
		// Check access teh user for create
		if( $this->access_delete == false ){
				
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Rol eliminar", Informe a su administrador para que le otorge los permisos necesarios.'
							
			));	
			
			
			redirect( 'roles', 'refresh' );
		
		}
		
		
		// Validation of id number
		if( empty( $id ) or !is_numeric( $id ) ){
			
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'No es valido. El registro no se puede encontrar.'
							
			));												
			
			
			redirect( 'roles', 'refresh' );
			
		}
		
		
		
		// Load model
		$this->load->model( 'rol' );
		
		
		$data = $this->rol->id( $id );
		
		// Validation rol
		if( empty( $data ) ){
			
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'El registro no existe'
							
			));												
			
			
			redirect( 'roles', 'refresh' );
			
		}
		
		
		
		
		
		
		
		
		
		
		
		if( !empty( $_POST ) ){
			
			
			// Load model
			$this->load->model( 'rol' );
			
			// Save Record	
			if( $this->rol->delete( $id ) == true ){
				
				
				// Set true message		
				$this->session->set_flashdata( 'message', array( 
					
					'type' => true,	
					'message' => 'Se elimino el registro correctamente'
								
				));												
				
				
				redirect( 'roles', 'refresh' );
				
				
				
			}else{
				
				
				// Set false message		
				$this->session->set_flashdata( 'message', array( 
					
					'type' => false,	
					'message' => 'No se pudo eliminar el registro, ocurrio un error en la base de datos. Pongase en contacto con el desarrollador'
								
				));												
				
				
				redirect( 'roles', 'refresh' );
					
			}						
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		// Config view
		$this->view = array(
				
		  'title' => 'Eliminar role -' .$data['name'],
		  // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'scripts' =>  array(
			  '<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/jquery.validate.js"></script>',
			  '<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/es_validator.js"></script>',
			  '<script src="'.base_url().'roles/assets/scripts/roles.js"></script>',		
			  '<script src="'.base_url().'scripts/config.js"></script>'				
		  ),
		  'content' => 'roles/delete', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'data' => $data		
		);
		
		
		// Render view 
		$this->load->view( 'index', $this->view );	
	}

/* End of file roles.php */
/* Location: ./application/controllers/roles.php */
}
?>