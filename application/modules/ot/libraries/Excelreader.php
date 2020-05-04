<?php ini_set('max_execution_time',90000);
defined('BASEPATH') OR exit('No direct script access allowed');

class Excelreader {

	public $reader = null;
	public $reader_alt = null;
	public $file_path = null;

	private $file = null;

	private $directory;

	private $CI;
			
    public function __construct()
    {
		error_reporting(E_ALL ^ E_NOTICE);
		
		$this->directory = APPPATH.'modules/ot/assets/tmp/';
		
		@require 'spreadsheet-reader/SpreadsheetReader.php' ;
		//@require 'XLSXReader.php';

		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->model('usuarios/user');
		$this->CI->load->model('termometro/termometro_model');
         				
    }

	public function setInstance( $file = null ){
		
		if( empty( $file ) ) return false;
		
		if( !is_file( $this->directory . $file ) ) return false;
		
		$this->file = $file;
		
		$this->reader =  new SpreadsheetReader( $this->directory . $file );
		//$this->reader_alt = new XLSXReader( $this->directory . $file );
		$this->file_path = $this->directory . $file;
	}

	public function setSpreadsheetReader()
	{
		return $this->reader;
	}


    // Upload Files
	public function upload(){
				
        if( !isset( $_FILES['file'] ) or empty( $_FILES  ) ) return false;
		
		//if( $_FILES['file']['type'] != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ) return false;	
			
		// Setting file temporal file named
		$name = $_FILES['file']['name'];
		
		$name = strtr($name, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');

		$name = preg_replace('/([^.a-z0-9]+)/i', '_', $name);

		if( is_dir( $this->directory ) )
				if( !move_uploaded_file( $_FILES['file']['tmp_name'],  $this->directory  . $name ) ) return false;
		
		return $name;
		
    }

    public function readSheetData($name, $checkpoint = null)
    {
    	//version 3.0 del codigo del reader de 
    	putenv("PATH=/usr/bin/:" . exec('echo $PATH'));
    	$database_name = $this->CI->db->database;
    	$this->clear_data_selo($this->reader, $name); 

		$str = "python ".APPPATH."modules/ot/libraries/reader.py '".$database_name."' '".$name."' ".$this->file_path." 2>&1";
		var_dump($str);
    	$output = shell_exec($str);
    	error_log(print_r($output,true));

		return array('sheet_name' => $name,'status' => (string) substr($output, 0, -1));
    }
    public function set_last_update()
    {
    	$selo_date = "python ".APPPATH."modules/ot/libraries/reader_date.py ".$this->file_path." 2>&1";
    	$output = shell_exec($selo_date);
    	$this->insert_last_update_selo($output);
    }
    public function insert_last_update_selo($selo_date)
    {
    	$sql = "SELECT * FROM cache_updates WHERE type_update='selo_date';";
    	$query = $this->CI->db->query($sql);
        $res = $query->result_array();
        if( $res[0]['last_update']==null )
        {
        	$sql_query = "INSERT INTO cache_updates(last_update,type_update) VALUES('".$selo_date."','selo_date')";
        	$query_date = $this->CI->db->query($sql_query);
        } 
        else
        {
        	$sql_query = "UPDATE cache_updates SET last_update='".$selo_date."' WHERE type_update='selo_date'";
        	$query_date = $this->CI->db->query($sql_query);
        }
    }

    public function insert_raw_data_row(&$array_columns, &$data_row, &$array_fields_table, &$name_table)
    {
    	$row_array = array();
		//recorrer la fila para crear el array de datos
		for ($i=0; $i < count($array_columns); $i++) 
    	{
    		$field_array = array(); 
    		if ( in_array(strtolower($array_columns[$i]), $array_fields_table) )
    		{
    			if ($data_row[$i] == '')
    			{
    				$field_array =  array(strtolower($array_columns[$i]) => NULL);
    			}
    			else
    			{
    				$temp = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $data_row[$i]);
    				$field_array = array(strtolower($array_columns[$i]) => $temp);
    			}
    		}
    		$row_array = $row_array + $field_array;
    	}
    	$sql = $this->CI->db->insert_string($name_table,$row_array);
    	$query = $this->CI->db->query($sql);
    	unset($row_array);	
    }
    

