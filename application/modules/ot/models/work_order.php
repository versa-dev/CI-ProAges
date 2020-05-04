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
class Work_order extends CI_Model{

	private $data = array();

	private $insertId;


	public function __construct(){

        parent::__construct();

    }


/*
 *	CRUD Functions, dynamic table.
 **/


// Add
	public function create( $table = '', $values = array() ){


		if( empty( $table ) or empty( $values ) ) return false;


		if( $this->db->insert( $table, $values ) ){

			$this->insertId = $this->db->insert_id();

			return true;

		}else

			return false;

    }

	public function replace( $table = '', $values = array() ){


		if( empty( $table ) or empty( $values ) ) return false;


		if( $this->db->replace( $table, $values ) ){

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
	 public function delete( $table = '', $id ){

		if( empty( $table ) or empty( $id ) ) return false;

			if( $this->db->delete( $table, array('id' => $id ) ) )

					return true;

			else

				return false;



    }





// Return insert id
	public function insert_id(){   return $this->insertId;  }










/**
 |	Getting for overview
 **/

/*	public function overview( $user = null, $start = 0, $limit = null ) {

		/*

			SELECT product_group.name as group_name, work_order_types.name as type_name, work_order_status.name as status_name, work_order.*
			FROM `work_order`
			JOIN product_group ON product_group.id=work_order.product_group_id
			JOIN work_order_types ON work_order_types.id=work_order.work_order_type_id
			JOIN work_order_status ON work_order_status.id=work_order.work_order_status_id;

		*/
/*
		$this->db->select( 'product_group.name as group_name, work_order_types.name as type_name, work_order_status.name as status_name, work_order.*' );
		$this->db->from( 'work_order' );
		$this->db->join( 'product_group', 'product_group.id=work_order.product_group_id' );
		$this->db->join( 'work_order_types', 'work_order_types.id=work_order.work_order_type_id ' );
		$this->db->join( 'work_order_status', 'work_order_status.id=work_order.work_order_status_id' );
		$this->db->where( 'work_order.work_order_status_id', 9 );
		$this->db->or_where( 'work_order.work_order_status_id', 5  );
		$this->db->or_where( 'work_order.work_order_status_id', 6  );
		$this->db->or_where( 'work_order.work_order_status_id', 7  );


		if( !empty( $user ) )
			$this->db->where( 'work_order.user', $user );

		if ( $start && !empty( $limit ) )
			$this->db->limit( $limit, $start );
		$query = $this->db->get();


		if ($query->num_rows() == 0) return false;

		$ot = array();

		foreach ($query->result() as $row) {

			$type_tramite = $this->getParentsWorkTipes( $row->work_order_type_id );

			$ot[] = array(
		    	'id' => $row->id,
				'uid' => $row->uid,
				'policy' => $this->getPolicyBuId( $row->policy_id ),
				'agents' => $this->getAgentsByPolicy( $row->policy_id ),
		    	'product_group_id' => $row->product_group_id,
				'group_name' => $row->group_name,
				'parent_type_name' => $this->getTypeTramiteId( $type_tramite ),
				'type_name' => $row->type_name,
		    	'work_order_status_id' => $row->work_order_status_id,
				'status_name' =>  $row->status_name,
				'creation_date' =>  $row->creation_date,
				'duration' =>  $row->duration,
				'last_updated' =>  $row->last_updated,
				'date' =>  $row->date
		    );

		}

		return $ot;


   }
*/



// Count records for pagination
	public function record_count( $user = null ) {

		$this->db->where_in('work_order_status_id', array('9', '5', '6', '7'));
		if( !empty( $user ) )
			$this->db->where('work_order.user', $user);
		return $this->db->from( 'work_order' )->count_all_results();
	}

// Last payment imported date
	public function getLastPaymentImportedDate($ramo, $requested = "vida"){
		if ($requested == "selo")
		{
			$sql = "SELECT DATE_FORMAT(last_update, '%Y-%m-%d') as date_selo FROM cache_updates WHERE type_update ='selo_date'";
			$query = $this->db->query($sql);
	        $res = $query->result_array();

	        return $res[0]['date_selo'];
		}
		else
		{
			$this->db->select_max("payment_date");
			$this->db->where('product_group', $ramo);
			if ($requested == "vida"){
				$where = 'allocated_prime is null or bonus_prime is null';
				$this->db->where($where);
			} else if ($requested == "selo"){
				$where = '(allocated_prime is not null or bonus_prime is not null)';
				$this->db->where($where);
			}
			$query = $this->db->get('payments');
			$row = $query->row_array();
			return !$row["payment_date"] ?"0000-00-00":$row["payment_date"];
		}
		
	}



// Notifications
	public function getNotification( $id = null ){

		/*

			SELECT product_group.name as group_name, work_order_types.name as type_name, work_order_status.name as status_name, work_order.*
			FROM `work_order`
			JOIN product_group ON product_group.id=work_order.product_group_id
			JOIN work_order_types ON work_order_types.id=work_order.work_order_type_id
			JOIN work_order_status ON work_order_status.id=work_order.work_order_status_id;

		*/

		$this->db->select( 'product_group.name as group_name, work_order_types.name as type_name, work_order_status.name as status_name, work_order.*' );
		$this->db->from( 'work_order' );
		$this->db->join( 'product_group', 'product_group.id=work_order.product_group_id' );
		$this->db->join( 'work_order_types', 'work_order_types.id=work_order.work_order_type_id ' );
		$this->db->join( 'work_order_status', 'work_order_status.id=work_order.work_order_status_id' );

		if( !empty(  $id ) )
			$this->db->where( 'work_order.id', $id );

		$this->db->order_by( 'work_order.id', 'desc' );



		$this->db->limit( 1 );

		$query = $this->db->get();


		if ($query->num_rows() == 0) return false;

		$ot = array();

		foreach ($query->result() as $row) {

			$type_tramite = $this->getParentsWorkTipes( $row->work_order_type_id );

			$ot[] = array(
		    	'id' => $row->id,
				'uid' => $row->uid,
				'work_order_status_id' => $row->work_order_status_id,
				'work_order_responsible_id' => $row->work_order_responsible_id,
				'work_order_reason_id' => $row->work_order_reason_id,
				'policy' => $this->getPolicyBuId( $row->policy_id ),
				'agents' => $this->getAgentsByPolicy( $row->policy_id ),
		    	'product_group_id' => $row->product_group_id,
				'group_name' => $row->group_name,
				'parent_type_name' => $this->getTypeTramiteId( $type_tramite ),
				'type_name' => $row->type_name,
		    	'status_name' =>  $row->status_name,
				'creation_date' =>  $row->creation_date,
				'duration' =>  $row->duration,
				'comments' => $row->comments,
				'last_updated' =>  $row->last_updated,
				'date' =>  $row->date,
				'notes' => $row->notes
		    );

		}

		return $ot;

	}


	public function getResponsiblesById( $id = null ){

		if( empty( $id ) ) return false;

		$this->db->where( 'id', $id );

		$query = $this->db->get( 'work_order_responsibles' );

		if ($query->num_rows() == 0) return false;


		$responsibles = array();

		foreach ($query->result() as $row) {

			$responsibles[] = array(
		    	'id' => $row->id,
				'name' => $row->name,
				'last_updated' =>  $row->last_updated,
				'date' =>  $row->date
		    );

		}

		return $responsibles;
	}

	public function getReasonById( $id = null ){

		if( empty( $id ) ) return false;

		$this->db->where( 'id', $id );

		$query = $this->db->get( 'work_order_reason' );

		if ($query->num_rows() == 0) return false;


		$reason = array();

		foreach ($query->result() as $row) {

			$reason[] = array(
		    	'id' => $row->id,
				'name' => $row->name,
				'last_updated' =>  $row->last_updated,
				'date' =>  $row->date
		    );

		}

		return $reason;
	}








/**
 *	Getting for update
 **/
	public function getById( $ot = null ){

		if( empty( $ot ) )return false;


		$this->db->where( 'id', $ot );
		$this->db->limit( 1 );

		$query  = $this->db->get( 'work_order' );


		if ($query->num_rows() == 0) return false;

		$ot = array();

		foreach ($query->result() as $row) {

			$ot[] = array(
		    	'id' => $row->id,
				'product_group_id' => $row->product_group_id,
				'policy_id' => $row->policy_id ,
				'subtype' => $row->work_order_type_id,
				'type' => $this->getParentsWorkTipes( $row->work_order_type_id ),
		    	'uid' => $row->uid,
				'creation_date' => date( 'Y-m-d', strtotime($row->creation_date) ),
				'comments' => $row->comments
		    );

		}

		return $ot;


	}


	public function getParentsWorkTipes( $type = null ){

		if( empty( $type ) )return false;

		//SELECT patent_id FROM `work_order_types` WHERE id=61;
		$this->db->select( 'patent_id' );
		$this->db->where( 'id', $type );
		$this->db->limit( 1 );

		$query  = $this->db->get( 'work_order_types' );


		if ($query->num_rows() == 0) return false;

		$type = array();

		foreach ($query->result() as $row) {

			$type[] = array(
		    	'type' => $row->patent_id
		    );

		}

		return $type[0]['type'];

	}

	// OTs filtered
	public function find_new( $filter, $access_all = FALSE  )
	{
		$agentes_gerentes = array();
		// Gerentes (prepare the agents who have selected gerente)
		if  ( isset($filter['gerente']) && ( $gerente = $filter['gerente'] ) )
		{
			$this->db->select( 'agents.id' );
			$this->db->from( 'agents' );
			$this->db->join('users', 'users.id = agents.user_id');
			$this->db->join( 'users_vs_user_roles', 'users_vs_user_roles.user_id=users.id '  );
			$this->db->where( 'manager_id', (int) $gerente );
			$this->db->where( 'users_vs_user_roles.user_role_id', 1 );
			$query = $this->db->get();
			foreach ($query->result() as $row)
				$agentes_gerentes[] = $row->id;
			if (count($agentes_gerentes) == 0)
				return FALSE;
		}

		// Prepare work order type for filter on patent id (this should be validated against ramo filter field)
		$patent_work_order_types = array();
		if  ( isset($filter['patent_type']) && ( $patent_type = $filter['patent_type'] ) )
		{
			$this->db->select( 'work_order_types.id' );
			$this->db->from( 'work_order_types' );
			$this->db->where( 'patent_id', (int)$patent_type );

			$query = $this->db->get();
			foreach ($query->result() as $row)
				$patent_work_order_types[] = $row->id;
		}

		$this->db->select( 'policies.name as asegurado, policies.prima as policy_prima, policies.uid as poliza_number, work_order_types.patent_id as tramite_type, product_group.name as group_name, work_order_types.name as type_name, work_order_status.name as status_name, work_order.*' );
		$this->db->from( 'work_order' );
		$this->db->join( 'product_group', 'product_group.id=work_order.product_group_id' );
		$this->db->join( 'work_order_types', 'work_order_types.id=work_order.work_order_type_id ' );
		$this->db->join( 'work_order_status', 'work_order_status.id=work_order.work_order_status_id' );
		$this->db->join( 'policies', 'policies.id=work_order.policy_id' );

		if (isset($filter['work_order_status_id']))
		{
			switch ($filter['work_order_status_id'])
			{
				case 'activadas':
					$this->db->where( 'work_order.work_order_status_id', 6 );
					break;
				case 'canceladas':
					$this->db->where( 'work_order.work_order_status_id', 2 );
					break;
				case 'tramite':
					$this->db->where_in('work_order.work_order_status_id', array('5', '9'));
					break;
				case 'terminada':
					$this->db->where_in('work_order.work_order_status_id', array('7', '6'));
					break;
				case 'NTU':
					$this->db->where( 'work_order.work_order_status_id', 10 );
					break;
				case 'pagada':
					$this->db->where( 'work_order.work_order_status_id', 4 );
					break;
				default:
					break;
			}
		}

		if (isset($filter['coordinators']) )
		{
			if (is_array($filter['coordinators']))
				$this->db->where_in( 'work_order.user', $filter['coordinators'] );
			elseif ($filter['coordinators'])
				$this->db->where_in( 'work_order.user', explode('_', $filter['coordinators'] ));
		}
		elseif ( !$access_all || (isset($filter['user']) && ( $filter['user'] == 'mios' ) ))
			$this->db->where( array( 'work_order.user' => $this->sessions['id'] ) );

		// OT id
		if  ( isset($filter['id']) && ( $id = $filter['id'] ) )
			$this->db->where( array( 'work_order.uid' => trim( $id )) );

		// Ramo
		if  ( isset($filter['ramo']) && ( $ramo = $filter['ramo'] ) &&
			( ( $ramo == 1 ) || (  $ramo == 2 ) || ( $ramo == 3 ) ) )
			$this->db->where( array( 'work_order.product_group_id' => $ramo ) );

		// Complete handling filter on patent id (tipo  de tramite)
		if ( count($patent_work_order_types) ) {
			$this->db->where_in('work_order.work_order_type_id', $patent_work_order_types);
		}

		// Periodo
		if  ( isset($filter['periodo']) && ( $periodo = $filter['periodo'] ) &&
			( ( $periodo == 1 ) || (  $periodo == 2 ) || ( $periodo == 3 ) || ( $periodo == 4) ) )
		{
			if( $periodo == 1 ) // Month
				$this->db->where(  array(
					'work_order.creation_date >= ' => date( 'Y' ) . '-' . (date( 'm' )) . '-01',
					'work_order.creation_date < ' => date('Y-m', mktime(0, 0, 0, date('m') + 1, date('d'), date('Y'))) . '-01'));
			if( $periodo == 2 ) // Trimester or cuatrimester depending ramo
			{
				$this->load->helper('tri_cuatrimester');
				if( ($ramo == 2 ) || ( $ramo == 3 ) )
					$begin_end = get_tri_cuatrimester( cuatrimestre(), 'cuatrimestre' ) ;
				else
					$begin_end = get_tri_cuatrimester( trimestre(), 'trimestre' );

				if (isset($begin_end) && isset($begin_end['begind']) && isset($begin_end['end']))
					$this->db->where( array(
						'work_order.creation_date >= ' => $begin_end['begind'],
						'work_order.creation_date <=' =>  $begin_end['end']) );
			}
			if(  $periodo == 3 ) // Year
				$this->db->where( array(
					'work_order.creation_date >= ' => date( 'Y' ) .'-01-01',
					'work_order.creation_date <=' => date( 'Y-m-d' ) . ' 23:59:59') );

			if( $periodo == 4 ) // Custom
			{
				$from = $this->custom_period_from;
				$to = $this->custom_period_to;
				if ( ( $from === FALSE ) || ( $to === FALSE ) )
				{
					$from = date('Y-m-d');
					$to = $from;
				}
				$this->db->where( array(
					'work_order.creation_date >= ' => $from . ' 00:00:00',
					'work_order.creation_date <=' => $to . ' 23:59:59') );
			}
		}

		// Agent
		if  ( isset($filter['agent']) && ( $agent = $filter['agent'] ) )
		{
			/**
			 JOIN policies_vs_users ON policies_vs_users.policy_id=work_order.policy_id
			 WHERE policies_vs_users.user_id=1
			*/
			$this->db->join( 'policies_vs_users AS policies_users_A', 'policies_users_A.policy_id=work_order.policy_id' );
			$this->db->where( 'policies_users_A.user_id', (int) $agent );

		}
		// Complete Gerente filtering
		if ( count($agentes_gerentes) ) {
			$this->db->join( 'policies_vs_users AS policies_users_B', 'policies_users_B.policy_id=work_order.policy_id' );
			$this->db->where_in( 'policies_users_B.user_id', $agentes_gerentes );
		}
		execute_filters("find-new-1");
		$query = $this->db->get();
		if ($query->num_rows() == 0) return false;

		$ot = array();
		$tramite_types = array();
		$policy_ids = array();
		foreach ($query->result_array() as $row)
		{
			$ot[$row['id']] = $row;
			$policy_ids[$row['id']] = $row['policy_id'];
			if (!isset($tramite_types[$row['tramite_type']]))
				$tramite_types[$row['tramite_type']] = $row['tramite_type'];
		}
		$query->free_result();

		$query_parent_type_name = $this->db
			->select('id, name')
			->from('work_order_types')
			->where_in('id', array_values($tramite_types))
			->get();
		$tramite_names = array();
		foreach ($query_parent_type_name->result() as $row)
		{
			$tramite_names[$row->id] = $row->name;
		}
		$query_parent_type_name->free_result();

		$agents = array();
		$this->db->select( ' policies_vs_users.policy_id as id_of_policy, policies_vs_users.percentage, policies_vs_users.user_id AS agent_id, users.name, users.lastnames, users.company_name, users.email, users.id as user_id, users.manager_id ' )
			->from( 'policies_vs_users' )
			->join( 'agents', 'agents.id=policies_vs_users.user_id' )
			->join( 'users', 'users.id=agents.user_id ' )
			->where_in('policies_vs_users.policy_id', array_values($policy_ids));
		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				if (!isset($agents[$row->id_of_policy]))
					$agents[$row->id_of_policy] = array();
				$agents[$row->id_of_policy][] = array(
					'agent_id' => $row->agent_id,
					'percentage' => $row->percentage,
					'name' => $row->name,
					'lastnames' => $row->lastnames,
					'company_name' => $row->company_name,
					'email' => $row->email,
					'user_id' => $row->user_id,
					'manager_id' => $row->manager_id,
				);
			}
		}
		foreach ($ot as $key => $row)
		{
			$ot_agents = (isset($agents[$row['policy_id']])) ? $agents[$row['policy_id']] : array();
			$ot[$key] = array_merge($row, array(
				'agents' => $ot_agents,
				'parent_type_name' => $tramite_names[$row['tramite_type']],
				'is_editable' => $this->is_editable( $row['product_group_id'], $row['tramite_type'], $row['work_order_status_id'] ),
				'is_nuevo_negocio' => $this->is_nuevo_negocio( $row['product_group_id'], $row['tramite_type'])));
		}
		return $ot;
   }

// Getting for filters
	public function find( $access_all = false ) {

		$agentes_gerentes = array();
		// Gerentes (prepare the agents who have selected gerente)
		if  ( ( ( $gerente = $this->input->post('gerente') ) !== FALSE ) &&
			strlen($gerente) ){
			$this->db->select( 'agents.id' );
			$this->db->from( 'agents' );
			$this->db->join('users', 'users.id = agents.user_id');
			$this->db->join( 'users_vs_user_roles', 'users_vs_user_roles.user_id=users.id '  );
			$this->db->where( 'manager_id', (int) $gerente );
			$this->db->where( 'users_vs_user_roles.user_role_id', 1 );
			$query = $this->db->get();
			foreach ($query->result() as $row)
				$agentes_gerentes[] = $row->id;
		}

		// Prepare work order type for filter on patent id (this should be validated against ramo filter field)
		$patent_work_order_types = array();
		if  ( ( ( $patent_type = $this->input->post('patent_type') ) !== FALSE ) &&
			strlen($patent_type) ){
			$this->db->select( 'work_order_types.id' );
			$this->db->from( 'work_order_types' );
			$this->db->where( 'patent_id', (int)$patent_type );
			$query = $this->db->get();
			foreach ($query->result() as $row)
				$patent_work_order_types[] = $row->id;
		}

		$this->db->select( 'policies.name as asegurado, policies.prima as policy_prima, work_order_types.patent_id as tramite_type, product_group.name as group_name, work_order_types.name as type_name, work_order_status.name as status_name, work_order.*' );
		$this->db->from( 'work_order' );
		$this->db->join( 'product_group', 'product_group.id=work_order.product_group_id' );
		$this->db->join( 'work_order_types', 'work_order_types.id=work_order.work_order_type_id ' );
		$this->db->join( 'work_order_status', 'work_order_status.id=work_order.work_order_status_id' );
		$this->db->join( 'policies', 'policies.id=work_order.policy_id' );

		switch ($this->input->post('work_order_status_id'))
		{
			case 'activadas':
				$this->db->where( 'work_order.work_order_status_id', 6 );
				break;
			case 'canceladas':
				$this->db->where( 'work_order.work_order_status_id', 2 );
				break;
			case 'tramite':
				$this->db->where_in('work_order.work_order_status_id', array('5', '9'));
				break;
			case 'terminada':
				$this->db->where_in('work_order.work_order_status_id', array('7', '6'));
				break;
			case 'NTU':
				$this->db->where( 'work_order.work_order_status_id', 10 );
				break;
			case 'pagada':
				$this->db->where( 'work_order.work_order_status_id', 4 );
				break;
			default:
				break;
		}

		if ( !$access_all || ( $this->input->post('user') == 'mios' ) )
			$this->db->where( array( 'work_order.user' => $this->sessions['id'] ) );

		// OT id
		if ( ( ( $id = $this->input->post('id') ) !== FALSE ) &&
			strlen($id) )
			$this->db->where( array( 'work_order.uid' => trim( $id )) );

		// Ramo
		if ( ( ( $ramo = $this->input->post('ramo') ) !== FALSE ) &&
			( ( $ramo == 1 ) || (  $ramo == 2 ) || ( $ramo == 3 ) ) )
			$this->db->where( array( 'work_order.product_group_id' => $ramo ) );

		// Complete handling filter on patent id (tipo  de tramite)
		if ( count($patent_work_order_types) ) {
			$this->db->where_in('work_order.work_order_type_id', $patent_work_order_types);
		}

		// Periodo
		if ( ( ( $periodo = $this->input->post('periodo') ) !== FALSE ) &&
			( ( $periodo == 1 ) || (  $periodo == 2 ) || ( $periodo == 3 ) || ( $periodo == 4) ) )
		{
			if( $periodo == 1 ) // Month
				$this->db->where(  array(
					'work_order.creation_date >= ' => date( 'Y' ) . '-' . (date( 'm' )) . '-01',
					'work_order.creation_date < ' => date('Y-m', mktime(0, 0, 0, date('m') + 1, date('d'), date('Y'))) . '-01'));
			if( $periodo == 2 ) // Trimester or cuatrimester depending ramo
			{
				$this->load->helper('tri_cuatrimester');
				if( ($ramo == 2 ) || ( $ramo == 3 ) )
					$begin_end = get_tri_cuatrimester( cuatrimestre(), 'cuatrimestre' ) ;
				else
					$begin_end = get_tri_cuatrimester( trimestre(), 'trimestre' );

				if (isset($begin_end) && isset($begin_end['begind']) && isset($begin_end['end']))
					$this->db->where( array(
						'work_order.creation_date >= ' => $begin_end['begind'],
						'work_order.creation_date <=' =>  $begin_end['end']) );
			}
			if(  $periodo == 3 ) // Year
				$this->db->where( array(
					'work_order.creation_date >= ' => date( 'Y' ) .'-01-01',
					'work_order.creation_date <=' => date( 'Y-m-d' ) . ' 23:59:59') );

			if( $periodo == 4 ) // Custom
			{
				$from = $this->custom_period_from;
				$to = $this->custom_period_to;
				if ( ( $from === FALSE ) || ( $to === FALSE ) )
				{
					$from = date('Y-m-d');
					$to = $from;
				}
				$this->db->where( array(
					'work_order.creation_date >= ' => $from . ' 00:00:00',
					'work_order.creation_date <=' => $to . ' 23:59:59') );
			}
		}

		// Agent
		if  ( ( ( $agent = $this->input->post('agent') ) !== FALSE ) &&
			strlen($agent) ){
			/**
			 JOIN policies_vs_users ON policies_vs_users.policy_id=work_order.policy_id
			 WHERE policies_vs_users.user_id=1
			*/
			$this->db->join( 'policies_vs_users AS policies_users_A', 'policies_users_A.policy_id=work_order.policy_id' );
			$this->db->where( 'policies_users_A.user_id', (int) $agent );

		}
		// Complete Gerente filtering
		if ( count($agentes_gerentes) ) {
			$this->db->join( 'policies_vs_users AS policies_users_B', 'policies_users_B.policy_id=work_order.policy_id' );
			$this->db->where_in( 'policies_users_B.user_id', $agentes_gerentes );
		}

		$query = $this->db->get();

		if ($query->num_rows() == 0) return false;

		$ot = array();
		$tramite_types = array();
		$policy_ids = array();
		foreach ($query->result_array() as $row)
		{
			$ot[$row['id']] = $row;
			$policy_ids[$row['id']] = $row['policy_id'];
			if (!isset($tramite_types[$row['tramite_type']]))
				$tramite_types[$row['tramite_type']] = $row['tramite_type'];
		}
		$query->free_result();

		$query_parent_type_name = $this->db
			->select('id, name')
			->from('work_order_types')
			->where_in('id', array_values($tramite_types))
			->get();
		$tramite_names = array();
		foreach ($query_parent_type_name->result() as $row)
		{
			$tramite_names[$row->id] = $row->name;
		}
		$query_parent_type_name->free_result();

		$agents = array();
		$this->db->select( ' policies_vs_users.policy_id as id_of_policy, policies_vs_users.percentage, policies_vs_users.user_id AS agent_id, users.name, users.lastnames, users.company_name, users.email, users.id as user_id ' )
			->from( 'policies_vs_users' )
			->join( 'agents', 'agents.id=policies_vs_users.user_id' )
			->join( 'users', 'users.id=agents.user_id ' )
			->where_in('policies_vs_users.policy_id', array_values($policy_ids));
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				if (!isset($agents[$row->id_of_policy]))
					$agents[$row->id_of_policy] = array();
				$agents[$row->id_of_policy][] = array(
					'agent_id' => $row->agent_id,
					'percentage' => $row->percentage,
					'name' => $row->name,
					'lastnames' => $row->lastnames,
					'company_name' => $row->company_name,
					'email' => $row->email,
					'user_id' => $row->user_id,
				);
			}
		}
		foreach ($ot as $key => $row)
		{
			$ot_agents = (isset($agents[$row['policy_id']])) ? $agents[$row['policy_id']] : array();
			$ot[$key] = array_merge($row, array(
				'agents' => $ot_agents,
				'parent_type_name' => $tramite_names[$row['tramite_type']],
				'is_editable' => $this->is_editable( $row['product_group_id'], $row['tramite_type'], $row['work_order_status_id'] ),
				'is_nuevo_negocio' => $this->is_nuevo_negocio( $row['product_group_id'], $row['tramite_type'])));
		}
		return $ot;
   }



   public function getWorkOrderById( $id = null ){

		if( empty( $id ) ) return false;



		$this->db->select( 'product_group.name as group_name, work_order_types.name as type_name, work_order_status.name as status_name, work_order.*' );
		$this->db->from( 'work_order' );
		$this->db->join( 'product_group', 'product_group.id=work_order.product_group_id' );
		$this->db->join( 'work_order_types', 'work_order_types.id=work_order.work_order_type_id ' );
		$this->db->join( 'work_order_status', 'work_order_status.id=work_order.work_order_status_id' );
		$this->db->where( 'work_order.id', $id );
		$this->db->limit(1);


		$query = $this->db->get();

		if ($query->num_rows() == 0) return false;

		$ot = array();

		foreach ($query->result() as $row) {

			$type_tramite = $this->getParentsWorkTipes( $row->work_order_type_id );

			$ot[] = array(
		    	'id' => $row->id,
				'uid' => $row->uid,
				'policy_id' => $row->policy_id,
				'policy' => $this->getPolicyBuId( $row->policy_id ),
				'agents' => $this->getAgentsByPolicy( $row->policy_id ),
		    	'product_group_id' => $row->product_group_id,
				'group_name' => $row->group_name,
				'parent_type_name' => $this->getTypeTramiteId( $type_tramite ),
				'type_id' => $row->work_order_type_id,
				'type_name' => $row->type_name,
				'status_id' => $row->work_order_status_id,
		    	'status_name' =>  $row->status_name,
				'creation_date' =>  $row->creation_date,
				'comments' => $row->comments,
				'duration' =>  $row->duration,
				'last_updated' =>  $row->last_updated,
				'notes' =>  $row->notes,
				'date' =>  $row->date,
		    );

		}


		return $ot;

   }

   public function getWorkOrderByPolicy( $policy = null ){

		if( empty( $policy ) ) return false;


		$this->db->where( 'policy_id', $policy );
		$this->db->limit(1);


		$query = $this->db->get( 'work_order' );

		$ot = array();

		if ($query->num_rows() == 0) return false;


		foreach ($query->result() as $row) {

			$type_tramite = $this->getParentsWorkTipes( $row->work_order_type_id );

			$ot[] = array(
		    	'id' => $row->id,
				'uid' => $row->uid,
				'policy_id' => $row->policy_id,
				'policy' => $this->getPolicyBuId( $row->policy_id ),
				'agents' => $this->getAgentsByPolicy( $row->policy_id ),
		    	'product_group_id' => $row->product_group_id,
				'parent_type_name' => $this->getTypeTramiteId( $type_tramite ),
				'creation_date' =>  $row->creation_date,
				'duration' =>  $row->duration,
				'last_updated' =>  $row->last_updated,
				'date' =>  $row->date
		    );

		}


		return $ot;

   }

   public function getWorkOrdersGroupBy($filter, $group = array()){
   		//Default fields
   		if(empty($group)){
	   		$this->db->select('work_order.*');
	   		$this->db->select('product_group.name ramo');
	   		$this->db->select('policies.name asegurado, policies.prima, policies.uid poliza');
	   		$this->db->select('users.name, users.lastnames, users.company_name, policies_vs_users.percentage');
	   		$this->db->select('agents.id agent_id, connection_date');
	   		$this->db->select('work_order_patent.name tipo_tramite');
	   		$this->db->select('products.name producto');
	   		$this->db->select('work_order_status.name status');
	   		$this->db->order_by('users.lastnames asc, users.name asc');
   		}
   		else{
   			$this->db->select($group["select"]);
   			$this->db->select('count(*) as conteo');
   			if(isset($group["sum"]))
   				$this->db->select_sum($group["sum"]);

   			$this->db->order_by($group["order"]);
   			$this->db->group_by($group["by"]);
   		}
   		$this->db->from('work_order');
   		$this->db->join('policies', 'work_order.policy_id = policies.id');
   		$this->db->join('product_group', 'work_order.product_group_id = product_group.id');
   		$this->db->join('policies_vs_users', 'policies_vs_users.policy_id = policies.id');
   		$this->db->join('agents', 'policies_vs_users.user_id = agents.id');
   		$this->db->join('users', 'agents.user_id = users.id');
   		$this->db->join('work_order_status', 'work_order_status.id = work_order.work_order_status_id');
   		$this->db->join('work_order_types', 'work_order_types.id = work_order.work_order_type_id');
   		$this->db->join('work_order_types as work_order_patent', 'work_order_types.patent_id = work_order_patent.id');
   		$this->db->join('products', 'products.id = policies.product_id', 'right');

   		//Remove NTU, Excedido and Canceladas
   		$this->db->where_not_in("work_order_status.id", array(2, 3, 10));

   		if(isset($filter["nuevos_negocios"]))
   			$this->db->where_in('work_order_types.patent_id', array(47, 90));

		if(isset($filter["gerente"]) && !empty($filter["gerente"]))
		   $this->db->where('users.manager_id', $filter["gerente"]);

   		$ramo = $filter["ramo"];
   		$periodo = (int) $filter["periodo"];
   		$producto = $filter["product"];
   		$agente = $filter["agent"];
   		// Ramo
		if ($ramo == 1 || $ramo == 2)
			$this->db->where('work_order.product_group_id', $ramo);

		// Producto
		if ($producto > 0)
			$this->db->where('products.id', $producto);

		if ($agente > 0)
			$this->db->where('agents.id', $agente);

		if(isset($filter["status"]) && $filter["status"] != "")
			$this->db->where('work_order_status.name', $filter["status"]);

		// Periodo
		if (is_int($periodo) && $periodo >= 1 && $periodo <= 4)
		{
			if( $periodo == 1 ) // Month
				$this->db->where(  array(
					'work_order.creation_date >= ' => date( 'Y' ) . '-' . (date( 'm' )) . '-01',
					'work_order.creation_date < ' => date('Y-m', mktime(0, 0, 0, date('m') + 1, date('d'), date('Y'))) . '-01'));
			if( $periodo == 2 ) // Trimester or cuatrimester depending ramo
			{
				$this->db->where( array(
					'work_order.creation_date >= ' => $this->custom_period_from,
					'work_order.creation_date <=' =>  $this->custom_period_to )
				);
			}
			if(  $periodo == 3 ) // Year
				$this->db->where( array(
					'work_order.creation_date >= ' => date( 'Y' ) .'-01-01',
					'work_order.creation_date <=' => date( 'Y-m-d' ) . ' 23:59:59') );

			if( $periodo == 4 ) // Custom
			{
				$from = $this->custom_period_from;
				$to = $this->custom_period_to;
				if ( ( $from === FALSE ) || ( $to === FALSE ) )
				{
					$from = date('Y-m-d');
					$to = $from;
				}
				$this->db->where( array(
					'work_order.creation_date >= ' => $from . ' 00:00:00',
					'work_order.creation_date <=' => $to . ' 23:59:59') );
			}
		}
		if(isset($filter["where"]))
			$this->db->where($filter["where"]);

		execute_filters("work-orders-get-group-by");
		$query = $this->db->get();
		return $query->result_array();
   }

   public function getWorkOrdersGroupByAgents($filter, $orderby){
   		//Configuration agent group
		$args = array(
			"select" => "users.company_name, users.name, users.lastnames, policies_vs_users.percentage, agents.id",
			"sum" => "policies.prima",
			"by" => "users.company_name, agents.id, policies_vs_users.percentage",
			"order" => "$orderby desc",
		);
		return $this->getWorkOrdersGroupBy($filter, $args);
   }

   public function getWorkOrdersGroupByStatus($filter){
   		//Configuration status group
		$args = array(
			"select" => "work_order_status.name status",
			"sum" => "policies.prima",
			"by" => "work_order_status.name",
			"order" => "conteo desc",
		);
		return $this->getWorkOrdersGroupBy($filter, $args);
   }

   public function getWorkOrdersGroupByProducts($filter){
		//Configuration products group
		$args = array(
			"select" => "products.name producto, products.id",
			"sum" => "policies.prima",
			"by" => "products.id",
			"order" => "conteo desc",
		);
		return $this->getWorkOrdersGroupBy($filter, $args);
   }

   public function getWorkOrdersGroupByGeneracion($filter){
   		$this->load->helper('agent/generations');
   		$this->load->model('usuarios/user');
   		//work_order_status = Pagadas
   		//$filter["where"] = array("work_order_status.id" => 4);

   		$work_orders = $this->getWorkOrdersGroupBy($filter);

   		//Declare aditional parameters for the generations array
   		$params = array("primas", "solicitudes");
   		//Create generations array
   		$generaciones = getGenerationList($params);

   		foreach ($work_orders as $order) {
   			//get generation index of this order
   			$generation = ($order["ramo"] == 1) ? $this->user->getGenerationByAgentId($order["agent_id"]) : $this->user->getGenerationByAgentId($order["agent_id"], false);
   			if ($generation == "Generación 1") {
   				$generaciones["generacion_1"]["primas"] += $order["prima"];
   				$generaciones["generacion_1"]["solicitudes"]++;
   			}elseif ($generation == "Generación 2") {
   				$generaciones["generacion_2"]["primas"] += $order["prima"];
   				$generaciones["generacion_2"]["solicitudes"]++;
   			}elseif ($generation == "Generación 3") {
   				$generaciones["generacion_3"]["primas"] += $order["prima"];
   				$generaciones["generacion_3"]["solicitudes"]++;
   			}elseif ($generation == "Generación 4") {
   				$generaciones["generacion_4"]["primas"] += $order["prima"];
   				$generaciones["generacion_4"]["solicitudes"]++;
   			}elseif ($generation == "Consolidado") {
   				$generaciones["consolidado"]["primas"] += $order["prima"];
   				$generaciones["consolidado"]["solicitudes"]++;
   			}
   		}
   		return array_values($generaciones);
   }


