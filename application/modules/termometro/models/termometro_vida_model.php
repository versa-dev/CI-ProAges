<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/** 
 * Termometro de Ventas - Modelo de Datos
 * 
 * @author     Jesus Castilla & José Gilberto Pérez Molina
 * Date:       December, 2018
 * Locaion:    Veracruz, Mexico
 * Mail:       jesuscv1821@gmail.com - jgilbertopmolina@gmail.com
 */


class termometro_vida_model extends CI_Model{
	public function __construct()
	{
		parent::__construct();
        $this->load->model('termometro/termometro_generics_model');
	}

    // ***  FUNCIONES PARA LA VISTA GENERAL DE VIDA  ***
    public function get_grl_main_list($year)
    {
        $sql = "SELECT * FROM general_pays_agent_vi WHERE periodo = ".$year.";";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res;
    }
    public function get_grl_primas_iniciales($date,$year)
    {
        $sql = "SELECT SUM(impri) AS sum_prima_inicial FROM production_ WHERE apri = 1 AND femovibo >= '".$year."-01-01 00:00:00'  AND femovibo <= '".$date." 23:59:59';";
        $query = $this->db->query($sql);
        $res = $query->result_array();

        return $res[0]['sum_prima_inicial'];
    }
    public function get_grl_percs($year)
    {
        $sql = "SELECT avg(pconreal) as vida_perc_con_real, avg(pconacot) as vida_perc_con_acot FROM bona_t where periodo like '%" . $year . "';";
        $query = $this->db->query($sql);
        $a1 = $query->result_array();
        $query->free_result();

        $sql2 = "SELECT avg (pe_con_zona) as vida_perc_canc FROM conservation_ where periodo like '%" . $year . "';";
        $query = $this->db->query($sql2);
        $a2 = $query->result_array();
        $query->free_result();

        $sql3 = "SELECT avg (bxacot) as vida_perc_acot FROM bona_vi where periodo like '%" . $year . "';";
        $query = $this->db->query($sql3);
        $a3 = $query->result_array();
        $query->free_result();

        $vida_percs_final = array(
            'perc_conserv_real'         => $a1[0]['vida_perc_con_real'],
            'perc_conserv_real_acot'    => $a1[0]['vida_perc_con_acot'],
            'perc_cancel'               => $a2[0]['vida_perc_canc'],
            'perc_acot'                 => $a3[0]['vida_perc_acot']
        );

        return $vida_percs_final;
    }
    public function get_grl_agentes_venta_nueva_array($grl_values = array())
    {
        $agentes_venta_nueva = 0;
        foreach ($grl_values as $row) {
            
            if($row['ingresos_nuevo'] > 0)
            {
                $agentes_venta_nueva ++;
            }
        }
        return $agentes_venta_nueva;
    }
    public function get_grl_mdrt_agents($date,$year)
    {
        return $this->termometro_generics_model->generic_congress_query_vida($date,$year,array('Generación 1','Generación 2','Generación 3','Generación 4','Consolidado'),1900000);
    }
    public function get_grl_ingresos_total($main_list = array())
    {
        return $this->termometro_generics_model->generic_value_ingresos($main_list);
    }
    public function get_grl_congresos($main_list = array())
    {
        return $this->termometro_generics_model->generic_value_congreso($main_list);
    }
    public function get_grl_ingresos_venta_nueva($main_list = array())
    {
        return $this->termometro_generics_model->generic_value_ingresos_tipo($main_list,'ingresos_nuevo');
    }
    public function get_grl_ingresos_venta_renov($main_list = array())
    {
        return $this->termometro_generics_model->generic_value_ingresos_tipo($main_list,'ingresos_renov');
    }
    public function get_grl_vida_array($main_list = array())
    {
        $array_values = array(
            'ingresos_generales'    => 0,
            'primas_ubicar'         => 0,
            'primas_pagar'          => 0,
            'negocios_pai'          => 0,
            'ptos_standing'         => 0,
            'produccion_inicial'    => 0,
            'ingresos_nuevo'        => 0,
            'ingresos_renov'        => 0,
            'ingresos_generation'   => $this->get_grl_ingresos_total($main_list),
            'agentes_congreso'      => $this->get_grl_congresos($main_list),
            'agentes_venta_nueva'   => $this->get_grl_agentes_venta_nueva_array($main_list)
        );
        foreach ($main_list as $row) {
            $array_values['primas_ubicar']      = $array_values['primas_ubicar'] + $row['primas_ubicar'];
            $array_values['primas_pagar']       = $array_values['primas_pagar'] + $row['primas_pagar'];
            $array_values['negocios_pai']       = $array_values['negocios_pai'] + $row['negocios_pai'];
            $array_values['ingresos_nuevo']     = $array_values['ingresos_nuevo'] + $row['ingresos_nuevo'];
            $array_values['ingresos_renov']     = $array_values['ingresos_renov'] + $row['ingresos_renov'];
            $array_values['ingresos_generales'] = $array_values['ingresos_generales'] + $row['ingresos_generales'];
            $array_values['ptos_standing']      = $array_values['ptos_standing'] + $row['ptos_standing'];
            if($row['generation'] != 'Consolidado')
            {
                $array_values['produccion_inicial'] = $array_values['produccion_inicial'] + $row['primas_ubicar'];
            }
        }
        return $array_values;
    }
    public function get_grl_points_bonus_agent($id,$main_list)
    {
        foreach ($main_list as $row) 
        {
            if($row['id'] == $id)
            {
                if($row['primas_ubicar'] >= 120000 && $row['primas_ubicar'] < 240000)
                {
                    return 1;
                }
                if($row['primas_ubicar'] >= 240000 && $row['primas_ubicar'] < 410000)
                {
                    return 2;
                }
                if($row['primas_ubicar'] >= 410000)
                {
                    return 3;
                }
            }
        }
        return 0;
    }
    public function get_grl_agent_active($id,$main_list)
    {
        foreach ($main_list as $row) 
        {
            if($row['id'] == $id)
            {
                return $row['ingresos_generales'];
            }
        }
        return 0;
    }



