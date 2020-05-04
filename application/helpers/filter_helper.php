<?php
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

/*
 Get filter period stored in ( pseudo ) session if any
 Returns the value of the option in the dropdown used to select filter period
*/
if ( ! function_exists('get_filter_period'))
{
	function get_filter_period()
	{
		$CI =& get_instance();
		$saved_period = $CI->default_period_filter;
		if ($saved_period === FALSE)
			$saved_period = 2;	// custom period by default
		return $saved_period;
	}
}

/*
 Get the default selected html property of the period dropdown options 
 Returns an array
*/
if ( ! function_exists('get_selected_filter_period'))
{
	function get_selected_filter_period()
	{
		$result = array(1 => '', 2 => '', 3 => '', 4 => '', 5 => '');
		$option_selected = get_filter_period();
		if ( isset($result[$option_selected]) )
			$result[$option_selected] = ' selected="selected"';
		else
			$result[2] = ' selected="selected"';
		return $result;
	}
}
/*
 Store filter period in session
 (what is stored is the value of the option in the dropdown used to select filter period)
*/
if ( ! function_exists('set_filter_period'))
{
	function set_filter_period( $to_save )
	{
		$CI =& get_instance();
		if (( $to_save >= 1 ) && ( $to_save <= 5 ) && ($CI->period_filter_for !== FALSE))
		{
//			$CI->session->set_userdata('default_period_filter', $to_save);
			$CI->session->set_userdata('default_period_filter_' . $CI->period_filter_for, $to_save);
			$CI->default_period_filter = $to_save;
		}
	}
}
/*
Display custom filter period
*/
if ( ! function_exists('show_custom_period'))
{
	function show_custom_period()
	{
		$CI =& get_instance();
		$data = array(
			'from' => $CI->custom_period_from,
			'to' => $CI->custom_period_to
		);
		if ( ( $data['from'] === FALSE ) || ( $data['to'] === FALSE ) )
		{
			$data['from'] = date('Y-m-d');
			$data['to'] = $data['from'];
		}
		return $CI->load->view('custom_period', $data, TRUE);
	}
}
/*
	Update custom filter period in session
*/
if ( ! function_exists('update_custom_period'))
{
	function update_custom_period($from, $to, $update_filter_for = TRUE)
	{
		$result = 0;
		if (($from !== FALSE) && ($to !== FALSE))
		{
			$CI =& get_instance();
			$from_array = explode('-', $from);
			$to_array = explode('-', $to);
			if ( (count($from_array) == 3) && (count($to_array) == 3) &&
				checkdate ( $from_array[1], $from_array[2], $from_array[0]) && 
				checkdate ( $to_array[1], $to_array[2], $to_array[0]) &&
				( $CI->period_filter_for !== FALSE ))
			{
				$new_sess_data = array(
					'custom_period_from_' . $CI->period_filter_for => $from,
					'custom_period_to_' . $CI->period_filter_for => $to,
				);
				if ($update_filter_for)
				{
					$new_sess_data['default_period_filter_' . $CI->period_filter_for] = 4;
					$CI->default_period_filter = 4;
				}
				$CI->session->set_userdata( $new_sess_data);
				$CI->custom_period_from = $from;
				$CI->custom_period_to = $to;

				$result = 1;
			}
		}
		return $result;
	}
}
/*
	Get start and end date depending on period selection (for activity report)
*/
if ( ! function_exists('get_period_start_end'))
{
	function get_period_start_end(&$selection)
	{
		if (!isset($selection['periodo']) || !isset($selection['begin']) || !isset($selection['end']))
			return;
		$current_month = date('m');
		$current_year = date('Y');
		switch ($selection['periodo']) 
		{
			case 1: // Month
				$selection['begin'] = sprintf("%s-%s-01", $current_year, $current_month);
				$selection['end'] = sprintf("%s-%s-%s", $current_year, $current_month, date('t'));
				break;
			case 2: // Week
				$from_to_time = strtotime($selection['begin']);
				$to_to_time = strtotime($selection['end']);
				if (($from_to_time !== -1) && ($to_to_time !== -1) && ($from_to_time <= $to_to_time))
				{
					$selection['begin'] = date('Y-m-d', $from_to_time );
					$selection['end'] = date('Y-m-d', $to_to_time );					
				}
				break;	
			case 3: // Year
				$selection['begin'] = sprintf("%s-01-01", $current_year);
				$selection['end'] = sprintf("%s-12-31", $current_year);
				break;
			case 4: // Custom
				$CI =& get_instance();
				$in_session = array(
					'from' => $CI->custom_period_from,
					'to' => $CI->custom_period_to
				);
				if ( ( $in_session['from'] === FALSE ) || ( $in_session['to'] === FALSE ) )
				{
					$in_session['from'] = date('Y-m-d');
					$in_session['to'] = $data['from'];
				}
				$selection['begin'] = $in_session['from'];
				$selection['end'] = $in_session['to'];
				break;
			default:
				break;
		}
	}
}