/**
 *	Functions Policies
 **/

	// Get Policy by Id
	public function getPolicyBuId( $id = null ){

		if( empty( $id ) ) return false;
		/*

			SELECT *
			FROM policies
			WHERE id=1


		*/
		$this->db->select( 'policies.*, payment_methods.name as payment_method_name, payment_intervals.name as payment_intervals_name' );
		$this->db->join( 'payment_methods', 'payment_methods.id=policies.payment_method_id' );
		$this->db->join( 'payment_intervals', 'payment_intervals.id=policies.payment_interval_id' );
		$this->db->where( 'policies.id', $id );
		$this->db->limit(1);
		$query = $this->db->get('policies');


		if ($query->num_rows() == 0) {


			$this->db->select();
			$this->db->where( 'policies.id', $id );
			$this->db->limit(1);
			$query = $this->db->get('policies');



			if ($query->num_rows() == 0) return false;


		}

		$policy = array();

		foreach ($query->result() as $row) {

			$payment_intervals_name='';
			$payment_method_name='';
			if( isset( $row->payment_intervals_name ) )
				$payment_intervals_name=$row->payment_intervals_name;
			if( isset( $row->payment_method_name ) )
				$payment_method_name=$row->payment_method_name;

			$policy[] = array(
		    	'id' => $row->id,
		    	'product_id' => $row->product_id,
				'currency_id' => $row->currency_id,
				'payment_interval_id' => $row->payment_interval_id,
				'payment_intervals_name' => $payment_intervals_name,
		    	'payment_method_id' =>  $row->payment_method_id,
				'payment_method_name' => $payment_method_name,
				'prima' => $row->prima,
				'prima_entered' => $row->prima_entered,
				'allocated_prime' => $row->allocated_prime,
				'bonus_prime' => $row->bonus_prime,
				'name' =>  $row->name,
				'uid' =>  $row->uid,
				'lastname_father' =>  $row->lastname_father,
				'lastname_mother' =>  $row->lastname_mother,
				'year_premium' =>  $row->year_premium,
				'period' => $row->period,
				'expired_date' =>  $row->expired_date,
				'products' => $this->getProductsByPolicy( $row->product_id ),
				'last_updated' =>  $row->last_updated,
				'date' =>  $row->date
		    );

		}

		return $policy;

	}

	public function getProductsByPolicy( $policy = null ){

		if( empty( $policy ) ) return false;

		$this->db->where( 'id', $policy );
		$this->db->limit(1);
		$query = $this->db->get('products');

		if ($query->num_rows() == 0) return false;

		$products = array();

		foreach ($query->result() as $row) {

			$products[] = array(
		    	'id' => $row->id,
		    	'platform_id' => $row->platform_id,
				'product_group_id' => $row->product_group_id,
				'name' => $row->name,
		    	'period' =>  $row->period,
				'active' =>  $row->active,
				'last_updated' =>  $row->last_updated,
				'date' =>  $row->date
		    );

		}

		return $products;

	}


	public function getAgentsByPolicy( $policy = null ){

		if( empty( $policy ) ) return false;
		/*
		SELECT policies_vs_users.percentage, users.name, users.lastnames
		FROM policies_vs_users
		JOIN agents ON agents.id=policies_vs_users.user_id
		JOIN users ON users.id=agents.user_id
		WHERE policy_id=1
		*/
		$this->db->select( ' policies_vs_users.percentage, policies_vs_users.user_id AS agent_id, users.name, users.lastnames, users.company_name, users.email, users.id as user_id ' );
		$this->db->from( 'policies_vs_users' );
		$this->db->join( 'agents', 'agents.id=policies_vs_users.user_id' );
		$this->db->join( 'users', 'users.id=agents.user_id ' );
		$this->db->where( 'policies_vs_users.policy_id', $policy );
		$query = $this->db->get();

		if ($query->num_rows() == 0) return false;

		$agents = array();

		foreach ($query->result() as $row) {

			$agents[] = array(
				'agent_id' => $row->agent_id,
		    	'percentage' => $row->percentage,
				'name' => $row->name,
				'lastnames' => $row->lastnames,
				'company_name' => $row->company_name,
				'email' => $row->email,
				'user_id' => $row->user_id,
		    );

		}

		return $agents;
	}


