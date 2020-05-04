<?php
if (!function_exists("issetor")) {
	function issetor(&$var, $default = false) {
	    return isset($var) ? $var : $default;
	}
}


if (!function_exists("printEquals")) {
	function printEquals($var, $comparation_value, $print_value) {
	    return $var == $comparation_value ? $print_value : '';
	}
}

if (!function_exists("sign")) {
	function sign($number, $class_positive = "positive", $class_negative = "negative", $class_zero = "positive") {
	    if($number > 0)
	    	return $class_positive;
	    elseif($number == 0)
	    	return $class_zero;
	    else
	    	return $class_negative;
	}
}
if (!function_exists("signPercentages")) {
	function signPercentages($number, $comparation_value, $class_positive = "positive", $class_negative = "negative", $class_zero = "positive") {
	    if($number < $comparation_value)
			return $class_positive;
		elseif ($number > $comparation_value)
			return $class_negative;
		else
			return $class_zero;
	}
}

if (!function_exists("comparationRatio")) {
	function comparationRatio($value, $comparation_value){
		$ratio = 0;
		if($comparation_value != 0){
			$ratio = ($value-$comparation_value)*100/$comparation_value;
		}
		else
		{
			$ratio = 100;
		}
		return $ratio;
	}
}

if (!function_exists("percentageRatio")) {
	function percentageRatio($value, $total){
		$ratio = 0;
		if($total != 0){
			$ratio = ($value/$total)*100;
		}
		return $ratio;
	}
}

