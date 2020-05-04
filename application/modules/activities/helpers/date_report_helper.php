<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Function to check the dates inputted for querying reports
 *
 */

if ( ! function_exists('checkdate_from_to'))
{
	function checkdate_from_to($from, $to)
	{
		$result = FALSE;
		if ($from && $to)
		{
			$from_to_time = strtotime($from);
			$to_to_time = strtotime($to);
			if (($from_to_time !== -1) && ($to_to_time !== -1) && ($from_to_time <= $to_to_time))
				$result = TRUE;
		}
		return $result;
	}
}
/**
 * Function to get previous calendar week
 *
 */

if ( ! function_exists('get_calendar_week'))
{
	function get_calendar_week()
	{
		$result = array();
		for ($i = 0; $i < 7; $i++)
		{
			$current = mktime(0, 0, 0, date("m")  , date("d") - $i, date("Y"));
			if (date('w', $current) == 0)
			{
				$start = mktime(0, 0, 0, date("m")  , date("d") - $i - 6, date("Y"));
				$result = array(
					'start' => date('Y-m-d', $start),
					'end' => date('Y-m-d', $current)
					);
				break;
			}
		}
		return $result;
	}
}
/* Check UTF-8 without BOM ùà */
/* End of file date_report_helper.php */
/* Location: ./application/modules/activities/helpers/date_report_helper.php */