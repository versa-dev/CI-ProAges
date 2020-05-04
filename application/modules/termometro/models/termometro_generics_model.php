<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/** 
 * Termometro de Ventas - Modelo de Datos
 * 
 * @author     Jesus Castilla & José Gilberto Pérez Molina
 * Date:       December, 2018
 * Locaion:    Veracruz, Mexico
 * Mail:       jesuscv1821@gmail.com - jgilbertopmolina@gmail.com
 */


class termometro_generics_model extends CI_Model{
	public function __construct()
	{
		parent::__construct();
	}
    // ***  FUNCIONES GENERICAS USADAS PARA LA VISTA GENERAL  ***
    public function get_generic_value_array($main_list = array(), $index, $agent_id, $is_prod = false)
    {
        $data = 0;
        foreach ($main_list as $row ) {
           if($row['agent_id'] == $agent_id)
           {
                $data = $row[$index];
           }
        }
        if ($data != null)
        {
            return $data;
        }
        else
        {
            return 0;
        }
    }
    public function get_generic_value_array_prod($main_list = array(), $index, $agent_id, $is_prod = false)
    {
        $data = 0;
        foreach ($main_list as $row ) {
           if($row['agent_id'] == $agent_id && $row['generation'] != "Consolidado")
           {
                $data = $row[$index];
           }
        }
        if ($data != null)
        {
            return $data;
        }
        else
        {
            return 0;
        }
    }
    public function get_congress_agent($main_list = array(), $agent_id)
    {
        $data = '';
        foreach ($main_list as $row ) {
           if($row['agent_id'] == $agent_id)
           {
                $data = $row['congreso'];
           }
        }
       return $data;
    }
    public function get_name_agent($agent_id)
    {
        $sql3  = "SELECT CONCAT(name, ' ', lastnames) AS name, company_name FROM agents JOIN users ON agents.user_id = users.id WHERE agents.id = ".$agent_id;
        $query = $this->db->query($sql3);
        $res   = $query->result_array();
        if($res[0]['name']!= ' ')
        {
            return $res[0]['name'];
        }
        else
        {
            return $res[0]['company_name'];
        }

    }
    public function generic_value_ingresos($main_list = array())
    {
        $ingresos_total = array('Generación 1' => 0,'Generación 2' => 0,'Generación 3' => 0,'Generación 4' => 0,'Consolidado' => 0);
        foreach ($main_list as $row) 
        {
            switch ($row['generation'])
             {
                case 'Generación 1':
                    $ingresos_total['Generación 1'] = $ingresos_total['Generación 1'] + $row['ingresos_generales'];
                    break;
                case 'Generación 2':
                    $ingresos_total['Generación 2'] = $ingresos_total['Generación 2'] + $row['ingresos_generales'];
                    break;
                case 'Generación 3':
                    $ingresos_total['Generación 3'] = $ingresos_total['Generación 3'] + $row['ingresos_generales'];
                    break;
                case 'Generación 4':
                    $ingresos_total['Generación 4'] = $ingresos_total['Generación 4'] + $row['ingresos_generales'];
                    break;
                case 'Consolidado':
                    $ingresos_total['Consolidado'] = $ingresos_total['Consolidado'] + $row['ingresos_generales'];
                    break;
                default:
                    # code...
                    break;
            }
        }
        return $ingresos_total;
    }
    public function generic_value_congreso($main_list = array())
    {
        $array_values = array('Oro' => 0,'Platino' => 0,'Diamante' => 0,'Consejo' => 0);
        foreach ($main_list as $row) 
        {
            switch ($row['congreso']) 
            {
                case 'Diamante':
                    $array_values['Diamante']++;
                    break;
                case 'Oro':
                    $array_values['Oro']++;
                    break;
                case 'Platino':
                    $array_values['Platino']++;
                    break;
                case 'Consejo':
                    $array_values['Consejo']++;
                    break;
                default:
                    break;
            }
        }
        return $array_values;
    }
    public function generic_value_ingresos_tipo($main_list = array(), $index)
    {
        $array_values = 0;
        foreach ($main_list as $row) 
        {
            if($row[$index] > 0)
            {
                $array_values = $array_values + $row[$index];
            }
        }
        return $array_values;
    }



