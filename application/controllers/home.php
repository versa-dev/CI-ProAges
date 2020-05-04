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
class Home extends CI_Controller{
	
	public $sessions = array();
	
	public $view = array();
	
	public $user_vs_rol = array();
	
	public $roles_vs_access = array();
	
				
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
								
								
		if( empty( $this->sessions ) and $this->uri->segment(2) != 'login'  ) redirect( 'usuarios/login', 'refresh' );
			
	}
	
	

// Show Proages index	
	public function index(){
		
		// Load Model Users
		$this->load->model( 'usuarios/user' );
		
		// Counts Users 
		$rolcount = array(
			'agents' => $this->user->record_count_roles(1),
			'coordinador' => $this->user->record_count_roles(2),
			'gerente' => $this->user->record_count_roles(3),
			'director' => $this->user->record_count_roles(4), 
			'administrador' => $this->user->record_count_roles(5)
		);
		
		// Setting vars views
		$this->view = array(
			
			'user' => $this->sessions,
			'user_vs_rol' => $this->user_vs_rol,
			'roles_vs_access' => $this->roles_vs_access,
			'rolcount' => $rolcount,
			'content' => 'admin/index', // View to load
			'message' => $this->session->flashdata('message') // Return Message, true and false if have
		
		);
		
		//Load the view
		$this->load->view( 'index', $this->view );	
	}
	
	
	
}
?>