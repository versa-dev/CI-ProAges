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
class Modulos extends CI_Controller {

			
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
		foreach( $this->roles_vs_access  as $value ): if( in_array( 'Modulos', $value ) ):
			
			$this->access = true;
			
		break; endif; endforeach;						
		
		
		
		// Check if is empty rol 
		if( empty( $this->roles_vs_access ) or $this->access == false ){ 
			
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Modulos", Informe a su administrador para que le otorge los permisos necesarios.'
							
			));	
			
			
			redirect( 'home', 'refresh' );
		}
			
		
		
		// Added Acctions for user, change the bool access
		foreach( $this->roles_vs_access  as $value ): if( in_array( 'Modulos', $value ) ):
			
			
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
		$this->load->model( 'modulo' );
		
		
		
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
		$config['base_url'] = base_url().'modulos/index/';
		$config['total_rows'] = $this->modulo->record_count();
		$config['per_page'] = 30;
		$config['num_links'] = 5;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;
		
		$this->pagination->initialize($config); 
				
						
		// Config view
		$this->view = array(
				
		  'title' => 'Modulos',
		  // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'access_create' => $this->access_create,
		  'access_update' => $this->access_update,
		  'access_delete' => $this->access_delete,


		  'content' => 'modulos/list', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'data' => $this->modulo->all( $begin )
		  
		  		
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
				'message' => 'No tiene permisos para ingresar en esta sección "Modulos crear", Informe a su administrador para que le otorge los permisos necesarios.'
							
			));	
			
			
			redirect( 'modulos', 'refresh' );
		
		}
			
		
		if( !empty( $_POST ) ){
			
			
			// Load model
			$this->load->model( 'modulo' );
			
			
			// Validations
			$this->form_validation->set_rules('name', 'Nombre del Modulo', 'required');
			
			
			// Run Validation
			if ( $this->form_validation->run() == TRUE ){
					
					
				// Save Record	
				if( $this->modulo->create( $this->input->post() ) == true ){
					
					
					// Set true message		
					$this->session->set_flashdata( 'message', array( 
						
						'type' => true,	
						'message' => 'Se guardo el registro correctamente'
									
					));												
					
					
					redirect( 'modulos', 'refresh' );
					
					
					
				}else{
					
					
					// Set false message		
					$this->session->set_flashdata( 'message', array( 
						
						'type' => false,	
						'message' => 'No se pudo guardar el registro, ocurrio un error en la base de datos. Pongase en contacto con el desarrollador'
									
					));												
					
					
					redirect( 'modulos', 'refresh' );
						
				}						
					
			}	
			
						
		}
		
		
		
		// Config view
		$this->view = array(
				
		  'title' => 'Crear Módulo',
		  // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'scripts' =>  array(
			  '<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/jquery.validate.js"></script>',
			  '<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/es_validator.js"></script>',
			  '<script src="'.base_url().'modulos/assets/scripts/modulos.js"></script>',		
			  '<script src="'.base_url().'scripts/config.js"></script>'				
		  ),
		  'content' => 'modulos/create', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'user' => $this->sessions
		  		
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
				'message' => 'No tiene permisos para ingresar en esta sección "Modulos editar", Informe a su administrador para que le otorge los permisos necesarios.'
							
			));	
			
			
			redirect( 'modulos', 'refresh' );
		
		}
		
		
		
		
		
		// Validation of id number
		if( empty( $id ) or !is_numeric( $id ) ){
			
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'No es valido. El registro no se puede encontrar.'
							
			));												
			
			
			redirect( 'modulos', 'refresh' );
			
		}
		
		
		
		// Load model
		$this->load->model( 'modulo' );
		
		
		$data = $this->modulo->id( $id );
		
		// Validation rol
		if( empty( $data ) ){
			
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'El registro no existe'
							
			));												
			
			
			redirect( 'modulos', 'refresh' );
			
		}
		
		
		
		
		
		
		
		
		
		
		
		if( !empty( $_POST ) ){
						
			// Validations
			$this->form_validation->set_rules('name', 'Nombre del Módulo', 'required');
			
			
			// Run Validation
			if ( $this->form_validation->run() == TRUE ){
					
					
				// Save Record	
				if( $this->modulo->update( $id, $this->input->post() ) == true ){
					
					
					// Set true message		
					$this->session->set_flashdata( 'message', array( 
						
						'type' => true,	
						'message' => 'Se guardo el registro correctamente'
									
					));												
					
					
					redirect( 'modulos', 'refresh' );
					
					
					
				}else{
					
					
					// Set false message		
					$this->session->set_flashdata( 'message', array( 
						
						'type' => false,	
						'message' => 'No se pudo guardar el registro, ocurrio un error en la base de datos. Pongase en contacto con el desarrollador'
									
					));												
					
					
					redirect( 'modulos', 'refresh' );
						
				}						
					
			}	
			
						
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		// Config view
		$this->view = array(
				
		  'title' => 'Editar módulo -' .$data['name'],
		  // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'scripts' =>  array(
			  '<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/jquery.validate.js"></script>',
			  '<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/es_validator.js"></script>',
			  '<script src="'.base_url().'modulos/assets/scripts/modulos.js"></script>',		
			  '<script src="'.base_url().'scripts/config.js"></script>'				
		  ),
		  'content' => 'modulos/update', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'data' => $data,
		  'user' => $this->sessions		
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
				'message' => 'No tiene permisos para ingresar en esta sección "Modulos eliminar", Informe a su administrador para que le otorge los permisos necesarios.'
							
			));	
			
			
			redirect( 'modulos', 'refresh' );
		
		}
		
		
		// Validation of id number
		if( empty( $id ) or !is_numeric( $id ) ){
			
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'No es valido. El registro no se puede encontrar.'
							
			));												
			
			
			redirect( 'modulos', 'refresh' );
			
		}
		
		
		
		// Load model
		$this->load->model( 'modulo' );
		
		
		$data = $this->modulo->id( $id );
		
		// Validation rol
		if( empty( $data ) ){
			
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'El registro no existe'
							
			));												
			
			
			redirect( 'modulos', 'refresh' );
			
		}
		
		
		
		
		
		
		
		
		
		
		
		if( !empty( $_POST ) ){
						
			// Save Record	
			if( $this->modulo->delete( $id ) == true ){
				
				
				// Set true message		
				$this->session->set_flashdata( 'message', array( 
					
					'type' => true,	
					'message' => 'Se elimino el registro correctamente'
								
				));												
				
				
				redirect( 'modulos', 'refresh' );
				
				
				
			}else{
				
				
				// Set false message		
				$this->session->set_flashdata( 'message', array( 
					
					'type' => false,	
					'message' => 'No se pudo eliminar el registro, ocurrio un error en la base de datos. Pongase en contacto con el desarrollador'
								
				));												
				
				
				redirect( 'modulos', 'refresh' );
					
			}						
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		// Config view
		$this->view = array(
				
		  'title' => 'Eliminar Módulo -' .$data['name'],
		  // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'scripts' =>  array(
			  '<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/jquery.validate.js"></script>',
			  '<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/es_validator.js"></script>',
			  '<script src="'.base_url().'modulos/assets/scripts/modulos.js"></script>',		
			  '<script src="'.base_url().'scripts/config.js"></script>'				
		  ),
		  'content' => 'modulos/delete', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'data' => $data	,
		  'user' => $this->sessions	
		);
		
		
		// Render view 
		$this->load->view( 'index', $this->view );	
	}

/* End of file modulos.php */
/* Location: ./application/controllers/modulos.php */
}
?>