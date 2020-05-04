<?php
	if(!function_exists("sort_object")){
		function sort_object(&$ObjectArray, $property){
			if(empty($ObjectArray)) return array();
			foreach ($ObjectArray as $key => $row) {
				$aux[$key] = $row[$property];
			}
			array_multisort($aux, SORT_DESC, $ObjectArray);
		}
	}
