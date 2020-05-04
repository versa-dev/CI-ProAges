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
class Simulators extends CI_Model{
	
	private $data = array();
	private $insertId;
	
	private $maybe_array_fields = array(	// the fields that are arrays for gmm
		'comisionVentaInicial', 'comisionVentaRenovacion',
		'noNegocios', 'porsiniestridad',
		'simulatorPrimasPeriod', 'primasRenovacion',
		'XAcotamiento'
		);

	public function __construct(){

        parent::__construct();

    }


/*
 *	CRUD Functions, dynamic table.
 **/


// Add
	public function create( $table = '', $values = array() ){
        
		
		if( empty( $table ) or empty( $values ) ) return false;
				
		// Set timestamp unix
		$timestamp = date( 'Y-m-d H:i:s' ) ;
		
		// Set timestamp unix
		$values['last_updated'] = $timestamp;
		$values['date'] = $timestamp;
		
		
			
		if( $this->db->insert( $table, $values ) ){
			
			$this->insertId = $this->db->insert_id();
			
			return true;
		
		}else
		
			return false;
       
    }
	
	
	public function create_banch( $table = '', $values = array() ){
        
		
		if( empty( $table ) or empty( $values ) ) return false;
				
			
		if( $this->db->insert_batch( $table, $values ) ){
									
			return true;
		
		}else
		
			return false;
       
    }
	
	
	
	

/**
 |	Update
 **/ 

    public function update( $table = '', $id = 0, $values = array() ){
        
		if( empty( $table ) or empty( $values ) or empty( $id ) ) return false;
					
				
        if( $this->db->update( $table, $values, array( 'id' => $id ) ) )
			
			return true;
        
		else
        	
			return false;
        
		
    }

/**
 |	Remove 
 **/ 	
	public function delete( $table = '', $field = 'id', $value = null ){
		if( empty( $table ) or empty( $field )  or empty( $value ) ) return false;
			if( $this->db->delete( $table, array( $field => $value ) ) )
				return true;
			else
				return false;
    }

/**
 A more generic delete
 **/ 
	public function generic_delete( $table = '', $where = array() )
	{
		if (empty($table))
			return false;
		if ($where)
			$result = $this->db->delete( $table, $where);
		else
			$result = $this->db->delete( $table);
		return $result;
    }

// Return insert id
	public function insert_id(){   return $this->insertId;  }

// Getting by Agent
	public function getByAgent( $agent = null, $ramo = null ){
		
		if( empty( $agent ) ) return false;
		
		//SELECT * FROM simulator WHERE agent_id = '' ORDER BY id DESC LIMIT 1;
		$this->db->select( 'simulator.*, users.name, users.lastnames' );
		$this->db->from( 'simulator' );
		$this->db->join( 'agents', 'simulator.agent_id=agents.id' );
		$this->db->join( 'users', 'agents.user_id=users.id' );
		$this->db->where( 'agent_id', $agent );
		$this->db->where( 'product_group_id', $ramo );
		$this->db->order_by( 'simulator.id', 'desc' );
		$this->db->limit(1);

		$query = $this->db->get();

		if ($query->num_rows() == 0) return false;
		
		$data = array();
		
		// Getting data
		foreach ($query->result() as $row)
			
			$data[]  = array(
			
				'id' => $row->id,
				'name' => $row->name,
				'lastnames' => $row->lastnames,
				'data' => json_decode( $row->data )
				
			);

		//echo "<pre>". print_r($data). "</pre>";		
		return $data;
		
	}