/*
	Get start and end date depending on new (!!!) period selection
*/
if ( ! function_exists('get_new_period_start_end'))
{
	function get_new_period_start_end(&$selection)
	{
		if (!isset($selection['periodo']) || !isset($selection['begin']) || !isset($selection['end']))
			return;
		$current_month = date('m');
		$current_year = date('Y');
		switch ($selection['periodo']) 
		{
			case 1: // Month
				$selection['begin'] = sprintf("%s-%s-01", $current_year, $current_month);
				$selection['end'] = sprintf("%s-%s-%s", $current_year, $current_month, date('t'));
				break;
			case 2: // Trimestre (cannot select Cuatrimestre)
				$mes = date('m');
				$trimestre = floor( ( $mes - 1 ) / 3) + 1;
				$begin_end = get_tri_cuatrimester( $trimestre, 'trimestre' );
				$selection['begin'] = $begin_end['begind'];
				$selection['end'] = $begin_end['end'];
				break;	
			case 3: // Year
				$selection['begin'] = sprintf("%s-01-01", $current_year);
				$selection['end'] = sprintf("%s-12-31", $current_year);
				break;
			case 4: // Custom
				$CI =& get_instance();
				$in_session = array(
					'from' => $CI->custom_period_from,
					'to' => $CI->custom_period_to
				);
				if ( ( $in_session['from'] === FALSE ) || ( $in_session['to'] === FALSE ) )
				{
					$in_session['from'] = date('Y-m-d');
					$in_session['to'] = $data['from'];
				}
				$selection['begin'] = $in_session['from'];
				$selection['end'] = $in_session['to'];
				break;
			case 5: // Week???
				break;			
			default:
				break;
		}
	}
}

/*
 Store ot/reporte/html filter fields other than the period in session
*/
if ( ! function_exists('set_ot_report_filter'))
{
	function set_ot_report_filter( $to_save, $agents_in_db )
	{
		if (!$to_save)
			return;
		$CI =& get_instance();

		$to_check_array2 = array();
		if (isset($to_save['agent_name']))
		{
			$to_check_array1 = explode("\n", $to_save['agent_name']);
			$to_replace = array(']', "\n", "\r");
			foreach ($to_check_array1 as $value)
			{
				$pieces = explode( ' [ID: ', $value);
				if (isset($pieces[1]))
				{
					$pieces[1] = str_replace($to_replace, '', $pieces[1]);
					if (isset($agents_in_db[$pieces[1]]) && !isset($to_check_array2[$pieces[1]]))
						$to_check_array2[] = $pieces[1];
				}
			}
		}
		$to_save['agent_name'] = implode('|', $to_check_array2);
		$CI->session->set_userdata('ot_r_misc_filter', $to_save);
		$CI->ot_r_misc_filter = $to_save;
	}
}

/*
 Store filter fields other than the period in session (a more generic version of set_ot_report_filter()
*/
if ( ! function_exists('generic_set_report_filter'))
{
	function generic_set_report_filter( $to_save, $agents_in_db )
	{
		if (!$to_save)
			return;
		$CI =& get_instance();

		$to_check_array2 = array();
		if (isset($to_save['agent_name']))
		{
			$to_check_array1 = explode("\n", $to_save['agent_name']);
			$to_replace = array(']', "\n", "\r");
			foreach ($to_check_array1 as $value)
			{
				$pieces = explode( ' [ID: ', $value);
				if (isset($pieces[1]))
				{
					$pieces[1] = str_replace($to_replace, '', $pieces[1]);
					if (isset($agents_in_db[$pieces[1]]) && !isset($to_check_array2[$pieces[1]]))
						$to_check_array2[] = $pieces[1];
				}
			}
		}
		$to_save['agent_name'] = implode('|', $to_check_array2);
		$CI->session->set_userdata($CI->misc_filter_name, $to_save);
		$CI->misc_filters = $to_save;
	}
}

