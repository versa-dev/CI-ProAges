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

// Render table for user
function renderTable( $data = array(), $access_activate = false, $access_update = false, $access_delete = false ){

	if( empty( $data ) ) return false;








	$table = null;

	foreach( $data as $value ){

    		if( $value['creation_date'] == '0000-00-00 00:00:00' ) $value['creation_date'] = '';

    		$table .= '<tr onclick="menu(\'menu-'. $value['id'] .'\');">';




			$color = diferenciaEntreFechas( date('Y-m-d H:i:s'), $value['creation_date'], "DIAS", FALSE );


			$table .='				<td class="center">';

			if( $value['work_order_status_id'] == 5 or $value['work_order_status_id'] == 9 ) {
				if( (float)$color <= 5 )
					$table .= '<div style="background-color:#0C0; width: 10px;  height: 10px; border-radius: 50%; float:left; margin-top:5px;"></div>';
				else if( (float)$color > 5 and (float)$color <= 10 )
					$table .= '<div style="background-color:#FF0; width: 10px;  height: 10px; border-radius: 50%; float:left; margin-top:5px;"></div>';
				else if( (float)$color > 10 )
					$table .= '<div style="background-color:#F30; width: 10px;  height: 10px; border-radius: 50%; float:left; margin-top:5px;"></div>';
			}


			if( $value['work_order_status_id'] == 6 )
				$table .= '<div style="background-color:#000; width: 10px;  height: 10px; border-radius: 50%; float:left; margin-top:5px;"></div>';


			$table .= $value['uid'];

			$table .='</td>';


			$table .='		<td class="center">'. $value['creation_date'] .'</td>';



			if( !empty( $value['agents'] ) ){

					$table .='		<td class="center">';

					foreach( $value['agents'] as $agent )

							if( !empty( $agent['company_name'] ) )
								$table .=  $agent['company_name'] . ' '. $agent['percentage'] . '% <br>';
							else
								$table .= $agent['name']. ' '. $agent['lastnames']. ' '. $agent['percentage'] . '% <br>';

					$table .='		</td>';

			}

			else
					$table .='		<td class="center"></td>';

			$table .= '<td class="center">'.$value['group_name'] .'</td>';
			$table .= '<td class="center">'.$value['parent_type_name']['name'] .'</td>';

			$table .='<td class="center">'.  $value['policy'][0]['name'] .'</td>';



			$table .= '<td class="center"> ';


			$table .=  ucwords(str_replace( 'desactivada', 'en trámite', $value['status_name']));



			$table .= '</td>';

			$table .='</tr>';



			if( $value['work_order_status_id'] != 2 and $value['work_order_status_id'] != 7 and $value['work_order_status_id'] != 8 )
				$table .='<tr id="menu-'. $value['id'] .'" class="popup">';
			else
				$table .='<tr class="popup">';






            $table .='    	<td colspan="8">';

   				    $table .= '<a href="javascript:void(0)" class="btn btn-link btn-hide"><i class="icon-arrow-up"></i></a>';

					$new = false;

					if( $value['parent_type_name']['name'] == 'NUEVO NEGOCIO' )

						$new = true;

					$scrips='';

					if( $access_activate == true and $value['work_order_status_id'] ==  9 )
						$scrips .= '<a href="javascript:void(0)" onclick="chooseOption(\'activar-'.$value['id'].'\', \''.$new.'\')">Activar</a>&nbsp;&nbsp;|&nbsp;&nbsp;';


					else if( $access_activate == true and $value['work_order_status_id'] ==  6 )
						$scrips .= '<a href="javascript:void(0)" onclick="chooseOption(\'desactivar-'.$value['id'].'\', \''.$new.'\')">Desactivar</a>&nbsp;&nbsp;|&nbsp;&nbsp;';

					else
						$scrips .= '<a href="javascript:void(0)" onclick="chooseOption(\'activar-'.$value['id'].'\', \''.$new.'\')">Activar</a>&nbsp;&nbsp;|&nbsp;&nbsp;';




					if( $access_update == true ){
						$scrips .= '<a href="javascript:void(0)" onclick="chooseOption(\'aceptar-'.$value['id'].'\', \''.$new.'\')">Marcar como aceptada</a>&nbsp;&nbsp;|&nbsp;&nbsp;';

						$scrips .= '<a href="javascript:void(0)" onclick="chooseOption(\'rechazar-'.$value['id'].'\', \''.$new.'\')">Marcar como rechazada</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
												}
					if( $access_delete == true )

						$scrips .= '<a href="javascript:void(0)" onclick="chooseOption(\'cancelar-'.$value['id'].'\', \''.$new.'\')">Cancelar</a>&nbsp;&nbsp;';

					if( $value['work_order_status_id'] != 2 and $value['work_order_status_id'] != 7 and $value['work_order_status_id'] != 8 )
					$table .=  $scrips;


                    $table .= '
                    </td>
                </tr>    ';

	}


	return $table;
}


