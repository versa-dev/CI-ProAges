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
class Activity extends CI_Model{
	
	private $data = array();
	
	private $insertId;
	private $agent_name_where_in = null;
		
	public function __construct(){
		
        parent::__construct();
			
    }
	

/*
 *	CRUD Functions, dynamic table.
 **/


// Add
	public function create( $table = 'agents_activity', $values = array() ){
        
		
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

    public function update( $table = 'agents_activity', $id = 0, $values = array() ){
        
		if( empty( $table ) or empty( $values ) or empty( $id ) ) return false;
					
				
        if( $this->db->update( $table, $values, array( 'id' => $id ) ) )
			
			return true;
        
		else
        	
			return false;
        
		
    }

/**
 |	Remove 
 **/ 	
	 public function delete( $table = 'agents_activity', $field = 'id', $value = null ){
        
		if( empty( $table ) or empty( $field )  or empty( $value ) ) return false;
					   
			if( $this->db->delete( $table, array( $field => $value ) ) )
			
					return true;
			
			else
			
				return false;
			
			
				
    }
// Read a row
    public function getForUpdateOrDelete( $table = 'agents_activity', $id = 0, $agent_id = 0 ){

		if	( empty( $table ) or empty( $agent_id ) or empty( $id ) )
			return false;

		$where = array( 'id' => (int) $id );
		if ($agent_id)
			$where['agent_id'] = (int) $agent_id;
		$this->db->where( $where );
	
		$query = $this->db->get($table);
		if ($query->num_rows() == 0)
			return false;

		return $query->row();
    }

// Return insert id
	public function insert_id(){   return $this->insertId;  }


	public function exist( $table = 'agents_activity', $values = array() ){
        
		
		if( empty( $table ) or empty( $values ) ) return false;
		
		$this->db->select();
		$this->db->from( 'agents_activity' );
		$this->db->where( 'agent_id', $values['agent_id'] );
		$this->db->where( 'begin', $values['begin'] );
		$this->db->where( 'end', $values['end'] );
        $this->db->limit( 1 );
		
		$query = $this->db->get();		
		
		if($query->num_rows() == 0) 
			
			return true;
		
		else
			
			return false;	
		
			
    }

/**
 |	Getting for overview
 **/ 
	
	public function overview( $start = 0, $agent_id = null, $filter = null ) {
		
		if( empty( $agent_id ) ) return false;
		/*
		 SELECT *
		 FROM `agents_activity` 
		 ORDER BY id DESC		
		*/
		$this->db->select();
		$this->db->from( 'agents_activity' );
		$this->db->where( 'agent_id', $agent_id );
		if (isset($filter['begin']) && isset($filter['end']))
		{
			$this->db->where(array(
				'begin >= ' => $filter['begin'],
				'end <= ' => $filter['end'],				
			));
		}
		$this->db->order_by( 'id', 'desc' );
//        $this->db->limit( 50, $start );
		
		$query = $this->db->get();	
		if ($query->num_rows() == 0) return false;
 	
		$data = array();
													
		foreach ($query->result() as $row) {
			
			$data[] = array(
				 'activity_id' => $row->id,			
				 'begin' => $row->begin,
				 'end' => $row->end,
				 'cita' => $row->cita,
				 'prospectus' => $row->prospectus,
				 'vida_requests' => $row->vida_requests,
				 'vida_businesses' => $row->vida_businesses,
				 'gmm_requests' => $row->gmm_requests,
				 'gmm_businesses' => $row->gmm_businesses,
				 'autos_businesses' => $row->autos_businesses,
				 'interview' => $row->interview,
				 'comments' => $row->comments,
				 'last_updated' => $row->last_updated,
				 'date' => $row->date       			
			);
			
		}
		
		return $data;
		
   }

	public function getByAgentId( $agent_id = null ){
		
		if( empty( $agent_id ) ) return false;
		/*
		 SELECT SUM( cita ) AS cita, SUM( prospectus ) AS prospectus, SUM( interview ) AS interview
		 FROM `agents_activity` 
		 WHERE agent_id=27
		 ORDER BY id DESC		
		*/ 
		$this->db->select( 'SUM( cita ) AS cita, SUM( prospectus ) AS prospectus, SUM( interview ) AS interview' );
		$this->db->from( 'agents_activity' );
		$this->db->where( 'agent_id', $agent_id );
		$this->db->order_by( 'id', 'desc' );
		
		$query = $this->db->get();	
		
		if ($query->num_rows() == 0) return false;
 	
		$data = array();
													
		foreach ($query->result() as $row) {
			
			$data[] = array(
				 'cita' => $row->cita,
				 'prospectus' => $row->prospectus,
				 'interview' => $row->interview,
			);
			
		}
		
		return $data;
		
	}


// Count records for pagination
	public function record_count( $agent_id = null, $filter = null ) {
		
		if( empty( $agent_id ) ) return false;
	    		
		return $this->db->from( 'agents_activity' )->where( 'agent_id',$agent_id )->count_all_results();
    }


//View Activities report
	public function report( $table = 'agents_activity', $values = array() ){
        
		$data = array();
		
		if( empty( $table ) or empty( $values ) ) return false;
		if ($values['periodo'] == 2)	// if Week is selected
		{
			$this->db->select();
		}
		else // Month, Year or Custom
//		if (TRUE)
		{
			$fields_selected = '`agents_activity`.`agent_id` ,
`agents_activity`.`begin`,
`agents_activity`.`end`,
"" AS `comments`,
`agents_activity`.`last_updated`,
`agents_activity`.`date`,
SUM( `agents_activity`.`cita` ) AS `cita` , 
SUM( `agents_activity`.`prospectus` )  AS `prospectus`, 
SUM( `agents_activity`.`interview` )  AS `interview`, 
SUM( `agents_activity`.`vida_requests` )  AS `vida_requests`, 
SUM( `agents_activity`.`vida_businesses` )  AS `vida_businesses`, 
SUM( `agents_activity`.`gmm_requests` )  AS `gmm_requests`, 
SUM( `agents_activity`.`gmm_businesses` )  AS `gmm_businesses`,
SUM( `agents_activity`.`autos_businesses` )  AS `autos_businesses`,
`users`.`name` ,
`users`.`lastnames`';
			$this->db->select($fields_selected, FALSE);
			$this->db->group_by('agent_id');
		}
		$this->db->from( 'agents_activity' );
		$this->db->join( 'agents', 'agents_activity.agent_id=agents.id');
		$this->db->join( 'users', 'agents.user_id=users.id');
//		$this->db->where( 'begin', $values['begin'] );
//		$this->db->where( 'end', $values['end'] );
		$this->db->where( array(
			'begin >= ' => $values['begin'],
			'end <= ' => $values['end']) );
		$this->db->order_by( 'interview', 'desc' );
		$query = $this->db->get();		

		if ($query->num_rows() == 0) return false;
 	
		$data = array(
			'totals' => array(
				'cita' => 0, 'prospectus' => 0, 'interview' => 0,
				'vida_requests' => 0, 'vida_businesses' => 0, 'gmm_requests' => 0,
				'gmm_businesses' => 0, 'autos_businesses' => 0),
			'rows' => array()
		);
													
		foreach ($query->result() as $row) {
			foreach ($data['totals'] as $key => $value)
				$data['totals'][$key] += $row->$key;
			$data['rows'][] = array(
				 'name' => $row->name,
				 'lastnames' => $row->lastnames,
				 'begin' => $row->begin,
				 'end' => $row->end,
				 'cita' => $row->cita,
				 'prospectus' => $row->prospectus,
				 'vida_requests' => $row->vida_requests,
				 'vida_businesses' => $row->vida_businesses,
				 'gmm_requests' => $row->gmm_requests,
				 'gmm_businesses' => $row->gmm_businesses,
				 'autos_businesses' => $row->autos_businesses,
				 'interview' => $row->interview,
				 'comments' => $row->comments,
				 'last_updated' => $row->last_updated,
				 'date' => $row->date       			
			);
			
		}

		return $data;
			
    }

// Sales activity
	public function sales_activity( $values, $new_period_filter = FALSE )
	{
		$agents_with_activity = array();
		$data = array(
			'totals' => array(
				'cita' => 0, 'prospectus' => 0, 'interview' => 0, 'weeks_reported' => 0),
			'rows' => array(),
		);
		$activity_rows = array();
		$solicitudes_work_order_rows = array();
		$negocios_work_order_rows = array();
		if (!$new_period_filter)
		{
			if ($values['periodo'] == 2)	// if Week is selected
			{
				$fields_selected = '`agents_activity`.`agent_id` , 
1  AS `weeks_reported` ,
`agents_activity`.`cita` AS `cita` , 
`agents_activity`.`prospectus` AS `prospectus`, 
`agents_activity`.`interview`  AS `interview`';
				$this->db->select($fields_selected, FALSE);
			}
			else // Month, Year or Custom
			{
				$fields_selected = '`agents_activity`.`agent_id` ,
COUNT( `agents_activity`.`agent_id` ) AS `weeks_reported` ,
SUM( `agents_activity`.`cita` ) AS `cita` , 
SUM( `agents_activity`.`prospectus` )  AS `prospectus`, 
SUM( `agents_activity`.`interview` )  AS `interview`';
				$this->db->select($fields_selected, FALSE);
				$this->db->group_by('agent_id');
			}
		}
		else
		{
			$fields_selected = '`agents_activity`.`agent_id` ,
COUNT( `agents_activity`.`agent_id` ) AS `weeks_reported` ,
SUM( `agents_activity`.`cita` ) AS `cita` , 
SUM( `agents_activity`.`prospectus` )  AS `prospectus`, 
SUM( `agents_activity`.`interview` )  AS `interview`';

			$fields_selected = '`agents_activity`.`agent_id` ,
( SUM(1 + DATEDIFF(`end`, `begin`)) / 7 ) AS `weeks_reported`,
SUM( `agents_activity`.`cita` ) AS `cita` , 
SUM( `agents_activity`.`prospectus` )  AS `prospectus`, 
SUM( `agents_activity`.`interview` )  AS `interview`';


			$this->db->select($fields_selected, FALSE);
			$this->db->group_by('agent_id');
		}
		$this->db->from( 'agents_activity' );
		$this->db->join( 'agents', 'agents_activity.agent_id=agents.id');

		$this->db->where( array(
			'begin >= ' => $values['begin'],
			'end <= ' => $values['end']) );

		$agents_selected = array();
		if ( isset( $values['agent_name'] ) and !empty( $values['agent_name'] ) )
		{
			$this->_get_agent_filter_where($values['agent_name']);
			if ($this->agent_name_where_in)
			{
				foreach ($this->agent_name_where_in as $value)
					$agents_selected[$value] = $value;
				$this->db->where_in('agents.id', $this->agent_name_where_in);
			}
		}
		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				foreach ($data['totals'] as $key => $value)
					$data['totals'][$key] += $row->$key;
				$activity_rows[$row->agent_id] = array(
					'weeks_reported' => $row->weeks_reported,
					'citaT' => $row->cita,
					'citaP' => $row->cita / $row->weeks_reported,				
					'prospectusT' => $row->prospectus,
					'prospectusP' => $row->prospectus / $row->weeks_reported,				
					'interviewT' => $row->interview,
					'interviewP' => $row->interview / $row->weeks_reported,
				);
				$agents_with_activity[$row->agent_id] = $row->agent_id;
			}
		}
		$query->free_result();
////////////////////////////////
		$totals_work_order = array('VIDA_solicitudes' => 0, 'GMM_solicitudes' => 0);
		$solicitudes_work_order_rows = $this->get_ot_count($values, $agents_selected, $totals_work_order, $agents_with_activity);
		$data['totals'] = array_merge($data['totals'], $totals_work_order);

////////////////////////////////
		$totals_work_order = array('GMM_negocios' => 0);
		$negocios_work_order_rows = $this->get_ot_count($values, $agents_selected, $totals_work_order, $agents_with_activity,
			'negocios', array('work_order_status_id' => 4, 'product_group.name' => 'GMM'));
		$data['totals'] = array_merge($data['totals'], $totals_work_order);