/*
 Get filter fields other than the period (a more generic version than get_ot_report_filter()
*/
if ( ! function_exists('get_generic_filter'))
{
	function get_generic_filter(&$other_filters, $agents_in_db)
	{
		$CI =& get_instance();
		if ( $CI->misc_filters !== FALSE )
		{
			foreach ($CI->misc_filters as $key => $value)
			{
				if ($key != 'agent_name')
					$other_filters[$key] = $value;
				else
				{
					$agent_names_in_session = explode('|', $value);
					foreach ($agent_names_in_session as $agent_value)
					{
						if (isset($agents_in_db[$agent_value]))
						{
							$other_filters['agent_name'] .= $agents_in_db[$agent_value] . " [ID: $agent_value]\n";
						}
					}
				}
			}
//			$result = array_merge($other_filters, $CI->ot_r_misc_filter);
		}
	}
}
/*
 Get ot/reporte/html filter fields other than the period
*/
if ( ! function_exists('get_ot_report_filter'))
{
	function get_ot_report_filter(&$other_filters, $agents_in_db)
	{
		$CI =& get_instance();
		if ( $CI->ot_r_misc_filter !== FALSE )
		{
			foreach ($CI->ot_r_misc_filter as $key => $value)
			{
				if ($key != 'agent_name')
					$other_filters[$key] = $value;
				else
				{
					$agent_names_in_session = explode('|', $value);
					foreach ($agent_names_in_session as $agent_value)
					{
						if (isset($agents_in_db[$agent_value]))
						{
							$other_filters['agent_name'] .= $agents_in_db[$agent_value] . " [ID: $agent_value]\n";
						}
					}
				}
			}
		}
	}
}

/*
 Builds the javascript to handle agent autocomplete
*/
if ( ! function_exists('get_agent_autocomplete_js'))
{
	function get_agent_autocomplete_js($agent_array, $form_selector = '#form', $submit_selector = '.submit-form',
		$clear_selector = '#clear-agent-filter', $agent_selector = '#agent-name')
	{
		$agent_multi = array();
		foreach ( $agent_array as $key => $value )
		{
			$agent_multi[] = "\n'$value [ID: $key]'";
		}
		$inline_js =
		'<script>
		var agentList = [' . implode(',', $agent_multi) . '
		];
	$( document ).ready( function(){
		function split( val ) {
			return val.split( /\n\s*/ );
		}
		function extractLast( term ) {
			return split( term ).pop();
		}
		$( "' . $submit_selector . '").bind("click", function( event ) {
			$( "' . $form_selector . '").submit();
		})
		$( "' . $clear_selector . '").bind("click", function( event ) {
			$( "' . $agent_selector . '" ).val("");
			$( "' . $form_selector . '").submit();
		})
		$( "' . $agent_selector . '" )
		// don\'t navigate away from the field on tab when selecting an item
			.bind( "keydown", function( event ) {
				if ( event.keyCode === $.ui.keyCode.TAB &&
					$( this ).data( "ui-autocomplete" ).menu.active ) {
					event.preventDefault();
				}
			})
/*			.bind( "change", function( event ) {
alert("changed!");
			})*/
			.autocomplete({
				minLength: 0,
				source: function( request, response ) {
					// delegate back to autocomplete, but extract the last term
					response( $.ui.autocomplete.filter(
						agentList, extractLast( request.term ) ) );
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
					terms.push( "" );
					this.value = terms.join( "\n" );
					$( "' . $form_selector . '").submit();
					return false;
				}
			})
	});
</script>
';
		return ($inline_js);
	}
}

/*
Display fields related to period filter selection
*/
if ( ! function_exists('show_period_fields'))
{
	function show_period_fields($filter_for, $ramo = 1)
	{
// $filter_for = 'ot_index', 'ot_reporte', 'activities_report', 'agent_profile' etc. depending on the page
		$CI =& get_instance();
		$CI->load->helper('activities/date_report');
		$default_week = get_calendar_week();
		$selected_period_filter = $CI->default_period_filter;
		$data = array(
			'from' => $CI->custom_period_from,
			'to' => $CI->custom_period_to,
			'filter_for' => $filter_for,
			'ramo' => $ramo,
			'begin' => $default_week['start'],
			'end' => $default_week['end']
		);
		if ( ( $data['from'] === FALSE ) || ( $data['to'] === FALSE ) )
			$selected_period_filter = 2;
		
		switch ($selected_period_filter)
		{
			case 1: // month
				$data['from'] = date( 'Y' ) . '-' . date( 'm' ) . '-01';
				$data['to'] = date( 'Y-m-d');
			break;
			case 2: // trimestre or cuatrimestre
				$CI->load->helper('tri_cuatrimester');
				if ($ramo == 1) // Vida -> current trimestre
				{
					$rank = floor((date('m') - 1) / 3) + 1;
					$result = get_tri_cuatrimester( $rank, 'trimestre' );
				}
				else	// -> current cuatrimestre
				{
					$rank = floor((date('m') - 1) / 4) + 1;
					$result = get_tri_cuatrimester( $rank, 'cuatrimestre' );
				}
				$data['from'] = substr($result['begind'], 0, 10);
				$data['to'] = substr($result['end'], 0, 10);
				break;
			case 3: // Year
				$data['from'] = date( 'Y' ) . '-01-01';
				$data['to'] = date( 'Y-m-d');
			break;
			default:
			break;
		}		
		return $CI->load->view('select_period', $data, TRUE);
	}
}