// Getting policy by uid
	public function getPolicyByUid( $uid = null, $strict = true)
	{
		if( empty( $uid ) )
			return false;
		/*	SELECT id
			FROM policies
			WHERE uid='';
		*/
		$this->db->select( 'id, currency_id, prima, prima_entered,payment_interval_id' );
		if ($strict)
			$this->db->where( 'policies.uid', $uid );
		else
			$this->db->where("`policies`.`uid` REGEXP '0*$uid' ", NULL, FALSE);
		$this->db->limit(1);
		$query = $this->db->get('policies');
		if ($query->num_rows() == 0)
			return false;

		$policy = array();
		foreach ($query->result() as $row) {
			$policy[] = array(
		    	'id' => $row->id,
		    	'currency_id' => $row->currency_id,
				'prima' => $row->prima,
				'prima_entered' => $row->prima_entered,
				'payment_interval_id'=>$row->payment_interval_id,
		    );
		}
		return $policy;
	}

	public function getByPolicyUid( $policy_uid = null ){

		if( empty( $policy_uid ) ) return false;
		/*
		SELECT work_order.id, work_order.uid,  policies.name
		FROM work_order
   		JOIN policies ON policies.id=work_order.policy_id
		WHERE policies.uid*/
		$this->db->select( 'work_order.id, work_order.uid,  policies.name' );
		$this->db->from( 'work_order' );
		$this->db->join( 'policies', 'policies.id=work_order.policy_id' );
		$this->db->where( 'policies.uid', $policy_uid );

		$query = $this->db->get();

		if ($query->num_rows() == 0) return false;


		foreach ($query->result() as $row)

			return $row->uid.' - '.$row->name;


	}


