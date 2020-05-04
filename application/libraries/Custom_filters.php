<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Custom filters class
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Development 
 * @author      Gustavo Sanchez
 * @link        http://www.avalogics.com
 */

class Custom_filters {
    public $Name;
    public $Active_page;
    public $Filters;
    public $Current_filters;

    //Render options
    public $General_open_tags;
    public $General_close_tags;
    public $Filter_open_tags;
    public $Filter_close_tags;


    public function __construct($params = array())
    {
    	$this->Filters = array();
        require_once("Custom_filters/Filter.php");
        $this->General_open_tags = "<div>";
        $this->General_close_tags = "</div>";
        $this->Filter_open_tags = "";
        $this->Filter_close_tags = "";

        if(isset($params["page"]))
       		$this->Active_page = $params["page"];

       	if(isset($params["general_open"]) && isset($params["general_close"])){
       		$this->General_open_tags = $params["general_open"];
       		$this->General_close_tags = $params["general_close"];
       	}

       	if(isset($params["filter_open"]) && isset($params["filter_close"])){
       		$this->Filter_open_tags = $params["filter_open"];
       		$this->Filter_close_tags = $params["filter_close"];
       	}

        if(isset($params["filter_values"]))
            $this->Current_filters = $params["filter_values"];
        
        if(isset($params["name"]) && $params["name"] == "general")
        	require_once("Custom_filters/filters_".$params["name"].".php");
    }

    public function prepare_filters(){
        foreach ($this->Filters as $filter) {
            $filter->prepare_options($this->Current_filters);
        }
    }

    public function execute_filters($section){
        foreach ($this->Filters as $filter) {
            $filter->execute_filter($section, $this->Current_filters);
        }
    }

    public function add_filter($params = array()){
    	$params["page"] = $this->Active_page;
    	$filter = new Filter($params);
    	$this->Filters[] = $filter;
    }

    public function create_text($params = array()){
    	$params["type"] = 1;
    	$this->add_filter($params);
    }

    public function create_dropdown($params = array()){
    	$params["type"] = 2;
    	$this->add_filter($params);
    }

    public function create_static_dropdown($params = array()){
    	$params["type"] = 3;
    	$this->add_filter($params);
    }

    public function create_date_period($params = array()){
    	$params["type"] = 4;
    	$this->add_filter($params);
    }

    public function create_external($params = array()){
    	$params["type"] = 5;
    	$this->add_filter($params);
    }

    public function set_array_defaults(&$external_defaults){
        $arr_defaults = $this->get_array_defaults();
        $external_defaults = array_merge($external_defaults, $arr_defaults);
    }

    public function set_filters_to_save(&$filters_to_save){
        $CI =& get_instance();
        foreach ($this->Filters as $filter) {
            if(isset($_POST["query"][$filter->Name]))
                $filters_to_save[$filter->Name] = $_POST["query"][$filter->Name];
        }
    }

    public function set_current_filters($filter_values){
        $this->Current_filters = $filter_values;
        $this->prepare_filters();
    }

    public function get_array_defaults(){
        $arr_defaults = array();
        foreach ($this->Filters as $filter) 
            $arr_defaults[$filter->Name] = $filter->Options_default;
        return $arr_defaults;
    }

    public function render(){
    	echo form_open('', 'id="filter-form"');
    	echo $this->General_open_tags;
    	$this->render_filters(TRUE);
    	echo $this->General_close_tags;
    	echo form_close();
    }

    public function render_filters($full_render = FALSE){
    	foreach ($this->Filters as $filter)
    		$filter->render($this->Filter_open_tags, $this->Filter_close_tags, $this->Current_filters);	
    }

    public function render_javascript(){
    	$select_flag = FALSE;
        $text_flag = FALSE;
        $js_str = "";
    	$js_str .= "<script>";
    	$js_str .= "\n  jQuery(document).on('ready', function(){\n";
    	foreach($this->Filters as $filter){
    		if($filter->Type == 1){
    			$list_name = $filter->Name."List";
                $filter_id = $filter->Element_id;
    			$list_array = array();
    			foreach ($filter->Options_values as $i => $value)
    				$list_array[] = $filter->Options_text[$i]." [ID: $value]"; 
                if(!$text_flag){
                    $js_str .= "jQuery('.filter-button').on('click', function(){
                            jQuery(this).closest('form').submit();
                        });
                        jQuery('.clear-button').on('click', function(){
                            var id=jQuery(this).attr('data-control');
                            console.log(id);
                            jQuery('#'+id).val('');
                            jQuery(this).closest('form').submit();
                        })
                    ";
                }
                $js_str .= "if(typeof split !== 'function'){
                        function split( val ) {
                            return val.split( /".'\n'."\s*/ );
                        }
                    }\n
                    if(typeof extractLast !== 'function'){
                        function extractLast( term ) {
                            return split( term ).pop();
                        }
                    }\n";
    			$js_str .= "var $list_name = ".json_encode($list_array).";\n";
    			$js_str .= "jQuery('#$filter_id')
                    .on( 'keydown', function( event ) {
                        if ( event.keyCode === jQuery.ui.keyCode.TAB &&
                            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
                            event.preventDefault();
                        }
                    })
                    .autocomplete({
                        minLength: 2,
                        source: function( request, response ) {
                            // delegate back to autocomplete, but extract the last term
                            response( jQuery.ui.autocomplete.filter(
                                $list_name, extractLast( request.term ) ) );
                        },          
                        focus: function() {
                            // prevent value inserted on focus
                            return false;
                        },
                        select: function( event, ui ) {
                            var terms = split( this.value );
                            // remove the current input
                            terms.pop();
                            // add the selected item
                            terms.push( ui.item.value );
                            // add placeholder to get the comma-and-space at the end
                            terms.push( '' );
                            this.value = terms.join( '".'\n'."' );
                            
                            jQuery(this).closest('form').submit();
                            return false;
                        }
                    });";
    		}
    		else if(in_array($filter->Type, array(2,3)) && !$select_flag){
    			$js_str .= "jQuery('.filter-select').on('change', function(){
					jQuery(this).closest('form').submit();
    			});";
    			$select_flag = TRUE;
    		}
    	}
    	$js_str .= "\n});\n";
    	$js_str .= "</script>";
        return $js_str;
    }

    public function get()
    {
    	echo "<pre>";
    	print_r($this);
    	echo "</pre>";
    }
}