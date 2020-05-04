<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/** 
 * Termometro de Ventas - Modelo de Datos
 * 
 * @author     Jesus Castilla & José Gilberto Pérez Molina
 * Date:       December, 2018
 * Locaion:    Veracruz, Mexico
 * Mail:       jesuscv1821@gmail.com - jgilbertopmolina@gmail.com
 */


class termometro_gmm_model extends CI_Model{
	public function __construct()
	{
		parent::__construct();
        $this->load->model('termometro/termometro_generics_model');
	}

    // ***  FUNCIONES PARA LA VISTA GENERAL DE GMM ***
    public function set_final_array($array, $num_decimals)
    {
        $final_array = array(
            number_format(abs($array[0]),$num_decimals),
            number_format(abs($array[1]),$num_decimals),
            number_format(abs($array[2]),$num_decimals),
            number_format(abs(array_sum($array)),$num_decimals),
            number_format(abs(array_sum($array)/12),$num_decimals)
        );
        return $final_array;
    }
    public function get_grl_payments_amount($date,$year, $generation = null, $prod_pmaneta = false)
    {
        /*
        Explicacion: esta función regresara la sumatoria de las polizas registradas en la hoja dep roduccion_sa
        Logica: Regresar un solo dato que sera la sumatoria de las polizas en el año que se ha seleccionado, si se necesita la generacion, se agrega el parametro a la consulta, de lo contrario solo devuelve toda la sumatoria
        */
        $total_gmm = 0;
        $this->db->select('SUM(pmaneta) as total_amount');
        $this->db->from('production_gmm_');
        $this->db->where('fecsis >= "'. $year .'-01-01 00:00:00"');
        $this->db->where('fecsis <= "'. $date .' 23:59:59"');
        if(isset($generation))
        {
            $this->db->where('generation = "'. $generation.'"');
        }
        if($prod_pmaneta)
        {
            $this->db->where('st_nueren = "NUEVO"');
            $this->db->where('generation <> "Consolidado"');
        }
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) 
            {
                $total_gmm = $row->total_amount;
            }
        }
        $query->free_result();

        return $total_gmm;
    }
    public function get_grl_congress_agents($date, $year)
    {
        //Obtener el mnumero de agentes con congreso Oro
        $total_agents_oro = 0;
        $total_agents_oro = $total_agents_oro + $this->termometro_generics_model->generic_congress_query_gmm($date,$year,array('Generación 3','Consolidado'),1100000);
        $total_agents_oro = $total_agents_oro + $this->termometro_generics_model->generic_congress_query_gmm($date,$year,array('Generación 2','Generación 1'),900000);

        //Obtener el mnumero de agentes con congreso Platino
        $total_agents_platinum = 0;
        $total_agents_platinum = $total_agents_platinum + $this->termometro_generics_model->generic_congress_query_gmm($date,$year,array('Generación 1','Generación 2','Generación 3','Consolidado'),1700000);

        //Obtener el mnumero de agentes con congreso Diamante
        $total_agents_diamond = 0;
        $total_agents_diamond = $total_agents_diamond + $this->termometro_generics_model->generic_congress_query_gmm($date,$year,array('Generación 1','Generación 2','Generación 3','Consolidado'),2700000);

        //Obtener el mnumero de agentes con congreso Consejo
        $total_agents_consul = 0;
        $total_agents_consul = $total_agents_consul + $this->termometro_generics_model->generic_congress_query_gmm($date,$year,array('Generación 1','Generación 2','Generación 3','Consolidado'),4900000);

        //Array Final
        $agents_congress = array(
            $total_agents_oro,
            $total_agents_platinum,
            $total_agents_diamond,
            $total_agents_consul
        );

        return $agents_congress;
    }
    public function get_grl_agentes_venta_nueva($year)
    {
        $sql = "select count(DISTINCT clave) as gmm_agente_vn from production_gmm_  where st_nueren = 'NUEVO' and year(fecsis) =". $year . ";";
        $query = $this->db->query($sql);
        $res = $query->result_array();

        return $res[0]['gmm_agente_vn'];
    }
    public function get_grl_primas_pagadas_iniciales($year)
    {
        $sql = "select sum(pmaneta) as gmm_pri_pag_inic from production_gmm_  where st_nueren = 'NUEVO' and year(fecsis) =". $year . ";";
        $query = $this->db->query($sql);
        $res = $query->result_array();

        return $res[0]['gmm_pri_pag_inic'];
    }
    public function get_grl_primas_ubicar($year)
    {
        $sql = "select sum(pmaubi) as gmm_sum_pri_ubi from production_gmm_  where year(fecsis) =". $year . ";";
        $query = $this->db->query($sql);
        $res = $query->result_array();

        return $res[0]['gmm_sum_pri_ubi'];
    }
    public function get_grl_asegurados_nuevos($year)
    {
        $sql = "select sum(n_aseg) as gmm_asegurados_nuevos from bona_sa where periodo like '%" . $year . "';";
        $query = $this->db->query($sql);
        $res = $query->result_array();

        return $res[0]['gmm_asegurados_nuevos'];
    }
    public function get_grl_puntos_productivos($year)
    {
        $sql = "select sum(ptos) as gmm_ptos_prod from bona_sa where periodo like '%" . $year . "';";
        $query = $this->db->query($sql);
        $res = $query->result_array();

        return $res[0]['gmm_ptos_prod'];
    }
    public function get_grl_ventas_tipo($year)
    {
        $sql2 = "SELECT (SELECT ingresos_generales FROM general_pays_gmm WHERE generation = \"Nuevo\" AND periodo = ".$year.") as venta_nueva, 
        (SELECT ingresos_generales FROM general_pays_gmm WHERE generation = \"Renovación\" AND periodo = ".$year.") as venta_renov from production_ limit 1;";
        $query = $this->db->query($sql2);
        $a2 = $query->result_array();
        $query->free_result();
        $gmm_ventas_final = array('', '');
        if($a2 != null){
            $gmm_ventas_final = array(
                $a2[0]['venta_nueva'],
                $a2[0]['venta_renov']
            );
        }
        return $gmm_ventas_final;
    }
    public function get_grl_produccion_inicial_ubi($year)
    {
        $sql = "select sum(pmaubi) as gmm_produccion_inicial_ubi from production_gmm_ where periodo like '%" . $year . "' and st_nueren = 'NUEVO' and generation != 'Consolidado';";
        $query = $this->db->query($sql);
        $res = $query->result_array();

        return $res[0]['gmm_produccion_inicial_ubi'];
    }
    public function get_grl_percs($year)
    {
        $sql = "select avg(indicador_porsini) as gmm_perc_conservacion, avg(porsini_real) as gmm_perc_sin_real, avg(porsini) as gmm_perc_sin_acot from bong_sa where periodo like '%" . $year . "';";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        $gmm_percs = array(
            'gmm_perc_conservacion' => $res[0]['gmm_perc_conservacion'],
            'gmm_perc_sin_real' => $res[0]['gmm_perc_sin_real'],
            'gmm_perc_sin_acot' => $res[0]['gmm_perc_sin_acot']
        );
        return $gmm_percs;
    }
    public function get_grl_agentes_productivos($date, $year)
    {
        $agentes_productivos = $this->termometro_generics_model->generic_productive_agent_query_gmm($date, $year, 150000, 'Generación 1') +
                               $this->termometro_generics_model->generic_productive_agent_query_gmm($date, $year, 150000, 'Generación 2') +
                               $this->termometro_generics_model->generic_productive_agent_query_gmm($date, $year, 250000, 'Generación 3') +
                               $this->termometro_generics_model->generic_productive_agent_query_gmm($date, $year, 300000, 'Generación 4') +
                               $this->termometro_generics_model->generic_productive_agent_query_gmm($date, $year, 300000, 'Consolidado')
        ;

        return $agentes_productivos;
    }
    public function get_grl_ingresos_general_amount($year, $generation = null)
    {
        $sql = "";
        if($generation != null)
        {
            $sql = "SELECT SUM(ingresos_generales) AS ingresos_generales FROM general_pays_agent_gmm WHERE generation = '".$generation."' AND periodo = '" . $year . "';";
        }
        else
        {
            $sql = "SELECT SUM(ingresos_generales) AS ingresos_generales FROM general_pays_agent_gmm WHERE periodo = '" . $year . "';";
        }
        
        $query = $this->db->query($sql);
        $res = $query->result_array();

        if (!empty($res)){
            return $res[0]['ingresos_generales'];
        } else {
            return 0;
        }
    }
    public function get_grl_ingresos($year)
    {
        $array_shell = array('ingresos_generales' => 0, 'ingresos_nuevo' => 0, 'ingresos_renov' => 0, 'primas_ubicar' => 0, 'primas_pagar' => 0, 'numero_asegurados' => 0);
        $array_values = array('Consolidado' => $array_shell, 'Generación 1' => $array_shell,'Generación 2' => $array_shell,'Generación 3' => $array_shell,'Generación 4' => $array_shell);
        $sql = "SELECT SUM(ingresos_generales) AS ingresos_generales, SUM(ingresos_nuevo) AS ingresos_nuevo, SUM(ingresos_renov) AS ingresos_renov, SUM(primas_ubicar) AS primas_ubicar, SUM(primas_pagar) AS primas_pagar,SUM(numero_asegurados) AS numero_asegurados, generation FROM general_pays_agent_gmm WHERE periodo = ".$year." GROUP BY generation;";
        $query = $this->db->query($sql);
        
        if($query->num_rows() > 0)
        {
            
            foreach ($query->result() as $row) 
            {
                switch ($row->generation) {
                    case 'Consolidado':
                        $array_values['Consolidado'] = array(
                            'ingresos_generales' => $row->ingresos_generales, 
                            'ingresos_nuevo'     => $row->ingresos_nuevo, 
                            'ingresos_renov'     => $row->ingresos_renov, 
                            'primas_ubicar'      => $row->primas_ubicar, 
                            'primas_pagar'       => $row->primas_pagar,
                            'numero_asegurados'  => $row->numero_asegurados
                        );
                        break;
                    case 'Generación 1':
                        $array_values['Generación 1'] = array(
                            'ingresos_generales' => $row->ingresos_generales, 
                            'ingresos_nuevo'     => $row->ingresos_nuevo, 
                            'ingresos_renov'     => $row->ingresos_renov, 
                            'primas_ubicar'      => $row->primas_ubicar, 
                            'primas_pagar'       => $row->primas_pagar,
                            'numero_asegurados'  => $row->numero_asegurados
                        );
                        break;
                    case 'Generación 2':
                        $array_values['Generación 2'] = array(
                            'ingresos_generales' => $row->ingresos_generales, 
                            'ingresos_nuevo'     => $row->ingresos_nuevo, 
                            'ingresos_renov'     => $row->ingresos_renov, 
                            'primas_ubicar'      => $row->primas_ubicar, 
                            'primas_pagar'       => $row->primas_pagar,
                            'numero_asegurados'  => $row->numero_asegurados
                        );
                        break;
                    case 'Generación 3':
                        $array_values['Generación 3'] = array(
                            'ingresos_generales' => $row->ingresos_generales, 
                            'ingresos_nuevo'     => $row->ingresos_nuevo, 
                            'ingresos_renov'     => $row->ingresos_renov, 
                            'primas_ubicar'      => $row->primas_ubicar, 
                            'primas_pagar'       => $row->primas_pagar,
                            'numero_asegurados'  => $row->numero_asegurados
                        );
                        break;
                    case 'Generación 4':
                        $array_values['Generación 4'] = array(
                            'ingresos_generales' => $row->ingresos_generales, 
                            'ingresos_nuevo'     => $row->ingresos_nuevo, 
                            'ingresos_renov'     => $row->ingresos_renov, 
                            'primas_ubicar'      => $row->primas_ubicar, 
                            'primas_pagar'       => $row->primas_pagar,
                            'numero_asegurados'  => $row->numero_asegurados
                        );
                        break;
                    default:
                        break;
                }
            }
        }
        $res = $query->result_array();
        return $array_values;
    }
    public function get_grl_congress($year)
    {
        $array_values = array('Oro' => 0,'Platino' => 0,'Diamante' => 0,'Consejo' => 0);
        $sql = "SELECT COUNT(congreso) AS congreso, congreso AS name FROM general_pays_agent_gmm WHERE periodo = ".$year." GROUP BY congreso;";
        $query = $this->db->query($sql);
        
        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) 
            {
                switch ($row->name) {
                    case 'Diamante':
                        $array_values['Diamante'] = $row->congreso;
                        break;
                    case 'Oro':
                        $array_values['Oro'] = $row->congreso;
                        break;
                    case 'Platino':
                        $array_values['Platino'] = $row->congreso;
                        break;
                    case 'Consejo':
                        $array_values['Consejo'] = $row->congreso;
                        break;
                    default:
                        break;
                }
            }
        }
        return $array_values;
    }

    public function get_grl_main_list($year)
    {
        $sql = "SELECT * FROM general_pays_agent_gmm WHERE periodo = ".$year.";";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res;
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
    public function get_grl_points_bonus_agent($id,$main_list)
    {
        foreach ($main_list as $row) 
        {
            if($row['id'] == $id)
            {
                if($row['primas_ubicar'] >= 120000 && $row['primas_ubicar'] < 200000)
                {
                    return 1;
                }
                if($row['primas_ubicar'] >= 200000 && $row['primas_ubicar'] < 290000)
                {
                    return 2;
                }
                if($row['primas_ubicar'] >= 290000 && $row['primas_ubicar'] < 400000)
                {
                    return 3;
                }
                if($row['primas_ubicar'] >= 400000)
                {
                    return 4;
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
        return false;
    }
    public function get_grl_gmm_array($main_list = array())
    {
        $array_values = array(
            'ingresos_generales'    => 0,
            'primas_ubicar'         => 0,
            'primas_pagar'          => 0,
            'numero_asegurados'     => 0,
            'produccion_inicial'    => 0,
            'ingresos_nuevo'        => 0,
            'ingresos_renov'        => 0,
            'agentes_productivos'   => 0,
            'ingresos_generation'   => $this->get_grl_ingresos_total($main_list),
            'agentes_congreso'      => $this->get_grl_congresos($main_list),
            'agentes_venta_nueva'   => $this->get_grl_agentes_venta_nueva_array($main_list)
        );
        foreach ($main_list as $row) {
            $array_values['primas_ubicar']      = $array_values['primas_ubicar'] + $row['primas_ubicar'];
            $array_values['primas_pagar']       = $array_values['primas_pagar'] + $row['primas_pagar'];
            $array_values['numero_asegurados']  = $array_values['numero_asegurados'] + $row['numero_asegurados'];
            $array_values['ingresos_generales'] = $array_values['ingresos_generales'] + $row['ingresos_generales'];
            $array_values['ingresos_nuevo']     = $array_values['ingresos_nuevo'] + $row['ingresos_nuevo'];
            $array_values['ingresos_renov']     = $array_values['ingresos_renov'] + $row['ingresos_renov'];
            if($row['generation'] != 'Consolidado')
            {
                $array_values['produccion_inicial'] = $array_values['produccion_inicial'] + $row['primas_ubicar'];
            }
            switch ($row['generation'])
            {
                case 'Generación 1':
                    if($row['primas_ubicar'] >= 150000)
                    {
                        $array_values['agentes_productivos'] = $array_values['agentes_productivos']+1;
                    }
                    break;
                case 'Generación 2':
                    if($row['primas_ubicar'] >= 150000)
                    {
                        $array_values['agentes_productivos'] = $array_values['agentes_productivos']+1;
                    }
                    break;
                case 'Generación 3':
                    if($row['primas_ubicar'] >= 250000)
                    {
                        $array_values['agentes_productivos'] = $array_values['agentes_productivos']+1;
                    }
                    break;
                case 'Generación 4':
                    if($row['primas_ubicar'] >= 300000)
                    {
                        $array_values['agentes_productivos'] = $array_values['agentes_productivos']+1;
                    }
                    break;
                case 'Consolidado':
                    if($row['primas_ubicar'] >= 300000)
                    {
                        $array_values['agentes_productivos'] = $array_values['agentes_productivos']+1;
                    }
                    break;
                default:
                    break;
            }
        }
        return $array_values;
    }
    








    // ***  FUNCIONES PARA LA VISTA DETALLE DE VIDA  ***
    /*
    public function get_value_by_period($type, $year, $agent_id)
    {
        return array(
            $this->termometro_generics_model->generic_value_gmm($type,"1".$year,$agent_id),
            $this->termometro_generics_model->generic_value_gmm($type,"2".$year,$agent_id),
            $this->termometro_generics_model->generic_value_gmm($type,"3".$year,$agent_id)
        );
    }
    public function get_value_in_table($type,$year,$value_vertical,$value_horizontal)
    {
        return array(
            $this->termometro_generics_model->find_value_in_table($type, 'gmm', $year, $value_vertical[0], $value_horizontal[0], $agent_generation),
            $this->termometro_generics_model->find_value_in_table($type, 'gmm', $year, $value_vertical[1], $value_horizontal[1], $agent_generation),
            $this->termometro_generics_model->find_value_in_table($type, 'gmm', $year, $value_vertical[2], $value_horizontal[2], $agent_generation)
        );
    }

    public function get_detail_perce_bono_rentabilidad($prima_ubicar, $perc_bono_1st_year, $res_sin_real, $res_sin_acot, $year, $agent_id)
    {
        return array(
            $this->termometro_generics_model->generic_value_perc_bono_rentabilidad_gmm($prima_ubicar[0], $perc_bono_1st_year[0], $res_sin_real[0], $res_sin_acot[0], "1".$year, $agent_id),
            $this->termometro_generics_model->generic_value_perc_bono_rentabilidad_gmm($prima_ubicar[1], $perc_bono_1st_year[1], $res_sin_real[1], $res_sin_acot[1], "2".$year, $agent_id),
            $this->termometro_generics_model->generic_value_perc_bono_rentabilidad_gmm($prima_ubicar[2], $perc_bono_1st_year[2], $res_sin_real[2], $res_sin_acot[2], "3".$year, $agent_id)
        );
    }
    */
    















    public function get_detail_primas_ubicar($year,$agent_id)
    {
        $primas_ubicar = array(
            $this->termometro_generics_model->generic_value_primas_ubicar_gmm("1".$year, $agent_id),
            $this->termometro_generics_model->generic_value_primas_ubicar_gmm("2".$year, $agent_id),
            $this->termometro_generics_model->generic_value_primas_ubicar_gmm("3".$year, $agent_id)
        );
        
        return $primas_ubicar;
    }
    
    public function get_detail_primas_pagar($year,$agent_id)
    {
        $primas_pagar = array(
            $this->termometro_generics_model->generic_value_primas_pagar_gmm("1".$year, $agent_id),
            $this->termometro_generics_model->generic_value_primas_pagar_gmm("2".$year, $agent_id),
            $this->termometro_generics_model->generic_value_primas_pagar_gmm("3".$year, $agent_id)
        );
        
        return $primas_pagar;
    }
    public function get_detail_cartera_estimada($year,$agent_id)
    {
        $cartera_estimada = array(
            $this->termometro_generics_model->generic_value_cartera_estimada_gmm("1".$year, $agent_id),
            $this->termometro_generics_model->generic_value_cartera_estimada_gmm("2".$year, $agent_id),
            $this->termometro_generics_model->generic_value_cartera_estimada_gmm("3".$year, $agent_id)
        );
        
        return $cartera_estimada;
    }
    public function get_detail_cartera_real($year,$agent_id)
    {
        $cartera_estimada = array(
            $this->termometro_generics_model->generic_value_cartera_real_gmm("1".$year, $agent_id),
            $this->termometro_generics_model->generic_value_cartera_real_gmm("2".$year, $agent_id),
            $this->termometro_generics_model->generic_value_cartera_real_gmm("3".$year, $agent_id)
        );
        
        return $cartera_estimada;
    }
    public function get_detail_nuevos_asegurados($year,$agent_id)
    {
        $asegurados_nuevos = array(
            $this->termometro_generics_model->generic_value_asegurado_nuevo_gmm("1".$year, $agent_id),
            $this->termometro_generics_model->generic_value_asegurado_nuevo_gmm("2".$year, $agent_id),
            $this->termometro_generics_model->generic_value_asegurado_nuevo_gmm("3".$year, $agent_id)
        );
        
        return $asegurados_nuevos;
    }
    public function get_detail_faltante_nuevos_asegurados($year,$agent_id)
    {
        $faltante_asegurados_nuevos = array(
            $this->termometro_generics_model->generic_value_asegurado_nuevo_gmm("1".$year, $agent_id),
            $this->termometro_generics_model->generic_value_asegurado_nuevo_gmm("2".$year, $agent_id),
            $this->termometro_generics_model->generic_value_asegurado_nuevo_gmm("3".$year, $agent_id)
        );
        
        return $faltante_asegurados_nuevos;
    }
    public function get_detail_comisiones_directas($primas_ubicar)
    {
        $comisiones_directas = array(
            $primas_ubicar[0] * 0.25,
            $primas_ubicar[1] * 0.25,
            $primas_ubicar[2] * 0.25
        );
        
        return $comisiones_directas;
    }
    public function get_detail_perce_bono_primer_anio($year, $primas_ubicar,$generation,$asegurados_nuevos)
    {
        return array(
            $this->termometro_generics_model->generic_value_perce_bono_gmm($year, $primas_ubicar[0],$generation,$asegurados_nuevos[0]),
            $this->termometro_generics_model->generic_value_perce_bono_gmm($year, $primas_ubicar[1],$generation,$asegurados_nuevos[1]),
            $this->termometro_generics_model->generic_value_perce_bono_gmm($year, $primas_ubicar[2],$generation,$asegurados_nuevos[2])
        );
    }
    public function get_detail_bono_primer_anio($cartera_conservada, $generation, $perce_bono, $primas_pagar)
    {
        return array(
            $this->termometro_generics_model->generic_value_bono_gmm($cartera_conservada[0], $generation, $perce_bono[0], $primas_pagar[0]),
            $this->termometro_generics_model->generic_value_bono_gmm($cartera_conservada[1], $generation, $perce_bono[1], $primas_pagar[1]),
            $this->termometro_generics_model->generic_value_bono_gmm($cartera_conservada[2], $generation, $perce_bono[2], $primas_pagar[2])
        );
    }
    public function get_detail_primas_netas($year,$agent_id)
    {
        return array(
            $this->termometro_generics_model->generic_value_primas_netas_gmm($agent_id, "1".$year),
            $this->termometro_generics_model->generic_value_primas_netas_gmm($agent_id, "2".$year),
            $this->termometro_generics_model->generic_value_primas_netas_gmm($agent_id, "3".$year)
        );
    }
    public function get_detail_siniestros_pagados_real($year,$agent_id)
    {
        return array(
            $this->termometro_generics_model->generic_value_siniestralidad_real_gmm($agent_id, "1".$year),
            $this->termometro_generics_model->generic_value_siniestralidad_real_gmm($agent_id, "2".$year),
            $this->termometro_generics_model->generic_value_siniestralidad_real_gmm($agent_id, "3".$year)
        );
    }
    public function get_detail_siniestros_pagados_acot($year,$agent_id)
    {
        return array(
            $this->termometro_generics_model->generic_value_siniestralidad_acot_gmm($agent_id, "1".$year),
            $this->termometro_generics_model->generic_value_siniestralidad_acot_gmm($agent_id, "2".$year),
            $this->termometro_generics_model->generic_value_siniestralidad_acot_gmm($agent_id, "3".$year)
        );
    }
    public function get_detail_result_siniestralidad_real($year,$agent_id)
    {
        return array(
            $this->termometro_generics_model->generic_value_result_siniestralidad_real_gmm($agent_id, "1".$year) * 100,
            $this->termometro_generics_model->generic_value_result_siniestralidad_real_gmm($agent_id, "2".$year) * 100,
            $this->termometro_generics_model->generic_value_result_siniestralidad_real_gmm($agent_id, "3".$year) * 100
        );
    }
    public function get_detail_result_siniestralidad_acot($year,$agent_id)
    {
        return array(
            $this->termometro_generics_model->generic_value_result_siniestralidad_acot_gmm($agent_id, "1".$year) * 100,
            $this->termometro_generics_model->generic_value_result_siniestralidad_acot_gmm($agent_id, "2".$year) * 100,
            $this->termometro_generics_model->generic_value_result_siniestralidad_acot_gmm($agent_id, "3".$year) * 100
        );
    }
    public function get_detail_cartera_conservada($year,$agent_id)
    {
        return array(
            $this->termometro_generics_model->generic_value_cartera_conservada_gmm($agent_id, "1".$year),
            $this->termometro_generics_model->generic_value_cartera_conservada_gmm($agent_id, "2".$year),
            $this->termometro_generics_model->generic_value_cartera_conservada_gmm($agent_id, "3".$year)
        );
    }
    public function get_detail_comision_cartera($cartera_conservada)
    {
        return array(
            $cartera_conservada[0] * 0.15,
            $cartera_conservada[1] * 0.15,
            $cartera_conservada[2] * 0.15
        );
    }
    public function get_detail_numero_asegurados($year,$agent_id)
    {
        return array(
            $this->termometro_generics_model->generic_value_numero_asegurados_nuevos_gmm($agent_id, "1".$year),
            $this->termometro_generics_model->generic_value_numero_asegurados_nuevos_gmm($agent_id, "2".$year),
            $this->termometro_generics_model->generic_value_numero_asegurados_nuevos_gmm($agent_id, "3".$year)
        );
    }
    public function get_detail_perce_bono_rentabilidad($prima_ubicar, $perc_bono_1st_year, $res_sin_real, $res_sin_acot, $year, $agent_id)
    {
        return array(
            $this->termometro_generics_model->generic_value_perc_bono_rentabilidad_gmm($prima_ubicar[0], $perc_bono_1st_year[0], $res_sin_real[0], $res_sin_acot[0], "1".$year, $agent_id),
            $this->termometro_generics_model->generic_value_perc_bono_rentabilidad_gmm($prima_ubicar[1], $perc_bono_1st_year[1], $res_sin_real[1], $res_sin_acot[1], "2".$year, $agent_id),
            $this->termometro_generics_model->generic_value_perc_bono_rentabilidad_gmm($prima_ubicar[2], $perc_bono_1st_year[2], $res_sin_real[2], $res_sin_acot[2], "3".$year, $agent_id)
        );
    }
    public function get_detail_bono_rentabilidad($cartera_conservada, $perc_bono_rentabilidad) 
    {
        return array(
            $cartera_conservada[0] * $perc_bono_rentabilidad[0],
            $cartera_conservada[1] * $perc_bono_rentabilidad[1],
            $cartera_conservada[2] * $perc_bono_rentabilidad[2],
        );
    }
    public function get_detail_faltante_bono_rentabilidad($year,$agent_id) //???
    {
        return array(0,0,0);
    }
    public function get_detail_bono_rentabilidad_no_ganado($cartera_conservada, $bono_rentabilidad, $year,$agent_id) 
    {
        return array(
            ($cartera_conservada[0] * 0.08) - $bono_rentabilidad[0],
            ($cartera_conservada[1] * 0.08) - $bono_rentabilidad[1],
            ($cartera_conservada[2] * 0.08) - $bono_rentabilidad[2],
        );
    }
    public function get_detail_suma_ingresos($bono_1st_year, $comisiones_directas, $comision_cartera, $bono_rentabilidad)
    {
        return array(
            $this->termometro_generics_model->generic_value_suma_ingresos_gmm($bono_1st_year[0], $comisiones_directas[0], $comision_cartera[0], $bono_rentabilidad[0]),
            $this->termometro_generics_model->generic_value_suma_ingresos_gmm($bono_1st_year[1], $comisiones_directas[1], $comision_cartera[1], $bono_rentabilidad[1]),
            $this->termometro_generics_model->generic_value_suma_ingresos_gmm($bono_1st_year[2], $comisiones_directas[2], $comision_cartera[2], $bono_rentabilidad[2]),
        );
    }
    public function get_detail_congreso($prima_ubicar, $year,$agent_id) 
    {
        $total_priubi = $prima_ubicar[0] + $prima_ubicar[1] + $prima_ubicar[2];
        return $this->termometro_generics_model->generic_value_congreso_gmm($total_priubi, $year);
    }
    public function get_detail_congreso_siguiente($congreso, $year, $agent_id) 
    {
        return $this->termometro_generics_model->generic_value_siguente_congreso_gmm($congreso);
    }
    public function get_detail_faltante_produccion_inicial($prima_ubicar, $prox_congreso, $year,$agent_id) 
    {
        $total_priubi = $prima_ubicar[0] + $prima_ubicar[1] + $prima_ubicar[2];
        return $this->termometro_generics_model->generic_value_faltante_produccion_gmm($prox_congreso, $total_priubi, $year);
    }

    public function get_detail_agente_productivo($prima_ubicar, $generacion, $year,$agent_id) 
    {
        $total_priubi = $prima_ubicar[0] + $prima_ubicar[1] + $prima_ubicar[2];
        return $this->termometro_generics_model->generic_value_es_productivo_gmm($generacion, $total_priubi,  $year);
    }
    public function get_detail_ingresos_totales($suma_ingresos) // Esta se puede sacar con la suma de ingresos
    {
        return number_format(($suma_ingresos[0] + $suma_ingresos[1] + $suma_ingresos[2]),2);
    }


    //Funciones de los arrays de los poupups de los datos 
    public function redirect_popup_gmm($poliza)
    {
        $sql = 'SELECT work_order.id AS url_id FROM work_order JOIN policies ON policies.id = work_order.policy_id WHERE policies.uid = "'.$poliza.'";';
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
    public function get_popup_data_gmm($agent_id, $periodo, $type, $agent_values = null, $index_periodo = null,$where_array = null)
    {
        $this->db->select('poliza_ AS poliza, asegurado, st_nueren, pmaubi AS prima_ubicar, primaafe AS prima_pagar');
        $this->db->from('production_gmm_');
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
                    case 'prima_ubicar':
                        if($row->prima_ubicar != null)
                        {
                            $total = $total + $row->prima_ubicar;
                            $data  = array($this->redirect_popup_gmm($row->poliza), $row->asegurado, "$ ".number_format($row->prima_ubicar,2));
                        }
                        break;
                    case 'prima_pagar':
                        if($row->prima_pagar != null && $row->st_nueren == 'NUEVO')
                        {
                            $total = $total + $row->prima_pagar;
                            $data  = array($this->redirect_popup_gmm($row->poliza), $row->asegurado, "$ ".number_format($row->prima_pagar,2));
                        }
                        break;
                    case 'comisiones_directas':
                        if($row->prima_ubicar != null)
                        {
                            $value = $row->prima_ubicar * 0.25;
                            $total = $total + $value;
                            $data  = array($this->redirect_popup_gmm($row->poliza), $row->asegurado, "$ ".number_format($value,2));
                        }
                        break;
                    case 'bono_primer_anio':
                        if($row->prima_pagar != null && $row->st_nueren == 'NUEVO')
                        {
                            $value = $row->prima_pagar * ($agent_values['perce_bono_primer_anio'][$index_periodo]/100);
                            $total = $total + $value;
                            if($value >0)
                            {
                                $data  = array($this->redirect_popup_gmm($row->poliza), $row->asegurado, "$ ".number_format($value,2));
                            }
                        }
                        
                        break;
                    case 'cartera_conservada':
                        if ($row->st_nueren == 'RENOVADO')
                        {
                            $total = $total + $row->prima_pagar;
                            $data  = array($this->redirect_popup_gmm($row->poliza), $row->asegurado, "$ ".number_format($row->prima_pagar,2));
                        }
                        break;
                    case 'comision_cartera':
                        if ($row->st_nueren == 'RENOVADO')
                        {
                            $value = $row->prima_pagar * 0.15;
                            $total = $total + $value;
                            $data  = array($this->redirect_popup_gmm($row->poliza), $row->asegurado, "$ ".number_format($value,2));
                        }
                        break;
                    case 'bono_rentabilidad':
                        if ($row->st_nueren == 'RENOVADO')
                        {
                            $value = $row->prima_pagar * ($agent_values['perce_bono_rentabilidad'][$index_periodo]/100);
                            $total = $total + $value;
                            $data  = array($this->redirect_popup_gmm($row->poliza), $row->asegurado, "$ ".number_format($value,2));
                        }
                        break;
                    case 'suma_ingresos':
                        if ($agent_values['bono_primer_anio'][$index_periodo] == 0)
                        {
                            if ($row->st_nueren == 'RENOVADO')
                            {
                                $total = $total + $row->prima_pagar * 0.15;
                                $data  = array($this->redirect_popup_gmm($row->poliza), $row->asegurado, "$ ".number_format($row->prima_pagar,2));
                            }
                            else
                            {
                                $value = $row->prima_ubicar * 0.25;
                                $total = $total + $value;
                                $data  = array($this->redirect_popup_gmm($row->poliza), $row->asegurado, "$ ".number_format($value,2));
                            }
                        }
                        else
                        {
                            
                            if ($row->st_nueren == 'RENOVADO')
                            {
                                $value_bono_rent = $row->prima_pagar * ($agent_values['perce_bono_rentabilidad'][$index_periodo]/100);
                                $value_comision  = $row->prima_pagar * 0.15;
                                $total = $total + $value_bono_rent;
                                $total = $total + $value_comision;
                                array_push($array_values, array($this->redirect_popup_gmm($row->poliza), $row->asegurado, "$ ".number_format($value_bono_rent,2)));
                                array_push($array_values, array($this->redirect_popup_gmm($row->poliza), $row->asegurado, "$ ".number_format($value_comision,2)));
                            }
                            else
                            {
                                $value_bono_anio = $row->prima_pagar * ($agent_values['perce_bono_primer_anio'][$index_periodo]/100);
                                $value = $row->prima_ubicar * 0.25;
                                $total = $total + $value_bono_anio;
                                $total = $total + $value;
                                array_push($array_values, array($this->redirect_popup_gmm($row->poliza), $row->asegurado, "$ ".number_format($value,2)));
                                array_push($array_values, array($this->redirect_popup_gmm($row->poliza), $row->asegurado, "$ ".number_format($value_bono_anio,2)));
                            }
                        }
                        break;
                    default:
                        break;
                }
                array_push($array_values, $data);
            }
        }
        array_push($array_values, array('Total',"$ ".number_format($total,2)));
        return array('heads' => array('Poliza', 'Asegurado', 'Cantidad'), 'values' => $array_values);
    } 

    public function get_popup_data_ingresos_totales($agent_id, $agent_values, $year)
    {
        $this->db->select('periodo, poliza_ AS poliza, asegurado, st_nueren, pmaubi AS prima_ubicar, primaafe AS prima_pagar');
        $this->db->from('production_gmm_');
        $this->db->where('agent_id', $agent_id);
        $this->db->like('periodo', $year);
        $query = $this->db->get();
        $total = 0;
        $array_values = array();
        if($query->num_rows > 0)
        {
            foreach ($query->result() as $row) 
            {
                $index = 0;
                switch ($row->periodo) {
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
                error_log(print_r($index,true));
                if ($agent_values['bono_primer_anio'][$index] == 0)
                {
                    if ($row->st_nueren == 'RENOVADO')
                    {
                        $total = $total + $row->prima_pagar * 0.15;
                        array_push($array_values, array($this->redirect_popup_gmm($row->poliza), $row->asegurado, "$ ".number_format($row->prima_pagar,2), 'Comisión Cartera'));
                    }
                    else
                    {
                        $value = $row->prima_ubicar * 0.25;
                        $total = $total + $value;
                        array_push($array_values, array($this->redirect_popup_gmm($row->poliza), $row->asegurado, "$ ".number_format($value,2),'Comisión Directa'));
                    }
                }
                else
                {
                    if ($row->st_nueren == 'RENOVADO')
                    {
                        $value_bono_rent = ($agent_values['perce_bono_rentabilidad'][$index]/100);
                        $value_comision  = $row->prima_pagar * 0.15;
                        $total = $total + $value_bono_rent;
                        $total = $total + $value_comision;
                        array_push($array_values, array($this->redirect_popup_gmm($row->poliza), $row->asegurado, "$ ".number_format($value_bono_rent,2), 'Bono de Rentabilidad'));
                        array_push($array_values, array($this->redirect_popup_gmm($row->poliza), $row->asegurado, "$ ".number_format($value_comision,2), 'Comisión Cartera'));
                    }
                    else
                    {
                        $value_bono_anio = $row->prima_pagar * ($agent_values['perce_bono_primer_anio'][$index]/100);
                        $value = $row->prima_ubicar * 0.25;
                        $total = $total + $value_bono_anio;
                        $total = $total + $value;
                        array_push($array_values, array($this->redirect_popup_gmm($row->poliza), $row->asegurado, "$ ".number_format($value,2),'Comisión Directa'));
                        array_push($array_values, array($this->redirect_popup_gmm($row->poliza), $row->asegurado, "$ ".number_format($value_bono_anio,2),'Bono de Primer Año'));
                    }
                }
            }
        }
        array_push($array_values, array('Total','',"$ ".number_format($total,2),''));
        return array('heads' => array('Poliza', 'Asegurado', 'Cantidad', 'Tipo'), 'values' => $array_values);
    }

}
/* End of file termometro_model.php */
/* Location: ./application/modules/termometro/models/termometro_model.php */
?>