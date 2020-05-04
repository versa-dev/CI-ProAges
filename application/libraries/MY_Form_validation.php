<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation{
    
    public function __construct()
    {
        parent::__construct();
    }

    // --------------------------------------------------------------------
    /**
     * Validates against indices of an array
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   boolean
     */
    function src_is_array_index($str, $allowed)
    {
        $result = $this->_src_check_array_index($str, $allowed);
        if (!$result)
            $this->set_message('src_is_array_index', 'El campo %s no tiene un valor válido.');
        return $result;
    }

    // --------------------------------------------------------------------
    /**
     * Validates against values of an array
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   boolean
     */
    function src_is_array_value($str, $allowed)
    {
        $result = $this->_src_check_array_index($str, $allowed, FALSE);
        if (!$result)
            $this->set_message('src_is_array_value', 'El campo %s no tiene un valor válido.');
        return $result;
    }

    // --------------------------------------------------------------------
    /**
     * Validates against an array (index or value)
     *
     * @access   private
     * @param    string
     * @param    array
     * @param    boolean
     * @return   boolean
     */
    function _src_check_array_index($str, $allowed, $on_index = TRUE)
    {
        $result = TRUE;
        $CI = & get_instance();
        if (($allowed_array = $CI->config->item($allowed)) === FALSE)
            $result = FALSE;
        elseif (!is_array($allowed_array))
            $result = FALSE;
        else {
            if ($on_index)
                $result = isset($allowed_array[$str]);
            else
                $result = in_array($str, $allowed_array);
        }
        return $result;
    }

    // --------------------------------------------------------------------
    /**
     * Validates is not array index
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   boolean
     */
    public function src_is_not_array_index($str, $not_allowed) {
        $result = TRUE;
        $CI = & get_instance();
        if (($not_allowed_array = $CI->config->item($not_allowed)) === FALSE)
            $result = FALSE;
        elseif (!is_array($not_allowed_array))
            $result = FALSE;
        else
            $result = !isset($allowed_array[$str]);
        if (!$result)
            $this->set_message('src_is_not_array_index', 'El campo %s no tiene un valor válido.');
        return $result;
    }
    // --------------------------------------------------------------------
    /**
     * Validates is decimal or integer
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   boolean
     */
	public function decimal_or_integer($str)
	{
        $result = $this->decimal($str) || $this->integer($str);
        if (!$result)
            $this->set_message('decimal_or_integer', 'El campo %s debe contener un número decimal o entero.');
        return $result;
	}

	// --------------------------------------------------------------------

	/**
	 * Match 2 fields to others
	 *
	 * @access	public
	 * @param	string
	 * @param	fields
	 * @return	bool
	 */
	public function is_unique_but($str, $fields)
	{
		list($table, $field_unique, $field_common_name, $field_common_value)=explode('.', $fields);
		$query = $this->CI->db->limit(1)->get_where($table, array(
			$field_unique => $str,
			"$field_common_name !=" => $field_common_value)
		);
		$result = ($query->num_rows() === 0);
        if (!$result)
            $this->set_message('is_unique_but', 'El campo %s no tiene un valor válido. Debe verificar si otro OT tiene el mismo número.');
		return $result;
    }

	// --------------------------------------------------------------------

	/**
	 * Our version of is_unique() that also compares the length of the fields
	 *
	 * @access	public
	 * @param	string
	 * @param	field
	 * @return	bool
	 */
	public function my_is_unique($str, $field)
	{
		list($table, $field)=explode('.', $field);
		$query = $this->CI->db->limit(1)->get_where($table, array($field => $str));

		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			if (strlen($row->$field) == strlen($str))
			{
				$this->set_message('my_is_unique', 'El campo %s debe contener un valor único.');
				return FALSE;
			}
		}
		return TRUE;
    }

	// --------------------------------------------------------------------

	/**
	 * Exists in database table
	 *
	 * @access	public
	 * @param	string
	 * @param	field
	 * @return	bool
	 */
	public function exists_in_db($str, $field)
	{
		list($table, $field)=explode('.', $field);
		if ($table == 'agents')
		{
			$parts = explode('[ID: ', $str);
			if (count($parts) == 2)
				$str = str_replace(']', '', $parts[1]);
		}
			
		$query = $this->CI->db->limit(1)->get_where($table, array($field => $str));

		if ($query->num_rows() == 0)
		{
			$this->set_message('exists_in_db', 'El campo %s no existe.');
			return FALSE;
		}
		return TRUE;
    }

	// --------------------------------------------------------------------

	/**
	 * Is date (i.e. can be parsed by strtotime)
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function is_date($str)
	{
		$str_array = explode('-', $str);
		if ( (count($str_array) == 3) && 
			checkdate ( $str_array[1], $str_array[2], $str_array[0]))
			return TRUE;

		$this->set_message('is_date', 'El campo %s debe ser una fecha.');
		return FALSE;
    }

	// --------------------------------------------------------------------

	/**
	 * Absolute value of input <= some value
	 *
	 * @access	public
	 * @param	string
	 * @param	field
	 * @return	bool
	 */
	public function abs_le($str, $value)
	{
		if (abs($str) > $value)
		{
			$this->set_message('is_date', 'El campo %s no tiene un valor válido.');
			return FALSE;
		}
		return TRUE;
    }
}