    public function get_values_string($array)
    {
    	$str = "";
    	for ($i=0; $i<count($array); $i++)
    	{
    		if($i == count($array)-1)
    		{
    			$str = $str.'%s';
    		}
    		else
    		{
    			$str = $str.'%s,';
    		}
    	}
    	return $str;
    }
    public function read_data_cell(&$array_columns, &$data_row, &$name_table, &$sheets_interl, &$sheets_inter, &$date_columns, &$array_fields_table)
    {
    	//obtener el array de los datos de la fila
    	$row_array = $this->set_array_data($data_row,$array_columns, $date_columns, $array_fields_table);
    	/*
    	$array_agent = array();
    	switch ($name_table) {
    		case 'production_':
	    		$array_agent 		= $this->get_agent_id($row_array["fla_num"]);
	    		$array_gen   		= array('generation' => $this->CI->user->generationByAgentIdNew($row_array['femovibo'], $array_agent['agent_id'], 'vida'));
	    		$row_array   		= $row_array + $array_gen;	
	    		$aux1 		 		= explode("-", $row_array['inter']);
	    		$row_array['inter'] = substr($aux1[1], 1); 
	    		break;
    		case 'production_gmm_':
	    		$agent_uid   = explode(" ", $row_array["clave"]);
	    		$array_agent = $this->get_agent_id($agent_uid[0]);
	    		$array_gen   = array('generation' => $this->CI->user->generationByAgentIdNew($row_array['femovibo'], $array_agent['agent_id'], 'gmm'));
	    		$row_array   = $row_array + $array_gen;
	    		$aux1		 = explode("-", $row_array['clave']);
	    		$row_array['fla_num'] = str_replace(' ', '', $aux1[0]); 
	    		$row_array['clave']   = substr($aux1[1], 1);
	    		break;
    		case 'siniestralidad_':
	    		$array_agent = $this->get_agent_id($row_array['agente']);
	    		break;
    		case 'asegurados_':
	    		$agent_uid   = substr($row_array["agente"], 0,-3);
	    		$array_agent = $this->get_agent_id($agent_uid);
	    		break;
    		case 'bona_sa':
	    		$agent_uid   = substr($row_array["lider"], 0,-3);
	    		$array_agent = $this->get_agent_id($agent_uid);
	    		break;
    		default:
	    		if(in_array($name, $sheets_inter))
	    		{
	    			$agent_uid   = substr($row_array["inter"], 0,-3);
	    			$array_agent = $this->get_agent_id($agent_uid);
	    		}
	    		if(in_array($name, $sheets_interl))
	    		{
	    			$agent_uid   = substr($row_array["interl"], 0,-3);
	    			$array_agent = $this->get_agent_id($agent_uid);
	    		}
    		break;
    	}
    	$row_array = $row_array + $array_agent;
    	*/
    	$sql = $this->CI->db->insert_string($name_table,$row_array);
    	$query = $this->CI->db->query($sql);
    	unset($row_array);	
    }
    public function set_array_data(&$data_row, &$array_columns, &$date_columns, &$array_fields_table)
    {
		//array de la fila de datos
		$row_array = array();
		//recorrer la fila para crear el array de datos
		for ($i=0; $i < count($array_columns); $i++) 
    	{
    		$field_array = array(); 
    		if ( in_array(strtolower($array_columns[$i]), $array_fields_table) )
    		{
    			if ($data_row[$i] == '')
    			{
    				$field_array =  array(strtolower($array_columns[$i]) => NULL);
    			}
    			else
    			{
    				$temp = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $data_row[$i]);
    				$field_array = array(strtolower($array_columns[$i]) => $temp);
    			}
    		}
    		$row_array = $row_array + $field_array;
    	}
    	return $row_array;
    }
    public function clear_data_selo($reader, $name_sheet)
    {
    	//Variable extras
    	$date_columns  = array('fec_cnsf','fec_emis','fecontbo','fecha','fec_conec','fec_act','fecconec','fecsis','femovibo','fefipebo');
    	$sheets_year   = array('bona','bong_sa','produccion_sa','siniestralidad_','asegurados_','BONA_SA');

    	// Get if SELO id from 2019
    	$is_selo_new = $this->get_year_selo($reader);

    	// Get index of sheet and change reader to that sheet
    	$sheets 	 = $reader->Sheets();
    	$sheet_index = array_search($name_sheet, $sheets);
    	$reader->ChangeSheet($sheet_index);

    	//Get columns of sheet and columns of bd table
    	$aux_columns = $reader->current();
		$columns_arr = $this->refactor_columns_array($aux_columns, $name_sheet);
		$columns_tab = $this->get_columns_array($name_sheet);

		//Get data of first row
		$reader->next();
		$first_row = $reader->current();
		$array_row = $this->set_array_data($first_row,$columns_arr, $date_columns, $columns_tab);


		//Get year/s
		if (in_array($name_sheet, $sheets_year))
		{
			$year = substr($array_row['periodo'], 1);
			$next_year = (int) $year;
			$next_year = $next_year + 1;
			$name_table = $this->get_name_sheet($name_sheet);
			$this->CI->db->like('periodo', $year);
			$this->CI->db->delete($name_table);
			error_log(print_r($this->CI->db->last_query(),true));
			if($is_selo_new)
			{
				$this->CI->db->like('periodo', $next_year);
				$this->CI->db->delete($name_table);
				error_log(print_r($this->CI->db->last_query(),true));
			}
		}
		else
		{
			$year = substr($array_row['periodo'], 1);
			$name_table = $this->get_name_sheet($name_sheet);
			$this->CI->db->like('periodo', $year);
			$this->CI->db->delete($name_table);
			error_log(print_r($this->CI->db->last_query(),true));
		}
    }
    public function get_year_selo($reader)
    {
    	// Get index of sheet and change reader to that sheet
    	$sheets 	 = $reader->Sheets();
    	$sheet_index = array_search('produccion_', $sheets);
    	$reader->ChangeSheet($sheet_index);

    	//Get columns of sheet and columns of bd table
    	$aux_columns = $reader->current();
		$columns_arr = $this->refactor_columns_array($aux_columns, 'produccion_');
		$columns_tab = $this->get_columns_array('produccion_');

		//Get data of first row
		$reader->next();
		$first_row = $reader->current();
		$array_row = $this->set_array_data($first_row,$columns_arr, $date_columns, $columns_tab);

		$year = substr($array_row['periodo'], 1);
		if ($year == '2018')
		{
			return false;
		}
		else
		{
			return true;
		}
    }
    public function get_name_sheet($name_sheet)
    {
    	$names_traduct	= array('produccion_' 		=>'production_',
								'bona' 				=>'bona_t',
								'bong_sa' 			=>'bong_sa',
								'negocios_' 		=>'business_',
								'conservacion_' 	=>'conservation_',
								'puntos_' 			=>'points_',
								'siniestralidad_'	=>'siniestralidad_',
								'asegurados_' 		=>'asegurados_',
								'bona_vi' 			=>'bona_vi',
								'produccion_sa' 	=>'production_gmm_',
								'asegurados_' 		=>'asegurados_',
								'BONA_SA'			=>'bona_sa'
		);
		return $names_traduct[$name_sheet];
    }
    public function get_agent_id(&$uid)
    {
    	$array_agent = array();
    	$this->CI->db->select('distinct(agents.id) as agent_id');
    	$this->CI->db->from('agents');
    	$this->CI->db->join('agent_uids','agent_uids.agent_id = agents.id');
    	$this->CI->db->where('agent_uids.uid like "%'. $uid .'" or agent_uids.uid like "'. $uid .'%"');
		$query = $this->CI->db->get();
		

    	if($query->num_rows() > 0)
    	{
    		foreach ($query->result() as $row) 
    		{
    			$array_agent = array("agent_id" => $row->agent_id);
    		}
    	}
    	$query->free_result();

    	return $array_agent;
    }
    public function get_columns_array($name)
    {
    	$table_name = $this->get_name_sheet($name);
    	$fields_array = $this->CI->db->list_fields($table_name);
    	return $fields_array;
    }
    public function refactor_columns_array($columns_array, $name_table)
    {
    	if($name_table == 'negocios_') 
    	{ 
    		$columns_array[6] = "neg_camp_mas_pai"; 
    	}
    	else
    	{
    		return $columns_array;
    	}
    	return $columns_array;
    }

	public function reader()
	{
		
		if( !is_object( $this->reader ) ) return false;	
		
        $data = array();
        $Sheets = $this->reader->Sheets();
			
        foreach ($Sheets as $Index => $Name)
		{
			$this->reader->ChangeSheet($Index);
			

			foreach ($this->reader as $Key => $Row)
			{
                echo $Key.': ';
				if ($Row)
				{
					foreach($Row as $Value){
						//$Test[$Name][$Key] = $Row;
						//echo $Value;
					}
					$data[$Name][$Key] = $Row;
				}
			}
		}						
		return $data;
	}
	public function get_reader()
	{
		return $this->reader;
	}
	public function set_reader($reader)
	{
		$this->reader = $reader;
	}
}

?>