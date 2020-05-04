<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Helpers for create/update activity
 *
 */

if ( ! function_exists('create_update_activity'))
{
	function create_update_activity($activity_id, $redirect_page = 'activities', $userid = null)
	{
		$CI =& get_instance();
		if ($_POST)
		{
			$CI->form_validation->set_rules('cust_period_from', 'Periodo', 'trim|required');
			$CI->form_validation->set_rules('cust_period_to', 'Periodo', 'trim|required');
			$CI->form_validation->set_rules('cita', 'Cita', 'trim|required|is_natural');
			$CI->form_validation->set_rules('prospectus', 'Prospecto', 'trim|required|is_natural');
			$CI->form_validation->set_rules('interview', 'Entrevista', 'trim|required|is_natural');
			$CI->form_validation->set_rules('vida_requests', 'Solicitudes Vida', 'trim|required|is_natural');			
			$CI->form_validation->set_rules('vida_businesses', 'Negocios Vida', 'trim|required|is_natural');			
			$CI->form_validation->set_rules('gmm_requests', 'Solicitudes GMM', 'trim|required|is_natural');			
			$CI->form_validation->set_rules('gmm_businesses', 'Negocios GMM', 'trim|required|is_natural');			
			$CI->form_validation->set_rules('autos_businesses', 'Negocios Autos', 'trim|required|is_natural');			
			$CI->form_validation->set_rules('comments', 'Comentarios', 'trim');

			if ( $CI->form_validation->run() )
			{
				//Load Model

				$field_names = array('cita', 'prospectus', 'interview',
					'vida_requests', 'vida_businesses', 'gmm_requests', 'gmm_businesses',
					'autos_businesses', 'comments');
				$values = array();
				foreach ($field_names as $field_name)
					$values[$field_name] = $CI->input->post($field_name);
				if( !empty( $userid ) )	
					$values['agent_id'] =  $CI->user->getAgentIdByUser( $userid );
				else					
					$values['agent_id'] = $CI->user->getAgentIdByUser( $CI->sessions['id'] );

				$values['begin'] = $CI->input->post('cust_period_from');
				$values['end'] = $CI->input->post('cust_period_to');

				if (!$activity_id) // create
				{
					if( $CI->activity->create( 'agents_activity', $values ) )
						// Set false message		
						$CI->session->set_flashdata( 'message', array( 
							'type' => true,	
							'message' => 'Se creó la actividad correctamente.'
						));	
					else
						// Set false message		
						$CI->session->set_flashdata( 'message', array( 
							'type' => false,	
							'message' => 'No se puede crear la actividad, consulte a su administrador.'
						));	

					redirect($redirect_page, 'refresh');
				}
				else // update
				{
					if ( $CI->activity->update( 'agents_activity', $activity_id, $values) )
						$CI->session->set_flashdata( 'message', array( 
							'type' => true,  
							'message' => 'Se guardo la actividad correctamente.'
						));
					else
						$CI->session->set_flashdata( 'message', array( 
							'type' => false,  
							'message' => 'No se pudo guardar el registro, Actividad, ocurrio un error en la base de datos. Pongase en contacto con el desarrollador'
						));				
					redirect( $redirect_page, 'refresh' );
				}
			}
		}
	}
}

if ( ! function_exists('activity_create_update_js'))
{
	function activity_create_update_js()
	{
		$img_wait = base_url() . 'images/ajax-loaders/ajax-loader-5.gif';
		return 
'
<script type="text/javascript">
	$( document ).ready( function(){ 
		var saveClicked = false;
		$("#save-activity").click(function() { 
			saveClicked = true;
		});
		$("#form").submit(function() {
			if (!saveClicked)
				return false;
			saveClicked = false;
			$( "#actions-buttons-forms" ).html( "<img src=\"' . $img_wait . '\">" );
			var defFrom = $("#cust_period_from").val();
			var defTo = $("#cust_period_to").val();
			$(this).append(
				"<input type=\"hidden\" name=\"cust_period_from\" value=\"" + defFrom + "\" />" +
				"<input type=\"hidden\" name=\"cust_period_to\" value=\"" + defTo + "\" />");
		});

	});
</script>';
	}
}
/* Check UTF-8 without BOM ùà */
/* End of file date_report_helper.php */
/* Location: ./application/modules/activities/helpers/date_report_helper.php */