// Getting format date
function getFormatDate( $date = null ){

	 if( empty( $date ) or $date == '0000-00-00' ) return false;


	 $date = explode( '-', $date );


	 $meses= array('01'=>"Enero",'02'=>"Febrero",'03'=>"Marzo",'04'=>"Abril",'05'=>"Mayo",'06'=>"Junio",'07'=>"Julio",'08'=>"Agosto",'09'=>"Septiembre",'10'=>"Octubre",'11'=>"Noviembre",'12'=>"Diciembre");

	 return 'el '.$date[2].' de '.$meses[$date[1]].' del '.$date[0];



}
/*
	Prepare form fields for ot.html page
*/
if ( ! function_exists('prepare_ot_form'))
{
	function prepare_ot_form($other_filters, &$gerente_str, &$agente_str, &$ramo_tramite_types, &$patent_type_ramo)
	{
		$CI =& get_instance();
		$gerente_str = '';
		$gerentes_array = $CI->user->getSelectsGerentes2();
		if ($gerentes_array)
		{
			foreach ($gerentes_array as $key => $gerente)
			{
				$selected = '';
				if (isset($other_filters['gerente']) && ($gerente['id'] == $other_filters['gerente']))
					$selected = ' selected="selected"';
				$gerentes_array[$key] = '<option value="' . $gerente['id'] . '"' . $selected . '>' . $gerente['name'] . '</option>';
			}
			$gerente_str .= implode("\n", $gerentes_array);
		}

		$agente_str = '<option value="">Todos</option>';
		$agent_array = $CI->user->getAgents( FALSE );
		if ($agent_array)
		{
			foreach ($agent_array as $key => $value)
			{
				$selected = '';
				if (isset($other_filters['agent']) && ($key == $other_filters['agent']))
					$selected = ' selected="selected"';
				$agent_array[$key] = '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
			}
			$agente_str .= implode("\n", $agent_array);
		}
		if (isset($other_filters['patent_type']))
			$ramo_tramite_types = $CI->work_order->get_tramite_types_select_arr($other_filters['patent_type']);
		else
			$ramo_tramite_types = $CI->work_order->get_tramite_types_select_arr();
		$patent_type_ramo = 0;
		if (isset($other_filters['ramo']) && isset($ramo_tramite_types[$other_filters['ramo']]))
			$patent_type_ramo = $other_filters['ramo'];
	}
}

/*
	Update OT status
*/
if ( ! function_exists('change_ot_status'))
{
	function change_ot_status($new_status, $module = 'ot')
	{
		$CI =& get_instance();
		if ( !$CI->input->is_ajax_request() )
			redirect( "$module.html", 'refresh' );

		if ( !$CI->access_update ){
			echo json_encode('-1');
			exit;
		}
		$result = json_encode('0');
		$order_id = $CI->input->post('order_id');
		$gmm = $CI->input->post('gmm');
		$is_poliza = $CI->input->post('is_poliza');
		$user_id = $CI->input->post('user_id');
		$send_notification = $CI->input->post('send_notification');
		if (($order_id !== FALSE) && ($gmm !== FALSE) && ($is_poliza !== FALSE) && ($user_id !== FALSE))
		{
			$order_id = (int)$order_id;
			$user_id = (int)$user_id;
			$CI->load->model( 'ot/work_order' );
			$work_order = array(
				'work_order_status_id' => $new_status
			);

			if ( $CI->work_order->update( 'work_order', $order_id, $work_order ) &&
				( ($updated = $CI->work_order->generic_get( 'work_order', array('id' => $order_id), 1))
				 !== FALSE)
				)
			{
				$creator = $CI->work_order->generic_get( 'users', array('id' => $updated[0]->user), 1);
			// Send Email
				if (($send_notification === FALSE) || ($send_notification == 1))
				{
					$CI->load->library( 'mailer' );
					$notification = $CI->work_order->getNotification( $order_id );
					$from_reply_to = array();
					if ($creator)
					{
						$recipient = array(
							'agent_id' => 0,
							'percentage' => 100,
							'name' => $creator[0]->name,
							'lastnames' => $creator[0]->lastnames,
							'company_name' => $creator[0]->company_name,
							'email' =>  $creator[0]->email
						);
						$notification[0]['agents'][] = $recipient;
						$from_reply_to = array(
/*							'from' => $creator[0]->email,
							'reply-to' =>  $creator[0]->email);*/
						'from' => $CI->sessions['email'],
						'reply-to' => $CI->sessions['email']);
					}
					$CI->mailer->notifications( $notification, null, null, $from_reply_to);
				}
				$row_result = array(
					'is_poliza' => $is_poliza,
					'gmm' => $gmm,
					'access_update' => $CI->access_update);
				$row_result['value'] = $CI->work_order->pop_up_data($order_id, $user_id);
				$CI->load->model( 'usuarios/user' );
				$row_result['value']['general'][0]->adjusted_prima = $CI->user->get_adjusted_prima(
					$row_result['value']['general'][0]->policy_id );
				$data['main'] = $CI->load->view('ot/popup_report_main_row', $row_result, TRUE);
				$data['menu'] = $CI->load->view('ot/popup_report_menu_row', $row_result, TRUE);
				$result = json_encode($data);
			}
		}
		echo $result;
		exit;
	}
}