/**
 *	Ajax Requests, getting data
 **/





// Getting typetramite for ramo
	public function getTypeTramite( $ramo = null ){


		if( empty( $ramo ) ) return false;


		// SELECT * FROM `work_order_types` WHERE patent_id=1 and duration=0;

		$options = '<option value="">Seleccione</option>';

		$this->db->where( array( 'patent_id' => $ramo, 'duration' => 0 ) );

		$query = $this->db->get( 'work_order_types' );


		if ($query->num_rows() == 0) return $options;


		foreach ($query->result() as $row)

			$options .= '<option value="'.$row->id.'">'.$row->name.'</option>';


		return $options;

	}


	public function getTypeTramiteId( $id = null ){


		if( empty( $id ) ) return false;


		// SELECT * FROM `work_order_types` WHERE patent_id=1 and duration=0;

		$this->db->where( array( 'id' => $id ) );
		$this->db->limit(1);

		$query = $this->db->get( 'work_order_types' );


		if ($query->num_rows() == 0) return $options;

		$type = array();

		foreach ($query->result() as $row)

			$type[] = array( 'id' => $row->id,  'name' => $row->name );


		return $type[0];

	}

// Getting getSubType for type
	public function getSubType( $type = null ){


		if( empty( $type ) ) return false;


		// SELECT * FROM `work_order_types` WHERE patent_id=1 and duration!=0;

		$options = '<option value="">Seleccione</option>';

		$this->db->where( array( 'patent_id' => $type, 'duration !=' => 0 ) );

		$query = $this->db->get( 'work_order_types' );


		if ($query->num_rows() == 0) return $options;


		foreach ($query->result() as $row)

			$options .= '<option value="'.$row->id.'">'.$row->name.'</option>';


		return $options;

	}

	public function getPeriod( $product = null, $as_string = TRUE ){

		$this->db->select( 'period' );

		if( !empty( $product ) )
			$this->db->where( array( 'id' => $product ) );

		$this->db->limit(1);

		$query = $this->db->get( 'products' );

		$options = '<option value="">Seleccione</option>';
		$result_array = array();

		if ($query->num_rows() == 0) {
			if ($as_string)
				return $options;
			return $result_array;
		}

		$period = array();
		foreach ($query->result() as $row)
			$period[] = $row->period;

		if ( empty( $period[0] ) ) {
			if ($as_string)
				return $options;
			return $result_array;
		}

		$explode = explode( '-', $period[0] );
		if( is_array( $explode ) and isset( $explode[1] ) ){
			for( $i = (int)$explode[0]; $i <= (int) $explode[1]; $i++ ) {
				$options .= '<option value="'.$i.'">'.$i.'</option>';
				$result_array[$i] = $i;
			}
		}else{
			$explode = explode( ',', $period[0] );
			foreach( $explode as $value ) {
				$options .= '<option value="'.$value.'">'.$value.'</option>';
				$result_array[$value] = $value;
			}
		}
		//print_r( $period );
		//exit;
		if ($as_string)
			return $options;
		return $result_array;
	}

