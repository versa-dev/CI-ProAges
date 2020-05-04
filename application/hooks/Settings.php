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
function get_settings()
{
	$CI =& get_instance();
	$CI->load->model( 'settings_model' );
	$CI->settings_model->set_table('settings');
	$settings = $CI->settings_model->get(null, 'rank');
	if ($settings)
	{
		foreach ($settings as $field_name => $field_value)
			$CI->config->set_item($field_name, $field_value->value);
	}
}
// END Settings hook

/* End of file Settings.php */
/* Location: ./application/hooks/Settings.php */