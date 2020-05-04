<?php
/**
 * Filter class
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Development 
 * @author      Gustavo Sanchez
 * @link        http://www.avalogics.com
 */
class Filter
{
	public $Active_page;
	public $Name;
	public $Label;
	public $Element_id;
	public $Element_class;
	public $Element_style;
	public $Element_placeholder;
	public $Element_template;
	public $Type; // 1. Texto, 2. Select dbfield, 3. Select static, 4. Date period, 5. External filter
	public $Filter_dbfield;

	public $Options_dbtable;
	public $Options_dbfield_text;
	public $Options_dbfield_values;
	public $Options_dbfilter;
	public $Options_text;
	public $Options_values;
	public $Options_default;

	/*
	 * Filter process field: contains the instructions of his functionality in each module (Model, Controller and view  
	 * render)
	 * @prototype array(
 	 *		"page_name" => array(
 	 *			"section" => array(
 	 *				"relationships" => array()
 	 *				"filters" => array()
 	 *				"after_query" => true/false
 	 *			)
 	 *		)
 	 *		...
 	 *			 
 	 * 	)
	 */
	public $Filter_process;
	var $CI;

	function __construct($params = array())
	{
		$this->CI =& get_instance();
		$this->Type = 1;
		$this->Options_dbfield_values = "id";
		$this->Options_text = array();
		$this->Options_values = array();
		$this->Options_default = "";
		$this->Options_dbfilter = array();
		$this->Filter_process = array();
		$this->Element_placeholder = "";
		$this->Element_template = "#{input}";

		if(isset($params["name"]))
       		$this->Name = $params["name"];

       	if(isset($params["id"]))
       		$this->Element_id = $params["id"];

       	if(isset($params["class"]))
       		$this->Element_class = $params["class"];

       	if(isset($params["style"]))
       		$this->Element_style = $params["style"];

       	$this->Label = $this->Name;
       	if(isset($params["label"]))
       		$this->Label = $params["label"];

		if(isset($params["page"]))
       		$this->Active_page = $params["page"];

       	if(isset($params["type"]) && in_array($params["type"], array(1,2,3,4)))
       		$this->Type = $params["type"];

       	if(isset($params["filter_db"]))
       		$this->Filter_dbfield = $params["filter_db"];

       	if(isset($params["odb_table"]))
       		$this->Options_dbtable = $params["odb_table"];

       	if(isset($params["odb_text"]))
       		$this->Options_dbfield_text = $params["odb_text"];

       	if(isset($params["odb_values"]))
       		$this->Options_dbfield_values = $params["odb_values"];

       	if(isset($params["odb_filter"]))
       		$this->Options_dbfilter = $params["odb_filter"];
       		
       	if(isset($params["o_text"]) && is_array($params["o_text"]))
       		$this->Options_text = $params["o_text"];

       	if(isset($params["o_values"]) && is_array($params["o_values"]))
       		$this->Options_values = $params["o_values"];

       	if(isset($params["default"]))
       		$this->Options_default = $params["default"];

       	if(isset($params["process"]) && is_array($params["process"]))
       		$this->Filter_process = $params["process"];

       	if($this->Active_page){
       		if(isset($this->Filter_process[$this->Active_page]["elm_placeholder"]))
       			$this->Element_placeholder = $this->Filter_process[$this->Active_page]["elm_placeholder"];
       		if(isset($this->Filter_process[$this->Active_page]["elm_label"]))
       			$this->Label = $this->Filter_process[$this->Active_page]["elm_label"];
       		if(isset($this->Filter_process[$this->Active_page]["elm_template"]))
       			$this->Element_template = $this->Filter_process[$this->Active_page]["elm_template"];
       		if(isset($this->Filter_process[$this->Active_page]["odb_filter"]))
       			$this->Options_dbfilter = $this->Filter_process[$this->Active_page]["odb_filter"];
       	}
	}