//////////////
// actions on payment (ignore, delete)
if ( ! function_exists('payment_actions'))
{
	function payment_actions($module = 'ot')
	{
		$CI =& get_instance();
		if ( !$CI->input->is_ajax_request() )
			redirect( "$module.html", 'refresh' );

		$action = $CI->input->post('payment_action');
		switch ($action)
		{
			case 'mark_ignored':
				if ( !$CI->access_update ){
					echo json_encode('-1');
					exit;
				}
				break;
			case 'payment_delete':
				if ( !$CI->access_delete ){
					echo json_encode('-1');
					exit;
				}
				break;
			default:
				echo json_encode('0');
				exit;
				break;
		}
		$result = json_encode('0');
		$agent_id = $CI->input->post('for_agent_id');
		$amount = $CI->input->post('amount');
		$payment_date = $CI->input->post('payment_date');
		$policy_number = $CI->input->post('policy_number');

		if (($agent_id !== FALSE) && strlen($agent_id = trim($agent_id)) &&
			($amount !== FALSE) && strlen($amount = trim($amount)) && $CI->form_validation->decimal_or_integer($amount) &&
			($payment_date !== FALSE) && (strlen($payment_date = trim($payment_date)) == 10) &&
			($policy_number !== FALSE) && (strlen($policy_number = trim($policy_number)) >=  0)
			)
		{
			$CI->load->model( 'ot/work_order' );
			$compare_amount = floor($amount * 100);
			$where = array(
				'agent_id' => (int)$agent_id,
				"ABS((amount * 100) - ($compare_amount) ) <= " => 1,
				'payment_date' => $payment_date,
				'policy_number' => $policy_number
			);
			switch ($action)
			{
				case 'mark_ignored':
					$db_result = $CI->work_order->generic_update('payments', array('valid_for_report' => 0), $where, 1, 0);
					break;
				case 'payment_delete':
					$db_result = $CI->work_order->generic_delete('payments', $where, 1, 0);
					break;
				break;
				default:
					$db_result = FALSE;
					break;
			}
			if ( $db_result )
				$result = json_encode('1');
		}
		echo $result;
		exit;
	}
}
//////
//////////////
// show report popup
if ( ! function_exists('reporte_popup'))
{
	function reporte_popup($module = 'ot')
	{
		$CI =& get_instance();
		$work_order_ids = $CI->input->post('wrk_ord_ids');
		$data['is_poliza'] = $CI->input->post('is_poliza');
		$data['gmm'] = $CI->input->post('gmm');

		$CI->load->model( array( 'ot/work_order', 'usuarios/user' ) );
		$CI->view = array(
			'css' => array(
			'<link href="'. base_url() .'ot/assets/style/report.css" rel="stylesheet">',
			'<!--<link rel="stylesheet" href="'. base_url() .'ot/assets/style/normalize.min.css">-->
			<link rel="stylesheet" href="'. base_url() .'ot/assets/style/main.css">',
			'<link rel="stylesheet" href="'. base_url() .'ot/assets/style/jquery.fancybox.css">'
			),
			'scripts' =>  array(
		  	'<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/jquery.validate.js"></script>',
			'<script type="text/javascript" src="'.base_url().'plugins/jquery-validation/es_validator.js"></script>',
			'<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.js"></script>',
			//'<script type="text/javascript" language="javascript" src="'. base_url() .'ot/assets/plugins/DataTables/media/js/jquery.dataTables.js"<script>',
			'<script src="'. base_url() .'ot/assets/scripts/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>',
			'<script>window.jQuery || document.write ("<script src='. base_url() .'ot/assets/scripts/vendor/jquery-1.10.1.min.js><\/script>");</script>',
			'<script type="text/javascript" src="'. base_url() .'ot/assets/scripts/jquery.ddslick.js"></script>',
			'<script type="text/javascript" src="'. base_url() .'ot/assets/scripts/jquery.tablesorter.js"></script>',
			'<script type="text/javascript" src="'. base_url() .'ot/assets/scripts/main.js"></script>',
			'<script src="'.base_url().'scripts/config.js"></script>'	,
			'<script src="'.base_url().'ot/assets/scripts/report.js"></script>',
			'<script src="'.base_url().'ot/assets/scripts/jquery.fancybox.js"></script>'
		  ));

		$ramo = 1;
		$period = 2;
		$prev_post = $CI->input->post('prev_post');
		if (($prev_post !== FALSE) && is_array($prev_post))
		{
			if (isset($prev_post['ramo']))
				$ramo = (int) $prev_post['ramo'];
			if (isset($prev_post['periodo']))
				$period = $prev_post['periodo'];
		}

		$CI->load->model('policy_model');
		$primas_cached = $CI->policy_model->get_ot_adjusted_primas($CI->input->post('wrk_ord_ids'));

		$results = array();
		$row_result = array_merge($data, array('access_update' => $CI->access_update));
		$data['values'] = array();
		foreach($work_order_ids as $work_order_id)
		{
			$row_result['value'] = $CI->work_order->pop_up_data($work_order_id, (int)$CI->input->post('agent_id'));
			$row_result['value']['general'][0]->adjusted_prima = $row_result['value']['general'][0]->prima;

			// For OTs en tramite and pendientes, adjust prima:
			if (($row_result['value']['general'][0]->work_order_status_id == 5) ||
				($row_result['value']['general'][0]->work_order_status_id == 9) ||
				($row_result['value']['general'][0]->work_order_status_id == 7) )
			{
				if (isset($primas_cached[$work_order_id]) &&
					isset($primas_cached[$work_order_id]['adjusted_prima']))
				{
					$ot_adjusted = $primas_cached[$work_order_id]['adjusted_prima'];
				}
				else{
					$ot_adjusted = $CI->user->get_adjusted_prima($row_result['value']['general'][0]->policy_id,
					$ramo, $period);
				}
				//Only if primas_cached is set, adjusted_allocated_prime is set AND if adjusted_allocated_prime is greater than zero.
				//Last condition is added so it doesn't return 0 as a total.
				if (isset($primas_cached[$work_order_id]) &&
				isset($primas_cached[$work_order_id]['adjusted_allocated_prime']) && $primas_cached[$work_order_id]['adjusted_allocated_prime'] > 0 )
				{
					$ot_adjusted_allocated = $primas_cached[$work_order_id]['adjusted_allocated_prime'];
				}
				else{
					$ot_adjusted_allocated = $CI->user->get_adjusted_prima($row_result['value']['general'][0]->policy_id,
					$ramo, $period, $prime_requested = 'allocated_prime');
				}

				if (isset($primas_cached[$work_order_id]) &&
				isset($primas_cached[$work_order_id]['adjusted_bonus_prime']) && $primas_cached[$work_order_id]['adjusted_bonus_prime'] > 0)
				{
					$ot_adjusted_bonus = $primas_cached[$work_order_id]['adjusted_bonus_prime'];
				}
				else{
					$ot_adjusted_bonus = $CI->user->get_adjusted_prima($row_result['value']['general'][0]->policy_id,
					$ramo, $period, $prime_requested = 'bonus_prime');
				}	
				$row_result['value']['general'][0]->adjusted_prima = $ot_adjusted * ($row_result['value']['general'][0]->p_percentage / 100);
				$row_result['value']['general'][0]->adjusted_allocated_prime = $ot_adjusted_allocated * ($row_result['value']['general'][0]->p_percentage / 100);
				$row_result['value']['general'][0]->adjusted_bonus_prime = $ot_adjusted_bonus * ($row_result['value']['general'][0]->p_percentage / 100);
			}
			$data['values'][$work_order_id]['main'] = $CI->load->view('ot/popup_report_main_row', $row_result, TRUE);
			$data['values'][$work_order_id]['menu'] = $CI->load->view('ot/popup_report_menu_row', $row_result, TRUE);
		}
		$CI->load->view('ot/popup_report', $data);
	}
}

/*
	Delete cobranza
*/
if ( ! function_exists('delete_cobranza'))
{
	function delete_cobranza($module = 'ot')
	{
		$CI =& get_instance();
		if ( !$CI->input->is_ajax_request() )
			redirect( "$module.html", 'refresh' );

		if ( !$CI->access_delete )
		{
			echo json_encode('-1');
			exit;
		}

		$result = json_encode('0');
		$ids = $CI->input->post('ids');
		if (!$ids || !is_array($ids))
		{
			echo $result;
			exit;
		}
		$CI->load->model('policy_model');
		if ($CI->policy_model->delete_adjusted_primas($ids))
			$result = json_encode('1');

		echo $result;
		exit;
	}
}
?>