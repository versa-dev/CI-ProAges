<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*

  Author
  Site:
  Twitter:
  Facebook:
  Github:
  Email:
  Skype:
  Location:		Mexíco


*/
class Policy_model extends CI_Model
{
	private $table = '';

	public function __construct()
	{
		parent::__construct();
	}

/**
  Set table
 **/
	public function set_table( $table )
	{
		$this->table = $table;
	}

/**
  Initialize the table `policy_adjusted_primas`
 **/
	public function populate_adjusted_primas()
	{
		$result = 0;
		if ($this->db->table_exists('policy_adjusted_primas'))
		{
			$query = $this->db->get_where('policies',
				'prima is NOT NULL', 1, 0);
			if ($query->num_rows() == 0)
				return;
			$query = $this->db->get('policy_adjusted_primas', 1, 0);
			if ($query->num_rows() > 0)
				return;

			$this->db->select('`work_order`.`work_order_status_id`, `work_order`.`product_group_id`, `work_order_types`.`patent_id`, `policies`.`id`, `policies`.`prima`, `policies`.`allocated_prime`, `policies`.`bonus_prime`, `policies`.`payment_interval_id`, `policies`.`date`, `extra_payment`.`extra_percentage`,`work_order`.`creation_date`', FALSE);
			$this->db->from(array('work_order', 'work_order_types', 'policies', 'products', 'extra_payment'));
			$this->db->where("
`work_order_types`.`id`=`work_order`.`work_order_type_id`
AND
`work_order`.`policy_id` = `policies`.`id`
AND
 `products`.`id`=`policies`.`product_id`
AND
`extra_payment`.`x_product_platform` = `products`.`platform_id`
AND
`extra_payment`.`x_currency` = `policies`.`currency_id`
AND
`extra_payment`.`x_payment_interval` = `policies`.`payment_interval_id`
AND
`work_order`.`work_order_status_id` IN ( 7, 4)
AND
(
((`work_order`.`product_group_id` = 1) AND (`work_order_types`.`patent_id` = 47))
OR
((`work_order`.`product_group_id` = 2) AND (`work_order_types`.`patent_id` = 90))
)
", NULL, FALSE);

//`policies`.`prima` IS NOT NULL
//AND
			$query = $this->db->get();
			$new_rows = array();
			foreach ($query->result_array() as $row)
			{
				$this->_prepare_adjusted_primas($row, $new_rows);
			}
			if ($new_rows)
				$this->db->insert_batch('policy_adjusted_primas', $new_rows);
			$result = $this->db->count_all('policy_adjusted_primas');
		}
		return $result;
	}

/**
  Insert rows in `policy_adjusted_primas` for one given OT
 **/
	public function add_adjusted_primas($ot_id = null)
	{
		if (!$ot_id)
			return FALSE;
		$this->db->select('`work_order`.`work_order_status_id`, `policies`.`id`, `policies`.`prima`, `policies`.`allocated_prime`, `policies`.`bonus_prime`, `policies`.`payment_interval_id`, `policies`.`date`, `extra_payment`.`extra_percentage`,`work_order`.`creation_date`', FALSE);
		$this->db->from(array('work_order', 'policies', 'products', 'extra_payment'));
		$this->db->where("
`work_order`.`policy_id` = `policies`.`id`
AND
 `products`.`id`=`policies`.`product_id`
AND
`extra_payment`.`x_product_platform` = `products`.`platform_id`
AND
`extra_payment`.`x_currency` = `policies`.`currency_id`
AND
`extra_payment`.`x_payment_interval` = `policies`.`payment_interval_id`
AND
`work_order`.`id` = '$ot_id'
", NULL, FALSE);

//`policies`.`prima` IS NOT NULL
//AND
		$query = $this->db->get();
		$new_rows = array();
		foreach ($query->result_array() as $row)
		{
			$this->_prepare_adjusted_primas($row, $new_rows);
		}
		if ($new_rows)
			return $this->db->insert_batch('policy_adjusted_primas', $new_rows);
		else
			return false;
	}

        public function recalculate_adjusted_primas($ot_id = FALSE, $policy_id = FALSE)
	{
		//return TRUE;
                if (!$ot_id || !$policy_id )
			return FALSE;
		$result = TRUE;

		$this->db->order_by('due_date', 'asc');
		$query = $this->db->get_where('policy_adjusted_primas',
			array('policy_id' => $policy_id), 1, 0);
		if ($query->num_rows() == 0)
			return $result;
		$old_policy_adjusted_prima = $query->row();
		$start_date = $old_policy_adjusted_prima->due_date . ' 00:01:00';

		$this->db->select('`work_order`.`work_order_status_id`, `work_order`.`product_group_id`, `work_order_types`.`patent_id`, `policies`.`id`, `policies`.`prima`, `policies`.`allocated_prime`, `policies`.`bonus_prime`, `policies`.`payment_interval_id`, `policies`.`date`, `extra_payment`.`extra_percentage`,`work_order`.`creation_date`', FALSE);
		$this->db->from(array('work_order', 'work_order_types', 'policies', 'products', 'extra_payment'));
		$this->db->where("
                `work_order`.`id` = '$ot_id'
                AND
                `work_order_types`.`id`=`work_order`.`work_order_type_id`
                AND
                `work_order`.`policy_id` = `policies`.`id`
                AND
                 `products`.`id`=`policies`.`product_id`
                AND
                `extra_payment`.`x_product_platform` = `products`.`platform_id`
                AND
                `extra_payment`.`x_currency` = `policies`.`currency_id`
                AND
                `extra_payment`.`x_payment_interval` = `policies`.`payment_interval_id`
                AND
                `work_order`.`work_order_status_id` IN ( 7, 4)
                ", NULL, FALSE);

		$query = $this->db->get();

		$new_rows = array();
		foreach ($query->result_array() as $row)
		{
			$row['date'] = $start_date;
			$this->_prepare_adjusted_primas($row, $new_rows);
		}

		if ($new_rows)
		{
			if ($this->db->delete('policy_adjusted_primas',
				array('policy_id' => $new_rows[0]['policy_id'])))
			{
				$result = $this->db->insert_batch('policy_adjusted_primas', $new_rows);
			}
			else
			{
				$result = FALSE;
			}
		}
		return $result;
	}
/**
  Insert rows in `policy_adjusted_primas` for one given OT
 **/
	private function _prepare_adjusted_primas($row, &$new_rows)
	{
		list($year, $month, $day_time) = explode('-', $row['date']);
		list($day, $time) = explode(' ', $day_time);
		$year =  substr($row['creation_date'], 0, 4);
		$row['prima'] = ($year == 2018) ? $row['prima'] * 1 : $row['prima'] * (1 + $row['extra_percentage']);
		$row['allocated_prime'] = ($year == 2018) ? $row['allocated_prime'] * 1 : $row['allocated_prime'] * (1 + $row['extra_percentage']);
		$row['bonus_prime'] = ($year == 2018) ? $row['bonus_prime'] * 1 : $row['bonus_prime'] * (1 + $row['extra_percentage']);

		switch ($row['payment_interval_id'])
		{
			case 1: // mensual payment
				for ($i = 0; $i < 12; $i++) {
					$new_rows[] = array(
						'policy_id' => $row['id'],
						'adjusted_prima' => $row['prima'] / 12,
						'adjusted_allocated_prime' => $row['allocated_prime'] / 12,
						'adjusted_bonus_prime' => $row['bonus_prime'] / 12,
						'due_date' => date('Y-m-d', mktime(0, 0, 0, $month + $i, $day, $year))
						);
				}
				break;
			case 2: // trimestrial payment
				for ($i = 0; $i < 4; $i++) {
					$new_rows[] = array(
						'policy_id' => $row['id'],
						'adjusted_prima' => $row['prima'] / 4,
						'adjusted_allocated_prime' => $row['allocated_prime'] / 4,
						'adjusted_bonus_prime' => $row['bonus_prime'] / 4,
						'due_date' => date('Y-m-d', mktime(0, 0, 0, $month + ($i * 3), $day, $year))
						);
				}
				break;
			case 3: // semestrial payment
				for ($i = 0; $i < 2; $i++) {
					$new_rows[] = array(
						'policy_id' => $row['id'],
						'adjusted_prima' => $row['prima'] / 2,
						'adjusted_allocated_prime' => $row['allocated_prime'] / 2,
						'adjusted_bonus_prime' => $row['bonus_prime'] / 2,
						'due_date' => date('Y-m-d', mktime(0, 0, 0, $month + ($i * 6), $day, $year))
						);
				}
				break;
			case 4: // annual payment
				$new_rows[] = array(
					'policy_id' => $row['id'],
					'adjusted_prima' => $row['prima'],
					'adjusted_allocated_prime' => $row['allocated_prime'],
					'adjusted_bonus_prime' => $row['bonus_prime'],
					'due_date' => date('Y-m-d', mktime(0, 0, 0, $month, $day, $year))
					);
				break;
		}
	}

/**
  Get rows in `policy_adjusted_primas` for given OTs
 **/
	public function get_ot_adjusted_primas($ots = null)
	{
		$result = array();
		if (!$ots)
			return $result;
		$this->db->select( 'policy_adjusted_primas.*, work_order.id as ot_id' );
		$this->db->from( 'policy_adjusted_primas' );
		$this->db->join( 'work_order', 'work_order.policy_id=policy_adjusted_primas.policy_id' );
		$this->db->where_in('work_order.id', $ots);
		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				if (!isset($result[$row->ot_id]))
					$result[$row->ot_id] = array(
						'adjusted_prima' => $row->adjusted_prima,
						'adjusted_allocated_prime' => $row->adjusted_allocated_prime,
						'adjusted_bonus_prime' => $row->adjusted_bonus_prime,
						'due_date' => array($row->due_date)
					);
				else
					$result[$row->ot_id]['due_date'][] = $row->due_date;
			}
		}
		$query->free_result();
		return $result;
	}

/**
  Deletes rows of table policy_adjusted_prima for a given policy and given dates
 **/
	public function delete_adjusted_primas($policy_dates = array())
	{
		$deleted = 0;
		$to_delete = count($policy_dates);
		foreach ($policy_dates as $policy_date)
		{
			$policy_date_arr = explode('_', $policy_date);
			if (isset($policy_date_arr[0]) && isset($policy_date_arr[1]))
			{
				$where = array('policy_id' => $policy_date_arr[0], 'due_date' => $policy_date_arr[1]);
				$this->db->delete('policy_adjusted_primas', $where);
				if ($this->db->affected_rows())
					$deleted++;
			}
		}
		return ($deleted >= $to_delete);
	}

/**
  Script to compute (initialize) `policies`.`prima_entered`
  (special attention to rows with currency_id USD)
 **/
	public function init_policy_prima_entered()
	{
		$result = array('mxn' => 0, 'usd' => 0);
// 1. process policies entered in MXN if any: just copy `prima` into `prima_entered'
		$this->db->select( 'prima_entered' );
		$query = $this->db->get_where('policies',
			'prima is NOT NULL AND prima_entered is NULL AND currency_id = 1', 1);

		if ($query->num_rows() > 0)
		{
			$query->free_result();
			$this->db->flush_cache();
			$to_update = array();
			$this->db->select( 'id, prima' );
			$query = $this->db->get_where('policies',
				'prima is NOT NULL AND prima_entered is NULL AND currency_id = 1');
			foreach ($query->result() as $row)
			{
				$to_update[] = array(
					'id' => $row->id,
					'prima_entered' => $row->prima
				);
			}
			$query->free_result();
			$this->db->flush_cache();
//			$this->db->update_batch('policies', $to_update, 'id'); // CI bug
			$result['mxn'] = $this->update_batch('policies', $to_update, 'id');
		}

// 2. process polcies entered in USD: just copy `prima` into `prima_entered'
		$this->db->flush_cache();
		$this->db->select( 'prima_entered' );
		$query = $this->db->get_where('policies',
			'prima is NOT NULL AND prima_entered is NULL AND currency_id = 2', 1);

		if ($query->num_rows() > 0)
		{
			$query->free_result();
			$this->db->flush_cache();
			$to_update = array();
			$this->db->select(
				'policies.id as policy_id, policies.prima, policies.last_updated, exchange_rates.id as xrate_id, exchange_rates.date, exchange_rates.rate' );
			$this->db->join( 'exchange_rates',
				'exchange_rates.date=DATE(policies.last_updated)', 'right' );
			$query = $this->db->get_where('policies',
				'policies.prima is NOT NULL AND policies.prima_entered is NULL AND policies.currency_id = 2');
			$ne_rates = array();
			foreach ($query->result() as $row)
			{
				if ($row->rate == 'N/E')
				{
					$ne_rates[] = $row;
				}
				else
				{
					$to_update[] = array(
						'id' => $row->policy_id,
						'prima_entered' => $row->prima / $row->rate,
					);
				}
			}
			foreach ($ne_rates as $ne_rate)
			{
				$this->db->flush_cache();
				$this->db->select( 'rate' );
				$this->db->order_by('date', 'DESC');
				$rate_query = $this->db->get_where('exchange_rates',
					"date < '" . $ne_rate->date . "' and rate != 'N/E'", 1);
				if ($rate_query->num_rows() > 0)
				{
					$rate_row = $rate_query->row();
					$to_update[] = array(
						'id' => $ne_rate->policy_id,
						'prima_entered' => $ne_rate->prima / $rate_row->rate,
					);
				}
				else
				{
					echo "Falta el tipo cambio para convertir la prima de una poliza. Informe al administrador.";
					exit;
				}
				$rate_query->free_result();
			}

			$query->free_result();
			$this->db->flush_cache();
//			$this->db->update_batch('policies', $to_update, 'id'); // CI bug
			$result['usd'] = $this->update_batch('policies', $to_update, 'id');
		}
		return $result;
	}

/**
  Update batch - workaround for the CI method not working
 **/
	public function update_batch($table, $to_update, $id)
	{
		$this->db->flush_cache();
		$where_array = array();
		$update_string = "UPDATE `$table` SET `prima_entered` = CASE";
		foreach ($to_update as $policy_row)
		{
			$update_string .= sprintf("\nWHEN `$id` = '%s' THEN '%s'",
				$policy_row[$id], $policy_row['prima_entered']);
			$where_array[] = "'" . $policy_row[$id] .  "'";
		}
		if ($where_array)
		{
			$update_string .= "\nELSE `prima_entered` END
WHERE `id` IN (" . implode(',', $where_array) . ")";
			$this->db->query($update_string);
			return $this->db->affected_rows();
		}
		return 0;
	}

/**
  Rest `prima_entered`
 **/
	public function reset_prima_entered()
	{
		$this->db->flush_cache();
		$this->db->query("UPDATE `policies` SET `prima_entered` = NULL");
	}

	public function get_percentages_products($product_id, $period_id)
	{
		if($period_id != null)
		{
			$sql   = 'SELECT allocated_prime, bonus_prime FROM products_percentage JOIN products_period on products_period.idPerc = products_percentage.id WHERE idProducts = '.$product_id.' AND period ='.$period_id;
			$query = $this->db->query($sql);
	        $res   = $query->result_array();
	        return $res;
		}
		else
		{
			return 0;
		}
		
	}

	public function reset_values_primas()
	{
		$actual_date  = date('Y-m-d h:m:s');
		$sql_update   = 'SELECT * FROM cache_updates WHERE type_update = "primas_ubicar_ot";';
		$query_update =  $this->db->query($sql_update);
		$res_update   = $query_update->result_array();
		if($actual_date > $res_update[0]['next_update'])
		{
			$this->execute_reset_values();
			$data = array('last_update' => $actual_date, 'next_update' => date( "Y-m-d h:m:s", strtotime( "$actual_date +1 day" ) ));
			$this->db->where('type_update', 'primas_ubicar_ot');
			$this->db->update('cache_updates', $data);
		}
		$query_update->free_result();
	}
	public function execute_reset_values()
	{
		$sql = 'SELECT  policies.id as id_policy, prima, 
						products_percentage.allocated_prime as perc_ubi, 
						products_percentage.bonus_prime as perc_pag
				FROM policies
				JOIN products_percentage ON policies.product_id = products_percentage.idProducts
				WHERE policies.allocated_prime = 0.00 OR policies.bonus_prime = 0.00;';
		$query = $this->db->query($sql);
		$res   = $query->result_array();
		if ( $query->num_rows > 0 )
		{
			foreach ($res as $row) {
				$prima_ubi = $row['prima'] * $row['perc_ubi'];
				$prima_pag = $row['prima'] * $row['perc_pag'];
				$data = array('allocated_prime' => $prima_ubi,'bonus_prime' => $prima_pag);
				$this->db->where('id', $row['id_policy']);
				$this->db->update('policies', $data);
				error_log(print_r($this->db->last_query(),true));
			}
		}
		$query->free_result();
	}

}
/* End of file policy_model.php */
/* Location: ./application/models/policy_model.php */