    // ***  FUNCIONES PARA LA VISTA DETALLE DE VIDA  ***
    public function set_final_array($array, $num_decimals)
    {
        $final_array = array(
            number_format(abs($array[0]),$num_decimals),
            number_format(abs($array[1]),$num_decimals),
            number_format(abs($array[2]),$num_decimals),
            number_format(abs($array[3]),$num_decimals),
            number_format(abs(array_sum($array)),$num_decimals),
            number_format(abs(array_sum($array)/12),$num_decimals)
        );
        return $final_array;
    }
    public function get_detail_cartera_real($id_agent, $year, $comision_cartera = false)
    {
        $cartera_periods = array( 
            $this->termometro_generics_model->generic_value_cartera_real_period($id_agent, "1".$year,$comision_cartera),
            $this->termometro_generics_model->generic_value_cartera_real_period($id_agent, "2".$year,$comision_cartera),
            $this->termometro_generics_model->generic_value_cartera_real_period($id_agent, "3".$year,$comision_cartera),
            $this->termometro_generics_model->generic_value_cartera_real_period($id_agent, "4".$year,$comision_cartera)
        );
        return $cartera_periods;
    }  
    public function get_detail_cartera_pronosticada($id_agent, $year)
    {
        $cartera_pronosticada = array(
            $this->termometro_generics_model->generic_value_cartera_pronosticada($id_agent, "1".$year),
            $this->termometro_generics_model->generic_value_cartera_pronosticada($id_agent, "2".$year),
            $this->termometro_generics_model->generic_value_cartera_pronosticada($id_agent, "3".$year),
            $this->termometro_generics_model->generic_value_cartera_pronosticada($id_agent, "4".$year)
        );
        return $cartera_pronosticada;
    }
    public function get_detail_cartera_real_simulation($cartera_real)
    {
        $cartera_periods = array( 
            ($cartera_real[0] * 0.08),
            ($cartera_real[1] * 0.08),
            ($cartera_real[2] * 0.08),
            ($cartera_real[3] * 0.08),
        );
        return $cartera_periods;
    }  
    public function get_detail_prima_ubicar($id_agent, $year)
    {
        $primas_ubicar = array( 
            $this->termometro_generics_model->generic_value_prima_ubicar_period($id_agent, "1".$year),
            $this->termometro_generics_model->generic_value_prima_ubicar_period($id_agent, "2".$year),
            $this->termometro_generics_model->generic_value_prima_ubicar_period($id_agent, "3".$year),
            $this->termometro_generics_model->generic_value_prima_ubicar_period($id_agent, "4".$year)
        );
        return $primas_ubicar;
    } 
    public function get_detail_prima_pago($id_agent, $year)
    {
        $primas_pago = array( 
            $this->termometro_generics_model->generic_value_prima_pago_period($id_agent, "1".$year),
            $this->termometro_generics_model->generic_value_prima_pago_period($id_agent, "2".$year),
            $this->termometro_generics_model->generic_value_prima_pago_period($id_agent, "3".$year),
            $this->termometro_generics_model->generic_value_prima_pago_period($id_agent, "4".$year)
        );
        return $primas_pago;
    } 
    public function get_detail_bussiness($id_agent,$year)
    {
        $business = array(
            $this->termometro_generics_model->generic_value_bussiness_vida_period($id_agent, "1".$year),
            $this->termometro_generics_model->generic_value_bussiness_vida_period($id_agent, "2".$year),
            $this->termometro_generics_model->generic_value_bussiness_vida_period($id_agent, "3".$year),
            $this->termometro_generics_model->generic_value_bussiness_vida_period($id_agent, "4".$year)
        );
        
        return $business;
    }
    public function get_detail_conservation($id_agent,$year)
    {
        $conservation = array(
            $this->termometro_generics_model->generic_value_conservacion($id_agent, "1".$year),
            $this->termometro_generics_model->generic_value_conservacion($id_agent, "2".$year),
            $this->termometro_generics_model->generic_value_conservacion($id_agent, "3".$year),
            $this->termometro_generics_model->generic_value_conservacion($id_agent, "4".$year)
        );
        return $conservation;
    }
    public function get_detail_comisiones_directas($primas_ubi)
    {
        $comisiones_di = array( 
            $primas_ubi[0] * 0.25,
            $primas_ubi[1] * 0.25,
            $primas_ubi[2] * 0.25,
            $primas_ubi[3] * 0.25
        );
        return $comisiones_di;
    }
    public function get_detail_perce_bono_primer_anio($generation,$primas_ubicar,$negocios,$faltante = false, $year)
    {
        $perce_bono = array(
            $this->termometro_generics_model->generic_value_perce_bono($generation,$primas_ubicar[0],$negocios[0],$faltante, $year),
            $this->termometro_generics_model->generic_value_perce_bono($generation,$primas_ubicar[1],$negocios[1],$faltante, $year),
            $this->termometro_generics_model->generic_value_perce_bono($generation,$primas_ubicar[2],$negocios[2],$faltante, $year),
            $this->termometro_generics_model->generic_value_perce_bono($generation,$primas_ubicar[3],$negocios[3],$faltante, $year)
        );
        $final_array = $this->set_final_array($perce_bono,2);
        return $final_array;
    }
    public function get_detail_bono_primer_anio($bono, $primas_pagos, $conservacion, $generation)
    {
        $primas_bono = array(
            $this->termometro_generics_model->generic_value_bono_primer_anio($bono[0], $primas_pagos[0], $conservacion[0], $generation),
            $this->termometro_generics_model->generic_value_bono_primer_anio($bono[1], $primas_pagos[1], $conservacion[1], $generation),
            $this->termometro_generics_model->generic_value_bono_primer_anio($bono[2], $primas_pagos[2], $conservacion[2], $generation),
            $this->termometro_generics_model->generic_value_bono_primer_anio($bono[3], $primas_pagos[3], $conservacion[3], $generation)
        );
        return $primas_bono;
    }
    public function get_detail_business_faltantes($id_agent,$year)
    {
        $result = 0;
        $business = $this->termometro_generics_model->generic_value_businees_faltantes($id_agent,"1".$year) +
        $this->termometro_generics_model->generic_value_businees_faltantes($id_agent,"2".$year) +
        $this->termometro_generics_model->generic_value_businees_faltantes($id_agent,"3".$year) +
        $this->termometro_generics_model->generic_value_businees_faltantes($id_agent,"4".$year);
        if($business < 20)
        {
            $result = 20 - $business;
        }
        return $result;
    }
    public function get_detail_puntos_standing($year, $num_negocios,$primas_ubi,$generation,$next=false)
    {
        return $this->termometro_generics_model->generic_value_ptos_standing_agent($year, $num_negocios,$primas_ubi,$generation,$next);
    }
    public function get_detail_puntos_vida($prima_ubicar,$generation)
    {
        $ptos = array(
            $this->termometro_generics_model->generic_value_ptos_vida_agent($prima_ubicar[0],$generation),
            $this->termometro_generics_model->generic_value_ptos_vida_agent($prima_ubicar[1],$generation),
            $this->termometro_generics_model->generic_value_ptos_vida_agent($prima_ubicar[2],$generation),
            $this->termometro_generics_model->generic_value_ptos_vida_agent($prima_ubicar[3],$generation)
        );
        $final_array = $this->set_final_array($ptos,0);
        return $final_array;
    }
    public function get_detail_puntos_gmm($id_agent, $year)
    {
        $ptos = array(
            $this->termometro_generics_model->generic_value_ptos_gmm_agent($id_agent,"1".$year),
            $this->termometro_generics_model->generic_value_ptos_gmm_agent($id_agent,"2".$year),
            $this->termometro_generics_model->generic_value_ptos_gmm_agent($id_agent,"3".$year),
            $this->termometro_generics_model->generic_value_ptos_gmm_agent($id_agent,"4".$year)
        );
        $final_array = $this->set_final_array($ptos,0);
        return $final_array;
    }
    public function get_detail_puntos_autos($id_agent, $year)
    {
        $ptos = array(
            $this->termometro_generics_model->generic_value_ptos_auto_agent($id_agent,"1".$year),
            $this->termometro_generics_model->generic_value_ptos_auto_agent($id_agent,"2".$year),
            $this->termometro_generics_model->generic_value_ptos_auto_agent($id_agent,"3".$year),
            $this->termometro_generics_model->generic_value_ptos_auto_agent($id_agent,"4".$year)
        );
        $final_array = $this->set_final_array($ptos,0);
        return $final_array;
    }
    public function get_detail_congreso_agent($year, $generation, $prima_ubi, $num_negocios)
    {
        $congreso = array('actual' => '','siguiente' => '', 'prod_siguiente' => '', 'neg_siguiente' => '');

        $primas = array_sum($prima_ubi);

        $prod_min = (array) json_decode($this->termometro_generics_model->generic_value_get_pai_data($year, 'congreso', 'vida'));
        $neg_pai = array_sum($num_negocios);
        if($neg_pai >= 20)
        {
            $congreso['actual'] = 'N/A';
            $congreso['siguiente'] = 'Oro';
            $congreso['neg_siguiente'] = 0;

            if($primas < $prod_min['plat'])
            {
                if($generation == 'Generación 1')
                {
                    if($primas >= $prod_min['oro_g1'])
                    {
                        $congreso['actual'] = 'Oro';
                        $congreso['siguiente'] = 'Platino';
                        $congreso['prod_siguiente'] = $prod_min['plat'] - $primas;
                    }
                }
                if($generation == 'Generación 2')
                {
                    if($primas >= $prod_min['oro_g2'])
                    {
                        $congreso['actual'] = 'Oro';
                        $congreso['siguiente'] = 'Platino';
                        $congreso['prod_siguiente'] = $prod_min['plat'] - $primas;
                    }
                }
                if(($generation == 'Consolidado')|| ($generation == 'Generación 3') || ($generation == 'Generación 4'))
                {
                    if($primas >= $prod_min['oro_g3-4'])
                    {
                        $congreso['actual'] = 'Oro';
                        $congreso['siguiente'] = 'Platino';
                        $congreso['prod_siguiente'] = $prod_min['plat'] - $primas;
                    }
                    else
                    {
                        $congreso['prod_siguiente'] = $prod_min['oro_g3-4'] - $primas;
                    }
                }
            }
            if($primas >= $prod_min['plat'] && $primas < $prod_min['diam'])
            {
                $congreso['actual'] = 'Platino';
                $congreso['siguiente'] = 'Diamante';
                $congreso['prod_siguiente'] = $prod_min['diam'] - $primas;
            }
            if($primas >= $prod_min['diam'] && $primas < $prod_min['con'])
            {
                $congreso['actual'] = 'Diamante';
                $congreso['siguiente'] = 'Consejo';
                $congreso['prod_siguiente'] = $prod_min['con'] - $primas;
            }
            if($primas >= $prod_min['con'])
            {
                $congreso['actual'] = 'Consejo';
                $congreso['siguiente'] = 'N/A';
                $congreso['prod_siguiente'] = 0;
            }
        }
        else
        {
            $congreso['actual'] = 'N/A';
            $congreso['siguiente'] = 'Oro';
            $congreso['neg_siguiente'] = 20 - $neg_pai;
            if($generation == 'Generación 1')
            {
                $congreso['prod_siguiente'] = $prod_min['oro_g1'] - $primas;
            }
            if($generation == 'Generación 2')
            {
                $congreso['prod_siguiente'] = $prod_min['oro_g2'] - $primas;
            }
            if(($generation == 'Consolidado')|| ($generation == 'Generación 3') || ($generation == 'Generación 4'))
            {
                $congreso['prod_siguiente'] = $prod_min['oro_g3-4'] - $primas;   
            }
            
            if ($congreso['prod_siguiente'] < 0)
            {
                $congreso['prod_siguiente'] = 0;
            }
        }
        $congreso['prod_siguiente'] = number_format(abs($congreso['prod_siguiente']),2);
        return $congreso;
    }
    public function get_detail_perce_bono_cartera($year, $bono_primer_anio,$conservacion,$prima_ubi,$faltante = false)
    {
        $perce_bono_cartera = array(
            $this->termometro_generics_model->generic_value_perce_bono_cartera($year, $bono_primer_anio[0],$conservacion[0],$prima_ubi[0],$faltante, $year),
            $this->termometro_generics_model->generic_value_perce_bono_cartera($year, $bono_primer_anio[1],$conservacion[1],$prima_ubi[1],$faltante, $year),
            $this->termometro_generics_model->generic_value_perce_bono_cartera($year, $bono_primer_anio[2],$conservacion[2],$prima_ubi[2],$faltante, $year),
            $this->termometro_generics_model->generic_value_perce_bono_cartera($year, $bono_primer_anio[3],$conservacion[3],$prima_ubi[3],$faltante, $year)
        );
        $final_array = $this->set_final_array($perce_bono_cartera,2);
        return $final_array;
    }
    public function get_detail_bono_cartera($cartera_real, $perce_bono_cartera)
    {
        $bono_cartera = array(
            $this->termometro_generics_model->generic_value_bono_cartera($cartera_real[0],$perce_bono_cartera[0]),
            $this->termometro_generics_model->generic_value_bono_cartera($cartera_real[1],$perce_bono_cartera[1]),
            $this->termometro_generics_model->generic_value_bono_cartera($cartera_real[2],$perce_bono_cartera[2]),
            $this->termometro_generics_model->generic_value_bono_cartera($cartera_real[3],$perce_bono_cartera[3])
        );
        return $bono_cartera;
    }
    public function get_detail_bono_cartera_no_ganado($cartera_real, $bono_cartera)
    {
        $bono_cartera_no_ganado = array(
            ($cartera_real[0]*.12) - $bono_cartera[0],
            ($cartera_real[1]*.12) - $bono_cartera[1],
            ($cartera_real[2]*.12) - $bono_cartera[2],
            ($cartera_real[3]*.12) - $bono_cartera[3],
        );
        $final_array = $this->set_final_array($bono_cartera_no_ganado,2);
        return $final_array;
    }
    public function get_detail_suma_ingresos_totales($comisiones_directas,$comision_cartera,$bono_primer_anio,$bono_cartera)
    {
        $ingresos_totales =  array(
            $this->termometro_generics_model->generic_value_ingresos_totales($comisiones_directas[0], $bono_primer_anio[0], $comision_cartera[0], $bono_cartera[0]),
            $this->termometro_generics_model->generic_value_ingresos_totales($comisiones_directas[1], $bono_primer_anio[1], $comision_cartera[1], $bono_cartera[1]),
            $this->termometro_generics_model->generic_value_ingresos_totales($comisiones_directas[2], $bono_primer_anio[2], $comision_cartera[2], $bono_cartera[2]),
            $this->termometro_generics_model->generic_value_ingresos_totales($comisiones_directas[3], $bono_primer_anio[3], $comision_cartera[3], $bono_cartera[3])
        );
        $final_array = $this->set_final_array($ingresos_totales,2);
        return $final_array;        
    }
    public function get_detail_club_elite($faltante_prod, $faltante_neg)
    {
        $club_elite = 'No';
        if($faltante_prod <= 0 && $faltante_neg <=0)
        {
            $club_elite = 'Si';
        }
        return $club_elite;
    }
    public function get_detail_faltante_elite_prod($year, $prima_ubi)
    {
        $prima_min = (array) json_decode($this->termometro_generics_model->generic_value_get_pai_data($year, 'prima_club_elite', 'vida'));
        $prima = array_sum($prima_ubi);
        if($prima_min[0] - $prima < 0)
        {
            return 0;
        }
        else
        {
            return number_format(abs($prima_min[0] - $prima),2);
        }
        
    }
    public function get_detail_faltante_elite_neg($num_negocios)
    {
        $negocios_pai = 20 - array_sum($num_negocios);
        if($negocios_pai < 0)
        {
            return number_format(abs(0),0);
        }
        else
        {
            return number_format(abs($negocios_pai),0);
        }
    }
    public function get_detail_bono_integral($puntos_vida, $puntos_gmm, $puntos_auto, $bono_primer_anio)
    {
        error_log(print_r($puntos_gmm,true));
        $total_puntos = $puntos_vida[0]+$puntos_vida[1]+$puntos_vida[2]+$puntos_vida[3]+ 
                        $puntos_gmm[0] +$puntos_gmm[1] +$puntos_gmm[2] +$puntos_gmm[3] +
                        $puntos_auto[0]+$puntos_auto[1]+$puntos_auto[2]+$puntos_auto[3];
        error_log(print_r($total_puntos,true));
        $bono_integral = 0;

        if($total_puntos >=6 && $total_puntos < 11)
        {
            $bono_integral = 10;
        }
        if($total_puntos >=11 && $total_puntos < 16)
        {
            $bono_integral = 13;
        }
        if($total_puntos >=16 && $total_puntos < 21)
        {
            $bono_integral = 15;
        }
        if($total_puntos >=21 && $total_puntos < 26)
        {
            $bono_integral = 18;
        }
        if($total_puntos >=26 && $total_puntos < 31)
        {
            $bono_integral = 20;
        }
        if($total_puntos >=31)
        {
            $bono_integral = 25;
        }
        if($bono_integral > 0)
        {
            return  number_format(abs(($bono_primer_anio[0] * ($bono_integral/100)) +
                ($bono_primer_anio[1] * ($bono_integral/100)) +
                ($bono_primer_anio[2] * ($bono_integral/100)) +
                ($bono_primer_anio[3] * ($bono_integral/100))),2);
        }
        else
        {
            return number_format(abs(0),2);
        }
    }
    public function get_detail_ingresos_totales($bono_primer_anio, $club_elite, $bono_integral, $comisiones_directas, $comisiones_cartera, $bono_cartera,$ingresos_flag=false)
    {
        $ingresos_totales = 0;
        if(array_sum($bono_primer_anio)==0)
        {
            $ingresos_totales = array_sum($comisiones_directas) + array_sum($comisiones_cartera);
        }
        else
        {
            $ingresos_totales = array_sum($comisiones_directas) + array_sum($comisiones_cartera) + array_sum($bono_cartera) + array_sum($bono_primer_anio);
        }
        if($club_elite == 'Si')
        {
            $ingresos_totales = $ingresos_totales + 65000;
        }
        $ingresos_totales = $ingresos_totales + $bono_integral;

        if($ingresos_flag)
        {
            return $ingresos_totales;
        }
        else
        {
            return number_format(abs($ingresos_totales),2);
        }  
    }
    public function get_detail_cartera_estimada($cartera_real,$primas_pagos) // Esta se puede sacar con la suma de ingresos
    {
        $cartera_estimada = array(
            number_format(($cartera_real[0] + $primas_pagos[0]),2),
            number_format(($cartera_real[1] + $primas_pagos[1]),2),
            number_format(($cartera_real[2] + $primas_pagos[2]),2),
            number_format(($cartera_real[3] + $primas_pagos[3]),2),
        );
        return $cartera_estimada;
    }

