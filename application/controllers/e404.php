<?php

class E404 extends CI_Controller{

	public function index(){
		
		$this->load->view( 'admin/error.php' ) ;
		
	}
}
?>