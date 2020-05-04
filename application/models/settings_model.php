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
class Settings_model extends CI_Model
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
  Update batch
 **/
    public function update_batch( $values = array() )
	{
		if ( empty( $values ) ) 
			return false;
        return $this->db->update_batch( $this->table, $values, 'key' ); // error message in CI
    }

/**
  Update batch (manual)
 **/
    public function update_batch_manual( $values = array() )
	{
		$key = 'key';
		$other_field = 'value';
		if ( empty( $values ) ) 
			return false;
		$query_str = "UPDATE `" . $this->table . "` SET `$other_field` = CASE \n";
		$in = array();
		foreach ($values as $row)
		{
			$query_str .= "WHEN `$key` = '" . $row[$key] . "' THEN '". $row[$other_field] . "'\n";
			$in[] = "'" . $row[$key] . "'";
		}
		$query_str .= "ELSE `$other_field` END WHERE `$key` IN (" . implode(',', $in) . ")\n";
		return $this->db->query($query_str);
    }
/**
  Get
 **/
	public function get( $where = null, $order_by = null )
	{
		$result = array();
		$this->db->select( '*' )
				->from( $this->table );
		if ($where)
			$this->db->where( $where );
		if ($order_by)
			$this->db->order_by($order_by);
		$query = $this->db->get();
		foreach ($query->result() as $row)
		{
			$result[$row->key] = $row;
		}
		return $result;
	}
}
/* End of file settings_model.php */
/* Location: ./application/models/settings_model.php */