	private $meta_period = array();
	public function get_meta_period()
	{
		return $this->meta_period;
	}
	public function meta_rows( $table = 'meta_new', $agent = null, $ramo = null)
	{
		$data = array();
		if ( ($agent === null) || !is_array($agent) || ($ramo === null) ||
			 ($ramo < 1) || ($ramo > 3))
			return $data;

		if (!$this->custom_period_from || !$this->custom_period_to)
		{
			$this->meta_period['start_year'] = date('Y');
			$this->meta_period['end_year'] = $this->meta_period['start_year'];
			if ($ramo == 1) // ramo = 1 -> defaults to current trimestre
			{
				$this->meta_period['end_month'] = 3 * $this->trimestre();
				$this->meta_period['start_month'] = $this->meta_period['end_month'] - 2;
			}
			else // ramo = 2 or 3 -> defaults to current cuatrimestre
			{
				$this->meta_period['end_month'] = 4 * $this->cuatrimestre();
				$this->meta_period['start_month'] = $this->meta_period['end_month'] - 3;
			}
		}
		else
		{
			$this->meta_period['start_year'] = substr($this->custom_period_from, 0, 4);
			$this->meta_period['start_month'] = substr($this->custom_period_from, 5, 2);
			$this->meta_period['end_year'] = substr($this->custom_period_to, 0, 4);
			$this->meta_period['end_month'] = substr($this->custom_period_to, 5, 2);
		}
		if ($this->meta_period['start_year'] > $this->meta_period['end_year'])
			$this->meta_period['end_year'] = $this->meta_period['start_year'];

		$this->db->select( $table . '.*' );
		$this->db->from( $table );
		$where = array(
			'ramo' => (int)$ramo,
			'year >= ' => $this->meta_period['start_year'],
			'year <= ' => $this->meta_period['end_year'],				
		);
		$this->db->where($where);	
		if ($agent)
			$this->db->where_in('agent_id', $agent);
		$query = $this->db->get();
		if ($query->num_rows() == 0)
			return $data;

		foreach ($query->result() as $row)
		{
			if (isset($data[$row->agent_id]))
				$data[$row->agent_id][$row->year] = $row;
			else
				$data[$row->agent_id] = array($row->year => $row);
		}
		return $data;
	}

// Getting by Agent
	public function getByAgentNew( $table = 'meta_new', $agent = null, $ramo = null, $period = 0, $year = null)
	{
		$data = array();
		if ( empty( $agent ) || empty($ramo) || ($ramo < 1) || ($ramo > 3))
			return $data;
		if (empty($year))
			$year = date('Y');
		$this->db->select( $table . '.*, users.name, users.lastnames' );
		$this->db->from( $table );
		$this->db->join( 'agents', $table . '.agent_id=agents.id' );
		$this->db->join( 'users', 'agents.user_id=users.id' );
		$where = array(
			'agent_id' => (int)$agent,
			'ramo' => (int)$ramo,
			'period' => (int)$period,
			'year' => (int)$year
		);
		$this->db->where($where);
		$this->db->order_by( $table . '.id', 'desc' );
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() == 0)
		{
			if ( ($year == date('Y')) && ($period == 0) )  // check 'old' table
			{
				$data = $this->getByAgent( $agent, $ramo );
				$field_name_normalize = array(
					'simulatorprimasprimertrimestre' => 'simulatorPrimasPeriod_1',
					'simulatorprimassegundotrimestre' => 'simulatorPrimasPeriod_2',
					'simulatorprimastercertrimestre' => 'simulatorPrimasPeriod_3',
					'simulatorprimascuartotrimestre' => 'simulatorPrimasPeriod_4'
				);
				foreach ($field_name_normalize as $key => $value)
				{
					if (isset($data[0]['data']->$key))
						$data[0]['data']->$value = $data[0]['data']->$key;
				}
//				if (isset($data[0]))
//					$this->db->delete('simulator', array('id' => $data[0]['id']));
			}
		}
		foreach ($query->result() as $row)
		{
			$result_row = array(
            	'id' => $row->id,
            	'name' => $row->name,
            	'lastnames' => $row->lastnames,
				'data' => $row
				);
			$this->_get_computed_fields($result_row['data'], $row, $table);
			$data[] = $result_row;
		}
		if (!isset($data[0]))
			return $data;
		$this->_get_complementing_fields($data[0]['data'], $table );
		return $data;
	}

	private function _get_computed_fields(&$result, $row, $table = 'meta_new' )
	{
		if ($table == 'meta_new')
		{
			foreach ($this->computed_meta_fields as $value)
				$result->$value = 0;
			for ($i = 1; $i <= 12; $i++)
			{
				if ($row->ramo == 1)
				{
					switch (TRUE)
					{
						case ($i < 4):
							$result->{'primas-meta-primer'} += $row->{'primas-meta-' . $i};
							$result->{'primas-negocio-meta-primer'} += $row->{'primas-negocios-meta-' . $i};
							$result->{'primas-solicitud-meta-primer'} += $row->{'primas-solicitud-meta-' . $i};
							break;
						case (($i >= 4) && ($i < 7)):
							$result->{'primas-meta-segund'} += $row->{'primas-meta-' . $i};
							$result->{'primas-negocio-meta-segund'} += $row->{'primas-negocios-meta-' . $i};
							$result->{'primas-solicitud-meta-segund'} += $row->{'primas-solicitud-meta-' . $i};						break;
						case (($i >= 7) && ($i < 10)):
							$result->{'primas-meta-tercer'} += $row->{'primas-meta-' . $i};
							$result->{'primas-negocio-meta-tercer'} += $row->{'primas-negocios-meta-' . $i};
							$result->{'primas-solicitud-meta-tercer'} += $row->{'primas-solicitud-meta-' . $i};
							break;
						case (($i >= 10) && ($i <= 12)):
							$result->{'primas-meta-cuarto'} += $row->{'primas-meta-' . $i};
							$result->{'primas-negocio-meta-cuarto'} += $row->{'primas-negocios-meta-' . $i};
							$result->{'primas-solicitud-meta-cuarto'} += $row->{'primas-solicitud-meta-' . $i};
							break;
						default:
							break;
					}
				}
				else
				{
					switch (TRUE)
					{
						case ($i < 5):
							$result->{'primas-meta-primer'} += $row->{'primas-meta-' . $i};
							$result->{'primas-negocio-meta-primer'} += $row->{'primas-negocios-meta-' . $i};
							$result->{'primas-solicitud-meta-primer'} += $row->{'primas-solicitud-meta-' . $i};
							break;
						case (($i >= 5) && ($i < 9)):
							$result->{'primas-meta-second'} += $row->{'primas-meta-' . $i};
							$result->{'primas-negocio-meta-second'} += $row->{'primas-negocios-meta-' . $i};
							$result->{'primas-solicitud-meta-second'} += $row->{'primas-solicitud-meta-' . $i};						break;
						case (($i >= 0) && ($i <= 12)):
							$result->{'primas-meta-tercer'} += $row->{'primas-meta-' . $i};
							$result->{'primas-negocio-meta-tercer'} += $row->{'primas-negocios-meta-' . $i};
							$result->{'primas-solicitud-meta-tercer'} += $row->{'primas-solicitud-meta-' . $i};
							break;
						default:
							break;
					}
				}
				$result->{'primas-meta-total'} += $row->{'primas-meta-' . $i};
				$result->{'primas-negocios-meta-total'} += $row->{'primas-negocios-meta-' . $i};
				$result->{'primas-solicitud-meta-total'} += $row->{'primas-solicitud-meta-' . $i};
			}
		}
	}

	private function _get_complementing_fields(&$full_result, $table = 'meta_new' )
	{
		if ($table == 'meta_new')
		{
			$primas_promedio = isset($full_result->primas_promedio) ? $full_result->primas_promedio : 0;
			if (isset($full_result->primaspromedio) && ($full_result->primaspromedio > $primas_promedio))
				$primas_promedio = $full_result->primaspromedio;
			$full_result->primas_promedio = $primas_promedio;
			$full_result->primaspromedio = $primas_promedio;
			if (!isset($full_result->prima_total_anual))
			{
				if (isset($full_result->primasAfectasInicialesUbicar))
					$full_result->prima_total_anual = $full_result->primasAfectasInicialesUbicar;
				elseif (isset($full_result->primasnetasiniciales))
					$full_result->prima_total_anual = $full_result->primasnetasiniciales;
				else
					$full_result->prima_total_anual = 0;
			}
			if ($full_result->prima_total_anual)
			{
				$total = (float)$full_result->prima_total_anual;
				for ($i = 1; $i <= 12; $i++)
					$full_result->{'mes-' . $i} = round(($full_result->{'primas-meta-' . $i} / $total * 10000) * 100) / 10000;
			}
			else
				for ($i = 1; $i <= 12; $i++)
					$full_result->{'mes-' . $i} = 0;
		}
		else
		{
			foreach ($this->maybe_array_fields as $field_name)
			{
				for ($i = 1; $i <= 4; $i++)
				{
					if (isset($full_result->$field_name->$i))
						$full_result->{$field_name . '_' . $i} = $full_result->$field_name->$i;
				}
				unset($full_result->$field_name);
			}
		}
	}

