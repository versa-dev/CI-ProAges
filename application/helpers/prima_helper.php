<?php
/*
  Author
  Site:
  Twitter:
  Facebook:
  Github:
  Email:
  Skype:
  Location: Mexíco
*/

/*
  Compute `prima_entered` (special attention to rows with currency_id USD)
*/
if ( ! function_exists('init_policy_prima_entered'))
{
	function init_policy_prima_entered()
	{
		$CI =& get_instance();
		$CI->load->model('policy_model');
		return $CI->policy_model->init_policy_prima_entered();
	}
}

/*
  Reset and recompute `prima_entered` (special attention to rows with currency_id USD)
*/
if ( ! function_exists('reset_policy_prima_entered'))
{
	function reset_policy_prima_entered()
	{
		$CI =& get_instance();
		$CI->load->model('policy_model');
		$CI->policy_model->reset_prima_entered();
		return $CI->policy_model->init_policy_prima_entered();
	}
}

/* End of file prima_helper.php */
/* Location: ./application/helpers/prima_helper.php */
?>