/**
 *	Get Policies
 **/
	public function getPolicies( $product = null ){


		if( empty( $product ) ) return false;

		/*
			SELECT policies.id, products.name as product_name
			FROM policies
			JOIN products ON products.id=policies.product_id
		*/

		$options = '<option value="">Seleccione</option>';



		$this->db->where( array( 'product_id' => $product ) );

		$query = $this->db->get( 'policies' );

		//print_r( $query );

		if ($query->num_rows() == 0) return $options;


		foreach ($query->result() as $row)

			$options .= '<option value="'.$row->id.'">'.$row->name.'</option>';


		return $options;

	}




/**
 *	Functions Products
 **/
	public function getProductsGroups(){
		$query = $this->db->get('product_group');
		return $query->result_array();
	}

	public function getProductsGroupsOptions(){

		$query = $this->db->get( 'product_group' );

		$options = '<option value="">Seleccione</option>';


		if ($query->num_rows() == 0) return $options;


		foreach ($query->result() as $row) {

			$options  .= '<option value="'.$row->id.'">'.$row->name.'</option>';

		}

		return $options;

	}

	public function getProducts( $product_group = null ){


		//SELECT * FROM `products` WHERE product_group_id=1;
		if( !empty( $product_group ) )

			$this->db->where( array( 'product_group_id' => $product_group ) );
		$this->db->order_by('name', 'asc');


		$query = $this->db->get( 'products' );



		if ($query->num_rows() == 0) return false;

		$products = array();

		foreach ($query->result() as $row) {

			$products[] = array(
		    	'id' => $row->id,
		    	'platform_id' => $row->platform_id,
				'product_group_id' => $row->product_group_id,
		    	'name' =>  $row->name,
				'period' =>  $row->period,
				'active' =>  $row->active,
				'last_updated' =>  $row->last_updated,
				'date' =>  $row->date
		    );

		}

		return $products;

	}


	public function getProductsOptions( $product_group = null ){


		//SELECT * FROM `products` WHERE product_group_id=1;
		$this->db->select( 'products.*, product_group.name as group_name' );
		$this->db->join( 'product_group', 'product_group.id = products.product_group_id' );

		if( !empty( $product_group ) )

			$this->db->where( array( 'products.product_group_id' => $product_group ) );


		$query = $this->db->get( 'products' );

		$options = '<option value="">Seleccione</option>';


		if ($query->num_rows() == 0) return $options;


		foreach ($query->result() as $row) {

			$options  .= '<option value="'.$row->id.'">'.$row->name.'</option>';

		}

		return $options;

	}



/**
 *	Currency
 **/
	public function getCurrency(){

		$query = $this->db->get( 'currencies' );

		if ($query->num_rows() == 0) return false;

		$currency = array();

		foreach ($query->result() as $row) {

			$currency[] = array(
		    	'id' => $row->id,
		    	'name' => $row->name,
				'label' => $row->label,
		    	'last_updated' =>  $row->last_updated,
				'date' =>  $row->date
		    );

		}

		return $currency;

	}

	public function getCurrencyOptions(){

		$query = $this->db->get( 'currencies' );

		$options = '<option value="">Seleccione</option>';

		if ($query->num_rows() == 0) return $options;

		foreach ($query->result() as $row) {

			$options  .= '<option value="'.$row->id.'">'.$row->name.'</option>';

		}

		return $options;

	}


/**
 *	Payments Methods
 **/
	public function getPaymentMethods(){

		$query = $this->db->get( 'payment_intervals' );

		if ($query->num_rows() == 0) return false;

		$payment = array();

		foreach ($query->result() as $row) {

			$payment[] = array(
		    	'id' => $row->id,
		    	'name' => $row->name,
				'label' => $row->label,
		    	'last_updated' =>  $row->last_updated,
				'date' =>  $row->date
		    );

		}

		return $payment;

	}
/**
 *	Payments Intervals as array
 **/
	public function getPaymentIntervals() {
		return $this->getPaymentMethods();
	}

/**
 *	Payments Intervals as string
 **/
	public function getPaymentIntervalOptions(){

		$query = $this->db->get( 'payment_intervals' );

		$options = '<option value="">Seleccione</option>';

		if ($query->num_rows() == 0) return $options;

		foreach ($query->result() as $row) {

			$options  .= '<option value="'.$row->id.'">'.$row->name.'</option>';

		}

		return $options;

	}

/**
 *	Payments Methods Conducto (payment method) as array
 **/
	public function getPaymentMethodsConducto(){

		$query = $this->db->get( 'payment_methods' );

		if ($query->num_rows() == 0) return false;

		$payment = array();

		foreach ($query->result() as $row) {

			$payment[] = array(
		    	'id' => $row->id,
		    	'name' => $row->name,
				'label' => $row->label,
		    	'last_updated' =>  $row->last_updated,
				'date' =>  $row->date
		    );

		}

		return $payment;

	}
/**
 *	Payments Methods Conducto (payment method) as string
 **/
	public function getPaymentMethodsConductoOptions(){

		$query = $this->db->get( 'payment_methods' );

		$options = '<option value="">Seleccione</option>';

		if ($query->num_rows() == 0) return $options;

		foreach ($query->result() as $row) {

			$options  .= '<option value="'.$row->id.'">'.$row->name.'</option>';

		}

		return $options;

	}

/**
 *	Activate / Desactivate
 **/
 	public function getOtActivateDesactivate( $ot = null ){

		if( empty( $ot ) )
			return false;

		$this->db->where( 'id', $ot );
		$query = $this->db->get( 'work_order' );
		$ot = array();
		foreach ($query->result() as $row)
		{
			$ot[] = array(
		    	'id' => $row->id,
		    	'product_group_id' => $row->product_group_id,
				'work_order_status_id' => $row->work_order_status_id,
				'work_order_reason_id' => $row->work_order_reason_id,
		    	'work_order_responsible_id' =>  $row->work_order_responsible_id,
				'comments' =>  $row->comments
		    );
		}
		return $ot;
	}

	public function setPolicy( $ot = null, $policy = null )
	{

		if ( empty( $ot ) or empty( $policy ) )
			return false;

		$this->db->select( 'policy_id' );
		$this->db->where( 'id', $ot );
		$this->db->limit(1);
		$query = $this->db->get( 'work_order' );
		if ($query->num_rows() == 0)
			return false;

		$policies = array();
		foreach ($query->result() as $row)
			$policies[] = array(
		    	'policy_id' => $row->policy_id
		    );

		$updatepolicy = array( 'uid' =>  $policy );
		return $this->db->update( 'policies', $updatepolicy, array( 'id' => $policies[0]['policy_id'] ) );
	}

	public function getStatusArray($args = array()){
		$this->db->group_by('name');
		$this->db->order_by('name', 'asc');
		if(isset($args["not_in"]))
			foreach ($args["not_in"] as $column => $values)
				$this->db->where_not_in($column, $values);

		$query = $this->db->get('work_order_status');
		return $query->result_array();
	}


	public function getStatus( $work_order_status = null ){

		$query = $this->db->get( 'work_order_status' );

		$options = '<option value="">Seleccione</option>';

		if ($query->num_rows() == 0) return $options;

		foreach ($query->result() as $row) {

			if( !empty( $work_order_status ) and $work_order_status == $row->id )

				$options  .= '<option selected="selected" value="'.$row->id.'">'.$row->name.'</option>';

			else

				$options  .= '<option value="'.$row->id.'">'.$row->name.'</option>';

		}

		return $options;

	}

	public function getReason( $group = null, $work_order_status = null, $work_order_reason = null ){


		// And Where
		if( !empty( $group ) )
			$this->db->where( 'product_group_id', $group );


		if( !empty( $work_order_status ) )
			$this->db->where( 'work_order_status_id', $work_order_status );


		$query = $this->db->get( 'work_order_reason' );


		$options = '<option value="">Seleccione</option>';

		if ($query->num_rows() == 0){ return $options; }

		foreach ($query->result() as $row) {

			if( !empty( $work_order_reason ) and $work_order_reason == $row->id )

				$options  .= '<option selected="selected" value="'.$row->id.'">'.$row->name.'</option>';

			else

				$options  .= '<option value="'.$row->id.'">'.$row->name.'</option>';

		}


		return $options;

	}

	public function getResponsibles( $work_order_responsibles = null ){

		$query = $this->db->get( 'work_order_responsibles' );

		$options = '<option value="">Seleccione</option>';

		if ($query->num_rows() == 0) return $options;

		foreach ($query->result() as $row) {

			if( !empty( $work_order_responsibles ) and $work_order_responsibles == $row->id )

				$options  .= '<option selected="selected" value="'.$row->id.'">'.$row->name.'</option>';

			else

				$options  .= '<option value="'.$row->id.'">'.$row->name.'</option>';

		}

		return $options;

	}



