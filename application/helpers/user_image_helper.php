<?php
// Make sure UTF8 w/o BOM éùà
/*
  User image helper function
*/
/*
Default image name

*/
 
if ( ! function_exists('get_default_user_image'))
{
	function get_default_user_image( &$image_name )
	{
		$CI = & get_instance();
		$CI->load->config('config_proages');
		$file_name_suffix = $CI->config->item('profile_picture_default');
		if ($file_name_suffix === FALSE )
			return;
		$image_path = $CI->config->item('profile_picture_path');
		if ($image_path === FALSE)		
			$image_path = APPPATH . 'modules/usuarios/assets/profiles/';

		$pos = strrpos($image_name, ".");
		if ($pos !== FALSE)
		{
			$base_fname = substr($image_name, 0, $pos );
			$fextension = substr($image_name, $pos + 1);
			if (file_exists($image_path . $base_fname . "_" . $file_name_suffix . ".$fextension"))
				$image_name = $base_fname . "_" . $file_name_suffix . ".$fextension";
		}
	}
}

