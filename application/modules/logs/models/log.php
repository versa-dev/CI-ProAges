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
class Log extends CI_Model{
	
	private $data = array();
	
	public function __construct(){
		
        parent::__construct();
			
    }
	


// Add method	
	public function create( $values = array() ){
        
		
		if( empty( $values ) ) return false;
					
		if( $this->db->insert( 'logs', $values ) )
			
			return true;
		else
		
			return false;
       
    }
	
	
	

/**
 |	Remove 
 **/ 	
	 public function delete( $id ){
        
		if( empty( $id ) ) return false;
					   
			if( $this->db->delete( 'logs', array('id' => $id ) ) )
			
					return true;
			
			else
			
				return false;
			
			
				
    }

















/**
 |	Getting All
 **/ 
	
	public function all( $start = 0 ) {
		
        $this->db->limit( 250, $start );

        $query = $this->db->get( 'logs' );

		
		
		if ($query->num_rows() == 0) return false;
 	
		
		
		unset( $this->data );

		$this->data = array();
		
		
		
		foreach ($query->result() as $row) {

			$this->data[] = array( 
		    	'id' => $row->id,
		    	'description' => $row->description,
				'ip' => $row->ip,
				'user_agent' => $row->user_agent,
				'referer' => $row->referer,
		    	'date' => date( 'd-m-Y H:i:s', $row->date ),
		    	'last_updated' => $row->last_updated
		    );

		}

		return $this->data;
		
   }

	
	
	
	
	
	








// Count records for pagination
	public function record_count() {
        return $this->db->count_all( 'logs'  );
    }






}
?>