// Getting config
	public function getConfig(){
				
		//SELECT * FROM simulator_default_estacionalidad ORDER BY id DESC;
		$this->db->select();
		$this->db->from( 'simulator_default_estacionalidad' );
		$this->db->order_by( 'id', 'asc' );
		
		$query = $this->db->get();
		
		if ($query->num_rows() == 0) return false;
		
		$data = array();
		
		// Getting data
		foreach ($query->result() as $row)
			
			$data[]  = array(
			
				'id' => $row->id,
				'month' => $row->month,
				'vida' => $row->vida,
				'gmm' => $row->gmm,
				'autos' => $row->autos
				
				
			);
			
				
		return $data;
		
	}		

	public function getNewConfigMetas($where)
	{
		//SELECT * FROM simulator_default_estacionalidad ORDER BY id DESC;
		$this->db->select();
		$this->db->from( 'simulator_default_estacionalidad' );
		if ($where)
			$this->db->where($where);
		$this->db->order_by( 'id', 'asc' );
		$query = $this->db->get();
		if ($query->num_rows() == 0)
			return false;

		$data = array();
		foreach ($query->result_array() as $row)
			$data[] = $row;
		return $data;
	}	
	
	public function getConfigMetas( $year = false, $trimestre = null, $cuatrimestre = null ){
				
		//SELECT * FROM simulator_default_estacionalidad ORDER BY id DESC;
		$this->db->select();
		$this->db->from( 'simulator_default_estacionalidad' );
		
		
		
		if( $year == false ){
			
			// Where
			if( !empty( $trimestre ) and $cuatrimestre == null ){
				
				if( $trimestre == 1 ) $this->db->where( 'id = 1 or id = 2 or id = 3' );
				if( $trimestre == 2 ) $this->db->where( 'id = 4 or id = 5 or id = 6' );
				if( $trimestre == 3 ) $this->db->where( 'id = 7 or id = 8 or id = 9' );
				if( $trimestre == 4 ) $this->db->where( 'id = 10 or id = 11 or id = 12' );
					
				
			}
			
			
			if( !empty( $cuatrimestre ) and $trimestre == null ){
				
				if( $cuatrimestre == 1 ) $this->db->where( 'id = 1 or id = 2 or id = 3 or id = 4' );
				if( $cuatrimestre == 2 ) $this->db->where( 'id = 5 or id = 6 or id = 7 or id = 8' );
				if( $cuatrimestre == 3 ) $this->db->where( 'id = 9 or id = 10 or id = 11 or id = 12' );
			
			}
			
			
			
			
		}
				
		
		$this->db->order_by( 'id', 'asc' );
		
		$query = $this->db->get();
		
		if ($query->num_rows() == 0) return false;
		
		$data = array();
		
		// Getting data
		foreach ($query->result() as $row)
			
			$data[]  = array(
			
				'id' => $row->id,
				'month' => $row->month,
				'vida' => $row->vida,
				'gmm' => $row->gmm,
				'autos' => $row->autos
				
				
			);
			
				
		return $data;
		
	}
	
	
	/*Solicitudes logradas*/
	public function getSolicitudLograda( $agent = null, $product_group_id = null, $month = null, $year = null ){
		
		if( empty( $agent ) or empty( $product_group_id ) or empty( $month ) or empty( $year ) ) return 0;		
		/*
		SELECT COUNT(id) AS count 
		FROM `agents_activity` 
		WHERE  YEAR(end) = 2013 
		AND MONTH(end) = 09 
		AND `agent_id` =1
		*/
				
		$this->db->select( 'COUNT(work_order.id) AS count ' );
		$this->db->from( 'work_order' );
		$this->db->join( 'policies_vs_users', 'work_order.policy_id=policies_vs_users.policy_id' );
		$this->db->join( 'work_order_types', ' work_order_types.id=work_order.work_order_type_id' );
		$this->db->where( 'YEAR(creation_date) = '. $year );
		$this->db->where( 'MONTH(creation_date) = '. $month );
		$this->db->where( 'policies_vs_users.user_id', $agent );
		$this->db->where( '( work_order_types.patent_id=90 OR work_order_types.patent_id=47 )' );
		$this->db->where( 'product_group_id', $product_group_id );
				
		$query = $this->db->get();
		
		if ($query->num_rows() == 0) return false;
		
		$data = array();
		
		$count = 0;
		
		// Getting data
		foreach ($query->result() as $row)
			
			$count = $row->count;
									
				
		return $count;
		
	}		
	
	
	
	/* Negocios logrados */
	public function getNegociosLograda( $agent = null, $product_group_id = null, $month = null, $year = null ){
		
		if( empty( $agent ) or empty( $product_group_id ) or empty( $month ) or empty( $year ) ) return 0;		
		/*
		SELECT  COUNT(id) AS count  
		FROM `work_order` 
		WHERE  YEAR(creation_date) = 2013 
		AND MONTH(creation_date) = 09 
		AND `user` =1
		AND work_order_status_id=7
		AND product_group_id=1;
		*/
						
		$this->db->select( 'COUNT(payments.business) AS count ' );
		$this->db->from( 'payments' );
		$this->db->where( 'YEAR(payment_date) = '. $year );
		$this->db->where( 'MONTH(payment_date) = '. $month );
		$this->db->where( 'agent_id', $agent );
		$this->db->where( 'business', 1 );
		$this->db->where( 'product_group', $product_group_id );
		$this->db->where( 'valid_for_report', 1 );
		
		$query = $this->db->get();

		if ($query->num_rows() == 0) return false;
		
		$data = array();
		
		$count = 0;
		
		// Getting data
		foreach ($query->result() as $row)
			
			$count = $row->count;
									
				
		return $count;
		
	}	
	
	
	
	
	/*Primas logradas*/
	public function getPrimasLograda( $agent = null, $product_group_id = null, $month = null, $year = null ){

		if( empty( $agent ) or empty( $product_group_id ) or empty( $month ) or empty( $year ) ) return 0;		
		/*
		SELECT DISTINCT(policy_id) 
		FROM `work_order` 
		WHERE  YEAR(creation_date) = 2013 
		AND MONTH(creation_date) = 09 
		AND `user` =1
		AND work_order_status_id=7
		AND product_group_id=1;
		*/
				
		$this->db->select( );
		$this->db->from( 'payments' );
		$this->db->where( 'YEAR(payment_date) = '. $year );
		$this->db->where( 'MONTH(payment_date) = '. $month );
		$this->db->where( 'agent_id', $agent );
                $this->db->where( 'year_prime', 1 );
		$this->db->where( 'product_group', $product_group_id );
		$this->db->where( 'valid_for_report', 1 );		
				
		$query = $this->db->get();
	
		if ($query->num_rows() == 0) return 0;
		
		$data = array();
		
		$prima = 0;
		
		// Getting data
		//foreach ($query->result() as $row){
		
			
			/*
			SELECT prima
			FROM `policies` 
			WHERE `id` =policy_id
			*/
			/*$this->db->select( 'prima' );
			$this->db->from( 'policies' );
			$this->db->where( 'id', $row->policy_id );
			
			$queryprima = $this->db->get();*/
			
			if ($query->num_rows() == 0) return 0;
			
			foreach ($query->result() as $rowprima)
				
				$prima = $prima + (float)$rowprima->amount;
			
			
		
		
		//}
				
		return $prima;		
	}	
	
	
	
	public function trimestre($mes=null){	  
		$mes = is_null($mes) ? date('m') : $mes;
		$trim=floor(($mes-1) / 3)+1;
		return $trim;
	}
	  
	 public function cuatrimestre($mes=null){	  
		$mes = is_null($mes) ? date('m') : $mes;
		$trim=floor(($mes-1) / 4)+1;
		return $trim;
	}

	public function create_update($table, $values = NULL)
	{
		if ( empty( $table ) or empty( $values ) )
			return false;
		$result = false;
		$query = $this->db->get_where($table, array(
			'period' => $values['period'],
			'agent_id' => $values['agent_id'],
			'ramo' => $values['ramo'],
			'year' => $values['year'],
			),
			1, 0);
		if ($query->num_rows() > 0)	// update existing row
		{
			$old_row = $query->row();
			$this->db->where('id', $old_row->id);  // ??
			$values['date'] = date( 'Y-m-d H:i:s' ) ;
			$result = $this->db->update($table, $values);
			if ($result)
				$result = $old_row->id;
		}
		else
		{
			unset($values['id']);
			$result = $this->db->insert( $table, $values );
			if ($result)
			{
				$this->insertId = $this->db->insert_id();
				$result = $this->insertId;
			}
		}
		return $result;
	}

	public function translate_periodo($periodo, $ramo)
	{
		$result = 0;
		switch ($periodo)
		{
			case 2: // current trimestre or cuatrimestre depending on ramo
				if ($ramo == 1)
					$result = $this->trimestre() + 110;
				else
					$result = $this->cuatrimestre() + 120;
				break;
			case 1: // current month
				$result = date('m');
			case 3: // current year
			case 4: // should be custom period but this does not exist for meta
			default:
				break;
		}
		return $result;
	}
}
?>