		$totals_work_order = array('VIDA_negocios' => 0);
		$negocios_work_order_rows_vida = $this->get_ot_count($values, $agents_selected, $totals_work_order, $agents_with_activity,
			'negocios', array('work_order_status_id' => 4, 'product_group.name' => 'Vida'));
		$data['totals'] = array_merge($data['totals'], $totals_work_order);

////////////////////////////////
		if (count($agents_with_activity))
		{
			$this->db->select( 'users.id as user_id, users.name, users.lastnames, users.company_name, agents.id as agent_id' );
			$this->db->from( 'users' );
			$this->db->join( 'agents', 'agents.user_id=users.id' );
			$this->db->where_in('agents.id', $agents_with_activity);
			$this->db->order_by('name asc, lastnames asc, company_name asc');
			$query = $this->db->get();
			if ($query->num_rows() > 0)
			{
				$base_url = base_url();
				foreach ($query->result() as $row)
				{
					$name = $row->name . ' ' . $row->lastnames;
					if ($row->company_name)
						$name .= ' ' . $row->company_name;
					$data['rows'][$row->agent_id] = array(
						'user_id' => $row->user_id,
						'simulator_url' => $base_url . 'simulator/index/' . $row->user_id . '/1.html',
						'perfil_url' => $base_url . 'agent/index/' . $row->user_id . '/1.html',
						'activities_url' => $base_url . 'activities/index/' . $row->user_id . '.html',
						'add_activities_url' => $base_url . 'activities/create/' . $row->user_id . '.html',
						'name' => $name,
						'weeks_reported' => 0,
						'citaT' => 0,
						'citaP' => 0,
						'interviewT' => 0,
						'interviewP' => 0,				
						'prospectusT' => 0,
						'prospectusP' => 0,
						'vida_solicitudes' => 0,
						'vida_negocios' => 0,
						'gmm_solicitudes' => 0,
						'gmm_negocios' => 0,
					);
					if (isset($activity_rows[$row->agent_id]))
					{
						foreach ($activity_rows[$row->agent_id] as $key => $activity_value)
							$data['rows'][$row->agent_id][$key] = $activity_value;
					}
					if (isset($solicitudes_work_order_rows[$row->agent_id]))
					{
						if (isset($solicitudes_work_order_rows[$row->agent_id]['VIDA']) && 
							isset($solicitudes_work_order_rows[$row->agent_id]['VIDA']['solicitudes']))
							$data['rows'][$row->agent_id]['vida_solicitudes'] = $solicitudes_work_order_rows[$row->agent_id]['VIDA']['solicitudes'];
						if (isset($solicitudes_work_order_rows[$row->agent_id]['GMM']) && 
							isset($solicitudes_work_order_rows[$row->agent_id]['GMM']['solicitudes']))
							$data['rows'][$row->agent_id]['gmm_solicitudes'] = $solicitudes_work_order_rows[$row->agent_id]['GMM']['solicitudes'];
					}
					if (isset($negocios_work_order_rows[$row->agent_id]))
					{
						if (isset($negocios_work_order_rows[$row->agent_id]['GMM']) && 
							isset($negocios_work_order_rows[$row->agent_id]['GMM']['negocios']))
							$data['rows'][$row->agent_id]['gmm_negocios'] = $negocios_work_order_rows[$row->agent_id]['GMM']['negocios'];
					}
					if (isset($negocios_work_order_rows_vida[$row->agent_id]))
					{
						if (isset($negocios_work_order_rows_vida[$row->agent_id]['VIDA']) && 
							isset($negocios_work_order_rows_vida[$row->agent_id]['VIDA']['negocios']))
							$data['rows'][$row->agent_id]['vida_negocios'] = $negocios_work_order_rows_vida[$row->agent_id]['VIDA']['negocios'];
					}
				}
			}
			$query->free_result();
		}
		return $data;
    }