/**
 *	Import payments
 **/
  public function importPaymentsTmp( $data = array() ){

		if( empty( $data ) ) return false;

		$query = $this->db->get( 'payments_tmp' );

		if ($query->num_rows() == 0){  $this->db->insert( 'payments_tmp', array( 'data' => json_encode( $data ) ) ); return;  }

		$id = null;

		foreach ($query->result() as $row)

			$id	= $row->id;


	   $this->db->delete( 'payments_tmp', array('id' => $id ) );

	   $this->db->insert( 'payments_tmp', array( 'data' => json_encode( $data ) ) );

	   return true;

  }

   public function removeImportPaymentsTmp(){

		$query = $this->db->get( 'payments_tmp' );

		if ($query->num_rows() == 0){  return true; }

		$id = null;

		foreach ($query->result() as $row)

			$id	= $row->id;


	   $this->db->delete( 'payments_tmp', array('id' => $id ) );

	   return true;

  }

  public function getImportPaymentsTmp(){

		$query = $this->db->get( 'payments_tmp' );

		if ($query->num_rows() == 0){  return true; }

		$data = array();

		foreach ($query->result() as $row)

			$data[]= array( 'id' => $row->id, 'data' => $row->data );  ;


	   return $data;

  }

  public function checkPayment( $uid = null, $prima = null, $payment_date = null, $user_id = null ){

	if( empty( $uid ) ) return false;
	if( empty( $prima ) ) return false;
	if( empty( $payment_date ) ) return false;
	if( empty( $user_id ) ) return false;

	/*
	    SELECT *
		FROM policies
		JOIN policies_vs_users ON policies_vs_users.policy_id=policies.id
		JOIN payments ON payments.policy_id=policies.id
		WHERE policies.uid=''
		AND policies.prima >=''
		AND payments.payment_date=''
		AND policies_vs_users.user_id='';
	*/

	$this->db->select();
	$this->db->from( 'payments' );
	$this->db->where( array( 'policy_number' => $uid, 'amount >=' => $prima, 'payment_date' => $payment_date, 'agent_id' => $user_id ) );


	$query = $this->db->get();

	if ($query->num_rows() == 0)  return true;

	return false;

  }

	  public function getWathdo( $i = 0 ){

		/*
		SELECT work_order.id, work_order.uid,  policies.name
		FROM work_order
   		JOIN policies ON policies.id=work_order.policy_id
   	    JOIN work_order_types ON work_order_types.id=work_order.work_order_type_id
		WHERE work_order.work_order_status_id=7
		AND ( work_order_types.patent_id=90
		OR work_order_types.patent_id=47 )
		*//*
		$query = $this->db->query(

		   'SELECT work_order.id, work_order.uid,  policies.name
			FROM work_order
			JOIN policies ON policies.id=work_order.policy_id
			JOIN work_order_types ON work_order_types.id=work_order.work_order_type_id
			WHERE work_order.work_order_status_id=7
			AND ( work_order_types.patent_id=90
			OR work_order_types.patent_id=47 )'

		);*/

		$this->db->select( 'work_order.id, work_order.uid,  policies.name' );
		$this->db->from( 'work_order' );
		$this->db->join( 'policies', 'policies.id=work_order.policy_id' );
		$this->db->join( 'work_order_types', ' work_order_types.id=work_order.work_order_type_id' );
		$this->db->where( 'work_order.work_order_status_id', 7 );
		$this->db->where( '( work_order_types.patent_id=90 OR work_order_types.patent_id=47 )' );
		$this->db->order_by( 'policies.name', 'asc' );

		$query = $this->db->get();

		$options = '<select name="assing['.$i.']" class="required"><option value="">Seleccione OT relacionada</option>';

		if ($query->num_rows() == 0){


			$options .= '<option value="noasignar" selected>No asignar a OT</option></select>';


			return $options;

		}

		$options .= '<option value="noasignar" selected>No asignar a OT</option>';

		foreach ($query->result() as $row)

			$options .= '<option value="'.$row->id.'">'.$row->uid.' - '.$row->name.'</option>';


		$options .= '</select>';

		return $options;
  }


  public function getWathdoPayment( $policy = null ){

		if( empty( $policy ) ) return false;
		/*
		SELECT *
		FROM `payments`
		WHERE `policy_id`='1'
		*/
		$this->db->select();
		$this->db->from( 'payments' );
		$this->db->where( 'policy_id', $policy );

		$query = $this->db->get();

		if ($query->num_rows() == 0) return true;

		return false;
  }

  public function getOtPolicyAssing( $ot = null ){

		if( empty( $ot ) ) return 'No se encontro la ot';
		/*
		SELECT work_order.id, work_order.uid,  policies.name
		FROM work_order
		JOIN policies ON policies.id=work_order.policy_id
		WHERE work_order.work_order_status_id=7
		*/



		$this->db->select( ' work_order.id, work_order.uid,  policies.name');
		$this->db->from( 'work_order' );
		$this->db->join( 'policies', 'policies.id=work_order.policy_id' );
		$this->db->where( array( 'work_order.id ' =>  $ot  ) );

		$query = $this->db->get();

		if ($query->num_rows() == 0) return 'No se encontro la ot';


		foreach ($query->result() as $row)

			return $row->uid.' - '.$row->name;


		return true;

  }

// The variable name $agent_id below is misleading: what should be passed is `users`.`id`
	function pop_up_data($work_order_id, $agent_id, $add_where = null)
	{
		$this->db->select('*,work_order.id AS work_order_id,agent_user.email AS agent_user_email,policies.uid AS policies_uid,policies.name AS policies_name,products.name AS products_name,policies.period AS policies_period,work_order_status.name AS work_order_status_name,payment_methods.name AS payment_methods_name,currencies.name AS currencies_name,work_order.uid AS work_order_uid,payment_intervals.name AS payment_intervals_name, work_order_types.patent_id AS patent_id, `policies_vs_users`.`percentage` as `p_percentage`');
		//$this->db->select('*');
		$this->db->from('work_order');
		$this->db->where(array('agent_user.id' => $agent_id));
		if (is_array($work_order_id))
			$this->db->where_in('work_order.id', $work_order_id);
		else
			$this->db->where(array('work_order.id' => $work_order_id));
		if ($add_where)
			$this->db->where($add_where);
		$this->db->join('users','work_order.user = users.id','left');
		$this->db->join('work_order_status','work_order.work_order_status_id = work_order_status.id','left');
		$this->db->join('policies','work_order.policy_id = policies.id','left');

		$this->db->join('policies_vs_users','policies.id = policies_vs_users.policy_id','left');
		$this->db->join('agents','policies_vs_users.user_id = agents.id','left');
		$this->db->join('users agent_user','agents.user_id = agent_user.id','left');

		$this->db->join('products','policies.product_id = products.id','left');
		$this->db->join('payment_intervals','policies.payment_interval_id = payment_intervals.id','left');
		$this->db->join('payment_methods','policies.payment_method_id = payment_methods.id','left');
		$this->db->join('currencies','policies.currency_id = currencies.id','left');
		$this->db->join('work_order_types','work_order.work_order_type_id = work_order_types.id','left');
		//$query = $this->db->get_where('work_order',array('work_order.id'=>$work_order_id));
		$query = $this->db->get();

		$result['general'] = $query->result();
		foreach ($result['general'] as $key => $value) {
			$result['general'][$key]->is_ntuable = $this->is_ntuable(
				$value->product_group_id,
				$value->patent_id,
				$value->work_order_status_id);
		}

		$this->db->select('email,name');
		$this->db->from('users_vs_user_roles');
		$this->db->where('users_vs_user_roles.user_role_id',4);
		$this->db->join('users','users_vs_user_roles.user_id = users.id');
		$query_later = $this->db->get();
		$result['director'] = $query_later->result();
		return $result;
	}

// Determine if an OT is editable

	public function is_editable( $product_group_id, $tramite, $ot_status ) {

		return ($ot_status != 4) &&						// OT is editable if not already paid
			$this->is_nuevo_negocio( $product_group_id, $tramite ); // AND NUEVO NEGOCIO
	}
// Determine if an OT is "NTU-able"

	public function is_ntuable( $product_group_id, $tramite, $ot_status ) {

		return ($ot_status == 7) &&						// OT is NTUable if status "aceptado"
			$this->is_nuevo_negocio( $product_group_id, $tramite ); // AND NUEVO NEGOCIO
	}

// Determine if an OT is "NUEVO NEGOCIO"

	public function is_nuevo_negocio( $product_group_id, $tramite ) {

		return 	(
			(($product_group_id == 1) && ($tramite == 47)) 	// "Vida" and "NUEVO NEGOCIO" or ...
				||
			(($product_group_id == 2) && ($tramite == 90))  // "GMM" and "NUEVO NEGOCIO"
			);
	}

// Search values
	public function generic_search( $table = null, $searched = null, $like = null,
		$limit = null, $offset = 0 )
	{
		if (( $table == null ) || ( $searched == null ))
			return FALSE;
		$this->db->select($searched, FALSE)->from($table);

		//limit
		if ($limit)
			$this->db->limit($limit, $offset);

		// like
		if ($like)
			$this->db->like($like[0], $like[1], $like[2]);

		$q = $this->db->get();

		return ($q->num_rows() > 0) ? $q->result() : FALSE;
	}


// Generic row retrieval

	public function generic_get( $table = null, $where = null, $limit = null, $offset = 0 ) {
		if ( $table == null )
			return FALSE;
        $this->db->select('*')->from($table);

		$where = is_array($where) ? $where : array();
		foreach ($where as $key => $value)
			$this->db->where($key, $value);

		//limit
		if ($limit)
			$this->db->limit($limit, $offset);

		$q = $this->db->get();

		return ($q->num_rows() > 0) ? $q->result() : FALSE;
	}

// Another generic update method
	public function generic_update( $table = null, $values = null, $where = null, $limit = null, $offset = 0 ) {
		if (( $table === null ) ||  !is_array($values) || !count($values))
			return FALSE;

		$where = is_array($where) ? $where : array();
		foreach ($where as $key => $value)
			$this->db->where($key, $value);
		//limit
		if ($limit)
			$this->db->limit($limit, $offset);

		$result = $this->db->update($table, $values) && ($this->db->affected_rows() > 0);
		return $result;
	}

// Another generic delete method
	public function generic_delete( $table = null, $where = null, $limit = null, $offset = 0 ) {
		if ( $table === null )
			return FALSE;

		$where = is_array($where) ? $where : array();
		foreach ($where as $key => $value)
			$this->db->where($key, $value);
		//limit
		if ($limit)
			$this->db->limit($limit, $offset);

		$result = $this->db->delete($table) && ($this->db->affected_rows() > 0);
		return $result;
	}

// Tramite type dropdown
	public function get_tramite_types()
	{
		// Tramite types per ramo
		$ramo_tramite_types = array(
			'1' => $this->getTypeTramite(1), // vida
			'2' => $this->getTypeTramite(2), // gmm
			'3' => $this->getTypeTramite(3)  // autos
			);

		$all_tramite_types = '';
		foreach ($ramo_tramite_types as $key => $value)
		{
			$all_tramite_types .= str_replace('<option value="">Seleccione</option>',
				'<optgroup label="Ramo = %s">', $value) . '</optgroup>';
			$ramo_tramite_types[$key] = str_replace('<option value="">Seleccione</option>',
				'<option value="">Todos</option>', $value);
			$ramo_tramite_types[$key] = sprintf("\n$key : '%s'", $ramo_tramite_types[$key]);

		}
		$all_tramite_types = sprintf($all_tramite_types, 'Vida', 'GMM', 'Autos');
		$ramo_tramite_types[0] = "\n0 : '" . '<option value="">Todos</option>' . $all_tramite_types . "'";
		return $ramo_tramite_types;
	}