    public function get_grl_primas_pagos_ini_by_agent_vida($year)
    {
        $data = array('heads' => array('Nombre','Ingresos'), 'values' =>'');
        $values_array = array();
        $this->db->select('inter as name, SUM(impri) as amount');
        $this->db->from('production_');
        $this->db->where('apri = 1');
        $this->db->like('periodo', $year);
        $this->db->group_by('name');
        $this->db->order_by('amount', 'DESC');
        $query = $this->db->get();
        $total = 0;
        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) 
            {

                $tmp_array = array($row->name, "$ ".number_format($row->amount,2));
                $total = $total + $row->amount;
                array_push($values_array, $tmp_array);
            }
        }
        array_push($values_array, array('Total', "$ ".number_format($total,2)));
        $data['values'] = $values_array;
        return $data;
    }



    //Funciones de los arrays de los poupups de los datos 
    public function redirect_popup_vida($poliza)
    {
        $sql = 'SELECT  work_order.id AS url_id FROM work_order JOIN policies ON policies.id = work_order.policy_id WHERE policies.uid = "'.$poliza.'";';
        $query = $this->db->query($sql);
        $res   = $query->result_array();
        if($res != null)
        {
            $ur_str = base_url().'ot/ver_ot/'.$res[0]['url_id'];
            $url = '<a target="_blank" href="'.$ur_str.'">'.$poliza.'</a>';
            return $url;
        }
        else
        {
            return $poliza;
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
    public function get_popup_data_vida($agent_id, $periodo, $type, $agent_values = null, $index_periodo = null,$where_array = null)
    {
        $this->db->select('poliza, dnnom AS asegurado, apri, po_pag1_da AS perce_pag, po_ubi1_da AS perce_ubi, impri AS prima, fecontbo as date');
        $this->db->from('production_');
        $this->db->where('agent_id', $agent_id);
        $this->db->where('periodo', $periodo);
        if($where_array != null)
        {
            $this->db->where($where_array);
        } 
        $query = $this->db->get();
        $total = 0;
        $array_values = array();
        if($query->num_rows > 0)
        {
            foreach ($query->result() as $row) 
            {
                $data = array();
                switch ($type) {
                    case 'cartera_real':
                        $value = ($row->perce_pag/100) * $row->prima;
                        $total = $total + $value;
                        if($value !=0)
                        {
                            $poliza = $this->get_poliza_uid($row->poliza);
                            $data  = array(date('d-m-Y',strtotime($row->date)),$this->redirect_popup_vida($poliza, 5), $row->asegurado, "$ ".number_format($value,2));
                        }
                        break;
                    case 'prima_ubicar':
                        $value = ($row->perce_ubi/100) * $row->prima;
                        $total = $total + $value;
                        if($value !=0)
                        {
                            $poliza = $this->get_poliza_uid($row->poliza);
                            $data  = array(date('d-m-Y',strtotime($row->date)),$this->redirect_popup_vida($poliza), $row->asegurado, "$ ".number_format($value,2));
                        }
                        break;
                    case 'prima_pagar':
                        $value = ($row->perce_pag/100) * $row->prima;
                        $total = $total + $value;
                        if($value !=0)
                        {
                            $poliza = $this->get_poliza_uid($row->poliza);
                            $data  = array(date('d-m-Y',strtotime($row->date)),$this->redirect_popup_vida($poliza), $row->asegurado, "$ ".number_format($value,2));
                        }
                        break;
                    case 'comisiones_directas':
                        $value = (($row->perce_ubi/100) * $row->prima) * 0.25;
                        $total = $total + $value;
                        if($value !=0)
                        {
                            $poliza = $this->get_poliza_uid($row->poliza);
                            $data  = array(date('d-m-Y',strtotime($row->date)),$this->redirect_popup_vida($poliza), $row->asegurado, "$ ".number_format($value,2));
                        }
                        break;
                    case 'bono_primer_anio':
                        if($agent_values['conservacion'][$index_periodo] != 0)
                        {
                            $value = (($row->perce_pag/100) * $row->prima) * ($agent_values['perce_bono_primer_anio'][$index_periodo]/100);
                            $total = $total + $value;
                            if($value !=0)
                            {
                                $poliza = $this->get_poliza_uid($row->poliza);
                                $data  = array(date('d-m-Y',strtotime($row->date)),$this->redirect_popup_vida($poliza), $row->asegurado, "$ ".number_format($value,2));
                            }
                        }
                        break;
                    case 'comision_cartera':
                        $value = (($row->perce_pag/100) * $row->prima) * 0.08;
                        $total = $total + $value;
                        if($value !=0)
                        {
                            $poliza = $this->get_poliza_uid($row->poliza);
                            $data  = array(date('d-m-Y',strtotime($row->date)),$this->redirect_popup_vida($poliza), $row->asegurado, "$ ".number_format($value,2));
                        }
                        break;
                    case 'bono_cartera':
                        if($agent_values['cartera_real'][$index_periodo] != 0)
                        {
                            $value = (($row->perce_pag/100) * $row->prima) * ($agent_values['perce_bono_cartera'][$index_periodo]/100);
                            $total = $total + $value;
                            if($value !=0)
                            {
                                $poliza = $this->get_poliza_uid($row->poliza);
                                $data  = array(date('d-m-Y',strtotime($row->date)),$this->redirect_popup_vida($poliza), $row->asegurado, "$ ".number_format($value,2));
                            }
                        }
                        break;
                    default:
                        # code...
                        break;
                }
                array_push($array_values, $data);
            }
        }
        array_push($array_values, array('Total','','',"$ ".number_format($total,2)));
        return array('heads' => array('Fecha de poliza','Poliza', 'Asegurado', 'Cantidad'), 'values' => $array_values);
    } 
    public function get_data_popup_cartera_estimada($agent_id, $periodo)
    {
        $sql = 'SELECT  prima, poliza, perce_pag, asegurado, tipo
                FROM (
                    (SELECT impri AS prima, po_pag1_da AS perce_pag, dnnom AS asegurado, poliza, "Cartera Real" AS tipo FROM production_ WHERE agent_id ='.$agent_id.' and apri > 1 and periodo = '.$periodo.' )
                    UNION (SELECT  impri AS prima, po_pag1_da AS perce_pag, dnnom AS asegurado, poliza, "Prima para Pagar" as tipo from production_ where agent_id='.$agent_id.' and apri = 1 and periodo = '.$periodo.')) AS a;';
        $query = $this->db->query($sql);
        $res   = $query->result_array();
        $array_values = array();
        $total_amount = 0;
        if( $query->num_rows() > 0 )
        {
            foreach ($res as $row) {
                $total_amount = $total_amount + $row['prima'] *($row['perce_pag']/100);
                array_push($array_values, array($this->redirect_popup_vida($row['poliza']), $row['asegurado'], "$ ".number_format($row['prima'] *($row['perce_pag']/100),2), $row['tipo']));
            } 
        }
        array_push($array_values, array('Total',number_format($total_amount,2)));
        return array('heads' => array('Poliza', 'Asegurado', 'Cantidad', 'Tipo'), 'values' => $array_values);
    }
    public function get_data_popup_suma_ingresos($agent_id, $periodo, $agent_values, $index_periodo)
    {
        $sql = 'SELECT poliza, dnnom AS asegurado, po_ubi1_da AS perce_ubi, po_pag1_da AS perce_pag, apri, impri AS prima, fecontbo AS date
                FROM  production_
                WHERE agent_id = '.$agent_id.'
                AND periodo = '.$periodo;
        $query = $this->db->query($sql);
        $res   = $query->result_array();
        $array_values = array();
        $total_amount = 0;
        if( $query->num_rows() > 0 )
        {
            foreach ($res as $row) {
                $poliza = $this->get_poliza_uid($row['poliza']);
                if($agent_values['bono_primer_anio'][$index_periodo] != 0)
                {
                    $tipo = '';
                    if ($row['apri'] == 1)
                    {
                        $bono_primer_anio   = $row['prima']*($row['perce_pag']/100)*($agent_values['perce_bono_primer_anio'][$index_periodo]/100);
                        $comision_directa   = $row['prima']*($row['perce_ubi']/100)*0.25;
                        $total_amount = $total_amount + $bono_primer_anio;
                        $total_amount = $total_amount + $comision_directa;
                        array_push($array_values, array(date('d-m-Y',strtotime($row['date'])),$this->redirect_popup_vida($poliza), $row['asegurado'], "$ ".number_format($bono_primer_anio,2), 'Bono de Primer Año'));
                        array_push($array_values, array(date('d-m-Y',strtotime($row['date'])),$this->redirect_popup_vida($poliza), $row['asegurado'], "$ ".number_format($comision_directa,2), 'Comisión Directa'));
                    }
                    else
                    {
                        $bono_cartera       = $row['prima']*($row['perce_pag']/100)*($agent_values['perce_bono_cartera'][$index_periodo]/100);
                        $comision_cartera   = $row['prima']*($row['perce_pag']/100)*0.08;
                        $total_amount = $total_amount + $bono_cartera;
                        $total_amount = $total_amount + $comision_cartera;
                        array_push($array_values, array(date('d-m-Y',strtotime($row['date'])),$this->redirect_popup_vida($poliza), $row['asegurado'], "$ ".number_format($bono_cartera,2), 'Bono Cartera'));
                        array_push($array_values, array(date('d-m-Y',strtotime($row['date'])),$this->redirect_popup_vida($poliza), $row['asegurado'], "$ ".number_format($comision_cartera,2), 'Comisión Cartera'));
                    }
                }
                else
                {
                    $tipo = '';
                    if($row['apri'] == 1)
                    {
                        $value = (($row['prima'])*($row['perce_ubi']/100))*0.25;
                        $tipo = 'Comisión Directa';
                    }
                    else
                    {
                        $value = (($row['prima'])*($row['perce_pag']/100))*0.08;
                        $tipo = 'Comisión Cartera';
                    }
                    if($value != 0)
                    {
                        $total_amount = $total_amount + $value;
                        array_push($array_values, array(date('d-m-Y',strtotime($row['date'])),$this->redirect_popup_vida($poliza), $row['asegurado'], "$ ".number_format($value,2), $tipo));
                    }
                    
                }
            } 
        }
        array_push($array_values, array('Total','','','$ '.number_format($total_amount,2),''));
        return array('heads' => array('Fecha','Poliza', 'Asegurado', 'Cantidad', 'Tipo'), 'values' => $array_values);
    }
    public function get_data_popup_ingresos_totales($agent_id, $agent_values, $year)
    {
        $periodos = array('1'.$year,'2'.$year,'3'.$year,'4'.$year);
        $array_values = array();
        $total_amount = 0;

        foreach ($periodos as $periodo) {
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
            $sql = 'SELECT poliza, dnnom AS asegurado, po_ubi1_da AS perce_ubi, po_pag1_da AS perce_pag, apri, impri AS prima, fecontbo AS date
                    FROM  production_
                    WHERE agent_id = '.$agent_id.'
                    AND periodo = '.$periodo;
            $query = $this->db->query($sql);
            $res   = $query->result_array();
            if( $query->num_rows() > 0 )
            {
                foreach ($res as $row) {
                    $poliza = $this->get_poliza_uid($row['poliza']);
                    if($agent_values['bono_primer_anio'][$index] != 0)
                    {
                        if ($row['apri'] == 1)
                        {
                            $bono_primer_anio   = $row['prima']*($row['perce_pag']/100)*($agent_values['perce_bono_primer_anio'][$index]/100);
                            $comision_directa   = $row['prima']*($row['perce_ubi']/100)*0.25;
                            $total_amount = $total_amount + $bono_primer_anio;
                            $total_amount = $total_amount + $comision_directa;
                            array_push($array_values, array(date('d-m-Y',strtotime($row['date'])),$this->redirect_popup_vida($poliza), $row['asegurado'], "$ ".number_format($bono_primer_anio,2), 'Bono de Primer Año'));
                            array_push($array_values, array(date('d-m-Y',strtotime($row['date'])),$this->redirect_popup_vida($poliza), $row['asegurado'], "$ ".number_format($comision_directa,2), 'Comisión Directa'));
                        }
                        else
                        {
                            $bono_cartera       = $row['prima']*($row['perce_pag']/100)*($agent_values['perce_bono_cartera'][$index]/100);
                            $comision_cartera   = $row['prima']*($row['perce_pag']/100)*0.08;
                            $total_amount = $total_amount + $bono_cartera;
                            $total_amount = $total_amount + $comision_cartera;
                            array_push($array_values, array(date('d-m-Y',strtotime($row['date'])),$this->redirect_popup_vida($poliza), $row['asegurado'], "$ ".number_format($bono_cartera,2), 'Bono Cartera'));
                            array_push($array_values, array(date('d-m-Y',strtotime($row['date'])),$this->redirect_popup_vida($poliza), $row['asegurado'], "$ ".number_format($comision_cartera,2), 'Comisión Cartera'));
                        }
                    }
                    else
                    {
                        $tipo = '';
                        if($row['apri'] == 1)
                        {
                            $value = (($row['prima'])*($row['perce_ubi']/100))*0.25;
                            $tipo = 'Comisión Directa';
                        }
                        else
                        {
                            $value = (($row['prima'])*($row['perce_pag']/100))*0.08;
                            $tipo = 'Comisión Cartera';
                        }
                        if($value != 0)
                        {
                            $total_amount = $total_amount + $value;
                            array_push($array_values, array(date('d-m-Y',strtotime($row['date'])),$this->redirect_popup_vida($poliza), $row['asegurado'], "$ ".number_format($value,2), $tipo));
                        }
                        
                    }
                } 
            }
            $query->free_result();
        }
        
        array_push($array_values, array('Total','','','$ '.number_format($total_amount,2),''));
        return array('heads' => array('Fecha','Poliza', 'Asegurado', 'Cantidad', 'Tipo'), 'values' => $array_values);
    }
}
/* End of file termometro_model.php */
/* Location: ./application/modules/termometro/models/termometro_model.php */
?>