/////////////////////////
	private function _get_agent_filter_where($agent_name)
	{
		if ($this->agent_name_where_in !== null)
			return;
		$this->agent_name_where_in = array();
		$agent_name_array = explode("\n", $agent_name);
		$to_replace = array(']', "\n", "\r");
		foreach ($agent_name_array as $value)
		{
			$pieces = explode( ' [ID: ', $value);
			if (isset($pieces[1]))
			{
				$pieces[1] = str_replace($to_replace, '', $pieces[1]);
				if (!isset($this->agent_name_where_in[$pieces[1]]))
					$this->agent_name_where_in[] = $pieces[1];
			}
		}
	}

/*  Requests from table `work_order` related to sales activity
    Maybe this should be put in /application/modules/ot/models/work_order.php */

	public function get_ot_count($values, $agents_selected, &$totals_work_order, &$agents_with_activity, $field = 'solicitudes', $add_where = array())
	{
		return $this->_get_ots(TRUE, $values, $agents_selected, $totals_work_order, $agents_with_activity, $field, $add_where);
	}

	public function get_ot_list($values, $agents_selected, &$totals_work_order, &$agents_with_activity, $field = 'solicitudes', $add_where = array())
	{
		return $this->_get_ots(FALSE, $values, $agents_selected, $totals_work_order, $agents_with_activity, $field, $add_where);
	}

	private function _get_ots($get_count, $values, $agents_selected, &$totals_work_order, &$agents_with_activity, $field = 'solicitudes', $add_where = array())
	{
		$work_order_rows = array();
		if ($get_count)
		{
			$column_name = $field . '_count';
			$this->db->select( 'product_group.name as group_name, COUNT(work_order.id) AS ' . $column_name . ', agents.id as agent_id' );
		}
		else
			$this->db->select( 'product_group.name as group_name, work_order.id AS work_order_id, agents.id as agent_id' );

		$this->db->from( 'work_order' );
		$this->db->join( 'product_group', 'product_group.id=work_order.product_group_id' );
		$this->db->join( 'work_order_types', 'work_order_types.id=work_order.work_order_type_id ' );
		$this->db->join( 'policies_vs_users', 'policies_vs_users.policy_id=work_order.policy_id' );
		$this->db->join( 'agents', 'agents.id=policies_vs_users.user_id' );

		if ($add_where)
			$this->db->where($add_where);
		$this->db->where( array(
			'creation_date >= ' => $values['begin'] . ' 00:00:00',
			'creation_date <= ' => $values['end']  . ' 23:59:59',
			));
		$this->db->where(
			'(((patent_id = 47) && (work_order.product_group_id = 1)) || ((patent_id = 90) && (work_order.product_group_id = 2)))'
		);
		if ($this->agent_name_where_in)
			$this->db->where_in('agents.id', $this->agent_name_where_in);
		if ($get_count)
			$this->db->group_by(array('agent_id', 'group_name'));
		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			if ($get_count)
			{
				foreach ($query->result() as $row)
				{
					if (!$agents_selected || isset($agents_selected[$row->agent_id]))
					{
						$group_name = strtoupper($row->group_name);
						$totals_work_order[$group_name . '_' . $field] += $row->{$column_name};
						$work_order_rows[$row->agent_id][$group_name] = array(
							$field => $row->{$column_name}); 
						$agents_with_activity[$row->agent_id] = $row->agent_id;
					}
				}
			}
			else
			{
				foreach ($query->result() as $row)
					$work_order_rows[] = $row;
			}
		}
		$query->free_result();
		return $work_order_rows;
	}