    // ***  FUNCIONES GENERICAS USADAS PARA LA VISTA GENERAL DE VIDA  ***
    public function generic_congress_query_vida($date, $year, $generations, $amount)
    {
        $first_gen = array_shift($generations);
        $total_agents_congresss = 0;
        $sql_gens = "AND `generation` = '" . $first_gen . "'";
        foreach ($generations as $generation ) 
        {
            $sql_gens = $sql_gens."OR `generation` = '".$generation."' ";
        }
        $sql = "SELECT Count(*) as total_agents
                FROM (SELECT DISTINCT(agent_id) AS agent_id, SUM(impri)
                      FROM (`production_`)
                      WHERE `femovibo` >= '".$year."-01-01 00:00:00'
                      AND `femovibo` <= '".$year."-01-01 00:00:00' 
                      ".$sql_gens." 
                      GROUP BY `agent_id`
                      HAVING sum(impri) >= ".$amount.") AS a
                JOIN (SELECT DISTINCT(agent_id),sum(neg_camp_mas_pai)
                      FROM business_
                      WHERE  periodo LIKE '%".$year."'
                      GROUP BY agent_id
                      HAVING sum(neg_camp_mas_pai)>20) AS b
                ON a.agent_id = b.agent_id;"
        ;

        $query = $this->db->query($sql);
        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) 
            {
                $total_agents_congresss = $row->total_agents;
            }
        }
        $query->free_result();

        return $total_agents_congresss;
    }
    public function generic_congress_query_gmm($date,$year, $generations, $amount)
    {
        $first_gen = array_shift($generations);
        $total_agents_congresss = 0;
        $sql_gens = "AND `generation` = '" . $first_gen . "' ";
        foreach ($generations as $generation ) 
        {
            $sql_gens = $sql_gens."OR `generation` = '".$generation."' ";
        }
        $sql = "SELECT Count(*) as total_agents
                FROM (SELECT DISTINCT(agent_id) AS agent_id, SUM(pmaneta)
                      FROM (`production_gmm_`)
                      WHERE `fecsis` >= '".$year."-01-01 00:00:00'
                      AND `fecsis` <= '".$year."-01-01 00:00:00' 
                      ".$sql_gens." 
                      GROUP BY `agent_id`
                      HAVING sum(pmaneta) >= 1100000) AS a
                JOIN (SELECT DISTINCT(agent_id),sum(neg_camp_mas_pai)
                      FROM business_
                      WHERE  periodo LIKE '%".$year."'
                      GROUP BY agent_id
                      HAVING sum(neg_camp_mas_pai)>20) AS b
                ON a.agent_id = b.agent_id;"
        ;
        $query = $this->db->query($sql);
        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) 
            {
                $total_agents_congresss = $row->total_agents;
            }
        }
        $query->free_result();
        return $total_agents_congresss;
    }
    public function generic_productive_agent_query_gmm($date, $year, $amount, $generation)
    {
        $this->db->select('sum(pmaubi) as gmm_pri_pag_inic');
        $this->db->from('production_gmm_');
        $this->db->where('st_nueren', 'NUEVO');
        $this->db->where('fecsis >= "'. $year .'-01-01 00:00:00"');
        $this->db->where('fecsis <= "'. $date .' 23:59:59"');
        $this->db->where('generation = "'. $generation .'"');
        $this->db->group_by('agent_id');
        $this->db->having('sum(pmaubi) >= '. $amount);
        $query = $this->db->get();

        return $query->num_rows();
    }

    // ***  FUNCIONES GENERICAS USADAS PARA LA VISTA DE DETALLE DE VIDA  ***
    public function generic_value_cartera_real_period($id_agent, $periodo, $comision_cartera = false)
    {
        $cartera = 0;
        $this->db->select('apri, impri, po_pag1_da as perce');
        $this->db->from('production_');
        $this->db->where('agent_id', $id_agent);
        $this->db->where('periodo', $periodo);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) 
            {
                if($row->apri > 1)
                {   
                    if($comision_cartera)
                    {
                        $cartera = $cartera + (($row->perce/100)*($row->impri))*0.08;
                    }
                    else
                    {
                        $cartera = $cartera + ($row->perce/100)*($row->impri);
                    }
                    
                }
            }
        }
        return $cartera;
    }
    public function generic_value_prima_ubicar_period($id_agent,$periodo)
    {
        $prima_ubicar = 0;
        $this->db->select('apri, impri, po_ubi1_da as prima_ubicada');
        $this->db->from('production_');
        $this->db->where('agent_id', $id_agent);
        $this->db->where('periodo', $periodo);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) 
            {
                if($row->apri == 1)
                {
                    $prima_ubicar = $prima_ubicar + ($row->prima_ubicada/100)*($row->impri);
                }
            }
        }

        return $prima_ubicar;
    }
    public function generic_value_prima_ubicar_ingreso($id_agent,$periodo, $ingresos_flag)
    {
        $prima_ubicar = 0;
        $this->db->select('apri, impri, po_ubi1_da as prima_ubicada');
        $this->db->from('production_');
        $this->db->where('agent_id', $id_agent);
        $this->db->where('periodo', $periodo);
        if($ingresos_flag)
        {
            $this->db->where('apri == 1');
        }
        else
        {
            $this->db->where('apri > 1');
        }
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) 
            {
                if($row->apri == 1)
                {
                    $prima_ubicar = $prima_ubicar + ($row->prima_ubicada/100)*($row->impri);
                }
            }
        }

        return $prima_ubicar;
    }
    public function generic_value_prima_pago_period($id_agent, $periodo)
    {
        $prima_pago = 0;
        $this->db->select('apri, impri, po_pag1_da as prima_pagar');
        $this->db->from('production_');
        $this->db->where('agent_id', $id_agent);
        $this->db->where('periodo', $periodo);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) 
            {
                if($row->apri == 1)
                {
                    $prima_pago = $prima_pago + ($row->prima_pagar/100)*($row->impri);
                }
            }
        }
        return $prima_pago;
    }
    public function generic_value_bussiness_vida_period($id_agent,$periodo)
    {
        $business = 0;
        $this->db->select('sum(neg_camp_mas_pai) as total_amount');
        $this->db->from('business_');
        $this->db->where('agent_id', $id_agent);
        $this->db->where('periodo', $periodo);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) 
            {
                $business = $row->total_amount;
            }
        }
        return $business;
    }
    public function generic_value_conservacion($id_agent,$periodo)
    {
        $conservation = 0;
        $conservation_final = 0;
        $this->db->select('SUM(cons_real1) as cons_real1, SUM(cons_acot1) as cons_acot1,  SUM(prima1) as prima1');
        $this->db->from('conservation_');
        $this->db->where('agent_id', $id_agent);
        $this->db->where('periodo', $periodo);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) 
            {
                if($row->cons_acot1 == null)
                {
                    $row->cons_acot1 = 0;
                }
                if($row->prima1 == null) {
                    $conservation = 0;
                }
                else
                {
                    $conservation = ($row->cons_real1 + $row->cons_acot1) / $row->prima1;
                }
            }
            $conservation_final = ($conservation / $query->num_rows())*100;
        }
        return $conservation_final;
    }
    public function generic_value_perce_bono($generation, $prima_ubicar, $negocios, $faltante_flag, $year )
    {
        //Es aquí
        $faltante = 0;
        $bono_perce = 0;

        $priubi_ini_min = (array) json_decode($this->generic_value_get_pai_data($year, 'bono_1st_year_perc', 'vida'));

        if($prima_ubicar >= $priubi_ini_min[0] &&  $prima_ubicar < $priubi_ini_min[1])
        {
            if($negocios >= 5 && $negocios < 7)
            {
                $bono_perce = 10;
            }
            if($negocios >= 7 && $negocios < 9)
            {
                $bono_perce = 15;
            }
            if($negocios >= 9)
            {
                $bono_perce = 17.5;
            }
            $faltante = $priubi_ini_min[1] - $prima_ubicar;
        }
        if($prima_ubicar >= $priubi_ini_min[1] &&  $prima_ubicar < $priubi_ini_min[2])
        {
            if($negocios >= 5 && $negocios < 7)
            {
                $bono_perce = 13;
            }
            if($negocios >= 7 && $negocios < 9)
            {
                $bono_perce = 17.5;
            }
            if($negocios >= 9)
            {
                $bono_perce = 20;
            }
            $faltante = $priubi_ini_min[2] - $prima_ubicar;
        }
        if($prima_ubicar >= $priubi_ini_min[2] &&  $prima_ubicar < $priubi_ini_min[3])
        {
            if($negocios >= 5 && $negocios < 7)
            {
                $bono_perce = 16;
            }
            if($negocios >= 7 && $negocios < 9)
            {
                $bono_perce = 20;
            }
            if($negocios >= 9)
            {
                $bono_perce = 22.5;
            }
            $faltante = $priubi_ini_min[3] - $prima_ubicar;
        }
        if($prima_ubicar >= $priubi_ini_min[3] &&  $prima_ubicar < $priubi_ini_min[4])
        {
            if($negocios >= 5 && $negocios < 7)
            {
                $bono_perce = 19;
            }
            if($negocios >= 7 && $negocios < 9)
            {
                $bono_perce = 22.5;
            }
            if($negocios >= 9)
            {
                $bono_perce = 25;
            }
            $faltante = $priubi_ini_min[4] - $prima_ubicar;
        }
        if($prima_ubicar >= $priubi_ini_min[4] &&  $prima_ubicar < $priubi_ini_min[5])
        {
            if($negocios >= 5 && $negocios < 7)
            {
                $bono_perce = 26;
            }
            if($negocios >= 7 && $negocios < 9)
            {
                $bono_perce = 30;
            }
            if($negocios >= 9)
            {
                $bono_perce = 32.5;
            }
            $faltante = $priubi_ini_min[5] - $prima_ubicar;
        }
        if($prima_ubicar >= $priubi_ini_min[5] &&  $prima_ubicar < $priubi_ini_min[6])
        {
            if($negocios >= 5 && $negocios < 7)
            {
                $bono_perce = 28;
            }
            if($negocios >= 7 && $negocios < 9)
            {
                $bono_perce = 32.5;
            }
            if($negocios >= 9)
            {
                $bono_perce = 36;
            }
            $faltante = $priubi_ini_min[6] - $prima_ubicar;
        }
        if($prima_ubicar >= $priubi_ini_min[4] &&  $prima_ubicar < $priubi_ini_min[5])
        {
            if($negocios >= 5 && $negocios < 7)
            {
                $bono_perce = 26;
            }
            if($negocios >= 7 && $negocios < 9)
            {
                $bono_perce = 30;
            }
            if($negocios >= 9)
            {
                $bono_perce = 32.5;
            }
            $faltante = $priubi_ini_min[5] - $prima_ubicar;
        }
        if($prima_ubicar >= $priubi_ini_min[6] )
        {
            if($negocios >= 5 && $negocios < 7)
            {
                $bono_perce = 30;
            }
            if($negocios >= 7 && $negocios < 9)
            {
                $bono_perce = 35;
            }
            if($negocios >= 9)
            {
                $bono_perce = 40;
            }
            $faltante = 0;
        }
        if($faltante_flag){
            return $faltante;
        }
        else
        {
            return $bono_perce; 
        }     
    }
    public function generic_value_bono_primer_anio($bono, $prima_pago, $conservacion, $generation)
    {
        $bono_primer_anio = 0;
        if ($generation == 'Generación 1')
        {
            $bono_primer_anio = $prima_pago * ($bono/100);
        }
        else
        {
            if($conservacion >=90)
            {
                $bono_primer_anio = $prima_pago * ($bono/100);
            }  
        }
        
        return $bono_primer_anio;
    }
    public function generic_value_perce_bono_cartera($year, $bono_primer_anio,$conservacion,$prima_ubi, $faltante_flag, $year)
    { 
        $priubi_min = (array) json_decode($this->generic_value_get_pai_data($year, 'perce_bono_cartera', 'vida'));

        $faltante = 0;
        $perce_bono_cartera = 0;
        if($bono_primer_anio <> 0)
        {
            if($conservacion >= 90)
            {
                if($prima_ubi < $priubi_min[0])
                {
                    $perce_bono_cartera = 0;
                    $faltante = $priubi_min[0] - $prima_ubi;
                }
                if($prima_ubi >= $priubi_min[0] && $prima_ubi < $priubi_min[1])
                {
                    if($conservacion >= 90 && $conservacion < 93)
                    {
                        $perce_bono_cartera = 2;
                    }
                    if($conservacion >= 93 && $conservacion < 95)
                    {
                        $perce_bono_cartera = 3;
                    }
                    if($conservacion >= 95 )
                    {
                        $perce_bono_cartera = 4;
                    }
                    $faltante = $priubi_min[1] - $prima_ubi;
                }

                if($prima_ubi >= $priubi_min[1] && $prima_ubi < $priubi_min[2])
                {
                    if($conservacion >= 90 && $conservacion < 93)
                    {
                        $perce_bono_cartera = 3;
                    }
                    if($conservacion >= 93 && $conservacion < 95)
                    {
                        $perce_bono_cartera = 4;
                    }
                    if($conservacion >= 95 )
                    {
                        $perce_bono_cartera = 5;
                    }
                    $faltante = $priubi_min[2] - $prima_ubi;
                }


                if($prima_ubi >= $priubi_min[2] && $prima_ubi < $priubi_min[3])
                {
                    if($conservacion >= 90 && $conservacion < 93)
                    {
                        $perce_bono_cartera = 4;
                    }
                    if($conservacion >= 93 && $conservacion < 95)
                    {
                        $perce_bono_cartera = 5;
                    }
                    if($conservacion >= 95 )
                    {
                        $perce_bono_cartera = 6;
                    }
                    $faltante = $priubi_min[3] - $prima_ubi;
                }


                if($prima_ubi >= $priubi_min[3] && $prima_ubi < $priubi_min[4])
                {
                    if($conservacion >= 90 && $conservacion < 93)
                    {
                        $perce_bono_cartera = 5;
                    }
                    if($conservacion >= 93 && $conservacion < 95)
                    {
                        $perce_bono_cartera = 6;
                    }
                    if($conservacion >= 95 )
                    {
                        $perce_bono_cartera = 7;
                    }
                    $faltante = $priubi_min[4] - $prima_ubi;
                }


                if($prima_ubi >= $priubi_min[4] && $prima_ubi < $priubi_min[5])
                {
                    if($conservacion >= 90 && $conservacion < 93)
                    {
                        $perce_bono_cartera = 7;
                    }
                    if($conservacion >= 93 && $conservacion < 95)
                    {
                        $perce_bono_cartera = 9;
                    }
                    if($conservacion >= 95 )
                    {
                        $perce_bono_cartera = 10;
                    }
                    $faltante = $priubi_min[5] - $prima_ubi;
                }


                if($prima_ubi >= $priubi_min[5] && $prima_ubi < $priubi_min[6])
                {
                    if($conservacion >= 90 && $conservacion < 93)
                    {
                        $perce_bono_cartera = 8;
                    }
                    if($conservacion >= 93 && $conservacion < 95)
                    {
                        $perce_bono_cartera = 10;
                    }
                    if($conservacion >= 95 )
                    {
                        $perce_bono_cartera = 11;
                    }
                    $faltante = $priubi_min[6] - $prima_ubi;
                }


                if($prima_ubi >= $priubi_min[6] )
                {
                    if($conservacion >= 90 && $conservacion < 93)
                    {
                        $perce_bono_cartera = 9;
                    }
                    if($conservacion >= 93 && $conservacion < 95)
                    {
                        $perce_bono_cartera = 11;
                    }
                    if($conservacion >= 95 )
                    {
                        $perce_bono_cartera = 12;
                    }
                    $faltante = 0;
                }
            }
        }
        if($faltante_flag)
        {
            return $faltante;
        }
        else
        {
            return $perce_bono_cartera;
        }       
    }  
    public function generic_value_businees_faltantes($id_agent,$periodo)
    {
        $business = 0;
        $this->db->select('sum(neg_camp_mas_pai) as negocios');
        $this->db->from('business_');
        $this->db->where('agent_id', $id_agent);
        $this->db->where('periodo', $periodo);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) 
            {
                $business = $row->negocios;
            }
        }
        return $business;
    }
    public function generic_value_ptos_vida_agent($prima_ubi, $generation)
    {
        $life_points = 0;
        if($generation <> 'Generación 3')
        {
            if($prima_ubi >= 120000 && $prima_ubi < 240000)
            {
                $life_points = 1;
            }
            if($prima_ubi >= 240000 && $prima_ubi < 410000)
            {
                $life_points = 2;
            }
            if($prima_ubi >= 410000)
            {
                $life_points = 3;
            }
        }
        return $life_points;
    }
    public function generic_value_ptos_gmm_agent($id_agent,$periodo)
    {
        $ptos_gmm = 0;
        $this->db->select('sum(ptos) as ptos_gmm');
        $this->db->from('bona_sa');
        $this->db->where('agent_id', $id_agent);
        $this->db->where('periodo', $periodo);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) 
            {
                $ptos_gmm = $row->ptos_gmm;
            }
        }
        return $ptos_gmm;
    }
    public function generic_value_ptos_auto_agent($id_agent,$periodo)
    {
        $ptos_auto = 0;
        
        $this->db->select('sum(ptos) as ptos_gmm');
        $this->db->from('bona_au');
        $this->db->where('agent_id', $id_agent);
        $this->db->where('periodo', $periodo);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) 
            {
                $ptos_gmm = $row->ptos_gmm;
            }
        }
        
        return $ptos_auto;
    }
    public function generic_value_ptos_standing_agent($year, $num_negocios, $primas_ubi, $generation, $next)
    {

        $num_neg = array_sum($num_negocios);
        $prim_ubi = array_sum($primas_ubi);
        $puntos_standing = array('puntos_standing' => '', 'faltante_neg' => '', 'faltante_pro' => '');
        $priubi_min = (array) json_decode($this->generic_value_get_pai_data($year, 'ptos_standing', 'vida'));

        if($num_neg >=20)
        {
            $puntos_standing['faltante_neg'] = 0;
            if($generation == 'Generación 1') // Individuales
            {
                if($prim_ubi >= $priubi_min['individual'][0] && $prim_ubi < $priubi_min['individual'][1])
                {
                    $puntos_standing['puntos_standing'] = 1;
                    $puntos_standing['faltante_pro'] = $priubi_min['individual'][1] - $prim_ubi;
                }
                else
                {
                    $puntos_standing['faltante_pro'] = $priubi_min['individual'][0] - $prim_ubi;
                }
                if($prim_ubi >= $priubi_min['individual'][1] && $prim_ubi < $priubi_min['individual'][2])
                {
                    $puntos_standing['puntos_standing'] = 1.5;
                    $puntos_standing['faltante_pro'] = $priubi_min['individual'][2] - $prim_ubi;
                }
                if($prim_ubi >= $priubi_min['individual'][2] && $prim_ubi < $priubi_min['individual'][3])
                {
                    $puntos_standing['puntos_standing'] = 2;
                    $puntos_standing['faltante_pro'] = $priubi_min['individual'][3] - $prim_ubi;
                }
                if($prim_ubi >= $priubi_min['individual'][3])
                {
                    $puntos_standing['puntos_standing'] = 3;
                    $puntos_standing['faltante_pro'] = 0;
                }
            }
            else // Agrupados
            {
                if($prim_ubi >= $priubi_min['agrupados'][0] && $prim_ubi < $priubi_min['agrupados'][1])
                {
                    $puntos_standing['puntos_standing'] = 1;
                    $puntos_standing['faltante_pro'] = $priubi_min['agrupados'][1] - $prim_ubi;
                }
                else
                {
                    $puntos_standing['faltante_pro'] = $priubi_min['agrupados'][0] - $prim_ubi;
                }
                if($prim_ubi >= $priubi_min['agrupados'][1] && $prim_ubi < $priubi_min['agrupados'][2])
                {
                    $puntos_standing['puntos_standing'] = 1.5;
                    $puntos_standing['faltante_pro'] = $priubi_min['agrupados'][2] - $prim_ubi;
                }
                if($prim_ubi >= $priubi_min['agrupados'][2] && $prim_ubi < $priubi_min['agrupados'][3])
                {
                    $puntos_standing['puntos_standing'] = 2;
                    $puntos_standing['faltante_pro'] = $priubi_min['agrupados'][3] - $prim_ubi;
                }
                if($prim_ubi >= $priubi_min['agrupados'][3])
                {
                    $puntos_standing['puntos_standing'] = 3;
                    $puntos_standing['faltante_pro'] = 0;
                }
            } 
        }
        else
        {  
            $puntos_standing['puntos_standing'] = 0;
            $puntos_standing['faltante_neg'] = 20 - $num_neg;
            if($generation == 'Generación 1')
            {
                $puntos_standing['faltante_pro'] = $priubi_min['individual'][0] - $prim_ubi;
            }
            else
            {
                $puntos_standing['faltante_pro'] = $priubi_min['agrupados'][0] - $prim_ubi;
            }
            if($puntos_standing['faltante_pro'] < 0)
            {
                $puntos_standing['faltante_pro'] = 0;
            }
        }
        $puntos_standing['puntos_standing'] = number_format(abs($puntos_standing['puntos_standing']));
        $puntos_standing['faltante_neg'] = number_format(abs($puntos_standing['faltante_neg']));
        $puntos_standing['faltante_pro'] = number_format(abs($puntos_standing['faltante_pro']),2);
        return $puntos_standing;
    }
    public function get_detail_puntos_standing_next($puntos, $primas, $nextG1 = false)
    {
        $next_primas = 0;
        if($nextG1)
        {
            if($puntos == 0)
            {
                $next_primas = 900000 - $primas;
            }
            if($puntos == 1)
            {
                $next_primas = 17000000 - $primas;
            }
            if($puntos == 1.5)
            {
                $next_primas = 27000000 - $primas;
            }
            if($puntos == 2)
            {
                $next_primas = 49000000 - $primas;
            }
            if($puntos == 3)
            {
                $next_primas = 0;
            }
            
        }
        else
        {
            if($puntos == 0)
            {
                $next_primas = 400000 - $primas;
            }
            if($puntos == 1)
            {
                $next_primas = 1050000 - $primas;
            }
            if($puntos == 1.5)
            {
                $next_primas = 1800000 - $primas;
            }
            if($puntos == 2)
            {
                $next_primas = 4700000 - $primas;
            }
            if($puntos == 3)
            {
                $next_primas = 0;
            }
        }
        return $next_primas;
    }
    public function generic_value_impri_agent($id_agent,$periodo)
    {
        $total_amount = 0;
        $this->db->select('SUM(impri) as total_amount');
        $this->db->from('production_');
        $this->db->where('periodo', $periodo);
        $this->db->where('agent_id = ' . $id_agent);
        $this->db->where('apri','1');
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) 
            {
                $total_amount = $row->total_amount;
            }
        }
        return $total_amount;
    }
    public function generic_value_bono_cartera($cartera_real, $perce_bono_cartera)
    {
        if($cartera_real > 0)
        {
            return $cartera_real * ($perce_bono_cartera/100);
        }
        else
        {
            return 0;
        }
    }
    public function generic_value_ingresos_totales($comisiones_directas,$comision_cartera,$bono_primer_anio,$bono_cartera)
    {
        if($bono_primer_anio > 0)
        {
            return $comisiones_directas + $bono_primer_anio + $comision_cartera + $bono_cartera;
        }
        else
        {
            return $comisiones_directas + $comision_cartera;
        }
        return 0;
    }


    // ***  FUNCIONES GENERICAS USADAS PARA LA VISTA DE DETALLE DE GMM  ***
    public function generic_query($select, $from, $where)
    {
        $sql = "SELECT ".$select." AS total_amount FROM ".$from." WHERE ".$where."";
        $query = $this->db->query($sql);
        $res = $query->result_array();

        return $res[0]['total_amount'];
    }
    public function generic_value_gmm($type, $periodo, $agent_id)
    {
        $select = '';
        $from = '';
        $where = '';
        switch ($type) {
            case 'primas_ubicar':
                $select = 'SUM(pmaubi)';
                $from = 'production_gmm_';
                $where = 'agent_id ='.$agent_id.' AND periodo ='.$periodo;
                break;
            case 'primas_pagar':
                $select = 'SUM(pmaneta)';
                $from = 'production_gmm_';
                $where = 'agent_id ='.$agent_id.' AND periodo ='.$periodo;
                break;
            case 'nuevos_asegurados':
                $select = 'SUM(n_aseg)';
                $from = 'bona_sa';
                $where = 'agent_id ='.$agent_id.' AND periodo ='. $periodo;
                break;
            case 'nuevos_negocios':
                $select = 'SUM(n_aseg)';
                $from = 'bona_sa';
                $where = 'agent_id ='.$agent_id.' AND periodo ='.$periodo;
                break;
            case 'cartera_conservada':
                $select = 'SUM(primaafe)';
                $from = 'production_gmm_';
                $where = 'st_nueren = "RENOVADO" AND agent_id ='.$agent_id.' AND periodo ='.$periodo;
                break;
            case 'primas_netas':
                $select = 'SUM(pma_ult_12)';
                $from = 'bona_sa';
                $where = 'agent_id ='.$agent_id.' AND periodo ='.$periodo;
                break;
            case 'siniestralidad_real':
                $select = 'SUM(total)';
                $from = 'siniestralidad_';
                $where = 'agent_id ='.$agent_id.' AND periodo ='.$periodo;
                break;
            case 'siniestralidad_acot':
                $select = 'SUM(sinaco_da)';
                $from = 'siniestralidad_';
                $where = 'agent_id ='.$agent_id.' AND periodo ='.$periodo;
                break;
            case 'result_siniestralidad_real':
                $select = 'SUM(porsini_real)';
                $from = 'bona_sa';
                $where = 'agent_id ='.$agent_id.' AND periodo ='.$periodo;
                break;
            case 'result_siniestralidad_acot':
                $select = 'SUM(porsini)';
                $from = 'bona_sa';
                $where = 'agent_id ='.$agent_id.' AND periodo ='.$periodo;
                break;
            case 'numero_asegurados_nuevos':
                $select = 'SUM(n_aseg)';
                $from = 'bona_sa';
                $where = 'agent_id ='.$agent_id.' AND periodo ='.$periodo;
                break;            
            default:
                break;
        }
        return $this->generic_query($select, $from, $where);
    }
    public function find_value_in_table($type, $ramo, $year, $value_vertical, $value_horizontal, $agent_generation)
    {
        $table_values = json_decode($this->generic_query('array_data','t_pai_data', 'data_type = '.$type.' AND data_product = '.$ramo.' AND year = '.$year.';'),true);
        foreach($table_values as $generation => $array_generation)
        {
            if($agent_generation == $generation)
            {
                $values_primas_ubi = array_keys($array_generation);
                $index = 0;
                if ($value_vertical >= $values_primas_ubi[0])
                {
                    $index = $this->get_index_in_array($values_primas_ubi, $value_vertical); 
                }


                if($index != 0)
                {
                    if(is_array($array_generation[$index]))
                    {
                        $keys_array_gen = array_keys($array_generation[$index]);
                        if ($keys_array_gen[0]=='negocios')
                        {
                            $values_neg = array_keys($array_generation[$index]['negocios']);
                            $index_neg = 0;
                            if ($value_horizontal >= $values_neg[0])
                            {
                                $index = $this->get_index_in_array($values_neg, $value_horizontal); 
                            }
                            
                            if($index_neg != 0)
                            {
                                return $array_generation[$index]['negocios'][$index_neg];
                            }
                        }
                    }
                    else
                    {
                       return $array_generation[$index];
                    }
                }

            }
        }
        return 0;
    }
    public function get_index_in_array($array, $value_comparation)
    {
        $index = 0;
        foreach ($array as $key => $value) 
        {
            $next_value = $array[$key+1];
            if($next_value != null)
            {
                if($value_comparation >= $value && $value_comparation < $next_value)
                {
                    $index = $value;
                }
            }
            else
            {
                if($value_comparation >= $value)
                {
                    $index = $value;
                }
            }
        }
        return $index;
    }
    public function generic_value_percentage($value, $percentage)
    {
        return $value * ($percentage/100);
    }







    public function generic_value_primas_ubicar_gmm($year,$agent_id)
    {
        $sql = "SELECT SUM(pmaubi) AS total_amount FROM production_gmm_ WHERE agent_id =".$agent_id." AND periodo ='". $year . "';";   
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res[0]['total_amount'];
    }
    public function generic_value_primas_pagar_gmm($year,$agent_id)
    {
        $sql = "SELECT SUM(pmaneta) AS total_amount FROM production_gmm_ WHERE st_nueren = 'NUEVO' AND agent_id =".$agent_id." AND periodo ='". $year . "';";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res[0]['total_amount'];
    }
    public function generic_value_cartera_estimada_gmm($year,$agent_id)
    {
        $sql = "SELECT SUM(pmaneta) AS total_amount FROM production_gmm_ WHERE agent_id =".$agent_id." AND periodo ='". $year . "';";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res[0]['total_amount'];
    }
    public function generic_value_cartera_real_gmm($year,$agent_id)
    {
        $sql = "SELECT SUM(pmaneta) AS total_amount FROM production_gmm_ WHERE st_nueren = 'RENOVADO' AND agent_id =".$agent_id." AND periodo ='". $year . "';";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res[0]['total_amount'];
    }
    public function generic_value_asegurado_nuevo_gmm($year,$agent_id)
    {
        $sql = "SELECT SUM(n_aseg) AS total_amount FROM bona_sa WHERE agent_id =".$agent_id." AND periodo ='". $year . "';";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res[0]['total_amount'];
    }
    public function generic_value_negocio_nuevo_gmm($year,$agent_id)
    {
        $sql = "SELECT SUM(n_aseg) AS total_amount FROM bona_sa WHERE agent_id =".$agent_id." AND periodo ='". $year . "';";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res[0]['total_amount'];
    }
    public function generic_value_perce_bono_gmm($year, $prima_ubicar, $generation, $nuevos_asegurados)
    {
        $perce_bono_primer_anio = array('perce_bono' => 0,'faltante' => 0);

        $priubi_min = (array) json_decode($this->generic_value_get_pai_data($year, 'bono_1st_year_perc', 'gmm'));
        if ($year == '2019')
        {
            if ($generation != 'Consolidado')
            {
                if($prima_ubicar < $priubi_min['noveles'][0])
                {
                    $perce_bono_primer_anio['faltante'] = $priubi_min['noveles'][0] - $prima_ubicar;
                    $perce_bono_primer_anio['perce_bono'] = 0;            
                }
                if($prima_ubicar >= $priubi_min['noveles'][0] && $prima_ubicar < $priubi_min['noveles'][1])
                {
                    $perce_bono_primer_anio['faltante'] = $priubi_min['noveles'][1] - $prima_ubicar;
                    if($nuevos_asegurados >= 5 && $nuevos_asegurados<8)
                    {
                        $perce_bono_primer_anio['perce_bono'] = 5;
                    }
                    if($nuevos_asegurados >= 8)
                    {
                        $perce_bono_primer_anio['perce_bono'] = 6;
                    }
                    
                }
                if($prima_ubicar >= $priubi_min['noveles'][1] && $prima_ubicar < $priubi_min['noveles'][2])
                {
                    $perce_bono_primer_anio['faltante'] = $priubi_min['noveles'][2] - $prima_ubicar;
                    if($nuevos_asegurados >= 5 && $nuevos_asegurados<8)
                    {
                        $perce_bono_primer_anio['perce_bono'] = 6.5;
                    }
                    if($nuevos_asegurados >= 8)
                    {
                        $perce_bono_primer_anio['perce_bono'] = 7.5;
                    }
                }
                if($prima_ubicar >= $priubi_min['noveles'][2] && $prima_ubicar < $priubi_min['noveles'][3])
                {
                    $perce_bono_primer_anio['faltante'] = $priubi_min['noveles'][3] - $prima_ubicar;
                    if($nuevos_asegurados >= 5 && $nuevos_asegurados<8)
                    {
                        $perce_bono_primer_anio['perce_bono'] = 8;
                    }
                    if($nuevos_asegurados >= 8)
                    {
                        $perce_bono_primer_anio['perce_bono'] = 9.5;
                    }
                }
                if($prima_ubicar >= $priubi_min['noveles'][3] && $prima_ubicar < $priubi_min['noveles'][4])
                {
                    $perce_bono_primer_anio['faltante'] = $priubi_min['noveles'][4] - $prima_ubicar;
                    if($nuevos_asegurados >= 5 && $nuevos_asegurados<8)
                    {
                        $perce_bono_primer_anio['perce_bono'] = 10;
                    }
                    if($nuevos_asegurados >= 8)
                    {
                        $perce_bono_primer_anio['perce_bono'] = 12;
                    }
                }
                if($prima_ubicar >= $priubi_min['noveles'][4])
                {
                    $perce_bono_primer_anio['faltante'] = 0;
                    if($nuevos_asegurados >= 5 && $nuevos_asegurados<8)
                    {
                        $perce_bono_primer_anio['perce_bono'] = 12.5;
                    }
                    if($nuevos_asegurados >= 8)
                    {
                        $perce_bono_primer_anio['perce_bono'] = 15;
                    }
                } 
            }
            else
            {
                if($prima_ubicar < $priubi_min['consolidados'][0])
                {
                    $perce_bono_primer_anio['faltante'] = $priubi_min['consolidados'][0] - $prima_ubicar;
                    $perce_bono_primer_anio['perce_bono'] = 0;            
                }
                if($prima_ubicar >= $priubi_min['consolidados'][0] && $prima_ubicar < $priubi_min['consolidados'][1])
                {
                    $perce_bono_primer_anio['faltante'] = $priubi_min['consolidados'][1] - $prima_ubicar;
                    $perce_bono_primer_anio['perce_bono'] = 5;
                    
                }
                if($prima_ubicar >= $priubi_min['consolidados'][1] && $prima_ubicar < $priubi_min['consolidados'][2])
                {
                    $perce_bono_primer_anio['faltante'] = $priubi_min['consolidados'][2] - $prima_ubicar;
                    $perce_bono_primer_anio['perce_bono'] = 7.5;
                }
                if($prima_ubicar >= $priubi_min['consolidados'][2] && $prima_ubicar < $priubi_min['consolidados'][3])
                {
                    $perce_bono_primer_anio['faltante'] = $priubi_min['consolidados'][3] - $prima_ubicar;
                    $perce_bono_primer_anio['perce_bono'] = 10;
                }
                if($prima_ubicar >= $priubi_min['consolidados'][3] && $prima_ubicar < $priubi_min['consolidados'][4])
                {
                    $perce_bono_primer_anio['faltante'] = $priubi_min['consolidados'][4] - $prima_ubicar;
                    $perce_bono_primer_anio['perce_bono'] = 12.5;
                }
                if($prima_ubicar >= $priubi_min['consolidados'][4])
                {
                    $perce_bono_primer_anio['faltante'] = 0;
                    $perce_bono_primer_anio['perce_bono'] = 15;
                } 
            }
        }
        else
        {
            if($prima_ubicar < $priubi_min[0])
            {
                $perce_bono_primer_anio['faltante'] = $priubi_min[0] - $prima_ubicar;
                $perce_bono_primer_anio['perce_bono'] = 0;            
            }
            if($prima_ubicar >= $priubi_min[0] && $prima_ubicar < $priubi_min[1])
            {
                $perce_bono_primer_anio['faltante'] = $priubi_min[1] - $prima_ubicar;
                if($nuevos_asegurados >= 5 && $nuevos_asegurados<8)
                {
                    $perce_bono_primer_anio['perce_bono'] = 5;
                }
                if($nuevos_asegurados >= 8)
                {
                    $perce_bono_primer_anio['perce_bono'] = 6;
                }
                
            }
            if($prima_ubicar >= $priubi_min[1] && $prima_ubicar < $priubi_min[2])
            {
                $perce_bono_primer_anio['faltante'] = $priubi_min[2] - $prima_ubicar;
                if($nuevos_asegurados >= 5 && $nuevos_asegurados<8)
                {
                    $perce_bono_primer_anio['perce_bono'] = 6.5;
                }
                if($nuevos_asegurados >= 8)
                {
                    $perce_bono_primer_anio['perce_bono'] = 7.5;
                }
            }
            if($prima_ubicar >= $priubi_min[2] && $prima_ubicar < $priubi_min[3])
            {
                $perce_bono_primer_anio['faltante'] = $priubi_min[3] - $prima_ubicar;
                if($nuevos_asegurados >= 5 && $nuevos_asegurados<8)
                {
                    $perce_bono_primer_anio['perce_bono'] = 8;
                }
                if($nuevos_asegurados >= 8)
                {
                    $perce_bono_primer_anio['perce_bono'] = 9.5;
                }
            }
            if($prima_ubicar >= $priubi_min[3] && $prima_ubicar < $priubi_min[4])
            {
                $perce_bono_primer_anio['faltante'] = $priubi_min[4] - $prima_ubicar;
                if($nuevos_asegurados >= 5 && $nuevos_asegurados<8)
                {
                    $perce_bono_primer_anio['perce_bono'] = 10;
                }
                if($nuevos_asegurados >= 8)
                {
                    $perce_bono_primer_anio['perce_bono'] = 12;
                }
            }
            if($prima_ubicar >= $priubi_min[4])
            {
                $perce_bono_primer_anio['faltante'] = 0;
                if($nuevos_asegurados >= 5 && $nuevos_asegurados<8)
                {
                    $perce_bono_primer_anio['perce_bono'] = 12.5;
                }
                if($nuevos_asegurados >= 8)
                {
                    $perce_bono_primer_anio['perce_bono'] = 15;
                }
            } 
        }

     
        
        return $perce_bono_primer_anio;
    }
    public function generic_value_bono_gmm($cartera_conservada, $generation, $perce_bono, $primas_pagar)
    {
        $bono_primer_anio = 0;
        if($generation=='Generación 1')
        {
            $bono_primer_anio = $primas_pagar * ($perce_bono/100);
        }
        else
        {

            $bono_primer_anio = $primas_pagar * ($perce_bono/100);

        }
        return $bono_primer_anio;
    }
    public function generic_value_cartera_conservada_gmm($agent_id, $year)
    {
        $sql = "SELECT SUM(primaafe) AS total_amount FROM production_gmm_ WHERE st_nueren = 'RENOVADO' AND agent_id =".$agent_id." AND periodo ='". $year . "';";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res[0]['total_amount'];
    }
    public function generic_value_primas_netas_gmm($agent_id, $year)
    {
        $sql = "SELECT SUM(pma_ult_12) AS total_amount FROM bona_sa WHERE agent_id =".$agent_id." AND periodo ='". $year . "';";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res[0]['total_amount'];
    }
    public function generic_value_siniestralidad_real_gmm($agent_id, $year)
    {
        $sql = "SELECT SUM(total) AS total_amount FROM siniestralidad_ WHERE agent_id =".$agent_id." AND periodo ='". $year . "';";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res[0]['total_amount'];
    }
    public function generic_value_siniestralidad_acot_gmm($agent_id, $year)
    {
        $sql = "SELECT SUM(sinaco_da) AS total_amount FROM siniestralidad_ WHERE agent_id =".$agent_id." AND periodo ='". $year . "';";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res[0]['total_amount'];
    }
    public function generic_value_result_siniestralidad_real_gmm($agent_id, $year)
    {
        $sql = "SELECT SUM(porsini_real) AS total_amount FROM bona_sa WHERE agent_id =".$agent_id." AND periodo ='". $year . "';";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res[0]['total_amount'];
    }
    public function generic_value_result_siniestralidad_acot_gmm($agent_id, $year)
    {
        $sql = "SELECT SUM(porsini) AS total_amount FROM bona_sa WHERE agent_id =".$agent_id." AND periodo ='". $year . "';";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res[0]['total_amount'];
    }
    public function generic_value_numero_asegurados_nuevos_gmm($agent_id, $year)
    {
        $sql = "SELECT SUM(n_aseg) AS total_amount FROM bona_sa WHERE agent_id =".$agent_id." AND periodo ='". $year . "';";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res[0]['total_amount'];
    }


    public function generic_value_perc_bono_rentabilidad_gmm($prima_ubicar, $perc_bono_1st_year, $res_sin_real, $res_sin_acot, $agent_id, $year){
        $limits = (array) json_decode($this->generic_value_get_pai_data($year, 'bono_rentabilidad', 'gmm'));
        $faltante = $limits[1] - $prima_ubicar;

        if ($perc_bono_1st_year == 0){
            $bono_rent = 0;
        } else if ($res_sin_real >= 0.66 && $res_sin_real < 0.8) {
            if ($prima_ubicar >= $limits[0] && $prima_ubicar < $limits[1]){
                $bono_rent = 0;
                $faltante = $limits[1] - $prima_ubicar;
            } else if ($prima_ubicar >= $limits[1] && $prima_ubicar < $limits[2]){
                $bono_rent = 0.005;
                $faltante = $limits[2] - $prima_ubicar;
            } else if ($prima_ubicar >= $limits[2] && $prima_ubicar < $limits[3]){
                $bono_rent = 0.01;
                $faltante = $limits[3] - $prima_ubicar;
            } else if ($prima_ubicar >= $limits[3] && $prima_ubicar < $limits[4]){
                $bono_rent = 0.015;
                $faltante = $limits[4] - $prima_ubicar;
            } else if ($prima_ubicar >= $limits[4] && $prima_ubicar < $limits[5]){
                $bono_rent = 0.02;
                $faltante = $limits[5] - $prima_ubicar;
            } else if ($prima_ubicar >= $limits[5]){
                $bono_rent = 0.03;
            }
        } else if ($res_sin_acot >= 0.66) {
            $bono_rent = 0;
        } else {
            if ($res_sin_acot < 0.58) {
                if ($prima_ubicar >= $limits[0] && $prima_ubicar < $limits[1]){
                    $bono_rent = 0;
                    $faltante = $limits[1] - $prima_ubicar;
                } else if ($prima_ubicar >= $limits[1] && $prima_ubicar < $limits[2]){
                    $bono_rent = 0.02;
                    $faltante = $limits[2] - $prima_ubicar;
                } else if ($prima_ubicar >= $limits[2] && $prima_ubicar < $limits[3]){
                    $bono_rent = 0.03;
                    $faltante = $limits[3] - $prima_ubicar;
                } else if ($prima_ubicar >= $limits[3] && $prima_ubicar < $limits[4]){
                    $bono_rent = 0.045;
                    $faltante = $limits[4] - $prima_ubicar;
                } else if ($prima_ubicar >= $limits[4] && $prima_ubicar < $limits[5]){
                    $bono_rent = 0.06;
                    $faltante = $limits[5] - $prima_ubicar;
                } else if ($prima_ubicar >= $limits[5]){
                    $bono_rent = 0.08;
                }
            } else if ($res_sin_acot < 0.62) {
                if ($prima_ubicar >= $limits[0] && $prima_ubicar < $limits[1]){
                    $bono_rent = 0;
                    $faltante = $limits[1] - $prima_ubicar;
                } else if ($prima_ubicar >= $limits[1] && $prima_ubicar < $limits[2]){
                    $bono_rent = 0.01;
                    $faltante = $limits[2] - $prima_ubicar;
                } else if ($prima_ubicar >= $limits[2] && $prima_ubicar < $limits[3]){
                    $bono_rent = 0.02;
                    $faltante = $limits[3] - $prima_ubicar;
                } else if ($prima_ubicar >= $limits[3] && $prima_ubicar < $limits[4]){
                    $bono_rent = 0.03;
                    $faltante = $limits[4] - $prima_ubicar;
                } else if ($prima_ubicar >= $limits[4] && $prima_ubicar < $limits[5]){
                    $bono_rent = 0.05;
                    $faltante = $limits[5] - $prima_ubicar;
                } else if ($prima_ubicar >= $limits[5]){
                    $bono_rent = 0.05;
                }
            } else {
                if ($prima_ubicar >= $limits[0] && $prima_ubicar < $limits[1]){
                    $bono_rent = 0;
                    $faltante = $limits[1] - $prima_ubicar;
                } else if ($prima_ubicar >= $limits[1] && $prima_ubicar < $limits[2]){
                    $bono_rent = 0.005;
                    $faltante = $limits[2] - $prima_ubicar;
                } else if ($prima_ubicar >= $limits[2] && $prima_ubicar < $limits[3]){
                    $bono_rent = 0.01;
                    $faltante = $limits[3] - $prima_ubicar;
                } else if ($prima_ubicar >= $limits[3] && $prima_ubicar < $limits[4]){
                    $bono_rent = 0.015;
                    $faltante = $limits[4] - $prima_ubicar;
                } else if ($prima_ubicar >= $limits[4] && $prima_ubicar < $limits[5]){
                    $bono_rent = 0.02;
                    $faltante = $limits[5] - $prima_ubicar;
                } else if ($prima_ubicar >= $limits[5]){
                    $bono_rent = 0.03;
                } 
            }
        }
        
        return array($bono_rent,$faltante);

    }

    public function generic_value_detail_bono_rentabilidad_gmm($perc_bono_renta, $cartera_conservada){
        return $cartera_conservada * $perc_bono_renta;
    }

    public function generic_value_suma_ingresos_gmm($bono_1st_year, $comisiones_directas, $comision_cartera, $bono_rentabilidad){
        $res = 0;
        if ($bono_1st_year == 0){
            $res = $comisiones_directas + $comision_cartera;
        } else {
            $res = $comisiones_directas + $bono_1st_year + $comision_cartera + $bono_rentabilidad;
        }
        
        return $res;
    }

    public function generic_value_congreso_gmm($total_priubi, $year){
        $res = '';
        $table_values = (array) json_decode($this->generic_query('array_data','t_pai_data', 'data_type="congreso" AND data_product="gmm" AND year='.$year.';'),true);

        if ($total_priubi >=0 && $total_priubi < $table_values['Generación 1']['Oro']){
            $res = 'No';
        } else if ($total_priubi >= $table_values['Generación 1']['Oro'] && $total_priubi < $table_values['Generación 1']['Platino']){
            $res = 'Oro';
        } else if ($total_priubi >= $table_values['Generación 1']['Platino'] && $total_priubi < $table_values['Generación 1']['Diamante']){
            $res = 'Platino';
        } else if ($total_priubi >= $table_values['Generación 1']['Diamante'] && $total_priubi < $table_values['Generación 1']['Consejo']){
            $res = 'Diamante';
        } else if ($total_priubi >= $table_values['Generación 1']['Consejo']){
            $res = 'Consejo';
        }
        return $res;
    }

    public function generic_value_siguente_congreso_gmm($congreso){
        $next = array(
            'No' => 'Oro',
            'Oro' => 'Platino',
            'Platino' => 'Diamante',
            'Diamante' => 'Consejo',
            'Consejo' => 'N/A',
        );
        if($congreso != null)
        {
            return $next[$congreso];
        }
        else
        {
            return "N/A";   
        }
        
    }

    public function generic_value_faltante_produccion_gmm($sig_congreso, $prima_ubicar, $year){
        $table_values = (array) json_decode($this->generic_query('array_data','t_pai_data', 'data_type = "congreso" AND data_product = "gmm" AND year = '.$year.';'),true);
        if ($sig_congreso != 'N/A'){
            $res = $table_values['Generación 1'][$sig_congreso] - $prima_ubicar;
            return number_format($res,2);
        } else {
            return 0;
        }
    }

    public function generic_value_es_productivo_gmm($generacion, $total_priubi, $year){
        $res = '';
        $faltante = 0;
        $minimum = (array) json_decode($this->generic_query('array_data','t_pai_data', 'data_type = "agente_productivo" AND data_product = "gmm" AND year = '.$year.';'),true);
        if ($generacion == 'Generación 1' || $generacion == 'Generación 2'){
            if ($total_priubi >= $minimum[0]){
                $res = "Si";
            } else {
                $res = "No";
                $faltante = $minimum[0] - $total_priubi;
            }
        } else if ($generacion == 'Generación 3'){
            if ($total_priubi >= $minimum[1]){
                $res = "Si";
            } else {
                $res = "No";
                $faltante = $minimum[1] - $total_priubi;
            }
        } else if ($generacion == 'Generación 4' || $generacion == 'Consolidado'){
            if ($total_priubi >= $minimum[2]){
                $res = "Si";
            } else {
                $res = "No";
                $faltante = $minimum[2] - $total_priubi;
            }
        } else if ($generacion == 'Agrupado'){
            if ($total_priubi >= $minimum[3]){
                $res = "Si";
            } else {
                $res = "No";
                $faltante = $minimum[3] - $total_priubi;
            }
        } else {
            $res = "No";
        }
    
        return array($res,number_format($faltante));
    }

    public function generic_value_get_pai_data($year, $data_type, $product){
        $sql = "SELECT * FROM t_pai_data WHERE data_type = '" . $data_type . "' AND data_product = '" . $product . "' AND year = " . $year . ";";
        $query = $this->db->query($sql);
        $row = $query->row();
        if ($query->num_rows() > 0){
            $res = $row->array_data;
        }
        else {
            //echo "no!";
            $query->free_result();

            $sql_failsafe = "select year from t_pai_data WHERE data_type = '" . $data_type . "' AND data_product = '" . $product . "' order by year desc limit 1;";
            $query_failsafe = $this->db->query($sql_failsafe);
            //echo $this->db->last_query();
            $row_failsafe = $query_failsafe->row();
            $year_failsafe = $row_failsafe->year;
            $query->free_result();

            $sql = "SELECT * FROM t_pai_data WHERE data_type = '" . $data_type . "' AND data_product = '" . $product . "' AND year = " . $year_failsafe . ";";
            $query = $this->db->query($sql);
            //echo $this->db->last_query();
            $row = $query->row();

            $res = $row->array_data;
        }
        return $res;
    }
    public function generic_value_cartera_pronosticada($agent_id, $year)
    {
        $sql= "SELECT SUM(impri*(po_pag1_da/100)) as total_amount from production_ where agent_id = ".$agent_id." and periodo='".$year."';";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        
        return $res[0]['total_amount'];
    }
}
/* End of file termometro_model.php */
/* Location: ./application/modules/termometro/models/termometro_model.php */
?>