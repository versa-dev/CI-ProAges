<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class groups extends CI_Controller {
	public $module = "Grupos";

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
		foreach( $this->roles_vs_access  as $value ): 
			if(in_array( $this->module, $value ) ):
				$this->access = true;
				break; 
			endif; 
		endforeach;						
		
		// Check if is empty rol 
		if( empty( $this->roles_vs_access ) or $this->access == false ){ 
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "'.$this->module.'", Informe a su administrador para que le otorge los permisos necesarios.'
							
			));			
			redirect( 'home', 'refresh' );
		}
			
		// Added Acctions for user, change the bool access
		foreach( $this->roles_vs_access  as $value ): 
			if( in_array( $this->module, $value ) ):
				if( $value['action_name'] == 'Crear' )
					$this->access_create = true;
				if( $value['action_name'] == 'Editar' )
					$this->access_update = true;
				if( $value['action_name'] == 'Eliminar' )
					$this->access_delete = true;				
			endif; 
		endforeach;

		if( empty( $this->sessions ) and $this->uri->segment(2) != 'login'  ) redirect( 'usuarios/login', 'refresh' );
				
	}

	public function index(){
		$this->listado();
	}

	public function listado($start = 0, $filter = ""){
		$this->load->model('group');

		//Filter Parameters
		$userid = $this->sessions["id"];
		$limit = 10;

		// Pagination config	
		$this->load->library('pagination');
		
					
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
		$config['base_url'] = base_url('groups/listado/');
		$config['total_rows'] = $this->group->record_count($userid, $filter);
		$config['per_page'] = $limit;
		$config['num_links'] = 5;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = FALSE;
		
		$this->pagination->initialize($config); 
		
		$data = $this->group->all($limit, $start, $filter);
		foreach ($data as $i => $value) {
			switch ($value["filter_type"]) {
				case 1:
					$data[$i]["ramo"] = "Vida";
					break;
				case 2:
					$data[$i]["ramo"] = "GMM";
					break;
				case 3:
					$data[$i]["ramo"] = "Ambos";
					break;
				default:
					$data[$i]["ramo"] = "";
					break;
			}
		}
						
		// Config view
		$this->view = array(
				
		  'title' => 'Grupos',
		  // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'access_create' => $this->access_create,
		  'access_update' => $this->access_update,
		  'access_delete' => $this->access_delete,


		  'content' => 'groups/list', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'data' => $data,
		);
				
		// Render view 
		$this->load->view( 'index', $this->view );
	}

	public function create(){
		// Check access the user for create
		if( $this->access_create == false ){
			// Set false message		
			$this->session->set_flashdata( 'message', array( 	
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Grupo crear", Informe a su administrador para que le otorge los permisos necesarios.'
							
			));	
			redirect( 'groups', 'refresh' );
		}
		if($this->input->post()){
			// Load model
			$this->load->model( 'group' );

			// Validations - THis is where we create the groups
			$this->form_validation->set_rules('name', 'Nombre del Grupo', 'trim|required');
			$this->form_validation->set_rules('ramo', 'Ramo ', 'trim|required|is_natural_no_zero|less_than[4]');
			// Run Validation
			if ( $this->form_validation->run() == TRUE ){
				$miembros = $this->input->post("miembros");
				$filtered_miembros = array();
				foreach ($miembros as $miembro) {
					if(is_numeric($miembro) and $miembro > 0 and !in_array($miembro, $filtered_miembros))
						$filtered_miembros[] = $miembro;
				}
				// Save Record	

				$newGroup = array(
					"description" => $this->input->post("name"),
					"filter_type" => $this->input->post("ramo"),
					"group_owner" => $this->sessions['id']
				);
				$group_id = $this->group->create($newGroup);
				if( $group_id){
					if(count($filtered_miembros) > 0){
						foreach ($filtered_miembros as $miembro) {
							$newMiembro = array(
								"agent_id" => $miembro,
								"user_group_id" =>$group_id
							);
							$this->group->add_member($newMiembro);
						}
					}
					// Set true message		
					$this->session->set_flashdata( 'message', array( 
						'type' => true,	
						'message' => 'Se guardo el registro correctamente'
					));												
					redirect( 'groups', 'refresh' );	
				}else{
					// Set false message		
					$this->session->set_flashdata( 'message', array( 
						'type' => false,	
						'message' => 'No se pudo guardar el registro, ocurrio un error en la base de datos. Pongase en contacto con el desarrollador'
									
					));												
					redirect( 'groups', 'refresh' );
				}							
			}					
		}

		// Config view
		$this->view = array(
				
		  'title' => 'Crear Grupo',
		  // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'scripts' =>  array(
			  '<script type="text/javascript" src="'.base_url("plugins/jquery-validation/jquery.validate.js").'"></script>',
			  '<script type="text/javascript" src="'.base_url("plugins/jquery-validation/es_validator.js").'"></script>',
			  '<script src="'.base_url("groups/assets/scripts/groups.js").'"></script>',		
			  '<script src="'.base_url("scripts/config.js").'"></script>'				
		  ),
		  'content' => 'groups/create', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'ramos' => array(
		  	"" => "Seleccione un ramo",
		  	1 => "Vida",
		  	2 => "GMM",
		  	3 => "Ambos"
		  ),
		  'user' => $this->sessions
		  		
		);
		// Render view 
		$this->load->view( 'index', $this->view );	
	
	}

	// Update group
	public function update( $id = null ){
		// Check access teh user for update
		if( $this->access_update == false ){
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Grupos editar", Informe a su administrador para que le otorge los permisos necesarios.'
							
			));	
			redirect( 'groups', 'refresh' );
		}

		// Validation of id number
		if( empty( $id ) or !is_numeric( $id ) ){
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'No es valido. El registro no se puede encontrar.'			
			));												
			redirect( 'groups', 'refresh' );	
		}
		// Load model
		$this->load->model( 'group' );
		
		$data = $this->group->id( $id );
		// Validation rol
		if( empty( $data ) ){
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'El registro no existe'			
			));												
			redirect( 'groups', 'refresh' );
		}
		
		if($this->input->post()){
			// Validations
			$this->form_validation->set_rules('name', 'Nombre del Grupo', 'trim|required');
			$this->form_validation->set_rules('ramo', 'Ramo ', 'trim|required|is_natural_no_zero|less_than[4]');
			// Run Validation
			if ( $this->form_validation->run() == TRUE ){
				$miembros = $this->input->post("miembros");
				$filtered_miembros = array();
				foreach ($miembros as $miembro) {
					if(is_numeric($miembro) and $miembro > 0 and !in_array($miembro, $filtered_miembros))
						$filtered_miembros[] = $miembro;
				}
				$updateGroup = array(
					"description" => $this->input->post("name"),
					"filter_type" => $this->input->post("ramo")
				);
				// Save Record	
				if( $this->group->update( $id, $updateGroup ) == true ){
					$this->group->clean_members($id);
					if(count($filtered_miembros) > 0){
						foreach ($filtered_miembros as $miembro) {
							$newMiembro = array(
								"agent_id" => $miembro,
								"user_group_id" =>$id
							);
							$this->group->add_member($newMiembro);
						}
					}

					// Set true message		
					$this->session->set_flashdata( 'message', array( 
						'type' => true,	
						'message' => 'Se guardo el registro correctamente'
									
					));												
					redirect( 'groups', 'refresh' );	
				}else{	
					// Set false message		
					$this->session->set_flashdata( 'message', array( 
						
						'type' => false,	
						'message' => 'No se pudo guardar el registro, ocurrio un error en la base de datos. Pongase en contacto con el desarrollador'
									
					));													
					redirect( 'groups', 'refresh' );		
				}								
			}						
		}
		// Config view
		$this->view = array(
		  'title' => 'Editar grupo -' .$data['description'],
		  // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'scripts' =>  array(
			  '<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/jquery.validate.js"></script>',
			  '<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/es_validator.js"></script>',
			  '<script src="'.base_url("groups/assets/scripts/groups.js").'"></script>',		
			  '<script src="'.base_url("scripts/config.js").'"></script>'				
		  ),
		  'content' => 'groups/update', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'data' => $data,
		  'ramos' => array(
		  	"" => "Seleccione un ramo",
		  	1 => "Vida",
		  	2 => "GMM",
		  	3 => "Ambos"
		  ),
		  'user' => $this->sessions		
		);
		// Render view 
		$this->load->view( 'index', $this->view );	
	}


	// Delete group
	public function delete( $id = null ){
		// Check access teh user for create
		if( $this->access_delete == false ){	
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'No tiene permisos para ingresar en esta sección "Modulos eliminar", Informe a su administrador para que le otorge los permisos necesarios.'
							
			));	
			redirect( 'groups', 'refresh' );
		
		}
		
		// Validation of id number
		if( empty( $id ) or !is_numeric( $id ) ){
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'No es valido. El registro no se puede encontrar.'
							
			));												
			redirect( 'groups', 'refresh' );
			
		}

		// Load model
		$this->load->model( 'group' );
		$data = $this->group->id( $id );
		
		// Validation rol
		if( empty( $data ) ){
			// Set false message		
			$this->session->set_flashdata( 'message', array( 
				'type' => false,	
				'message' => 'El registro no existe'
			));												
			redirect( 'groups', 'refresh' );
		}
		
		if($this->input->post()){		
			// Save Record	
			if( $this->group->delete( $id ) == true ){
				// Set true message		
				$this->session->set_flashdata( 'message', array( 
					'type' => true,	
					'message' => 'Se elimino el registro correctamente'
				));												
				redirect( 'groups', 'refresh' );
			}else{
				// Set false message		
				$this->session->set_flashdata( 'message', array( 
					'type' => false,	
					'message' => 'No se pudo eliminar el registro, ocurrio un error en la base de datos. Pongase en contacto con el desarrollador'
				));												
				
				
				redirect( 'groups', 'refresh' );
					
			}						
			
		}

		// Config view
		$this->view = array(
				
		  'title' => 'Eliminar Grupo -' .$data['description'],
		  // Permisions
		  'user' => $this->sessions,
		  'user_vs_rol' => $this->user_vs_rol,
		  'roles_vs_access' => $this->roles_vs_access,
		  'scripts' =>  array(
			  '<script type="text/javascript" src="'.base_url("plugins/jquery-validation/jquery.validate.js").'"></script>',
			  '<script type="text/javascript" src="'.base_url("plugins/jquery-validation/es_validator.js").'"></script>',
			  '<script src="'.base_url("groups/assets/scripts/groups.js").'"></script>',		
			  '<script src="'.base_url("scripts/config.js").'"></script>'				
		  ),
		  'content' => 'groups/delete', // View to load
		  'message' => $this->session->flashdata('message'), // Return Message, true and false if have
		  'data' => $data	,
		  'user' => $this->sessions	
		);
		
		
		// Render view 
		$this->load->view( 'index', $this->view );	
	}

	public function searchAgent(){
		if(!$this->input->is_ajax_request()) show_404();

		$this->load->model('usuarios/user');
		$name = $this->input->post("name");
		$agents = $this->input->post("agents");

		//Search parameters
		if ($name!=''){
			$search = array(
				"fullname" => $name,
				"advanced" => array(
					array("clave", ""),
					array("agent_not_in", $agents)
				)
			);
		} else { //Search all users
			$search = array(
				"advanced" => array(
					array("clave", ""),
					array("agent_not_in", $agents)
				)
			);
		}

		$agents = $this->user->find($search);
		$data = array(
			"agents" => $agents
		);

		$this->load->view('groups/agent-table', $data);
	}
}

/* End of file groups.php */
/* Location: ./application/modules/groups/controllers/groups.php */