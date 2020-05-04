<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
 * Termometro de Ventas - Modelo de Datos
 * 
 * @author     Jesus Castilla & José Gilberto Pérez Molina
 * Date:       December, 2018
 * Locaion:    Veracruz, Mexico
 * Mail:       jesuscv1821@gmail.com - jgilbertopmolina@gmail.com
 */
class termometro_model extends CI_Model{
	//variable globales
	public $main_list  = array();
	public $indicators = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model('usuarios/user');
		$this->load->model('termometro/termometro_vida_model');
		$this->load->model('termometro/termometro_gmm_model');
		$this->load->model('termometro/termometro_generics_model');
		$this->load->library('termometro/pdf');
	}
	/*******  FUNCIONES PARA OBTENER DATOS GLOBALES  *******/
	public function set_global_values($date,$year)
	{
		$this->main_list  = $this->get_main_list($year);
		$this->indicators = $this->get_main_array($this->main_list, $year);
	}
	public function get_global_years()
	{
		$periods = array();
        $minYear = 2018;
        $auxYear = date("Y");
        do
        {
        	$periods[$auxYear] = $auxYear;
        	$auxYear--;
        }
		while ($auxYear >= $minYear);
		return $periods;
	}


	/*******  FUNCIONES PARA OBTENER INDICADORES DE LA VISTA GENERAL  *******/
	public function get_grl_indicator_view($date,$year)
	{
		//Definicion de las variables con valores a usar
		//Ingresos por generacion
		$ingresos_gen_vi = array(
			'Generación 1' => $this->indicators['vi_income_gen'][0] + $this->indicators['gmm_income_gen'][0],
			'Generación 2' => $this->indicators['vi_income_gen'][1] + $this->indicators['gmm_income_gen'][1],
			'Generación 3' => $this->indicators['vi_income_gen'][2] + $this->indicators['gmm_income_gen'][2],
			'Generación 4' => $this->indicators['vi_income_gen'][3] + $this->indicators['gmm_income_gen'][3],
			'Consolidado'  => $this->indicators['vi_income_gen'][4] + $this->indicators['gmm_income_gen'][4]
		);
		//Distribucion de ingresos por Ramo:
		$ingresos_ramo_vi = array(
			'ingresos_vida' => $this->indicators['vi_grl_income'],
			'ingresos_gmm'  => $this->indicators['gmm_grl_income']
		);
		//Agentes congresistas por tipo
		$agentes_congresistas_tipo_vi = array(
			'Oro' 		=> $this->indicators['vi_agent_con'][0] + $this->indicators['gmm_agent_con'][0],
			'Platino' 	=> $this->indicators['vi_agent_con'][1] + $this->indicators['gmm_agent_con'][1],
			'Diamante' 	=> $this->indicators['vi_agent_con'][2] + $this->indicators['gmm_agent_con'][2],
			'Consejo' 	=> $this->indicators['vi_agent_con'][3] + $this->indicators['gmm_agent_con'][3]
		);
		$agents_active 	= $this->indicators['agent_active'];
		$agents_recruit = $this->indicators['agent_recruit'];

		//Array Final
		$indicadores_grl = array(
			'agentes_total'                  => $agents_active,
			'agentes_reclutados'             => $agents_recruit,
			'ingresos_generacion'            => $ingresos_gen_vi,
			'distribucion_ingreso_ramo'      => $ingresos_ramo_vi,
			'agentes_congresistas_tipo'      => $agentes_congresistas_tipo_vi,
			'ingresos_agentes' 				 => array_sum($ingresos_gen_vi),
			'agentes_congresistas'           => array_sum($agentes_congresistas_tipo_vi),
			'produccion_g1_g4'               => $this->indicators['vi_prod_ini'] + $this->indicators['gmm_prod_ini'],
			'agentes_bono_integral'			 => $this->indicators['agent_bonus'],
			'agentes_reclutados_produccion'  => $this->indicators['agent_rec_prod'],
			'percentage_agentes_activos'     => $this->get_grl_perce_active_agents($agents_active,$agents_recruit),
            'agentes_mdrt'                   => $this->termometro_vida_model->get_grl_mdrt_agents($date,$year)
		);
		return $indicadores_grl;
	}	
	public function get_vida_indicators_view($date,$year)
	{
		$ingresos_gen_vi = array(
			'Generación 1' => $this->indicators['vi_prod_ini'][0],
			'Generación 2' => $this->indicators['vi_prod_ini'][1],
			'Generación 3' => $this->indicators['vi_prod_ini'][2],
			'Generación 4' => $this->indicators['vi_prod_ini'][3],
			'Consolidado'  => $this->indicators['vi_prod_ini'][4] 		
		);
		$ventas_tipos_vi = array(
			'Venta Nueva' => $this->indicators['vi_new_income'],
			'Renovación'  => $this->indicators['vi_ren_income']
		);
		$percentages = $this->termometro_vida_model->get_grl_percs($year);
		
		//Array Final
		$indicadores_vida = array( 
			'vida_ventas'                   => $ventas_tipos_vi, 
			'vida_ingresos_gen' 			=> $ingresos_gen_vi,
			'vida_primas_iniciales'         => $this->termometro_vida_model->get_grl_primas_iniciales($date,$year),
			'vida_agentes_congresistas'     => array_sum($this->indicators['vi_agent_con']),
			'vida_ingresos_acumulados'      => $this->indicators['vi_grl_income'],
			'vida_agentes_venta_nueva'      => $this->indicators['vi_agent_new'],
			'vida_prod_inicial'             => $this->indicators['vi_prod_ini'],
			'vida_primas_pagar'				=> $this->indicators['vi_primas_pag'],
			'vida_primas_ubicar'			=> $this->indicators['vi_primas_ubi'],
			'vida_negocios_pai'             => $this->indicators['vi_pai_buss'],
			'vida_puntos_standing'          => $this->indicators['vi_ptos_stand'],
			'vida_perc_conserv_real'        => $percentages['perc_conserv_real'],
			'vida_perc_conserv_real_acot'	=> $percentages['perc_conserv_real_acot'],
			'vida_perc_cancel' 				=> $percentages['perc_cancel']
		);
		return $indicadores_vida;
	}	
	public function get_gmm_indicators_view($date,$year)
	{
		$ingresos_gen_vi = array(
			'Generación 1' => $this->indicators['gmm_income_gen'][0],
			'Generación 2' => $this->indicators['gmm_income_gen'][1],
			'Generación 3' => $this->indicators['gmm_income_gen'][2],
			'Generación 4' => $this->indicators['gmm_income_gen'][3],
			'Consolidado'  => $this->indicators['gmm_income_gen'][4]
		);
		$ingresos_tipo_vi = array(
			'Venta Nueva' => $this->indicators['gmm_new_income'],
			'Renovacion'  => $this->indicators['gmm_ren_income']
		);
		$percentages = $this->termometro_gmm_model->get_grl_percs($year);
		//Array Final
		$indicadores_gmm = array(  
			'gmm_ventas'                        => $ingresos_tipo_vi, 
			'gmm_ingresos_acumulados_gen'       => $ingresos_gen_vi,
			'gmm_primas_pagadas_iniciales'      => $this->termometro_gmm_model->get_grl_primas_pagadas_iniciales($year),
			'gmm_puntos_productivos'            => $this->termometro_gmm_model->get_grl_puntos_productivos($year),
			'gmm_agentes_congresistas'          => array_sum($this->indicators['gmm_agent_con']),
			'gmm_primas_ubicar'                 => $this->indicators['gmm_primas_ubi'],
			'gmm_asegurados_nuevos'             => $this->indicators['gmm_num_buss'],
			'gmm_ingresos_acumulados'           => $this->indicators['gmm_grl_income'],
			'gmm_prod_incial_ubi'               => $this->indicators['gmm_prod_ini'],
			'gmm_agentes_venta_nueva'           => $this->indicators['gmm_agent_new'],
			'gmm_agentes_productivos'           => $this->indicators['gmm_agent_prod'],
			'gmm_perc_conserv'                  => $percentages['gmm_perc_conservacion'],
			'gmm_perc_sinis_real'				=> $percentages['gmm_perc_sin_real'],
			'gmm_perc_sinis_acot'				=> $percentages['gmm_perc_sin_acot'],
		);

		return $indicadores_gmm;
	}
	

	/*******  FUNCIONES PARA OBTENER INDICADORES DE GRAFICAS DE LA VISTA GENERAL  *******/
	public function get_grl_idicators_graph($date,$year)
	{
		//Definicion de variables auxiliares 
		$ingresos_gen_js = array(
			$this->indicators['vi_income_gen'][0] + $this->indicators['gmm_income_gen'][0],
			$this->indicators['vi_income_gen'][1] + $this->indicators['gmm_income_gen'][1],
			$this->indicators['vi_income_gen'][2] + $this->indicators['gmm_income_gen'][2],
			$this->indicators['vi_income_gen'][3] + $this->indicators['gmm_income_gen'][3],
			$this->indicators['vi_income_gen'][4] + $this->indicators['gmm_income_gen'][4]
		);
		//Distribucion de ingresos por ramo
		$ingresos_ramo_js = array(
			$this->indicators['vi_grl_income'],
			$this->indicators['gmm_grl_income']
		);
		//agentes congresistas 
		$congress_agents_js = array(
			$this->indicators['vi_agent_con'][0] + $this->indicators['gmm_agent_con'][0],
			$this->indicators['vi_agent_con'][1] + $this->indicators['gmm_agent_con'][1],
			$this->indicators['vi_agent_con'][2] + $this->indicators['gmm_agent_con'][2],
			$this->indicators['vi_agent_con'][3] + $this->indicators['gmm_agent_con'][3]
		);
		//Array Final
		$indicadores_graph = array(
			'ingresos_generacion'		=> $ingresos_gen_js,
			'distribucion_ingreso_ramo' => $ingresos_ramo_js,
			'agentes_congresistas_tipo' => $congress_agents_js,
		);
		return $indicadores_graph;
	}
	public function get_vida_indicators_graph($date,$year)
	{
		$ingresos_gen_js = array(
			$this->indicators['vi_income_gen'][0],
			$this->indicators['vi_income_gen'][1],
			$this->indicators['vi_income_gen'][2],
			$this->indicators['vi_income_gen'][3],
			$this->indicators['vi_income_gen'][4]
		);
		$ventas_tipos_js = array(
			$this->indicators['vi_new_income'],
			$this->indicators['vi_ren_income']
		);
		//Array Final
		$indicadores_graph = array(
			'ingresos_generacion'	=> $ingresos_gen_js,
			'ingresos_tipo_venta' 	=> $ventas_tipos_js
		);
		return $indicadores_graph;
	}
	public function get_gmm_indicators_graph($date,$year)
	{
		$ingresos_gen_js = array(
			$this->indicators['gmm_income_gen'][0],
			$this->indicators['gmm_income_gen'][1],
			$this->indicators['gmm_income_gen'][2],
			$this->indicators['gmm_income_gen'][3],
			$this->indicators['gmm_income_gen'][4]
		);
		$ingresos_tipo_js = array(
			$this->indicators['gmm_new_income'],
			$this->indicators['gmm_ren_income']
		);
		$indicadores_graph = array(
			'ingresos_generacion'	=> $ingresos_gen_js,
			'ingresos_tipo_venta' 	=> $ingresos_tipo_js,
		);
		return $indicadores_graph;
	}


	/*******  FUNCIONES PARA AGRUPAR TODOS LOS INDICADORES  *******/
	public function get_all_indicators($date, $year)
	{
		$this->restore_agent_id();
		$this->set_global_values($date,$year);
		return array(
			'vida_view' 	=> $this->get_vida_indicators_view($date,$year),
			'vida_js' 		=> $this->get_vida_indicators_graph($date,$year),
			'gmm_view'  	=> $this->get_gmm_indicators_view($date,$year),
			'gmm_js' 		=> $this->get_gmm_indicators_graph($date,$year),
			'grl_view'  	=> $this->get_grl_indicator_view($date,$year),
			'grl_js' 		=> $this->get_grl_idicators_graph($date,$year),
			'last_update' 	=> $this->get_last_update_selo(),
			'lost_data' 	=> $this->get_incomplete_data()
		);
	}
	public function get_last_update_selo()
	{
		$sql = "SELECT DATE_FORMAT(last_update, '%Y-%m-%d') as date_selo FROM cache_updates WHERE type_update ='selo_date'";
		$query = $this->db->query($sql);
        $res = $query->result_array();

        return $res[0]['date_selo'];
	}


	/*******  FUNCIONES PARA OBTENER DETALLES DE AGENTES EN VIDA Y GMM  *******/
	public function get_agent_array($id_agent)
	{
		$name ='';
		$exist = false;
		$connection_date = '';	
		$actual_day = date('Y-m-d');
		$generation = $this->user->generationByAgentIdNew($actual_day, $id_agent);
		$sql = "SELECT CONCAT(name,' ',lastnames) as name, company_name, connection_date FROM agents JOIN users on users.id = agents.user_id WHERE agents.id =" . $id_agent . ";";
		$query = $this->db->query($sql);
		$res = $query->row();
		
		if ($res->connection_date == '0000-00-00')
		{
			$connection_date = null;
		}
		else
		{
			$connection_date = $res->connection_date;
		}
		if(isset($res->generation) && $res->generation != null)
		{
			$generation = $res->generation;
			$exist = true;
		}
		if($res->name != ' ')
		{
			$name = $res->name;
		}
		else
		{
			$name = $res->company_name;
		}

		return array(
			'name' => $name,
			'generation' => $generation,
			'connection_date' => $connection_date,
			'exist' => $exist
		);
	}









	public function get_vida_agent_details($year, $id_agent, $ingresos_flag = false)
	{
		$agent_array = $this->get_agent_array($id_agent);



		$actual_day = date('Y-m-d');
		$sql = "SELECT CONCAT(name,' ',lastnames) as name, company_name, connection_date FROM agents JOIN users on users.id = agents.user_id WHERE agents.id =" . $id_agent . ";";
		$query = $this->db->query($sql);
		$res = $query->row();
		$name = '';
		$generation = $this->user->generationByAgentIdNew($actual_day, $id_agent);
		$exist = false;
		if ($res->connection_date == '0000-00-00')
		{
			$connection_date = null;
		}
		else
		{
			$connection_date = $res->connection_date;
		}
		

		if(isset($res->generation) && $res->generation != null)
		{
			$generation = $res->generation;
			$exist = true;
		}
		if($res->name != ' ')
		{
			$name = $res->name;
		}
		else
		{
			$name = $res->company_name;
		}

		//variables auxiliares
		$cartera_real 				= $this->termometro_vida_model->get_detail_cartera_real($id_agent, $year);
		$prima_ubicar 				= $this->termometro_vida_model->get_detail_prima_ubicar($id_agent, $year);
		$primas_pagos 				= $this->termometro_vida_model->get_detail_prima_pago($id_agent, $year);
		$num_negocios 				= $this->termometro_vida_model->get_detail_bussiness($id_agent,$year);
		$conservacion 				= $this->termometro_vida_model->get_detail_conservation($id_agent,$year);
		$cartera_pronosticada 		= $this->termometro_vida_model->get_detail_cartera_pronosticada($id_agent, $year-1);
		$comisiones_directas 		= $this->termometro_vida_model->get_detail_comisiones_directas($prima_ubicar);
		$perce_bono_primer_anio 	= $this->termometro_vida_model->get_detail_perce_bono_primer_anio($generation,$prima_ubicar,$num_negocios, false, $year );
		$bono_primer_anio 			= $this->termometro_vida_model->get_detail_bono_primer_anio($perce_bono_primer_anio, $primas_pagos, $conservacion, $generation);
		$congreso 					= $this->termometro_vida_model->get_detail_congreso_agent($year, $generation,$prima_ubicar,$num_negocios);
		$comision_cartera 			= $this->termometro_vida_model->get_detail_cartera_real($id_agent, $year, true);
		$perce_bono_cartera 		= $this->termometro_vida_model->get_detail_perce_bono_cartera($year, $perce_bono_primer_anio, $conservacion, $prima_ubicar);
		$bono_cartera 				= $this->termometro_vida_model->get_detail_bono_cartera($cartera_real, $perce_bono_cartera);
		$faltante_elite_prod 		= $this->termometro_vida_model->get_detail_faltante_elite_prod($year, $prima_ubicar);
		$faltante_elite_neg  		= $this->termometro_vida_model->get_detail_faltante_elite_neg($num_negocios);
		$puntos_vida 				= $this->termometro_vida_model->get_detail_puntos_vida($prima_ubicar, $generation);
		$puntos_gmm 				= $this->termometro_vida_model->get_detail_puntos_gmm($id_agent, $year);
		$puntos_auto 				= $this->termometro_vida_model->get_detail_puntos_autos($id_agent, $year);
		$club_elite 				= $this->termometro_vida_model->get_detail_club_elite($faltante_elite_prod, $faltante_elite_neg);
		$bono_integral 				= $this->termometro_vida_model->get_detail_bono_integral($puntos_vida, $puntos_gmm, $puntos_auto, $bono_primer_anio);
		$bono_cartera_no_ganado 	= $this->termometro_vida_model->get_detail_bono_cartera_no_ganado($cartera_real, $bono_cartera);
		$suma_ingresos_totales 		= $this->termometro_vida_model->get_detail_suma_ingresos_totales($comisiones_directas,$comision_cartera,$bono_primer_anio,$bono_cartera);
		$faltante_bono_primer_anio 	= $this->termometro_vida_model->get_detail_perce_bono_primer_anio($generation,$prima_ubicar,$num_negocios, true, $year);
		$puntos_standing 			= $this->termometro_vida_model->get_detail_puntos_standing($year, $num_negocios, $prima_ubicar, $generation);
		$faltante_bono_cartera 		= $this->termometro_vida_model->get_detail_perce_bono_cartera($year, $perce_bono_primer_anio, $conservacion, $prima_ubicar, true);
		$ingresos_totales 			= $this->termometro_vida_model->get_detail_ingresos_totales($bono_primer_anio, $club_elite, $bono_integral, $comisiones_directas, $comision_cartera, $bono_cartera,$ingresos_flag);
		$cartera_estimada 			= $cartera_real;

		//dar formato a los arrays
		if($ingresos_flag == false)
		{
			$cartera_pronosticada 	= $this->termometro_vida_model->set_final_array($cartera_pronosticada,2);
			$cartera_real 			= $this->termometro_vida_model->set_final_array($cartera_real,2);
			$prima_ubicar 			= $this->termometro_vida_model->set_final_array($prima_ubicar,2);
			$primas_pagos 			= $this->termometro_vida_model->set_final_array($primas_pagos,2);
			$num_negocios 			= $this->termometro_vida_model->set_final_array($num_negocios,0);
			$conservacion 			= $this->termometro_vida_model->set_final_array($conservacion,2);
			$comision_cartera 		= $this->termometro_vida_model->set_final_array($comision_cartera,2);
			$bono_primer_anio 		= $this->termometro_vida_model->set_final_array($bono_primer_anio,2);
			$bono_cartera 			= $this->termometro_vida_model->set_final_array($bono_cartera,2);
			$comisiones_directas 	= $this->termometro_vida_model->set_final_array($comisiones_directas,2);
			$cartera_estimada 		= $this->termometro_vida_model->set_final_array($cartera_estimada,2);
		}
		
		//array final
		$values_agent = array(
			'name' 										=> $agent_array['name'],
			'connection_date' 							=> $agent_array['connection_date'],
			'generation' 								=> $agent_array['generation'],
			'exist'										=> $agent_array['exist'],
			'cartera_real' 								=> $cartera_real,
			'prima_ubicar' 								=> $prima_ubicar,
			'prima_pago' 								=> $primas_pagos,
			'numero_negocios' 							=> $num_negocios,
			'comisiones_directas' 						=> $comisiones_directas,
			'perce_bono_primer_anio' 					=> $perce_bono_primer_anio,
			'bono_primer_anio' 							=> $bono_primer_anio,
			'conservacion' 								=> $conservacion,
			'comision_cartera' 							=> $comision_cartera,
			'perce_bono_cartera' 						=> $perce_bono_cartera,
			'bono_cartera' 								=> $bono_cartera,
			'bono_cartera_no_ganado' 					=> $bono_cartera_no_ganado,
			'suma_ingresos_totales' 					=> $suma_ingresos_totales,
			'faltante_negocios' 						=> $faltante_elite_neg,
			'congreso' 									=> $congreso['actual'],
			'congreso_siguiente'						=> $congreso['siguiente'],
			'faltante_congreso_prod'					=> $congreso['prod_siguiente'],
			'faltante_congreso_neg'						=> $congreso['neg_siguiente'],
			'puntos_standing' 							=> $puntos_standing['puntos_standing'],
			'puntos_vida' 								=> $puntos_vida,
			'puntos_gmm' 								=> $puntos_gmm,
			'puntos_autos'								=> $puntos_auto,
			'faltante_bono'								=> $faltante_bono_primer_anio,
			'faltate_bono_cartera'						=> $faltante_bono_cartera,
			'club_elite'								=> $club_elite,
			'faltante_elite_prod'						=> $faltante_elite_prod,
			'faltante_elite_neg'						=> $faltante_elite_neg,
			'faltante_ptos_standing_neg'				=> $puntos_standing['faltante_neg'],
			'faltante_ptos_standing_pro'				=> $puntos_standing['faltante_pro'],
			'bono_integral'								=> $bono_integral,
			'ingresos_totales'							=> $ingresos_totales,
			'cartera_estimada' 							=> $cartera_estimada,
			'cartera_pronosticada'						=> $cartera_pronosticada
			
		);
		return $values_agent;
	}
	public function get_gmm_agent_details($year, $agent_id, $ingresos_flag=false)
	{
		//array de datos del agente
		$agent_array = $this->get_agent_array($agent_id);

		/*

		//variables auxiliares sin datos dependientes
		$primas_ubicar 					= $this->termometro_gmm_model->get_value_by_period('primas_ubicar', $year, $agent_id);
		$primas_pagos 					= $this->termometro_gmm_model->get_value_by_period('primas_pagar', $year, $agent_id);
		$nuevos_asegurados 				= $this->termometro_gmm_model->get_value_by_period('nuevos_asegurados', $year, $agent_id);
		$cartera_conservada 			= $this->termometro_gmm_model->get_value_by_period('cartera_conservada', $year, $agent_id);
		$primas_netas 					= $this->termometro_gmm_model->get_value_by_period('primas_netas', $year, $agent_id);
		$result_siniestralidad_real 	= $this->termometro_gmm_model->get_value_by_period('result_siniestralidad_real', $year, $agent_id);
		$result_siniestralidad_acot 	= $this->termometro_gmm_model->get_value_by_period('result_siniestralidad_acot', $year, $agent_id);
		$siniestros_pagados_real 		= $this->termometro_gmm_model->get_value_by_period('siniestralidad_real', $year, $agent_id);
		$siniestros_pagados_acot 		= $this->termometro_gmm_model->get_value_by_period('siniestralidad_acot', $year, $agent_id);
		$numero_asegurados 				= $this->termometro_gmm_model->get_value_by_period('numero_asegurados_nuevos', $year, $agent_id);
		$perce_bono_primer_anio 		= $this->termometro_gmm_model->get_value_in_table('bono_1st_year_perc',$year,$primas_ubicar,$nuevos_asegurados);
		$perce_bono_rentabilidad 		= 

		*/


		//variables auxiliares con datos dependientes


		$cartera_real 					= $this->termometro_gmm_model->get_detail_cartera_real($year, $agent_id);
		$cartera_estimada 				= $this->termometro_gmm_model->get_detail_cartera_estimada($year-1, $agent_id);
		$primas_ubicar 					= $this->termometro_gmm_model->get_detail_primas_ubicar($year, $agent_id);
		$primas_pagos 					= $this->termometro_gmm_model->get_detail_primas_pagar( $year, $agent_id);
		$nuevos_asegurados 				= $this->termometro_gmm_model->get_detail_nuevos_asegurados($year,$agent_id);
		$bono_primer_anio 				= $this->termometro_gmm_model->get_detail_perce_bono_primer_anio($year, $primas_ubicar,$agent_array['generation'],$nuevos_asegurados);
		$cartera_conservada 			= $this->termometro_gmm_model->get_detail_cartera_conservada($year,$agent_id);
		$result_siniestralidad_real 	= $this->termometro_gmm_model->get_detail_result_siniestralidad_real($year,$agent_id);
		$result_siniestralidad_acot 	= $this->termometro_gmm_model->get_detail_result_siniestralidad_acot($year,$agent_id);
		$comisiones_directas 			= $this->termometro_gmm_model->get_detail_comisiones_directas($primas_ubicar);
		$comision_cartera 				= $this->termometro_gmm_model->get_detail_comision_cartera($cartera_conservada);
		$perce_bono_primer_anio = array(
			$bono_primer_anio[0]['perce_bono'],
			$bono_primer_anio[1]['perce_bono'],
			$bono_primer_anio[2]['perce_bono']
		);
		$faltante_bono_primer_anio = array(
			$bono_primer_anio[0]['faltante'],
			$bono_primer_anio[1]['faltante'],
			$bono_primer_anio[2]['faltante']
		);
		$bono_primer_anio_aux 			= $this->termometro_gmm_model->get_detail_bono_primer_anio($cartera_conservada, $agent_array['generation'], $perce_bono_primer_anio, $primas_pagos);
		$primas_netas 					= $this->termometro_gmm_model->get_detail_primas_netas($year,$agent_id);
		$siniestros_pagados_real 		= $this->termometro_gmm_model->get_detail_siniestros_pagados_real($year,$agent_id);
		$siniestros_pagados_acot 		= $this->termometro_gmm_model->get_detail_siniestros_pagados_acot($year,$agent_id);
		$numero_asegurados 				= $this->termometro_gmm_model->get_detail_numero_asegurados($year,$agent_id);
		$bono_rent_perc_aux 			= $this->termometro_gmm_model->get_detail_perce_bono_rentabilidad($primas_ubicar, $perce_bono_primer_anio,$result_siniestralidad_real, $result_siniestralidad_acot, $year,$agent_id);
		$perce_bono_rentabilidad 		= array($bono_rent_perc_aux[0][0], $bono_rent_perc_aux[1][0], $bono_rent_perc_aux[2][0]);
		$bono_rentabilidad 				= $this->termometro_gmm_model->get_detail_bono_rentabilidad($cartera_conservada, $perce_bono_rentabilidad);
		$faltante_bono_rentabilidad 	= array($bono_rent_perc_aux[0][1], $bono_rent_perc_aux[1][1], $bono_rent_perc_aux[2][1]);
		$bono_rentabilidad_no_ganado 	= $this->termometro_gmm_model->get_detail_bono_rentabilidad_no_ganado($cartera_conservada, $bono_rentabilidad, $year,$agent_id);
		$suma_ingresos 					= $this->termometro_gmm_model->get_detail_suma_ingresos($bono_primer_anio_aux, $comisiones_directas, $comision_cartera, $bono_rentabilidad);
		$congreso 						= $this->termometro_gmm_model->get_detail_congreso($primas_ubicar, $year, $agent_id);
		$congreso_siguiente 			= $this->termometro_gmm_model->get_detail_congreso_siguiente($congreso, $year, $agent_id);
		$faltante_produccion_inicial 	= $this->termometro_gmm_model->get_detail_faltante_produccion_inicial($primas_ubicar, $congreso_siguiente, $year,$agent_id);
		$agente_productivo 				= $this->termometro_gmm_model->get_detail_agente_productivo($primas_ubicar, $agent_array['generation'], $year,$agent_id);
		$ingresos_totales 				= $this->termometro_gmm_model->get_detail_ingresos_totales($suma_ingresos);
		
		//dar formato a los arrays
		if($ingresos_flag==false)
		{
			$cartera_real 					= $this->termometro_gmm_model->set_final_array($cartera_real,2);
			$cartera_estimada 				= $this->termometro_gmm_model->set_final_array($cartera_estimada,2);
			$primas_ubicar 					= $this->termometro_gmm_model->set_final_array($primas_ubicar,2);
			$primas_pagos 					= $this->termometro_gmm_model->set_final_array($primas_pagos,2);
			$nuevos_asegurados 				= $this->termometro_gmm_model->set_final_array($nuevos_asegurados ,0);
			$comisiones_directas 			= $this->termometro_gmm_model->set_final_array($comisiones_directas,2);
			$bono_primer_anio_aux 			= $this->termometro_gmm_model->set_final_array($bono_primer_anio_aux,2);
			$primas_netas 					= $this->termometro_gmm_model->set_final_array($primas_netas,2);
			$siniestros_pagados_real 		= $this->termometro_gmm_model->set_final_array($siniestros_pagados_real,2);
			$siniestros_pagados_acot 		= $this->termometro_gmm_model->set_final_array($siniestros_pagados_acot,2);
			$result_siniestralidad_real 	= $this->termometro_gmm_model->set_final_array($result_siniestralidad_real,2);
			$result_siniestralidad_acot 	= $this->termometro_gmm_model->set_final_array($result_siniestralidad_real,2);
			$cartera_conservada  			= $this->termometro_gmm_model->set_final_array($cartera_conservada,2);
			$comision_cartera 				= $this->termometro_gmm_model->set_final_array($comision_cartera,2);
			$numero_asegurados 				= $this->termometro_gmm_model->set_final_array($numero_asegurados ,0);
			$bono_rentabilidad 				= $this->termometro_gmm_model->set_final_array($bono_rentabilidad ,2);
			$bono_rentabilidad_no_ganado 	= $this->termometro_gmm_model->set_final_array($bono_rentabilidad_no_ganado ,2);
			$suma_ingresos 					= $this->termometro_gmm_model->set_final_array($suma_ingresos ,2);
			$faltante_bono_rentabilidad		= $this->termometro_gmm_model->set_final_array($faltante_bono_rentabilidad ,2);
			$faltante_bono_primer_anio 		= $this->termometro_gmm_model->set_final_array($faltante_bono_primer_anio,2);
			$perce_bono_primer_anio 		= $this->termometro_gmm_model->set_final_array($perce_bono_primer_anio,2);
			$perce_bono_rentabilidad 		= $this->termometro_gmm_model->set_final_array($perce_bono_rentabilidad ,2);
		}

		$values_agent = array(
			'name' 							=> $agent_array['name'],
			'connection_date' 				=> $agent_array['connection_date'],
			'generation' 					=> $agent_array['generation'],
			'exist'							=> $agent_array['exist'],
			'primas_ubicar'				 	=> $primas_ubicar,
			'primas_pagar' 				 	=> $primas_pagos,
			'nuevos_asegurados'			 	=> $nuevos_asegurados,
			'comisiones_directas' 		 	=> $comisiones_directas,
			'perce_bono_primer_anio' 	 	=> $perce_bono_primer_anio,
			'bono_primer_anio' 			 	=> $bono_primer_anio_aux,
			'faltante_bono_primer_anio' 	=> $faltante_bono_primer_anio,
			'primas_netas'				 	=> $primas_netas,
			'siniestros_pagados_real'	 	=> $siniestros_pagados_real,
			'siniestros_pagados_acot'	 	=> $siniestros_pagados_acot,
			'result_siniestralidad_real' 	=> $result_siniestralidad_real,
			'result_siniestralidad_acot' 	=> $result_siniestralidad_acot,
			'cartera_conservada'		 	=> $cartera_conservada,
			'comision_cartera'			 	=> $comision_cartera,
			'numero_asegurados'			 	=> $numero_asegurados,
			'perce_bono_rentabilidad'	 	=> $perce_bono_rentabilidad,
			'bono_rentabilidad'			 	=> $bono_rentabilidad,
			'faltante_bono_rentabilidad' 	=> $faltante_bono_rentabilidad,
			'bono_rentabilidad_no_ganado' 	=> $bono_rentabilidad_no_ganado,
			'suma_ingresos' 				=> $suma_ingresos,
			'congreso' 						=> $congreso,
			'congreso_siguiente' 			=> $congreso_siguiente,
			'faltante_produccion_inicial' 	=> $faltante_produccion_inicial,
			'agente_productivo' 			=> $agente_productivo,
			'ingresos_totales' 				=> $ingresos_totales,
			'cartera_estimada' 				=> $cartera_estimada,
			'cartera_real' 					=> $cartera_real
		);

		return $values_agent;
	}

	/*******  FUNCIONES PARA OBTENER LA LISTA PRINCIPAL DE LOS INDICADORES  *******/
	public function get_main_list($year)
    {
        $sql = "SELECT * FROM general_pays_agent WHERE periodo = ".$year.";";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res;
    }
    public function get_main_array($main_list = array(), $year)
    {
        $array_values = array(
            'vi_grl_income'  => 0,
            'vi_primas_ubi'  => 0,
            'vi_primas_pag'  => 0,
            'vi_ptos_stand'  => 0,
            'vi_new_income'  => 0,
            'vi_ren_income'  => 0,
            'vi_income_gen'  => array(0,0,0,0,0),
            'vi_pai_buss'    => 0,
            'vi_prod_ini'    => 0,
            'vi_agent_new'   => 0,
            'gmm_grl_income' => 0,
            'gmm_primas_ubi' => 0,
            'gmm_primas_pag' => 0,
            'gmm_new_income' => 0,
            'gmm_ren_income' => 0,
            'gmm_income_gen' => array(0,0,0,0,0),
            'gmm_agent_new'  => 0,
            'gmm_num_buss'   => 0,
            'gmm_prod_ini'   => 0,
            'vi_agent_con'   => array(0,0,0,0),
            'gmm_agent_con'  => array(0,0,0,0),
            'gmm_agent_prod' => 0,
            'agent_recruit'  => 0,
            'agent_rec_prod' => 0,
            'agent_bonus' 	 => 0,
            'agent_active'	 => 0

        );
        foreach ($main_list as $row) 
        {
            $array_values['vi_grl_income'] = $array_values['vi_grl_income'] + $row['vi_grl_income'];
            $array_values['vi_primas_ubi'] = $array_values['vi_primas_ubi'] + $row['vi_primas_ubi'];
            $array_values['vi_primas_pag'] = $array_values['vi_primas_pag'] + $row['vi_primas_pag'];
            $array_values['vi_ptos_stand'] = $array_values['vi_ptos_stand'] + $row['vi_ptos_stand'];
            $array_values['vi_new_income'] = $array_values['vi_new_income'] + $row['vi_new_income'];
            $array_values['vi_ren_income'] = $array_values['vi_ren_income'] + $row['vi_ren_income'];
            $array_values['vi_pai_buss']   = $array_values['vi_pai_buss']   + $row['vi_pai_buss'];

            $array_values['gmm_grl_income'] = $array_values['gmm_grl_income'] + $row['gmm_grl_income'];
            $array_values['gmm_primas_ubi'] = $array_values['gmm_primas_ubi'] + $row['gmm_primas_ubi'];
            $array_values['gmm_primas_pag'] = $array_values['gmm_primas_pag'] + $row['gmm_primas_pag'];
            $array_values['gmm_new_income'] = $array_values['gmm_new_income'] + $row['gmm_new_income'];
            $array_values['gmm_ren_income'] = $array_values['gmm_ren_income'] + $row['gmm_ren_income'];
            $array_values['gmm_num_buss']   = $array_values['gmm_num_buss']   + $row['gmm_num_buss'];

            if( $row['vi_grl_income'] + $row['gmm_grl_income'] >= 380000)
            {
            	$array_values['agent_active']++;
            }
            switch ($row['generation']) {
            	case 'Generación 1':
            		$array_values['vi_income_gen'][0]  = $array_values['vi_income_gen'][0]  + $row['vi_grl_income'];
            		$array_values['gmm_income_gen'][0] = $array_values['gmm_income_gen'][0] + $row['gmm_grl_income'];
            		if($row['gmm_primas_ubi'] >= 150000)
                    {
                        $array_values['gmm_agent_prod']++;
                    }
            		break;
            	case 'Generación 2':
            		$array_values['vi_income_gen'][1]  = $array_values['vi_income_gen'][1]  + $row['vi_grl_income'];
            		$array_values['gmm_income_gen'][1] = $array_values['gmm_income_gen'][1] + $row['gmm_grl_income'];
            		if($row['gmm_primas_ubi'] >= 150000)
                    {
                        $array_values['gmm_agent_prod']++;
                    }
            		break;
            	case 'Generación 3':
            		$array_values['vi_income_gen'][2]  = $array_values['vi_income_gen'][2]  + $row['vi_grl_income'];
            		$array_values['gmm_income_gen'][2] = $array_values['gmm_income_gen'][2] + $row['gmm_grl_income'];
            		if($row['gmm_primas_ubi'] >= 250000)
                    {
                        $array_values['gmm_agent_prod']++;
                    }
            		break;
            	case 'Generación 4':
            		$array_values['vi_income_gen'][3]  = $array_values['vi_income_gen'][3]  + $row['vi_grl_income'];
            		$array_values['gmm_income_gen'][3] = $array_values['gmm_income_gen'][3] + $row['gmm_grl_income'];
            		if($row['gmm_primas_ubi'] >= 300000)
                    {
                        $array_values['gmm_agent_prod']++;
                    }
            		break;
            	case 'Consolidado':
            		$array_values['vi_income_gen'][4]  = $array_values['vi_income_gen'][4]  + $row['vi_grl_income'];
            		$array_values['gmm_income_gen'][4] = $array_values['gmm_income_gen'][4] + $row['gmm_grl_income'];
            		if($row['gmm_primas_ubi'] >= 300000)
                    {
                        $array_values['gmm_agent_prod']++;
                    }
            		break;
            	default:
            		break;
            }
            switch ($row['vi_congreso']) {
            	case 'Oro':
            		$array_values['vi_agent_con'][0]++;
            		break;
            	case 'Platino':
            		$array_values['vi_agent_con'][1]++;
            		break;
            	case 'Diamante':
            		$array_values['vi_agent_con'][2]++;
            		break;
            	case 'Consejo':
            		$array_values['vi_agent_con'][3]++;
            		break;
            	default:
            		break;
            }
            switch ($row['gmm_congreso']) {
            	case 'Oro':
            		$array_values['gmm_agent_con'][0]++;
            		break;
            	case 'Platino':
            		$array_values['gmm_agent_con'][1]++;
            		break;
            	case 'Diamante':
            		$array_values['gmm_agent_con'][2]++;
            		break;
            	case 'Consejo':
            		$array_values['gmm_agent_con'][3]++;
            		break;
            	default:
            		break;
            }

            if( $row['generation'] != 'Consolidado' )
            {
                $array_values['vi_prod_ini']  = $array_values['vi_prod_ini']  + $row['vi_primas_ubi'];
                $array_values['gmm_prod_ini'] = $array_values['gmm_prod_ini'] + $row['gmm_primas_ubi'];
            }
            if(  $row['vi_new_income'] > 0 )
            {
            	$array_values['vi_agent_new']++;
            }
            if(  $row['gmm_new_income'] > 0 )
            {
            	$array_values['gmm_agent_new']++;
            }
            if(strtotime($row['connection_date']) >= strtotime(''.$year.'-01-01') && strtotime($row['connection_date']) <= strtotime(''.$year.'-12-31'))
			{
				$array_values['agent_recruit']++;	
				if($row['vi_primas_ubi'] + $row['gmm_primas_ubi'] > 0)
				{
					$array_values['agent_rec_prod']++;
				}
			}
			$points = 0;
			if($row['gmm_primas_ubi'] >= 120000 && $row['gmm_primas_ubi'] < 200000)
			{
				$points = $points + 1;
			}
			if($row['gmm_primas_ubi'] >= 200000 && $row['gmm_primas_ubi'] < 290000)
			{
				$points = $points + 2;
			}
			if($row['gmm_primas_ubi'] >= 290000 && $row['gmm_primas_ubi'] < 400000)
			{
				$points = $points + 3;
			}
			if($row['gmm_primas_ubi'] >= 400000)
			{
				$points = $points + 4;
			}
			if($row['vi_primas_ubi'] >= 120000 && $row['vi_primas_ubi'] < 240000)
			{
				$points = $points + 1;
			}
			if($row['vi_primas_ubi'] >= 240000 && $row['vi_primas_ubi'] < 410000)
			{
				$points = $points + 2;
			}
			if($row['vi_primas_ubi'] >= 410000)
			{
				$points = $points + 3;
			}
			if($points > 6)
			{
				$array_values['agent_bonus']++;
			}

        }
        return $array_values;
    }

	/*******  FUNCIONES PARA OBTENER SIMULACION DE AGENTES EN VIDA Y GMM  *******/
	public function get_vida_agent_simulation($data_array)
	{
		$id_agent = $data_array['agent_id'];
		$sql = "SELECT CONCAT(name,' ',lastnames) as name FROM agents JOIN users on users.id = agents.user_id WHERE agents.id =" . $id_agent . ";";
		$query = $this->db->query($sql);
		$res = $query->row();
		$name = '';
		if(isset($res->name) && $res->name != null)
		{
			$name = $res->name;
		}

		
		$generation 				= $data_array['generation'];
		$year						= $data_array['year'];
		$cartera_estimada 			= $data_array['cartera_real'];
		$prima_ubicar 				= $data_array['prima_ubicar'];
		$primas_pagos 				= $data_array['primas_pagos'];
		$num_negocios 				= $data_array['num_negocios'];
		$conservacion 				= $data_array['conservacion'];
		$cartera_real 				= $this->termometro_vida_model->get_detail_cartera_real($id_agent, $year);
		$comisiones_directas 		= $this->termometro_vida_model->get_detail_comisiones_directas($prima_ubicar);
		$perce_bono_primer_anio 	= $this->termometro_vida_model->get_detail_perce_bono_primer_anio($generation,$prima_ubicar,$num_negocios, false, $year);
		$bono_primer_anio 			= $this->termometro_vida_model->get_detail_bono_primer_anio($perce_bono_primer_anio, $primas_pagos, $conservacion, $generation);
		$congreso 					= $this->termometro_vida_model->get_detail_congreso_agent($year, $generation,$prima_ubicar,$num_negocios);
		$comision_cartera 			= $this->termometro_vida_model->get_detail_cartera_real_simulation($cartera_estimada);
		$perce_bono_cartera 		= $this->termometro_vida_model->get_detail_perce_bono_cartera($year, $perce_bono_primer_anio, $conservacion, $prima_ubicar);
		$bono_cartera 				= $this->termometro_vida_model->get_detail_bono_cartera($cartera_estimada, $perce_bono_cartera);
		$faltante_elite_prod 		= $this->termometro_vida_model->get_detail_faltante_elite_prod($year, $prima_ubicar);
		$faltante_elite_neg  		= $this->termometro_vida_model->get_detail_faltante_elite_neg($num_negocios);
		$puntos_vida 				= $this->termometro_vida_model->get_detail_puntos_vida($prima_ubicar, $generation);
		$puntos_gmm 				= $this->termometro_vida_model->get_detail_puntos_gmm($id_agent, $year);
		$puntos_auto 				= $this->termometro_vida_model->get_detail_puntos_autos($id_agent, $year);
		$club_elite 				= $this->termometro_vida_model->get_detail_club_elite($faltante_elite_prod, $faltante_elite_neg);
		$bono_integral 				= $this->termometro_vida_model->get_detail_bono_integral($puntos_vida, $puntos_gmm, $puntos_auto, $bono_primer_anio);
		$bono_cartera_no_ganado 	= $this->termometro_vida_model->get_detail_bono_cartera_no_ganado($cartera_estimada, $bono_cartera);
		$suma_ingresos_totales 		= $this->termometro_vida_model->get_detail_suma_ingresos_totales($bono_primer_anio,$comisiones_directas,$comision_cartera,$bono_cartera);
		$faltante_bono_primer_anio 	= $this->termometro_vida_model->get_detail_perce_bono_primer_anio($generation,$prima_ubicar,$num_negocios, true, $year);
		$puntos_standing 			= $this->termometro_vida_model->get_detail_puntos_standing($year, $num_negocios, $prima_ubicar, $generation);
		$faltante_bono_cartera 		= $this->termometro_vida_model->get_detail_perce_bono_cartera($year, $perce_bono_primer_anio, $conservacion, $prima_ubicar, true);
		$ingresos_totales 			= $this->termometro_vida_model->get_detail_ingresos_totales($bono_primer_anio, $club_elite, $bono_integral, $comisiones_directas, $comision_cartera, $bono_cartera);
		$cartera_pronosticada 		= $this->termometro_vida_model->get_detail_cartera_pronosticada($id_agent, $year-1);
		//$cartera_estimada 			= $this->termometro_vida_model->get_detail_cartera_estimada($cartera_real,$primas_pagos);

		//dar formato a los arrays
		$cartera_pronosticada 	= $this->termometro_vida_model->set_final_array($cartera_pronosticada,2);
		$cartera_estimada 		= $this->termometro_vida_model->set_final_array($cartera_estimada,2);
		$cartera_real 			= $this->termometro_vida_model->set_final_array($cartera_real,2);
		$prima_ubicar 			= $this->termometro_vida_model->set_final_array($prima_ubicar,2);
		$primas_pagos 			= $this->termometro_vida_model->set_final_array($primas_pagos,2);
		$num_negocios 			= $this->termometro_vida_model->set_final_array($num_negocios,0);
		$conservacion 			= $this->termometro_vida_model->set_final_array($conservacion,2);
		$comision_cartera 		= $this->termometro_vida_model->set_final_array($comision_cartera,2);
		$bono_primer_anio 		= $this->termometro_vida_model->set_final_array($bono_primer_anio,2);
		$bono_cartera 			= $this->termometro_vida_model->set_final_array($bono_cartera,2);
		$comisiones_directas 	= $this->termometro_vida_model->set_final_array($comisiones_directas,2);

		//array final
		$values_agent = array(
			'name' 										=> $name,
			'generation' 								=> $generation,
			'cartera_real' 								=> $cartera_real,
			'prima_ubicar' 								=> $prima_ubicar,
			'prima_pago' 								=> $primas_pagos,
			'numero_negocios' 							=> $num_negocios,
			'comisiones_directas' 						=> $comisiones_directas,
			'conservacion' 								=> $conservacion,
			'perce_bono_primer_anio' 					=> $perce_bono_primer_anio,
			'bono_primer_anio' 							=> $bono_primer_anio,
			'faltante_bono'								=> $faltante_bono_primer_anio,
			'comision_cartera' 							=> $comision_cartera,
			'perce_bono_cartera' 						=> $perce_bono_cartera,
			'bono_cartera' 								=> $bono_cartera,
			'bono_cartera_no_ganado' 					=> $bono_cartera_no_ganado,
			'suma_ingresos_totales' 					=> $suma_ingresos_totales,
			'faltante_negocios' 						=> $faltante_elite_neg,
			'congreso' 									=> $congreso['actual'],
			'congreso_siguiente'						=> $congreso['siguiente'],
			'faltante_congreso_prod'					=> $congreso['prod_siguiente'],
			'faltante_congreso_neg'						=> $congreso['neg_siguiente'],
			'puntos_standing' 							=> $puntos_standing['puntos_standing'],
			'puntos_vida' 								=> $puntos_vida,
			'puntos_gmm' 								=> $puntos_gmm,
			'puntos_autos'								=> $puntos_auto,
			'faltate_bono_cartera'						=> $faltante_bono_cartera,
			'club_elite'								=> $club_elite,
			'faltante_elite_prod'						=> $faltante_elite_prod,
			'faltante_elite_neg'						=> $faltante_elite_neg,
			'faltante_ptos_standing_neg'				=> $puntos_standing['faltante_neg'],
			'faltante_ptos_standing_pro'				=> $puntos_standing['faltante_pro'],
			'bono_integral'								=> $bono_integral,
			'ingresos_totales'							=> $ingresos_totales,
			'cartera_estimada' 							=> $cartera_estimada,
			'cartera_pronosticada'						=> $cartera_pronosticada
		);
		
		$final_result = json_encode($values_agent);
		return $final_result;
	} 
	public function get_gmm_agent_simulation($data_array)
	{
		$agent_id 					= $data_array['agent_id'];
		$sql = "SELECT CONCAT(name,' ',lastnames) as name, company_name FROM agents JOIN users on users.id = agents.user_id WHERE agents.id =" . $agent_id . ";";
		$query = $this->db->query($sql);
		$res = $query->row();
		$name = '';
		if($res->name != ' ')
		{
			$name = $res->name;
		}
		else
		{
			$name = $res->company_name;
		}
		$generation 				= $data_array['generation'];
		$year						= $data_array['year'];

		//variables auxiliares
		$cartera_estimada 				= $this->termometro_gmm_model->get_detail_cartera_estimada($year-1, $agent_id);
		$prima_ubicar 					= $data_array['prima_ubicar'];
		$primas_pagos 					= $data_array['primas_pagos'];
		$nuev_asegurados 				= $data_array['nuev_asegurados'];
		$bono_primer_anio 				= $this->termometro_gmm_model->get_detail_perce_bono_primer_anio($year, $prima_ubicar, $generation, $nuev_asegurados);
		$cartera_conservada 			= $data_array['cartera_conservada'];
		$result_siniestralidad_real 	= $this->termometro_gmm_model->get_detail_result_siniestralidad_real($year,$agent_id);
		$result_siniestralidad_acot 	= $this->termometro_gmm_model->get_detail_result_siniestralidad_acot($year,$agent_id);
		$comisiones_directas 			= $this->termometro_gmm_model->get_detail_comisiones_directas($prima_ubicar);
		$comision_cartera 				= $this->termometro_gmm_model->get_detail_comision_cartera($cartera_conservada);
		$perce_bono_primer_anio = array(
			$bono_primer_anio[0]['perce_bono'],
			$bono_primer_anio[1]['perce_bono'],
			$bono_primer_anio[2]['perce_bono']
		);
		$faltante_bono_primer_anio = array(
			$bono_primer_anio[0]['faltante'],
			$bono_primer_anio[1]['faltante'],
			$bono_primer_anio[2]['faltante']
		);
		$bono_primer_anio_aux 			= $this->termometro_gmm_model->get_detail_bono_primer_anio($cartera_conservada, $generation, $perce_bono_primer_anio, $primas_pagos);

		$primas_netas 					= $this->termometro_gmm_model->get_detail_primas_netas($year,$agent_id);
		$siniestros_pagados_real 		= $this->termometro_gmm_model->get_detail_siniestros_pagados_real($year,$agent_id);
		$siniestros_pagados_acot 		= $this->termometro_gmm_model->get_detail_siniestros_pagados_acot($year,$agent_id);
		$numero_asegurados 				= $this->termometro_gmm_model->get_detail_numero_asegurados($year,$agent_id);
		$bono_rent_perc_aux 			= $this->termometro_gmm_model->get_detail_perce_bono_rentabilidad($prima_ubicar, $perce_bono_primer_anio,$result_siniestralidad_real, $result_siniestralidad_acot, $year,$agent_id);
		$perce_bono_rentabilidad 		= array($bono_rent_perc_aux[0][0], $bono_rent_perc_aux[1][0], $bono_rent_perc_aux[2][0]);
		$bono_rentabilidad 				= $this->termometro_gmm_model->get_detail_bono_rentabilidad($cartera_conservada, $perce_bono_rentabilidad);
		$faltante_bono_rentabilidad 	= array($bono_rent_perc_aux[0][1], $bono_rent_perc_aux[1][1], $bono_rent_perc_aux[2][1]);
		$bono_rentabilidad_no_ganado 	= $this->termometro_gmm_model->get_detail_bono_rentabilidad_no_ganado($cartera_conservada, $bono_rentabilidad, $year,$agent_id);
		$suma_ingresos 					= $this->termometro_gmm_model->get_detail_suma_ingresos($bono_primer_anio_aux, $comisiones_directas, $comision_cartera, $bono_rentabilidad);
		$congreso 						= $this->termometro_gmm_model->get_detail_congreso($prima_ubicar, $year, $agent_id);
		$congreso_siguiente 			= $this->termometro_gmm_model->get_detail_congreso_siguiente($congreso, $year, $agent_id);
		$faltante_produccion_inicial 	= $this->termometro_gmm_model->get_detail_faltante_produccion_inicial($prima_ubicar, $congreso_siguiente, $year,$agent_id);
		$agente_productivo 				= $this->termometro_gmm_model->get_detail_agente_productivo($prima_ubicar, $generation, $year,$agent_id);
		$ingresos_totales 				= $this->termometro_gmm_model->get_detail_ingresos_totales($suma_ingresos);

		//dar formato a los arrays
		$cartera_estimada 				= $this->termometro_gmm_model->set_final_array($cartera_estimada,2);
		$prima_ubicar 					= $this->termometro_gmm_model->set_final_array($prima_ubicar,2);
		$primas_pagos 					= $this->termometro_gmm_model->set_final_array($primas_pagos,2);
		$nuev_asegurados 				= $this->termometro_gmm_model->set_final_array($nuev_asegurados ,0);
		$comisiones_directas 			= $this->termometro_gmm_model->set_final_array($comisiones_directas,2);
		$bono_primer_anio 				= $this->termometro_gmm_model->set_final_array($bono_primer_anio,2);
		$primas_netas 					= $this->termometro_gmm_model->set_final_array($primas_netas,2);
		$siniestros_pagados_real 		= $this->termometro_gmm_model->set_final_array($siniestros_pagados_real,2);
		$siniestros_pagados_acot 		= $this->termometro_gmm_model->set_final_array($siniestros_pagados_acot,2);
		$result_siniestralidad_real 	= $this->termometro_gmm_model->set_final_array($result_siniestralidad_real,2);
		$result_siniestralidad_acot 	= $this->termometro_gmm_model->set_final_array($result_siniestralidad_real,2);
		$cartera_conservada  			= $this->termometro_gmm_model->set_final_array($cartera_conservada,2);
		$comision_cartera 				= $this->termometro_gmm_model->set_final_array($comision_cartera,2);
		$numero_asegurados 				= $this->termometro_gmm_model->set_final_array($numero_asegurados ,2);
		$bono_rentabilidad 				= $this->termometro_gmm_model->set_final_array($bono_rentabilidad ,2);
		$bono_rentabilidad_no_ganado 	= $this->termometro_gmm_model->set_final_array($bono_rentabilidad_no_ganado ,2);
		$suma_ingresos 					= $this->termometro_gmm_model->set_final_array($suma_ingresos ,2);
		$bono_primer_anio_aux 			= $this->termometro_gmm_model->set_final_array($bono_primer_anio_aux ,2);


		//array final
		$values_agent = array(
			'name'							=> $name,
			'generation'					=> $generation,
			'primas_ubicar'				 	=> $prima_ubicar,
			'primas_pagar' 				 	=> $primas_pagos,
			'nuevos_asegurados'			 	=> $nuev_asegurados,
			'comisiones_directas' 		 	=> $comisiones_directas,
			'perce_bono_primer_anio' 	 	=> $perce_bono_primer_anio,
			'bono_primer_anio' 			 	=> $bono_primer_anio_aux,
			'faltante_bono_primer_anio' 	=> $faltante_bono_primer_anio,
			'primas_netas'				 	=> $primas_netas,
			'siniestros_pagados_real'	 	=> $siniestros_pagados_real,
			'siniestros_pagados_acot'	 	=> $siniestros_pagados_acot,
			'result_siniestralidad_real' 	=> $result_siniestralidad_real,
			'result_siniestralidad_acot' 	=> $result_siniestralidad_acot,
			'cartera_conservada'		 	=> $cartera_conservada,
			'comision_cartera'			 	=> $comision_cartera,
			'numero_asegurados'			 	=> $numero_asegurados,
			'perce_bono_rentabilidad'	 	=> $perce_bono_rentabilidad,
			'bono_rentabilidad'			 	=> $bono_rentabilidad,
			'faltante_bono_rentabilidad' 	=> $faltante_bono_rentabilidad,
			'bono_rentabilidad_no_ganado' 	=> $bono_rentabilidad_no_ganado,
			'suma_ingresos' 				=> $suma_ingresos,
			'congreso' 						=> $congreso,
			'congreso_siguiente' 			=> $congreso_siguiente,
			'faltante_produccion_inicial' 	=> $faltante_produccion_inicial,
			'agente_productivo' 			=> $agente_productivo,
			'ingresos_totales' 				=> $ingresos_totales,
			'cartera_estimada' 				=> $cartera_estimada
		);
		
		$final_result = json_encode($values_agent);
		return $final_result;
	}
	

	/*******  FUNCIONES PARA LOS INDICADORES RELACIONADOS CON LOS AGENTES  *******/
    public function get_grl_recruited_agents($date,$year, $production = false)
    {
        $total_agents = 0;
        $this->db->select('count(*) as total_count');
        $this->db->from('agents');
        if($production)
        {
            $this->db->join('production_','production_.agent_id = agents.id');
            $this->db->like('periodo', $year);
        }
        $this->db->where('connection_date >= "'. $year .'-01-01 00:00:00"');
        $this->db->where('connection_date <= "'. $date .' 23:59:59"');
        if($production)
        {
            $this->db->group_by('agent_id');
        }
        $query = $this->db->get();

        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) 
            {
                $total_agents = $row->total_count;
            }
        }
        $query->free_result();

        return $total_agents;
    }
    public function get_grl_perce_active_agents($agents_actve, $agents_recruit)
    {
    	$sql = "SELECT COUNT(*) AS agentes_sum FROM agents ;";
        $query = $this->db->query($sql);
        $res = $query->result_array();

    	$percentage = 0;
    	if($res[0]['agentes_sum'] !=0)
    	{
    		$percentage = ($agents_actve/$res[0]['agentes_sum'])*100; 
    	}

        return $percentage;
    }


    /*******  FUNCIONES PARA GUARDAR LOS INDICADORES GENERALES POR AGENTE *******/
    public function set_ingresos_generales_agente()
    {	
    	$years = $this->get_global_years();
    	
    	foreach ($years as $year) 
    	{
    		$sql   = "SELECT agent_id FROM production_ WHERE periodo LIKE '%".$year."' GROUP BY agent_id UNION SELECT agent_id FROM production_gmm_ WHERE periodo LIKE '%".$year."' GROUP BY agent_id ";
        	$query = $this->db->query($sql);
        	$resul = $query->result_array();
        	if($query->num_rows() > 0)
        	{
        		foreach ($resul as $row) {
        			if($row['agent_id'] != null)
        			{
        				$agent_data_vi   = $this->get_vida_agent_details($year, $row['agent_id'], true);
		        		$agent_data_gmm  = $this->get_gmm_agent_details($year,  $row['agent_id'], true);
		        		$data_array 	 = array(
		        			'agent_id'		  => $row['agent_id'],
		        			'name'			  => $agent_data_vi['name'],
		        			'vi_grl_income'   => $agent_data_vi['ingresos_totales'],
							'vi_new_income'   => array_sum($agent_data_vi['comisiones_directas']) + array_sum($agent_data_vi['bono_primer_anio']),
							'vi_ren_income'   => array_sum($agent_data_vi['comision_cartera']) + array_sum($agent_data_vi['bono_cartera']),
							'vi_primas_ubi'   => array_sum($agent_data_vi['prima_ubicar']),
							'vi_primas_pag'   => array_sum($agent_data_vi['prima_pago']),
							'vi_ptos_stand'   => $agent_data_vi['puntos_standing'],
							'vi_pai_buss'	  => array_sum($agent_data_vi['numero_negocios']),
							'vi_congreso'	  => $agent_data_vi['congreso'],
							'gmm_grl_income'  => array_sum($agent_data_gmm['suma_ingresos']),
							'gmm_new_income'  => array_sum($agent_data_gmm['comisiones_directas']) + array_sum($agent_data_gmm['bono_primer_anio']),
							'gmm_ren_income'  => array_sum($agent_data_gmm['comision_cartera']) + array_sum($agent_data_gmm['bono_rentabilidad']),
							'gmm_primas_ubi'  => array_sum($agent_data_gmm['primas_ubicar']),
							'gmm_primas_pag'  => array_sum($agent_data_gmm['primas_pagar']),
							'gmm_num_buss'	  => array_sum($agent_data_gmm['nuevos_asegurados']),
							'gmm_congreso'	  => $agent_data_gmm['congreso'],
							'generation'	  => $agent_data_gmm['generation'],
							'connection_date' => $agent_data_vi['connection_date'],
							'periodo'		  => $year,
		        		);
		        		$this->db->select('agent_id');
				    	$this->db->from('general_pays_agent');
				    	$this->db->where('agent_id',$row['agent_id']);
				    	$this->db->where('periodo',$year);
				    	$query_select = $this->db->get();
				    	if($query_select->num_rows() > 0)
		        		{
		        			$this->db->where('agent_id',$row['agent_id']);
		        			$this->db->where('periodo' ,$year);
		        			$this->db->update('general_pays_agent',$data_array);
		        		}
		        		else
		        		{
		        			$this->db->insert('general_pays_agent',$data_array);
		        		}
		        		$query_select->free_result();
        			}
	        	}
	        	$sql_date 	= 'SELECT femovibo AS last_update FROM production_ ORDER BY fecontbo DESC LIMIT 1;';
	        	$query_date = $this->db->query($sql);
        		$resul_date = $query->result_array();
        		$last_updat = array('last_update' => $resul_date[0]['last_update']);
        		$this->db->where('type_update', 'selo');
				$this->db->update('cache_updates', $last_updat);
				$query_date->free_result();
        	}
		}
    }
    public function get_poliza_uid($poliza)
    {
        while ($poliza[0] == '0')
        {
            $poliza = substr($poliza, 1);
        }
        return $poliza;
    }
    public function complete_data_table()
    {
    	$names_group =array(
    		'production_' 		=> 'fla_num',
    		'business_' 		=> 'interl',
    		'conservation_'		=> 'interl',
    		'points_'			=> 'inter',
    		'siniestralidad_' 	=> 'agente',
    		'asegurados_' 		=> 'f_lider',
    		'bona_vi'			=> 'inter',
    		'production_gmm_' 	=> 'clave',
    		'bona_sa' 			=> 'lider'
    	);
    	$sheets_interl = array('business_','conservation_');
		$sheets_inter  = array('points_','bona_vi');
		foreach ($names_group as $table_name => $value) {
			$this->db->select('*');
			$this->db->from($table_name);
			$this->db->where('agent_id is null');
			$this->db->group_by($value);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				foreach ($query->result() as $row) 
	            {
	            	$data_row = array();
	            	$agent_uid = null;
	                switch ($table_name) {
	                	case 'production_':
	                		$agent_uid 				= $row->fla_num;
	                		$aux1 		 			= explode("-", $row->inter);
	    					$data_row['agent_id']   = $this->get_agent_id($row->fla_num);
		    				$data_row['generation'] = $this->user->generationByAgentIdNew($row->femovibo, $data_row['agent_id'], 'vida');
		    				$data_row['inter']  	= $aux1[1]; 
		    				//$data_row['poliza'] 	= $this->get_poliza_uid($row->poliza);
	                		break;
	                	case 'production_gmm_':
	                		$agent_uid   			= $row->clave;
	                		$agent_clave 			= explode(" ", $row->clave);
				    		$array_agent 			= $this->get_agent_id($agent_clave[0]);
				    		$aux1		 			= explode("-", $row->clave);
				    		$data_row['fla_num'] 	= str_replace(' ', '', $aux1[0]); 
				    		$data_row['clave']   	= substr($aux1[1], 1);
				    		$data_row['agent_id']   = $array_agent;
				    		$data_row['generation'] = $this->user->generationByAgentIdNew($row->fecsis, $array_agent, 'gmm');
				    		//$data_row['poliza_'] 	= $this->get_poliza_uid($row->poliza_);
	                		break;
	                	case 'siniestralidad_':
	                		$agent_uid 				= $row->agente;
	                		$data_row['agent_id']   = $this->get_agent_id($row->agente);
	                		break;
	                	case 'asegurados_':
	                		$agent_uid = $row->f_lider;
	                		$data_row['agent_id']   = $this->get_agent_id($row->f_lider);
	                		//$data_row['poliza'] 	= $this->get_poliza_uid($row->poliza);
	                		break;
	                	case 'bona_sa':
	                		$agent_uid = $row->lider;
	                		$data_row['agent_id']   = $this->get_agent_id(substr($row->lider, 0,-3));
	                		break;
	                	
	                	default:
	                		if(in_array($table_name, $sheets_inter))
				    		{
				    			$agent_uid   = $row->inter;
				    			$data_row['agent_id']   = $this->get_agent_id(substr($row->inter, 0,-3));
				    		}
				    		if(in_array($table_name, $sheets_interl))
				    		{
				    			$agent_uid   = $row->interl;
				    			$data_row['agent_id']   = $this->get_agent_id(substr($row->interl, 0,-3));
				    		}
	                		break;
	                }
	                $this->db->where($value,$agent_uid);
	    			$this->db->update($table_name,$data_row);
	            }
			}
			$query->free_result();
		}
		$this->reubild_uids_policies();
    }
    public function reubild_uids_policies()
    {
    	$names_group =array(
    		'production_' 		=> 'poliza',
    		'asegurados_' 		=> 'poliza',
    		'production_gmm_' 	=> 'poliza_'
    	);
    	foreach ($names_group as $table_name => $value) {
			$this->db->select('id,'.$value);
			$this->db->from($table_name);
			$this->db->group_by($value);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				foreach ($query->result() as $row) 
	            {
	            	$data_row = array();
	            	$policy_uid = null;
	                switch ($table_name) {
	                	case 'production_':
	                		$policy_uid = $row->poliza;
		    				$data_row['poliza'] 	= $this->get_poliza_uid($policy_uid);
	                		break;
	                	case 'production_gmm_':
	                		$policy_uid = $row->poliza_;
				    		$data_row['poliza_'] 	= $this->get_poliza_uid($policy_uid);
	                		break;
	                	case 'asegurados_':
	                		$policy_uid = $row->poliza;
	                		$data_row['poliza'] 	= $this->get_poliza_uid($policy_uid);
	                		break;
	                	
	                	default:
	                		break;
	                }
	                $this->db->where($value,$policy_uid);
	    			$this->db->update($table_name,$data_row);
	            }
			}
			$query->free_result();
		}
    }
    public function rebuild_agents_uids()
    {
    	$names	= array('production_','business_','conservation_','points_','siniestralidad_','asegurados_','bona_vi','production_gmm_','asegurados_','bona_sa');
    	$sheets_interl = array('business_','conservation_');
		$sheets_inter  = array('points_','bona_vi');
		foreach ($names as $table_name) 
		{
			
			$sql   = "SELECT * FROM ".$table_name." WHERE agent_id IS NULL";
	    	$query = $this->db->query($sql);
	    	$resul = $query->result_array();
	    	if($query->num_rows() > 0)
	    	{
	    		foreach ($resul as $row_array) 
	    		{
	    			$agent_id = null;
	    			switch ($table_name) {
	    				case 'production_':
	    					$agent_id = $this->get_agent_id($row_array["fla_num"]);
	    					if($agent_id == null)
	    					{
		    					$agent_id 				= $this->get_agent_by_last_name($row_array['inter']);
	    					}
		    				
	    					break;
	    				case 'production_gmm_':
	    					$agent_id = $this->get_agent_id($row_array["fla_num"]);
	    					if($agent_id == null)
	    					{

		    					$agent_id 				= $this->get_agent_by_last_name($row_array['clave']);
	    					}

	    					break;
	    				/*
	    				case 'siniestralidad_':
	    					$agent_id = $this->get_agent_id($row_array["agente"]);
	    					if($agent_id == null)
	    					{
	    						$aux1 		 			= explode(" ", $row_array['clave']);
		    					$agent_last_name 		= $aux1[0].' '.$aux1[1];
		    					$agent_uid 				= $row['fla_num'];
		    					$agent_id 				= $this->get_agent_by_last_name($agent_last_name);
	    					}
	    					break;
	    				
	    				case 'asegurados_':
	    					$agent_uid   = substr($row_array["f_lider"], 0,-3);
		    				$data_row['agent_id']   = $this->get_agent_id($agent_uid);
	    					break;
	    				*/
	    				case 'bona_sa':
	    					$agent_id = $this->get_agent_id(substr($row_array["lider"], 0,-3));
		    				if($agent_id == null)
	    					{
		    					$agent_id 				= $this->get_agent_by_last_name($row_array['nlider']);
	    					}
	    					break;
	    				default:
	    					if(in_array($table_name, $sheets_inter))
				    		{
				    			if ($table_name == 'bona_vi')
				    			{
				    				$agent_id = $this->get_agent_id($row_array["folio"]);
				    			}
				    			else
				    			{

				    				$agent_id = $this->get_agent_id($row_array["inter"]);
				    			}
				    			if($agent_id == null)
				    			{
				    				$agent_id  = $this->get_agent_by_last_name($row_array['nombre']);
				    			}
				    		}
				    		if(in_array($table_name, $sheets_interl))
				    		{
				    			$agent_id = $this->get_agent_id($row_array["interl"]);
				    			if($agent_id == null)
		    					{
			    					$agent_id = $this->get_agent_by_last_name($row_array['inter']);
		    					}
				    		}
	    					break;
	    			}
	    			$this->db->where('id',$row_array['id']);
	    			$this->db->update($table_name, array('agent_id' => $agent_id));
	    		}
			}
    	}
    }
    public function get_incomplete_data()
    {
    	$names	= array('production_','business_','conservation_','points_','siniestralidad_','asegurados_','bona_vi','production_gmm_','asegurados_','bona_sa');
    	$sheets_interl = array('business_','conservation_');
		$sheets_inter  = array('points_','bona_vi');
		$array_data = array();
		foreach ($names as $table_name) 
		{
			$sql   = "SELECT * FROM ".$table_name." WHERE agent_id IS null";
	    	$query = $this->db->query($sql);
	    	$resul = $query->result_array();
	    	if($query->num_rows() > 0)
	    	{
	    		foreach ($resul as $row_array) 
	    		{
	    			$data = array('pestaña' => '', 'agente' => '', 'extras' => '');
	    			switch ($table_name) 
	    			{
	    				case 'production_':
	    					$data['pestaña'] = 'produccion_';
	    					$data['agente']  = $row_array['inter'];
	    					$data['extras']  = array('poliza' => $row_array['poliza'], 'apri' => $row_array['apri'], 'impri' => $row_array['impri']);
	    					break;
	    				case 'production_gmm_': 
	    					$data['pestaña'] = 'produccion_sa_';
	    					$data['agente']  = $row_array['clave'];
	    					$data['extras']  = array('poliza' => $row_array['poliza_'], 'st_nueren' => $row_array['st_nueren'], 'pmaneta' => $row_array['pmaneta']);
	    					break;
	    				
	    				case 'siniestralidad_':
	    					$data['pestaña'] = 'siniestralidad_';
	    					$data['agente']  = $row_array['agente'];
	    					$data['extras']  = array('poliza' => $row_array['poliza'], 'asegurado' => $row_array['asegurado'], 'total' => $row_array['total']);
	    					break;
	    				case 'asegurados_':
	    					$data['pestaña'] = 'asegurados_';
	    					$data['agente']  = $row_array['f_lider'];
	    					$data['extras']  = array('poliza' => $row_array['poliza'], 'asegurado' => $row_array['asegurado'], 'numcert' => $row_array['numcert']);
	    					break;
	    				
	    				case 'bona_sa':
	    					$data['pestaña'] = 'bona_sa';
	    					$data['agente']  = $row_array['nlider'];
	    					$data['extras']  = array('clave' => $row_array['lider']);
	    					break;
	    				default:
	    					if(in_array($table_name, $sheets_inter))
				    		{
				    			if ($table_name == 'bona_vi')
				    			{
				    				$data['pestaña'] = 'bona_vi';
	    							$data['agente']  = $row_array['nombre'];
	    							$data['extras']  = array('clave' => $row_array['folio']);
				    			}
				    			else
				    			{
				    				$data['pestaña'] = 'puntos_';
	    							$data['agente']  = $row_array['nombre'];
	    							$data['extras']  = array('ptos_total' => $row_array['ptos_tot']);

				    			}
				    		}
				    		if(in_array($table_name, $sheets_interl))
				    		{
				    			if ($table_name == 'business_')
				    			{
				    				$data['pestaña'] = 'negocios_';
	    							$data['agente']  = $row_array['inter'];
	    							$data['extras']  = array('clave' => $row_array['interl'], 'negocios' => $row_array['neg_camp_mas_pai']);
				    			}
				    			else
				    			{
				    				$data['pestaña'] = 'conservacion_';
	    							$data['agente']  = $row_array['nombre'];
	    							$data['extras']  = array('clave' => $row_array['interl'], 'conservacion_real' => $row_array['cons_real1'], 'conservacion_acotada' => $row_array['cons_acot1']);

				    			}
				    		}
	    					break;
	    			}
	    			array_push($array_data, $data);
	    		}
			}
    	}
    	return $array_data;
    }
   	public function get_agent_by_last_name($last_name)
    {
    	$sql 	= "SELECT agents.id AS id FROM users JOIN agents ON users.id = agents.user_id WHERE CONCAT(users.lastnames,' ',users.name) = '".$last_name."';";
    	$query 	= $this->db->query($sql);
    	$resul 	= $query->result_array();

    	if($query->num_rows() > 0)
    	{
    		if($resul[0]['id'] != null)
	    	{
	    		return $resul[0]['id'];
	    	}
	    	else
	    	{
	    		return null;
	    	}
    	}
    	return null;
    	
    }
    public function get_agent_id(&$uid)
    {
    	$array_agent = array();
    	$this->db->select('distinct(agents.id) as agent_id');
    	$this->db->from('agents');
    	$this->db->join('agent_uids','agent_uids.agent_id = agents.id');
    	$this->db->where('agent_uids.uid like "%'. $uid .'" or agent_uids.uid like "'. $uid .'%"');
		$query = $this->db->get();
		

    	if($query->num_rows() > 0)
    	{
    		foreach ($query->result() as $row) 
    		{
    			$array_agent = $row->agent_id;
    		}
    	}
    	else
    	{
    		$array_agent = null;
    	}
    	$query->free_result();

    	return $array_agent;
    }
	

    /*******  FUNCIONES PARA CREAR AUTOCOMPLETE  *******/
	public function autocomplete_js_vida($agent_array, $form_selector = '#sales-activity-form', $submit_selector = '.submit-form',$clear_selector = '#clear-agent-filter', $agent_selector = '#agent-name', $all_agents_selector = '#show-agent-list')
	{
		$agent_multi = array();
		foreach ( $agent_array as $key => $value )
		{
			$agent_multi[] = "\n'$value [ID: $key]'";
		}
		$inline_js = 
		'
		var agentList = [' . implode(',', $agent_multi) . '];
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
			$( "' . $all_agents_selector . '").bind("click", function( event ) {
				$(this).autocomplete(\'search\', $(this).val());
			})
			$( "' . $clear_selector . '").bind("click", function( event ) {
				$( "' . $agent_selector . '" ).val("");
				$( "' . $form_selector . '").submit();
			})
			$( "' . $agent_selector . '").focus("click", function( ) {
				$.ui.autocomplete("search", "" );
			})
			$( "' . $agent_selector . '" )
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
				
				.focus(function() {
					$(this).autocomplete(\'search\', $(this).val())
				})
		});
		</script>
		';
		return ($inline_js);
	}
	
	/*******  FUNCIONES PARA EXPORTAR A PDF  *******/
	public function get_logo_app()
	{
		$logo = '';
		$this->db->select('value');
		$this->db->from('settings');
		$this->db->where('key','logo');
		$query = $this->db->get();
		if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) 
            {
                $logo = $row->value;
            }
        }
        $query->free_result();

        return $logo;
	}
	public function export_pdf_vida($year, $agent_id, $post = null)
	{
		$header = '';
		$array_values = array();
		$base_url = base_url();
		$logo = 'images/'.$this->get_logo_app();
		$str_simulation = "";
		//Get data
		if($post != null)
		{
			$array_values = (array) json_decode($post);
			$header = '<p>Simulación del año '.$year.' de ' .$array_values['name'].' ('.$array_values['generation'].') <img src="'.$logo.'" align="right" width="1000"/></p>';
			$str_simulation = '
			<tr>
                <td class="text-left">Cartera Estimada</td>
                <td class="text-info" >$ '.$array_values['cartera_estimada'][0].' </td>
                <td class="text-info" >$ '.$array_values['cartera_estimada'][1].' </td>
                <td class="text-info" >$ '.$array_values['cartera_estimada'][2].' </td>
                <td class="text-info" >$ '.$array_values['cartera_estimada'][3].' </td>
                <td colspan="2"></td>
            </tr>';
		}
		else
		{
			$array_values = $this->get_vida_agent_details($year,$agent_id);
			$header = '<p>Resumen del año '.$year.' de ' .$array_values['name'].' ('.$array_values['generation'].') <img src="'.$logo.'" align="right" width="1000"/></p>';
		}
		
		


		//Set html script
		$html_content = '
			<link rel="stylesheet" href="'.$base_url.'ot/assets/style/main.css">
			<link rel="stylesheet" href="'.$base_url.'agent/assets/style/agent.css">
			<link rel="stylesheet" href="'.$base_url.'ot/assets/style/jquery.fancybox.css">
			<link rel="stylesheet" href="'.$base_url.'style/style-report.css?'.time().'">
			<link rel="stylesheet" href="'.$base_url.'style/print-reset.css?'.time().'">
			<link rel="stylesheet" href="'.$base_url.'style/charisma-app.css">
			<link rel="stylesheet" href="'.$base_url.'bootstrap/css/bootstrap.css">
			<link rel="stylesheet" href="'.$base_url.'ot/assets/style/theme.default.css">
			<link rel="stylesheet" href="'.$base_url.'bootstrap/css/bootstrap-responsive.css">
   			<link rel="stylesheet" href="'.$base_url.'bootstrap/FortAwesome/css/font-awesome.css">
   			<style type="text/css">
				td { text-align: right !important; }
				.text-left { text-align: left !important; }
				.text-center { text-align: center !important; }
			</style>
			<div class="row-fluid span6">
				'.$header.'
			</div>
			<div class="row-fluid">
				
		        <table class="table table-hover table-bordered">
		        	<thead class="thead-dark">
						<tr>
							<td class="text-center">
								<h5 style="color: black; font-family: sans-serif;" align="center">Club Élite</h5>
				                <p align="center" >'.$array_values['club_elite'].'</p>
				                <p><h5 style="color: black; font-family: sans-serif;" align="center">Faltante Negocios PAI</h5></p>
				                <p align="center">'.$array_values['faltante_elite_neg'].'</p>
				                <p><h5 style="color: black; font-family: sans-serif;" align="center">Faltante Producción</h5></p>
				                <p align="center">$ '.$array_values['faltante_elite_prod'].'</p>
							</td>
							<td class="text-center">
								<h5 style="color: black; font-family: sans-serif;"><p align="center">Congreso</p></h5>
				                <p align="center">'.$array_values['congreso'].'</p>
				                <p><h5 style="color: black; font-family: sans-serif;" align="center">Proximo Congreso</h5></p>
				                <p align="center">'.$array_values['congreso_siguiente'].'</p>
				                <p><h5 style="color: black; font-family: sans-serif;" align="center">Faltante Negocios</h5></p>
				                <p align="center">'.$array_values['faltante_congreso_neg'].'</p>
				                <p><h5 style="color: black; font-family: sans-serif;" align="center">Faltante Producción</h5></p>
				                <p align="center">$ '.$array_values['faltante_congreso_prod'].'</p>
							</td>
							<td class="text-center">
								<h5 style="color: black; font-family: sans-serif;"><p align="center">Puntos Standing</p></h5>
				                <p align="center">'.$array_values['puntos_standing'].'</p>
				                <p><h5 style="color: black; font-family: sans-serif;" align="center">Faltante Negocios</h5></p>
				                <p align="center">'.$array_values['faltante_ptos_standing_neg'].'</p>
				                <p><h5 style="color: black; font-family: sans-serif;" align="center">Faltante Producción</h5></p>
				                <p align="center">$ '.$array_values['faltante_ptos_standing_pro'].'</p>
							</td>
						</tr>
		            </thead>
		        </table>
				<table class="table table-hover table-bordered">
					<thead class="thead-dark">
						<tr>
							<th> </th>
							<th>1er Trimestre</th>
							<th>2o Trimestre</th>
							<th>3er Trimestre</th>
							<th>4o Trimestre</th>
							<th class="celda_amarilla">Acumulado Anual</th>
							<th class="celda_verde">Promedio Mensual </th>
						</tr>
		            </thead>
		            <tbody>
		            	'.$str_simulation.'
		            	<tr>
			            	<td class="text-left">Cartera Pronosticada</td>
			                <td class="text-info">$ '.$array_values['cartera_pronosticada'][0].' </td>
			                <td class="text-info">$ '.$array_values['cartera_pronosticada'][1].' </td>
			                <td class="text-info">$ '.$array_values['cartera_pronosticada'][2].' </td>
			                <td class="text-info">$ '.$array_values['cartera_pronosticada'][3].' </td>
			                <td colspan="2"></td>
			            </tr>
	              		<tr>
			            	<td class="text-left">Cartera Real</td>
			                <td class="text-info">$ '.$array_values['cartera_real'][0].' </td>
			                <td class="text-info">$ '.$array_values['cartera_real'][1].' </td>
			                <td class="text-info">$ '.$array_values['cartera_real'][2].' </td>
			                <td class="text-info">$ '.$array_values['cartera_real'][3].' </td>
			                <td class="celda_amarilla text-info">$ '.$array_values['cartera_real'][4].'</td>
			                <td class="celda_verde text-info">$ '.$array_values['cartera_real'][5].'</td>
			            </tr>
			            <tr>
		                	<td colspan="8"></td>
		              	</tr>
			            <tr>
			                <td class="text-left">Prima P/ Ubicar</td>
			                <td class="text-info">$ '.$array_values['prima_ubicar'][0].'</td>
			                <td class="text-info">$ '.$array_values['prima_ubicar'][1].'</td>
			                <td class="text-info">$ '.$array_values['prima_ubicar'][2].'</td>
			                <td class="text-info">$ '.$array_values['prima_ubicar'][3].'</td>
			                <td class="celda_amarilla text-info">$ '.$array_values['prima_ubicar'][4].'</td>
			                <td class="celda_verde text-info">$ '.$array_values['prima_ubicar'][5].'</td>
	            		</tr>
	            		<tr>
			                <td class="text-left">Prima P/ Pago de Bono</td>
			                <td class="text-info">$ '.$array_values['prima_pago'][0].' </td>
			                <td class="text-info">$ '.$array_values['prima_pago'][1].' </td>
			                <td class="text-info">$ '.$array_values['prima_pago'][2].' </td>
			                <td class="text-info">$ '.$array_values['prima_pago'][3].' </td>
			                <td class="celda_amarilla text-info">$ '.$array_values['prima_pago'][4].'</td>
			                <td class="celda_verde text-info">$ '.$array_values['prima_pago'][5].'</td>
	            		</tr>
	            		<tr>
			                <td class="text-left">Numero de Negocios</td>
			                <td class="text-info">'.$array_values['numero_negocios'][0].' </td>
			                <td class="text-info">'.$array_values['numero_negocios'][1].' </td>
			                <td class="text-info">'.$array_values['numero_negocios'][2].' </td>
			                <td class="text-info">'.$array_values['numero_negocios'][3].' </td>
			                <td class="celda_amarilla text-info">'.$array_values['numero_negocios'][4].'</td>
			                <td class="celda_verde text-info">'.$array_values['numero_negocios'][5].'</td>
			            </tr>
			            <tr>
			            	<td colspan="8"></td>
			            </tr>
	              		<tr>
		                	<td class="text-left">Comisiones Directas</td>
			                <td class="text-info">$ '.$array_values['comisiones_directas'][0].'</td>
			                <td class="text-info">$ '.$array_values['comisiones_directas'][1].'</td>
			                <td class="text-info">$ '.$array_values['comisiones_directas'][2].' </td>
			                <td class="text-info">$ '.$array_values['comisiones_directas'][3].'</td>
			                <td class="celda_amarilla text-info">$ '.$array_values['comisiones_directas'][4].'</td>
			                <td class="celda_verde text-info">$ '.$array_values['comisiones_directas'][5].'</td>
		              	</tr>
		              	<tr>
			              	<td class="text-left">% Bono Primer Año</td>
			                <td class="text-info" class="text-info">'.$array_values['perce_bono_primer_anio'][0].' %</td>
			                <td class="text-info" class="text-info">'.$array_values['perce_bono_primer_anio'][1].' %</td>
			                <td class="text-info" class="text-info">'.$array_values['perce_bono_primer_anio'][2].' %</td>
			                <td class="text-info" class="text-info">'.$array_values['perce_bono_primer_anio'][3].' %</td>
			                <td colspan="2"></td>
		              	</tr>
		              	<tr>
			                <td class="text-left">Bono Primer Año</td>
			                <td class="text-info">$ '.$array_values['bono_primer_anio'][0].'</td>
			                <td class="text-info">$ '.$array_values['bono_primer_anio'][1].'</td>
			                <td class="text-info">$ '.$array_values['bono_primer_anio'][2].'</td>
			                <td class="text-info">$ '.$array_values['bono_primer_anio'][3].'</td>
			                <td class="celda_amarilla text-info">$ '.$array_values['bono_primer_anio'][4].'</td>
			                <td class="celda_verde text-info">$ '.$array_values['bono_primer_anio'][5].'</td>
		              	</tr>
		              	<tr>
			                <td class="text-left">Faltante Bono Primer Año</td>
			                <td class="text-info">$ '.$array_values['faltate_bono_cartera'][0].'</td>
			                <td class="text-info">$ '.$array_values['faltate_bono_cartera'][1].'</td>
			                <td class="text-info">$ '.$array_values['faltate_bono_cartera'][2].'</td>
			                <td class="text-info">$ '.$array_values['faltate_bono_cartera'][3].'</td>
			                <td colspan="2"> </td>
		              	</tr>
		              	<tr>
		                	<td colspan="7"></td>
		              	</tr>
		              	<tr>
			                <td class="text-left">Conservacion</td>
			                <td class="text-info">'.$array_values['conservacion'][0].'%</td>
			                <td class="text-info">'.$array_values['conservacion'][1].'%</td>
			                <td class="text-info">'.$array_values['conservacion'][2].'%</td>
			                <td class="text-info">'.$array_values['conservacion'][3].'%</td>
			                <td colspan="2"> </td>
		              	</tr>
		              	<tr>
			              	<td class="text-left">Comision Cartera</td>
			                <td class="text-info">$ '.$array_values['comision_cartera'][0].'</td>
			                <td class="text-info">$ '.$array_values['comision_cartera'][1].'</td>
			                <td class="text-info">$ '.$array_values['comision_cartera'][2].'</td>
			                <td class="text-info">$ '.$array_values['comision_cartera'][3].'</td>
			                <td class="celda_amarilla text-info">$ '.$array_values['comision_cartera'][4].'</td>
			                <td class="celda_verde text-info">$ '.$array_values['comision_cartera'][5].'</td>
		              	</tr>
		              	<tr>
			                <td class="text-left">% Bono Cartera</td>
			                <td class="text-info">'.$array_values['perce_bono_cartera'][0].' %</td>
			                <td class="text-info">'.$array_values['perce_bono_cartera'][1].' %</td>
			                <td class="text-info">'.$array_values['perce_bono_cartera'][2].' %</td>
			                <td class="text-info">'.$array_values['perce_bono_cartera'][3].' %</td>
			                <td colspan="2"></td>
		              	</tr>
		              	<tr>
			                <td class="text-left">Bono Cartera</td>
			                <td class="text-info">$ '.$array_values['bono_cartera'][0].'</td>
			                <td class="text-info">$ '.$array_values['bono_cartera'][1].'</td>
			                <td class="text-info">$ '.$array_values['bono_cartera'][2].'</td>
			                <td class="text-info">$ '.$array_values['bono_cartera'][3].'</td>
			                <td class="celda_amarilla text-info">$ '.$array_values['bono_cartera'][4].'</td>
			                <td class="celda_verde text-info">$ '.$array_values['bono_cartera'][5].'</td>
		              	</tr>
		              	<tr>
			                <td class="text-left">Faltante Bono Cartera</td>
			                <td class="text-info">$ '.$array_values['faltate_bono_cartera'][0].' </td>
			                <td class="text-info">$ '.$array_values['faltate_bono_cartera'][1].' </td>
			                <td class="text-info">$ '.$array_values['faltate_bono_cartera'][2].' </td>
			                <td class="text-info">$ '.$array_values['faltate_bono_cartera'][3].' </td>
			                <td colspan="2"></td>
		             	</tr>
		              	<tr>
		                	<td colspan="8"></td>
		              	</tr>
		              	<tr class="error">
			                <td class="text-left">Bono Cartera No Ganado</td>
			                <td class="text-info">$ '.$array_values['bono_cartera_no_ganado'][0].' </td>
			                <td class="text-info">$ '.$array_values['bono_cartera_no_ganado'][1].' </td>
			                <td class="text-info">$ '.$array_values['bono_cartera_no_ganado'][2].' </td>
			                <td class="text-info">$ '.$array_values['bono_cartera_no_ganado'][3].' </td>
			                <td class="celda_amarilla text-info">$ '.$array_values['bono_cartera_no_ganado'][4].' </td>
			                <td class="celda_verde text-info">$ '.$array_values['bono_cartera_no_ganado'][5].' </td>
		              	</tr>
		              	<tr>
		                	<td colspan="8"></td>
		              	</tr>
		              	<tr class="info">
			                <td class="text-left">Suma de Ingresos Totales</td>
			                <td class="text-info">$ '.$array_values['suma_ingresos_totales'][0].'</td>
			                <td class="text-info">$ '.$array_values['suma_ingresos_totales'][1].'</td>
			                <td class="text-info">$ '.$array_values['suma_ingresos_totales'][2].'</td>
			                <td class="text-info">$ '.$array_values['suma_ingresos_totales'][3].'</td>
			                <td class="celda_amarilla text-info">$ '.$array_values['suma_ingresos_totales'][4].'</td>
			                <td class="celda_verde text-info">$ '.$array_values['suma_ingresos_totales'][5].'</td>
		              	</tr>
		              	<tr>
		                	<td colspan="7"></td>
		              	</tr>
		              	<tr>
			                <td class="text-left">Puntos</td>
			                <td class="text-left" colspan="4">Periodos</td>
			                <td class="text-left">Total</td>
			                <td colspan="2"></td>
		              	</tr>
		              	<tr>
			                <td class="text-left">Puntos Vida</td>
			                <td class="text-info" id="cell_puntos_vida_1"> '.$array_values['puntos_vida'][0].' </td>
			                <td class="text-info" id="cell_puntos_vida_2"> '.$array_values['puntos_vida'][1].' </td>
			                <td class="text-info" id="cell_puntos_vida_3"> '.$array_values['puntos_vida'][2].' </td>
			                <td class="text-info celda_amarilla" id="cell_puntos_vida_4"> '.$array_values['puntos_vida'][3].' </td>
			                <td class="text-info" id="cell_puntos_vida_5"> '.$array_values['puntos_vida'][4].' </td>
			                <td colspan="2"></td>
		              	</tr>
		              	<tr>
			                <td class="text-left">Puntos GMM</td>
			                <td class="text-info" id="cell_puntos_vida_1"> '.$array_values['puntos_gmm'][0].'</td>
			                <td class="text-info" id="cell_puntos_vida_2"> '.$array_values['puntos_gmm'][1].'</td>
			                <td class="text-info celda_amarilla" id="cell_puntos_vida_3" colspan="2"> '.$array_values['puntos_gmm'][2].'</td>
			                <td class="text-info" id="cell_puntos_vida_4"> '.$array_values['puntos_gmm'][4].' </td>
			                <td colspan="2"></td>
		              	</tr>
		              	<tr>
			                <td class="text-left">Puntos Autos</td>
			                <td class="text-info" id="cell_puntos_vida_1"> '.$array_values['puntos_autos'][0].'</td>
			                <td class="text-info" id="cell_puntos_vida_2"> '.$array_values['puntos_autos'][1].'</td>
			                <td id="cell_puntos_vida_3" class="celda_amarilla text-info" colspan="2"> '.$array_values['puntos_autos'][2].'</td>
			                <td class="text-info" id="cell_puntos_vida_4" > '.$array_values['puntos_autos'][4].' </td>
			                <td colspan="2"></td>
		              	</tr>
		              	<tr>
		                	<td colspan="7"></td>
		              	</tr>
		              	<tr>
		                	<td class="text-left">Bono Integral</td>
		                	<td class="text-info text-left" id="cell_bono_integral" colspan="6">$ '.$array_values['bono_integral'].' </td>
		              	</tr>
		              	<tr>
		                	<td colspan="7"></td>
		              	</tr>
		              	<tr>
		                	<td class="text-left">Total de ingresos</td>
		                	<td class="text-info text-left" id="cell_ingresos_totales" colspan="6"> $ '.$array_values['ingresos_totales'].' </td>
		              	</tr>
	              	</tbody>
				</table>
				
			</div>';
		$this->pdf->loadHtml($html_content);
		$this->pdf->setPaper('A3', 'portrait');
   		$this->pdf->render();
   		$this->pdf->stream("resumen_anual_".$year.".pdf", array("Attachment"=>0));
	}
	public function export_pdf_gmm($year, $agent_id, $post = null)
	{
		//Get data
		$header = '';
		$array_values = array();
		$base_url = base_url();
		$logo = 'images/'.$this->get_logo_app();

		//Get data
		if($post != null)
		{
			$array_values = (array) json_decode($post);
			$header = '<p>Simulación del año '.$year.' de ' .$array_values['name'].' ('.$array_values['generation'].') <img src="'.$logo.'" align="right" width="1000"/></p>';
		}
		else
		{
			$array_values = $this->get_gmm_agent_details($year,$agent_id);
			$header = '<p>Resumen del año '.$year.' de ' .$array_values['name'].' ('.$array_values['generation'].') <img src="'.$logo.'" align="right" width="1000"/></p>';
		}		

		//Set html script
		$html_content = '
			<link rel="stylesheet" href="'.$base_url.'ot/assets/style/main.css">
			<link rel="stylesheet" href="'.$base_url.'agent/assets/style/agent.css">
			<link rel="stylesheet" href="'.$base_url.'ot/assets/style/jquery.fancybox.css">
			<link rel="stylesheet" href="'.$base_url.'style/style-report.css?'.time().'">
			<link rel="stylesheet" href="'.$base_url.'style/print-reset.css?'.time().'">
			<link rel="stylesheet" href="'.$base_url.'style/charisma-app.css">
			<link rel="stylesheet" href="'.$base_url.'bootstrap/css/bootstrap.css">
			<link rel="stylesheet" href="'.$base_url.'ot/assets/style/theme.default.css">
			<link rel="stylesheet" href="'.$base_url.'bootstrap/css/bootstrap-responsive.css">
   			<link rel="stylesheet" href="'.$base_url.'bootstrap/FortAwesome/css/font-awesome.css">
   			<style type="text/css">
				td { text-align: right !important; }
				.text-left { text-align: left !important; }
				.text-center { text-align: center !important; }
			</style>
			<div class="row-fluid span6">
				'.$header.'
			</div>
			<div class="row-fluid span6">
				
			</div>
			<div class="row-fluid">
				<table class="table table-hover table-bordered">
		        	<thead class="thead-dark">
						<tr>
							<td class="text-center">
								<p><h5 style="color: black; font-family: sans-serif;" align="center">Congreso</h5></p>
				                <p align="center">'.$array_values['congreso'].'</p>
				                <p><h5 style="color: black; font-family: sans-serif;" align="center">Proximo Congreso</h5></p>
				                <p align="center" >'.$array_values['congreso_siguiente'].'</p>
				                <p><h5 style="color: black; font-family: sans-serif;" align="center">Faltante de producción inicial</h5></p>
				                <p align="center">'.$array_values['faltante_produccion_inicial'].'</p>
							</td>
							<td class="text-center">
								<h5 style="color: black; font-family: sans-serif;"><p align="center">Productivo</p></h5>
				                <p align="center">'.$array_values['agente_productivo'][0].'</p>
				                <p><h5 style="color: black; font-family: sans-serif;" align="center">Faltante para ser Agente Productivo</h5></p>
				                <p align="center">'.$array_values['agente_productivo'][1].'</p>

							</td>
						</tr>
		            </thead>
		        </table>
				<table class="table table-hover table-bordered">
					<thead class="thead-dark">
						<tr>
							<th></th>
							<th>1er Cuatrimestre</th>
							<th>2o Cuatrimestre</th>
							<th>3er Cuatrimestre</th>
							<th class="celda_amarilla">Acumulado Anual</th>
							<th class="celda_verde">Promedio Mesual </th>
						</tr>
			      </thead>
			      <tbody>
			      	<tr>
			      		<td class="text-left">Cartera Estimada</td>
			      		<td class="text-info">$ '.$array_values['cartera_estimada'][0].'  </td>
			      		<td class="text-info">$ '.$array_values['cartera_estimada'][1].'  </td>
			      		<td class="text-info">$ '.$array_values['cartera_estimada'][2].'  </td>
			      		<td colspan="2"> </td>
			      	</tr>
			      	<tr>
			      		<td class="text-left">Prima P/Ubicar</td>
			      		<td class="text-info">$ '.$array_values['primas_ubicar'][0].'  </td>
			      		<td class="text-info">$ '.$array_values['primas_ubicar'][1].'  </td>
			      		<td class="text-info">$ '.$array_values['primas_ubicar'][2].'  </td>
			      		<td class="celda_amarilla text-info">$ '.$array_values['primas_ubicar'][3].' </td>
			      		<td class="celda_verde text-info">$ '.$array_values['primas_ubicar'][4].' </td>
			      	</tr>
			      	<tr>
			      		<td class="text-left">Prima P/Pagar</td>
			      		<td class="text-info">$ '.$array_values['primas_pagar'][0].'  </td>
			      		<td class="text-info">$ '.$array_values['primas_pagar'][1].'  </td>
			      		<td class="text-info">$ '.$array_values['primas_pagar'][2].'  </td>
			      		<td class="celda_amarilla text-info">$ '.$array_values['primas_pagar'][3].' </td>
			      		<td class="celda_verde text-info">$ '.$array_values['primas_pagar'][4].' </td>
			      	</tr>
			      	<tr>
			      		<td class="text-left">Nuevos Asegurados</td>
			      		<td class="text-info"> '.$array_values['nuevos_asegurados'][0].'  </td>
			      		<td class="text-info"> '.$array_values['nuevos_asegurados'][1].'  </td>
			      		<td class="text-info"> '.$array_values['nuevos_asegurados'][2].'  </td>
			      		<td class="celda_amarilla text-info">  '.$array_values['nuevos_asegurados'][3].' </td>
			      		<td class="celda_verde text-info"> '.$array_values['nuevos_asegurados'][4].' </td>
			      	</tr>
			      	<tr>
			      		<td colspan="6"></td>
			      	</tr>
			      	<tr>
			      		<td class="text-left">Comisiones Directas</td>
			      		<td class="text-info">$ '.$array_values['comisiones_directas'][0].'  </td>
			      		<td class="text-info">$ '.$array_values['comisiones_directas'][1].'  </td>
			      		<td class="text-info">$ '.$array_values['comisiones_directas'][2].'  </td>
			      		<td class="celda_amarilla text-info">$ '.$array_values['comisiones_directas'][3].' </td>
			      		<td class="celda_verde text-info">$ '.$array_values['comisiones_directas'][4].' </td>
			      	</tr>
			      	<tr>
			      		<td class="text-left">% de bono de 1er año</td>
			      		<td class="text-info">'.$array_values['perce_bono_primer_anio'][0].' %</td>
			      		<td class="text-info">'.$array_values['perce_bono_primer_anio'][1].' %</td>
			      		<td class="text-info">'.$array_values['perce_bono_primer_anio'][2].' %</td>
			      		<td colspan="2"></td>
			      	</tr>
			      	<tr>
			      		<td class="text-left">Bono de 1er año</td>
			      		<td class="text-info">$ '.$array_values['bono_primer_anio'][0].' </td>
			      		<td class="text-info">$ '.$array_values['bono_primer_anio'][1].' </td>
			      		<td class="text-info">$ '.$array_values['bono_primer_anio'][2].' </td>
			      		<td class="celda_amarilla text-info">$ '.$array_values['bono_primer_anio'][3].'</td>
			      		<td class="celda_verde text-info">$ '.$array_values['bono_primer_anio'][4].'</td>
			      	</tr>
			      	<tr>
			      		<td class="text-left">Faltante para siguiente renglon de Bono</td>
			      		<td class="text-info">$ '.$array_values['faltante_bono_primer_anio'][0].' </td>
			      		<td class="text-info">$ '.$array_values['faltante_bono_primer_anio'][1].' </td>
			      		<td class="text-info">$ '.$array_values['faltante_bono_primer_anio'][2].' </td>
			      		<td colspan="2"></td>
			      	</tr>
			      	<tr>
			      		<td colspan="6"></td>
			      	</tr>
			      	<tr>
			      		<td class="text-left">Primas netas pagadas en los utimos 12 meses</td>
			      		<td class="text-info">$ '.$array_values['primas_netas'][0].' </td>
			      		<td class="text-info">$ '.$array_values['primas_netas'][1].' </td>
			      		<td class="text-info">$ '.$array_values['primas_netas'][2].' </td>
			      		<td class="celda_amarilla text-info">$ '.$array_values['primas_netas'][3].'</td>
			      		<td class="celda_verde text-info">$ '.$array_values['primas_netas'][4].'</td>
			      	</tr>
			      	<tr>
			      		<td class="text-left">Siniestros Pagados-Real</td>
			      		<td class="text-info">$ '.$array_values['siniestros_pagados_real'][0].' </td>
			      		<td class="text-info">$ '.$array_values['siniestros_pagados_real'][1].' </td>
			      		<td class="text-info">$ '.$array_values['siniestros_pagados_real'][2].' </td>
			      		<td class="celda_amarilla text-info">$ '.$array_values['siniestros_pagados_real'][3].'</td> 
			      		<td class="celda_verde text-info">$ '.$array_values['siniestros_pagados_real'][4].'</td>     		
			      	</tr>
			      	<tr>
			      		<td class="text-left">Siniestros Pagados-Acotados</td>
			      		<td class="text-info">$ '.$array_values['siniestros_pagados_acot'][0].' </td>
			      		<td class="text-info">$ '.$array_values['siniestros_pagados_acot'][1].' </td>
			      		<td class="text-info">$ '.$array_values['siniestros_pagados_acot'][2].' </td>
			      		<td class="celda_amarilla text-info">$ '.$array_values['siniestros_pagados_acot'][3].'</td>
			      		<td class="celda_verde text-info">$ '.$array_values['siniestros_pagados_acot'][4].'</td>	
			      	</tr>
			      	<tr>
			      		<td class="text-left">Resultados de siniestralidad real</td>
			      		<td class="text-info">'.$array_values['result_siniestralidad_real'][0].' %</td>
			      		<td class="text-info">'.$array_values['result_siniestralidad_real'][1].' %</td>
			      		<td class="text-info">'.$array_values['result_siniestralidad_real'][2].' %</td>
			      		<td colspan="2"></td>
			      	</tr>
			      	<tr>
			      		<td class="text-left">Resultados de siniestralidad acotado</td>
			      		<td class="text-info">'.$array_values['result_siniestralidad_acot'][0].' %</td>
			      		<td class="text-info">'.$array_values['result_siniestralidad_acot'][1].' %</td>
			      		<td class="text-info">'.$array_values['result_siniestralidad_acot'][2].' %</td>
			      		<td colspan="2"></td>
			      	</tr>	
			      	<tr>
			      		<td colspan="6"></td>
			      	</tr>
			      	<tr>
			      		<td class="text-left">Cartera Conservada</td>
			      		<td class="text-info">$ '.$array_values['cartera_conservada'][0].' </td>
			      		<td class="text-info">$ '.$array_values['cartera_conservada'][1].' </td>
			      		<td class="text-info">$ '.$array_values['cartera_conservada'][2].' </td>
			      		<td class="celda_amarilla text-info">$ '.$array_values['cartera_conservada'][3].'</td>
			      		<td class="celda_verde text-info">$ '.$array_values['cartera_conservada'][4].'</td>
			      	</tr>
			      	<tr>
			      		<td class="text-left">Comision Cartera</td>
			      		<td class="text-info">$ '.$array_values['comision_cartera'][0].' </td>
			      		<td class="text-info">$ '.$array_values['comision_cartera'][1].' </td>
			      		<td class="text-info">$ '.$array_values['comision_cartera'][2].' </td>
						<td class="celda_amarilla text-info">$ '.$array_values['comision_cartera'][3].'</td>
			      		<td class="celda_verde text-info">$ '.$array_values['comision_cartera'][4].'</td>
			      	</tr>
			      	<tr>
			      		<td class="text-left">Numero de Asegurados</td>
			      		<td class="text-info"> '.$array_values['numero_asegurados'][0].' </td>
			      		<td class="text-info"> '.$array_values['numero_asegurados'][1].' </td>
			      		<td class="text-info"> '.$array_values['numero_asegurados'][2].' </td>
			      		<td class="celda_amarilla text-info"> '.$array_values['numero_asegurados'][3].'</td>
			      		<td class="celda_verde text-info"> '.$array_values['numero_asegurados'][4].'</td>
			      	</tr>
			      	<tr>
			      		<td class="text-left">% bono de rentabilidad</td>
			      		<td class="text-info">'.$array_values['perce_bono_rentabilidad'][0].' %</td>
			      		<td class="text-info">'.$array_values['perce_bono_rentabilidad'][1].' %</td>
			      		<td class="text-info">'.$array_values['perce_bono_rentabilidad'][2].' %</td>
			      		<td colspan="2"></td>
			      	</tr>
			      	<tr>
			      		<td class="text-left">Bono de rentabilidad</td>
			      		<td class="text-info" class="text-info">$ '.$array_values['bono_rentabilidad'][0].' </td>
			      		<td class="text-info" class="text-info">$ '.$array_values['bono_rentabilidad'][1].' </td>
			      		<td class="text-info" class="text-info">$ '.$array_values['bono_rentabilidad'][2].' </td>
			      		<td class="celda_amarilla text-info">$ '.$array_values['bono_rentabilidad'][3].'</td>
			      		<td class="celda_verde text-info">$ '.$array_values['bono_rentabilidad'][4].'</td>
			      	</tr>
			      	<tr>
			      		<td class="text-left">Faltante para el siguiente renglon de bono de rentabilidad</td>
			      		<td class="text-info">$ '.$array_values['faltante_bono_rentabilidad'][0].' </td>
			      		<td class="text-info">$ '.$array_values['faltante_bono_rentabilidad'][1].' </td>
			      		<td class="text-info">$ '.$array_values['faltante_bono_rentabilidad'][2].' </td>
			      		<td colspan="2"></td>
			      	</tr>
			      	<tr>
			      		<td colspan="6"></td>

			      	</tr>
			      	<tr class="error">
			      		<td class="text-left">Bono de rentabilidad no ganado</td>
			      		<td class="text-info">$ '.$array_values['bono_rentabilidad_no_ganado'][0].' </td>
			      		<td class="text-info">$ '.$array_values['bono_rentabilidad_no_ganado'][1].' </td>
			      		<td class="text-info">$ '.$array_values['bono_rentabilidad_no_ganado'][2].' </td>
			      		<td class="celda_amarilla text-info">$ '.$array_values['bono_rentabilidad_no_ganado'][3].'</td>
			      		<td class="celda_verde text-info">$ '.$array_values['bono_rentabilidad_no_ganado'][4].'</td>
			      	</tr>
			      	<tr>
			      		<td colspan="6"></td>
			      	</tr>
			      	<tr>
			      		<td class="text-left">Suma de ingresos</td>
			      		<td class="text-info">$ '.$array_values['suma_ingresos'][0].' </td>
			      		<td class="text-info">$ '.$array_values['suma_ingresos'][1].' </td>
			      		<td class="text-info">$ '.$array_values['suma_ingresos'][2].' </td>
			      		<td class="celda_amarilla text-info">$ '.$array_values['suma_ingresos'][3].'</td>
			      		<td class="celda_verde text-info">$ '.$array_values['suma_ingresos'][4].'</td>
			      	</tr>
			      	<tr>
			      		<td colspan="6"></td>
			      	</tr>
			      	<tr>
			      		<td class="text-left">Total de ingresos</td>
			      		<td class="text-info text-left" class="text-info" colspan="5">$ '.$array_values['ingresos_totales'].' </td>
			      	</tr>
			      </tbody>
				</table>
			</div>
		';
		$this->pdf->loadHtml($html_content);
		$this->pdf->setPaper('A3', 'portrait');
   		$this->pdf->render();
   		$this->pdf->stream("resumen_anual_".$year.".pdf", array("Attachment"=>0));
	}

	/*******  FUNCIONES PARA SUBIR LOS DATOS EN POPUP  *******/
	public function get_data_popup_grl($year,$index,$heads,$is_congress = false ,$is_prod = false, $is_recruit = false, $is_recruit_prod = false, $is_bonus = false)
	{
		$main_list    = $this->get_main_list($year);
		$array_values = array();
		$value_vid    = 0;
		$value_gmm    = 0;
		$total 		  = 0;
		foreach ($main_list as $row) 
		{
			if($is_prod)
			{
				if($row['generation'] != 'Consolidado')
				{
					$value_vid = $value_vid + $row[$index[0]];
					$value_gmm = $value_gmm + $row[$index[1]];
					if($row[$index[0]] + $row[$index[1]] > 0)
					{
						array_push($array_values, array($row['name'], $this->redirect_popup('detail_vida',"$ ".number_format($row[$index[0]],2), $row['agent_id']), $this->redirect_popup('detail_gmm',"$ ".number_format($row[$index[1]],2), $row['agent_id']), number_format($row[$index[0]] + $row[$index[1]],2) ));
					}
				}
			}
			if($is_congress)
			{
				if(($row['vi_congreso'] != 'N/A' && $row['vi_congreso'] != '')  || ($row['gmm_congreso'] != 'No' && $row['gmm_congreso'] != ''))
				{
					$total++;

					array_push($array_values, array($row['name'], $this->redirect_popup('detail_vida',$row['vi_congreso'], $row['agent_id']), $this->redirect_popup('detail_gmm',$row['gmm_congreso'], $row['agent_id'])));
				}
			}
			if($is_recruit)
			{
				if(strtotime($row['connection_date']) >= strtotime(''.$year.'-01-01') && strtotime($row['connection_date']) <= strtotime(''.$year.'-12-31'))
				{
					$total++;
					array_push($array_values, array($row['name'], $row['connection_date']));
				}
			}
			if($is_recruit_prod)
			{
				if($row['connection_date'] >= $year.'-01-01' && $row['connection_date'] <= $year.'-12-31')
				{
					if($row[$index[0]] + $row[$index[1]] != 0)
					{
						$total++;
						array_push($array_values, array($row['name'], number_format($row[$index[0]] + $row[$index[1]],2)));
					}
				}
			}
			if($is_bonus)
			{
				$points = 0;
				$bonus = '';
				if($row['gmm_primas_ubi'] >= 120000 && $row['gmm_primas_ubi'] < 200000)
				{
					$points = $points + 1;
				}
				if($row['gmm_primas_ubi'] >= 200000 && $row['gmm_primas_ubi'] < 290000)
				{
					$points = $points + 2;
				}
				if($row['gmm_primas_ubi'] >= 290000 && $row['gmm_primas_ubi'] < 400000)
				{
					$points = $points + 3;
				}
				if($row['gmm_primas_ubi'] >= 400000)
				{
					$points = $points + 4;
				}
				if($row['vi_primas_ubi'] >= 120000 && $row['vi_primas_ubi'] < 240000)
				{
					$points = $points + 1;
				}
				if($row['vi_primas_ubi'] >= 240000 && $row['vi_primas_ubi'] < 410000)
				{
					$points = $points + 2;
				}
				if($row['vi_primas_ubi'] >= 410000)
				{
					$points = $points + 3;
				}
				if($points > 6)
				{
					if($points >= 6 && $points < 11)
					{
						$bonus = '10.0 %';
					}
					if($points >= 11 && $points < 16)
					{
						$bonus = '12.5 %';
					}
					if($points >= 16 && $points < 21)
					{
						$bonus = '15.0 %';
					}
					if($points >= 21 && $points < 26)
					{
						$bonus = '17.5 %';
					}
					if($points >= 26 && $points < 31)
					{
						$bonus = '20.0 %';
					}
					if($points >= 31)
					{
						$bonus = '25.0 %';
					}
					$total++;
					array_push($array_values, array($row['name'], $bonus));
				}
			}
			if(!$is_prod && !$is_congress && !$is_recruit && !$is_recruit_prod && !$is_bonus)
			{
				$value_vid = $value_vid + $row[$index[0]];
				$value_gmm = $value_gmm + $row[$index[1]];
				array_push($array_values, array($row['name'], $this->redirect_popup('detail_vida',"$ ".number_format($row[$index[0]],2), $row['agent_id']), $this->redirect_popup('detail_gmm',"$ ".number_format($row[$index[1]],2), $row['agent_id']), number_format($row[$index[0]] + $row[$index[1]],2) ));
			}
		}
		if($is_congress || $is_recruit || $is_recruit_prod || $is_bonus)
		{
			array_push($array_values, array('Total', $total));
		}
		else
		{
			array_push($array_values, array('Total', "$ ".number_format($value_vid,2), "$ ".number_format($value_gmm,2), "$ ".number_format($value_vid + $value_gmm,2)));
		}
		return array('heads' => $heads, 'values' => $array_values);
	}
	public function get_grl_detail_primas_pagos_ini_vida($year)
    {
        $data = array('heads' => array('Nombre','Cantidad'), 'values' =>'');
        $total = 0;
        $values_array = array();
        $this->db->select('inter as name, SUM(impri) as amount');
        $this->db->from('production_');
        $this->db->where('apri = 1');
        $this->db->like('periodo', $year);
        $this->db->group_by('name');
        $this->db->order_by('amount', 'DESC');
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) 
            {
            	$total = $total + $row->amount;
                $tmp_array = array($row->name, "$ ".number_format($row->amount,2));
                array_push($values_array, $tmp_array);
            }
        }
        array_push($values_array, array('Total', "$ ".number_format($total,2)));
        $data['values'] = $values_array;
        return $data;
    }

    public function get_data_popup($year, $index, $heads, $num_dec,$is_vida = true, $is_congress = false, $is_prod = false, $is_agent_prod = false)
    {
    	//Set aux varibales
    	$array_values = array();
    	$array_used   = $this->get_main_list($year);
    	$total 		  = 0;
    	$ramo_url 	  = '';
    	if($is_vida)
    	{
    		$ramo_url = 'detail_vida';
    	}
    	else
    	{
    		$ramo_url = 'detail_gmm';
    	}

    	//Recorring array
    	foreach ($array_used as $row) 
    	{
    		$name = $name = $row['name'];

    		//Setting conditionals
    		if( $is_congress )
    		{
    			if($is_vida)
    			{
    				if($row[$index] != 'N/A' && $row[$index] != null)
    				{
    					$total++;
    					array_push($array_values, array($name, $this->redirect_popup($ramo_url,$row[$index],$row['agent_id'])));
    				}
    			}
    			else
    			{
    				if($row[$index] != 'No' && $row[$index] != null)
    				{
    					$total++;
    					array_push($array_values, array($name, $this->redirect_popup($ramo_url,$row[$index],$row['agent_id'])));
    				}
    			}
    		}
    		if( $is_prod )
    		{
    			if($row['generation'] != 'Consolidado')
    			{

    				if( $row[$index] != 0 )
    				{
    					$total = $total + $row[$index];

    					array_push($array_values, array($name, $this->redirect_popup($ramo_url,"$ ".number_format($row[$index],$num_dec),$row['agent_id'])));
    				}
    			}	
    		}
    		if($is_agent_prod)
    		{
    			switch ($row['generation']) {
    				case 'Generación 1':
    					if($row[$index] >= 150000)
    					{
    						$total++;
    						array_push($array_values, array($name, $this->redirect_popup($ramo_url,"$ ".number_format($row[$index],$num_dec),$row['agent_id'])));
    					}
    					break;
    				case 'Generación 2':
    					if($row[$index] >= 150000)
    					{
    						$total++;
    						array_push($array_values, array($name, $this->redirect_popup($ramo_url,"$ ".number_format($row[$index],$num_dec),$row['agent_id'])));
    					}
    					break;
    				case 'Generación 3':
    					if($row[$index] >= 250000)
    					{
    						$total++;
    						array_push($array_values, array($name, $this->redirect_popup($ramo_url,"$ ".number_format($row[$index],$num_dec),$row['agent_id'])));
    					}
    					break;
    				case 'Generación 4':
    					if($row[$index] >= 300000)
    					{
    						$total++;
    						array_push($array_values, array($name, $this->redirect_popup($ramo_url,"$ ".number_format($row[$index],$num_dec),$row['agent_id'])));
    					}
    					break;
    				case 'Consolidado':
    					if($row[$index] >= 300000)
    					{
    						$total++;
    						array_push($array_values, array($name, $this->redirect_popup($ramo_url,"$ ".number_format($row[$index],$num_dec),$row['agent_id'])));
    					}
    					break;
    				default:
    					break;
    			}
    		}
    		if( (!$is_congress) && (!$is_prod) && (!$is_agent_prod))
    		{
    			if( $row[$index] != 0)
    			{
    				$total = $total + $row[$index];	
    				$tmp_str = '';
    				if($num_dec > 0)
    				{
    					$tmp_str = "$ ".number_format($row[$index],$num_dec);
    				}
    				else
    				{
    					$tmp_str = number_format($row[$index],$num_dec);
    				}

    				array_push($array_values, array($name, $this->redirect_popup($ramo_url,$tmp_str,$row['agent_id'])));
    			}
    		}
    	}

    	//Set Total Row
    	$tmp_str = '';
    	if($num_dec > 0)
    	{
    		$tmp_str = "$ ".number_format($total,$num_dec);
    	}
    	else
    	{
    		$tmp_str = number_format($total,$num_dec);
    	}

    	//Setting final array 
    	array_push($array_values, array('Total', $tmp_str));
    	return array('heads' => $heads, 'values' => $array_values);
    }
    public function redirect_popup($ramo_view, $value, $agent_id)
    {
    	$ur_str = base_url().'termometro/'.$ramo_view.'/?id='.$agent_id;
    	$url = '<a target="_blank" href="'.$ur_str.'">'.$value.'</a>';
    	return $url;
    }
    public function popup_detail_vida($agent_id, $periodo, $type)
    {
    	$year 		  = substr($periodo,1);
    	$data 		  = array();
    	$select_where = '';
    	$index = 0;
    	switch ($periodo) {
    		case '1'.$year:
	    		$index = 0;
	    		break;
    		case '2'.$year:
	    		$index = 1;
	    		break;
    		case '3'.$year:
	    		$index = 2;
	    		break;
    		case '4'.$year:
	    		$index = 3;
	    		break;
    		default:
    			break;
    	}
    	switch ($type) {
    		case 'cartera_real':
    			$select_where 	= 'apri > 1';
    			$data 			= $this->termometro_vida_model->get_popup_data_vida($agent_id, $periodo, $type, null, null, $select_where);
    			break;
    		case 'prima_ubicar':
    			$select_where 	= 'apri = 1';
    			$data 			= $this->termometro_vida_model->get_popup_data_vida($agent_id, $periodo, $type,null, null, $select_where);
    			break;
    		case 'prima_pagar':
    			$select_where 	= 'apri = 1';
    			$data 			= $this->termometro_vida_model->get_popup_data_vida($agent_id, $periodo, $type,null, null, $select_where);
    			break;
    		case 'num_negocios':
    			$agent_values   = $this->get_vida_agent_details($periodo, $agent_id);
    			break;
    		case 'cartera_estimada':
    			$data = $this->termometro_vida_model->get_data_popup_cartera_estimada($agent_id, $periodo);
    			break;
    		case 'comisiones_directas':
    			$select_where 	= 'apri = 1';
    			$data 			= $this->termometro_vida_model->get_popup_data_vida($agent_id, $periodo, $type,null,null, $select_where);
    			break;
    		case 'bono_primer_anio':
    			$select_where 	= 'apri = 1';
    			$agent_values 	= $this->get_vida_agent_details($year, $agent_id, true);
    			$data 			= $this->termometro_vida_model->get_popup_data_vida($agent_id, $periodo, $type, $agent_values, $index, $select_where);
    			break;
    		case 'comision_cartera':
    			$select_where 	= 'apri > 1';
    			$data 			= $this->termometro_vida_model->get_popup_data_vida($agent_id, $periodo, $type,null, null, $select_where);
    			break;
    		case 'bono_cartera':
    			$select_where 	= 'apri > 1';
    			$agent_values 	= $this->get_vida_agent_details($year, $agent_id);
    			$data 			= $this->termometro_vida_model->get_popup_data_vida($agent_id, $periodo, $type, $agent_values, $index, $select_where);
    			break;
    		case 'suma_ingresos_totales':
    			$agent_values 	= $this->get_vida_agent_details($year, $agent_id, true);
    			$data 			= $this->termometro_vida_model->get_data_popup_suma_ingresos($agent_id, $periodo, $agent_values,$index);
    			break;
    		case 'bono_integral':
    			# code...
    			break;
    		case 'total_ingresos':
    			$agent_values 	= $this->get_vida_agent_details($year, $agent_id, true);
    			$data 			= $this->termometro_vida_model->get_data_popup_ingresos_totales($agent_id, $agent_values, substr($periodo,1));
    			break;
    		
    		default:
    			# code...
    			break;
    	}
    	return $data;
    }
    public function popup_detail_gmm($agent_id, $periodo, $type)
    {
    	$year 		  = substr($periodo,1);
    	$data 		  = array();
    	$select_where = '';
    	$index = 0;
    	switch ($periodo) {
    		case '1'.$year:
	    		$index = 0;
	    		break;
    		case '2'.$year:
	    		$index = 1;
	    		break;
    		case '3'.$year:
	    		$index = 2;
	    		break;
    		default:
    			break;
    	}
    	switch ($type) {
    		case 'prima_ubicar':
    			$data 			= $this->termometro_gmm_model->get_popup_data_gmm($agent_id, $periodo, $type);
    			break;
    		case 'prima_pagar':
    			$data 			= $this->termometro_gmm_model->get_popup_data_gmm($agent_id, $periodo, $type);
    			break;
    		case 'comisiones_directas':
    			$data 			= $this->termometro_gmm_model->get_popup_data_gmm($agent_id, $periodo, $type);
    			break;
    		case 'bono_primer_anio':
    			$values_agent   = $this->get_gmm_agent_details($year, $agent_id, true);
    			$data 			= $this->termometro_gmm_model->get_popup_data_gmm($agent_id, $periodo, $type, $values_agent, $index);
    			break;
    		case 'cartera_conservada':
    			$data 			= $this->termometro_gmm_model->get_popup_data_gmm($agent_id, $periodo, $type);
    			break;
    		case 'comision_cartera':
    			$data 			= $this->termometro_gmm_model->get_popup_data_gmm($agent_id, $periodo, $type);
    			break;
    		case 'bono_rentabilidad':
    			$values_agent   = $this->get_gmm_agent_details($year, $agent_id, true);
    			$data 			= $this->termometro_gmm_model->get_popup_data_gmm($agent_id, $periodo, $type, $values_agent, $index);
    			break;
    		case 'suma_ingresos':
    			$values_agent   = $this->get_gmm_agent_details($year, $agent_id, true);
    			$data 			= $this->termometro_gmm_model->get_popup_data_gmm($agent_id, $periodo, $type, $values_agent, $index);
    			break;
    		case 'ingresos_totales':
    			$values_agent   = $this->get_gmm_agent_details($year, $agent_id, true);
    			$data 			= $this->termometro_gmm_model->get_popup_data_ingresos_totales($agent_id, $values_agent, $year);
    			break;
    		
    		default:
    			# code...
    			break;
    	}
    	return $data;
    }
    public function restore_agent_id()
    {
    	$actual_date  = date('Y-m-d h:m:s');
		$sql_update   = 'SELECT * FROM cache_updates WHERE type_update = "termometro";';
		$query_update =  $this->db->query($sql_update);
		$res_update   = $query_update->result_array();
		if($actual_date > $res_update[0]['next_update'])
		{
			//on business_
			$sql = 'SELECT production_.agent_id, business_.interl FROM business_ JOIN production_ ON production_.interl = business_.interl WHERE business_.agent_id IS NULL GROUP BY agent_id;';
	    	$query = $this->db->query($sql);
	        $res   = $query->result_array();
	        foreach ($res as $row) {
	            $this->db->where('interl',$row['interl']);
			    $this->db->update('business_',array('agent_id' => $row['agent_id']));
	        }
	        $query->free_result();

	        $data = array('last_update' => $actual_date, 'next_update' => date( "Y-m-d h:m:s", strtotime( "$actual_date +1 day" ) ));
			$this->db->where('type_update', 'termometro');
			$this->db->update('cache_updates', $data);
		}
		$query_update->free_result();
    }
}
/* End of file termometro_model.php */
/* Location: ./application/modules/termometro/models/termometro_model.php */
?>