/*  Requests from table `payments` related to sales activity */
	public function get_payment_count($values, $agents_selected, &$totals_payments, &$agents_with_activity, $field = 'negocios', $add_where = array())
	{
		return $this->_get_payments(TRUE, $values, $agents_selected, $totals_payments, $agents_with_activity, $field, $add_where);
	}

	public function get_payment_list($values, $agents_selected, &$totals_payments, &$agents_with_activity, $field = 'negocios', $add_where = array())
	{
		return $this->_get_payments(FALSE, $values, $agents_selected, $totals_payments, $agents_with_activity, $field, $add_where);
	}

	private function _get_payments($get_count, $values, $agents_selected, &$totals_payments, &$agents_with_activity, $field = 'negocios', $add_where = array())
	{
		$payment_rows = array();
		if ($get_count)
			$this->db->select( 'SUM(business) AS sum_business, product_group.name as group_name, agents.id as agent_id' );
		else
			$this->db->select( 'payments.*, product_group.name as group_name, agents.id as agent_id, users.name as first_name, users.lastnames as last_name, users.company_name as company_name' );
		$this->db->from( 'payments' );
		$this->db->join( 'product_group', 'product_group.id=payments.product_group' );
		$this->db->join( 'agents', 'agents.id=payments.agent_id' );
		$this->db->join( 'users', 'users.id=agents.user_id' );		

		$this->db->where(array('valid_for_report' => '1', 'product_group.name' => 'Vida'));
		$this->db->where( "((business = '1') OR (business = '-1'))" );
		$this->db->where( array(
			'payments.payment_date >= ' => $values['begin'] . ' 00:00:00',
			'payments.payment_date <= ' => $values['end']  . ' 23:59:59'
			));
		if ($this->agent_name_where_in)
			$this->db->where_in('agents.id', $this->agent_name_where_in);
		if ($add_where)
			$this->db->where($add_where);

		if ($get_count)
			$this->db->group_by(array('agent_id', 'group_name'));
		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			if ($get_count)
			{
				foreach ($query->result() as $row)
				{
					if (!$agents_selected || isset($agents_selected[$row->agent_id]))
					{
						$group_name = strtoupper($row->group_name);
						$totals_payments[$group_name . '_' . $field] += $row->sum_business;
						$payment_rows[$row->agent_id][$group_name] = array(
							$field => $row->sum_business);
						$agents_with_activity[$row->agent_id] = $row->agent_id;
					}
				}
			}
			else
			{
				foreach ($query->result() as $row)
				{
					$row->asegurado = '';
					if ($row->policy_number) {
						$query_policy = $this->db->get_where('policies', array('uid' => $row->policy_number), 1, 0);
						if ($query_policy->num_rows() > 0)
							$row->asegurado = $query_policy->row()->name;
						$query_policy->free_result();
					}
					$payment_rows[] = $row;
				}
			}
		}
		$query->free_result();
		return $payment_rows;
	}
}
?>