// Work order tramite types array
	public function get_tramite_types_arr()
	{
		$work_order_types = $this->generic_get(
			'work_order_types', array( 'duration' => 0 ));

		$ramo_tramite_types = array(
			1 => array(), // Vida
			2 => array(), // GMM
			3 => array()	// Autos
			);
		foreach ($work_order_types as $ot_type)
		{
			if (isset($ramo_tramite_types[$ot_type->patent_id]))
				$ramo_tramite_types[$ot_type->patent_id][$ot_type->id] = $ot_type->name;
		}
		return ($ramo_tramite_types);
	}

	public function get_tramite_types_select_arr($selected_option = NULL)
	{
		$ramos = array(1 => 'Vida', 2 => 'GMM', 3 => 'Autos');
		// Tramite types per ramo
		$ramo_tramite_types = $this->get_tramite_types_arr();
		$all_tramite_types = '';
		foreach ($ramo_tramite_types as $key_ramo => $value_ramo)
		{
			$options = '';
			foreach ($value_ramo as $key => $value)
			{
				$selected = ($key == $selected_option) ? ' selected="selected"' : '';
				$options .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
			}
			$all_tramite_types .=
				'<optgroup label="Ramo = ' . $ramos[$key_ramo] . '">' . $options . '</optgroup>';
			$options = $key_ramo . " : '" . '<option value="">Todos</option>' . $options . "'";
			$ramo_tramite_types[$key_ramo] = "\n" . $options;
		}
		$ramo_tramite_types[0] = "\n0 : '" . '<option value="">Todos</option>' . $all_tramite_types . "'";

		return $ramo_tramite_types;
	}

	// Operation statistics
	private $operation_where = array();
	private $operation_where_in = array();
	public function init_operations($user_id = NULL, $periodo = NULL, $ramo = NULL)
	{
//		if (($user_id === NULL) || ($periodo === NULL))
		if (!$periodo)
			return FALSE;
		if ($user_id){
			if ($user_id != 'null')
				$this->operation_where_in['work_order.user'] = explode('_', $user_id);
		}
		if ($ramo)
			$this->operation_where['work_order.product_group_id'] = $ramo;
		switch ($periodo)
		{
			case 1: // Month
				$this->operation_where['work_order.creation_date >= '] =
					date( 'Y' ) . '-' . (date( 'm' )) . '-01';
				$this->operation_where['work_order.creation_date < '] =
					date('Y-m', mktime(0, 0, 0, date('m') + 1, date('d'), date('Y'))) . '-01';
				break;
			case 2:  // Trimester
			case 5:  // Cuatrimester
				$this->load->helper(array('tri_cuatrimester', 'ot/date'));
				if ($periodo == 5 )
					$begin_end = get_tri_cuatrimester( cuatrimestre(), 'cuatrimestre' ) ;
				else
					$begin_end = get_tri_cuatrimester( trimestre(), 'trimestre' );

				if (isset($begin_end) && isset($begin_end['begind']) && isset($begin_end['end']))
				{
					$this->operation_where['work_order.creation_date >= '] = $begin_end['begind'];
					$this->operation_where['work_order.creation_date <='] =  $begin_end['end'];
				}
				break;
			case 3: // Year
				$this->operation_where['work_order.creation_date >= '] = date( 'Y' ) .'-01-01';
				$this->operation_where['work_order.creation_date <='] =  date( 'Y-m-d' ) . ' 23:59:59';
				break;
			case 4: // Custom
				$from = $this->custom_period_from;
				$to = $this->custom_period_to;
				if ( ( $from === FALSE ) || ( $to === FALSE ) )
				{
					$from = date('Y-m-d');
					$to = $from;
				}
				$this->operation_where['work_order.creation_date >= '] = $from . ' 00:00:00';
				$this->operation_where['work_order.creation_date <='] = $to . ' 23:59:59';
				break;
		}
	}

	public function add_operation_where($conditions)
	{
		if ($conditions && is_array($conditions))
		{
			foreach ($conditions as $key => $value)
				if (is_array($value))
					$this->operation_where_in[$key] = $value;
				else
					$this->operation_where[$key] = $value;
		}
	}
	public function operation_stats( $ramo = NULL, $add_where = NULL, $with_agents = NULL )
	{
		if ($add_where)
			$this->operation_where = array_merge($this->operation_where, $add_where);

		$ot = $this->init_operation_result($ramo, TRUE);
		foreach ($this->operation_where_in as $key_c => $key_v)
			$this->db->where_in($key_c , $key_v);

		if ($with_agents)
//			$query = $this->db->select('COUNT(*) AS count, work_order.product_group_id, `policies_vs_users`.`user_id` as `agent_id`, work_order_status_id, work_order_responsible_id, t1.patent_id, t2.name AS tramite_type')
			$query = $this->db->select('COUNT(DISTINCT(work_order.id)) AS count, work_order.product_group_id, policies_vs_users.user_id as agent_id, work_order_status_id, work_order_responsible_id, t1.patent_id, t2.name AS tramite_type')
						->from('work_order' )
						->join('work_order_types AS t1', 't1.id = work_order.work_order_type_id')
						->join('work_order_types AS t2', 't2.id = t1.patent_id')
						->join('policies', 'policies.id = work_order.policy_id')
						->join('policies_vs_users', 'policies_vs_users.policy_id = policies.id')
						->where($this->operation_where)
						->group_by(array('product_group_id', 'work_order_status_id', 'work_order_responsible_id', 'patent_id'))
						->get();
		else
			$query = $this->db->select('COUNT(*) AS count, work_order.product_group_id, work_order_status_id, work_order_responsible_id, t1.patent_id, , t2.name AS tramite_type')
						->from('work_order' )
						->join('work_order_types AS t1', 't1.id = work_order.work_order_type_id')
						->join('work_order_types AS t2', 't2.id = t1.patent_id')
						->where($this->operation_where)
						->group_by(array('product_group_id', 'work_order_status_id', 'work_order_responsible_id', 'patent_id'))
						->get();

		if ($query->num_rows() == 0)
			return $ot;

		foreach ($query->result() as $row)
		{
			if (!$ramo)
			{
				$ot['per_ramo_tramite'][$row->product_group_id][$row->patent_id]['value'] += $row->count;
				$ot['per_ramo_tramite'][$row->product_group_id]['all']  += $row->count;
				$ot['recap-left'] += $row->count;
			}
			switch ($row->work_order_status_id)
			{
/*
'tramite': '5' OR '9'
'terminada': '4' OR '7' OR '8' OR '10'
'canceladas': 2
'activadas': 6
'NTU': 10
'pagada': 4
----------
'pendientes de pago': 7
*/
				case 5: // tramite
				case 9:
					$ot['per_status']['tramite'] += $row->count;
					$ot['recap-middle'] += $row->count;
				break;
				case 2: // cancelada
					$ot['per_status']['canceladas'] += $row->count;
					$ot['recap-middle'] += $row->count;
				break;
				case 4: // pagada
					$ot['per_status']['pagada'] += $row->count;
					$ot['per_status']['terminada'] += $row->count;
					$ot['recap-middle'] += $row->count;
				break;
				case 6: // activada
					$ot['per_status']['activadas'] += $row->count;
					$ot['recap-middle'] += $row->count;
				break;
				case 7: // aceptada, pendientes de pago
				case 8: // rechazada
					if ($row->work_order_status_id == 7)
						$ot['per_status']['pendientes_pago'] += $row->count;
					$ot['per_status']['terminada'] += $row->count;
					$ot['recap-middle'] += $row->count;
				break;
				case 10: // NTU
					$ot['per_status']['terminada'] += $row->count;
					$ot['per_status']['NTU'] += $row->count;
					$ot['recap-middle'] += $row->count;
				default:
				break;
			}
			if (!$ramo && isset($ot['per_responsible'][$row->work_order_responsible_id]))
			{
				$ot['per_responsible'][$row->work_order_responsible_id]['value'] += $row->count;
				$ot['recap-right'] += $row->count;
			}
		}
		return $ot;
	}

	public function operation_detailed( $ramo = NULL, $ot_status = NULL, $full = FALSE, $get_ot_list = FALSE, $qs = NULL )
	{
		if ($qs){
			if ($qs['cust_periodo']){
				$aux = explode("%",$qs['cust_periodo']);
				$cust_start = $aux[0];
				$cust_finish = $aux[1];
				$this->custom_period_from = $cust_start;
				$this->custom_period_to = $cust_finish;
				
			}
			$this->init_operations($qs['user_id'], $qs['periodo'], $ramo);
		}

		if (($ramo === NULL) || ($ot_status === NULL))
			return FALSE;
/*
'tramite': '5' OR '9'
'terminada': '4' OR '7' OR '8' OR '10'
'canceladas': 2
'activadas': 6
'NTU': 10
'pagada': 4
----------
'pendientes de pago': 7
*/
		$status_where = array();
		switch ($ot_status)
		{
			case 'tramite':
				$status_where = array(5, 9);
				break;
			case 'terminada':
				$status_where = array(4, 7, 8, 10);
				break;
			case 'canceladas':
				$status_where = array(2);
				break;
			case 'activadas':
				$status_where = array(6);
				break;
			case 'NTU':
				$status_where = array(10);
				break;
			case 'pagada':
				$status_where = array(4);
				break;
			case 'pendientes_pago':
				$status_where = array(7);
				break;
			case 'todos':
				$status_where = array(5, 6, 7, 9, 4, 2, 10);
				break;
			default:
				return FALSE;
				break;
		}

		if ($get_ot_list)
			$ot = array();
		else
			$ot = $this->init_operation_result($ramo, FALSE, $full);
		foreach ($this->operation_where_in as $key_c => $key_v)
			$this->db->where_in($key_c , $key_v);
		if ($full){
			$this->db->select('COUNT(DISTINCT(work_order.id)) AS count, SUM(prima) AS sum_prima, products.id AS prod_id, products.name AS product_name, policies_vs_users.user_id as agent_id')
					->from('work_order' )
					->join('policies', 'policies.id = work_order.policy_id')
					->join('products', 'products.id = policies.product_id')
					->join('policies_vs_users', 'policies_vs_users.policy_id = policies.id')
					->where($this->operation_where);
		} else {
			if (!$get_ot_list) {		
				$this->db->select('COUNT(*) AS count, SUM(prima) AS sum_prima, products.id AS prod_id, products.name AS product_name')
					->from('work_order' )
					->join('policies', 'policies.id = work_order.policy_id')
					->join('products', 'products.id = policies.product_id')
					->where($this->operation_where);
			} else {
				$this->db->select('policies_vs_users.percentage, policies_vs_users.user_id AS agent_id, users.name, users.lastnames, users.company_name, users.email, users.manager_id, users.id as user_id , policies.name as asegurado, policies.period as plazo, policies.prima as policy_prima, work_order_types.patent_id as tramite_type, product_group.name as group_name, work_order_types.name as type_name, work_order_status.name as status_name, work_order.*, products.id AS prod_id, products.name AS product_name')
					->from('work_order' )
					->join( 'product_group', 'product_group.id=work_order.product_group_id' )
					->join( 'work_order_types', 'work_order_types.id=work_order.work_order_type_id ' )
					->join( 'work_order_status', 'work_order_status.id=work_order.work_order_status_id' )
					->join( 'policies', 'policies.id = work_order.policy_id')

					->join( 'policies_vs_users', 'policies_vs_users.policy_id = work_order.policy_id')
					->join( 'agents', 'agents.id=policies_vs_users.user_id' )
					->join( 'users', 'users.id=agents.user_id ' )
					->join('products', 'products.id = policies.product_id')
					->where($this->operation_where);
			}
		}
		if ($status_where)
			$this->db->where_in('work_order_status_id', $status_where);
		if (!$get_ot_list)
			$this->db->group_by('products.id');
		$query = $this->db->get();

		if ($query->num_rows() == 0)
			return $ot;

		if ($get_ot_list)
		{
			foreach ($query->result_array() as $row)
			{
				if (!isset($ot[$row['id']]))
				{
					$ot[$row['id']] = $row;
					$ot[$row['id']]['agents'] = array();
				}
				$ot[$row['id']]['agents'][] = array(
					'percentage' => $row['percentage'],
					'agent_id' => $row['agent_id'],
					'name' => $row['name'],
					'lastnames' => $row['lastnames'],
					'company_name' => $row['company_name'],
					'email' => $row['email'],
					'user_id' => $row['user_id'],
					'manager_id' => $row['manager_id'],
				);
			}
			$ramo_tramite_types = $this->get_tramite_types_arr();
			foreach ($ot as $key => $value)
			{
				if (!empty( $ramo_tramite_types[$ramo][$value['tramite_type']]))
					$ot[$key]['parent_type_name'] = $ramo_tramite_types[$ramo][$value['tramite_type']];
				else
					$ot[$key]['parent_type_name'] = '';
				$ot[$key]['is_editable'] = $this->is_editable( $value['product_group_id'], $value['tramite_type'], $value['work_order_status_id'] );
				$ot[$key]['is_nuevo_negocio'] = $this->is_nuevo_negocio( $value['product_group_id'], $value['tramite_type']);
			}
		}
		else
		{
			foreach ($query->result() as $row)
			{
				$ot[$row->prod_id]['value'] = $row->count;
				$ot[0]['value'] += $row->count;
				if ($full)
				{
					$ot[$row->prod_id]['prima'] = $row->sum_prima;
					$ot[0]['prima'] += $row->sum_prima;
				}
			}
		}
		$query->free_result();
		return $ot;
	}

	public function init_operation_result($ramo, $recap = TRUE, $full = FALSE)
	{
		if ($recap)
		{
			if ($ramo)
			{
				$ot = array('recap-middle' => 0);
			}
			else
			{
				$ramo_tramite_types = $this->get_tramite_types_arr();
				$responsibles = $this->generic_get('work_order_responsibles');
				$ot = array(
					'per_ramo_tramite' => array(),
					'per_responsible' => array(),
					'recap-left' => 0,
					'recap-middle' => 0,
					'recap-right' => 0
				);
				foreach ($ramo_tramite_types as $ramo_key => $tramite_value)
				{
					foreach ($tramite_value as $key => $value)
					{
						$ot['per_ramo_tramite'][$ramo_key][$key]['label'] = $value;
						$ot['per_ramo_tramite'][$ramo_key][$key]['value'] = 0;
					}
					$ot['per_ramo_tramite'][$ramo_key]['all'] = 0;
				}
				foreach ($responsibles as $responsible)
				{
					$ot['per_responsible'][$responsible->id] = array(
						'label' => $responsible->name,
						'value' => 0,
					);
				}
			}
			$ot['per_status'] = array(
				'tramite' => 0,
				'terminada' => 0,
				'canceladas' => 0,
				'activadas' => 0,
				'NTU' => 0,
				'pagada' => 0,
				'pendientes_pago' => 0
				);
		}
		else
		{
			$ot = array();
			$products = $this->getProducts( $ramo );
			foreach ($products as $product)
			{
				if ($full)
					$ot[$product['id']] = array(
						'label' => $product['name'],
						'value' => 0,
						'prima' => 0,
						);
				else
					$ot[$product['id']] = array(
						'label' => $product['name'],
						'value' => 0,
						);
			}
			if ($full)
				$ot[0] = array(
					'label' => 'Total',
					'value' => 0,
					'prima' => 0,
					);
			else
				$ot[0] = array(
					'label' => 'Total',
					'value' => 0,
				);
		}
		return $ot;
	}

	public function getTotalNewBusiness(){
		$sql = "SELECT wo.policy_id, p.product_id, p.prima_entered, p.period, p.currency_id
			FROM work_order as wo 
			JOIN policies as p on wo.policy_id = p.id
			WHERE wo.creation_date like '2018%' and
			(wo.work_order_type_id = 48 or
			wo.work_order_type_id = 49 or 
			wo.work_order_type_id = 50) and
			(wo.work_order_status_id = 5 or
			wo.work_order_status_id = 6 or
			wo.work_order_status_id = 7 or
			wo.work_order_status_id = 9) and 
			p.period is not null";
		$query = $this->db->query($sql);
        return $query->result();
	}

	public function updatePrimes($id, $allocated, $bonus){
		$data = array(
			'allocated_prime' => $allocated,
			'bonus_prime' => $bonus
		);

		$this->db->where('id',$id);
		$this->db->update('policies',$data);
	}

	public function solicitudes_ingresadas( $ramo = NULL, $add_where = NULL )
	{
		$result = array();
		if ($add_where)
			$this->operation_where = array_merge($this->operation_where, $add_where);

		foreach ($this->operation_where_in as $key_c => $key_v)
			$this->db->where_in($key_c , $key_v);

		$query = $this->db->select('COUNT(DISTINCT(work_order.id)) AS count, work_order.product_group_id, policies_vs_users.user_id as agent_id, t1.patent_id, t2.name AS tramite_type')
					->from('work_order' )
					->join('work_order_types AS t1', 't1.id = work_order.work_order_type_id')
					->join('work_order_types AS t2', 't2.id = t1.patent_id')
					->join('policies', 'policies.id = work_order.policy_id')
					->join('policies_vs_users', 'policies_vs_users.policy_id = policies.id')
					->where($this->operation_where)
					->group_by('agent_id')
					->get();
		if ($query->num_rows() == 0)
			return $result;

		foreach ($query->result() as $row)
			$result[$row->agent_id] = $row->count;
		return $result;
	}

        public function mark_polizas_as_paid(){
            //$this->db->replace('policy_negocio_pai', array('ramo'=>2,'policy_number'=>'240932327','negocio_pai'=>'3','date_pai'=>'2001-12-13 00:00:00','creation_date'=>'2001-12-14 00:00:00'));

            $num =0;
            $sql = "SELECT `payments`.*
	    FROM `payments`
	    WHERE `valid_for_report` = '1'
            AND `year_prime` = '1'
            GROUP BY `policy_number` ORDER BY `payment_date` ASC";

            $query = $this->db->query($sql);

            foreach ($query->result() as $row)
            {
                $sql2 = "SELECT `payments`.*
                        FROM `payments`
                        WHERE `valid_for_report` = '1'
                        AND `year_prime` = '1'
                        AND `policy_number` = '".$row->policy_number."' ORDER BY `payment_date` ASC";
                $q = $this->db->query($sql2);
                $total = 0;
                $payment_date = "";
                $flag = true;
                $data = array();
                    foreach ($q->result() as $payment)
                    {
                        $total+=(int)$payment->amount;
                        if($flag && $total > 12000){
                        $payment_date = $payment->payment_date;
                        $flag = false;
                        }
                    }

                    if($total > 500000){
                        $data = array('ramo'=>$row->product_group,'policy_number'=>$row->policy_number,'negocio_pai'=>'3','date_pai'=>$payment_date,'creation_date'=>date("Y-m-d H:i:s"));
                        $this->db->replace('policy_negocio_pai',$data);
                    }elseif($total > 110000){
                        $data = array('ramo'=>$row->product_group,'policy_number'=>$row->policy_number,'negocio_pai'=>'2','date_pai'=>$payment_date,'creation_date'=>date("Y-m-d H:i:s"));
                        $this->db->replace('policy_negocio_pai',$data);
                    }elseif($total > 12000){
                        $data = array('ramo'=>$row->product_group,'policy_number'=>$row->policy_number,'negocio_pai'=>'1','date_pai'=>$payment_date,'creation_date'=>date("Y-m-d H:i:s"));
                        $this->db->replace('policy_negocio_pai',$data);
                    }else{
                        $this->db->where('policy_number',$row->policy_number)->delete('policy_negocio_pai');
                    }
                $num++;
            }
            echo "Registros agregados:".$num;
        }

              /**
         * Check if a order work is already PAI
         */
        public function set_policy_pai($policy){
            $total = 0;
            $query = $this->db->select("*")
                    ->from("payments")
                    ->where("policy_number",$policy)
                    ->where("year_prime",1)
                    ->order_by("payment_date","ASC")
                    ->get();
            foreach ($query->result() as $row){
                $total += $row->amount;
                $pai = 0;

                    if($this->db->where("policy_number",$row->policy_number)->get("policy_negocio_pai")->num_rows() > 0){
                        $this->db->where("policy_number",$row->policy_number);
                        $this->db->update("policy_negocio_pai",array("pai_date"=>$row->payment_date));
                        break;
                    }else{
                        if($total > 500000){
                        $pai = 3;
                        $this->db->insert("policy_negocio_pai",array(
				'ramo' => $row->product_group,
				'policy_number' => $row->policy_number,
				'negocio_pai' => $pai,
				'creation_date' => date('Y-m-d H:i:s'),
                                'pai_date' => $row->payment_date
				));
                        break;
                        }elseif($total > 110000){
                            $pai = 2;
                            $this->db->insert("policy_negocio_pai",array(
				'ramo' => $row->product_group,
				'policy_number' => $row->policy_number,
				'negocio_pai' => $pai,
				'creation_date' => date('Y-m-d H:i:s'),
                                'pai_date' => $row->payment_date
				));
                        break;
                        }elseif($total > 12000){
                            $pai =1 ;
                            $this->db->insert("policy_negocio_pai",array(
				'ramo' => $row->product_group,
				'policy_number' => $row->policy_number,
				'negocio_pai' => $pai,
				'creation_date' => date('Y-m-d H:i:s'),
                                'pai_date' => $row->payment_date
				));
                        break;
                        }


                    }
            }
        }
}
?>