<?php
//Entrada:
 // $fecha: Valor de la fecha a comparar
 // $fecha_compara: Fecha con la que se establecerá la comparación, si no se establece una fecha se comparara con la fecha actual. por defecto NULL
 //Salida: BOLEAN, si las fechas son iguales se devuelve 0, si la primer fecha es menor a la segunda se devuelve -1, si la primer fecha es mayor a la segunda se devuelve 1.
 //Nota: Esta función puede devolver el valor booleano FALSE, pero también puede devolver un valor no booleano que se evalúa como FALSE. Use el operador === para comprobar el valor devuelto por esta función.
 //Autor: dantepiazza
 //Version: 1.0

function comparar_fechas($fecha, $fecha_comparar = null){
 if($fecha_comparar == null){
  $fecha_comparar = date("Y-m-d H:i:s");
 }

 $fecha = strtotime($fecha);
 $fecha_comparar = strtotime($fecha_comparar);

 if($fecha == $fecha_comparar){
  return 0;
 }
 else if($fecha < $fecha_comparar){
  return -1;
 }
 else if($fecha > $fecha_comparar){
  return 1;
 }

 return false;
}


// Tiempo transcurrido
function tiempo_transcurrido($fecha) {


		if(empty($fecha)) {
			  return "No hay fecha";
		}



		$intervalos = array("segundo", "minuto", "hora", "día", "semana", "mes", "año");
		$duraciones = array("60","60","24","7","4.35","12");

		$ahora = time();
		$Fecha_Unix = strtotime($fecha);

		if(empty($Fecha_Unix)) {
			  return "Fecha incorracta";
		}
		if($ahora > $Fecha_Unix) {
			  $diferencia     =$ahora - $Fecha_Unix;
			  $tiempo         = "Hace";
		} else {
			  $diferencia     = $Fecha_Unix -$ahora;
			  $tiempo         = "Dentro de";
		}
		for($j = 0; $diferencia >= $duraciones[$j] && $j < count($duraciones)-1; $j++) {
		  $diferencia /= $duraciones[$j];
		}

		$diferencia = round($diferencia);

		if($diferencia != 1) {
			$intervalos[5].="e"; //MESES
			$intervalos[$j].= "s";
		}

		return "$tiempo $diferencia $intervalos[$j]";
}
// Ejemplos de uso
// fecha en formato yyyy-mm-dd
// echo tiempo_transcurrido('2010/02/05');
// fecha y hora
// echo tiempo_transcurrido('2010/02/10 08:30:00');

/**
 * Devuelve la diferencia entre 2 fechas según los parámetros ingresados
 * @author Gerber Pacheco
 * @param string $fecha_principal Fecha Principal o Mayor
 * @param string $fecha_secundaria Fecha Secundaria o Menor
 * @param string $obtener Tipo de resultado a obtener, puede ser SEGUNDOS, MINUTOS, HORAS, DIAS, SEMANAS
 * @param boolean $redondear TRUE retorna el valor entero, FALSE retorna con decimales
 * @return int Diferencia entre fechas
 */
function diferenciaEntreFechas($fecha_principal, $fecha_secundaria, $obtener = 'SEGUNDOS', $redondear = false){
   $f0 = strtotime($fecha_principal);
   $f1 = strtotime($fecha_secundaria);
   if ($f0 == $f1) { $tmp = $f1; $f1 = $f0; $f0 = $tmp; }
   $resultado = ($f0 - $f1);
   switch ($obtener) {
       default: break;
       case "MINUTOS"   :   $resultado = $resultado / 60;   break;
       case "HORAS"     :   $resultado = $resultado / 60 / 60;   break;
       case "DIAS"      :   $resultado = $resultado / 60 / 60 / 24;   break;
       case "SEMANAS"   :   $resultado = $resultado / 60 / 60 / 24 / 7;   break;
   }
   if($redondear) $resultado = round($resultado);
   return $resultado;
}

if ( ! function_exists('trimestre'))
{
	function trimestre($mes=null)
	{
		$mes = is_null($mes) ? date('m') : $mes;
		$trim=floor(($mes-1) / 3)+1;
		return $trim;
	}
}

if ( ! function_exists('cuatrimestre'))
{
	function cuatrimestre($mes=null)
	{
		$mes = is_null($mes) ? date('m') : $mes;
		$trim=floor(($mes-1) / 4)+1;
		return $trim;
	}
}

if ( ! function_exists('my_check_date'))
{
	function my_check_date($date = null, $product = 1)
	{
		if (!$date)
			return FALSE;
		$date_parts = explode('-', $date);
		if (count($date_parts) < 3){
			if ( $product == 8 || $product == 9){
				return checkdate( substr($date_parts[0], 4, 2), substr($date_parts[0], -2), substr($date_parts[0], 0, 4));
			}else{
				return FALSE;
			}
		}
			
		return checkdate( $date_parts[1], $date_parts[2], $date_parts[0]);
	}
}
?>