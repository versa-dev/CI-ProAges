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
class Exchange_rate_model extends CI_Model
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
  Get current exchange rate from www.banxico.org.mx
 **/
	public function get_from_banxico()
	{
		$yesterday = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
		$query = $this->db->get_where('exchange_rates',
			"date >= '$yesterday'");

		if ($query->num_rows() > 0)
		{
			return NULL;
		}
		$result = FALSE;

		$getContents = @file_get_contents(
			'http://www.banxico.org.mx/rsscb/rss?BMXC_canal=fix&BMXC_idioma=es');
		if ($getContents === FALSE)
		{
			$this->handle_error('No respuesta del sitio www.banxico.org.mx. Informe al administrador.');
			return $result;
		}
		$getContents = str_replace(array("rdf:", "dc:", "cb:"),
			array("", "", ""), $getContents);  // to be able to parse the xml
		$xml = (array) simplexml_load_string($getContents, null, LIBXML_NOCDATA);
		if (!$xml)
		{
			$this->handle_error('Leyendo la rspuesta del sitio www.banxico.org.mx: no se pudo analizar el xml. Informe al administrador.');
			return $result;
		}
		$json = json_encode($xml);
		$json_decoded = json_decode($json, true);

		if (empty($json_decoded['item']['date']) ||
			empty($json_decoded['item']['statistics']['exchangeRate']['value'])
			)
		{
			$this->handle_error('No se pudo leer campos en la respuesta del sitio www.banxico.org.mx. Informe al administrador.');
			return $result;
		}
		$response_date = substr($json_decoded['item']['date'], 0, 10);
		$matched = array();
		$regexp = preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",
			$response_date, $matched);
		if (!$regexp || 
			(count($matched) < 3) || 
			!checkdate($matched[1], $matched[2], 
				substr($matched[0], 0, 4)))
		{
			$this->handle_error('No se pudo leer el campo &lt;date/&gt; en la respuesta del sitio www.banxico.org.mx. Informe al administrador.');	
			return $result;
		}

		$query = $this->db->get_where('exchange_rates', 
			"date = '$response_date'");
		if ($query->num_rows() > 0)
		{
			return NULL;
		}

		$response_value = $json_decoded['item']['statistics']['exchangeRate']['value'];
		if (!preg_match('/^([0-9]*[.])?[0-9]+$/', $response_value) &&
			(strtoupper($response_value) != 'N/E'))
		{
			$this->handle_error('No se pudo leer el campo &lt;value/&gt; en la respuesta del sitio www.banxico.org.mx. Informe al administrador.');	
			return $result;
		}

		$now = date('Y-m-d H:i:s');
		$data = array('date' => $response_date, 
			'rate' => $response_value,
			'created_at' => $now,
			'updated_at' => $now
		);
		$result = $this->db->insert('exchange_rates', $data);
		if (!$result)
		{
			$this->handle_error('No se pudo actualizar el tipo de cambio en la base de datos. Informe al administrador.');	
		}
		return $result;
	}

	private function handle_error($message)
	{
		echo $message;
	}

/**
  Converts prima
 **/
	public function convert_prima($prima, $currency_from, $currency_to, $rate_date = null)
	{
		$this->db->select( 'rate' );
		$this->db->order_by('date', 'DESC');
		if ($rate_date)
		{
			$rate_query = $this->db->get_where('exchange_rates',
				"(rate != 'N/E') AND (date <= '$rate_date')", 1);
		}
		else
		{
			$rate_query = $this->db->get_where('exchange_rates',
				"rate != 'N/E'", 1);
		}
		if ($rate_query->num_rows() > 0)
		{
			$rate_row = $rate_query->row();
		}
		else
		{
			return FALSE;
		}
		if (($currency_from == '2') && ($currency_to == '1'))
			// converts from USD to MXN
		{
			return $rate_row->rate * $prima;
		}
		elseif  (($currency_from == '1') && ($currency_to == '2'))
		{
			// converts from MXN to USD
			return $prima / $rate_row->rate;
		}
		else
			return FALSE;
	}

/**
  Get rate list
 **/
	public function get($where = null, $fields = 'id, date, rate',
		$orderby = 'date desc', $limit = 25)
	{
		if ($fields)
		{
			$this->db->select($fields);
		}
		else
		{
			$this->db->select( '*' );
		}
		if ($where)
		{
			$this->db->where($where);
		}
		else
		{
			$this->db->where("date < '" . date('Y-m-d') . "'");
		}
		if ($orderby)
		{
			$this->db->order_by($orderby);
		}
		if ($limit)
		{
			$this->db->limit($limit);
		}
		$query = $this->db->get('exchange_rates');

		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return array();
		}
	}


}
/* End of file exchange_rate_model.php */
/* Location: ./application/models/exchange_rate_model.php */