/*
	Get filtered data for ot.html
*/
if ( ! function_exists('get_ot_data'))
{
	function get_ot_data(&$other_filters, $access_all)
	{
		$CI =& get_instance();

		$data = array();
		$default_filter = get_filter_period();
		$new_other_filters = array(
			'user' => 'mios',
			'id' => '',
			'ramo' => '',
			'gerente' => '',
			'agent' => '',
			'patent_type' => '',
			'work_order_status_id' => '',
		);
		get_generic_filter($new_other_filters, array());
		if (isset($other_filters['coordinators']))
			$other_filters = array_merge($new_other_filters, $other_filters);

		if ( !empty( $_POST ) )
		{
			if ( isset($_POST['periodo']) && $CI->form_validation->is_natural_no_zero($_POST['periodo']) &&
				($_POST['periodo'] <= 4) )
				set_filter_period($_POST['periodo']);

//			$filters_to_save = array();
			if ( isset($_POST['user']) && 
				(($_POST['user'] == 'mios') || ($_POST['user'] == 'todos')) )
				$other_filters['user'] = $_POST['user'];
				
			if ( isset($_POST['id']) )
				$other_filters['id'] = $_POST['id'];

			if ( isset($_POST['ramo']) && (($CI->form_validation->is_natural_no_zero($_POST['ramo']) &&
				($_POST['ramo'] <= 3)) || (($_POST['ramo']) === '')) )
				$other_filters['ramo'] = $_POST['ramo'];

			if ( isset($_POST['gerente']) && 
				(($_POST['gerente'] === '') || 
				$CI->form_validation->is_natural_no_zero($_POST['gerente']))
				)
				$other_filters['gerente'] = $_POST['gerente'];

			if ( isset($_POST['agent']) &&
				( ($_POST['agent'] === '') || 
				$CI->form_validation->is_natural_no_zero($_POST['agent']))
				)
				$other_filters['agent'] = $_POST['agent'];

			if ( isset($_POST['patent_type']) &&
				( ($_POST['patent_type'] === '') || 
				 $CI->form_validation->is_natural_no_zero($_POST['patent_type']) )
				)
				$other_filters['patent_type'] = $_POST['patent_type'];

			if ( isset($_POST['work_order_status_id']))
				$other_filters['work_order_status_id'] = $_POST['work_order_status_id'];
			if($CI->load->is_loaded('custom_filters')){
				$CI->custom_filters->set_filters_to_save($other_filters);
				$CI->custom_filters->set_current_filters($other_filters);
			}
			generic_set_report_filter( $other_filters, array() );
		}

		$query = array_merge($other_filters, array('periodo' => $CI->default_period_filter));
		$data = $CI->work_order->find_new( $query, $access_all );
		if ($data)
		{
			$gerentes = $CI->user->getSelectsGerentes2();
			$gerente_array = array();
			foreach ($gerentes as $gerente)
				$gerente_array[$gerente['id']] = $gerente['name'];

			foreach ($data as $key1 => $value1)
			{
				foreach ($value1['agents'] as $key2 => $value2)
				{
					if (isset($gerente_array[$value2['manager_id']]))
						$data[$key1]['agents'][$key2]['manager_name'] = 
							$gerente_array[$value2['manager_id']];
					else
						$data[$key1]['agents'][$key2]['manager_name'] = '';
				}
			}
		}
		return $data;
	}
}
/*
	Get coordinator filter form field
	TODO: use this in the operation module also
*/
if ( ! function_exists('get_coordinator_form_field'))
{
	function get_coordinator_form_field($selected_coordinators)
	{
		$CI =& get_instance();
		$selected_coordinator_text	= '';
		$roles = $CI->rol->get_user_roles_where(array(
			'modules.name' => 'Orden de trabajo', 'actions.name' => 'Crear'));
		foreach ($roles as $role)
			$where_in[] = $role->user_role_id;
		$where_in = array_unique(array_merge($where_in, array('2')));
		$coordinators_in_db = $CI->user->get_users_with_role($where_in);
		$coordinators_array = array();
		foreach ($coordinators_in_db as $value_c)
		{
			$displayed_name = $value_c['company_name'] ?
				$value_c['company_name'] :
				$value_c['name'] . ' ' .  $value_c['lastnames'];
			$coordinators_array[$value_c['id']] = $displayed_name;
		}
		$coordinators_multi = array();
		foreach ( $coordinators_array as $key => $value )
		{
			$coordinators_multi[] = "\n'$value [ID: $key]'";
			if (in_array($key, $selected_coordinators)) 
				$selected_coordinator_text .= $value .  " [ID: $key]\n";
		}
		if (!$selected_coordinator_text && isset($CI->operation_user) && isset($CI->operation_user->displayed_user_name))
			$selected_coordinator_text = $CI->operation_user->displayed_user_name . ' [ID: ' . $CI->sessions['id'] . "]\n";

		$result = 
'
<script type="text/javascript">
	$( document ).ready( function(){

		var coordinatorList = [' . implode(',', $coordinators_multi) . '
		];

		function split( val ) {
			return val.split( /\n\s*/ );
		}
		function extractLast( term ) {
			return split( term ).pop();
		}
		$( ".submit-form").bind("click", function( event ) {
			submitThisForm();
		})
		$( "#clear-coordinator-filter").bind("click", function( event ) {
			$( "#coordinador-name" ).val("");
			submitThisForm();
		})
		$( "#coordinador-name" )
		// don\'t navigate away from the field on tab when selecting an item
			.bind( "keydown", function( event ) {
				if ( event.keyCode === $.ui.keyCode.TAB &&
					$( this ).data( "ui-autocomplete" ).menu.active ) {
					event.preventDefault();
				}
			})
			.autocomplete({
				minLength: 0,
				source: function( request, response ) {
					// delegate back to autocomplete, but extract the last term
					response( $.ui.autocomplete.filter(
						coordinatorList, extractLast( request.term ) ) );
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
					terms.push( "" );
					this.value = terms.join( "\n" );
					submitThisForm();
					return false;
				}
			})
	});
</script>
';
		$CI->coordinator_select = $CI->load->view('operations/coordinator_select', array(
			'coordinators' => $coordinators_in_db,
			'selected_coordinator_text' => $selected_coordinator_text,
			), TRUE);

		return $result;
	}
}