	public function prepare_options($filters){
		if($this->Type == 1 || $this->Type == 2){
			$this->CI->db->select($this->Options_dbfield_values." options_value, ".$this->Options_dbfield_text. " options_text");
			if(!empty($this->Options_dbfilter)){
				foreach ($this->Options_dbfilter as $field => $filter) {
					if(!is_array($filter))
					{
						$where_val = isset($filters[$filter]) ? $filters[$filter] : $filter;
						$this->CI->db->where($field, $where_val);
					}
					else{
						$arr_in = array();
						foreach ($filter as $single_filter)
							$arr_in[] = isset($filters[$single_filter]) ? $filters[$single_filter] : $single_filter;
						
						$this->CI->db->where_in($field, $arr_in);
					}
				}
			}

			$query = $this->CI->db->get($this->Options_dbtable);

			$result = $query->result_array();
			foreach ($result as $row) {
				$this->Options_values[] = $row["options_value"];
				$this->Options_text[] = $row["options_text"];
			}
		}
	}

	public function execute_filter($section, $current_filters){
		if(isset($this->Filter_process[$this->Active_page]["sections"][$section])){
			$relationships = $this->Filter_process[$this->Active_page]["sections"][$section]["relationships"];
			$filters = $this->Filter_process[$this->Active_page]["sections"][$section]["filters"];

			$filters_fields = array_keys($filters);
			$filters_queries = array_values($filters);

			$put_filters = TRUE;

			foreach ($filters_queries as $i => $query_index) {
				if($i == 0){
					$where_val = isset($current_filters[$query_index]) ? $current_filters[$query_index]: $query_index;
					if(empty($where_val))
						$put_filters = FALSE;
					else{
						foreach ($relationships as $field => $join)
							$this->CI->db->join($field, $join);
					}
				}
				if($put_filters){
					if(!is_array($query_index)){
						$where_val = isset($current_filters[$query_index]) ? $current_filters[$query_index]: $query_index;
						if(!empty($where_val)){
							$this->CI->db->where($filters_fields[$i], $where_val);
						}
					}
					else{
						$in_arr = array();
						foreach ($query_index as $query_single) 
							$in_arr[] = isset($current_filters[$query_single]) ? $current_filters[$query_single]: $query_single;
						$this->CI->db->where_in($filters_fields[$i], $in_arr);
					}
				}
			}
		}
	}

	public function render($open = "", $close = "", $filter_values = array()){
		$value = $this->Options_default;
		if(isset($filter_values[$this->Name]))
			$value = $filter_values[$this->Name];

		echo $open;
		$input = "";
		// IF type is text
		if($this->Type == 1){
			$arr_text = array(
				"name" => "query[".$this->Name."]",
				"placeholder" => !empty($this->Element_placeholder) ? $this->Element_placeholder : $this->Label,
				"id" => $this->Element_id,
				"class" => $this->Element_class,
				"style" => $this->Element_style,
				"rows" => 1,
				"cols" => 20,
				"autocomplete" => "off",
				"value" => $value
			);
			$input = form_textarea($arr_text);
			$input .= $open;
			$input .= "\n<i style='cursor: pointer; vertical-align: top' class='icon-filter filter-button' title='Filtrar' data-control='".$this->Element_id."'></i>
    			<br>
    			<i style='cursor: pointer;' class='icon-list-alt clear-button' title='Mostrar ".$this->Name."' data-control='".$this->Element_id."'></i>\n";
			$input .= $close;
		}
		// If type is dropdown
		else if(in_array($this->Type, array(2,3))){
			$options = $this->get_options_array();
			
			$input = form_dropdown("query[".$this->Name."]", $options, $value, "id='".$this->Element_id."' class='".$this->Element_class." filter-select' style='" . $this->Element_style ."'");	
		}
		if(!empty($input)){
			$converted_template = str_replace("#{input}", $input, $this->Element_template);
			$converted_template = str_replace("#{label}", $this->Label, $converted_template);
			echo $converted_template;
		}

		echo $close;
	}

	public function get_options_array(){
		$return_array = array();

		// If is type is dropdown
		if(in_array($this->Type, array(2,3))){
			$return_array[$this->Options_default] = !empty($this->Element_placeholder) ? $this->Element_placeholder : $this->Label;
			foreach ($this->Options_values as $i => $val)
				$return_array[$val] = $this->Options_text[$i];
		}
		return $return_array;
	}
}