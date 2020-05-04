<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// SET NEWS INBOX MESSAGE
class Inbox extends CI_Model{
	
		
	// Add Message for users
	public function send_message( $data = array() ){
		
		if( empty( $data ) ) return false;
		
		
		// Load Module
		$this->load->model( 'usuarios/users' );
		
		$query = array();
		
		$status = true;
		
		/*
		$send['subject'] = $data['subject'];	
		
		$send['message'] = $data['message'];	
		
		$send['date'] = date( 'Y-m-d' );	
		
		$send['view'] = 0;	
		*/
		
		if( !empty( $data['main_option'] ) )
		
			$usuarios = $this->users->getByStatusSendMail( $data['main_option'] );
		
		
		if( !empty( $usuarios ) ){
			
			foreach( $usuarios as $value )
				
				$query[] = array( 
							'subject' => $data['subject'],
							'message' => $data['message'],
							'date' => date( 'Y-m-d' ), 
							'view' => 0,
							'user_id' => $value['id'] 
				);
		
		
			if( $this->db->insert_batch( 'users_inbox', $query) == false ) $status = false;
		
		
		}
		
		
		if( isset( $usuarios ) and !empty( $usuarios )  )unset( $usuarios );
				
		if( !empty( $data['send_to'] ) )
		
			$usuarios = $this->users->getByStatusSendMail( $data['send_to'] );
		
		
		if( !empty( $usuarios ) ){
			
			foreach( $usuarios as $value )
				
				$query[] = array( 
							'subject' => $data['subject'],
							'message' => $data['message'],
							'date' => date( 'Y-m-d' ), 
							'view' => 0,
							'user_id' => $value['id'] 
				);
		
		
			if( $this->db->insert_batch( 'users_inbox', $query) == false ) $status = false;
		
		}
		
		
		return $status;
		
		
	}
	
	




// Inbox functions

// Getting by User
	public function getByUser( $user = null, $begin = 0 ){
		
		if( empty( $user ) ) return false;
		
		$this->db->select();
		$this->db->from( 'users_inbox' );
		$this->db->where( 'user_id', $user );
		$this->db->limit(  30, $begin );
		$this->db->order_by( 'id', 'desc' );
						
		$query = $this->db->get();
				
		if ( $query->num_rows() == 0 ) return false;
				
		$data = array();
					
		foreach ( $query->result() as $row ){
			
		    $data[] = array( 
				'id' => $row->id,
				'subject' => $row->subject,
				'message' => $row->message,
				'view' => $row->view,
				'date' => $row->date,
		    );
		  
		}
		
		return $data;
		
		
		
		
	}
	
// Count message pagination for user
	public function getByUserCountPag( $user = null ){
		
		if( empty( $user ) ) return false;
		
		$this->db->select();
		$this->db->from( 'users_inbox' );
		$this->db->where( array( 'user_id' => $user ) );
			
		$query = $this->db->get();
		
			
		return $query->num_rows();
	
		
	}	
	
	
	
	// Count news message for user
	public function getByUserCount( $user = null ){
		
		if( empty( $user ) ) return false;
		
		$this->db->select();
		$this->db->from( 'users_inbox' );
		$this->db->where( array( 'user_id' => $user, 'view' => 0 ) );
			
		$query = $this->db->get();
		
			
		return $query->num_rows();
	
		
	}
	
	
	
// Render Message
	public function getByIdRender( $id = null ){
		
		if( empty( $id ) ) return false;
		
		$this->db->select();
		$this->db->from( 'users_inbox' );
		$this->db->where( 'id', $id );
		$this->db->limit(  1 );
						
		$query = $this->db->get();
				
		if ( $query->num_rows() == 0 ) return false;
				
		$data = array();
					
		foreach ( $query->result() as $row ){
			
		    $data[] = array( 
				'id' => $row->id,
				'subject' => $row->subject,
				'message' => $row->message,
				'view' => $row->view,
				'date' => $row->date,
		    );
		  
		}
		
		
		$this->db->update( 'users_inbox' , array( 'view' => 1 ), array('id' => $id ) );
				
		
		return $data;
		
	}	
	

}
?>