/*
	Extract coordinator name
	TODO: use this in the operation module also (_read_stats)
*/
if ( ! function_exists('extract_coordinator_name'))
{
	function extract_coordinator_name($coordinator_name)
	{
		$to_check_array2 = array();
		$to_check_array1 = explode("\n", $coordinator_name);
		$to_replace = array(']', "\n", "\r");
		foreach ($to_check_array1 as $value)
		{
			$pieces = explode( ' [ID: ', $value);
			if (isset($pieces[1]))
			{
				$pieces[1] = (int)str_replace($to_replace, '', $pieces[1]);
				if (!isset($to_check_array2[$pieces[1]]))
					$to_check_array2[] = $pieces[1];
			}
		}
		return implode('_', $to_check_array2);
	}
}

if (!function_exists('makeDropdown'))
{

	function makeDropdown($arr, $idColumn, $valColumn, $all = TRUE)
	{
		$returnArr = array();
		if($all)
			$returnArr[""] = "Todos";
		foreach ($arr as $row)
			$returnArr[$row[$idColumn]] = $row[$valColumn];
		return $returnArr;
	}

}


if ( ! function_exists('render_custom_filters'))
{

	function render_custom_filters($full_render = FALSE)
	{
		$CI =& get_instance();
		if($CI->load->is_loaded('custom_filters')){
			if($full_render)
				$CI->custom_filters->render();
			else
				$CI->custom_filters->render_filters();
		}
	}

}

if ( ! function_exists('execute_filters'))
{

	function execute_filters($section)
	{
		$CI =& get_instance();
		if($CI->load->is_loaded('custom_filters')){
			$CI->custom_filters->execute_filters($section);
		}
	}

}

/* End of file filter_helper.php */
/* Location: ./application/helpers/filter_helper.php */
?>